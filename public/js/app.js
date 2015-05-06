var appurl = document.getElementById('_url').value,
	_token 	= document.getElementById('_token').value,

	app = angular.module('iApp', ['ngRoute','ngMessages']), 
	typingTimer;

	app.config(['$interpolateProvider','$httpProvider',function($interpolateProvider,$httpProvider)
	{
		$httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
		$interpolateProvider.startSymbol('[[');
		$interpolateProvider.endSymbol(']]');
	}]);

	app.directive('ensureUnique', function($http, $timeout){
		return {
		require: 'ngModel',
			link: function(scope, ele, attrs, c) {
			scope.$watch(attrs.ngModel, function(n){
				if (!n) return;
				ele.on('keyup',function(){
					$timeout.cancel(typingTimer);
				    typingTimer = $timeout(function(){
					    $http({
							method: 'POST',
							url: appurl+'/invoice/check',
							data: {"field": n, "_token" : _token}
						}).success(function(data){
							c.$setValidity('unique', data.isUnique);
						}).error(function(data) {
								c.$setValidity('unique', false);
							});
				    },1500);
				});
				ele.on('keydown',function(){
					$timeout.cancel(typingTimer);
				});
				});
			}
		}
	});
	app.directive('isUnique', function($http, $timeout){
		return {
		require: 'ngModel',
			link: function(scope, ele, attrs, c){
			scope.$watch(attrs.ngModel, function(n) {
				if (!n) return;
				ele.on('keyup',function(){
					$timeout.cancel(typingTimer);
					typingTimer = $timeout(function(){
						$http({
						method: 'POST',
						url: appurl+'/receipt/check',
						data: {"field": n, "_token" : _token}
					}).success(function(data){
						c.$setValidity('isunique', data.isUnique);
					}).error(function(data){
							c.$setValidity('isunique', false);
					});
					}, 1500);
				});

				ele.on('keydown',function(){
					$timeout.cancel(typingTimer);
				});
				});
			}
		}
	});

	app.directive('ngFocus', [function() {
		var FOCUS_CLASS = "ng-focused";
		return {
			restrict: 'A',
			require: 'ngModel',
			link: function(scope, element, attrs, ctrl) {
				ctrl.$focused = false;
				element.bind('focus', function(evt) {
					element.addClass(FOCUS_CLASS);
					scope.$apply(function() {
						ctrl.$focused = true;
					});
				}).bind('blur', function(evt){
				element.removeClass(FOCUS_CLASS);
					scope.$apply(function() {
						ctrl.$focused = false;
					});
				});
			}
		}
	}]);
      
	app.directive('ngDatepicker', function ($parse){
	    return function (scope, element, attrs, controller) {
	        var ngModel = $parse(attrs.ngModel);
	        element.bind('changeDate',function(){
	        	$(this).datepicker('hide');
	        	  var dateText = $(this).prop('value');
        	    scope.$apply(function(scope){
                    ngModel.assign(scope, dateText);
                });
	        });
        	element.datepicker({format:'yyyy-mm-dd'});
	    }
	});


	app.controller('WorkInvoiceController',function($scope, $http, $timeout, $element){
		$scope.workInvoiceButton = "Create Invoice ...";
		$scope.workInvoiceButtonStatus = true;
		$scope.databaseError = false;
		$scope.dataSubmitted = false;
		$scope.manualCode = false;
		$scope.errors = [{}];
		$scope.hasErrors = false;

		$scope.suggestList = false;
		$scope.customer = {};
		$scope.organization = {};
		$scope.customers = [{}];
		$scope.searchCustomer = function(){
			var searchText = $scope.customer.name;
			if(searchText){
				$timeout.cancel(typingTimer);
				typingTimer = $timeout(function(){
					
					if(!searchText){
						$('.searched-result').fadeOut();
					}else{
						$http({
								method: 'POST',
								url: appurl+'/config/searchcustomer',
								data:{'searchText': searchText}
						}).success(function(response){
							if(response == ''){
								$('.searched-result').fadeOut();
							}
							if(response != ''){
								$scope.customers = response;
								$scope.suggestList = true;
								$('.searched-result').fadeIn();
							}
						});
					}
					
				},300);	
			}
			$element.on('focusout', function(){
				$('.searched-result').fadeOut();
			});
		}

		$scope.selectAddress = function(index){
			var name = $scope.customers[index].name,
				address = $scope.customers[index].long_address;
			$scope.customer.name = name;
			$scope.customer.address = address;
		}

		$scope.choices = [{"unit":"hour"}];
		$scope.addNewChoice = function() {  
			$scope.choices.push({"unit":"hour"});
		};

		$scope.removeInput = function(index){
			if(index != 0){
				$scope.choices.splice(index,1);	
			}	    
		}
		
		
		$http.get(appurl+'/config/init').success(function(data){
         	$scope.organization = data;
        });

		$scope.doFocus = function(){
			$scope.manualCode = true;
			if($scope.manualCode){
				$timeout(function(){
					$('.unique-number').focus();
					if($scope.organization.isManualCode == $scope.organization.invoiceNumber){
						$scope.organization.isManual = 0;
					}else{
						$scope.organization.isManual = 1;
					}
				});
			}
		}

		$scope.checkState = function(){
			if($scope.organization.isManualCode == $scope.organization.invoiceNumber){
				$scope.organization.isManual = 0;
			}else{
				$scope.organization.isManual = 1;
			}
		}
		
		$scope.workInvoiceProcess = function(){
			if($scope.workInvoiceForm.$invalid){
				$scope.dataSubmitted = true;
			}

			if($scope.workInvoiceForm.$valid){
				$scope.workInvoiceButton = "Creating Invoice ...";
				$scope.workInvoiceButtonStatus = false;	
				$http({
			        method  : 'POST',
			        url     : appurl+'/invoice/work',
			        data    : {'organization' : $scope.organization,
			        		   'customer': $scope.customer,
			        		   'descs': $scope.choices,
			        			'_token' : _token,
			        			'requestType': 'default',
			        			'currentState': 'work'
			        		},
			    })
				.success(function(data){
					if(data.statusCode == 200 && data.response == true){
		            	window.location.replace(appurl+'/invoice/view?response='+data.invoiceId+'&secure='+data.invoiceTpye+'&status='+data.statusCode);
		            }else if(data.statusCode == 503 && data.response == false){
		            	$scope.databaseError = true;
		            	$scope.workInvoiceButtonStatus = true;
		            	$scope.workInvoiceButton = "Create Invoice ...";
		            }else{
		            	$scope.hasErrors = true;
		            	$scope.errors = data;
		            	$timeout(function(){
							 $("html, body").animate({ scrollTop: 0 }, 600);
		            	});
		            }
		        });
			}
		}
	});	

	app.controller('LoginFormController',function($scope){
		$scope.buttonText = 'Sign In';
		$scope.checkLogin = function(){
			$scope.buttonText = 'Signing In ...';
		}
	});

	app.controller('ServiceInvoiceController',function($scope, $http, $timeout, $element){
		$scope.serviceInvoiceButton = "Create Invoice ...";
		$scope.serviceInvoiceButtonStatus = true;
		$scope.databaseError = false;
		$scope.manualCode = false;
		$scope.errors = [{}];
		$scope.hasErrors = false;

		$scope.suggestList = false;
		$scope.customer = {};
		$scope.organization = {};
		$scope.customers = [{}];
		$scope.searchCustomer = function(){
			$timeout.cancel(typingTimer);
			typingTimer = $timeout(function(){
				var searchText = $scope.customer.name;
				if(!searchText){
					$('.searched-result').fadeOut();
				}else{
					$http({
							method: 'POST',
							url: appurl+'/config/searchcustomer',
							data:{'searchText': searchText}
					}).success(function(response){
						if(response == ''){
							$('.searched-result').fadeOut();
						}
						if(response != ''){
							$scope.customers = response;
							$scope.suggestList = true;
							$('.searched-result').fadeIn();
						}
					});
				}
				
			},300);	
			$element.on('focusout', function(){
				$('.searched-result').fadeOut();
			});
		}

		$scope.selectAddress = function(index){
			var name = $scope.customers[index].name,
				address = $scope.customers[index].long_address;
			$scope.customer.name = name;
			$scope.customer.address = address;
		}

		$scope.choices = [{}];
		$scope.addNewChoice = function() {  
			$scope.choices.push({});
		};

		$scope.removeInput = function(index){
			if(index != 0){
				$scope.choices.splice(index,1);	
			}	    
		}

		$http.get(appurl+'/config/init').success(function(data){
         	$scope.organization = data;
        });

		$scope.doFocus = function(){
			$scope.manualCode = true;
			if($scope.manualCode){
				$timeout(function(){
					$('.is-unique-invoice').focus();
					if($scope.organization.isManualCode == $scope.organization.invoiceNumber){
						$scope.organization.isManual = 0;
					}else{
						$scope.organization.isManual = 1;
					}
				});
			}
		}

		$scope.checkState = function(){
			if($scope.organization.isManualCode == $scope.organization.invoiceNumber){
				$scope.organization.isManual = 0;
			}else{
				$scope.organization.isManual = 1;
			}
		}

		$scope.serviceInvoiceProcess = function(){	
			if($scope.serviceInvoiceForm.$valid){
				$scope.serviceInvoiceButton = "Creating Invoice ...";
				$scope.serviceInvoiceButtonStatus = false;

				$http({
			        method  : 'POST',
			        url     : appurl+'/invoice/service',
			        data    : {'organization' : $scope.organization,
			        		   'customer': $scope.customer,
			        		   'descs': $scope.choices,
			        			'_token' : _token,
			        			'requestType': 'default',
			        			'currentState': 'service'
			        		},
			    })
				.success(function(data){
		            if(data.statusCode == 200 && data.response == true){
		            	window.location.href = appurl+'/invoice/view?response='+data.invoiceId+'&secure='+data.invoiceTpye+'&status='+data.statusCode;
		            }else if(data.statusCode == 503 && data.response == false){
		            	$scope.databaseError = true;
		            	$scope.serviceInvoiceButtonStatus = true;
		            	$scope.serviceInvoiceButton = "Create Invoice ...";
		            }else{
		            	$scope.hasErrors = true;
		            	$scope.errors = data;
		            	$timeout(function(){
							 $("html, body").animate({ scrollTop: 0 }, 600);
		            	});
		            }
		        });	
			}
		}
	});

	app.controller('WorkReceiptController',function($scope, $http, $timeout, $element){
		$scope.workReceiptButton = "Create Receipt ...";
		$scope.workReceiptButtonStatus = true;
		$scope.databaseError = false;
		$scope.manualCode = false;
		$scope.errors = [{}];
		$scope.hasErrors = false;

		$scope.suggestList = false;
		$scope.customer = {};
		$scope.organization = {};
		$scope.customers = [{}];
		$scope.searchCustomer = function(){
			$timeout.cancel(typingTimer);
			typingTimer = $timeout(function(){
				var searchText = $scope.customer.name;
				if(!searchText){
					$('.searched-result').fadeOut();
				}else{
					$http({
							method: 'POST',
							url: appurl+'/config/searchcustomer',
							data:{'searchText': searchText}
					}).success(function(response){
						if(response == ''){
							$('.searched-result').fadeOut();
						}
						if(response != ''){
							$scope.customers = response;
							$scope.suggestList = true;
							$('.searched-result').fadeIn();
						}
					});
				}
				
			},300);	
			$element.on('focusout', function(){
				$('.searched-result').fadeOut();
			});
		}

		$scope.selectAddress = function(index){
			var name = $scope.customers[index].name,
				address = $scope.customers[index].long_address;
			$scope.customer.name = name;
			$scope.customer.address = address;
		}

		$scope.choices = [{"unit":"hour"}];
		$scope.addNewChoice = function() {  
			$scope.choices.push({"unit":"hour"});
		};

		$scope.removeInput = function(index){
			if(index != 0){
				$scope.choices.splice(index,1);	
			}	    
		}
		
		$http.get(appurl+'/config/initr').success(function(data){
         	$scope.organization = data;
        });

		$scope.doFocus = function(){
			$scope.manualCode = true;
			if($scope.manualCode){
				$timeout(function(){
					$('.is-unique-receipt').focus();
					if($scope.organization.isManualCode == $scope.organization.receiptNumber){
						$scope.organization.isManual = 0;
					}else{
						$scope.organization.isManual = 1;
					}
				});
			}
		}

		$scope.checkState = function(){
			if($scope.organization.isManualCode == $scope.organization.receiptNumber){
				$scope.organization.isManual = 0;
			}else{
				$scope.organization.isManual = 1;
			}
		}

		$scope.workReceiptProcess = function(){	
			if($scope.workReceiptForm.$valid){
				$scope.workReceiptButton = "Creating Receipt ...";
				$scope.workReceiptButtonStatus = false;
				var bulkData = {'organization' : $scope.organization,'customer': $scope.customer,
								'descs': $scope.choices, '_token' : _token, 'requestType': 'default', 'currentState': 'work'};
				$http.post(appurl+'/receipt/work' ,bulkData)
				.success(function(data){					
		            if(data.statusCode == 200 && data.response == true){
		            	window.location.href = appurl+'/receipt/view?response='+data.receiptId+'&secure='+data.receiptTpye+'&status='+data.statusCode;
		            }else if(data.statusCode == 503 && data.response == false){
		            	$scope.databaseError = true;
		            	$scope.workReceiptButtonStatus = true;
		            	$scope.workReceiptButton = "Create Receipt ...";
		            }else{
		            	$scope.hasErrors = true;
		            	$scope.errors = data;
		            	$timeout(function(){
							 $("html, body").animate({ scrollTop: 0 }, 600);
		            	});
		            }
		        });	
			}
		}
	});	

	app.controller('ServiceReceiptController',function($scope, $http, $timeout, $element){		
		$scope.serviceReceiptButton = "Create Receipt ...";
		$scope.serviceReceiptButtonStatus = true;
		$scope.databaseError = false;
		$scope.manualCode = false;
		$scope.errors = [{}];
		$scope.hasErrors = false;

		$scope.suggestList = false;
		$scope.customer = {};
		$scope.organization = {};
		$scope.customers = [{}];
		$scope.searchCustomer = function(){
			$timeout.cancel(typingTimer);
			typingTimer = $timeout(function(){
				var searchText = $scope.customer.name;
				if(!searchText){
					$('.searched-result').fadeOut();
				}else{
					$http({
							method: 'POST',
							url: appurl+'/config/searchcustomer',
							data:{'searchText': searchText}
					}).success(function(response){
						if(response == ''){
							$('.searched-result').fadeOut();
						}
						if(response != ''){
							$scope.customers = response;
							$scope.suggestList = true;
							$('.searched-result').fadeIn();
						}
					});
				}
				
			},300);
			$element.on('focusout', function(){
				$('.searched-result').fadeOut();
			});
		}

		$scope.selectAddress = function(index){
			var name = $scope.customers[index].name,
				address = $scope.customers[index].long_address;
			$scope.customer.name = name;
			$scope.customer.address = address;
		}

		$scope.choices = [{}];
		$scope.addNewChoice = function() {  
			$scope.choices.push({});
		};

		$scope.removeInput = function(index){
			if(index != 0){
				$scope.choices.splice(index,1);	
			}	    
		}	

		$http.get(appurl+'/config/initr').success(function(data){
         	$scope.organization = data;
        });

		$scope.doFocus = function(){
			$scope.manualCode = true;
			if($scope.manualCode){
				$timeout(function(){
					$('.is-unique-receipt').focus();
					if($scope.organization.isManualCode == $scope.organization.receiptNumber){
						$scope.organization.isManual = 0;
					}else{
						$scope.organization.isManual = 1;
					}
				});
			}
		}

		$scope.checkState = function(){
			if($scope.organization.isManualCode == $scope.organization.receiptNumber){
				$scope.organization.isManual = 0;
			}else{
				$scope.organization.isManual = 1;
			}
		}

		$scope.serviceReceiptProcess = function(){
			if($scope.serviceReceiptForm.$valid){
				$scope.serviceReceiptButton = "Creating Receipt ...";
				$scope.serviceReceiptButtonStatus = false;
				$http({
			        method  : 'POST',
			        url     : appurl+'/receipt/service',
			        data    : {'organization' : $scope.organization,
			        		   'customer': $scope.customer,
			        		   'descs': $scope.choices,
			        			'_token' : _token,
			        			'requestType': 'default',
			        			'currentState': 'service'
			        		},
			    })
				.success(function(data){
		            if(data.statusCode == 200 && data.response == true){
		            	window.location.href = appurl+'/receipt/view?response='+data.receiptId+'&secure='+data.receiptTpye+'&status='+data.statusCode;
		            }else if(data.statusCode == 503 && data.response == false){
		            	$scope.databaseError = true;
		            	$scope.serviceReceiptButtonStatus = true;
		            	$scope.serviceReceiptButton = "Create Receipt ...";
		            }else{
		            	$scope.hasErrors = true;
		            	$scope.errors = data;
		            	$timeout(function(){
							 $("html, body").animate({ scrollTop: 0 }, 600);
		            	});
		            }
		        });	
			}
		}
	});

	app.controller('ConfigController',function($scope, $http){	
		$scope.configButton = 'Update Information ...';
		$scope.doLoad = function(){
			$scope.configButton = 'Updating Information ...';
		}
		$scope.company = {};
		$http.get(appurl+'/config/inito').success(function(data){
           $scope.company =  data;
        });		
	});

	app.directive('ngPayInvoice', function($http, $timeout) {
	    return {
	        link: function (scope, element, attrs) {
	            element.bind('click', function (){
	              var  Id = element.data('id');
	                $http({
	                		method  : 'POST',
					        url     : appurl+'/invoice/status',
					        data    : {id : Id, _token : _token}, 
					}).success(function(data)
				    {
            			if(data.statusCode == 200){
            				element.html('Wait ..').css('background-color','#45B4D7').css('color','#fff');
            				$timeout(function(){
            					element.closest('td').prev().find('span').replaceWith('<span class="iapp-badge">Paid</span>');
		                		element.parent().next().next('li').remove();
		                		element.replaceWith('<span class="glyphicon glyphicon-ok iapp-ok"></span>');				                			

            				},1000);
            				
            			}
					});				
	            });
	        }
	    };
	});

	app.directive('ngFadeOut',function(){
		return {
			link: function(scope, element, attrs){
				element.bind('click',function(){
					element.parent().fadeOut(3000,"linear");
				});
			}
		}
	});

	app.directive('ngEditInvoice', function($http, $timeout) {
	    return {
	        link: function (scope, element, attrs) {
	            element.bind('click', function (){
	              var  Id = element.data('id');
	              window.location.replace(appurl+'/invoice/edit/'+Id);				
	            });
	        }
	    };
	});

	app.directive('ngEditReceipt', function($http, $timeout) {
	    return {
	        link: function (scope, element, attrs) {
	            element.bind('click', function (){
	              var  Id = element.data('id');
	              window.location.replace(appurl+'/receipt/edit/'+Id);				
	            });
	        }
	    };
	});


	app.directive('ngRemoveInvoice', function($http, $timeout) {
	    return {
	        link: function (scope, element, attrs) {
	            element.bind('click', function (){
	              var  Id = element.data('id');
	                $http({
	                		method  : 'POST',
					        url     : appurl+'/invoice/remove',
					        data    : {id : Id, _token : _token}, 
					    }).success(function(data){
                			if(data.statusCode == 200){
                				window.location.reload();            				               				
                			}
					});				
	            });
	        }
	    };
	});

	app.directive('ngRemoveReceipt', function($http, $timeout) {
	    return {
	        link: function (scope, element, attrs) {
	            element.bind('click', function (){
	              var  Id = element.data('id');
	                $http({
	                		method  : 'POST',
					        url     : appurl+'/receipt/remove',
					        data    : {id : Id, _token : _token}, 
					}).success(function(data){
	        			if(data.statusCode == 200){
	        				window.location.reload();            				               				
	        			}
					});				
	            });
	        }
	    };
	});

	app.controller('EditServiceInvoiceController',function($scope, $http, $timeout, $element){
		$scope.serviceInvoiceButton = "Update Invoice ...";
		$scope.serviceInvoiceButtonStatus = true;
		$scope.databaseError = false;
		$scope.manualCode = false;
		$scope.errors = [{}];
		$scope.hasErrors = false;

		$scope.suggestList = false;
		$scope.customer = {};
		$scope.organization = {};
		$scope.customers = [{}];
		$scope.searchCustomer = function(){
			$timeout.cancel(typingTimer);
			typingTimer = $timeout(function(){
				var searchText = $scope.customer.name;
				if(!searchText){
					$('.searched-result').fadeOut();
				}else{
					$http({
							method: 'POST',
							url: appurl+'/config/searchcustomer',
							data:{'searchText': searchText}
					}).success(function(response){
						if(response == ''){
							$('.searched-result').fadeOut();
						}
						if(response != ''){
							$scope.customers = response;
							$scope.suggestList = true;
							$('.searched-result').fadeIn();
						}
					});
				}
				
			},300);	
			$element.on('focusout', function(){
				$('.searched-result').fadeOut();
			});
		}

		$scope.selectAddress = function(index){
			var name = $scope.customers[index].name,
				address = $scope.customers[index].long_address;
			$scope.customer.name = name;
			$scope.customer.address = address;
		}

		$scope.choices = [{}];
		$scope.addNewChoice = function() {  
			$scope.choices.push({});
		};

		$scope.removeInput = function(index){
			if(index != 0){
				$scope.choices.splice(index,1);	
			}	    
		}
		var currentId;
		$timeout(function(){
			currentId = $scope.general.currentId;
			$http.get(appurl+'/invoice/current/'+currentId)
			.success(function(data){
	         	$scope.organization = data.organization;
	         	$scope.choices = data.description;
	         	$scope.customer = data.customer;
	        });
		
		},500);

		$scope.doFocus = function(){
			$scope.manualCode = true;
			if($scope.manualCode){
				$timeout(function(){
					$('.is-unique-invoice').focus();
					if($scope.organization.isManualCode == $scope.organization.invoiceNumber){
						$scope.organization.isManual = 0;
					}else{
						$scope.organization.isManual = 1;
					}
				});
			}
		}

		$scope.checkState = function(){
			if($scope.organization.isManualCode == $scope.organization.invoiceNumber){
				$scope.organization.isManual = 0;
			}else{
				$scope.organization.isManual = 1;
			}
		}

		$scope.serviceInvoiceProcess = function(){	
			if($scope.serviceInvoiceForm.$valid){
				$scope.serviceInvoiceButton = "Updating Invoice ... ...";
				$scope.serviceInvoiceButtonStatus = false;

				$http({
			        method  : 'POST',
			        url     : appurl+'/invoice/updateservice',
			        data    : {'organization' : $scope.organization,
			        		   'customer': $scope.customer,
			        		   'descs': $scope.choices,
			        			'_token' : _token,
			        			'currentId' : currentId,
			        			'requestType': 'default',
			        			'currentState': 'service'
			        		},
			    })
				.success(function(data){
					if(data.statusCode == 200 && data.response == true){
		            	window.location.href = appurl+'/invoice/view?response='+data.invoiceId+'&secure='+data.invoiceTpye+'&status='+data.statusCode;
		            }else if(data.statusCode == 503 && data.response == false){
		            	$scope.databaseError = true;
		            	$scope.serviceInvoiceButtonStatus = true;
		            	$scope.serviceInvoiceButton = "Update Invoice ...";
		            }
		            else{
		            	$scope.hasErrors = true;
		            	$scope.errors = data;
		            	$timeout(function(){
							 $("html, body").animate({ scrollTop: 0 }, 600);
		            	});
		            }
		        });	
			}
		}
	});

	app.controller('EditWorkInvoiceController',function($scope, $http, $timeout, $element){

		$scope.workInvoiceButton = "Update Invoice ...";
		$scope.workInvoiceButtonStatus = true;
		$scope.databaseError = false;
		$scope.dataSubmitted = false;
		$scope.manualCode = false;
		$scope.errors = [{}];
		$scope.hasErrors = false;

		$scope.suggestList = false;
		$scope.customer = {};
		$scope.organization = {};
		$scope.customers = [{}];
		$scope.searchCustomer = function(){
			$timeout.cancel(typingTimer);
			typingTimer = $timeout(function(){
				var searchText = $scope.customer.name;
				if(!searchText){
					$('.searched-result').fadeOut();
				}else{
					$http({
							method: 'POST',
							url: appurl+'/config/searchcustomer',
							data:{'searchText': searchText}
					}).success(function(response){
						if(response == ''){
							$('.searched-result').fadeOut();
						}
						if(response != ''){
							$scope.customers = response;
							$scope.suggestList = true;
							$('.searched-result').fadeIn();
						}
					});
				}
				
			},300);	
			$element.on('focusout', function(){
				$('.searched-result').fadeOut();
			});
		}

		$scope.selectAddress = function(index){
			var name = $scope.customers[index].name,
				address = $scope.customers[index].long_address;
			$scope.customer.name = name;
			$scope.customer.address = address;
		}


		$scope.choices = [{"unit":"hour"}];
		$scope.addNewChoice = function() {  
			$scope.choices.push({"unit":"hour"});
		};

		$scope.removeInput = function(index){
			if(index != 0){
				$scope.choices.splice(index,1);	
			}	    
		}

		var currentId;
		$timeout(function(){
			currentId = $scope.general.currentId;
			$http.get(appurl+'/invoice/current/'+currentId)
			.success(function(data){
	         	$scope.organization = data.organization;
	         	$scope.choices = data.description;
	         	$scope.customer = data.customer;
	        });
		
		},1000);

		$scope.doFocus = function(){
			$scope.manualCode = true;
			if($scope.manualCode){
				$timeout(function(){
					$('.unique-number').focus();
					if($scope.organization.isManualCode == $scope.organization.invoiceNumber){
						$scope.organization.isManual = 0;
					}else{
						$scope.organization.isManual = 1;
					}
				});
			}
		}

		$scope.checkState = function(){
			if($scope.organization.isManualCode == $scope.organization.invoiceNumber){
				$scope.organization.isManual = 0;
			}else{
				$scope.organization.isManual = 1;
			}
		}
		
		$scope.workInvoiceProcess = function(){
			if($scope.workInvoiceForm.$invalid){
				$scope.dataSubmitted = true;
			}

			if($scope.workInvoiceForm.$valid){
				$scope.workInvoiceButton = "Updating Invoice ...";
				$scope.workInvoiceButtonStatus = false;	
				$http({
			        method  : 'POST',
			        url     : appurl+'/invoice/updatework',
			        data    : {'organization' : $scope.organization,
			        		   'customer': $scope.customer,
			        		   'descs': $scope.choices,
			        			'_token' : _token,
			        			'currentId' : currentId,
			        			'requestType': 'default',
			        			'currentState': 'work'
			        		},
			    })
				.success(function(data){
					if(data.statusCode == 200 && data.response == true){
		            	window.location.replace(appurl+'/invoice/view?response='+data.invoiceId+'&secure='+data.invoiceTpye+'&status='+data.statusCode);
		            }else if(data.statusCode == 503 && data.response == false){
		            	$scope.databaseError = true;
		            	$scope.workInvoiceButtonStatus = true;
		            	$scope.workInvoiceButton = "Update Invoice ...";
		            }else{
		            	$scope.hasErrors = true;
		            	$scope.errors = data;
		            	$timeout(function(){
							 $("html, body").animate({ scrollTop: 0 }, 600);
		            	});
		            }
		        });
			}
		}
	});

	app.controller('EditWorkReceiptController',function($scope, $http, $timeout, $element){
		$scope.workReceiptButton = "Update Receipt ...";
		$scope.workReceiptButtonStatus = true;
		$scope.databaseError = false;
		$scope.manualCode = false;
		$scope.errors = [{}];
		$scope.hasErrors = false;

		$scope.suggestList = false;
		$scope.customer = {};
		$scope.organization = {};
		$scope.customers = [{}];
		$scope.searchCustomer = function(){
			$timeout.cancel(typingTimer);
			typingTimer = $timeout(function(){
				var searchText = $scope.customer.name;
				if(!searchText){
					$('.searched-result').fadeOut();
				}else{
					$http({
							method: 'POST',
							url: appurl+'/config/searchcustomer',
							data:{'searchText': searchText}
					}).success(function(response){
						if(response == ''){
							$('.searched-result').fadeOut();
						}
						if(response != ''){
							$scope.customers = response;
							$scope.suggestList = true;
							$('.searched-result').fadeIn();
						}
					});
				}
				
			},300);	
			$element.on('focusout', function(){
				$('.searched-result').fadeOut();
			});
		}

		$scope.selectAddress = function(index){
			var name = $scope.customers[index].name,
				address = $scope.customers[index].long_address;
			$scope.customer.name = name;
			$scope.customer.address = address;
		}


		$scope.choices = [{}];
		$scope.addNewChoice = function() {  
			$scope.choices.push({});
		};

		$scope.removeInput = function(index){
			if(index != 0){
				$scope.choices.splice(index,1);	
			}	    
		}
		
		var currentId;
		$timeout(function(){
			currentId = $scope.general.currentId;
			$http.get(appurl+'/receipt/current/'+currentId)
			.success(function(data){
	         	$scope.organization = data.organization;
	         	$scope.choices = data.description;
	         	$scope.customer = data.customer;
	        });
		
		},1000);

		$scope.doFocus = function(){
			$scope.manualCode = true;
			if($scope.manualCode){
				$timeout(function(){
					$('.is-unique-receipt').focus();
					if($scope.organization.isManualCode == $scope.organization.receiptNumber){
						$scope.organization.isManual = 0;
					}else{
						$scope.organization.isManual = 1;
					}
				});
			}
		}

		$scope.checkState = function(){
			if($scope.organization.isManualCode == $scope.organization.receiptNumber){
				$scope.organization.isManual = 0;
			}else{
				$scope.organization.isManual = 1;
			}
		}

		$scope.workReceiptProcess = function(){	
			if($scope.workReceiptForm.$valid){
				$scope.workReceiptButton = "Updating Receipt ...";
				$scope.workReceiptButtonStatus = false;
				$http.post(appurl+'/receipt/updatework' ,{'organization' : $scope.organization,
															'customer': $scope.customer,
															'descs': $scope.choices, 
															'_token' : _token, 
															'currentId' : currentId,
															'requestType': 'default', 
															'currentState': 'work'})
				.success(function(data){					
		            if(data.statusCode == 200 && data.response == true){
		            	window.location.href = appurl+'/receipt/view?response='+data.receiptId+'&secure='+data.receiptTpye+'&status='+data.statusCode;
		            }else if(data.statusCode == 503 && data.response == false){
		            	$scope.databaseError = true;
		            	$scope.workReceiptButtonStatus = true;
		            	$scope.workReceiptButton = "Update Receipt ...";
		            }else{
		            	$scope.hasErrors = true;
		            	$scope.errors = data;
		            	$timeout(function(){
							 $("html, body").animate({ scrollTop: 0 }, 600);
		            	});
		            }
		        });	
			}
		}
	});	

	app.controller('EditServiceReceiptController',function($scope, $http, $timeout, $element){		
		$scope.serviceReceiptButton = "Update Receipt ...";
		$scope.serviceReceiptButtonStatus = true;
		$scope.databaseError = false;
		$scope.manualCode = false;
		$scope.errors = [{}];
		$scope.hasErrors = false;

				$scope.suggestList = false;
		$scope.customer = {};
		$scope.organization = {};
		$scope.customers = [{}];
		$scope.searchCustomer = function(){
			$timeout.cancel(typingTimer);
			typingTimer = $timeout(function(){
				var searchText = $scope.customer.name;
				if(!searchText){
					$('.searched-result').fadeOut();
				}else{
					$http({
							method: 'POST',
							url: appurl+'/config/searchcustomer',
							data:{'searchText': searchText}
					}).success(function(response){
						if(response == ''){
							$('.searched-result').fadeOut();
						}
						if(response != ''){
							$scope.customers = response;
							$scope.suggestList = true;
							$('.searched-result').fadeIn();
						}
					});
				}
				
			},300);	
			$element.on('focusout', function(){
				$('.searched-result').fadeOut();
			});
		}

		$scope.selectAddress = function(index){
			var name = $scope.customers[index].name,
				address = $scope.customers[index].long_address;
			$scope.customer.name = name;
			$scope.customer.address = address;
		}

		$scope.choices = [{}];
		$scope.addNewChoice = function() {  
			$scope.choices.push({});
		};

		$scope.removeInput = function(index){
			if(index != 0){
				$scope.choices.splice(index,1);	
			}	    
		}	

		var currentId;
		$timeout(function(){
			currentId = $scope.general.currentId;
			$http.get(appurl+'/receipt/current/'+currentId)
			.success(function(data){
	         	$scope.organization = data.organization;
	         	$scope.choices = data.description;
	         	$scope.customer = data.customer;
	        });
		
		},1000);

		$scope.doFocus = function(){
			$scope.manualCode = true;
			if($scope.manualCode){
				$timeout(function(){
					$('.is-unique-receipt').focus();
					if($scope.organization.isManualCode == $scope.organization.receiptNumber){
						$scope.organization.isManual = 0;
					}else{
						$scope.organization.isManual = 1;
					}
				});
			}
		}

		$scope.checkState = function(){
			if($scope.organization.isManualCode == $scope.organization.receiptNumber){
				$scope.organization.isManual = 0;
			}else{
				$scope.organization.isManual = 1;
			}
		}

		$scope.serviceReceiptProcess = function(){
			if($scope.serviceReceiptForm.$valid){
				$scope.serviceReceiptButton = "Updating Receipt ...";
				$scope.serviceReceiptButtonStatus = false;
				$http({
			        method  : 'POST',
			        url     : appurl+'/receipt/updateservice',
			        data    : {'organization' : $scope.organization,
			        		   'customer': $scope.customer,
			        		   'descs': $scope.choices,
			        			'_token' : _token,
			        			'currentId' : currentId,
			        			'requestType': 'default',
			        			'currentState': 'service'
			        		},
			    })
				.success(function(data){
					if(data.statusCode == 200 && data.response == true){
		            	window.location.href = appurl+'/receipt/view?response='+data.receiptId+'&secure='+data.receiptTpye+'&status='+data.statusCode;
		            }else if(data.statusCode == 503 && data.response == false){
		            	$scope.databaseError = true;
		            	$scope.serviceReceiptButtonStatus = true;
		            	$scope.serviceReceiptButton = "Update Receipt ...";
		            }else{
		            	$scope.hasErrors = true;
		            	$scope.errors = data;
		            	$timeout(function(){
							 $("html, body").animate({ scrollTop: 0 }, 600);
		            	});
		            }
		        });	
			}
		}
	});

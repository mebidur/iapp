var appurl = document.getElementById('siteUrl').value;
var _token 	= document.getElementById('_token').value;

var app = angular.module('iApp', ['ngMessages'])
	.config(['$interpolateProvider','$httpProvider',function($interpolateProvider,$httpProvider){
		$httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
		$interpolateProvider.startSymbol('[[');
		$interpolateProvider.endSymbol(']]');
	}]);


	app.directive('ensureUnique', function($http) {
		return {
		require: 'ngModel',
			link: function(scope, ele, attrs, c) {
			scope.$watch(attrs.ngModel, function(n) {
			if (!n) return;
				$http({
					method: 'POST',
					url: appurl+'/invoice/check',
					data: {"field": n, "_token" : _token}
				}).success(function(data) {
					c.$setValidity('unique', data.isUnique);
				}).error(function(data) {
						c.$setValidity('unique', false);
					});
				});
			}
		}
	});
	app.directive('isUnique', function($http) {
		return {
		require: 'ngModel',
			link: function(scope, ele, attrs, c) {
			scope.$watch(attrs.ngModel, function(n) {
			if (!n) return;
				$http({
					method: 'POST',
					url: appurl+'/receipt/check',
					data: {"field": n, "_token" : _token}
				}).success(function(data) {
					c.$setValidity('isunique', data.isUnique);
				}).error(function(data) {
						c.$setValidity('isunique', false);
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
	        $(function(){
	            element.datepicker({
	                showOn:"both",
	                changeYear:true,
	                changeMonth:true,
	                format:'yyyy-mm-dd',
	                maxDate: new Date(),	                
	                onSelect:function (dateText, inst) {
	                    scope.$apply(function(scope){
	                        ngModel.assign(scope, dateText);
	                    });
	                }
	            }).on('changeDate', function(ev){
				        $(this).datepicker('hide');
				    });
	        });
	    }
	});


	app.controller('WorkInvoiceController',function($scope, $http, $timeout){

		$scope.workInvoiceButton = "Continue ...";
		$scope.workInvoiceButtonStatus = true;
		$scope.databaseError = true;
		$scope.submitted = false;
		$scope.manualCode = false;
		$scope.errors = [{}];
		$scope.hasErrors = false;

		$scope.choices = [{}];
		$scope.addNewChoice = function() {  
			$scope.choices.push({});
		};

		$scope.removeInput = function(index){
			if(index != 0){
				$scope.choices.splice(index,1);	
			}	    
		}

		$scope.organization = {};
		$scope.customer = {};
		$http({method : 'GET', url: appurl+'/config/initialize'}).success(function(data){
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
			if($scope.workInvoiceForm.$valid){
				$scope.workInvoiceButton = "Please wait ...";
				$scope.workInvoiceButtonStatus = false;	
				

				$http({
			        method  : 'POST',
			        url     : appurl+'/invoice/work',
			        data    : {'organization' : $scope.organization,
			        		   'customer': $scope.customer,
			        		   'allDesc': $scope.choices,
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
		            	$scope.workInvoiceButton = "Continue ...";
		            }else{
		            	$scope.hasErrors = true;
		            	$scope.errors = data;
		            	$timeout(function(){
							 $("html, body").animate({ scrollTop: 0 }, 600);
		            	});
		            }
		        });
			}else{
				$scope.submitted = true;
			}
		}
	});	


	app.controller('LoginFormController',function($scope){
		$scope.buttonText = 'Sign In';
		$scope.checkLogin = function(){
			$scope.buttonText = 'Signing In ...';
		}
	});

	app.controller('ServiceInvoiceController',function($scope, $http, $timeout){
		$scope.serviceInvoiceButton = "Continue ...";
		$scope.serviceInvoiceButtonStatus = true;
		$scope.databaseError = false;
		$scope.manualCode = false;
		$scope.errors = [{}];
		$scope.hasErrors = false;

		$scope.choices = [{}];
		$scope.addNewChoice = function() {  
			$scope.choices.push({});
		};

		$scope.removeInput = function(index){
			if(index != 0){
				$scope.choices.splice(index,1);	
			}	    
		}

		$scope.organization = {};
		$scope.customer = {};
		$http({method : 'GET', url: appurl+'/config/initialize'}).success(function(data){
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
				$scope.serviceInvoiceButton = "Please wait ...";
				$scope.serviceInvoiceButtonStatus = false;

				$http({
			        method  : 'POST',
			        url     : appurl+'/invoice/service',
			        data    : {'organization' : $scope.organization,
			        		   'customer': $scope.customer,
			        		   'allDesc': $scope.choices,
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
		            	$scope.serviceInvoiceButton = "Continue ...";
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

	app.controller('WorkReceiptController',function($scope, $http, $timeout){
		$scope.workReceiptButton = "Continue ...";
		$scope.workReceiptButtonStatus = true;
		$scope.databaseError = false;
		$scope.manualCode = false;
		$scope.errors = [{}];
		$scope.hasErrors = false;

		$scope.choices = [{}];
		$scope.addNewChoice = function() {  
			$scope.choices.push({});
		};

		$scope.removeInput = function(index){
			if(index != 0){
				$scope.choices.splice(index,1);	
			}	    
		}
		
		$scope.organization = {};
		$scope.customer = {};
		$http({method : 'GET', url: appurl+'/config/initializer'}).success(function(data){
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
				$scope.workReceiptButton = "Please wait ...";
				$scope.workReceiptButtonStatus = false;

				$http({
			        method  : 'POST',
			        url     : appurl+'/receipt/work',
			        data    : {'organization' : $scope.organization,
			        		   'customer': $scope.customer,
			        		   'allDesc': $scope.choices,
			        			'_token' : _token,
			        			'requestType': 'default',
			        			'currentState': 'work'
			        		},
			    })
				.success(function(data){					
		            if(data.statusCode == 200 && data.response == true){
		            	window.location.href = appurl+'/receipt/view?response='+data.receiptId+'&secure='+data.receiptTpye+'&status='+data.statusCode;
		            }else if(data.statusCode == 503 && data.response == false){
		            	$scope.databaseError = true;
		            	$scope.workReceiptButtonStatus = true;
		            	$scope.workReceiptButton = "Continue ...";
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

	app.controller('ServiceReceiptController',function($scope, $http, $timeout){		
		$scope.serviceReceiptButton = "Continue ...";
		$scope.serviceReceiptButtonStatus = true;
		$scope.databaseError = false;
		$scope.manualCode = false;
		$scope.errors = [{}];
		$scope.hasErrors = false;

		$scope.choices = [{}];
		$scope.addNewChoice = function() {  
			$scope.choices.push({});
		};

		$scope.removeInput = function(index){
			if(index != 0){
				$scope.choices.splice(index,1);	
			}	    
		}	
		$scope.organization = {};
		$scope.customer = {};
		$http({method : 'GET', url: appurl+'/config/initializer'}).success(function(data){
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
				$scope.serviceReceiptButton = "Please wait ...";
				$scope.serviceReceiptButtonStatus = false;

				$http({
			        method  : 'POST',
			        url     : appurl+'/receipt/service',
			        data    : {'organization' : $scope.organization,
			        		   'customer': $scope.customer,
			        		   'allDesc': $scope.choices,
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
		            	$scope.serviceReceiptButton = "Continue ...";
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
		$scope.configButton = 'Continue ...';
		$scope.doLoad = function(){
			$scope.configButton = 'Loading ...';
		}
		$scope.company = {};
		$http({method : 'GET', url: appurl+'/config/initializeo'}).success(function(data){
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
					        data    : {id : Id, _token : _token}, })
	                	.success(function(data){
	                			if(data.statusCode == 200){
	                				element.html('Wait ..').css('background-color','#45B4D7');
	                				$timeout(function(){
	                					element.closest('td').prev().find('span').replaceWith('<span class="iapp-badge">Paid</span>');
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


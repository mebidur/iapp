var baseUrl = document.getElementById('siteUrl').value;
var _token 	= document.getElementById('_token').value;
var _date 	= document.getElementById('_date').value;

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
					url: baseUrl+'/invoice/check',
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
					url: baseUrl+'/receipt/check',
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
		$scope.databaseError = false;
		$scope.submitted = false;
		$scope.manualCode = false;

		$scope.choices = [{}];
		$scope.addNewChoice = function() {  
			$scope.choices.push({});
		};

		$scope.removeInput = function(index){
			if(index != 0){
				$scope.choices.splice(index,1);	
			}	    
		}

		$scope.organization = {'serviceDate': _date};
		$scope.customer = {};

		$scope.doFocus = function(){
			$scope.manualCode = true;
			if($scope.manualCode){
				$timeout(function(){
					$('.unique-number').focus();
					if($scope.organization.isManualCode == $scope.organization.invoiceNumber){
						$scope.organization.state = 0;
					}else{
						$scope.organization.state = 1;
					}
				});
			}
		}

		$scope.checkNumber = function(){
			if($scope.organization.isManualCode == $scope.organization.invoiceNumber){
				$scope.organization.state = 0;
			}else{
				$scope.organization.state = 1;
			}
		}
		
		$scope.workInvoiceProcess = function(){	
			if($scope.workInvoiceForm.$valid){
				$scope.workInvoiceButton = "Please wait ...";
				$scope.workInvoiceButtonStatus = false;	
				

				$http({
			        method  : 'POST',
			        url     : baseUrl+'/invoice/work',
			        data    : {'organization' : $scope.organization,
			        		   'customer': $scope.customer,
			        		   'allDesc': $scope.choices,
			        			'_token' : _token,
			        			'requestType': 'default',
			        			'currentState': 'work'
			        		},
			    })
				.success(function(data){
					console.log(data);

		            if(data.statusCode == 200 && data.response == true){
		            	window.location.replace(baseUrl+'/invoice/view?response='+data.invoiceId+'&secure='+data.invoiceTpye+'&status='+data.statusCode);
		            }else if(data.statusCode == 503 && data.response == false){
		            	$scope.databaseError = true;
		            	$scope.workInvoiceButtonStatus = true;
		            	$scope.workInvoiceButton = "Continue ...";
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

	app.controller('ServiceInvoiceController',function($scope, $http){
		$scope.serviceInvoiceButton = "Continue ...";
		$scope.serviceInvoiceButtonStatus = true;
		$scope.databaseError = false;

		$scope.choices = [{}];
		$scope.addNewChoice = function() {  
			$scope.choices.push({});
		};

		$scope.removeInput = function(index){
			if(index != 0){
				$scope.choices.splice(index,1);	
			}	    
		}

		$scope.organization = {'serviceDate': _date};
		$scope.customer = {};

		$scope.serviceInvoiceProcess = function(){	
			if($scope.serviceInvoiceForm.$valid){
				$scope.serviceInvoiceButton = "Please wait ...";
				$scope.serviceInvoiceButtonStatus = false;

				$http({
			        method  : 'POST',
			        url     : baseUrl+'/invoice/service',
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
		            	window.location.href = baseUrl+'/invoice/view?response='+data.invoiceId+'&secure='+data.invoiceTpye+'&status='+data.statusCode;
		            }else if(data.statusCode == 503 && data.response == false){
		            	$scope.databaseError = true;
		            	$scope.serviceInvoiceButtonStatus = true;
		            	$scope.serviceInvoiceButton = "Continue ...";
		            }
		        });	
			}
		}
	});

	app.controller('WorkReceiptController',function($scope, $http){
		$scope.workReceiptButton = "Continue ...";
		$scope.workReceiptButtonStatus = true;
		$scope.databaseError = false;

		$scope.choices = [{}];
		$scope.addNewChoice = function() {  
			$scope.choices.push({});
		};

		$scope.removeInput = function(index){
			if(index != 0){
				$scope.choices.splice(index,1);	
			}	    
		}
		
		$scope.organization = {'serviceDate': _date};
		$scope.customer = {};


		$scope.workReceiptProcess = function(){	
			if($scope.workReceiptForm.$valid){
				$scope.workReceiptButton = "Please wait ...";
				$scope.workReceiptButtonStatus = false;

				$http({
			        method  : 'POST',
			        url     : baseUrl+'/receipt/work',
			        data    : {'organization' : $scope.organization,
			        		   'customer': $scope.customer,
			        		   'allDesc': $scope.choices,
			        			'_token' : _token,
			        			'requestType': 'default',
			        			'currentState': 'work'
			        		},
			    })
				.success(function(data){
					console.log(data);
		            if(data.statusCode == 200 && data.response == true){
		            	window.location.href = baseUrl+'/receipt/view?response='+data.receiptId+'&secure='+data.receiptTpye+'&status='+data.statusCode;
		            }else if(data.statusCode == 503 && data.response == false){
		            	$scope.databaseError = true;
		            	$scope.workReceiptButtonStatus = true;
		            	$scope.workReceiptButton = "Continue ...";
		            }
		        });	
			}
		}
	});	

	app.controller('ServiceReceiptController',function($scope, $http){
		
		$scope.serviceReceiptButton = "Continue ...";
		$scope.serviceReceiptButtonStatus = true;
		$scope.databaseError = false;


		$scope.choices = [{}];
		$scope.addNewChoice = function() {  
			$scope.choices.push({});
		};

		$scope.removeInput = function(index){
			if(index != 0){
				$scope.choices.splice(index,1);	
			}	    
		}

		$scope.organization = {'serviceDate': _date};
		$scope.customer = {};

		$scope.serviceReceiptProcess = function(){
			if($scope.serviceReceiptForm.$valid){
				$scope.serviceReceiptButton = "Please wait ...";
				$scope.serviceReceiptButtonStatus = false;

				$http({
			        method  : 'POST',
			        url     : baseUrl+'/receipt/service',
			        data    : {'organization' : $scope.organization,
			        		   'customer': $scope.customer,
			        		   'allDesc': $scope.choices,
			        			'_token' : _token,
			        			'requestType': 'default',
			        			'currentState': 'service'
			        		},
			    })
				.success(function(data){
					console.log(data);
		            if(data.statusCode == 200 && data.response == true){
		            	window.location.href = baseUrl+'/receipt/view?response='+data.receiptId+'&secure='+data.receiptTpye+'&status='+data.statusCode;
		            }else if(data.statusCode == 503 && data.response == false){
		            	$scope.databaseError = true;
		            	$scope.serviceReceiptButtonStatus = true;
		            	$scope.serviceReceiptButton = "Continue ...";
		            }
		        });	
			}
		}
	});
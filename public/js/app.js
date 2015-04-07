var baseUrl = document.getElementById('siteUrl').value;
var _token = document.getElementById('_token').value;
var _date = document.getElementById('_date').value;
var app = angular.module('iApp', [])
	.config(['$interpolateProvider',function($interpolateProvider){
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

	app.controller('DateController',function($scope){
		$scope.serviceDate = _date;
		
	});
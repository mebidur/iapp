var app = angular.module('iApp', [])
	.config(['$interpolateProvider',function($interpolateProvider){
		$interpolateProvider.startSymbol('[[');
		$interpolateProvider.endSymbol(']]');
	}]);
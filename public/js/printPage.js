// (function (angular){
// 	'use strict';
 
// function printDirective(){
// 	var printSection = document.getElementById('printSection');

// 	if (!printSection){
// 		printSection = document.createElement('div');
// 		printSection.id = 'printSection';
// 		document.body.appendChild(printSection);
// 	}
	 
// 	function link(scope, element, attrs){
// 		element.on('click', function () {
// 			var elemToPrint = document.getElementById(attrs.printElementId);
// 			if (elemToPrint) {
// 			printElement(elemToPrint);
// 			window.print();
// 			}
// 		});
	 
// 		window.onafterprint = function(){
// 			printSection.innerHTML = '';
// 		}
// 	}
	 
// 	function printElement(elem){
// 		var domClone = elem.cloneNode(true);
// 		printSection.appendChild(domClone);
// 	}
	 
// 	return{
// 		link: link,
// 		restrict: 'A'
// 	};
// } 
// app.directive('ngPrint', [printDirective]);
// }(window.angular));
  document.getElementById("pdf-print-btn").onclick = function () {
    printElement(document.getElementById("container"));  
    window.print();
}

function printElement(elem) {
    var domClone = elem.cloneNode(true);
}
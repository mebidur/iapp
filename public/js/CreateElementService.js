app.controller('CrtElementService',function($scope, $compile){
    $scope.add = function(){
        $('#workDescriptonHolder').append($compile('<div class="row">'+
            '<div class="col-md-6">'+
                '<label><b>Description of Work</b></label>'+
                '<textarea class="form-control" rows="3" name="workDescription[]" placeholder="Description of Work"></textarea>'+
            '</div>'+
            '<div class="col-md-4">'+
                '<label><b>Amount</b></label>'+
                '<input type="text" class="form-control" name="amount[]" placeholder="Eg: 10">'+
            '</div>'+
           ' <div class="col-md-2">'+
                '<br>'+
                '<button type="button"class="btn btn-danger" style="float: right;  position: relative; top: 6px;"><b>&times;</b></button>'+
            '</div>'+
        '</div>')($scope));
    }
});
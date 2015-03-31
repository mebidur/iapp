app.controller('CrtElement',function($scope, $compile){
    $scope.add = function(){
        $('#workDescriptonHolder').append($compile('<div class="row">'+
            '<div class="col-md-6">'+
                '<label><b>Description of Work</b></label>'+
                '<textarea class="form-control" rows="3" name="workDescription[]" placeholder="Description of Work"></textarea>'+
            '</div>'+
            '<div class="col-md-2">'+
                '<label><b>Rate</b></label>'+
                '<input type="text" class="form-control" name="rate[]" placeholder="Eg: 10">'+
            '</div>'+
            '<div class="col-md-3">'+
                '<label><b>Hour</b></label>'+
                '<input type="text" class="form-control" name="hour[]" placeholder="Eg: 10">'+
            '</div>'+
            '<div class="col-md-1">'+
                '<br>'+
                '<button type="button"class="btn btn-danger" style="float: right;  position: relative; top: 6px;"><b>&times;</b></button>'+
            '</div>'+
        '</div>')($scope));
    }
});

// $(document).ready(function(){
//     $('.add-more-field').on('click',function(){
//         $('#workDescriptonHolder').append('<div class="row">'+
//             '<div class="col-md-6">'+
//                 '<label><b>Description of Work</b></label>'+
//                 '<textarea class="form-control" rows="3" name="workDescription[]" placeholder="Description of Work"></textarea>'+
//             '</div>'+
//             '<div class="col-md-2">'+
//                 '<label><b>Rate</b></label>'+
//                 '<input type="text" class="form-control" name="rate[]" placeholder="Eg: 10">'+
//             '</div>'+
//             '<div class="col-md-3">'+
//                 '<label><b>Hour</b></label>'+
//                 '<input type="text" class="form-control" name="hour[]" placeholder="Eg: 10">'+
//             '</div>'+
//             '<div class="col-md-1">'+
//                 '<br>'+
//                 '<button type="button"class="btn btn-danger" style="float: right; font-size: 15px;position: relative; top: 6px;"><b>&times;</b></button>'+
//             '</div>'+
//         '</div>');
//     });
// });
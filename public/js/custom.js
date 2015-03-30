$(document).ready(function(){
	$('.sign-in-btn, .work-invoice-btn').on('click',function(){
		$(this).button('loading');
	});
	$('.datepicker').datepicker({format: "yyyy-mm-dd" }).on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
});
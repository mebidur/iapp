$(document).ready(function(){
	$('.sign-in-btn, .work-invoice-btn').on('click',function(){
		if($('#downloadPDF').is(':checked')){
			return true;
		}else{
			$(this).button('loading');
		}		
	});
	$('.datepicker').datepicker({format: "yyyy-mm-dd" }).on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
});
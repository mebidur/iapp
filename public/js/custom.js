$(document).ready(function(){

	$(document).ready(function(){
	     // $(document).on("keydown", disableF5);
	});

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	function disableF5(e){
		if ((e.which || e.keyCode) != 116 || (e.which || e.keyCode) != 82){
			window.onbeforeunload = function(){
		        return 'Really, want to reload ?';
			}
		}
	};

	$('.btn-login, .work-invoice-btn').on('click',function(){
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
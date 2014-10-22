$(function(){
			$(".fit-a").each(function(){
				$(this).click(function(){

					var checktaken=$(this).prev().attr('disabled');
					var checkchecked=$(this).prev().prop('checked');
					var value=$(this).prev().val();
					var seatid=$(this).prop('id');
					if(checktaken!="disabled"){
						// $(this).prev().prop("disabled", false);
						var rpl_seatid=seatid.replace('.','-');
						var price=$(".price"+rpl_seatid).val();
						$(this).toggleClass('choose available');
					}
					
				});
			});
});
$(function(){
			$("#totalamount").html('0');

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
						if($(this).hasClass('operatorseat_1')){
							$(this).toggleClass('choose operator_1');
						}
						if($(this).hasClass('operatorseat_2')){
							$(this).toggleClass('choose operator_2');
						}
						if($(this).hasClass('operatorseat_3')){
							$(this).toggleClass('choose operator_3');
						}
						
						$(this).toggleClass('available choose');
					}
				});
			});
});
$(function(){
			$("#totalamount").html('0');
			$("#total").val('0');
			$('.radios').each(function(){
				$(this).prop('checked', "");
			});

			$(".fit-a").each(function(){
				$(this).click(function(){
					var total=$('#total').val();

					var checktaken=$(this).prev().attr('disabled');
					var checkchecked=$(this).prev().prop('checked');
					var value=$(this).prev().val();
					var seatid=$(this).prop('id');
					if(checktaken!="disabled"){
						// $(this).prev().prop("disabled", false);
						var rpl_seatid=seatid.replace('.','-');
						var price=$(".price"+rpl_seatid).val();
						var price=$(".price"+rpl_seatid).val();
						if($(this).hasClass('operatorseat_1')){
							$(this).toggleClass('available operator_1');
						}
						if($(this).hasClass('operatorseat_2')){
							$(this).toggleClass('available operator_2');
						}
						if($(this).hasClass('operatorseat_3')){
							$(this).toggleClass('available operator_3');
						}
						$(this).toggleClass('choose available');
						if(checkchecked==false){
							total 	=Number(price) +Number(total);
							var seatno=$(".seatno"+rpl_seatid).val();
							var results='	<tr class="tbodyrow selectrow'+rpl_seatid+'">'+
									  	'		<td>'+seatno+'</td>'+
									  	'		<td>'+price+'</td>'+
									  	'		<td><span class="removerow'+ rpl_seatid +' remove">remove</span></td>'+
									  	'	</tr>';
							
							results +='<script>';
							results +='$(".removerow'+rpl_seatid+'").click(function(){'+
										' $(".selectrow'+rpl_seatid+'").remove();'+
										'var total=$("#total").val();'+
										'total =Number(total)-Number('+price+');'+
										'$("#total").val(total);$("#totalamount").html(total);'+
										' $("#'+rpl_seatid+'").toggleClass("choose available");'+
										'$("#'+rpl_seatid+'").prev().prop("checked", "")'+
										'});';
							results +='</script>';

							$('.selectedseats').append(results);
						}else{
							$(".selectrow"+seatid).remove();
							total =Number(total)-Number(price);
						}
						$("#total").val(total);
						$("#totalamount").html(total);
					}
					
				});
			});

			jQuery(document).tooltip({
				position: {
					my: "center bottom-10",
					at: "center top",
					using: function( position, feedback ) {
						$( this ).css( position );
						$( "<div>" )
							.addClass( "arrow" )
							.addClass( feedback.vertical )
							.addClass( feedback.horizontal )
							.appendTo( this );
					}
				}
			});
});
$(function(){
			$("#totalamount").html('0');
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
						var price=$(".price"+seatid).val();
						$(this).toggleClass('choose available');
						if(checkchecked==false){
							total 	=Number(price) +Number(total);
							var seatno=$(".seatno"+seatid).val();
							var results='	<tr class="tbodyrow selectrow'+seatid+'">'+
									  	'		<td>'+seatno+'</td>'+
									  	'		<td>'+price+'</td>'+
									  	'		<td><span class="removerow'+ seatid +' remove">remove</span></td>'+
									  	'	</tr>';
							
							results +='<script>';
							results +='$(".removerow'+seatid+'").click(function(){'+
										' $(".selectrow'+seatid+'").remove();'+
										'var total=$("#total").val();'+
										'total =Number(total)-Number('+price+');'+
										'$("#total").val(total);$("#totalamount").html(total);'+
										' $("#'+seatid+'").toggleClass("choose available");'+
										'$("#'+seatid+'").prev().prop("checked", "")'+
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
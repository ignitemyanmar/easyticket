@extends('../../../../master')
@section('content')

{{HTML::style('../../../../css/hover/component.css')}}
{{HTML::style('../../../../css/smkstyle.css')}}

<br>
{{HTML::script('../../../../js/hover/modernizr.custom.js')}}
{{HTML::script('../../../../js/hover/toucheffects.js')}}
<style type="text/css">
	.payment ul{
		list-style: none;
	}
	.payment ul label{
		color: black;
		font-size: 14px;
	}
	.submitshow{
		display: inline block !important;
	}
	.submithide{
		display: none !important;
	}
</style>
<div id="confirm_payment" class="reveal-modal small" data-reveal> 
	<div class="row">
		<div class="large-12 columns"><h2 style="text-align:left; font-size: 20px;">ဘဏ္သို႕ ေငြလြဲမႈ အတည္ျပဳရန္</h2></div>
		<div class="large-12 columns"><p>ဘဏ္သို႕ ေပးလြဲခဲ့ေသာ ေငြပမာဏႏွင့္ ဘဏ္ခ်လန္နံပါတ္အာ ေအာက္တြင္ ျဖည့္စြပ္ေပးပါ။</p></div>
		
		<div class="large-12 columns">
			<form action="/orderconfirm?access_token={{Auth::user()->access_token}}" method="GET"> 
				<input type="hidden" name="access_token" value="@if(Auth::check()){{Auth::user()->access_token}} @endif">
				<div class="row"> 
					<div class="small-12"> 
						<div class="row"> 
							<div class="small-5 columns text-right inline"> 
								ဘဏ္၏ ခ်လန္နံပါတ္
							</div> 
							<div class="small-7 columns right"> 
								<input type="text" required>
							</div> 
						</div> 
					</div> 
					<div class="small-12"> 
						<div class="row"> 
							<div class="small-5 columns text-right inline"> 
								ဘဏ္သို႕ ေပးလြဲခဲ့ေသာေငြ ပမာဏ
							</div> 
							<div class="small-7 columns right"> 
								<input type="text" id="you-pay" required>
							</div> 
						</div> 
					</div> 
					
					<div class="small-12"> 
						<div class="row"> 
							<div>&nbsp;</div>
							<div class="small-12 columns text-right"> 
								<input type="submit" id="confirm-payment" class="button" disabled value="သိမ္းမည္" style="padding: 9px 40px;">
							</div> 
						</div> 
					</div> 
				</div> 
			</form>
		</div>


	</div>
	<a class="close-reveal-modal">&#215;</a> 
</div>
<div class="row nopadding">
	<div class="large-1 columns">&nbsp;</div>
	<div class="large-10 columns nopadding shopping_cart">
		<div class="row nopadding">
			<div class="large-12 columns"><h3>Order Confirmation</h3></div>
		</div>
		<div class="row">
		<form action="@if(isset($deliveryto))../delivery/{{$deliveryto->id}}/update @else ../delivery/store @endif" method="POST">
			<input type="hidden" name="access_token" value="{{Auth::user()->access_token}}">
			<div class="large-12 columns"><h4>Receiving Information</h4></div>
			<div class="row nopadding"><div class="large-12 column"><div class="footer_line"></div></div></div>
			<div class="row" style="margin-top: 10px;">
				<div class="large-2 columns">Full Name:</div>
				<div class="large-4 columns left"><input type="text" name="name" value="@if(isset($deliveryto)){{$deliveryto->name}}@endif" required/></div>
			</div>
			<div class="row">
				<div class="large-2 columns">Delivery Address:</div>
				<div class="large-5 columns left"><textarea name="address" style="padding:0.5rem" required>@if(isset($deliveryto)){{$deliveryto->address}}@endif</textarea></div>
			</div>
			<div class="row">
				<div class="large-2 columns">Township:</div>
				<div class="large-6 columns left">
					<select id="township" name="township">
					@foreach($township as $rows)
						<option value="{{$rows->id}}" @if(Session::has('delivery_to_township')) @if(Session::get('delivery_to_township') == $rows->id) selected @endif @elseif(!Session::has('delivery_to_township')) @if(isset($deliveryto)) @if($rows->id == $deliveryto->city_id) selected @endif @endif @endif>{{$rows->name}}</option>
					@endforeach
					</select>
				</div>
			</div>
			<div>&nbsp;</div>
			<div class="row">
				<div class="large-2 columns">City:</div>
				<div class="large-4 columns left">
					<select id="city" name="city">
						@foreach($city as $rows)
						<option value="{{$rows->id}}" @if(Session::has('delivery_to_city')) @if(Session::get('delivery_to_city') == $rows->id) selected @endif @elseif(!Session::has('delivery_to_city')) @if(isset($deliveryto)) @if($rows->id == $deliveryto->city_id) selected @endif @endif @endif>{{$rows->name}}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div>&nbsp;</div>
			<div class="row">
				<div class="large-2 columns">Phone No.:</div>
				<div class="large-2 columns left"><input type="text" name="phone" value="@if(isset($deliveryto)){{$deliveryto->phone}}@endif" required/></div>
			</div>
			<div class="row">
				<div class="large-2 columns">&nbsp;</div>
				<div class="large-2 columns left"><input class="btn delivery" type="submit" name="btn_delivery" value="OK" /></div>
			</div>
		</form>
		</div>
		<div class="row">&nbsp;</div>
		<div class="row">
			<div class="large-12 columns"><h4>Delivery Information</h4></div>
			<div class="row nopadding"><div class="large-12 column"><div class="footer_line"></div></div></div>
			<div class="row">
				<div class="large-12 columns"><p>Make sure the "receiving information"</p></div>
			</div>
		</div>

		<div class="row">&nbsp;</div>
		<div class="row">
			<div class="large-12 columns"><h4>Invoice Information</h4> </div>
			<div class="row nopadding"><div class="large-12 column"><div class="footer_line"></div></div></div>
			<div class="row">
				<div class="large-12 columns"><p><span>Invoice amount for the cash payment amount(excluding gift card balance, vouchers, rebate amount, etc.)</span>, Make sure the "invoice information"</p></div>
			</div>
		</div>

		<div class="row">
			<div class="large-12 columns"><h4>Payment Information</h4> </div>
			<div class="row nopadding"><div class="large-12 column"><div class="footer_line"></div></div></div>
			<div class="row">
				<div class="large-12 columns payment">
					<ul>
						<li><label><input type="radio" name="payment" value="1" checked> Cart on Delivery</label></li>
						<li><label><input type="radio" name="payment" value="2"> CB Mobile Banking</label></li>
						<li><label><input type="radio" name="payment" value="3"> Ayeyatwady Banking</label></li>
					</ul>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="large-12 columns"><h4>Product Information</h4> </div>
			<div class="row nopadding"><div class="large-12 column"><div class="footer_line"></div></div></div>
			<div class="row">
				<div class="row">
					<div class="large-12 columns">
						<div class="large-6 columns item_header">Product Name</div>
						<div class="large-2 columns item_header">Number of(a)</div>
						<div class="large-2 columns item_header">Color & Size</div>
						<div class="large-2 columns text-right item_header">Subtotal</div>
					</div>
				</div>
				
				
			</div>
			<?php $grand_total = 0; ?>
			@foreach($shoppingcart as $rows)
			<div class="row">&nbsp;</div>
			<div class="row">
				<div class="row">
					<div class="large-12 columns">
						<div class="large-6 columns">{{$rows->name}}</div>
						<div class="large-2 columns">{{$rows->qty}}</div>
						<div class="large-2 columns">{{$rows->options->color.', '.$rows->options->size}}</div>
						<div class="large-2 columns text-right">{{$rows->subtotal}}</div>
						<?php $grand_total += $rows->subtotal; ?>
					</div>
				</div>
				
			</div>
			<div class="row">&nbsp;</div>
			@endforeach
			<div class="row nopadding"><div class="large-12 column"><div class="footer_line"></div></div></div>
		</div>

		<div class="row total">
			<div class="large-12 columns text-right">The amount of goods: {{$grand_total}} Kyats</div>
			<div class="large-12 columns text-right"><h4>You need to pay for the order: <span id="payable">{{$grand_total}}</span> Kyats</h4></div>
			<div class="large-12 columns text-right payment_method">
				<a href="../orderconfirm?access_token={{Auth::user()->access_token}}" class="button cart-on-delivery submitshow"  style="padding: 15px 40px;">Submit Order</a>
				<a href="" data-reveal-id="confirm_payment" class="button other-payment submithide"  style="padding: 15px 40px;">Submit Order</a>
			</div>
			<h4>&nbsp;</h4>
		</div>
		

	</div>
	<div class="large-1 columns">&nbsp;</div>
</div>
<script type="text/javascript">		
	$('.payment input[type="radio"]').change(function(){
		if(this.checked) { 
			var val = $(this).val(); 
			if(val == 1){
				$('.other-payment').addClass('submithide');
				$('.other-payment').removeClass('submitshow');
				$('.cart-on-delivery').addClass('submitshow');
				$('.cart-on-delivery').removeClass('submithide');

			}else{
				$('.other-payment').addClass('submitshow');
				$('.other-payment').removeClass('submithide');
				$('.cart-on-delivery').addClass('submithide');
				$('.cart-on-delivery').removeClass('submitshow');
				if(val == 2){
					window.open("http://www.cbbank.com.mm/ebanking/ebanking_ibanking.aspx", '_blank');
				}
				if(val == 3){
					window.open("https://www.ayaibanking.com/ibLogin.aspx", '_blank');
				}
			}
		}
		
	});
	$('#you-pay').keypress(function() {
    	var payment = this.value;
    	var payable = $('#payable').html();
		if(payment === payable){
			document.getElementById('confirm-payment').disabled = false;
		}else{
			document.getElementById('confirm-payment').disabled = true;
		}
	   
	});
	
	$(function(){
       $('#township').select2();
       $('#city').select2();
    });
</script>
@stop
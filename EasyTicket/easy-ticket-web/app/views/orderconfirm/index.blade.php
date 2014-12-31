@extends('../../../../master')
@section('content')

{{HTML::style('../../../../css/hover/component.css')}}
{{HTML::style('../../../../css/smkstyle.css')}}

<br>
{{HTML::script('../../../../js/hover/modernizr.custom.js')}}
{{HTML::script('../../../../js/hover/toucheffects.js')}}
<div class="row nopadding">
	<div class="large-1 columns">&nbsp;</div>
	<div class="large-10 columns nopadding shopping_cart">
		<div class="row nopadding">
			<div class="large-12 columns text-center"><h3><span>Thank you</span>, your order is complete!</h3></div>
			<div class="large-12 columns text-center confirmation"><h4>Your confirmation number is <span>{{$generate_ref_id}}</span></h4></div>
		</div>
		<?php 
			$grand_total = 0;
			foreach ($shoppingcart as $rows) {
				$grand_total += $rows->subtotal;
			}
		?>
		<div class="row">
			<div class="row">
				<div class="row">
					<div class="large-12 columns nopadding border">
						<div class="large-8 columns item_header border-right">Customer Information</div>
						<div class="large-4 columns item_header ">Order Summary</div>
						<div class="large-8 columns nopadding border-right">
							<div class="row cus_label">
								<div class="large-3 columns">Full Name:</div>
								<div class="large-8 columns a">{{$deliveryto->name}}</div>
							</div>
							<div class="row cus_label">
								<div class="large-3 columns">Delivery Address:</div>
								<div class="large-8 columns a">{{$deliveryto->address}}</div>
							</div>
							<div class="row cus_label">
								<div class="large-3 columns">Email Address:</div>
								<div class="large-8 columns a">{{Auth::user()->email}}</div>
							</div>
							<div class="row cus_label">
								<div class="large-3 columns">Phone No.:</div>
								<div class="large-8 columns a">{{$deliveryto->phone}}</div>
							</div>
							<p>&nbsp;</p>
							<div class="row">
								<div class="large-12 columns">
									<ul><li>Payment</li></ul>
									<p>Please pay according invoice amount at the same time make sure the following is complete (Invoices, packing list and warranty card, etc.)</p>
								</div>
								<div class="large-12 columns">
									<ul><li>Special Request</li></ul>
									<p>Make sure when you choose the brand, type and size is correct.</p>
									<p>The following article buyer can refuse to accept (the packaging is damaged, broken goods, packing list and invoice data is incorrect, etc.)</p>
									<p>Above problems can’t be solved please contact the following telephone numbers 09400030345 or 09253356699</p>
								</div>
							</div>
							<div class="row border-top">
								<div class="large-12 columns total text-center"><h4>Grand Total : <span>{{$grand_total}}</span> Kyat</h4></div>
							</div>
							<div class="row">
								<div class="large-12 columns text-right"><a href="/" class="button submitorder" style="padding:10px 2px;">Continue</a></div>
								<!-- <div class="large-6 columns "><a href="" class="button submitorder left" style="padding:10px 2px;">Print Receipt</a></div> -->
								<h4>&nbsp;</h4>
							</div>
						</div>
						<div class="large-4 columns ">
							@foreach($shoppingcart as $rows)
							<div class="row nopadding border-bottom">
								<div class="large-4 columns nopadding">
									<div class="item_photo"><img class="center_photo" src="../../itemphoto/php/files/medium/{{$rows->options->image}}"></div>
								</div>
								<div class="large-8 columns nopadding">
									<div class="item_name">{{$rows->name}}</div>
									<div class="item_specification">{{$rows->options->color.', '.$rows->options->size}}</div>
									<div class="item_qty">Qty: {{$rows->qty}}</div>
									<div class="item_amount">Amount: <span class="label">{{$rows->subtotal}} Ks</span></div>
								</div>
							</div>
							@endforeach
							<div class="row nopadding summary_item right">
								<div class="large-12 columns nopadding text-right total">The amount of goods: {{$grand_total}} Kyats</div>
								<div class="large-12 columns nopadding text-right total">You need to pay for the order: <span>{{$grand_total}}</span> Kyat</div>
							</div>
						</div>
					</div>
					
				</div>
				
				
			</div>
			
			<div class="row nopadding"><div class="large-12 column"><div class="footer_line"></div></div></div>
		</div>	

	</div>
	<div class="large-1 columns">&nbsp;</div>
</div>
<script type="text/javascript">	
	function increaseQty(qtyId){
		var qty = $('#qty'+qtyId).html();
		$('#qty'+qtyId).html(Number(qty) + 1);
	}

	function decreaseQty(qtyId){
		var qty = $('#qty'+qtyId).html();
		if(Number(qty) > 1)
			$('#qty'+qtyId).html(Number(qty) - 1);
	}

	function updateQty(event, item_id, color_id, size_id, qtyId){
		event.preventDefault();
		$.ajax({
			type: "POST",
			url: "/cart/"+item_id+"/"+color_id+"/"+size_id+"/"+$('#qty'+qtyId).html(),
			data: "",
		}).done(function( data ) {
		
		});

	}
	

	$(document).foundation({
	  orbit: {
	    animation: 'slide',
	    timer_speed: 1000,
	    pause_on_hover: true,
	    animation_speed: 500,
	    navigation_arrows: true,
	    bullets: true,
	    next_on_click: true
	  }
	});
</script>
@stop
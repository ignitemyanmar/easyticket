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
			<div class="large-12 columns"><h3>Shopping Cart</h3></div>
		</div>
		<div class="row nopadding header">
			<div class="large-1 columns">Photo</div>
			<div class="large-2 columns">Item Name</div>
			<div class="large-2 columns">Price(Ks)</div>
			<div class="large-2 columns">Qty</div>
			<div class="large-2 columns">Size & Color</div>
			<div class="large-1 columns">Amount</div>
			<div class="large-2 columns text-right">Action</div>

		</div>
		<div class="row nopadding"><div class="large-12 column"><div class="footer_line"></div></div></div>
		<div class="row nopadding">
			<?php 
				$grand_total = 0; 
				$i = 0;
			?>
			@foreach($shoppingcart as $rows)
			<div class="row cart_row">
				<div class="large-1 columns">
					<div class="cart_photo"><img class="center_photo" src="../../itemphoto/php/files/medium/{{$rows->options->image}}"></div>
				</div>
				<div class="large-2 columns">{{$rows->name}}</div>
				<div class="large-2 columns">{{$rows->price}}</div>
				<div class="large-2 columns">
					<div class="large-12 columns nopadding qty"><label class="mimus" onclick="decreaseQty('{{$rows->id.$i}}')"> - </label> <span id="qty{{$rows->id.$i}}">{{$rows->qty}}</span> <label class="plus" onclick="increaseQty('{{$rows->id.$i}}')">+</label></div>
				</div>
				<div class="large-2 columns">{{$rows->options->color .', '.$rows->options->size}}</div>
				<div class="large-1 columns">{{$rows->subtotal}}</div>
				<div class="large-2 columns text-right"><a href="" class="btn update" onclick="updateQty(event,'{{$rows->id}}','{{$rows->options->color_id}}','{{$rows->options->size_id}}','{{$rows->id.$i}}')">Update</a> &nbsp; <a href="../cart/{{$rows->id}}/{{$rows->options->color_id}}/{{$rows->options->size_id}}/delete" class="btn delete">Delete</a></div>
				<div class="row nopadding"><div class="large-12 column"><div class="footer_line"></div></div></div>
				<?php 
					$grand_total += $rows->subtotal; 
					$i++;
				?>
			</div>
			@endforeach
			<div class="row">
				<div class="large-9 columns text-right"><h5>Grand Total:</h5></div>
				<div class="large-3 columns"><h5>{{$grand_total}} Kyat</h5></div>
			</div>
			<div class="row">
				<div class="large-12 columns text-right"><a href="../delivery?@if(Auth::check())access_token={{Auth::user()->access_token}}@endif" @if(!Auth::check()) data-reveal-id="login_register" @endif class="button placeorder" style="padding:10px 2px;">Place Order</a></div>
			</div>
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
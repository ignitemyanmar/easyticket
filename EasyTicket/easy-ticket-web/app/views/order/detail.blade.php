@extends('../admin')
@section('content')
    {{HTML::style('../../../css/hover/component.css')}}
    {{HTML::style('../../../css/hover/default.css')}}

	<!-- BEGIN PAGE -->
		<div class="page-content">
			<!-- BEGIN PAGE CONTAINER-->
			<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->
					<div class="row-fluid">
						<div class="span12">
							<!-- BEGIN STYLE CUSTOMIZER -->
							<div class="color-panel hidden-phone">
								<div class="color-mode-icons icon-color"></div>
								<div class="color-mode-icons icon-color-close"></div>
								<div class="color-mode">
									<p>THEME COLOR</p>
									<ul class="inline">
										<li class="color-black current color-default" data-style="default"></li>
										<li class="color-blue" data-style="blue"></li>
										<li class="color-brown" data-style="brown"></li>
										<li class="color-purple" data-style="purple"></li>
										<li class="color-white color-light" data-style="light"></li>
									</ul>
									<label class="hidden-phone">
									<input type="checkbox" class="header" checked value="" />
									<span class="color-mode-label">Fixed Header</span>
									</label>							
								</div>
							</div>
							<!-- END BEGIN STYLE CUSTOMIZER -->   	
							<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
							<h3 class="page-title">
								Dashboard				
								<small></small>
							</h3>
							<ul class="breadcrumb">
								<li>
									<i class="icon-home"></i>
									<a href="/">Home</a> 
									<i class="icon-angle-right"></i>
								</li>
								<li><a href="/dashboard">Dashboard</a></li>
								
							</ul>
							<!-- END PAGE TITLE & BREADCRUMB-->
						</div>
					</div>
				<!-- END PAGE HEADER-->
				<div id="dashboard">
				<!-- BEGIN DASHBOARD STATS -->
				<div class="row-fluid invoice">
					<div class="row-fluid invoice-logo">
						<div class="span6 invoice-logo-space"><img src="../../skins/logo.png" alt=""> </div>
						<div class="span6">
							<p>#{{$order->ref_id}} / {{$order->created_at}} <span class="muted">Consectetuer adipiscing elit</span></p>
						</div>
					</div>
					<hr>
					<div class="row-fluid">
						<div class="span4">
							<h4>Customer Information:</h4>
							<ul class="unstyled">
								<li><b>Name:</b> {{$order->user->name}}</li>
								<li><b>Email:</b> {{$order->user->email}}</li>
								<li><b>Phone:</b> {{$order->user->phone}}</li>
							</ul>
						</div>
						<div class="span4">
							<h4>Shipping Information:</h4>
							<ul class="unstyled">
								<li><b>Receive Name:</b> {{$order->shipping->name}}</li>
								<li><b>Address:</b> {{$order->shipping->address}}</li>
								<li><b>Township:</b> {{$order->shipping->township}}</li>
								<li><b>City:</b> {{$order->shipping->city}}</li>
								<li><b>Phone:</b> {{$order->shipping->phone}}</li>
							</ul>
						</div>
						<div class="span4 invoice-payment">
							<h4>Payment Details:</h4>
							<ul class="unstyled">
								@if($order->payment != null)
								<li><strong>Payment Type: </strong> {{$order->payment->payment_type}}</li>
								<li><strong>Description: </strong> {{$order->payment->description}}</li>
								<li><strong>Amount: </strong> {{$order->payment->amount}}</li>
								<li><strong>Pay Date: </strong> {{$order->payment->created_at}}</li>
								@endif
							</ul>
						</div>
					</div>
					<div class="row-fluid">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Item</th>
									<th class="hidden-480">Description</th>
									<th class="hidden-480">Quantity</th>
									<th class="hidden-480">Price</th>
									<th class="hidden-480">Discount(%)</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<?php 
									$i = 1; 
									$sub_total = 0;
									$discount_amount = 0;
								?>
								@foreach($order->orderdetail as $rows)
								<tr>
									<td>{{$i}}</td>
									<td>{{$rows->item_name}}</td>
									<td class="hidden-480">{{$rows->color}}, {{$rows->size}}</td>
									<td class="hidden-480">{{$rows->quantity}}</td>
									<td class="hidden-480">{{$rows->price}}</td>
									<td class="hidden-480">{{$rows->discount}}</td>
									<td>{{$rows->quantity * $rows->price}}</td>
								</tr>
								<?php 
									$i++; 
									$sub_total += $rows->quantity * $rows->price;
									$discount_amount += ($rows->price * ($rows->discount / 100)) * $rows->quantity;
								?>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="row-fluid">
						<div class="span4">
							<!-- <div class="well">
								<address>
									<strong>Loop, Inc.</strong><br>
									795 Park Ave, Suite 120<br>
									San Francisco, CA 94107<br>
									<abbr title="Phone">P:</abbr> <span class="skype_c2c_print_container notranslate">(234) 145-1810</span><span id="skype_c2c_container" class="skype_c2c_container notranslate" dir="ltr" tabindex="-1" onmouseover="SkypeClick2Call.MenuInjectionHandler.showMenu(this, event)" onmouseout="SkypeClick2Call.MenuInjectionHandler.hideMenu(this, event)" onclick="SkypeClick2Call.MenuInjectionHandler.makeCall(this, event)" skype_menu_props="{&quot;numberToCall&quot;:&quot;+12341451810&quot;,&quot;isFreecall&quot;:false,&quot;isMobile&quot;:false,&quot;isRtl&quot;:false}"><span class="skype_c2c_highlighting_inactive_common" dir="ltr" skypeaction="skype_dropdown"><span class="skype_c2c_textarea_span" id="non_free_num_ui"><img class="skype_c2c_logo_img" src="resource://skype_ff_extension-at-jetpack/skype_ff_extension/data/call_skype_logo.png" height="0" width="0"><span class="skype_c2c_text_span">(234) 145-1810</span><span class="skype_c2c_free_text_span"></span></span></span></span>
								</address>
								<address>
									<strong>Full Name</strong><br>
									<a href="mailto:#">first.last@email.com</a>
								</address>
							</div> -->
						</div>
						<div class="span8 invoice-block">
							<ul class="unstyled">
								<li><strong>Sub - Total amount:</strong> {{$sub_total}}</li>
								<li><strong>Discount:</strong> {{$discount_amount}}</li>
								<li><strong>VAT:</strong> -----</li>
								<li><strong>Grand Total:</strong>{{$sub_total - $discount_amount}}</li>
							</ul>
							<br>
							<a href="/order" class="btn green big" style="padding: 6px 22px;height: 50px;">Submit Your Invoice <i class="m-icon-big-swapright m-icon-white" style="margin-top: 4px;"></i></a>
						</div>
					</div>
				</div>
					
					<!-- END DASHBOARD STATS -->
					
				</div>
			</div>
			<!-- END PAGE CONTAINER-->		
		</div>
		<!-- END PAGE -->	
  <script src="assets/js/jquery.blockui.js"></script>
  {{HTML::script('../js/hover/modernizr.custom.js')}}
  {{HTML::script('../js/hover/toucheffects.js')}}
@stop
@extends('master')
@section('content')
<style type="text/css">
	.btn1.green:hover, .btn.green:focus, .btn.green:active, .btn.green.active, .btn.green.disabled, .btn.green[disabled] {
	    background-color: #1D943B !important;
	    color: #FFF !important;
	    /*padding:1em;*/
	}
	.btn1 {
    background-color: #FF951D;
    background-image: none;
    filter: none;
    border: 0px none;
    box-shadow: none;
    padding: 7px 14px;
    text-shadow: none;
    font-family: "Segoe UI",Helvetica,Arial,sans-serif;
    font-size: 14px;
    color: #eee;
    cursor: pointer;
    outline: medium none;
    float: right;
    margin-left: 1em;
    border-radius: 0px !important;
	}
	#warning{border:1px solid #C09853;color: #333;
				padding: 9px 9px;
				margin-bottom: 13px;
				background: rgba(236, 210, 166, .1);}
</style>
	<div class="clear">&nbsp;</div>
		<div class="row" style="min-height:480px;">
			<div class="large-8 columns">
				<h3 class="title">Shopping Bag</h3>
				<form action="/checkout" method="post" class="pannel" style="padding:0;">
					<table style="width:100%">
						<thead>
							<tr>
								<th>Operator</th>
								<th>BusNo</th>
								<th>Trip</th>
								<th>Time</th>
								<th>SeatNo</th>
								<th>Price</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if($response)
								<?php $total=0; ?>
								@foreach($response as $tickets)
									<input type="hidden" name="busoccurance_id[]" value="{{$tickets['busoccurance_id']}}">
									<input type="hidden" name="sale_order_no" value="{{$tickets['sale_order_no']}}">
									<input type="hidden" name="seat_no[]" value="{{$tickets['seat_no']}}">
									<input type="hidden" name="agent_id" value="{{$tickets['agent_id']}}">
									<tr>
										<td>{{$tickets['operator_id']}}</td>
										<td>{{$tickets['bus_no']}}</td>
										<td>{{$tickets['from_to']}}</td>
										<td>{{$tickets['time']}}</td>
										<td>{{$tickets['seat_no']}}</td>
										<td>{{$tickets['price']}}</td>
										<td><a href="/sale/{{$tickets['id']}}/delete" class="remove">Remove</a></td>
									</tr>
									<?php $total +=$tickets['price']; ?>
								@endforeach
								<tr>
									<td colspan="6" class="text-right">Grand Total:</td>
									<td>{{ $total }}</td>
								</tr>
							@endif
							
						</tbody>
					</table>

					<table style="width:100%">
						<thead>
							<tr>
								<th>Customer Information</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<div class="row">
										<div class="large-3 columns">Ticket No</div>
										<div class="large-5 columns">
											<input type="text" name="ticket_no" required>
										</div>
										<div class="large-4 columns">&nbsp;</div>
									</div>	
									<div class="row">
										<div class="large-3 columns">Customer Name</div>
										<div class="large-5 columns">
											<input type="text" name="buyer_name" required>
										</div>
										<div class="large-4 columns">&nbsp;</div>
									</div>
									<div class="row">
										<div class="large-3 columns">NRC</div>
										<div class="large-5 columns">
											<input type="text" name="nrc" required>
										</div>
										<div class="large-4 columns">&nbsp;</div>
									</div>
									<div class="row">
										<div class="large-3 columns">Address</div>
										<div class="large-5 columns">
											<input type="text" name="address" required>
										</div>
										<div class="large-4 columns">&nbsp;</div>
									</div>
									<div class="row">
										<div class="large-3 columns">Phone</div>
										<div class="large-5 columns">
											<input type="text" name="phone" required>
										</div>
										<div class="large-4 columns">&nbsp;</div>
									</div>
									<div class="row">
										<div class="large-3 columns">Agent</div>
										<div class="large-5 columns">
											<select name="agent_id">
												<option value="1">City Mart (Myaynigone)</option>
												<option value="2">Lanmadaw City Mart</option>
												<option value="4">Hledan ABC</option>
											</select>
										</div>
										<div class="large-4 columns">&nbsp;</div>
									</div>
									<div class="row">
										<div class="large-8 columns">
											<input type="submit" class="btn1 green right" value="Comfirm Order" >
										</div>
										<div class="large-4 columns">&nbsp;</div>
									</div>		
								</td>
							</tr>
							
						</tbody>
					</table>
				</form>
			</div>
			<div class="large-4 columns" id='results'>&nbsp;</div>
		</div>
	<script type="text/javascript">
		$(function(){
			$('.remove').click(function(e){
				e.preventDefault();
				var link=$(this).attr('href');
				$.ajax({
					type:'POST',
					url:link,
					data:{}
				}).done(function(result){
					if(result=='Have been deleted.'){
						$(this).parent().parent().remove();
					}
				});
			});
		});
	</script>
@stop
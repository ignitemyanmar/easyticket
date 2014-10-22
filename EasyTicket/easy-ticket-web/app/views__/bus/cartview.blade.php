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
			<!-- <div class="large-8 columns"> -->
				<form action="/checkout" method="post" class="pannel" style="padding:0;">
					<div class="large-6 columns">
						<h3 class="title">Customer Information</h3>
						<table style="width:100%">
							<!-- <thead>
								<tr>
									<th>Customer Information</th>
								</tr>
							</thead> -->
							<tbody>
								<tr>
									<td>
										@for($i=1; $i<=count($response); $i++)
										<div class="row">
											<div class="large-3 columns">Ticket No {{$i}}</div>
											<div class="large-9 columns">
												<input type="text" name="ticket_no[]" required>
											</div>
										</div>	
										@endfor
										<div class="row">
											<div class="large-3 columns">Customer Name</div>
											<div class="large-9 columns">
												<input type="text" name="buyer_name" required>
											</div>
										</div>
										<div class="row">
											<div class="large-3 columns">NRC</div>
											<div class="large-9 columns">
												<input type="text" name="nrc">
											</div>
										</div>
										<div class="row" style="display:none;">
											<div class="large-3 columns">Address</div>
											<div class="large-9 columns">
												<input type="text" name="address">
											</div>
										</div>
										<div class="row">
											<div class="large-3 columns">Phone</div>
											<div class="large-9 columns">
												<input type="text" name="phone" required>
											</div>
										</div>
										<div class="row">
											<div class="large-3 columns">Agent</div>
											<div class="large-9 columns">
												<select name="agent_id" id="agent">
													@foreach($agents as $row)
														<option value="{{$row->id}}">{{$row->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
										<br>
										<div class="row">
											<div class="large-12 columns">
												<input type="submit" class="btn1 right" value="Comfirm Order" >
											</div>
										</div>		
									</td>
								</tr>
								
							</tbody>
						</table>
					</div>
					<div class="large-6 columns">
						<h3 class="title">Ticket List</h3>
						<table style="width:100%">
							<thead>
								<tr>
									<th>Operator</th>
									<th>BusNo</th>
									<th>Trip</th>
									<th>Time</th>
									<th>SeatNo</th>
									<th>Price</th>
									<!-- <th>Action</th> -->
								</tr>
							</thead>
							<tbody>
								@if($response)
									<?php $total=0; ?>
									@foreach($response as $tickets)
										<input type="hidden" name="busoccurance_id[]" value="{{$tickets['busoccurance_id']}}">
										<input type="hidden" name="sale_order_no" value="{{$tickets['sale_order_no']}}">
										<input type="hidden" name="seat_no[]" value="{{$tickets['seat_no']}}">
										<input type="hidden" name="agent_id1" value="{{$tickets['agent_id']}}">
										<tr>
											<td>{{$tickets['operator']}}</td>
											<td>{{$tickets['bus_no']}}</td>
											<td>{{$tickets['from_to']}}</td>
											<td>{{$tickets['time']}}</td>
											<td>{{$tickets['seat_no']}}</td>
											<td>{{$tickets['price']}} KS</td>
											<!-- <td>
												<a href="/sales/{{$tickets['id']}}/delete" class="remove">Remove</a>
											</td> -->
										</tr>
										<?php $total +=$tickets['price']; ?>
									@endforeach
									<tr>
										<td colspan="4" class="text-right"><b>Grand Total:</b></td>
										<td colspan="2" class="text-right"><span style="color:red;">{{ $total }}</span> KS</td>
									</tr>
								@endif
								
							</tbody>
						</table>
					</div>
				</form>
			<!-- </div> -->
			<!-- <div class="large-4 columns" id='results'>&nbsp;</div> -->
		</div>
	<script type="text/javascript">
		$(function(){
			$("#agent").select2();
			$('.remove').click(function(e){
				$(this).parent().parent().remove();
				e.preventDefault();
				var link=$(this).attr('href');
				$.ajax({
					type:'POST',
					url:link,
					data:{}
				}).done(function(result){
					if(result=='Have been deleted.'){
					}
				});
			});

			/*if (window.history && window.history.pushState) {
			    $(window).on('popstate', function() {
			      var hashLocation = location.hash;
			      var hashSplit = hashLocation.split("#!/");
			      var hashName = hashSplit[1];

			      if (hashName !== '') {
			        var hash = window.location.hash;
			        if (hash === '') {
			          alert('Back button was pressed.');
			          // return 1;
			        }
			      }
			    });

			    // window.history.pushState('forward', null, './#forward');
			    window.history.pushState('#', null, '#');
			}*/

		});
	</script>
@stop
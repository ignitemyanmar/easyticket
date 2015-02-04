@extends('master')
@section('content')
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
<style type="text/css">
	.select2-container{min-width: 160px;max-width: 325px;}
	.button{background: none repeat scroll 0% 0% #D6D6D6;border-radius: 0px;border: 2px solid #AAA;}
	.button:hover{background: #222; color: #fff;}
		.message{
			color: #468847;
			background-color: #DFF0D8;
			border-color: #D6E9C6;
			padding:12px 24px;margin-bottom: 24px;width: auto;
		}
</style>
<div class="clear">&nbsp;</div>
<!-- <div class="large-12 columns"> -->
			<div class="row slideframe">
				<div class="callbacks_container">
				    <ul class="rslides" id="slider0">
				        <li>
				          <img src="bannerphoto/bus.jpg" alt="">
				        </li>
				    </ul>
			    </div>
			</div>
		<!-- </div> -->
		
		<div class="row">
			<div class="large-1 columns">
				&nbsp;
			</div>
			<div class="large-10 columns">
				<!-- <div class="row slideframe" style="padding:10px 1px;">
					<div class="callbacks_container">
					    <ul class="rslidescs" id="slider0">
					        <li>
					          <img src="bannerphoto/bus.jpg" alt="">
					        </li>
					    </ul>
				    </div>
				</div>	 -->

				<br>
				@if(Session::has('message'))
					<div class="message">Successfully your booking tickets.<span style="position:absolute; right:28px; top:26px;cursor:pointer;color:#999;" id="hide">X</span></div>
				@endif
				<form action="/triplist" method="get" class="pannel" style="padding:0;">
					<div class="row" style="background:#eee;padding-top:12px;padding-top: 12px;border: 2px solid #CECECE;">
						<div class="large-3 columns">
							<b>Operator</b><br>
							<select name="operator" id="operator" >
								<!-- <option value="">All</option> -->
								@if($response['operators'])
									@foreach($response['operators'] as $operator)
										@if($operator['name']=='Mandalar Min')
										<option value="{{$operator['id']}}">{{$operator['name']}}</option>
										@endif
									@endforeach
								@endif
							</select>
						</div>

						<div class="large-3 columns">
							<b>From</b><br>
							<select name="from" id="from" >
								@if($response['from_cities'])
									@foreach($response['from_cities'] as $city)
										<option value="{{$city['id']}}">{{$city['name']}}</option>
									@endforeach
								@endif
							</select>
						</div>

						<div class="large-3 columns">
							<b>To</b><br>
							<select name="to" id="to" >
								@if($response['to_cities'])
									@foreach($response['to_cities'] as $city)
										<option value="{{$city['id']}}">{{$city['name']}}</option>
									@endforeach
								@endif
							</select>
						</div>

						<div class="large-3 columns">
							<b>Date  </b><br>
							<input type="text" id="StartDate" style="float:right; width:100%; float:left;" name="departure_date">
						</div>
						<div class="large-12 columns right">
							<input type="submit" class="button small right" value="Search">
						</div>
					</div>
					<!-- <div class="row"> -->
						
					<!-- </div> -->
				</form>
				@if($buslist && count($buslist)>0)
					<div class="row">
						<?php $i=1;?>
					@foreach($buslist as $rows)
						<?php 
							$operator_id=$rows['operator_id'];
							$from=$rows['seat_plan'][0]['from'];
							$to=$rows['seat_plan'][0]['to'];
							$date=$rows['seat_plan'][0]['departure_date'];
							$time=$rows['seat_plan'][0]['departure_time'];
							$bus_no=$rows['seat_plan'][0]['bus_no'];
						?>
					<div class="large-6 columns">
						<div class="row listframe" style="padding:0;">
								<div class="large-4 columns listimg">
									<img src="img/bus.png">
								</div>
								<div class="large-8 columns listdesc">
									<p><b>Bus Line :</b> {{$rows['operator']}}</p>
									<p><b>Trip :</b>{{$rows['trip']}}</p>
									<p><b>Date :</b>{{$rows['seat_plan'][0]['departure_date'] }}</p>
									
									<div class="row">
										<div class="large-9 columns" style="padding-left:0;">
											<b>Time -</b>
											<a href="/bus_seat_choose?operator_id={{$operator_id}}&from_city={{$from}}&to_city={{$to}}&date={{$date}}&time={{$time}}&bus_no={{$bus_no}}"><span>{{$rows['seat_plan'][0]['departure_time']}}</span></a>
										</div>
									</div>
									
									<a href="/bus_seat_choose?operator_id={{$operator_id}}&from_city={{$from}}&to_city={{$to}}&date={{$date}}&time={{$time}}&bus_no={{$bus_no}}" class="showticket">View Tickets</a>
								</div>
						</div><br>
					</div>
						@if($i%2==0)
							<div class="clear">&nbsp;</div>
						@endif
						<?php $i++; ?>
					@endforeach
					</div>
				@endif
				<br>
				<br><br><br>
			</div>
			<div class="large-1 columns" id='results'>&nbsp;</div>
		</div>
	<script type="text/javascript" src="../js/vendor/jquery-ui.js"></script>
	<script type="text/javascript">
	$(function(){
		var date = new Date();
					var m = date.getMonth(), d = date.getDate(), y = date.getFullYear();
					$("#StartDate").datepicker({
						minDate: new Date(y, m, d),
						numberOfMonth: 2,
						onSelect: function(dateStr) {
								var min = $(this).datepicker('getDate');
								/*$('#checkout').datepicker('option', 'minDate', min || '2');
								Default.utils.checkinoutdate();*/
						},
						dateFormat: 'd-M-yy'
					});
		$("#operator").select2();
		$("#from").select2();
		$("#to").select2();

		$('#hide').click(function(){
			$(this).parent().hide();
		})
	});
	</script>
@stop
@extends('master')
@section('content')
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
<style type="text/css">
	.select2-container{min-width: 160px;}
</style>
<!-- <div class="large-12 columns"> -->
			<div class="row slideframe">
				<div class="callbacks_container">
				    <ul class="rslides" id="slider0">
				        <li>
				          <img src="bannerphoto/bus.jpg" alt="">
				        </li>
				        
				        <li>
				          <img src="bannerphoto/movie.jpg" alt="">
				        </li>
				        
				    </ul>
			    </div>
			</div>
		<!-- </div> -->
		
		<div class="row">
			<div class="large-2 columns">
				&nbsp;
			</div>
			<div class="large-8 columns">
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
				<form action="/triplist" method="get" class="pannel" style="padding:0;">
					<div class="row">
						<div class="large-3 columns">
							<b>Operator</b>
							<select name="operator" id="operator" >
								<option value="">All</option>
								@if($response['operators'])
									@foreach($response['operators'] as $operator)
										<option value="{{$operator['id']}}">{{$operator['name']}}</option>
									@endforeach
								@endif
							</select>
						</div>

						<div class="large-3 columns">
							<b>From</b>
							<select name="from" id="from" >
								@if($response['cities'])
									@foreach($response['cities'] as $city)
										<option value="{{$city['id']}}">{{$city['name']}}</option>
									@endforeach
								@endif
							</select>
						</div>

						<div class="large-3 columns">
							<b>To</b>
							<select name="to" id="to" >
								@if($response['cities'])
									@foreach($response['cities'] as $city)
										<option value="{{$city['id']}}">{{$city['name']}}</option>
									@endforeach
								@endif
							</select>
						</div>

						<div class="large-3 columns">
							<b>Date  </b><br>
							<input type="text" id="StartDate" style="float:right; width:100%; float:left;" name="departure_date">
						</div>
					</div>
					<div class="row">
						<div class="large-12 columns right">
							<input type="submit" class="button small right" value="Search">
						</div>
					</div>
				</form>
				@if($buslist && count($buslist)>0)
					@foreach($buslist as $rows)
						<?php 
							$operator_id=$rows['operator_id'];
							$from=$rows['seat_plan'][0]['from'];
							$to=$rows['seat_plan'][0]['to'];
							$date=$rows['seat_plan'][0]['departure_date'];
							$time=$rows['seat_plan'][0]['departure_time'];
							$bus_no=$rows['seat_plan'][0]['bus_no'];
						?>
						<div class="row listframe">
								<div class="large-3 columns listimg">
									<img src="bannerphoto/bus.jpg">
								</div>
								<div class="large-9 columns listdesc">
									<p><span>Operator :</span> {{$rows['operator']}}</p>
									<p><span>Trip :</span>{{$rows['trip']}}</p>
									<p><span>Date :</span>{{$rows['seat_plan'][0]['departure_date'] }}</p>
									<b>Time </b><br>
									<div class="row">
										<div class="large-3 columns">
											<a href="/bus_seat_choose?operator_id={{$operator_id}}&from_city={{$from}}&to_city={{$to}}&date={{$date}}&time={{$time}}&bus_no={{$bus_no}}"><span>{{$rows['seat_plan'][0]['departure_time']}}</span></a>
										</div>
									</div>
									
									<a href="/bus_seat_choose?operator_id={{$operator_id}}&from_city={{$from}}&to_city={{$to}}&date={{$date}}&time={{$time}}&bus_no={{$bus_no}}" class="showticket">View Tickets</a>
								</div>
						</div><br>
					@endforeach
				@endif
				
				<br>
				<br><br><br>

				<!-- <div class="row content">
					<h3 class="hdtitle">Releated Bus for this trip</h3>
			        <div class="orbit-container"> 
			            <ul class="example-orbit-content" data-orbit> 
				            <li> 
				                <div class="row">
				               		<div class="large-3 columns left related">
									<div class="imgframe">
										<img src="bannerphoto/bus.jpg">
									</div>
									<h4 class="title">Yangon to Bagan</h4>
										<p class="unicodefont" style="font-size:19px;"></p>
									<div class="detail">
										<a href="">Detail</a>
									</div>
									</div>
									<div class="large-3 columns left related">
										<div class="imgframe">
											<img src="bannerphoto/bus.jpg">
										</div>
										<h4 class="title">Yangon to Inle</h4>
											<p class="unicodefont" style="font-size:19px;"></p>
										<div class="detail">
											<a href="">Detail</a>
										</div>
									</div>
									<div class="large-3 columns left related">
										<div class="imgframe">
											<img src="bannerphoto/bus.jpg">
										</div>
										<h4 class="title">Yangon to Mandalay</h4>
											<p class="unicodefont" style="font-size:19px;"></p>
										<div class="detail">
											<a href="">Detail</a>
										</div>
									</div>
									<div class="large-3 columns left related">
										<div class="imgframe">
											<img src="bannerphoto/ticket3.jpg">
										</div>
										<h4 class="title">Yangon to Bagan</h4>
											<p class="unicodefont" style="font-size:19px;"></p>
										<div class="detail">
											<a href="">Detail</a>
										</div>
									</div>


				              	</div>
				            </li> 
				            <li> 
				                <div class="row">
				               		<div class="large-3 columns left related">
									<div class="imgframe">
										<img src="bannerphoto/bus.jpg">
									</div>
									<h4 class="title">Yangon to Bagan</h4>
										<p class="unicodefont" style="font-size:19px;"></p>
									<div class="detail">
										<a href="">Detail</a>
									</div>
									</div>
									<div class="large-3 columns left related">
										<div class="imgframe">
											<img src="bannerphoto/showsm.jpg">
										</div>
										<h4 class="title">Yangon to Inle</h4>
											<p class="unicodefont" style="font-size:19px;"></p>
										<div class="detail">
											<a href="">Detail</a>
										</div>
									</div>
									<div class="large-3 columns left related">
										<div class="imgframe">
											<img src="bannerphoto/bus.jpg">
										</div>
										<h4 class="title">Yangon to Mandalay</h4>
											<p class="unicodefont" style="font-size:19px;"></p>
										<div class="detail">
											<a href="">Detail</a>
										</div>
									</div>
									<div class="large-3 columns left related">
										<div class="imgframe">
											<img src="bannerphoto/ticket3.jpg">
										</div>
										<h4 class="title">Yangon to Bagan</h4>
											<p class="unicodefont" style="font-size:19px;"></p>
										<div class="detail">
											<a href="">Detail</a>
										</div>
									</div>


				              	</div>
				            </li>  
			            </ul>
			        </div>
			    </div> -->

				<!-- <div class="large-4 columns"><img src="bannerphoto/showsm.jpg"></div> -->
			</div>
			<div class="large-2 columns" id='results'>&nbsp;</div>
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
	});
	</script>
@stop
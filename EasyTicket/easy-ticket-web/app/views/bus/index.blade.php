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
							<b>Date : </b>
							<input type="text" name='departure_date' id="StartDate" style="float:right; width:70%; float:right;">
						</div>
					</div>
					<div class="row">
						<div class="large-12 columns right">
							<input type="submit" class="button small right" value="Search">
						</div>
					</div>
				</form>
				<div class="row listframe">
						<div class="large-3 columns listimg">
							<img src="bannerphoto/bus.jpg">
						</div>
						<div class="large-9 columns listdesc">
							<p><span>Bus Name </span> Mandalar Min /JJ</p>
							<p><span>From - To</span>Yangon - Mandalay</p>
							<p><span>Date</span> 20.2.2014 </p>
							<b>Time </b><br>
							<div class="row">
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									&nbsp;
								</div>
							</div>
							<br>
							<div class="row">
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									&nbsp;
								</div>
							</div>
							
							<a href="choosebusseat.html" class="showticket">View Tickets</a>
						</div>
				</div><br>
				<div class="row listframe">
						<div class="large-3 columns listimg">
							<img src="bannerphoto/bus.jpg">
						</div>
						<div class="large-9 columns listdesc">
							<p><span>Bus Name </span> Mandalar Min /JJ 2</p>
							
							<p><span>From - To</span>Yangon - Mandalay</p>
							<p><span>Date</span> 20.2.2014 </p>
							<br>
							<b>Time </b><br>
							<div class="row">
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									&nbsp;
								</div>
							</div>
							<br>
							<div class="row">
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									&nbsp;
								</div>
							</div>
							
							<a href="choosebusseat.html" class="showticket">View Tickets</a>
						</div>
				</div><br>
				<div class="row listframe">
						<div class="large-3 columns listimg">
							<img src="bannerphoto/bus.jpg">
						</div>
						<div class="large-9 columns listdesc">
							<p><span>Bus Name </span> Mandalar Min /JJ 3</p>
							
							<p><span>From - To</span>Yangon - Mandalay</p>
							<p><span>Date</span> 20.2.2014 </p>
							<b>Time </b><br>
							<div class="row">
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									&nbsp;
								</div>
							</div>
							<br>
							<div class="row">
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									&nbsp;
								</div>
							</div>
							
							
							<a href="choosebusseat.html" class="showticket">View Tickets</a>
						</div>
				</div><br>
				<div class="row listframe">
						<div class="large-3 columns listimg">
							<img src="bannerphoto/bus.jpg">
						</div>
						<div class="large-9 columns listdesc">
							<p><span>Bus Name </span>Shwe Min Thar</p>
							
							<p><span>From - To</span>Yangon - Mandalay</p>
							<p><span>Date</span> 20.2.2014 </p>
							<br>
							<b>Time </b><br>
							<div class="row">
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									&nbsp;
								</div>
							</div>
							<br>
							<div class="row">
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									&nbsp;
								</div>
							</div>
							
							<a href="choosebusseat.html" class="showticket">View Tickets</a>
						</div>
				</div><br>
				<div class="row listframe">
						<div class="large-3 columns listimg">
							<img src="bannerphoto/bus.jpg">
						</div>
						<div class="large-9 columns listdesc">
							<p><span>Bus Name </span> Mandalar Min /JJ</p>
							
							<p><span>From - To</span>Yangon - Mandalay</p>
							<p><span>Date</span> 20.2.2014 </p>
							<br>
							<b>Time </b><br>
							<div class="row">
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									&nbsp;
								</div>
							</div>
							<br>
							<div class="row">
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									&nbsp;
								</div>
							</div>
							
							<a href="choosebusseat.html" class="showticket">View Tickets</a>
						</div>
				</div><br>
				<div class="row listframe">
						<div class="large-3 columns listimg">
							<img src="bannerphoto/bus.jpg">
						</div>
						<div class="large-9 columns listdesc">
							<p><span>Bus Name </span>Shwe Min Thar</p>
							
							<p><span>From - To</span>Yangon - Mandalay</p>
							<p><span>Date</span> 20.2.2014 </p>
							<br>
							<b>Time </b><br>
							<div class="row">
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									&nbsp;
								</div>
							</div>
							<br>
							<div class="row">
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									<a href="choosebusseat.html"><span>2:30 pm</span></a>
								</div>
								<div class="large-3 columns">
									&nbsp;
								</div>
							</div>
							
							<a href="choosebusseat.html" class="showticket">View Tickets</a>
						</div>
				</div><br>
				<br><br><br>

				<div class="row co
				ntent">
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
			    </div>

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
						},
						dateFormat: 'd-M-yy'
					});
		$("#operator").select2();
		$("#from").select2();
		$("#to").select2();
	});
	</script>
@stop
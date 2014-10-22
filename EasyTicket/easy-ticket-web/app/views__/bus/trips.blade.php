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
		
		<form action="/triplist" method="get" class="pannel" style="padding:0;">
			<div class="row">
				<div class="large-8 columns">&nbsp;</div>
				<div class="large-2 columns">
					<div class="right">
					<b>Filter By Operator</b>
					<select name="operator" id="operator" >
						@if($operators)
							@foreach($operators as $operator)
								<option value="{{$operator['id']}}">{{$operator['name']}}</option>
							@endforeach
						@endif
					</select>
					</div>
				</div>
				<div class="large-2 columns">&nbsp;</div>
			</div>
		</div>
		<div class="row">
			<div class="large-2 columns">
				&nbsp;
			</div>
			<div class="large-8 columns">
				@if($response)
				<div class="row">
					@foreach($response as $trip)
						<div class="large-3 columns">
							<div class="listframe">
								{{$trip['from']}} - {{ $trip['to']}}
							</div>
						</div>
					@endforeach
				</div><br>
				@endif

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
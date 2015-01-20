@extends('master')
@section('content')
<?php $current_url=Request::url(); ?>
<style type="text/css">
	h3{line-height: 2.7rem; margin-right: 4px;}
	h3.orange{border-bottom: 3px solid #FF9811; }
	.nopadding{padding: 0; padding-right: 4px;}
	/*.morning-bg, .morning-bg .row{background: #fff;margin-bottom:0px;}*/
	.stripe-morning{border-left: 4px solid rgba(31, 249, 203,.8);}
	.stripe-evening{border-left: 4px solid rgba(21, 179, 185,.8);}
	.tripframe-morning{background: #0AD2E6 !important; font-family: "Helvetica Light";padding: 0;}
	.tripframe-evening{background: #22409A !important; font-family: "Helvetica Light";}
	
	.small-12{margin-bottom: 0;}
	.centerdiv{position:absolute;top:-100%;bottom:-100%;left:0; right:0; margin:auto;}
	.time-frame{font-size: 45px; color: #fff;}
	.departure-info{position:relative;height:150px; padding-top:2.7rem;}
	.class-label{text-align:center;font-weight:bold;padding-top:1.6rem;font-family: "Dosis" !important; color: #fff; }
	
	.class-seat{font-size:24px;line-height:1rem;color:#292031;}
	.light-blue{color:#01AEBF; border-bottom: 3px solid #01AEBF;}
	.blue{color:#01AEBF; border-bottom: 3px solid #01AEBF;}
	.white{color:#fff;}
	.lightblue{color:#D6FFFF;}

	
	.clear{clear:both; height: 1.7rem;}
	.trips{color: white; text-align: center; font-size: 24px;position: relative;top:40%; bottom:40%; left: 0; right: 0; margin: auto;}
	a:hover{text-decoration: none;}
	hr{margin-bottom:0;padding-bottom:0;border-top:1px dashed #000;}
	.title_heading{width:50%;padding-left:1rem;border-bottom: 45px solid rgba(52,78,161, 1);border-right: 40px solid transparent;position:relative;}
	.morning_hd{border-bottom: 45px solid #0AD2E6}
	.title_heading span{color:#fff;font-size:24px;position:absolute;top:.6rem;}
	.panel{padding:.2rem;border:2px solid #ddd;}
	@media only screen and (max-width: 40em){
		.small-12{margin-bottom: 2px;}
		.departure_frame{margin-bottom: 0px;}
	}
	table tr:nth-of-type(2n) {
	    background: none repeat scroll 0% 0% #EFEFEF;
	}
	table thead tr:nth-of-type(2n){
		background: #fff;
	}
</style>
	<div style="padding:1rem;">
		<div class="clear">&nbsp;&nbsp;</div>
		<h3 class="light-blue">မနက္</h3>
		@if($response)
			<!-- <div class="departure_frame"> -->
			@if(count($response['morning'])>0)
				@foreach($response['morning'] as $key=>$trip)
					<?php $i=1;?>
					@if($trip)
						<div class="clear">&nbsp;&nbsp;</div>
						<h3 class="title_heading morning_hd"><span>{{$key}}</span></h3>
							<div class="panel">
							@foreach($trip as $row)
								@if($i%3==1)
								<div class="row departure_frame">
								@endif
									<div class="large-4 medium-4 small-12 columns nopadding stripe-morning left">
										<?php 
											$link="operator_id=".$response['operator_id']."&from_city=".$response['from']."&to_city=".$response['to']."&date=".$response['date']."&time=".$row['time']."&class_id=".$row['class_id']."&bus_no=-";
										?>
										<a href="/bus_seat_choose?{{$link}}">
											<div class="row tripframe-morning">
												<div class="large-6 medium-6 small-6 columns">
													<div class="time-frame">
														<div class="class-label">{{substr($row['time'],0,5)}} <br>{{substr($row['time'],5)}}</div>
													</div>
												</div>
												<div class="large-6 medium-6 small-6 columns nopadding">
													<div class="departure-info">
														<div class="class-seat">
														{{$row['bus_class']}} <br><br>
														Seats : {{$row['total_sold_seat'].'/'.$row['total_seat']}}
														</div>
													</div>
													<!-- {{$i}} -->
												</div>
											</div>
										</a>
									</div>
								@if($i%3==0 || $i==count($trip))
								</div>
								@endif
								<?php $i++;?>
							@endforeach
							</div>
						<hr>
					@endif
				@endforeach
			@endif
			<!-- </div> -->
		@endif
		
		<div class="clear">&nbsp;&nbsp;</div>
		<h3 class="blue">ညေန</h3>
		@if($response)
			@if(count($response['evening'])>0)
				@foreach($response['evening'] as $key=>$trip)
					<?php $i=1;?>
					@if($trip)
						<div class="clear">&nbsp;&nbsp;</div>
						<h3 class="title_heading"><span>{{$key}}</span></h3>
						<div class="panel">
							@foreach($trip as $row)
								@if($i%3==1)
								<div class="row departure_frame">
								@endif
									<div class="large-4 medium-4 small-12 columns nopadding stripe-evening left">
										<?php 
											$link="operator_id=".$response['operator_id']."&from_city=".$response['from']."&to_city=".$response['to']."&date=".$response['date']."&time=".$row['time']."&class_id=".$row['class_id']."&bus_no=-";
										?>
										<a href="/bus_seat_choose?{{$link}}">
											<div class="row tripframe-evening">
												<div class="large-6 medium-6 small-6 columns">
													<div class="time-frame">
														<div class="class-label">{{$row['time']}}</div>
													</div>
												</div>
												<div class="large-6 medium-6 small-6 columns">
													<div class="departure-info">
														<div class="class-seat lightblue">
														{{$row['bus_class']}} <br><br>
														Seats : {{$row['total_sold_seat'].'/'.$row['total_seat']}}
														</div>
													</div>
													<!-- {{$i}} -->
												</div>
											</div>
										</a>
									</div>
								@if($i%3==0 || $i==count($trip))
								</div>
								@endif
								<?php $i++;?>
							@endforeach
						</div>
						<hr>

					@endif
				@endforeach
			@endif
		@endif

	</div>
	
    <script type="text/javascript">
	    $(function(){
	    	$('.trips').each(function(){
		    	var objelement=$(this);
				$(this).click(function(){
					from_to_id	= $(this).data('id');
					$('#from_to_id').val(from_to_id);
					$('#StartDate').focus();
				});
			}); 

			var date = new Date();
					// var m = date.getMonth(), d = date.getDate(), y = date.getFullYear();
					$("#StartDate").datepicker({
						// minDate: new Date(y, m, d),
						numberOfMonth: 2,
						onSelect: function(dateStr) {
								var min = $(this).datepicker('getDate');
								/*$('#checkout').datepicker('option', 'minDate', min || '2');
								Default.utils.checkinoutdate();*/
						},
						dateFormat: 'yy-mm-dd'
					});
	    });
    </script>
	
@stop
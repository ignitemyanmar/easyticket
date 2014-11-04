@extends('master')
@section('content')
<?php $current_url=Request::url(); ?>
<style type="text/css">
	
	h3{border-bottom: 3px solid #7DD783; line-height: 2.7rem; margin-right: 4px;}
	h3.orange{border-bottom: 3px solid #FF9811; }
	.nopadding{padding: 0; padding-right: 4px;}
	/*.morning-bg, .morning-bg .row{background: #fff;margin-bottom:0px;}*/
	.departure_frame .large-6{border-left: 4px solid #349A10;}
	.small-12{margin-bottom: 0;}
	.centerdiv{position:absolute;top:-100%;bottom:-100%;left:0; right:0; margin:auto;}
	.time-frame{background:#fff; border-radius:50%; height:140px;position:relative;margin:4px 0; border:4px solid #349A10;}
	.departure-info{position:relative;height:150px; padding-top:2.7rem;}
	.class-label{text-align:center;font-weight:bold;padding-top:3.6rem;}
	.orange{color:#FF9811;}
	.green{color:#7DD783;}
	.white{color:#fff;}
	.class-seat{font-size:24px;line-height:1rem;}

	.tripframe-morning{background: #F6B867 !important; font-family: "Helvetica Light";padding: 0;}
	.tripframe-evening{background: #54BC30 !important; font-family: "Helvetica Light";}
	.clear{clear:both; height: 1.7rem;}
	.trips{color: white; text-align: center; font-size: 24px;position: relative;top:40%; bottom:40%; left: 0; right: 0; margin: auto;}
	a:hover{text-decoration: none;}
	
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
	div.calendar {
        /*max-width: 240px;*/
        margin-left: auto;
        margin-right: auto;
      }
      div.calendar table {
        width: 100%;
      }
      div.dateField {
        width: 140px;
        padding: 6px;
        -webkit-border-radius: 6px;
        -moz-border-radius: 6px;
        color: #555;
        background-color: white;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
      }
      div#popupDateField:hover {
        background-color: #cde;
        cursor: pointer;
      }
      #calen_darviews{min-height: 450px;}
      #ui-datepicker-div{width: 40%;}

</style>
	<div style="padding:1rem;">
	<div class="clear">&nbsp;&nbsp;</div>
	<h3 class="orange">မနက္</h3>
	<?php $i=1;?>
	@if($response)
		<!-- <div class="departure_frame"> -->
		@foreach($response['morning'] as $trip)
			@if($i%2==1)
			<div class="row departure_frame">
			@endif
				<div class="large-6 medium-6 small-12 columns nopadding">
					<?php 
						$link="operator_id=".$response['operator_id']."&from_city=".$response['from']."&to_city=".$response['to']."&date=".$response['date']."&time=".$trip['time']."&bus_no=-";
					?>
					<a href="/bus_seat_choose?{{$link}}">
						<div class="row tripframe-morning">
							<div class="large-4 medium-4 small-4 columns">
								<div class="time-frame">
									<div class="class-label green">{{$trip['time']}}</div>
								</div>
							</div>
							<div class="large-8 medium-8 small-8 columns">
								<div class="departure-info">
									<div class="class-seat">
									Class : {{$trip['bus_class']}} <br><br>
									Seats : {{$trip['total_sold_seat'].'/'.$trip['total_seat']}}
									</div>
								</div>
								<!-- {{$i}} -->
							</div>
						</div>
					</a>
				</div>
			@if($i%2==0 || $i==count($response['morning']))
			</div>
			<!-- <div class="clear">&nbsp;&nbsp;</div> -->
			@endif
			<?php $i++;?>
		@endforeach
		<!-- </div> -->
	@endif
	
	<div class="clear">&nbsp;&nbsp;</div>
	<h3 class="green">ညေန</h3>
	<?php $i=1;?>
	@if($response)
		@foreach($response['evening'] as $trip)
			@if($i%2==1)
			<div class="row departure_frame">
			@endif
				<div class="large-6 medium-6 small-12 columns nopadding">
					<?php 
						$link="operator_id=".$response['operator_id']."&from_city=".$response['from']."&to_city=".$response['to']."&date=".$response['date']."&time=".$trip['time']."&bus_no=-";
					?>
					<a href="/bus_seat_choose?{{$link}}">
						<div class="row tripframe-evening">
							<div class="large-4 medium-4 small-4 columns">
								<div class="time-frame">
									<div class="class-label green">{{$trip['time']}}</div>
								</div>
							</div>
							<div class="large-8 medium-8 small-8 columns">
								<div class="departure-info">
									<div class="class-seat white">
									Class : {{$trip['bus_class']}} <br><br>
									Seats : {{$trip['total_sold_seat'].'/'.$trip['total_seat']}}
									</div>
								</div>
								<!-- {{$i}} -->
							</div>
						</div>
					</a>
				</div>
			@if($i%2==0 || $i==count($response['evening']))
			</div>
			@endif
			<?php $i++;?>
		@endforeach
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
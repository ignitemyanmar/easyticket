@extends('master')
@section('content')
{{HTML::style('../../ion-calendar/css/ion.calendar.css')}}

{{HTML::script('../../ion-calendar/js/moment.min.js')}}
{{HTML::script('../../ion-calendar/js/ion.calendar.min.js')}}

<style type="text/css">
	.ic__header {height: 49px;}
	.ic__days td, .ic__week-head td {font-size: 19px;}
	.ic__prev div,.ic__next div {top: 19px;}
	.ic__days .ic__day_state_selected, .ic__days .ic__day:hover{background: #FF6302;}
	.ic__days .ic__day_state_current{color:#FF6302;}
	.ic__header select{height: 35px !important;padding-top: 0;}

	.tripframe{background: #9A6610; height: 150px;position: relative;}
	.clear{clear:both;height: 0;}
	.trips{color: white; text-align: center; font-size: 29px;position: absolute;top:38%; bottom:40%; left: 0; right: 0; margin: auto; font-family: "Zawgyi-One";}
	a:hover{text-decoration: none;}
	

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

    .stripe-morning, .stripe-go{border-left: 4px solid rgba(31, 249, 203,.8);}
	.stripe-evening, .stripe-return{border-left: 4px solid rgba(21, 179, 185,.8);}
	.tripframe-morning, .tripframe-go{background: #0AD2E6 !important; font-family: "Helvetica Light";padding: 0;}
	.tripframe-evening, .tripframe-return{background: #22409A !important; font-family: "Helvetica Light";}
	.black{color: #444;}
	.btn_cyan{background: #0AD2E6; border-radius: 0;}
	.nopadding {
	    padding: 0px 8px 0px 0px;
	}
	.clear{clear: both; margin-bottom: 1.5rem;}
	.small-12{margin-bottom: 0;}
	.trip_frame{margin-bottom: 8px !important;}

	@media only screen and (max-width: 40em){
		.small-12{margin-bottom: 2px;}
		.trip_frame{margin-bottom: 2px;}
	}
</style>
<div style="padding:1rem;">
	<div class="clear">&nbsp;&nbsp;</div>
	<?php $i=1;?>
	@if($response['go'])
		@foreach($response['go'] as $trip)
			@if($i%2==1)
			<div class="row trip_frame">
			@endif
				<div class="large-6 medium-6 small-12 columns nopadding">
					<div class="tripframe tripframe-go stripe-go">
						<a href="#" data-reveal-id="calen_darviews"><div class="trips black" data-id="{{$trip['from_id'].','.$trip['to_id'] }}">  {{$trip['from'].'=>'.$trip['to']}}</div></a>
					</div>
				</div>
			@if($i%2==0)
			</div>
			@endif
			<?php $i++;?>
		@endforeach
	@endif

	<div class="clear">&nbsp;&nbsp;</div>
	<?php $i=1;?>
	@if($response['return'])
		@foreach($response['return'] as $trip)
			@if($i%2==1)
			<div class="row trip_frame">
			@endif
				<div class="large-6 medium-6 small-12 columns nopadding">
					<div class="tripframe tripframe-return stripe-return">
						<a href="#" data-reveal-id="calen_darviews"><div class="trips" data-id="{{$trip['from_id'].','.$trip['to_id'] }}">  {{$trip['from'].'=>'.$trip['to']}}</div></a>
					</div>
				</div>
			@if($i%2==0)
			</div>
			<!-- <div class="clear">&nbsp;&nbsp;</div> -->
			@endif
			<?php $i++;?>
		@endforeach
	@endif
	<?php $today=date('Y-m-d'); ?>
	<div id="calen_darviews" class="reveal-modal medium" data-reveal> 
		<form action="/departure-times" method="get">
			<h2>ခရီးသြားမည့္ ေန႕ေရြးရန္</h2> 
			<input type="hidden" name="access_token" value="{{Auth::user()->access_token}}">
			<input type="hidden" name="trip" id="from_to_id">
			<div class="myCalendar" id="calendar_view"></div>
			<input type="hidden" id="departure_date" name="departure_date" value="{{$today}}">
			<input type="hidden" id="today" value="{{$today}}">
			<div id="departure_date"></div><br>
			<input type="submit" class="button btn_cyan" value="ကားထြက္ခြာမည့္ အခ်ိန္ၾကည့္ရန္">
		</form>
		<a class="close-reveal-modal">&#215;</a>
	</div>
</div>
    <script type="text/javascript">
	    $(function(){
	    	var StartYear=new Date().getFullYear()-5;
	    	var NextYear=new Date().getFullYear()+1;
	    	$("#calendar_view").ionCalendar({
			    lang: "en",
			    years: StartYear+"-"+NextYear,
			    onClick: function(date){
			    	var $date1= date.substr(0,10);
			    	$("#departure_date").val($date1);
			    }
			});

	    	$('.trips').each(function(){
		    	var objelement=$(this);
				$(this).click(function(){
					from_to_id	= $(this).data('id');
					$('#from_to_id').val(from_to_id);
					$('#StartDate').focus();
				});
			}); 
	    });
    </script>
@stop
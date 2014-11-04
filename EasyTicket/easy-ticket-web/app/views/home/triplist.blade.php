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
	/*select{ padding-top: 0; }*/
	.ic__header select{height: 35px !important;padding-top: 0;}

	.tripframe{background: #9A6610; height: 150px;position: relative;}
	.clear{clear:both; height: 1.7rem;}
	.trips{color: white; text-align: center; font-size: 24px;position: absolute;top:40%; bottom:40%; left: 0; right: 0; margin: auto;}
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
</style>
<div class="clear">&nbsp;&nbsp;</div>
	<?php $i=1;?>
	@if($response)
		@foreach($response as $trip)
			@if($i%2==1)
			<div class="row">
			@endif
				<div class="large-6 medium-6 small-12 columns">
					<div class="tripframe">
						<a href="#" data-reveal-id="calen_darviews"><div class="trips" data-id="{{$trip['from_id'].','.$trip['to_id'] }}">  {{$trip['from'].'=>'.$trip['to']}}</div></a>
					</div>
				</div>
			@if($i%2==0)
			</div>
			<div class="clear">&nbsp;&nbsp;</div>
			@endif
			<?php $i++;?>
		@endforeach
	@endif
	<?php $today=date('Y-m-d'); ?>
	<div id="calen_darviews" class="reveal-modal medium" data-reveal> 
		<form action="/departure-times" method="get">
			<h2>ခရီးသြားမည့္ ေန႕ေရြးရန္</h2> 
			<input type="hidden" name="trip" id="from_to_id">
			<div class="myCalendar" id="calendar_view"></div>
			<input type="hidden" id="departure_date" name="departure_date" value="{{$today}}">
			<input type="hidden" id="today" value="{{$today}}">
			<div id="departure_date"></div><br>
			<input type="submit" class="button" value="ကားထြက္ခြာမည့္ အခ်ိန္ၾကည့္ရန္">
		</form>
		<a class="close-reveal-modal">&#215;</a>
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
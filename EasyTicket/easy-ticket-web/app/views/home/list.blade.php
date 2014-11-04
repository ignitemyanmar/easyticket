@extends('master')
@section('content')
	<style type="text/css">
	.tripframe{background: #d75717; height: 150px;position: relative;}
	.clear{clear:both; height: 1.7rem;}
	.trips{color: white; text-align: center; font-size: 24px;position: relative;top:40%; bottom:40%; left: 0; right: 0; margin: auto;}
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
	</style>
<link rel="stylesheet" type="text/css" href="../css/calendarview.css">

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
	<div id="calen_darviews" class="reveal-modal medium" data-reveal> 
		<h2>ခရီးသြားမည့္ ေန႕ေရြးရန္</h2> 
		<input type="hidden" name="trip" id="from_to_id">
		<!-- <input type="text" id="StartDate" style="float:right; width:100%; float:left;" name="departure_date"> -->
		<!-- <div style="float: left; width: 50%">
	      <div style="height: 400px; background-color: #efefef; padding: 10px; -webkit-border-radius: 12px; -moz-border-radius: 12px; margin-right: 10px">
	        <div id="embeddedExample" style="">
	          <div id="embeddedCalendar" style="margin-left: auto; margin-right: auto">
	          </div>
	          <br />
	          <div id="embeddedDateField" class="dateField">
	            Select Date
	          </div>
	          <br />
	        </div>
	      </div>
	    </div> -->
		<a class="close-reveal-modal">&#215;</a>

	</div>
	<div style="float: left; width: 50%">
      <div style="height: 400px; background-color: #efefef; padding: 10px; -webkit-border-radius: 12px; -moz-border-radius: 12px; margin-right: 10px">
        <div id="embeddedExample" style="">
          <div id="embeddedCalendar" style="margin-left: auto; margin-right: auto">
          </div>
          <br />
          <div id="embeddedDateField" class="dateField">
            Select Date
          </div>
          <br />
        </div>
      </div>
    </div>
	
	
	{{HTML::script('../../js/calendarview.js')}} 
    
	<script type="text/javascript">
		$(function() {
		     $('.trips').each(function(){
		    	var objelement=$(this);
				$(this).click(function(){
					from_to_id	= $(this).data('id');
					$('#from_to_id').val(from_to_id);
					var select_day=$('#from_to_id').val;
					if(select_day==)
					
				});

			});
		});
		
	</script>
@stop
@extends('master')
@section('content')
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
	<style type="text/css">
	.tripframe{background: #d75717; height: 150px;position: relative;}
	.clear{clear:both; height: 1.7rem;}
	.trips{color: white; text-align: center; font-size: 24px;position: relative;top:40%; bottom:40%; left: 0; right: 0; margin: auto;}
	a:hover{text-decoration: none;}
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
						<a href="#" data-reveal-id="calendarview"><div class="trips" data-id="{{$trip['from_id'].','.$trip['to_id'] }}">  {{$trip['from'].'=>'.$trip['to']}}</div></a>
					</div>
				</div>
			@if($i%2==0)
			</div>
			<div class="clear">&nbsp;&nbsp;</div>
			@endif
			<?php $i++;?>
		@endforeach
	@endif
	<div id="calendarview" class="reveal-modal medium" data-reveal> 
		<h2>ခရီးသြားမည့္ ေန႕ေရြးရန္</h2> 
		<input type="text" name="trip" id="from_to_id">
		<input type="text" id="StartDate" style="float:right; width:100%; float:left;" name="departure_date">
		<a class="close-reveal-modal">&#215;</a>

	</div>

	<script type="text/javascript">
	$(function(){
		$('.trips').each(function(){
			$(this).click(function(){
				from_to_id	= $(this).data('id');
				// alert(from_to_id);
				$('#from_to_id').val(from_to_id);
			});
			$('#StartDate').focus();
		})

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
	});
	</script>
@stop
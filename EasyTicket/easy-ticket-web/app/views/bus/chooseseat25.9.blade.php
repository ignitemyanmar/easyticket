@extends('master')
@section('content')
    {{HTML::style('../../css/Tixato_files/seating_builder.css')}}
	<div class="large-12 columns">
		<br>
		<h3 style="background:none; color:#000;margin-left:14px;">Choose your Seats</h3>
	</div>
	<div style="clear:both">&nbsp;</div>
	<div class="large-12 columns">
	<form action ="/ticket/order" method= "post">
		<div id="container">
			<div class="large-8 columns">
			    <div id="seating-map-wrapper">
				    <div id="seating-map-creator">
				    	<div class="row">
				      	 	<div class="check-a">
						        @if($response['seat_list'])
									<input type="hidden" value="{{$response['bus_id']}}" name='busoccurance_id' id='busoccurance_id'>
									<input type="hidden" value="{{$response['from']}}" name='from' id="from">
									<input type="hidden" value="{{$response['to']}}" name='to' id="to">
									<input type="hidden" value="{{$response['operator_id']}}" name='operatorid' id="operator_id">
									<input type="hidden" value="{{$response['operator_id']}}" name='agent_id' id="agent_id">
									<input type="hidden" value="{{$response['departure_date']}}" name='departure_date' id="departure_date">
									<input type="hidden" value="{{$response['departure_time']}}" name='departure_time' id="departure_time">
									<!-- <input type="hidden" value="WR8INtJJetDDd8pCDrpIEx7pCxMx6P1OxOoBDQqT" name='access_token' id="access_token"> -->
						        	<?php $k=1;  $columns=$response['column'];?>
						        	@foreach($response['seat_list'] as $rows)
							        	@if($k%$columns == 1)
							        	<div class="large-2 small-2 columns">&nbsp;</div>
						      	 		@endif
						      	 		<div class="large-2 small-2  columns">
									        @if($rows['status']==0)
									        	<div class="large-2 small-2 columns">&nbsp;</div>
									        @else
									        	<div class="checkboxframe">
										            <label>
										                <span></span>
										                <?php 
										                	if($rows['status'] != 1){
										                		$disabled="disabled";
										                		$taken="taken";
										                	}else{
										                		$disabled=''; 
										                 		$taken='available';	
										                	}
										                 ?>
										                <input class="radios" type="checkbox" multiple="multiple" value="{{$rows['seat_no']}}" name="tickets" {{ $disabled }}>
										                <div class="fit-a {{$taken}}" title="{{$rows['seat_no'].'('. $rows['price'].' K)'}}" id="{{$rows['seat_no']}}">{{$rows['seat_no']}}</div>
										            	<input type="hidden" value="{{$rows['price']}}" class="price{{$rows['seat_no']}}">
										            	<input type="hidden" value="{{$rows['seat_no']}}" class="seatno{{$rows['seat_no']}}">
										            </label>
										        </div>
									        @endif
									        
							        	</div>
							        	@if($k%$columns==0)
							        		<div class="large-2 small-2 columns">&nbsp;</div>
									   		<div style="clear:both;">&nbsp;</div>
									    @endif
					      	 			<?php $k++;?>
								    @endforeach
								@endif
						    </div>
					    </div>
				    </div>

				    <div id="unsupported_error_message" style="display:none">
				      <div class="loading_message">
				          <h3 class="error">
				              Sorry, this browser does not support the seating map.
				          </h3>
				          <p>
				          The seating map editor uses the SVG support built into modern
				          internet browsers, and thus is only supported in Chrome, Safari,
				          Firefox, Opera, and Internet Explorer 9 or better.
				          </p>
				          <p>
				          Check here: <a href="http://caniuse.com/svg">http://caniuse.com/svg</a>
				          to see if the seating chart will be useable on your or your
				          patrons' browsers.
				          </p>
				      </div>
				    </div>
				</div>
			</div>

			<div class="large-4 columns">
					<div class="large-12 columns">
						<img src="bannerphoto/bussm.jpg">
					</div>
					<div style="clear:both">&nbsp;</div>
					<div class="large-12 columns">
						<h3 class="hdtitle" style="padding:0px;"><a>Elite JJ</a></h3>
						<p><b>Yangon Mandalay Trip</b></p>
						<p>Sat: Feb 22, 2014 <b>(7:00 pm)</b></p>
					</div>
					<div class="large-12 columns">
						<p>Yangon Mandaly trip long time is 6hours. This bus is one stop at something township.</p>
					</div>
					<div style="border-bottom:4px dotted #333;">&nbsp;</div>
			</div>
		</div>
		<div class="clear">&nbsp;</div>
		
		<br>
		<div class="large-8 columns chooseticket">
			<h3 style="margin-left:2rem;">Selected Seats</h3>
			<table class="table table-striped table-hover" style="width:80%; margin:4%;">
				<thead>
					<tr>
						<th class="hidden-480">Seat-No</th>
						<th class="hidden-480">Price</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody class="selectedseats">
					
				</tbody>
			</table>
			<div class="large-10 columns">  

				<div id="warning" style="display:none;"></div>	
			    <input type="button" class="btn1 green" value="Order" style="border:1px solid #ccc;" id='order'> 
			    <input type="hidden" value="0" id='total' style="text-align:right">
			    <span class="title right">Sub Total: <span id='totalamount'>0</span> K </span>
			</div>
		</div>
    </form>
    </div>

	<div class="large-4 columns">&nbsp;</div>
    {{HTML::script('../js/chooseseats.js')}}
    <script>
	$('#order').click(function(){
				var busid=$('#busoccurance_id').val();
				var operator_id=$('#operator_id').val();
				var agent_id=$('#agent_id').val();
				var from=$('#from').val();
				var to=$('#to').val();
				var departure_date=$('#departure_date').val();
				var departure_time=$('#departure_time').val();
				var access_token=$('#access_token').val();
				var selected = new Array();
		              $("input:checkbox[name=tickets]:checked").each(function() {
		                   var seat_no=$(this).val();
		                   var pararray={'busoccurance_id':busid, 'seat_no':seat_no};
		                   selected.push(pararray);
		              });
		              if(selected.length ==0){
		                return false;
		              }
		        var stringparameter=JSON.stringify(selected);
		        // alert(stringparameter);
		        $.ajax({
		        	type:'POST',
		        	url:'/saleticket',
		        	data:{operator_id:operator_id,agent_id:agent_id,seat_list:stringparameter,from_city:from,to_city:to,date:departure_date,time:departure_time,access_token:access_token}
		        }).done(function(result){
		        	if(result.can_buy=='false'){
		        		$('#warning').html(result.message);
		        		document.getElementById('warning').style.display='block';
		        	}else{
		        		window.location.href='/cartview/'+result.sale_order_no;
		        	}
		        });    
			});
</script>
@stop

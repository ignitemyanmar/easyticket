@extends('master')
@section('content')
    {{HTML::style('../../css/Tixato_files/seating_builder.css')}}
	<style type="text/css">
	    .remove{cursor: pointer;background-color: rgba(0,0,0,.5);opacity: .6;color:white;padding:4px 8px;}
	    .remove:hover{opacity: 1;}
	    #totalamount{color:red; font-size: 19px;}
	    #warning{color: #B94A48;
		background-color: #F2DEDE;
		border-color: #EED3D7;}

		.colorbox{width:24px; height:24px;float:left;margin-right:8px;}
		.operator_0{background:#00FF00;}
		.operator_1{background:lightseagreen;}
	   	.operator_2{background:#2047CE;}
	   	.operator_3{background:#A520CE;}
	   	.operator_4{background:#93CE20;}
	   	.operator_5{background:#073963;}
	   	.operator_6{background:#206307;}
	   	.booking{background: #FFAB05;}

	   	.select2-container {max-width: 270px;}
	    /*.seat_frame{overflow-y: scroll;
			overflow-x: hidden;
			max-height: 600px;border:1px solid #eee;}*/
	</style>
	<div class="large-12 columns">
		<br>
		<h3 class="hdtitle" style="padding:9px;width:66%;">Choose your Seats</h3>
		<!-- <h3 style="background:none; color:#000;margin-left:14px;">Choose your Seats</h3> -->
	</div>
	<div style="clear:both">&nbsp;</div>
	<div class="large-12 columns">
	<form action ="/ticket/order" method= "post">
		<div id="container">
			<div class="large-8 columns seat_frame">

			    <div id="seating-map-wrapper">
				    <div id="seating-map-creator">
				    	<div class="row">
				      	 	<div class="check-a">
						        @if($response['seat_list'])
						        	<?php $current_url=Route::getCurrentRoute()->getPath(); $k=1;  $columns=$response['column'];?>

									<input type="hidden" value="{{$current_url}}" id='current_url'>
									<input type="hidden" value="{{$response['bus_id']}}" name='busoccurance_id' id='busoccurance_id'>
									<input type="hidden" value="{{$response['from_city']}}" name='from' id="from">
									<input type="hidden" value="{{$response['to_city']}}" name='to' id="to">
									<input type="hidden" value="{{$response['operator_id']}}" name='operatorid' id="operator_id">
									<input type="hidden" value="{{$response['departure_date']}}" name='departure_date' id="departure_date">
									<input type="hidden" value="{{$response['departure_time']}}" name='departure_time' id="departure_time">
									<!-- <input type="hidden" value="WR8INtJJetDDd8pCDrpIEx7pCxMx6P1OxOoBDQqT" name='access_token' id="access_token"> -->
						        	@foreach($response['seat_list'] as $rows)
							        	@if($k%$columns == 1)
							        	<div class="large-1 small-1 columns">&nbsp;</div>
						      	 		@endif
						      	 		<div class="large-2 small-2  columns">
									        @if($rows['status']==0)
									        	<div class="large-2 small-2 columns">&nbsp;</div>
									        @else
									        	<div class="checkboxframe">
										            <label>
										                <span></span>
										                <?php 
										                	$booking='';
										                	if($rows['status'] != 1){
										                		$disabled="disabled";
										                		$taken="taken";
										                		if($rows['status']==3){
										                			$taken="booking";
										                			$booking=" Booking";
										                		}
										                	}elseif($rows['operatorgroup_id']!=0){
										                		$color=OperatorGroup::whereid($rows['operatorgroup_id'])->pluck('color');
	                                                             $disabled=''; 
	                                                             $taken="operator_".$color.' operatorseat_'.$color;
	                                                        }
                                                            else{
										                		$disabled=''; 
										                 		$taken='available';	
										                	}
										                 ?>
										            	<?php $seatNO_id=str_replace('.', '-', $rows['seat_no']); ?>

										                <input class="radios" type="checkbox" multiple="multiple" value="{{$rows['seat_no']}}" name="tickets" {{ $disabled }}>
										                <div class="fit-a {{$taken}}" title="{{$rows['seat_no'].'('. $rows['price'].' K)'.$booking}}" id="{{$seatNO_id}}">{{$rows['seat_no']}}</div>
										            	<input type="hidden" value="{{$rows['price']}}" class="price{{$seatNO_id}}">
										            	<input type="hidden" value="{{$rows['seat_no']}}" class="seatno{{$seatNO_id}}">
										            </label>
										        </div>
									        @endif
									        
							        	</div>
							        	@if($k%$columns==0)
							        		<div class="large-3 small-2 columns">&nbsp;</div>
									   		<div style="clear:both;">&nbsp;</div>
									    @endif
					      	 			<?php $k++;?>
								    @endforeach
								@endif
						    </div>
					    </div>
				    </div>

				</div>
				
			</div>

			<div class="large-4 columns">
					<!-- <div class="large-12 columns">
						<img src="bannerphoto/bussm.jpg">
					</div> -->
					<div style="clear:both">&nbsp;</div>
					<div class="large-12 columns">
						<h3 class="hdtitle" style="padding:0px;"><a>{{$response['operator']}}</a></h3>
						<p><b>{{$response['from'].'-'.$response['to']}}</b></p>
						<p>{{$response['departure_date']}} ({{$response['departure_time']}})</b></p>
					</div>
					<div class="clear">&nbsp;</div>
					<div class="large-12 columns">
						<h3 class="hdtitle" style="padding:0px;">Selected Seats</h3>
						<!-- <h3 style="">Selected Seats</h3> -->
						<table class="table table-striped table-hover" style="width:100%;" cellspacing="0">
							<thead style="background:#eee;">
								<tr>
									<th  width="40%">Seat-No</th>
									<th  width="40%">Price</th>
									<th width="20%">Action</th>
								</tr>
							</thead>
							<tbody class="selectedseats" style="border:1px solid #eee;">
								
							</tbody>
						</table>
							
						<div class="row">
							<div class="large-12 columns nopadding">
								<label><input type="checkbox" value="1" name="booking" id='booking'><b> Booking တင္ရန္</b></label>
							</div>
						</div>

						<div class="row" style="display:none;" id="agents">
							<div class="large-12 columns nopadding"><b>အေရာင္းကုိယ္စားလွယ္ ေရြးရန္</b></div>
							<div class="clear">&nbsp;</div>
							<div class="large-12 columns nopadding">
								<select name="agent_id" id="agent_id">
									@foreach($agents as $row)
										<option value="{{$row->id}}">{{$row->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="clear">&nbsp;</div>


						<div class="large-12 columns nopadding">  
							<div id="warning" style="display:none;"></div>
						    <input type="button" class="btn1" value="Order" style="border:1px solid #ccc;padding:7px 24px;" id='order'> 

						    <input type="hidden" value="0" id='total' style="text-align:right">
						    <div><br><b>Sub Total: </b><span id='totalamount'>0</span> KS </div>
						</div>
						<div style="border-bottom:4px dotted #333;">&nbsp;</div>
						<!-- <p>Yangon Mandaly trip long time is 6hours. This bus is one stop at something township.</p> -->
						<br>
						<a href="/bookinglist/{{$response['bus_id']}}" target="_blank" class="button btn1 small">ၾကိဳတင္မွာယူထားေသာ စာရင္းသုိ႕ =></a>
						
					</div>
					<div class="clear">&nbsp;</div>

					<div style="margin:29px 24px;">
	                   <h3 class="hdtitle" style="padding:4px 0;">အေရာင္ႏွင့္သက္ဆုိင္ေသာ အခ်က္အလက္မ်ား</h3><br>
	                   @if($operatorgroup)
	                      @foreach($operatorgroup as $operator)
	                         <div class="colorbox operator_{{$operator->color}}"></div>{{$operator->user->name}} (ခုံပုိင္)<br>
	                         <div class="clear">&nbsp;</div>
	                      @endforeach
	                   @endif
	                      <div class="colorbox taken"></div>ေရာင္းျပီးသားခုံမ်ား<br>
	                      <div class="clear">&nbsp;</div>
	                      <div class="colorbox booking"></div>ၾကိဳတင္မွာယူထားေသာ ခုံမ်ား<br>
	                      <div class="clear">&nbsp;</div>
	                      <div class="colorbox choose"></div>ေရြးခ်ယ္ထားေသာ ခုံမ်ား<br>
	                      <div class="clear">&nbsp;</div>
	                      <div class="colorbox available"></div>ခုံလြတ္မ်ား<br>
	                      <div class="clear">&nbsp;</div>
	                </div>
	                <br>
			</div>
		</div>
		<div class="clear">&nbsp;</div>
		
		<br>
		<div class="large-8 columns chooseticket">
			
		</div>
    </form>
    </div>

	<div class="large-4 columns">&nbsp;</div>
    {{HTML::script('../js/chooseseats.js')}}
    <script type="text/javascript">
    	$(function(){
			$("#agent_id").select2();
			checkbooking();

			$('#booking').click(function(){
				checkbooking();
			})
    	});

    	function checkbooking(){
    		var status=$("#booking").prop('checked');
			if(status==true){
				document.getElementById('agents').style.display="block";
			}else{
				document.getElementById('agents').style.display="none";
			}
    	}
		$('#order').click(function(){
				var busid=$('#busoccurance_id').val();
				var operator_id=$('#operator_id').val();
				var agent_id=$('#agent_id').val();
				// alert(agent_id);
				var from=$('#from').val();
				var to=$('#to').val();
				var departure_date=$('#departure_date').val();
				var departure_time=$('#departure_time').val();
				var access_token=$('#access_token').val();
				var booking=$('#booking:checked').val();
				if(booking==undefined){booking=0;}

				var current_url=$('#current_url').val();
				var selected = new Array();
		              $("input:checkbox[name=tickets]:checked").each(function() {
		                   var seat_no=$(this).val();
		                   // var rpl_seat_no=seat_no.replace('-',)
		                   var pararray={'busoccurance_id':busid, 'seat_no':seat_no};
		                   selected.push(pararray);
		              });
		              if(selected.length ==0){
		                return false;
		              }
		        var stringparameter=JSON.stringify(selected);
		        // console.log(operator_id+'0p'+agent_id+'ag'+from+'fc'+to+'to'+departure_date+'dt'+departure_time+'dt');
		        $.ajax({
		        	type:'POST',
		        	url:'/saletickets',
		        	data:{	operator_id:operator_id,
		        			agent_id:agent_id,
		        			seat_list:stringparameter,
		        			from_city:from,
		        			to_city:to,
		        			date:departure_date,
		        			time:departure_time,
		        			booking:booking,
		        			access_token:access_token}
		        }).done(function(result){
		        	console.log(result);
		        	if(result.can_buy==false){
		        		$('#warning').html(result.message);
		        		document.getElementById('warning').style.display='block';
		        		return false;
		        	}else{
		        		if(result.booking=="1"){
		        			var parameters="?operator_id="+operator_id+"&from_city="+from+"&to_city="+to+"&date="+departure_date+"&time="+departure_time+"&bus_no=-";
		        			window.location.href='/'+current_url+parameters;
		        			return false;	
		        		}
		        		window.location.href='/cartview/'+result.sale_order_no;
		        	}
		        });  

		});
</script>
@stop

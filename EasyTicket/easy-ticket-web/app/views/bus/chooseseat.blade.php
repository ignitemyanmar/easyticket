@extends('master')
@section('content')
    {{HTML::style('../../css/Tixato_files/seating_builder.css')}}
    {{HTML::style('../../bootstrap/css/bootstrap.alert.css')}}
	<style type="text/css">
	    .remove{cursor: pointer;background-color: rgba(0,0,0,.5);opacity: .6;color:white;padding:4px 8px;}
	    .remove:hover{opacity: 1;}
	    #totalamount{color:red; font-size: 19px;}
	    #warning{color: #B94A48;
		background-color: #F2DEDE;
		border-color: #EED3D7;}

		.colorbox{width:24px; height:24px;float:left;margin-right:8px;}
		.operator_0{background:#5586c8;} /*elite*/
		.operator_1{background:#FF8514;} /*gov*/
	   	.operator_2{background:#4BFFFF;} /*Aung Minglar*/
	   	.operator_3{background:#B08620;} /*Mindama*/
	   	.operator_4{background:#640F6D;} /*Aung San*/
	   	.operator_5{background:#073963;}
	   	.operator_6{background:#206307;}
	   	.booking{background:  #470203;}

	   	.select2-container {max-width: 270px;}
		.loading{
	      width:32px;
	      height:32px;
	      background: url('../../img/loader.gif') no-repeat;
	  	}
	  	.fit-a div{font-family: "Zawgyi-One" !important;}
	  	#booking{margin-bottom: 0 !important;}
	  	#agents{display:none;background:#eee;overflow:hidden;margin-top:0;padding-top:1rem;}
	  	body .ui-tooltip {
			font: bold 14px "Zawgyi-One", Sans-Serif;
		}
	</style>
	<div class="large-12 columns">
		<br>
		<h3 class="hdtitle" style="padding:9px;width:66%;">Choose your Seats</h3>
	</div>
	<div style="clear:both">&nbsp;</div>
	<form action ="/ticket/order" method= "post">
		<div class="large-8 columns nopadding">
	    	<div class="row">
	      	 	<div class="check-a">
			        @if($response['seat_list'])
			        	<?php $current_url=Route::getCurrentRoute()->getPath(); $k=1;  $columns=$response['column'];?>

						<input type="hidden" value="{{$current_url}}" id='current_url'>
						<input type="hidden" value="{{$response['bus_id']}}" name='busoccurance_id' id='busoccurance_id'>
						<input type="hidden" value="{{$response['class_id']}}" name='class_id' id='class_id'>
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
			      	 		<div class="large-2 small-2  columns nopadding">
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
							                <div class="fit-a {{$taken}}" title="{{$rows['seat_no'].'('. $rows['price'].' K)'.$booking}}" id="{{$seatNO_id}}">
							                	@if($rows['customer'])
							                		<div title="{{$rows['customer']['nrc'].'၊ '.$rows['agent']}}">
							                		<strong>{{$rows['customer']['name']}}</strong><br>
							                		{{$rows['customer']['phone']}}<br>
							                		{{$rows['customer']['nrc']}}<br>
							                		{{$rows['customer']['ticket_no']}}<br>
							                		&nbsp;{{$rows['agent']}}<br>
							                		</div>
							                	@endif
							                	<span style="position:absolute;right:0;top:0;padding:6px 16px; background:#000;color:#fff;">{{$rows['seat_no']}}</span>
							                </div>
							            	<input type="hidden" value="{{$rows['price']}}" class="price{{$seatNO_id}}">
							            	<input type="hidden" value="{{$rows['seat_no']}}" class="seatno{{$seatNO_id}}">
							            </label>
							        </div>
						        @endif
						        
				        	</div>
				        	@if($k%$columns==0)
				        		<div class="large-1 small-1 columns">&nbsp;</div>
						   		<div style="clear:both;">&nbsp;</div>
						    @endif
		      	 			<?php $k++;?>
					    @endforeach
					@endif
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
					<p>{{date('d/m/Y',strtotime($response['departure_date']))}} ({{$response['departure_time']}})</b></p>
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
						
					<div class="row" style="background:#4BFFFF;padding-top: .5rem;">
						<div class="large-12 columns">
							<label><input type="checkbox" value="1" name="booking" id='booking'><b> Booking တင္ရန္</b></label>
						</div>
					</div>
					<div class="row" id="agents">
						<div class="large-12 columns"><b>အေရာင္းကုိယ္စားလွယ္ ေရြးရန္</b></div>
						<div class="clear">&nbsp;</div>
						<div class="large-12 columns">
							<select name="agent_id" id="agent_id">
								@foreach($agents as $row)
									<option value="{{$row->id}}">{{$row->name}}</option>
								@endforeach
							</select>
						</div>

						<div class="clear">&nbsp;</div>
						<div class="large-12 columns"><label>ဝယ္သူအမည္</label><input type="text" name="customer_name" id="customer_name"></div>
						<div class="large-12 columns"><label>ဖုန္းနံပါတ္</label><input type="text" name="phone_no" id="phone_no"></div>
					</div>
					<div class="clear">&nbsp;</div>


					<div class="large-12 columns nopadding">  
						<div id="warning" style="display:none;"></div>
					    <div class="large-12 columns nopadding"><b style="color:#555;">Sub Total:</b> <span id='totalamount'>0</span> KS </div>
					    <div class="clear"></div>

					    <input type="button" class="button btn1" value="လက္မွတ္ မွာယူရန္" style="border:1px solid #ccc;padding:0.875rem 1.75rem 0.9375rem;" id='order'> 
					    <div class="indicator right">&nbsp;</div>
					    <input type="hidden" value="0" id='total' style="text-align:right">
					    
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
		<div class="clear">&nbsp;</div>
		<br>
    </form>

	<div class="large-4 columns">&nbsp;</div>
    {{HTML::script('../js/chooseseats.js')}}
    {{HTML::script('../bootstrap/js/bootstrap.alert.js')}}
    {{HTML::script('../js/jquery.bootstrap-growl.min.js')}}
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
    	var option_info = {
				  ele: 'body', // which element to append to
				  type: 'info', // (null, 'info', 'error', 'success')
				  offset: {from: 'top', amount: 20}, // 'top', or 'bottom'
				  align: 'right', // ('left', 'right', or 'center')
				  width: 250, // (integer, or 'auto')
				  delay: 6000,
				  allow_dismiss: true,
				  stackup_spacing: 10 // spacing between consecutively stacked growls.
				}
		var option_success = {
				  ele: 'body', // which element to append to
				  type: 'success', // (null, 'info', 'error', 'success')
				  offset: {from: 'top', amount: 20}, // 'top', or 'bottom'
				  align: 'right', // ('left', 'right', or 'center')
				  width: 250, // (integer, or 'auto')
				  delay: 6000,
				  allow_dismiss: true,
				  stackup_spacing: 10 // spacing between consecutively stacked growls.
				}
		$('#order').click(function(){
				$('.indicator').addClass('loading');
				var busid=$('#busoccurance_id').val();
				var operator_id=$('#operator_id').val();
				var agent_id=$('#agent_id').val();
				var class_id=$('#class_id').val();
				var from=$('#from').val();
				var to=$('#to').val();
				var departure_date=$('#departure_date').val();
				var departure_time=$('#departure_time').val();
				var customer_name=$('#customer_name').val();
				var phone_no=$('#phone_no').val();
				var access_token=$('#access_token').val();
				var booking=$('#booking:checked').val();

				if(booking==undefined){booking=0;}
				var current_url=$('#current_url').val();
				var selected = new Array();
				$("input:checkbox[name=tickets]:checked").each(function() {
				   var seat_no=$(this).val();
				   var pararray={'busoccurance_id':busid, 'seat_no':seat_no};
				   selected.push(pararray);
				});
				if(selected.length ==0){
					$.bootstrapGrowl("Please choose seat!.", option_info);
					return false;
				}
				
		        var stringparameter=JSON.stringify(selected);
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
		        			class_id:class_id,
		        			customer_name:customer_name,
		        			phone_no:phone_no,
		        			booking:booking,
		        			access_token:access_token}
		        }).done(function(result){
		        	
		        	$('.indicator').removeClass('loading');
		        	console.log(result);
		        	if(result.can_buy==false){
		        		$('#warning').html(result.message);
		        		$.bootstrapGrowl(result.message, option_info);
		        		document.getElementById('warning').style.display='block';
		        		var parameters="?operator_id="+operator_id+"&from_city="+from+"&to_city="+to+"&date="+departure_date+"&time="+departure_time+"&class_id="+class_id+"&bus_no=-";
		        		window.location.href='/'+current_url+parameters;
		        		return false;
		        	}else if(result.can_buy==true){
		        		if(result.booking=="1"){
		        			$.bootstrapGrowl("Successfully your booking!.", option_success);
		        			var parameters="?operator_id="+operator_id+"&from_city="+from+"&to_city="+to+"&date="+departure_date+"&time="+departure_time+"&class_id="+class_id+"&bus_no=-";
		        			window.location.href='/'+current_url+parameters;
		        			return false;	
		        		}
		        		window.location.href='/cartview/'+result.sale_order_no;
		        	}
		        });  

		});
</script>
@stop

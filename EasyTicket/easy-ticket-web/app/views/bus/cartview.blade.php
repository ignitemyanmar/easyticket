@extends('master')
@section('content')
<style type="text/css">
	.btn1.green:hover, .btn.green:focus, .btn.green:active, .btn.green.active, .btn.green.disabled, .btn.green[disabled] {
	    background-color: #1D943B !important;
	    color: #FFF !important;
	}
	
	.btn1 {
    background-color: #FF951D;
    background-image: none;
    filter: none;
    border: 0px none;
    box-shadow: none;
    padding: 7px 14px;
    text-shadow: none;
    font-family: "Segoe UI",Helvetica,Arial,sans-serif;
    font-size: 14px;
    color: #eee;
    cursor: pointer;
    outline: medium none;
    float: right;
    margin-left: 1em;
    border-radius: 0px !important;
	}
	#warning{border:1px solid #C09853;color: #333;
				padding: 9px 9px;
				margin-bottom: 13px;
				background: rgba(236, 210, 166, .1);}
	.select2-container {max-width: 287px; min-width: 287px;}
	.large-4 label{padding-right: 0;}
</style>
	<div class="clear">&nbsp;</div>
	<div class="row" style="min-height:480px;">
			<form action="/checkout" method="post" class="pannel" style="padding:0;">
				<div class="large-6 columns">
					<h3 class="title">Customer Information</h3>
					<table style="width:100%">
						<tbody>
							<tr>
								<td>
									@if($objexendcity)
									<div class="row">
										<div class="large-4 columns">ဆက္သြားမည့္ျမိဳ႕</div>
										<div class="large-8 columns">
											<select name="extra_dest_id" id="extendcity">
												<option value="0">ဆက္သြားမည့္ျမိဳ႕ ေရြးရန္</option>
												<option value="{{$objexendcity->id}}">{{$objexendcity->city->name}}</option>
											</select>
											<input type="hidden" value="{{$objexendcity->city_id}}" name="extend_city_id">
										</div>
									</div>
									<br>
									@endif
									<input type="hidden" id="order_id" value="{{$objorder->id}}">

									<div class="row">
										<div class="large-4 columns">အေရာင္းကုိ္ယ္စားလွယ္</div>
										<div class="large-8 columns">
											<select name="agent_id" id="agent">
												<option value="0">အေရာင္းကုိ္ယ္စားလွယ္ ေရြးရန္</option>
												@foreach($agents as $row)
													<option value="{{$row->id}}" @if($objorder->agent_id==$row->id) selected @endif>{{$row->name}}</option>
												@endforeach
											</select>
										</div>
									</div>
									<br>

									<div class="row">
										<div class="large-4 columns">ႏုိင္ငံသား</div>
										<div class="large-4 columns">
											<label>
	                                            <input name="nationality" class="nationality" value="local" checked="" type="radio">
	                                            ႏုိင္ငံသား
	                                        </label>
	                                    </div>
										<div class="large-4 columns">
	                                        <label>
	                                            <input name="nationality" class="nationality" value="foreign" type="radio">
	                                            ႏုိင္ငံျခားသား
	                                        </label>
										</div>
                                    </div>

                                    <div class="row">
										<div class="large-4 columns">ဂိတ္ၾကီး / ဂိတ္ခြဲ</div>
										<div class="large-4 columns nopadding">
											<label>
	                                            <input name="cash_credit" class="cash_credit" value="1" checked="checked" type="radio">
	                                           	ဂိတ္ၾကီး
	                                        </label>
	                                    </div>
										<div class="large-4 columns nopadding">
	                                        <label>
	                                            <input name="cash_credit" class="cash_credit" value="2" type="radio">
	                                            ဂိတ္ခြဲ
	                                        </label>
										</div>
                                    </div>

                                    <!-- <div class="row">
										<div class="large-4 columns">ၾကိဳတင္မွာယူသည္</div>
										<div class="large-4 columns nopadding">
											<label>
	                                            <input name="booking" class="booking" value="1" type="checkbox">
	                                            ၾကိဳတင္မွာ ယူသည္
	                                        </label>
	                                    </div>
										<div class="large-4 columns nopadding">&nbsp;</div>
                                    </div> -->

                                    <div class="row">
										<div class="large-4 columns">စာရင္းေဟာင္းထည့္မည္</div>
										<div class="large-6 columns nopadding">
											<label>
	                                            <input name="oldsale" class="oldsale" value="1" type="checkbox">
	                                        </label>

	                                        <div id="solddate">
	                                        <?php $today=date('Y-m-d'); ?>
	                                          <input type="text" name="solddate" id="sold_date" value="{{date('Y-m-d', strtotime($today.'-1 days'))}}">
	                                       </div>
	                                    </div>
										<div class="large-2 columns nopadding">&nbsp;</div>
                                    </div>
									@for($i=1; $i<=count($response); $i++)
									<div class="row">
										<div class="large-4 columns">လက္မွတ္နံပါတ္ ({{$i}})</div>
										<div class="large-8 columns">
											<input type="text" name="ticket_no[]" class="ticket_no" @if($i==1) id="ticket_no" onkeyup="return setsameticketno(this)" @endif required>
											<label><input type="checkbox" name="foc{{$i}}" value="1"> အခမဲ႔</label>
										</div>
									</div>	
									@endfor
									<div class="row">
										<div class="large-4 columns">ဝယ္သူ အမည္</div>
										<div class="large-8 columns">
											<input type="text" name="buyer_name" required>
										</div>
									</div>
									<div class="row">
										<div class="large-4 columns">မွတ္ပုံတင္နံပါတ္</div>
										<div class="large-8 columns">
											<input type="text" name="nrc">
										</div>
									</div>
									<div class="row" style="display:none;">
										<div class="large-4 columns">Address</div>
										<div class="large-8 columns">
											<input type="text" name="address">
										</div>
									</div>
									<div class="row">
										<div class="large-4 columns">ဖုန္းနံပါတ္</div>
										<div class="large-8 columns">
											<input type="text" name="phone" required>
										</div>
									</div>
									

									<div class="row">
										<div class="large-12 columns">
											<input type="submit" class="btn1 right" value="Comfirm Order" >
										</div>
									</div>		
								</td>
							</tr>
							
						</tbody>
					</table>
				</div>
				<div class="large-6 columns">
					<h3 class="title">Ticket List</h3>
					@if($response)
						<div>Trip : {{$response[0]['from_to']}}</div><br>
						<div>Date : {{date('d/m/Y',strtotime($response[0]['departure_date']))}}</div><br>
						<div>Time : {{$response[0]['time']}}</div><br>
					<table style="width:100%">
						<thead>
							<tr>
								<!-- <th>BusNo</th> -->
								<!-- <th>Trip</th> -->
								<!-- <th>Date Time</th> -->
								<th>SeatNo</th>
								<th>Price</th>
								<th>Foreign Price</th>
								<!-- <th>Action</th> -->
							</tr>
						</thead>
						<tbody>
								<?php $total=0; $foreign_total=0;?>
								@foreach($response as $tickets)
									<input type="hidden" name="busoccurance_id[]" value="{{$tickets['busoccurance_id']}}">
									<input type="hidden" name="sale_order_no" value="{{$tickets['sale_order_no']}}">
									<input type="hidden" name="seat_no[]" value="{{$tickets['seat_no']}}">
									<input type="hidden" name="agent_id1" value="{{$tickets['agent_id']}}">
									<tr>
										<!-- <td>{{$tickets['bus_no']}}</td> -->
										<!-- <td>{{$tickets['from_to']}}</td> -->
										<!-- <td>{{$tickets['departure_date'] .'<br>'.$tickets['time']}}</td> -->
										<td>{{$tickets['seat_no']}}</td>
										<td>{{$tickets['price']}} KS</td>
										<td>{{$tickets['foreign_price']}} KS</td>
										<!-- <td>
											<a href="/sales/{{$tickets['id']}}/delete" class="remove">Remove</a>
										</td> -->
									</tr>
									<?php $total +=$tickets['price']; ?>
									<?php $foreign_total +=$tickets['foreign_price']; ?>
								@endforeach
								<tr>
									<td colspan="1" class="text-right"><b>Grand Total (Local):</b></td>
									<td colspan="2" class="text-right"><span style="color:red;">{{ $total }}</span> KS</td>
								</tr>
								<tr>
									<td colspan="1" class="text-right"><b>Grand Total (Foreign):</b></td>
									<td colspan="2" class="text-right"><span style="color:red;">{{ $foreign_total }}</span> KS</td>
								</tr>
						</tbody>
					</table>
					@endif
				</div>
			</form>
		<!-- </div> -->
		<!-- <div class="large-4 columns" id='results'>&nbsp;</div> -->
	</div>

	<script type="text/javascript">
		/*$(document).ready(function()
		{
		    $(window).bind("beforeunload", function() { 
		    	alert("Alert");
		        return confirm("Do you really want to close?"); 
		    });
		});*/
		/*function HandleBackFunctionality()
		 {
		     if(window.event)
		    {
		          if(window.event.clientX < 40 && window.event.clientY < 0)
		         {
		             alert("Browser back button is clicked...");
		         }
		         else
		         {
		             alert("Browser refresh button is clicked...");
		         }
		     }
		     else
		     {
		          if(event.currentTarget.performance.navigation.type == 1)
		         {
		              alert("Browser refresh button is clicked...");
		         }
		         if(event.currentTarget.performance.navigation.type == 2)
		        {
		              alert("Browser back button is clicked...");
		        }
		     }
		 }
		*/
		/*if (window.history && window.history.pushState) {

		    $(window).on('popstate', function() {
		      var hashLocation = location.hash;
		      var hashSplit = hashLocation.split("#!/");
		      var hashName = hashSplit[1];

		      if (hashName !== '') {
		        var hash = window.location.hash;
		        if (hash === '') {
		          alert('Back button was pressed.');
		        }
		      }
		    });

		    window.history.pushState('forward', null, './#forward');
		  }*/

	    /*window.onbeforeunload = function(evt)   
	        {   
	        if (typeof evt == 'undefined')    
	        evt = window.event;    
	     
	     	// alert(evt);
	            if(evt)   
	            {  
	            	console.log('abcccc'); 
	            	return "If you leave this page, your information will not be updated.";   
	            }   
	        }  
	     */



		$(function(){

			if (window.history && window.history.pushState) {
			    var order_id=$('#order_id').val();
			    window.history.pushState('forward', null, './'+order_id);
			    $(window).on('popstate', function() {
			      	$.get('/notconfirm-order-delete/'+order_id,function(data){
				    });
			    });
			}

			/*window.onbeforeunload = function(evt)   
		    {   
			    if (typeof evt == 'undefined')    
			    evt = window.event;    
		        if(evt)   
		        {  
				    var order_id=$('#order_id').val();
			        $.get('/order-delete/'+order_id,function(data){
					    });
			        // return "message to display";
                }   
		    }  */

			$("#agent").select2();
			$("#extendcity").select2();

			$('.remove').click(function(e){
				$(this).parent().parent().remove();
				e.preventDefault();
				var link=$(this).attr('href');
				$.ajax({
					type:'POST',
					url:link,
					data:{}
				}).done(function(result){
					if(result=='Have been deleted.'){
					}
				});
			});

			$("#sold_date").datepicker({
	            numberOfMonth: 2,
	            dateFormat: 'yy-mm-dd'
	        });

	        var checkchecked=$('.oldsale').prop('checked');
	        if(checkchecked==false){
				$("#solddate").hide();
	        }

			$('.oldsale').click(function(){
				var checkchecked=$(this).prop('checked');
				if(checkchecked){
					$("#solddate").show();
					
				}else{
					$("#solddate").hide();
				}
			});
		});
		function setsameticketno(obj){
			var ticketno=obj.value;
			$('.ticket_no').each(function(){
				$(this).val(ticketno);
			})
		}
	</script>
@stop
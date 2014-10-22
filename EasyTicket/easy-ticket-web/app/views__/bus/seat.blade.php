@extends('master')
@section('content')
    {{HTML::style('../../css/Tixato_files/seating_builder.css')}}
	<div class="large-12 columns">
		<h3 class="hdtitle" style="background:none; color:#000;margin-bottom:14px; padding-bottom:11px;"> <a title="abcddd dd" href="">Choose your Seats</a></h3>
	</div>
	<div class="row" id="container">
		<div class="large-8 columns">
		    <div id="seating-map-wrapper">
			    <div id="seating-map-creator">
			    	<div class="row">
			      	 	<div class="check-a">
					        <div class="large-3 small-3 columns">&nbsp;</div>
			      	 		<div class="large-1 small-1  columns">
						        <div class="checkboxframe">
						            <label class="checkboxframe">
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]" disabled>
						                <div class="fit-a taken" title="Row A, Seat 1 (Taken)" id="A1"></div>
						            	<input type="hidden" value="A" class='seatrowA1'>
						            	<input type="hidden" value="1" class='seatnoA1'>
						            </label>
						        </div>
						        <div class="checkboxframe">
						            <label>
						                <span></span>
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]" disabled>
						                <div class="fit-a taken" title="Row B, Seat 1 (Taken)" id="B1"></div>
						            </label>
						        </div>
						        <div class="checkboxframe">
						            <label>
						                <span></span>
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
						                <div class="fit-a available" title="Row C, Seat 1, Price : $ 5" id="C1"></div>
						            	<input type="hidden" value="C" class='seatrowC1'>
						            	<input type="hidden" value="1" class='seatnoC1'>
						            </label>
						        </div>
						        <div class="checkboxframe">
						            <label class="checkboxframe">
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]" disabled>
						                <div class="fit-a taken" title="Row D, Seat 1 (Taken)" id="D1"></div>
						            	<input type="hidden" value="D" class='seatrowD1'>
						            	<input type="hidden" value="1" class='seatnoD1'>
						            </label>
						        </div>
						        <div class="checkboxframe">
						            <label>
						                <span></span>
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
						                <div class="fit-a available" title="Row E, Seat 1, Price : $ 5" id="E1"></div>
						            	<input type="hidden" value="E" class='seatrowE1'>
						            	<input type="hidden" value="1" class='seatnoE1'>
						            </label>
						        </div>
						        <div class="checkboxframe">
						            <label>
						                <span></span>
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
						                <div class="fit-a available" title="Row F, Seat 1, Price : $ 5" id="F1"></div>
						            	<input type="hidden" value="F" class='seatrowF1'>
						            	<input type="hidden" value="1" class='seatnoF1'>
						            </label>
						        </div>
						        <div class="checkboxframe">
						            <label class="checkboxframe">
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]" disabled>
						                <div class="fit-a taken" title="Row G, Seat 1 (Taken)" id="G1"></div>
						            	<input type="hidden" value="G" class='seatrowG1'>
						            	<input type="hidden" value="1" class='seatnoG1'>
						            </label>
						        </div>
						        <div class="checkboxframe">
						            <label>
						                <span></span>
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
						                <div class="fit-a available" title="Row H, Seat 8, Price : $ 5" id="H1"></div>
						            	<input type="hidden" value="H" class='seatrowH1'>
						            	<input type="hidden" value="1" class='seatnoH1'>
						            </label>
						        </div>
						        <div class="checkboxframe">
						            <label>
						                <span></span>
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
						                <div class="fit-a available" title="Row I, Seat 1, Price : $ 5" id="I1"></div>
						            	<input type="hidden" value="I" class='seatrowI1'>
						            	<input type="hidden" value="1" class='seatnoI1'>
						            </label>
						        </div>
						        <div class="checkboxframe">
						            <label>
						                <span></span>
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
						                <div class="fit-a available" title="Row J, Seat 1, Price : $ 5" id="J1"></div>
						            	<input type="hidden" value="J" class='seatrowJ1'>
						            	<input type="hidden" value="1" class='seatnoJ1'>
						            </label>
						        </div>
					        </div>
					        <div class="large-1 small-1 columns">
						        <div class="checkboxframe">
						            <label class="checkboxframe">
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]" disabled>
						                <div class="fit-a taken" title="Row A, Seat 2 (Taken)" id="A2"></div>
						            	<input type="hidden" value="A" class='seatrowA2'>
						            	<input type="hidden" value="2" class='seatnoA2'>
						            </label>
						        </div>
						        <div class="checkboxframe">
						            <label>
						                <span></span>
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]" disabled>
						                <div class="fit-a taken" title="Row B, Seat 2 (Taken)" id="B2"></div>
						            </label>
						        </div>
						        <div class="checkboxframe">
						            <label>
						                <span></span>
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
						                <div class="fit-a available" title="Row C, Seat 2, Price : $ 5" id="C2"></div>
						            	<input type="hidden" value="C" class='seatrowC2'>
						            	<input type="hidden" value="2" class='seatnoC2'>
						            </label>
						        </div>
						        <div class="checkboxframe">
						            <label class="checkboxframe">
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]" disabled>
						                <div class="fit-a taken" title="Row D, Seat 2 (Taken)" id="D2"></div>
						            	<input type="hidden" value="D" class='seatrowD2'>
						            	<input type="hidden" value="2" class='seatnoD2'>
						            </label>
						        </div>
						        <div class="checkboxframe">
						            <label>
						                <span></span>
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
						                <div class="fit-a available" title="Row E, Seat 2, Price : $ 5" id="E2"></div>
						            	<input type="hidden" value="E" class='seatrowE2'>
						            	<input type="hidden" value="2" class='seatnoE2'>
						            </label>
						        </div>
						        <div class="checkboxframe">
						            <label>
						                <span></span>
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
						                <div class="fit-a available" title="Row F, Seat 2, Price : $ 5" id="F2"></div>
						            	<input type="hidden" value="F" class='seatrowF2'>
						            	<input type="hidden" value="2" class='seatnoF2'>
						            </label>
						        </div>
						        <div class="checkboxframe">
						            <label class="checkboxframe">
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]" disabled>
						                <div class="fit-a taken" title="Row G, Seat 2 (Taken)" id="G2"></div>
						            	<input type="hidden" value="G" class='seatrowG2'>
						            	<input type="hidden" value="2" class='seatnoG2'>
						            </label>
						        </div>
						        <div class="checkboxframe">
						            <label>
						                <span></span>
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
						                <div class="fit-a available" title="Row H, Seat 2, Price : $ 5" id="H2"></div>
						            	<input type="hidden" value="H" class='seatrowH2'>
						            	<input type="hidden" value="2" class='seatnoH2'>
						            </label>
						        </div>
						        <div class="checkboxframe">
						            <label>
						                <span></span>
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
						                <div class="fit-a available" title="Row I, Seat 2, Price : $ 5" id="I2"></div>
						            	<input type="hidden" value="I" class='seatrowI2'>
						            	<input type="hidden" value="2" class='seatnoI2'>
						            </label>
						        </div>
						        <div class="checkboxframe">
						            <label>
						                <span></span>
						                <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
						                <div class="fit-a available" title="Row J, Seat 2, Price : $ 5" id="J2"></div>
						            	<input type="hidden" value="J" class='seatrowJ2'>
						            	<input type="hidden" value="2" class='seatnoJ2'>
						            </label>
						        </div>
					        </div>

					        <div class="large-2 small-2 columns">&nbsp;</div>


					        <div class="large-1 small-1 columns">
							    <div class="checkboxframe">
							        <label class="checkboxframe">
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]" disabled>
							            <div class="fit-a taken" title="Row A, Seat 3 (Taken)" id="A3"></div>
							        	<input type="hidden" value="A" class='seatrowA3'>
							        	<input type="hidden" value="3" class='seatnoA3'>
							        </label>
							    </div>
							    <div class="checkboxframe">
							        <label>
							            <span></span>
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]" disabled>
							            <div class="fit-a taken" title="Row B, Seat 3 (Taken)" id="B3"></div>
							        </label>
							    </div>
							    <div class="checkboxframe">
							        <label>
							            <span></span>
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
							            <div class="fit-a available" title="Row C, Seat 3, Price : $ 5" id="C3"></div>
							        	<input type="hidden" value="C" class='seatrowC3'>
							        	<input type="hidden" value="3" class='seatnoC3'>
							        </label>
							    </div>
							    <div class="checkboxframe">
							        <label class="checkboxframe">
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]" disabled>
							            <div class="fit-a taken" title="Row D, Seat 3 (Taken)" id="D3"></div>
							        	<input type="hidden" value="D" class='seatrowD3'>
							        	<input type="hidden" value="3" class='seatnoD3'>
							        </label>
							    </div>
							    <div class="checkboxframe">
							        <label>
							            <span></span>
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
							            <div class="fit-a available" title="Row E, Seat 3, Price : $ 5" id="E3"></div>
							        	<input type="hidden" value="E" class='seatrowE3'>
							        	<input type="hidden" value="3" class='seatnoE3'>
							        </label>
							    </div>
							    <div class="checkboxframe">
							        <label>
							            <span></span>
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
							            <div class="fit-a available" title="Row F, Seat 3, Price : $ 5" id="F3"></div>
							        	<input type="hidden" value="F" class='seatrowF3'>
							        	<input type="hidden" value="3" class='seatnoF3'>
							        </label>
							    </div>
							    <div class="checkboxframe">
							        <label class="checkboxframe">
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]" disabled>
							            <div class="fit-a taken" title="Row G, Seat 3 (Taken)" id="G3"></div>
							        	<input type="hidden" value="G" class='seatrowG3'>
							        	<input type="hidden" value="3" class='seatnoG3'>
							        </label>
							    </div>
							    <div class="checkboxframe">
							        <label>
							            <span></span>
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
							            <div class="fit-a available" title="Row H, Seat 8, Price : $ 5" id="H3"></div>
							        	<input type="hidden" value="H" class='seatrowH3'>
							        	<input type="hidden" value="3" class='seatnoH3'>
							        </label>
							    </div>
							    <div class="checkboxframe">
							        <label>
							            <span></span>
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
							            <div class="fit-a available" title="Row I, Seat 3, Price : $ 5" id="I3"></div>
							        	<input type="hidden" value="I" class='seatrowI3'>
							        	<input type="hidden" value="3" class='seatnoI3'>
							        </label>
							    </div>
							    <div class="checkboxframe">
							        <label>
							            <span></span>
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
							            <div class="fit-a available" title="Row J, Seat 3, Price : $ 5" id="J3"></div>
							        	<input type="hidden" value="J" class='seatrowJ3'>
							        	<input type="hidden" value="3" class='seatnoJ3'>
							        </label>
							    </div>
							</div>
							<div class="large-1 small-1 columns">
							    <div class="checkboxframe">
							        <label class="checkboxframe">
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]" disabled>
							            <div class="fit-a taken" title="Row A, Seat 4 (Taken)" id="A4"></div>
							        	<input type="hidden" value="A" class='seatrowA4'>
							        	<input type="hidden" value="4" class='seatnoA4'>
							        </label>
							    </div>
							    <div class="checkboxframe">
							        <label>
							            <span></span>
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]" disabled>
							            <div class="fit-a taken" title="Row B, Seat 4 (Taken)" id="B4"></div>
							        </label>
							    </div>
							    <div class="checkboxframe">
							        <label>
							            <span></span>
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
							            <div class="fit-a available" title="Row C, Seat 4, Price : $ 5" id="C4"></div>
							        	<input type="hidden" value="C" class='seatrowC4'>
							        	<input type="hidden" value="4" class='seatnoC4'>
							        </label>
							    </div>
							    <div class="checkboxframe">
							        <label class="checkboxframe">
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]" disabled>
							            <div class="fit-a taken" title="Row D, Seat 4 (Taken)" id="D4"></div>
							        	<input type="hidden" value="D" class='seatrowD4'>
							        	<input type="hidden" value="4" class='seatnoD4'>
							        </label>
							    </div>
							    <div class="checkboxframe">
							        <label>
							            <span></span>
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
							            <div class="fit-a available" title="Row E, Seat 4, Price : $ 5" id="E4"></div>
							        	<input type="hidden" value="E" class='seatrowE4'>
							        	<input type="hidden" value="4" class='seatnoE4'>
							        </label>
							    </div>
							    <div class="checkboxframe">
							        <label>
							            <span></span>
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
							            <div class="fit-a available" title="Row F, Seat 4, Price : $ 5" id="F4"></div>
							        	<input type="hidden" value="F" class='seatrowF4'>
							        	<input type="hidden" value="4" class='seatnoF4'>
							        </label>
							    </div>
							    <div class="checkboxframe">
							        <label class="checkboxframe">
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]" disabled>
							            <div class="fit-a taken" title="Row G, Seat 4 (Taken)" id="G4"></div>
							        	<input type="hidden" value="G" class='seatrowG4'>
							        	<input type="hidden" value="4" class='seatnoG4'>
							        </label>
							    </div>
							    <div class="checkboxframe">
							        <label>
							            <span></span>
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
							            <div class="fit-a available" title="Row H, Seat 4, Price : $ 5" id="H4"></div>
							        	<input type="hidden" value="H" class='seatrowH4'>
							        	<input type="hidden" value="4" class='seatnoH4'>
							        </label>
							    </div>
							    <div class="checkboxframe">
							        <label>
							            <span></span>
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
							            <div class="fit-a available" title="Row I, Seat 4, Price : $ 5" id="I4"></div>
							        	<input type="hidden" value="I" class='seatrowI4'>
							        	<input type="hidden" value="4" class='seatnoI4'>
							        </label>
							    </div>
							    <div class="checkboxframe">
							        <label>
							            <span></span>
							            <input class="radios" type="checkbox" multiple="multiple" value="219" name="roomtype[]">
							            <div class="fit-a available" title="Row J, Seat 4, Price : $ 5" id="J4"></div>
							        	<input type="hidden" value="J" class='seatrowJ4'>
							        	<input type="hidden" value="4" class='seatnoJ4'>
							        </label>
							    </div>
							</div>

					        <div class="large-3 small-3 columns">&nbsp;</div>
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
			<div class="row">
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
	</div>
	
	<br>
	<div class="large-8 columns chooseticket">
		<h3 class="hdtitle">Selected Seats</h3>
		<form class="row" action ="checkout.html" method= "post">
			  	<div class="large-12 columns tbhead"  style="padding:0; padding-bottom:2rem;margin-left:2rem;">
			  		<div class="row hdrow">
			  			<div class="columns">&nbsp;</div>
			  			<div class="small-2 columns">Section</div>
			  			<div class="small-1 columns">Row</div>
			  			<div class="small-2 columns">Seat</div>
			  			<div class="small-4 columns">price per ticket</div>
			  			<div class="small-3 columns text-right">Remove All</div>
			  		</div>
			  		<div class="selectedseats">
				  		<!-- append dynamic selected seat data info: -->
			  		</div>
			  	</div>
		    <input type="submit" class="button small right" value="Buy Tickets" style="border:1px solid #ccc;"> 
		    <span class="title right">Sub Total: $ 20 </span>
	    </form>
	</div>
	<div class="large-4 columns">&nbsp;</div>
    {{HTML::script('../js/chooseseats.js')}}
@stop
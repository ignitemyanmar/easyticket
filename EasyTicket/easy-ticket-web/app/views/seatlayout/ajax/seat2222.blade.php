
@if($seatlayout)
	<?php 
		$column=$seatlayout['column'];
		$row=$seatlayout['row'];
		$width=(100%$column);
		$seatno=1;
	?>
	
   	<div id="seatplanview">
    	<div id="seating-map-wrapper">
	        <div id="seating-map-creator">
	            <div class="check-a">
					<table style="width:100%">

						@for($i=1;$i<=$seatlayout['row'];$i++)
						<tr>
							@for($j=1;$j<=$seatlayout['column'];$j++)
							<td>
								<div class="checkboxframe">
						            <label>
						                <span></span>
						                <input class="radios" type="checkbox" multiple="multiple" value="{{$seatno}}" name="seats[]" >
						                <div class="fit-a available" title="" id="{{$seatno}}"><p>&nbsp;</p></div>
						            	<input type="hidden" value="" class="price">
						            	<input type="hidden" value="{{$seatno}}" class="seatno{{$seatno}}">
						            </label>
						        </div>
							</td>
								<?php $seatno++; ?>

							@endfor
						</tr>
						<tr><td colspan="$seatlayout['column']">&nbsp;<br></td></tr>
						@endfor

					</table>
				</div>
			</div>
		</div>
	</div>
	
	
@endif

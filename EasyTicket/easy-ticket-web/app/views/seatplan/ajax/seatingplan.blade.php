
{{HTML::script('../../js/chooseseats.js')}}
{{HTML::style('../../css/Tixato_files/seating_builder.css')}}
<style type="text/css">
  .padding-5{padding: 5px;}
  body .ui-tooltip, .arrow:after {
      background: transparent;
      border: 0px solid #FF8004;
  }
  .arrow{position: relative;
			left: 64%;
			margin-left: 0px;
			box-shadow: transparent;
		}
</style>
@if($seatplan)
	<?php 
		$column   =$seatplan['column'];
		$row      =$seatplan['row'];
		$seatlist =$seatplan['seat_list'];
		$width    =(100%$column);
		$seatno   =1;
	?>
	<table style="width:100%">
		<?php $k=0; ?>
		@for($i=1;$i<=$seatplan['row'];$i++)
		<tr>
			@for($j=1;$j<=$seatplan['column'];$j++)
			<td>
				<div class="checkboxframe">
		            <label>
		                <span></span>
		                	@if($seatlist[$k]==0)
		                	<input  type="text"  value="No Seat" name="seats[]"  style="width:65px;" readonly>
		                	@else
		                	<input  type="text"  value="" name="seats[]"  style="width:65px;">
		                	@endif
		            		<input type="hidden" value="" class="price">
		            		<input type="hidden" value="{{$seatno}}" class="seatno{{$seatno}}">
		                
			                
		            </label>
		        </div>
			</td>
				<?php $seatno++; $k++; ?>

			@endfor
		</tr>
		@endfor
	</table>
@endif

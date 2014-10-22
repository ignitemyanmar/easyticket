<div id="seating-map-wrapper">
    <div id="seating-map-creator">
      	 	<div class="check-a">
		        @if($response['seat_list'])
		        	<?php $k=1;  $columns=$response['column'];?>
		        	@foreach($response['seat_list'] as $rows)
			        	
			        	@if($k%$columns == 1)
    					<div class="row-fluid">
			        	<div class="span1 small-1">&nbsp;</div>
		      	 		@endif
		      	 		<div class="span2 small-2 ">
					        @if($rows['status']==0)
					        	<div class="span2 small-2">&nbsp;</div>
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
						                <div class="fit-a {{$taken}}" title="{{$rows['seat_no']}}" id="">{{$rows['seat_no']}}</div>
						            </label>
						        </div>
					        @endif
					        
			        	</div>
			        	@if($k%$columns==0)
			        		<div class="span3 small-2">&nbsp;</div>
			        	</div>
					   		<div style="clear:both;height:0px;">&nbsp;</div>
					    @endif
	      	 			<?php $k++;?>
				    @endforeach
				@endif
		    </div>
    </div>
</div>
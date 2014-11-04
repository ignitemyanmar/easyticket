
@if($seatlayout)
	<?php 
		$column=$seatlayout['column'];
		$row=$seatlayout['row'];
		$width=(100%$column);
		$seatno=1;
	?>
	<table style="width:100%">
		@for($i=1;$i<=$seatlayout['row'];$i++)
		<tr>
			@for($j=1;$j<=$seatlayout['column'];$j++)
			<td>
				<div class="checkboxframe">
		            <label>
		                <span></span>
		                <input class="radios" type="checkbox" multiple="multiple" value="{{$seatno}}" name="seats[]">
		                <div class="fit-a noseat" title="" id="{{$seatno}}"><p>&nbsp;</p></div>
		                <div style="background:#ccc;">
		            </label>
		        </div>
			</td>
				<?php $seatno++; ?>

			@endfor
		</tr>
		@endfor

	</table>

	<script type="text/javascript">
		$(function(){
			$(".fit-a").each(function(){
				$(this).click(function(){
					$(this).toggleClass('available noseat');
				});
		    });
		});
	</script>
	
	
@endif

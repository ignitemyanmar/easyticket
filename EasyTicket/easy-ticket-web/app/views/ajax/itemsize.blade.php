<select class="span8 size" data-placeholder="Choose a size" tabindex="9" name="size[]" id="size">
	@if(count($response)>0)
		<?php $i=0; ?>
		@foreach($response as $itemsize)
		   <option value="{{$itemsize['id']}}" @if($i==0) selected @else @endif>{{$itemsize['name']}}</option>
			<?php $i++; ?>
		@endforeach
	@else
	   <option value=""></option>
	@endif
</select>

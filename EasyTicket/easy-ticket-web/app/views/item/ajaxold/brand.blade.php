<select class="span12 chosen" data-placeholder="Choose a Brand" tabindex="1" name="brand" id="brand">
	@if(count($response)>0)
		<?php $i=0; ?>
		@foreach($response as $brand)
		   <option value="{{$brand['id']}}" @if($i==0) selected @else @endif>{{$brand['name']}}</option>
			<?php $i++; ?>
		@endforeach
	@endif
</select>
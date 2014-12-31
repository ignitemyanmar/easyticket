<select class="span6 chosen" data-placeholder="Choose a itemcategory" tabindex="3" name="itemcategory" id="itemcategory">
	@if(count($response)>0)
		@foreach($response as $itemcategory)
		   <option value="{{$itemcategory['id']}}">{{$itemcategory['name']}}</option>
		@endforeach
	@else
	   <option value=""></option>
	@endif
</select>
<script type="text/javascript">
	$(function(){
		chosencombo();
	});
</script>



<select class="span6 chosen" data-placeholder="Choose a Category" tabindex="3" name="category" id="category">
	@if(count($response)>0)
		<?php $i=0; ?>
		@foreach($response as $category)
		   <option value="{{$category['id']}}" @if($i==0) selected @else @endif>{{$category['name']}}</option>
			<?php $i++; ?>
		@endforeach
	@endif
</select>
<input type="hidden" name="hdcategoryid" id="hdcategoryid" value="0">
<script type="text/javascript">
	$(function(){
	    var catid=$("#category").val();
	    if(catid){
	    	loaditemsize(catid);
	    	loadsubcategories(catid);
	    }
	    $('#category').change(function(){
	    	$('#itemcategoryframe').html('');	
	    	catid=$(this).val();
	    	loadsubcategories(catid);
	    	loaditemsize(catid);
	    });
	});
</script>
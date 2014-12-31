<select class="span6 chosen" data-placeholder="Choose a subcategory" tabindex="3" name="subcategory" id="subcategory">
	@if(count($response)>0)
		<?php $i=0; ?>
		@foreach($response as $subcategory)
		   <option value="{{$subcategory['id']}}" @if($i==0) selected @else @endif>{{$subcategory['name']}}</option>
			<?php $i++; ?>
		@endforeach
	@else
	   <option value=""></option>
	@endif
</select>
<script type="text/javascript">
	$(function(){
	    var catid=$("#subcategory").val();
	    if(catid){
	    	// loaditemsize(catid);
	    	loaditemcategories(catid);
	    }
	    $('#subcategory').change(function(){
	    	$('.itemcategory').html('');
	    	catid=$(this).val();
	    	loaditemcategories(catid);
	    });
	});
</script>

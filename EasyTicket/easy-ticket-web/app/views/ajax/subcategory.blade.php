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
	    chosencombo();
		var subcat_id=$('#subcategory').val();
	    if(subcat_id)
	      loaditemcategories(subcat_id);
		$('#subcategory').change(function(){
	      $('#itemcategoryframe').hide();
	      var subcatid=$(this).val();
	      $('#itemcategoryloading').show();
	          $.get('/itemcategorylist/'+subcatid,function(data){
	          $('#itemcategoryframe').show();
	          $('#itemcategoryframe').html(data);
	          $('#itemcategoryloading').hide();
	      });
	  	});
	});
</script>

<select class="span6 selecttwo chosen" data-placeholder="Choose a Category" tabindex="3" name="category" id="category">
	@if(count($response)>0)
		<?php $i=0; ?>
		@foreach($response as $category)
		   <option value="{{$category['id']}}" @if($i==0) selected @else @endif>{{$category['name']}}</option>
			<?php $i++; ?>
		@endforeach
	@else
	   <option value=""></option>
	@endif
</select>
<input type="hidden" name="hdcategoryid" id="hdcategoryid" value="0">
<script type="text/javascript">
	$(function(){
		$('#category').select2();
		$('#subcategoryframe').hide();
	    var catid=$("#category").val();
	    if(catid)
	    	loaditemsize(catid);
	    chosencombo();
		$('#category').change(function(){
	      $('#subcategoryframe').hide();
	      var catid=$(this).val();
	      	$('#subcategoryloading').show();
	          $.get('/subcategorylist/'+catid,function(data){
		          $('.selecttwo').select2();
		          $('#subcategoryframe').show();
		          $('#subcategoryframe').html(data);
		          $('#subcategoryloading').hide();
		          $('#hdcategoryid').val(catid);
		      });
          	if(catid){
	          	$.get('/itemsizelist/'+catid,function(data){
			        $('#itemsizeframe').show();
			        $('#itemsizeframe').html(data);
			        $('#itemsizeloading').hide();
			    });
      		}
	      $('#subcategoryframe').show();
	  	});
	});
</script>
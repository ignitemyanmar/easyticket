$(function(){
		$('#shopadd').click(function(){
            $('#addnewshoploading').show();
            var shop=$('#pu_shop').val();
            var shopmm=$('#pu_shopmm').val();
            var shopsmm=$('#pu_shopsmm').val();
            var shopimage=$('#shopimage').val();
            $.ajax({
	                type: "POST",
	                url: "/shop/popup",
	                data: {name:shop,name_mm:shopmm, search_key_mm:shopsmm,image:shopimage}
            }).done(function( result ) {
            	if(result=='This record is already exit'){
            		alert(result);
            	}else{
            		 var options='<select class="span12 chosen" data-placeholder="Choose a Shop" tabindex="1" name="shop" id="shop">';

            		for(var i=0; i < result.length; i++){
            			options +="<option value='"+result[i].id+"'>"+result[i].name+" ("+result[i].name_mm+" )"+"</option>";
            		}
            		options +='</select>';

            		$('#shopframe').html('');
            		$('#shopframe').html(options);
            		$('#shop').chosen();
            		$(".reveal-modal-bg").css('display',"none");
            		$("#shopform").removeClass('open');
            		$("#shopform").css('visibility','hidden');
                    $(".gallery_container1").html('');
                    $('.upload1').show();

            	}
            });
     	});

		$('#categoryadd').click(function(e){
        	e.preventDefault();

         	var combomenu_id=$('#menu').val();
            $('#addnewcategoryloading').show();
            // var menuid=$('#pu_menuid').val();
            var category=$('#pu_category').val();
            var categorymm=$('#pu_categorymm').val();
            var categorysmm=$('#pu_categorysmm').val();
            var categoryimage=$('#categoryimage').val();
            $.ajax({
	                type: "POST",
	                url: "/category/popup",
	                data: {/*menu_id:menuid,*/name:category,name_mm:categorymm, search_key_mm:categorysmm,image:categoryimage,cbomenuid:combomenu_id}
            }).done(function( result ) {
            	if(result=='This record is already exit'){
            		alert(result);
            	}else{
            		var options='<select class="span12 chosen" data-placeholder="Choose a Category" tabindex="1" name="category" id="category">';
            		for(var i=0; i < result.length; i++){
            			options +="<option value='"+result[i].id+"'>"+result[i].name+" ("+result[i].name_mm+" )"+"</option>";
            		}
            		options +='</select>';
            		
            		$('#categoryframe').html('');
            		$('#categoryframe').html(options);
            		$('#category').chosen();

            		$(".reveal-modal-bg").css('display',"none");
            		$("#categoryform").removeClass('open');
            		$("#categoryform").css('visibility','hidden');
                    $(".gallery_container1").html('');
                    $('.upload1').show();


            	}
            });
     	});

		$('#subcategoryadd').click(function(e){
          e.preventDefault();
          var combosubcategory_id=$('#category').val();
            $('#addnewsubcategoryloading').show();
            var subcategory=$('#pu_subcategory').val();
            var subcategorymm=$('#pu_subcategorymm').val();
            var subcategorysmm=$('#pu_subcategorysmm').val();
            var subcategoryimage=$('#subcategoryimage').val();
            $.ajax({
                  type: "POST",
                  url: "/subcategory/popup",
                  data: {name:subcategory,name_mm:subcategorymm, search_key_mm:subcategorysmm,image:subcategoryimage,cbocategoryid:combosubcategory_id}
            }).done(function( result ) {
              if(result=='This record is already exit'){
                alert(result);
              }else{
                var options='<select class="span12 chosen" data-placeholder="Choose a Category" tabindex="1" name="subcategory" id="subcategory">';
                for(var i=0; i < result.length; i++){
                  options +="<option value='"+result[i].id+"'>"+result[i].name+" ("+result[i].name_mm+" )"+"</option>";
                }
                options +='</select>';
                
                $('#subcategoryframe').html('');
                $('#subcategoryframe').html(options);
                $('#subcategory').chosen();
                $('#addnewsubcategoryloading').hide();

                $(".reveal-modal-bg").css('display',"none");
                $("#subcategoryform").removeClass('open');
                $("#subcategoryform").css('visibility','hidden');
                $(".gallery_container1").html('');
                $('.upload1').show();


              }
            });
    });

    $('#sizeadd').click(function(e){
          e.preventDefault();
          var combosubcategory_id=$('#category').val();
            $('#addnewsizeloading').show();
            var size=$('#pu_size').val();
            var sizemm=$('#pu_sizemm').val();
            $.ajax({
                  type: "POST",
                  url: "/size/popup",
                  data: {name:size,name_mm:sizemm,cbocategoryid:combosubcategory_id}
            }).done(function( result ) {
              if(result=='This record is already exit'){
                alert(result);
              }else{
                var options='<select class="span12 chosen" data-placeholder="Choose a Category" tabindex="1" name="size" id="size">';
                for(var i=0; i < result.length; i++){
                  options +="<option value='"+result[i].id+"'>"+result[i].name+"</option>";
                }
                options +='</select>';
                
                $('#itemsizeframe').html('');
                $('#itemsizeframe').html(options);
                $('#size').chosen();
                $('#addnewsizeloading').hide();

                $(".reveal-modal-bg").css('display',"none");
                $("#sizeform").removeClass('open');
                $("#sizeform").css('visibility','hidden');
              }
            });
    });

    $('#coloradd').click(function(e){
          e.preventDefault();
            $('#addnewcolorloading').show();
            var color=$('#pu_color').val();
            var colormm=$('#pu_colormm').val();
            $.ajax({
                  type: "POST",
                  url: "/color/popup",
                  data: {name:color,name_mm:colormm}
            }).done(function( result ) {
              if(result=='This record is already exit'){
                alert(result);
                $('#addnewcolorloading').hide();
              }else{
                var options='<select class="span12 chosen" data-placeholder="Choose a Color" tabindex="1" name="color" id="color">';
                for(var i=0; i < result.length; i++){
                  options +="<option value='"+result[i].id+"'>"+result[i].name+"</option>";
                }
                options +='</select>';
                
                $('#itemcolorframe').html('');
                $('#itemcolorframe').html(options);
                // $('#color').chosen();
                $('#addnewcolorloading').hide();

                $(".reveal-modal-bg").css('display',"none");
                $("#colorform").removeClass('open');
                $("#colorform").css('visibility','hidden');
              }
            });
    });
		
		$('#itemcategoryadd').click(function(e){
          e.preventDefault();
          // alert('a');
          var subcategoryid=$('#subcategory').val();
            $('#addnewitemcategoryloading').show();
            var itemcategory=$('#pu_itemcategory').val();
            var itemcategorymm=$('#pu_itemcategorymm').val();
            var itemcategorysmm=$('#pu_itemcategorysmm').val();
            var itemcategoryimage=$('#itemcategoryimage').val();
            $.ajax({
                  type: "POST",
                  url: "/itemcategory/popup",
                  data: {name:itemcategory,name_mm:itemcategorymm, search_key_mm:itemcategorysmm,image:itemcategoryimage,subcategory_id:subcategoryid}
            }).done(function( result ) {
              if(result=='This record is already exit'){
                alert(result);
              }else{
                var options='<select class="span12 chosen" data-placeholder="Choose a Item Category" tabindex="1" name="itemcategory" id="itemcategory">';
                for(var i=0; i < result.length; i++){
                  options +="<option value='"+result[i].id+"'>"+result[i].name+" ("+result[i].name_mm+" )"+"</option>";
                }
                options +='</select>';
                
                $('#itemcategoryframe').html('');
                $('#itemcategoryframe').html(options);
                $('#itemcategory').chosen();
                $('#addnewitemcategoryloading').hide();

                $(".reveal-modal-bg").css('display',"none");
                $("#itemcategoryform").removeClass('open');
                $("#itemcategoryform").css('visibility','hidden');
                $(".gallery_container1").html('');
                $('.upload1').show();

              }
            });
        });

        $('#brandadd').click(function(e){
        	e.preventDefault();

         	var combomenu_id=$('#menu').val();
            $('#addnewbrandloading').show();
            // var menuid=$('#pu_menuid').val();
            var brand=$('#pu_brand').val();
            var brandmm=$('#pu_brandmm').val();
            var brandsmm=$('#pu_brandsmm').val();
            var brandimage=$('#brandimage').val();
            $.ajax({
	                type: "POST",
	                url: "/brand/popup",
	                data: {menu_id:combomenu_id,name:brand,name_mm:brandmm, search_key_mm:brandsmm,image:brandimage/*,cbomenuid:combomenu_id*/}
            }).done(function( result ) {
            	if(result=='This record is already exit'){
            		alert(result);
            	}else{
            		var options='<select class="span12 chosen" data-placeholder="Choose a Brand" tabindex="1" name="brand" id="brand">';
            		for(var i=0; i < result.length; i++){
            			options +="<option value='"+result[i].id+"'>"+result[i].name+" ("+result[i].name_mm+" )"+"</option>";
            		}
            		options +='</select>';
            		
            		$('#brandframe').html('');
            		$('#brandframe').html(options);
            		$('#brand').chosen();

            		$(".reveal-modal-bg").css('display',"none");
            		$("#update_form").removeClass('open');
            		$("#update_form").css('visibility','hidden');
            	}
            });
     	});

		
});


$('#shopupload').fileupload({
	dataType: 'json',
	progressall: function (e, data) {
	      var count=$('#progressbar').length;
	      if(count==0)
	        $(".gallery_container1").append('<li class="gallery_photo" id="progressbar"><div class="progress" id="progress"><span class="meter"></span></div></li>');
	      var progress = parseInt(data.loaded / data.total * 100, 10);
	      $('#progress span').css('width',progress+'%');
	  	},
	 
	done: function (e, data) {
		    $.each(data.result.files, function (index, file) {
		    if (file.error)
		    {
		      $("#progressbar").remove();
		      alert("Please check file size.");
              $(".gallery_photo").remove();
		      $('.upload1').show();
		      $('.upload1 span').html('+');
		    }else{
		      $(".gallery_photo").remove();
		      $('.upload1').hide();
		      $(".gallery_container1").append('<li class="gallery_photo"><img src='+file.thumbnail_url+'><span class="icon-cancel-circle" ><input type="hidden" name="gallery1" id="shopimage" value="'+file.name+'"></span><script>$(".gallery_photo").click(function(){$(this).remove(); $(".upload1").show();$(".upload1 span").html("+");});</script></li>');
		    }
		});
	}
});

$('#categoryupload').fileupload({
	dataType: 'json',
	progressall: function (e, data) {
	      var count=$('#progressbar').length;
	      if(count==0)
	        $(".gallery_container1").append('<li class="gallery_photo" id="progressbar"><div class="progress" id="progress"><span class="meter"></span></div></li>');
	      var progress = parseInt(data.loaded / data.total * 100, 10);
	      $('#progress span').css('width',progress+'%');
	  	},
	 
	done: function (e, data) {
		    $.each(data.result.files, function (index, file) {
		    if (file.error)
		    {
		      $("#progressbar").remove();
		      alert("Please check file size.");
              $(".gallery_photo").remove();
		      $('.upload1').show();
		      $('.upload1 span').html('+');
		    }else{
              $(".gallery_photo").remove();
		      
		      $('.upload1').hide();
		      $(".gallery_container1").append('<li class="gallery_photo"><img src='+file.thumbnail_url+'><span class="icon-cancel-circle" ><input type="hidden" name="gallery1" id="categoryimage" value="'+file.name+'"></span><script>$(".gallery_photo").click(function(){$(this).remove(); $(".upload1").show();$(".upload1 span").html("+");});</script></li>');
		    }
		});
	}
});

$('#subcategoryupload').fileupload({
	dataType: 'json',
	progressall: function (e, data) {
	      var count=$('#progressbar').length;
	      if(count==0)
	        $(".gallery_container1").append('<li class="gallery_photo" id="progressbar"><div class="progress" id="progress"><span class="meter"></span></div></li>');
	      var progress = parseInt(data.loaded / data.total * 100, 10);
	      $('#progress span').css('width',progress+'%');
	  	},
	 
	done: function (e, data) {
		    $.each(data.result.files, function (index, file) {
		    if (file.error)
		    {
		      $("#progressbar").remove();
		      alert("Please check file size.");
              $(".gallery_photo").remove();
		      $('.upload1').show();
		      $('.upload1 span').html('+');
		    }else{
		      $(".gallery_photo").remove();
		      $('.upload1').hide();
		      $(".gallery_container1").append('<li class="gallery_photo"><img src='+file.thumbnail_url+'><span class="icon-cancel-circle" ><input type="hidden" name="gallery1" id="subcategoryimage" value="'+file.name+'"></span><script>$(".gallery_photo").click(function(){$(this).remove(); $(".upload1").show();$(".upload1 span").html("+");});</script></li>');
		    }
		});
	}
});

$('#itemcategoryupload').fileupload({
	dataType: 'json',
	progressall: function (e, data) {
	      var count=$('#progressbar').length;
	      if(count==0)
	        $(".gallery_container1").append('<li class="gallery_photo" id="progressbar"><div class="progress" id="progress"><span class="meter"></span></div></li>');
	      var progress = parseInt(data.loaded / data.total * 100, 10);
	      $('#progress span').css('width',progress+'%');
	  	},
	 
	done: function (e, data) {
		    $.each(data.result.files, function (index, file) {
		    if (file.error)
		    {
		      $("#progressbar").remove();
		      alert("Please check file size.");
              $(".gallery_photo").remove();
		      $('.upload1').show();
		      $('.upload1 span').html('+');
		    }else{
              $(".gallery_photo").remove();
		      $('.upload1').hide();
		      $(".gallery_container1").append('<li class="gallery_photo"><img src='+file.thumbnail_url+'><span class="icon-cancel-circle" ><input type="hidden" name="gallery1" id="itemcategoryimage" value="'+file.name+'"></span><script>$(".gallery_photo").click(function(){$(this).remove(); $(".upload1").show();$(".upload1 span").html("+");});</script></li>');
		    }
		});
	}
});

$('#brandupload').fileupload({
	dataType: 'json',
	progressall: function (e, data) {
	      var count=$('#progressbar').length;
	      if(count==0)
	        $(".gallery_container1").append('<li class="gallery_photo" id="progressbar"><div class="progress" id="progress"><span class="meter"></span></div></li>');
	      var progress = parseInt(data.loaded / data.total * 100, 10);
	      $('#progress span').css('width',progress+'%');
	  	},
	 
	done: function (e, data) {
		    $.each(data.result.files, function (index, file) {
		    if (file.error)
		    {
		      $("#progressbar").remove();
		      alert("Please check file size.");
              $(".gallery_photo").remove();
		      $('.upload1').show();
		      $('.upload1 span').html('+');
		    }else{
		      $(".gallery_photo").remove();
		      $('.upload1').hide();
		      $(".gallery_container1").append('<li class="gallery_photo"><img src='+file.thumbnail_url+'><span class="icon-cancel-circle" ><input type="hidden" name="gallery1" id="brandimage" value="'+file.name+'"></span><script>$(".gallery_photo").click(function(){$(this).remove(); $(".upload1").show();$(".upload1 span").html("+");});</script></li>');
		      
            }
		});
	}
});

$('.updateitem').click(function(){
		itemid=this.id;
		$.get('/items/edit/'+itemid,function(data){
			var name=data.name;
			var name_mm=data.name_mm;
			var image=data.image;
			$('#itemname').val(name);
			$('#itemnamemm').val(name_mm);
			$(".gallery_photo img").attr("src", 'itemphoto/php/files/'+image);
      $('#itemid').val(itemid);
    });
	});

$('#btnitemupdate').click(function(){
  var itemid= $('#itemid').val();
  var itemname= $('#itemname').val();
  var itemnamemm= $('#itemnamemm').val();
  var image= $('#ch_image').val();
  $('.loading_indicator').addClass('loading');
  $.ajax({
    type: "POST",
    url: "/items/update/"+itemid,
    data: {name:itemname, name_mm:itemnamemm, image:image}
  }).done(function( result ) {
       window.location.href='/';
  });

});

	$('#gallery_upload1').fileupload({
        dataType: 'json',
        progressall: function (e, data) {
            var uploadedBytes =1048576;
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('.upload1 span').html(progress+'%')
            if(progress == 100){
              $('.upload1').hide();
            }
        },
       
        done: function (e, data) {
          $.each(data.result.files, function (index, file) {
            if (file.error)
            {
              $('.upload1 span').html('+');
              alert("File Size should be width between (480px - 200px) and height between (720px - 200px).");
               $('.upload1').show(); 
            }else{
              $(".gallery_container1").append('<li class="gallery_photo"><img src='+file.thumbnail_url+'><span class="icon-cancel-circle" ><input type="hidden" name="gallery1" id="ch_image" value="'+file.name+'"></span><script>$(".gallery_photo").click(function(){$(this).remove(); $(".upload1").show();$(".upload1 span").html("+");});</script></li>');
            }
          });
        }
  	});
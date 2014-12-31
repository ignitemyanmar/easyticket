$(document).ready(function(){
  $('#gallery_upload').fileupload({
          dataType: 'json',
          progressall: function (e, data) {
              // var iMaxFilesize = 1048576; // 1MB
              var uploadedBytes =1048576;//1MB max filesize
              var imgcount=$('.gallery_photo').length;
              var progress = parseInt(data.loaded / data.total * 100, 10);
              $('.upload span').html(progress+'% Loading...')
              if(progress == 100 && imgcount < 1){
                $('.upload span').html('+');
              }else{
               $('.upload').hide(); 
              }
          },
         
          done: function (e, data) {
            $.each(data.result.files, function (index, file) {
            if (file.error)
            {
              alert("Error : File size must be (453px X 600px) for portrait, (960px X 636px) for Landscape) and minimum width and height are 200px.");
            }else{
              $(".gallery_container").append('<li class="gallery_photo"><img src='+file.thumbnail_url+'><span class="icon-cancel-circle" ><input type="hidden" name="gallery[]" class="galleryimg" value="'+file.name+'"></span><script>$(".gallery_photo").click(function(){$(this).remove();$(".upload").show();$(".upload span").html("upload");});</script></li>');
            }
          });
          }
            
    });
  
  $('#gallery_upload1').fileupload({
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
              $('.upload1').show();
              $('.upload1 span').html('+');
            }else{
              $("#progressbar").remove();
              $('.upload1').hide();
              $(".gallery_container1").append('<li class="gallery_photo"><img src='+file.thumbnail_url+'><span class="icon-cancel-circle" ><input type="hidden" name="gallery1" value="'+file.name+'"></span><script>$(".gallery_photo").click(function(){$(this).remove(); $(".upload1").show();$(".upload1 span").html("+");});</script></li>');
            }
          });
          }
            
  });
});


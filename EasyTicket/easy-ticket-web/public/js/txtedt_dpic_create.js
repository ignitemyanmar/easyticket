$(document).ready(function(){
	/*tinymce.init({
      selector: "textarea#tinymce",
      theme: "modern",
      width: "100%",
      height: 300,
      plugins: [
           "hr anchor",
           "wordcount fullscreen insertdatetime",
           "table contextmenu textcolor"
     ],
     content_css: "src/skins/lightgray/content.min.css",
     toolbar: "undo redo | styleselect | bold italic removeformat | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor", 
     style_formats: [
          {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
          {title: 'Black text', inline: 'span', styles: {color: '#333333'}},
          {title: 'Red Bold', inline: 'b', styles: {color: '#ff0000'}},
          {title: 'Red heading 3', block: 'h3', styles: {color: '#ff0000'}},
          {title: 'Red heading 4', block: 'h4', styles: {color: '#ff0000'}},
          {title: 'Red heading 5', block: 'h5', styles: {color: '#ff0000'}},
          {title: 'Red heading 6', block: 'h6', styles: {color: '#ff0000'}},
          {title: 'Heading 3', block: 'h3', styles: {color: '#000000'}},
          {title: 'Table styles'},
          {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
      ]
 });
tinymce.init({
      selector: "textarea.tinymce",
      theme: "modern",
      width: "100%",
      height: 200,
      plugins: [
           "advlist autolink link lists charmap preview hr anchor pagebreak spellchecker",
           "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime nonbreaking",
           "save table contextmenu directionality emoticons template paste textcolor"
     ],
     content_css: "src/skins/lightgray/content.min.css",
     toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons", 
     style_formats: [
          {title: 'Bold text', inline: 'b'},
          {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
          {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
          {title: 'Example 1', inline: 'span', classes: 'example1'},
          {title: 'Example 2', inline: 'span', classes: 'example2'},
          {title: 'Table styles'},
          {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
      ]
 });

*/
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
/*
  $('#gallery_upload1').fileupload({
          dataType: 'json',
          progressall: function (e, data) {
              // var uploadedBytes =1048576;//1MB max filesize
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
              alert("File Size should be width between (480px - 200px) and height between (720px - 200px).");
              $('.upload1 span').html('+');              
              $('.upload1').show();
            }else{
              var imgcount=$('.gallery_photo').length;
              if(imgcount < 1){
                $('.upload1 span').html('+');
              }else{
               $('.upload1').remove(); 
              }
              $("#progressbar").remove();
              $(".gallery_container1").append('<li class="gallery_photo"><img src='+file.thumbnail_url+'><span class="icon-cancel-circle" ></span><input type="hidden" name="gallery[]" class="galleryimg" value="'+file.name+'"><script>$(".gallery_photo").click(function(){$(this).remove();$(".upload1").show();$(".upload1 span").html("+");});</script></li>');
            }
          });
          }
  });*/

  
  $('#gallery_upload1').fileupload({
          dataType: 'json',
          progressall: function (e, data) {
              /*// var iMaxFilesize = 1048576; // 1MB
              var uploadedBytes =1048576;//1MB max filesize
              var progress = parseInt(data.loaded / data.total * 100, 10);
              $('.upload1 span').html(progress+'% Loading...')
              if(progress == 100){
                $('.upload1').hide();
              }*/
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

    $('#gallery_upload2').fileupload({
          dataType: 'json',
          progressall: function (e, data) {
              // var iMaxFilesize = 1048576; // 1MB
              var uploadedBytes =1048576;//1MB max filesize
              var progress = parseInt(data.loaded / data.total * 100, 10);
              $('.upload2 span').html(progress+'% Loading...')
              if(progress == 100){
                $('.upload2').hide();
              }
          },
         
          done: function (e, data) {
            $.each(data.result.files, function (index, file) {
            if (file.error)
            {
              alert("Error : File size must be (453px X 600px) for portrait, (960px X 636px) for Landscape) and minimum width and height are 200px.");
              $('.upload2').show();
              $('.upload2 span').html('upload');
            }else{
              $(".gallery_container2").html('<li class="gallery_photo"><img src='+file.thumbnail_url+' style="max-height:98px; max-width:150px;"><span class="icon-cancel-circle" ><input type="hidden" name="gallery2" id="gallery2" value="'+file.name+'"></span><script>$(".gallery_photo").click(function(){$(this).remove(); $(".upload2").show();$(".upload2 span").html("upload");});</script></li>');
            }
          });
          }
            
    });
  $('#gallery_upload3').fileupload({
          dataType: 'json',
          progressall: function (e, data) {
              // var iMaxFilesize = 1048576; // 1MB
              var uploadedBytes =1048576;//1MB max filesize
              var progress = parseInt(data.loaded / data.total * 100, 10);
              $('.upload3 span').html(progress+'% Loading...')
              if(progress == 100){
                $('.upload3').hide();
              }
          },
         
          done: function (e, data) {
            $.each(data.result.files, function (index, file) {
            if (file.error)
            {
              alert("Error : File size must be (453px X 600px) for portrait, (960px X 636px) for Landscape) and minimum width and height are 200px.");
              $('.upload3').show();
              $('.upload3 span').html('upload');
            }else{
              $(".gallery_container3").html('<li class="gallery_photo"><img src='+file.thumbnail_url+' style="max-height:98px; max-width:150px;"><span class="icon-cancel-circle" ><input type="hidden" name="gallery3" id="gallery3" value="'+file.name+'"></span><script>$(".gallery_photo").click(function(){$(this).remove(); $(".upload3").show();$(".upload3 span").html("upload");});</script></li>');
            }
          });
          }
            
    });
	
});


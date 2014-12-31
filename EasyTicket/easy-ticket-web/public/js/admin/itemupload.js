$(function(){
});

  function reloadchosen(){
    if($('.chosen').size() > 0) {
      var els=$('.chosen');
      els.each(function () {
          $(this).chosen();
      })
    }
  }
  
  function loadcategories(menu_id){
      $('#categoryframe').hide();
      $('#categoryloading').show();
          $.get('/categorylist/'+menu_id,function(data){
          $("#categoryframe").show();
          $("#categoryframe").html(data);
          $('#categoryloading').hide();
          reloadchosen();          
      });
  }

  $('#menu').change(function(){
    $('#categoryframe').hide();
    var menu_id=$(this).val();
    $('#categoryloading').show();
        $.get('/categorylist/'+menu_id,function(data){
          $('#categoryloading').hide();
          $('#categoryframe').show();
          $('#categoryframe').html(data);
          reloadchosen();
    });
  });

  $('#category').change(function(){
        $('#subcategoryframe').hide();
        var catid=$(this).val();
        loadsubcategories(catid);
          /*$('#subcategoryloading').show();
            $.get('/subcategorylist/'+catid,function(data){
              $('#subcategoryframe').show();
              $('#subcategoryframe').html(data);
              $('#subcategoryloading').hide();
              $('#hdcategoryid').val(catid);
              reloadchosen();
          });*/
            if(catid){
              $.get('/itemsizelist/'+catid,function(data){
              $('#itemsizeframe').show();
              $('#itemsizeframe').html(data);
              $('#itemsizeloading').hide();
              reloadchosen();
          });
          }
        $('#subcategoryframe').show();
  });

  $('#subcategory').change(function(){
    $('#itemcategoryframe').hide();
    var subcatid=$(this).val();
    $('#itemcategoryloading').show();
        $.get('/itemcategorylist/'+subcatid,function(data){
        $('#itemcategoryframe').show();
        $('#itemcategoryframe').html(data);
        $('#itemcategoryloading').hide();
        reloadchosen();
    });
  });

  function loadsubcategories(catid) {
    $('#subcategoryframe').hide();
    $('#subcategoryloading').show();
        $.get('/subcategorylist/'+catid,function(data){
          $('#subcategoryframe').show();
          $('#subcategoryframe').html(data);
          $('#subcategoryloading').hide();
          reloadchosen();
        });
  }

  function loaditemcategories(subcatid) {
    $('#itemcategoryframe').hide();
    $('#itemcategoryloading').show();
      $.get('/itemcategorylist/'+subcatid,function(data){
              $('#itemcategoryframe').show();
              $('#itemcategoryframe').html(data);
              $('#itemcategoryloading').hide();
              reloadchosen();
          });
  }

  function loaditemsize(catid) {
    $('#itemsizeframe').hide();
    $('#itemsizeloading').show();
        $.get('/itemsizelist/'+catid,function(data){
        $('#itemsizeframe').show();
        $('#itemsizeframe').html(data);
        $('#itemsizeloading').hide();
        reloadchosen();
    });
  }

  $('#gallery_upload').fileupload({
          dataType: 'json',
          progressall: function (e, data) {
              // var iMaxFilesize = 1048576; // 1MB
              var uploadedBytes =1848576;//1MB max filesize
              var imgcount=$('.gallery_photo').length;
              var progress = parseInt(data.loaded / data.total * 100, 10);
              $('.upload span').html(progress+'%')
              if(progress == 100 && imgcount < 4){
                $('.upload span').html('+');
              }else{
               $('.upload').hide(); 
              }
          },
         
          done: function (e, data) {
            $.each(data.result.files, function (index, file) {
            if (file.error)
            {
              alert("File Size should be width between (480px - 200px) and height between (720px - 200px).");
              $('.upload span').html('+');              
              $('.upload').show();
            }else{
              $(".gallery_container").append('<li class="gallery_photo"><img src='+file.thumbnail_url+'><span class="icon-cancel-circle" ><input type="hidden" name="gallery[]" class="galleryimg" value="'+file.name+'"></span><script>$(".gallery_photo").click(function(){$(this).remove();$(".upload").show();$(".upload span").html("+");});</script></li>');
            }
          });
          }
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
              $(".gallery_container1").append('<li class="gallery_photo"><img src='+file.thumbnail_url+'><span class="icon-cancel-circle" ><input type="hidden" name="gallery1" value="'+file.name+'"></span><script>$(".gallery_photo").click(function(){$(this).remove(); $(".upload1").show();$(".upload1 span").html("+");});</script></li>');
            }
          });
        }
  });
  
  function isNumberKey(evt)
  {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57) )
      return false;
    
    return true;
  }




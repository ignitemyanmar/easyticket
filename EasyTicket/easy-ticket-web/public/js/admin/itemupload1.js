$(function(){
  $('.removerow').each(function(idx){
      if(idx==0){
        $(this).attr('disabled',true);
      }
    });

    $('.btnadd').click(
        function(e){
          e.preventDefault();
            var curMaxInput = $('.items').length;
            $('#itemlistinfo .items:first')
                .clone()
                .insertAfter($('#itemlistinfo .items:last'));

            var totalrow=$('.items').length;
            /*for(i=0; i<totalrow; i++){
              if(i>0){
                tlength=$('#s2id_autogen1').length;
                if(tlength > 0)
                  tl= $('.items #s2id_autogen1').length;
                if(tl>1)
                  $('.items #s2id_autogen1')[i].remove();
                  $('#itemsizeframe .s2id_size:first').remove();
              }
            }*/

            $('.removerow')
                .removeAttr('disabled');
            if ($('#itemlistinfo .items').length >= 8) {
                $('#addRow')
                    .attr('disabled',true);
            }
            var maxcolorsize=$('.color').length;
            $('.color').each(function(idx) {
                if(maxcolorsize == (idx + 1) ){
                  // $(this).select2();
                }
              });

            var maxsizesize=$('.size').length;
            $('.size').each(function(idx) {
                if(maxsizesize == (idx + 1) ){
                  // $(this).select2();
                }
              });
            return false;
    });

    $('.btnaddqtyrange').click(function(e){
      e.preventDefault();
            var curMaxInput = $('.qtyranges').length;
            $('#quantityrangeinfo .qtyranges:first')
                .clone()
                .insertAfter($('#quantityrangeinfo .qtyranges:last'));
          
            /*var totalrow=$('.qtyranges').length;
            for(i=0; i<totalrow; i++){
              if(i>0){
                tlength=$('#s2id_autogen1').length;
                if(tlength > 0)
                  tl= $('.qtyranges #s2id_autogen1').length;
                if(tl>1)
                  $('.qtyranges #s2id_autogen1')[i].remove();
              }
            }*/
            if($('#quantityrangeinfo .qtyranges').length >= 8) {
                $('#addRow')
                    .attr('disabled',true);
            }

            var maxcolorsize=$('.qtyrange').length;
            /*$('.qtyrange').each(function(idx) {
                if(maxcolorsize == (idx + 1) ){
                  $(this).select2();
                }
              });*/

            return false;
    });

    $('.btncompareprice').click(
        function(){
            var curMaxInput = $('.comparepricediv').length;
            $('#comparepriceinfo .comparepricediv:first')
                .clone()
                .insertAfter($('#comparepriceinfo .comparepricediv:last'));
            $('.removerow')
                .removeAttr('disabled');
            if($('#comparepriceinfo .comparepricediv').length >= 8) {
                $('#addRow')
                    .attr('disabled',true);
            }

            return false;
    });
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
      $('#popupcategoryloading').show();
          $.get('/categorylist/'+menu_id,function(data){
          $("#categoryframe").show();
          $("#categoryframe").html(data);
          $('#categoryloading').hide();

          $("#popupcategoryframe").html(data);
          $('#popupcategoryloading').hide();
          reloadchosen();          
      });
  }

  $('#menu').change(function(){
    $('#categoryframe').hide();
    var menu_id=$(this).val();
    loadbrands(menu_id);
    loadcategories(menu_id);
  });

  function loadbrands(menu_id){
      $('#brandframe').hide();
      $('#brandloading').show();
          $.get('/brandlist/'+menu_id,function(data){
          $("#brandframe").show();
          $("#brandframe").html(data);
          $('#brandloading').hide();
          reloadchosen();          
      });
  }

  $('#category').change(function(){
        $('#subcategoryframe').hide();
        var catid=$(this).val();
        loadsubcategories(catid);
        loaditemsize(catid);
          /*if(catid){
              $.get('/itemsizelist/'+catid,function(data){
                  $('#itemsizeframe').show();
                  $('#itemsizeframe').html(data);
                  $('#itemsizeloading').hide();
                  reloadchosen();
              });
          }*/
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
              var count=$('#progressbar').length;
              if(count==0)
                $(".gallery_container").append('<li class="gallery_photo progressbars" id="progressbar"><div class="progress" id="progress"><span class="meter"></span></div></li>');
              var progress = parseInt(data.loaded / data.total * 100, 10);
              $('#progress span').css('width',progress+'%');

              var imgcount=$('.gallery_photo').length;
              if(progress == 100 && imgcount < 5){
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
                $('.progressbars').remove();            
                $('.upload').show();
              }else{
                $('.progressbars').remove();            
                $(".gallery_container").append('<li class="gallery_photo"><img src='+file.thumbnail_url+'><span class="icon-cancel-circle" ><input type="hidden" name="gallery[]" class="galleryimg" value="'+file.name+'"></span><script>$(".gallery_photo").click(function(){$(this).remove();$(".upload").show();$(".upload span").html("+");});</script></li>');
              }
            });
          }
  });

  $('#gallery_upload1').fileupload({
        dataType: 'json',
        progressall: function (e, data) {
             var uploadedBytes =1848576;//1MB max filesize
              var count=$('#progressbar').length;
              if(count==0)
                $(".gallery_container1").append('<li class="gallery_photo progressbars" id="progressbar"><div class="progress" id="progress"><span class="meter"></span></div></li>');
              var progress = parseInt(data.loaded / data.total * 100, 10);
              $('#progress span').css('width',progress+'%');
              if(progress == 100){
                $('.upload1').hide(); 
              }
        },
       
        done: function (e, data) {
          $.each(data.result.files, function (index, file) {
            if (file.error)
            {
              $('.upload1 span').html('+');
              $('.progressbars').remove();
              alert("File Size should be width between (480px - 200px) and height between (720px - 200px).");
               $('.upload1').show(); 
            }else{
              $('.progressbars').remove();
              $(".gallery_container1").append('<li class="gallery_photo"><img src='+file.thumbnail_url+'><span class="icon-cancel-circle" ><input type="hidden" name="gallery1" value="'+file.name+'"></span><script>$(".gallery_photo").click(function(){$(this).remove(); $(".upload1").show();$(".upload1 span").html("+");});</script></li>');
            }
          });
        }
  });

  function removerow(obj){
          var objs=$(obj).parent().parent().parent();
          if ($('#itemlistinfo .items').length > 1) {
              objs.remove();
          }
          if ($('#itemlistinfo .items').length <= 1) {
              $('.removerows')
                  .attr('disabled', true);
          }
          else if ($('#itemlistinfo .items').length < 8) {
              $('#addRow')
                  .removeAttr('disabled');
          }
          return false;
  }

  function removeqtyrangerow(obj){
          var objs=$(obj).parent().parent().parent().parent();
          if ($('#quantityrangeinfo .qtyranges').length > 1) {
              objs.remove();
          }
          if ($('#quantityrangeinfo .qtyranges').size() <= 1) {
              $('.removerow')
                  .attr('disabled', true);
          }
          else if ($('#quantityrangeinfo .qtyranges').length < 8) {
              $('#addRow')
                  .removeAttr('disabled');
          }
          return false;
  }

  function removecompareprice(obj){
          var objs=$(obj).parent().parent().parent().parent();
          if ($('#comparepriceinfo .comparepricediv').length > 1) {
              objs.remove();
          }
          if ($('#comparepriceinfo .comparepricediv').size() <= 1) {
              $('.removerow')
                  .attr('disabled', true);
          }
          else if ($('#comparepriceinfo .comparepricediv').length < 8) {
              $('#addRow')
                  .removeAttr('disabled');
          }
          return false;
  }
  
  
  function isNumberKey(evt)
  {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57) )
      return false;
    
    return true;
  }




$(function(){
      // checkextendcity();
     var _token = $('.access_token').val();
     $('#seatplanview').html('');      
     seatplan_id=$('#seatplan').val();
     links="/trip/seatplan/"+seatplan_id+"?access_token="+_token;
     $.ajax({
       type: "GET",
       url: links,
       data: {}
     }).done(function(result) {
           $('#seatplanview').html(result);         
     });

     $('#seatplan').change(function(){
        $('#seatplanview').html('');      
        seatplan_id=$(this).val();
        links="/trip/seatplan/"+seatplan_id+"?access_token="+_token;
        $.ajax({
          type: "GET",
          url: links,
          data: {}
        }).done(function(result) {
              $('#seatplanview').html(result);         
         });
     });


     var val_radio=$('.departuredays:checked').val();
     if(val_radio=="custom"){
        onlyone.style.display='none';
       customdays.style.display='block';
     }else if(val_radio=="onlyone"){
       onlyone.style.display='block';
       customdays.style.display='none';
       $("#onlyone_day").datepicker({
            numberOfMonth: 2,
            dateFormat: 'yy-mm-dd'
         });
     }else{
       onlyone.style.display='none';
       customdays.style.display='none';
     }
      $(".departuredays").each(function(){
        $(this).click(function(){
           var val_radio=$(this).val();
           if(val_radio=="custom"){
             onlyone.style.display='none';
             customdays.style.display='block';
           }else if(val_radio=="onlyone"){
             onlyone.style.display='block';
             customdays.style.display='none';
             $("#onlyone_day").datepicker({
                numberOfMonth: 2,
                dateFormat: 'yy-mm-dd'
             });
           }else{
             onlyone.style.display='none';
             customdays.style.display='none';
           }
        });
     });
      $(".chkextend").each(function(){
        $(this).click(function(){
          var val_radio=$(this).val();
          if(val_radio=="1"){
             $('#extend_frame').show();
          }else{
             $('#extend_frame').hide();
          }
        });
      });


  });

 /* function checkextendcity(){
    
  }*/
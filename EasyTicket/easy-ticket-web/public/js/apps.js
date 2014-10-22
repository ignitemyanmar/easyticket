$(function(){
     $('#seatplanview').html('');      
     seatplan_id=$('#seatplan').val();
     links="/trip/seatplan/"+seatplan_id;
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
        links="/trip/seatplan/"+seatplan_id;
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
       customdays.style.display='block';
     }else{
       customdays.style.display='none';
     }
     $(".departuredays").each(function(){
        $(this).click(function(){
           var val_radio=$(this).val();
           if(val_radio=="custom"){
             customdays.style.display='block';
           }else{
             customdays.style.display='none';
           }
        });
     });
  });

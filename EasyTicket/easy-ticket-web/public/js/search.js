$(function(){
   /*$('#from').select2();
   $('#to').select2();
   $('#departure_time').select2();*/
   var date = new Date();
   var m = date.getMonth(), d = date.getDate()-30, y = date.getFullYear();
   $("#startdate").datepicker({
      minDate: new Date(y, m, d),
      numberOfMonth: 2,
      onSelect: function(dateStr) {
            var min = $(this).datepicker('getDate');
      },
      dateFormat: 'yy-mm-dd'
   });
   $("#enddate").datepicker({
      minDate: new Date(y, m, d),
      numberOfMonth: 2,
      onSelect: function(dateStr) {
            var min = $(this).datepicker('getDate');
      },
      dateFormat: 'yy-mm-dd'
   });
});
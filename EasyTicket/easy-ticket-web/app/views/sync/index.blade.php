@extends('../admin')
@section('content')
   <style type="text/css">
      .loading{
         width:32px;
         height:32px;
         background: url('../../img/loader.gif') no-repeat;
      }
   </style>
   <link rel="stylesheet" href="assets/data-tables/DT_bootstrap.css" />
   <link rel="shortcut icon" href="favicon.ico" />
   <!-- BEGIN PAGE -->
      <div class="page-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
               <div class="row-fluid">
                  <div class="span12">
                     <!-- BEGIN PAGE TITLE & BREADCRUMB-->        
                     <h3 class="page-title">
                        Synchronization            
                        <small></small>
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="#">Home</a> 
                           <i class="icon-angle-right"></i>
                        </li>
                        <li><a href="/sync">Sync</a></li>
                        
                     </ul>
                     <!-- END PAGE TITLE & BREADCRUMB-->
                  </div>
               </div>
            <!-- END PAGE HEADER-->
            <div id="dashboard">
               <!-- BEGIN PAGE -->
                  <div class="row-fluid">
                     <div>
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet">
                           <div class="portlet-title">
                              <h4><i class="icon-edit"></i>Synchronization</h4>
                           </div>
                           <div class="portlet-body">

                                 <div class="alert" style="display:none;">
                                    <span></span>
                                    <button class="close" data-dismiss="alert"></button>
                                 </div>

                                 <div id="myModal2" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="false">
                                    <form action="/import_saleorder" method="post" enctype="multipart/form-data">
                                    <div class="modal-header">
                                       <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                       <h3>Import Sale Order</h3>
                                    </div>
                                    <div class="modal-body">
                                       <h5>Select file to upload:</h5>
                                       <input type="file" name="file" required>
                                    </div>
                                    <div class="modal-footer">
                                       <input class="btn green" type="submit" value="Upload" />
                                       <!-- <button class="btn red" data-dismiss="modal" aria-hidden="true" id="close-dialog">Close</button> -->
                                    </div>
                                    </form>
                                 </div>
                                 
                                 <div id="myModal1" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="false">
                                    <div class="modal-header">
                                       <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                       <h3>Sync</h3>
                                       <h5>Please wait, we are transferring your data...</h5>
                                    </div>
                                    <div class="modal-body">
                                       <div id="message_list" style="display:none;">
                                          
                                       </div>
                                       <div id="upload_list" style="display:none;">
                                          <p class="alert" id="up_pg_trip"><strong>3.Message:</strong> <span id="message-up_pg_trip">Wainting for connect to Server...</span></p>
                                          <p class="alert" id="up_pg_extradestination"><strong>3.Message:</strong> <span id="message-up_pg_extradestination">Wainting for connect to Server...</span></p>
                                          <!-- <p class="alert" id="up_pg_delsaleorder"><strong>3.Message:</strong> <span id="message-up_pg_delsaleorder">Wainting for connect to Server...</span></p> -->
                                          <p class="alert" id="up_pg_sale_order"><strong>1.Message:</strong> <span id="message-up_pg_sale_order">Wainting for connect to Server...</span></p>
                                          <p class="alert" id="up_pg_payment"><strong>2.Message:</strong> <span id="message-up_pg_payment">Wainting for connect to Server...</span></p>
                                       </div>
                                       <div id="download_list" style="display:none;">
                                          <!-- <p class="alert" id="pg_trip"><strong>1.Message:</strong> <span id="message-pg_trip">Wainting for connect to Server...</span></p> -->
                                          <!-- <p class="alert" id="pg_del_trip"><strong>2.Message:</strong> <span id="message-pg_del_trip">Wainting for connect to Server...</span></p> -->
                                          <!-- <p class="alert" id="pg_seating_plan"><strong>3.Message:</strong> <span id="message-pg_seating_plan">Wainting for connect to Server...</span></p> -->
                                          <p class="alert" id="pg_agent"><strong>4.Message:</strong> <span id="message-pg_agent">Wainting for connect to Server...</span></p>
                                          <p class="alert" id="pg_agent_group"><strong>5.Message:</strong> <span id="message-pg_agent_group">Wainting for connect to Server...</span></p>
                                          <!-- <p class="alert" id="pg_city"><strong>6.Message:</strong> <span id="message-pg_city">Wainting for connect to Server...</span></p> -->
                                          <!-- <p class="alert" id="pg_extra_destination"><strong>7.Message:</strong> <span id="message-pg_extra_destination">Wainting for connect to Server...</span></p> -->
                                          <!-- <p class="alert" id="pg_classes"><strong>8.Message:</strong> <span id="message-pg_classes">Wainting for connect to Server...</span></p> -->
                                          <!-- <p class="alert" id="pg_agent_commission"><strong>9.Message:</strong> <span id="message-pg_agent_commission">Wainting for connect to Server...</span></p> -->
                                          <!-- <p class="alert" id="pg_cls_seatinfo"><strong>10.Message:</strong> <span id="message-pg_cls_seatinfo">Wainting for connect to Server...</span></p> -->
                                          <!-- <p class="alert" id="pg_commission_type"><strong>11.Message:</strong> <span id="message-pg_commission_type">Wainting for connect to Server...</span></p> -->
                                          <!-- <p class="alert" id="pg_op_group"><strong>12.Message:</strong> <span id="message-pg_op_group">Wainting for connect to Server...</span></p> -->
                                          <!-- <p class="alert" id="pg_opg_user"><strong>13.Message:</strong> <span id="message-pg_opg_user">Wainting for connect to Server...</span></p> -->
                                          <!-- <p class="alert" id="pg_user"><strong>14.Message:</strong> <span id="message-pg_user">Wainting for connect to Server...</span></p> -->
                                          <!-- <p class="alert" id="pg_operator"><strong>15.Message:</strong> <span id="message-pg_operator">Wainting for connect to Server...</span></p> -->
                                          <!-- <p class="alert" id="pg_seat_info"><strong>16.Message:</strong> <span id="message-pg_seat_info">Wainting for connect to Server...</span></p> -->
                                          <p class="alert" id="pg_saleorder"><strong>17.Message:</strong> <span id="message-pg_saleorder">Wainting for connect to Server...</span></p>
                                          <!-- <p class="alert" id="pg_del_saleorder"><strong>18.Message:</strong> <span id="message-pg_del_saleorder">Wainting for connect to Server...</span></p> -->
                                       </div>
                                       
                                    </div>
                                    <div class="modal-footer">
                                       <div id="file_upload" style="display:none;">
                                          <p>Lenght: <span id="uploaded"></span> kb</p>
                                          <div class="progress progress-striped progress-warning">
                                             <div style="width: 0%;" class="bar"></div>
                                          </div>
                                       </div>
                                       <button class="btn red" data-dismiss="modal" aria-hidden="true" id="close-dialog">Close</button>
                                    </div>
                                 </div>

                                 <div class="control-group">
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Today Sale Order" button.</span>
                                       <p>
                                          <a  href="#myModal1" role="button" data-toggle="modal" class="btn green big" id="sync_to_server">Sync Today Sale Order <i class="m-icon-big-swapup m-icon-white"></i></a></div>
                                       </p>
                                    </div>
                                    <!-- <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync from Server" button.</span>
                                       <p>
                                          <a  href="#myModal1" role="button" data-toggle="modal" class="btn yellow big" id="sync_from_server">Sync from Server <i class="m-icon-big-swapdown m-icon-white"></i></a></div>
                                       </p>
                                    </div> -->
                                    <br>
                                    @if(Session::has('message'))
                                       <?php $message=Session::get('message'); ?>
                                       <div class="alert alert-error">
                                          <span>{{$message}}</span>
                                          <button class="close" data-dismiss="alert"></button>
                                       </div>
                                    @endif
                                    <div class="controls">
                                       <span class="help-inline">Backup Sale Order</span>
                                       <p>
                                          <a  href="#" role="button" data-toggle="modal" class="btn yellow big" id="backup">Backup Sale Order <i class="m-icon-big-swapdown m-icon-white"></i></a></div>
                                       </p>
                                       <br>
                                       @if(Session::has('import_message'))
                                          <?php $import_message = Session::get('import_message'); ?>
                                          <div class="alert alert-success">
                                             <span>{{$import_message}}</span>
                                             <button class="close" data-dismiss="alert"></button>
                                          </div>
                                       @endif
                                    
                                       <span class="help-inline">Import Sale Order</span>
                                       <p>
                                          <a  href="#myModal2" role="button" data-toggle="modal" class="btn blue big" id="import_saleorder">Import Sale Order <i class="m-icon-big-swapup m-icon-white"></i></a></div>
                                       </p>
                                    </div>
                                 </div>                                    
                              
                           </div>
                           
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                     </div>
                  </div>
                  
               <!-- END PAGE -->               

            </div>
         </div>
         <!-- END PAGE CONTAINER-->    
      </div>
      <!-- END PAGE --> 

   <script type="text/javascript">
   /**
    * Sync To Server
    */

   $('#sync_to_server').click(function(){
      $('.progress-striped').addClass('active');
      $('#upload_list').show();
      $('#download_list').hide(); 
      syncTripToServer('up_pg_trip');
   });

   /*
    * jqXHR Response Callback
    */
   var jqxhrUploadResponse = function(response, _obj, _obj_name){
      
         $('.progress-striped').removeClass('active');

         if(progressUploadInterval) {
            $('#'+_obj).removeClass('alert-info');
            $('#'+_obj).addClass('alert-success');
            $('#message-'+_obj).remove();
            $('#'+_obj).append('<span>Successfully sync to ['+_obj_name+'].</span><button class="close" data-dismiss="alert"></button>')
            clearInterval(progressUploadInterval);
         }


   }

   var syncTripToServer = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $('.progress-striped').addClass('active');
      $('#message').html('Connecting to server...');
      $('#file').html('');
      $.ajax({
        type: "GET",
        url: "/uploadtripjson/"+sync_id,
        data: null
      })
      .done(function( response ) {

         jqxhrUploadResponse(response, that, 'Trip');
         syncExtraDestinationToServer('up_pg_extradestination');
         
      });

      progressUploadCallback(sync_id);
   }

   var syncExtraDestinationToServer = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $('.progress-striped').addClass('active');
      $('#message').html('Connecting to server...');
      $('#file').html('');
      $.ajax({
        type: "GET",
        url: "/uploadextradestinationjson/"+sync_id,
        data: null
      })
      .done(function( response ) {

         jqxhrUploadResponse(response, that, 'ExtraDestination');
         //syncDelSaleOrderToServer('up_pg_delsaleorder');
         syncSaleOrderToServer('up_pg_sale_order');
         
      });

      progressUploadCallback(sync_id);
   }

   var syncDelSaleOrderToServer = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $('.progress-striped').addClass('active');
      $('#message').html('Connecting to server...');
      $('#file').html('');
      $.ajax({
        type: "GET",
        url: "/uploaddelsaleorderjson/"+sync_id,
        data: null
      })
      .done(function( response ) {

         jqxhrUploadResponse(response, that, 'Deleted Sale Order');
         syncSaleOrderToServer('up_pg_sale_order');
         
      });

      progressUploadCallback(sync_id);
   }


   var syncSaleOrderToServer = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/uploadjson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         jqxhrUploadResponse(response, that, 'Sale Order');
         if(response.status_code === -1){
            var result = "";
            for (var i = 0; i <response.agents.length; i++) {
               result += '<p class="alert alert-error"><strong>'+i+'.Message: </strong><span>Please defind agent ['+response.agents[i].name+']\'s code.</span></p>';
            };
            $('#message_list').html(result);
            $('#message_list').show();
            $('#upload_list').hide();
            return false;
         }
         syncPaymantToServer('up_pg_payment');
      });
      progressUploadCallback(sync_id);
   }

   var syncPaymantToServer = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $('.progress-striped').addClass('active');
      $('#message').html('Connecting to server...');
      $('#file').html('');
      $.ajax({
        type: "GET",
        url: "/uploadpaymentjson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         
         if(response.status_code === 1){
            // alert( "Data Saved: " + response.message );
            $('.alert').removeClass('alert-success alert-error');
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert span').html(response.message);
            //$('#myModal1').modal('hide');
         }
         else if(response.status_code === 0){
            // alert( "Error: " + response.message);
            $('.alert').removeClass('alert-success alert-error');
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert span').html(response.message);
            $('#myModal1').modal('hide');
         }else{
            
            $('#myModal1').modal('hide');
            location.reload();
         }
          
                   
      });

      progressUploadCallback(sync_id);
   }

   
   $('#sync_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $('#download_list').show();
      $('#upload_list').hide();     
      syncAgent('pg_agent');
   });

   $('#backup').click(function(){
      window.location.href="/backup_saleorder";
   });

   /*
    * jqXHR Response Callback
    */
   var jqxhrResponse = function(response, _obj, _obj_name){
      
         $('.progress-striped').removeClass('active');

         if(progressDownloadInterval) {
            $('#'+_obj).removeClass('alert-info');
            $('#'+_obj).addClass('alert-success');
            $('#message-'+_obj).remove();
            $('#'+_obj).append('<span>Successfully sync to ['+_obj_name+'].</span><button class="close" data-dismiss="alert"></button>')
            clearInterval(progressDownloadInterval);
         }
   }
   
   $('#sync_trip_from_server').click(function(){
      syncTrip('pg_trip');  
   });
   var syncTrip = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/downloadtripjson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         jqxhrResponse(response, that, 'Trip');
         syncDeleteTrip('pg_del_trip');         
      });
      progressDownloadCallback(sync_id);
   }
   $('#sync_del_trip_from_server').click(function(){
      syncDeleteTrip('pg_del_trip');      
   });
   var syncDeleteTrip = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/downloaddeletetripjson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         jqxhrResponse(response, that, 'Deleted Trip');
         syncSeatingPlan('pg_seating_plan');        
      });
      progressDownloadCallback(sync_id);
   }
   $('#sync_seatingplan_from_server').click(function(){
      syncSeatingPlan('pg_seating_plan');      
   });
   var syncSeatingPlan = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/downloadseatingplanjson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         jqxhrResponse(response, that, 'Seating Plan');
         syncAgent('pg_agent');        
      });
      progressDownloadCallback(sync_id);
   }
   $('#sync_agent_from_server').click(function(){
      syncAgent('pg_agent');      
   });
   var syncAgent = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/downloadagentjson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         jqxhrResponse(response, that, 'Agent');
         syncAgentGroup('pg_agent_group');         
      });
      progressDownloadCallback(sync_id);
   }
   $('#sync_agentgroup_from_server').click(function(){
      syncAgentGroup('pg_agent_group');      
   });
   var syncAgentGroup = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/downloadagentgroupjson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         jqxhrResponse(response, that, 'Agent Group'); 
         syncSaleOrder('pg_saleorder'); 
      });
      progressDownloadCallback(sync_id);
   }
   $('#sync_city_from_server').click(function(){
      syncCity('pg_city');      
   });
   var syncCity = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/downloadcityjson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         jqxhrResponse(response, that, 'City');
         syncExtraDestination('pg_extra_destination');         
      });
      progressDownloadCallback(sync_id);
   }

   $('#sync_extradestination_from_server').click(function(){
      syncExtraDestination('pg_extra_destination');      
   });

   var syncExtraDestination = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/downloadextradestinationjson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         jqxhrResponse(response, that, 'Extra Destination');
         syncClasses('pg_classes');         
      });
      progressDownloadCallback(sync_id);
   }

   $('#sync_classes_from_server').click(function(){
      syncClasses('pg_classes');
   });

   var syncClasses = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/downloadclassesjson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         jqxhrResponse(response, that, 'Bus Class');
         syncAgentCommission('pg_agent_commission');         
      });
      progressDownloadCallback(sync_id);
   }

   $('#sync_agentcommission_from_server').click(function(){
      syncAgentCommission('pg_agent_commission');      
   });

   var syncAgentCommission = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/downloadagentcommissionjson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         jqxhrResponse(response, that, 'Agent Commission');
         syncCloseSeatInfo('pg_cls_seatinfo');         
      });
      progressDownloadCallback(sync_id);
   }

   $('#sync_closeseatinfo_from_server').click(function(){
      syncCloseSeatInfo('pg_cls_seatinfo');      
   });
   var syncCloseSeatInfo = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/downloadcloseseatinfojson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         jqxhrResponse(response, that, 'Closed Seat Info');
         syncCommissionType('pg_commission_type');         
      });
      progressDownloadCallback(sync_id);
   }

   $('#sync_commissiontype_from_server').click(function(){
      syncCommissionType('pg_commission_type');      
   });

   var syncCommissionType = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/downloadcomissiontypejson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         jqxhrResponse(response, that, 'Commission Type'); 
         syncOperatorGroup('pg_op_group');        
      });
      progressDownloadCallback(sync_id);
   }

   $('#sync_operatorgroup_from_server').click(function(){
      syncOperatorGroup('pg_op_group');      
   });

   var syncOperatorGroup = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/downloadoperatorgroupjson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         jqxhrResponse(response, that, 'Operator Group'); 
         syncOperatorGroupUser('pg_opg_user');        
      });
      progressDownloadCallback(sync_id);
   }

   $('#sync_operatorgroupuser_from_server').click(function(){
      syncOperatorGroupUser('pg_opg_user');      
   });

   var syncOperatorGroupUser = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/downloadoperatorgroupuserjson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         jqxhrResponse(response, that, 'Operator Group User');
         syncUser('pg_user');         
      });
      progressDownloadCallback(sync_id);
   }

   $('#sync_user_from_server').click(function(){
      syncUser('pg_user');      
   });
   var syncUser = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/downloaduserjson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         jqxhrResponse(response, that, 'User');  
         syncOperator('pg_operator');       
      });
      progressDownloadCallback(sync_id);
   }
   $('#sync_operator_from_server').click(function(){
      syncOperator('pg_operator');      
   });
   var syncOperator = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/downloadoperatorjson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         jqxhrResponse(response, that, 'Operator');
         syncSeatInfo('pg_seat_info');         
      });
      progressDownloadCallback(sync_id);
   }
   $('#sync_seatinfo_from_server').click(function(){
      syncSeatInfo('pg_seat_info');      
   });
   var syncSeatInfo = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/downloadseatinfojson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         jqxhrResponse(response, that, 'Seat Info');  
          
         syncDeleteSaleOrder('pg_del_saleorder');      
      });
      progressDownloadCallback(sync_id);
   }
   $('#sync_del_saleorder_from_server').click(function(){
      syncDeleteSaleOrder('pg_del_saleorder');      
   });
   var syncDeleteSaleOrder = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/downloaddeletesaleorderjson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         
         jqxhrResponse(response, that, 'Deleted Sale Order'); 
         syncSaleOrder('pg_saleorder');        
      });
      progressDownloadCallback(sync_id);
   }
   $('#sync_saleorder_from_server').click(function(){
      syncSaleOrder('pg_saleorder');      
   });
   var syncSaleOrder = function(sync_id){
      var that = sync_id;
      $('#'+that).addClass('alert-info');
      $.ajax({
        type: "GET",
        url: "/downloadsaleorderjson/"+sync_id,
        data: null
      })
      .done(function( response ) {
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            $('.alert').removeClass('alert-success alert-error');
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert span').html(response.message);
            $('#myModal1').modal('hide');
         }
         else if(response.status_code === 0){
            $('.alert').removeClass('alert-success alert-error');
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert span').html(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         jqxhrResponse(response, that, 'Sale Order');
      });
      progressDownloadCallback(sync_id);
   }

   var progressUploadInterval

   var progressUploadCallback = function(sync_id){
      progressUploadInterval = setInterval(function(){
         $.ajax({
           type: "GET",
           url: "/getupprogress/"+sync_id,
           data: null
         })
         .always(function( data ) {
            response = jQuery.parseJSON(data);
            if(response !== null){
               console.log(JSON.stringify(data));
               $('#message-'+sync_id).html(response.message != "undefined"? response.message : '');
               if(response.progress > 0){
                  $('#file_upload').show()
                  $('#uploaded').html(response.uploaded_size + '/'+ response.total_size);
                  $('#speed').html(response.agv_speed);
                  $('#elapsed_time_left').html(response.elapsed_time_left);
                  $('.bar').css({'width':+response.progress+'%'});
               }else{
                  $('#file_upload').hide();                                      
               }

            }
         });

      }, 1000);
   }

   var progressDownloadInterval;

   var progressDownloadCallback = function(sync_id){
      progressDownloadInterval = setInterval(function(){
         $.ajax({
           type: "GET",
           url: "/getdownprogress/"+sync_id,
           data: null
         })
         .always(function( data ) {
            response = jQuery.parseJSON(data);
            if(response !== null){
               console.log(JSON.stringify(data));
               $('#message-'+sync_id).html(response.message != "undefined"? response.message : '');
               if(response.progress > 0){
                  $('#file_upload').show();
                  $('#uploaded').html(response.downloaded + '/'+ response.total_size);
                  $('.bar').css({'width':+response.progress+'%'});
               }else{
                  $('#file_upload').hide();                                      
               }

            }
         });

      }, 300);
   }

   $('#close-dialog').click(function(){
      location.reload();
   });


   </script>
   
@stop
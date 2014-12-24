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
                     <!-- BEGIN STYLE CUSTOMIZER -->
                     <div class="color-panel hidden-phone">
                        <div class="color-mode-icons icon-color"></div>
                        <div class="color-mode-icons icon-color-close"></div>
                        <div class="color-mode">
                           <p>THEME COLOR</p>
                           <ul class="inline">
                              <li class="color-black current color-default" data-style="default"></li>
                              <li class="color-blue" data-style="blue"></li>
                              <li class="color-brown" data-style="brown"></li>
                              <li class="color-purple" data-style="purple"></li>
                              <li class="color-white color-light" data-style="light"></li>
                           </ul>
                           <label class="hidden-phone">
                           <input type="checkbox" class="header" checked value="" />
                           <span class="color-mode-label">Fixed Header</span>
                           </label>                   
                        </div>
                     </div>
                     <!-- END BEGIN STYLE CUSTOMIZER -->    
                     <!-- BEGIN PAGE TITLE & BREADCRUMB-->        
                     <h3 class="page-title">
                        Synchronization            
                        <small></small>
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
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
                        <div class="portlet box blue">
                           <div class="portlet-title">
                              <h4><i class="icon-edit"></i>Synchronization</h4>
                           </div>
                           <div class="portlet-body">

                                 <div class="alert" style="display:none;">
                                    <button class="close" data-dismiss="alert"></button>
                                 </div>

                                 
                                 <div id="myModal1" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="false">
                                    <div class="modal-header">
                                       <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                       <h3>Sync</h3>
                                    </div>
                                    <div class="modal-body">
                                       <p>Please wait, we are transferring your data...[<span id="uploaded"></span>]</p>
                                       <div class="progress progress-striped progress-warning">
                                          <div style="width: 100%;" class="bar"></div>
                                       </div>
                                    </div>
                                    <div class="modal-footer">
                                       <button class="btn red" data-dismiss="modal" aria-hidden="true">Close</button>
                                    </div>
                                 </div>
                                 <div class="control-group">
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Today Sale Order" button.</span>
                                       <p>
                                          <a  href="#myModal1" role="button" data-toggle="modal" class="btn green big" id="sync_to_server">Sync Today Sale Order <i class="m-icon-big-swapup m-icon-white"></i></a></div>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Test Upload" button.</span>
                                       <p>
                                          <a  href="#myModal1" role="button" data-toggle="modal" class="btn green big" id="test_upload">Test Upload<i class="m-icon-big-swapup m-icon-white"></i></a></div>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync from Server" button.</span>
                                       <p>
                                          <a  href="#myModal1" role="button" data-toggle="modal" class="btn yellow big" id="sync_from_server">Sync from Server <i class="m-icon-big-swapdown m-icon-white"></i></a></div>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync Bus Occourance" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_bus_from_server">Sync Bus Occourance <i class="m-icon-big-swapdown m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync Trip" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_trip_from_server">Sync Trip <i class="m-icon-big-swapdown m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync Deleted Trip" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_del_trip_from_server">Sync Deleted Trip <i class="m-icon-big-swapdown m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync Seating Plan" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_seatingplan_from_server">Sync Seating Plan<i class="m-icon-big-swapdown m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync Agent" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_agent_from_server">Sync Agent<i class="m-icon-big-swapdown m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync Agent Group" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_agentgroup_from_server">Sync Agent Group<i class="m-icon-big-swapdown m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync City" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_city_from_server">Sync City<i class="m-icon-big-swapdown m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync Extra Destination" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_extradestination_from_server">Sync Extra Destination<i class="m-icon-big-swapdown m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync Bus Classes" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_classes_from_server">Sync Bus Classes <i class="m-icon-big-swapdown m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync Agent Commission" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_agentcommission_from_server">Sync Agent Commission <i class="m-icon-big-swapdown m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync Close Seat Info" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_closeseatinfo_from_server">Sync Close Seat Info <i class="m-icon-big-swapdown m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync Commission Type" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_commissiontype_from_server">Sync Commission Type <i class="m-icon-big-swapdown m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync Operator Group" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_operatorgroup_from_server">Sync Operator Group <i class="m-icon-big-swapdown m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync Operator Group User" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_operatorgroupuser_from_server">Sync Operator Group User <i class="m-icon-big-swapdown m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync User" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_user_from_server">Sync User <i class="m-icon-big-swapdown m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync Operator" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_operator_from_server">Sync Operator <i class="m-icon-big-swapdown m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync Seat Info" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_seatinfo_from_server">Sync Seat Info <i class="m-icon-big-swapdown m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync Sale Order" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_saleorder_from_server">Sync Sale Order <i class="m-icon-big-swapdown m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync Deleted Sale Order" button.</span>
                                       <p>
                                          <a href="#myModal1" role="button" data-toggle="modal" class="btn blue big" id="sync_del_saleorder_from_server">Sync Deleted Sale Order <i class="m-icon-big-swapdown m-icon-white"></i></a>
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
      $.ajax({
        type: "GET",
        url: "/uploadjson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');
         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');
         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');
            
         }
         
      });
      
   });
   $('#sync_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloadjson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');
         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');
         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });
   /**
    * Sync from Server
    */
   $('#sync_bus_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloadbusjson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');
         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });
   $('#sync_trip_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloadtripjson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });
   $('#sync_del_trip_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloaddeletetripjson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });
   $('#sync_seatingplan_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloadseatingplanjson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');
         }
         
      });
      
   });
   $('#sync_agent_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloadagentjson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });
   $('#sync_agentgroup_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloadagentgroupjson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });
   $('#sync_city_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloadcityjson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });
   $('#sync_extradestination_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloadextradestinationjson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });

   $('#sync_classes_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloadclassesjson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });

   $('#sync_agentcommission_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloadagentcommissionjson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });

   $('#sync_closeseatinfo_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloadcloseseatinfojson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });

   $('#sync_commissiontype_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloadcomissiontypejson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });

   $('#sync_operatorgroup_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloadoperatorgroupjson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });

   $('#sync_operatorgroupuser_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloadoperatorgroupuserjson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });

   $('#sync_user_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloaduserjson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });
   $('#sync_operator_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloadoperatorjson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });
   $('#sync_seatinfo_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloadseatinfojson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });
   $('#sync_saleorder_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloadsaleorderjson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });
   $('#sync_del_saleorder_from_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/downloaddeletesaleorderjson",
        data: null
      })
      .done(function( response ) {
         $('.progress-striped').removeClass('active');
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }
         else if(response.status_code === 0){
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
            $('#myModal1').modal('hide');

         }else{
            alert(JSON.stringify(response));
            $('#myModal1').modal('hide');

         }
         
      });
      
   });

   $('#test_upload').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/testupload",
        data: null
      })
      .done(function( response ) {
         alert(JSON.stringify(response));
         var data = jQuery.parseJSON(response);
         $('#uploaded').html(data.uploaded_size);
      });
      
   });

   </script>
   
@stop
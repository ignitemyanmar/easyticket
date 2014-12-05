@extends('../admin')
@section('content')
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
                     <div class="span12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet box blue">
                           <div class="portlet-title">
                              <h4><i class="icon-edit"></i>Synchronization</h4>
                           </div>
                           <div class="portlet-body">

                                 <div class="alert" style="display:none;">
                                    <button class="close" data-dismiss="alert"></button>
                                 </div>

                                 <div class="progress progress-striped progress-warning">
                                    <div style="width: 100%;" class="bar"></div>
                                 </div>
                                 <div class="control-group">
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Today Sale Order" button.</span>
                                       <p>
                                          <a class="btn green big" id="sync_to_server">Sync Today Sale Order <i class="m-icon-big-swapup m-icon-white"></i></a>
                                       </p>
                                    </div>
                                    <div class="controls">
                                       <span class="help-inline">Please click to Sync "Sync Bus Occourance" button.</span>
                                       <p>
                                          <a class="btn blue big" id="sync_from_server">Sync Bus Occourance <i class="m-icon-big-swapdown m-icon-white"></i></a>
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

   $('#sync_to_server').click(function(){
      $('.progress-striped').addClass('active');
      $.ajax({
        type: "GET",
        url: "/uploadjson",
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
         }
         else{
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
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
         //alert(JSON.stringify(response));
         if(response.status_code === 1){
            alert( "Data Saved: " + response.message );
            $('.alert').show();
            $('.alert').addClass('alert-success');
            $('.alert').append(response.message);
         }
         else{
            alert( "Error: " + response.message);
            $('.alert').show();
            $('.alert').addClass('alert-error');
            $('.alert').append(response.message);
         }
         
      });
      
   });

   </script>
   
@stop
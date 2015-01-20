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
                     <!-- BEGIN PAGE TITLE & BREADCRUMB-->        
                     <h3 class="page-title">
                        အေရာင္းကုိယ္စားလွယ္ အုပ္စုမ်ား            
                        <small></small>
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <i class="icon-angle-right"></i>
                        </li>
                        <li><a href="#">အေရာင္းကုိယ္စားလွယ္ အုပ္စုမ်ား</a></li>
                        
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
                              <h4><i class="icon-edit"></i>Agent Group List</h4>
                           </div>
                           <div class="portlet-body">
                              <div class="clearfix">
                                 <div class="btn-group">
                                    <a href="/agentgroup/create">
                                    <button id="" class="btn green">
                                    Add New <i class="icon-plus"></i>
                                    </button>
                                    </a>
                                 </div>
                                 
                              </div>
                              @if(Session::has('message'))
                                 <?php $message=Session::get('message'); ?>
                                 @if($message['status']='0')
                                 <div class="alert alert-info">
                                    <button class="close" data-dismiss="alert"></button>
                                    {{$message['info']}}
                                 </div>
                                 @else
                                 <div class="alert alert-success">
                                    <button class="close" data-dismiss="alert"></button>
                                    <strong>Success!</strong> {{$message['info']}}
                                 </div>
                                 @endif
                              @endif
                              <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                 <thead>
                                    <tr>
                                       <th class="span10">Name</th>
                                       <!-- <th class="span3">Phone</th> -->
                                       <!-- <th class="span3">Address</th> -->
                                       <th class="span3">Branches</th>
                                       <!-- <th class="span1">Edit</th> -->
                                       <th class="span1">Actions</th>
                                       <!-- <th class="span1">Delete</th> -->
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @if(count($response)==0)
                                       There is no record in database.
                                    @else
                                    @endif
                                 
                                    @foreach($response as $agent)
                                                <tr>
                                                   <td>{{$agent['name']}}</td>
                                                   <td><a href="/agentgroupchildlist/{{ $agent['id'] }}"  class="btn blue button-submit">View Branches <i class="m-icon-swapright m-icon-white"></i></a><br><br></td>
                                                   <td>
                                                         <div class="btn-group">
                                                            <a class="btn blue" href="#" data-toggle="dropdown">
                                                            <i class="icon-cog"></i> Settings
                                                            <i class="icon-angle-down"></i>
                                                            </a>
                                                            <ul class="dropdown-menu"> 
                                                               <li><a href="/agentgroup-update/{{ $agent['id'] }}"><i class="icon-pencil"></i> Edit</a></li>
                                                               <li><a href="/agentgroup-actions/{{ $agent['id'] }}"><i class="icon-plus"></i> Entry Payments</a></li>
                                                               <li><a href="/deleteagentgroup/{{ $agent['id'] }}"><i class="icon-remove"></i> Delete</a></li>
                                                               <li class="divider"></li>
                                                               <!-- <li><a href="#"><i class="i"></i> Full Settings</a></li> -->
                                                            </ul>
                                                         </div>
                                                   </td>
                                                   <!-- <td style="text-align:center;">
                                                         <a href="/agentgroup-actions/{{ $agent['id'] }}"  class="btn blue-stripe button-submit">Entry</a><br><br>
                                                   </td>
                                                   <td style="text-align:center;">
                                                         <a href="deleteagentgroup/{{ $agent['id'] }}"   class="btn red-stripe button-submit">Delete</a>
                                                   </td> -->
                                                </tr>
                                    @endforeach
                                    
                                 </tbody>
                              </table>
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
   <script type="text/javascript" src="assets/data-tables/jquery.dataTables.js"></script>
   <script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>
   <script>
      jQuery(document).ready(function() {       
         // initiate layout and plugins
         App.setPage("table_editable");
         // App.init();
      });
   </script>
@stop
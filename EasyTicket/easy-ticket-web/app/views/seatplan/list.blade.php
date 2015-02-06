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
                        Seat Plan List            
                        <small></small>
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <i class="icon-angle-right"></i>
                        </li>
                        <li><a href="#">Seat Plan List</a></li>
                        
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
                              <h4><i class="icon-edit"></i>Seat Plan List</h4>
                           </div>
                           <div class="portlet-body">
                              <div class="clearfix">
                                 <div class="btn-group">
                                    <a href="/seatplans/create?{{$myApp->access_token}}">
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
                                    <strong>Exiting ! </strong>This record is already exit.
                                 </div>
                                 @else
                                 <div class="alert alert-success">
                                    <button class="close" data-dismiss="alert"></button>
                                    <strong>Success!</strong> One record have been added.
                                 </div>
                                 @endif
                              @endif
                              <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                 <thead>
                                    <tr>
                                       <th>Seat Plan Name</th>
                                       <th>Ticket Type</th>
                                       <th class="span6">Operator</th>
                                       <th>Edit</th>
                                       @if(Auth::user()->role!=2)
                                          <th>Delete</th>
                                       @endif
                                       <th>View</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @if(count($response)==0)
                                       There is no record in database.
                                    @else
                                    @endif
                                 
                                    @foreach($response as $seatplan)
                                        <tr>
                                            <td>{{$seatplan['name']}}</td>
                                            <td>{{$seatplan['ticket_type_id']}}</td>
                                            <td>{{$seatplan['operator_id']}}</td>
                                            <td style="text-align:center;">
                                              <a href="/seatplan/update/{{ $seatplan['id'] }}?{{$myApp->access_token}}"  class="btn green button-submit">Edit</a><br><br>
                                            </td>
                                             @if(Auth::user()->role!=2)
                                                <td>
                                                   <a href="deleteseatplan/{{ $seatplan['id'] }}?{{$myApp->access_token}}"   class="btn green delete button-submit">Delete</a>
                                                </td>
                                             @endif
                                            <td>
                                                <a href="/seatplandetail/{{$seatplan['id']}}/seat_plan_id?seatplan_id={{$seatplan['id']}}&operator_id={{$seatplan['operator_id']}}&name={{$seatplan['name']}}&ticket_type_id={{$seatplan['ticket_type_id']}}&{{$myApp->access_token}}"   class="btn green button-submit">Detail</a>
                                            </td>
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
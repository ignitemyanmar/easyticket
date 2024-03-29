@extends('../admin')
@section('content')
   <style type="text/css">
   .ticket, .price{border:1px solid #35AA47; background: #D8D8D8; padding: 2px 9px; }
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
                        Dashboard            
                        <small></small>
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <i class="icon-angle-right"></i>
                        </li>
                        <li><a href="/dashboard">Dashboard</a></li>
                        
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
                              <h4><i class="icon-edit"></i>Order List</h4>
                           </div>
                           <div class="portlet-body">
                              <div class="clearfix">
                              </div>
                              @if(Session::has('message'))
                                 <?php $message=Session::get('message'); ?>
                                 @if($message)
                                 <div class="alert alert-success">
                                    <button class="close" data-dismiss="alert"></button>
                                    {{$message}}
                                 </div>
                                 @endif
                              @endif
                              <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                 <thead>
                                    <tr>
                                       <!-- <th class="hidden-phone">&nbsp;</th> -->
                                       <th class="hidden-phone">Order ID</th>
                                       <th>ticket_no</th>
                                       <th>Seat No</th>
                                       <th>Price</th>
                                       <th>Foreign Price</th>
                                       <th>FOC</th>
                                       <th></th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <form action="/" method="post">
                                    @foreach($response as $ticket)
                                          <tr>
                                             <!-- <th><input type="checkbox" name="ticket[]" value="{{$ticket->id}}" ></th> -->
                                             <th>{{$ticket->order_id}}</th>
                                             <td>{{$ticket->ticket_no}}</td>
                                             <td>{{$ticket->seat_no}}</td>
                                             <td>{{$ticket->price}}</td>
                                             <td>{{$ticket->foreign_price}}</td>
                                             <td>@if($ticket->free_ticket==0) No @else Yes @endif</td>
                                             <td>
                                                <div class="btn-group pull-right">
                                                   <a class="btn blue" href="#" data-toggle="dropdown">
                                                      <i class="icon-cog"></i> Settings
                                                      <i class="icon-angle-down"></i>
                                                   </a>
                                                   <ul class="dropdown-menu"> 
                                                      <li>
                                                        <a class="delete" href="/order-tickets/delete/{{$ticket->id}}?{{$myApp->access_token}}"><i class="icon-trash"></i>Delete</a>
                                                      </li>
                                                      <li>
                                                        <a  href="/orderlist?{{$myApp->access_token}}"><i class="icon-list"></i>Order List</a>
                                                      </li>
                                                      <li class="divider"></li>
                                                   </ul>
                                                </div>
                                             </td>
                                          </tr>
                                    @endforeach
                                      
                                    </form>
                                    
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
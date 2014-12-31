@extends('../admin')
@section('content')
   <link rel="stylesheet" href="../assets/data-tables/DT_bootstrap.css" />
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
                              <h4><i class="icon-edit"></i>Booking List</h4>
                           </div>
                           <div class="portlet-body">
                              
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
                                       <th>ခရီးစဥ္</th>
                                       <th>အေရာင္းကုိယ္စားလွယ္</th>
                                       <th>စုစုေပါင္းခုံ</th>
                                       <th>စုစုေပါင္း</th>
                                       <th>-</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                       @if($response)
                                          @foreach($response as $booking)
                                             <tr>
                                                <td class="span-2">{{$booking['trip']}}<br>{{$booking['departure_date']}}<br>{{$booking['departure_time']}}<br>({{$booking['class']}})</td>
                                                <td>{{ $booking['agent']['name']}}</td>
                                                <td><b class="btn mini green">{{ $booking['total_seat']}}</b><br>({{ $booking['seat_numbers']}})</td>
                                                
                                                <td>{{ $booking['total_amount']}}</td>
                                                <td>
                                                   <a class="btn large red-stripe delete" href="/report/booking/delete/{{$booking->id}}">ဖ်က္မည္</a>
                                                   <a class="btn mini green-stripe" href="/cartview/{{$booking->id}}">အတည္ျပဳမည္</a>
                                                </td>
                                             </tr>
                                          @endforeach
                                       @endif
                                    
                                    
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
   <script type="text/javascript" src="../assets/data-tables/jquery.dataTables.js"></script>
   <script type="text/javascript" src="../assets/data-tables/DT_bootstrap.js"></script>
   <script>
      jQuery(document).ready(function() {       
         // initiate layout and plugins
         App.setPage("table_editable");
         // App.init();
      });
   </script>
@stop
@extends('../admin')
@section('content')
   <link rel="stylesheet" href="../../../../css/jquery.dataTables.v1.10.4.css" />
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
                        ရပ္ဆိုင္းထားေသာ စာရင္းမ်ား         
                        <small></small>
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <i class="icon-angle-right"></i>
                        </li>
                        <li><a href="#">ရပ္ဆိုင္းထားေသာ စာရင္းမ်ား</a></li>
                        
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
                              <h4><i class="icon-edit"></i>ရပ္ဆိုင္းထားေသာ စာရင္းမ်ား</h4>
                           </div>
                           <div class="portlet-body">
                              <div class="clearfix">
                                 <div class="btn-group">
                                    <a href="/closetrip/{{ $trip->id }}?access_token={{Auth::user()->access_token}}">
                                    <button id="" class="btn green">
                                    <i class="icon-plus"></i> Add Close Trip
                                    </button>
                                    </a>
                                 </div>
                                 @if($trip->ever_close > 0)
                                 <div class="btn-group pull-right">
                                    <a href="/everclose/{{$trip->id}}/remove?access_token={{Auth::user()->access_token}}">
                                      <button id="" class="btn red">
                                      <i class="icon-remove"></i> Remove From Ever Close
                                      </button>
                                    </a>
                                 </div>
                                 @endif
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

                              <table class="table table-striped table-hover table-bordered" id="table_editable">
                                 <thead>
                                    <tr>
                                       <th class="hidden-phone span2">ခရီးစဥ္</th>
                                       <th class="span1">Start Date</th>
                                       <th class="span1">End Date</th>
                                       <th class="span1">Remark</th>
                                       <th class="span1">Created</th>
                                       <th class="span1">-</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach($close_trip as $rows)
                                    <tr>
                                       <td class="hidden-phone span2">{{$trip->from_city}} - {{$trip->to_city}}<br>Time: {{$trip->time}}</td>
                                       <td class="span1">{{date('d/m/Y', strtotime($rows->start_date))}}</td>
                                       <td class="span1">{{date('d/m/Y', strtotime($rows->end_date))}}</td>
                                       <td class="span1">{{$rows->remark}}</td>
                                       <td class="span1">{{date('d/m/Y h:i:s', strtotime($rows->created_at))}}</td>
                                       <td class="span1">
                                         <div class="btn-group">
                                            <a href="/closedtrip/{{$rows->id}}/delete?{{$myApp->access_token}}">
                                            <button id="" class="btn red">
                                            <i class="icon-remove"></i> Remove
                                            </button>
                                            </a>
                                         </div>
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
   <script type="text/javascript" src="../../../../js/jquery.dataTables.v1.10.4.min.js"></script>
@stop
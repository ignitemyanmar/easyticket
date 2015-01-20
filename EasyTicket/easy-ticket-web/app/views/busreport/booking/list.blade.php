@extends('../admin')
@section('content')
   <link rel="stylesheet" href="../assets/data-tables/DT_bootstrap.css" />
   <link rel="stylesheet" type="text/css" href="../../../../assets/chosen-bootstrap/chosen/chosen.css" />
   <link rel="stylesheet" type="text/css" href="../../../../assets/bootstrap-datepicker/css/datepicker.css" />

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
                        Booking List            
                        <small></small>
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <!-- <i class="icon-angle-right"></i> -->
                        </li>

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
                              <form action="/report/booking" method="get" class="horizontal-form">
                                    <div class="clear-fix">&nbsp;</div>
                                    <div class="row-fluid">
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="startdate">ထွက်ခွာမည့် အစေန့ေရွးရန်</label>
                                             <div class="controls">
                                                <input id="startdate" name="start_date" class="m-wrap span12 m-ctrl-medium date-picker" size="16" type="text" data-date-format="dd/mm/yyyy" value="{{date('d/m/Y', strtotime($search['start_date']))}}">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="enddate">ထွက်ခွာမည့် အဆုံးေန့ေရွးရန်</label>
                                             <div class="controls">
                                                <input id="enddate" name="end_date" class="m-wrap span12 m-ctrl-medium  date-picker" size="16" type="text" data-date-format="dd/mm/yyyy" value="{{date('d/m/Y', strtotime($search['end_date']))}}">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="departure_time">အချိန်ေရွးရန်</label>
                                             <div class="controls">
                                                <select id="departure_time" name="departure_time" class="m-wrap span12 chosen">
                                                   @if($search['times'])
                                                         <option value="">All</option>
                                                      @foreach($search['times'] as $time)
                                                         <option value="{{$time['time']}}" @if($search['time']== $time['time']) selected @endif>{{$time['time']}}</option>
                                                      @endforeach
                                                   @endif
                                                </select>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="departure_time">&nbsp;</label>

                                             <input type="hidden" value="{{$search['start_date'].' - '. $search['end_date']}} " id="report_date">
                                             <button type="submit" class="btn green pull-right">Search &nbsp; <i class="m-icon-swapright m-icon-white"></i></button>
                                          </div>
                                       </div>
                                    </div>
                              </form>

                              <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                 <thead>
                                    <tr>
                                       <th>ခရီးစဥ္</th>
                                       <th>အေရာင္းကုိယ္စားလွယ္</th>
                                       <th>Remark</th>
                                       <th>စုစုေပါင္းခုံ</th>
                                       <th>ခုံနံပါတ္မ်ား</th>
                                       <th>စုစုေပါင္း</th>
                                       @if(Auth::user()->role != 2)
                                          <th>-</th>
                                       @endif
                                    </tr>
                                 </thead>
                                 <tbody>
                                       @if($response)
                                          @foreach($response as $booking)
                                             @if(!$search['time'])
                                                <tr>
                                                   <td class="span-2">{{$booking['trip']}}<br>{{$booking['departure_date']}}<br>{{$booking['departure_time']}}<br>({{$booking['class']}})</td>
                                                   <td>{{ $booking['agent']['name']}}</td>
                                                   <td>{{$booking['remark']}}</td>
                                                   <td><b class="btn mini green">{{ $booking['total_seat']}}</b></td>
                                                   <td>({{ $booking['seat_numbers']}})</td>
                                                   <td>{{ $booking['total_amount']}}</td>
                                                   @if(Auth::user()->role != 2)
                                                      <td>
                                                         <a class="btn large red-stripe delete" href="/report/booking/delete/{{$booking->id}}">ဖ်က္မည္</a>
                                                         <a class="btn mini green-stripe" href="/cartview/{{$booking->id}}">အတည္ျပဳမည္</a>
                                                      </td>
                                                   @endif
                                                </tr>
                                             @else
                                                @if($search['time']== $booking['departure_time'])
                                                   <tr>
                                                      <td class="span-2">{{$booking['trip']}}<br>{{$booking['departure_date']}}<br>{{$booking['departure_time']}}<br>({{$booking['class']}})</td>
                                                      <td>{{ $booking['agent']['name']}}</td>
                                                      <td>{{$booking['remark']}}</td>
                                                      <td><b class="btn mini green">{{ $booking['total_seat']}}</b></td>
                                                      <td>({{ $booking['seat_numbers']}})</td>
                                                      <td>{{ $booking['total_amount']}}</td>
                                                      @if(Auth::user()->role != 2)
                                                         <td>
                                                            <a class="btn large red-stripe delete" href="/report/booking/delete/{{$booking->id}}">ဖ်က္မည္</a>
                                                            <a class="btn mini green-stripe" href="/cartview/{{$booking->id}}">အတည္ျပဳမည္</a>
                                                         </td>
                                                      @endif
                                                   </tr>
                                                @endif
                                             @endif
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
   <script type="text/javascript" src="../../../../assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
   <script type="text/javascript" src="../../../../assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

   <script>
      jQuery(document).ready(function() {       
         // initiate layout and plugins
         App.setPage("table_editable");
         // App.init();
      });
   </script>
@stop
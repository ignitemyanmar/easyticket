@extends('../admin')
@section('content')
   <style type="text/css">
   .ticket, .price{border:1px solid #35AA47; background: #D8D8D8; padding: 2px 9px; }
   .zawgyi-one{font-family: "Zawgyi-One" !important;}
   </style>
   <link rel="stylesheet" href="assets/data-tables/DT_bootstrap.css" />
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
                     <!-- BEGIN STYLE CUSTOMIZER -->
                     
                     <!-- END BEGIN STYLE CUSTOMIZER -->    
                     <!-- BEGIN PAGE TITLE & BREADCRUMB-->        
                     <h3 class="page-title">
                        &nbsp;ေရာင္းျပီးလက္မွတ္မ်ား ဖ်က္ရန္           
                        <small></small>
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <i class="icon-angle-right"></i>
                        </li>
                        <li><a href="#">&nbsp;ေရာင္းျပီးလက္မွတ္မ်ား ဖ်က္ရန္ </a></li>
                        
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
                              <h4><i class="icon-edit"></i>Sold Ticket List</h4>
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
                              <form action="/orderlist" method="get" class="horizontal-form">
                                    <input type="hidden" value="{{$myApp->v_access_token}}" name="access_token">
                                    <div class="clear-fix">&nbsp;</div>
                                    <div class="row-fluid">
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="startdate">လက်မှတ်မှာယူသည့်ေန့ အစေန့ေရွးရန်</label>
                                             <div class="controls">
                                                <input id="startdate" name="start_date" class="m-wrap span12 m-ctrl-medium date-picker" size="16" type="text" data-date-format="dd/mm/yyyy" value="{{date('d/m/Y', strtotime($search['start_date']))}}">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span3">
                                          <div class="control-group">
                                             <label class="control-label" for="enddate">လက်မှတ်မှာယူသည့်ေန့ အဆုံးေန့ေရွးရန်</label>
                                             <div class="controls">
                                                <input id="enddate" name="end_date" class="m-wrap span12 m-ctrl-medium  date-picker" size="16" type="text" data-date-format="dd/mm/yyyy" value="{{date('d/m/Y', strtotime($search['end_date']))}}">
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span2">
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
                                       <div class="span2">
                                          <div class="control-group">
                                             <label class="control-label" for="departure_time">Choose Remark</label>
                                             <div class="controls">
                                                <select name="remark" class="m-wrap span12 chosen">
                                                   @if($remark)
                                                      @foreach($remark as $key=>$value)
                                                         <option value="{{$key}}" @if($search['remark'] == $key) selected @endif>{{$value}}</option>
                                                      @endforeach
                                                   @endif
                                                </select>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="span2">
                                          <div class="control-group">
                                             <label class="control-label" for="departure_time">&nbsp;</label>
                                             <div class="controls">
                                                <button type="submit" class="btn green pull-right">Search &nbsp; <i class="m-icon-swapright m-icon-white"></i></button>
                                             </div>
                                          </div>
                                       </div>

                                    </div>
                              </form>


                              <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                 <thead>
                                    <tr>
                                       <th class="hidden-phone">လက္မွတ္မွာယူ သည့္ေန႕</th>
                                       <th>အေရာင္းကုိယ္စားလွယ္</th>
                                       <th>ကားအခ်က္အလက္မ်ား</th>
                                       <!-- <th>ေရာက္ရွိမည့္ျမိဳ႕</th>
                                       <th>ထြက္ခြာမည့္ေန႕ရက္</th>
                                       <th>ထြက္ခြာမည့္အခ်ိန္</th>
                                       <th>ကားအမ်ိဳးအစား</th> -->
                                       <th>ဝယ္သူဧ။္ အခ်က္အလက္မ်ား</th>
                                       <th>Remark</th>
                                       <th>စုစုေပါင္း တန္ဖုိး</th>
                                       <th>Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach($response as $order)
                                          <tr>
                                             <td>{{date('d/m/Y',strtotime($order->orderdate))}}</td>
                                             <td>
                                                <div class="wordwrap" style="width:100px;"> &nbsp;@if($order->agent) {{$order->agent->name}}@else - @endif
                                                </div>
                                             </td>
                                             <td><div class="text-left">
                                                {{$order->from_to.' ('.$order->busclass.')'}}<br>
                                                {{$order->departure_date.' ('.$order->departure_time.')'}}<br><br>
                                               
                                                </div>
                                             </td>
                                             <td>
                                                <div class="text-left"> 
                                                အမည္ : {{$order->name}}<br>
                                                မွတ္ပုံတင္နံပါတ္ : {{$order->nrc_no}}<br>
                                                ဖုန္းနံပါတ္ :{{$order->phone}}<br>
                                                &nbsp;ႏုိင္ငံသား : @if($order->nationality=='local') ႏုိင္ငံသား @else  ႏုိင္ငံျခားသား@endif<br>
                                                လက္မွတ္အေရအတြက္ : <b>{{$order->total_ticket}}</b><br>
                                                &nbsp;ေစ်းနုန္း :<b>{{$order->price}} MMK</b>

                                                </div>
                                             </td>
                                             <td>{{$remark[$order->remark_type]}}</td>
                                             <td>@if($order->total_amount==0) {{ $order->price * $order->total_ticket }} @else{{$order->total_amount}} @endif</td>
                                             <td>
                                                <div class="btn-group pull-right">
                                                   <a class="btn blue" href="#" data-toggle="dropdown">
                                                      <i class="icon-cog"></i> Settings
                                                      <i class="icon-angle-down"></i>
                                                   </a>
                                                   <ul class="dropdown-menu"> 
                                                      <li>
                                                        <a class="delete zawgyi-one" href="/order-delete/{{ $order['id'] }}?{{$myApp->access_token}}"><i class="icon-list"></i>ေဘာက္ခ်ာ တစ္ခုလုံးဖ်က္မည္။</a>
                                                      </li>
                                                      <li>
                                                        <a class="zawgyi-one" href="/order-tickets/{{ $order['id'] }}?{{$myApp->access_token}}"><i class="icon-list"></i>လက္မွတ္တစ္ခု ခ်င္းစီ ဖ်က္မည္။</a>
                                                      </li>
                                                      <li class="divider"></li>
                                                   </ul>
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
   <script type="text/javascript" src="assets/data-tables/jquery.dataTables.js"></script>
   <script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>
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
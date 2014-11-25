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
                                       <th class="hidden-phone">လက္မွတ္မွာယူ သည့္ေန႕</th>
                                       <th>အေရာင္းကုိယ္စားလွယ္</th>
                                       <th>ကားအခ်က္အလက္မ်ား</th>
                                       <!-- <th>ေရာက္ရွိမည့္ျမိဳ႕</th>
                                       <th>ထြက္ခြာမည့္ေန႕ရက္</th>
                                       <th>ထြက္ခြာမည့္အခ်ိန္</th>
                                       <th>ကားအမ်ိဳးအစား</th> -->
                                       <th>ဝယ္သူဧ။္ အခ်က္အလက္မ်ား</th>
                                      
                                       <th>စုစုေပါင္း တန္ဖုိး</th>
                                       <th>Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach($response as $order)
                                          <tr>
                                             <td>{{date('d/m/Y',strtotime($order->orderdate))}}</td>
                                             <td>
                                                <div class="wordwrap" style="width:100px;"> @if($order->agent) {{$order->agent->name}}@else - @endif
                                                </div>
                                             </td>
                                             <td><div class="text-left">
                                                {{$order->from_to.' ('.$order->busclass.')'}}<br>
                                                Date :{{$order->departure_date.'<br> ('.$order->departure_time.')'}}<br><br>
                                               
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
                                             <td>@if($order->total_amount==0) {{ $order->price * $order->total_ticket }} @else{{$order->total_amount}} @endif</td>
                                             <td>
                                                <!-- <a class="btn large green-stripe edit" href="#">ျပင္မည္</a> -->
                                                <a class="btn large red-stripe delete" href="order-delete/{{ $order['id'] }}">အကုန္ဖ်က္မည္</a>
                                                <a class="btn large red-stripe" href="order-tickets/{{ $order['id'] }}">တစ္ခုခ်င္းဖ်က္မည္</a>
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
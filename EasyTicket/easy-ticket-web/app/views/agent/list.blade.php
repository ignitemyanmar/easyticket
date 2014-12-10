@extends('../admin')
@section('content')
   <link rel="stylesheet" href="assets/data-tables/DT_bootstrap.css" />
   <style type="text/css">
   tr.heading td{background: #E4F6F5 !important;}
   </style>
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
                     <?php 
                        // $orderdate=$response[0]['departure_date']; 
                        $orderdate=Session::get('search_daily_date'); 
                        $V_operator_id=Session::get('operator_id');
                     ?>
                     <h3 class="page-title">
                        အေရာင္းကုိယ္စားလွယ္မ်ား          
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}">ပင္မစာမ်က္ႏွာ</a> 
                           <i class="icon-angle-right"></i>
                        </li>
                        <li><a href="#">အေရာင္းကုိယ္စားလွယ္မ်ား</a></li>
                        
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
                              <h4><i class="icon-th-list"></i>အေရာင္းကုိယ္စားလွယ္မ်ား</h4>
                           </div>
                           <div class="portlet-body">
                              <div class="clearfix">
                                 <div class="btn-group">
                                    <a href="/agents/create">
                                    <button id="" class="btn green">
                                    အသစ္ထည့္မည္ <i class="icon-plus"></i>
                                    </button>
                                    </a>
                                 </div>
                                 
                              </div>
                              @if(Session::has('message'))
                                 <?php $message=Session::get('message'); ?>
                                 @if($message['status']==0)
                                    <div class="alert alert-error">
                                       <button class="close" data-dismiss="alert"></button>
                                       <strong>Info ! </strong>{{$message['info']}}.
                                    </div>
                                 @else
                                    <div class="alert alert-success">
                                       <button class="close" data-dismiss="alert"></button>
                                       <strong>Success!</strong>{{$message['info']}}
                                    </div>
                                 @endif
                              @endif
                              <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                 <thead>
                                       <th class="span2">အမည္</th>
                                       <th class="span2">ဖုန္းနံပါတ္</th>
                                       <th class="span2">လိပ္စာ</th>
                                       <th class="span2">Owner</th>
                                       <th class="span1">ျပင္ရန္</th>
                                       <th class="span1">ဖ်က္ရန္</th>
                                 </thead>
                                 <tbody>
                                    @if(count($response)==0)
                                       There is no record in database.
                                    @else
                                    @endif
                                 
                                    @foreach($response as $key=>$rows)
                                       <tr class="heading">
                                          <td colspan="6">{{$key}}</td>
                                       </tr>
                                       @foreach($rows as $agent)
                                                   <tr>
                                                      <td>{{$agent['name']}}</td>
                                                      <td>{{$agent['phone']}}</td>
                                                      <td><div class="wordwrap">{{$agent['address']}}</div></td>
                                                      <td>@if($agent['owner']==1) Owner @else - @endif</td>
                                                      <td style="text-align:center;">
                                                            <a href="/agent-update/{{ $agent['id'] }}"  class="btn green">ျပင္ရန္</a><br><br>
                                                      </td>
                                                      <td style="text-align:center;">
                                                            <a href="deleteagent/{{ $agent['id'] }}"   class="btn green">ဖ်က္ရန္</a>
                                                      </td>
                                                   </tr>
                                       @endforeach
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
   
@stop
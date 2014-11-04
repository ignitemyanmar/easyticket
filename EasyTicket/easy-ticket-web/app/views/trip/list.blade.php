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
                              <h4><i class="icon-edit"></i>Trip List</h4>
                           </div>
                           <div class="portlet-body">
                              <div class="clearfix">
                                 <div class="btn-group">
                                    <a href="/trip/create">
                                    <button id="" class="btn green">
                                    Add New <i class="icon-plus"></i>
                                    </button>
                                    </a>
                                 </div>
                                 
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
                                       <th class="hidden-phone">ခရီးစဥ္</th>
                                       <!-- <th class="span2">ေရာက္မည့္ျမိဳ႕</th> -->
                                       <th class="span2">ခုံအစီအစဥ္</th>
                                       <th class="span2">ကားအမ်ိဳးအစား</th>
                                       <th class="span2">ကားထြက္သည့္ ေန႕ အခ်ိန္<!-- ႕မ်ား --></th>
                                       <!-- <th class="span2">အခ်ိန္</th> -->
                                       <th class="span1">ႏုိင္ငံသား ေစ်းႏုန္း</th>
                                       <th class="span1">ႏုိင္ငံျခားသား ေစ်းႏုန္း</th>
                                       <th class="span2">ေကာ္မစ္ရွင္ႏႈန္း</th>
                                       <th class="span1">-</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach($response as $trip)
                                          <tr>
                                             <td class="hidden-phone">{{$trip['from_city']->name.'-'.$trip['to_city']->name}}</td>
                                             <!-- <td>{{$trip['to_city']->name}}</td> -->
                                             <td>{{$trip['seat_plan']->name}}</td>
                                             <td>{{$trip['busclass']->name}}</td>
                                             <td>
                                                <p>ကားထြက္သည့္ ေန႕မ်ား : {{$trip['available_day']}}</p>
                                            အခ်ိန္ :   {{$trip['time']}}</td>
                                             <td>
                                                {{$trip['price']}}
                                             </td>
                                             <td class="span1">{{$trip['foreign_price']}}</td>
                                             <td>{{$trip['commission']}}</td>
                                             <td>
                                                <!-- <a class="btn mini green-stripe" href="#">ျပင္မည္</a> -->
                                                <a class="btn mini red-stripe delete" href="deletetrip/{{ $trip['id'] }}">ဖ်က္မည္</a>
                                                <a class="btn mini blue-stripe" href="define-ownseat/{{ $trip['id'] }}">ခုံပုိင္သတ္မွတ္ရန္</a>
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
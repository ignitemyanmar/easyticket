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
                                 
                                 <div class="btn-group pull-right">
                                     <button class="btn green dropdown-toggle" data-toggle="dropdown">Tools <i class="icon-angle-down"></i>
                                     </button>
                                     <ul class="dropdown-menu">
                                        <li>
                                           <a href="#" class="print">Print</a></li>
                                        <!-- <li><a href="#">Save as PDF</a></li> -->
                                        <li><a href="#" id="btnExportExcel">Export to Excel</a></li>
                                     </ul>
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
                              <table class="table table-striped table-hover table-bordered" id="tblExport" style="display:none;">
                                 <thead>
                                    <tr>
                                       <th class="hidden-phone span3">ခရီးစဥ္</th>
                                       <!-- <th class="span2">ေရာက္မည့္ျမိဳ႕</th> -->
                                       <th class="span2">ကားအမ်ိဳးအစား / ခုံအစီအစဥ္</th>
                                       <th class="span2">ကားထြက္သည့္ ေန႕ အခ်ိန္<!-- ႕မ်ား --></th>
                                       <!-- <th class="span2">အခ်ိန္</th> -->
                                       <th class="span1">ႏုိင္ငံသား ေစ်းႏုန္း</th>
                                       <th class="span1">ႏုိင္ငံျခားသား<br> ေစ်းႏုန္း</th>
                                       <th class="span1">ေကာ္မစ္ရွင္ႏႈန္း</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach($response as $trip)
                                          <tr>
                                             <td class="hidden-phone span2">{{$trip['from_city']->name.'=>'.$trip['to_city']->name}} @if($trip['extendcity']) ==> {{City::whereid($trip['extendcity']->city_id)->pluck('name');}} @endif</td>
                                             <!-- <td>{{$trip['to_city']->name}}</td> -->
                                             <td>{{$trip['busclass']->name}} {{$trip['seat_plan']->name}}</td>
                                             <td>
                                                ကားထြက္သည့္ ေန႕မ်ား : {{$trip['available_day']}}
                                            အခ်ိန္ :   {{$trip['time']}}</td>
                                             <td>
                                                {{$trip['price']}}
                                             </td>
                                             <td>{{$trip['foreign_price']}}</td>
                                             <td>{{$trip['commission']}}</td>
                                          </tr>
                                    @endforeach
                                    
                                 </tbody>
                              </table>

                              <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                 <thead>
                                    <tr>
                                       <th class="hidden-phone span3">ခရီးစဥ္</th>
                                       <!-- <th class="span2">ေရာက္မည့္ျမိဳ႕</th> -->
                                       <th class="span2">ကားအမ်ိဳးအစား / ခုံအစီအစဥ္</th>
                                       <th class="span2">ကားထြက္သည့္ ေန႕ အခ်ိန္<!-- ႕မ်ား --></th>
                                       <!-- <th class="span2">အခ်ိန္</th> -->
                                       <th class="span1">ႏုိင္ငံသား ေစ်းႏုန္း</th>
                                       <th class="span1">ႏုိင္ငံျခားသား<br> ေစ်းႏုန္း</th>
                                       <th class="span1">ေကာ္မစ္ရွင္ႏႈန္း</th>
                                       <th class="span2">-</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @foreach($response as $trip)
                                          <tr>
                                             <td class="hidden-phone span2">{{$trip['from_city']->name.'=>'.$trip['to_city']->name}} @if($trip['extendcity'])
                                             <span class="label label-info mini">{{City::whereid($trip['extendcity']->city_id)->pluck('name');}}</span> @endif
                                             @if($trip->ever_close == 0)
                                                @if((strtotime($trip->from_close_date) >= strtotime(date('Y-m-d'))) && (strtotime(date('Y-m-d')) <= strtotime($trip->to_close_date)))
                                                <br><span class="label label-important">NOTE!</span>
                                                <span>We are close from [{{date('d/m/Y',strtotime($trip->from_close_date))}}] to [{{date('d/m/Y',strtotime($trip->to_close_date))}}] date because of [{{$trip->remark}}]</span>
                                                @endif
                                             @endif
                                             @if($trip->ever_close == 1)
                                                <br><span class="label label-important">NOTE!</span>
                                                <span>We are ever close from [{{date('d/m/Y',strtotime($trip->from_close_date))}}] because of [{{$trip->remark}}]</span>
                                             @endif
                                             </td>
                                             <!-- <td>{{$trip['to_city']->name}}</td> -->
                                             <td>{{$trip['busclass']->name}}<br>{{$trip['seat_plan']->name}}

                                             </td>
                                             <td>
                                                <p>ကားထြက္သည့္ ေန႕မ်ား : {{$trip['available_day']}}</p>
                                            အခ်ိန္ :   {{$trip['time']}}</td>
                                             <td>
                                                {{$trip['price']}}
                                             </td>
                                             <td>{{$trip['foreign_price']}}</td>
                                             <td>{{$trip['commission']}}</td>
                                             <td>
                                                <div class="btn-group">
                                                   <a class="btn green" href="#" data-toggle="dropdown">
                                                   <i class="icon-cogs"></i> Tools
                                                   <i class="icon-angle-down"></i>
                                                   </a>
                                                   <ul class="dropdown-menu pull-right">
                                                      <li><a href="deletetrip/{{ $trip['id'] }}"><i class="icon-trash"></i> ဖ်က္မည္</a></li>
                                                      <li class="divider"></li>
                                                      <li><a href="define-ownseat/{{ $trip['id'] }}"><i class="icon-pencil"></i> ခုံပုိင္သတ္မွတ္ရန္</a></li>
                                                      <li class="divider"></li>
                                                      @if(!is_null($trip['extendcity']))
                                                      <li><a href="/trip/editextend/{{$trip['id']}}"><i class="icon-pencil"></i> ဆက္သြားမည့္ျမိဳ႕ ျပင္ရန္</a></li>
                                                      <li><a href="/trip/deleteextend/{{$trip['extendcity']['id']}}"><i class="icon-trash"></i> ဆက္သြားမည့္ျမိဳ႕ ဖ်က္ရန္</a></li>
                                                      @else
                                                      <li><a href="/trip/extend/{{$trip['id']}}"><i class="icon-pencil"></i> ဆက္သြားမည့္ျမိဳ႕ ထည့္ရန္</a></li>
                                                      @endif
                                                      <li class="divider"></li>
                                                      <li><a href="/closetrip/{{ $trip['id'] }}"><i class="icon-ban-circle"></i> ခဏ ရပ္ဆိုင္းရန္</a></li>
                                                      
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
   <script type="text/javascript" src="../../../js/jquery.battatech.excelexport.min.js"></script>
   <script>
      jQuery(document).ready(function() {       
         // initiate layout and plugins
         App.setPage("table_editable");
         // App.init();
      });
      $("#btnExportExcel").click(function () {
               var filename="TripList";
               var uri =$("#tblExport").btechco_excelexport({
                      containerid: "tblExport"
                     , datatype: $datatype.Table
                     , returnUri: true
                  });
               $(this).attr('download', filename+'.xls').attr('href', uri).attr('target', '_blank');
        });
   </script>
@stop
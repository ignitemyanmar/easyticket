@extends('../admin')
@section('content')
   <link rel="stylesheet" href="../../css/reveal.css" />
   <link rel="stylesheet" href="../../assets/data-tables/DT_bootstrap.css" />
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
                        အေရာင္းကုိယ္စားလွယ္ ႏွင့္ အေၾကြးစာရင္းမ်ား          
                        <small></small>
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <i class="icon-angle-right"></i>
                        </li>
                        <!-- <li><a href="/">Dashboard</a></li> -->
                        
                     </ul>
                     <!-- END PAGE TITLE & BREADCRUMB-->
                  </div>
               </div>
            <!-- END PAGE HEADER-->
            <div id="dashboard">
               <!-- BEGIN PAGE -->
                  <div class="row-fluid">
                     <div class="span12">
                        <div id="action_form" class="reveal-modal small" data-reveal> 
                           <h2>1876</h2> 
                           <button class="btn green btn-block">အရင္ေၾကြးေဟာင္းမ်ား ထည့္ရန္</button>
                           <button class="btn blue btn-block">ေကာ္မစ္ရွင္ ထည့္ရန္</button>
                           <button class="btn purple btn-block">ခရီးစဥ္ အလုိက္ ေကာ္မစ္ရွင္ထည့္ရန္</button>
                           <button class="btn green btn-block">စရံေငြထည့္ရန္</button>
                           <button class="btn yellow btn-block">ေၾကြးစာရင္းမ်ားၾကည့္ရန္</button>
                           <button class="btn blue btn-block">ေပးျပီးသားစာရင္းမ်ားၾကည့္ရန္</button>
                        </div>
                     </div>
                  </div>

                  <div class="row-fluid">
                     <div class="span12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet box blue">
                           <div class="portlet-title">
                              <h4><i class="icon-edit"></i>အေရာင္းကုိယ္စားလွယ္ ႏွင့္ အေၾကြးစာရင္းမ်ား</h4>
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
                                       <th>အမည္</th>
                                       <th>ၾကိဳတင္ေငြ</th>
                                       <th>ယခုအေၾကြး</th>
                                       <th>လက္က်န္အေၾကြး</th>
                                       <th>-</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                       @if($response)
                                          @foreach($response as $row)
                                             <tr>
                                                <td class="span-2">{{$row['name']}}</td>
                                                <td>@if($row['deposit_balance'] <0) 0 @else {{ $row['deposit_balance']}} @endif</td>
                                                <td>{{$row['credit']}}</td>
                                                <td>@if($row['deposit_balance'] > 0) 0 @else {{ substr($row['deposit_balance'],1) ? substr($row['deposit_balance'],1) : 0}} @endif</td>
                                                <td>
                                                   <a class="btn mini green-stripe" href="/report/agentcredit/{{$row->id}}">အေသးစိတ္ၾကည့္မည္</a>
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
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
   <script type="text/javascript" src="../../assets/data-tables/DT_bootstrap.js"></script>
   
   <script type="text/javascript" src="../../js/foundation.min.js"></script>
   <script>
      jQuery(document).ready(function() {  
         // initiate layout and plugins
         App.setPage("table_editable");
         // App.init();
      });
   </script>
   <script>
       $(document).foundation();
   </script>
@stop
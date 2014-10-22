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
                     <?php 
                        // $orderdate=$response[0]['departure_date']; 
                        $orderdate=Session::get('search_daily_date'); 
                        $V_operator_id=Session::get('operator_id');
                     ?>
                     <!-- END BEGIN STYLE CUSTOMIZER -->    
                     <!-- BEGIN PAGE TITLE & BREADCRUMB-->        
                     <h3 class="page-title">
                        ကားဂိတ်များ            
                      
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}">ပင်မ စာမျက်နှာ</a> 
                           <i class="icon-angle-right"></i>
                        </li>
                        <li><a href="/#">ကားဂိတ်များ </a></li>
                        
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
                              <h4><i class="icon-edit"></i>ကားဂိတ်များ </h4>
                           </div>
                           <div class="portlet-body">
                              <div class="clearfix">
                                 <div class="btn-group">
                                    <a href="/agents/create">
                                    <button id="" class="btn green">
                                    Add New <i class="icon-plus"></i>
                                    </button>
                                    </a>
                                 </div>
                                 
                              </div>
                              @if(Session::has('message'))
                                 <?php $message=Session::get('message'); ?>
                                 <div class="alert alert-warning">
                                    <button class="close" data-dismiss="alert"></button>
                                    <strong>Info ! </strong>{{$message}}
                                 </div>
                              @endif
                              <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                 <thead>
                                    <tr>
                                       <th>ကားဂိတ် အမည်</th>
                                       <th>ဖုန်းနံပါတ် </th>
                                       <th>လိပ်စာ</th>
                                       <th>ြပင်ရန်</th>
                                       <th>ဖျက်ရန်</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php $userid=Auth::user()->id; 
                                          $operator_id=Operator::whereuser_id($userid)->pluck('id');
                                    ?>

                                    @if(count($response)==0)
                                       There is no record in database.
                                    @else
                                       Total Record : {{$totalcount}}
                                    @endif
                                 
                                    @foreach($response as $operator)
                                                <tr>
                                                   <td>{{$operator['name']}}</td>
                                                   <td>{{$operator['phone']}}</td>
                                                   <td>{{$operator['address']}}<br>
                                                    <!-- @if($operator['id']!=$operator_id) {{$operator->login_info['username']}}<br>Client Secret : {{$operator->login_info['client_secret']}}<br>Client_id : {{$operator->login_info['client_id']}}<br> @else @endif -->
                                                    </td>
                                                   <td style="text-align:center;">
                                                         <a href="/operator-update/{{ $operator['id'] }}"  class="btn green button-submit">Edit</a><br><br>
                                                   </td>
                                                   <td style="text-align:center;">
                                                         <a href="deleteoperator/{{ $operator['id'] }}"   class="btn green button-submit">Delete</a>
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
@extends('../admin')
@section('content')
<style type="text/css">
   .padding-10{padding: 5px 10px;}
</style>
<link rel="stylesheet" href="../../assets/data-tables/DT_bootstrap.css" />
      <!-- {{HTML::style('../../src/select2.css')}}
      {{HTML::style('../../css/jquery-ui.css')}} -->
<!-- BEGIN PAGE -->  
   <div class="page-content">
      <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
               <div class="row-fluid">
                  <div class="span12">
                     <?php 
                        $orderdate=Session::get('search_daily_date'); 
                        $V_operator_id=Session::get('operator_id');
                     ?>
                     <h3 class="page-title">
                        အေရာင္းရဆုံး အေရာင္းကုိယ္စားလွယ္ စာရင္းမ်ား
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}">ပင္မစာမ်က္ႏွာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">
                           အေရာင္းရဆုံး အေရာင္းကုိယ္စားလွယ္ စာရင္းမ်ား
                           </a>
                        </li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-user"></i>
                           အေရာင္းရဆုံး အေရာင္းကုိယ္စားလွယ္ စာရင္းမ်ား
                           </h4>
                           <div class="actions">
                           </div>
                        </div>

                        <div class="portlet-body">
                           <table class="table table-striped table-bordered table-advance table-hover" id="sample_editable_1">
                              <thead>
                                 <tr>
                                    <th>အေရာင္းကုိယ္စားလွယ္</th>
                                    <th>စုစုေပါင္းခုံ</th>
                                    <th>ေရာင္းရေငြစုစုေပါင္း</th>
                                    <th>-</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if($response)
                                    @foreach($response as $trips)
                                       <tr>
                                          <td>{{$trips['name']}}</td>
                                          <td>{{ $trips['purchased_total_seat']}}</td>
                                          <td>{{ $trips['total_amount']}}</td>
                                          <td>
                                             <a class="btn mini green-stripe imagechange" id="" href="#">အေသးစိတ္ၾကည့္ရန္</a>
                                          </td>
                                       </tr>
                                    @endforeach
                                 @endif

                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            <!-- END PAGE CONTENT-->         
         </div>
      <!-- END PAGE CONTAINER-->
   </div>
<!-- END PAGE -->  
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
   <script type="text/javascript" src="../../assets/data-tables/DT_bootstrap.js"></script>
   <script>
      jQuery(document).ready(function() {       
         // initiate layout and plugins
         App.setPage("table_editable");
         // App.init();
      });
   </script>
   
@stop
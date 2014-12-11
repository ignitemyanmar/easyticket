@extends('../admin')
@section('content')
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
                        ကားအလုိက္ေရာင္းရေသာ လက္မွတ္စာရင္း
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}">ပင္မစာမ်က္ႏွာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">ကားအလုိက္ေရာင္းရေသာ လက္မွတ္စာရင္း</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-user"></i>ကားအလုိက္ေရာင္းရေသာ လက္မွတ္စာရင္း</h4>
                           <div class="actions">
                           </div>
                        </div>

                        <div class="portlet-body">
                           <table class="table table-striped table-bordered table-advance table-hover">
                              <thead>
                                 <tr>
                                   <!--  <th>ကားနံပါတ္</th>
                                    <th>ခရီးစဥ္</th>
                                    <th>ကားအမ်ုိဳးအစား</th>
                                    <th>အခ်ိန္</th> -->
                                    <th>အေရာင္းကုိယ္စားလွယ္</th>
                                    <th>လက္မွတ္အေရအတြက္</th>
                                    <th>ေရာင္းရေငြစုစုေပါင္း</th>
                                    <th>-</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if($response)
                                    @foreach($response as $result)
                                       <tr>
                                          <td><div style="word-wrap: break-word;width:330px;">{{$result['agent']}}</div></td>
                                          <td>{{$result['sold_tickets']}}</td>
                                          <td>{{$result['total_amount']}}</td>
                                          <td>
                                             <a class="btn mini green-stripe imagechange" id="" href="/report/seatoccupiedbybus/detail?bus_id={{$result['bus_id']}}">ခုံနံပါတ္ႏွင့္ဝယ္သူတြဲၾကည့္ရန္</a>
                                             <a class="btn mini green-stripe imagechange" id="" href="/report/dailybydeparturedate/detail?bus_id={{$result['bus_id']}}&agent_id={{$result['agent_id']}}">အေသးစိတ္ၾကည့္ရန္</a>
                                          </td>
                                       </tr>
                                    @endforeach
                                    <tr>
                                       <td>ဂိတ္ၾကီးအေရာင္း စုစုေပါင္း = {{$total_sold['main_gate_total']}} က်ပ္</td>
                                       <td colspan="2">ဂိတ္ခြဲအေရာင္း စုစုေပါင္း = {{$total_sold['agent_gate_total']}} က်ပ္</td>
                                       <td>အားလုံးစုစုေပါင္း = {{$total_sold['grand_total']}} က်ပ္</td>
                                    </tr>
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
@stop
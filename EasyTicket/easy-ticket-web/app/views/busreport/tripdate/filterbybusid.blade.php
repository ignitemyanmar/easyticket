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
                        ကားအလုိက် ေရာင်းရေသာလက်မှတ်စာရင်း
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}">ပင်မ စာမျက်နှာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">ကားအလုိက် ေရာင်းရေသာလက်မှတ်စာရင်း</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-user"></i>ကားအလုိက် ေရာင်းရေသာလက်မှတ်စာရင်း</h4>
                           <div class="actions">
                           </div>
                        </div>

                        <div class="portlet-body">
                           <table class="table table-striped table-bordered table-advance table-hover">
                              <thead>
                                 <tr>
                                    <th>ကားနံပါတ်</th>
                                    <th>ခရီးစဥ်</th>
                                    <th>ကားအမျိုးအစား</th>
                                    <th>အချိန်</th>
                                    <th>အေရာင်း ကုိယ်စားလှယ်</th>
                                    <th>ေရာင်းြပီး/စုစုေပါင်း လက်မှတ်များ </th>
                                    <th>စုစုေပါင်း ေရာင်းရေငွ</th>
                                    <th>-</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if($response)
                                    @foreach($response as $result)
                                       <tr>
                                          <td>{{$result['bus_no']}}</td>
                                          <td>{{$result['trip']}}</td>
                                          <td>{{$result['class']}}</td>
                                          <td>{{$result['departure_time']}}</td>
                                          <td>{{$result['agent']}}</td>
                                          <td>{{$result['sold_tickets']}}/{{$result['total_seats']}}</td>
                                          <td>{{$result['total_amount']}}</td>
                                          <td>
                                             <a class="btn mini green-stripe imagechange" id="" href="/report/seatoccupiedbybus/detail?bus_id={{$result['bus_id']}}">ခုံနံပါတ်နှင့်ဝယ်သူ တွဲြကည့်ရန်</a>
                                             <a class="btn mini green-stripe imagechange" id="" href="/report/dailybydeparturedate/detail?bus_id={{$result['bus_id']}}&agent_id={{$result['agent_id']}}">အေသးစိတ်ြကည့်ရန်</a>
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
@stop
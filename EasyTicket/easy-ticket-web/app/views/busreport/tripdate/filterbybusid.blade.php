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
                           <a href="/report/dailycarandadvancesale?access_token={{Auth::user()->access_token}}&operator_id={{Session::get('operator_id')}}">ပင္မစာမ်က္ႏွာ</a> 
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
                                    <th class="span3">ခရီးစဥ္/ထြက္ခြာမည့္ေန႔ / အခ်ိန္</th>
                                    <th class="span3">အေရာင္းကုိယ္စားလွယ္</th>
                                    <th class="span2">ဂိတ္ၾကီး / ဂိတ္ခြဲ</th>
                                    <th class="span2">လက္မွတ္ အေရအတြက္</th>
                                    <th class="span2">Free Ticket</th>
                                    <th class="span2">Discount</th>
                                    <th class="span2">Price</th>
                                    <th class="span2">ေရာင္းရေငြစုစုေပါင္း</th>
                                    <th class="span2">-</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if($response)
                                    @foreach($response as $result)
                                       <tr>
                                          <td>{{$result['trip'] .' ['.$result['class'].']'}}<br>{{$result['departure_date'].' ['.$result['departure_time'].']'}}</td>
                                          <td><div>{{$result['agent']}}</div></td>
                                          <td>@if($result['owner'] ==1) ဂိတ္ၾကီး @else ဂိတ္ခြဲ  @endif</td>
                                          <td>{{$result['sold_tickets']}}</td>
                                          <td>{{$result['free_ticket']}}</td>
                                          <td>{{$result['discount']}}</td>
                                          <td>{{$result['price']}}({{$result['commission']}})</td>
                                          <td>{{$result['total_amount']}}</td>
                                          <td>
                                             <div class="btn-group pull-right">
                                                   <a class="btn blue" href="#" data-toggle="dropdown">
                                                      <i class="icon-cog"></i> Views
                                                      <i class="icon-angle-down"></i>
                                                   </a>
                                                   <ul class="dropdown-menu"> 
                                                      <li>
                                                        <a class="btn mini green-stripe imagechange" id="" href="/report/seatoccupiedbybus/detail?access_token={{Auth::user()->access_token}}&trip_id={{$result['id']}}&departure_date={{$result['departure_date']}}">ခုံနံပါတ္ႏွင့္ဝယ္သူတြဲၾကည့္ရန္</a>
                                                      </li>
                                                      <li>
                                                        <a class="btn mini green-stripe imagechange" id="" href="/report/dailybydeparturedate/detail?access_token={{Auth::user()->access_token}}&trip_id={{$result['id']}}&departure_date={{$result['departure_date']}}&agent_id={{$result['agent_id']}}">အေသးစိတ္ၾကည့္ရန္</a>
                                                      </li>
                                                   </ul>
                                                </div>
                                          </td>
                                       </tr>
                                    @endforeach
                                    <tr>
                                       <td colspan="4">ဂိတ္ၾကီးအေရာင္း စုစုေပါင္း = {{$total_sold['main_gate_total']}} က်ပ္</td>
                                       <td colspan="3">ဂိတ္ခြဲအေရာင္း စုစုေပါင္း = {{$total_sold['agent_gate_total']}} က်ပ္</td>
                                       <td colspan="2">အားလုံးစုစုေပါင္း = {{$total_sold['grand_total']}} က်ပ္</td>
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
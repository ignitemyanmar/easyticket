@extends('../admin')
@section('content')
<style type="text/css">
   .padding-5{padding: 5px;}
</style>
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
                        &nbsp;ေန့ရက်အလိုက် အေရာင်းစာရင်းများ
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}">ပင်မ စာမျက်နှာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">အစေန့မှ အဆုံးေန့အတွင်းအေရာင်းစာရင်း </a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">ေန့ရက်အလိုက် အေရာင်းစာရင်းများ</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-user"></i>ေန့ရက်အလိုက် အေရာင်းစာရင်းများ</h4>
                           <div class="actions">
                              
                           </div>
                        </div>

                        <div class="portlet-body">
                           <table class="table table-striped table-bordered table-advance table-hover">
                              <thead>
                                 <tr>
                                    <th>ကားနံပါတ်</th>
                                    <th>ေရာင်းြပီး/စုစုေပါင်း လက်မှတ်များ</th>
                                    <th>ထွက်ခွာမည့်ေန့ရက်</th>
                                    <th>အချိန်</th>
                                    <th>စုစုေပါင်း ေရာင်းရေငွ</th>
                                    <th>-</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if($response)
                                    @foreach($response as $tripbydaily)
                                       <tr>
                                          <td class="hidden-phone">{{ $tripbydaily['bus_no']}}</td>
                                          <td>{{ $tripbydaily['purchased_total_seat']}}/{{ $tripbydaily['total_seat']}}</td>
                                          <td>{{ $tripbydaily['departure_date']}}</td>
                                          <td>{{ $tripbydaily['time']}}</td>
                                          <td>{{ $tripbydaily['total_amout']}}</td>
                                          <td>
                                             <a class="btn mini green-stripe imagechange" id="" href="/report/seatoccupiedbybus/detail?bus_id={{$tripbydaily['bus_id']}}">ခုံနံပါတ်နှင့်ဝယ်သူ တွဲြကည့်ရန်</a>
                                             <a class="btn mini green-stripe imagechange" id="" href="/triplist/{{$tripbydaily['bus_id']}}/busid?operator_id={{$parameter['operator_id']}}&agent_id={{$parameter['agent_id']}}&date={{$parameter['date']}}&time={{$tripbydaily['time']}}">အေသးစိတ်ြကည့်ရန်</a>
                                          </td>
                                       </tr>
                                    @endforeach
                                 @endif

                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
                  <div class="responsive span3 padding-5" data-tablet="span4" data-desktop="span4">
                     
                  </div>
               </div>
            <!-- END PAGE CONTENT-->         
         </div>
      <!-- END PAGE CONTAINER-->
   </div>
<!-- END PAGE -->  
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
@stop
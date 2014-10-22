@extends('../admin')
@section('content')
{{HTML::style('../../css/jquery-ui.css')}}
<style type="text/css">
   .padding-10{padding: 5px 10px;}
   .select2-container {min-width: 180px;}
</style>
<!-- BEGIN PAGE -->  
   <div class="page-content">
      <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
               <div class="row-fluid">
                  <div class="span12">
                        
                     <h3 class="page-title">
                     <?php 
                        // $orderdate=$response[0]['departure_date']; 
                        $orderdate=Session::get('search_daily_date'); 
                        $Vdeparture_time=Session::get('daily_rp_time'); 
                        Session::forget('daily_rp_time');
                        $V_operator_id=Session::get('operator_id');
                        $time='';
                        if($Vdeparture_time)
                           $time="(".$Vdeparture_time.")";
                     ?>
                        {{$orderdate.$time}}&nbsp;ေန့တွင် ေရာင်းရေသာ စာရင်း

                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}">ပင်မ စာမျက်နှာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="/report/dailycarandadvancesale/search?date={{$orderdate}}&operator_id={{$V_operator_id}}">ေန့စဥ် အေရာင်းစာရင်း</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">{{$time}}&nbsp;အတွက် ေရာင်းရေသာ စာရင်း</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-user"></i>{{$orderdate.$time}}&nbsp;အတွက် ေရာင်းရေသာ စာရင်း</h4>
                           <div class="actions">
                           </div>
                        </div>

                        <div class="portlet-body">
                           <table class="table table-striped table-bordered table-advance table-hover">
                              <thead>
                                 <tr>
                                    <th class="span1">ကားနံပါတ်</th>
                                    <th class="span2">ခရီးစဥ်</th>
                                    <th class="span1">&nbsp;&nbsp;&nbsp;&nbsp;အချိန်&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th class="span1">ကားအမျိုးအစား</th>
                                    <th class="span1">ေရာင်းြပီး/စုစုေပါင်း လက်မှတ်များ</th>
                                    <th class="span1">ေစျးနုန်း</th>
                                    <th class="span1">စုစုေပါင်းေရာင်းရေငွ</th>
                                    <th class="span4">-</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if($dailyforbus)
                                    @foreach($dailyforbus as $result)
                                    <tr>
                                       <td>{{$result['bus_no']}}</td>
                                       <td>{{$result['from']. '-'. $result['to']}}</td>
                                       <td>{{$result['time']}}</td>
                                       <td>{{$result['class']}}</td>
                                       <td>{{$result['sold_seat']}}/{{$result['total_seat']}}</td>
                                       <td>{{$result['price']}}</td>
                                       <td>{{$result['sold_amount']}}</td>
                                       <td>
                                          <a class="btn mini green-stripe imagechange" id="" href="/report/seatoccupiedbybus/detail?bus_id={{$result['bus_id']}}">ခုံနံပါတ်နှင့်ဝယ်သူ တွဲြကည့်ရန်</a>
                                          <a class="btn mini green-stripe imagechange" id="" href="/report/dailycarandadvancesale/detail?bus_id={{$result['bus_id']}}">အေသးစိတ်ြကည့်ရန်</a>
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
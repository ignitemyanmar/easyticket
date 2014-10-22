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
                        $orderdate=Session::get('search_daily_date'); 
                        $V_operator_id=Session::get('operator_id');
                     ?>
                        {{$orderdate}}&nbsp;ေန့တွင် ေရာင်းရေသာ စာရင်း

                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}">ပင္မစာမ်က္ႏွာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="/report/dailycarandadvancesale/search?date={{$orderdate}}&operator_id={{$V_operator_id}}">ေန႔စဥ္ အေရာင္းစာရင္း</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">{{$orderdate}}&nbsp;ေန့တွင် ေရာင်းရေသာ စာရင်း</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-user"></i>{{$orderdate}}&nbsp;ေန့တွင် ေရာင်းရေသာ စာရင်း</h4>
                           <div class="actions">
                           </div>
                        </div>

                        <div class="portlet-body">
                           <table class="table table-striped table-bordered table-advance table-hover">
                              <thead>
                                 <tr>
                                    <th class="span1">ကားနံပါတ္</th>
                                    <th class="span2">ခရီးစဥ္</th>
                                    <th class="span1">&nbsp;&nbsp;&nbsp;&nbsp;အခ်ိန္&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                    <th class="span1">ထွက်ခွာမည့် ေန့ရက်</th>
                                    <th class="span1">ကားအမ်ုိဳးအစား</th>
                                    <th class="span1">ေရာင္းျပီး/ စုစုေပါင္းလက္မွတ္</th>
                                    <th class="span1">ေရာင္းရေငြစုစုေပါင္း</th>
                                    <th class="span4">-</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if($response)
                                    @foreach($response as $result)
                                       <tr>
                                          <td>{{$result['bus_no']}}</td>
                                          <td>{{$result['from']. '-'. $result['to']}}</td>
                                          <td>{{$result['time']}}</td>
                                          <td>{{$result['departure_date']}}</td>
                                          <td>{{$result['class']}}</td>
                                          <td>{{$result['purchased_total_seat']}}/{{$result['total_seat']}}</td>
                                          <td>{{$result['total_amout']}}</td>
                                          <td>
                                             <a class="btn mini green-stripe imagechange" id="" href="/report/seatoccupiedbybus/detail?bus_id={{$result['bus_id']}}">ခုံနံပါတ္ႏွင့္ဝယ္သူတြဲၾကည့္ရန္</a>
                                             <a class="btn mini green-stripe imagechange" id="" href="/report/dailycarandadvancesale/detail?bus_id={{$result['bus_id']}}">အေသးစိတ္ၾကည့္ရန္</a>
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
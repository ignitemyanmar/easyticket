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
                        &nbsp;ေန႕ရက္အလုိက္အေရာင္းစာရင္းမ်ား
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}">ပင္မစာမ်က္ႏွာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">အစေန႕မွအဆုံးေန႕အတြင္းအေရာင္းစာရင္း</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">ေန႕ရက္အလုိက္အေရာင္းစာရင္းမ်ား</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-user"></i>ေန႕ရက္အလုိက္အေရာင္းစာရင္းမ်ား</h4>
                           <div class="actions">
                              
                           </div>
                        </div>

                        <div class="portlet-body">
                           <table class="table table-striped table-bordered table-advance table-hover">
                              <thead>
                                 <tr>
                                    <!-- <th>ကားနံပါတ္</th> -->
                                    <th>ခရီးစဥ္</th>
                                    <th>ကားထြက္ခ်ိန္</th>
                                    <th>ထြက္ခြာမည့္ေန႕</th>
                                    <th>လက္မွတ္အေရအတြက္</th>
                                    <th>ေရာင္းရေငြစုစုေပါင္း</th>
                                    <th>-</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if($response)
                                    @foreach($response as $tripbydaily)
                                       <tr>
                                          <!-- <td class="hidden-phone">{{ $tripbydaily['bus_no']}}</td> -->
                                          <td>{{ $tripbydaily['from'].'-'.$tripbydaily['to']}}</td>
                                          <td>{{ $tripbydaily['time']}}</td>
                                          <td>{{ $tripbydaily['departure_date']}}</td>
                                          <td>{{ $tripbydaily['purchased_total_seat']}}</td>
                                          <td>{{ $tripbydaily['total_amout']}}</td>
                                          <td>
                                             <a class="btn mini green-stripe imagechange" id="" href="/report/seatoccupiedbybus/detail?bus_id={{$tripbydaily['bus_id']}}">ခုံနံပါတ္ႏွင့္ဝယ္သူတြဲၾကည့္ရန္</a>
                                             <a class="btn mini green-stripe imagechange" id="" href="/triplist/{{$tripbydaily['bus_id']}}/busid?operator_id={{$parameter['operator_id']}}&agent_id={{$parameter['agent_id']}}&date={{$parameter['date']}}&time={{$tripbydaily['time']}}">အေသးစိတ္ၾကည့္ရန္</a>
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
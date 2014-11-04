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
                        &nbsp;ေန႔စဥ္ အေရာင္းစာရင္း
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}">ပင္မစာမ်က္ႏွာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">ေန႔စဥ္ အေရာင္းစာရင္း</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span9" data-tablet="span9" data-desktop="span9">
                     <?php $dailyforbustotal=0; $dailyforadvancetotal=0;?>
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-th-list"></i>ေန႔စဥ္ အေရာင္းစာရင္း</h4>
                           <div class="actions">
                           </div>
                        </div>

                        <div class="portlet-body">
                           <table class="table table-striped table-bordered table-advance table-hover">
                              <thead>
                                 <tr>
                                    <th>အခ်ိန္</th>
                                    <th>ခရီးစဥ္</th>
                                    <!-- <th>ေရာင္းျပီး/ စုစုေပါင္းလက္မွတ္</th> -->
                                    <th>ခုံအေရအတြက္</th>
                                    <!-- <th>ေစ်းႏုန္း</th> -->
                                    <th>ေရာင္းရေငြစုစုေပါင္း</th>
                                    <th>-</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if($dailyforbus)
                                    @foreach($dailyforbus as $result)
                                       <tr>
                                          <td>{{$result['time'].' ('.$result['class'].')'}}</td>
                                          <td>{{$result['from']. '-'. $result['to']}}</td>
                                          <td>{{$result['sold_seat']}}</td>
                                          <!-- <td>{{$result['price']}}</td> -->
                                          <td>{{$result['sold_amount']}}</td>
                                          <td>

                                             <!-- <a class="btn mini green-stripe imagechange" id="" href="/report/dailycarandadvancesale/time?operator_id={{$search['operator_id']}}&date={{$search['date']}}&departure_time={{$result['time']}}">အေသးစိတ္ၾကည့္ရန္</a> -->
                                             <a class="btn mini green-stripe imagechange" id="" href="/report/dailycarandadvancesale/detail?bus_id={{$result['busid']}}">အေသးစိတ္ၾကည့္ရန္</a>
                                          </td>
                                       </tr>
                                       <?php $dailyforbustotal +=$result['sold_amount']; ?>
                                    @endforeach
                                       <tr>
                                          <td colspan="2">&nbsp;</td>
                                          <td colspan="2" class="total" align="right">ယေန့အတွက် ေရာင်းရေငွစုစုေပါင်း</td>
                                          <td >{{$dailyforbustotal}}</td>
                                          <td >&nbsp;</td>
                                       </tr>
                                 @endif
                              </tbody>
                           </table>
                        </div>
                     </div>

                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-th-list"></i>ယေန႕ၾကိဳေရာင္းစာရင္း</h4>
                           <div class="actions">
                           </div>
                        </div>

                        <div class="portlet-body">
                           <table class="table table-striped table-bordered table-advance table-hover">
                              <thead>
                                 <tr>
                                    <!-- <th>ခရီးစဥ္</th> -->
                                    <th>ထြက္ခြာမည့္ေန႕</th>
                                    <th>ေရာင္းျပီး</th>
                                    <th>ခရီးစဥ္</th>
                                    <th>ေရာင္းရေငြစုစုေပါင္း</th>
                                    <th>-</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if($dailyforadvance)
                                    @foreach($dailyforadvance as $advance)
                                       <tr>
                                          <!-- <td>{{$advance['from'].'-'. $advance['to'] }}</td> -->
                                          <td>{{$advance['departure_date']}}</td>
                                          <td>{{$advance['purchased_total_seat']}}</td>
                                          <td>{{$advance['from'].'-'.$advance['to']}}</td>
                                          <td>{{$advance['total_amout']}}</td>
                                          <td>
                                             <a class="btn mini green-stripe imagechange" id="" href="/report/dailycarandadvancesale/date?operator_id={{$search['operator_id']}}&order_date={{$search['date']}}&departure_date={{$advance['departure_date']}}">အေသးစိတ္ၾကည့္ရန္</a>
                                          </td>
                                       </tr>
                                       <?php $dailyforadvancetotal +=$advance['total_amout']; ?>
                                    @endforeach
                                       <tr>
                                          <td colspan="2">&nbsp;</td>
                                          <td class="text-right total">ြကိုေရာင်းေငွ စုစုေပါင်း</td>
                                          <td >{{$dailyforadvancetotal}}</td>
                                          <td >&nbsp;</td>
                                       </tr>
                                 @endif

                              </tbody>
                           </table>
                        </div>
                     </div>
                     <div class="row-fluid">
                        <div class="span10 text-right total">ယေန့ နှင့် ြကိုေရာင်း စုစုေပါင်း :</div>
                        <div class="span2 total">{{$dailyforbustotal + $dailyforadvancetotal}} ကျပ်</div>
                     </div>
                  </div>
                  <div class="responsive span3 border padding-10" data-tablet="span3" data-desktop="span3">
                     <form action="/report/dailycarandadvancesale/search" method="get" class="horizontal-form">
                        <h3 class="form-section">ေန႕ရက္အလုိက္ရွာရန္</h3>
                        
                        <div class="row-fluid">
                           <div class="span6">
                              <div class="control-group">
                                 <label class="control-label" for="startdate">ေန့ရက် ေရွးရန်</label>
                                 <div class="controls">
                                    <input id="startdate" name="date" class="m-wrap span12" type="text" value="{{$search['date']}}">
                                 </div>
                              </div>
                           </div>
                        </div>
                        @if($search)
                           <input type="hidden" name="operator_id" value="{{$search['operator_id']}}">
                           <input type="hidden" name="agent_id" value="{{$search['agent_id']}}">
                        @endif

                        <div class="form-actions clearfix">
                           <button type="submit" class="btn green button-submit">ရွာရန္</button>
                        </div>

                     </form>
                  </div>
               </div>


            <!-- END PAGE CONTENT-->         
         </div>
      <!-- END PAGE CONTAINER-->
   </div>
<!-- END PAGE -->  
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
   {{HTML::script('../../src/jquery-ui.js')}}
   <script type="text/javascript">
      $(function(){
         var date = new Date();
         var m = date.getMonth(), d = date.getDate()-30, y = date.getFullYear();
         $("#startdate").datepicker({
            minDate: new Date(y, m, d),
            numberOfMonth: 2,
            onSelect: function(dateStr) {
                  var min = $(this).datepicker('getDate');
            },
            dateFormat: 'yy-mm-dd'
         });
      });
   </script>
@stop
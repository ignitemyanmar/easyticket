@extends('../admin')
@section('content')
<style type="text/css">
   .padding-10{padding: 5px 10px;}
</style>
      {{HTML::style('../../src/select2.css')}}
      {{HTML::style('../../css/jquery-ui.css')}}
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
                        အစေန့မှ အဆုံးေန့အတွင်းအေရာင်းစာရင်း
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}">ပင္မစာမ်က္ႏွာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">အေရာင်းစာရင်း များ</a>
                        </li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span8" data-tablet="span8" data-desktop="span8">
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-user"></i>အစေန့မှ အဆုံးေန့အတွင်းအေရာင်းစာရင်း </h4>
                           <div class="actions">
                           </div>
                        </div>

                        <div class="portlet-body">
                           <table class="table table-striped table-bordered table-advance table-hover">
                              <thead>
                                 <tr>
                                    <th>ေရာင်းသည့်ေန့ရက်</th>
                                    <th>ေရာင္းျပီး/ စုစုေပါင္းလက္မွတ္ </th>
                                    <th>ေရာင္းရေငြစုစုေပါင္း</th>
                                    <th>-</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if($response)
                                    @foreach($response as $tripbydaily)
                                       <tr>
                                          <td>{{ $tripbydaily['order_date']}}</td>
                                          <td>{{ $tripbydaily['purchased_total_seat']}}/{{ $tripbydaily['total_seat']}}</td>
                                          <td>{{ $tripbydaily['total_amout']}}</td>
                                          <td>
                                             <a class="btn mini green-stripe imagechange" id="" href="/triplist/{{$tripbydaily['order_date']}}/daily?operator_id={{$search['operator_id']}}&from_city={{$search['from']}}&to_city={{$search['to']}}&date={{$tripbydaily['order_date']}}&time={{$search['time']}}">အေသးစိတ္ၾကည့္ရန္</a>
                                          </td>
                                       </tr>
                                    @endforeach
                                 @endif

                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
                  <div class="responsive span4 border padding-10" data-tablet="span4" data-desktop="span4">
                     <form action="/report/operator/trip/dateranges" method="get" class="horizontal-form">
                        <h3 class="form-section">အစေန့မှ အဆုံးေန့အတွင်း အေရာင်း စာရင်း ရွာရန္ abc</h3>
                        <input type="hidden" value="{{$search['operator_id']}}" name="operator_id">
                        <div class="row-fluid">
                           <div class="span11">
                              <input type="hidden" value="{{$search['operator_id']}}" name="operator_id">
                              <div class="control-group">
                                 <label class="control-label" for="from">ဂိတ်ခွဲများ</label>
                                 <div class="controls">
                                    <select id="from" name="agent_id" class="m-wrap span10">
                                       <option value="All">All</option>
                                       @if($search['agent'])
                                          @foreach($search['agent'] as $row)
                                             <option value="{{$row['id']}}">{{$row['name']}}</option>
                                          @endforeach
                                       @endif
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="clear-fix">&nbsp;</div>
                        
                        <div class="row-fluid">
                           <div class="span11">
                              <div class="control-group">
                                 <label class="control-label" for="agent">ဂိတ်ခွဲ ေရွးရန်</label>
                                 <div class="controls">
                                    <select id="agent" name="agent" class="m-wrap span10">
                                       @if($search['cities'])
                                          @foreach($search['cities']['from'] as $from)
                                             <option value="{{$from['id']}}">{{$from['name']}}</option>
                                          @endforeach
                                       @endif
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="clear-fix">&nbsp;</div>
                        <div class="row-fluid">
                           <div class="span11">
                              <div class="control-group">
                                 <label class="control-label" for="from">ထွက်ခွာသည့်   ြမို့ ေရွးရန်</label>
                                 <div class="controls">
                                    <select id="from" name="from" class="m-wrap span10">
                                       @if($search['cities'])
                                          @foreach($search['cities']['from'] as $from)
                                             <option value="{{$from['id']}}">{{$from['name']}}</option>
                                          @endforeach
                                       @endif
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="clear-fix">&nbsp;</div>
                        
                        <div class="row-fluid">
                           <div class="span11">
                              <div class="control-group">
                                 <label class="control-label" for="to">ေရာက်ၡိသည့်ြမို့ ေရွးရန်</label>
                                 <div class="controls">
                                    <select id="to" name="to" class="m-wrap span10">
                                       @if($search['cities'])
                                          @foreach($search['cities']['to'] as $to)
                                             <option value="{{$to['id']}}">{{$to['name']}}</option>
                                          @endforeach
                                       @endif
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="clear-fix">&nbsp;</div>

                        <?php $date=date('Y-m-d'); ?>
                        <div class="row-fluid">
                           <div class="span6">
                              <div class="control-group">
                                 <label class="control-label" for="startdate">အစေန့ေရွးရန် (မှ)</label>
                                 <div class="controls">
                                    <input id="startdate" name="start_date" class="m-wrap span12" type="text" value="{{$date}}">
                                 </div>
                              </div>
                           </div>
                           <div class="span6">
                              <div class="control-group">
                                 <label class="control-label" for="enddate">အဆုံးေန့ေရွးရန်(အထိ)</label>
                                 <div class="controls">
                                    <input id="enddate" name="end_date" class="m-wrap span12" type="text" value="{{$date}}">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row-fluid">
                           <div class="span11">
                              <div class="control-group">
                                 <label class="control-label" for="departure_time">အချိန်ေရွးရန်</label>
                                 <div class="controls">
                                    <select id="departure_time" name="departure_time" class="m-wrap span10">
                                       @if($search['times'])
                                             <option value="">All</option>
                                          @foreach($search['times'] as $time)
                                             <option value="{{$time['time']}}">{{$time['time']}}</option>
                                          @endforeach
                                       @endif
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>

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
   {{HTML::script('../../src/select2.min.js')}}
   <script type="text/javascript">
      $(function(){
         $('#from').select2();
         $('#to').select2();
         $('#departure_time').select2();
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
         $("#enddate").datepicker({
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
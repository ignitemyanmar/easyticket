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
                        အေရာင္းရဆုံး ခရီးစဥ္ စာရင္းမ်ား
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
                           အေရာင္းရဆုံး ခရီးစဥ္ စာရင္းမ်ား
                           </a>
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
                           <h4><i class="icon-user"></i>
                           အေရာင္းရဆုံး ခရီးစဥ္ စာရင္းမ်ား
                           </h4>
                           <div class="actions">
                           </div>
                        </div>

                        <div class="portlet-body">
                           <table class="table table-striped table-bordered table-advance table-hover">
                              <thead>
                                 <tr>
                                    <th>ခရီးစဥ္</th>
                                    <th>ကားထြက္ခ်ိန္</th>
                                    <th>ကားအမ်ိဳးအစား</th>
                                    <th>စုစုေပါင္းခုံ</th>
                                    <th>ေရာင္းရေငြစုစုေပါင္း</th>
                                    <th>-</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if($response)
                                    @foreach($response as $trips)
                                       <tr>
                                          <td>{{$trips['trip']}}</td>
                                          <td>{{$trips['time']}}</td>
                                          <td>{{$trips['classes']}}</td>
                                          <td>{{ $trips['sold_total_seat']}}</td>
                                          <td>{{ $trips['total_amount']}}</td>
                                          <td>
                                             <a class="btn mini green-stripe imagechange" id="" href="/report/bestseller/tripdetail?operator_id={{$search['operator_id']}}&agent_id={{$search['agent_id']}}&start_date={{$search['start_date']}}&end_date={{$search['end_date']}}">အေသးစိတ္ၾကည့္ရန္</a>
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
                     <form action="/report/bestseller/time" method="get" class="horizontal-form">
                        <h3 class="form-section">အေရာင္းရဆုံး ခရီးစဥ္ စာရင္းမ်ား</h3>
                        <div class="row-fluid">
                           <div class="span11">
                              <input type="hidden" value="{{$search['operator_id']}}" name="operator_id">
                              <div class="control-group">
                                 <label class="control-label" for="from">ဂိတ်ခွဲများ</label>
                                 <div class="controls">
                                    <select id="agents" name="agent_id" class="m-wrap span10">
                                       <option value="">All</option>
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
                              <input type="hidden" value="{{$search['operator_id']}}" name="operator_id">
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
         $('#agents').select2();
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
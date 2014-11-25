@extends('../admin')
@section('content')
{{HTML::style('../../src/select2.css')}}
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
                     <?php 
                        $orderdate=Session::get('search_daily_date'); 
                        $V_operator_id=Session::get('operator_id');
                     ?> 
                     <h3 class="page-title">
                        ကားခ်ဳပ္အေရာင္းစာရင္း
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}">ပင္မစာမ်က္ႏွာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">ကားခ်ဳပ္အေရာင္းစာရင္း</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span9" data-tablet="span9" data-desktop="span9">
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-user"></i>ကားခ်ဳပ္အေရာင္းစာရင္း</h4>
                           <div class="actions">
                           </div>
                        </div>

                        <div class="portlet-body">
                           <table class="table table-striped table-bordered table-advance table-hover">
                              <thead>
                                 <tr>
                                    <!-- <th>ကားနံပါတ္</th> -->
                                    <!-- <th> ခရီးစဥ္</th> -->
                                    <th>ကား အမ်ုိဳးအစား</th>
                                    <th>ကားထြက္ခ်ိန္</th>
                                    <th>ကားထြက္မည့္ေန႕</th>
                                    <th>ခုံအေရအတြက္</th>
                                    <!-- <th>ေရာင္းျပီး/ စုစုေပါင္း လက္မွတ္</th> -->
                                    <th>ေရာင္းရေငြစုစုေပါင္း</th>
                                    <th>-</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if($response)
                                    @foreach($response as $tripbydaily)
                                       <tr>
                                          <!-- <td>{{$tripbydaily['bus_no']}}</td> -->
                                          <!-- <td>{{$tripbydaily['trip']}}</td> -->
                                          <td>{{$tripbydaily['class']}}</td>
                                          <td>{{$tripbydaily['departure_time']}}</td>
                                          <td>{{date('d/m/Y',strtotime($tripbydaily['departure_date']))}}</td>
                                          <td>{{$tripbydaily['sold_seats']}}</td>
                                          <td>{{$tripbydaily['total_amount']}}</td>
                                          <td>
                                             <a class="btn mini green-stripe imagechange" id="" href="/report/dailybydeparturedate/busid?bus_id={{$tripbydaily['id']}}">အေသးစိတ္ၾကည့္ရန္</a>
                                          </td>
                                       </tr>
                                    @endforeach
                                 @endif

                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
                  <div class="responsive span3 border padding-10" data-tablet="span3" data-desktop="span3">
                     <form action="/report/dailybydeparturedate/search" method="get" class="horizontal-form">
                        <h3 class="form-section">ကားခ်ဳပ္အေရာင္းစာရင္း ရွာရန္</h3>
                        <div class="row-fluid">
                           <div class="span11">
                                 <input type="hidden" value="{{$search['operator_id']}}" name="operator_id">
                              <div class="control-group">
                                 <label class="control-label" for="from">ထွက်ခွာသည့် ြမို့ ေရွးရန်</label>
                                 <div class="controls">
                                    <select id="from" name="from" class="m-wrap span10">
                                       @if(isset($search['cities']['from']))
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
                                       @if(isset($search['cities']['to']))
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

                        <div class="row-fluid">
                           <div class="span6">
                              <div class="control-group">
                                 <label class="control-label" for="startdate">ထွက်ခွာသည့် ေန့ရက် ေရွးရန်</label>
                                 <div class="controls">
                                    <input id="startdate" name="departure_date" class="m-wrap span12" type="text">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row-fluid">
                           <div class="span11">
                              <div class="control-group">
                                 <label class="control-label" for="departure_time">ထွက်ခွာသည့် အချိန်ေရွးရန်</label>
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
         /*$("#enddate").datepicker({
            minDate: new Date(y, m, d),
            numberOfMonth: 2,
            onSelect: function(dateStr) {
                  var min = $(this).datepicker('getDate');
            },
            dateFormat: 'yy-mm-dd'
         });*/
      });
   </script>
@stop
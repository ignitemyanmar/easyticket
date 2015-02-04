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
                        
                     <h3 class="page-title">
                        Trip list by Date Range (Agent Report)
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="/itemlist?access_token={{Auth::user()->access_token}}">Trip List</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">Trip list reports</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span8" data-tablet="span8" data-desktop="span8">
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-user"></i>Trip list report List</h4>
                           <div class="actions">
                           </div>
                        </div>

                        <div class="portlet-body">
                           <table class="table table-striped table-bordered table-advance table-hover">
                              <thead>
                                 <tr>
                                    <th>Date</th>
                                    <th class="hidden-phone">Sold Tickets</th>
                                    <th>Total Tickets</th>
                                    <th class="hidden-phone">Total</th>
                                    <th>Action</th>
                                    <!-- <th></th> -->
                                 </tr>
                              </thead>
                              <tbody>
                                 @if($response)
                                    @foreach($response as $tripbydaily)
                                       <tr>
                                          <td class="hidden-phone">{{ $tripbydaily['order_date']}}</td>
                                          <td>{{ $tripbydaily['purchased_total_seat']}}</td>
                                          <td>{{ $tripbydaily['total_seat']}}</td>
                                          <td>{{ $tripbydaily['total_amout']}}</td>
                                          <td>
                                             <a class="btn mini green-stripe imagechange" id="" href="/agent/triplist/{{$tripbydaily['order_date']}}/daily?access_token={{Auth::user()->access_token}}&operator_id={{$search['operator_id']}}&from_city={{$search['from']}}&to_city={{$search['to']}}&date={{$tripbydaily['order_date']}}&time={{$search['time']}}&agent_id={{$search['agent_id']}}">အေသးစိတ္ၾကည့္ရန္</a>
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
                     <form action="/report/agent/trip/dateranges" method="get" class="horizontal-form">
                        <h3 class="form-section">Search trips report by date</h3>
                        <div class="row-fluid">
                           <div class="span11">
                                 <input type="hidden" name="access_token" value="{{Auth::user()->access_token}}">
                                 <input type="hidden" value="{{$search['operator_id']}}" name="operator_id">
                                 <input type="hidden" value="{{$search['agent_id']}}" name="agent_id">
                              <div class="control-group">
                                 <label class="control-label" for="from">From</label>
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
                        <div class="row-fluid">
                           <div class="span11">
                              <div class="control-group">
                                 <label class="control-label" for="to">To</label>
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
                        <div class="row-fluid">
                           <div class="span6">
                              <div class="control-group">
                                 <label class="control-label" for="startdate">Start Date</label>
                                 <div class="controls">
                                    <input id="startdate" name="start_date" class="m-wrap span12" type="text">
                                 </div>
                              </div>
                           </div>
                           <div class="span6">
                              <div class="control-group">
                                 <label class="control-label" for="enddate">End Date</label>
                                 <div class="controls">
                                    <input id="enddate" name="end_date" class="m-wrap span12" type="text">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row-fluid">
                           <div class="span11">
                              <div class="control-group">
                                 <label class="control-label" for="departure_time">Departure Time</label>
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
@extends('../admin')
@section('content')
<style type="text/css">
   .padding-10{padding: 5px 10px;}
</style>
{{HTML::style('../../assets/chosen-bootstrap/chosen/chosen.css')}}

      <!-- {{HTML::style('../../src/select2.css')}} -->
      {{HTML::style('../../css/jquery-ui.css')}}
<!-- BEGIN PAGE -->  
   <div class="page-content">
      <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
               <div class="row-fluid">
                  <div class="span12">
                        
                     <h3 class="page-title">
                        Seat Occupied by Bus
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
                           <h4><i class="icon-user"></i>Bus List for trip</h4>
                           <div class="actions">
                           </div>
                        </div>

                       <!--  <div class="portlet-body" id="bus_lists">
                           <div class="portlet box light-grey">
                              <div class="portlet-title">
                                 <h4><i class="icon-user"></i>Trip list report List</h4>
                                 <div class="actions">
                                    
                                 </div>
                              </div> -->

                              <div class="portlet-body">
                                 <table class="table table-striped table-bordered table-advance table-hover">
                                    <thead>
                                       <tr>
                                          <th>Seat No</th>
                                          <th class="hidden-phone">customer_name</th>
                                          <th>Agent</th>
                                          <th>Departure Date</th>
                                          <th>Time</th>
                                          <th>Invoice No</th>
                                          <th class="hidden-phone">Amount</th>
                                          <!-- <th></th> -->
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @if($response)
                                          @foreach($response['seat_plan'] as $bus)
                                             <tr>
                                                <td class="hidden-phone">{{ $bus['bus_no']}}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                
                                             </tr>
                                          @endforeach
                                       @endif

                                    </tbody>
                                 </table>
                              </div>
                          <!--  </div>
                        </div> -->
                     </div>
                  </div>
                  <div class="responsive span4 border padding-10" data-tablet="span4" data-desktop="span4">
                     <form action="/report/seatoccupiedbytrip" method="get" class="horizontal-form">
                        <h3 class="form-section">Search trips report by date</h3>
                        <input type="hidden" name="access_token" value="{{Auth::user()->access_token}}">
                        <div class="row-fluid">
                           <div class="span12">
                              <input type="hidden" value="{{$search['operator_id']}}" name="operator_id">
                              <div class="control-group">
                                 <label class="control-label" for="Operator">Operator</label>
                                 <div class="controls">
                                    <select class="span12 chosen" data-placeholder="Choose a operator" tabindex="1" name="operator">
                                       <option value="all">All</option>
                                       @if(isset($search['operators']))
                                          @foreach($search['operators'] as $operator)
                                             <option value="{{$operator['id']}}">{{$operator['name']}}</option>
                                          @endforeach
                                       @endif
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row-fluid">
                           <div class="span12">
                              <input type="hidden" value="{{$search['operator_id']}}" name="operator_id">
                              <div class="control-group">
                                 <label class="control-label" for="from">From</label>
                                 <div class="controls">
                                    <select class="span12 chosen" data-placeholder="Choose a City" tabindex="2" name="from">
                                       <option value=""></option>
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
                        <div class="row-fluid">
                           <div class="span12">
                              <div class="control-group">
                                 <label class="control-label" for="to">To</label>
                                 <div class="controls">
                                    <select class="span12 chosen" data-placeholder="Choose a City" tabindex="3" name="to">
                                       <option value=""></option>
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
                        <div class="row-fluid">
                           <div class="span6">
                              <div class="control-group">
                                 <label class="control-label" for="startdate">Departure Date</label>
                                 <div class="controls">
                                    <input id="startdate" name="departure_date" class="m-wrap span12" type="text">
                                 </div>
                              </div>
                           </div>
                           <div class="span6">
                              &nbsp;
                           </div>
                        </div>
                        <div class="row-fluid">
                           <div class="span11">
                              <div class="control-group">
                                 <label class="control-label" for="departure_time">Departure Time</label>
                                 <div class="controls">
                                    <select class="span12 chosen" data-placeholder="Choose a Time" tabindex="5" name="departure_time">
                                       <option value=""></option>

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
<!-- 
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
   
    -->
    {{HTML::script('../../src/jquery-ui.js')}}
   <!-- {{HTML::script('../../src/select2.min.js')}} -->
{{HTML::script('../../assets/chosen-bootstrap/chosen/chosen.jquery.min.js')}}

   {{HTML::script('../../js/search.js')}}
   
@stop
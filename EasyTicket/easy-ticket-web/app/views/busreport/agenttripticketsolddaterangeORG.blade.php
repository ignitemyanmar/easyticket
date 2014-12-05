@extends('../admin')
@section('content')

<link rel="stylesheet" href="../../../../assets/data-tables/DT_bootstrap.css" />
<link rel="stylesheet" type="text/css" href="../../../../assets/chosen-bootstrap/chosen/chosen.css" />
<link rel="stylesheet" type="text/css" href="../../../../assets/bootstrap-datepicker/css/datepicker.css" />

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
                        @if($search['trips']==1)
                           ခရီးစဥ္အလုိက္ အေရာင္းစာရင္းမ်ား
                        @else
                           အေရာင္းကုိယ္စားလွယ္ႏွင့္ အေရာင္းစာရင္းမ်ား
                        @endif
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
                           @if($search['trips']==1)
                              ခရီးစဥ္အလုိက္ အေရာင္းစာရင္းမ်ား
                           @else
                           အေရာင္းကုိယ္စားလွယ္ႏွင့္ အေရာင္းစာရင္းမ်ား
                           @endif</a>
                        </li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-user"></i>
                           @if($search['trips']==1)
                              ခရီးစဥ္အလုိက္ အေရာင္းစာရင္းမ်ား
                           @else
                           အေရာင္းကုိယ္စားလွယ္ႏွင့္ အေရာင္းစာရင္းမ်ား
                           @endif
                           </h4>
                           <div class="actions">
                           </div>
                        </div>

                        <div class="row-fluid search-default">
                           <div class="span2">
                              <div class="control-group">
                                 <label class="control-label" for="from">&nbsp;</label>
                                 <div class="btn-group" style="margin:11px;">
                                    <button class="btn green dropdown-toggle" data-toggle="dropdown">Tools (Print) <i class="icon-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                       <li><a href="#" class="print">Print</a></li>
                                       <li><a href="#" id="btnExportExcel">Export to Excel</a></li>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                           <div class="span10">
                                 <form action="/report/operator/trip/dateranges" method="get" class="horizontal-form">
                                    @if($search['trips']!=1)
                                       <div class="row-fluid">
                                          <div class="span6 responsive">
                                             <input type="hidden" value="{{$search['operator_id']}}" name="operator_id">
                                             <div class="control-group">
                                                <label class="control-label" for="from">ဂိတ်ခွဲများ</label>
                                                <div class="controls">
                                                   <select name="agent_id" class="m-wrap span10 chosen">
                                                      <option value="All">All</option>
                                                      @if($search['agent'])
                                                         @foreach($search['agent'] as $row)
                                                            <option value="{{$row['id']}}" @if($row['id']==$search['agent_id']) selected @endif>{{$row['name']}}</option>
                                                         @endforeach
                                                      @endif
                                                   </select>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="span3">
                                             <input type="hidden" value="{{$search['trips']}}" name="trips">
                                             <input type="hidden" value="{{$search['operator_id']}}" name="operator_id">
                                             <div class="control-group">
                                                <label class="control-label" for="from">ထွက်ခွာသည့်   ြမို့ ေရွးရန်</label>
                                                <div class="controls">
                                                   <select name="from" class="m-wrap span12 chosen" id="from">
                                                      <option value="all">All</option>
                                                      @if($search['cities'])
                                                         @foreach($search['cities']['from'] as $from)
                                                            <option value="{{$from['id']}}" @if($from['id']==$search['from']) selected @endif>{{$from['name']}}</option>
                                                         @endforeach
                                                      @endif
                                                   </select>
                                                </div>
                                                
                                             </div>
                                          </div>
                                          <div class="span3">
                                             <div class="control-group">
                                                <label class="control-label" for="to">ေရာက်ၡိသည့်ြမို့ ေရွးရန်</label>
                                                <div class="controls" id='to'>
                                                   <select name="to" class="m-wrap span12 chosen">
                                                      @if($search['cities'])
                                                         @foreach($search['cities']['to'] as $to)
                                                            <option value="{{$to['id']}}" @if($to['id']==$search['to']) selected @endif>{{$to['name']}}</option>
                                                         @endforeach
                                                      @endif
                                                   </select>
                                                </div>
                                                <div class="controls"  id='to_all' style="display:none;">
                                                   <select name="to_all" class="m-wrap span12 chosen">
                                                      <option value="0">All</option>
                                                   </select>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="clear-fix">&nbsp;</div>
                                       
                                       <div class="row-fluid">
                                          <div class="span3">
                                             <div class="control-group">
                                                <label class="control-label" for="startdate">အစေန့ေရွးရန် (မှ)</label>
                                                <div class="controls">
                                                   <input id="startdate" name="start_date" class="m-wrap span12 m-ctrl-medium date-picker" size="16" type="text" value="{{$search['start_date']}}">
                                                </div>
                                             </div>
                                          </div>
                                          <div class="span3">
                                             <div class="control-group">
                                                <label class="control-label" for="enddate">အဆုံးေန့ေရွးရန်(အထိ)</label>
                                                <div class="controls">
                                                   <input id="enddate" name="end_date" class="m-wrap span12 m-ctrl-medium  date-picker" size="16" type="text" value="{{$search['end_date']}}">
                                                </div>
                                             </div>
                                          </div>
                                          <div class="span3">
                                             <div class="control-group">
                                                <label class="control-label" for="departure_time">အချိန်ေရွးရန်</label>
                                                <div class="controls">
                                                   <select id="departure_time" name="departure_time" class="m-wrap span12 chosen">
                                                      @if($search['times'])
                                                            <option value="">All</option>
                                                         @foreach($search['times'] as $time)
                                                            <option value="{{$time['time']}}" @if($search['time']== $time['time']) selected @endif>{{$time['time']}}</option>
                                                         @endforeach
                                                      @endif
                                                   </select>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="span3">
                                             <div class="control-group">
                                                <label class="control-label" for="departure_time">&nbsp;</label>

                                                <input type="hidden" value="{{$search['end_date'].' to '. $search['end_date']}}" id="report_date">
                                                <button type="submit" class="btn green pull-right">Search &nbsp; <i class="m-icon-swapright m-icon-white"></i></button>
                                             </div>
                                          </div>
                                       </div>
                                    @else
                                       <div class="row-fluid">
                                          <div class="span3">
                                             <input type="hidden" value="{{$search['trips']}}" name="trips">
                                             <input type="hidden" value="{{$search['operator_id']}}" name="operator_id">
                                             <div class="control-group">
                                                <label class="control-label" for="from">ထွက်ခွာသည့်   ြမို့ ေရွးရန်</label>
                                                <div class="controls">
                                                   <select name="from" class="m-wrap span12 chosen" id="from">
                                                      <option value="all">All</option>
                                                      @if($search['cities'])
                                                         @foreach($search['cities']['from'] as $from)
                                                            <option value="{{$from['id']}}" @if($from['id']==$search['from']) selected @endif>{{$from['name']}}</option>
                                                         @endforeach
                                                      @endif
                                                   </select>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="span3">
                                             <div class="control-group">
                                                <label class="control-label" for="to">ေရာက်ၡိသည့်ြမို့ ေရွးရန်</label>
                                                <div class="controls"  id='to'>
                                                   <select name="to" class="m-wrap span12 chosen">
                                                      @if($search['cities'])
                                                         @foreach($search['cities']['to'] as $to)
                                                            <option value="{{$to['id']}}" @if($to['id']==$search['to']) selected @endif>{{$to['name']}}</option>
                                                         @endforeach
                                                      @endif
                                                   </select>
                                                </div>
                                                <div class="controls"  id='to_all' style="display:none;">
                                                   <select name="to_all" class="m-wrap span12 chosen">
                                                      <option value="0">All</option>
                                                   </select>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="span2">
                                             <div class="control-group">
                                                <label class="control-label" for="startdate">အစေန့ေရွးရန် (မှ)</label>
                                                <div class="controls">
                                                   <input id="startdate" name="start_date" class="m-wrap span12 m-ctrl-medium date-picker" size="16" type="text" value="{{$search['start_date']}}">
                                                </div>
                                             </div>
                                          </div>
                                          <div class="span2">
                                             <div class="control-group">
                                                <label class="control-label" for="enddate">အဆုံးေန့ေရွးရန်(အထိ)</label>
                                                <div class="controls">
                                                   <input id="enddate" name="end_date" class="m-wrap span12 m-ctrl-medium  date-picker" size="16" type="text" value="{{$search['end_date']}}">
                                                </div>
                                             </div>
                                          </div>
                                          <div class="span2">
                                             <div class="control-group">
                                                <label class="control-label" for="departure_time">အချိန်ေရွးရန်</label>
                                                <div class="controls">
                                                   <select id="departure_time" name="departure_time" class="m-wrap span12 chosen">
                                                      @if($search['times'])
                                                            <option value="">All</option>
                                                         @foreach($search['times'] as $time)
                                                            <option value="{{$time['time']}}" @if($search['time']== $time['time']) selected @endif>{{$time['time']}}</option>
                                                         @endforeach
                                                      @endif
                                                   </select>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    
                                       <input type="hidden" value="{{$search['end_date'].' to '. $search['end_date']}}" id="report_date">
                                       <button type="submit" class="btn green pull-right">Search &nbsp; <i class="m-icon-swapright m-icon-white"></i></button>
                                    
                                    @endif
                                 </form>
                           </div>
                        </div>
                        
                        <div class="clear-fix">&nbsp;</div>
                        <div class="portlet-body">
                           <table class="table table-striped table-bordered table-advance table-hover" id="tblExport">
                              <thead>
                                 <tr>
                                    <th>ဝယ္ယူသည့္ေန႔</th>
                                    @if($search['trips']!=1)
                                    <th>အေရာင္း ကုိယ္စားလွယ္</th>
                                    @endif
                                    <th>ထြက္ခြာမည့္ေန ့ရက္</th>
                                    <th>ခရီးစဥ္</th>
                                    <th>ထြက္ခြာမည့္အခ်ိန္</th>
                                    <th>ကားအမ်ိဴးအစား</th>
                                    <th>ခုံအေရအတြက္</th>
                                    <th>အခမဲ႕ လက္မွတ္ </th>
                                    <th>ေစ်းႏုန္း</th>
                                    <th>စုုစုုေပါင္း</th>
                                    <th><a class="btn small green blue-stripe imagechange" id="" href="/triplist/{{$search['start_date'].','.$search['end_date']}}/daily?f={{$search['from']}}&t={{$search['to']}}&a={{$search['agent_id']}}">အေသးစိတ္(All)</a></th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if($response)
                                    <div id="jsonvalue" style="display:none;">{{json_encode($response)}}</div>
                                    <div id="dvjson"></div>
                                    @foreach($response as $result)
                                       <tr>
                                          <td>{{date('d/m/Y',strtotime($result['order_date']))}}</td>
                                          @if($search['trips']!=1)
                                          <td>{{$result['agent_name']}}</td>
                                          @endif
                                          <td>{{date('d/m/Y',strtotime($result['departure_date']))}}</td>
                                          <td>{{$result['from_to']}}</td>
                                          <td>{{$result['time']}}</td>
                                          <td>{{$result['class_name']}}</td>
                                          <td>{{$result['sold_seat']}}</td>
                                          <td>{{$result['free_ticket']}}</td>
                                          <td>{{$result['local_price']}}</td>
                                          <td>{{$result['total_amount']}}</td>
                                          <td>
                                             <a class="btn mini green-stripe imagechange" id="" href="/triplist/{{$result['order_date']}}/daily?bus_id={{$result['bus_id']}}&a={{$result['agent_id']}}">အေသးစိတ္ၾကည့္ရန္</a>
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
   </div>
<!-- END PAGE -->  
   <script type="text/javascript" src="../../../../assets/data-tables/jquery.dataTables.js"></script>
   <script type="text/javascript" src="../../../../assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
   <script type="text/javascript" src="../../../../assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
   <script type="text/javascript" src="../../../../js/jquery.battatech.excelexport.min.js"></script>
   <script type="text/javascript">
      $(function(){
         $('.page-container').addClass('sidebar-closed');
         $('.chosen').chosen();
         tripallcombo($('#from').val());           

         $('#from').change(function(){
             tripallcombo($(this).val());           
         })

         $("#btnExportExcel").click(function () {
            var report_date=$('#report_date').val();
            var filename="Daily Sale Report("+report_date+")";
            var dataobject = $('#jsonvalue').html();
            var dataobj =JSON.parse(dataobject);
            var uri =$("#dvjson").btechco_excelexport({
                containerid: "dvjson"
                , datatype: $datatype.Json
                , dataset: dataobj
                , columns: [
                    { headertext: "ထြက္ခြာမည့္ေန ့ရက္", datatype: "date", format: "xxxx,xx", datafield: "departure_date", ishidden: false }
                    , { headertext: "ခရီးစဥ္", datatype: "string", datafield: "from_to"}
                    , { headertext: "ထြက္ခြာမည့္အခ်ိန္", datatype: "string", datafield: "time", ishidden: false }
                    , { headertext: "ကားအမ်ိဴးအစား", datatype: "string", datafield: "class_name", ishidden: false }
                    , { headertext: "ခုံအေရအတြက္", datatype: "string",  datafield: "sold_seat", ishidden: false }
                    , { headertext: "အခမဲ႕ လက္မွတ္", datatype: "string",  datafield: "free_ticket", ishidden: false, }
                    , { headertext: "ေစ်းႏုန္း", datatype: "string",  datafield: "local_price", ishidden: false, }
                    , { headertext: "စုုစုုေပါင္း", datatype: "string",  datafield: "total_amount", ishidden: false }
                ]
               , returnUri: true
            });
            $(this).attr('download', filename+'.xls').attr('href', uri).attr('target', '_blank');
        });
      });
      
      function tripallcombo(from){
         if(from=='all'){
            $('#to').hide();
            $('#to_all').show();
         }else{
            $('#to').show();
            $('#to_all').hide();
         }
      }
   </script>
@stop
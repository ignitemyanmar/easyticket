@extends('../admin')
@section('content')
<!-- <link rel="stylesheet" href="../../../../assets/data-tables/DT_bootstrap.css" /> -->
<link rel="stylesheet" href="../../../../css/jquery.dataTables.v1.10.4.css" />
<link rel="stylesheet" type="text/css" href="../../../../assets/chosen-bootstrap/chosen/chosen.css" />
<link rel="stylesheet" type="text/css" href="../../../../assets/bootstrap-datepicker/css/datepicker.css" />
<style type="text/css">
   .footer-row th{background:#ddd !important;}
   .title-row th{background:rgba(228, 246, 245, 1) !important;}
   tr.group td,
   tr.group td:hover {
       background: #ddd !important;
   }
   #sample_editable_1_length select{width: 80px;}
   .text-right{text-align:right !important;}
   .loader{background: url('../../../img/loader.gif');}
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
                                          <div class="span3">
                                             <input type="hidden" value="{{$search['trips']}}" name="trips">
                                             <input type="hidden" value="{{$search['operator_id']}}" name="operator_id">
                                             <div class="control-group">
                                                <label class="control-label" for="from">Agent Group</label>
                                                <div class="controls">
                                                   <select name="agentgroup" class="m-wrap span12 chosen" id="agentgroup">
                                                      <option value="All">All</option>
                                                      @if($search['agentgroup'])
                                                         @foreach($search['agentgroup'] as $rows)
                                                            <option value="{{$rows['id']}}" @if($rows['id']==$search['agentgroup_id']) selected @endif>{{$rows['name']}}</option>
                                                         @endforeach
                                                      @endif
                                                   </select>
                                                </div>
                                                
                                             </div>
                                          </div>

                                          <div class="span3 responsive">
                                             <input type="hidden" value="{{$search['operator_id']}}" name="operator_id">
                                             <div class="control-group">
                                                <label class="control-label" for="from">ဂိတ်ခွဲများ</label>
                                                <div class="controls" id="agent_id">
                                                   <select name="agent_id" class="m-wrap span12 chosen">
                                                      <option value="All">All</option>
                                                      @if($search['agent'])
                                                         @foreach($search['agent'] as $row)
                                                            <option value="{{$row['id']}}" @if($row['id']==$search['agent_id']) selected @endif>{{$row['name']}}</option>
                                                         @endforeach
                                                      @endif
                                                   </select>
                                                </div>
                                                <!-- <span id="loading" class="loader">&nbsp;</span> -->

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
                                                <div class="controls">
                                                   <div id='to_all' style="display:none;">
                                                      <select name="to_all" class="m-wrap span12 chosen">
                                                         <option value="0">All</option>
                                                      </select>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="clear-fix">&nbsp;</div>
                                       
                                       <div class="row-fluid">
                                          <div class="span3">
                                             <div class="control-group">
                                                <label class="control-label" for="startdate">ထွက်ခွာမည့် အစေန့ေရွးရန်</label>
                                                <div class="controls">
                                                   <input id="startdate" name="start_date" class="m-wrap span12 m-ctrl-medium date-picker" size="16" type="text" value="{{$search['start_date']}}">
                                                </div>
                                             </div>
                                          </div>
                                          <div class="span3">
                                             <div class="control-group">
                                                <label class="control-label" for="enddate">ထွက်ခွာမည့် အဆုံးေန့ေရွးရန်</label>
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

                                                <input type="hidden" value="{{$search['start_date'].' - '. $search['end_date']}} " id="report_date">
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
                                                <label class="control-label" for="startdate">ထွက်ခွာမည့် အစေန့ေရွးရန်</label>
                                                <div class="controls">
                                                   <input id="startdate" name="start_date" class="m-wrap span12 m-ctrl-medium date-picker" size="16" type="text" value="{{$search['start_date']}}">
                                                </div>
                                             </div>
                                          </div>
                                          <div class="span2">
                                             <div class="control-group">
                                                <label class="control-label" for="enddate">ထွက်ခွာမည့် အဆုံးေန့ေရွးရန်</label>
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
                           <table class="table table-striped table-bordered table-advance table-hover" id="sample_editable_1">
                              <thead>
                                 <tr>
                                    <th>ဝယ္ယူသည့္ေန႔</th>
                                    @if($search['trips']!=1)
                                    <th>အေရာင္း ကုိယ္စားလွယ္</th>
                                    @endif
                                    <th>ခရီးစဥ္</th>
                                    <th>ထြက္ခြာမည့္ေန ့ရက္ / အခ်ိန္</th>
                                    <!-- <th>ထြက္ခြာမည့္အခ်ိန္</th> -->
                                    <th>ကားအမ်ိဴးအစား</th>
                                    <th>ခုံအေရအတြက္</th>
                                    <th>အခမဲ႕ လက္မွတ္ </th>
                                    <th>ေစ်းႏုန္း</th>
                                    <th>စုုစုုေပါင္း</th>
                                    <th style="width:0;"><a class="btn small green blue-stripe imagechange" id="" href="/triplist/{{$search['start_date'].','.$search['end_date']}}/daily?f={{$search['from']}}&t={{$search['to']}}&agentgroup={{$search['agentgroup_id']}}&a={{$search['agent_id']}}&agentrp={{$search['agent_rp']}}&time={{$search['time']}}">အေသးစိတ္(All)</a></th>
                                 </tr>
                              </thead>
                                 @if($response)
                                    <tbody>
                                       @if($search['agent_rp'])
                                          <?php $columns=10; ?>
                                       @else
                                          <?php $columns=9;?>

                                       @endif
                                       <!-- <div id="jsonvalue" style="display:none;">{{json_encode($response)}}</div> -->
                                       <div id="dvjson"></div>
                                       <?php $G_total_ticket=0; $G_total_amount=0; $G_free_ticket=0; ?>
                                       @foreach($response as $key=>$rows)
                                       <!-- <tr class="title-row">
                                          <th align="left">{{$key}}</th>
                                          @if($search['agent_rp'])
                                             <th>&nbsp;</th>
                                          @endif
                                          <th>&nbsp;</th>
                                          <th>&nbsp;</th>
                                          <th>&nbsp;</th>
                                          <th>&nbsp;</th>
                                          <th>&nbsp;</th>
                                          <th>&nbsp;</th>
                                          <th>&nbsp;</th>
                                          <th>&nbsp;</th>
                                       </tr> -->
                                          <?php $total_ticket=0; $total_amount=0; $free_ticket=0; ?>
                                          @if(count($rows)>0)
                                             @foreach($rows as $result)
                                                <tr>
                                                   <td>{{date('d/m/Y',strtotime($result['order_date']))}}</td>
                                                   @if($search['trips']!=1)
                                                      <td><div class="wordwrap">{{$result['agent_name']}}</div></td>
                                                   @endif
                                                   <td>{{$result['from_to']}}</td>
                                                   <td>{{date('d/m/Y',strtotime($result['departure_date']))}} ({{$result['time']}})</td>
                                                   <!-- <td>{{$result['time']}}</td> -->
                                                   <td>{{$result['class_name']}}</td>
                                                   <td>{{$result['sold_seat']}}</td>
                                                   <td>{{$result['free_ticket']}}</td>
                                                   <td>{{$result['local_price']}}</td>
                                                   <td>{{$result['total_amount']}}</td>
                                                   <td>
                                                      <a class="btn mini green-stripe imagechange" id="" href="/triplist/{{$result['order_date']}}/daily?bus_id={{$result['bus_id']}}&a={{$result['agent_id']}}&agentrp={{$search['agent_rp']}}">အေသးစိတ္ၾကည့္ရန္</a>
                                                   </td>
                                                </tr>
                                                <?php 
                                                   $total_ticket +=$result['sold_seat']; 
                                                   $total_amount+=$result['total_amount'];
                                                   $free_ticket+=$result['free_ticket'];
                                                ?>
                                             @endforeach
                                          @endif
                                          <!-- <tr>
                                             @if($search['agent_rp'])
                                                <th>&nbsp;</th>
                                             @endif
                                             <th>&nbsp;</th>
                                             <th>&nbsp;</th>
                                             <th>&nbsp;</th>
                                             <th>Sub Quantity</th>
                                             <th>{{$total_ticket}}</th>
                                             <th>Sub Free Ticket : {{$free_ticket}}</th>
                                             <th>Sub Total</th>
                                             <th>{{$total_amount}}</th>
                                             <th>&nbsp;</th>
                                          </tr> -->
                                          <?php 
                                             $G_total_ticket +=$total_ticket; 
                                             $G_total_amount +=$total_amount; 
                                             $G_free_ticket +=$free_ticket; 
                                          ?>

                                       @endforeach
                                       
                                    </tbody>
                                    <tfoot>
                                       <tr class="footer-row">
                                          @if($search['agent_rp'])
                                             <th></th>
                                          @endif
                                          <!-- <th></th> -->
                                          <th></th>
                                          <th colspan="2" class="text-right">Grand Quantity</th>
                                          <th>: {{$G_total_ticket}}</th>
                                          <th colspan="2">Grand Free Ticket : {{$G_free_ticket}}</th>
                                          <th colspan="2" class="text-right">Grand Total</th>
                                          <th>: {{$G_total_amount}}</th>
                                          <!-- <th>&nbsp;</th> -->
                                       </tr>
                                    </tfoot>
                                 @endif
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            <!-- END PAGE CONTENT-->         
         </div>
   </div>
<!-- END PAGE -->  
   <!-- 
   <script type="text/javascript" src="../../../../assets/data-tables/jquery.dataTables.js"></script>
    -->

   <script type="text/javascript" src="../../../../assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
   <script type="text/javascript" src="../../../../assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
   <script type="text/javascript" src="../../../../js/jquery.battatech.excelexport.min.js"></script>
   <!-- 
   <script type="text/javascript" src="../../../../assets/data-tables/DT_bootstrap.js"></script>
    -->
   <script type="text/javascript" src="../../../../js/jquery.dataTables.v1.10.4.min.js"></script>
   <script type="text/javascript">
      $(document).ready(function() {
          var table = $('#sample_editable_1').DataTable({
              "columnDefs": [
                  // { "visible": false, "targets": 2 }
              ],
              "order": [[ 1, 'asc' ]],
              "displayLength": 25,
              "pagingType": "full_numbers",
              "drawCallback": function ( settings ) {
                  var api = this.api();
                  var rows = api.rows( {page:'current'} ).nodes();
                  var last=null;
       
                  api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                      if ( last !== group ) {
                          $(rows).eq( i ).before(
                              '<tr class="group"><td colspan="10">'+group+'</td></tr>'
                          );
       
                          last = group;
                      }
                  } );
              }
          } );
          // Order by the grouping
          $('#sample_editable_1 tbody').on( 'click', 'tr.group', function () {
              var currentOrder = table.order()[0];
              if ( currentOrder[0] === 1 && currentOrder[1] === 'asc' ) {
                  table.order( [ 1, 'desc' ] ).draw();
              }
              else {
                  table.order( [ 1, 'asc' ] ).draw();
              }
          } );
      } );
   </script>
   <script type="text/javascript">
      $(function(){
         // App.setPage("table_editable");
         $('.page-container').addClass('sidebar-closed');
         $('.chosen').chosen();
         tripallcombo($('#from').val());           

         $('#from').change(function(){
             tripallcombo($(this).val());           
         })
         $("#btnExportExcel").click(function () {
               var report_date=$('#report_date').val();
               var filename=report_date+" Sale List";
               var uri =$("#sample_editable_1").btechco_excelexport({
                      containerid: "sample_editable_1"
                     , datatype: $datatype.Table
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
         $('chosen').chosen();
      }

      $('#agentgroup').change(function(){
         var agentgroup_id=$(this).val();
         if(agentgroup_id=="All")
         {
            agentgroup_id=0;
         }
         var result='<select name="agent_id" class="m-wrap span12 chosen">';
               result+='<option value="All">All</option>';
         $('#agent_id').html('');
         $('#agent_id').addClass('loader');
         $.get('/agentbranches/'+agentgroup_id,function(data){
            for(var i=0; i<data.length; i++){
               result +='<option value="'+data[i].id+'">'+data[i].name+'</option>';
            }
            result +='</select>';
            $('#agent_id').removeClass('loader');
            $('#agent_id').html(result);
            $('.chosen').chosen();
         });
      });
   </script>
@stop
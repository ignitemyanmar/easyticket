@extends('../admin')
@section('content')
<link rel="stylesheet" href="../../../../css/jquery.dataTables.v1.10.4.css" />
<link rel="stylesheet" type="text/css" href="../../../../assets/chosen-bootstrap/chosen/chosen.css" />
<link rel="stylesheet" type="text/css" href="../../../../assets/bootstrap-datepicker/css/datepicker.css" />
<style type="text/css">
   .footer-row th{background:#ddd !important;}
   .title-row th{background:rgba(228, 246, 245, 1) !important;}
   tr.group th,
   tr.group th:hover {
       background: #ddd !important;
   }
   #sample_editable_1_length select{width: 80px;}
   .text-right{text-align:right !important;}
   .loader{background: url('../../../img/loader.gif');}
   td .dropdown-menu{right: 0;left: auto;}
   .modal-body {overflow-y: visible;}
   .chzn-container-single .chzn-search input, .chzn-container{min-width: 90% !important;}
   .chzn-container-single .chzn-drop{width: 100% !important;}
</style>
<!-- BEGIN PAGE -->  
   <div class="page-content">
      <!-- BEGIN AGENT CHANGE POPUP FORM -->  
         <div id="portlet-changeagent" class="modal hide">
            <div class="modal-header">
               <button data-dismiss="modal" class="close" type="button"></button>
               <h3>အေရာင္းကုိယ္စားလွယ္ ေျပာင္းရန္</h3>
            </div>
            <div class="modal-body">
               <div id="trip_label"></div>
               <form action="/order/changeagent" method="post">
                  <div class="control-group">
                     <label class="control-label">Choose Agent</label>
                     <div class="controls">
                        <select name="agent_id" class="chosen">
                           @if($agentlist)
                              @foreach($agentlist as $row)
                                 <option value="{{$row['id']}}">{{$row['name']}}</option>
                              @endforeach
                           @endif
                        </select>
                        <input type="hidden" id="order_id" name="order_id">
                        <div class="btn-group">
                           <div class="clear-fix">&nbsp;</div>
                           <a href="/items/promotion">
                           <button type="submit" id="sample_editable_1_new" class="btn green">
                              Change Agent<i class="m-icon-swapright m-icon-white"></i>
                           </button>
                           </a>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      <!-- END AGENT CHANGE POPUP FORM -->  

      <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
               <div class="row-fluid">
                  <div class="span12">
                     <h3 class="page-title">
                        &nbsp;{{$search['agent_name']}} အေရာင္းစာရင္းမ်ား
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}">ပင္မစာမ်က္ႏွာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">
                           &nbsp;{{$search['agent_name']}} အေရာင္းစာရင္းမ်ား
                           </a>
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
                              &nbsp;{{$search['agent_name']}} အေရာင္းစာရင္းမ်ား
                           </h4>
                           <div class="actions">
                           </div>
                        </div>

                        <div class="row-fluid search-default">
                           <div class="span12">
                                 <input type="hidden" value="{{$search['agent_rp']}}" id="hdagent_rp">
                                 <form action="/agent-salelist/{{$search['agent_id']}}" method="get" class="horizontal-form">
                                       <div class="clear-fix">&nbsp;</div>
                                       <div class="row-fluid">
                                          <div class="span3">
                                             <div class="control-group">
                                                <label class="control-label" for="startdate">ထွက်ခွာမည့် အစေန့ေရွးရန်</label>
                                                <div class="controls">
                                                   <input id="startdate" name="start_date" class="m-wrap span12 m-ctrl-medium date-picker" size="16" type="text" data-date-format="dd/mm/yyyy" value="{{date('d/m/Y', strtotime($search['start_date']))}}">
                                                </div>
                                             </div>
                                          </div>
                                          <div class="span3">
                                             <div class="control-group">
                                                <label class="control-label" for="enddate">ထွက်ခွာမည့် အဆုံးေန့ေရွးရန်</label>
                                                <div class="controls">
                                                   <input id="enddate" name="end_date" class="m-wrap span12 m-ctrl-medium  date-picker" size="16" type="text" data-date-format="dd/mm/yyyy" value="{{date('d/m/Y', strtotime($search['end_date']))}}">
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
                                 </form>
                           </div>
                           
                        </div>
                        
                        <!-- <div class="clear-fix">&nbsp;</div> -->
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
                                    <th>ကားအမ်ိဴးအစား</th>
                                    <th>ခုံအေရ အတြက္</th>
                                    <th>အခမဲ႕ လက္မွတ္ </th>
                                    <th>ေစ်းႏုန္း</th>
                                    <th>စုုစုုေပါင္း</th>
                                    <th>% ႏုတ္ျပီး စုုစုုေပါင္း</th>
                                    <th><a class="btn small green blue-stripe imagechange" id="" href="/triplist/{{$search['start_date'].','.$search['end_date']}}/daily?f={{$search['from']}}&t={{$search['to']}}&agentgroup={{$search['agentgroup_id']}}&a={{$search['agent_id']}}&agentrp={{$search['agent_rp']}}&time={{$search['time']}}">အေသးစိတ္(All)</a></th>
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
                                       <?php $G_total_ticket=0;$G_prc_total_amount=0; $G_total_amount=0; $G_free_ticket=0; ?>
                                       @foreach($response as $key=>$rows)
                                          <?php $total_ticket=0; $total_amount=0;$percent_total=0; $free_ticket=0; ?>
                                          @if(count($rows)>0)
                                             @foreach($rows as $result)
                                                <tr>
                                                   <td>{{date('d/m/Y',strtotime($result['order_date']))}}</td>
                                                   @if($search['trips']!=1)
                                                      <td><div class="">{{$result['agent_name']}}</div></td>
                                                   @endif
                                                   <?php $departuredate=date('d/m/Y',strtotime($result['departure_date'])); ?>
                                                   <td>{{$result['from_to']}}</td>
                                                   <td>{{$departuredate}} ({{$result['time']}})</td>
                                                   <!-- <td>{{$result['time']}}</td> -->
                                                   <td>{{$result['class_name']}}</td>
                                                   <td>{{$result['sold_seat']}}</td>
                                                   <td>{{$result['free_ticket']}}</td>
                                                   <td>{{$result['local_price']}}</td>
                                                   <td>{{$result['total_amount']}}</td>
                                                   <td>{{$result['percent_total']}}</td>
                                                   <td>
                                                      <div class="btn-group pull-right">
                                                         <a class="btn blue" href="#" data-toggle="dropdown">
                                                            <i class="icon-cog"></i> Settings
                                                            <i class="icon-angle-down"></i>
                                                         </a>
                                                         <ul class="dropdown-menu"> 
                                                            <li>
                                                              <a href="/triplist/{{$result['order_date']}}/daily?bus_id={{$result['bus_id']}}&a={{$result['agent_id']}}&agentrp={{$search['agent_rp']}}"><i class="icon-list"></i>အေသးစိတ္ၾကည့္ရန္</a>
                                                            </li>
                                                            <li>
                                                               <input type="hidden" value="<h4>{{$result['from_to'].' ('.$result['class_name'].') </h4>'.$departuredate. ' ('.$result['time'].')'}} <br><br>" class="trip_info">
                                                              <a data-orderid={{$result['order_id']}} href="#portlet-changeagent" data-toggle="modal" class="config"><i class="icon-list"></i>အေရာင္းကုိယ္စားလွယ္ ေျပာင္းရန္</a>
                                                            </li>
                                                            <li class="divider"></li>
                                                         </ul>
                                                      </div>
                                                   </td>
                                                </tr>
                                                <?php 
                                                   $total_ticket +=$result['sold_seat']; 
                                                   $total_amount+=$result['total_amount'];
                                                   $percent_total+=$result['percent_total'];
                                                   $free_ticket+=$result['free_ticket'];
                                                ?>
                                             @endforeach
                                          @endif
                                         
                                          <?php 
                                             $G_total_ticket +=$total_ticket; 
                                             $G_total_amount +=$total_amount; 
                                             $G_prc_total_amount +=$percent_total; 
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
                                          <th colspan="1" class="text-right">Total Tickets:{{$G_total_ticket}}</th>
                                          <!-- <th></th> -->
                                          <th colspan="2">Total Free Tickets : {{$G_free_ticket}}</th>
                                          <th colspan="3" class="text-right">Grand Total : {{$G_total_amount}}</th>
                                          <th colspan="3" class="text-right">% ႏုတ္ျပီး စုစုေပါင္း = {{$G_prc_total_amount}}</th>
                                          <th></th>
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
         var hdagent_rp=$('#hdagent_rp').val();
         // var addcolumn="";
         // if(hdagent_rp==1){
            addcolumn="<th>&nbsp;</th>";
         // }
          var table = $('#sample_editable_1').DataTable({
              "columnDefs": [
                  // { "visible": false, "targets": 2 }
              ],
              "order": [[ 1, 'asc' ]],
              "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
              "pagingType": "full_numbers",
              "drawCallback": function ( settings ) {
                  var api = this.api();
                  var rows = api.rows( {page:'current'} ).nodes();
                  var last=null;
       
                  api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                      if ( last !== group ) {
                          $(rows).eq( i ).before(
                              '<tr class="group"><th colspan="5">'+group+'</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th>'+addcolumn+'</tr>'
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
      $('.config').click(function(){
         var order_id=$(this).data('orderid');
         var trip=   $(this).prev('.trip_info').val();
         $('#order_id').val(order_id);
         $('#trip_label').html(trip);
      });

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
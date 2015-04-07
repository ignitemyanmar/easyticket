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
</style>
<!-- BEGIN PAGE -->  
   <div class="page-content">
      <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
               <div class="row-fluid">
                  <div class="span12">
                     <h3 class="page-title">
                        Staff အလုိက္ အေရာင္းစားရင္းမ်ား
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?access_token={{Auth::user()->access_token}}&operator_id={{Session::get('operator_id')}}">ပင္မစာမ်က္ႏွာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">
                              Staff အလုိက္ အေရာင္းစားရင္းမ်ား
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
                              Staff အလုိက္ အေရာင္းစားရင္းမ်ား
                           </h4>
                           <div class="actions">
                               <!-- <div class="span2"> -->
                                 <!-- <div class="control-group"> -->
                                    <!-- <label class="control-label" for="from">&nbsp;</label> -->
                                    <div class="btn-group">
                                       <button class="btn green dropdown-toggle" data-toggle="dropdown"><i class="icon-print"></i> Print <i class="icon-angle-down"></i>
                                       </button>
                                       <ul class="dropdown-menu">
                                          <li><a href="#" class="print">Print</a></li>
                                          <li><a href="#" id="btnExportExcel">Export to Excel</a></li>
                                       </ul>
                                    </div>
                                 <!-- </div> -->
                              <!-- </div> -->
                           </div>
                        </div>

                        <div class="row-fluid search-default">
                          
                           <div class="span12">
                                 <form action="/staff/salereport" method="get" class="horizontal-form">
                                    <input type="hidden" name="access_token" class="access_token" value="{{Auth::user()->access_token}}">
                                       <div class="row-fluid">
                                          <div class="span2">
                                             <div class="control-group">
                                                <label class="control-label" for="from">ေရာင်းသူ (Staff)</label>
                                                <div class="controls">
                                                   <select name="seller_id" class="m-wrap span12 chosen">
                                                      <option value="all">All</option>
                                                      @if($search['users'])
                                                         @foreach($search['users'] as $user)
                                                            <option value="{{$user['id']}}" @if($user['id']==$search['seller_id']) selected @endif>{{$user['name']}}</option>
                                                         @endforeach
                                                      @endif
                                                   </select>
                                                </div>
                                             </div>
                                          </div>

                                          <div class="span2">
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
                                          <div class="span2">
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
                                 </form>
                           </div>
                        </div>
                        
                        <div class="clear-fix">&nbsp;</div>
                        <div class="portlet-body">
                           <table class="table table-striped table-bordered table-advance table-hover" id="sample_editable_1">
                              <thead>
                                 <tr>
                                    <th>ဝယ္ယူသည့္ေန႔</th>
                                    <th>ေရာင္းသူ</th>
                                    <th>ခရီးစဥ္</th>
                                    <th>ထြက္ခြာမည့္ေန ့ရက္ / အခ်ိန္</th>
                                    <th>ကားအမ်ိဴးအစား</th>
                                    <th>ခုံအေရ အတြက္</th>
                                    <th>အခမဲ႕ လက္မွတ္ </th>
                                    <th>Discount </th>
                                    <th>ေစ်းႏုန္း</th>
                                    <th>စုုစုုေပါင္း</th>
                                    <th>% ႏုတ္ျပီး စုုစုုေပါင္း</th>
                                    <th style="width:0;">
                                       <a class="btn small green blue-stripe imagechange" id="" href="/staff/salebytrip/{{$search['start_date'].','.$search['end_date']}}?access_token={{Auth::user()->access_token}}&seller_id={{$search['seller_id']}}&fr={{$search['from']}}&to={{$search['to']}}&ti={{$search['time']}}">အေသးစိတ္(All)</a>
                                    </th>
                                 </tr>
                              </thead>
                                 @if($response)
                                    <tbody>
                                       <?php 
                                          $G_total_ticket=0;
                                          $G_prc_total_amount=0; 
                                          $G_total_amount=0; 
                                          $G_free_ticket=0; 
                                       ?>
                                       @foreach($response as $key=>$result)
                                                <tr>
                                                   <td>{{date('d/m/Y',strtotime($result['orderdate']))}}</td>
                                                   <td>{{$result['seller_name']}}</td>
                                                   <td>{{$result['trip']}}</td>
                                                   <td>{{date('d/m/Y',strtotime($result['departure_date']))}} ({{$result['time']}})</td>
                                                   <td>{{$result['class_name']}}</td>
                                                   <td>{{$result['sold_seat']}}</td>
                                                   <td>{{$result['free_ticket']}}</td>
                                                   <td>{{$result['discount']}}</td>
                                                   <td>{{$result['price']}}</td>
                                                   <td>{{number_format($result['total_amount'], 0, '.' , ',')}}</td>
                                                   <td>{{number_format($result['total_amount'] - $result['commission'] ,0 , '.', ',') }}</td>
                                                   <td>
                                                      <a class="btn mini green-stripe imagechange" id="" href="/staff/salebytrip/{{$result['departure_date']}}?access_token={{Auth::user()->access_token}}&seller_id={{$result['user_id']}}&trip_id={{$result['trip_id']}}">အေသးစိတ္ၾကည့္ရန္</a>
                                                   </td>
                                                </tr>
                                          <?php 
                                             $G_total_ticket +=$result['sold_seat']; 
                                             $G_total_amount +=$result['total_amount']; 
                                             $G_prc_total_amount +=$result['total_amount']- $result['commission']; 
                                             $G_free_ticket +=$result['free_ticket']; 
                                          ?>

                                       @endforeach
                                       
                                    </tbody>
                                    <tfoot>
                                       <tr class="footer-row">
                                          <th colspan="3">Grand Quantity : {{$G_total_ticket}}</th>
                                          <th colspan="3">Grand Free Ticket : {{$G_free_ticket}}</th>
                                          <th colspan="3">Grand Total : {{$G_total_amount}}</th>
                                          <th colspan="3">% ႏုတ္ျပီး စုစုေပါင္း = {{$G_prc_total_amount}}</th>
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
                  { "visible": false, "targets": 1 }
              ],
              "order": [[ 1, 'asc' ]],
              "autoWidth": false,
              "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
              "pagingType": "full_numbers",
              "drawCallback": function ( settings ) {
                  var api = this.api();
                  var rows = api.rows( {page:'current'} ).nodes();
                  var last=null;
       
                  api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                      if ( last !== group ) {
                          $(rows).eq( i ).before(
                              '<tr class="group"><th colspan="12">'+group+'</th></tr>'
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
         var agentgroup_id=$('#agentgroup').val();
         loadagentbranches(agentgroup_id);

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

      function loadagentbranches(agentgroup_id){
         var _token = $('.access_token').val();
         var result='<select name="agent_id" class="m-wrap span12 chosen">';
               result+='<option value="All">All</option>';
         $('#agent_id').html('');
         $('#agent_id').addClass('loader');
         $.get('/agentbranches/'+agentgroup_id+'?access_token='+_token,function(data){

            for(var i=0; i<data.length; i++){
               result +='<option value="'+data[i].id+'">'+data[i].name+'</option>';
            }
            result +='</select>';
            $('#agent_id').removeClass('loader');
            $('#agent_id').html(result);
            $('.chosen').chosen();

         });
      }
      $('#agentgroup').change(function(){
         var agentgroup_id=$(this).val();
         if(agentgroup_id=="All")
         {
            agentgroup_id=0;
         }
         loadagentbranches(agentgroup_id);
      });
   </script>
@stop
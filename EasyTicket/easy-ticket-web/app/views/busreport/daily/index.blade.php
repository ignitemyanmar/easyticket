@extends('../admin')
@section('content')
<!-- {{HTML::style('../../css/jquery-ui.css')}} -->
<!-- BEGIN PAGE --> 
<link rel="stylesheet" type="text/css" href="../../../../assets/bootstrap-datepicker/css/datepicker.css" />
<link rel="stylesheet" href="../../../../css/jquery.dataTables.v1.10.4.css" />

<style type="text/css">
   #tblExport_length select{width: 80px;}
   tr.group td,
   tr.group td:hover {
       background: #ddd !important;
   }
   tfoot th{background: #ddd !important;}

</style> 


   <div class="page-content">
      <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
               <div class="row-fluid">
                  <div class="span12">
                        
                     <h3 class="page-title">
                        &nbsp;ေန႔စဥ္ အေရာင္းစာရင္း
                        <br><small style="margin-left:.8rem; color:#333;">{{date('d/m/Y',strtotime(Session::get('search_daily_date')))}} အေရာင္းစာရင္း</small>
                        <input type="hidden" value="{{date('d/m/Y',strtotime(Session::get('search_daily_date')))}}" id="report_date">
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
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <?php $dailyforbustotal=0; $dailyforadvancetotal=0;?>
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-th-list"></i>ေန႔စဥ္ အေရာင္းစာရင္း</h4>
                           <div class="actions">
                           </div>
                        </div>

                        <div class="row-fluid search-default">
                           <div class="span8">
                                 <div class="btn-group" style="margin:11px;">
                                    <button class="btn green dropdown-toggle" data-toggle="dropdown">Tools <i class="icon-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                       <li><a href="#" class="print">Print</a></li>
                                       <li><a href="#" id="btnExportExcel">Export to Excel</a></li>
                                    </ul>
                                 </div>
                           </div>
                           <div class="span4">
                              <form class="form-search" action="/report/dailycarandadvancesale/search">
                                 <div class="chat-form">
                                    <div class="input-cont">   
                                       <input placeholder="Choose Date..." class="m-wrap" type="text" id="startdate" name="date" data-date-format="dd/mm/yyyy" value="{{date('d/m/Y', strtotime(Session::get('search_daily_date')))}}">
                                    </div>
                                    <button type="submit" class="btn green">Search &nbsp; <i class="m-icon-swapright m-icon-white"></i></button>
                                 </div>
                              </form>
                           </div>
                        </div>
                        <div class="clear-fix">&nbsp;</div>
                        <div class="portlet-body" id="contents">
                           <table class="table table-striped table-bordered table-advance table-hover" id="tblExport">
                              <thead>
                                 <tr>
                                    <th>ထြက္ခြာ မည့္ေန ့ရက္</th>
                                    <th>ခရီးစဥ္</th>
                                    <th>ထြက္ခြာမည့္ အခ်ိန္</th>
                                    <th>ကားအမ်ိဴးအစား</th>
                                    <th>ခုံအေရ အတြက္</th>
                                    <th>အခမဲ႕ လက္မွတ္ </th>
                                    <th>ေစ်းႏုန္း</th>
                                    <th>စုုစုုေပါင္း</th>
                                    <th><a class="btn small green blue-stripe imagechange" id="" href="/report/dailycarandadvancesale/detail?date={{date('Y-m-d', strtotime(Session::get('search_daily_date')))}}">အေသးစိတ္(All)</a></th>
                                 </tr>
                              </thead>
                                 @if($dailyforbus)
                                    <tbody>
                                       <div id="jsonvalue" style="display:none;">{{json_encode($dailyforbus)}}</div>
                                       <div id="dvjson"></div>
                                       @foreach($dailyforbus as $result)
                                          <tr>
                                             <td>{{date('d/m/Y',strtotime($result['departure_date']))}}</td>
                                             <td>{{$result['from_to']}}</td>
                                             <td>{{$result['time']}}</td>
                                             <td>{{$result['class_name']}}</td>
                                             <td>{{$result['sold_seat']}}</td>
                                             <td>{{$result['free_ticket']}}</td>
                                             <td>{{$result['local_price']}}</td>
                                             <td>{{$result['total_amount']}}</td>
                                             <td>
                                                <a class="btn mini green-stripe imagechange" id="" href="/report/dailycarandadvancesale/detail?bus_id={{$result['bus_id']}}&date={{date('Y-m-d', strtotime(Session::get('search_daily_date')))}}">အေသးစိတ္ၾကည့္ရန္</a>
                                             </td>
                                          </tr>
                                          <?php $dailyforbustotal +=$result['total_amount']; ?>
                                       @endforeach

                                          
                                    </tbody>
                                    <tfoot>
                                       <tr>
                                          <th colspan="4">&nbsp;</th>
                                          <th colspan="4" class="" align="right">ယေန႕ေရာင္းရေငြစုစုေပါင္း</th>
                                          <th>: {{$dailyforbustotal}} KS</th>
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
      <!-- END PAGE CONTAINER-->
   </div>
<!-- END PAGE -->  
   <!-- 
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
    -->
   <!-- {{HTML::script('../../src/jquery-ui.js')}} -->
   <script type="text/javascript" src="../../../js/jquery.battatech.excelexport.min.js"></script>
   <script type="text/javascript" src="../../../../assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
   <script type="text/javascript" src="../../../../js/jquery.dataTables.v1.10.4.min.js"></script>
   <script type="text/javascript">
      $(document).ready(function() {
          var table = $('#tblExport').DataTable({
              "columnDefs": [
                  // { "visible": false, "targets": 2 }
              ],
              "order": [[ 1, 'asc' ]],
              "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
              "pagingType": "full_numbers",
              "drawCallback": function ( settings ) {
                  var api = this.api();
                  var rows = api.rows( {page:'current'} ).nodes();
                  var last=null;
       
                  api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                      if ( last !== group ) {
                          $(rows).eq( i ).before(
                              '<tr class="group"><td colspan="9">'+group+'</td></tr>'
                          );
       
                          last = group;
                      }
                  } );
              }
          } );
          // Order by the grouping
          $('#tblExport tbody').on( 'click', 'tr.group', function () {
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

         $('.print').click(function() {
            //Get the HTML of div
            var divElements = document.getElementById('contents').innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML = 
              "<html><head><title>Report</title></head><body>" + 
              divElements + "</body>";
            //Print Page
            window.print();
            //Restore orignal HTML
            document.body.innerHTML = oldPage;
            return false;
         });

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
                    , { headertext: "ေစ်းႏုန္း", datatype: "string",  datafield: "local_price", ishidden: false, }
                    , { headertext: "စုုစုုေပါင္း", datatype: "string",  datafield: "total_amount", ishidden: false }
                ]
               , returnUri: true
            });
            $(this).attr('download', filename+'.xls').attr('href', uri).attr('target', '_blank');
        });

      });
   </script>
 
@stop
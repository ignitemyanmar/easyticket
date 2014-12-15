@extends('../admin')
@section('content')
<link rel="stylesheet" href="../../../../css/jquery.dataTables.v1.10.4.css" />
<style type="text/css">
   thead tr,thead tr td{border:none !important; color: #222;font-weight: bold; border-right: none !important;border-right-color:#fff; }
   h4,th,td,h3{font-family: "Zawgyi-One" !important;}
   
   @media print{
      @font-face {
        font-family: 'Zawgyi-One';
        src: url('fonts/zawgyi-one/ZAWGYIONE.eot');
        src: local('Zawgyi-One'), url('fonts/zawgyi-one/ZAWGYIONE.woff') format('woff'), url('fonts/zawgyi-one/ZAWGYIONE.ttf') format('truetype'), url('fonts/zawgyi-one/ZAWGYIONE.svg') format('svg');
        font-weight: normal;
        font-style: normal;
      }
      body,#printable { display: block; font-family: "Zawgyi-One" !important; }
      h3{text-align: center !important; }
      h4,th,td,h3{font-family: "Zawgyi-One" !important;}
      .span2{ width: 16.6%;position: relative;float: left;}
      .span4{ width: 33.3%;position: relative;float: left;}
   }
   #printable { display: none; }
   table{border-top: 1px solid #ddd;}
   tr.group th,
   tr.group th:hover {
       background: #ddd !important;
   }
   tbody td, thead th{border-right: 1px solid #ddd;}
   .text-right{text-align: right !important;}
   
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
                     <?php $pagetitle=""; ?>
                     @if($search['agent_rp']==1)
                        <h3 class="page-title">
                           &nbsp;အေရာင္းကုိယ္စားလွယ္ႏွင့္ အေရာင္းစာရင္းမ်ား
                        </h3>
                        <?php $pagetitle="အေရာင္းကုိယ္စားလွယ္ႏွင့္ အေရာင္းစာရင္းမ်ား"; ?>
                     @else
                        <h3 class="page-title">
                           &nbsp;ခရီးစဥ္အလုိက္ အေရာင္းစာရင္းမ်ား အေသးစိတ္
                        </h3>
                        <?php $pagetitle="ခရီးစဥ္အလုိက္ အေရာင္းစာရင္းမ်ား"; ?>

                     @endif

                     @if($bus_id)
                        @if($response)
                           @foreach($response as $key=>$value)
                              <div id="filename" style="display:none;">{{$key}} {{date('d/m/Y',strtotime($value[0]['departure_date']))}} ({{$value[0]['time']}}) </div>
                           @endforeach
                        @endif
                     @else
                        <div id="filename" style="display:none;">{{$orderdate}} Sales</div>
                     @endif
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}">ပင္မစာမ်က္ႏွာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="{{$search['back_url']}}">{{$pagetitle}}</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">&nbsp; {{$pagetitle}} အေသးစိတ္</a>
                        </li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <div>
                        <div class="portlet-body">
                           <div class="row-fluid">
                              <div class="span7">
                                 &nbsp;
                              </div>
                              <div class="span5">
                                 <div class="clearfix">
                                    <div class="btn-group pull-right">
                                       <button class="btn green dropdown-toggle" data-toggle="dropdown">Print <i class="icon-angle-down"></i>
                                       </button>
                                       <ul class="dropdown-menu">
                                          <li>
                                             <!-- <a class="print-preview">Print this page</a> -->
                                             <a href="#" class="print">Print</a></li>
                                          <!-- <li><a href="#">Save as PDF</a></li> -->
                                          <li><a href="#" id="btnExportExcel">Export to Excel</a></li>
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div id="contents">
                              <div id="printable">
                                 <h3>Elite Express Public Company Limited</h3>
                                 <h3>Sale Details</h3>
                              </div>
                              <table class="table table-striped table-advance table-hover" id="tblExport">
                                 <thead>
                                    <tr>
                                       <td  colspan="13">
                                          <h3 align="center">Elite Express Public Company Limited</h3>
                                          <h4 align="center">{{$pagetitle}} အေသးစိတ္</h4>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td colspan="13"><b>Trips : 
                                          @if(!$bus_id)
                                            @if($search['from']) 
                                             [ {{$search['first_trip']}} ] 
                                            @else 
                                              All Tirps
                                            @endif
                                          @else 
                                             [ {{$search['first_trip']}} ] 
                                          @endif</b>

                                       </td>
                                    </tr>
                                    <tr>
                                       <td colspan="13"><b>Sale Date : 
                                          @if(!$bus_id) 
                                             [ {{date('d/m/Y',strtotime($search['start_date']))}} ] - [ {{date('d/m/Y',strtotime($search['end_date'])) }}]
                                          @else 
                                             [ {{date('d/m/Y',strtotime($search['start_date']))}} ]
                                          @endif
                                          </b>
                                       </td>
                                    </tr>
                                    <tr class="table-bordered">
                                       <th class="span1">ဝယ္ယူ သည့္ေန႔</th>
                                       <th>လက္မွတ္ No</th>
                                       <th>ခရီးစဥ္</th>
                                       <th>ထြက္ခြာ မည့္ ေန့ရက္ / အခ်ိန္</th>
                                       <th>ကားအမ်ုိဳး အစား</th>
                                       <th>အေရာင္း ကုိယ္စားလွယ္</th>
                                       <th>ဝယ္သူ</th>
                                       <th>ခုံနံပါတ္</th>
                                       <th>ခုံအေရ အတြက္</th>
                                       <th>အခမဲ႕ လက္မွတ္ </th>
                                       <th>ေစ်းႏုန္း</th>
                                       <th>ရွင္းႏႈန္း</th>
                                       <th>စုုစုုေပါင္း</th>
                                    </tr>
                                 </thead>
                                  @if($response)
                                    <tbody class="table-bordered">
                                      <?php 
                                        $G_totalticket=0;
                                        $G_totalamount=0;
                                      ?> 
                                      @foreach($response as $key=>$rows)
                                          <!-- <tr>
                                             <td colspan="13"><h4>{{$key}}</h4></td>
                                          </tr> -->
                                          @if(count($rows)>0)
                                             <?php 
                                                $totalticket=0;
                                                $totalamount=0;
                                             ?> 
                                             @foreach($rows as $result)
                                                <tr>
                                                   <!-- <td></td> -->
                                                   <td>{{date('d/m/Y',strtotime($result['order_date']))}}</td>
                                                   <td>{{$result['ticket_no']}}</td>
                                                   <td>{{$result['from_to']}}</td>
                                                   <td>{{date('d/m/Y',strtotime($result['departure_date']))}} ({{$result['time']}})</td>
                                                   <td>{{$result['classes']}}</td>
                                                   <td>
                                                      <div class="wordwrap">
                                                         &nbsp;{{$result['agent_name']}}
                                                      </div>
                                                   </td>
                                                   <td>{{$result['buyer_name']}}</td>
                                                   <td>{{$result['seat_no']}}</td>
                                                   <td>{{$result['sold_seat']}}</td>
                                                   <td>{{$result['free_ticket']}}</td>
                                                   <td>{{$result['price']}}</td>
                                                   <td>{{$result['price']-$result['commission']}} ({{$result['commission']}})</td>
                                                   <td>{{$result['total_amount']}}</td>
                                                </tr>
                                                <?php 
                                                   $totalticket +=$result['sold_seat'];
                                                   $totalamount +=$result['total_amount'];
                                                ?> 
                                             @endforeach
                                             <!-- <tr>
                                                <th colspan="6">&nbsp;</th>
                                                <th colspan="2">Sub Quantity</th>
                                                <th>{{$totalticket}}</th>
                                                <th colspan="2">Sub Total</th>
                                                <th colspan="2" style="text-align:right;">{{$totalamount}}</th>
                                             </tr> -->
                                          @endif
                                          <?php 
                                             $G_totalticket +=$totalticket;
                                             $G_totalamount +=$totalamount;
                                          ?> 
                                      @endforeach
                                    </tbody>
                                    <tfoot>
                                      <tr style="background:#ddd;">
                                        <th colspan="6">&nbsp;</th>
                                        <th colspan="2" class="text-right">Grand Quantity</th>
                                        <th>: {{$G_totalticket}}</th>
                                        <th colspan="3" class="text-right">Grand Total :</th>
                                        <th style="text-align:right;">{{$G_totalamount}}</th>
                                      </tr>
                                    </tfoot>
                                  @endif
                              </table>
                           </div>
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
   <script type="text/javascript" src="../../../js/jquery.battatech.excelexport.min.js"></script>
   <script type="text/javascript" src="../../../../js/jquery.dataTables.v1.10.4.min.js"></script>
   <script type="text/javascript">
      $(document).ready(function() {
          var table = $('#tblExport').DataTable({
             /* "columnDefs": [
                  { "visible": false, "targets": 2 }
              ],*/
              "order": [[ 2, 'asc' ]],
              "displayLength": 25,
              "pagingType": "full_numbers",
              "drawCallback": function ( settings ) {
                  var api = this.api();
                  var rows = api.rows( {page:'current'} ).nodes();
                  var last=null;
       
                  api.column(2, {page:'current'} ).data().each( function ( group, i ) {
                      if ( last !== group ) {
                          $(rows).eq( i ).before(
                              '<tr class="group"><th colspan="13">'+group+'</th></tr>'
                          );
       
                          last = group;
                      }
                  } );
              }
          } );
          // Order by the grouping
          $('#tblExport tbody').on( 'click', 'tr.group', function () {
              var currentOrder = table.order()[0];
              if ( currentOrder[0] === 5 && currentOrder[1] === 'asc' ) {
                  table.order( [ 2, 'desc' ] ).draw();
              }
              else {
                  table.order( [ 2, 'asc' ] ).draw();
              }
          } );
      } );
   </script>
   
   <script type="text/javascript">
      $(function() {
         $('.page-container').addClass('sidebar-closed');
         $('.print').click(function() {
            w=window.open();
            w.document.write($('#contents').html());
            w.print();
            w.close();

            
         });

         $("#btnExportExcel").click(function () {
               var filename=$('#filename').html();
               var filename="Sale Report("+filename+")";
               var uri =$("#tblExport").btechco_excelexport({
                      containerid: "tblExport"
                     , datatype: $datatype.Table
                     , returnUri: true
                  });
               $(this).attr('download', filename+'.xls').attr('href', uri).attr('target', '_blank');
         });
      
                
      });
      
   </script>
@stop
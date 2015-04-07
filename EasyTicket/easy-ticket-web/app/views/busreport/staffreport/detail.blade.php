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
                     ?>
                     <?php $pagetitle=""; ?>
                      <h3 class="page-title">
                         &nbsp;Staff အလုိက္ အေရာင္းစားရင္း အေသးစိတ္
                      </h3>
                      <?php $pagetitle="Staff အလုိက္ အေရာင္းစားရင္း အေသးစိတ္"; ?>

                     
                      <div id="filename" style="display:none;">{{$orderdate}} Sales</div>
                      <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?access_token={{Auth::user()->access_token}}&operator_id={{Session::get('operator_id')}}">ပင္မစာမ်က္ႏွာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="{{$search['back_url']}}">Staff အလုိက္ အေရာင္းစားရင္း အေသးစိတ္</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">&nbsp; Staff အလုိက္ အေရာင္းစားရင္း အေသးစိတ္</a>
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
                                    <div class="btn-group pull-right hide">
                                       <button class="btn green dropdown-toggle" data-toggle="dropdown">Print <i class="icon-angle-down"></i>
                                       </button>
                                       <ul class="dropdown-menu">
                                          <li>
                                             <!-- <a href="#" class="print">Print</a></li> -->
                                          <li><a href="#" id="btnExportExcel">Export to Excel</a></li>
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div id="contents">
                              <div id="printable">
                                 <h3>{{ucwords($myApp->operator_name)}} Express Company Limited</h3>
                                 <h3>Sale Details</h3>
                              </div>
                              <table class="table table-striped table-advance table-hover" id="tblExport">
                                 <thead>
                                    <tr>
                                       <td  colspan="13">
                                          <h3 align="center">{{ucwords($myApp->operator_name)}} Express Company Limited</h3>
                                          <h4 align="center">{{$pagetitle}}</h4>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td colspan="13"><b>Staff : {{$search['seller_name']}} 
                                         

                                       </td>
                                    </tr>
                                    <tr>
                                       <td colspan="13"><b>Trip Departure Date :{{$search['trip_date']}} 
                                          
                                          </b>
                                       </td>
                                    </tr>
                                    <tr class="table-bordered">
                                       <th class="span1">လက္မွတ္ No</th>
                                       <th>Seller</th>
                                       <th>ခုံနံပါတ္ </th>
                                       <th>ခရီးစဥ္</th>
                                       <th>ထြက္ခြာ မည့္ ေန့ရက္ / အခ်ိန္</th>
                                       <th>ကားအမ်ုိဳး အစား</th>
                                       <th>အေရာင္း ကုိယ္စားလွယ္</th>
                                       <th>ဝယ္သူ</th>
                                       <th>ဝယ္ယူ သည့္ေန႔</th>
                                       <th>ခုံအေရ အတြက္</th>
                                       <th>အခမဲ႕ လက္မွတ္ </th>
                                       <th>Discount </th>
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
                                        $Org_totalamount=0;
                                      ?> 
                                      @foreach($response as $key=>$result)
                                        <tr>
                                           <!-- <td></td> -->
                                           <td># {{$result['ticket_no']}}</td>
                                           <td>{{$result['seller_name']}}</td>
                                           <td>{{$result['seat_no']}}</td>
                                           <td>{{$result['trip']}}</td>
                                           <td>{{date('d/m/Y',strtotime($result['departure_date']))}} ({{$result['time']}})</td>
                                           <td>{{$result['class_name']}}</td>
                                           <td>
                                              <div class="wordwrap">
                                                 &nbsp;{{$result['agent_name']}}
                                              </div>
                                           </td>
                                           <td>{{$result['buyer_name']}}</td>
                                           <td>{{date('d/m/Y',strtotime($result['orderdate']))}}</td>
                                           <td>{{$result['sold_seat']}}</td>
                                           <td>{{$result['free_ticket']}}</td>
                                           <td>{{$result['discount']}}</td>
                                           @if($result['foreign_person'] > 0)
                                           <td>{{$result['foreign_price']}}</td>
                                           @else
                                           <td>{{$result['price']}}</td>
                                           @endif
                                           @if($result['foreign_person'] > 0)
                                           <td>{{$result['foreign_person']-$result['commission']}} ({{$result['commission']}})</td>
                                           @else
                                           <td>{{$result['price']-$result['commission']}} ({{$result['commission']}})</td>
                                           @endif
                                           <td>{{$result['price']- $result['commission']}}</td>
                                        </tr>
                                        <?php 
                                           $G_totalticket++;
                                           $G_totalamount +=$result['price']- $result['commission'];
                                           $Org_totalamount +=$result['price'];
                                        ?> 
                                      @endforeach
                                    </tbody>
                                    <tfoot>
                                      <tr style="background:#ddd;">
                                        <th colspan="5" class="text-right">Grand Quantity : {{BaseController::number_format($G_totalticket)}} seats</th>
                                        <th colspan="5" class="text-right">Grand Total : {{BaseController::number_format($Org_totalamount)}} Ks</th>
                                        <th colspan="5" class="text-right">% ႏုတ္ျပီး စုစုေပါင္း = {{BaseController::number_format($G_totalamount)}} Ks</th>
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
              "columnDefs": [
                  { "visible": false, "targets": 1 }
              ],
              "autoWidth":false,
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
                              '<tr class="group"><th colspan="9">'+group+'</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>'
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
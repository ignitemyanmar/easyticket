@extends('../admin')
@section('content')
<style type="text/css">
   thead tr,thead tr td{border:none !important; color: #222;font-weight: bold; border-right: none !important;border-right-color:#fff; }
   h4,th,td,h3{font-family: "Zawgyi-One" !important;}
   
   @media print{
      #printable { display: block; font-family: "Zawgyi-One" !important; }
      h3{text-align: center !important; }
      h4,th,td{font-family: "Zawgyi-One" !important;}
   }
   #printable { display: none; }
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
                        &nbsp;ေန႔စဥ္ အေရာင္းစာရင္း
                     </h3>

                     @if($bus_id)
                        <div id="filename" style="display:none;">{{date('d/m/Y',strtotime($search['date']))}} [{{$search['trip']}}]-Sale Report</div>
                        
                     @else
                        <div id="filename" style="display:none;">{{$orderdate}}-Sale Report</div>
                     @endif
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}">ပင္မစာမ်က္ႏွာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="/report/dailycarandadvancesale/search?date={{$orderdate}}&operator_id={{$V_operator_id}}">ေန႔စဥ္ အေရာင္းစာရင္း</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">&nbsp; ေန႔စဥ္ အေရာင္းစာရင္း အေသးစိတ္</a>
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
                           <h4><i class="icon-th-list"></i>ေန႔စဥ္ အေရာင္းစာရင္း </h4>
                           <div class="actions">
                           </div>
                        </div>


                        <div class="portlet-body">
                           <div class="row-fluid">
                              <div class="span7">
                                 
                              </div>
                              <div class="span5">
                                 <div class="clearfix">
                                    <div class="btn-group pull-right">
                                       <button class="btn green dropdown-toggle" data-toggle="dropdown">Tools <i class="icon-angle-down"></i>
                                       </button>
                                       <ul class="dropdown-menu">
                                          <li>
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
                                       <td  colspan="14">
                                          <h3 align="center">Elite Express Public Company Limited</h3>
                                          <h4 align="center">ေန႔စဥ္ အေရာင္းစာရင္း အေသးစိတ္</h4>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td colspan="14"><b>Trips : 
                                          [ {{$search['start_trip']}} ] @if($search['start_trip'] != $search['end_trip']) - [ {{$search['end_trip']}} ]  @endif
                                       </td>
                                    </tr>
                                    <tr>
                                       <td colspan="14"><b>Date Of Report : 
                                          [ {{date('d/m/Y',strtotime($search['date']))}} ]
                                          </b>
                                       </td>
                                    </tr>

                                    <tr class="table-bordered">
                                       <!-- <th>ထြက္ခြာ မည့္ ေန့ရက္</th> -->
                                       <th>လက္မွတ္ No</th>
                                       <th>ခုံနံပါတ္</th>
                                       <th>ခရီးစဥ္</th>
                                       <th>ထြက္ခြာ မည့္ ေန့ရက္ / အခ်ိန္</th>
                                       <th>ကားအမ်ုိဳး အစား</th>
                                       <th>အေရာင္း ကုိယ္စားလွယ္</th>
                                       <th>ဝယ္သူ</th>
                                       <th>ခုံအေရ အတြက္</th>
                                       <th>အခမဲ႕ လက္မွတ္ </th>
                                       <th>ေစ်းႏုန္း</th>
                                       <th>ရွင္းႏႈန္း</th>
                                       <th>စုုစုုေပါင္း</th>
                                    </tr>
                                 </thead>
                                 <tbody class="table-bordered">
                                    @if($response)
                                       <?php 
                                          $G_totalticket=0;
                                          $G_totalamount=0;
                                       ?> 
                                       @foreach($response as $key=>$rows)
                                          <tr>
                                             <td colspan="13"><h4>{{$key}}</h4></td>
                                          </tr>
                                          @if(count($rows)>0)
                                             <?php 
                                                $totalticket=0;
                                                $totalamount=0;
                                             ?> 
                                             @foreach($rows as $result)
                                                <tr>
                                                   <!-- <td></td> -->
                                                   <td>{{$result['ticket_no']}}</td>
                                                   <td>{{$result['seat_no']}}</td>
                                                   <td>{{$result['from_to']}}</td>
                                                   <td>{{date('d/m/Y',strtotime($result['departure_date']))}} ({{$result['time']}})</td>
                                                   <td>{{$result['classes']}}</td>
                                                   <td>
                                                      <div class="wordwrap">
                                                         &nbsp;{{$result['agent_name']}}
                                                      </div>
                                                   </td>
                                                   <td>{{$result['buyer_name']}}</td>
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
                                             <tr>
                                                <th colspan="6">&nbsp;</th>
                                                <th colspan="2">Sub Quantity</th>
                                                <th>{{$totalticket}}</th>
                                                <th colspan="2">Sub Total</th>
                                                <th colspan="2" style="text-align:right;">{{$totalamount}}</th>
                                             </tr>
                                          @endif
                                          <?php 
                                             $G_totalticket +=$totalticket;
                                             $G_totalamount +=$totalamount;
                                          ?> 
                                       @endforeach
                                          <tr style="background:#ddd;">
                                             <th colspan="6">&nbsp;</th>
                                             <th colspan="2">Grand Quantity</th>
                                             <th>{{$G_totalticket}}</th>
                                             <th colspan="2">Grand Total</th>
                                             <th colspan="2" style="text-align:right;">{{$G_totalamount}}</th>
                                          </tr>

                                    @endif
                                 </tbody>
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
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
   <script type="text/javascript" src="../../../js/jquery.battatech.excelexport.min.js"></script>
   
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
               var filename=filename;
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
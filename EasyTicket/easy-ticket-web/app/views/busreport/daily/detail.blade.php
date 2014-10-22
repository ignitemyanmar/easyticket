@extends('../admin')
@section('content')
<!-- BEGIN PAGE -->  
   <div class="page-content">
      <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
               <div class="row-fluid">
                  <div class="span12">
                     <?php 
                        // $orderdate=$response[0]['departure_date']; 
                        $orderdate=Session::get('search_daily_date'); 
                        $V_operator_id=Session::get('operator_id');
                     ?>

                     <h3 class="page-title">
                        ကားအလုိက္ေရာင္းရေသာ လက္မွတ္စာရင္း
                        <!-- <small>form wizard sample</small> -->
                     </h3>
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
                           <a href="#">ကားအလုိက္ေရာင္းရေသာ လက္မွတ္စာရင္း</a>
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
                           <h4><i class="icon-th-list"></i>ကားအလုိက္ေရာင္းရေသာ လက္မွတ္စာရင္း</h4>
                           <div class="actions">
                           </div>
                        </div>


                        <div class="portlet-body">
                           <div class="clearfix">
                              <div class="btn-group pull-right">
                                 <button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="icon-angle-down"></i>
                                 </button>
                                 <ul class="dropdown-menu">
                                    <li><a href="#" class="print">Print</a></li>
                                    <!-- <li><a href="#">Save as PDF</a></li> -->
                                    <li><a href="#" id="btnExportExcel">Export to Excel</a></li>
                                 </ul>
                              </div>
                           </div>
                           <div id="contents">
                              <table class="table table-striped table-bordered table-advance table-hover" id="tblExport">
                                 <thead>
                                    <tr>
                                       <th>ကားနံပါတ္</th>
                                       <th>ခရီးစဥ္</th>
                                       <th>အေရာင္း ကုိယ္စားလွယ္</th>
                                       <th>ဝယ္သူ</th>
                                       <th>ကားအမ်ုိဳးအစား</th>
                                       <th>ထြက္ခြာမည့္ေန႕</th>
                                       <th>အခ်ိန္</th>
                                       <th>ခုံနံပါတ္</th>
                                       <th>လက္မွတ္ နံပါတ္</th>
                                       <th>ဝယ္သည့္ေန႕</th>
                                       <th>ေစ်းႏုန္း</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @if($response)
                                       @foreach($response as $result)
                                          <tr>
                                             <td>{{$result['bus_no']}}</td>
                                             <td>{{$result['Trip']}}</td>
                                             <td>{{$result['agent']}}</td>
                                             <td>{{$result['name']}}</td>
                                             <td>{{$result['class']}}</td>
                                             <td>{{$result['departure_date']}}</td>
                                             <td>{{$result['departure_time']}}</td>
                                             <td>{{$result['seat_no']}}</td>
                                             <td>{{$result['ticket_no']}}</td>
                                             <td>{{$result['order_date']}}</td>
                                             <td>{{$result['price']}}</td>
                                          </tr>
                                       @endforeach
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
               $("#tblExport").btechco_excelexport({
                   containerid: "tblExport"
                  , datatype: $datatype.Table
               });
            return false;  
         });
         
      });

   </script>
@stop
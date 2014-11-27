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
                        $orderdate=Session::get('search_daily_date'); 
                        $V_operator_id=Session::get('operator_id');
                     ?>

                     <h3 class="page-title">
                        &nbsp;ေန႔စဥ္ အေရာင္းစာရင္း
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
                                 @if(count($response)>0)
                                    <div><h4>ခရီးစဥ္ : {{$response[0]['from_to']}} ({{$response[0]['classes']}}) </h4></div>
                                    <div><h4>ေန ့ရက္(အခ်ိန္)  : {{date('d/m/Y',strtotime($response[0]['departure_date']))}} ({{$response[0]['time']}})  </h4></div><br>
                                 @endif
                              </div>
                              <div class="span5">
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
                              </div>
                           </div>
                           <div id="contents">
                              <table class="table table-striped table-bordered table-advance table-hover" id="tblExport">
                                 <thead>
                                    <tr>
                                       <th>ထြက္ခြာမည့္ေန ့ရက္</th>
                                       <th>ခရီးစဥ္</th>
                                       <th>ထြက္ခြာမည့္အခ်ိန္</th>
                                       <th>ကားအမ်ုိဳးအစား</th>
                                       <th>အေရာင္း ကုိယ္စားလွယ္</th>
                                       <th>ခုံနံပါတ္</th>
                                       <th>ခုံအေရအတြက္</th>
                                       <th>ေစ်းႏုန္း</th>
                                       <th>ရွင္းႏႈန္း</th>
                                       <th>စုုစုုေပါင္း</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @if($response)
                                       @foreach($response as $result)
                                          <tr>
                                             <td>{{date('d/m/Y',strtotime($result['departure_date']))}}</td>
                                             <td>{{$result['from_to']}}</td>
                                             <td>{{$result['time']}}</td>
                                             <td>{{$result['classes']}}</td>
                                             <td>
                                                <div class="wordwrap">
                                                   &nbsp;{{$result['agent_name']}}
                                                </div>
                                             </td>
                                             <td>{{$result['seat_no']}}</td>
                                             <td>{{$result['sold_seat']}}</td>
                                             <td>{{$result['price']}}</td>
                                             <td>{{$result['price']-$result['commission']}} ({{$result['commission']}})</td>
                                             <td>{{$result['total_amount']}}</td>
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
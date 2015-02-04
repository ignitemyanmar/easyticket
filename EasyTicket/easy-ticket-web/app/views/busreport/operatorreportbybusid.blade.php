@extends('../admin')
@section('content')
<style type="text/css">
   .padding-5{padding: 5px;}
</style>
<!-- BEGIN PAGE -->  
   <div class="page-content">
      <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
               <div class="row-fluid">
                  <div class="span12">
                     <!-- BEGIN STYLE CUSTOMIZER -->
                     
                     <div class="color-panel hidden-phone">
                        <div class="color-mode-icons icon-color"></div>
                        <div class="color-mode-icons icon-color-close"></div>
                        <div class="color-mode">
                           <p>THEME COLOR</p>
                           <ul class="inline">
                              <li class="color-black current color-default" data-style="default"></li>
                              <li class="color-blue" data-style="blue"></li>
                              <li class="color-brown" data-style="brown"></li>
                              <li class="color-purple" data-style="purple"></li>
                              <li class="color-white color-light" data-style="light"></li>
                           </ul>
                           <label class="hidden-phone">
                           <input type="checkbox" class="header" checked value="" />
                           <span class="color-mode-label">Fixed Header</span>
                           </label>                    
                        </div>
                     </div>
                     <!-- END BEGIN STYLE CUSTOMIZER -->  
                     <?php 
                        $orderdate=Session::get('search_daily_date'); 
                        $V_operator_id=Session::get('operator_id');
                     ?> 
                     <h3 class="page-title">
                        ကားအလုိက္ေရာင္းရေသာ လက္မွတ္စာရင္း
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?access_token={{Auth::user()->access_token}}&operator_id={{Session::get('operator_id')}}">ပင္မစာမ်က္ႏွာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">ေန႕ရက္အလုိက္အေရာင္းစာရင္းမ်ား</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">ကားအလုိက္ေရာင္းရေသာ လက္မွတ္စာရင္း</a></li>
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
                                       <th>ခုံနံပါတ္</th>
                                       <th>ဝယ္သူ</th>
                                       <th>အေရာင္းကုိယ္စားလွယ္</th>
                                       <!-- <th>ထြက္ခြာမည့္ေန႕</th> -->
                                       <!-- <th>အခ်ိန္</th> -->
                                       <th>ေစ်းႏႈန္း</th>
                                       <th>ရွင္း-ႏႈန္း</th>
                                       <th>လက္မွတ္နံပါတ္</th>
                                       <!-- <th></th> -->
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @if($response)
                                       @foreach($response as $tripbydaily)
                                          <tr>
                                             <td class="hidden-phone">{{ $tripbydaily['seat_no']}}</td>
                                             <td>{{ $tripbydaily['customer_name']}}</td>
                                             <td>{{ $tripbydaily['agent_name']}}</td>
                                             <!-- <td>{{ $parameter['date']}}</td> -->
                                             <!-- <td>{{ $parameter['time']}}</td> -->
                                             <td>{{ $tripbydaily['price']}}</td>
                                             <td>{{ $tripbydaily['price']-$tripbydaily['commission']}} ({{$tripbydaily['commission']}})</td>
                                             <td>{{ $tripbydaily['ticket_no']}}</td>
                                             
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
               <!-- <div class="row-fluid">
                  <div class="responsive span3 padding-5" data-tablet="span4" data-desktop="span4">
                     <a href="#" class="print" rel="contents"><img src="../../img/icon-48-print.png" border=0 align="middle"></a>
                     
                  </div>
               </div> -->
            <!-- END PAGE CONTENT-->         
         </div>
      <!-- END PAGE CONTAINER-->
   </div>


<!-- END PAGE -->  
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
   <script type="text/javascript" src="../../js/jquery.battatech.excelexport.min.js"></script>
   
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
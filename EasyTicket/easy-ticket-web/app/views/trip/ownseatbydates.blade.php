@extends('../admin')
@section('content')
   <link rel="stylesheet" href="../../../../css/jquery.dataTables.v1.10.4.css" />
   <link rel="shortcut icon" href="favicon.ico" />
   <!-- BEGIN PAGE -->
      <div class="page-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
               <div class="row-fluid">
                  <div class="span12">
                        
                     <!-- BEGIN PAGE TITLE & BREADCRUMB-->        
                     <h3 class="page-title">
                        ခုပုိင္သတ္မွတ္ ထားေသာစာရင္း          
                        <small></small>
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <i class="icon-angle-right"></i>
                        </li>
                        <li><a href="#">ခုပုိင္သတ္မွတ္ ထားေသာစာရင္း</a></li>
                        
                     </ul>
                     <!-- END PAGE TITLE & BREADCRUMB-->
                  </div>
               </div>
            <!-- END PAGE HEADER-->
            <div id="dashboard">
               <!-- BEGIN PAGE -->
                  <div class="row-fluid">
                     <div class="span12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet box blue">
                           <div class="portlet-title">
                              <h4><i class="icon-edit"></i>ခုပုိင္သတ္မွတ္ ထားေသာစာရင္း</h4>
                           </div>
                           <div class="portlet-body">
                              <div class="clearfix">
                                 <div class="btn-group">
                                    <a href="/define-ownseat/{{$trip->id}}?{{$myApp->access_token}}">
                                    <button id="" class="btn green">
                                    Add New <i class="icon-plus"></i>
                                    </button>
                                    </a>
                                 </div>
                                 
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
                              @if(Session::has('message'))
                                 <?php $message=Session::get('message'); ?>
                                 @if($message)
                                 <div class="alert alert-success">
                                    <button class="close" data-dismiss="alert"></button>
                                    {{$message}}
                                 </div>
                                 @endif
                              @endif

                              <table class="table table-striped table-hover table-bordered" id="table_editable">
                                 <thead>
                                    <tr>
                                       <th class="hidden-phone span3">ခရီးစဥ္</th>
                                       <th class="span1">-</th>
                                       <th class="span2">ကားအမ်ိဳးအစား / ခုံအစီအစဥ္</th>
                                       <th class="span2">ကားထြက္သည့္ ေန႕ အခ်ိန္<!-- ႕မ်ား --></th>
                                       <th class="span1">ႏုိင္ငံသား ေစ်းႏုန္း</th>
                                       <th class="span1">ႏုိင္ငံျခားသား<br> ေစ်းႏုန္း</th>
                                       <th class="span1">ေကာ္မစ္ရွင္ႏႈန္း</th>
                                       <th class="span2">Date</th>
                                       <th class="span2">-</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @if($trip)
                                      @if($trip->closeseat)
                                        @foreach($trip->closeseat as $key=>$rows)
                                          <tr>
                                             <td class="hidden-phone span2">
                                                {{$trip['from_city']->name.' => '.$trip['to_city']->name}} 
                                                @if($trip->ever_close == 0)
                                                   @if((strtotime($trip->from_close_date) >= strtotime(date('Y-m-d'))) && (strtotime(date('Y-m-d')) <= strtotime($trip->to_close_date)))
                                                   <br><span class="label label-important">NOTE!</span>
                                                   <span>We are close from [{{date('d/m/Y',strtotime($trip->from_close_date))}}] to [{{date('d/m/Y',strtotime($trip->to_close_date))}}] date because of [{{$trip->remark}}]</span>
                                                   @endif
                                                @endif
                                                @if($trip->ever_close == 1)
                                                   <br><span class="label label-important">NOTE!</span>
                                                   <span>We are ever close from [{{date('d/m/Y',strtotime($trip->from_close_date))}}] because of [{{$trip->remark}}]</span>
                                                @endif
                                             </td>
                                             <td class="hidden-phone span2">
                                                {{$trip['from_city']->name.' => '.$trip['to_city']->name}} 
                                             </td>
                                             <!-- <td>{{$trip['to_city']->name}}</td> -->
                                             <td>{{$trip['busclass']->name}}<br>{{$trip['seat_plan']->name}}

                                             </td>
                                             <td>
                                                <p>ကားထြက္သည့္ ေန႕မ်ား : {{$trip['available_day']}}</p>
                                             အခ်ိန္ :   {{$trip['time']}}</td>
                                             <td>
                                                {{$trip['price']}}
                                             </td>
                                             <td>{{$trip['foreign_price']}}</td>
                                             <td>{{$trip['commission']}}</td>
                                             <td> <span class="label label-info">{{date('d-m-Y',strtotime($rows->start_date)).' To '. date('d-m-Y',strtotime($rows->end_date))}} &nbsp;</span></td>
                                             <td>
                                                <a href="/define-ownseat/{{$trip['id']}}?date_range={{$rows->start_date.','.$rows->end_date}}&{{$myApp->access_token}}" style="float:left;">
                                                  <span class="label label-info">&nbsp;View <i class="icon-external-link"></i></span>
                                                </a>
                                                  <a href="/ownseat/delete/{{$rows->id}}?{{$myApp->access_token}}" style="float:right;">
                                                    <span  class="label label-important"><i class="icon-remove"></i></span>
                                                  </a>
                                                <br>
                                             </td>
                                        </tr>
                                        @endforeach
                                      @endif
                                    @endif
                                    
                                 </tbody>
                              </table>
                           </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                     </div>
                  </div>
                  
               <!-- END PAGE -->
               
            </div>
         </div>
         <!-- END PAGE CONTAINER-->    
      </div>
      <!-- END PAGE --> 
   <script type="text/javascript" src="../../../../js/jquery.dataTables.v1.10.4.min.js"></script>
   <script type="text/javascript" src="../../../js/jquery.battatech.excelexport.min.js"></script>
   <script type="text/javascript">
      $(document).ready(function() {
          var table = $('#table_editable').DataTable({
              "columnDefs": [
                  { "visible": false, "targets": 1 }
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
                              '<tr class="group"><th colspan="8">'+group+'</th></tr>'
                          );
       
                          last = group;
                      }
                  } );
              }
          } );
          // Order by the grouping
          $('#table_editable tbody').on( 'click', 'tr.group', function () {
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
   <script>
      
      $("#btnExportExcel").click(function () {
               var filename="TripList";
               var uri =$("#tblExport").btechco_excelexport({
                      containerid: "tblExport"
                     , datatype: $datatype.Table
                     , returnUri: true
                  });
               $(this).attr('download', filename+'.xls').attr('href', uri).attr('target', '_blank');
        });
   </script>
@stop
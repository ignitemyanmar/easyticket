@extends('../admin')
@section('content')
   <!-- <link rel="stylesheet" href="assets/data-tables/DT_bootstrap.css" /> -->
   <link rel="stylesheet" href="../../../../css/jquery.dataTables.v1.10.4.css" />
   <style type="text/css">
      #tblExport_length select{width: 80px;}
      tr.group td,
      tr.group td:hover {
          background: #ddd !important;
      }
      tfoot th{background: #ddd !important;}

   </style> 
   <style type="text/css">
   tr.heading td{background: #E4F6F5 !important;}
   </style>
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
                        လုပ္ပုိင္ခြင့္မ်ား          
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}?{{$myApp->access_token}}">ပင္မစာမ်က္ႏွာ</a> 
                           <i class="icon-angle-right"></i>
                        </li>
                        <li><a href="#">လုပ္ပုိင္ခြင့္မ်ား</a></li>
                        
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
                              <h4><i class="icon-th-list"></i>လုပ္ပုိင္ခြင့္မ်ား</h4>
                           </div>
                           <div class="portlet-body">
                              <div class="clearfix">
                                 <div class="btn-group">
                                    <a href="/permission-create?{{$myApp->access_token}}">
                                    <button id="" class="btn green">
                                    အသစ္ထည့္မည္ <i class="icon-plus"></i>
                                    </button>
                                    </a>
                                 </div>
                                 
                              </div>
                              @if(Session::has('message'))
                                 <?php $message=Session::get('message'); ?>
                                 @if($message['status']==0)
                                    <div class="alert alert-error">
                                       <button class="close" data-dismiss="alert"></button>
                                       <strong>Info ! </strong>{{$message['info']}}.
                                    </div>
                                 @else
                                    <div class="alert alert-success">
                                       <button class="close" data-dismiss="alert"></button>
                                       <strong>Success!</strong>{{$message['info']}}
                                    </div>
                                 @endif
                              @endif
                              <table class="table table-striped table-hover table-bordered" id="tblExport">
                                 <thead>
                                       <th>Header</th>
                                       <th class="span4">ရာထူး</th>
                                       <th class="span5">လုပ္ပုိင္ခြင့္မ်ား</th>
                                       <th class="span3">ဖ်က္ရန္</th>
                                 </thead>
                                 <tbody>
                                    @foreach($response as $key=>$rows)
                                      <tr>
                                         <td>{{$rows->header}}</td>
                                         <td>
                                            @if($rows->role==2)
                                              Staff 
                                            @elseif($rows->role==3) 
                                                Agent 
                                            @elseif($rows->role==4) 
                                                Supervisor 
                                            @elseif($rows->role==8) 
                                              Manager 
                                            @else 
                                              Administrator 
                                            @endif</td>
                                         <td>{{$rows->menu}}</td>
                                         <td style="text-align:right;">
                                            <a href="permission-delete/{{$rows->id}}?{{$myApp->access_token}}" class="btn red-stripe delete">ဖ်က္ရန္</a>
                                         </td>
                                      </tr>
                                    @endforeach
                                    
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
   <script type="text/javascript">
      $(document).ready(function() {
          var table = $('#tblExport').DataTable({
              "columnDefs": [
                  { "visible": false, "targets": 0 }
              ],
              "order": [[ 0, 'asc' ]],
              "displayLength": 25,
              "pagingType": "full_numbers",
              "drawCallback": function ( settings ) {
                  var api = this.api();
                  var rows = api.rows( {page:'current'} ).nodes();
                  var last=null;
       
                  api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                      if ( last !== group ) {
                          $(rows).eq( i ).before(
                              '<tr class="group"><td colspan="4">'+group+'</td></tr>'
                          );
       
                          last = group;
                      }
                  } );
              }
          } );
          // Order by the grouping
          $('#tblExport tbody').on( 'click', 'tr.group', function () {
              var currentOrder = table.order()[0];
              if ( currentOrder[0] === 0 && currentOrder[1] === 'asc' ) {
                  table.order( [ 0, 'desc' ] ).draw();
              }
              else {
                  table.order( [ 0, 'asc' ] ).draw();
              }
          } );

          $('#tblExport a.delete').live('click', function (e) {
            if (confirm("Are you sure to delete this row ?") == false) {
                return false;
            }
            return true;
          });
      } );
   </script>
@stop
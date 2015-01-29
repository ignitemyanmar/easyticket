@extends('../admin')
@section('content')
   <!-- <link rel="stylesheet" href="../../assets/data-tables/DT_bootstrap.css" /> -->
   {{HTML::style('../../../css/jquery.dataTables.v1.10.4.css')}}
   <style type="text/css">
      tr.group td,
      tr.group td:hover {
          background: #ddd !important;
      }
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
                        အေရာင္းကုိယ္စားလွယ္ ႏွင့္ အေၾကြးစာရင္းမ်ား          
                        <small></small>
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <i class="icon-angle-right"></i>
                        </li>
                        <!-- <li><a href="/">Dashboard</a></li> -->
                        
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
                              <h4><i class="icon-edit"></i>အေရာင္းကုိယ္စားလွယ္ ႏွင့္ အေၾကြးစာရင္းမ်ား</h4>
                           </div>
                           <div class="portlet-body">
                              @if(Session::has('message'))
                                 <?php $message=Session::get('message'); ?>
                                 @if($message)
                                 <div class="alert alert-success">
                                    <button class="close" data-dismiss="alert"></button>
                                    {{$message}}
                                 </div>
                                 @endif
                              @endif

                              <table class="table table-striped table-hover table-bordered" id="tblExport">
                                 <thead>
                                    <tr>
                                       <th>ခရီးစဥ္</th>
                                       <th>&nbsp;</th>
                                       <th>အခ်ိန္</th>
                                       <th>Agent (Branches)</th>
                                       <!-- <th>Commission အမ်ိဳးအစား</th> -->
                                       <th>Commission ပမာဏ</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                       @if($response)
                                          @foreach($response as $row)
                                             <tr>
                                                <td>{{$row['tripname']}}</td>
                                                <td>{{$row['tripname']}} @if($row['trip']) [ {{$row['trip']['time']}} ] @else - @endif ( {{$row['commission']}} )</td>
                                                <td>@if($row['trip']) {{$row['trip']['time']}} @else - @endif</td>
                                                <td>@if($row['agent']) {{$row['agent']['name']}} @else - @endif</td>
                                                <td>{{$row['commission']}}</td>
                                             </tr>
                                          @endforeach
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
   {{HTML::script('../../../js/jquery.dataTables.v1.10.4.min.js')}}
   
   <script type="text/javascript">
      $(document).ready(function() {
          var table = $('#tblExport').DataTable({
              "columnDefs": [
                  { "visible": false, "targets": 1 }
              ],
              "order": [[ 1, 'asc' ]],
              "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
              "pagingType": "full_numbers",
              "drawCallback": function ( settings ) {
                  var api = this.api();
                  var rows = api.rows( {page:'current'} ).nodes();
                  var last=null;
                  
                  api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                     var grouplength=$('.group').length;
                     if ( last !== group ) {
                        $(rows).eq( i ).before(
                           '<tr class="group"><td colspan="5">'+group+'</td></tr>'
                        );
                        last = group;
                      }
                  } );
              }
          } );
      } );
   </script>
@stop
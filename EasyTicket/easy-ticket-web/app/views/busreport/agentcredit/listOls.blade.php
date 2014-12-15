@extends('../admin')
@section('content')
<link rel="stylesheet" href="../../../../css/jquery.dataTables.v1.10.4.css" />
<style type="text/css">
   #tblExport_length select{width: 80px;}
   tr.group th,
   tr.group th:hover {
       background: #E4F6F5  !important;
   }
   tr.groupfooter th,
   tr.groupfooter th:hover {
       background: #ddd  !important;
   }
   /*.group b{display: none !important;}
   .groupfooter span{display: none !important;}*/
   tfoot th{background: #E4F6F5  !important;}
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
                                       <th class="span3">အမည္</th>
                                       <th>Group Header</th>
                                       <th>ၾကိဳတင္ေငြ</th>
                                       <th>ယခုအေၾကြး</th>
                                       <th>လက္က်န္အေၾကြး</th>
                                       <th>-</th>
                                    </tr>
                                 </thead>
                                       @if($response)
                                       <tbody>
                                          @foreach($response as $key=>$rows)
                                             <tr class="group">
                                                <th>{{$key}}</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                             </tr>
                                             <?php  
                                                $topayamount =0;
                                                $topaycredit =0;
                                                $grandtotalcredit=0;
                                             ?>
                                             @foreach($rows as $row)
                                                <tr>
                                                   <td class="span-2">{{$row['name']}}</td>
                                                   <td class="span-2">{{$row['name']}}</td>
                                                   <td>@if($row['deposit_balance'] <0) 0 @else {{ $row['deposit_balance']}} @endif</td>
                                                   <td>{{$row['credit']}}</td>
                                                   <td>@if($row['deposit_balance'] > 0) 0 @else {{ substr($row['deposit_balance'],1) ? substr($row['deposit_balance'],1) : 0}} @endif</td>
                                                   <td>
                                                      <a class="btn mini green-stripe" href="/report/agentcredit/{{$row['id']}}">အေသးစိတ္ၾကည့္မည္</a>
                                                   </td>
                                                </tr>
                                                <?php  
                                                      $topayamount +=$row['deposit_balance'];
                                                      $topaycredit +=$row['credit'];
                                                ?>
                                             @endforeach
                                             <?php $grandtotalcredit=str_replace('-', "", $topayamount)+$topaycredit; ?>
                                             <tr class="groupfooter">
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th>ယခုအေၾကြး :{{str_replace('-', "", $topaycredit)}}</th>
                                                <th> လက္က်န္အေၾကြး :{{str_replace('-', "", $topayamount)}}</th>
                                                <th>စုစုေပါင္း  ယခုအေၾကြး : {{str_replace('-', "", $grandtotalcredit)}}</th>
                                             </tr>
                                          @endforeach
                                       </tbody>
                                       @endif
                                       
                                    
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
    
   <script type="text/javascript" src="../../../../js/jquery.dataTables.v1.10.4.min.js"></script>
   <script type="text/javascript">
      $(document).ready(function() {
          var table = $('#tblExport').DataTable({
              "columnDefs": [
                  {
                        "targets": [ 1 ],
                         "visible": false,
                  },
              ],
              "order": [],
              "displayLength": 25,
              "pagingType": "full_numbers",
              /*"drawCallback": function ( settings ) {
                  var api = this.api();
                  var rows = api.rows( {page:'current'} ).nodes();
                  var last=null;
                  var prevgroup=null;
                  api.column(1, {page:'current'} ).data().each( function ( group, i) {
                     if ( last !== group ) {
                           
                        
                        $(rows).eq( i ).before(
                              '<tr class="group"><th colspan="5">'+group+'</th></tr>'
                        );
                        
                        last = group;
                     }
                  } );

              }*/

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

 @stop
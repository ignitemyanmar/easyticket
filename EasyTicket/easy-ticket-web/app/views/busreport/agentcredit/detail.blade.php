@extends('../admin')
@section('content')
<link rel="stylesheet" href="../../../../css/jquery.dataTables.v1.10.4.css" />
<link rel="stylesheet" type="text/css" href="../../../../assets/chosen-bootstrap/chosen/chosen.css" />
<link rel="stylesheet" type="text/css" href="../../../../assets/bootstrap-datepicker/css/datepicker.css" />
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
   .noti{color:red !important; font-weight: bold;}
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
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-edit"></i>အေရာင္းကုိယ္စားလွယ္ ႏွင့္ အေၾကြးစာရင္းမ်ား</h4>
                              <div class="actions">
                              </div>
                           </div>
                          
                           <div class="clear-fix">&nbsp;</div>
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
                                       <th>အမည္</th>
                                       <th>Group Header</th>
                                       <th>Date</th>
                                       <th>Voucher No</th>
                                       <th>Trip</th>
                                       <th>Amount</th>
                                       <th>Receivable (Debit)</th>
                                       <th>Receipt(Credit)</th>
                                       <th>Balance</th>
                                    </tr>
                                 </thead>
                                 
                                 <tbody>
                                    @if(count($response)>0)
                                      @foreach($response as $rows)
                                          <?php $i=0; $balanceforward=0; $subbalance=0; $groupdate=null;?>
                                          @foreach($rows->transactions as $rowvalues)
                                            @if($i==0)
                                             <?php $balanceforward +=$rowvalues['balanceforward']; ?>
                                            @else
                                              <?php $balanceforward=0; ?>
                                            @endif
                                            @if ( $groupdate !== $rowvalues['pay_date'] && $rowvalues['receipt'] > 0 )
                                                <tr>
                                                   <th></th>
                                                   <th>{{$rows['name']}} <b style="float:right;">Balance Forward : {{number_format( str_replace("-",'',$rows['balanceforward']) , 0 , '.' , ',' )}}</b></th>
                                                   <th>Received from : {{$rows['name']}} </th>
                                                   <th>{{$rowvalues['receipt']}}</th>
                                                   <th>-</th>
                                                   <th>-</th>
                                                   <th>-</th>
                                                   <th>-</th>
                                                   <th>-</th>
                                                </tr>
                                                <?php $groupdate=$rowvalues['pay_date']; ?>
                                            @endif

                                            @if($rowvalues['receivable'] >0 && $rowvalues['receipt'] < 0 )
                                            @else
                                              <tr>
                                                 <td>{{$rows['name']}}</td>
                                                 <td>{{$rows['name']}} <b style="float:right;">Balance Forward : {{number_format( str_replace("-",'',$rows['balanceforward']) , 0 , '.' , ',' )}}</b></td>
                                                 <td>{{date('d/m/Y',strtotime($rowvalues['pay_date']))}}</td>
                                                 <td>{{$rowvalues['voucher_no']}}</td>
                                                 <td>{{$rowvalues['trip']}} [ {{$rowvalues['class']}} ({{$rowvalues['time']}})]</td>
                                                 <td>{{number_format( $rowvalues['receivable'] , 0 , '.' , ',' )}}</td>
                                                 <td>{{number_format( $rowvalues['receivable'] , 0 , '.' , ',' )}}</td>
                                                 <td>{{number_format( $rowvalues['receipt'] , 0 , '.' , ',' )}}</td>
                                                 <td> <span @if( $rowvalues['closing_balance'] < 0)class="noti" @endif>{{number_format( str_replace("-",'',$rowvalues['closing_balance']) , 0 , '.' , ',' )}}</span></td>
                                              </tr>
                                            @endif
                                            <?php $i++; ?>
                                          @endforeach
                                      @endforeach
                                    @endif

                                    <!-- <tr>
                                       <td>ABC Sh(1)</td>
                                       <td>ABC Sh(1)</td>
                                       <td>14/12/2014</td>
                                       <td>V00001238</td>
                                       <td>Yangon-Mandalay</td>
                                       <td>{{number_format( 500000 , 0 , '.' , ',' )}}</td>
                                       <td>{{number_format( 500000 , 0 , '.' , ',' )}}</td>
                                       <td>{{number_format( 400000 , 0 , '.' , ',' )}}</td>
                                       <td>{{number_format( 300000 , 0 , '.' , ',' )}}</td>
                                    </tr> -->
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
    
   <script type="text/javascript" src="../../../../assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
   <script type="text/javascript" src="../../../../assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
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
              "drawCallback": function ( settings ) {
                  var api = this.api();
                  var rows = api.rows( {page:'current'} ).nodes();
                  var last=null;
                  var prevgroup=null;
                  api.column(1, {page:'current'} ).data().each( function ( group, i) {
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
          $('#tblExport tbody').on( 'click', 'tr.group', function () {
              var currentOrder = table.order()[0];
              if ( currentOrder[0] === 0 && currentOrder[1] === 'asc' ) {
                  table.order( [ 0, 'desc' ] ).draw();
              }
              else {
                  table.order( [ 0, 'asc' ] ).draw();
              }
          } );


         var datestatus=$('#alldate').val();
         checkdatestatus(datestatus);
         
         $('#alldate').change(function(){
            var datestatus=$(this).val();
            checkdatestatus(datestatus);
            
         });

        
         
         $('chosen').chosen();
         $('#agentgroup').change(function(){
            var agentgroup_id=$(this).val();
            if(agentgroup_id=="All")
            {
               agentgroup_id=0;
            }
            var result='<select name="agent_id" class="m-wrap span12 chosen">';
                  result+='<option value="All">All</option>';
            $('#agent_id').html('');
            $('#agent_id').addClass('loader');
            $.get('/agentbranches/'+agentgroup_id,function(data){
               for(var i=0; i<data.length; i++){
                  result +='<option value="'+data[i].id+'">'+data[i].name+'</option>';
               }
               result +='</select>';
               $('#agent_id').removeClass('loader');
               $('#agent_id').html(result);
               $('.chosen').chosen();
            });
         });
      } );

      function checkdatestatus(datestatus)
      {
         if(datestatus=="All"){
            $('#daterange').hide();
            $('#daterange1').hide();
         }else{
            $('#daterange').show();
            $('#daterange1').show();
         }
      }
   </script>

 @stop
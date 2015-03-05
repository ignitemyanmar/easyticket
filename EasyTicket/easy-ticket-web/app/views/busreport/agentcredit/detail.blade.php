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
                                       <th>#</th>
                                       <th>Operator Name</th>
                                       <th>Date</th>
                                       <th>အမည္</th>
                                       <!-- <th>Group Header</th> -->
                                       <th>Voucher No</th>
                                       <th>Trip</th>
                                       <th>Amount</th>
                                       <th>Receivable (Debit)</th>
                                       <th>Receipt(Credit)</th>
                                       <th>Balance</th>
                                    </tr>
                                    @if(count($response)>0)
                                        <tr>
                                          <td colspan="10">Balance Forward : <b @if( $response[0]->balanceforward < 0)class="noti" @endif>{{str_replace('-','',$response[0]->balanceforward)}}</b></td>
                                        </tr>
                                    @endif
                                 </thead>
                                 
                                 <tbody>
                                    @if(count($response)>0)
                                      @foreach($response as $key=>$rows)
                                          
                                          <tr>
                                            <td>{{++$key}}</td>
                                            <td>{{$rows->operator_name}}</td>
                                            <td>{{date('d/m/Y',strtotime($rows['pay_date']))}}</td>
                                            <td>{{$rows['agent_name']}}</td>
                                            @if($rows->payment==0)
                                            <td>{{$rows['order_ids']}}</td>
                                            <td>{{$rows['trip']}} [ {{$rows['class']}} ({{$rows['time']}})]</td>
                                            <td>{{number_format( $rows['total_ticket_amt'] - $rows['free_ticketamount'] , 0 , '.' , ',' )}}</td>
                                            <td>{{number_format( $rows['total_ticket_amt'] - $rows['free_ticketamount'] , 0 , '.' , ',' )}}</td>
                                            @else
                                              <td>-</td>
                                              <td>-</td>
                                              <td>-</td>
                                              <td>-</td>
                                            @endif
                                            <td>{{number_format( $rows['payment'] , 0 , '.' , ',' )}}</td>
                                            <td> <span @if( $rows['closing_balance'] < 0)class="noti" @endif>{{number_format( str_replace("-",'',$rows['closing_balance']) , 0 , '.' , ',' )}}</span></td>
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
    
   <script type="text/javascript" src="../../../../assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
   <script type="text/javascript" src="../../../../assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
   <script type="text/javascript" src="../../../../js/jquery.dataTables.v1.10.4.min.js"></script>
   
   <script type="text/javascript">
      $(document).ready(function() {
          var table = $('#tblExport').DataTable({
              "columnDefs": [
                  {/*
                        "targets": [ 1 ],
                         "visible": false,*/
                  },
              ],
              "order": [0,'asc'],
              "displayLength": 25,
              "pagingType": "full_numbers",
              "drawCallback": function ( settings ) {
                  var api = this.api();
                  var rows = api.rows( {page:'current'} ).nodes();
                  var last=null;
                  var prevgroup=null;

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
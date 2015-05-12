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
      <form class="horizontal-form" action="#" id="codenoform">
         <div id="portlet-agentcodeform" class="modal hide">
            <div class="modal-header">
               <button data-dismiss="modal" class="close" type="button"></button>
               <h3>Create / Update Code No</h3>
            </div>
            <div class="modal-body">
               <input type="hidden" value="" name="agent_id" id="agent_id">
               <input type="hidden" value="" id="hdcodeid">
               <input type="hidden" value="{{$myApp->v_access_token}}" id="access_token">
               <div class="control-group">
                  <label class="control-label">Code No</label>
                  <div class="controls">
                     <input type="text" class="span6" name="code_no" id="code_no">
                  </div>
               </div>
               <br>
               <div class="control-group">
                  <!-- <div class="btn-group"> -->
                   <button type="button" class="btn green pull-left" id="btnupdate">
                       Save <i class='m-icon-swapright m-icon-white'></i>
                   </button>
                   <div class="span4">
                     <div class="loader hide"></div>
                   </div>
                     <!-- <input type="submit" class="btn green" value="Save"> -->
                       <!-- <i class="icon-bullhorn"></i>  -->
                     <!-- </button> -->
                  <!-- </div> -->
               </div>
            </div>
         </div>
      </form>

      <div class="page-content">
         <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->
               <div class="row-fluid">
                  <div class="span12">
                     
                     <h3 class="page-title">
                        အေရာင္းကုိယ္စားလွယ္မ်ား          
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}&access_token={{Auth::user()->access_token}}">ပင္မစာမ်က္ႏွာ</a> 
                           <i class="icon-angle-right"></i>
                        </li>
                        <li><a href="#">အေရာင္းကုိယ္စားလွယ္မ်ား</a></li>
                        
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
                              <h4><i class="icon-th-list"></i>အေရာင္းကုိယ္စားလွယ္မ်ား</h4>
                           </div>
                           <div class="portlet-body">
                              <div class="clearfix">
                                 <div class="btn-group">
                                    <a href="/agents/create?access_token={{Auth::user()->access_token}}">
                                    <button id="" class="btn green">
                                    အသစ္ထည့္မည္ <i class="icon-plus"></i>
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
                              <table class="table table-striped table-hover table-bordered" id="tblExportExcel" style="display:none;">
                                 <thead>
                                       <th>Group</th>
                                       <th>အမည္</th>
                                       <th>ဖုန္းနံပါတ္</th>
                                       <th>လိပ္စာ</th>
                                       <th>ေကာ္္မစ္ရွင္ ႏႈန္း</th>
                                       <th>Owner</th>
                                 </thead>
                                 <tbody>
                                    @if(count($response)==0)
                                       There is no record in database.
                                    @else
                                    @endif
                                 
                                    @foreach($response as $key=>$rows)
                                       <!-- <tr class="heading">
                                          <td colspan="6">{{$key}}</td>
                                       </tr> -->
                                       @foreach($rows as $agent)
                                          <tr>
                                             <td>{{$key}}</td>
                                             <td>{{$agent['name']}}</td>
                                             <td>{{$agent['phone']}}</td>
                                             <td><div class="wordwrap">{{$agent['address']}}</div></td>
                                             <td>{{$agent['commission']}} @if($agent['commission_id']==2) % @endif</td>
                                             <td>@if($agent['owner']==1) Owner @else - @endif</td>
                                          </tr>
                                       @endforeach
                                    @endforeach
                                    
                                 </tbody>
                              </table>

                              <table class="table table-striped table-hover table-bordered" id="tblExport">
                                 <thead>
                                       <th>Group</th>
                                       <th>အမည္</th>
                                       <th>Code No</th>
                                       <th>ဖုန္းနံပါတ္</th>
                                       <th>လိပ္စာ</th>
                                       <th>ေကာ္္မစ္ရွင္ ႏႈန္း</th>
                                       <th>Owner</th>
                                       <th class="span2">&nbsp;</th>
                                       <!-- <th class="span1">ဖ်က္ရန္</th> -->
                                 </thead>
                                 <tbody>
                                    @if(count($response)==0)
                                       There is no record in database.
                                    @else
                                    @endif
                                 
                                    @foreach($response as $key=>$rows)
                                       <!-- <tr class="heading">
                                          <td colspan="6">{{$key}}</td>
                                       </tr> -->
                                       @foreach($rows as $agent)
                                          <tr>
                                             <td>{{$key}}</td>
                                             <td>{{$agent['name']}}</td>
                                              <td>
                                                <div id="{{$agent['id']}}">
                                                  {{$agent['code_no']}}
                                                </div>
                                              </td>
                                             <td>{{$agent['phone']}}</td>
                                             <td><div class="wordwrap">{{$agent['address']}}</div></td>
                                             <td>{{$agent['commission']}} @if($agent['commission_id']==2) % @endif</td>
                                             <td>@if($agent['owner']==1) Owner @else - @endif</td>
                                             <td>
                                                <div class="btn-group">
                                                    <a class="btn blue" href="#" data-toggle="dropdown">
                                                    <i class="icon-cog"></i> Settings
                                                    <i class="icon-angle-down"></i>
                                                    </a>
                                                    <ul class="dropdown-menu"> 
                                                      <li>
                                                        <a href="/agent-update/{{ $agent['id'] }}?access_token={{Auth::user()->access_token}}"><i class="icon-pencil"></i> ျပင္ရန္</a>
                                                      </li>
                                                      <li>
                                                        <a href="#portlet-agentcodeform" data-toggle="modal" class="config btnagentupdate" data-agentid="{{$agent['id']}}" data-codeid="{{$agent['code_no']}}">
                                                           <i class="icon-edit"></i> &nbsp;Code No 
                                                        </a>
                                                       </li>
                                                      <li>
                                                        <a href="deleteagent/{{ $agent['id'] }}?access_token={{Auth::user()->access_token}}"><i class="icon-trash"></i>ဖ်က္ရန္</a>
                                                      </li>
                                                      <li>
                                                        <a href="agent-salelist/{{ $agent['id'] }}?access_token={{Auth::user()->access_token}}"><i class="icon-list"></i>အေရာင္းစာရင္းမ်ား</a>
                                                      </li>
                                                      <li class="divider"></li>
                                                    </ul>
                                                </div>
                                              </td>
                                          </tr>
                                       @endforeach
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
   <script type="text/javascript" src="../../../js/jquery.battatech.excelexport.min.js"></script>
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
                              '<tr class="group"><td colspan="7">'+group+'</td></tr>'
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
      } );
      
        $('.btnagentupdate').click(function(){
           var agent_id=$(this).data('agentid');
           var code_id=$(this).data('codeid');
           $('#agent_id').val(agent_id);
           $('#hdcodeid').val(code_id);
           $('#code_no').val(code_id);
        });

        $('#btnupdate').click(function(){
           $('.loader').removeClass('hide');
           var agent_id    =$('#agent_id').val();
           var code_no     =$('#code_no').val();
           var codeid     =$('#hdcodeid').val();
           var access_token=$('#access_token').val();
           var status=1;
           $.ajax({
             type:'POST',
             url:'/updateagent/'+agent_id,
             data:{access_token:access_token, code_no: code_no, status:status }
           }).done(function(result){
             if(result=="Successfully update."){
               $('#'+agent_id).html(code_no);
               $('#portlet-agentcodeform').modal('hide');
               $('.loader').addClass('hide');
             }else{
               alert(result);
             }
           });
        });


      $("#btnExportExcel").click(function () {
               var filename="AgentList";
               var uri =$("#tblExportExcel").btechco_excelexport({
                      containerid: "tblExportExcel"
                     , datatype: $datatype.Table
                     , returnUri: true
                  });
               $(this).attr('download', filename+'.xls').attr('href', uri).attr('target', '_blank');
      });


   </script>
@stop
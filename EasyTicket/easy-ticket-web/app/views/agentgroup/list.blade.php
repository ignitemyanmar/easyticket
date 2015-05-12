@extends('../admin')
@section('content')
   <link rel="stylesheet" href="../../../../css/jquery.dataTables.v1.10.4.css" />
   <link rel="shortcut icon" href="favicon.ico" />
   <!-- BEGIN PAGE -->
      <form class="horizontal-form" action="#" id="codenoform">
         <div id="portlet-agentcodeform" class="modal hide">
            <div class="modal-header">
               <button data-dismiss="modal" class="close" type="button"></button>
               <h3>Create / Update Code No</h3>
            </div>
            <div class="modal-body">
               <input type="hidden" value="" name="agentgroup_id" id="agentgroup_id">
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
                     <!-- BEGIN PAGE TITLE & BREADCRUMB-->        
                     <h3 class="page-title">
                        အေရာင္းကုိယ္စားလွယ္ အုပ္စုမ်ား            
                        <small></small>
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <i class="icon-angle-right"></i>
                        </li>
                        <li><a href="#">အေရာင္းကုိယ္စားလွယ္ အုပ္စုမ်ား</a></li>
                        
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
                              <h4><i class="icon-edit"></i>Agent Group List</h4>
                           </div>
                           <div class="portlet-body">
                              <div class="clearfix">
                                 <div class="btn-group">
                                    <a href="/agentgroup/create?{{$myApp->access_token}}">
                                    <button id="" class="btn green">
                                    Add New <i class="icon-plus"></i>
                                    </button>
                                    </a>
                                 </div>
                                 
                              </div>
                              @if(Session::has('message'))
                                 <?php $message=Session::get('message'); ?>
                                 @if($message['status']='0')
                                 <div class="alert alert-info">
                                    <button class="close" data-dismiss="alert"></button>
                                    {{$message['info']}}
                                 </div>
                                 @else
                                 <div class="alert alert-success">
                                    <button class="close" data-dismiss="alert"></button>
                                    <strong>Success!</strong> {{$message['info']}}
                                 </div>
                                 @endif
                              @endif
                              <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                                 <thead>
                                    <tr>
                                       <th class="span6">Name</th>
                                       <th class="span2">Code No</th>
                                       <!-- <th class="span3">Address</th> -->
                                       <th class="span3">Branches</th>
                                       <!-- <th class="span1">Edit</th> -->
                                       <th class="span1">Actions</th>
                                       <!-- <th class="span1">Delete</th> -->
                                    </tr>
                                 </thead>
                                 <tbody>
                                    @if(count($response)==0)
                                       There is no record in database.
                                    @else
                                    @endif
                                 
                                    @foreach($response as $agent)
                                                <tr>
                                                   <td>{{$agent['name']}} @if(Auth::user()->role==3 || Auth::user()->role==9) @if($agent->operator) [ {{$agent->operator->name}} ] @endif @endif</td>
                                                   <td>
                                                      <div id="{{$agent['id']}}">
                                                         {{$agent['code_no']}}
                                                      </div>
                                                   </td>
                                                   <td><a href="/agentgroupchildlist/{{ $agent['id'] }}?access_token={{Auth::user()->access_token}}"  class="btn blue button-submit">View Branches <i class="m-icon-swapright m-icon-white"></i></a><br><br></td>
                                                   <td>
                                                         <div class="btn-group">
                                                            <a class="btn blue" href="#" data-toggle="dropdown">
                                                            <i class="icon-cog"></i> Settings
                                                            <i class="icon-angle-down"></i>
                                                            </a>
                                                            <ul class="dropdown-menu"> 
                                                               <li><a href="/agentgroup-update/{{ $agent['id'] }}?access_token={{Auth::user()->access_token}}"><i class="icon-pencil"></i> Edit</a></li>
                                                               <li>
                                                                  <a href="#portlet-agentcodeform" data-toggle="modal" class="config btnagentupdate" data-agentid="{{$agent['id']}}" data-codeid="{{$agent['code_no']}}">
                                                                     <i class="icon-edit"></i> &nbsp;Code No 
                                                                  </a>
                                                               </li>
                                                               <li><a href="/agentgroup-actions/{{ $agent['id'] }}?access_token={{Auth::user()->access_token}}"><i class="icon-plus"></i> Entry Payments</a></li>
                                                               <li><a href="/deleteagentgroup/{{ $agent['id'] }}?access_token={{Auth::user()->access_token}}"><i class="icon-remove"></i> Delete</a></li>
                                                               <li class="divider"></li>
                                                               <!-- <li><a href="#"><i class="i"></i> Full Settings</a></li> -->
                                                            </ul>
                                                         </div>
                                                   </td>
                                                   <!-- <td style="text-align:center;">
                                                         <a href="/agentgroup-actions/{{ $agent['id'] }}"  class="btn blue-stripe button-submit">Entry</a><br><br>
                                                   </td>
                                                   <td style="text-align:center;">
                                                         <a href="deleteagentgroup/{{ $agent['id'] }}"   class="btn red-stripe button-submit">Delete</a>
                                                   </td> -->
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
         var table = $('.table').DataTable({
              "columnDefs": [
                  // { "visible": false, "targets": 0 }
              ],
              "order": [[ 0, 'asc' ]],
              "lengthMenu": [[25, 50, -1], [25, 50, "All"]],
              "pagingType": "full_numbers",
              "drawCallback": function ( settings ) {
                  var api = this.api();
                  var rows = api.rows( {page:'current'} ).nodes();
                  var last=null;
              }
         } );

         $('.btnagentupdate').click(function(){
           var agentgroup_id=$(this).data('agentid');
           var code_id=$(this).data('codeid');
           $('#agentgroup_id').val(agentgroup_id);
           $('#hdcodeid').val(code_id);
           $('#code_no').val(code_id);
         });

         $('#btnupdate').click(function(){
           $('.loader').removeClass('hide');
           var agentgroup_id    =$('#agentgroup_id').val();
           var code_no     =$('#code_no').val();
           var codeid     =$('#hdcodeid').val();
           var access_token=$('#access_token').val();
           var status=1;
           $.ajax({
             type:'POST',
             url:'/updateagentgroup/'+agentgroup_id,
             data:{access_token:access_token, code_no: code_no, status:status }
           }).done(function(result){
             if(result=="Successfully update."){
               $('#'+agentgroup_id).html(code_no);
               $('#portlet-agentcodeform').modal('hide');
               $('.loader').addClass('hide');
             }else{
               alert(result);
             }
           });
         });

      } );
   </script>
@stop
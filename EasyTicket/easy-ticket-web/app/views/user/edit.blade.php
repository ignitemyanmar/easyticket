@extends('../admin')
@section('content')
{{HTML::style('../../assets/chosen-bootstrap/chosen/chosen.css')}}
<link rel="stylesheet" type="text/css" href="../../assets/uniform/css/uniform.default.css" />
<!-- BEGIN PAGE -->  
   <div class="page-content">
      <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
               <div class="row-fluid">
                  <div class="span12">
                     <!-- END BEGIN STYLE CUSTOMIZER -->   
                     <h3 class="page-title">
                        User အခ်က္အလက္မ်ား ျပင္ရန္
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="/user-list">User List</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">User အခ်က္အလက္မ်ား ျပင္ရန္</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <!-- <form action="/addagent" class="horizontal-form" method= "post" enctype="multipart/form-data"> -->
                     <form id="addnew-form" class="form-horizontal" action = "/user-update/{{$user_info->id}}" method= "post" enctype="">    
                        <input type="hidden" value="{{$myApp->v_access_token}}" name="access_token">
                        <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-user"></i>User အခ်က္အလက္မ်ား ျပင္ရန္</h4>
                              <div class="actions">
                              </div>
                           </div>
                           <div class="portlet-body">
                              <div class="row-fluid">
                                 <div class="span12">
                                    <div class="control-group">
                                       <label class="control-label" for="name">User Name</label>
                                       <div class="controls">
                                          <input type="text" name="name" class="span8" value="{{$user_info->name}}">
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <div class="row-fluid">
                                 <div class="span12">
                                    <label class="control-label">Email</label>
                                    <div class="control-group">
                                       <div class="controls">
                                          <input type="email" name="email" class="span8" value="{{$user_info->email}}">
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <div class="row-fluid">
                                 <div class="span12">
                                    <label class="control-label">Password</label>
                                    <div class="control-group">
                                       <div class="controls">
                                          <input type="password" name="password" class="span8">
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <div class="row-fluid">
                                 <div class="span12">
                                    <label class="control-label">Position (Role)</label>
                                    <div class="control-group">
                                       <div class="controls">
                                          <select name="role" class="chosen span4" id="role">
                                             @foreach($response['role'] as $key=>$role)
                                                <option value="{{$key}}" @if($user_info->role == $key) selected @endif>{{$role}}</option>
                                             @endforeach
                                          </select>
                                          <input type="hidden" value="operator" name="type">
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <div class="row-fluid" id="agentgroup_frame">
                                 <div class="span12">
                                    <label class="control-label">Agent Groups</label>
                                    <div class="control-group">
                                       <div class="controls">
                                          <select name="agentgroup_id" class="chosen span4" id="agentgroup">
                                             @foreach($response['agentgroup'] as $agentgroup)
                                                <option value="{{$agentgroup->id}}" @if($agentgroup->id == AgentGroup::whereuser_id($user_info->id)->pluck('id')) selected @endif>{{$agentgroup->name}}</option>
                                             @endforeach
                                          </select>
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <div class="control-group">
                                 <label class="control-label">Group User</label>
                                 <div class="controls">
                                    <label class="radio">
                                    <div id="uniform-undefined" class="radio"><span><input style="opacity: 0;" name="group_user" value="group" type="radio" class="groupuser" @if($user_info->group == "group") checked="" @endif></span></div>
                                     Group
                                    </label>
                                    <label class="radio">
                                    <div id="uniform-undefined" class="radio"><span class=""><input style="opacity: 0;" name="group_user" value="undergroup" class="groupuser" @if($user_info->group == "undergroup") checked="" @endif  type="radio"></span></div>
                                    Under Group
                                    </label>  
                                     
                                 </div>
                              </div>
                              <div class="row-fluid" id="undergroup">
                                 <div class="span12">
                                    <label class="control-label">Under Group User</label>
                                    <div class="control-group">
                                       <div class="controls">
                                          <select name="groupuser_id" class="chosen span4">
                                             @foreach($response['operator_group'] as $group)
                                                <option value="{{$group->id}}" @if($user_info->undergroup_id == $group->id) selected @endif>{{$group->user->name}}</option>
                                             @endforeach
                                          </select>
                                          <input type="hidden" value="operator" name="type">
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <div class="cleardiv">&nbsp;</div>
                              <div class="controls">
                                    <a href="/user-list">
                                    <button type = "button" class="btn icn-only green">
                                       <i class="m-icon-swapleft m-icon-white"></i>
                                       Cancel
                                    </button>
                                    </a>
                                    <button type = "submit" class="btn icn-only green">
                                       Save
                                       <i class="m-icon-swapright m-icon-white"></i>
                                    </button>
                                 <!-- <button style="display: inline-block;" type="submit" class="btn green button-submit">Submit</button> -->
                              </div>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            <!-- END PAGE CONTENT-->         
         </div>
      <!-- END PAGE CONTAINER-->
   </div>
<!-- END PAGE --> 
   {{HTML::script('../../assets/chosen-bootstrap/chosen/chosen.jquery.min.js')}}
   <script type="text/javascript" src="../../assets/uniform/jquery.uniform.min.js"></script>
   <script type="text/javascript">
      //init for check group user or under group user
      $(function(){
         var groupuser=$('.groupuser:checked').val();
         checkgroupornot(groupuser);

         var role=$('#role').val();
         showhideagentgroup(role);
      });

      //for handle click radio optional
      $('.groupuser').click(function(){
         var groupuser=$(this).val();
         checkgroupornot(groupuser);
      })

      //for check group user or under group user
      function checkgroupornot(groupuser){
         if(groupuser=="group"){
            $('#undergroup').css({'opacity':0});
         }else{
            $('#undergroup').css({'opacity':1});
            $('.chosen').chosen();
         }
      }

      $('#role').change(function(){
         var role=$(this).val();
         showhideagentgroup(role);
      });

      function showhideagentgroup(role){
         if(role==3){
            $('#agentgroup_frame').css({'opacity':1});
         }else{
            $('#agentgroup_frame').css({'opacity':0});
         }
      }
   </script>
@stop
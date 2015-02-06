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
                      
                     <h3 class="page-title">
                        Edit AgentGroup
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">AgentGroup</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">Edit AgentGroup</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span8" data-tablet="span8" data-desktop="span8">
                     <form id="addnew-form" class="horizontal-form" action ="/updateagentgroup/{{$agentgroup->id}}" method= "post">    
                        <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-user"></i>AgentGroup Information</h4>
                              <div class="actions">
                              </div>
                           </div>
                           <div class="portlet-body">
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="name">AgentGroup Name</label>
                                       <div class="controls">
                                          <input name="name" class="m-wrap span12" required="required" value="{{$agentgroup['name']}}" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <input type="hidden" value="{{$myApp->v_access_token}}" name="access_token">
                              
                              <div class="cleardiv">&nbsp;</div>
                              <div class="controls">
                                    <button type = "submit" class="btn green button-submit" id="btn_create" />Save <i class="m-icon-swapright m-icon-white"></i></button>
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
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
@stop
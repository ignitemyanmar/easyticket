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
                        Create New Agent Group
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">Agent Group</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">Add Agent Group</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span8" data-tablet="span8" data-desktop="span8">
                     <form id="addnew-form" class="horizontal-form" action = "/addagentgroup" method= "post" enctype="multipart/form-data">    
                        <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-user"></i>Agent Group Information</h4>
                              <div class="actions">
                              </div>
                           </div>
                           <input type="hidden" value="{{Auth::user()->access_token}}" name="access_token">
                           <div class="portlet-body">
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="name">Agent Group Name</label>
                                       <div class="controls">
                                          <input name="agentgroup" type="text" required="required" class="m-wrap span12">
                                          <!-- <input name="name" class="m-wrap span12" placeholder="Mg Mg" type="text"> -->
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="cleardiv">&nbsp;</div>
                              <div class="controls">
                                    <div class="large-2 column">&nbsp;</div>
                                    <div class="large-10 column">
                                       <input type = "submit" value = "Save" class="btn green button-submit" id="btn_create" />
                                       <br>
                                    </div>
                              </div>
                              
                              <!-- <div class="controls"> -->
                                    <!-- <input type = "submit" value = "Save" class="btn green button-submit" id="btn_create" /> -->
                                 <!-- <button style="display: inline-block;" type="submit" class="btn green button-submit">Submit</button> -->
                              <!-- </div> -->
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
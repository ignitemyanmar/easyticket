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
                        User အသစ္ ထည့္သြင္းရန္
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
                        <li><a href="#">User အသစ္ ထည့္သြင္းရန္</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <!-- <form action="/addagent" class="horizontal-form" method= "post" enctype="multipart/form-data"> -->
                     <form id="addnew-form" class="form-horizontal" action = "/user-create" method= "post" enctype="">    
                        <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-user"></i>User အသစ္ ထည့္သြင္းရန္</h4>
                              <div class="actions">
                              </div>
                           </div>
                           <div class="portlet-body">
                              <div class="row-fluid">
                                 <div class="span12">
                                    <div class="control-group">
                                       <label class="control-label" for="name">User Name</label>
                                       <div class="controls">
                                          <input type="text" name="name" class="span8">
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <div class="row-fluid">
                                 <div class="span12">
                                    <label class="control-label">Email</label>
                                    <div class="control-group">
                                       <div class="controls">
                                          <input type="email" name="email" class="span8">
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
@stop
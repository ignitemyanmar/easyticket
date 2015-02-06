@extends('../admin')
@section('content')
{{HTML::style('../../assets/chosen-bootstrap/chosen/chosen.css')}}
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
                        Add New Bus Class
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">Bus Class</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">Add Bus Class</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span8" data-tablet="span8" data-desktop="span8">
                     <form id="addnew-form" class="horizontal-form" action = "/addbusclass" method= "post" enctype="multipart/form-data">    
                        <input type="hidden" name="access_token" value="{{$myApp->v_access_token}}">
                        <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-user"></i>Bus Class Information</h4>
                              <div class="actions">
                              </div>
                           </div>
                           <div class="portlet-body">
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="name">Bus Class Name</label>
                                       <div class="controls">
                                          <input name="name" class="m-wrap span12"  type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="operator">Operator Name</label>
                                       <div class="controls">
                                             <select name="operator" class="m-wrap span12 chosen">
                                                @foreach($operator as $rows)
                                                   <option value="{{$rows->id}}">{{$rows->name}}</option>
                                                @endforeach   
                                             </select> 
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              
                              <div class="cleardiv">&nbsp;</div>
                              <div class="controls">
                                    <input type = "submit" value = "Save" class="btn green button-submit" id="btn_create" />
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

@stop
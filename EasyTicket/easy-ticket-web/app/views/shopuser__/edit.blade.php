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
                     <!-- BEGIN STYLE CUSTOMIZER -->
                     <div class="color-panel hidden-phone">
                        <div class="color-mode-icons icon-color"></div>
                        <div class="color-mode-icons icon-color-close"></div>
                        <div class="color-mode">
                           <p>THEME COLOR</p>
                           <ul class="inline">
                              <li class="color-black current color-default" data-style="default"></li>
                              <li class="color-blue" data-style="blue"></li>
                              <li class="color-brown" data-style="brown"></li>
                              <li class="color-purple" data-style="purple"></li>
                              <li class="color-white color-light" data-style="light"></li>
                           </ul>
                           <label class="hidden-phone">
                           <input type="checkbox" class="header" checked value="" />
                           <span class="color-mode-label">Fixed Header</span>
                           </label>                    
                        </div>
                     </div>
                     <!-- END BEGIN STYLE CUSTOMIZER -->   
                     <h3 class="page-title">
                        Add Shop User
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">Shop User</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">Add Shop User</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span8" data-tablet="span8" data-desktop="span8">
                     <form id="addnew-form" class="horizontal-form" action = "/shopuser/update/{{$response->id}}" method= "post" enctype="multipart/form-data">    
                        <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-user"></i>Shop User Information</h4>
                              <div class="actions">
                              </div>
                           </div>
                           <div class="portlet-body">
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="name">Shop User Name</label>
                                       <div class="controls">
                                          <input name="name" class="m-wrap span12" required="required" value="{{$response->name}}" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="email">Email</label>
                                       <div class="controls">
                                          <input name="email" class="m-wrap span12" required="required" value="{{$response->email}}" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>

                                 <div class="row-fluid">
                                    <div class="span6">
                                       <div class="control-group">
                                          <label class="control-label" for="password">Password</label>
                                          <div class="controls">
                                             <input name="password" class="m-wrap span12"  type="password">
                                          </div>
                                       </div>
                                    </div>
                                 </div>

                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="address">Address</label>
                                       <div class="controls">
                                          <input name="address" class="m-wrap span12" value="{{$response->address}}" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="address">Phone</label>
                                       <div class="controls">
                                          <input name="phone" class="m-wrap span12" required="required" value="{{$response->phone}}" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="address">Permit_count</label>
                                       <div class="controls">
                                          <input name="permit_count" class="m-wrap span12" required="required" value="{{$response->permit_count}}" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="address">Shop User_Life</label>
                                       <div class="controls">
                                          <select name="expired_at" required="required">
                                             @for($i=1; $i <= 10; $i++)
                                                <option value="{{$i}}" @if( $response->expired_at==$i) selected @endif>{{$i}} @if($i<= 1) - Year @else - Years @endif</option>
                                             @endfor
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
@stop
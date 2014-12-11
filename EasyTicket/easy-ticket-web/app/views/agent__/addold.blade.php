@extends('../admin')
@section('content')
{{HTML::style('../../assets/chosen-bootstrap/chosen/chosen.css')}}
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
                        အေရာင်း ကုိယ်စားလှယ် အသစ်ထည့်သွင်းြခင်း
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">အေရာင်း ကုိယ်စားလှယ်များ</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">အေရာင်း ကုိယ်စားလှယ် အသစ်ထည့်သွင်းြခင်း</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span8" data-tablet="span8" data-desktop="span8">
                     <!-- <form action="/addagent" class="horizontal-form" method= "post" enctype="multipart/form-data"> -->
                     <form id="addnew-form" class="horizontal-form" action = "/addagent" method= "post" enctype="multipart/form-data">    
                        <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-user"></i>အေရာင်း ကုိယ်စားလှယ် အချက်အလက်များ </h4>
                              <div class="actions">
                              </div>
                           </div>
                           <div class="portlet-body">
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="agentgroup">အေရာင်း ကုိယ်စားလှယ် အုပ်စု</label>
                                       <div class="controls">
                                          <select name="agentgroup_id" id='agentgroup' class="m-wrap span12">
                                             @foreach($agentgroup as $rows)
                                                <option value="{{$rows->id}}">{{$rows->name}}</option>
                                             @endforeach   
                                          </select>    
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="name">အေရာင်း ကုိယ်စားလှယ် အမည်</label>
                                       <div class="controls">
                                          <input name="name" class="m-wrap span12" placeholder="Agent Name" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="name">အီးေမးလ်</label>
                                       <div class="controls">
                                          <input name="email" class="m-wrap span12" placeholder="email" type="email" required>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="name">လျို့ဝှက်နံပါတ်</label>
                                       <div class="controls">
                                          <input name="password" class="m-wrap span12" placeholder="" type="password" required>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="address">လိပ္စာ</label>
                                       <div class="controls">
                                          <input  name="address" class="m-wrap span12" placeholder="Address" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="phone">ဖုန္းနံပါတ္</label>
                                       <div class="controls">
                                          <input  name="phone" class="m-wrap span12" placeholder="Phone" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="comission">ေကာ်မၡင် အမျိုးအစား</label>
                                       <div class="controls">
                                             <select name="comissiontype" id='comission' class="m-wrap span12">
                                                @foreach($commission as $rows)
                                                   <option value="{{$rows->id}}">{{$rows->name}}</option>
                                                @endforeach   
                                             </select> 
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="commission">ေကာ်မၡင်နုန်း</label>
                                       <div class="controls">
                                          <input  name="commission" class="m-wrap span12" placeholder="eg.100 or 10 percent" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              
                              <div class="cleardiv">&nbsp;</div>
                              <div class="controls">
                                    <input type = "submit" value = "Save" class="btn green button-submit" id="btn_create" />
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
@stop
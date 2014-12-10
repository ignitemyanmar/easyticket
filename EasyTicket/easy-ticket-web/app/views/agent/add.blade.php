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
                     <!-- END BEGIN STYLE CUSTOMIZER -->   
                     <h3 class="page-title">
                        အေရာင္းကုိယ္စားလွယ္မ်ား  အခ်က္အလက္မ်ား
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">အေရာင္းကုိယ္စားလွယ္မ်ား</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">အေရာင္းကုိယ္စားလွယ္မ်ား အသစ္ထည့္သြင္းျခင္း</a></li>
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
                              <h4><i class="icon-user"></i>အေရာင္းကုိယ္စားလွယ္ အခ်က္အလက္မ်ား </h4>
                              <div class="actions">
                              </div>
                           </div>
                           <input type="hidden" name="operator_id" value="{{$operator_id}}">
                           <div class="portlet-body">
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="name">Agent Group</label>
                                       <div class="controls">
                                          <select class="chosen span12" name="agentgroup_id">
                                             <option value="0">None</option>
                                             @foreach($agentgroup as $row)
                                             <option value="{{$row->id}}">{{$row->name}}</option>
                                             @endforeach
                                          </select>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="clear-fix">&nbsp;</div>

                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="name">အေရာင်းကုိယ်စားလှယ်အမည်</label>
                                       <div class="controls">
                                          <input name="name" class="m-wrap span12" placeholder="Agent Name" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="address">လိပ်စာ</label>
                                       <div class="controls">
                                          <input  name="address" class="m-wrap span12" placeholder="Address" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="phone">ဖုန်းနံပါတ်</label>
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

                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <div class="controls">
                                       <label>
                                          <input  name="owner" class="span3" value="1" type="checkbox" style="float:left !important;margin-left:0;"><p style="padding-top:.7rem;">Owner</p>
                                       </label>
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
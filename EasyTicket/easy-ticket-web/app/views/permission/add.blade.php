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
                        လုပ္ပုိင္ခြင့္မ်ား သတ္မွတ္ရန္
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">လုပ္ပုိင္ခြင့္မ်ား သတ္မွတ္ရန္</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">လုပ္ပုိင္ခြင့္မ်ား သတ္မွတ္ရန္</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <!-- <form action="/addagent" class="horizontal-form" method= "post" enctype="multipart/form-data"> -->
                     <form id="addnew-form" class="horizontal-form" action = "/premission-create" method= "post" enctype="">    
                        <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-user"></i>လုပ္ပုိင္ခြင့္မ်ား သတ္မွတ္ရန္</h4>
                              <div class="actions">
                              </div>
                           </div>
                           <div class="portlet-body">
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="name">Role</label>
                                       <div class="controls">
                                          <select class="chosen span12" name="role">
                                             @foreach($response['role'] as $key=>$row)
                                                <option value="{{$key}}">{{$row}}</option>
                                             @endforeach
                                          </select>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="clear-fix">&nbsp;</div>

                              <div class="row-fluid">
                                 <div class="span12">
                                    <label class="control-label">Permissions</label>
                                    <div class="control-group">
                                       <div class="controls">
                                          @foreach($response['menu'] as $key=>$permission)
                                             @if($key==0 || $key % 2==0 )
                                                <div class="row-fluid">
                                             @endif
                                             <div class="span6">
                                                <label class="checkbox">
                                                <div id="uniform-undefined" class="checker"><span><input style="opacity: 0;" value="{{$permission}}" type="checkbox" name="permission[]"></span></div> {{$permission}}
                                                </label>
                                             </div>
                                             @if(count($response['menu']) == $key+1 || $key % 2==1 )
                                                </div>
                                             @endif
                                          @endforeach
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <!-- <div class="control-group">
                                 <label class="control-label">Permissions</label>
                                 <div class="controls">
                                    @foreach($response['menu'] as $permission)
                                       <label class="checkbox">
                                       <div id="uniform-undefined" class="checker"><span><input style="opacity: 0;" value="{{$permission}}" type="checkbox" name="permission"></span></div> {{$permission}}
                                       </label>
                                    @endforeach
                                 </div>
                              </div> -->
                              
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
   <script type="text/javascript" src="../../assets/uniform/jquery.uniform.min.js"></script>
@stop
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
                  <div class="responsive span9" data-tablet="span9" data-desktop="span9">
                     <!-- <form action="/addagent" class="horizontal-form" method= "post" enctype="multipart/form-data"> -->
                     <form id="addnew-form" class="form-horizontal" action = "/addagent" method= "post" enctype="multipart/form-data">    
                        <input type="hidden" name="access_token" value="{{$myApp->v_access_token}}">
                        <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-user"></i>အေရာင္းကုိယ္စားလွယ္ အခ်က္အလက္မ်ား </h4>
                              <div class="actions">
                              </div>
                           </div>
                           <input type="hidden" name="operator_id" value="{{$operator_id}}">
                           <div class="portlet-body">
                              <div class="row-fluid">
                                 <div class="span9">
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
                                 <div class="span9">
                                    <div class="control-group">
                                       <label class="control-label" for="name">အေရာင်းကုိယ်စားလှယ်အမည်</label>
                                       <div class="controls">
                                          <input name="name" class="m-wrap span12" placeholder="Agent Name" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span9">
                                    <div class="control-group">
                                       <label class="control-label" for="address">လိပ်စာ</label>
                                       <div class="controls">
                                          <input  name="address" class="m-wrap span12" placeholder="Address" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span9">
                                    <div class="control-group">
                                       <label class="control-label" for="phone">ဖုန်းနံပါတ်</label>
                                       <div class="controls">
                                          <input  name="phone" class="m-wrap span12" placeholder="Phone" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <div class="control-group">
                                 <label class="control-label">ေကာ်မစ်ၡင်</label>
                                 <div class="controls">
                                    <label class="radio">
                                       <div id="uniform-undefined" class="radio">
                                          <span class="">
                                             <div id="uniform-undefined" class="radio">
                                                <span class="">
                                                   <input style="opacity: 0;" type="radio" name="rdo_commission_type" value="0" class="chkcommission" checked="">
                                                   <!-- <input style="opacity: 0;" name="day" class="departuredays" value="daily" checked="" type="radio"> -->
                                                </span>
                                             </div>
                                          </span>
                                       </div>
                                       ခရီးစဥ်အလုိက်သတ်မှတ်မည်
                                    </label>
                                    <label class="radio">
                                       <div id="uniform-undefined" class="radio">
                                          <span class="">
                                             <div id="uniform-undefined" class="radio">
                                                <span class="checked">
                                                   <input type="radio" name="rdo_commission_type" value="1" class="chkcommission" >
                                                </span>
                                             </div>
                                          </span>
                                       </div>
                                       အေရာင်းကုိယ်စားလှယ်အလုိက်သတ်မှတ်မည်။
                                    </label>
                                 </div>
                              </div>

                              <div id="commission_frame" style="display:none">
                                 <div class="row-fluid">
                                    <div class="span9">
                                       <div class="control-group">
                                          <label class="control-label" for="comission">ေကာ်မစ်ၡင် အမျိုးအစား</label>
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
                                    <div class="span9">
                                       <div class="control-group">
                                          <label class="control-label" for="commission">ေကာ်မစ်ၡင်နုန်း</label>
                                          <div class="controls">
                                             <input  name="commission" class="m-wrap span12" placeholder="eg.100 or 10 percent" type="text">
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <div class="row-fluid">
                                 <div class="span9">
                                    <div class="control-group">
                                       <label class="control-label">Owner</label>
                                       <div class="controls">
                                          <label class="checkbox">
                                          <div id="uniform-undefined" class="checker"><span class=""><input name="owner" style="opacity: 0;" value="" type="checkbox"></span></div> &nbsp;
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
   <script type="text/javascript" src="../../assets/uniform/jquery.uniform.min.js"></script>
   <script type="text/javascript">
      $(function(){
         choosecommission('.chkcommission');
      });

      function choosecommission(chkcommission){
        var val_radio=$(chkcommission).val();
         if(val_radio=="1"){
            $('#commission_frame').show();
         }else{
            $('#commission_frame').hide();
         }   
      }

      $('.chkcommission').each(function(){
        $(this).click(function(){
          var val_radio=$(this).val();
          if(val_radio=="1"){
             $('#commission_frame').show();
          }else{
             $('#commission_frame').hide();
          }
        });
      }); 
   </script>
@stop
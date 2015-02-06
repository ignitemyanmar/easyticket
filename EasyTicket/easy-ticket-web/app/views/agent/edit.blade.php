@extends('../admin')
@section('content')
<style type="text/css">
   .padding-5{padding: 5px;}
</style>
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
                        အေရာင္းကုိယ္စားလွယ္ အခ်က္အလက္ျပင္ရန္
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}">ပင္မစာမ်က္ႏွာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        
                        <li><a href="#">အေရာင္းကုိယ္စားလွယ္ အခ်က္အလက္ျပင္ရန္</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span9" data-tablet="span9" data-desktop="span9">
                     <form id="addnew-form" class="form-horizontal" action = "/updateagent/{{$response['agent']['id']}}" method= "post" enctype="multipart/form-data">    
                        <input type="hidden" value="{{$myApp->v_access_token}}" name="access_token">
                        <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-user"></i>အေရာင္းကုိယ္စားလွယ္ အခ်က္အလက္မ်ား </h4>
                              <div class="actions">
                              </div>
                           </div>
                           <div class="portlet-body">

                              <div class="row-fluid">
                                 <div class="span9">
                                    <div class="control-group">
                                       <label class="control-label" for="name">Agent Group</label>
                                       <div class="controls">
                                          <select class="chosen span12" name="agentgroup_id">
                                             <option value="0">None</option>
                                             @foreach($agentgroup as $row)
                                             <option value="{{$row->id}}" @if($row->id== $response['agent']['agentgroup_id']) selected @endif >{{$row->name}}</option>
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
                                       <label class="control-label" for="name">အမည်</label>
                                       <div class="controls">
                                          <?php $agent_name=str_replace('"','',$response['agent']['name']); ?>
                                          <input name="name" class="m-wrap span12" type="text" required="required" value="{{$agent_name}}">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span9">
                                    <div class="control-group">
                                       <label class="control-label" for="address">လိပ်စာ</label>
                                       <div class="controls">
                                          <input  name="address" class="m-wrap span12" required="required" value="{{$response['agent']['address']}}" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span9">
                                    <div class="control-group">
                                       <label class="control-label" for="phone">ဖုန်းနံပါတ်</label>
                                       <div class="controls">
                                          <input  name="phone" class="m-wrap span12" required="required" value="{{$response['agent']['phone']}}" type="text">
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
                                                   <input style="opacity: 0;" type="radio" name="rdo_commission_type" value="0" class="chkcommission" @if($response['agent']['commission'] == 0) checked="" @endif>
                                                   <!-- <input style="opacity: 0;" name="day" class="departuredays" value="daily" checked="" type="radio"> -->
                                                </span>
                                             </div>
                                          </span>
                                       </div>
                                       ခရီးစဥ်အလုိက်သတ်မှတ်မည်
                                    </label>
                                    <label class="radio">
                                       <div id="uniform-undefined" class="radio">
                                          <span class="checked">
                                             <div id="uniform-undefined" class="radio">
                                                <span class="checked">
                                                   <input type="radio" name="rdo_commission_type" value="1" class="chkcommission" @if($response['agent']['commission'] > 0) checked="" @endif>
                                                </span>
                                             </div>
                                          </span>
                                       </div>
                                       အေရာင်းကုိယ်စားလှယ်အလုိက်သတ်မှတ်မည်။
                                    </label>
                                 </div>
                              </div>

                              <div id="commission_frame" @if($response['agent']['commission'] > 0) style="display:block" @else style="display:none;" @endif>
                                 <div class="row-fluid">
                                    <div class="span9">
                                       <div class="control-group">
                                          <label class="control-label" for="comission">ေကာ်မၡင် အမျိုးအစား</label>
                                          <div class="controls">
                                                <select name="comissiontype" id='comission' class="m-wrap span12">
                                                   @foreach($response['comissiontype'] as $objcomissiontype)
                                                      <option value="{{$objcomissiontype->id}}" @if( $objcomissiontype['id']==$response['agent']['commission_id']) selected @endif>{{$objcomissiontype['name']}}</option>
                                                   @endforeach   
                                                </select> 
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="row-fluid">
                                    <div class="span9">
                                       <div class="control-group">
                                          <label class="control-label" for="commission">ေကာ်မၡင်နုန်း</label>
                                          <div class="controls">
                                             <input  name="comission" class="m-wrap span12" required="required" value="{{$response['agent']['commission']}}" type="text" id="commission">
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              
                              
                              <div class="row-fluid">
                                 <div class="span9">
                                    <div class="control-group">
                                       <div class="controls">
                                       <label>
                                          <input  name="owner" class="span3" value="1" @if($response['agent']['owner']==1) checked="" @endif type="checkbox" style="float:left !important;margin-left:0;"><p style="padding-top:.7rem;">Owner</p>
                                       </label>
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <div class="cleardiv">&nbsp;</div>
                              <div class="controls">
                                    <button type ="submit" class="btn green"> ျပင္မည္ <i class="m-icon-swapright m-icon-white"></i></button> 
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
      $('.chkcommission').each(function(){
        $(this).click(function(){
          var val_radio=$(this).val();
          if(val_radio=="1"){
             $('#commission_frame').show();
          }else{
             $('#commission_frame').hide();
             $('#commission').val("0");
          }
        });
      }); 
   </script>
@stop
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
                     <?php 
                        $orderdate=Session::get('search_daily_date'); 
                        $V_operator_id=Session::get('operator_id');
                     ?>
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
                  <div class="responsive span8" data-tablet="span8" data-desktop="span8">
                     <form id="addnew-form" class="horizontal-form" action = "/updateagent/{{$response['agent']['id']}}" method= "post" enctype="multipart/form-data">    
                        <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-user"></i>အေရာင္းကုိယ္စားလွယ္ အခ်က္အလက္မ်ား </h4>
                              <div class="actions">
                              </div>
                           </div>
                           <div class="portlet-body">
                              <div class="row-fluid">
                                 <div class="span6">
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
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="address">လိပ်စာ</label>
                                       <div class="controls">
                                          <input  name="address" class="m-wrap span12" required="required" value="{{$response['agent']['address']}}" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="phone">ဖုန်းနံပါတ်</label>
                                       <div class="controls">
                                          <input  name="phone" class="m-wrap span12" required="required" value="{{$response['agent']['phone']}}" type="text">
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
                                                @foreach($response['comissiontype'] as $objcomissiontype)
                                                   <option value="{{$objcomissiontype->id}}" @if( $objcomissiontype['id']==$response['agent']['commission_id']) selected @endif>{{$objcomissiontype['name']}}</option>
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
                                          <input  name="comission" class="m-wrap span12" required="required" value="{{$response['agent']['commission']}}" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <div class="controls">
                                       <label>
                                          <input  name="owner" class="m-wrap span12" value="1" @if($response['agent']['owner']==1) checked="" @endif type="checkbox">Owner
                                       </label>

                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <div class="cleardiv">&nbsp;</div>
                              <div class="controls">
                                    <input type = "submit" value = "ျပင္မည္" class="btn green button-submit" id="btn_create" />
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
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
@stop
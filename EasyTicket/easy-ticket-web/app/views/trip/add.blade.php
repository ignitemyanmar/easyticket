@extends('../admin')
@section('content')
<style type="text/css">
   .padding-5{padding: 5px;}
   select.m-wrap, textarea.m-wrap, select {
       font-family: "Ayar Wagaung";
   }
   .fit-a{color:white;text-align:center; padding-top: 19px;}
   .checkboxframe input[type="checkbox"]{
     margin:9px;
     position: absolute;
     z-index: -1;
   }
   .radios{opacity: 1;}
   .check-a .span1{height: 21px; margin-top: 15px;}
   .check-a .spanhalf{margin-top: 65px;}
   .check-a label > .fit-a {
         display: block;
         position: relative;
         z-index: 1;
         min-width: 21px;
         min-height: 21px;
         cursor: pointer;
     }
   .check-a label{height: 21px;}
   .taken{background: url("../../img/rdored.png") repeat scroll transparent;}
   .choose{background: url("../../img/rdoyellow.png") repeat scroll transparent;}
   .available{background: url("../../img/rdogreen.png") repeat scroll transparent;}

</style>
{{HTML::style('../../css/jquery-ui.css')}}
<link rel="stylesheet" type="text/css" href="../../assets/chosen-bootstrap/chosen/chosen.css" />
   <link rel="stylesheet" type="text/css" href="../../assets/bootstrap-timepicker/compiled/timepicker.css" />
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
                        Creat New Trip
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">Trip</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">Add New Trip</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <form id="tripcreate" class="form-horizontal" action = "/trip-create" method= "post">    
                        <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-user"></i> Trip Information</h4>
                              <div class="actions">
                              </div>
                           </div>
                           <div class="portlet-body">
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="from">ထွက်ခွာမည့်ြမို့</label>
                                       <div class="controls">
                                             <select name="from" id='from' class="m-wrap span12 chosen">
                                                @foreach($response['cities'] as $rows)
                                                   <option value="{{$rows->id}}">{{$rows->name}}</option>
                                                @endforeach   
                                             </select> 
                                       </div>
                                    </div><br>

                                    <div class="control-group">
                                       <label class="control-label" for="to">ေရာက်ရှိမည့်ြမို့</label>
                                       <div class="controls">
                                             <select name="to" id='to' class="m-wrap span12 chosen">
                                                @foreach($response['cities'] as $rows)
                                                   <option value="{{$rows->id}}">{{$rows->name}}</option>
                                                @endforeach   
                                             </select> 
                                       </div>
                                    </div><br>
                                                                     

                                    <div class="control-group">
                                       <label class="control-label" for="class">ကားအတန်းအစား(အမျိုးအစား)</label>
                                       <div class="controls">
                                             <select name="class_id" id='class' class="m-wrap span12 chosen">
                                                @foreach($response['busclasses'] as $rows)
                                                   <option value="{{$rows->id}}">{{$rows->name}}</option>
                                                @endforeach   
                                             </select> 
                                       </div>
                                    </div><br>

                                    <div class="control-group">
                                       <label class="control-label">ကားထွက်ခွာသည့်ေန့များ</label>
                                       <div class="controls">
                                          <label class="radio">
                                          <div id="uniform-undefined" class="radio"><span class="">
                                             <input style="opacity: 0;" name="day" class="departuredays" value="daily" checked="" type="radio"></span></div>
                                             ေန့စဥ်
                                          </label>
                                          <label class="radio">
                                             <div id="uniform-undefined" class="radio"><span class="checked">
                                             <input style="opacity: 0;" name="day" class="departuredays" value="custom" type="radio"></span></div>
                                             အြခား
                                          </label> 
                                          <label class="radio">
                                             <div id="uniform-undefined" class="radio"><span class="checked">
                                             <input style="opacity: 0;" name="day" class="departuredays" value="onlyone" type="radio"></span></div>
                                             တစ်ရက်တည်းသာ
                                          </label>  
                                       </div>

                                       <div class="controls" id="customdays">
                                          @foreach($response['days'] as $day)
                                             <label class="checkbox" style="min-width:50px;">
                                                <div id="uniform-undefined" class="checker"><span>
                                                   <input style="opacity: 0;" name="available_day[]" value="{{$day}}" type="checkbox"></span>
                                                </div> 
                                                   {{$day}}
                                             </label>
                                          @endforeach
                                       </div>

                                       <div class="controls" id="onlyone">
                                          <input type="text" name="onlyone_day" id="onlyone_day">
                                       </div>
                                    </div><br>
                                    
                                    <div class="control-group">
                                       <label class="control-label">ကားထွက်ခွာမည့်အချိန်</label>
                                       <div class="controls">
                                          <div class="input-append bootstrap-timepicker-component">
                                             <input class="m-wrap m-ctrl-small timepicker-default" type="text" name="time">
                                             <span class="add-on"><i class="icon-time"></i></span>
                                          </div>
                                       </div>
                                    </div><br>

                                    <div class="control-group">
                                       <label class="control-label" for="price">နုိင်ငံသား ေစျးနုန်း</label>
                                       <div class="controls">
                                          <input  name="price" class="m-wrap span12" placeholder="" type="text" required>
                                       </div>
                                    </div>
                                      
                                    <div class="control-group">
                                       <label class="control-label" for="price">နုိင်ငံြခားသား ေစျးနုန်း</label>
                                       <div class="controls">
                                          <input  name="foreign_price" class="m-wrap span12" placeholder="" type="text" required>
                                       </div>
                                    </div>

                                    <div class="control-group">
                                       <label class="control-label" for="seatplan">ခုံအစီအစဥ်</label>
                                       <div class="controls">
                                             <select name="seat_plan_id" id='seatplan' class="m-wrap span12 chosen ayar-wagaung seatplan" required="">
                                                   <option value="">ခုံအစီအစဥ္ ေရြးရန္</option>
                                                @foreach($response['seatplan'] as $rows)
                                                   <option value="{{$rows->id}}">{{$rows->name}}</option>
                                                @endforeach   
                                             </select> 
                                       </div>
                                    </div>

                                    <div class="control-group">
                                       <label class="control-label" for="price">ေကာ်မစ်ရှင်နုန်း</label>
                                       <div class="controls">
                                          <input  name="commission" class="m-wrap span12" placeholder="" type="text" required>
                                       </div>
                                    </div>

                                    <div class="control-group">
                                       <label class="control-label" for="to">ဆက်သွားမည့်ြမို့  ရှိ/ မရှိ ေရွးရန်</label>
                                       <div class="controls">
                                          <label class="radio">
                                             <div id="uniform-undefined" class="radio">
                                                <span class="">
                                                   <div id="uniform-undefined" class="radio">
                                                      <span class="">
                                                         <input style="opacity: 0;" type="radio" name="chkextendcity" value="1" class="chkextend" checked="">
                                                         <!-- <input style="opacity: 0;" name="day" class="departuredays" value="daily" checked="" type="radio"> -->
                                                      </span>
                                                   </div>
                                                </span>
                                             </div>
                                             ရှိ
                                          </label>
                                          <label class="radio">
                                             <div id="uniform-undefined" class="radio">
                                                <span class="">
                                                   <div id="uniform-undefined" class="radio">
                                                      <span class="checked">
                                                         <input type="radio" name="chkextendcity" value="0" class="chkextend" >
                                                      </span>
                                                   </div>
                                                </span>
                                             </div>
                                             မရှိ
                                          </label>
                                       </div>
                                    </div>

                                    <div id="extend_frame">
                                       <div class="control-group">
                                          <label class="control-label" for="to">ဆက်သွားမည့်ြမို့</label>
                                          <div class="controls">
                                                <select name="extendcity" id='cboextendcity' class="m-wrap span12 chosen">
                                                      <option value="0">ဆက္သြားမည့္ျမိဳ႕ ေရြးရန္</option>
                                                   @foreach($response['cities'] as $rows)
                                                      <option value="{{$rows->id}}">{{$rows->name}}</option>
                                                   @endforeach   
                                                </select>  
                                          </div>
                                       </div>

                                       <div class="control-group">
                                          <label class="control-label" for="price">နုိင်ငံသား ေစျးနုန်း (ဆက်သွားမည့်ြမို့)</label>
                                          <div class="controls">
                                             <input  name="extend_price" class="m-wrap span12" placeholder="" type="text">
                                          </div>
                                       </div>
                                         
                                       <div class="control-group">
                                          <label class="control-label" for="price">နုိင်ငံြခားသား ေစျးနုန်း (ဆက်သွားမည့်ြမို့)</label>
                                          <div class="controls">
                                             <input  name="extend_foreign_price" class="m-wrap span12" placeholder="" type="text">
                                          </div>
                                       </div>
                                    </div>

                                 </div>
                                 <input type="hidden" value="{{$operator_id}}" name="operator_id" id="operator_id">
                                 <div class="span6" style="min-height:550px;border:1px solid #eee;">
                                    <br><div id="seatplanview"></div>
                                 </div>
                              </div>
                              
                              <div class="cleardiv">&nbsp;</div>
                              <div class="controls">
                                    <button type = "submit" class="btn green"/>သိမ္းမည္ <i class="m-icon-swapright m-icon-white"></i></div>
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

   
   
   
   <script type="text/javascript" src="../../assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
   <script type="text/javascript" src="../../assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
   {{HTML::script('../../src/jquery-ui.js')}}
   <script type="text/javascript" src="../../js/apps.js"></script>
@stop
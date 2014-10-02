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
                        <li><a href="#">Edit Trip</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span8" data-tablet="span8" data-desktop="span8">
                     <form id="addnew-form" class="horizontal-form" action = "/updatetrip/{{$response['trip']['id']}}" method= "post" enctype="multipart/form-data">    
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
                                       <label class="control-label" for="operator">Operator</label>
                                       <div class="controls">
                                             <select name="operator" id='operator' class="m-wrap span12">
                                                @foreach($response['operator'] as $objoperator)
                                                   <option value="{{$objoperator->id}}" @if( $objoperator['id']==$response['trip']['operator_id']) selected @endif>{{$objoperator['name']}}</option>
                                                @endforeach   
                                             </select> 
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="from">From</label>
                                       <div class="controls">
                                             <select name="from" id='from' class="m-wrap span12">
                                                @foreach($response['city'] as $objcity)
                                                   <option value="{{$objcity->id}}" @if( $objcity['id']==$response['trip']['from']) selected @endif>{{$objcity['name']}}</option>
                                                @endforeach   
                                             </select> 
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="to">To</label>
                                       <div class="controls">
                                             <select name="to" id='to' class="m-wrap span12">
                                                @foreach($response['city'] as $objcity)
                                                   <option value="{{$objcity->id}}" @if( $objcity['id']==$response['trip']['to']) selected @endif>{{$objcity['name']}}</option>
                                                @endforeach  
                                             </select> 
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="class">Class</label>
                                       <div class="controls">
                                             <select name="class" id='class' class="m-wrap span12">
                                                @foreach($response['class'] as $objclass)
                                                   <option value="{{$objclass->id}}" @if( $objclass['id']==$response['trip']['class_id']) selected @endif>{{$objclass['name']}}</option>
                                                @endforeach   
                                             </select> 
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="day">Available Day</label>
                                       <div class="controls">
                                          <input  name="day" class="m-wrap span12" required="required" value="{{$response['trip']['available_day']}}" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="time">Time</label>
                                       <div class="controls">
                                          <input  name="time" class="m-wrap span12" required="required" value="{{$response['trip']['time']}}" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="price">Price</label>
                                       <div class="controls">
                                          <input  name="price" class="m-wrap span12" required="required" value="{{$response['trip']['price']}}" type="text">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="seatplan">Seat Plan</label>
                                       <div class="controls">
                                             <select name="seatplan" id='seatplan' class="m-wrap span12">
                                                 @foreach($response['seatplan'] as $objseatplan)
                                                   <option value="{{$objseatplan->id}}" @if( $objseatplan['id']==$response['trip']['seat_plan_id']) selected @endif>{{$objseatplan['name']}}</option>
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
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
@stop
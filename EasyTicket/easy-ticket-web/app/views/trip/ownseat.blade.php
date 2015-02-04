@extends('../admin')
@section('content')
<style type="text/css">
   .padding-5{padding: 5px;}
   select.m-wrap, textarea.m-wrap, select {
       font-family: "Ayar Wagaung";
   }
   .fit-a{color:white;text-align:center; padding-top: 19px;}
   .checkboxframe input[type="checkbox"]{
     margin:9px !important;
     position: absolute !important;
     z-index: -1;

   }
   .colorbox{width:24px; height:24px;float:left;margin-right:8px;}
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
   .own{background: url("../../img/rdoyellow.png") repeat scroll transparent;}

   .taken{background: #FF1711;}
   .choose{background:  #52C789;}
   .available{background: #5586c8;}

   .controls .control-label{text-align: left;}

   .operator_0{background:#5586c8;} /*Mandalar Min*/
   .operator_1{background:#FF8514;} /*gov*/
   .operator_2{background:#4BFFFF;} /*Aung Minglar*/
   .operator_3{background:#B08620;} /*Mindama*/
   .operator_4{background:#640F6D;} /*Aung San*/

</style>
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
                        ခုံပုိင္သတ္မွတ္ရန္
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
                        <li><a href="#">ခုံပုိင္သတ္မွတ္ရန္</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <form id="tripcreate" class="form-horizontal" action = "/define-ownseat" method= "post">    
                        <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-user"></i>ခုံပုိင္သတ္မွတ္ရန္</h4>
                              <div class="actions">
                              </div>
                           </div>
                           <div class="portlet-body">
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="from">ကားဂိတ်ေရွးရန် <b class="required">(*)</b></label>
                                       <div class="controls">
                                          <select name="operatorgroup_id" class="chosen">
                                             @foreach($operatorgroup as $row)
                                             <option value="{{$row->id}}">{{$row->user->name}}</option>
                                             @endforeach
                                          </select>
                                       </div>
                                    </div><br>


                                    <div class="control-group">
                                       <label class="control-label" for="from">ထွက်ခွာမည့်ြမို့ ==></label>
                                       <div class="controls">
                                            <label class="control-label">{{$tripinfo['from_to']}}</label>
                                       </div>
                                    </div><br>


                                    <div class="control-group">
                                       <label class="control-label" for="class">ကားအမျိုးအစား ==></label>
                                       <div class="controls">
                                             <label class="control-label">{{$tripinfo['class']}}</label>
                                       </div>
                                    </div><br>

                                    <div class="control-group">
                                       <label class="control-label">ကားထွက်ခွာသည့်ေန့များ ==></label>
                                       <div class="controls">
                                             <label class="control-label">{{$tripinfo['available_day']}}</label>
                                       </div>
                                    </div><br>
                                    
                                    <div class="control-group">
                                       <label class="control-label">ကားထွက်ခွာမည့်အချိန် ==></label>
                                       <div class="controls">
                                          <label class="control-label">{{$tripinfo['time']}}</label>
                                       </div>
                                    </div><br>

                                    <div class="control-group">
                                       <label class="control-label" for="price">နုိင်ငံသား ေစျးနုန်း ==></label>
                                       <div class="controls">
                                         <label class="control-label">{{$tripinfo['price']}}</label>
                                       </div>
                                    </div>
                                      
                                    <div class="control-group">
                                       <label class="control-label" for="price">နုိင်ငံြခားသား ေစျးနုန်း ==></label>
                                       <div class="controls">
                                          <label class="control-label">{{$tripinfo['foreign_price']}}</label>
                                       </div>
                                    </div>
                                    <div class="cleardiv">&nbsp;</div>
                                    <div class="controls">
                                          <a style="display: inline-block;margin-top: 4px;" href="/trip-list" class="btn button-previous">
                                             <i class="m-icon-swapleft"></i> Back 
                                          </a>
                                          <input type ="submit" value ="ခုံပုိင္သတ္မွတ္မည္" class="btn green button-submit"/>
                                    </div>
                                 </div>
                                 <input type="hidden" value="{{$operator_id}}" name="operator_id">
                                 <input type="hidden" value="{{$tripinfo['seat_plan_id']}}" name="seat_plan_id">
                                 <input type="hidden" value="{{$tripinfo['trip_id']}}" name="trip_id">
                                 
                                 <div class="span6" style="min-height:550px;border:1px solid #eee;">
                                    <div style="margin:24px;">
                                       <h4>ခုံပုိင္သတ္မွတ္ထားေသာေရာင္မ်ား</h4><br>
                                       @if($operatorgroup)
                                          @foreach($operatorgroup as $operator)
                                             <div class="colorbox operator_{{$operator->color}}"></div>{{$operator->user->name}}<br>
                                             <div class="clear">&nbsp;</div>
                                          @endforeach
                                       @endif
                                          <div class="colorbox choose"></div>ေရြးခ်ယ္ထားေသာ ခုံမ်ား<br>
                                          <div class="clear">&nbsp;</div>
                                          <div class="colorbox available"></div>ခုံလြတ္မ်ား<br>
                                          <div class="clear">&nbsp;</div>
                                    </div>
                                    <br>
                                           
                                       <div id="seatplanview">
                                          <div id="seating-map-wrapper">
                                             <div id="seating-map-creator">
                                                      <div class="check-a">
                                                        @if($jsoncloseseat)
                                                         <?php $k=1;  $columns=$response['column'];?>
                                                         @foreach($jsoncloseseat as $rows)
                                                            
                                                            @if($k%$columns == 1)
                                                            <div class="row-fluid">
                                                               <div class="span1 small-1">&nbsp;</div>
                                                            @endif
                                                               <div class="span2 small-2 ">
                                                                 @if($rows['status']==0)
                                                                  <div class="span2 small-2">&nbsp;</div>
                                                                 @else
                                                                  <div class="checkboxframe">
                                                                        <label>
                                                                            <span class=""></span>
                                                                            <?php 
                                                                              if($rows['status'] != 1){
                                                                                 $disabled="disabled";
                                                                                 $taken="taken";
                                                                              }elseif($rows['operatorgroup_id']!=0){
                                                                                 $color=OperatorGroup::whereid($rows['operatorgroup_id'])->pluck('color');
                                                                                 $disabled=''; 
                                                                                 $taken="operator_".$color.' operatorseat_'.$color;
                                                                              }else{
                                                                                 $disabled=''; 
                                                                                 $taken='available';  
                                                                              }
                                                                             ?>
                                                                           <div style="opacity:.1;">
                                                                              <input class="radios" type="checkbox" multiple="multiple" value="{{$rows['seat_no']}}" name="seats[]" {{ $disabled }} @if($rows['operatorgroup_id']!=0) checked @endif>
                                                                           </div>
                                                                            <div class="fit-a {{$taken}}" title="{{$rows['seat_no']}}" id="">{{$rows['seat_no']}}</div>
                                                                        </label>
                                                                    </div>
                                                                 @endif
                                                                 
                                                               </div>
                                                            @if($k%$columns==0)
                                                               <div class="span3 small-2">&nbsp;</div>
                                                            </div>
                                                            <div style="clear:both;height:0px;">&nbsp;</div>
                                                             @endif
                                                               <?php $k++;?>
                                                          @endforeach
                                                      @endif
                                                    </div>
                                             </div>
                                          </div>
                                       </div>
                                    <br>
                                 </div>
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
   {{HTML::script('../js/ownseat.js')}}
  
   
@stop
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
   .controls .control-label {
       text-align: left;
   }

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
                     <h3 class="page-title">
                        Change Seat Plan
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
                        <li><a href="#">Change Seat Plan</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <form id="tripcreate" class="form-horizontal" action = "/changetripseatplan/{{$response['trip']->id}}" method= "post"> 
                        <input type="hidden" class="access_token" name="access_token" value="{{Auth::user()->access_token}}">   
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
                                       <label class="control-label" for="from">ခရီးစဥ်</label>
                                       <div class="controls">
                                            <label class="control-label">{{$response['trip']->from_city->name}} ==> {{$response['trip']->to_city->name}}</label>
                                       </div>
                                    </div><br>


                                    <div class="control-group">
                                       <label class="control-label">ကားအတန်းအစား(အမျိုးအစား)</label>
                                       <div class="controls">
                                             <label class="control-label">{{$response['trip']->busclass->name}}</label>
                                       </div>
                                    </div><br>

                                    <div class="control-group">
                                       <label class="control-label">ကားထွက်ခွာသည့်ေန့</label>
                                                                             
                                       <div class="controls">
                                          <input type="text" name="onlyone_day"  id="onlyone_day" required="">
                                       </div>
                                    </div><br>
                                    
                                    <div class="control-group">
                                       <label class="control-label">ကားထွက်ခွာမည့်အချိန်</label>
                                       <div class="controls">
                                          <label class="control-label">{{$response['trip']->time}}</label>
                                       </div>
                                    </div><br>

                                    <div class="control-group">
                                       <label class="control-label">နုိင်ငံသား ေစျးနုန်း</label>
                                       <div class="controls">
                                          <label class="control-label">{{$response['trip']->price}} KS</label>
                                       </div>
                                    </div>
                                      
                                    <div class="control-group">
                                       <label class="control-label">နုိင်ငံြခားသား ေစျးနုန်း</label>
                                       <div class="controls">
                                          <label class="control-label">{{$response['trip']->foreign_price}} KS</label>
                                       </div>
                                    </div>

                                    <div class="control-group">
                                       <label class="control-label" for="seatplan">ခုံအစီအစဥ်</label>
                                       <div class="controls">
                                             <select name="seat_plan_id" id='seatplan' class="m-wrap span12 chosen ayar-wagaung seatplan" required="">
                                                   <option value="">ခုံအစီအစဥ္ ေရြးရန္</option>
                                                @foreach($response['seatplan'] as $rows)
                                                   <option value="{{$rows->id}}" @if($rows->id==$response['trip']->seat_plan_id) selected @endif>{{$rows->name}}</option>
                                                @endforeach   
                                             </select> 
                                       </div>
                                    </div>

                                    <div class="control-group">
                                       <label class="control-label" for="price">ေကာ်မစ်ရှင်နုန်း</label>
                                       <div class="controls">
                                          <label class="control-label">{{$response['trip']->commission}} KS</label>
                                       </div>
                                    </div>

                                    <div class="control-group">
                                       <label class="control-label" for="to">ဆက်သွားမည့်ြမို့  ရှိ/ မရှိ ေရွးရန်</label>
                                       <div class="controls">
                                          <label class="control-label">@if(count($response['trip']->extendcity) >0 ) ရှိ @else မရှိ @endif</label>
                                       </div>
                                    </div>
                                    @if(count($response['trip']->extendcity)>0)
                                       <div id="extend_frame">
                                          <div class="control-group">
                                             <label class="control-label" for="to">ဆက်သွားမည့်ြမို့</label>
                                             <div class="controls">
                                                   <label class="control-label">{{City::whereid($response['trip']->extendcity[0]->city_id)->pluck('name');}}</label> 
                                             </div>
                                          </div>

                                          <div class="control-group">
                                             <label class="control-label" for="price">နုိင်ငံသား ေစျးနုန်း (ဆက်သွားမည့်ြမို့)</label>
                                             <div class="controls">
                                                <label class="control-label">{{$response['trip']->extendcity[0]->local_price}}</label>
                                             </div>
                                          </div>
                                            
                                          <div class="control-group">
                                             <label class="control-label" for="price">နုိင်ငံြခားသား ေစျးနုန်း (ဆက်သွားမည့်ြမို့)</label>
                                             <div class="controls">
                                                <label class="control-label">{{$response['trip']->extendcity[0]->foreign_price}}</label>
                                             </div>
                                          </div>
                                       </div>
                                    @endif

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
   <script type="text/javascript" src="../../assets/uniform/jquery.uniform.min.js"></script>
   {{HTML::script('../../src/jquery-ui.js')}}
   <script type="text/javascript" src="../../js/apps.js"></script>
   <script type="text/javascript">
      $(function(){
         alert('a');
        /* $("#onlyone_day").datepicker({
            numberOfMonth: 2,
            dateFormat: 'yy-mm-dd'
         });*/
      });
   </script>
@stop
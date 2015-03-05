@extends('../admin')
@section('content')
<link rel="stylesheet" type="text/css" href="../../assets/bootstrap-datepicker/css/datepicker.css" />
<link rel="stylesheet" type="text/css" href="../../assets/bootstrap-timepicker/compiled/timepicker.css" />
<link rel="stylesheet" type="text/css" href="../../assets/bootstrap-daterangepicker/daterangepicker.css" />
<!-- BEGIN PAGE -->  
   <div class="page-content">
      <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
               <div class="row-fluid">
                  <div class="span12">
                     <!-- BEGIN STYLE CUSTOMIZER -->
                     <h3 class="page-title">
                        ခဏ ရပ္ဆိုင္းရန္
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
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <form id="tripcreate" class="form-horizontal" action = "/closetrip" method= "post">  
                        <input type="hidden" name="access_token" value="{{Auth::user()->access_token}}">
                        <input type="hidden" name="trip_id" value="{{$trip_id}}">
                        <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-user"></i> ခဏ ရပ္ဆိုင္းရန္</h4>
                              <div class="actions">
                              </div>
                           </div>
                           <div class="portlet-body">
                              <div class="row-fluid">
                                 <div class="span8">
                                    <div class="control-group">
                                      <label class="control-label"></label>
                                      <div class="controls">
                                         <label class="checkbox" id="ever_close">
                                         <div id="uniform-undefined" class="checker"><span class=""><input style="opacity: 0;" value="1" id="chk_ever_close" type="checkbox" name="ever_close"></span></div> အြမဲတမ်း ရပ်ဆိုင်မည်
                                         </label>
                                      </div>
                                    </div>
                                    <div class="control-group date_range">
                                      <label class="control-label">Date Ranges</label>
                                      <div class="controls">
                                         <div class="input-prepend">
                                            <span class="add-on"><i class="icon-calendar"></i></span><input name="date_range" id="date_range" class="m-wrap m-ctrl-medium date-range" type="text">
                                         </div>
                                      </div>
                                    </div>
                                    <div class="control-group from_date" style="display:none;">
                                      <label class="control-label">From Date</label>
                                      <div class="controls">
                                         <div class="input-prepend">
                                            <span class="add-on"><i class="icon-calendar"></i></span><input name="date_range" id="date_range" class="m-wrap m-ctrl-medium date-picker" type="text">
                                         </div>
                                      </div>
                                    </div>
                                    <div class="control-group">
                                       <label class="control-label" for="price">ခဏ ရပ်ဆိုင်ရြခင်း၏ အေြကာင်းအရင်း</label>
                                       <div class="controls">
                                          <textarea name="remark" class="m-wrap span12" placeholder="" type="text" required></textarea>
                                       </div>
                                    </div>

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
<script type="text/javascript" src="../../assets/uniform/jquery.uniform.min.js"></script>
<script type="text/javascript" src="../../assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="../../assets/bootstrap-daterangepicker/date.js"></script>
<script type="text/javascript" src="../../assets/bootstrap-daterangepicker/daterangepicker.js"></script> 
<script type="text/javascript">
  $('#ever_close').click(function(){
    if($('#chk_ever_close').is(':checked')){
      $('.from_date').show();
      $('.date_range').hide();
    }else{
      $('.from_date').hide();
      $('.date_range').show();
    }
  });
</script>
@stop
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
                     
                     <h3 class="page-title">
                      &nbsp; ျမိဳ႕ အသစ္ထည့္သြင္းမည္
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <?php 
                        $orderdate=Session::get('search_daily_date'); 
                        $V_operator_id=Session::get('operator_id');
                     ?>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}&{{$myApp->access_token}}">ပင္မစာမ်က္ႏွာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="#">ျမိဳ႕ အသစ္ထည့္သြင္းမည္</a>
                           <span class="icon-angle-right"></span>
                        </li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span8" data-tablet="span8" data-desktop="span8">
                     <form id="addnew-form" class="horizontal-form" action = "/addcity" method= "post" enctype="multipart/form-data">    
                        <input type="hidden" name="access_token" value="{{$myApp->v_access_token}}">
                        <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-user"></i>ျမိဳ႕အခ်က္အလက္မ်ား</h4>
                              <div class="actions">
                              </div>
                           </div>
                           <div class="portlet-body">
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="name">အမည်</label>
                                       <div class="controls">
                                          <input name="name" type="text" required="required" class="m-wrap span12">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="cleardiv">&nbsp;</div>
                              <div class="controls">
                                    <div class="large-2 column">&nbsp;</div>
                                    <div class="large-10 column">
                                       <input type = "submit" value = "အသစ္ထည့္မည္" class="btn green button-submit" id="btn_create" />
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
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
@stop
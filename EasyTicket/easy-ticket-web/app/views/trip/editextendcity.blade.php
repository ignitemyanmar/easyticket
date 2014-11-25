@extends('../admin')
@section('content')
<style type="text/css">
   .dd-handle {
    display: block;
    height: 30px;
    margin: 5px 0px;
    cursor: move;
    padding: 5px 10px;
    color: #333;
    text-decoration: none;
    font-weight: 400;
    border: 1px solid #CCC;
    background: none repeat scroll 0% 0% #FAFAFA;
    border-radius: 3px;
    box-sizing: border-box;
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
                     <!-- BEGIN STYLE CUSTOMIZER -->
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
                     <form id="tripcreate" class="form-horizontal" action = "/trip/editextend/{{$response->id}}" method= "post">    
                        <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-user"></i> Trip Information</h4>
                              <div class="actions">
                              </div>
                           </div>
                           <div class="portlet-body">
                              <input type="hidden" name="extendcity_id" value="{{$extendcity->id}}">
                              <div class="row-fluid">
                                 <div class="span8">
                                    <div class="control-group">
                                       <label class="control-label" for="from">ခရီးစဥ်</label>
                                       <div class="controls">
                                             <div class="dd-handle">{{$response->from_city->name.'==>'. $response->to_city->name .' ( '.$response->busclass->name .'-'.$response->time.' )'}}</div>
                                       </div>
                                    </div><br>
                                    <div class="control-group">
                                       <label class="control-label" for="to">ဆက်သွားမည့်ြမို့</label>
                                       <div class="controls">
                                             <select name="extendcity" id='cboextendcity' class="m-wrap span12 chosen">
                                                @foreach($cities as $rows)
                                                   <option value="{{$rows->id}}" @if($rows->id == $extendcity->city_id) selected @endif>{{$rows->name}}</option>
                                                @endforeach   
                                             </select>  
                                       </div>
                                    </div>

                                    <div class="control-group">
                                       <label class="control-label" for="price">နုိင်ငံသား ေစျးနုန်း (ဆက်သွားမည့်ြမို့)</label>
                                       <div class="controls">
                                          <input  name="extend_price" class="m-wrap span12" placeholder="" type="text" required value="{{$extendcity->local_price}}">
                                       </div>
                                    </div>
                                      
                                    <div class="control-group">
                                       <label class="control-label" for="price">နုိင်ငံြခားသား ေစျးနုန်း (ဆက်သွားမည့်ြမို့)</label>
                                       <div class="controls">
                                          <input  name="extend_foreign_price" class="m-wrap span12" placeholder="" type="text" required value="{{$extendcity->foreign_price}}">
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

   
   
   
   <script type="text/javascript" src="../../assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
   <script type="text/javascript" src="../../assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
   {{HTML::script('../../src/jquery-ui.js')}}
@stop
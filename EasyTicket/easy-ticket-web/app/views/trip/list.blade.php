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
                        Trip List
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="/itemlist">Trip list</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">Trip list reports</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-user"></i>Trip list </h4>
                           <div class="actions">
                              
                           </div>
                        </div>

                        <div class="portlet-body">
                           <table class="table table-striped table-bordered table-advance table-hover">
                              <thead>
                                 <tr>
                                    <th></th>
                                    <th>Operator</th>
                                    <th class="hidden-phone">From</th>
                                    <th>To</th>
                                    <th>Class</th>
                                    <th>Available Day</th>
                                    <th>Time</th>
                                    <th>Price</th>
                                    <th>SeatPlan</th>
                                    <th>Action</th>
                                    <!-- <th></th> -->
                                 </tr>
                              </thead>
                              <tbody>
                              <form action = "/searchtrip" method= "post" class="row">
                                 <div class="row-fluid">
                                    <input name="keyword" type="text" class="m-wrap span6" placeholder = " Search Trip" data-required="true">
                                 </div>
                                 <div class="portlet-body">
                                    <input type = "submit" value = "Search" class="btn green button-submit" required>
                                 </div>
                                 <div class="row-fluid">
                                    <a href="/tripcreate" class="btn green button-submit">Add New Trip </a>
                                 </div>
                           </form>
                           <br><br>
                @if(count($response)==0)
                  There is no record in database.
               @else
                  Total Record : {{$totalCount}}
               @endif
                  
                  @foreach($response as $trip)
                              <tr>
                                 <td><input type="checkbox" name="recordstoDelete[]" value="{{$trip['id']}}" /> </td>
                                 <td>{{$trip['operator_id']}}</td>
                                 <td class="hidden-phone">{{$trip['from']}}</td>
                                 <td>{{$trip['to']}}</td>
                                 <td>{{$trip['class_id']}}</td>
                                 <td>{{$trip['available_day']}}</td>
                                 <td>{{$trip['time']}}</td>
                                 <td>{{$trip['price']}}</td>
                                 <td>{{$trip['seat_plan_id']}}</td>
                                 <td style="text-align:center;">
                                       <a href="/trip-update/{{ $trip['id'] }}"  class="btn green button-submit">Edit</a><br><br>
                                       <a href="deletetrip/{{ $trip['id'] }}"   class="btn green button-submit">Delete</a>
                                 </td>
                              </tr>
                  @endforeach
                     <tr><td colspan="8"><ul class="pagination">{{ $response->links() }}</ul></td></tr>        

                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
                  <div class="responsive span3 padding-5" data-tablet="span4" data-desktop="span4">
                   
                  </div>
               </div>
            <!-- END PAGE CONTENT-->         
         </div>
      <!-- END PAGE CONTAINER-->
   </div>
<!-- END PAGE -->  
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
@stop
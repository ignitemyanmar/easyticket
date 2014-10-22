@extends('../admin')
@section('content')
   {{HTML::style('../../css/Tixato_files/seating_builder.css')}}
   {{HTML::style('../../css/jquery-ui.css')}}
   {{HTML::style('../../css/component.css')}}
<style type="text/css">
  .padding-5{padding: 5px;}
  body .ui-tooltip, .arrow:after {
      background: transparent;
      border: 0px solid #FF8004;
  }
  .arrow{position: relative;
      left: 64%;
      margin-left: 0px;
      box-shadow: transparent;
   }
   .zawgyi-one{font-family: "Zawgyi-One";}  
 
</style>
<!-- BEGIN PAGE -->  
   <div class="page-content">
      <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
               <div class="row-fluid">
                  <div class="span12">
                     <?php 
                        // $orderdate=$response[0]['departure_date']; 
                        $orderdate=Session::get('search_daily_date'); 
                        $V_operator_id=Session::get('operator_id');
                     ?>
                     <h3 class="page-title">
                        ခုံနံပါတ္ႏွင့္ဝယ္သူ စာရင္း
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/report/dailycarandadvancesale?operator_id={{Session::get('operator_id')}}">ပင္မစာမ်က္ႏွာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="/report/dailycarandadvancesale/search?date={{$orderdate}}&operator_id={{$V_operator_id}}">ေန႔စဥ္ အေရာင္းစာရင္း</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">ခုံနံပါတ္ႏွင့္ဝယ္သူ စာရင္း</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div id="myModal" class="reveal-modal small" data-reveal> 
                  <form id="update-form" name ="update-form" class="horizontal-form" action = "" method= "post">    
                        <div class="portlet box light-grey">
                           <div class="portlet-title">
                              <h4><i class="icon-user"></i>ဝယ်သူ အချက်အလက်ြပင်ရန်</h4>
                              <div class="actions">
                              </div>
                           </div>
                           <div class="portlet-body">
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="name">အမည်</label>
                                       <div class="controls">
                                          <input name="customer_name" type="text" required="required" class="m-wrap span12" id="customer_name">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="cleardiv">&nbsp;</div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="name">မှတ်ပုံတင် နံပါတ်</label>
                                       <div class="controls">
                                          <input name="customer_nrc" type="text" required="required" class="m-wrap span12" id="customer_nrc">
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="cleardiv">&nbsp;</div>
                              <div class="controls">
                                    <div class="large-2 column">&nbsp;</div>
                                    <div class="large-10 column">
                                       <input type = "submit" value = "ြပင်မည်" class="btn green button-submit" id="btn_create" />
                                       <br>
                                    </div>
                              </div>
                              
                           </div>
                        </div>
                     </form>
                  <a class="close-reveal-modal">&#215;</a> 
               </div>
               <div class="row-fluid">
                  <div class="responsive span12" data-tablet="span12" data-desktop="span12">
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-th-list"></i>ခုံနံပါတ္ႏွင့္ဝယ္သူ စာရင္း</h4>
                           <div class="actions">
                           </div>
                        </div>


                        <div class="portlet-body">
                           <div class="clearfix">
                              <div class="btn-group pull-right">
                                 <button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="icon-angle-down"></i>
                                 </button>
                                 <ul class="dropdown-menu">
                                    <li><a href="" class="print">Print</a></li>
                                    <!-- <li><a href="#">Save as PDF</a></li> -->
                                    <!-- <li><a href="#" id="btnExportExcel">Export to Excel</a></li> -->
                                 </ul>
                              </div>
                           </div>
                           <div id="contents">

                              @if($response['seat_plan']['seat_list'])
                              <ul class="trip_info">
                                 <h3>ခရီးစဥ္ အခ်က္အလက္မ်ား</h3>
                                 <li><span>ကားနံပါတ္</span>: {{$response['seat_plan']['bus_no']}}</li>
                                 <li><span>ခရီးစဥ္ </span>: {{$response['seat_plan']['from'].'-'. $response['seat_plan']['to']}}</li>
                                 <li><span>ကားအမ်ုိဳးအစား </span>: {{$response['seat_plan']['class']}}</li>
                                 <li><span>ထွက်ခွာမည့် ေန့ရက်</span>: {{$response['seat_plan']['date']}}</li>
                                 <li><span>အခ်ိန္ </span>: {{$response['seat_plan']['time']}}</li>
                                 <li><span>ေရာင္းျပီး/ စုစုေပါင္းလက္မွတ္ </span>: {{$response['seat_plan']['total_sold_seats'].'/'.$response['seat_plan']['total_seats']}}</li>
                                 <li><span>ေရာင္းရေငြစုစုေပါင္း </span>: {{$response['seat_plan']['total_amount']}} MMK</li>
                                 <hr>
                              </ul>
                                 <div id="seating-map-wrapper">
                                    <div id="seating-map-creator">
                                             <div class="check-a">

                                                   <?php $k=1;  $columns=$response['seat_plan']['column'];?>
                                                   @foreach($response['seat_plan']['seat_list'] as $rows)
                                                      @if($k%$columns==1)
                                                      <div class="row-fluid .ul">
                                                         <div class="span1 ">&nbsp;</div>
                                                      @endif
                                                            @if($rows['status']==0)
                                                               <div class="span2">&nbsp;</div>
                                                            @else
                                                               <?php 
                                                                  if($rows['status'] != 1){
                                                                     $disabled="disabled";
                                                                     $taken="taken";
                                                                  }else{
                                                                     $disabled=''; 
                                                                     $taken='available';  
                                                                  }
                                                               ?>
                                                               @if($taken=="taken")
                                                                  <div class="span2 grid cs-style-3">
                                                                     <div class="list">
                                                                        <figure>
                                                                           <div class="checkboxframe">
                                                                              <label>
                                                                                 <span></span>
                                                                                 
                                                                                  <!-- <input class="radios" type="checkbox" multiple="multiple" value="{{$rows['seat_no']}}" name="tickets" {{ $disabled }}> -->
                                                                                 <div class="fit-a {{$taken}}" title="{{$rows['seat_no'].'('. $rows['price'].' K)'}}" id="{{$rows['seat_no']}}">Seat No : <b>{{$rows['seat_no']}}</b><span class="zawgyi-one"><br>Price : <b>{{$rows['price']}}</b><br>{{$rows['customer']['name']}}<br>{{$rows['customer']['nrc']}}</span></div>
                                                                                 <input type="hidden" value="{{$rows['price']}}" class="price{{$rows['seat_no']}}">
                                                                                 <input type="hidden" value="{{$rows['seat_no']}}" class="seatno{{$rows['seat_no']}}">
                                                                              </label>
                                                                           </div>
                                                                           <figcaption><br>
                                                                              <!-- <a href="" class="print1" id='one'>Print</a> -->
                                                                              <a href="/report/customers/update?saleitem_id={{$rows['customer']['saleitem_id']}}" style="text-align:center;padding-left:9px;" data-reveal-id="myModal" class="updatecustomer AyarWagaung" rel="{{$rows['customer']['name']}}" id="{{$rows['customer']['nrc']}}">ဝယ်သူ အချက်အလက် ြပင်ရန်</a>
                                                                           </figcaption>
                                                                        </figure>
                                                                     </div>
                                                                  </div>

                                                               @else
                                                                  <div class="span2">
                                                                     <div class="checkboxframe">
                                                                        <label>
                                                                           <span></span>
                                                                           <div class="fit-a {{$taken}}" title="{{$rows['seat_no'].'('. $rows['price'].' K)'}}" id="{{$rows['seat_no']}}">Seat No : <b>{{$rows['seat_no']}}</b><span class="zawgyi-one"><br>Price : <b>{{$rows['price']}}</b><br>{{$rows['customer']['name']}}<br>{{$rows['customer']['nrc']}}</span></div>
                                                                           <input type="hidden" value="{{$rows['price']}}" class="price{{$rows['seat_no']}}">
                                                                           <input type="hidden" value="{{$rows['seat_no']}}" class="seatno{{$rows['seat_no']}}">
                                                                        </label>
                                                                     </div>
                                                                  </div>
                                                               @endif
                                                            @endif
                                                      @if($k%$columns==0)
                                                         <div class="span">&nbsp;</div>
                                                      </div>
                                                      <div class="clear-fix">&nbsp;</div>
                                                      @endif
                                                      <?php $k++;?>
                                                   @endforeach
                                             </div>
                                    </div>
                                 </div>

                                 <!-- <ul class="grid cs-style-3">
                                    <li>
                                       <figure>
                                          <img src="../../images/4.png" alt="img04">
                                          <figcaption>
                                             <h3>Settings</h3>
                                             <span>Jacob Cummings</span>
                                             <a href="http://dribbble.com/shots/1116685-Settings">Take a look</a>
                                          </figcaption>
                                       </figure>
                                    </li>
                                    <li>
                                       <figure>
                                          <img src="../../images/1.png" alt="img01">
                                          <figcaption>
                                             <h3>Camera</h3>
                                             <span>Jacob Cummings</span>
                                             <a href="http://dribbble.com/shots/1115632-Camera">Take a look</a>
                                          </figcaption>
                                       </figure>
                                    </li>
                                    <li>
                                       <figure>
                                          <img src="../../images/2.png" alt="img02">
                                          <figcaption>
                                             <h3>Music</h3>
                                             <span>Jacob Cummings</span>
                                             <a href="http://dribbble.com/shots/1115960-Music">Take a look</a>
                                          </figcaption>
                                       </figure>
                                    </li>
                                    <li>
                                       <figure>
                                          <img src="bannerphoto/bussm.jpg" alt="img05">
                                          <figcaption>
                                             <h3>Safari</h3>
                                             <span>Jacob Cummings</span>
                                             <a href="http://dribbble.com/shots/1116775-Safari">Take a look</a>
                                          </figcaption>
                                       </figure>
                                    </li>
                                    <li>
                                       <figure>
                                          <img src="../../images/3.png" alt="img03">
                                          <figcaption>
                                             <h3>Phone</h3>
                                             <span>Jacob Cummings</span>
                                             <a href="http://dribbble.com/shots/1117308-Phone">Take a look</a>
                                          </figcaption>
                                       </figure>
                                    </li>
                                    <li>
                                       <figure>
                                          <img src="../../images/6.png" alt="img06"> <figcaption>
                                             <h3>Game Center</h3>
                                             <span>Jacob Cummings</span>
                                             <a href="http://dribbble.com/shots/1118904-Game-Center">Take a look</a>
                                          </figcaption>
                                       </figure>
                                    </li>
                                 </ul> -->
                              @endif
                           </div>
                           

                           <!-- <img src="../../img/icon-48-print.png" border=0 align="middle" class="print"></a> -->
                        </div>
                     </div>

                  </div>
               </div>
            <!-- END PAGE CONTENT-->         
         </div>
      <!-- END PAGE CONTAINER-->
   </div>
   
<!-- END PAGE -->  

   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
      <script src="../../js/hovereffect/modernizr.custom.js"></script>
      <script src="../../js/hovereffect/toucheffects.js"></script>
      <script src="../../js/foundation.min.js"></script>
   <script type="text/javascript">
      $(function() {
          $('.updatecustomer').click(function(e){
            var link=this.href;
            var name=this.rel;
            var nrc=this.id;
            $('#customer_name').val(name);
            $('#customer_nrc').val(nrc);
             document.getElementById('update-form').action=link;
            e.preventDefault();
         });
          $(document).foundation();
         $('.print').click(function() {
            //Get the HTML of div
            var divElements = document.getElementById('contents').innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML = 
              "<html><head><title>Report</title></head><body>" + 
              divElements + "</body>";
            //Print Page
            window.print();
            //Restore orignal HTML
            document.body.innerHTML = oldPage;
            return false;
         });

      });
   </script>
@stop
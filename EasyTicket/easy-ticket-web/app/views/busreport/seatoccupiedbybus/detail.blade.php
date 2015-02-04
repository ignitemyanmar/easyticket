@extends('../admin')
@section('content')
   {{HTML::style('../../css/Tixato_files/seating_builder.css')}}
   {{HTML::style('../../css/jquery-ui.css')}}
   {{HTML::style('../../css/component.css')}}
   
<style type="text/css" media="all">
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
   .zawgyi-one, h4{font-family: "Zawgyi-One";}  
.fit-a{padding-top: 9px;}
 .check-a label{height: 175px;}
 .colorbox{width:24px; height:24px;float:left;margin-right:8px;}
 .booking{background:  #470203;}
 .rm_typeframe{border:1px solid #eee;min-height:200px !important;background: rgba(231, 241, 246, 1);}
 .rm_heading{background:#000; color:white;padding:11px 12px;margin-top:0;}
 hr{margin:4px 0; border-bottom:1px solid #444;}
 .padding_rmtype{padding: 0 12px;}
 .padding_rmtype span{background:#000;padding:0 1px;color:white;position:relative;}
 .show-for-print{display: none;}
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
                           <a href="/report/dailycarandadvancesale?access_token={{Auth::user()->access_token}}&operator_id={{Session::get('operator_id')}}">ပင္မစာမ်က္ႏွာ</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="/report/dailycarandadvancesale/search?access_token={{Auth::user()->access_token}}&date={{$orderdate}}&operator_id={{$V_operator_id}}">ေန႔စဥ္ အေရာင္းစာရင္း</a>
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
                              <h4><i class="icon-user"></i>ဝယ္သူအခ်က္အလက္ျပင္ရန္</h4>
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
                                       <label class="control-label" for="name">Ticket No</label>
                                       <div class="controls">
                                          <input name="ticket_no" type="text" required="required" class="m-wrap span12" id="ticket_no">
                                       </div>
                                    </div>
                                 </div>
                              </div>

                              <div class="cleardiv">&nbsp;</div>
                              <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="name">Phone No</label>
                                       <div class="controls">
                                          <input name="phone" type="text" required="required" class="m-wrap span12" id="phone">
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
                                       <input type = "submit" value = "ျပင္မည္" class="btn green button-submit" id="btn_create" />
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
                                 </ul>
                              </div>
                           </div>

                             <div id="contentprint" class="show-for-print">
                                 @if($response['seat_plan']['seat_list'])
                                 <ul class="trip_info">
                                    <h3>ခရီးစဥ္ အခ်က္အလက္မ်ား</h3>
                                    <li>ကားနံပါတ္: <span style="margin-left:250px;">{{$response['seat_plan']['bus_no']}}</span></li>
                                    <li>ခရီးစဥ္ : <span style="margin-left:250px;">{{$response['seat_plan']['from'].' =>'. $response['seat_plan']['to']}}</span></li>
                                    <li>ကားအမ်ုိဳးအစား : <span style="margin-left:250px;">{{$response['seat_plan']['class']}}</span></li>
                                    <li>ကားထြက္မည့္ေန႕: <span style="margin-left:250px;">{{date('d/m/Y',strtotime($response['seat_plan']['date']))}}</span></li>
                                    <li>အခ်ိန္ : <span style="margin-left:250px;">{{$response['seat_plan']['time']}}</span></li>
                                    <li>ေရာင္းျပီး/ စုစုေပါင္းလက္မွတ္ : <span style="margin-left:250px;">{{$response['seat_plan']['total_sold_seats'].'/'.$response['seat_plan']['total_seats']}}</span></li>
                                    <li>ေရာင္းရေငြစုစုေပါင္း : <span style="margin-left:250px;">{{$response['seat_plan']['total_amount']}} MMK</span></li>
                                    <br>
                                    <div style="width:25px;height:25px; background-color:#FF1711 !important;float:left;margin-right:5px;"></div>ေရာင္းျပီးသားခုံမ်ား<br>
                                    <div class="clear">&nbsp;</div>
                                    <div style="width:25px;height:25px; background-color:#470203 !important;float:left;margin-right:5px;"></div>ၾကိဳတင္မွာယူထားေသာ ခုံမ်ား<br>
                                    <div class="clear">&nbsp;</div>
                                    <div style="width:25px;height:25px; background-color:#5586C8 !important;float:left;margin-right:5px;"></div>ခုံလြတ္မ်ား<br>
                                    <div class="clear">&nbsp;</div>
                                    <hr>
                                 </ul>

                                 <div class="check-a">
                                       <?php $k=1;  $columns=$response['seat_plan']['column'];?>
                                       @foreach($response['seat_plan']['seat_list'] as $rows)
                                          @if($k%$columns==1)
                                          <div class="row-fluid">
                                             <div class="span1 ">&nbsp;</div>
                                          @endif
                                                @if($rows['status']==0)
                                                   <div class="span2">&nbsp;</div>
                                                @else
                                                   <?php 
                                                      if($rows['status'] == 2){
                                                         $disabled="disabled";
                                                         $taken="taken";
                                                         $color='#FF1711;';
                                                      }else if($rows['status'] ==3){
                                                         $disabled="disabled";
                                                         $taken="booking";
                                                         $color='#470203;';
                                                      }else{
                                                         $disabled=''; 
                                                         $taken='available';  
                                                         $color='#5586C8;';
                                                      }
                                                   ?>
                                                   @if($taken=="taken")
                                                      <div class="span2 grid cs-style-3">
                                                         <div class="list">
                                                            <figure>
                                                               <div class="checkboxframe">
                                                                  <label>
                                                                     <span></span>
                                                                     <div class="fit-a {{$taken}} zawgyi-one" title="{{$rows['agent_name']}}" id="{{$rows['seat_no']}}">{{$rows['customer']['name']}}<br> {{$rows['customer']['phone']}}<br> {{$rows['ticket_no']}}<br> {{$rows['customer']['nrc_no']}}<br> &nbsp;{{$rows['agent_name']}}</div>
                                                                  </label>
                                                               </div>
                                                            </figure>
                                                         </div>
                                                      </div>

                                                   @else
                                                      <div class="span2 grid cs-style-3">
                                                         <div class="list">
                                                            <figure>
                                                               <div class="checkboxframe">
                                                                  <label>
                                                                     <span></span>
                                                                     <div class="fit-a {{$taken}}" title="{{$rows['seat_no'].'('. $rows['price'].' K)'}}" id="{{$rows['seat_no']}}">Seat No : <b>{{$rows['seat_no']}}</b><span class="zawgyi-one"> <br> &nbsp;{{$rows['agent_name']}}</b><br>{{$rows['customer']['name']}}<br>{{$rows['customer']['nrc_no']}}</span></div>
                                                                     <!-- <div style="background:{{$color}}" class="fit-a zawgyi-one" title="{{$rows['agent_name']}}" id="{{$rows['seat_no']}}">{{$rows['customer']['name']}}<br> {{$rows['customer']['phone']}}<br> {{$rows['ticket_no']}}<br> {{$rows['customer']['nrc_no']}}<br> &nbsp;{{$rows['agent_name']}}</div> -->
                                                                  </label>
                                                               </div>
                                                            </figure>
                                                         </div>
                                                      </div>
                                                      
                                                   @endif
                                                @endif
                                          @if($k%$columns==0)
                                          </div>
                                          @endif
                                          <?php $k++;?>
                                       @endforeach
                                 </div>
                                 @endif
                           </div>

                           <div id="contents">
                              @if($response['seat_plan']['seat_list'])
                              <ul class="trip_info">
                                 <h3>ခရီးစဥ္ အခ်က္အလက္မ်ား</h3>
                                 <li><span>ကားနံပါတ္</span>: {{$response['seat_plan']['bus_no']}}</li>
                                 <li><span>ခရီးစဥ္ </span>: {{$response['seat_plan']['from'].' => '. $response['seat_plan']['to']}}</li>
                                 <li><span>ကားအမ်ုိဳးအစား </span>: {{$response['seat_plan']['class']}}</li>
                                 <li><span>ကားထြက္မည့္ေန႕</span>: {{date('d/m/Y',strtotime($response['seat_plan']['date']))}}</li>
                                 <li><span>အခ်ိန္ </span>: {{$response['seat_plan']['time']}}</li>
                                 <li><span>ေရာင္းျပီး/ စုစုေပါင္းလက္မွတ္ </span>: {{$response['seat_plan']['total_sold_seats'].'/'.$response['seat_plan']['total_seats']}}</li>
                                 <li><span>ေရာင္းရေငြစုစုေပါင္း </span>: {{$response['seat_plan']['total_amount']}} MMK</li>
                                 <br>
                                 <div class="colorbox taken"></div>ေရာင္းျပီးသားခုံမ်ား<br>
                                 <div class="clear">&nbsp;</div>
                                 <div class="colorbox booking"></div>ၾကိဳတင္မွာယူထားေသာ ခုံမ်ား<br>
                                 <div class="clear">&nbsp;</div>
                                 <div class="colorbox available"></div>ခုံလြတ္မ်ား<br>
                                 <div class="clear">&nbsp;</div>
                                 <hr>
                              </ul>

                              <div class="check-a">
                                    <?php $k=1;  $columns=$response['seat_plan']['column'];?>
                                    @foreach($response['seat_plan']['seat_list'] as $rows)
                                       @if($k%$columns==1)
                                       <div class="row-fluid">
                                          <div class="span1 ">&nbsp;</div>
                                       @endif
                                             @if($rows['status']==0)
                                                <div class="span2">&nbsp;</div>
                                             @else
                                                <?php 
                                                   if($rows['status'] == 2){
                                                      $disabled="disabled";
                                                      $taken="taken";
                                                   }else if($rows['status'] ==3){
                                                      $disabled="disabled";
                                                      $taken="booking";
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

                                                                  <div class="fit-a {{$taken}} zawgyi-one" title="{{$rows['agent_name']}}" id="{{$rows['seat_no']}}">@if($rows['extra_city']) <b style="position:absolute; top:0; right:0;background:#000;">+ Extend City</b> <br>@endif {{$rows['customer']['name']}}<br> {{$rows['customer']['phone']}}<br> {{$rows['ticket_no']}}<br> {{$rows['customer']['nrc_no']}}<br> &nbsp;{{$rows['agent_name']}}</div>
                                                                  <input type="hidden" value="{{$rows['price']}}" class="price{{$rows['seat_no']}}">
                                                                  <input type="hidden" value="{{$rows['seat_no']}}" class="seatno{{$rows['seat_no']}}">
                                                               </label>
                                                            </div>
                                                            <figcaption><br>
                                                               <a href="/report/customers/update?saleitem_id={{$rows['customer']['saleitem_id']}}" style="text-align:center;padding-left:9px;" data-reveal-id="myModal" class="updatecustomer AyarWagaung" rel="{{$rows['customer']['name']}}" id="{{$rows['customer']['nrc_no']}}" data-phone="{{$rows['customer']['phone']}}" data-ticketno="{{$rows['ticket_no']}}">ဝယ်သူ အချက်အလက် ြပင်ရန်</a>
                                                            </figcaption>
                                                         </figure>
                                                      </div>
                                                   </div>

                                                @else
                                                   <div class="span2">
                                                      <div class="checkboxframe">
                                                         <label>
                                                            <span></span>
                                                            <div class="fit-a {{$taken}}" title="{{$rows['seat_no'].'('. $rows['price'].' K)'}}" id="{{$rows['seat_no']}}">Seat No : <b>{{$rows['seat_no']}}</b><span class="zawgyi-one"><br>Price : <b>{{$rows['price']}} <br> &nbsp;{{$rows['agent_name']}}</b><br>{{$rows['customer']['name']}}<br>{{$rows['customer']['nrc_no']}}</span></div>
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
                              @endif
                           </div>
                        </div>
                     </div>

                  </div>
               </div>
                  @if(count($remarkgroup)>0)
                     <?php $i=1;?>
                     @foreach($remarkgroup as $key=>$remark_typerow)
                        <?php 
                           switch ($key) {
                              case '1':
                                 $remark_type="လမ္းၾကိဳ";
                                 break;
                              case '2':
                                 $remark_type="ေတာင္းရန္";
                                 break;
                              case '3':
                                 $remark_type="ခုံေရြ႕ရန္";
                                 break;
                              case '4':
                                 $remark_type="Date Chanage ရန္";
                                 break;
                              
                              default:
                                  $remark_type="စည္းဖ်က္";
                                 break;
                           }
                        ?>
                        @if($i%3==1)
                        <div class="row-fluid">
                        @endif
                           <div class="span4 rm_typeframe">
                                 <h4 class="rm_heading">{{$remark_type}}</h4>
                                 <div class="row-fluid padding_rmtype">
                                    <div class="span4"><b>Seat No</b><br>
                                    </div>
                                    <div class="span8"><b>Remark</b></div> 

                                 </div>
                                 <div class="clearfix"><hr></div> 

                                 @foreach($remark_typerow as $remarkrow)
                                    @if(count($remarkrow['saleitems'])>0)
                                       <div class="row-fluid padding_rmtype">
                                          <div class="span4">
                                             <?php $j=1;?>
                                             @foreach($remarkrow['saleitems'] as $seats)
                                                <span>{{$seats['seat_no']}}</span>
                                                @if($j%4==0)
                                                   <div>&nbsp;</div>
                                                @endif
                                                <?php $j++;?>
                                             @endforeach
                                          </div>
                                          <div class="span8">
                                             @if($key==5)
                                                {{$remarkrow['name']}}
                                                <br>
                                             @endif
                                             @if($remarkrow['remark']) {{$remarkrow['remark']}} @else - @endif
                                          </div>
                                       </div>
                                       <div class="clearfix"><hr></div> 
                                    @endif
                                 @endforeach

                           </div>
                        @if($i%3==0 || $i==count($remarkgroup)) 
                        </div>
                        <div class="clearfix"><br></div> 
                        @endif
                        <?php $i++;?>
                     @endforeach
                  @endif

               
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
            var phone= $(this).data('phone');
            var ticket_no= $(this).data('ticketno');
            // alert(ticket_no);
            $('#customer_name').val(name);
            $('#customer_nrc').val(nrc);
            $('#phone').val(phone);
            $('#ticket_no').val(ticket_no);
            document.getElementById('update-form').action=link;
            e.preventDefault();
         });
          $(document).foundation();
         

         /*$('.print').click(function() {
           w=window.open();
           w.document.write($('#contentprint').html());
           w.print();
           w.close();
        });
*/



        
      });
      $('.print').click(function() {
            //Get the HTML of div
            var divElements = document.getElementById('contentprint').innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML = 
              '<html><head><title>Report</title></head><body>' + 
              divElements + "</body>";
            //Print Page
            window.print();
            //Restore orignal HTML
            document.body.innerHTML = oldPage;
            return false;
         });
   </script>
@stop
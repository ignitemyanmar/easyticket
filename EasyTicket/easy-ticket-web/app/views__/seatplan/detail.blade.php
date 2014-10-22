@extends('../admin')
@section('content')
{{HTML::script('../../js/chooseseats.js')}}
{{HTML::style('../../css/Tixato_files/seating_builder.css')}}
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
                        SeatPlan Lsit
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="/seatplanlist">SeatPlan</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#"> SeatPlan Lsit</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
               <div class="row-fluid">
                  <div class="responsive span7" data-tablet="span7" data-desktop="span8">
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-user"></i> SeatPlan Detail</h4>
                           
                        </div>

                        <div class="portlet-body">
                        @if($parameter)
                          <?php 
                            $column   =$parameter['column'];
                            $row      =$parameter['row'];
                            $seatlist =$parameter['seat_list'];
                            $width    =(100%$column);
                            $seatno   =1;

                          ?>
                          <table style="width:100%">
                            <?php $k=0; ?>
                            @for($i=1;$i<=$parameter['row'];$i++)
                            <tr>
                              @for($j=1;$j<=$parameter['column'];$j++)
                              <td>
                                <div class="checkboxframe">
                                        <label>
                                            <span></span>
                                              @if($seatlist[$k]['status']==0)
                                              <input  type="text"  value="NO SEAT" name="seats[]"  style="width:65px;" readonly>
                                              @else
                                              <input  type="text"  value="{{ $seatlist[$k]['seat_no']}}" name="seats[]"  style="width:65px;">
                                              @endif
                                            <input type="hidden" value="" class="price">
                                            <input type="hidden" value="{{$seatno}}" class="seatno{{$seatno}}">
                                        </label>
                                </div>
                              </td>
                                <?php $seatno++; $k++; ?>

                              @endfor
                            </tr>
                            @endfor

                          </table>
                        @endif
                        </div>
                     </div>
                  </div>
                   <div class="row-fluid">
                   </div>
                
               </div>
            <!-- END PAGE CONTENT--> 

         </div>
      <!-- END PAGE CONTAINER-->

   </div>
<!-- END PAGE -->  
   <script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
   
@stop
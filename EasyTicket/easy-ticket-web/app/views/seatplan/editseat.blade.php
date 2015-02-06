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
                        Edit Seat Layout Design
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        
                        <li><a href="#">Edit Seat Layout  </a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
              <form action="/updateseatplan/{{$seatplan['id']}}" method="post" class="horizontal-form" enctype="multipart/form-data">
                <input type="hidden" name="access_token" value="{{$myApp->v_access_token}}">
                <div class="row-fluid">
                  <div class="responsive span8" data-tablet="span8" data-desktop="span8">
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-user"></i>Edit Seat Layout</h4>
                           
                        </div>

                        <div class="portlet-body">
                           <div id='seatlayoutframe'>
                              @if($seatplan)
                                <?php 
                                  $column   =$seatplan['column'];
                                  $row      =$seatplan['row'];
                                  $seatlist =$seatplan['seat_list'];
                                  $width    =(100%$column);
                                  $seatno   =1;
                                ?>
                                <table style="width:100%">
                                  <?php $k=0; ?>
                                  @for($i=1;$i<=$seatplan['row'];$i++)
                                  <tr>
                                    @for($j=1;$j<=$seatplan['column'];$j++)
                                    <td>
                                      <div class="checkboxframe">
                                              <label>
                                                  <span></span>
                                                    @if($seatlist[$k]==0)
                                                    <input  type="text"  value="No Seat" name="seats[]"  style="width:65px;" readonly>
                                                    @else
                                                   
                                                      @if($seat_no_list)
                                                      <?php
                                                        $seat_no=$seat_no_list[$k]['seat_no']; 
                                                      ?>
                                                      @else
                                                        <?php $seat_no=''; ?>
                                                      @endif
                                                      <input  type="text"  value="{{$seat_no}}" name="seats[]"  style="width:65px;">
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

                            <div class="cleardiv">&nbsp;</div>
                            <div class="controls">
                                  <input type = "submit" value = "Submit" class="btn green button-submit" id="btn_create" />
                            </div>
                        </div>
                     </div>
                  </div>
               </div>
           </form>

            <!-- END PAGE CONTENT--> 

         </div>
      <!-- END PAGE CONTAINER-->

   </div>
<!-- END PAGE -->
<script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
@stop
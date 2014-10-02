@extends('../admin')
@section('content')
{{HTML::script('../../js/chooseseats.js')}}
{{HTML::style('../../css/Tixato_files/seating_builder.css')}}
<style type="text/css">
  body .ui-tooltip, .arrow:after {
      background: transparent;
      border: 0px solid #FF8004;
  }
  .arrow{position: relative;
      left: 64%;
      margin-left: 0px;
      box-shadow: transparent;
    }
  div.checker span {
      opacity: 0;
  }
    /*.padding-5{padding: 5px;}*/
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
                        Edit SeatLayout Design
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="/itemlist">Seat Layout</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">Edit Seat Layout  </a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
              <form action="/updateseatlayout/{{$response['seatlayout']['id']}}" method="post" class="horizontal-form" enctype="multipart/form-data">

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
                                                    <input  type="text"  value="" name="seats[]"  style="width:65px;">
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
                 <!-- <div class="responsive span4 border padding-5" data-tablet="span4" data-desktop="span4">
                        <h3 class="form-section">SeatLayout Information</h3>
                        <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="tickettype">Ticket Type</label>
                                       <div class="controls">
                                          <select name="tickettype" id='ticket_type_id' class="m-wrap span12">
                                             @foreach($response['tickettype'] as $objtickettype)
                                                <option value="{{$objtickettype->id}}" @if( $objtickettype['id']==$response['seatlayout']['ticket_type_id']) selected @endif>{{$objtickettype['name']}}</option>
                                             @endforeach   
                                          </select>    
                                       </div>
                                    </div>
                                 </div>
                        </div>
                        <div class="row-fluid">
                             <div class="span6">
                                <div class="control-group">
                                   <label class="control-label" for="row">Row</label>
                                   <div class="controls">
                                      <input id='row' name="row" class="m-wrap span12"  type="text" required="required" value="{{$response['seatlayout']['row']}}">
                                   </div>
                                </div>
                             </div>
                        </div>
                        <div class="row-fluid">
                             <div class="span6">
                                <div class="control-group">
                                   <label class="control-label" for="column">Column</label>
                                   <div class="controls">
                                      <input id='column' name="column" class="m-wrap span12"  type="text" required="required" value="{{$response['seatlayout']['column']}}">
                                   </div>
                                </div>
                             </div>
                        </div>
                       

                        <div class="form-actions clearfix">
                           <div class="btn green button-submit" id="btnseatlayout">Submit</div>
                        </div>

                  </div> -->
               </div>
           </form>

            <!-- END PAGE CONTENT--> 

         </div>
      <!-- END PAGE CONTAINER-->

   </div>
<!-- END PAGE -->
<script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
@stop
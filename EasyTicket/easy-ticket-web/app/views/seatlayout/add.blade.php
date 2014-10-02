@extends('../admin')
@section('content')

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
                        SeatLayout Design
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
                        <li><a href="#">Seat Layout list </a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
              <form action="/addseatlayout" method="post" class="horizontal-form" enctype="multipart/form-data">

               <div class="row-fluid">
                  <div class="responsive span8" data-tablet="span8" data-desktop="span8">
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-user"></i>Seat Layout</h4>
                           
                        </div>

                        <div class="portlet-body">
                           <div id='seatlayoutframe'>

                           </div>

                            <div class="cleardiv">&nbsp;</div>
                            <div class="controls">
                                  <input type = "submit" value = "Submit" class="btn green button-submit" id="btn_create" />
                            </div>
                        </div>
                     </div>
                  </div>
                  <div class="responsive padding-10 span4 border" data-tablet="span4" data-desktop="span4">
                      <form action="">
                        <h3 class="form-section">SeatLayout Information</h3>
                        <div class="row-fluid">
                            <div class="span1">&nbsp;</div>
                            <div class="span6">
                              <div class="control-group">
                                 <label class="control-label" for="tickettype">Ticket Type</label>
                                 <div class="controls">
                                    <select name="tickettype" id='ticket_type_id' class="m-wrap span12">
                                       @foreach($tickettype as $rows)
                                          <option value="{{$rows->id}}">{{$rows->name}}</option>
                                       @endforeach   
                                    </select>    
                                 </div>
                              </div>
                            </div>
                            <div class="span5">&nbsp;</div>
                        </div>

                        <div class="row-fluid">
                            <div class="span1">&nbsp;</div>
                             <div class="span6">
                                <div class="control-group">
                                   <label class="control-label" for="row">Row</label>
                                   <div class="controls">
                                      <input id='row' name="row" class="m-wrap span12"  type="text">
                                   </div>
                                </div>
                             </div>
                            <div class="span5">&nbsp;</div>
                        </div>
                        <div class="row-fluid">
                            <div class="span1">&nbsp;</div>
                              <div class="span6">
                                <div class="control-group">
                                   <label class="control-label" for="column">Column</label>
                                   <div class="controls">
                                      <input id='column' name="column" class="m-wrap span12"  type="text">
                                   </div>
                                </div>
                              </div>
                            <div class="span5">&nbsp;</div>

                        </div>
                        <div class="form-actions clearfix">
                           <div class="btn green button-submit" id="btnseatlayout">Submit</div>
                        </div>
                      </form>

                  </div>
               </div>
           </form>

            <!-- END PAGE CONTENT--> 

         </div>
      <!-- END PAGE CONTAINER-->

   </div>
<!-- END PAGE -->
<script type="text/javascript" src="../../assets/data-tables/jquery.dataTables.js"></script>
<script>
  $('#btnseatlayout').click(function(){
        var tickettypeid=$('#ticket_type_id').val();
        var row=$('#row').val();
        var column=$('#column').val();
         $.ajax({
                type:'GET',
                url:'/seatlayoutframe',
                data:{tickettypeid:tickettypeid,row:row,column:column}
              }).done(function(result){
                $('#seatlayoutframe').html(result);
                
              });
         return false;
      });
</script>
@stop
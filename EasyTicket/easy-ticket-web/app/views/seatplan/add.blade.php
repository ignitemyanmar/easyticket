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
                        Seat Plan Design
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="/itemlist">Seat Plan</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">Add Seat Plan  </a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
              <form action="/addseatplan" method="post" class="horizontal-form" enctype="multipart/form-data">
                <input type="hidden" value="{{$myApp->v_access_token}}" name="access_token">
                <div class="row-fluid">
                  <div class="responsive span8" data-tablet="span8" data-desktop="span8">
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-user"></i>Seat Plan</h4>
                           
                        </div>

                        <div class="portlet-body">
                           <div id='makeseatplan'>
                                
                           </div>

                            <div class="cleardiv">&nbsp;</div>
                            <div class="controls">
                                    <input type = "submit" value = "Save" class="btn green button-submit" id="btn_create" />
                            </div>
                        </div>
                     </div>
                  </div>
                 <div class="responsive span4 border padding-5" data-tablet="span4" data-desktop="span4">
                    <h3 class="form-section">Seat Plan Information</h3>
                        <div class="row-fluid" style="display:none;">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="operator">Operator</label>
                                       <div class="controls">
                                          <select name="operator" id='operator' class="m-wrap span12">
                                          @foreach($operator as $rows)
                                                <option value="{{$rows->id}}" @if(strtolower($rows->name)=="Mandalar Min") selected @endif>{{$rows->name}}</option>
                                          @endforeach      
                                          </select>    
                                       </div>
                                    </div>
                                 </div>
                        </div>
                        <div class="row-fluid"  style="display:none;">
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
                        </div>
                        
                       <div class="row-fluid">
                                 <div class="span6">
                                    <div class="control-group">
                                       <label class="control-label" for="seatlayout">Seat Layout</label>
                                       <div class="controls">
                                          <select name="seat_layout" id='seat_layout_id' class="m-wrap span12" >
                                          @foreach($seatlayout as $rows)
                                                <option onchange="#seat_layout" value="{{$rows->id}}">row-{{$rows->row}},&nbsp col-{{$rows->column}}</option>
                                          @endforeach      
                                          </select>    
                                       </div>
                                    </div>
                                 </div>
                        </div>

                       <!--  <div class="form-actions clearfix">
                           <div class="btn green button-submit" id="btnseatlayout">Submit</div>
                        </div> -->

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
  $(function(){
      var _token={{json_encode($myApp->access_token)}};
      $('#seat_layout_id').change(function(){
        // alert('hi');
        var seat_layout_id = $('#seat_layout_id').val();
        $.ajax({
                type:'GET',
                url:'/makeseatplan?'+_token,
                data:{seat_layout_id:seat_layout_id}
        }).done(function(result)
        {
          $('#makeseatplan').html(result);
        });
      return false;
  });
});
  
</script>
@stop
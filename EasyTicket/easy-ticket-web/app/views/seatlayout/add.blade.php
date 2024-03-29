@extends('../admin')
@section('content')
<style type="text/css">
  .radios{opacity: 0;}
  .fit-a{width: 90%; height: 60px;}
  .noseat{background: #ccc;}
  .available{background: #0f0;}
</style>
<!-- BEGIN PAGE -->  
   <div class="page-content">
      <!-- BEGIN PAGE CONTAINER-->
         <div class="container-fluid">
            <!-- BEGIN PAGE HEADER-->   
               <div class="row-fluid">
                  <div class="span12">
                     
                     <h3 class="page-title">
                        ခုံအေနအထားပုံစံ
                        <!-- <small>form wizard sample</small> -->
                     </h3>
                     <ul class="breadcrumb">
                        <li>
                           <i class="icon-home"></i>
                           <a href="/">Home</a> 
                           <span class="icon-angle-right"></span>
                        </li>
                        <li>
                           <a href="/seatlayoutlist">ခုံအေနအထားပုံစံမ်ား</a>
                           <span class="icon-angle-right"></span>
                        </li>
                        <li><a href="#">ခုံအေနအထားပုံစံ ခုံအေနအထားပုံစံ အသစ္ထည့္မည္</a></li>
                     </ul>
                  </div>
               </div>
            <!-- END PAGE HEADER-->

            <!-- BEGIN PAGE CONTENT-->
              <form action="/addseatlayout" method="post" class="horizontal-form" enctype="multipart/form-data">
                <input type="hidden" name="access_token" value="{{$myApp->v_access_token}}">
                <div class="row-fluid">
                  <div class="responsive span8" data-tablet="span8" data-desktop="span8">
                     <div class="portlet box light-grey">
                        <div class="portlet-title">
                           <h4><i class="icon-user"></i>ခုံအေနအထားပုံစံ</h4>
                           
                        </div>

                        <div class="portlet-body">
                           <div id='seatlayoutframe'>

                           </div>

                            <div class="cleardiv">&nbsp;</div>
                            <div class="controls">
                                  <input type = "submit" value = "ခုံအေနအထားပုံစံ သတ္မွတ္မည္" class="btn green button-submit" id="btn_create" />
                            </div>
                        </div>
                     </div>
                  </div>
                  <div class="responsive padding-10 span4 border" data-tablet="span4" data-desktop="span4">
                      <form action="">
                        <h3 class="form-section" style="margin-left:1rem;">ခုံအေနအထားပုံစံ</h3>
                        <div class="row-fluid" style="display:none;">
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
                           <div class="btn green button-submit" id="btnseatlayout">ခုံ (Rows & Columns) သတ္မွတ္မည္</div>
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
        var access_token={{json_encode($myApp->access_token)}};
          $.ajax({
                type:'GET',
                url:'/seatlayoutframe?'+access_token,
                data:{tickettypeid:tickettypeid,row:row,column:column}
              }).done(function(result){
                $('#seatlayoutframe').html(result);
              });
         return false;
      });
</script>

@stop
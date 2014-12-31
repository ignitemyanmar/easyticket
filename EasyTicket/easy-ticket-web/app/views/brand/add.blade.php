@extends('../admin')
@section('content')
{{HTML::style('../../assets/chosen-bootstrap/chosen/chosen.css')}}
{{HTML::style('../../css/upload.css')}}
<!-- BEGIN PAGE -->
		<div class="page-content">
			<!-- BEGIN PAGE CONTAINER-->
			<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
						<h3 class="page-title">
							Add New Brand				
							<small></small>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="/">Home</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="/brand">Brand List</a></li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
						<div class="row-fluid">
						   <div class="span12">
						      <!-- BEGIN SAMPLE FORM PORTLET-->   
						      <div class="portlet box blue">
						         <div class="portlet-title">
						            <h4><i class="icon-reorder"></i>Create Brand</h4>
						         </div>
						         <div class="portlet-body form">
						            <!-- BEGIN FORM-->
						            <form class="form-horizontal" action = "/brand" method= "post" enctype="multipart/form-data">
						                <div class="control-group">
						                  <label class="control-label">Menu</label>
						                  <div class="controls">
						                  	<select name="menu_id" id="menu_id" class="chosen span6">
						                  		@foreach($menu as $row)
						                  			<option value="{{$row->id}}">{{$row->name. '( '. $row->name_mm .' )'}}</option>
						                  		@endforeach
						                  	</select>
						                     <!-- <span class="help-inline">Some hint here</span> -->
						                  </div>
						                </div>
						                <div class="control-group">
						                  <label class="control-label">Brand</label>
						                  <div class="controls">
						                     <input type="text" class="span6 m-wrap" name="name" required/>
						                     <!-- <span class="help-inline">Some hint here</span> -->
						                  </div>
						                </div>
						                <div class="control-group">
						                  <label class="control-label">Brand (Myanmar)</label>
						                  <div class="controls">
						                     <input type="text" class="span6 m-wrap" name="name_mm" required/>
						                     <!-- <span class="help-inline">Some hint here</span> -->
						                  </div>
						                </div>
						                
						                <div class="control-group">
						                  <label class="control-label">ျမန္မာစာျဖင့္ ရွာေဖြရန္ (အတုိေကာက္)</label>
						                  <div class="controls">
						                     <input type="text" class="span6 m-wrap" name="search_key_mm" required/>
						                     <p class="help-block">ဥပမာ. ပနဆန (ပန္နဆုိးနစ္)</p>
						                  </div>
						                </div>

						                <div class="control-group">
						                  <label class="control-label">Image</label>
											<div class="controls images-frame">
                                                <div class="gallery-input">
                                                   <ul>
                                                      <div class="gallery_container1">
                                                      </div>
                                                      <div class="script"></div>
                                                      <div class="upload1">
                                                         <span>+</span>
                                                         <input type="file" id="gallery_upload1" data-url="../brandphoto/php/index.php">
                                                      </div>
                                                   </ul>
                                                </div>
	                                        </div>
						                </div>

						                <div class="control-group">
	                                        <div class="span2">&nbsp;</div>
	                                        <div class="span10 responsive">
                                               <span class="label label-important">NOTE!</span>
                                               <span>Maximun image size is (200px X 200px ) minimun size is (30px X 30px).</span>
                                            </div>
                                        </div>

                                        <div class="control-group">
						                  <label class="control-label">Priority (Sorting)</label>
						                  <div class="controls">
						                  	<select name="priority" id="sorting" class="chosen span6">
						                  		@for($i=0; $i < 12; $i++)
						                  			<?php $same=false; ?>
						                  			@foreach($listsorting as $sorting)
						                  				@if($i ==$sorting)
						                  					<?php $same=true; ?>
						                  				@endif
						                  			@endforeach
						                  			@if($same==false)
						                  				<option value="{{$i}}">@if($i==0) Normal @else {{$i}} @endif</option>
						                  			@endif
						                  		@endfor
						                  	</select>
						                  </div>
						                </div>
						              
						                <div class="form-actions">
						                  <button type="submit" class="btn blue">Submit</button>
						                  <button type="button" class="btn">Cancel</button>
						                </div>
						            </form>
						            <!-- END FORM-->           
						         </div>
						      </div>
						      <!-- END SAMPLE FORM PORTLET-->
						   </div>
						</div>
					<!-- END DASHBOARD STATS -->
					
				</div>
			</div>
			<!-- END PAGE CONTAINER-->		
		</div>
	<!-- END PAGE -->	
{{HTML::script('../../../src/jquery-ui.js')}}
{{HTML::script('../../../src/jquery.fileupload.js')}}
{{HTML::script('../../js/admin/itemupload1.js')}}
{{HTML::script('../../assets/chosen-bootstrap/chosen/chosen.jquery.min.js')}}
<script type="text/javascript">
	$(function(){
		$('.chosen').chosen();
	});
</script>

@stop
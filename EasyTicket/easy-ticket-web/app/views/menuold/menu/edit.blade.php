@extends('../admin')
@section('content')
{{HTML::style('../../css/upload.css')}}
<!-- BEGIN PAGE -->
		<div class="page-content">
			<!-- BEGIN PAGE CONTAINER-->
			<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
						<h3 class="page-title">
							Edit menu				
							<small></small>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="/">Home</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="/menu">Menu List</a></li>
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
						            <h4><i class="icon-reorder"></i>Update Menu</h4>
						         </div>
						         <div class="portlet-body form">
						            <!-- BEGIN FORM-->
						            <form class="form-horizontal" action = "/menu/update/{{$response->id}}" method= "post" enctype="multipart/form-data">
						                <div class="control-group">
						                  <label class="control-label">Menu</label>
						                  <div class="controls">
						                     <input type="text" class="span6 m-wrap" name="name" value="{{$response->name}}" required/>
						                     <!-- <span class="help-inline">Some hint here</span> -->
						                  </div>
						                </div>
						                <div class="control-group">
						                  <label class="control-label">Menu (Myanmar)</label>
						                  <div class="controls">
						                     <input type="text" class="span6 m-wrap" name="name_mm" value="{{$response->name_mm}}" required/>
						                     <!-- <span class="help-inline">Some hint here</span> -->
						                  </div>
						                </div>

						                <div class="control-group">
						                  <label class="control-label">ျမန္မာစာျဖင့္ ရွာေဖြရန္ (အတုိေကာက္)</label>
						                  <div class="controls">
						                     <input type="text" class="span6 m-wrap" name="search_key_mm" value="{{$response->search_key_mm}}" required/>
						                     <p class="help-block">ဥပမာ. ပနဆန (ပန္နဆုိးနစ္)</p>
						                  </div>
						                </div>
						               
						                <div class="control-group">
						                  <label class="control-label">Image</label>
											<div class="controls images-frame">
                                                <div class="gallery-input">
                                                   <ul>
                                                      <div class="gallery_container1">
                                                      	<li class="gallery_photo right">
															<img src="../../menuphoto/php/files/thumbnail/{{ $response['image'] }}"></img>
															<span class="icon-cancel-circle"></span><input type="hidden" value="{{ $response['image'] }}" name="hdimage"></input></span>
															<script>
														      $(".gallery_photo").click(function(){$(this).remove();$(".upload1").show();$(".upload1 span").html("+");});
														    </script>
														</li>
                                                      </div>
                                                      <div class="script"></div>
                                                      <div class="upload1" style="display:none">
                                                         <span>+</span>
                                                         <input type="file" id="gallery_upload1" data-url="../../menuphoto/php/index.php">
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
						                  <label class="control-label">Item Code Prefix</label>
						                  <div class="controls">
						                     <input type="text" class="span6 m-wrap" name="itemcode_prefix" value="{{$response->itemcode_prefix}}" required/>
						                     <p class="help-block">eg. "E" or "EL" for Electronic.</p>
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

@stop
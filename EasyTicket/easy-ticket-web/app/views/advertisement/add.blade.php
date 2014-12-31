@extends('../admin')
@section('content')
{{HTML::style('../../css/upload.css')}}
{{HTML::style('../../assets/chosen-bootstrap/chosen/chosen.css')}}

<!-- BEGIN PAGE -->
		<div class="page-content">
			<!-- BEGIN PAGE CONTAINER-->
			<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
						<h3 class="page-title">
							Add New Advertisement				
							<small></small>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="/">Home</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="/advertisementlist">Advertisement List</a></li>
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
						            <h4><i class="icon-reorder"></i>Create Advertisement</h4>
						         </div>
						         <div class="portlet-body form">
						            <!-- BEGIN FORM-->
						            <form class="form-horizontal" action = "/advertisement" method= "post" enctype="multipart/form-data">
						                <div class="control-group">
						                  <label class="control-label">Default Link</label>
						                  <div class="controls">
						                  <select name="menu_id" class="chosen">
							                  @foreach($menu as $row)
							                  	<option value="{{$row->id}}">{{$row->name}}</option>
							                  @endforeach
						                  </select>
						                  </div>
						                </div>
						                <div class="control-group">
						                  <label class="control-label">Position</label>
						                  <div class="controls">
						                  <select name="position" class="chosen">
							                  @foreach($position as $value)
							                  	<option value="{{$value}}">{{$value}}</option>
							                  @endforeach
						                  </select>
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
                                                         <input type="file" id="gallery_upload1" data-url="../advertisementphoto/php/index.php">
                                                      </div>
                                                   </ul>
                                                </div>
	                                        </div>
						                </div>
						                <div class="control-group">
							                <div class="span2 responsive">&nbsp;</div>
							                <div class="span10 responsive">
	                                           <span class="label label-important">NOTE!</span>
	                                           <span>Image size for maximun width is and height is (1024px X 455px) and minimun width and height is (200px X 200px).<br>
	                                           For Group sale, Free Gift, Time Sale maximun Width and height(1024px X 315px).
	                                           </span>
                                        	</div>
                                        </div>

                                        <div class="control-group">
						                  <label class="control-label">Custom Link</label>
						                  <div class="controls">
						                     <input type="text" class="span6 m-wrap" name="link"/>
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
{{HTML::script('../../js/admin/txtedt_dpic_create.js')}}
{{HTML::script('../../assets/chosen-bootstrap/chosen/chosen.jquery.min.js')}}


@stop
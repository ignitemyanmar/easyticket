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
							Edit Color				
							<small></small>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="/">Home</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="/color">Color List</a></li>
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
						            <h4><i class="icon-reorder"></i>Update Color</h4>
						         </div>
						         <div class="portlet-body form">
						            <!-- BEGIN FORM-->
						            <form class="form-horizontal" action = "/color/update/{{$response->id}}" method= "post" enctype="multipart/form-data">
						                <div class="control-group">
						                  <label class="control-label">Color</label>
						                  <div class="controls">
						                     <input type="text" class="span6 m-wrap" name="name" value="{{$response->name}}" required/>
						                     <!-- <span class="help-inline">Some hint here</span> -->
						                  </div>
						                </div>
						                <div class="control-group">
						                  <label class="control-label">Color (Myanmar)</label>
						                  <div class="controls">
						                     <input type="text" class="span6 m-wrap" name="name_mm" value="{{$response->name_mm}}" required/>
						                     <!-- <span class="help-inline">Some hint here</span> -->
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

@stop
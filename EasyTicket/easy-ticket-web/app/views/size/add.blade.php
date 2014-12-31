@extends('../admin')
@section('content')
{{HTML::style('../../assets/chosen-bootstrap/chosen/chosen.css')}}
<!-- BEGIN PAGE -->
		<div class="page-content">
			<!-- BEGIN PAGE CONTAINER-->
			<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
						<h3 class="page-title">
							Add New Size				
							<small></small>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="/">Home</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="/size">Size List</a></li>
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
						            <h4><i class="icon-reorder"></i>Create Size</h4>
						         </div>
						         <div class="portlet-body form">
						            <!-- BEGIN FORM-->
						            <form class="form-horizontal" action = "/size" method= "post" enctype="multipart/form-data">
						                <div class="control-group">
						                  <label class="control-label">Category</label>
						                  <div class="controls">
						                  	<select name="category_id" id="category_id" class="chosen span6">
						                  		@foreach($category as $row)
						                  			<option value="{{$row->id}}">{{$row->name. '( '. $row->name_mm .' )'}}</option>
						                  		@endforeach
						                  	</select>
						                     <!-- <span class="help-inline">Some hint here</span> -->
						                  </div>
						                </div>
						                <div class="control-group">
						                  <label class="control-label">Size</label>
						                  <div class="controls">
						                     <input type="text" class="span6 m-wrap" name="name" required/>
						                     <!-- <span class="help-inline">Some hint here</span> -->
						                  </div>
						                </div>
						                <div class="control-group">
						                  <label class="control-label">Size (Myanmar)</label>
						                  <div class="controls">
						                     <input type="text" class="span6 m-wrap" name="name_mm" required/>
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
{{HTML::script('../../assets/chosen-bootstrap/chosen/chosen.jquery.min.js')}}
<script type="text/javascript">
	$(function(){
		$('.chosen').chosen();
	});
</script>

@stop
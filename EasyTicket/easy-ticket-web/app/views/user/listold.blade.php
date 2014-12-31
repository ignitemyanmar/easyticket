@extends('../admin')
@section('content')
    {{HTML::style('../../../css/hover/component.css')}}
    {{HTML::style('../../../css/hover/default.css')}}
<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/metro.css" rel="stylesheet" />
	<link href="assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
	<link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
	<link href="assets/css/style.css" rel="stylesheet" />
	<link href="assets/css/style_responsive.css" rel="stylesheet" />
	<link href="assets/css/style_default.css" rel="stylesheet" id="style_color" />
	<link href="assets/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="assets/uniform/css/uniform.default.css" />
	<link rel="stylesheet" type="text/css" href="assets/chosen-bootstrap/chosen/chosen.css" />
	<link rel="stylesheet" href="assets/data-tables/DT_bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="assets/uniform/css/uniform.default.css" />
	<link rel="shortcut icon" href="favicon.ico" />
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
							<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
							<h3 class="page-title">
								Dashboard				
								<small></small>
							</h3>
							<ul class="breadcrumb">
								<li>
									<i class="icon-home"></i>
									<a href="/">Home</a> 
									<i class="icon-angle-right"></i>
								</li>
								<li><a href="/dashboard">Dashboard</a></li>
								
							</ul>
							<!-- END PAGE TITLE & BREADCRUMB-->
						</div>
					</div>
				<!-- END PAGE HEADER-->
				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
						<div class="portlet box light-grey">
							<div class="portlet-title">
								<h4><i class="icon-globe"></i>Managed Table</h4>
								<div class="tools">
									<a href="javascript:;" class="collapse"></a>
									<a href="#portlet-config" data-toggle="modal" class="config"></a>
									<a href="javascript:;" class="reload"></a>
									<a href="javascript:;" class="remove"></a>
								</div>
							</div>
							<div class="portlet-body">
								<div class="clearfix">
									<div class="btn-group">
										<button id="sample_editable_1_new" class="btn green">
										Add New <i class="icon-plus"></i>
										</button>
									</div>
									<div class="btn-group pull-right">
										<button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="icon-angle-down"></i>
										</button>
										<ul class="dropdown-menu">
											<li><a href="#">Print</a></li>
											<li><a href="#">Save as PDF</a></li>
											<li><a href="#">Export to Excel</a></li>
										</ul>
									</div>
								</div>
								<div id="sample_1_wrapper" class="dataTables_wrapper form-inline" role="grid"><div class="row-fluid"><div class="span6"><div class="dataTables_length" id="sample_1_length"><label><select class="m-wrap xsmall" aria-controls="sample_1" size="1" name="sample_1_length"><option value="10">5</option><option value="25">15</option><option value="50">20</option><option value="-1">All</option></select> records per page</label></div></div><div class="span6"><div id="sample_1_filter" class="dataTables_filter"><label>Search: <input class="m-wrap medium" aria-controls="sample_1" type="text"></label></div></div></div><table aria-describedby="sample_1_info" class="table table-striped table-bordered table-hover dataTable" id="sample_1">
									<thead>
										<tr role="row"><th aria-label="" colspan="1" rowspan="1" role="columnheader" class="sorting_disabled" style="width: 24px;"><div id="uniform-undefined" class="checker"><span><input style="opacity: 0;" class="group-checkable" data-set="#sample_1 .checkboxes" type="checkbox"></span></div></th><th aria-label="Username: activate to sort column ascending" style="width: 153px;" colspan="1" rowspan="1" aria-controls="sample_1" tabindex="0" role="columnheader" class="sorting">Username</th><th aria-label="Email: activate to sort column descending" aria-sort="ascending" style="width: 279px;" colspan="1" rowspan="1" aria-controls="sample_1" tabindex="0" role="columnheader" class="hidden-480 sorting_asc">Email</th><th aria-label="Points: activate to sort column ascending" style="width: 99px;" colspan="1" rowspan="1" aria-controls="sample_1" tabindex="0" role="columnheader" class="hidden-480 sorting">Points</th><th aria-label="Joined: activate to sort column ascending" style="width: 165px;" colspan="1" rowspan="1" aria-controls="sample_1" tabindex="0" role="columnheader" class="hidden-480 sorting">Joined</th><th aria-label=": activate to sort column ascending" style="width: 154px;" colspan="1" rowspan="1" aria-controls="sample_1" tabindex="0" role="columnheader" class="sorting"></th></tr>
									</thead>
									
								<tbody aria-relevant="all" aria-live="polite" role="alert"><tr class="odd gradeX">
											<td class=""><div id="uniform-undefined" class="checker"><span><input style="opacity: 0;" class="checkboxes" value="1" type="checkbox"></span></div></td>
											<td class=" ">foopl</td>
											<td class="hidden-480  sorting_1"><a href="mailto:userwow@gmail.com">good@gmail.com</a></td>
											<td class="hidden-480 ">20</td>
											<td class="center hidden-480 ">19.11.2010</td>
											<td class=" "><span class="label label-success">Approved</span></td>
										</tr><tr class="odd gradeX even">
											<td class=""><div id="uniform-undefined" class="checker"><span><input style="opacity: 0;" class="checkboxes" value="1" type="checkbox"></span></div></td>
											<td class=" ">weep</td>
											<td class="hidden-480  sorting_1"><a href="mailto:userwow@gmail.com">good@gmail.com</a></td>
											<td class="hidden-480 ">20</td>
											<td class="center hidden-480 ">19.11.2010</td>
											<td class=" "><span class="label label-success">Approved</span></td>
										</tr><tr class="odd gradeX">
											<td class=""><div id="uniform-undefined" class="checker"><span><input style="opacity: 0;" class="checkboxes" value="1" type="checkbox"></span></div></td>
											<td class=" ">coop</td>
											<td class="hidden-480  sorting_1"><a href="mailto:userwow@gmail.com">good@gmail.com</a></td>
											<td class="hidden-480 ">20</td>
											<td class="center hidden-480 ">19.11.2010</td>
											<td class=" "><span class="label label-success">Approved</span></td>
										</tr><tr class="odd gradeX even">
											<td class=""><div id="uniform-undefined" class="checker"><span><input style="opacity: 0;" class="checkboxes" value="1" type="checkbox"></span></div></td>
											<td class=" ">pppol</td>
											<td class="hidden-480  sorting_1"><a href="mailto:userwow@gmail.com">good@gmail.com</a></td>
											<td class="hidden-480 ">20</td>
											<td class="center hidden-480 ">19.11.2010</td>
											<td class=" "><span class="label label-success">Approved</span></td>
										</tr><tr class="odd gradeX">
											<td class=""><div id="uniform-undefined" class="checker"><span><input style="opacity: 0;" class="checkboxes" value="1" type="checkbox"></span></div></td>
											<td class=" ">test</td>
											<td class="hidden-480  sorting_1"><a href="mailto:userwow@gmail.com">good@gmail.com</a></td>
											<td class="hidden-480 ">20</td>
											<td class="center hidden-480 ">19.11.2010</td>
											<td class=" "><span class="label label-success">Approved</span></td>
										</tr></tbody></table><div class="row-fluid"><div class="span6"><div id="sample_1_info" class="dataTables_info">Showing 1 to 5 of 25 entries</div></div><div class="span6"><div class="dataTables_paginate paging_bootstrap pagination"><ul><li class="prev disabled"><a href="#">← Prev</a></li><li class="active"><a href="#">1</a></li><li><a href="#">2</a></li><li><a href="#">3</a></li><li><a href="#">4</a></li><li><a href="#">5</a></li><li class="next"><a href="#">Next → </a></li></ul></div></div></div></div>
							</div>
						</div>
						
					<!-- END DASHBOARD STATS -->
					
				</div>
			</div>
			<!-- END PAGE CONTAINER-->		
		</div>
		<!-- END PAGE -->	
  <!-- 
  <script src="assets/js/jquery.blockui.js"></script>
  {{HTML::script('../js/hover/modernizr.custom.js')}}
  {{HTML::script('../js/hover/toucheffects.js')}}
  <script type="text/javascript" src="../assets/data-tables/jquery.dataTables.js"></script>
  <script type="text/javascript" src="../assets/data-tables/DT_bootstrap.js"></script>

	<script src="../assets/js/app.js"></script>		
  	<script>
		jQuery(document).ready(function() {			
			// initiate layout and plugins
			App.setPage("table_managed");
			App.init();
		});
	</script>
   -->

   <script src="assets/js/jquery-1.8.3.min.js"></script>	
	<script src="assets/breakpoints/breakpoints.js"></script>	
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>		
	<!-- 
	<script src="assets/js/jquery.blockui.js"></script>
	<script src="assets/js/jquery.cookie.js"></script>
	 -->
	<!-- ie8 fixes -->
	<!--[if lt IE 9]>
	<script src="assets/js/excanvas.js"></script>
	<script src="assets/js/respond.js"></script>
	<![endif]-->	
	<script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>
	<script type="text/javascript" src="assets/data-tables/jquery.dataTables.js"></script>
	<script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>
	<script src="assets/js/app.js"></script>		
	<script>
		jQuery(document).ready(function() {			
			// initiate layout and plugins
			App.setPage("table_managed");
			App.init();
		});
	</script>
@stop
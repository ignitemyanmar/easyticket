@extends('../admin')
@section('content')
	<link rel="stylesheet" href="assets/data-tables/DT_bootstrap.css" />
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
					<!-- BEGIN PAGE -->
						<div class="row-fluid">
							<div class="span12">
								<!-- BEGIN EXAMPLE TABLE PORTLET-->
								<div class="portlet box blue">
									<div class="portlet-title">
										<h4><i class="icon-edit"></i>SubCategory List</h4>
									</div>
									<div class="portlet-body">
										<div class="clearfix">
											<div class="btn-group">
												<a href="/subcategory/create">
												<button id="" class="btn green">
												အသစ္ထည့္ရန္<i class="icon-plus"></i>
												</button>
												</a>
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
										@if(Session::has('message'))
											<?php $message=Session::get('message'); ?>
											@if($message['status']='0')
											<div class="alert alert-info">
												<button class="close" data-dismiss="alert"></button>
												<strong>Exiting ! </strong>This record is already exit.
											</div>
											@else
											<div class="alert alert-success">
												<button class="close" data-dismiss="alert"></button>
												<strong>Success!</strong> One record have been added.
											</div>
											@endif
										@endif
										<table class="table table-striped table-hover table-bordered" id="sample_editable_1">
											<thead>
												<tr>
													<th>Image</th>
													<th>Menu</th>
													<th>Category</th>
													<th>SubCategory (English)</th>
													<th>SubCategory (Myanmar)</th>
													<th>ျမန္မာစာျဖင့္ ရွာေဖြရန္ (အတုိေကာက္)</th>
													<th>Item Code Prefix</th>
													<th>Priority (Sorting)</th>
													<th>ျပင္ရန္</th>
													<th>ဖ်က္ရန္</th>
												</tr>
											</thead>
											<tbody>
												@if($response)
													@foreach($response as $arr_category)
														@foreach($arr_category->subcategories as $row)
														<tr class="">
															<td><img src="../subcategoryphoto/php/files/thumbnail/{{ $row->image}}"></td>
															<td>{{$arr_category->name.' ( '.$arr_category->name_mm.' )'}}</td>
															<td>{{$row->category}}</td>
															<td>{{$row->name}}</td>
															<td>{{$row->name_mm}}</td>
															<td>{{$row->search_key_mm}}</td>
															<td>{{$row->itemcode_prefix}}</td>
															<td>@if($row->priority==1000) Normal @else {{$row->priority}} @endif</td>
															<td><a class="btn large green-stripe edit" href="/subcategory/edit/{{$row->id}}">ျပင္မည္</a></td>
															<td><a class="btn large red-stripe delete" href="subcategory/delete/{{$row->id}}">ဖ်က္မည္</a></td>
														</tr>
														@endforeach	
													@endforeach
												@endif
											</tbody>
										</table>
									</div>
								</div>
								<!-- END EXAMPLE TABLE PORTLET-->
							</div>
						</div>
						
					<!-- END PAGE -->
					
				</div>
			</div>
			<!-- END PAGE CONTAINER-->		
		</div>
		<!-- END PAGE -->	
	<script type="text/javascript" src="assets/data-tables/jquery.dataTables.js"></script>
	<script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>
	<script>
		jQuery(document).ready(function() {			
			// initiate layout and plugins
			App.setPage("table_editable");
			// App.init();
		});
	</script>
@stop
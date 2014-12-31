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
								Order List
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
										<h4><i class="icon-th-list"></i>Order List</h4>
									</div>
									<div class="portlet-body">
										<div class="clearfix">
			                                <div class="btn-group">
			                                    <!--  -->
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
											@if($message['status']==0)
											<div class="alert alert-error">
											@else
											<div class="alert alert-success">
											@endif
												<button class="close" data-dismiss="alert"></button>
												<strong>Info ! </strong>{{$message['info']}}
											</div>
										@endif
										<table class="table table-striped table-hover table-bordered" id="sample_editable_1">
											<thead>
												<tr>
													<th width="80px">Order No.</th>
													<th>Description</th>
													<th width="100px">Amount</th>
													<th width="100px">Order Date</th>
													<th width="100px">Status</th>
													<th width="100px">Action</th>
												</tr>
											</thead>
											<tbody>
												@foreach($order as $rows)
												<tr class="">
													<td>{{$rows->ref_id}}</td>
													<td>
														<b>Shipping Name:</b> {{$rows->shipping->name}}<br>
														<b>Address:</b> {{$rows->shipping->address}}<br>
														<b>Township:</b> {{$rows->shipping->township}}<br>
														<b>City:</b> {{$rows->shipping->city}}<br>
														<b>Phone:</b> {{$rows->shipping->phone}}
													</td>
													<td>{{$rows->total}}</td>
													<td>{{$rows->created_at}}</td>
													<td>
														@if($rows->status == 0)
														<a href="" class="label label-warning">pendding</a>
														@elseif($rows->status == 1)
														<a href="" class="label label-warning">shipping</a>
														@elseif($rows->status == 2)
														<a href="" class="label label-success">complete</a>
														@else
														<a href="" class="label">overdue</a>
														@endif
													</td>
													<td>
														<div class="btn-group">
															<a class="btn green" href="#" data-toggle="dropdown">
															<i class="icon-wrench"></i> ျပင္ဆင္ရန္
															<i class="icon-angle-down"></i>
															</a>
															<ul class="dropdown-menu">
																<li><a href="/order/replymessage/{{$rows->id}}"><i class="icon-edit"><span> Reply Message </span></i></a></li>
																@if($rows->status != 2)
																<li><a href="/order/delivery/{{$rows->id}}"><i class="icon-external-link"></i><span> Delivery </span></a></li>
																<li><a href="/order/complete/{{$rows->id}}"><i class="icon-edit"><span> Complete </span></i></a></li>
																@endif
																<li><a href="/order/detail/{{$rows->id}}"><i class="icon-list"></i><span> View </span></a></li>
																<li><a href="/order/delete/"><i class="icon-remove"></i><span> Delete </span></a></li>
																<li class="divider"></li>
															</ul>
														</div>
													</td>
												</tr>
												@endforeach
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
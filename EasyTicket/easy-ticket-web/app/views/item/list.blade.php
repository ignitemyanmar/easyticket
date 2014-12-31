@extends('../admin')
@section('content')
<!-- <link href="assets/fancybox/source/jquery.fancybox.css" rel="stylesheet" /> -->
<link rel="stylesheet" href="assets/data-tables/DT_bootstrap.css" />
<!-- <link rel="stylesheet" href="../../css/dashboard.css" /> -->
<style type="text/css">
	i.icon, a.icon {
	    color: #000;
	    margin-right: 20px;
	    font-weight: normal;
	    font-size: 19px;
	}
	.item .details {
		background: #7AFEF3;
		color:#000;
		opacity: .9
	}
</style>
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
							Item list			
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="/">Home</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
								<a href="#">Item List</a>
							</li>

						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN EXAMPLE TABLE PORTLET-->
						<div class="portlet box blue">
									<div class="portlet-title">
										<h4><i class="icon-edit"></i>Item List</h4>
									</div>
									<div class="portlet-body">
										<div class="clearfix">
											<div class="btn-group">
												<a href="/item/create">
												<button id="" class="btn green">
												အသစ္ထည့္ရန္။ <i class="icon-plus"></i>
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
											<div class="alert alert-info">
												<button class="close" data-dismiss="alert"></button>
												<strong>Info ! </strong>{{$message}}
											</div>
										@endif
										<table class="table table-striped table-hover table-bordered" id="sample_editable_1">
											<thead>
												<tr>
													<th>ပံု</th>
													<th class="span2">Item Code</th>
													<th class="span2">အမည္ (အဂၤလိပ္)</th>
													<th>အမည္ (ျမန္မာ)</th>
													<th class="span1">ျမန္မာစာျဖင့္ ရွာေဖြရန္ (အတုိေကာက္)</th>
													<th class="span4">အခ်က္အလက္မ်ားျပင္ရန္</th>
													<th>ျပင္ရန္</th>
													<!-- <th>ျပင္ရန္</th> -->
													<!-- <th>ဖ်က္ရန္</th> -->
												</tr>
											</thead>
											<tbody>
												@if($items)
													@foreach($items as $arr_item)
														<tr class="">
															<td><img src="../../itemphoto/php/files/thumbnail/{{$arr_item->image}}" alt="Photo" /></td>
															<td>{{$arr_item->item_code}}</td>
															<td>{{$arr_item->name}}</td>
															<td>{{$arr_item->name_mm}}</td>
															<td>{{$arr_item->search_key_mm}}</td>
															<td>
																Brand : {{ $arr_item->brand}}<br>
																Colors : {{$arr_item->colors }}<br>
																Sizes : {{$arr_item->sizes }}<br>
																Price : {{$arr_item->price }}<br>
																Price by Quantity Ranges...<br>
																@if($arr_item['priceByQtyrange'])
																	@foreach($arr_item['priceByQtyrange'] as $row)
																		<b>{{$row['quantityrange']}}</b> is {{ $row['price']}} MMK<br>
																	@endforeach
																@endif
																Morning/Evening : @if($arr_item->timesale==1) Morning @elseif($arr_item->timesale==2) Evening @else --- @endif<br>
																Freeget : @if($arr_item->freeget==1) Yes @else No @endif<br>

															</td>
															<td>
																<div class="btn-group">
																	<a class="btn green" href="#" data-toggle="dropdown">
																	<i class="icon-wrench"></i> ျပင္ဆင္ရန္
																	<i class="icon-angle-down"></i>
																	</a>
																	<ul class="dropdown-menu">
																		<li>
																			@if($arr_item->publish==0)
																				<a href="/item/publish/{{$arr_item->id}}"><i class="icon-external-link"><span> တင္မည္</span></i></a>
																			@else
																				<!-- <a href="/item/unpublish/{{$arr_item->id}}"><i class="icon-stop"><span> ယာယီ မတင်ေသး</span></i></a> -->
																			@endif
																			</a>
																		</li>
																		<li><a href="/item-detail/{{$arr_item->id}}/{{$arr_item->name}}"><i class="icon-list"></i><span> ေစ်းႏႈန္းျပင္ရန္</span></a></li>
																		<li><a href="/item/edit/{{$arr_item->id}}"><i class="icon-edit"></i><span> ျပင္မည္</span></a></li>
																		<li><a href="/group_item_price/{{$arr_item->id}}"><i class="icon-edit"></i><span> ပူးေပါင္းေစ်း - ႏႈန္း</span></a></li>
																		<li><a href="/item/delete/{{$arr_item->id}}"><i class="icon-remove"></i><span> ဖ်က္မည္</span></a></li>
																		
																		<li class="divider"></li>
																	</ul>
																</div>
															</td>
															<!-- <td>
															<a class="edit" href="/menu/edit/{{$arr_item->id}}">ျပင္မည္</a></td>
															<td><a class="delete" href="/menu/delete/{{$arr_item->id}}">ဖ်က္မည္</a></td> -->
														</tr>	
													@endforeach
												@endif
												
												
											</tbody>
										</table>
									</div>
						</div>
						<!-- END EXAMPLE TABLE PORTLET-->
					</div>
				</div>
				<!-- END PAGE CONTENT -->
			</div>
			<!-- END PAGE CONTAINER-->
		</div>
		<!-- END PAGE -->
	<script type="text/javascript" src="assets/data-tables/jquery.dataTables.js"></script>
	<script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>
	<script>
		jQuery(document).ready(function() {			
			App.setPage("table_editable");
		});
	</script>
@stop
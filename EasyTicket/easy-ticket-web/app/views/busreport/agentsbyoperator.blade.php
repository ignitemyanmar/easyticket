@extends('../admin')
@section('content')
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
								<li><a href="/dashboard?access_token={{Auth::user()->access_token}}&">Agents List By Operator</a></li>
								<li class="pull-right no-text-shadow">
									<div id="dashboard-report-range" class="dashboard-date-range tooltips no-tooltip-on-touch-device responsive" data-tablet="" data-desktop="tooltips" data-placement="top" data-original-title="Change dashboard date range">
										<i class="icon-calendar"></i>
										<span></span>
										<i class="icon-angle-down"></i>
									</div>
								</li>
							</ul>
							<!-- END PAGE TITLE & BREADCRUMB-->
						</div>
					</div>
				<!-- END PAGE HEADER-->
				<div id="dashboard">
					<!-- BEGIN DASHBOARD STATS -->
					@if(!$response)
						<p class="warning">You've not posted any agent yet,</p>
					@else
					<div class="row-fluid">
						<?php $i=2; ?>
						@foreach($response['agents'] as $agent)
							<div class="span6 responsive" data-tablet="span6 @if($i%2==0) fix-offset @else @endif" data-desktop="span6">
								<div class="dashboard-stat blue">
									<div class="visual">
							    		<img src="../../agentphoto/php/files/thumbnail/logo.jpg">
									</div>
									<div class="details">
										<div class="number">
											{{$agent['name']}}
										</div>
										<div class="desc">									
										</div>
									</div>
									<!-- <a class="more" href="/triplist/{{$agent['id']}}/daterange"> -->
									<a class="more" href="/report/operator/trip/dateranges?access_token={{Auth::user()->access_token}}&operator_id={{$response['operator_id']}}">

									Trip list <i class="m-icon-swapright m-icon-white"></i>
									</a>						
								</div>
							</div>
							<?php $i++; ?>
						@endforeach
					</div>
					@endif
					<!-- END DASHBOARD STATS -->
					
				</div>
			</div>
			<!-- END PAGE CONTAINER-->		
		</div>
		<!-- END PAGE -->	
@stop
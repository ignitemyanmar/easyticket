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
							Add New Group Item Price				
							<small></small>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="/">Home</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="/item">Item</a></li>
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
						            <h4><i class="icon-reorder"></i>Create Group Item Price</h4>
						         </div>
						         <div class="portlet-body form">
						            <!-- BEGIN FORM-->
						            <form class="form-horizontal" action = "/group_item_price/store" method= "post" enctype="multipart/form-data">
						                <input type="hidden" name="item_id" value="{{$item_id}}">
						                @if(count($group_item_price) > 0)
						                	@foreach($group_item_price as $rows)
						                	<div class="control-group">
							                  <label class="control-label">Number of Person</label>
							                  <div class="controls">
							                     <input type="number" class="span2 m-wrap" name="number_person[]" value="{{$rows->number_person}}" required/>
							                     <!-- <span class="help-inline">Some hint here</span> -->
							                  </div>
							                </div>
							                <div class="control-group">
							                  <label class="control-label">Percentage</label>
							                  <div class="controls">
							                     <input type="number" class="span2 m-wrap" name="percentage[]" value="{{$rows->percentage}}" required/>
							                     <!-- <span class="help-inline">Some hint here</span> -->
							                  </div>
							                </div>

							                <div class="control-group">
							                  <label class="control-label">Duration</label>
							                  <div class="controls">
							                  	 <?php 
							                  	 	$duration = explode(' ', $rows->duration);
							                   	 ?>
							                     <input type="number" class="span1 m-wrap" name="number_duration[]" @if($duration[0]) value="{{$duration[0]}}" @endif required/>
							                     <select class="span2 m-wrap" name="label_duration[]">
							                     	<option value="week" @if($duration[1]) @if($duration[1] == 'week') selected @endif   @endif>Week</option>
							                     	<option value="month" @if($duration[1]) @if($duration[1] == 'month') selected @endif   @endif>Month</option>
							                     	<option value="year" @if($duration[1]) @if($duration[1] == 'year') selected @endif   @endif>Year</option>
							                     </select>
							                     <p class="help-block">1 week, 2 week or 1 month</p>
							                  </div>
							                </div>
							                <hr/>
						                	@endforeach
						                @endif
						                <div class="template">
							                <div class="group_item_price">
							                	<div class="control-group">
								                  <label class="control-label" onClick="return removediv(this)">- Remove</label>
								                </div>
								                <div class="control-group">
								                  <label class="control-label">Number of Person</label>
								                  <div class="controls">
								                     <input type="number" class="span2 m-wrap" name="number_person[]" required/>
								                     <!-- <span class="help-inline">Some hint here</span> -->
								                  </div>
								                </div>
								                <div class="control-group">
								                  <label class="control-label">Percentage</label>
								                  <div class="controls">
								                     <input type="number" class="span2 m-wrap" name="percentage[]" required/>
								                     <!-- <span class="help-inline">Some hint here</span> -->
								                  </div>
								                </div>

								                <div class="control-group">
								                  <label class="control-label">Duration</label>
								                  <div class="controls">
								                     <input type="number" class="span1 m-wrap" name="number_duration[]" required/>
								                     <select class="span2 m-wrap" name="label_duration[]">
								                     	<option value="week">Week</option>
								                     	<option value="month">Month</option>
								                     	<option value="year">Year</option>
								                     </select>
								                     <p class="help-block">1 week, 2 week or 1 month</p>
								                  </div>
								                </div>
								                <hr/>
								            </div>
						                </div>
						                <div class="holder"></div>
						                <div class="control-group">
						                	<label class="control-label more">+ More</label>
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
<script type="text/javascript">
	$('.more').click(
	function(){
		var template = $('.template').html();
		$('.holder').append(template);
	});
	function removediv(obj){
		var objs=$(obj).parent().parent();
		objs.remove();
		return false;
  	}
</script>
{{HTML::script('../../../src/jquery-ui.js')}}
{{HTML::script('../../../src/jquery.fileupload.js')}}
{{HTML::script('../../js/admin/itemupload1.js')}}

@stop
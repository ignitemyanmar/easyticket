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
							Add New shop				
							<small></small>
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="/">Home</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li><a href="/shop">shop List</a></li>
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
						            <h4><i class="icon-reorder"></i>Create Shop</h4>
						         </div>
						         <div class="portlet-body form">
						            <!-- BEGIN FORM-->
						            <form class="form-horizontal" action = "/shop" method= "post" enctype="multipart/form-data">
						                <div class="control-group">
						                  <label class="control-label">Shop Owner</label>
						                  <div class="controls">
						                  	<select name="user_id" id="menu_id" class="chosen span6">
						                  		@foreach($response['user'] as $row)
						                  			<?php $same=false; ?>
						                  			@foreach($response['shopuser'] as $shopuserid)
						                  				@if($row->id == $shopuserid)
						                  					<?php $same=true; ?>
						                  				@endif
						                  			@endforeach
						                  			@if(!$same)
								                  		<option value="{{$row->id}}">{{$row->name}}</option>
						                  			@endif
						                  		@endforeach
						                  	</select>
						                     <!-- <span class="help-inline">Some hint here</span> -->
						                  </div>
						                </div>

						                <div class="control-group">
						                  <label class="control-label">Shop</label>
						                  <div class="controls">
						                     <input type="text" class="span6 m-wrap" name="name" required/>
						                     <!-- <span class="help-inline">Some hint here</span> -->
						                  </div>
						                </div>
						                <div class="control-group">
						                  <label class="control-label">Shop (Myanmar)</label>
						                  <div class="controls">
						                     <input type="text" class="span6 m-wrap" name="name_mm" required/>
						                     <!-- <span class="help-inline">Some hint here</span> -->
						                  </div>
						                </div>

						                <div class="control-group">
						                  <label class="control-label">ျမန္မာစာျဖင့္ ရွာေဖြရန္ (အတုိေကာက္)</label>
						                  <div class="controls">
						                     <input type="text" class="span6 m-wrap" name="search_key_mm" required/>
						                     <p class="help-block">ဥပမာ. ပနဆန (ပန္နဆုိးနစ္)</p>
						                  </div>
						                </div>

						                <div class="control-group">
						                  <label class="control-label">Phone</label>
						                  <div class="controls">
						                     <input type="text" class="span6 m-wrap" name="phone" required/>
						                  </div>
						                </div>

						                <div class="control-group">
						                  <label class="control-label">Address</label>
						                  <div class="controls">
						                     <input type="text" class="span6 m-wrap" name="address"/>
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
                                                         <input type="file" id="gallery_upload1" data-url="../shopphoto/php/index.php">
                                                      </div>
                                                   </ul>
                                                </div>
	                                        </div>
						                </div>
						              
						              	<div class="control-group">
	                                        <div class="span2">&nbsp;</div>
	                                        <div class="span10 responsive">
                                               <span class="label label-important">NOTE!</span>
                                               <span>Maximun image size is (1100px X 480px ) minimun size is (180px X 180px).</span>
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
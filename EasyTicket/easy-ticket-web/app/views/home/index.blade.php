@extends('../master')
@section('content')
{{HTML::style('../css/hover/component.css')}}
{{HTML::style('../../src/select2.css')}}
{{HTML::style('../../css/upload.css')}}
{{HTML::style('../../css/pages/home.css')}}
<style type="text/css">
@if(Auth::check())
		@if(Auth::user()->role==8)
				.btn_edit{display: block !important;}
				.grid:hover .btn_edit{opacity: 1;cursor: pointer;}
		@else
				.btn_edit{display: none !important;}
				.grid:hover .btn_edit{opacity: 0;cursor: pointer;}
		@endif
	@else
				.btn_edit{display: none !important;}
				.grid:hover .btn_edit{opacity: 0;cursor: pointer;}

	@endif
	.grid figure img{max-width: 100% !important;}
	
</style>
<div class="row">
	<div class="large-12 columns">
		<div id="update_form" class="reveal-modal small" data-reveal> 
         	<h3><i class="icon-user"></i>ကုန္ပစၥည္း အခ်က္အလက္ျပင္ရန္</h3>
            <form id="update-form" name ="update-form" class="horizontal-form" action ='' method= "post">    
                <div class="form-actions">
	                <div class="portlet box">
	                   <div class="portlet-title">
	                      <div class="actions">
	                      </div>
	                   </div>
		                <div class="portlet-body">
			                <div class="row">
			                	<div class="large-4 columns nopadding">
			                     	<label class="control-label">Name</label>
			                    </div>
			                    <div class="large-8 columns">
			                        <input type="text" name="name" required placeholder="Item Name" value="" id='itemname'/>
			                    </div>
			                </div>

			                <div class="row">
			                	<div class="large-4 columns nopadding">
			                     	<label class="control-label">Name MM</label>
			                    </div>
			                    <div class="large-8 columns">
			                        <input type="text" name="name" required placeholder="Item Name" value="" id='itemnamemm'/>
			                    </div>
			                </div>

			                <div class="row">
                                <label class="large-4 columns">Home Background Image</label>
                                <div class="large-8 columns">
                                    <div class="gallery-input">
                                       <ul>
                                          	<div class="gallery_container1">
                                             	<li class="gallery_photo">
	                                                <img src="../../itemphoto/php/files/thumbnail/"></img><span class="icon-cancel-circle">
	                                                <input type="hidden" value="" name="gallery[]"></input></span>
	                                                <script>
	                                                    $(".gallery_photo").click(function(){$(this).remove(); $(".upload1").show();$(".upload1 span").html("+");});
	                                                </script>
                                                </li>
                                            </div>
                                          <div class="script"></div>
                                          <div class="upload1" style="display:none;">
                                             <span>+</span>
                                             <input type="file" id="gallery_upload1" data-url="../itemphoto/php/index.php">
                                          </div>
                                       </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
			                	<div class="large-4 columns nopadding">
			                     	<label class="control-label">Background Color Code</label>
			                    </div>
			                    <div class="large-8 columns">
                                    <input type="text" class="colorpicker-default m-wrap" name="color_code" value="#ffffff" id="backgroundcolor_code"/>
			                    </div>
			                </div>

                            <input type="hidden" value="" name="itemid" id='itemid'>
                            <div class="row">
                            	<div class="large-4 columns">&nbsp;</div>
                            	<div class="large-8 columns">
                            		<div class="loading_indicator" style="width: 36px;height: 35px;position: relative;float: right;margin-top: 21px;"></div>
                            		<input type="button" value="Update" class=" btn button-submit right" id='btnitemupdate'>
                            	</div>
                            </div>

			            </div>
            		</div>
            	</div>
            </form>
          <a class="close-reveal-modal">&#215;</a> 
       </div>
	</div>
</div>

<div class="row">
	<div class="large-2 medium-6 columns">&nbsp;</div>
	<div class="large-8 columns slider_content">
		<div class="row">
			<div class="orbit-container"> 
	          	<ul class="example-orbit" data-orbit style="background:#F4F4F0;"> 
		            @if(count($responses['advertisement']) > 0)
				        @foreach($responses['advertisement'] as $row)
				            <li> 
				            	@if($row->link !='')
				            		<a href="{{$row->link}}">
				            	@else 
				            		<a href="/list/{{$row->menu_id}}">
				            	@endif
									<img src="advertisementphoto/php/files/{{$row->image}}">
								</a>
				            </li>
				        @endforeach
		            @endif
	          	</ul>
	      	</div>
      	</div>
      	<div class="row">
      		@if($responses['slidebelow'])
      			@foreach($responses['slidebelow'] as $sl_belowitem)
				<div class="large-4 medium-4 small-12 column gallery-3 grid">
					<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$sl_belowitem->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
						<span class="btn_edit"></span>
	                </a>
	                <a href="/itemdetail/{{$sl_belowitem->id}}">
						<div class="gallery_item" style="background:{{$sl_belowitem->backgroundcolor_code}};">
							@if(file_exists('itemphoto/php/files/medium/'.$sl_belowitem->home_image) && $sl_belowitem->home_image!='')
								<img src="../itemphoto/php/files/medium/{{$sl_belowitem->home_image}}" style="max-height: 175px;width: auto;height: auto;">
							@else
								<img src="../itemphoto/php/files/medium/{{$sl_belowitem->image}}" style="max-height: 175px;width: auto;height: auto;">
							@endif
						</div>

						<span class="grid_title_bg">
							<div class="grid_title">{{$sl_belowitem->name_mm}}</div>
							<div class="grid_price">@if($sl_belowitem->itemdetail) Price: {{$sl_belowitem->itemdetail[0]->price}} Ks @endif</div>
							<!-- <div class="second_row_grid_2_love">
								<div class="love_icon"></div>
								<div class="love_text">
									၅၂၈
								</div>
							</div> -->
						</span>
					</a>
				</div>
				@endforeach
			@endif
			
		</div>
	</div>
	<div class="large-2 medium-6 columns nopadding hide-for-small hide-for-medium">
		<div class="currency_exchange">
			<div class="row nopadding">
				<span class="currency_exchange_title"><a target="blank" href="http://forex.cbm.gov.mm/index.php/fxrate">Central Bank ၏ ယေန႔ ({{ date('d-M-y') }}) ေငြလဲလွယ္မႈႏူန္း </a> </span>
			</div>
			<div class="row">
				<span class="currency">ေငြေၾကး</span>
				<span class="buy text-right">ႏႈန္း(က်ပ္)</span>
			</div>
			<div style="border-top: 1px dashed;">
				<div class="row">
					<span class="currency"><img src="../flad_icon/USD_24.png"> USD</span>
					<span class="buy text-right">@if($exchange_rate != null) @if(isset($exchange_rate->rates->USD)) {{$exchange_rate->rates->USD}} @endif @endif</span>
				</div>
				<div class="row">
					<span class="currency"><img src="../flad_icon/EUR_24.png"> EUR</span>
					<span class="buy text-right">@if($exchange_rate != null) @if(isset($exchange_rate->rates->EUR)){{$exchange_rate->rates->EUR}} @endif @endif</span>
				</div>
				<div class="row">
					<span class="currency"><img src="../flad_icon/SGD_24.png"> SGD</span>
					<span class="buy text-right">@if($exchange_rate != null) @if(isset($exchange_rate->rates->SGD)) {{$exchange_rate->rates->SGD}} @endif @endif</span>
				</div>
				<div class="row">
					<span class="currency"><img src="../flad_icon/JPY_24.png"> JPY</span>
					<span class="buy text-right">@if($exchange_rate != null) @if(isset($exchange_rate->rates->JPY)) {{$exchange_rate->rates->JPY}} @endif @endif</span>
				</div>
				<div class="row">
					<span class="currency"><img src="../flad_icon/CNY_24.png"> CNY</span>
					<span class="buy text-right">@if($exchange_rate != null) @if(isset($exchange_rate->rates->CNY)) {{$exchange_rate->rates->CNY}} @endif @endif</span>
				</div>
				<div class="row">
					<span class="currency"><img src="../flad_icon/THB_24.png"> THB</span>
					<span class="buy text-right">@if($exchange_rate != null) @if(isset($exchange_rate->rates->THB)) {{$exchange_rate->rates->THB}} @endif @endif</span>
				</div>
			</div>
		</div>
		<div class="clear"></div>
		<div class="advertise_bg">
			<img src="../images/advertise.jpg">
		</div>
	</div>
</div>

<!-- <div data-magellan-expedition="fixed" id="navbarmagellan"> -->
<div data-magellan-expedition="fixed" style="z-index:97;" class="hide-for-small hide-for-medium">
    <dl class="sub-nav" id="navbarmagellan">
      <?php
      	$menu1 = Menu::where('id','=','2')->first();
      	$menu2 = Menu::where('id','=','4')->first();
      	$menu3 = Menu::where('id','=','5')->first();
      	$menu4 = Menu::where('id','=','6')->first();
      	$menu5 = Menu::where('id','=','7')->first();
      	$menu6 = Menu::where('id','=','8')->first();
      	$menu7 = Menu::where('id','=','9')->first();
      	$menu8 = Menu::where('id','=','11')->first();
      	$menu9 = Menu::where('id','=','12')->first();
      	$menu10 = Menu::where('id','=','12')->first();
      	$menu11 = Menu::where('id','=','12')->first();
      ?>
      <div><img src="../img/crow.png"></div>
      <dd data-magellan-arrival="electronic"><a href="#electronic" title="@if($menu1) {{$menu1->name_mm}} @endif"><img src="../menuphoto/php/files/@if($menu1){{$menu1->image}}@endif"></a></dd>
      <dd data-magellan-arrival="clothing"><a href="#clothing" title="@if($menu2) {{$menu2->name_mm}} @endif"><img src="../menuphoto/php/files/@if($menu2){{$menu2->image}}@endif"></a></dd>
      <dd data-magellan-arrival="cosmetic"><a href="#cosmetic" title="@if($menu3) {{$menu3->name_mm}} @endif"><img src="../menuphoto/php/files/@if($menu3){{$menu3->image}}@endif"></a></dd>
      <dd data-magellan-arrival="babies-mother"><a href="#babies-mother" title="@if($menu4) {{$menu4->name_mm}} @endif"><img src="../menuphoto/php/files/@if($menu4){{$menu4->image}}@endif"></a></dd>
      <dd data-magellan-arrival="kitchens"><a href="#kitchens" title="@if($menu5) {{$menu5->name_mm}} @endif"><img src="../menuphoto/php/files/@if($menu5){{$menu5->image}}@endif"></a></dd>
      <dd data-magellan-arrival="computer"><a href="#computer" title="@if($menu6) {{$menu6->name_mm}} @endif"><img src="../menuphoto/php/files/@if($menu6){{$menu6->image}}@endif"></a></dd>
      <dd data-magellan-arrival="mobile"><a href="#mobile" title="@if($menu7) {{$menu7->name_mm}} @endif"><img src="../menuphoto/php/files/@if($menu7){{$menu7->image}}@endif"></a></dd>
      <dd data-magellan-arrival="camera"><a href="#camera" title="@if($menu8) {{$menu8->name_mm}} @endif"><img src="../menuphoto/php/files/@if($menu8){{$menu8->image}}@endif"></a></dd>
      <dd data-magellan-arrival="stationery"><a href="#stationery" title="@if($menu10) {{$menu10->name_mm}} @endif"><img src="../menuphoto/php/files/@if($menu10){{$menu10->image}}@endif"></a></dd>
    </dl>
</div>


<!-- //electronic -->
<p class="magellan"><a name="electronic"></a></p>
<h3 data-magellan-destination="electronic" class="magellan"></h3>
<div class="row" >
	<div class="large-2 medium-12 columns nopadding" >
		<div class="fashion_title title_menu flow-1">
			@if($menu1) {{$menu1->name_mm}} @endif
		</div>
		<div class="row nopadding hide-for-small hide-for-medium" style="border: 1px solid #2A9632;height: 375px;">
			@if($responses['electroniccategory'])
				<div class="row nopadding" style="padding-left: 2px; min-height: 150px;">
				@foreach($responses['electroniccategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<a href="../../../list/{{$menu1->id}}/{{$row->id}}"><img class="center_photo" src="categoryphoto/php/files/{{$row->image}}"></a> 
					</div>
				@endforeach
				</div>
			@endif
			<div class="clear"></div>
			<div class="row electronic">
				<div class="large-12 columns feature_item_photo show-for-large grid nopadding">
	                @if($responses['electric_categoryadv'])
						@if(file_exists("advertisementphoto/php/files/".$responses['electric_categoryadv']['image']))
							<?php
								if($responses['electric_categoryadv']['link'] !=""){
									$link=$responses['electric_categoryadv']['link']; 
								}else{
									$link="/list/".$responses['electric_categoryadv']['menu_id']; 
								}

							?>
							<a href="{{$link}}"><img src="../advertisementphoto/php/files/{{$responses['electric_categoryadv']['image']}}">
						@else
							<a href="/list/{{$responses['electric_categoryadv']['menu_id']}}">
								<img src="../img/grid6.jpg">
							</a>
						@endif
					@endif
				</div>
			</div>
		</div>
	</div>
	@if(count($responses['objobjelectronic'])>0)
		<div class="large-8 medium-12 columns nopadding">
				<?php $i=1; $electroniccounts=count($responses['objobjelectronic'])?>
				
				@foreach($responses['objobjelectronic'] as $row)
					
					@if($i==1)
						<div class="row">
							<div class="large-6 medium-6 small-6 grid columns nopadding cs-style-3">
								<div class="grid_two_frame light-green-bl" style="background:{{$row->backgroundcolor_code}}">
									<figure>
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
						                <a href="/itemdetail/{{$row->id}}">
						                	@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
												<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
											@else
											<img style="height: auto; width: 100%;" src="itemphoto/php/files/{{$row->image}}">
											@endif
			                               	<figcaption><br>
				                                <div class="grid_title_bg">
				                                  <div class="grid_title">{{$row->name_mm}}</div>
													<div class="grid_price">Price: {{$row->price}} Ks </div>
													<!-- <div class="second_row_grid_2_love">
														<div class="love_icon"></div>
														<div class="love_text">
															၅၂၈
														</div>
													</div> -->
												</div>
				                            </figcaption>
				                        </a>
		                            </figure>
								</div>
							</div>
						@if($i==1 && $i==$electroniccounts)
								<div class="large-6 medium-6 small-6 grid columns nopadding">
									<div class="grid_two_frame pink">
									</div>
								</div>
							</div>
							<div class="clear show-for-small"></div>

							<div class="row">
								<div class="large-3 medium-3 small-6 columns grid cs-style-3 nopadding">
									<div class="grid_four_frame light-green">
									</div>
								</div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame pink">
									</div>
								</div>
								<div class="clear show-for-small"></div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame light-pink">
									</div>
								</div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange">
									</div>
								</div>
							</div>
						@endif
					@else
						@if($i==2)
							<div class="large-6 medium-6 small-6 grid columns nopadding cs-style-3">
									<div class="grid_two_frame pink" style="background:{{$row->backgroundcolor_code}}">
										<figure>
												<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
								                	@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
														<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
													@else
													<img style="height: auto; width: 100%;" src="itemphoto/php/files/{{$row->image}}">
													@endif
					                               	<figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">Price: {{$row->price}} Ks </div>
															<!-- <div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div> -->
														</div>
						                            </figcaption>
						                        </a>
			                            </figure>
									</div>
								</div>
							</div>
							<div class="clear show-for-small"></div>
						@endif
						@if($i==2 && $i==$electroniccounts)
							<div class="row">
								<div class="large-3 medium-3 small-6 columns grid cs-style-3 nopadding">
									<div class="grid_four_frame light-green">
									</div>
								</div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame pink">
									</div>
								</div>
								<div class="clear show-for-small"></div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame light-pink">
									</div>
								</div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange">
									</div>
								</div>
							</div>
						@endif

						@if($i==3)
							<div class="row">
								<div class="large-3 medium-3 small-6 columns grid cs-style-3 nopadding">
									<div class="grid_four_frame light-green" style="background:{{$row->backgroundcolor_code}};">
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
									    <a href="/itemdetail/{{$row->id}}">
									    	@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
												<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
											@else
											<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
											@endif
											<span class="grid_title_bg">
												<div class="grid_title">{{$row->name_mm}}</div>
												<div class="grid_price">Price: {{$row->price}} Ks</div>
												<!-- <div class="second_row_grid_2_love">
													<div class="love_icon"></div>
													<div class="love_text">
														၅၂၈
													</div>
												</div> -->
											</span>
										</a>
									</div>
								</div>
						@endif
						@if($i==3 && $i==$electroniccounts)
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame pink">
									</div>
								</div>
								<div class="clear show-for-small"></div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame light-pink">
									</div>
								</div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange">
									</div>
								</div>
							</div>
						@endif

						@if($i==4)
							<div class="large-3 medium-3 small-6 grid columns nopadding">
								<div class="grid_four_frame pink" style="background:{{$row->backgroundcolor_code}}">
									<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
										<span class="btn_edit"></span>
					                </a>
								    <a href="/itemdetail/{{$row->id}}">
								    	@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
											<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
										@else
										<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
										@endif
										<span class="grid_title_bg">
											<div class="grid_title">{{$row->name_mm}}</div>
											<div class="grid_price">Price: {{$row->price}} Ks</div>
											<!-- <div class="second_row_grid_2_love">
												<div class="love_icon"></div>
												<div class="love_text">
													၅၂၈
												</div>
											</div> -->
										</span>
									</a>
								</div>
							</div>
							<div class="clear show-for-small"></div>
						@endif
						@if($i==4 && $i==$electroniccounts)
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame light-pink">
									</div>
								</div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange">
									</div>
								</div>
							</div>
						@endif

						@if($i==5)
							<div class="large-3 medium-3 small-6 grid columns nopadding">
								<div class="grid_four_frame light-pink" style="background:{{$row->backgroundcolor_code}}">
									<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
										<span class="btn_edit"></span>
					                </a>
								    <a href="/itemdetail/{{$row->id}}">
										@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
											<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
										@else
										<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
										@endif
										<span class="grid_title_bg">
											<div class="grid_title">{{$row->name_mm}}</div>
											<div class="grid_price">Price: {{$row->price}} Ks</div>
											<!-- <div class="second_row_grid_2_love">
												<div class="love_icon"></div>
												<div class="love_text">
													၅၂၈
												</div>
											</div> -->
										</span>
									</a>
								</div>
							</div>
						@endif
						@if($i==5 && $i==$electroniccounts)
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange">
									</div>
								</div>
							</div>
						@endif

						@if($i==6 && $i==$electroniccounts)
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange" style="background:{{$row->backgroundcolor_code}}">
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
									    <a href="/itemdetail/{{$row->id}}">
											@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
												<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
											@else
											<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
											@endif
											<span class="grid_title_bg">
												<div class="grid_title">{{$row->name_mm}}</div>
												<div class="grid_price">Price: {{$row->price}} Ks</div>
												<!-- <div class="second_row_grid_2_love">
													<div class="love_icon"></div>
													<div class="love_text">
														၅၂၈
													</div>
												</div> -->
											</span>
										</a>
									</div>
								</div>
							</div>
						@endif

					@endif
					<?php $i++;?>
				@endforeach
		</div>
	@else
	<div class="large-8 medium-12 columns nopadding">
		<div class="row">
			<div class="large-6 medium-6 small-6 grid columns nopadding">
				<div class="grid_two_frame light-green-bl">
				</div>
			</div>
			<div class="large-6 medium-6 small-6 grid columns nopadding">
				<div class="grid_two_frame pink">
				</div>
			</div>
		</div>
		<div class="clear show-for-small"></div>

		<div class="row">
			<div class="large-3 medium-3 small-6 columns grid cs-style-3 nopadding">
				<div class="grid_four_frame light-green">
				</div>
			</div>
			<div class="large-3 medium-3 small-6 grid columns nopadding">
				<div class="grid_four_frame pink">
				</div>
			</div>
			<div class="clear show-for-small"></div>
			<div class="large-3 medium-3 small-6 grid columns nopadding">
				<div class="grid_four_frame light-pink">
				</div>
			</div>
			<div class="large-3 medium-3 small-6 grid columns nopadding">
				<div class="grid_four_frame orange">
				</div>
			</div>
		</div>
	</div>
	@endif
	<div class="large-2 columns nopadding hide-for-small hide-for-medium">
		<div class="second_brand_bg">
			@if($responses['electorincbrands'])
				@foreach($responses['electorincbrands'] as $brands)
					<a href="../listbybrand/{{$menu1->id}}/{{$brands->id}}">
					<div class="row">
						<div class="large-5 small-5 columns brand_image_frame">
							<img style="width:auto !important; height: auto !important;" class="center_photo" src="brandphoto/php/files/{{$brands->image}}">
						</div>
						<div class="large-7 small-7 columns">
							<div class="brand_title">{{$brands->name}}</div>
						</div>
					</div>
					</a>
				@endforeach
			@endif
		</div>
	</div>
</div>
<div>&nbsp;</div>

<!-- Clothings -->
<p class="magellan"><a name="clothing"></a></p>
<h3 data-magellan-destination="clothing" class="magellan">&nbsp;</h3>	
<div class="row" >
	<div class="large-2 medium columns  nopadding" >
		<div class="fashion_title title_menu flow-2">
			@if($menu2) {{$menu2->name_mm}} @endif
		</div>
		<div class="row nopadding hide-for-small hide-for-medium" style="border: 1px solid #64FF92;height: 375px;">
			@if($responses['clothingcategory'])
				<div class="row nopadding" style="background: white; padding-left: 2px; min-height: 150px;">
				@foreach($responses['clothingcategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<a href="../../../list/{{$menu2->id}}/{{$row->id}}"><img class="center_photo" src="categoryphoto/php/files/{{$row->image}}"></a>
					</div>
				@endforeach
				</div>
			@endif
			<div class="clear"></div>
			<div class="row">
				<div class="large-12 columns feature_item_photo show-for-large grid nopadding">
					@if($responses['clothing_categoryadv'])
						@if(file_exists("advertisementphoto/php/files/".$responses['clothing_categoryadv']['image']))
							<?php
								if($responses['clothing_categoryadv']['link'] !=""){
									$link=$responses['clothing_categoryadv']['link']; 
								}else{
									$link="/list/".$responses['clothing_categoryadv']['menu_id']; 
								}

							?>
							<a href="{{$link}}">
								<img src="../advertisementphoto/php/files/{{$responses['clothing_categoryadv']['image']}}">
							</a>
						@else
							<img src="../img/electronic3.jpg">
						@endif
					@endif
				</div>
			</div>
		</div>
	</div>
	
	@if(count($responses['clothings'])>0)
		<div class="large-8 medium-12 columns nopadding">
			<div class="row">
				<?php $i=1; $clothingcounts=count($responses['clothings'])?>
				@foreach($responses['clothings'] as $row)
					@if($i==1)
						<div class="large-6 medium-6 small-12 column nopadding">
							<div class="grid-2-row grid" style="background:{{$row->backgroundcolor_code}}">
								<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
									<span class="btn_edit"></span>
				                </a>
				                <a href="/itemdetail/{{$row->id}}">
				                    @if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
										<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
									@else
									<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
									@endif
				                    <div class="grid_title_bg">
				                      	<div class="grid_title">{{$row->name}}</div>
										<div class="grid_price text-center">Price: {{$row->price}} Ks </div>
										<!-- <div class="second_row_grid_2_love">
											<div class="love_icon"></div>
											<div class="love_text">
												၅၂၈
											</div>
										</div> -->
									</div>
								</a>
							</div>
						</div>
						<div class="clear show-for-small"></div>
						@if($i==1 && $i==$clothingcounts)
							<div class="large-6 medium-6 small-12 column nopadding">
					        	<div class="row">
									<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row light-green grid list">
				                       	</div>
				                    </div>

				                    <div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row grid pink list">
				                       </div>
				                    </div>
								</div>
								<div class="clear show-for-small"></div>
								<div class="row">
									<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding">
										<div class="grid-2ndsm pink grid list">
										</div>
									</div>
									<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding" >
										<div class="grid-2ndsm navy-blue grid list">
										</div>
									</div>
								</div>
							</div>
				        @endif
					@else

						@if($i==2)
							<div class="large-6 medium-6 small-12 column nopadding">
								<div class="row">
									<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row light-green grid list" style="background:{{$row->backgroundcolor_code}}">
				                            <figure>
												<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
								                	@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
														<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
													@else
													<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
													@endif
					                               	<figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">Price: {{$row->price}} Ks </div>
															<!-- <div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div> -->
														</div>
						                            </figcaption>
						                        </a>
				                            </figure>
				                       </div>
				                    </div>
				        @endif

				        @if($i==2 && $i==$clothingcounts)
				        			<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                    	<div class="grid-1h-row grid light-green list">
				                       	</div>
				                    </div>
				        		</div>
				        		<div class="clear show-for-small"></div>
								<div class="row">
									<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding">
										<div class="grid-2ndsm pink grid list">
										</div>
									</div>
									<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding" >
										<div class="grid-2ndsm navy-blue grid list">
										</div>
									</div>
								</div>
				        	</div>
				        @elseif($i==3)
						        	<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row grid light-green list" style="background:{{$row->backgroundcolor_code}}">
				                            <figure>
				                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
			                					</a>
								                <a href="/itemdetail/{{$row->id}}">
		  			                                @if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
														<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
													@else
													<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
													@endif
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">Price: {{$row->price}} Ks</div>
															<!-- <div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div> -->
														</div>
					                               	</figcaption>
					                            </a>
				                            </figure>
				                       </div>
				                    </div>
								</div>
								<div class="clear show-for-small"></div>
						@endif
						@if($i==3 && $i==$clothingcounts)
							<div class="row">
								<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding">
									<div class="grid-2ndsm pink grid list">
									</div>
								</div>
								<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding" >
									<div class="grid-2ndsm navy-blue grid list">
									</div>
								</div>
							</div>
						</div>
				        @endif

				        @if($i==4)
						        <div class="row">
									<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding">
										<div class="grid-2ndsm pink grid list" style="background:{{$row->backgroundcolor_code}}">
											<figure>
				                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
					                                @if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
														<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
													@else
													<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
													@endif
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">Price: {{$row->price}} Ks</div>
															<!-- <div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div> -->
														</div>
					                                </figcaption>
				                                </a>
				                            </figure>
										</div>
									</div>
						@endif
				        @if($i==4 && $i==$clothingcounts)
				        			<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding" >
										<div class="grid-2ndsm navy-blue grid list">
										</div>
									</div>
								</div>
							</div>
				        @endif

				        @if($i==5 && $i==$clothingcounts)
						        <div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding" >
									<div class="grid-2ndsm navy-blue grid list" style="background:{{$row->backgroundcolor_code}}">
										<figure>
			                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
												<span class="btn_edit"></span>
			                				</a>
							                <a href="/itemdetail/{{$row->id}}">
				                                @if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
													<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
												@else
												<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
												@endif
				                                <figcaption><br>
					                                <div class="grid_title_bg">
					                                  <div class="grid_title">{{$row->name_mm}}</div>
														<div class="grid_price">Price: {{$row->price}} Ks</div>
														<!-- <div class="second_row_grid_2_love">
															<div class="love_icon"></div>
															<div class="love_text">
																၅၂၈
															</div>
														</div> -->
													</div>
				                                </figcaption>
				                            </a>
			                            </figure>
									</div>
								</div>
							</div>
						</div>
						@endif
					@endif
					<?php $i++; ?>
				@endforeach
			</div>
		</div>
	@else
		<div class="large-8 medium-12 columns nopadding">
			<div class="row">
				<div class="large-6 medium-6 small-12 column nopadding">
					<div class="grid-2-row grid">
					</div>
				</div>
				<div class="clear show-for-small"></div>
				<div class="large-6 medium-6 small-12 column nopadding">
		        	<div class="row">
						<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
		                    <div class="grid-1h-row light-green grid list">
		                   	</div>
		                </div>

		                <div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
		                    <div class="grid-1h-row grid pink list">
		                   </div>
		                </div>
					</div>
					<div class="clear show-for-small"></div>
					<div class="row">
						<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding">
							<div class="grid-2ndsm pink grid list">
							</div>
						</div>
						<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding" >
							<div class="grid-2ndsm navy-blue grid list">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif
	<div class="large-2 medium-2 small-6 columns nopadding hide-for-small hide-for-medium">
		<div class="second_brand_bg">
			<div class="brand_bg">
				<div class="adv_brand">
					<div class="two_col_frames">
						<div class="clearfix brand_logo_wrap">
							@if($responses['clothingbrands'])
								<?php $i=1; ?>
								@foreach($responses['clothingbrands'] as $brands)
									@if($i<=8)
									<div class="logo_wrap">
										<a href="" class="brand_bg_white" style="-webkit-transform: rotate(45deg);"></a>
										<a href="../listbybrand/{{$menu2->id}}/{{$brands->id}}" class="logo_link" >
											<img src="brandphoto/php/files/{{$brands->image}}">
										</a>
										
									</div>
									@else
										@if($i==9)
											<?php $class='gray_logo1'; ?>
										@elseif($i==10)
											<?php $class='gray_logo2'; ?>
										@else
											<?php $class='gray_logo3'; ?>
										@endif
										<div class="logo_wrap {{$class}}">
											<a href="" class="brand_bg_gray" style="-webkit-transform: rotate(45deg);"></a>
											<a href="../listbybrand/{{$menu2->id}}/{{$brands->id}}" class="logo_link" ><img src="brandphoto/php/files/{{$brands->image}}"></a>
											
										</div>
									@endif
									<?php $i++; ?>
								@endforeach
							@endif

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div>&nbsp;</div>

<!-- Make UP accessories -->
<p class="magellan"><a name="cosmetic"></a></p>
<h3 data-magellan-destination="cosmetic" class="magellan">&nbsp;</h3>
<div class="row" >
	<div class="large-2 medium-12 columns nopadding" >
		<div class="fashion_title title_menu flow-3">
			@if($menu3) {{$menu3->name_mm}} @endif
		</div>
		<div class="row nopadding hide-for-small hide-for-medium" style="border: 1px solid #800511;height: 375px;">
			@if($responses['cosmeticcategory'])
				<div class="row nopadding" style="background: white; padding-left: 2px; min-height: 150px;">
				@foreach($responses['cosmeticcategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<a href="../../../list/{{$menu3->id}}/{{$row->id}}"><img class="center_photo" src="categoryphoto/php/files/{{$row->image}}"></a>
					</div>
				@endforeach
				</div>
			@endif
			<div class="clear"></div>
			<div class="row">
				<div class="large-12 columns feature_item_photo show-for-large nopadding">
					@if($responses['cosmetic_categoryadv'])
						@if(file_exists("advertisementphoto/php/files/".$responses['cosmetic_categoryadv']['image']))
							<?php
								if($responses['cosmetic_categoryadv']['link'] !=""){
									$link=$responses['cosmetic_categoryadv']['link']; 
								}else{
									$link="/list/".$responses['cosmetic_categoryadv']['menu_id']; 
								}

							?>
							<a href="{{$link}}">
								<img src="../advertisementphoto/php/files/{{$responses['cosmetic_categoryadv']['image']}}">
							</a>
						@else
							<img src="../img/grid6.jpg">
						@endif
					@else
						<img src="../img/grid6.jpg">
					@endif
				</div>
			</div>
		</div>

		<!-- </div> -->
	</div>
	@if(count($responses['cosmeticaccessories'])>0)
		<div class="large-8 medium-12 columns nopadding">
			<div class="row">
				<?php $i=1; $cosmeticaccessoriescounts=count($responses['cosmeticaccessories'])?>
				@foreach($responses['cosmeticaccessories'] as $row)
					@if($i==1)
						<div class="large-6 medium-6 small-12 column nopadding">
							<div class="grid-2-row grid" style="background:{{$row->backgroundcolor_code}}">
				                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
									<span class="btn_edit"></span>
				                </a>
				                <a href="/itemdetail/{{$row->id}}">
				                    @if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
										<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
									@else
									<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
									@endif
				                    <div class="grid_title_bg">
				                      	<div class="grid_title">{{$row->name_mm}}</div>
										<div class="grid_price text-center">Price: {{$row->price}} Ks</div>
										<!-- <div class="second_row_grid_2_love">
											<div class="love_icon"></div>
											<div class="love_text">
												၅၂၈
											</div>
										</div> -->
									</div>
								</a>
							</div>
						</div>
						<div class="clear show-for-small"></div>
						@if($i==1 && $i==$cosmeticaccessoriescounts)
							<div class="large-6 medium-6 small-12 column nopadding">
					        	<div class="row">
									<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row light-green grid list">
				                       	</div>
				                    </div>

				                    <div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row grid pink list">
				                       </div>
				                    </div>
								</div>
								<div class="clear show-for-small"></div>
								<div class="row">
									<div class="large-12 medium-12 small-12 grid columns nopadding">
										<div class="grid-2ndsm light-green-bl">
										</div>
									</div>
								</div>
							</div>
				        @endif
					@else
						@if($i==2)
							<div class="large-6 medium-6 small-12 column nopadding">
								<div class="row">
									<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row light-green grid list" style="background:{{$row->backgroundcolor_code}}">
				                            <figure>
				                               <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
				                				<a href="/itemdetail/{{$row->id}}">
 				                                	@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
														<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
													@else
													<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
													@endif
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">Price: {{$row->price}} Ks</div>
															<!-- <div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div> -->
														</div>
					                               </figcaption>
					                            </a>
				                            </figure>
				                       </div>
				                    </div>
				        @endif

				        @if($i==2 && $i==$cosmeticaccessoriescounts)
				        			<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                    	<div class="grid-1h-row grid light-green list">
				                       	</div>
				                    </div>
				        		</div>
				        		<div class="clear show-for-small"></div>
								<div class="row">
									<div class="large-12 medium-12 small-12 grid columns nopadding">
										<div class="grid-2ndsm light-green-bl">
										</div>
									</div>
								</div>
							</div>
				        @elseif($i==3)
						        	<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row grid light-green list" style="background:{{$row->backgroundcolor_code}}">
				                            <figure>
				                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
					                                @if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
														<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
													@else
													<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
													@endif
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">Price: {{$row->price}} Ks</div>
															<!-- <div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div> -->
														</div>
					                               	</figcaption>
					                            </a>
				                            </figure>
				                       </div>
				                    </div>
								</div>
								<div class="clear show-for-small"></div>
						@endif
						@if($i==3 && $i==$cosmeticaccessoriescounts)
							<div class="row">
								<div class="large-12 medium-12 small-12 grid columns nopadding">
									<div class="grid-2ndsm light-green-bl">
									</div>
								</div>
							</div>
						</div>
				        @endif
				        @if($i==4 && $i==$cosmeticaccessoriescounts)
			        			<div class="row">
									<div class="large-12 medium-12 small-12 grid columns nopadding cs-style-3">
										<div class="grid-2ndsm light-green-bl" style="background:{{$row->backgroundcolor_code}}">
											<figure>
												<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
								                	@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
														<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
													@else
													<img style="height: auto; width: 100%;" src="itemphoto/php/files/{{$row->image}}">
													@endif
					                               	<figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">Price: {{$row->price}} Ks </div>
															<!-- <div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div> -->
														</div>
						                            </figcaption>
						                        </a>
				                            </figure>
										</div>
									</div>
								</div>
							</div>
				        @endif
					@endif
					<?php $i++; ?>
				@endforeach
			</div>
		</div>
	@else
		<div class="large-8 medium-12 columns nopadding">
			<div class="row">
				<div class="large-6 medium-6 small-12 column nopadding">
					<div class="grid-2-row grid">
					</div>
				</div>
				<div class="clear show-for-small"></div>
				<div class="large-6 medium-6 small-12 column nopadding">
		        	<div class="row">
						<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
	                        <div class="grid-1h-row light-green grid list">
	                       	</div>
	                    </div>

	                    <div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
	                        <div class="grid-1h-row grid pink list">
	                       </div>
	                    </div>
					</div>
					<div class="clear show-for-small"></div>
					<div class="row">
						<div class="large-12 medium-12 small-12 grid columns nopadding">
							<div class="grid-2ndsm light-green-bl">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif
	<div class="large-2 columns nopadding hide-for-small hide-for-medium">
		<div class="second_brand_bg">
			@if($responses['cosmeticbrands'])
				@foreach($responses['cosmeticbrands'] as $brands)
					<a href="../listbybrand/{{$menu3->id}}/{{$brands->id}}">
					<div class="row">
						<div class="large-5 small-5 columns brand_image_frame">
							<img style="width:auto !important; height: auto !important;" class="center_photo" src="brandphoto/php/files/{{$brands->image}}">
						</div>
						<div class="large-7 small-7 columns">
							<div class="brand_title">{{$brands->name}}</div>
						</div>
					</div>
					</a>
				@endforeach
			@endif
		</div>
	</div>
</div>
<div>&nbsp;</div>

<!-- Mothers and Babies -->
<p class="magellan"><a name="babies-mother"></a></p>
<h3 data-magellan-destination="babies-mother" class="magellan">&nbsp;</h3>
<div class="row" >
	<div class="large-2 medium-12 columns nopadding" >
		<div class="fashion_title title_menu flow-4">
			@if($menu4) {{$menu4->name_mm}} @endif
		</div>
		<div class="row nopadding hide-for-small hide-for-medium" style="border: 1px solid #43E9AA;height: 375px;">
			@if($responses['babiescategory'])
				<div class="row nopadding" style="background: white; padding-left: 2px; min-height: 150px;">
				@foreach($responses['babiescategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<a href="../../../list/{{$menu4->id}}/{{$row->id}}"><img class="center_photo" src="categoryphoto/php/files/{{$row->image}}"></a>
					</div>
				@endforeach
				</div>
			@endif
			<div class="clear"></div>
			<div class="row babies">
				<div class="large-12 columns feature_item_photo show-for-large grid nopadding">
					@if($responses['babies_categoryadv'])
						@if(file_exists("advertisementphoto/php/files/".$responses['babies_categoryadv']['image']))
							<?php
								if($responses['babies_categoryadv']['link'] !=""){
									$link=$responses['babies_categoryadv']['link']; 
								}else{
									$link="/list/".$responses['babies_categoryadv']['menu_id']; 
								}

							?>
							<a href="{{$link}}">
								<img src="../advertisementphoto/php/files/{{$responses['babies_categoryadv']['image']}}">
							</a>
						@else
							<img src="../img/grid6.jpg">
						@endif
					@else
						<img src="../img/grid6.jpg">
					@endif
				</div>
			</div>
		</div>

		<!-- </div> -->
	</div>
	@if(count($responses['objobjbabies'])>0)
		<div class="large-8 medium-12 columns nopadding">
				<?php $i=1; $babiescounts=count($responses['objobjbabies'])?>
				
				@foreach($responses['objobjbabies'] as $row)
					
					@if($i==1)
						<div class="row">
							<div class="large-6 medium-6 small-6 grid columns nopadding cs-style-3">
								<div class="grid_two_frame light-green-bl" style="background:{{$row->backgroundcolor_code}}">
									<figure>
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
						                <a href="/itemdetail/{{$row->id}}">
						                	@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
												<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
											@else
											<img style="height: auto; width: 100%;" src="itemphoto/php/files/{{$row->image}}">
											@endif
			                               	<figcaption><br>
				                                <div class="grid_title_bg">
				                                  <div class="grid_title">{{$row->name_mm}}</div>
													<div class="grid_price">Price: {{$row->price}} Ks </div>
													<!-- <div class="second_row_grid_2_love">
														<div class="love_icon"></div>
														<div class="love_text">
															၅၂၈
														</div>
													</div> -->
												</div>
				                            </figcaption>
				                        </a>
		                            </figure>
								</div>
							</div>
						@if($i==1 && $i==$babiescounts)
								<div class="large-6 medium-6 small-6 grid columns nopadding">
									<div class="grid_two_frame pink">
									</div>
								</div>
							</div>
							<div class="clear show-for-small"></div>

							<div class="row">
								<div class="large-3 medium-3 small-6 columns grid cs-style-3 nopadding">
									<div class="grid_four_frame light-green">
									</div>
								</div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame pink">
									</div>
								</div>
								<div class="clear show-for-small"></div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame light-pink">
									</div>
								</div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange">
									</div>
								</div>
							</div>
						@endif
					@else
						@if($i==2)
							<div class="large-6 medium-6 small-6 grid columns nopadding cs-style-3">
									<div class="grid_two_frame pink" style="background:{{$row->backgroundcolor_code}}">
										<figure>
											<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
												<span class="btn_edit"></span>
							                </a>
							                <a href="/itemdetail/{{$row->id}}">
							                	@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
													<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
												@else
												<img style="height: auto; width: 100%;" src="itemphoto/php/files/{{$row->image}}">
												@endif
				                               	<figcaption><br>
					                                <div class="grid_title_bg">
					                                  <div class="grid_title">{{$row->name_mm}}</div>
														<div class="grid_price">Price: {{$row->price}} Ks </div>
														<!-- <div class="second_row_grid_2_love">
															<div class="love_icon"></div>
															<div class="love_text">
																၅၂၈
															</div>
														</div> -->
													</div>
					                            </figcaption>
					                        </a>
			                            </figure>
									</div>
								</div>
							</div>
							<div class="clear show-for-small"></div>
						@endif
						@if($i==2 && $i==$babiescounts)
							<div class="row">
								<div class="large-3 medium-3 small-6 columns grid cs-style-3 nopadding">
									<div class="grid_four_frame light-green">
									</div>
								</div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame pink">
									</div>
								</div>
								<div class="clear show-for-small"></div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame light-pink">
									</div>
								</div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange">
									</div>
								</div>
							</div>
						@endif

						@if($i==3)
							<div class="row">
								<div class="large-3 medium-3 small-6 columns grid cs-style-3 nopadding">
									<div class="grid_four_frame light-green" style="background:{{$row->backgroundcolor_code}}">
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
									    <a href="/itemdetail/{{$row->id}}">
											@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
												<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
											@else
											<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
											@endif
											<span class="grid_title_bg">
												<div class="grid_title">{{$row->name_mm}}</div>
												<div class="grid_price">Price: {{$row->price}} Ks</div>
												<!-- <div class="second_row_grid_2_love">
													<div class="love_icon"></div>
													<div class="love_text">
														၅၂၈
													</div>
												</div> -->
											</span>
										</a>
									</div>
								</div>
						@endif
						@if($i==3 && $i==$babiescounts)
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame pink">
									</div>
								</div>
								<div class="clear show-for-small"></div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame light-pink">
									</div>
								</div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange">
									</div>
								</div>
							</div>
						@endif

						@if($i==4)
							<div class="large-3 medium-3 small-6 grid columns nopadding">
								<div class="grid_four_frame pink" style="background:{{$row->backgroundcolor_code}}">
									<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
										<span class="btn_edit"></span>
					                </a>
								    <a href="/itemdetail/{{$row->id}}">
										@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
											<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
										@else
										<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
										@endif
										<span class="grid_title_bg">
											<div class="grid_title">{{$row->name_mm}}</div>
											<div class="grid_price">Price: {{$row->price}} Ks</div>
											<!-- <div class="second_row_grid_2_love">
												<div class="love_icon"></div>
												<div class="love_text">
													၅၂၈
												</div>
											</div> -->
										</span>
									</a>
								</div>
							</div>
							<div class="clear show-for-small"></div>
						@endif
						@if($i==4 && $i==$babiescounts)
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame light-pink">
									</div>
								</div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange">
									</div>
								</div>
							</div>
						@endif

						@if($i==5)
							<div class="large-3 medium-3 small-6 grid columns nopadding">
								<div class="grid_four_frame light-pink" style="background:{{$row->backgroundcolor_code}}">
									<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
										<span class="btn_edit"></span>
					                </a>
								    <a href="/itemdetail/{{$row->id}}">
										@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
											<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
										@else
										<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
										@endif
										<span class="grid_title_bg">
											<div class="grid_title">{{$row->name_mm}}</div>
											<div class="grid_price">Price: {{$row->price}} Ks</div>
											<!-- <div class="second_row_grid_2_love">
												<div class="love_icon"></div>
												<div class="love_text">
													၅၂၈
												</div>
											</div> -->
										</span>
									</a>
								</div>
							</div>
						@endif
						@if($i==5 && $i==$babiescounts)
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange">
									</div>
								</div>
							</div>
						@endif

						@if($i==6 && $i==$babiescounts)
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange" style="background:{{$row->backgroundcolor_code}}">
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
									    <a href="/itemdetail/{{$row->id}}">
											@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
												<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
											@else
												<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
											@endif
											<span class="grid_title_bg">
												<div class="grid_title">{{$row->name_mm}}</div>
												<div class="grid_price">Price: {{$row->price}} Ks</div>
												<!-- <div class="second_row_grid_2_love">
													<div class="love_icon"></div>
													<div class="love_text">
														၅၂၈
													</div>
												</div> -->
											</span>
										</a>
									</div>
								</div>
							</div>
						@endif

					@endif
					<?php $i++;?>
				@endforeach
		</div>
	@else
		<div class="large-8 medium-12 columns nopadding">
			<div class="row">
				<div class="large-6 medium-6 small-6 grid columns nopadding">
					<div class="grid_two_frame light-green-bl">
					</div>
				</div>
				<div class="large-6 medium-6 small-6 grid columns nopadding">
					<div class="grid_two_frame pink">
					</div>
				</div>
			</div>
			<div class="clear show-for-small"></div>

			<div class="row">
				<div class="large-3 medium-3 small-6 columns grid cs-style-3 nopadding">
					<div class="grid_four_frame light-green">
					</div>
				</div>
				<div class="large-3 medium-3 small-6 grid columns nopadding">
					<div class="grid_four_frame pink">
					</div>
				</div>
				<div class="clear show-for-small"></div>
				<div class="large-3 medium-3 small-6 grid columns nopadding">
					<div class="grid_four_frame light-pink">
					</div>
				</div>
				<div class="large-3 medium-3 small-6 grid columns nopadding">
					<div class="grid_four_frame orange">
					</div>
				</div>
			</div>
		</div>
	@endif
	<div class="large-2 medium-2 small-6 columns nopadding hide-for-small hide-for-medium">
		<div class="second_brand_bg">
			<div class="brand_bg">
				<div class="adv_brand">
					<div class="two_col_frames">
						<div class="clearfix brand_logo_wrap">
							@if($responses['babiesbrands'])
								<?php $i=1; ?>
								@foreach($responses['babiesbrands'] as $brands)
									@if($i<=8)
									<div class="logo_wrap">
										<a href="" class="brand_bg_white" style="-webkit-transform: rotate(45deg);"></a>
										<a href="../listbybrand/{{$menu4->id}}/{{$brands->id}}" class="logo_link" >
											<img src="brandphoto/php/files/{{$brands->image}}">
										</a>
										
									</div>
									@else
										@if($i==9)
											<?php $class='gray_logo1'; ?>
										@elseif($i==10)
											<?php $class='gray_logo2'; ?>
										@else
											<?php $class='gray_logo3'; ?>
										@endif
										<div class="logo_wrap {{$class}}">
											<a href="" class="brand_bg_gray" style="-webkit-transform: rotate(45deg);"></a>
											<a href="../listbybrand/{{$menu4->id}}/{{$brands->id}}" class="logo_link" ><img src="brandphoto/php/files/{{$brands->image}}"></a>
											
										</div>
									@endif
									<?php $i++; ?>
								@endforeach
							@endif

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div>&nbsp;</div>

<!-- kitchen accessories -->
<p class="magellan"><a name="kitchens"></a></p>
<h3 data-magellan-destination="kitchens" class="magellan">&nbsp;</h3>
<div class="row" >

	<div class="large-2 columns  nopadding" >
		<div class="fashion_title title_menu flow-5">
			@if($menu5) {{$menu5->name_mm}} @endif
		</div>
		<div class="row nopadding hide-for-small hide-for-medium" style="border: 1px solid #C72791;height: 375px;">
			@if($responses['kitchencategory'])
				<div class="row nopadding" style="background: white; padding-left: 2px; min-height: 150px;">
				@foreach($responses['kitchencategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<a href="../../../list/{{$menu5->id}}/{{$row->id}}"><img class="center_photo" src="categoryphoto/php/files/{{$row->image}}"></a>
					</div>
				@endforeach
				</div>
			@endif
			<div class="clear"></div>
			<div class="row">
				<div class="large-12 columns feature_item_photo show-for-large grid nopadding">
					@if($responses['kitchen_categoryadv'])
						@if(file_exists("advertisementphoto/php/files/".$responses['kitchen_categoryadv']['image']))
							<?php
								if($responses['kitchen_categoryadv']['link'] !=""){
									$link=$responses['kitchen_categoryadv']['link']; 
								}else{
									$link="/list/".$responses['kitchen_categoryadv']['menu_id']; 
								}

							?>
							<a href="{{$link}}">
								<img src="../advertisementphoto/php/files/{{$responses['kitchen_categoryadv']['image']}}">
							</a>
						@else
							<img src="../img/grid6.jpg">
						@endif
					@else
						<img src="../img/grid6.jpg">
					@endif
				</div>
			</div>
		</div>

		<!-- </div> -->
	</div>
	
	@if(count($responses['objkitchen'])>0 || count($responses['kitchencategory_in_grid'])>0)
		<?php $i=1; $count=count($responses['objkitchen']); ?>
		<div class="large-8 medium-12 columns nopadding" style="background: #EDF2F1;">
			<div class="row">
				@if(count($responses['objkitchen'])==0)
				<div class="large-6 medium-6 column nopadding">
					<div class="row">
						<div class="large-6 medium-6 small-6 columns grid cs-style-3 nopadding">
							<div class="grid_four_frame light-green">
							</div>
						</div>
						<div class="large-6 medium-6 small-6 grid columns nopadding">
							<div class="grid_four_frame pink">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="large-12 medium-12 small-12 grid columns nopadding">
							<div class="grid_two_frame light-green-bl">
							</div>
						</div>
					</div>
				</div>
				<div class="clear show-for-small"></div>
				@endif
				@foreach($responses['objkitchen'] as $row)
					@if($i==1)
							<div class="large-6 medium-6 column nopadding">
								<div class="row">
									<div class="large-6 medium-6 small-6 columns grid cs-style-3 nopadding">
										<div class="grid_four_frame light-green" style="background:{{$row->backgroundcolor_code}}">
											<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
												<span class="btn_edit"></span>
							                </a>
							                <a href="/itemdetail/{{$row->id}}">
												@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
													<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
												@else
												<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
												@endif
												<span class="grid_title_bg">
													<div class="grid_title">{{$row->name_mm}}</div>
													<div class="grid_price">Price: {{$row->price}} Ks</div>
													<!-- <div class="second_row_grid_2_love">
														<div class="love_icon"></div>
														<div class="love_text">
															၅၂၈
														</div>
													</div> -->
												</span>
											</a>
										</div>
									</div>

						@if($i==1 && $count==$i)
									<div class="large-6 medium-6 small-6 grid columns nopadding">
										<div class="grid_four_frame pink">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="large-12 medium-12 small-12 grid columns nopadding">
										<div class="grid_two_frame light-green-bl">
										</div>
									</div>
								</div>
							</div>
							<div class="clear show-for-small"></div>
						@endif
					@else

						@if($i==2)
								<div class="large-6 medium-6 small-6 grid columns nopadding">
											<div class="grid_four_frame pink" style="background:{{$row->backgroundcolor_code}}">
												<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
													@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
														<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
													@else
														<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
													@endif
													<span class="grid_title_bg">
														<div class="grid_title">{{$row->name_mm}}</div>
														<div class="grid_price">Price: {{$row->price}} Ks</div>
														<!-- <div class="second_row_grid_2_love">
															<div class="love_icon"></div>
															<div class="love_text">
																၅၂၈
															</div>
														</div> -->
													</span>
												</a>
											</div>
								</div>
							</div>
						@endif

						@if($i==2 && $i==$count)
							<div class="row">
									<div class="large-12 medium-12 small-12 grid columns nopadding">
										<div class="grid_two_frame light-green-bl">
										</div>
									</div>
							</div>
							</div>
							<div class="clear show-for-small"></div>
						
						@endif

						@if($i==3 && $i==$count)
							<div class="row">
								<div class="large-12 medium-12 small-12 grid columns nopadding cs-style-3">
									<div class="grid_two_frame light-green-bl">
										<figure>
											<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
												<span class="btn_edit"></span>
							                </a>
							                <a href="/itemdetail/{{$row->id}}">
							                	@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
													<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
												@else
												<img style="height: auto; width: 100%;" src="itemphoto/php/files/{{$row->image}}">
												@endif
				                               	<figcaption><br>
					                                <div class="grid_title_bg">
					                                  <div class="grid_title">{{$row->name_mm}}</div>
														<div class="grid_price">Price: {{$row->price}} Ks </div>
														<!-- <div class="second_row_grid_2_love">
															<div class="love_icon"></div>
															<div class="love_text">
																၅၂၈
															</div>
														</div> -->
													</div>
					                            </figcaption>
					                        </a>
			                            </figure>
									</div>
								</div>
								</div>
							</div>
							<div class="clear show-for-small"></div>
						@endif
					@endif
				<?php $i++; ?>
				@endforeach
				@if($responses['kitchencategory_in_grid'])
					<?php $i=1; $count=count($responses['kitchencategory_in_grid']);?>
					<div class="large-6 medium-6 small-12 column nopadding">
					@foreach($responses['kitchencategory_in_grid'] as $row)
						@if($i==1 || $i%4==1)
							<div class="row grey left-right-5">
						@endif
							<a href="/list/{{$row->menu_id}}/{{$row->id}}">
								<div class="large-3 medium-3 small-6 column left nopadding" >
									<div class="grid-three ">
										<img src="categoryphoto/php/files/{{$row->image}}" alt="">
									</div>
								</div>
							</a>
								@if($i%2==0)
									<div class="clear show-for-small"></div>
								@endif
						@if($i%4==0 || $i==$count)
							</div>
							<div class="clear show-for-small"></div>
						@endif
						<?php $i++; ?>
					@endforeach
					</div>

				@endif
			</div>
		</div>
	@else
		<div class="large-8 medium-12 columns nopadding" style="background: #EDF2F1;">
			<div class="row">
				<div class="large-6 medium-6 column nopadding">
					<div class="row">
						<div class="large-6 medium-6 small-6 columns grid cs-style-3 nopadding">
							<div class="grid_four_frame light-green">
							</div>
						</div>

						<div class="large-6 medium-6 small-6 grid columns nopadding">
							<div class="grid_four_frame pink">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="large-12 medium-12 small-12 grid columns nopadding">
							<div class="grid_two_frame light-green-bl">
							</div>
						</div>
					</div>
				</div>
				<div class="clear show-for-small"></div>

				<div class="large-6 medium-6 small-12 column nopadding">
					<div class="row grey left-right-5">
						<div class="large-3 medium-3 small-6 column nopadding" >
							<div class="grid-three ">
							</div>
						</div>
						<div class="large-3 medium-3 small-6 column nopadding" >
							<div class="grid-three ">
							</div>
						</div>
						<div class="clear show-for-small"></div>

						<div class="large-3 medium-3 small-6 column nopadding" >
							<div class="grid-three">
							</div>
						</div>
						<div class="large-3 medium-3 small-6 column nopadding" >
							<div class="grid-three">
							</div>
						</div>
					</div>
					<div class="clear show-for-small"></div>

					<div class="row grey left-right-5">
						<div class="large-3 medium-3 small-6 column nopadding" >
							<div class="grid-three ">
							</div>
						</div>
						<div class="large-3 medium-3 small-6 column nopadding" >
							<div class="grid-three ">
							</div>
						</div>
						<div class="clear show-for-small"></div>

						<div class="large-3 medium-3 small-6 column nopadding" >
							<div class="grid-three">
							</div>
						</div>
						<div class="large-3 medium-3 small-6 column nopadding" >
							<div class="grid-three">
							</div>
						</div>
					</div>
					<div class="clear show-for-small"></div>

					<div class="row grey left-right-5">
						<div class="large-3 medium-3 small-6 column nopadding" >
							<div class="grid-three ">
							</div>
						</div>
						<div class="large-3 medium-3 small-6 column nopadding" >
							<div class="grid-three ">
							</div>
						</div>
						<div class="clear show-for-small"></div>

						<div class="large-3 medium-3 small-6 column nopadding" >
							<div class="grid-three">
							</div>
						</div>
						<div class="large-3 medium-3 small-6 column nopadding" >
							<div class="grid-three">
							</div>
						</div>
					</div>
					<div class="clear show-for-small"></div>
				</div>
			</div>
		</div>
	@endif
	<div class="large-2 columns nopadding hide-for-small hide-for-medium">
		<div class="second_brand_bg">
			@if($responses['kitchenbrnads'])
				@foreach($responses['kitchenbrnads'] as $brands)
					<a href="../listbybrand/{{$menu5->id}}/{{$brands->id}}">
					<div class="row">
						<div class="large-5 small-5 columns brand_image_frame">
							<img style="width:auto !important; height: auto !important;" class="center_photo" src="brandphoto/php/files/{{$brands->image}}">
						</div>
						<div class="large-7 small-7 columns">
							<div class="brand_title">{{$brands->name}}</div>
						</div>
					</div>
					</a>
				@endforeach
			@endif
		</div>
	</div>
</div>
<div>&nbsp;</div>

<!-- Computer accessories -->
<p class="magellan"><a name="computer"></a></p>
<h3 data-magellan-destination="computer" class="magellan">&nbsp;</h3>
<div class="row" >
	<div class="large-2 medium-12 columns nopadding" >
		<div class="fashion_title title_menu flow-6">
			@if($menu6) {{$menu6->name_mm}} @endif
		</div>
		<div class="row nopadding hide-for-small hide-for-medium" style="border: 1px solid #D20C14;height: 375px;">
			@if($responses['computercategory'])
					<div class="row nopadding" style="background: white; padding-left: 2px; min-height: 150px;">
				@foreach($responses['computercategory'] as $row)
						<div class="large-4 medium-4 small-4 columns feature_sub_category left">
							<a href="../../../list/{{$menu6->id}}/{{$row->id}}"><img class="center_photo" src="categoryphoto/php/files/{{$row->image}}"></a>
						</div>
				@endforeach
					</div>
			@endif
			<div class="clear"></div>
			<div class="row">
				<div class="large-12 columns feature_item_photo show-for-large nopadding">
					@if($responses['computer_categoryadv'])
						@if(file_exists("advertisementphoto/php/files/".$responses['computer_categoryadv']['image']))
							<?php
								if($responses['computer_categoryadv']['link'] !=""){
									$link=$responses['computer_categoryadv']['link']; 
								}else{
									$link="/list/".$responses['computer_categoryadv']['menu_id']; 
								}

							?>
							<a href="{{$link}}">
								<img src="../advertisementphoto/php/files/{{$responses['computer_categoryadv']['image']}}">
							</a>
						@else
							<img src="../img/grid6.jpg">
						@endif
					@else
						<img src="../img/grid6.jpg">
					@endif
				</div>
			</div>
		</div>

		<!-- </div> -->
	</div>
	@if(count($responses['computeraccessories'])>0)
		<div class="large-8 medium-12 columns nopadding">
			<div class="row">
				<?php $i=1; $computeraccessoriescounts=count($responses['computeraccessories'])?>
				@foreach($responses['computeraccessories'] as $row)
					@if($i==1)
						<div class="large-6 medium-6 small-12 column nopadding">
							<div class="grid-2-row grid" style="background:{{$row->backgroundcolor_code}}">
				                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
									<span class="btn_edit"></span>
				                </a>
				                <a href="/itemdetail/{{$row->id}}">
				                    @if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
										<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
									@else
									<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
									@endif
				                    <div class="grid_title_bg">
				                      	<div class="grid_title">{{$row->name_mm}}</div>
										<div class="grid_price text-center">Price: {{$row->price}} Ks</div>
									</div>
								</a>
							</div>
						</div>
						<div class="clear show-for-small"></div>
						@if($i==1 && $i==$computeraccessoriescounts)
							<div class="large-6 medium-6 small-12 column nopadding">
					        	<div class="row">
									<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row light-green grid list">
				                       	</div>
				                    </div>

				                    <div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row grid pink list">
				                       </div>
				                    </div>
								</div>
								<div class="clear show-for-small"></div>
								<div class="row">
									<div class="large-12 medium-12 small-12 grid columns nopadding">
										<div class="grid-2ndsm light-green-bl">
										</div>
									</div>
								</div>
							</div>
				        @endif
					@else
						@if($i==2)
							<div class="large-6 medium-6 small-12 column nopadding">
								<div class="row">
									<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row light-green grid list" style="background:{{$row->backgroundcolor_code}}">
				                            <figure>
				                               <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
				                				<a href="/itemdetail/{{$row->id}}">
 				                                	@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
														<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
													@else
													<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
													@endif
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">Price: {{$row->price}} Ks</div>
															<!-- <div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div> -->
														</div>
					                               </figcaption>
					                            </a>
				                            </figure>
				                       </div>
				                    </div>
				        @endif

				        @if($i==2 && $i==$computeraccessoriescounts)
				        			<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                    	<div class="grid-1h-row grid light-green list">
				                       	</div>
				                    </div>
				        		</div>
				        		<div class="clear show-for-small"></div>
								<div class="row">
									<div class="large-12 medium-12 small-12 grid columns nopadding">
										<div class="grid-2ndsm light-green-bl">
										</div>
									</div>
								</div>
							</div>
				        @elseif($i==3)
						        	<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row grid light-green list" style="background:{{$row->backgroundcolor_code}}">
				                            <figure>
				                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
					                                @if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
														<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
													@else
													<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
													@endif
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">Price: {{$row->price}} Ks</div>
															<!-- <div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div> -->
														</div>
					                               	</figcaption>
					                            </a>
				                            </figure>
				                       </div>
				                    </div>
								</div>
								<div class="clear show-for-small"></div>
						@endif
						@if($i==3 && $i==$computeraccessoriescounts)
							<div class="row">
								<div class="large-12 medium-12 small-12 grid columns nopadding">
									<div class="grid-2ndsm light-green-bl">
									</div>
								</div>
							</div>
						</div>
				        @endif
				        @if($i==4 && $i==$computeraccessoriescounts)
			        			<div class="row">
									<div class="large-12 medium-12 small-12 grid columns nopadding cs-style-3">
										<div class="grid-2ndsm light-green-bl" style="background:{{$row->backgroundcolor_code}}">
											<figure>
												<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
								                	@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
														<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
													@else
													<img style="height: auto; width: 100%;" src="itemphoto/php/files/{{$row->image}}">
													@endif
					                               	<figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">Price: {{$row->price}} Ks </div>
															<!-- <div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div> -->
														</div>
						                            </figcaption>
						                        </a>
				                            </figure>
										</div>
									</div>
								</div>
							</div>
				        @endif
					@endif
					<?php $i++; ?>
				@endforeach
			</div>
		</div>
	@else
		<div class="large-8 medium-12 columns nopadding">
			<div class="row">
				<div class="large-6 medium-6 small-12 column nopadding">
					<div class="grid-2-row grid">
					</div>
				</div>
				<div class="clear show-for-small"></div>
				<div class="large-6 medium-6 small-12 column nopadding">
		        	<div class="row">
						<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
	                        <div class="grid-1h-row light-green grid list">
	                       	</div>
	                    </div>

	                    <div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
	                        <div class="grid-1h-row grid pink list">
	                       </div>
	                    </div>
					</div>
					<div class="clear show-for-small"></div>
					<div class="row">
						<div class="large-12 medium-12 small-12 grid columns nopadding">
							<div class="grid-2ndsm light-green-bl">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif
	<div class="large-2 medium-2 small-6 columns nopadding hide-for-small hide-for-medium">
		<div class="second_brand_bg">
			<div class="brand_bg">
				<div class="adv_brand">
					<div class="two_col_frames">
						<div class="clearfix brand_logo_wrap">
							@if($responses['computerbrands'])
								<?php $i=1; ?>
								@foreach($responses['computerbrands'] as $brands)
									@if($i<=8)
									<div class="logo_wrap">
										<a href="" class="brand_bg_white" style="-webkit-transform: rotate(45deg);"></a>
										<a href="../listbybrand/{{$menu6->id}}/{{$brands->id}}" class="logo_link" >
											<img src="brandphoto/php/files/{{$brands->image}}">
										</a>
										
									</div>
									@else
										@if($i==9)
											<?php $class='gray_logo1'; ?>
										@elseif($i==10)
											<?php $class='gray_logo2'; ?>
										@else
											<?php $class='gray_logo3'; ?>
										@endif
										<div class="logo_wrap {{$class}}">
											<a href="" class="brand_bg_gray" style="-webkit-transform: rotate(45deg);"></a>
											<a href="../listbybrand/{{$menu6->id}}/{{$brands->id}}" class="logo_link" ><img src="brandphoto/php/files/{{$brands->image}}"></a>
											
										</div>
									@endif
									<?php $i++; ?>
								@endforeach
							@endif

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div>&nbsp;</div>

<!-- Mobile Accessories -->
<p class="magellan"><a name="mobile"></a></p>
<h3 data-magellan-destination="mobile" class="magellan">&nbsp;</h3>
<div class="row" >
	<div class="large-2 medium-12 columns nopadding" >
		<div class="fashion_title title_menu flow-7">
			@if($menu7) {{$menu7->name_mm}} @endif
		</div>
		<div class="row nopadding hide-for-small hide-for-medium" style="border: 1px solid #D20040;height: 375px;">
			@if($responses['mobilecategory'])
				<div class="row nopadding" style="background: white; padding-left: 2px; min-height: 150px;">
				@foreach($responses['mobilecategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<a href="../../../list/{{$menu7->id}}/{{$row->id}}"><img class="center_photo" src="categoryphoto/php/files/{{$row->image}}"></a>
					</div>
				@endforeach
				</div>
			@endif
			<div class="clear"></div>
			<div class="row mobile">
				<div class="large-12 columns feature_item_photo show-for-large grid nopadding">
					@if($responses['mobile_categoryadv'])
						@if(file_exists("advertisementphoto/php/files/".$responses['mobile_categoryadv']['image']))
							<?php
								if($responses['mobile_categoryadv']['link'] !=""){
									$link=$responses['mobile_categoryadv']['link']; 
								}else{
									$link="/list/".$responses['mobile_categoryadv']['menu_id']; 
								}

							?>
							<a href="{{$link}}">
								<img src="../advertisementphoto/php/files/{{$responses['mobile_categoryadv']['image']}}">
							</a>
						@else
							<img src="../img/grid6.jpg">
						@endif
					@else
						<img src="../img/grid6.jpg">
					@endif
				</div>
			</div>
		</div>

		<!-- </div> -->
	</div>
	@if(count($responses['objobjmobile'])>0)
		<div class="large-8 medium-12 columns nopadding">
				<?php $i=1; $mobilecounts=count($responses['objobjmobile'])?>
				
				@foreach($responses['objobjmobile'] as $row)
					
					@if($i==1)
						<div class="row">
							<div class="large-6 medium-6 small-6 grid columns nopadding cs-style-3">
								<div class="grid_two_frame light-green-bl" style="background:{{$row->backgroundcolor_code}}">
									<figure>
		                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
						                <a href="/itemdetail/{{$row->id}}">
											@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
												<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
											@else
											<img style="height: auto; width: 100%;" src="itemphoto/php/files/{{$row->image}}">
											@endif
											<figcaption><br>
				                                <div class="grid_title_bg">
					                                <div class="grid_title">{{$row->name_mm}}</div>
													<div class="grid_price">Price: {{$row->price}} Ks</div>
												</div>
			                               	</figcaption>
										</a>
		                            </figure>
								</div>
							</div>
						@if($i==1 && $i==$mobilecounts)
								<div class="large-6 medium-6 small-6 grid columns nopadding">
									<div class="grid_two_frame pink">
									</div>
								</div>
							</div>
							<div class="clear show-for-small"></div>

							<div class="row">
								<div class="large-3 medium-3 small-6 columns grid cs-style-3 nopadding">
									<div class="grid_four_frame light-green">
									</div>
								</div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame pink">
									</div>
								</div>
								<div class="clear show-for-small"></div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame light-pink">
									</div>
								</div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange">
									</div>
								</div>
							</div>
						@endif
					@else
						@if($i==2)
							<div class="large-6 medium-6 small-6 grid columns nopadding cs-style-3">
									<div class="grid_two_frame pink" style="background:{{$row->backgroundcolor_code}}">
										<figure>
			                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
												<span class="btn_edit"></span>
							                </a>

							                <a href="/itemdetail/{{$row->id}}">
												@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
													<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
												@else
												<img style="height: auto; width: 100%;" src="itemphoto/php/files/{{$row->image}}">
												@endif

												<figcaption><br>
					                                <div class="grid_title_bg">
						                                <div class="grid_title">{{$row->name_mm}}</div>
														<div class="grid_price">Price: {{$row->price}} Ks</div>
													</div>
				                               	</figcaption>

											</a>
			                            </figure>
									</div>
								</div>
							</div>
							<div class="clear show-for-small"></div>
						@endif
						@if($i==2 && $i==$mobilecounts)
							<div class="row">
								<div class="large-3 medium-3 small-6 columns grid cs-style-3 nopadding">
									<div class="grid_four_frame light-green">
									</div>
								</div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame pink">
									</div>
								</div>
								<div class="clear show-for-small"></div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame light-pink">
									</div>
								</div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange">
									</div>
								</div>
							</div>
						@endif

						@if($i==3)
							<div class="row">
								<div class="large-3 medium-3 small-6 columns grid cs-style-3 nopadding">
									<div class="grid_four_frame light-green" style="background:{{$row->backgroundcolor_code}}">
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
									    <a href="/itemdetail/{{$row->id}}">
											@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
												<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
											@else
											<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
											@endif
											<span class="grid_title_bg">
												<div class="grid_title">{{$row->name_mm}}</div>
												<div class="grid_price">Price: {{$row->price}} Ks</div>
												<!-- <div class="second_row_grid_2_love">
													<div class="love_icon"></div>
													<div class="love_text">
														၅၂၈
													</div>
												</div> -->
											</span>
										</a>
									</div>
								</div>
						@endif
						@if($i==3 && $i==$mobilecounts)
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame pink">
									</div>
								</div>
								<div class="clear show-for-small"></div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame light-pink">
									</div>
								</div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange">
									</div>
								</div>
							</div>
						@endif

						@if($i==4)
							<div class="large-3 medium-3 small-6 grid columns nopadding cs-style-3">
								<div class="grid_four_frame pink" style="background:{{$row->backgroundcolor_code}}">
									<figure>
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
						                <a href="/itemdetail/{{$row->id}}">
						                	@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
												<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
											@else
											<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
											@endif
			                               	<figcaption><br>
				                                <div class="grid_title_bg">
				                                  <div class="grid_title">{{$row->name_mm}}</div>
													<div class="grid_price">Price: {{$row->price}} Ks </div>
													<!-- <div class="second_row_grid_2_love">
														<div class="love_icon"></div>
														<div class="love_text">
															၅၂၈
														</div>
													</div> -->
												</div>
				                            </figcaption>
				                        </a>
		                            </figure>
								</div>
							</div>
							<div class="clear show-for-small"></div>
						@endif
						@if($i==4 && $i==$mobilecounts)
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame light-pink">
									</div>
								</div>
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange">
									</div>
								</div>
							</div>
						@endif

						@if($i==5)
							<div class="large-3 medium-3 small-6 grid columns nopadding cs-style-3">
								<div class="grid_four_frame light-pink" style="background:{{$row->backgroundcolor_code}}">
									<figure>
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
						                <a href="/itemdetail/{{$row->id}}">
						                	@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
												<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
											@else
											<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
											@endif
			                               	<figcaption><br>
				                                <div class="grid_title_bg">
				                                  <div class="grid_title">{{$row->name_mm}}</div>
													<div class="grid_price">Price: {{$row->price}} Ks </div>
													<!-- <div class="second_row_grid_2_love">
														<div class="love_icon"></div>
														<div class="love_text">
															၅၂၈
														</div>
													</div> -->
												</div>
				                            </figcaption>
				                        </a>
		                            </figure>
								</div>
							</div>
						@endif
						@if($i==5 && $i==$mobilecounts)
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange">
									</div>
								</div>
							</div>
						@endif

						@if($i==6 && $i==$mobilecounts)
								<div class="large-3 medium-3 small-6 grid columns nopadding cs-style-3">
									<div class="grid_four_frame orange" style="background:{{$row->backgroundcolor_code}}">
										<figure>
											<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
												<span class="btn_edit"></span>
							                </a>
							                <a href="/itemdetail/{{$row->id}}">
							                	@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
													<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
												@else
												<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
												@endif
				                               	<figcaption><br>
					                                <div class="grid_title_bg">
					                                  <div class="grid_title">{{$row->name_mm}}</div>
														<div class="grid_price">Price: {{$row->price}} Ks </div>
														<!-- <div class="second_row_grid_2_love">
															<div class="love_icon"></div>
															<div class="love_text">
																၅၂၈
															</div>
														</div> -->
													</div>
					                            </figcaption>
					                        </a>
			                            </figure>
									</div>
								</div>
							</div>
						@endif

					@endif
					<?php $i++;?>
				@endforeach
		</div>
	@else
	<div class="large-8 medium-12 columns nopadding">
		<div class="row">
			<div class="large-6 medium-6 small-6 grid columns nopadding">
				<div class="grid_two_frame light-green-bl">
				</div>
			</div>
			<div class="large-6 medium-6 small-6 grid columns nopadding">
				<div class="grid_two_frame pink">
				</div>
			</div>
		</div>
		<div class="clear show-for-small"></div>

		<div class="row">
			<div class="large-3 medium-3 small-6 columns grid cs-style-3 nopadding">
				<div class="grid_four_frame light-green">
				</div>
			</div>
			<div class="large-3 medium-3 small-6 grid columns nopadding">
				<div class="grid_four_frame pink">
				</div>
			</div>
			<div class="clear show-for-small"></div>
			<div class="large-3 medium-3 small-6 grid columns nopadding">
				<div class="grid_four_frame light-pink">
				</div>
			</div>
			<div class="large-3 medium-3 small-6 grid columns nopadding">
				<div class="grid_four_frame orange">
				</div>
			</div>
		</div>
	</div>
	@endif
	<div class="large-2 columns nopadding hide-for-small hide-for-medium">
		<div class="second_brand_bg">
			@if($responses['mobilebrands'])
				@foreach($responses['mobilebrands'] as $brands)
					<a href="../listbybrand/{{$menu7->id}}/{{$brands->id}}">
					<div class="row">
						<div class="large-5 small-5 columns brand_image_frame">
							<img style="width:auto !important; height: auto !important;" class="center_photo" src="brandphoto/php/files/{{$brands->image}}">
						</div>
						<div class="large-7 small-7 columns">
							<div class="brand_title">{{$brands->name}}</div>
						</div>
					</div>
					</a>
				@endforeach
			@endif
		</div>
	</div>
</div>
<div>&nbsp;</div>

<!-- Camera accessories -->
<p class="magellan"><a name="camera"></a></p>
<h3 data-magellan-destination="camera" class="magellan">&nbsp;</h3>
<div class="row" >
	<div class="large-2 medium columns  nopadding" >
		<div class="fashion_title title_menu flow-8">
		@if($menu8) {{$menu8->name_mm}} @endif
		</div>
		<div class="row nopadding hide-for-small hide-for-medium" style="border: 1px solid #1AFF87;height: 375px; min-height: 150px;">
			@if($responses['cameracategory'])
				<div class="row nopadding" style="background: white; padding-left: 2px;min-height:150px;">
				@foreach($responses['cameracategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<a href="../../../list/{{$menu8->id}}/{{$row->id}}"><img class="center_photo" src="categoryphoto/php/files/{{$row->image}}"></a>
					</div>
				@endforeach
				</div>
			@endif
			<div class="clear"></div>
			<div class="row">
				<div class="large-12 columns feature_item_photo show-for-large grid nopadding">
					@if($responses['camera_categoryadv'])
						@if(file_exists("advertisementphoto/php/files/".$responses['camera_categoryadv']['image']))
							<?php
								if($responses['camera_categoryadv']['link'] !=""){
									$link=$responses['camera_categoryadv']['link']; 
								}else{
									$link="/list/".$responses['camera_categoryadv']['menu_id']; 
								}

							?>
							<a href="{{$link}}">
								<img src="../advertisementphoto/php/files/{{$responses['camera_categoryadv']['image']}}">
							</a>
						@else
							<img src="../img/grid6.jpg">
						@endif
					@else
						<img src="../img/grid6.jpg">
					@endif
				</div>
			</div>
		</div>

		<!-- </div> -->
	</div>
	
	@if(count($responses['cameras'])>0)
		<div class="large-8 medium-12 columns nopadding">
			<div class="row">
				<?php $i=1; $stationerycounts=count($responses['cameras'])?>
				@foreach($responses['cameras'] as $row)
					@if($i==1)
						<div class="large-6 medium-6 small-12 column nopadding">
							<div class="grid-2-row grid" style="background:{{$row->backgroundcolor_code}}">
								<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
									<span class="btn_edit"></span>
				                </a>
				                <a href="/itemdetail/{{$row->id}}">
				                    @if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
										<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
									@else
									<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
									@endif
				                    <div class="grid_title_bg">
				                      	<div class="grid_title">{{$row->name}}</div>
										<div class="grid_price text-center">Price: {{$row->price}} Ks </div>
										<!-- <div class="second_row_grid_2_love">
											<div class="love_icon"></div>
											<div class="love_text">
												၅၂၈
											</div>
										</div> -->
									</div>
								</a>
							</div>
						</div>
						<div class="clear show-for-small"></div>
						@if($i==1 && $i==$stationerycounts)
							<div class="large-6 medium-6 small-12 column nopadding">
					        	<div class="row">
									<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row light-green grid list">
				                       	</div>
				                    </div>

				                    <div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row grid pink list">
				                       </div>
				                    </div>
								</div>
								<div class="clear show-for-small"></div>
								<div class="row">
									<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding">
										<div class="grid-2ndsm pink grid list">
										</div>
									</div>
									<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding" >
										<div class="grid-2ndsm navy-blue grid list">
										</div>
									</div>
								</div>
							</div>
				        @endif
					@else

						@if($i==2)
							<div class="large-6 medium-6 small-12 column nopadding">
								<div class="row">
									<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row light-green grid list" style="background:{{$row->backgroundcolor_code}}">
				                            <figure>
												<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
				                               		@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
														<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
													@else
													<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
													@endif
					                               	<figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">Price: {{$row->price}} Ks </div>
															<!-- <div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div> -->
														</div>
						                            </figcaption>
						                        </a>
				                            </figure>
				                       </div>
				                    </div>
				        @endif

				        @if($i==2 && $i==$stationerycounts)
				        			<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                    	<div class="grid-1h-row grid light-green list">
				                       	</div>
				                    </div>
				        		</div>
				        		<div class="clear show-for-small"></div>
								<div class="row">
									<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding">
										<div class="grid-2ndsm pink grid list">
										</div>
									</div>
									<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding" >
										<div class="grid-2ndsm navy-blue grid list">
										</div>
									</div>
								</div>
				        	</div>
				        @elseif($i==3)
						        	<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row grid light-green list" style="background:{{$row->backgroundcolor_code}}">
				                            <figure>
				                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
			                					</a>
								                <a href="/itemdetail/{{$row->id}}">
		  			                                @if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
														<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
													@else
													<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
													@endif
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">Price: {{$row->price}} Ks</div>
															<!-- <div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div> -->
														</div>
					                               	</figcaption>
					                            </a>
				                            </figure>
				                       </div>
				                    </div>
								</div>
								<div class="clear show-for-small"></div>
						@endif
						@if($i==3 && $i==$stationerycounts)
							<div class="row">
								<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding">
									<div class="grid-2ndsm pink grid list">
									</div>
								</div>
								<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding" >
									<div class="grid-2ndsm navy-blue grid list">
									</div>
								</div>
							</div>
						</div>
				        @endif

				        @if($i==4)
						        <div class="row">
									<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding">
										<div class="grid-2ndsm pink grid list" style="background:{{$row->backgroundcolor_code}}">
											<figure>
				                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
					                                @if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
														<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
													@else
													<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
													@endif
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">Price: {{$row->price}} Ks</div>
															<!-- <div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div> -->
														</div>
					                                </figcaption>
				                                </a>
				                            </figure>
										</div>
									</div>
						@endif
				        @if($i==4 && $i==$stationerycounts)
				        			<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding" >
										<div class="grid-2ndsm navy-blue grid list">
										</div>
									</div>
								</div>
							</div>
				        @endif

				        @if($i==5 && $i==$stationerycounts)
						        <div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding" >
									<div class="grid-2ndsm navy-blue grid list" style="background:{{$row->backgroundcolor_code}}">
										<figure>
			                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
												<span class="btn_edit"></span>
			                				</a>
							                <a href="/itemdetail/{{$row->id}}">
				                                @if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
													<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
												@else
												<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
												@endif
				                                <figcaption><br>
					                                <div class="grid_title_bg">
					                                  <div class="grid_title">{{$row->name_mm}}</div>
														<div class="grid_price">Price: {{$row->price}} Ks</div>
														<!-- <div class="second_row_grid_2_love">
															<div class="love_icon"></div>
															<div class="love_text">
																၅၂၈
															</div>
														</div> -->
													</div>
				                                </figcaption>
				                            </a>
			                            </figure>
									</div>
								</div>
							</div>
						</div>
						@endif
					@endif
					<?php $i++; ?>
				@endforeach
			</div>
		</div>
	@else
		<div class="large-8 medium-12 columns nopadding">
			<div class="row">
				<div class="large-6 medium-6 small-12 column nopadding">
					<div class="grid-2-row grid">
					</div>
				</div>
				<div class="clear show-for-small"></div>
				<div class="large-6 medium-6 small-12 column nopadding">
		        	<div class="row">
						<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
		                    <div class="grid-1h-row light-green grid list">
		                   	</div>
		                </div>

		                <div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
		                    <div class="grid-1h-row grid pink list">
		                   </div>
		                </div>
					</div>
					<div class="clear show-for-small"></div>
					<div class="row">
						<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding">
							<div class="grid-2ndsm pink grid list">
							</div>
						</div>
						<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding" >
							<div class="grid-2ndsm navy-blue grid list">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif
	<div class="large-2 medium-2 small-6 columns nopadding hide-for-small hide-for-medium">
		<div class="second_brand_bg">
			<div class="brand_bg">
				<div class="adv_brand">
					<div class="two_col_frames">
						<div class="clearfix brand_logo_wrap">
							@if($responses['camerabrands'])
								<?php $i=1; ?>
								@foreach($responses['camerabrands'] as $brands)
									@if($i<=8)
									<div class="logo_wrap">
										<a href="" class="brand_bg_white" style="-webkit-transform: rotate(45deg);"></a>
										<a href="../listbybrand/{{$menu8->id}}/{{$brands->id}}" class="logo_link" >
											<img src="brandphoto/php/files/{{$brands->image}}">
										</a>
										
									</div>
									@else
										@if($i==9)
											<?php $class='gray_logo1'; ?>
										@elseif($i==10)
											<?php $class='gray_logo2'; ?>
										@else
											<?php $class='gray_logo3'; ?>
										@endif
										<div class="logo_wrap {{$class}}">
											<a href="" class="brand_bg_gray" style="-webkit-transform: rotate(45deg);"></a>
											<a href="../listbybrand/{{$menu8->id}}/{{$brands->id}}" class="logo_link" ><img src="brandphoto/php/files/{{$brands->image}}"></a>
											
										</div>
									@endif
									<?php $i++; ?>
								@endforeach
							@endif

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>
<div>&nbsp;</div>


<!-- School and office accessories -->
<p class="magellan"><a name="stationery"></a></p>
<h3 data-magellan-destination="stationery" class="magellan">&nbsp;</h3>
<div class="row" >

	<div class="large-2 medium columns  nopadding" >
		<div class="fashion_title title_menu flow-9">
			@if($menu10) {{$menu10->name_mm}} @endif
		</div>
		<div class="row nopadding hide-for-small hide-for-medium" style="border: 1px solid #A56B70;height: 375px;">
			@if($responses['stationerycategory'])
				<div class="row nopadding" style="background: white; padding-left: 2px; min-height: 150px;">
				@foreach($responses['stationerycategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<a href="../../../list/{{$menu10->id}}/{{$row->id}}"><img class="center_photo" src="categoryphoto/php/files/{{$row->image}}"></a>
					</div>
				@endforeach
				</div>
			@endif
			<div class="clear"></div>
			<div class="row">
				<div class="large-12 columns feature_item_photo show-for-large grid nopadding">
					@if($responses['stationery_categoryadv'])
						@if(file_exists("advertisementphoto/php/files/".$responses['stationery_categoryadv']['image']))
							<?php
								if($responses['stationery_categoryadv']['link'] !=""){
									$link=$responses['stationery_categoryadv']['link']; 
								}else{
									$link="/list/".$responses['stationery_categoryadv']['menu_id']; 
								}

							?>
							<a href="{{$link}}">
								<img src="../advertisementphoto/php/files/{{$responses['stationery_categoryadv']['image']}}">
							</a>
						@else
							<img src="../img/grid6.jpg">
						@endif
					@else
						<img src="../img/grid6.jpg">
					@endif
				</div>
			</div>
		</div>

		<!-- </div> -->
	</div>
	
	@if(count($responses['stationerys'])>0)
		<div class="large-8 medium-12 columns nopadding">
			<div class="row">
				<?php $i=1; $stationerycounts=count($responses['stationerys'])?>
				@foreach($responses['stationerys'] as $row)
					@if($i==1)
						<div class="large-6 medium-6 small-12 column nopadding">
							<div class="grid-2-row grid" style="background:{{$row->backgroundcolor_code}}">
								<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
									<span class="btn_edit"></span>
				                </a>
				                <a href="/itemdetail/{{$row->id}}">
				                    @if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
										<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
									@else
									<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
									@endif
				                    <div class="grid_title_bg">
				                      	<div class="grid_title">{{$row->name}}</div>
										<div class="grid_price text-center">Price: {{$row->price}} Ks </div>
										<!-- <div class="second_row_grid_2_love">
											<div class="love_icon"></div>
											<div class="love_text">
												၅၂၈
											</div>
										</div> -->
									</div>
								</a>
							</div>
							</div>
						<div class="clear show-for-small"></div>
						@if($i==1 && $i==$stationerycounts)
							<div class="large-6 medium-6 small-12 column nopadding">
					        	<div class="row">
									<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row light-green grid list">
				                       	</div>
				                    </div>

				                    <div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row grid pink list">
				                       </div>
				                    </div>
								</div>
								<div class="clear show-for-small"></div>
								<div class="row">
									<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding">
										<div class="grid-2ndsm pink grid list">
										</div>
									</div>
									<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding" >
										<div class="grid-2ndsm navy-blue grid list">
										</div>
									</div>
								</div>
							</div>
				        @endif
					@else

						@if($i==2)
							<div class="large-6 medium-6 small-12 column nopadding">
								<div class="row">
									<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row light-green grid list" style="background:{{$row->backgroundcolor_code}}">
				                            <figure>
												<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
				                               		@if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
														<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
													@else
													<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
													@endif
					                               	<figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">Price: {{$row->price}} Ks </div>
															<!-- <div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div> -->
														</div>
						                            </figcaption>
						                        </a>
				                            </figure>
				                       </div>
				                    </div>
				        @endif

				        @if($i==2 && $i==$stationerycounts)
				        			<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                    	<div class="grid-1h-row grid light-green list">
				                       	</div>
				                    </div>
				        		</div>
				        		<div class="clear show-for-small"></div>
								<div class="row">
									<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding">
										<div class="grid-2ndsm pink grid list">
										</div>
									</div>
									<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding" >
										<div class="grid-2ndsm navy-blue grid list">
										</div>
									</div>
								</div>
				        	</div>
				        @elseif($i==3)
						        	<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
				                        <div class="grid-1h-row grid light-green list" style="background:{{$row->backgroundcolor_code}}">
				                            <figure>
				                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
			                					</a>
								                <a href="/itemdetail/{{$row->id}}">
		  			                                @if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
														<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
													@else
													<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
													@endif
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">Price: {{$row->price}} Ks</div>
															<!-- <div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div> -->
														</div>
					                               	</figcaption>
					                            </a>
				                            </figure>
				                       </div>
				                    </div>
								</div>
								<div class="clear show-for-small"></div>
						@endif
						@if($i==3 && $i==$stationerycounts)
							<div class="row">
								<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding">
									<div class="grid-2ndsm pink grid list">
									</div>
								</div>
								<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding" >
									<div class="grid-2ndsm navy-blue grid list">
									</div>
								</div>
							</div>
						</div>
				        @endif

				        @if($i==4)
						        <div class="row">
									<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding">
										<div class="grid-2ndsm pink grid list" style="background:{{$row->backgroundcolor_code}}">
											<figure>
				                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
					                                @if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
														<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
													@else
													<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
													@endif
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">Price: {{$row->price}} Ks</div>
															<!-- <div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div> -->
														</div>
					                                </figcaption>
				                                </a>
				                            </figure>
										</div>
									</div>
						@endif
				        @if($i==4 && $i==$stationerycounts)
				        			<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding" >
										<div class="grid-2ndsm navy-blue grid list">
										</div>
									</div>
								</div>
							</div>
				        @endif

				        @if($i==5 && $i==$stationerycounts)
						        <div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding" >
									<div class="grid-2ndsm navy-blue grid list" style="background:{{$row->backgroundcolor_code}}">
										<figure>
			                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
												<span class="btn_edit"></span>
			                				</a>
							                <a href="/itemdetail/{{$row->id}}">
				                                @if(file_exists('itemphoto/php/files/medium/'.$row->home_image) && $row->home_image!='')
													<img src="../itemphoto/php/files/medium/{{$row->home_image}}" style="max-height: 100%;max-width: 100%;">
												@else
												<img class="center_photo" src="itemphoto/php/files/{{$row->image}}">
												@endif
				                                <figcaption><br>
					                                <div class="grid_title_bg">
					                                  <div class="grid_title">{{$row->name_mm}}</div>
														<div class="grid_price">Price: {{$row->price}} Ks</div>
														<!-- <div class="second_row_grid_2_love">
															<div class="love_icon"></div>
															<div class="love_text">
																၅၂၈
															</div>
														</div> -->
													</div>
				                                </figcaption>
				                            </a>
			                            </figure>
									</div>
								</div>
							</div>
						</div>
						@endif
					@endif
					<?php $i++; ?>
				@endforeach
			</div>
		</div>
	@else
		<div class="large-8 medium-12 columns nopadding">
			<div class="row">
				<div class="large-6 medium-6 small-12 column nopadding">
					<div class="grid-2-row grid">
					</div>
				</div>
				<div class="clear show-for-small"></div>
				<div class="large-6 medium-6 small-12 column nopadding">
		        	<div class="row">
						<div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
		                    <div class="grid-1h-row light-green grid list">
		                   	</div>
		                </div>

		                <div class="large-6 medium-6 small-6 column  grid cs-style-3 nopadding">
		                    <div class="grid-1h-row grid pink list">
		                   </div>
		                </div>
					</div>
					<div class="clear show-for-small"></div>
					<div class="row">
						<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding">
							<div class="grid-2ndsm pink grid list">
							</div>
						</div>
						<div class="large-6 medium-6 small-6 column grid cs-style-3 nopadding" >
							<div class="grid-2ndsm navy-blue grid list">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif
	<div class="large-2 medium-2 small-6 columns nopadding hide-for-small hide-for-medium">
		<div class="second_brand_bg">
			<div class="brand_bg">
				<div class="adv_brand">
					<div class="two_col_frames">
						<div class="clearfix brand_logo_wrap">
							@if($responses['stationerybrands'])
								<?php $i=1; ?>
								@foreach($responses['stationerybrands'] as $brands)
									@if($i<=8)
									<div class="logo_wrap">
										<a href="" class="brand_bg_white" style="-webkit-transform: rotate(45deg);"></a>
										<a href="../listbybrand/{{$menu10->id}}/{{$brands->id}}" class="logo_link" >
											<img src="brandphoto/php/files/{{$brands->image}}">
										</a>
										
									</div>
									@else
										@if($i==9)
											<?php $class='gray_logo1'; ?>
										@elseif($i==10)
											<?php $class='gray_logo2'; ?>
										@else
											<?php $class='gray_logo3'; ?>
										@endif
										<div class="logo_wrap {{$class}}">
											<a href="" class="brand_bg_gray" style="-webkit-transform: rotate(45deg);"></a>
											<a href="../listbybrand/{{$menu10->id}}/{{$brands->id}}" class="logo_link" ><img src="brandphoto/php/files/{{$brands->image}}"></a>
											
										</div>
									@endif
									<?php $i++; ?>
								@endforeach
							@endif

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div>&nbsp;</div>



{{HTML::script('../../../js/hover/modernizr.custom.js')}}
{{HTML::script('../../../js/hover/toucheffects.js')}}
{{HTML::script('../../../src/select2.min.js')}}
{{HTML::script('../../../src/jquery-ui.js')}}
{{HTML::script('../../../src/jquery.fileupload.js')}}
{{HTML::script('../../../js/updateitem.js')}}
<script type="text/javascript">	
	$(document).foundation({
	  orbit: {
	    animation: 'slide',
	    timer_speed: 1000,
	    pause_on_hover: true,
	    animation_speed: 500,
	    navigation_arrows: true,
	    bullets: true,
	    next_on_click: true
	  }
	});
</script>
<script type="text/javascript">
	$(function(){
        $('.selecttwo').select2();
        $('.delivery').select2();
        $('.selecttwo').select2();
       // $("#navbarmagellan").floatingFixed({ padding: 120, right:3, width:80 });
	});
</script>
<script type="text/javascript">
	//this is where we apply opacity to the arrow
	$(window).scroll( function(){

	  //get scroll position
	  var topWindow = $(window).scrollTop();
	  //multiply by 1.5 so the arrow will become transparent half-way up the page
	  var topWindow = topWindow * 1.5;
	  
	  //get height of window
	  var windowHeight = $(window).height();
	      
	  //set position as percentage of how far the user has scrolled 
	  var position = topWindow / windowHeight;
	  //invert the percentage
	  position = position;

	  //define arrow opacity as based on how far up the page the user has scrolled
	  //no scrolling = 1, half-way up the page = 0
	  $('#navbarmagellan').css('opacity', position);

	});
	
	jQuery(document).tooltip({
				position: {
					my: "right-0",
					at: "left",
					using: function( position, feedback ) {
						$( this ).css( position );
						$( "<div>" )
							.addClass( "arrow" )
							.addClass( feedback.vertical )
							.addClass( feedback.horizontal )
							.appendTo( this );
					}
				}
			});


</script>

@stop
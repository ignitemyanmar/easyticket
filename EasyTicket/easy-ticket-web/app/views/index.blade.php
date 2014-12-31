@extends('../master')
@section('content')
{{HTML::style('../css/hover/component.css')}}
{{HTML::style('../../src/select2.css')}}
{{HTML::style('../../css/upload.css')}}
<style type="text/css">

body .ui-tooltip, .arrow:after {
    background: #F45905;
    /*border: 1px solid #ccc;*/
    z-index: 99999;
  }
 body .ui-tooltip {
    padding: 12px 13px;
    color: white;
    border-radius: 0px;
    font: bold 14px "Helvetica Neue", Sans-Serif;
  }	
   .ui-tooltip {
    padding: 8px;
    position: absolute;
   }
   body .ui-tooltip {
    /*border-width: 2px;*/
   }
   /*.9999999999999999999999999999999999999999999*/
	body, p, form, input, button, dl, dt, dd, ul, ol, li, h1, h2, h3, h4 {
	    list-style: none outside none;
	}

	.magellan{height: 0px !important; margin: 0 !important;}
	#navbarmagellan{width:40px; background:#2484DB;position:fixed;top: 20%;
		right:0px; opacity:0;z-index: 99;		
	}
	.sub-nav dd.active a{border-radius: 0;-webkit-border-radius:0; -moz-border-radius:0; width:120px;}
	#navbarmagellan dd{line-height: 2.9rem;text-overflow: ellipsis;
		white-space: nowrap;
		overflow: hidden;
		width: 50px;

		}
	#navbarmagellan dd{
		margin-left: 0px;
	}
	.sub-nav dt.active a, .sub-nav dd.active a, .sub-nav li.active a{
		padding: 0px;
		background: transparent;
	}
	.sub-nav dt.active a:hover, .sub-nav dd.active a:hover, .sub-nav li.active a:hover{
		padding: 0px;
		background: transparent;
	}
	.sub-nav dt.active, .sub-nav dd.active , .sub-nav li.active {
		padding: 0px;
		background: #F45905;
	}
	.sub-nav dt:hover, .sub-nav dd:hover , .sub-nav li:hover {
		padding: 0px;
		background: #F45905;
	}
	#navbarmagellan dd img{
		margin-left: 5px;
	}
	.sub-nav dd a{color: white; cursor: pointer;}
	.sub-nav dd a:hover {width: 120px; }
	
  @-webkit-keyframes navbarmagellan {
    0% { top:0; }
    10% { top:12%; }
    20% { top:0; }
    30% { top:12%; }
    40% { top:-12%; }
    50% { top:12%; }
    60% { top:0; }
    70% { top:12%; }
    80% { top:-12%; }
    90% { top:12%; }
    100% { top:0; }
  }
  
  .arrow-wrap .arrow {
    -webkit-animation: arrows 2.8s 0.4s;
    -webkit-animation-delay: 3s;
  }
	.adv_brand {
	    position: relative;
	    overflow: hidden;
	}

	.adv_brand .two_col_frames {
	    position: relative;
	    width: 194px;
	    height: 403px;
	    float: left;
	    background: none repeat scroll 0% 0% #F5F5F5;
	}

	.adv_brand .two_col_frames .logo_wrap {
	    position: relative;
	    z-index: 95;
	    float: left;
	    width: 90px;
	    height: 90px;
	}

	.brand_logo_wrap {
	    width: 190px;
	    padding: 0px 2px;
	}
	.adv_brand .two_col_frames .brand_bg_white, .brand_bg_white {
	    position: absolute;
	    top: 0px;
	    left: 0px;
	    width: 63px;
	    height: 63px;
	    margin: 12px;
	    background: none repeat scroll 0% 0% #FFF;
	}
	.brand_bg_white {
	    transform: rotate(45deg);
	}

	.adv_brand .two_col_frames .logo_wrap .logo_link {
	    display: block;
	    width: 45px;
	    height: 45px;
	    position: absolute;
	    top: 33px;
	    left: 22px;
	}

	.adv_brand .two_col_frames .discount {
	    display: none;
	    position: absolute;
	    top: 0px;
	    left: 0px;
	    width: 90px;
	    height: 90px;
	    background: url('img/brand_hoverbg.png?1100777') no-repeat scroll 0% 0% transparent;
	    color: #FFF;
	    text-align: center;
	}

	.adv_brand .two_col_frames .gray_logo1 {
	    z-index: 96;
	    float: none;
	    top: 42px;
	    left: 42px;
	    width: 45px;
	    height: 45px;
	}

	.adv_brand .two_col_frames .gray_logo2 {
	    z-index: 96;
	    float: none;
	    top: 89px;
	    left: 42px;
	    width: 42px;
	    height: 45px;
	}
	.adv_brand .two_col_frames .gray_logo3 {
	    z-index: 96;
	    float: none;
	    top: 135px;
	    left: 42px;
	    width: 45px;
	    height: 45px;
	}

	.adv_brand .two_col_frames .brand_bg_gray {
	    position: absolute;
	    top: -23px;
	    left: -23px;
	    width: 63px;
	    height: 63px;
	    margin: 12px;
	}

	.clearfix {
	}

	.clearfix:after {
	    display: block;
	    content: ".";
	    height: 0px;
	    visibility: hidden;
	    clear: both;
	    font-size: 0px;
	    line-height: 0;
	}

	.grid{position: relative;}
	.grid img{ top: -100%;bottom: -100%; left: 0;right: 0; margin: auto; position: absolute;}
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
			                     	<label class="control-label">Name_MM</label>
			                    </div>
			                    <div class="large-8 columns">
			                        <input type="text" name="name" required placeholder="Item Name" value="" id='itemnamemm'/>
			                    </div>
			                </div>

			                <div class="row">
                                <label class="large-4 columns">Image </label>
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
	          	<ul class="example-orbit" data-orbit> 
		            <li> 
						<img src="../../img/promo.jpg">
		            </li>
		            <li> 
						<img src="../../img/promo1.jpg">
		            </li> 
		            <li> 
						<img src="../../img/promo2.jpg">
		            </li> 
		            <li> 
						<img src="../../img/promo3.jpg">
		            </li> 
		            <li> 
						<img src="../../img/promo4.jpg">
		            </li>  
		            <li> 
						<img src="../../img/promo1.png">
		            </li>  
	          	</ul>
	      	</div>
      	</div>
      	<div class="row">
      		@if($responses['slidebelow'])
      			@foreach($responses['slidebelow'] as $sl_belowitem)
				<div class="large-4 medium-4 small-6 column gallery-3 grid">
					<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$sl_belowitem->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
						<span class="btn_edit"></span>
	                </a>
	                <a href="/itemdetail/{{$sl_belowitem->id}}">
						<div class="gallery_item" style="background:{{$sl_belowitem->colorcode}};">
							<img src="itemphoto/php/files/medium/{{$sl_belowitem->image}}">
						</div>
					</a>
				</div>
				@endforeach
			@endif
			<!-- <div class="large-4 medium-4 small-6 column gallery-3">
				<div class="gallery_item">&nbsp;</div>
			</div>
			<div class="large-4 medium-4 column gallery-3 hide-for-small">
				<div class="gallery_item">&nbsp;</div>
			</div> -->
		</div>
	</div>
	<div class="large-2 medium-6 columns nopadding show-for-large">
		<div class="currency_exchange">
			<span class="currency_exchange_title">ယေန႕ေငြလဲလွဲမႈႏႈန္း </span>
			<hr class="exchange_line">
			<span class="currency_exchange_title">ေငြေၾကး  </span>
			<hr class="exchange_line">
			<div>
				<span class="currency">USD</span><span class="rate">980</span>
			</div>
			<div class="clearfix"></div>
			<div>
				<span class="currency">SGD</span><span class="rate">980</span>
			</div>
			<div class="clearfix"></div>
			<div>
				<span class="currency">SGD</span><span class="rate">980</span>
			</div>
			<div class="clearfix"></div>
			<div>
				<span class="currency">UR</span><span class="rate">980</span>
			</div>
		</div>
		<div class="clear"></div>
		<div class="advertise_bg">
			<img src="../images/advertise.jpg">
		</div>
	</div>
</div>

<!-- <div data-magellan-expedition="fixed" id="navbarmagellan"> -->
<div data-magellan-expedition="fixed" style="z-index:97;">
    <dl class="sub-nav" id="navbarmagellan">
      <?php
      	$menu1 = Menu::where('name','like','%Electronic%')->first();
      	$menu2 = Menu::where('name','like','%Fashion%')->first();
      	$menu3 = Menu::where('name','like','%Cosmetics%')->first();
      	$menu4 = Menu::where('name','like','%Babies & Mother Accessories%')->first();
      	$menu5 = Menu::where('name','like','%Kitchen accessories%')->first();
      	$menu6 = Menu::where('name','like','%Computer Accessories%')->first();
      	$menu7 = Menu::where('name','like','%Mobile Phone Accessories%')->first();
      	$menu8 = Menu::where('name','like','%Camera Accessories%')->first();
      	$menu9 = Menu::where('name','like','%Sport Accessories%')->first();
      	$menu10 = Menu::where('name','like','%Stationery%')->first();
      	$menu11 = Menu::where('name','like','%Car Accessories%')->first();
      ?>
      <dd data-magellan-arrival="electronic"><a href="#electronic" title="{{$menu1->name}}"><img src="../menuphoto/php/files/{{$menu1->image}}"></a></dd>
      <dd data-magellan-arrival="clothing"><a href="#clothing" title="{{$menu2->name}}"><img src="../menuphoto/php/files/{{$menu2->image}}"></a></dd>
      <dd data-magellan-arrival="cosmetic"><a href="#cosmetic" title="{{$menu3->name}}"><img src="../menuphoto/php/files/{{$menu3->image}}"></a></dd>
      <dd data-magellan-arrival="babies-mother"><a href="#babies-mother" title="{{$menu4->name}}"><img src="../menuphoto/php/files/{{$menu4->image}}"></a></dd>
      <dd data-magellan-arrival="kitchens"><a href="#kitchens" title="{{$menu5->name}}"><img src="../menuphoto/php/files/{{$menu5->image}}"></a></dd>
      <dd data-magellan-arrival="computer"><a href="#computer" title="{{$menu6->name}}"><img src="../menuphoto/php/files/{{$menu6->image}}"></a></dd>
      <dd data-magellan-arrival="mobile"><a href="#mobile" title="{{$menu7->name}}"><img src="../menuphoto/php/files/{{$menu7->image}}"></a></dd>
      <dd data-magellan-arrival="camera"><a href="#camera" title="{{$menu8->name}}"><img src="../menuphoto/php/files/{{$menu8->image}}"></a></dd>
      <dd data-magellan-arrival="sport"><a href="#sport" title="{{$menu9->name}}"><img src="../menuphoto/php/files/{{$menu9->image}}"></a></dd>
      <dd data-magellan-arrival="stationery"><a href="#stationery" title="{{$menu10->name}}"><img src="../menuphoto/php/files/{{$menu10->image}}"></a></dd>
      <dd data-magellan-arrival="caraccessories"><a href="#caraccessories" title="{{$menu11->name}}"><img src="../menuphoto/php/files/{{$menu11->image}}"></a></dd>
    </dl>
</div>


<!-- //electronic -->
<p class="magellan"><a name="electronic"></a></p>
<h3 data-magellan-destination="electronic" class="magellan">&nbsp;</h3>
<div class="row" >
	<div class="large-2 medium-12 columns nopadding">
		<div class="fashion_title orange">
			လွ်ပ္စစ္ပစၥည္း က႑မ်ား 
		</div>
		@if($responses['electroniccategory'])
				<div class="row nopadding">
			@foreach($responses['electroniccategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<img class="center_photo" src="categoryphoto/php/files/{{$row->image}}"></li> 
					</div>
			@endforeach
				</div>
		@endif
		<div class="clear"></div>
		<div class="row electronic">
			<div class="large-12 columns feature_item_photo show-for-large grid nopadding">
			<!-- <div class="large-12 columns feature_item_photo show-for-large"> -->
				<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
					<!-- <span class="btn_edit"></span> -->
                </a>
				<img src="../img/grid6.jpg">
			</div>
		</div>

		<!-- </div> -->
	</div>
	@if(count($responses['objobjelectronic'])>0)
		<div class="large-8 medium-12 columns nopadding">
				<?php $i=1; $electroniccounts=count($responses['objobjelectronic'])?>
				
				@foreach($responses['objobjelectronic'] as $row)
					
					@if($i==1)
						<div class="row">
							<div class="large-6 medium-6 small-6 grid columns nopadding">
								<div class="grid_two_frame light-green-bl">
									<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
										<span class="btn_edit"></span>
					                </a>
								    <a href="/itemdetail/{{$row->id}}">
										<img src="itemphoto/php/files/{{$row->image}}">
									</a>
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
							<div class="large-6 medium-6 small-6 grid columns nopadding">
									<div class="grid_two_frame pink">
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
									    <a href="/itemdetail/{{$row->id}}">
											<img src="itemphoto/php/files/{{$row->image}}">
										</a>
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
									<div class="grid_four_frame light-green">
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
									    <a href="/itemdetail/{{$row->id}}">
											<img src="itemphoto/php/files/{{$row->image}}">
											<span class="grid_title_bg">
												<div class="grid_title">{{$row->name_mm}}</div>
												<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
												<div class="second_row_grid_2_love">
													<div class="love_icon"></div>
													<div class="love_text">
														၅၂၈
													</div>
												</div>
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
								<div class="grid_four_frame pink">
									<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
										<span class="btn_edit"></span>
					                </a>
								    <a href="/itemdetail/{{$row->id}}">
										<img src="itemphoto/php/files/{{$row->image}}">
										<span class="grid_title_bg">
											<div class="grid_title">{{$row->name_mm}}</div>
											<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
											<div class="second_row_grid_2_love">
												<div class="love_icon"></div>
												<div class="love_text">
													၅၂၈
												</div>
											</div>
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
								<div class="grid_four_frame light-pink">
									<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
										<span class="btn_edit"></span>
					                </a>
								    <a href="/itemdetail/{{$row->id}}">
										<img src="itemphoto/php/files/{{$row->image}}">
										<span class="grid_title_bg">
											<div class="grid_title">{{$row->name_mm}}</div>
											<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
											<div class="second_row_grid_2_love">
												<div class="love_icon"></div>
												<div class="love_text">
													၅၂၈
												</div>
											</div>
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
									<div class="grid_four_frame orange">
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
									    <a href="/itemdetail/{{$row->id}}">
											<img src="itemphoto/php/files/{{$row->image}}">
											<span class="grid_title_bg">
												<div class="grid_title">{{$row->name_mm}}</div>
												<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
												<div class="second_row_grid_2_love">
													<div class="love_icon"></div>
													<div class="love_text">
														၅၂၈
													</div>
												</div>
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
	<div class="large-2 columns show-for-large nopadding">
		<div class="second_brand_bg">
			@if($responses['electorincbrands'])
				@foreach($responses['electorincbrands'] as $brands)
					<div class="row">
						<div class="large-5 small-5 columns brand_image_frame">
							<img src="brandphoto/php/files/{{$brands->image}}">
						</div>
						<div class="large-7 small-7 columns">
							<div class="brand_title">{{$brands->name}}</div>
						</div>
					</div>
				@endforeach
			@endif
		</div>
	</div>
</div>

<!-- Clothings -->
<p class="magellan"><a name="clothing"></a></p>
<h3 data-magellan-destination="clothing" class="magellan">&nbsp;</h3>
	
<div class="row">
	<div class="large-2 medium columns  nopadding">
		<div class="fashion_title pink">
			အဝတ္အစား က႑မ်ား
		</div>
		@if($responses['clothingcategory'])
				<div class="row nopadding">
			@foreach($responses['clothingcategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<img class="center_photo" src="categoryphoto/php/files/{{$row->image}}">
					</div>
			@endforeach
				</div>
		@endif
		<div class="clear"></div>
		<div class="row">
			<div class="large-12 columns feature_item_photo show-for-large grid nopadding">
				<!-- <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
					<span class="btn_edit"></span>
                </a> -->
	            <img src="img/cosmetic1.jpg">
			</div>
		</div>

		<!-- </div> -->
	</div>
	
	@if(count($responses['clothings'])>0)
		<div class="large-8 medium-12 columns nopadding">
			<div class="row">
				<?php $i=1; $clothingcounts=count($responses['clothings'])?>
				@foreach($responses['clothings'] as $row)
					@if($i==1)
						<div class="large-6 medium-6 small-12 column nopadding">
							<div class="grid-2-row grid">
								<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
									<span class="btn_edit"></span>
				                </a>
				                <a href="/itemdetail/{{$row->id}}">
				                    <img src="../itemphoto/php/files/{{$row->image}}">
				                    <div class="grid_title_bg">
				                      	<div class="grid_title">{{$row->name}}</div>
										<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k </div>
										<div class="second_row_grid_2_love">
											<div class="love_icon"></div>
											<div class="love_text">
												၅၂၈
											</div>
										</div>
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
				                        <div class="grid-1h-row light-green grid list">
				                            <figure>
												<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
				                               		<img src="itemphoto/php/files/{{$row->image}}">
					                               	<figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k </div>
															<div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div>
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
				                        <div class="grid-1h-row grid light-green list">
				                            <figure>
				                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
			                					</a>
								                <a href="/itemdetail/{{$row->id}}">
		  			                                <img src="itemphoto/php/files/{{$row->image}}">
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
															<div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div>
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
										<div class="grid-2ndsm pink grid list">
											<figure>
				                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
					                                <img src="itemphoto/php/files/{{$row->image}}">
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
															<div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div>
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
									<div class="grid-2ndsm navy-blue grid list">
										<figure>
			                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
												<span class="btn_edit"></span>
			                				</a>
							                <a href="/itemdetail/{{$row->id}}">
				                                <img src="itemphoto/php/files/{{$row->image}}">
				                                <figcaption><br>
					                                <div class="grid_title_bg">
					                                  <div class="grid_title">{{$row->name_mm}}</div>
														<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
														<div class="second_row_grid_2_love">
															<div class="love_icon"></div>
															<div class="love_text">
																၅၂၈
															</div>
														</div>
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
	<div class="large-2 medium-2 small-6 columns show-for-large nopadding">
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
										<a href="#" class="brand_bg_white" style="-webkit-transform: rotate(45deg);"></a>
										<a href="http://s.yhd.com/list/13225" target="_blank" class="logo_link" data-ref="14582_12949074_1">
											<img src="brandphoto/php/files/{{$brands->image}}">
										</a>
										<a href="http://s.yhd.com/list/13225?tp=1.0.27.0.22.VoYS0U" target="_blank" class="discount" data-ref="14582_12949074_1" style="display: none;">
										<i>2.9</i>
										<u>折起</u>
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
											<a href="#" class="brand_bg_gray" style="-webkit-transform: rotate(45deg);"></a>
											<a href="http://s.yhd.com/list/12900" target="_blank" class="logo_link" data-ref="14583_13142246_1"><img src="brandphoto/php/files/{{$brands->image}}"></a>
											<a href="http://s.yhd.com/list/12900" target="_blank" class="discount" data-ref="14583_13142246_1" style="display: none;">
											<i>4.5</i>
											<u>折封顶</u>
											</a>
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
<!-- Make UP accessories -->
<div class="row" >
	<p class="magellan"><a name="cosmetic"></a></p>
	<h3 data-magellan-destination="cosmetic" class="magellan">&nbsp;</h3>

	<div class="large-2 medium-12 columns nopadding">
		<div class="fashion_title navy-blue">
			မိတ္ကပ္ႏွင့္အလွျပင္ပစၥည္း မ်ား
		</div>
		@if($responses['cosmeticcategory'])
				<div class="row nopadding">
			@foreach($responses['cosmeticcategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<img class="center_photo" src="categoryphoto/php/files/{{$row->image}}"></li> 
					</div>
			@endforeach
				</div>
		@endif
		<div class="clear"></div>
		<div class="row">
			<div class="large-12 columns feature_item_photo show-for-large nopadding">
				<!-- <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
					<span class="btn_edit"></span>
                </a> -->
				<img src="img/electronic3.jpg">
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
							<div class="grid-2-row grid">
				                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
									<span class="btn_edit"></span>
				                </a>
				                <a href="/itemdetail/{{$row->id}}">
				                    <img src="itemphoto/php/files/{{$row->image}}">
				                    <div class="grid_title_bg">
				                      	<div class="grid_title">{{$row->name_mm}}</div>
										<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
										<div class="second_row_grid_2_love">
											<div class="love_icon"></div>
											<div class="love_text">
												၅၂၈
											</div>
										</div>
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
				                        <div class="grid-1h-row light-green grid list">
				                            <figure>
				                               <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
				                				<a href="/itemdetail/{{$row->id}}">
 				                                	<img src="itemphoto/php/files/{{$row->image}}">
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
															<div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div>
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
				                        <div class="grid-1h-row grid light-green list">
				                            <figure>
				                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
					                                <img src="itemphoto/php/files/{{$row->image}}">
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
															<div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div>
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
									<div class="large-12 medium-12 small-12 grid columns nopadding">
										<div class="grid-2ndsm light-green-bl">
											<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
												<span class="btn_edit"></span>
							                </a>
								            <a href="/itemdetail/{{$row->id}}">
												<img src="itemphoto/php/files/{{$row->image}}">
											</a>
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
	<div class="large-2 columns show-for-large nopadding">
		<div class="second_brand_bg">
			@if($responses['cosmeticbrand'])
				@foreach($responses['cosmeticbrand'] as $brands)
					<div class="row">
						<div class="large-5 small-5 columns brand_image_frame">
							<img src="brandphoto/php/files/{{$brands->image}}">
						</div>
						<div class="large-7 small-7 columns">
							<div class="brand_title">{{$brands->name}}</div>
						</div>
					</div>
				@endforeach
			@endif
		</div>
	</div>
</div>
<!-- Mothers and Babies -->
<div class="row" >
	<p class="magellan"><a name="babies-mother"></a></p>
	<h3 data-magellan-destination="babies-mother" class="babies-mother">&nbsp;</h3>
	<div class="large-2 medium-12 columns nopadding">
		<div class="fashion_title orange">
			မိခင္ႏွင့္ကေလး သုံးပစၥည္းမ်ား
		</div>
		@if($responses['babiescategory'])
				<div class="row nopadding">
			@foreach($responses['babiescategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<img class="center_photo" src="categoryphoto/php/files/{{$row->image}}"></li> 
					</div>
			@endforeach
				</div>
		@endif
		<div class="clear"></div>
		<div class="row babies">
			<div class="large-12 columns feature_item_photo show-for-large grid nopadding">
			<!-- <div class="large-12 columns feature_item_photo show-for-large"> -->
				<!-- <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
					<span class="btn_edit"></span>
                </a> -->
				<img src="../img/grid6.jpg">
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
							<div class="large-6 medium-6 small-6 grid columns nopadding">
								<div class="grid_two_frame light-green-bl">
									<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
										<span class="btn_edit"></span>
					                </a>
								    <a href="/itemdetail/{{$row->id}}">
										<img src="itemphoto/php/files/{{$row->image}}">
									</a>
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
							<div class="large-6 medium-6 small-6 grid columns nopadding">
									<div class="grid_two_frame pink">
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
									    <a href="/itemdetail/{{$row->id}}">
											<img src="itemphoto/php/files/{{$row->image}}">
										</a>
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
									<div class="grid_four_frame light-green">
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
									    <a href="/itemdetail/{{$row->id}}">
											<img src="itemphoto/php/files/{{$row->image}}">
											<span class="grid_title_bg">
												<div class="grid_title">{{$row->name_mm}}</div>
												<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
												<div class="second_row_grid_2_love">
													<div class="love_icon"></div>
													<div class="love_text">
														၅၂၈
													</div>
												</div>
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
								<div class="grid_four_frame pink">
									<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
										<span class="btn_edit"></span>
					                </a>
								    <a href="/itemdetail/{{$row->id}}">
										<img src="itemphoto/php/files/{{$row->image}}">
										<span class="grid_title_bg">
											<div class="grid_title">{{$row->name_mm}}</div>
											<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
											<div class="second_row_grid_2_love">
												<div class="love_icon"></div>
												<div class="love_text">
													၅၂၈
												</div>
											</div>
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
								<div class="grid_four_frame light-pink">
									<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
										<span class="btn_edit"></span>
					                </a>
								    <a href="/itemdetail/{{$row->id}}">
										<img src="itemphoto/php/files/{{$row->image}}">
										<span class="grid_title_bg">
											<div class="grid_title">{{$row->name_mm}}</div>
											<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
											<div class="second_row_grid_2_love">
												<div class="love_icon"></div>
												<div class="love_text">
													၅၂၈
												</div>
											</div>
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
									<div class="grid_four_frame orange">
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
									    <a href="/itemdetail/{{$row->id}}">
											<img src="itemphoto/php/files/{{$row->image}}">
											<span class="grid_title_bg">
												<div class="grid_title">{{$row->name_mm}}</div>
												<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
												<div class="second_row_grid_2_love">
													<div class="love_icon"></div>
													<div class="love_text">
														၅၂၈
													</div>
												</div>
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
	<div class="large-2 medium-2 small-6 columns show-for-large nopadding">
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
										<a href="#" class="brand_bg_white" style="-webkit-transform: rotate(45deg);"></a>
										<a href="http://s.yhd.com/list/13225" target="_blank" class="logo_link" data-ref="14582_12949074_1">
											<img src="brandphoto/php/files/{{$brands->image}}">
										</a>
										<a href="http://s.yhd.com/list/13225?tp=1.0.27.0.22.VoYS0U" target="_blank" class="discount" data-ref="14582_12949074_1" style="display: none;">
										<i>2.9</i>
										<u>折起</u>
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
											<a href="#" class="brand_bg_gray" style="-webkit-transform: rotate(45deg);"></a>
											<a href="http://s.yhd.com/list/12900" target="_blank" class="logo_link" data-ref="14583_13142246_1"><img src="brandphoto/php/files/{{$brands->image}}"></a>
											<a href="http://s.yhd.com/list/12900" target="_blank" class="discount" data-ref="14583_13142246_1" style="display: none;">
											<i>4.5</i>
											<u>折封顶</u>
											</a>
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
<div class="row" >
	<p class="magellan"><a name="kitchens"></a></p>
	<h3 data-magellan-destination="kitchens" class="magellan">&nbsp;</h3>

	<div class="large-2 columns  nopadding">
		<div class="fashion_title pink">
			မီးဖိုေခ်ာင္သံုးပစၥည္း က႑မ်ား
		</div>
		@if($responses['kitchencategory'])
				<div class="row nopadding">
			@foreach($responses['kitchencategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<img class="center_photo" src="categoryphoto/php/files/{{$row->image}}"></li> 
					</div>
			@endforeach
				</div>
		@endif
		<div class="clear"></div>
		<div class="row">
			<div class="large-12 columns feature_item_photo show-for-large grid nopadding">
				<!-- <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
					<span class="btn_edit"></span>
                </a> -->
				<img src="img/bedroom.jpg">
			</div>
		</div>

		<!-- </div> -->
	</div>
	
	@if(count($responses['objkitchen'])>0 || count($responses['kitchencategory_in_grid'])>0)
		<?php $i=1; $count=count($responses['objkitchen']); ?>
		<div class="large-8 medium-12 columns nopadding" style="background: #EDF2F1;">
			<div class="row">
				@foreach($responses['objkitchen'] as $row)
					@if($i==1)
							<div class="large-6 medium-6 column nopadding">
								<div class="row">
									<div class="large-6 medium-6 small-6 columns grid cs-style-3 nopadding">
										<div class="grid_four_frame light-green">
											<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
												<span class="btn_edit"></span>
							                </a>
							                <a href="/itemdetail/{{$row->id}}">
												<img src="itemphoto/php/files/{{$row->image}}">
												<span class="grid_title_bg">
													<div class="grid_title">{{$row->name_mm}}</div>
													<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
													<div class="second_row_grid_2_love">
														<div class="love_icon"></div>
														<div class="love_text">
															၅၂၈
														</div>
													</div>
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
						@endif
					@else

						@if($i==2)
								<div class="large-6 medium-6 small-6 grid columns nopadding">
											<div class="grid_four_frame pink">
												<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
													<img src="itemphoto/php/files/{{$row->image}}">
													<span class="grid_title_bg">
														<div class="grid_title">{{$row->name_mm}}</div>
														<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
														<div class="second_row_grid_2_love">
															<div class="love_icon"></div>
															<div class="love_text">
																၅၂၈
															</div>
														</div>
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
										<div class="large-12 medium-12 small-12 grid columns nopadding">
											<div class="grid_two_frame light-green-bl">
												<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
													<img src="itemphoto/php/files/{{$row->image}}">
												</a>
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
										<img src="categoryphoto/php/files/thumbnail/{{$row->image}}" alt="">
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
	<div class="large-2 columns show-for-large nopadding">
		<div class="second_brand_bg">
			@if($responses['kitchenbrnads'])
				@foreach($responses['kitchenbrnads'] as $brands)
					<div class="row">
						<div class="large-5 small-5 columns brand_image_frame">
							<img src="brandphoto/php/files/{{$brands->image}}">
						</div>
						<div class="large-7 small-7 columns">
							<div class="brand_title">{{$brands->name}}</div>
						</div>
					</div>
				@endforeach
			@endif
		</div>
	</div>
</div>
<div>&nbsp;</div>

<!-- Computer accessories -->
<div class="row" >
	<p class="magellan"><a name="computer"></a></p>
	<h3 data-magellan-destination="computer" class="magellan">&nbsp;</h3>

	<div class="large-2 medium-12 columns nopadding">
		<div class="fashion_title navy-blue">
			ကြန္ပ်ဴ တာ ပစၥည္းမ်ား
		</div>
		@if($responses['computercategory'])
				<div class="row nopadding">
			@foreach($responses['computercategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<img class="center_photo" src="categoryphoto/php/files/{{$row->image}}"></li> 
					</div>
			@endforeach
				</div>
		@endif
		<div class="clear"></div>
		<div class="row">
			<div class="large-12 columns feature_item_photo show-for-large nopadding">
				<!-- <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
					<span class="btn_edit"></span>
                </a> -->
				<img src="img/electronic3.jpg">
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
							<div class="grid-2-row grid">
				                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
									<span class="btn_edit"></span>
				                </a>
				                <a href="/itemdetail/{{$row->id}}">
				                    <img src="itemphoto/php/files/{{$row->image}}">
				                    <div class="grid_title_bg">
				                      	<div class="grid_title">{{$row->name_mm}}</div>
										<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
										<div class="second_row_grid_2_love">
											<div class="love_icon"></div>
											<div class="love_text">
												၅၂၈
											</div>
										</div>
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
				                        <div class="grid-1h-row light-green grid list">
				                            <figure>
				                               <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
				                				<a href="/itemdetail/{{$row->id}}">
 				                                	<img src="itemphoto/php/files/{{$row->image}}">
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
															<div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div>
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
				                        <div class="grid-1h-row grid light-green list">
				                            <figure>
				                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
					                                <img src="itemphoto/php/files/{{$row->image}}">
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
															<div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div>
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
									<div class="large-12 medium-12 small-12 grid columns nopadding">
										<div class="grid-2ndsm light-green-bl">
											<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
												<span class="btn_edit"></span>
							                </a>
								            <a href="/itemdetail/{{$row->id}}">
												<img src="itemphoto/php/files/{{$row->image}}">
											</a>
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
	<div class="large-2 medium-2 small-6 columns show-for-large nopadding">
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
										<a href="#" class="brand_bg_white" style="-webkit-transform: rotate(45deg);"></a>
										<a href="http://s.yhd.com/list/13225" target="_blank" class="logo_link" data-ref="14582_12949074_1">
											<img src="brandphoto/php/files/{{$brands->image}}">
										</a>
										<a href="http://s.yhd.com/list/13225?tp=1.0.27.0.22.VoYS0U" target="_blank" class="discount" data-ref="14582_12949074_1" style="display: none;">
										<i>2.9</i>
										<u>折起</u>
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
											<a href="#" class="brand_bg_gray" style="-webkit-transform: rotate(45deg);"></a>
											<a href="http://s.yhd.com/list/12900" target="_blank" class="logo_link" data-ref="14583_13142246_1"><img src="brandphoto/php/files/{{$brands->image}}"></a>
											<a href="http://s.yhd.com/list/12900" target="_blank" class="discount" data-ref="14583_13142246_1" style="display: none;">
											<i>4.5</i>
											<u>折封顶</u>
											</a>
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

<div class="row" >
	<p class="magellan"><a name="mobile"></a></p>
	<h3 data-magellan-destination="mobile" class="magellan">&nbsp;</h3>

	<div class="large-2 medium-12 columns nopadding">
		<div class="fashion_title orange">
			မိုးဘိုင္ဖုန္း ပစၥည္းမ်ား
		</div>
		@if($responses['mobilecategory'])
				<div class="row nopadding">
			@foreach($responses['mobilecategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<img class="center_photo" src="categoryphoto/php/files/{{$row->image}}"></li> 
					</div>
			@endforeach
				</div>
		@endif
		<div class="clear"></div>
		<div class="row mobile">
			<div class="large-12 columns feature_item_photo show-for-large grid nopadding">
			<!-- <div class="large-12 columns feature_item_photo show-for-large"> -->
				<!-- <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
					<span class="btn_edit"></span>
                </a> -->
				<img src="../img/grid6.jpg">
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
							<div class="large-6 medium-6 small-6 grid columns nopadding">
								<div class="grid_two_frame light-green-bl">
									<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
										<span class="btn_edit"></span>
					                </a>
								    <a href="/itemdetail/{{$row->id}}">
										<img src="itemphoto/php/files/{{$row->image}}">
									</a>
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
							<div class="large-6 medium-6 small-6 grid columns nopadding">
									<div class="grid_two_frame pink">
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
									    <a href="/itemdetail/{{$row->id}}">
											<img src="itemphoto/php/files/{{$row->image}}">
										</a>
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
									<div class="grid_four_frame light-green">
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
									    <a href="/itemdetail/{{$row->id}}">
											<img src="itemphoto/php/files/{{$row->image}}">
											<span class="grid_title_bg">
												<div class="grid_title">{{$row->name_mm}}</div>
												<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
												<div class="second_row_grid_2_love">
													<div class="love_icon"></div>
													<div class="love_text">
														၅၂၈
													</div>
												</div>
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
							<div class="large-3 medium-3 small-6 grid columns nopadding">
								<div class="grid_four_frame pink">
									<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
										<span class="btn_edit"></span>
					                </a>
								    <a href="/itemdetail/{{$row->id}}">
										<img src="itemphoto/php/files/{{$row->image}}">
										<span class="grid_title_bg">
											<div class="grid_title">{{$row->name_mm}}</div>
											<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
											<div class="second_row_grid_2_love">
												<div class="love_icon"></div>
												<div class="love_text">
													၅၂၈
												</div>
											</div>
										</span>
									</a>
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
							<div class="large-3 medium-3 small-6 grid columns nopadding">
								<div class="grid_four_frame light-pink">
									<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
										<span class="btn_edit"></span>
					                </a>
								    <a href="/itemdetail/{{$row->id}}">
										<img src="itemphoto/php/files/{{$row->image}}">
										<span class="grid_title_bg">
											<div class="grid_title">{{$row->name_mm}}</div>
											<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
											<div class="second_row_grid_2_love">
												<div class="love_icon"></div>
												<div class="love_text">
													၅၂၈
												</div>
											</div>
										</span>
									</a>
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
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange">
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
									    <a href="/itemdetail/{{$row->id}}">
											<img src="itemphoto/php/files/{{$row->image}}">
											<span class="grid_title_bg">
												<div class="grid_title">{{$row->name_mm}}</div>
												<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
												<div class="second_row_grid_2_love">
													<div class="love_icon"></div>
													<div class="love_text">
														၅၂၈
													</div>
												</div>
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
	<div class="large-2 columns show-for-large nopadding">
		<div class="second_brand_bg">
			@if($responses['mobilebrands'])
				@foreach($responses['mobilebrands'] as $brands)
					<div class="row">
						<div class="large-5 small-5 columns brand_image_frame">
							<img src="brandphoto/php/files/{{$brands->image}}">
						</div>
						<div class="large-7 small-7 columns">
							<div class="brand_title">{{$brands->name}}</div>
						</div>
					</div>
				@endforeach
			@endif
		</div>
	</div>
</div>
<div>&nbsp;</div>

<!-- Camera accessories -->
<div class="row" >
	<p class="magellan"><a name="camera"></a></p>
	<h3 data-magellan-destination="camera" class="magellan">&nbsp;</h3>

	<div class="large-2 medium columns  nopadding">
		<div class="fashion_title pink">
		ကင္မရာပစၥည္းမ်ား
		</div>
		@if($responses['stationerycategory'])
				<div class="row nopadding">
			@foreach($responses['stationerycategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<img class="center_photo" src="categoryphoto/php/files/{{$row->image}}">
					</div>
			@endforeach
				</div>
		@endif
		<div class="clear"></div>
		<div class="row">
			<div class="large-12 columns feature_item_photo show-for-large grid nopadding">
				<!-- <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
					<span class="btn_edit"></span>
                </a> -->
	            <img src="img/cosmetic1.jpg">
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
							<div class="grid-2-row grid">
								<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
									<span class="btn_edit"></span>
				                </a>
				                <a href="/itemdetail/{{$row->id}}">
				                    <img src="../itemphoto/php/files/{{$row->image}}">
				                    <div class="grid_title_bg">
				                      	<div class="grid_title">{{$row->name}}</div>
										<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k </div>
										<div class="second_row_grid_2_love">
											<div class="love_icon"></div>
											<div class="love_text">
												၅၂၈
											</div>
										</div>
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
				                        <div class="grid-1h-row light-green grid list">
				                            <figure>
												<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
				                               		<img src="itemphoto/php/files/{{$row->image}}">
					                               	<figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k </div>
															<div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div>
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
				                        <div class="grid-1h-row grid light-green list">
				                            <figure>
				                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
			                					</a>
								                <a href="/itemdetail/{{$row->id}}">
		  			                                <img src="itemphoto/php/files/{{$row->image}}">
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
															<div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div>
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
										<div class="grid-2ndsm pink grid list">
											<figure>
				                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
					                                <img src="itemphoto/php/files/{{$row->image}}">
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
															<div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div>
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
									<div class="grid-2ndsm navy-blue grid list">
										<figure>
			                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
												<span class="btn_edit"></span>
			                				</a>
							                <a href="/itemdetail/{{$row->id}}">
				                                <img src="itemphoto/php/files/{{$row->image}}">
				                                <figcaption><br>
					                                <div class="grid_title_bg">
					                                  <div class="grid_title">{{$row->name_mm}}</div>
														<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
														<div class="second_row_grid_2_love">
															<div class="love_icon"></div>
															<div class="love_text">
																၅၂၈
															</div>
														</div>
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
	<div class="large-2 medium-2 small-6 columns show-for-large nopadding">
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
										<a href="#" class="brand_bg_white" style="-webkit-transform: rotate(45deg);"></a>
										<a href="http://s.yhd.com/list/13225" target="_blank" class="logo_link" data-ref="14582_12949074_1">
											<img src="brandphoto/php/files/{{$brands->image}}">
										</a>
										<a href="http://s.yhd.com/list/13225?tp=1.0.27.0.22.VoYS0U" target="_blank" class="discount" data-ref="14582_12949074_1" style="display: none;">
										<i>2.9</i>
										<u>折起</u>
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
											<a href="#" class="brand_bg_gray" style="-webkit-transform: rotate(45deg);"></a>
											<a href="http://s.yhd.com/list/12900" target="_blank" class="logo_link" data-ref="14583_13142246_1"><img src="brandphoto/php/files/{{$brands->image}}"></a>
											<a href="http://s.yhd.com/list/12900" target="_blank" class="discount" data-ref="14583_13142246_1" style="display: none;">
											<i>4.5</i>
											<u>折封顶</u>
											</a>
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
<br>
<div>&nbsp;</div>

<!-- Sport accessories -->
<div class="row" >
	<p class="magellan"><a name="sport"></a></p>
	<h3 data-magellan-destination="sport" class="magellan">&nbsp;</h3>
	<div class="large-2 medium-12 columns nopadding">
		<div class="fashion_title navy-blue">
			အားကစား ပစၥည္းမ်ား
		</div>
		@if($responses['sportcategory'])
				<div class="row nopadding">
			@foreach($responses['sportcategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<img class="center_photo" src="categoryphoto/php/files/{{$row->image}}"></li> 
					</div>
			@endforeach
				</div>
		@endif
		<div class="clear"></div>
		<div class="row">
			<div class="large-12 columns feature_item_photo show-for-large nopadding">
				<!-- <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
					<span class="btn_edit"></span>
                </a> -->
				<img src="img/electronic3.jpg">
			</div>
		</div>

		<!-- </div> -->
	</div>
	@if(count($responses['sportaccessories'])>0)
		<div class="large-8 medium-12 columns nopadding">
			<div class="row">
				<?php $i=1; $sportaccessoriescounts=count($responses['sportaccessories'])?>
				@foreach($responses['sportaccessories'] as $row)
					@if($i==1)
						<div class="large-6 medium-6 small-12 column nopadding">
							<div class="grid-2-row grid">
				                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
									<span class="btn_edit"></span>
				                </a>
				                <a href="/itemdetail/{{$row->id}}">
				                    <img src="itemphoto/php/files/{{$row->image}}">
				                    <div class="grid_title_bg">
				                      	<div class="grid_title">{{$row->name_mm}}</div>
										<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
										<div class="second_row_grid_2_love">
											<div class="love_icon"></div>
											<div class="love_text">
												၅၂၈
											</div>
										</div>
									</div>
								</a>
							</div>
						</div>
						<div class="clear show-for-small"></div>
						@if($i==1 && $i==$sportaccessoriescounts)
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
				                        <div class="grid-1h-row light-green grid list">
				                            <figure>
				                               <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
				                				<a href="/itemdetail/{{$row->id}}">
 				                                	<img src="itemphoto/php/files/{{$row->image}}">
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
															<div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div>
														</div>
					                               </figcaption>
					                            </a>
				                            </figure>
				                       </div>
				                    </div>
				        @endif

				        @if($i==2 && $i==$sportaccessoriescounts)
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
				                        <div class="grid-1h-row grid light-green list">
				                            <figure>
				                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
					                                <img src="itemphoto/php/files/{{$row->image}}">
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
															<div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div>
														</div>
					                               	</figcaption>
					                            </a>
				                            </figure>
				                       </div>
				                    </div>
								</div>
								<div class="clear show-for-small"></div>
						@endif
						@if($i==3 && $i==$sportaccessoriescounts)
							<div class="row">
								<div class="large-12 medium-12 small-12 grid columns nopadding">
									<div class="grid-2ndsm light-green-bl">
									</div>
								</div>
							</div>
						</div>
				        @endif
				        @if($i==4 && $i==$sportaccessoriescounts)
			        			<div class="row">
									<div class="large-12 medium-12 small-12 grid columns nopadding">
										<div class="grid-2ndsm light-green-bl">
											<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
												<span class="btn_edit"></span>
							                </a>
								            <a href="/itemdetail/{{$row->id}}">
												<img src="itemphoto/php/files/{{$row->image}}">
											</a>
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
	<div class="large-2 columns show-for-large nopadding">
		<div class="second_brand_bg">
			@if($responses['sportbrand'])
				@foreach($responses['sportbrand'] as $brands)
					<div class="row">
						<div class="large-5 small-5 columns brand_image_frame">
							<img src="brandphoto/php/files/{{$brands->image}}">
						</div>
						<div class="large-7 small-7 columns">
							<div class="brand_title">{{$brands->name}}</div>
						</div>
					</div>
				@endforeach
			@endif
		</div>
	</div>
</div>
<div>&nbsp;</div>

<!-- School and office accessories -->
<div class="row" >
	<p class="magellan"><a name="stationery"></a></p>
	<h3 data-magellan-destination="stationery" class="magellan">&nbsp;</h3>

	<div class="large-2 medium columns  nopadding">
		<div class="fashion_title pink">
			ေက်ာင္းသုံးႏွင့္ရုံးသုံးစာေရးကိရိယာပစၥည္းမ်ား
		</div>
		@if($responses['stationerycategory'])
				<div class="row nopadding">
			@foreach($responses['stationerycategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<img class="center_photo" src="categoryphoto/php/files/{{$row->image}}">
					</div>
			@endforeach
				</div>
		@endif
		<div class="clear"></div>
		<div class="row">
			<div class="large-12 columns feature_item_photo show-for-large grid nopadding">
				<!-- <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
					<span class="btn_edit"></span>
                </a> -->
	            <img src="img/cosmetic1.jpg">
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
							<div class="grid-2-row grid">
								<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
									<span class="btn_edit"></span>
				                </a>
				                <a href="/itemdetail/{{$row->id}}">
				                    <img src="../itemphoto/php/files/{{$row->image}}">
				                    <div class="grid_title_bg">
				                      	<div class="grid_title">{{$row->name}}</div>
										<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k </div>
										<div class="second_row_grid_2_love">
											<div class="love_icon"></div>
											<div class="love_text">
												၅၂၈
											</div>
										</div>
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
				                        <div class="grid-1h-row light-green grid list">
				                            <figure>
												<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
				                               		<img src="itemphoto/php/files/{{$row->image}}">
					                               	<figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k </div>
															<div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div>
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
				                        <div class="grid-1h-row grid light-green list">
				                            <figure>
				                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
			                					</a>
								                <a href="/itemdetail/{{$row->id}}">
		  			                                <img src="itemphoto/php/files/{{$row->image}}">
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
															<div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div>
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
										<div class="grid-2ndsm pink grid list">
											<figure>
				                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
													<span class="btn_edit"></span>
								                </a>
								                <a href="/itemdetail/{{$row->id}}">
					                                <img src="itemphoto/php/files/{{$row->image}}">
					                                <figcaption><br>
						                                <div class="grid_title_bg">
						                                  <div class="grid_title">{{$row->name_mm}}</div>
															<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
															<div class="second_row_grid_2_love">
																<div class="love_icon"></div>
																<div class="love_text">
																	၅၂၈
																</div>
															</div>
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
									<div class="grid-2ndsm navy-blue grid list">
										<figure>
			                                <a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
												<span class="btn_edit"></span>
			                				</a>
							                <a href="/itemdetail/{{$row->id}}">
				                                <img src="itemphoto/php/files/{{$row->image}}">
				                                <figcaption><br>
					                                <div class="grid_title_bg">
					                                  <div class="grid_title">{{$row->name_mm}}</div>
														<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
														<div class="second_row_grid_2_love">
															<div class="love_icon"></div>
															<div class="love_text">
																၅၂၈
															</div>
														</div>
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
	<div class="large-2 medium-2 small-6 columns show-for-large nopadding">
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
										<a href="#" class="brand_bg_white" style="-webkit-transform: rotate(45deg);"></a>
										<a href="http://s.yhd.com/list/13225" target="_blank" class="logo_link" data-ref="14582_12949074_1">
											<img src="brandphoto/php/files/{{$brands->image}}">
										</a>
										<a href="http://s.yhd.com/list/13225?tp=1.0.27.0.22.VoYS0U" target="_blank" class="discount" data-ref="14582_12949074_1" style="display: none;">
										<i>2.9</i>
										<u>折起</u>
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
											<a href="#" class="brand_bg_gray" style="-webkit-transform: rotate(45deg);"></a>
											<a href="http://s.yhd.com/list/12900" target="_blank" class="logo_link" data-ref="14583_13142246_1"><img src="brandphoto/php/files/{{$brands->image}}"></a>
											<a href="http://s.yhd.com/list/12900" target="_blank" class="discount" data-ref="14583_13142246_1" style="display: none;">
											<i>4.5</i>
											<u>折封顶</u>
											</a>
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
<br>
<div>&nbsp;</div>

<!-- Car Accessories -->
<div class="row">
	<p class="magellan"><a name="caraccessories"></a></p>
	<h3 data-magellan-destination="caraccessories" class="magellan">&nbsp;</h3>

	<div class="large-2 medium-12 columns nopadding">
		<div class="fashion_title orange">
			ကားႏွင့္ဆယ္စပ္ပစၥည္းမ်ား
		</div>
		@if($responses['carcategory'])
				<div class="row nopadding">
			@foreach($responses['carcategory'] as $row)
					<div class="large-4 medium-4 small-4 columns feature_sub_category left">
						<img class="center_photo" src="categoryphoto/php/files/{{$row->image}}"></li> 
					</div>
			@endforeach
				</div>
		@endif
		<div class="clear"></div>
		<div class="row car">
			<div class="large-12 columns feature_item_photo show-for-large grid nopadding">
			<!-- <div class="large-12 columns feature_item_photo show-for-large"> -->
				<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
								<span class="btn_edit"></span>
			                </a>
				<img src="../img/grid6.jpg">
			</div>
		</div>

		<!-- </div> -->
	</div>
	@if(count($responses['objobjcar'])>0)
		<div class="large-8 medium-12 columns nopadding">
				<?php $i=1; $carcounts=count($responses['objobjcar'])?>
				
				@foreach($responses['objobjcar'] as $row)
					
					@if($i==1)
						<div class="row">
							<div class="large-6 medium-6 small-6 grid columns nopadding">
								<div class="grid_two_frame light-green-bl">
									<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
										<span class="btn_edit"></span>
					                </a>
								    <a href="/itemdetail/{{$row->id}}">
										<img src="itemphoto/php/files/{{$row->image}}">
									</a>
								</div>
							</div>
						@if($i==1 && $i==$carcounts)
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
							<div class="large-6 medium-6 small-6 grid columns nopadding">
									<div class="grid_two_frame pink">
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
									    <a href="/itemdetail/{{$row->id}}">
											<img src="itemphoto/php/files/{{$row->image}}">
										</a>
									</div>
								</div>
							</div>
							<div class="clear show-for-small"></div>
						@endif
						@if($i==2 && $i==$carcounts)
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
									<div class="grid_four_frame light-green">
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
									    <a href="/itemdetail/{{$row->id}}">
											<img src="itemphoto/php/files/{{$row->image}}">
											<span class="grid_title_bg">
												<div class="grid_title">{{$row->name_mm}}</div>
												<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
												<div class="second_row_grid_2_love">
													<div class="love_icon"></div>
													<div class="love_text">
														၅၂၈
													</div>
												</div>
											</span>
										</a>
									</div>
								</div>
						@endif
						@if($i==3 && $i==$carcounts)
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
								<div class="grid_four_frame pink">
									<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
										<span class="btn_edit"></span>
					                </a>
								    <a href="/itemdetail/{{$row->id}}">
										<img src="itemphoto/php/files/{{$row->image}}">
										<span class="grid_title_bg">
											<div class="grid_title">{{$row->name_mm}}</div>
											<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
											<div class="second_row_grid_2_love">
												<div class="love_icon"></div>
												<div class="love_text">
													၅၂၈
												</div>
											</div>
										</span>
									</a>
								</div>
							</div>
							<div class="clear show-for-small"></div>
						@endif
						@if($i==4 && $i==$carcounts)
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
								<div class="grid_four_frame light-pink">
									<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
										<span class="btn_edit"></span>
					                </a>
								    <a href="/itemdetail/{{$row->id}}">
										<img src="itemphoto/php/files/{{$row->image}}">
										<span class="grid_title_bg">
											<div class="grid_title">{{$row->name_mm}}</div>
											<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
											<div class="second_row_grid_2_love">
												<div class="love_icon"></div>
												<div class="love_text">
													၅၂၈
												</div>
											</div>
										</span>
									</a>
								</div>
							</div>
						@endif
						@if($i==5 && $i==$carcounts)
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange">
									</div>
								</div>
							</div>
						@endif

						@if($i==6 && $i==$carcounts)
								<div class="large-3 medium-3 small-6 grid columns nopadding">
									<div class="grid_four_frame orange">
										<a href="" style="background:transparent;" data-reveal-id="update_form" id="{{$row->id}}" class="updateitem AyarWagaung" rel="2211" id="222">
											<span class="btn_edit"></span>
						                </a>
									    <a href="/itemdetail/{{$row->id}}">
											<img src="itemphoto/php/files/{{$row->image}}">
											<span class="grid_title_bg">
												<div class="grid_title">{{$row->name_mm}}</div>
												<div class="grid_price">ေစ်းႏႈန္း= {{$row->price}} k</div>
												<div class="second_row_grid_2_love">
													<div class="love_icon"></div>
													<div class="love_text">
														၅၂၈
													</div>
												</div>
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
	<div class="large-2 columns show-for-large nopadding">
		<div class="second_brand_bg">
			@if($responses['carbrands'])
				@foreach($responses['carbrands'] as $brands)
					<div class="row">
						<div class="large-5 small-5 columns brand_image_frame">
							<img src="brandphoto/php/files/{{$brands->image}}">
						</div>
						<div class="large-7 small-7 columns">
							<div class="brand_title">{{$brands->name}}</div>
						</div>
					</div>
				@endforeach
			@endif
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


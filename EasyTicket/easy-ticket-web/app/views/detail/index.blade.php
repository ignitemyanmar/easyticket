@extends('../master')
@section('content')

{{HTML::style('../../css/hover/component.css')}}
{{HTML::style('../../css/smkstyle.css')}}
{{HTML::style('../../rateit/rateit.css')}}

<br>
{{HTML::script('../../js/hover/modernizr.custom.js')}}
{{HTML::script('../../js/hover/toucheffects.js')}}
{{HTML::script('../../js/elevatezoom.min.js')}}
{{HTML::script('../../js/zoom_ch_img_source.js')}}
{{HTML::script('../../rateit/jquery.rateit.min.js')}}
{{HTML::script('../../js/jquery.bootstrap-growl.min.js')}}

<style type="text/css">
	.share{
		width: 36px;
		height: auto;
		margin: 0px 13px;
	}
	.item_desc_tabs table,.item_desc_tabs table tr td{
		border:1px solid lightblue;
		padding: 0px !important;
	}
	.item_desc_tabs table tr td p{
		line-height: 0 !important;
		margin: 16px !important;
	}
	.item_desc_tabs td{
		padding-top: 10px !important;
	}
	.addtocard .button{
		height: 52px;
		padding: 13px 24px !important;
		min-width: 155px !important;
	}
</style>
<div id="rating_review" class="reveal-modal small" data-reveal>
	<form action="../ratereview/{{$item->id}}" method="POST">
	<h2>Rating & Review ေရးရန္</h2> 
	<p class="lead">Rating & Review မ်ားကို ေအာက္တြင္ ျဖည့္စြပ္ေပးပါ။</p> 
	<div class="row">
	    <div class="large-12 columns">
		<p>ပစၥည္း၏ အေရအေသြး</p> 
		<div class="rateit_item"></div><br><br>
		<input type="hidden" name="rateit_item" id="rateit_item" value="0">
		</div>
	</div>

	<div class="row">
	    <div class="large-12 columns">
		<p>ပို႕ေဆာင္မႈ ျမန္/မျမန္</p> 
		<div class="rateit_delivery"></div><br><br>
		<input type="hidden" name="rateit_delivery" id="rateit_delivery" value="0">
		</div>
	</div>

	<div class="row">
	    <div class="large-12 columns">
		<p>ဤဆိုင္ ေကာင္း/မေကာင္း</p> 
		<div class="rateit_shop"></div><br><br>
		<input type="hidden" name="rateit_shop" id="rateit_shop" value="0">
		</div>
	</div>
	<div class="row">
	    <div class="large-12 columns">
	      <div>သင့္၏ သံုးသပ္ခ်က္</div><br>
	      <textarea placeholder="Enter Review" name="review" required></textarea>
	    </div>
  	</div>
  	<div class="row">
  		<div class="large-12 columns">
	     	<input type="submit" name="btn_rating_reivew" value="Submit" class="button small right">
	    </div>
  	</div>
	</form>
<a class="close-reveal-modal">&#215;</a> 
</div>
@if($item->freeget == 0 && $item->timesale == 0)
<div @if(Session::has('delivery_to_city') && !isset($item->group_item)) id="group_sale" @endif class="reveal-modal small" data-reveal> 
	<h2 style="text-align:center;">ပူးေပါင္း ေစ်းဝယ္ရန္</h2> 
	<div class="row">
		<div class="large-12 columns"><h4>- စည္းမ်ဥ္း စည္းကမ္းမ်ား</h4></div>
		<div class="large-12 columns"><p>ပူးေပါင္း ေစ်းဝယ္အတြက္ လူဦးေရ အနည္းဆံုး ၁၀ ေယာက္အထက္ ျဖစ္ရပါမည္။ သက္သာေသာ ေစ်းႏူန္းမ်ားကို လူဦးေရ အလိုက္ ခ်ေပးသြားမည္ ျဖစ္ပါသည္။  ေနာက္ဆုံးရက္အတြင္း လူဦးေရ မျပည့္ပါက ယခုပစၥည္း၏ ပူးေပါင္းေစ်းဝယ္ျခင္းကို ပယ္ဖ်က္မည္ ျဖစ္ပါသည္။</p></div>
		@if(!Auth::check()) 
		<div class="large-12 columns alert_error"><p>ပူေပါင္း ေစ်းဝယ္ရန္ Member အေနျဖင့္ ပထမဦးဆံုး <a href="" data-reveal-id="login_register">Login</a> ဝင္ရန္ လိုအပ္ပါသည္။</p></div>
		@endif
		<div class="large-12 columns">
			<form action="/set_group_item/{{$item->id}}" method="POST"> 
				<input type="hidden" id="hid_access_token" name="access_token" value="@if(Auth::check()){{Auth::user()->access_token}} @endif">
				<div class="row"> 
					<div class="small-12"> 
						<div class="row"> 
							<div class="small-5 columns text-right inline"> 
								လူဦးေရ အေရအတြက္
							</div> 
							<div class="small-7 columns right"> 
								<select class="number_person" name="number_person">
								@if(count($group_item_price) > 0)
									@foreach($group_item_price as $rows)
									<option value="{{$rows->percentage}}">{{$rows->number_person}}</option>
									@endforeach
								@else
									<option value="3">10</option>
									<option value="7">30</option>
								@endif
								</select>
							</div> 
						</div> 
					</div> 
					
					<div class="small-12"> 
						<div class="row"> 
							<div class="small-5 columns text-right inline"> 
								မူရင္းေစ်းႏူန္း = 
							</div> 
							<div class="small-7 columns right"> 
								<span class="primary_price">{{$item->itemdetail[0]->price}}</span> Ks
							</div> 
						</div> 
					</div> 
					<br>
					<div class="small-12"> 
						<div class="row"> 
							<div class="small-5 columns text-right inline"> 
								သက္သာေသာ ေစ်းႏႈန္း = 
							</div> 
							@if(count($group_item_price) > 0)
							<div class="small-7 columns right noti"> 
								<span class="discounted_price" style="font-size: 27px;color: #FF823D;}">{{$item->itemdetail[0]->price - ($item->itemdetail[0]->price * ($group_item_price[0]->percentage / 100))}}</span> Ks
								<span class="discount"><em>&nbsp;</em><b style="color:white;">{{$group_item_price[0]->percentage}}% OFF</b></span>
							</div> 
							@else
							<div class="small-7 columns right noti"> 
								<span class="discounted_price" style="font-size: 27px;color: #FF823D;}">{{$item->itemdetail[0]->price - ($item->itemdetail[0]->price * (3 / 100))}}</span> Ks
								<span class="discount"><em>&nbsp;</em><b style="color:white;">3% OFF</b></span>
							</div> 
							@endif 
						</div> 
					</div> 
					<br>
					<div class="small-12"> 
						<div class="row"> 
							<div class="small-5 columns text-right inline"> 
								&nbsp;
							</div> 
							<div class="small-7 columns right"> 
								<input id="checkbox1" type="checkbox"required ><span for="checkbox1"> စည္းမ်ဥ္းစည္းကမ္း အားလံုးကို လုိက္နာပါမည္။</span>
							</div> 
						</div> 
					</div> 
					<div class="small-12"> 
						<div class="row"> 
							<div>&nbsp;</div>
							<div class="small-12 columns text-right"> 
								<input type="submit" class="button" @if(!Auth::check()) disabled @endif value="သိမ္းမည္" name="submit" style="padding: 9px 40px;">
							</div> 
						</div> 
					</div> 
				</div> 
			</form>
		</div>


	</div>
	<a class="close-reveal-modal">&#215;</a> 
</div>
@endif
<div class="row nopadding">
	<div class="large-12 columns nopadding link_menu">
		<ul>
			@if(count($link_menu) > 0)
				@if(isset($link_menu[0]))
					<li><a href="../../list/{{$link_menu[0]->id}}">{{$link_menu[0]->name_mm}}</a><img src="../../../../images/arrow.png"></li>
					@if(isset($link_menu[1]))
						<li><a href="../../list/{{$link_menu[0]->id}}/{{$link_menu[1]->id}}">{{$link_menu[1]->name_mm}}</a><img src="../../../../images/arrow.png"></li>
						@if(isset($link_menu[2]))
							<li><a href="../../list/{{$link_menu[0]->id}}/{{$link_menu[1]->id}}/{{$link_menu[2]->id}}">{{$link_menu[2]->name_mm}}</a><img src="../../../../images/arrow.png"></li>
						@endif
					@endif
				@endif
			@endif
		</ul>
	</div>
</div>

<div class="row">
	<div class="large-10 columns nopadding">
		<div class="large-6 columns nopadding">
			<div class="row wrapper">
				<div class="main-photo">
		            <img id="mainphoto" src="../../itemphoto/php/files/{{$item->image}}">
		        </div>
		        <div class="main-slider">
		            <div class="window">
		                <ul class="slider-large-image" id="slider-large-image" style="overflow:hidden;">
		                	<li><img src="../../itemphoto/php/files/medium/{{$item->image}}"></li>
		                    @foreach($item->itemthumbimages as $imgthumb)
								<li><img src="../../itemphoto/php/files/medium/{{$imgthumb->image}}"></li>
							@endforeach	
		                </ul>
		            </div>
		            <div class="slider-pager">
			            <a href="#" id="b" class="left">&lsaquo;</a>
			            <a href="#" id="f" class="right">&rsaquo;</a>
		            </div>
		        </div>
			</div>

			<div class="row" style="margin-top: 50px;"><p>&nbsp;</p></div>
			<div class="row" style="top:10px;">
				<div class="large-7 columns nopadding" style="top:-17px; margin-left: 0px !important;">
				<form> 
	                <fieldset style="padding: 8px 8px;"> 
	                	<legend>Share with</legend>
							<a rel="nofollow"
							    href="https://www.facebook.com/"
							    onclick="popUp=window.open(
							        'https://www.facebook.com/sharer.php?u={{Request::url()}}&title={{$item->name}}',
							        'popupwindow',
							        'scrollbars=yes,width=800,height=400');
							    popUp.focus();
							    return false">
							    <img class="share" src="../../img/facebook.png">
							</a>
							<a rel="nofollow"
							    href="https://www.plus.google.com/"
							    onclick="popUp=window.open(
							        'https://plus.google.com/share?url={{Request::url()}}',
							        'popupwindow',
							        'scrollbars=yes,width=800,height=400');
							    popUp.focus();
							    return false">
							    <img class="share" src="../../img/google.png">
							</a>
							<a rel="nofollow"
							    href="https://twitter.com/"
							    onclick="popUp=window.open(
							        'https://twitter.com/home?status=\'{{$item->name}}\' via @[handle] - {{Request::url()}}',
							        'popupwindow',
							        'scrollbars=yes,width=800,height=400');
							    popUp.focus();
							    return false">
							    <img class="share" src="../../img/twitter.png">
							</a>
							<a rel="nofollow"
							    href="https://www.linkedin.com/"
							    onclick="popUp=window.open(
							        'https://www.linkedin.com/shareArticle?url={{Request::url()}}',
							        'popupwindow',
							        'scrollbars=yes,width=800,height=400');
							    popUp.focus();
							    return false">
							    <img class="share" src="../../img/linkedin.png">
							</a>
	                </fieldset>
                </form>
				</div>
				<div class="large-4 medium-4 columns each nopadding" style="top:10px;"> <img src="../../img/person.png" style="width:24px;"> <a href="" @if(!Auth::check()) data-reveal-id="login_register" @elseif(!isset($item->group_item)) data-reveal-id="group_sale" @endif> ပူးေပါင္း ေစ်းဝယ္ရန္ </a></div>
			</div>
		</div>
		</style>
		<form action="/cart/{{$item->id}}" method="POST" id="frm_item">
			<div class="large-6 columns nopadding item_detail">
				<div class="row">
					<input type="hidden" name="access_token" value="@if(Auth::check()){{Auth::user()->access_token}} @endif">
					<input type="hidden" name="item_id" value="{{$item->id}}">
					<div class="large-12 columns title_label">{{$item->name_mm}}<div class="footer_line"></div></div>
				</div>
				<?php $i = 0; $sizelen = 0;?>
				@foreach($item->itemdetail as $rows)
					<?php $j = 0;?>
					@foreach($rows->color->size as $size)
						<div class="row  item_price_{{$sizelen}}" @if($i == 0 && $j ==0 )  @else style="display:none;" @endif>
							<div class="large-12 columns">
								<div class="row price_bg">
									<div class="large-7 columns nopadding">
									@if($item->freeget == 0 && $item->timesale == 0)
										<div class="new_price noti">{{$size->price}} Kyats @if($item->discount != 0)<span><em>&nbsp;</em>{{$item->discount}}% OFF</span> @endif </div>
										@if($rows->old_price != 0)
										<div class="old_price">{{$rows->old_price}} Kyats</div>
										@endif
									@elseif($item->timesale > 0 && $item->freeget == 0)
										<div class="new_price noti">{{round($size->price - ((10 * $size->price)/100))}} Kyats <span><em>&nbsp;</em>Time Sale</span></div>
										@if($size->price != 0)
										<div class="old_price">{{$size->price}} Kyat</div>
										@endif

									@else
										<div class="new_price noti">0.00 Kyat<span><em>&nbsp;</em>Fee Get</span></div>
									@endif
										
									</div>
									@if(isset($item->group_item))
									<div class="large-5 columns nopadding hole_sale_bg">
										<input type="hidden" id="hid_item_id" name="group_id" value="{{$item->group_item->id}}">
										<div class="hole_label">{{$item->group_item->number_person}} Person Sale!</div>
										<div class="hole_price">{{round($size->price - ($size->price * ($item->group_item->percentage / 100)))}} Ks</div>
										<div class="left_person"><span>{{$item->group_item->number_person - count($group_person)}} left person</span></div>
									</div>
									@endif
								</div>
							</div>
						</div>
					<?php $j++; $sizelen++;?>
					@endforeach
				<?php $i++;?>
				@endforeach
				
				<div class="row"><div class="large-12 column"><div class="footer_line"></div></div></div>
				<div class="row" id="pricebyqty">
					<div class="large-12 columns color_label">အေရအတြက္လိုက္ ေစ်းႏႈန္း</div>
					<div class="large-12 columns item_color">
					@if(count($item->itempricebyqty) > 0 && $item->freeget == 0)
					@foreach($item->itempricebyqty as $pricebyqty)
					<a href="#pricebyqty" class="hole_price label" onclick="setPricebyQty({{$pricebyqty->endqty}})" style="text-decoration: none;"> {{$pricebyqty->startqty}} - {{$pricebyqty->endqty}} Ps = <strong>{{$pricebyqty->price}}</strong> Ks</a>
					@endforeach
					@else
					<div class="hole_price label">မရိွေသးပါ</div>
					@endif
					
					</div>
				</div>
				<div class="row"><div class="large-12 column">&nbsp;</div></div>
				<div class="compare_price left"><a href="" style="color:black;">ေစ်းႏႈန္း ႏိုင္ယွဥ္ရန္</a></div>
				<div class="row">
					<div class="large-12 columns compare_price_table">
						<div class="row">
							<div class="large-6 columns text-left header">ဆိုင္အမည္</div>
							<div class="large-6 columns text-left header">ေစ်းႏႈန္း</div>
						</div>
						@foreach($item->compareprice as $rows)
						<div class="row">
							<div class="large-6 columns list_row" style="padding: 10px 10px;">{{$rows->shop}}</div>
							<div class="large-6 columns list_row" style="padding: 10px 10px;">{{$rows->price}} Ks</div>
						</div>
						@endforeach
					</div>
				</div>
				<div class="row"><div class="large-12 column"><div class="footer_line"></div></div></div>
				<div class="row">
					<div class="large-12 columns color_label">ပစၥည္း၏ အေရာင္</div>
					<div class="large-12 columns item_color">
					<?php $i = 0; ?>
					@foreach($item->itemdetail as $color)
						<div class="each_color" style="@if($color->image != null)background-image: url('../itemdetailphoto/php/files/large/{{$color->image}}');background-repeat: no-repeat; @else background:{{$color->color->color_code}} @endif">
							<div class="each_color_in @if($i == 0)each_color_selected@endif" >
							<input name="color" @if($i == 0) checked @endif onclick="changeSize('{{count($item->itemdetail)}}','{{$i}}');"  value="{{$color->color_id}}" type="radio"/>
							</div>
						</div>
					<?php $i++; ?>
					@endforeach
					</div>
				</div>
				<div class="row"><div class="large-12 column"><div class="footer_line"></div></div></div>

				<div class="row">
					<div class="large-12 columns size_label">ပစၥည္း၏ အရြယ္အစား</div>
					<?php $k = 0; $len = 0;?>
					@foreach($item->itemdetail as $colors)
						<div class="large-12 columns item_size item_size_{{$k}}" @if($k != 0) style="display:none;" @endif>
						<?php $i = 0; ?>
						@if(isset($colors->color->size))
							@foreach($colors->color->size as $size)
								@if(isset($size->id) && isset($size->name))
								<div class="each_size @if($k == 0 && $i == 0) each_size_selected @endif">
									<label>{{$size->name}}</label>
									<input name="size" @if($k == 0 && $i == 0) checked @endif onclick="changePrice({{$sizelen}},{{$len}})" value="{{$size->id}}" type="radio"/>
								</div>
								<?php $i++; $len++;?>
								@endif
							@endforeach
						@endif
						</div>
					<?php $k++; ?>
					@endforeach
				</div>
				<div class="row"><div class="large-12 column"><div class="footer_line"></div></div></div>

				<div class="row" id="item_qty">
					<div class="large-12 columns">
						<div class="row qty_bg">
							<div class="large-5 columns nopadding">
								<div class="large-12 columns qty_label">ပစၥည္း အေရအတြက္</div>
								@if($item->freeget == 0)
								<div class="large-12 columns nopadding qty">
									<label class="mimus" id="de_qty"> - </label>
									<span style="margin-top:0px;">
										<input type="text" name="qty" id="qty" style="margin-top: 0px;width: 45px;height: 28px;text-align: center;border: none;" value="1" required>
									</span>
									<label class="plus" id="inc_qty">+</label>
								</div>
								@else
								<div class="large-12 columns nopadding" style="margin: 16px -26px;font-size: 20px;color: #FF9A3C;">
									1/Person
									<input type="hidden" name="qty" value="1">
								</div>
								@endif
							</div>
							<div class="large-7 columns nopadding remaining_qty_bg">
								<div class="remaining_qty_label">လက္က်န္ ပစၥည္းအေရအတြက္</div>
								<?php $i = 0; ?>
								@foreach($item->itemdetail as $rows)
								<div class="remaining_qty_price item_remaining_qty_{$i}" @if($i != 0) style="display:none;" @endif>
									<?php 
										$percent = $rows->sold_qty > 0 ? ($rows->qty / ($rows->qty + $rows->sold_qty)) * 100 : 100;
										if($percent >= 75 && $percent <=100){
											$color = '#00F400';
										}
										if($percent >= 50 && $percent <=74){
											$color = 'yellow';
										}
										if($percent >= 0 && $percent <=49){
											$color = 'red';
										}
									?>
									@if($rows->qty > 0)
									<span style="font-size: 12px;position: absolute;color: gray;top: 62px;left: {{ $percent}}px;">
									{{ $rows->qty}} ခု
									</span>
									@else
									<span style="font-size: 12px;position: absolute;color: red;top: 64px;left: 67px;">
									Out of Stock!
									</span>
									@endif
									<div class="nice secondary progress" style="background:#FAEBD7; height:16px;">
									    <span class="meter" style="width: {{ $percent}}%; background:{{$color}};"></span>
									</div>
									<?php $i++; ?>
								</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
				<div class="row"><div class="large-12 column"><div class="footer_line"></div></div></div>
				@if(($item->item_qty) > 0)
				<div class="row left addtocard" style="height: 60px; padding-top: 9px;">
					<div class="large-4 columns">
						@if(!Auth::check()) 
							<a href="#" data-reveal-id="login_register" class="button" style="background:#D4BD6E;padding-top: 15px !important;" >Buy Now</a>
						@else
							<input type="submit" name="buy_now" class="button" style="background:#D4BD6E;" value="Buy Now" />
						@endif
					</div>
					<div class="large-4 columns" @if(!isset($item->group_item)) style="margin-left:20px;" @endif>
						<input type="submit" name="add_to_cart" class="button" value="Add to Cart"  />
					</div>
					<div class="large-4 columns">
					@if(isset($item->group_item))
						<a @if(!Auth::check()) data-reveal-id="login_register" class="button" @else href="" class="button add_to_group_sale" @endif style="padding: 16px 20px !important;min-width: 40px !important; @if($item->group_item->is_enter == true) background:#F3F3F3; @else background:#01A6EA; @endif">@if($item->group_item->is_enter == true) - Group Sale @else + Group Sale @endif</a>
					@endif
					</div>
				</div>
				@endif
				<div class="row"><div class="large-12 column"><div class="footer_line"></div></div></div>

				<div class="row">
					<div class="large-12 columns">
						<div class="row additional_info">
							<div class="large-6 columns split_bar">
								<div class="row monthly_sold_in_shop">
									<div class="large-12 columns text_title">လစဥ္ ေရာင္းရ အေရအတြက္</div>
									<div class="large-12 columns sold_qty_monthly">@if($item->sold_qty != 0) {{$item->sold_qty}} ခု @else မရိွေသးပါ @endif</div>
									<div class="large-12 columns shop"><img src="../../skins/shop.png"/><a href="../../shop/{{$item->shop->id}}">{{$item->shop->name_mm}}</a></div>
								</div>
							</div>
							<div class="large-6 columns ">
								<div class="row comment_rating">
									<div class="large-12 columns text_title"  style="margin-top: 10px;">ပစၥည္း၏ သံုးသပ္မႈႏႈန္း</div>
									@if(count($review) != 0)
										<?php
											$total_shop_rating = 0;
											$total_item_rating = 0;
											$total_delivery_rating =  0;
											$total_user = count($review);
											foreach ($review as $rows) {
												$total_shop_rating += $rows->shop_rating;
												$total_item_rating += $rows->shop_rating;
												$total_delivery_rating += $rows->shop_rating;
											}
										?>

										<div class="large-12 columns total_comments"><img src="../../skins/comment_icon.png"/><a href="">{{count($review)}} ခု</a></div>
										@if(round((($total_item_rating / $total_user) + ($total_shop_rating / $total_user) + ($total_delivery_rating / $total_user)) / 3) == 0)
										<div class="large-12 columns rating"><span class="rating-a a"></span>{{round((($total_item_rating / $total_user) + ($total_shop_rating / $total_user) + ($total_delivery_rating / $total_user)) / 3)}}%</div>
										@endif
										@if(round((($total_item_rating / $total_user) + ($total_shop_rating / $total_user) + ($total_delivery_rating / $total_user)) / 3) == 1)
										<div class="large-12 columns rating"><span class="rating-a b"></span>{{round((($total_item_rating / $total_user) + ($total_shop_rating / $total_user) + ($total_delivery_rating / $total_user)) / 3)}}%</div>
										@endif
										@if(round((($total_item_rating / $total_user) + ($total_shop_rating / $total_user) + ($total_delivery_rating / $total_user)) / 3) == 2)
										<div class="large-12 columns rating"><span class="rating-a c"></span>{{round((($total_item_rating / $total_user) + ($total_shop_rating / $total_user) + ($total_delivery_rating / $total_user)) / 3)}}%</div>
										@endif
										@if(round((($total_item_rating / $total_user) + ($total_shop_rating / $total_user) + ($total_delivery_rating / $total_user)) / 3) == 3)
										<div class="large-12 columns rating"><span class="rating-a d"></span>{{round((($total_item_rating / $total_user) + ($total_shop_rating / $total_user) + ($total_delivery_rating / $total_user)) / 3)}}%</div>
										@endif
										@if(round((($total_item_rating / $total_user) + ($total_shop_rating / $total_user) + ($total_delivery_rating / $total_user)) / 3) == 4)
										<div class="large-12 columns rating"><span class="rating-a e"></span>{{round((($total_item_rating / $total_user) + ($total_shop_rating / $total_user) + ($total_delivery_rating / $total_user)) / 3)}}%</div>
										@endif
										@if(round((($total_item_rating / $total_user) + ($total_shop_rating / $total_user) + ($total_delivery_rating / $total_user)) / 3) == 5)
										<div class="large-12 columns rating"><span class="rating-a f"></span>{{round((($total_item_rating / $total_user) + ($total_shop_rating / $total_user) + ($total_delivery_rating / $total_user)) / 3)}}%</div>
										@endif

									@else
										<div class="large-12 columns total_comments">မရိွေသးပါ</div>									
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</form>
			
	</div>
	<div class="large-2 columns nopadding right_title">
		<div class="row nopadding f_title">
			<div class="large-12 columns nopadding">ဆင္တူ ပစၥည္းမ်ား</div>
		</div>
		<div class="row nopadding right_border">

		@foreach($similar_items as $rows)
			<div class="large-12 medium-3 column left nopadding hot_item">
				<div class="hot_item_price">{{$rows->itemdetail[0]->price}} Ks</div>
					<div class="preview_photo">
						<a href="../../itemdetail/{{$rows->id}}"><img class="center_photo" src="../../itemphoto/php/files/medium/{{$rows->image}}" /></a>
					</div>
				<div class="row nopadding">
					<div class="large-5 column nopadding">
						<div class="list_item_rating">၄.၅</div>
					</div>
					<div class="large-7 column nopadding list_item_sold">ေရာင္းၿပီး (၃၆၇)</div>
				</div>
				<div class="row">
					<div class="column nopadding list_item_title"><a href="../../itemdetail/{{$rows->id}}">{{$rows->name}}</a></div>
				</div>
			</div>
		@endforeach

		</div>
	</div>
</div>

<div class="row" >&nbsp;</div>

<div class="row">
	<div class="large-2 columns nopadding left_title">
		<div class="row nopadding f_title">
			<div class="large-12 columns nopadding">ဆိုင္တြင္ရိွ ပစၥည္းမ်ား</div>
		</div>
		<div class="row nopadding left_border">
		@foreach($shop_items as $rows)
			<div class="large-12 medium-3 column left nopadding hot_item">
				<div class="hot_item_price">{{$rows->itemdetail[0]->price}} Ks</div>
				<div class="preview_photo">
					<a href="../../itemdetail/{{$rows->id}}"><img class="center_photo" src="../../itemphoto/php/files/medium/{{$rows->image}}" /></a>
				</div>
				<div class="row nopadding">
					<div class="large-5 column nopadding">
						<div class="list_item_rating">၄.၅</div>
					</div>
					<div class="large-7 column nopadding list_item_sold">ေရာင္းၿပီး (၃၆၇)</div>
				</div>
				<div class="row">
					<div class="column nopadding list_item_title"><a href="../../itemdetail/{{$rows->id}}">{{$rows->name}}</a></div>
				</div>
			</div>
		@endforeach
		</div>
	</div>
	<div class="large-10 columns nopadding">
			<div class="row">
				<div class="tabs-a item_desc_tabs">
					<ul>
						<li>ပစၥည္း၏ အေသးစိတ္</li>
						<li>ဝန္ေဆာင္မႈမ်ား</li>
						<li>ပစၥည္း၏ သံုးသပ္ခ်က္မ်ား</li>
					</ul>
					<div>
						<div>
							<p>{{$item->description}}</p>
						</div>
						<div>
							@if($item->service)
							{{$item->service}}
							@else
							<div class="row">
								<div class="large-12 columns">
									<h4>အျမန္ေခ်ာပို႔ (Express) ျဖင့္ေပးပို႔ျခင္း</h4>
									<p>528 GO Website တြင္ လူႀကီးမင္းမ်ားမွာယူထားေသာပစၥည္းမ်ားကိုမွန္ကန္တိက်မႈရွိေအာင္ အမွာစာတြင္ပါေသာ ဖုန္းနံပါတ္အားဆက္သြယ္ၿပီး ၄၈နာရီအတြင္းအိမ္တိုင္လာေရာက္ေပးပို႔ပါမည္။</p>
									<p>
										528 GO တြင္ပစၥည္းမ်ား မွာၾကားဝယ္ယူလုိေသာလူႀကီးမင္းမ်ားအေနျဖင့္ ကၽြႏု္ပ္တို႔ Website တြင္ပထမဦးစြာ Register စတင္လုပ္ေဆာင္ရပါမည္။
										<br><br><strong>(မွတ္ခ်က္။	။ ရန္ကုန္ၿမိဳ႕နယ္အတြင္းျဖစ္ပါသည္။) </strong><br><br>
										ထုိေၾကာင့္လူႀကီးမင္းတို႔၏ ေနရပ္လိပ္စာ ႏွင့္ ဖုန္းနံပါတ္မ်ားအား တိက်မွန္ကန္စြာျဖည့္သြင္းေပးရန္လို အပ္ပါသည္။
										<br><br>ဝန္ေဆာင္မႈရရွိႏိုင္ေသာၿမိဳ႕နယ္မ်ားမွာ-<br><br>
										အလံုၿမိဳ႕နယ္၊ ဗဟန္းၿမိဳ႕နယ္၊ ဗိုလ္တေထာင္ၿမိဳ႕နယ္၊ ဒဂံုၿမိဳ႕နယ္၊ ဒဂံုၿမိဳ႕သစ္(ေတာင္ /ေၿမာက္)၊ ေဒါပံုၿမိဳ႕နယ္၊ လႈိင္ၿမိဳ႕နယ္၊ လိႈင္သာယာၿမိဳ႕နယ္၊ အင္းစိန္ၿမိဳ႕နယ္၊ ကမာ႐ြတ္ၿမိဳ႕နယ္၊ ေက်ာက္တံတား ၿမိဳ႕နယ္၊ ႀကည့္ၿမင္တိုင္ၿမိဳ႕နယ္၊ လမ္းမေတာ္ၿမိဳ႕နယ္၊ လသာၿမိဳ႕နယ္၊ မရမ္းကုန္းၿမိဳ႕နယ္၊ မဂၤလာဒံုၿမိဳ႕နယ္၊ မဂၤလာေတာင္ညြန္႕ၿမိဳ႕နယ္၊ ဥကၠလာၿမိဳ႕နယ္ (ေတာင္/ေၿမာက္)၊ ပန္းဘဲတန္းၿမိဳ႕နယ္၊ စမ္းေခ်ာင္းၿမိဳ႕နယ္၊ ေ႐ႊၿပည္သာၿမိဳ႕နယ္၊ တာေမြၿမိဳ႕နယ္၊ သာေကတၿမိဳ႕နယ္ ၊ သဃၤန္းကၽြန္းၿမိဳ႕နယ္၊ ရန္ကင္းၿမိဳ႕နယ္၊ လွိဳင္ၿမိဳ႕နယ္ ၊ ပုဇြန္ေတာင္ၿမိဳ႕နယ္၊ ေက်ာက္ေျမာင္းၿမိဳ႕နယ္၊ မဂၤလာဒုံၿမိဳ႕နယ္။
									</p>
									<h4>ပစၥည္းမ်ားေရာက္ရွိမႈအေျခအေန</h4>
									<p>လူႀကီးမင္းမ်ား မွာယူထားေသာပစၥည္းမ်ား ပို႔ေဆာင္ရာလမ္းခရီးတြင္ အဆင္ေျပေခ်ာေမြ႔မႈရွိမရွိကို လူႀကီးမင္း ထံသို႔ ဖုန္း(သို႔)ေမးလ္ျဖင့္ ဆက္သြယ္အေၾကာင္းၾကားေပးပါမည္။</p>
									<h4>ေငြေပးေခ်စနစ္</h4>
									<ul>
										<li>ပစၥည္းေရာက္ေငြေပးေခ်စနစ္</li>
									</ul>
									<p>လူႀကီးမင္းမွာယူထားေသာ ပစၥည္းကို လက္ခံရရွိမွသာ ပစၥည္းတန္ဖိုးက်သင့္ေငြကိုေပးေခ်ႏိုင္ပါသည္။ လူႀကီးမင္းမွာယူထားေသာပစၥည္းမ်ားမိမိထံသို႔ေရာက္ရွိပါက 528 Go မွတာဝန္ရွိသူေရွ႕တြင္ အပ်က္အစီးမ်ား၊ အစြန္းထင္းမ်ား ရွိမရွိကို လက္ခံမယူမွီအခ်ိန္ေသခ်ာစြာစစ္ေဆးေပးပါရန္ ေလးစားစြာ မွာၾကားအပ္ပါသည္။</p>
									<ul>
										<li>Bank Mobile Banking စနစ္</li>
									</ul>
								</div>
							</div>
							@endif
						</div>
						<div class="double-a">
							<div class="row">
								<div class="total_rating">
									<h4>ပစၥည္း၏ သံုးသပ္မႈႏႈန္းမ်ား</h4>
									@if(count($review) != 0)
										
									<ul class="rating-list-a">
										<!-- Item Rating -->
										@if(round($total_item_rating/$total_user) == 1 || round($total_item_rating/$total_user) == 0)
										<li class="a">ပစၥည္း၏ အေရအေသြး<span>{{round($total_item_rating/$total_user)}}/5</span></li>
										@endif
										@if(round($total_item_rating/$total_user) == 2)
										<li class="b">ပစၥည္း၏ အေရအေသြး<span>{{round($total_item_rating/$total_user)}}/5</span></li>
										@endif
										@if(round($total_item_rating/$total_user) == 3)
										<li class="c">ပစၥည္း၏ အေရအေသြး<span>{{round($total_item_rating/$total_user)}}/5</span></li>
										@endif
										@if(round($total_item_rating/$total_user) == 4)
										<li class="d">ပစၥည္း၏ အေရအေသြး<span>{{round($total_item_rating/$total_user)}}/5</span></li>
										@endif
										@if(round($total_item_rating/$total_user) == 5)
										<li class="e">ပစၥည္း၏ အေရအေသြး<span>{{round($total_item_rating/$total_user)}}/5</span></li>
										@endif
										<!-- Delivery Rating -->
										@if(round($total_delivery_rating/$total_user) == 1 || round($total_delivery_rating/$total_user) == 0)
										<li class="a">ပို႕ေဆာင္မႈ ျမန္/မျမန္<span>{{round($total_delivery_rating/$total_user)}}/5</span></li>
										@endif
										@if(round($total_delivery_rating/$total_user) == 2 )
										<li class="b">ပို႕ေဆာင္မႈ ျမန္/မျမန္<span>{{round($total_delivery_rating/$total_user)}}/5</span></li>
										@endif
										@if(round($total_delivery_rating/$total_user) == 3 )
										<li class="c">ပို႕ေဆာင္မႈ ျမန္/မျမန္<span>{{round($total_delivery_rating/$total_user)}}/5</span></li>
										@endif
										@if(round($total_delivery_rating/$total_user) == 4 )
										<li class="d">ပို႕ေဆာင္မႈ ျမန္/မျမန္<span>{{round($total_delivery_rating/$total_user)}}/5</span></li>
										@endif
										@if(round($total_delivery_rating/$total_user) == 5 )
										<li class="e">ပို႕ေဆာင္မႈ ျမန္/မျမန္<span>{{round($total_delivery_rating/$total_user)}}/5</span></li>
										@endif
										<!-- Shop Rating -->
										@if(round($total_shop_rating/$total_user) == 1 || round($total_shop_rating/$total_user) == 0)
										<li class="a">ဤဆိုင္ ေကာင္း/မေကာင္း<span>{{round($total_shop_rating/$total_user)}}/5</span></li>
										@endif
										@if(round($total_shop_rating/$total_user) == 2)
										<li class="b">ဤဆိုင္ ေကာင္း/မေကာင္း<span>{{round($total_shop_rating/$total_user)}}/5</span></li>
										@endif
										@if(round($total_shop_rating/$total_user) == 3)
										<li class="c">ဤဆိုင္ ေကာင္း/မေကာင္း<span>{{round($total_shop_rating/$total_user)}}/5</span></li>
										@endif
										@if(round($total_shop_rating/$total_user) == 4)
										<li class="d">ဤဆိုင္ ေကာင္း/မေကာင္း<span>{{round($total_shop_rating/$total_user)}}/5</span></li>
										@endif
										@if(round($total_shop_rating/$total_user) == 5)
										<li class="e">ဤဆိုင္ ေကာင္း/မေကာင္း<span>{{round($total_shop_rating/$total_user)}}/5</span></li>
										@endif
									</ul>
									<p class="scheme-f"><span>{{round((($total_item_rating / $total_user) + ($total_shop_rating / $total_user) + ($total_delivery_rating / $total_user)) / 3)}}/5</span></p>
									@endif
								</div>
								<div>
									<h4>Customer ၏ သံုးသပ္ခ်က္မ်ား</h4>
									@if(count($review) != 0)
									<div class="scroller-a news-c">
										@foreach($review as $rows)
										<article>
											<h4>{{$rows->username}}<span> at {{$rows->created_at}}</span></h4>
											@if(round(($rows->item_rating + $rows->shop_rating + $rows->delivery_rating) / 3 ) == "1")
											<p class="rating-a b"></p>
											@endif
											@if(round(($rows->item_rating + $rows->shop_rating + $rows->delivery_rating) / 3 ) == "2")
											<p class="rating-a c"></p>
											@endif
											@if(round(($rows->item_rating + $rows->shop_rating + $rows->delivery_rating) / 3 ) == "3")
											<p class="rating-a d"></p>
											@endif
											@if(round(($rows->item_rating + $rows->shop_rating + $rows->delivery_rating) / 3 ) == "4")
											<p class="rating-a e"></p>
											@endif
											@if(round(($rows->item_rating + $rows->shop_rating + $rows->delivery_rating) / 3 ) == "5")
											<p class="rating-a f"></p>
											@endif
											@if(round(($rows->item_rating + $rows->shop_rating + $rows->delivery_rating) / 3 ) == "0")
											<p class="rating-a a"></p>
											@endif
											<p class="reviews">{{$rows->comment}}</p>
										</article>
										@endforeach
									</div>
									@endif
									@if(Auth::check())
									<a href="#" data-reveal-id="rating_review" class="button">Rating & Review ေရးရန္</a>
									@endif
								</div>
							</div>
							</div>
							
						<div>
						
						</div>
					</div>
				</div>
			</div>
		<div class="row">
			<div class="large-12 columns relative_title">အမ်ိဳးအစားတူ ပစၥည္းမ်ား</div>
			<div class="row">
				<div class="arge-12 columns nopadding relative_item_bg">
					@foreach($relative_items as $rows)
						<div class="item">
							<div class="hot_item_price">{{$rows->itemdetail[0]->price}} Ks</div>
							<div class="preview_photo">
								<a href="../../itemdetail/{{$rows->id}}"><img class="center_photo" src="../../itemphoto/php/files/medium/{{$rows->image}}" /></a>
							</div>
							<div class="row nopadding">
								<div class="column nopadding list_item_title"><a href="../../itemdetail/{{$rows->id}}">{{$rows->name}}</a></div>
							</div>
						</div>
					@endforeach
					<!-- <div class="pre"></div> -->
					<!-- <div class="next"></div> -->
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">


$('.add_to_group_sale').click(function(){
	var frm_item 		= $('#frm_item').serialize();
	var url = "/entertogroup?"+frm_item;
	$.ajax({
		type: "GET",
		url: url,
		data: null,
		}).done(function( response ) {
			var option = {
				  ele: 'body', // which element to append to
				  type: 'success', // (null, 'info', 'error', 'success')
				  offset: {from: 'top', amount: 20}, // 'top', or 'bottom'
				  align: 'right', // ('left', 'right', or 'center')
				  width: 250, // (integer, or 'auto')
				  delay: 4000,
				  allow_dismiss: true,
				  stackup_spacing: 10 // spacing between consecutively stacked growls.
				}
			if(response["status"] === 1){
				$.bootstrapGrowl(response["message"], option);
				window.location.href= response["redirect_uri"];
			}else if(response["status"] === 2){
				$.bootstrapGrowl(response["message"], option);
				window.location.href= response["redirect_uri"];
			}
		});
	return false;
});
/*!
 * jQuery Tools v1.2.7 - tabs.js
 * https://jquerytools.org/
 */
(function(a){a.tools=a.tools||{version:"v1.2.7"},a.tools.tabs={conf:{tabs:"a",current:"current",onBeforeClick:null,onClick:null,effect:"default",initialEffect:!1,initialIndex:0,event:"click",rotate:!1,slideUpSpeed:400,slideDownSpeed:400,history:!1},addEffect:function(a,c){b[a]=c}};var b={"default":function(a,b){this.getPanes().hide().eq(a).show(),b.call()},fade:function(a,b){var c=this.getConf(),d=c.fadeOutSpeed,e=this.getPanes();d?e.fadeOut(d):e.hide(),e.eq(a).fadeIn(c.fadeInSpeed,b)},slide:function(a,b){var c=this.getConf();this.getPanes().slideUp(c.slideUpSpeed),this.getPanes().eq(a).slideDown(c.slideDownSpeed,b)},ajax:function(a,b){this.getPanes().eq(0).load(this.getTabs().eq(a).attr("href"),b)}},c,d;a.tools.tabs.addEffect("horizontal",function(b,e){if(!c){var f=this.getPanes().eq(b),g=this.getCurrentPane();d||(d=this.getPanes().eq(0).width()),c=!0,f.show(),g.animate({width:0},{step:function(a){f.css("width",d-a)},complete:function(){a(this).hide(),e.call(),c=!1}}),g.length||(e.call(),c=!1)}});function e(c,d,e){var f=this,g=c.add(this),h=c.find(e.tabs),i=d.jquery?d:c.children(d),j;h.length||(h=c.children()),i.length||(i=c.parent().find(d)),i.length||(i=a(d)),a.extend(this,{click:function(d,i){var k=h.eq(d),l=!c.data("tabs");typeof d=="string"&&d.replace("#","")&&(k=h.filter("[href*=\""+d.replace("#","")+"\"]"),d=Math.max(h.index(k),0));if(e.rotate){var m=h.length-1;if(d<0)return f.click(m,i);if(d>m)return f.click(0,i)}if(!k.length){if(j>=0)return f;d=e.initialIndex,k=h.eq(d)}if(d===j)return f;i=i||a.Event(),i.type="onBeforeClick",g.trigger(i,[d]);if(!i.isDefaultPrevented()){var n=l?e.initialEffect&&e.effect||"default":e.effect;b[n].call(f,d,function(){j=d,i.type="onClick",g.trigger(i,[d])}),h.removeClass(e.current),k.addClass(e.current);return f}},getConf:function(){return e},getTabs:function(){return h},getPanes:function(){return i},getCurrentPane:function(){return i.eq(j)},getCurrentTab:function(){return h.eq(j)},getIndex:function(){return j},next:function(){return f.click(j+1)},prev:function(){return f.click(j-1)},destroy:function(){h.off(e.event).removeClass(e.current),i.find("a[href^=\"#\"]").off("click.T");return f}}),a.each("onBeforeClick,onClick".split(","),function(b,c){a.isFunction(e[c])&&a(f).on(c,e[c]),f[c]=function(b){b&&a(f).on(c,b);return f}}),e.history&&a.fn.history&&(a.tools.history.init(h),e.event="history"),h.each(function(b){a(this).on(e.event,function(a){f.click(b,a);return a.preventDefault()})}),i.find("a[href^=\"#\"]").on("click.T",function(b){f.click(a(this).attr("href"),b)}),location.hash&&e.tabs=="a"&&c.find("[href=\""+location.hash+"\"]").length?f.click(location.hash):(e.initialIndex===0||e.initialIndex>0)&&f.click(e.initialIndex)}a.fn.tabs=function(b,c){var d=this.data("tabs");d&&(d.destroy(),this.removeData("tabs")),a.isFunction(c)&&(c={onBeforeClick:c}),c=a.extend({},a.tools.tabs.conf,c),this.each(function(){d=new e(a(this),b,c),a(this).data("tabs",d)});return c.api?d:this}})(jQuery);(function(a){var b;b=a.tools.tabs.slideshow={conf:{next:".forward",prev:".backward",disabledClass:"disabled",autoplay:!1,autopause:!0,interval:3e3,clickable:!0,api:!1}};function c(b,c){var d=this,e=b.add(this),f=b.data("tabs"),g,h=!0;function i(c){var d=a(c);return d.length<2?d:b.parent().find(c)}var j=i(c.next).click(function(){f.next()}),k=i(c.prev).click(function(){f.prev()});function l(){g=setTimeout(function(){f.next()},c.interval)}a.extend(d,{getTabs:function(){return f},getConf:function(){return c},play:function(){if(g)return d;var b=a.Event("onBeforePlay");e.trigger(b);if(b.isDefaultPrevented())return d;h=!1,e.trigger("onPlay"),e.on("onClick",l),l();return d},pause:function(){if(!g)return d;var b=a.Event("onBeforePause");e.trigger(b);if(b.isDefaultPrevented())return d;g=clearTimeout(g),e.trigger("onPause"),e.off("onClick",l);return d},resume:function(){h||d.play()},stop:function(){d.pause(),h=!0}}),a.each("onBeforePlay,onPlay,onBeforePause,onPause".split(","),function(b,e){a.isFunction(c[e])&&a(d).on(e,c[e]),d[e]=function(b){return a(d).on(e,b)}}),c.autopause&&f.getTabs().add(j).add(k).add(f.getPanes()).hover(d.pause,d.resume),c.autoplay&&d.play(),c.clickable&&f.getPanes().click(function(){f.next()});if(!f.getConf().rotate){var m=c.disabledClass;f.getIndex()||k.addClass(m),f.onBeforeClick(function(a,b){k.toggleClass(m,!b),j.toggleClass(m,b==f.getTabs().length-1)})}}a.fn.slideshow=function(d){var e=this.data("slideshow");if(e)return e;d=a.extend({},b.conf,d),this.each(function(){e=new c(a(this),d),a(this).data("slideshow",e)});return d.api?e:this}})(jQuery);

/*!
 * jScrollPane - 2.0.0 b12
 * https://jscrollpane.kelvinluck.com/
 */
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('(3(b,a,c){b.2r.2U=3(e){3 d(D,O){16 9,Q=1f,Y,1a,v,18,T,Z,y,q,1u,1s,1o,i,I,h,j,1g,U,1A,X,t,A,1Z,21,2a,G,l,1D,22,x,1N,2V,f,L,2b=1l,P=1l,2c=11,k=11,2s=D.4u(11,11).4v(),2t=b.2r.3z?"3z.7":"4w.7";2V=D.1b("4x")+" "+D.1b("3A")+" "+D.1b("4y")+" "+D.1b("3B");f=(3C(D.1b("3B"),10)||0)+(3C(D.1b("3A"),10)||0);3 2u(a){16 d,1E,1d,14,17,1c,1i=11,12=11;9=a;6(Y===c){17=D.1v();1c=D.2v();D.1b({2W:"4z",3D:0});1a=D.2w()+f;v=D.3E();D.1m(1a);Y=b(\'<1h 1e="3F" />\').1b("3D",2V).1t(D.3G());18=b(\'<1h 1e="4A" />\').1b({1m:1a+"1w",2d:v+"1w"}).1t(Y).4B(D)}19{D.1b("1m","");1i=9.3H&&K();12=9.3I&&B();14=D.2w()+f!=1a||D.23()!=v;6(14){1a=D.2w()+f;v=D.3E();18.1b({1m:1a+"1w",2d:v+"1w"})}6(!14&&L==T&&Y.23()==Z){D.1m(1a);13}L=T;Y.1b("1m","");D.1m(1a);18.1p(">.2e,>.2f").4C().4D()}Y.1b("2W","4E");6(a.2X){T=a.2X}19{T=Y[0].4F}Z=Y[0].4G;Y.1b("2W","");y=T/1a;q=Z/v;1u=q>1;1s=y>1;6(!(1s||1u)){D.1x("2x");Y.1b({1q:0,1m:18.1m()-f});n();E();R();w()}19{D.1B("2x");d=9.3J&&(I||1g);6(d){1E=1F();1d=1G()}3K();z();F();6(d){N(12?(T-1a):1E,11);M(1i?(Z-v):1d,11)}J();3L();3M();6(9.3N){S()}6(9.3O){p()}C();6(9.2Y){m()}}6(9.2Z&&!1N){1N=3P(3(){2u(9)},9.3Q)}19{6(!9.2Z&&1N){31(1N)}}17&&D.1v(0)&&M(17,11);1c&&D.2v(0)&&N(1c,11);D.1O("7-4H",[1s||1u])}3 3K(){6(1u){18.1t(b(\'<1h 1e="2e" />\').1t(b(\'<1h 1e="1P 4I" />\'),b(\'<1h 1e="2y" />\').1t(b(\'<1h 1e="2z" />\').1t(b(\'<1h 1e="4J" />\'),b(\'<1h 1e="4K" />\'))),b(\'<1h 1e="1P 4L" />\')));U=18.1p(">.2e");1A=U.1p(">.2y");1o=1A.1p(">.2z");6(9.2g){1Z=b(\'<a 1e="1Q 4M" />\').15("1C.7",1H(0,-1)).15("1R.7",1S);21=b(\'<a 1e="1Q 4N" />\').15("1C.7",1H(0,1)).15("1R.7",1S);6(9.3a){1Z.15("2A.7",1H(0,-1,1Z));21.15("2A.7",1H(0,1,21))}3b(1A,9.3R,1Z,21)}t=v;18.1p(">.2e>.1P:3c,>.2e>.1Q").2h(3(){t-=b(1f).23()});1o.3S(3(){1o.1B("2B")},3(){1o.1x("2B")}).15("1C.7",3(c){b("1T").15("3d.7 3e.7",1S);1o.1B("24");16 s=c.1U-1o.1y().1q;b("1T").15("3f.7",3(a){V(a.1U-s,11)}).15("1M.7 3g.7",3h);13 11});o()}}3 o(){1A.2d(t+"1w");I=0;X=9.2C+1A.25();Y.1m(1a-X-f);2D{6(U.1y().1z===0){Y.1b("4O-1z",X+"1w")}}2E(s){}}3 z(){6(1s){18.1t(b(\'<1h 1e="2f" />\').1t(b(\'<1h 1e="1P 4P" />\'),b(\'<1h 1e="2y" />\').1t(b(\'<1h 1e="2z" />\').1t(b(\'<1h 1e="4Q" />\'),b(\'<1h 1e="4R" />\'))),b(\'<1h 1e="1P 4S" />\')));2a=18.1p(">.2f");G=2a.1p(">.2y");h=G.1p(">.2z");6(9.2g){22=b(\'<a 1e="1Q 4T" />\').15("1C.7",1H(-1,0)).15("1R.7",1S);x=b(\'<a 1e="1Q 4U" />\').15("1C.7",1H(1,0)).15("1R.7",1S);6(9.3a){22.15("2A.7",1H(-1,0,22));x.15("2A.7",1H(1,0,x))}3b(G,9.3T,22,x)}h.3S(3(){h.1B("2B")},3(){h.1x("2B")}).15("1C.7",3(c){b("1T").15("3d.7 3e.7",1S);h.1B("24");16 s=c.1V-h.1y().1z;b("1T").15("3f.7",3(a){W(a.1V-s,11)}).15("1M.7 3g.7",3h);13 11});l=18.2w();3i()}}3 3i(){18.1p(">.2f>.1P:3c,>.2f>.1Q").2h(3(){l-=b(1f).25()});G.1m(l+"1w");1g=0}3 F(){6(1s&&1u){16 a=G.23(),s=1A.25();t-=a;b(2a).1p(">.1P:3c,>.1Q").2h(3(){l+=b(1f).25()});l-=s;v-=s;1a-=a;G.4V().1t(b(\'<1h 1e="4W" />\').1b("1m",a+"1w"));o();3i()}6(1s){Y.1m((18.25()-f)+"1w")}Z=Y.23();q=Z/v;6(1s){1D=26.2F(1/y*l);6(1D>9.3j){1D=9.3j}19{6(1D<9.3k){1D=9.3k}}h.1m(1D+"1w");j=l-1D;2G(1g)}6(1u){A=26.2F(1/q*t);6(A>9.3l){A=9.3l}19{6(A<9.3m){A=9.3m}}1o.2d(A+"1w");i=t-A;2H(I)}}3 3b(a,b,c,s){16 d="4X",12="3U",1d;6(b=="4Y"){b=/4Z/.3V(51.52)?"3U":"3n"}6(b==d){12=b}19{6(b==12){d=b;1d=c;c=s;s=1d}}a[d](c)[12](s)}3 1H(a,s,b){13 3(){H(a,s,1f,b);1f.53();13 11}}3 H(a,c,d,e){d=b(d).1B("24");16 f,14,17=1l,s=3(){6(a!==0){Q.1W(a*9.2I)}6(c!==0){Q.1I(c*9.2I)}14=3o(s,17?9.2J:9.3W);17=11};s();f=e?"54.7":"1M.7";e=e||b("1T");e.15(f,3(){d.1x("24");14&&3p(14);14=2i;e.1n(f)})}3 p(){w();6(1u){1A.15("1C.7",3(d){6(d.2K===c||d.2K==d.3X){16 e=b(1f),1i=e.2j(),1d=d.1U-1i.1q-I,14,17=1l,s=3(){16 a=e.2j(),1r=d.1U-a.1q-A/2,1c=v*9.2k,1X=i*1c/(Z-v);6(1d<0){6(I-1X>1r){Q.1I(-1c)}19{V(1r)}}19{6(1d>0){6(I+1X<1r){Q.1I(1c)}19{V(1r)}}19{12();13}}14=3o(s,17?9.2J:9.3q);17=11},12=3(){14&&3p(14);14=2i;b(1J).1n("1M.7",12)};s();b(1J).15("1M.7",12);13 11}})}6(1s){G.15("1C.7",3(d){6(d.2K===c||d.2K==d.3X){16 e=b(1f),1i=e.2j(),1d=d.1V-1i.1z-1g,14,17=1l,s=3(){16 a=e.2j(),1r=d.1V-a.1z-1D/2,1c=1a*9.2k,1X=j*1c/(T-1a);6(1d<0){6(1g-1X>1r){Q.1W(-1c)}19{W(1r)}}19{6(1d>0){6(1g+1X<1r){Q.1W(1c)}19{W(1r)}}19{12();13}}14=3o(s,17?9.2J:9.3q);17=11},12=3(){14&&3p(14);14=2i;b(1J).1n("1M.7",12)};s();b(1J).15("1M.7",12);13 11}})}}3 w(){6(G){G.1n("1C.7")}6(1A){1A.1n("1C.7")}}3 3h(){b("1T").1n("3d.7 3e.7 3f.7 1M.7 3g.7");6(1o){1o.1x("24")}6(h){h.1x("24")}}3 V(s,a){6(!1u){13}6(s<0){s=0}19{6(s>i){s=i}}6(a===c){a=9.3r}6(a){Q.2L(1o,"1q",s,2H)}19{1o.1b("1q",s);2H(s)}}3 2H(a){6(a===c){a=1o.1y().1q}18.1v(0);I=a;16 b=I===0,14=I==i,12=a/i,s=-12*(Z-v);6(2b!=b||2c!=14){2b=b;2c=14;D.1O("7-3Y-3Z",[2b,2c,P,k])}u(b,14);Y.1b("1q",s);D.1O("7-2M-y",[-s,b,14]).1O("2M")}3 W(a,s){6(!1s){13}6(a<0){a=0}19{6(a>j){a=j}}6(s===c){s=9.3r}6(s){Q.2L(h,"1z",a,2G)}19{h.1b("1z",a);2G(a)}}3 2G(a){6(a===c){a=h.1y().1z}18.1v(0);1g=a;16 b=1g===0,12=1g==j,14=a/j,s=-14*(T-1a);6(P!=b||k!=12){P=b;k=12;D.1O("7-3Y-3Z",[2b,2c,P,k])}r(b,12);Y.1b("1z",s);D.1O("7-2M-x",[-s,b,12]).1O("2M")}3 u(a,s){6(9.2g){1Z[a?"1B":"1x"]("2N");21[s?"1B":"1x"]("2N")}}3 r(a,s){6(9.2g){22[a?"1B":"1x"]("2N");x[s?"1B":"1x"]("2N")}}3 M(s,a){16 b=s/(Z-v);V(b*i,a)}3 N(a,s){16 b=a/(T-1a);W(b*j,s)}3 2l(a,c,d){16 e,12,1j,s=0,27=0,17,1c,1i,1r,2m,2n;2D{e=b(a)}2E(1d){13}12=e.23();1j=e.25();18.1v(0);18.2v(0);55(!e.56(".3F")){s+=e.1y().1q;27+=e.1y().1z;e=e.57();6(/^2O|1T$/i.3V(e[0].58)){13}}17=1G();1i=17+v;6(s<17||c){2m=s-9.2C}19{6(s+12>1i){2m=s-v+12+9.2C}}6(2m){M(2m,d)}1c=1F();1r=1c+1a;6(27<1c||c){2n=27-9.3s}19{6(27+1j>1r){2n=27-1a+1j+9.3s}}6(2n){N(2n,d)}}3 1F(){13-Y.1y().1z}3 1G(){13-Y.1y().1q}3 K(){16 s=Z-v;13(s>20)&&(s-1G()<10)}3 B(){16 s=T-1a;13(s>20)&&(s-1F()<10)}3 3L(){18.1n(2t).15(2t,3(a,b,c,d){16 e=1g,s=I;Q.41(c*9.2P,-d*9.2P,11);13 e==1g&&s==I})}3 n(){18.1n(2t)}3 1S(){13 11}3 J(){Y.1p(":42,a").1n("2o.7").15("2o.7",3(s){2l(s.3t,11)})}3 E(){Y.1p(":42,a").1n("2o.7")}3 S(){16 s,17,12=[];1s&&12.43(2a[0]);1u&&12.43(U[0]);Y.2o(3(){D.2o()});D.2Q("3u",0).1n("3v.7 3w.7").15("3v.7",3(a){6(a.3t!==1f&&!(12.2p&&b(a.3t).44(12).2p)){13}16 c=1g,1j=I;45(a.2R){1k 40:1k 38:1k 34:1k 32:1k 33:1k 39:1k 37:s=a.2R;14();1K;1k 35:M(Z-v);s=2i;1K;1k 36:M(0);s=2i;1K}17=a.2R==s&&c!=1g||1j!=I;13!17}).15("3w.7",3(a){6(a.2R==s){14()}13!17});6(9.28){D.1b("46","59");6("28"47 18[0]){D.2Q("28",1l)}}19{D.1b("46","");6("28"47 18[0]){D.2Q("28",11)}}3 14(){16 a=1g,1j=I;45(s){1k 40:Q.1I(9.29,11);1K;1k 38:Q.1I(-9.29,11);1K;1k 34:1k 32:Q.1I(v*9.2k,11);1K;1k 33:Q.1I(-v*9.2k,11);1K;1k 39:Q.1W(9.29,11);1K;1k 37:Q.1W(-9.29,11);1K}17=a!=1g||1j!=I;13 17}}3 R(){D.2Q("3u","-1").5a("3u").1n("3v.7 3w.7")}3 C(){6(1Y.3x&&1Y.3x.2p>1){16 a,17,14=48(1Y.3x.2S(1));2D{a=b("#"+14+\', a[49="\'+14+\'"]\')}2E(s){13}6(a.2p&&Y.1p(14)){6(18.1v()===0){17=3P(3(){6(18.1v()>0){2l(a,1l);b(1J).1v(18.1y().1q);31(17)}},50)}19{2l(a,1l);b(1J).1v(18.1y().1q)}}}}3 m(){6(b(1J.2O).2q("4a")){13}b(1J.2O).2q("4a",1l);b(1J.2O).5b("a[1L*=#]","1R",3(s){16 c=1f.1L.2S(0,1f.1L.2T("#")),12=1Y.1L,1i,1c,14,1d,1j,1E;6(1Y.1L.2T("#")!==-1){12=1Y.1L.2S(0,1Y.1L.2T("#"))}6(c!==12){13}1i=48(1f.1L.2S(1f.1L.2T("#")+1));1c;2D{1c=b("#"+1i+\', a[49="\'+1i+\'"]\')}2E(1X){13}6(!1c.2p){13}14=1c.44(".2x");1d=14.2q("7");1d.4b(1c,1l);6(14[0].4c){1j=b(a).1v();1E=1c.2j().1q;6(1E<1j||1E>1j+b(a).2d()){14[0].4c()}}s.5c()})}3 3M(){16 c,17,1j,12,1d,s=11;18.1n("4d.7 4e.7 4f.7 1R.7-4g").15("4d.7",3(a){16 b=a.4h.4i[0];c=1F();17=1G();1j=b.1V;12=b.1U;1d=11;s=1l}).15("4e.7",3(a){6(!s){13}16 b=a.4h.4i[0],1i=1g,1E=I;Q.4j(c+1j-b.1V,17+12-b.1U);1d=1d||26.4k(1j-b.1V)>5||26.4k(12-b.1U)>5;13 1i==1g&&1E==I}).15("4f.7",3(a){s=11}).15("1R.7-4g",3(a){6(1d){1d=11;13 11}})}3 g(){16 s=1G(),17=1F();D.1x("2x").1n(".7");D.5d(2s.1t(Y.3G()));2s.1v(s);2s.2v(17);6(1N){31(1N)}}b.3y(Q,{4l:3(a){a=b.3y({},9,a);2u(a)},4b:3(a,b,s){2l(a,b,s)},4j:3(a,s,b){N(a,b);M(s,b)},5e:3(a,s){N(a,s)},5f:3(s,a){M(s,a)},5g:3(a,s){N(a*(T-1a),s)},5h:3(a,s){M(a*(Z-v),s)},41:3(a,s,b){Q.1W(a,b);Q.1I(s,b)},1W:3(s,a){16 b=1F()+26[s<0?"4m":"2F"](s),12=b/(T-1a);W(12*j,a)},1I:3(s,a){16 b=1G()+26[s<0?"4m":"2F"](s),12=b/(Z-v);V(12*i,a)},5i:3(s,a){W(s,a)},5j:3(a,s){V(a,s)},2L:3(a,b,s,c){16 d={};d[b]=s;a.2L(d,{5k:9.4n,5l:9.4o,5m:11,5n:c})},5o:3(){13 1F()},5p:3(){13 1G()},5q:3(){13 T},5r:3(){13 Z},5s:3(){13 1F()/(T-1a)},5t:3(){13 1G()/(Z-v)},5u:3(){13 1s},5v:3(){13 1u},5w:3(){13 Y},5x:3(s){V(i,s)},2Y:b.5y,5z:3(){g()}});2u(O)}e=b.3y({},b.2r.2U.4p,e);b.2h(["2P","2I","4q","29"],3(){e[1f]=e[1f]||e.4r});13 1f.2h(3(){16 f=b(1f),g=f.2q("7");6(g){g.4l(e)}19{g=5A d(f,e);f.2q("7",g)}})};b.2r.2U.4p={2g:11,3J:1l,3H:11,3I:11,3O:1l,2Z:11,3Q:5B,3m:0,3l:4s,3k:0,3j:4s,2X:c,3r:11,4n:4t,4o:"5C",2Y:11,2C:4,3s:4,2P:0,2I:0,3W:50,3a:11,4q:0,3q:5D,3R:"3n",3T:"3n",3N:1l,28:11,29:0,2J:4t,4r:30,2k:0.8}})(5E,1f);',62,351,'|||function|||if|jsp||ba||||||||||||||||||||||||||||||||||||||||||||||||||||||false|aK|return|aJ|bind|var|aI|al|else|aj|css|aP|aM|class|this|aa|div|aO|aL|case|true|width|unbind|au|find|top|aS|aE|append|az|scrollTop|px|removeClass|position|left|ap|addClass|mousedown|at|aN|aC|aA|aD|scrollByY|document|break|href|mouseup|av|trigger|jspCap|jspArrow|click|aB|html|pageY|pageX|scrollByX|aQ|location|aq||af|ax|outerHeight|jspActive|outerWidth|Math|aU|hideFocus|keyboardSpeed|am|ai|aG|height|jspVerticalBar|jspHorizontalBar|showArrows|each|null|offset|scrollPagePercent|ab|aR|aT|focus|length|data|fn|ao|ac|ar|scrollLeft|innerWidth|jspScrollable|jspTrack|jspDrag|mouseover|jspHover|verticalGutter|try|catch|ceil|ae|ad|arrowButtonSpeed|initialDelay|originalTarget|animate|scroll|jspDisabled|body|mouseWheelSpeed|attr|keyCode|substr|indexOf|jScrollPane|aH|overflow|contentWidth|hijackInternalLinks|autoReinitialise||clearInterval|||||||||arrowScrollOnHover|ak|visible|dragstart|selectstart|mousemove|mouseleave|aw|ah|horizontalDragMaxWidth|horizontalDragMinWidth|verticalDragMaxHeight|verticalDragMinHeight|split|setTimeout|clearTimeout|trackClickRepeatFreq|animateScroll|horizontalGutter|target|tabindex|keydown|keypress|hash|extend|mwheelIntent|paddingRight|paddingLeft|parseInt|padding|innerHeight|jspPane|children|stickToBottom|stickToRight|maintainPosition|aF|ag|an|enableKeyboardNavigation|clickOnTrack|setInterval|autoReinitialiseDelay|verticalArrowPositions|hover|horizontalArrowPositions|after|test|arrowRepeatFreq|currentTarget|arrow|change||scrollBy|input|push|closest|switch|outline|in|escape|name|jspHijack|scrollToElement|scrollIntoView|touchstart|touchmove|touchend|touchclick|originalEvent|touches|scrollTo|abs|reinitialise|floor|animateDuration|animateEase|defaults|trackClickSpeed|speed|99999|300|clone|empty|mousewheel|paddingTop|paddingBottom|hidden|jspContainer|appendTo|remove|end|auto|scrollWidth|scrollHeight|initialised|jspCapTop|jspDragTop|jspDragBottom|jspCapBottom|jspArrowUp|jspArrowDown|margin|jspCapLeft|jspDragLeft|jspDragRight|jspCapRight|jspArrowLeft|jspArrowRight|parent|jspCorner|before|os|Mac||navigator|platform|blur|mouseout|while|is|offsetParent|nodeName|none|removeAttr|delegate|preventDefault|replaceWith|scrollToX|scrollToY|scrollToPercentX|scrollToPercentY|positionDragX|positionDragY|duration|easing|queue|step|getContentPositionX|getContentPositionY|getContentWidth|getContentHeight|getPercentScrolledX|getPercentScrolledY|getIsScrollableH|getIsScrollableV|getContentPane|scrollToBottom|noop|destroy|new|500|linear|70|jQuery'.split('|'),0,{}));

	$('.scroller-a').jScrollPane({ verticalDragMinHeight: 70, verticalDragMaxHeight: 70, horizontalDragMinWidth: 9, horizontalDragMaxWidth: 9, showArrows: true });

	$('.tabs-a > ul').tabs('.tabs-a > div > div');

	function changeSize(length, position){
		for (var i = 0; i < length; i++) {
			if(i == position){
				$(".item_size_"+i).show();
				$(".item_remaining_qty_"+i).show();
			}else{
				$(".item_size_"+i).hide();
				$(".item_remaining_qty_"+i).hide();

			}
		}
	}

	function changePrice(length, position){
		for (var i = 0; i < length; i++) {
			if(i == position){
				$(".item_price_"+i).show();
			}else{
				$(".item_price_"+i).hide();

			}
		}
	}

	function setPricebyQty(qty){
		$('#qty').val(qty);
	}

	$('.each_size input[type="radio"]').on('click',function(){
		if(this.checked) { 
			$(this).parent('div').parent().parent().children().children('div').removeClass('each_size_selected');
			$(this).parent('div').addClass('each_size_selected'); 
		}
		else {
			$(this).parent('div').removeClass('each_size_selected'); 
		}
	});

	$('.each_color input[type="radio"]').on('click',function(){
		if(this.checked) { 
			$(this).parent('div').parent().parent().children().children('div').removeClass('each_color_selected');
			$(this).parent('div').addClass('each_color_selected'); 
		}
		else {
			$(this).parent('div').removeClass('each_color_selected'); 
		}
	});

	$('#inc_qty').click(
		function(){
			var qty = $('#qty').val();
			$('#qty').val(Number(qty) + 1);
		});

	$('#de_qty').click(
		function(){
			var qty = $('#qty').val();
			if(Number(qty) > 1){
				$('#qty').val(Number(qty) - 1 );
			}
		});

	$('.compare_price').click(
		function(){
			$('.compare_price_table').toggleClass('compare_price_table_show');
			return false;
	});

	$('div.rateit_item').rateit();
	$('div.rateit_item').bind('rated', function() { 
		$('#rateit_item').val($(this).rateit('value'));
	});

	$('div.rateit_delivery').rateit();
	$('div.rateit_delivery').bind('rated', function() { 
		$('#rateit_delivery').val($(this).rateit('value'));
	});

	$('div.rateit_shop').rateit();
	$('div.rateit_shop').bind('rated', function() { 
		$('#rateit_shop').val($(this).rateit('value'));
	});
</script>
@stop
@extends('../../../../master')
@section('content')

{{HTML::style('../../../../css/hover/component.css')}}
{{HTML::style('../../../../css/smkstyle.css')}}

<br>
{{HTML::script('../../../../js/hover/modernizr.custom.js')}}
{{HTML::script('../../../../js/hover/toucheffects.js')}}
<style type="text/css">
		ul.pagination{margin-top: -6px;}
		.pagination li.active{border: none; height: 24px !important;}	
		ul.pagination li{padding:0 8px; height: 24px;border:1px solid #eee;}
		ul.pagination li a{padding:0;}
		.pagination li:hover .info{color: #000;}
</style>
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
	<div class="large-10 medium-12 column nopadding">
		<div class="row nopadding">
			<div class="large-12 medium-12 column nopadding filter_menu">
				<div class="large-2	medium-3 hide-for-small columns nopadding menu @if($sorting == 'comprehensive') active @endif "><a href="?sort=comprehensive">Comprehensive Sorting</a></div>
				<div class="large-1	medium-2 small-4 columns nopadding menu @if($sorting == 'popularity') active @endif"><a href="?sort=popularity">Popularity</a></div>
				<div class="large-1	medium-1 small-4 columns nopadding menu @if($sorting == 'new') active @endif"><a href="?sort=new">New</a></div>
				<div class="large-1	medium-2 small-4 columns nopadding menu @if($sorting == 'discounts') active @endif"><a href="?sort=discounts">Discounts</a></div>
				<div class="large-2	medium-4 hide-for-small columns nopadding">
					<div class="large-3 medium-3 column txt_lable nopadding">Price : </div>
					<div class="large-4 medium-4 column nopadding"><input type="text" style="margin-top:7px; padding-top:5px;height:25px;width:50px;" /></div>
					<div class="large-1 medium-1 column txt_lable nopadding">-</div>
					<div class="large-4 medium-4 column nopadding"><input type="text" style="margin-top:7px; padding-top:5px;height:25px;width:50px;" /></div>
				</div>
				<div class="large-1 hide-for-small hide-for-medium columns nopadding txt_lable">&nbsp;</div>
				<div class="large-3 columns hide-for-small hide-for-medium nopadding txt_lable">
					@if(count($items)>0)
						@if ($items->getLastPage() > 0)
							<?php $previousPage = ($items->getCurrentPage() > 1) ? $items->getCurrentPage() - 1 : 1; ?>  
							<ul class="pagination"> 
								<li class="item{{ ($items->getCurrentPage() == 1) ? ' disabled' : '' }}"> 
									@if($items->getCurrentPage()>1)
									    <a href="{{ $items->getUrl($previousPage) }}&search={{$search}}">
									    	« Prev
									    </a>
								    @else
									    « Prev
								    @endif
								</li>
							    @for ($i = 1; $i <= $items->getLastPage(); $i++)
								  @if($items->getCurrentPage() == $i) 
								  <li class="item{{ ($items->getCurrentPage() == $i) ? ' active' : '' }}">
									    <a href="{{ $items->getUrl($i) }}&search={{$search}}">
									      {{ $i }}
									    </a>
								  </li>
								  @endif
							    @endfor
							    <li class="info">
								     of
								 </li>
							    <li class="item{{ ($items->getLastPage() == $i) ? ' active' : '' }}">
								    <a href="{{ $items->getUrl($items->getLastPage()) }}&search={{$search}}">
								    	{{ $items->getLastPage() }}
								    </a>
							    </li>
							  	<li class="item{{ ($items->getCurrentPage() == $items->getLastPage()) ? ' disabled' : '' }}">
								@if($items->getCurrentPage() != $items->getLastPage())
									<a href="{{ $items->getUrl($items->getCurrentPage()+1) }}&search={{$search}}">
								    	Next <span>»</span>
									</a>
								@else
								  	Next <span>»</span>
								@endif
								</li>
							</ul>  
						@endif
						<!-- <div class="pagination">{{$items->links()}}</div> -->
					@endif
				</div>
			</div>
		</div>
		<div class="row nopadding">
			<?php $i=0; ?>
			@foreach($items as $rows)
			<div class="column nopadding list_item left @if($i==0)first @elseif($i==4)last@endif" >
				@if($rows->status == "new-arrival")
				<div class="new_arrival">
					<img src="../../../../images/new_01.png">
				</div>
				@endif
				<div class="preview_photo">
					<a href="../../../../itemdetail/{{$rows->id}}"><img class="center_photo" src="../../../../itemphoto/php/files/medium/{{$rows->image}}" /></a>
				</div>
				<div class="row thumb_photo_container">
					@foreach($rows->itemthumbimages as $images)
					<div class="column nopadding thumb_photo"><img class="center_photo" src="../../../../itemphoto/php/files/thumbnail/{{$images->image}}" /></div>
					@endforeach
				</div>
				<div class="row nopadding">
					<div class="large-7 column nopadding list_item_price">@if(count($rows->itemdetail)>0){{$rows->itemdetail[0]->price}}@endif Ks</div>
					<div class="large-5 column">
						<div class="list_item_love">၅၂၈</div>
					</div>
				</div>
				<div class="row">
					<div class="column nopadding list_item_title"><a href="../../../../itemdetail/{{$rows->id}}"> {{$rows->name;}} </a></div>
				</div>
			</div>
			<?php 
				$i++; 
				if($i == 5)
					$i = 0;
			?>
			@endforeach
		</div>
	</div>
	<div class="large-2 medium-12 column nopadding" style="padding-left:5px; ">
		<div class="row nopadding">
			<div class="large-12 medium-12 nopadding hot_title txt_title">Hot Treasurer</div>
		</div>
		<div class="row nopadding right_border">
			@foreach($hot_items as $rows)
			<div class="large-12 medium-3 column left nopadding hot_item">
				<div class="hot_item_price">{{$rows->itemdetail[0]->price}} Ks</div>
				<div class="preview_photo">
					<a href="../../../../itemdetail/{{$rows->id}}"><img class="center_photo" src="../../../../itemphoto/php/files/medium/{{$rows->image}}" /></a>
				</div>
				<div class="row nopadding">
					<div class="large-5 column nopadding">
						<div class="list_item_rating">၄.၅</div>
					</div>
					<div class="large-7 column nopadding list_item_sold">ေရာင္းၿပီး (၃၆၇)</div>
				</div>
				<div class="row">
					<div class="column nopadding list_item_title"><a href="../../../../itemdetail/{{$rows->id}}">{{$rows->name}}</a></div>
				</div>
			</div>
			@endforeach
		</div>
	</div> 
</div>
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
		var prevlink=$('ul .pagination li:first').clone();
		var aaaa=prevlink.find('span').append('prev');
	});
</script>
@stop
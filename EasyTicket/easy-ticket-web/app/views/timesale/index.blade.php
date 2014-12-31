@extends('../master')
@section('content')
{{HTML::style('../../../../css/hover/component.css')}}
{{HTML::style('../../../../countdown/css/jquery.countdown.css')}}

<div class="row slideframe">
 	<div class="orbit-container"> 
	  	<ul class="example-orbit" data-orbit style="background:#F4F4F0;"> 
	        @if($response['advertisement'])
		        @foreach($response['advertisement'] as $row)
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
	<div class="large-12 medium-12 column nopadding">

		<h3 class="category_title"><a href="time-sale/morning">မနက္ေစ်း</a></h3>
		<?php $i=1; ?>
		@if(count($response['timesale'])> 0)
			@foreach($response['timesale'] as $row)
				@if($i==1)
				<div class="row">
				@endif
					<div class="large-2-half columns free_get_items">
						<div class="grid_four_frame">
							<a href="/itemdetail/{{$row->id}}">
								<div class="image_frames">
									<img class="center_photo" src="../../../../itemphoto/php/files/medium/{{$row->image}}">
								</div>
								<div class="item_description">
									<div class="item_title"><span>{{$row->name_mm}}</span></div>
									<div class="price_sold"><div class="item_price">{{$row->price}} က်ပ္</div> <div class="sold_item">ေရာင္းျပီး ၂၄၅</div></div>
									<div class="price_sold">
										<del class="oldprice">@if($row->oldprice > 0) {{$row->oldprice}} က်ပ္ @endif</del> <div class="like"> ၄၅၀ </div>
									</div>
								</div>
							</a>
						</div>
					</div>
				@if($i==5)
				</div>
				@endif
			<?php $i++; if($i>5) $i=1; ?>
			@endforeach
		@endif

		<div class="clear">&nbsp;</div>
		<h3 class="category_title"><a href="time-sale/night">ညေစ်း</a></h3>
		<?php $i=1; ?>
		@if(count($response['timesale'])> 0)
			@foreach($response['timesale'] as $row)
				@if($i==1)
				<div class="row">
				@endif
					<div class="large-2-half columns free_get_items left">
						<div class="grid_four_frame">
							<a href="/itemdetail/{{$row->id}}">
								<div class="image_frames ">
									<img class="center_photo" src="../../../../itemphoto/php/files/medium/{{$row->image}}">
								</div>
								<div class="item_description">
									<div class="item_title"><span>{{$row->name_mm}}</span></div>
									<div class="price_sold"><div class="item_price">{{$row->price}} က်ပ္</div> <div class="sold_item">ေရာင္းျပီး ၂၄၅</div></div>
									<div class="price_sold">
										<del class="oldprice">@if($row->oldprice > 0) {{$row->oldprice}} က်ပ္ @endif</del> <div class="like"> ၄၅၀ </div>
									</div>
								</div>
							</a>
						</div>
					</div>
				@if($i==5)
				</div>
				@endif
			<?php $i++; if($i>5) $i=1; ?>
			@endforeach
		@endif



		
	</div>
	
</div>
<div class="clear">&nbsp;</div>
<div class="clear">&nbsp;</div>

{{HTML::script('../../../js/hover/modernizr.custom.js')}}
{{HTML::script('../../../js/hover/toucheffects.js')}}
{{HTML::script('../../js/responsiveslides.min.js')}}


{{HTML::script('../../../../countdown/js/jquery.countdown.js')}}
{{HTML::script('../../../../countdown/js/script.js')}}
<script type="text/javascript">	
/*!
 * jQuery Tools v1.2.7 - tabs.js
 * http://jquerytools.org/
 */
(function(a){a.tools=a.tools||{version:"v1.2.7"},a.tools.tabs={conf:{tabs:"a",current:"current",onBeforeClick:null,onClick:null,effect:"default",initialEffect:!1,initialIndex:0,event:"click",rotate:!1,slideUpSpeed:400,slideDownSpeed:400,history:!1},addEffect:function(a,c){b[a]=c}};var b={"default":function(a,b){this.getPanes().hide().eq(a).show(),b.call()},fade:function(a,b){var c=this.getConf(),d=c.fadeOutSpeed,e=this.getPanes();d?e.fadeOut(d):e.hide(),e.eq(a).fadeIn(c.fadeInSpeed,b)},slide:function(a,b){var c=this.getConf();this.getPanes().slideUp(c.slideUpSpeed),this.getPanes().eq(a).slideDown(c.slideDownSpeed,b)},ajax:function(a,b){this.getPanes().eq(0).load(this.getTabs().eq(a).attr("href"),b)}},c,d;a.tools.tabs.addEffect("horizontal",function(b,e){if(!c){var f=this.getPanes().eq(b),g=this.getCurrentPane();d||(d=this.getPanes().eq(0).width()),c=!0,f.show(),g.animate({width:0},{step:function(a){f.css("width",d-a)},complete:function(){a(this).hide(),e.call(),c=!1}}),g.length||(e.call(),c=!1)}});function e(c,d,e){var f=this,g=c.add(this),h=c.find(e.tabs),i=d.jquery?d:c.children(d),j;h.length||(h=c.children()),i.length||(i=c.parent().find(d)),i.length||(i=a(d)),a.extend(this,{click:function(d,i){var k=h.eq(d),l=!c.data("tabs");typeof d=="string"&&d.replace("#","")&&(k=h.filter("[href*=\""+d.replace("#","")+"\"]"),d=Math.max(h.index(k),0));if(e.rotate){var m=h.length-1;if(d<0)return f.click(m,i);if(d>m)return f.click(0,i)}if(!k.length){if(j>=0)return f;d=e.initialIndex,k=h.eq(d)}if(d===j)return f;i=i||a.Event(),i.type="onBeforeClick",g.trigger(i,[d]);if(!i.isDefaultPrevented()){var n=l?e.initialEffect&&e.effect||"default":e.effect;b[n].call(f,d,function(){j=d,i.type="onClick",g.trigger(i,[d])}),h.removeClass(e.current),k.addClass(e.current);return f}},getConf:function(){return e},getTabs:function(){return h},getPanes:function(){return i},getCurrentPane:function(){return i.eq(j)},getCurrentTab:function(){return h.eq(j)},getIndex:function(){return j},next:function(){return f.click(j+1)},prev:function(){return f.click(j-1)},destroy:function(){h.off(e.event).removeClass(e.current),i.find("a[href^=\"#\"]").off("click.T");return f}}),a.each("onBeforeClick,onClick".split(","),function(b,c){a.isFunction(e[c])&&a(f).on(c,e[c]),f[c]=function(b){b&&a(f).on(c,b);return f}}),e.history&&a.fn.history&&(a.tools.history.init(h),e.event="history"),h.each(function(b){a(this).on(e.event,function(a){f.click(b,a);return a.preventDefault()})}),i.find("a[href^=\"#\"]").on("click.T",function(b){f.click(a(this).attr("href"),b)}),location.hash&&e.tabs=="a"&&c.find("[href=\""+location.hash+"\"]").length?f.click(location.hash):(e.initialIndex===0||e.initialIndex>0)&&f.click(e.initialIndex)}a.fn.tabs=function(b,c){var d=this.data("tabs");d&&(d.destroy(),this.removeData("tabs")),a.isFunction(c)&&(c={onBeforeClick:c}),c=a.extend({},a.tools.tabs.conf,c),this.each(function(){d=new e(a(this),b,c),a(this).data("tabs",d)});return c.api?d:this}})(jQuery);(function(a){var b;b=a.tools.tabs.slideshow={conf:{next:".forward",prev:".backward",disabledClass:"disabled",autoplay:!1,autopause:!0,interval:3e3,clickable:!0,api:!1}};function c(b,c){var d=this,e=b.add(this),f=b.data("tabs"),g,h=!0;function i(c){var d=a(c);return d.length<2?d:b.parent().find(c)}var j=i(c.next).click(function(){f.next()}),k=i(c.prev).click(function(){f.prev()});function l(){g=setTimeout(function(){f.next()},c.interval)}a.extend(d,{getTabs:function(){return f},getConf:function(){return c},play:function(){if(g)return d;var b=a.Event("onBeforePlay");e.trigger(b);if(b.isDefaultPrevented())return d;h=!1,e.trigger("onPlay"),e.on("onClick",l),l();return d},pause:function(){if(!g)return d;var b=a.Event("onBeforePause");e.trigger(b);if(b.isDefaultPrevented())return d;g=clearTimeout(g),e.trigger("onPause"),e.off("onClick",l);return d},resume:function(){h||d.play()},stop:function(){d.pause(),h=!0}}),a.each("onBeforePlay,onPlay,onBeforePause,onPause".split(","),function(b,e){a.isFunction(c[e])&&a(d).on(e,c[e]),d[e]=function(b){return a(d).on(e,b)}}),c.autopause&&f.getTabs().add(j).add(k).add(f.getPanes()).hover(d.pause,d.resume),c.autoplay&&d.play(),c.clickable&&f.getPanes().click(function(){f.next()});if(!f.getConf().rotate){var m=c.disabledClass;f.getIndex()||k.addClass(m),f.onBeforeClick(function(a,b){k.toggleClass(m,!b),j.toggleClass(m,b==f.getTabs().length-1)})}}a.fn.slideshow=function(d){var e=this.data("slideshow");if(e)return e;d=a.extend({},b.conf,d),this.each(function(){e=new c(a(this),d),a(this).data("slideshow",e)});return d.api?e:this}})(jQuery);


	$(function () {
     	$("#slider0").responsiveSlides({
	        auto: true,
	        pager: false,
	        nav: true,
	        timeout: 5000,  
	        speed: 800,
	        namespace: "callbacks",
	        before: function () {
	        },
	        after: function () {
	        }
	    });
	});

	$(document).foundation({
	  orbit: {
	    animation: 'slide',
	    timer_speed: 1000,
	    // pause_on_hover: false,
	    animation_speed: 500,
	    navigation_arrows: true,
	    bullets: true,
	    // next_on_click: true
	  }
	});
</script>
@stop
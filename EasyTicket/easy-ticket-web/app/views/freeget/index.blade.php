@extends('../master')
@section('content')
{{HTML::style('../../../../css/hover/component.css')}}
{{HTML::style('../../../../countdown/css/jquery.countdown.css')}}
{{HTML::style('../../../../rev-slider/revslider.css')}}
 <style type="text/css">
 	.tabs-a > div {
    	margin-top: 10px;
    	background: transparent;
	}
	.tabs-a > div {
	    position: relative;
	    padding: 0px;
	    border: none;
	    min-height: 300px;
	}
	.tabs-a > ul{
		border:1px solid #ddd;
		background: #F7F7F7 !important;
	}
	.tabs-a > ul li.current {
	    border-left: 1px solid #ddd !important;
	    border-right: 1px solid #ddd !important;
	    background: white !important;
	    color: #000;
	    cursor: default;
	}
	.tabs-a > ul li.current:first-child {
		border-left: none !important;
	}
	.tabs-a > ul li.current:last-child {
		border-right: none !important;
		float: right;
	}
	.tabs-a > ul li {
	    position: relative;
	    float: left;
	    min-width: 96px;
	    height: 39px;
	    width: 199px;
	    border: none;
	    background: #F7F7F7 !important;
	    color: black;
	    font-size: 15px;
	    line-height: 37px;
	    text-align: center;
	    cursor: pointer;
	}
	
	.item_title{
		margin-top:-35px !important;
	}
	.free_get_items{
		height:304px !important;
	}
	.free_get_items .grid_four_frame{
		height:290px !important;
	}
	.category_title img{
		width: 32px;
		margin-right: 10px;
	}
	.bannercontainer {
    	width:100%;
    	position:relative;
    	padding:0;
    }
     
    .banner{
    	width:100%;
    	position:relative;
    }
	
 </style>
<div class="row">
	<div id="slider-rev-container">
        <div id="slider-rev">
            <ul>
            	@if($response['advertisement'])
			        @foreach($response['advertisement'] as $row)
			            <li data-transition="fade" data-saveperformance="on"  data-title="Men Collection">
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
                <!-- <li data-transition="fade" data-saveperformance="on"  data-title="Men Collection">
                    <img src="images/homeslider/transparent.png" alt="background" style="background-color:#f1f6f7">
                    <div class="tp-caption lfb ltb" data-x="100" data-y="50" data-speed="1200" data-start="600" data-easing="Expo.easeOut">
                        <a href="#"><img src="images/homeslider/slide2_1.png" alt="slide2_1" width="300" height="600"></a>
                    </div>
                    <div class="tp-caption rev-title skewfromleft stt" data-x="610" data-y="150" data-speed="800" data-start="900" 
                    data-easing="Power3.easeIn" data-endspeed="300">New</div>
                     <div class="tp-caption rev-subtitle skewfromleft stt" data-x="610" data-y="205" data-speed="800" data-start="900" 
                     data-easing="Power3.easeIn" data-endspeed="300">Men Collection</div>
                    <div class="tp-caption rev-text sfl stl" data-x="610" data-y="275" data-speed="800" data-start="1300" data-easing="Power3.easeIn"
                    data-endspeed="300">Integer ultrices ipsum id justo ultrices sed blandit felis ultricies. Duis semper tristique congue. 
                    Pellentesque id orcised ligula elementum tempor ut non nulla. Aenean lectus nulla, rutrum in tincidunt non, vestibulum a arcu...</div>
                    
                    <div class="tp-caption sfb stb" data-x="610" data-y="395" data-speed="1200" data-start="1800" data-easing="Power3.easeIn" data-endspeed="300">
                        <a href="#" class="btn btn-custom-2">Shop Now</a>
                    </div>
                </li>
                
                <li data-transition="fade" data-saveperformance="on"  data-title="Sale">
                    <img src="images/homeslider/transparent.png" alt="background" style="background-color:#f1f6f7">
                    <div class="tp-caption rev-title skewfromleft stt" data-x="70" data-y="150" data-speed="800" data-start="900" 
                    data-easing="Power3.easeIn" data-endspeed="300">Sale</div>
                     <div class="tp-caption rev-subtitle skewfromleft stt" data-x="70" data-y="205" data-speed="800" data-start="900" 
                     data-easing="Power3.easeIn" data-endspeed="300">It’s here & You’re invited</div>
                    <div class="tp-caption rev-text sfl stl" data-x="70" data-y="275" data-speed="800" data-start="1300" data-easing="Power3.easeIn"
                    data-endspeed="300">Nulla facilisi. Sed ultrices augue in neque aliquet eleifend volutpat augue viverra. Integer ante tellus, gravida ut vestibulum ut,
                    varius idleo. Aliquam congue augue nec neque egestas bibendum. 
                    Phasellus dapibus tellus quis metus...</div>
                    
                    <div class="tp-caption sfb stb" data-x="70" data-y="395" data-speed="1200" data-start="1800" data-easing="Power3.easeIn" data-endspeed="300">
                        <a href="#" class="btn btn-custom-2">Shop Now</a>
                    </div>
                    <div class="tp-caption lfb ltb" data-x="570" data-y="50" data-speed="1200" data-start="600" data-easing="Expo.easeOut">
                        <a href="#"><img src="images/homeslider/slide3_1.png" alt="slide3_1" width="560" height="600"></a>
                    </div>
                </li>
                
                <li data-transition="fade" data-saveperformance="on"  data-title="LookBook">
                    <img src="images/homeslider/transparent.png" alt="background" style="background-color:#f1f6f7">
                    <div class="tp-caption lfb ltb" data-x="120" data-y="50" data-speed="1200" data-start="600" data-easing="Expo.easeOut">
                        <a href="#"><img src="images/homeslider/slide1_1.png" alt="slide1_1" width="330" height="600"></a>
                    </div>
                    <div class="tp-caption rev-title skewfromleft stt" data-x="610" data-y="150" data-speed="800" data-start="900" 
                    data-easing="Power3.easeIn" data-endspeed="300">Lookbook</div>
                     <div class="tp-caption rev-subtitle skewfromleft stt" data-x="610" data-y="205" data-speed="800" data-start="900" 
                     data-easing="Power3.easeIn" data-endspeed="300">Spring-Summer-2012</div>
                    <div class="tp-caption rev-text sfl stl" data-x="610" data-y="275" data-speed="800" data-start="1300" data-easing="Power3.easeIn"
                    data-endspeed="300">Praesent arcu urna, cursus sit amet condimentum id, dapibusa mauris. Sed ante massa pellentesque luctus, magna sed ultricies 
                    molestie, felis tortor pellentesque ligula, in sagittis neque turpis eget augue.</div>
                    
                    <div class="tp-caption sfb stb" data-x="610" data-y="395" data-speed="1200" data-start="1800" data-easing="Power3.easeIn" data-endspeed="300">
                        <a href="#" class="btn btn-custom-2">Shop Now</a>
                    </div>
                </li> -->
                
            </ul>
        </div><!-- End #slider-rev -->
    </div><!-- End #slider-rev-container -->
</div>
<!-- <div class="row slideframe">
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
</div> -->
<div class="row">
	<div class="large-12 medium-12 column nopadding">
		
		<div>
			<?php $i=1; ?>
			@if(count($response['freeget'])> 0)
				@foreach($response['freeget'] as $row)
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
											<del class="oldprice">@if($row->oldprice == 0) {{$row->oldprice}} က်ပ္ @endif</del> <div class="like"> ၄၅၀ </div>
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
</div>
<div class="clear">&nbsp;</div>
<div class="clear">&nbsp;</div>

{{HTML::script('../../../js/hover/modernizr.custom.js')}}
{{HTML::script('../../../js/hover/toucheffects.js')}}
<!-- {{HTML::script('../../js/responsiveslides.min.js')}} -->

{{HTML::script('../../../../countdown/js/jquery.countdown.js')}}
{{HTML::script('../../../../countdown/js/script.js')}}
{{HTML::script('../../../../rev-slider/jquery.themepunch.revolution.min.js')}}
{{HTML::script('../../../../rev-slider/jquery.themepunch.tools.min.js')}}
<script type="text/javascript">	
	$(function() {

        // Slider Revolution
        jQuery('#slider-rev').revolution({
            delay:5000,
            startwidth:1170,
            startheight:400,
            onHoverStop:"true",
            hideThumbs:250,
            navigationHAlign:"center",
            navigationVAlign:"bottom",
            navigationHOffset:0,
            navigationVOffset:20,
            soloArrowLeftHalign:"left",
            soloArrowLeftValign:"center",
            soloArrowLeftHOffset:0,
            soloArrowLeftVOffset:0,
            soloArrowRightHalign:"right",
            soloArrowRightValign:"center",
            soloArrowRightHOffset:0,
            soloArrowRightVOffset:0,
            touchenabled:"on",
            stopAtSlide:-1,
            stopAfterLoops:-1,
            dottedOverlay:"none",
            fullWidth:"on",
            spinned:"spinner2",
            shadow:0,
            hideTimerBar: "on",
            // navigationStyle:"preview3"
          });
    
    });


	// $(function () {
 	//     	$("#slider0").responsiveSlides({
	//         auto: true,
	//         pager: false,
	//         nav: true,
	//         timeout: 5000,  
	//         speed: 800,
	//         namespace: "callbacks",
	//         before: function () {
	//         },
	//         after: function () {
	//         }
	//     });
	// });

	// $(document).foundation({
	//   orbit: {
	//     animation: 'slide',
	//     timer_speed: 1000,
	//     // pause_on_hover: false,
	//     animation_speed: 500,
	//     navigation_arrows: true,
	//     bullets: true,
	//     // next_on_click: true
	//   }
	// });
</script>
@stop
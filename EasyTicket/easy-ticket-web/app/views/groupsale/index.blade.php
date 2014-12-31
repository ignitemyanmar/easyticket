@extends('../master')
@section('content')
{{HTML::style('../../../../css/hover/component.css')}}
{{HTML::style('../../../../css/pages/group_sale.css')}}
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

		<div>
			<?php $i=1; ?>
			@if(count($response['group_sale'])> 0)
				@foreach($response['group_sale'] as $row)
					@if($i==1)
					<div class="row">
					@endif
						<div class="large-2-half columns free_get_items left">
							<div class="grid_four_frame" style="overflow: inherit">
								<a href="/itemdetail/{{$row->id}}">
									<div class="image_frames">
										<img class="center_photo" src="../../../../itemphoto/php/files/medium/{{$row->image}}">
									</div>
									<div class="item_description">
										<div class="timesale"><div class="countdown" data-seconds="{{$row['end_date']}}" style="position: absolute;top:162px;z-index: 9;font-size: 18px;color: white !important;"></div></div>
										<div class="timesale"><div class="group_sale">{{$row['number_person']}} person sale!</div></div>
										<div class="item_title"><span>{{$row->name_mm}}</span></div>
										<div class="captain">Captain : {{$row->user_name}}</div>
										<div class="price_sold" style="margin-top:0px;"><div class="sold_item left" data-dropdown="{{$row->id}}"><span>{{$row['number_person'] - $row['left_person']}}</span> person included</div></div>
										<div class="price_sold"><div class="item_price l_price">Price: {{$row->price}} Ks</div><div class="like"> ၄၅၀ </div></div>
										
												<ul id="{{$row->id}}" data-dropdown-content class="f-dropdown group_person">
												@foreach($row->group_person as $rows)
													<li><img width="24px" height="24px" src="../../../../userphoto/php/files/crop/{{$rows->image}}"> {{$rows->name}}@if($row->user_id != $rows->user_id)<strong class="remove_user" data-id="{{$rows->group_id.'/'.$rows->user_id}}"> - </strong>@endif<strong>{{$rows->qty}}</strong></li>
												@endforeach
												</ul>
											
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
{{HTML::script('../../js/responsiveslides.min.js')}}


{{HTML::script('../../../../countdown/js/jquery.countdown.js')}}
{{HTML::script('../../../../countdown/js/script.js')}}
<script type="text/javascript">	
	$(".remove_user").click(function(){
		if (confirm("Are you sure to remove this user?") == true) {
			var id = $(this).data("id");
    		$.ajax({
				type: "GET",
				url: "/remove_group_user/"+id,
				data: null,
				}).done(function( response ) {
					if(response["status"] === 1){
						window.location.href= response["redirect_uri"];
					}
				});
        }
		return false;
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
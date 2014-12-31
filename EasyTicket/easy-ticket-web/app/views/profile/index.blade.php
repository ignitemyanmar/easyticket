@extends('../master')
@section('content')
{{HTML::style('../css/hover/component.css')}}
{{HTML::style('../../src/select2.css')}}
{{HTML::style('../../css/upload.css')}}
{{HTML::style('../../css/smkstyle.css')}}
{{HTML::style('../../css/croppic.css')}}
<script type="text/javascript">
	var croppicContaineroutputOptions = {
			uploadUrl:'userphoto/php/img_save_to_file.php',
			cropUrl:'userphoto/php/img_crop_to_file.php', 
			outputUrlId:'cropOutput',
			modal:false,
			loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> '
		}
	var cropContaineroutput = new Croppic('cropContaineroutput', croppicContaineroutputOptions);
</script>
<style type="text/css">
	#cropContaineroutput {
			width: 220px !important;
			height: 220px !important;
			margin: auto !important;
			border:1px solid #ccc !important;
			position:relative !important; /* or fixed or absolute */
	}
	.user_photo{
			width: 220px;
			height: 220px;
			margin: auto;
			border:1px solid #ccc;
			position:relative; 
	}
	.user_photo img{
			width: 220px;
			height: 220px;
	}
	#cropContaineroutput img{
		max-width: none !important;
	}
	#to_edit .button{
		height: 50px !important;
		margin: 0 !important;
	}

</style>
<div class="row">

	<div class="large-3 columns profile_nav panel" id="to_show" style="display:block;">
		<div class="user_photo"><img src="../../userphoto/php/files/crop/{{$me->image}}" style="max-width:100%;"></div>
		<ul style="list-style: none;margin-left: 0px;margin-top: 20px;">
			<li><h1>{{$me->name}}</h1></li>
			<li>Address: {{$me->address}}</li>
			<li>Email: {{$me->email}}</li>
			<li>Phone: {{$me->phone}}</li>
			<li>
				<hr>
				<a href="../../{{$me->id}}" class="button right" id="btn_edit">Edit Profile</a>
			</li>
		</ul>
	</div>

	<div class="large-3 columns profile_nav panel" id="to_edit" style="display:none;">
	<form action="../profile/{{$me->id}}/edit" method="POST">
		<input type="hidden" id="hid_access_token" name="access_token" value="@if(Auth::check()){{Auth::user()->access_token}} @endif">
		<div id="cropContaineroutput" style="background-image:url('../../userphoto/php/files/crop/{{$me->image}}')"></div>
		<ul style="list-style: none;margin-left: 0px;margin-top: 20px;">
			<li><h1><input type="text" name="name" value="{{$me->name}}"></h1></li>
			<li>Address: <input type="text" name="address" value="{{$me->address}}"></li>
			<li>Email: <input type="text" name="email" value="{{$me->email}}"></li>
			<li>Phone: <input type="text" name="phone" value="{{$me->phone}}"></li>
			<!-- <li>Current Password: <input type="password" name="current_password" value="" required></li> -->
			<li>New Password: <input type="password" name="new_password" value=""></li>
			<li>Confirm Password: <input type="password" name="confirm_password" value=""></li>
			<li>
				<hr>
				<a href="" class="button left" id="btn_cancel">Cancel</a>
				<input type="submit" value="Save Profile" class="button right" style="padding: 5px 22px;">
			</li>
		</ul>
	</form>
	</div>
	
	<div class="large-9 columns" style="padding-left:20px;">
		<dl class="tabs" data-tab style="margin-bottom: 0px !important;"> 
			<dd class="active"><a style="padding: 8px 42px;" href="#panel2-1">Recent Orders</a></dd> 
			<dd><a style="padding: 8px 42px;" href="#panel2-2">My Orders</a></dd> 
			<dd><a style="padding: 8px 42px;" href="#panel2-3">My Wish Items</a></dd> 
			<dd><a style="padding: 8px 42px;" href="#panel2-4">Payments</a></dd> 
		</dl> 
		<div class="row tabs-content panel"> 
			<div class="content active" id="panel2-1" style="width:100%;">
				<table style="width:100%;"> 
				<thead> 
				<tr> 
					<th>Order No.</th> 
					<th>Shipping Address</th> 
					<th>Date</th> 
					<th>Amount</th> 
					<th>Status</th> 
				</tr> 
				</thead> 
				<tbody> 
				@foreach($order as $rows)
				<tr> 
				<td>{{$rows->ref_id}}</td> 
				<td>
					<b>Address:</b> {{$rows->shipping->address}}<br>
					<b>Township:</b> {{$rows->shipping->township}}<br>
					<b>City:</b> {{$rows->shipping->city}}<br>
					<b>Phone:</b> {{$rows->shipping->phone}}</td> 
				<td>{{date('d-m-Y', strtotime($rows->created_at))}}</td> 
				<td>{{$rows->total}}</td> 
				<td>
					@if($rows->status == 0)
						<span class="label">Pendding</span>
					@else
						<span class="success label">Success</span>
					@endif
				</td>
				</tr> 
				@endforeach
				</tbody> 
				</table>
			</div>
			<div class="content" id="panel2-2" style="width:100%;"> 
				<table style="width:100%;"> 
				<thead> 
				<tr> 
					<th>Order No.</th> 
					<th>Shipping Address</th> 
					<th>Date</th> 
					<th>Amount</th> 
					<th>Status</th> 
				</tr> 
				</thead> 
				<tbody> 
				@foreach($order as $rows)
				<tr> 
				<td>{{$rows->ref_id}}</td> 
				<td>
					<b>Address:</b> {{$rows->shipping->address}}<br>
					<b>Township:</b> {{$rows->shipping->township}}<br>
					<b>City:</b> {{$rows->shipping->city}}<br>
					<b>Phone:</b> {{$rows->shipping->phone}}</td> 
				<td>{{date('d-m-Y',strtotime($rows->created_at))}}</td> 
				<td>{{$rows->total}}</td> 
				<td>
					@if($rows->status == 0)
						<span class="label">Pendding</span>
					@else
						<span class="success label">Success</span>
					@endif
				</td>
				</tr> 
				@endforeach
				</tbody> 
				</table>
			</div> 
			<div class="large-12 columns content" id="panel2-3" style="width:100%;">
				<div class="row">
				@foreach($wish_items as $rows)
					<div class="large-3 columns nopadding left" style="padding:3px;">
						<div class="large-4 columns left nopadding hot_item" style="height:289px;">
							<div class="hot_item_price">@if(count($rows->item->itemdetail) > 0) {{$rows->item->itemdetail[0]->price}} Ks @endif</div>
							<div class="preview_photo">
								<a href="../../../../itemdetail/{{$rows->item->id}}"><img class="center_photo" src="../../../../itemphoto/php/files/medium/{{$rows->item->image}}" /></a>
							</div>
							<div class="row nopadding">
								<div class="large-5 column nopadding">
									<div class="list_item_rating">၄.၅</div>
								</div>
								<div class="large-7 column nopadding list_item_sold">ေရာင္းျပီး (၃၆၇)</div>
							</div>
							<div class="row">
								<div class="column nopadding list_item_title"><a href="../../../../itemdetail/{{$rows->item->id}}">{{$rows->item->name}}.</a></div>
								<div class="column nopadding"><a href="../../wish/{{$rows->user_id}}/{{$rows->item_id}}/remove?access_token=@if(Auth::check()){{Auth::user()->access_token}}@endif" class="label right" style="margin:5px;">Remove</a></div> 
							</div>
						</div>
					</div>
				@endforeach									
				</div>	
			</div> 
			<div class="content" id="panel2-4" style="width:100%;">
				<table style="width:100%;"> 
				<thead> 
				<tr> 
					<th>Order No.</th> 
					<th>Description</th> 
					<th>Payment Date</th> 
					<th>Amount</th> 
					<th>Payment Type</th> 
				</tr> 
				</thead> 
				<tbody> 
				@foreach($payment as $rows)
				<tr> 
				<td>{{$rows->ref_id}}</td> 
				<td>{{$rows->description}}</td> 
				<td>{{date('d-m-Y',strtotime($rows->created_at))}}</td> 
				<td>{{$rows->amount}}</td> 
				<td><span class="label">{{$rows->payment_type}}</span></td>
				</tr> 
				@endforeach
				</tbody> 
				</table>
			</div> 
		</div>
	</div>

</div>
<br>
{{HTML::script('../../../js/hover/modernizr.custom.js')}}
{{HTML::script('../../../js/hover/toucheffects.js')}}
{{HTML::script('../../../src/select2.min.js')}}
{{HTML::script('../../../src/jquery-ui.js')}}
{{HTML::script('../../../src/jquery.fileupload.js')}}
{{HTML::script('../../../js/croppic.js')}}

<script type="text/javascript">
	$('#btn_edit').click(function(event){
		event.preventDefault();
		$('#to_show').hide();
		$('#to_edit').show();
	});
	$('#btn_cancel').click(function(event){
		event.preventDefault();
		$('#to_show').show();
		$('#to_edit').hide();
	});
	
	var croppicContaineroutputOptions = {
				uploadUrl:'../../userphoto/php/img_save_to_file.php',
				cropUrl:'../../userphoto/php/img_crop_to_file.php', 
				outputUrl:'../../userphoto/php/',
				modal:false,
				loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> '
		}
	var cropContaineroutput = new Croppic('cropContaineroutput', croppicContaineroutputOptions);
</script>


@stop
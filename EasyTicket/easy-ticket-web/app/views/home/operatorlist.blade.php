@extends('master')
@section('content')
<style type="text/css">
	.operatorlist{margin-bottom: 8px !important;height: 150px; background: #27A9E3;position: relative;}
	.operatorlist a{position:absolute;padding-top:40px; left: 0; right: 0;text-align: center; margin:auto;
		font-family: "Dosis" !important;
		color: #fff;font-size: 35px; font-weight: bold;
	}
	.operatorlist span{font-size: 18px;color:#eee;}
	@media only screen and (max-width: 40em){
		.small-12{margin-bottom: 2px;}
		.operatorlist{margin-bottom: 2px;}
	}
</style>
	<div class="clear"></div>
	@if($response)
		@foreach($response as $i=>$row)
			@if($i%2==0)
			<div class="row">
			@endif
				<div class="large-6 medium-6 small-12 columns nopadding">
					<div class="operatorlist">
						<a href="/?agopt_id={{$row->id}}"> 
							{{$row->name}}
							<br>
							<span>Express</span>
						</a>
					</div>
				</div>
			@if($i%2==1)
			</div>
			@endif
		@endforeach
	@endif
@stop
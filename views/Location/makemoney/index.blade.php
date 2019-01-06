<div class="overlay-mobile"></div>
<div class="page-ctv-revenue profile-page manager-profile-page">
	<div class="container">
		<div class="manager-profile d-flex flex-row">
			<!-- End modal modal-vertical-middle upload avata -->
			<form class="form-upload-avata">
				<a class="upload-img-avata" href="#" title="" data-toggle="modal" data-target="#modal-upload-avata">
					<img class="rounded-circle" src="{{$client->avatar?$client->avatar:''}}" alt="{{$client->full_name?$client->full_name:''}}">
				</a>
				<img id="avatar_source" crossOrigin="Anonymous" src="{{$client->avatar?$client->avatar:''}}" style="visibility: hidden; position: absolute;">
			</form>
			<!-- end form-upload-avata -->
			<div class="info-name">
				<h2 class="text-uppercase  text-truncate">{{$client->full_name?$client->full_name:''}}</h2>
				<p class="h6"  style="color:#2e3c52;">{{trans('Location'.DS.'makemoney.slogan')}}</p>
				@if( ($client->hasRole('cong_tac_vien') > 0) || ($client->hasRole('tong_dai_ly') > 0) || ($client->hasRole('ceo') > 0) )
				<p class="text-uppercase h5" style="color:#5b89ab;">
					<strong>
					{{trans('Location'.DS.'makemoney.report')}}
					@if($client->hasRole('cong_tac_vien') > 0)
					{{trans('Location'.DS.'makemoney.ctv')}}
					@endif
					@if($client->hasRole('tong_dai_ly') > 0)
					{{trans('Location'.DS.'makemoney.daily')}}
					@endif
					@if($client->hasRole('ceo') > 0)
					{{trans('CEO')}}
					@endif
					</strong>
				</p>
				@endif
			</div>
			<!-- end info name -->
		</div>
		<!-- end manager-profile -->
		<div class="page-ctv-revenue-wrapper d-md-flex align-items-md-stretch form-edit-profile-manager">
			
				@include('Location.makemoney.nav')
				
				@if($module=='register')
					@include('Location.makemoney.register')
				@endif

				@if($module=='register_pending')
					@include('Location.makemoney.register_pending')
				@endif

				@if($module=='ctv_is_lock')
					@include('Location.makemoney.ctv_is_lock')
				@endif
				
				@if($module=='daily_is_lock')
					@include('Location.makemoney.daily_is_lock')
				@endif
				
				<!-- CTV -->

				@if($module=='ctv_makemoney')
					@include('Location.makemoney.ctv_makemoney')
				@endif

				@if($module=='ctv_location')
					@include('Location.makemoney.ctv_location')
				@endif

				@if($module=='ctv_revenue')
					@include('Location.makemoney.ctv_revenue')
				@endif

				<!-- Daily -->

				@if($module=='daily_makemoney')
					@include('Location.makemoney.daily_makemoney')
				@endif

				@if($module=='daily_location')
					@include('Location.makemoney.daily_location')
				@endif

				@if($module=='daily_location_pending')
					@include('Location.makemoney.daily_location_pending')
				@endif

				@if($module=='daily_revenue')
					@include('Location.makemoney.daily_revenue')
				@endif

				@if($module=='daily_ctv')
					@include('Location.makemoney.daily_ctv')
				@endif

				@if($module=='daily_ctv_pending')
					@include('Location.makemoney.daily_ctv_pending')
				@endif


				<!-- CEO -->

				@if($module=='ceo_makemoney')
					@include('Location.makemoney.ceo_makemoney')
				@endif

				@if($module=='ceo_revenue')
					@include('Location.makemoney.daily_revenue')
				@endif

				@if($module=='ceo_location')
					@include('Location.makemoney.ceo_location')
				@endif

				@if($module=='ceo_ctv')
					@include('Location.makemoney.ceo_ctv')
				@endif

				@if($module=='ceo_daily')
					@include('Location.makemoney.ceo_daily')
				@endif

				<!-- Info Page -->


				<!-- Info Page -->
				@if($module=='info_ctv')
					@include('Location.makemoney.info_ctv')
				@endif

				@if($module=='info_location')
					@include('Location.makemoney.info_location')
				@endif

				@if($module=='grant_ctv')
					@include('Location.makemoney.grant_ctv')
				@endif

				@if($module=='info_daily')
					@include('Location.makemoney.info_daily')
				@endif
				<!-- end content edit profile manager -->
		</div>
		<!-- end form-edit-profile-manager -->
	</div>
</div>
<!-- end profile-page  -->

<style type="text/css" media="screen">
	.page-ctv-revenue-wrapper{
		background-color: #fff;
	}
	@media (max-width: 576px){
		.manager-profile-page:before{
			height: 240px !important;
		}
	}

	@media (min-width: 768px){
		.nav-manager-profile{
			border-right: 1px solid #e0e8ed;
		}
	}
	.text_no{
		font-style: italic;
		color: #5b89ab;
	}
</style>
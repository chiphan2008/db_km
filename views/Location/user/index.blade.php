<div class="overlay-mobile"></div>
<div class="profile-page manager-profile-page">
	<div class="container">
		<div class="manager-profile d-flex flex-row">
			<!-- modal modal-vertical-middle upload avata -->
			<div class="modal-upload-avata modal fade " id="modal-upload-avata" tabindex="-1" role="dialog" aria-labelledby="modal-upload-avata">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<!-- modal-header -->
						<div class="modal-header d-flex  align-items-start">
							<!-- Chọn vị trí và khích thước ảnh -->
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<i class="ixon-close-round"></i>
							</button>
						</div>
						<div class="modal-body">
							<div class="image-editor">
								<div class="cropit-preview"></div>
								<div class="slider-wrapper d-flex align-items-center justify-content-center pt-3">
									<i class="ion-image"></i>
									<input type="range" class="cropit-image-zoom-input">
									<i class="ion-image large-image"></i>
								</div>
								<input type="file" class="cropit-image-input" type="hidden">
							</div>
						</div>
						<!-- modal-body -->
						<div class="modal-footer d-flex align-items-star ">
							<a class="btn-upload-avata btn btn-secondary" href="" title="">{{trans('global.choose_image')}}</a>
							<a class="btn btn-secondary export" href="" title="">{{trans('global.save')}}</a>
							<a class="btn-close btn btn-secondary " href="" title="">{{trans('global.cancel')}}</a>
						</div>
					</div>
				</div>
			</div>
			<!-- End modal modal-vertical-middle upload avata -->
			<form class="form-upload-avata">
				<a class="upload-img-avata" href="#" title="" data-toggle="modal" data-target="#modal-upload-avata">
					<img class="rounded-circle" src="{{$user->avatar?$user->avatar:''}}" alt="{{$user->full_name?$user->full_name:''}}">

					@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
					<i class="icon-camera"></i>
					<input id="input-file-upload" class=" d-none" type="file">
					@endif
				</a>
				<img id="avatar_source" crossOrigin="Anonymous" src="{{$user->avatar?$user->avatar:''}}" style="visibility: hidden; position: absolute;">
			</form>
			<!-- end form-upload-avata -->
			<div class="info-name">
				<h2 class="text-uppercase  text-truncate">{{$user->full_name?$user->full_name:''}}</h2>
				<span class="addres  text-truncates">{{$user->address?$user->address:''}}&nbsp;</span>
				<span class="follow  text-truncate">
					{{-- <i class="icon-eye"></i>200 {{mb_strtolower(trans('Location'.DS.'user.follow'))}} --}}
					&nbsp;
				</span>
			</div>
			<!-- end info name -->
		</div>
		<!-- end manager-profile -->
		<div class="form-edit-profile-manager">
			<div class="d-md-flex align-items-md-stretch">
				@include('Location.user.nav')
				<!-- end nav-manager-profile -->
				@if($module=='view')
					@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
						@include('Location.user.profile_edit')
					@else
						@include('Location.user.profile_view')
					@endif
				@endif

				@if($module=='management-location')
					@if($total==0)
						@include('Location.user.empty_location')
					@else
						@include('Location.user.management_location')
					@endif
				@endif

				@if($module=='check-in')
				@include('Location.user.check_in')
				@endif

				@if($module=='like-location')
				@include('Location.user.like_location')
				@endif

				@if($module=='like')
				@include('Location.user.like')
				@endif

				@if($module=='change-password')
				@include('Location.user.change_password')
				@endif

				@if($module=='wallet')
				@include('Location.user.wallet')
				@endif

				@if($module=='edit-location')
					@include('Location.user.edit_location')
				@endif

				@if($module=='collection')
					@include('Location.user.collection')
				@endif

				@if($module=='create-discount')
					@include('Location.user.create_discount')
				@endif

				@if($module=='list-discount')
					@include('Location.user.list_discount')
				@endif

				@if($module=='update-discount')
					@include('Location.user.update_discount_new')
				@endif

				@if($module=='revenue-invite')
					@include('Location.user.revenue_invite')
				@endif
				
				@if($module=='register-invite')
					@include('Location.user.register_invite')
				@endif

				@if($module=='create-ads')
					@include('Location.user.create_ads')
				@endif

				@if($module=='publish-ads')
					@include('Location.user.publish_ads')
				@endif

				@if($module=='list-ads')
					@include('Location.user.list_ads')
				@endif

				@if($module=='update-ads')
					@include('Location.user.update_ads')
				@endif

				@if($module=='change-owner')
					@include('Location.user.change_owner')
				@endif
				<!-- end content edit profile manager -->
			</div>
		</div>
		<!-- end form-edit-profile-manager -->
	</div>
</div>
<!-- end profile-page  -->


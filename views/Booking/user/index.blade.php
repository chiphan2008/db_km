<div class="overlay-mobile"></div>
<div class="profile-page manager-profile-page">
	<div class="container">
		<div class="manager-profile d-flex flex-row">
			<!-- modal upload avata -->
			<div class="modal-upload-avata modal fade " id="modal-upload-avata" tabindex="-1" role="dialog" aria-labelledby="modal-upload-avata">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<!-- modal-header -->
						<div class="modal-header d-flex  align-items-start">
							<!-- Chọn vị trí và khích thước ảnh -->
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<i class="ion-close-round"></i>
							</button>
						</div>
						<div class="modal-body">
							<div class="image-editor">
								<div class="cropit-preview"></div>
								<div class="slider-wrapper d-flex align-items-center justify-content-center">
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
			<!-- End modal upload avata -->
			<form class="form-upload-avata">
				<a class="upload-img-avata" href="#" title="" data-toggle="modal" data-target="#modal-upload-avata">
					<img class="rounded-circle" src="{{$user->avatar?$user->avatar:''}}" alt="{{$user->full_name?$user->full_name:''}}">
					@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
					<i class="icon-camera"></i>
					<input id="input-file-upload" class=" d-none" type="file">
					@endif
				</a>
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
				<!-- end content edit profile manager -->
			</div>
		</div>
		<!-- end form-edit-profile-manager -->
	</div>
</div>
<!-- end profile-page  -->
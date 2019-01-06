<div class="overlay-mobile"></div>
<div class="profile-page manager-profile-page">
	<div class="container">
		<div class="manager-profile d-flex flex-row">
			<!-- modal modal-vertical-middle upload avata -->
			<div class="modal-upload-avata modal modal-vertical-middle fade " id="modal-upload-avata" tabindex="-1" role="dialog" aria-labelledby="modal-upload-avata">
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
			<!-- End modal modal-vertical-middle upload avata -->
			<form class="form-upload-avata">
				<a class="upload-img-avata" href="#" title="" data-toggle="modal" data-target="#modal-upload-avata">
					<img class="rounded-circle" src="{{$user->avatar?$user->avatar:''}}" alt="{{$user->full_name?$user->full_name:''}}">
					@if(Auth::guard('web_client')->user())
					<i class="icon-camera"></i>
					<input id="input-file-upload" class=" d-none" type="file">
					@endif
				</a>
			</form>
			<!-- end form-upload-avata -->
			<div class="info-name">
				<h2 class="text-uppercase">{{$user->full_name?$user->full_name:''}}</h2>
				<span class="address">{{$user->address?$user->address:''}}</span>
				<span class="follow"><i class="icon-eye"></i>200 {{mb_strtolower(trans('Location'.DS.'user.follow'))}}</span>
			</div>
			<!-- end info name -->
		</div>
		<!-- end manager-profile -->
		<div class="form-edit-profile-manager">
			<div class="d-md-flex align-items-md-stretch">
				<div class="nav-manager-profile">
					<ul class="list-unstyled">
						<li class="hidden-sm-down"><a href="{{url('/')}}/user/{{$user->id}}/" title="{{trans('Location'.DS.'user.profile')}}">{{trans('Location'.DS.'user.profile')}}</a></li>
						<li class="hidden-sm-down"><a href="{{url('/')}}/user/{{$user->id}}/check-in" title="{{trans('Location'.DS.'user.check_in')}}">{{trans('Location'.DS.'user.check_in')}}</a></li>
						<li class="hidden-sm-down"><a href="{{url('/')}}/user/{{$user->id}}/like-location" title="{{trans('Location'.DS.'user.like_location')}}">{{trans('Location'.DS.'user.like_location')}}</a></li>
						<li  class="hidden-sm-down"><a href="{{url('/')}}/user/{{$user->id}}/collection" title="{{trans('Location'.DS.'user.collection')}}">{{trans('Location'.DS.'user.collection')}}</a></li>
						<li class="hidden-sm-down"><a href="{{url('/')}}/user/{{$user->id}}/like" title="{{trans('Location'.DS.'user.like')}}">{{trans('Location'.DS.'user.like')}}</a></li>
						@if(Auth::guard('web_client')->user())
						<li class="active"><a href="{{url('/')}}/user/{{$user->id}}/friend" title="{{trans('Location'.DS.'user.follow_friend')}}">{{trans('Location'.DS.'user.follow_friend')}}</a></li>
						<li class="hidden-sm-down"><a href="{{url('/')}}/user/{{$user->id}}/change-password" title="{{trans('Location'.DS.'user.change_password')}}">{{trans('Location'.DS.'user.change_password')}} </a></li>
						@endif
					</ul>
					<ul class="sub-nav-manager-profile nav-manager-profile list-unstyled">
						<li><a href="{{url('/')}}/user/{{$user->id}}/check-in" title="{{trans('Location'.DS.'user.check_in')}}">{{trans('Location'.DS.'user.check_in')}}</a></li>
						<li><a href="{{url('/')}}/user/{{$user->id}}/like-location" title="{{trans('Location'.DS.'user.like_location')}}">{{trans('Location'.DS.'user.like_location')}}</a></li>
						<li><a href="{{url('/')}}/user/{{$user->id}}/collection" title="{{trans('Location'.DS.'user.collection')}}">{{trans('Location'.DS.'user.collection')}}</a></li>
						<li><a href="{{url('/')}}/user/{{$user->id}}/like" title="{{trans('Location'.DS.'user.like')}}">{{trans('Location'.DS.'user.like')}}</a></li>
						@if(Auth::guard('web_client')->user())
						<li><a href="{{url('/')}}/user/{{$user->id}}/friend" title="{{trans('Location'.DS.'user.follow_friend')}}">{{trans('Location'.DS.'user.follow_friend')}}</a></li>
						<li><a href="{{url('/')}}/user/{{$user->id}}/change-password" title="{{trans('Location'.DS.'user.change_password')}}">{{trans('Location'.DS.'user.change_password')}} </a></li>
						@endif
					</ul>
					<!-- end sub-nav-manager-profile -->
					<div class="total-payment w-100 text-center  hidden-sm-down">
              <p>
                  {{trans('Location'.DS.'user.you_have')}}
              </p>
              <h3>
                  {{$user->coin?money_number($user->coin):0}} K
              </h3>
              <a class="btn btn-primary" title="{{trans('Location'.DS.'user.view_detail')}}" href="{{url('/')}}/user/{{$user->id}}/wallet">{{trans('Location'.DS.'user.view_detail')}}</a>
          </div>
				</div>
				<!-- end nav-manager-profile -->
				<div class="content-profile-friend content-edit-profile-manager">
					<h3>{{mb_strtoupper(trans('Location'.DS.'user.follow_friend'))}} (45)</h3>
					<div class="form-edit-profile">
						<div class="row">
							<div class="col-xl-4 col-md-6">
								<div class="profile-friend-item media align-items-center">
									<img class="d-flex mr-3 rounded-circle" src="https://via.placeholder.com/56x56" alt="Generic placeholder image">
									<div class="media-body">
									<h6 class="mt-0 mb-1">Lawrence Sims</h6>
									<p class="mb-1">
										Lawrence Sims
									</p>
									</div>
								</div>
								<!-- end rofile-friend-item -->
							</div>
							<div class="col-xl-4 col-md-6">
								<div class="profile-friend-item media align-items-center">
									<img class="d-flex mr-3 rounded-circle" src="https://via.placeholder.com/56x56" alt="Generic placeholder image">
									<div class="media-body">
									<h6 class="mt-0 mb-1">Lawrence Sims</h6>
									<p class="mb-1">
										Lawrence Sims
									</p>
									</div>
								</div>
								<!-- end rofile-friend-item -->
							</div>
						</div>
					</div>
				</div>
				<!-- end content edit profile manager -->
			</div>
		</div>
		<!-- end form-edit-profile-manager -->
	</div>
</div>
<!-- end profile-page  -->
@section('JS')
	@if(Auth::guard('web_client')->user())
		@include('Location.user.crop_image')
	@endif
@endsection
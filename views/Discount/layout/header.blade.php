<header id="header" class="wrapper-header">
	<div class="header-topbar">
			<div class="container d-xl-flex justify-content-xl-between">
					<div class="d-flex justify-content-between mb-3 hidden-md-up">
              <div class="content">
                <div id="trigger" class="icon-toggle hidden-md-up menu-trigger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                    <!-- end icon-toggle -->
              </div>
              <!-- end icon-toggle -->
              <div class="logo-header-mobile logo-header hidden-md-up">
                <a class="" href="{{url('/')}}" title="">
                  <!-- <img src="/frontend/assets/img/logo/logo-mobi.svg" alt=""> -->
                  <!-- <img src="/frontend/assets/img/logo/logo-mobi-red.svg" alt=""> -->
                  <img width="131" src="/frontend/assets/img/logo/Logo.svg" alt="">
              	</a>
              </div>
              <!-- end logo header -->
              <div class="btn-login-mobile hidden-md-up">
              	@if (Auth::guard('web_client')->user())
              		<a href="" title=""><img width="28" height="28" class="img-circle" src="{{Auth::guard('web_client')->user()->avatar}}" alt=""></a>
              	@else
                  <a href="" title=""><img width="28" height="28"   src="/frontend/assets/img/icon/ic-login.svg" alt=""></a>
                @endif
              </div>
              <!-- end btn-login -->
          </div>
          <div class="logo-header hidden-sm-down">
            <a href="{{url('/')}}" title="">
              <img src="/frontend/assets/img/logo/Logo.svg" alt="">
          	</a>
          </div>
					<!-- end logo header -->
					<div class="search-header form-search">
						<form onsubmit="return false;">
							<input style ="padding-left:35px;height:auto !important" id="project" class="form-control w-100" onkeyup="suggestSearch(this)" type="" name="keyword" value="" placeholder="{{trans('global.keyword')}} …">
							<button type="button" class="btn btn-primary hidden-sm-down" id="search" onclick="searchKeyword()">{{trans('global.search')}}</button>
						</form>
					</div>
					<!-- end form search -->
					<!-- <div class="btn-login-mobile hidden-md-up">
							<a href="" title=""><img src="/frontend/assets/img/icon/ic-login.svg" alt=""></a>
					</div> -->
					<div class="header-right d-flex justify-content-between">
							<div class="lang-header select-lang hidden-sm-down">
									<select class="custom-select-style-1 custom-select-style custom-select"  onchange="changeLanguage(this)">
											<option {{\App::getLocale() == 'vn' ? 'selected' : ''}} value="vn">VIE</option>
											<option {{\App::getLocale() == 'en' ? 'selected' : ''}} value="en">ENG</option>
									</select>
							</div>
							<!-- end lang-header -->
							
							<div class="notification-header  hidden-sm-down ">
							@if (Auth::guard('web_client')->user())
								<a class="icon-notifi {{$count_notifications>0?'notifi-active':''}}" href="#" title="">
										<i class="icon-notification-white"></i>
										<i class="icon-circle"></i>
								</a>
							@else
								<a class="icon-notifi" href="#" title="">
										<i class="icon-notification-white"></i>
										<i class="icon-circle"></i>
								</a>
							@endif

							<div class="notification-content">
									<ul class="nav" role="tablist">
											<li class="nav-item">
													<a class="nav-link active" data-toggle="tab" href="#news" role="tab">{{trans('global.news')}} ({{$count_news}})</a>
											</li>
											@if (Auth::guard('web_client')->user())
											<li class="nav-item">
													<a class="nav-link" data-toggle="tab" href="#notification" role="tab" onclick="readNotifi({{Auth::guard('web_client')->user()->id}})">{{trans('global.notification')}} ({{$count_notifications}})</a>
											</li>
											@endif
									</ul>
									<!-- Tab panes -->
									<div class="tab-content">
											<div class="tab-pane active" id="news" role="tabpanel">
													<ul class="list-unstyled">
														@if($news)
														@foreach($news as $new)
														<li class="item-notification">
															<a class="d-flex align-items-center" href="{{$new->link?$new->link:'#'}}" title="">
																	@if($new->image)
																	<div class="img">
																			<img class="rounded-circle" src="{{$new->image}}" alt="" width="46" height="46">
																	</div>
																	@endif
																	<div class="content ">
																			<div class="title">
																					@if($new->template_notifi_id>0)
																							{!! trans($new->content,json_decode($new->data,true)) !!}
																					@else
																							{!! $new->content !!}
																					@endif
																			</div>
																			<span class="time">{{date('d-m-Y H:i:s',strtotime($new->updated_at))}}</span>
																	</div>
															</a>
														</li>
														@endforeach
														@endif
													</ul>
											</div>
											@if (Auth::guard('web_client')->user())
											<div class="tab-pane" id="notification" role="tabpanel">
													<ul class="list-unstyled">
														@if($notifications)
														@foreach($notifications as $notification)
														<li class="item-notification">
															<a class="d-flex align-items-center" href="{{$notification->link?$notification->link:'#'}}" title="">
																	@if($notification->image)
																	<div class="img">
																			<img class="rounded-circle" src="{{$notification->image}}" alt="" width="46" height="46">
																	</div>
																	@endif
																	<div class="content ">
																			<div class="title">
																					@if($notification->template_notifi_id>0)
																							{!! trans($notification->content,json_decode($notification->data,true)) !!}
																					@else
																							{!! $notification->content !!}
																					@endif
																			</div>
																			<span class="time">{{date('d-m-Y H:i:s',strtotime($notification->updated_at))}}</span>
																	</div>
															</a>
														</li>
														@endforeach
														@endif
													</ul>
											</div>
											@endif
									</div>
							</div>
								<!-- end notification content -->
							</div>
							
							<div class="signin-url">
								@if (Auth::guard('web_client')->guest())
								<div class="">
									<div class="hidden-sm-down">
										<img src="/frontend/assets/img/icon/ic-login.png" alt="">
										<a  class="" href="" title="" data-toggle="modal" data-target="#modal-signup">{{mb_strtoupper(trans('global.register'))}}   </a>
										<span> |</span>
										<a  class="" href="" title="" data-toggle="modal" data-target="#modal-signin">{{mb_strtoupper(trans('global.login'))}}</a>
									</div>
									<ul class="group-profile-sub group-nav list-unstyled p-3">
										<li>
											<a href="" title="" data-toggle="modal" data-target="#modal-signup">{{mb_strtoupper(trans('global.register'))}}   </a>
										</li>
										<li>
											<a href="" title="" data-toggle="modal" data-target="#modal-signin">{{mb_strtoupper(trans('global.login'))}}</a>
										</li>
									</ul>
								</div>
								@else
								<div class="box-profile">
									<a class="my-profile hidden-sm-down" href="#">
										<img class="img-circle" src="{{ Auth::guard('web_client')->user()->avatar }}" alt="Ảnh đại diện" style="max-width: 40px;max-height: 40px;">
										<span style="max-width: 110px;">{{ Auth::guard('web_client')->user()->full_name }}</span>
										<i class="ion-ios-arrow-down"></i>
									</a>
									<ul class="group-profile-sub group-nav list-unstyled p-3">
										<li>
											<a href ="{{url('/')}}/user/{{Auth::guard('web_client')->user()->id}}" title="">{{trans('global.profile')}}</a>
										</li>
										<li><a href="{{url('/')}}/user/{{Auth::guard('web_client')->user()->id}}/management-location" title="{{trans('Location'.DS.'user.management_location')}}">{{trans('Location'.DS.'user.management_location')}}</a></li>
										<li><a href="{{url('/')}}/user/{{Auth::guard('web_client')->user()->id}}/check-in" title="{{trans('Location'.DS.'user.check_in')}}">{{trans('Location'.DS.'user.check_in')}}</a></li>
										<li><a href="{{url('/')}}/user/{{Auth::guard('web_client')->user()->id}}/like-location" title="{{trans('Location'.DS.'user.like_location')}}">{{trans('Location'.DS.'user.like_location')}}</a></li>
										<li><a href="{{url('/')}}/user/{{Auth::guard('web_client')->user()->id}}/collection" title="{{trans('Location'.DS.'user.collection')}}">{{trans('Location'.DS.'user.collection')}}</a></li>
										<li>
											<a href="/client_logout" title="">{{trans('global.logout')}}</a>
										</li>
									</ul>
								</div>
								@endif
							</div>
							<!-- end  sigin url -->
					</div>
			</div>
	</div>
	<!-- end header topbar -->
	<div class="wrap-main-navigation-desktop clearfix hidden-sm-down">
      <div class="container">
          <div class="content-menu d-flex justify-content-between">
              @include('Location.layout.menu')
              <div class="box-app">
                  <a href="" title=""><img src="/frontend/assets/img/icon/Appstore.png" alt=""></a>
                  <a href="" title=""><img src="/frontend/assets/img/icon/Googleplay.png" alt=""></a>
              </div>
              <!--end  box-app -->
          </div>
      </div>
  </div>
  <!-- end  menu desktop -->
</header>
<div class="notification-mobile">
	<a class="come-back" href="" title="">
			<i class="icon-left-1"></i> {{trans('Location'.DS.'layout.comeback')}}
	</a>
	<div class="notification-content">
			<ul class="nav" role="tablist">
					<li class="nav-item">
							<a class="nav-link active" data-toggle="tab" href="#news_mobile" role="tab">{{trans('Location'.DS.'layout.news')}} ({{$count_news}})</a>
					</li>
					@if (Auth::guard('web_client')->user())
					<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#notification_mobile" role="tab" onclick="readNotifi({{Auth::guard('web_client')->user()->id}})">{{trans('Location'.DS.'layout.notifycation')}} ({{$count_notifications}})</a>
					</li>
					@endif
			</ul>
			<!-- Tab panes -->
			<div class="tab-content">
					<div class="tab-pane active" id="news_mobile" role="tabpanel">
							<ul class="list-unstyled">
								@if($news)
								@foreach($news as $new)
								<li class="item-notification">
									<a class="d-flex align-items-center" href="{{$new->link?$new->link:'#'}}" title="">
											@if($new->image)
											<div class="img">
													<img class="rounded-circle" src="{{$new->image}}" alt="" width="46" height="46">
											</div>
											@endif
											<div class="content ">
													<div class="title">
															@if($new->template_notifi_id>0)
																	{!! trans($new->content,json_decode($new->data,true)) !!}
															@else
																	{!! $new->content !!}
															@endif
													</div>
													<span class="time">{{date('d-m-Y H:i:s',strtotime($new->updated_at))}}</span>
											</div>
									</a>
								</li>
								@endforeach
								@endif
							</ul>
					</div>
					@if (Auth::guard('web_client')->user())
					<div class="tab-pane" id="notification_mobile" role="tabpanel">
							<ul class="list-unstyled">
								@if($notifications)
								@foreach($notifications as $notification)
								<li class="item-notification">
									<a class="d-flex align-items-center" href="{{$notification->link?$notification->link:'#'}}" title="">
											@if($notification->image)
											<div class="img">
													<img class="rounded-circle" src="{{$notification->image}}" alt="" width="46" height="46">
											</div>
											@endif
											<div class="content ">
													<div class="title">
															@if($notification->template_notifi_id>0)
																	{!! trans($notification->content,json_decode($notification->data,true)) !!}
															@else
																	{!! $notification->content !!}
															@endif
													</div>
													<span class="time">{{date('d-m-Y H:i:s',strtotime($notification->updated_at))}}</span>
											</div>
									</a>
								</li>
								@endforeach
								@endif
							</ul>
					</div>
					@endif
			</div>
	</div>
</div>
<!-- end notification-mobile -->
<script>
	function searchKeyword(){
		var keyword = $("input[name='keyword']").val();
		// alert('{{url('/')}}'+'/search?q='+encodeURIComponentss(keyword));
		//return;
		if(keyword.length){
			window.location = '{{url('/')}}'+'/search?q='+encodeURIComponentss(keyword);
		}
	}
	function readNotifi(id_user){
		$.ajax({
			url : '{{url("/")}}/readNotifi',
			type : 'POST',
			data : {
				_token 	: $("[name='_token']").prop('content'),
				id_user	: id_user				
			}

		})
	}

	$(function(){

		$("input[name='keyword']").keypress(function (e) {
			if (e.which == 13 || event.keyCode == 13) {
				searchKeyword();
			}
		});
	})
</script>

<style>
	.item-notification p{
		margin-top: 0 !important;
		margin-bottom: 0 !important;
	}
	.ui-autocomplete-loading{
	  background: url(/img_default/loading.gif) no-repeat right center !important;
    background-color: #fff !important;
    background-size: 30px !important;
	}
	@media screen and (min-width: 768px){
		.ui-autocomplete-loading{
			background-position-x: calc(100% - 120px) !important;
		}
	}
	@media screen and (max-width: 768px){
		.notification-content .nav-link.active{
			background: #d0021b;
			color: #fff;
		}
	}
</style>

<header id="header" class="wrapper-header">
	<div class="header-topbar">
			<div class="container d-xl-flex justify-content-xl-between">
					<div class="d-flex justify-content-between mb-3 hidden-md-up">
              <!-- end icon-toggle -->
              <div class="logo-header-mobile logo-header hidden-md-up">
                <a class="" href="{{url('/')}}" title="">
                  <!-- <img src="/frontend/assets/img/logo/logo-mobi.svg" alt=""> -->
                  <!-- <img src="/frontend/assets/img/logo/logo-mobi-red.svg" alt=""> -->
                  <img width="131" src="/frontend/assets/img/logo/Logo.svg" alt="">
              	</a>
              </div>
              <!-- end logo header -->
              
          </div>
          <div class="logo-header hidden-sm-down">
            <a href="{{url('/')}}" title="">
              <img src="/frontend/assets/img/logo/Logo.svg" alt="">
          	</a>
          </div>
					<!-- end logo header -->
					<div class="search-header form-search">
						<form onsubmit="return false;">
							<!-- <span id="text_over_search" style="{{app('request')->input('q')?'display: none;':''}}">
								{{trans('global.search')}} 
								<span class="text_over_choose" data-type="product">{{mb_strtolower(trans('global.product'))}}</span>, 
								<span class="text_over_choose" data-type="service">{{mb_strtolower(trans('global.service'))}}</span>, 
								<span class="text_over_choose" data-type="location">{{mb_strtolower(trans('global.locations'))}}</span>
							</span> -->
							
							<input style ="padding-left:35px;height:auto !important" id="project" class="form-control w-100" onkeyup="suggestSearch(this)" type="" name="q" value="{{app('request')->input('q')}}" placeholder="{{trans('global.search')}} {{mb_strtolower(trans('global.product'))}}, {{mb_strtolower(trans('global.service'))}}, {{mb_strtolower(trans('global.locations'))}}" data-history="0" autocomplete="off">

							<button type="button" class="btn btn-primary hidden-sm-down" id="search" onclick="searchKeyword()">{{trans('global.search')}}</button>
							<!-- <select name="type" class="" id="type_search" onchange="searchKeyword()">
								<option value="product" {{app('request')->input('type')=='product'?'selected':''}}>{{trans('global.product')}}</option>
								<option value="service" {{app('request')->input('type')=='service'?'selected':''}}>{{trans('global.service')}}</option>
								<option value="location" {{app('request')->input('type')=='location'?'selected':''}}>{{trans('global.locations')}}</option>
							</select> -->	
						</form>
					</div>
					<!-- end form search -->
					<!-- <div class="btn-login-mobile hidden-md-up">
							<a href="" title=""><img src="/frontend/assets/img/icon/ic-login.svg" alt=""></a>
					</div> -->
					<div class="header-right d-flex justify-content-between">
							<div class="lang-header select-lang">
									<select class="custom-select-style-1 custom-select-style custom-select"  onchange="changeLanguage(this)">
											<option {{\App::getLocale() == 'vn' ? 'selected' : ''}} value="vn">VIE</option>
											<option {{\App::getLocale() == 'en' ? 'selected' : ''}} value="en">ENG</option>
									</select>
							</div>
							<!-- end lang-header -->
							<div class="btn-login-mobile hidden-md-up ml-3">
              	@if (Auth::guard('web_client')->user())
              		<a><img width="28" height="28" class="img-circle" src="{{Auth::guard('web_client')->user()->avatar}}" alt=""></a>
              	@else
                  <a><img width="28" height="28"   src="/frontend/assets/img/icon/ic-login.svg" alt=""></a>
                @endif
              </div>
              <!-- end btn-login -->
							
							<div id="notification-header-dropdown" class="dropdown-notify notification-header  hidden-sm-down new-notification">
								@if (Auth::guard('web_client')->user())
									<a class="icon-notifi" id="dLabel" role="button" onclick="showDropdown(this)"  data-target="#">
											<i class="icon-notification-white"></i>
											<i class="icon-circle" @if($count_notifications) style="display: block" @else style="display: none" @endif></i>

									</a>
								@else
									<a class="icon-notifi" id="dLabel" role="button" onclick="showDropdown(this)" data-target="#">
											<i class="icon-notification-white"></i>

											<i class="icon-circle" @if($count_news) style="display: block" @else style="display: none" @endif></i>

									</a>
								@endif
								<div id="notification-header-tabs" class="dropdown-menu-notify dropdown-menu" style="display: none;">
										<ul class="nav nav-tabs-notifi" role="tablist">
												<li class="nav-item">
														<a class="nav-link active" data-toggle="tab" href="#news" role="tab">{{trans('global.news')}} ({{$count_news}})</a>
												</li>
												@if (Auth::guard('web_client')->user())
												<li class="nav-item">
														<a class="nav-link" id="notify_tab" data-toggle="tab" href="#notification" role="tab">{{trans('global.notification')}} ({{$count_notifications}})</a>
												</li>
												@endif
										</ul> <!-- end ul tablist -->
										<!-- Tab panes -->
										<div class="tab-content">
												<div class="tab-pane active" id="news" role="tabpanel">
														<ul class="list-unstyled" id="pusher-list-news">
															@if($news)
															@foreach($news as $new)
															<li class="item-notification">
																@if($new->link)
																<a class="d-flex align-items-center" href="{{$new->link?$new->link:''}}" title="">
																@else
																<span class="w-100 d-flex align-items-center">
																@endif
																		@if($new->image)
																		<div class="img">
																				<img class="rounded-circle" src="{{$new->image}}" alt="" width="46" height="46">
																		</div>
																		@endif
																		<div class="content ">
																				@if($new->template_notifi_id>0)
																				<div class="title" data-toggle="tooltip" data-placement="bottom" data-html="true">
																						{!! trans($new->content,json_decode($new->data,true)) !!}
																				@else
																				<div class="title" data-toggle="tooltip" data-placement="bottom" data-html="true">
																						{!! substr($new->content,0,150) !!}
																				@endif
																				</div>
																				<span class="time">{{date('d-m-Y H:i:s',strtotime($new->updated_at))}}</span>
																		</div>
																@if($new->link)
																</a>
																@else
																</span>
																@endif
															</li>
															@endforeach
															@endif
														</ul>
												</div>
												@if (Auth::guard('web_client')->user())
												<div class="tab-pane" id="notification" role="tabpanel">
														<ul class="list-unstyled" id="pusher-list-noti-user" style="height:310px;overflow-y: auto;">
															@if($notifications)
															@foreach($notifications as $notification)
															<li class="item-notification  {{$notification->read_at?'':'not_read'}}">
																@if($notification->link)
																<a class="d-flex align-items-center" href="{{$notification->link?$notification->link:''}}" title="">
																@else
																<span class="w-100 d-flex align-items-center">
																@endif
																		@if($notification->image)
																		<div class="img">
																				<img class="rounded-circle" src="{{$notification->image}}" alt="" width="46" height="46">
																		</div>
																		@endif
																		<div class="content ">
																				@if($notification->template_notifi_id>0)
																				<div class="title" data-toggle="tooltip" data-placement="bottom" data-html="true">
																						{!! trans($notification->content,json_decode($notification->data,true)) !!}
																				@else
																				<div class="title"  data-toggle="tooltip" data-placement="bottom" data-html="true" title="">
																						{!! $notification->content !!}
																				@endif
																				</div>
																				<span class="time">{{date('d-m-Y H:i:s',strtotime($notification->updated_at))}}</span>
																		</div>
																@if($notification->link)
																</a>
																@else
																</span>
																@endif
															</li>
															@endforeach
															@endif
														</ul>
												</div>
												@endif
										</div>
								</div> <!-- end div dropdown -->
								<!-- end notification content -->
							</div>
							
							<div class="signin-url">
								@if (Auth::guard('web_client')->guest())
								<div class="">
									<div class="hidden-sm-down">
										<!-- <img src="/frontend/assets/img/icon/ic-login.png" alt=""> -->
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
								<div class="box-profile" id="box-my-profile">
									<a class="my-profile hidden-sm-down" href="#">
										<img class="img-circle" src="{{ Auth::guard('web_client')->user()->avatar }}" alt="{{trans('global.avatar')}}" style="max-width: 40px;max-height: 40px;">
										<span style="max-width: 110px;">{{ Auth::guard('web_client')->user()->full_name }}</span>
										<i class="ion-ios-arrow-down"></i>
									</a>
									<ul class="group-profile-sub group-nav list-unstyled p-3">
										<li><a href="https://play.google.com/store/apps/details?id=com.kingmap_app">{{trans('global.download_app')}}</a></li>
										{{--<li><a href="{{url('/')}}/user/{{Auth::guard('web_client')->user()->id}}/revenue-invite" title="{{trans('global.make_money')}}">{{trans('global.make_money')}}</a></li>--}}
										<li>
											<a href ="{{url('/')}}/user/{{Auth::guard('web_client')->user()->id}}" title="">{{trans('global.profile')}}</a>
										</li>
										<li><a href="{{url('/')}}/user/{{Auth::guard('web_client')->user()->id}}/management-location" title="{{trans('Location'.DS.'user.management_location')}}">{{trans('Location'.DS.'user.management_location')}}</a></li>
										<li>
											<a href ="{{url('/')}}/user/{{Auth::guard('web_client')->user()->id}}/change-owner" title="{{trans('Admin'.DS.'content.owner_change')}}">{{trans('Admin'.DS.'content.owner_change')}}</a>
										</li>
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
	<div class="wrap-main-navigation-desktop clearfix">
      <div class="container">
          <div class="content-menu d-flex justify-content-start">
              @include('Location.layout.menu_new')
              <!-- <div class="box-app">
                  <a href="" title=""><img src="/frontend/assets/img/icon/Appstore.png" alt=""></a>
                  <a href="" title=""><img src="/frontend/assets/img/icon/Googleplay.png" alt=""></a>
              </div> -->
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
	<ul class="notification-content-mobile list-unstyled pt-2">
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
									@if($new->link)
									<a class="d-flex align-items-center" href="{{$new->link?$new->link:'#'}}" title="">
									@else
									<div class="d-flex align-items-center">
									@endif	

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
									@if($new->link)
									</a>
									@else
									</div>
									@endif
								</li>
								@endforeach
								@endif
							</ul>
					</div>
					@if (Auth::guard('web_client')->user())
					<div class="tab-pane" id="notification_mobile" role="tabpanel">
							<ul class="list-unstyled notification-content-mobile">
								@if($notifications)
								@foreach($notifications as $notification)
								<li class="item-notification {{$notification->read_at?'':'not_read'}}">
									@if($notification->link)
									<a class="d-flex align-items-center" href="{{$notification->link?$notification->link:'#'}}" title="">
									@else
									<div class="d-flex align-items-center">
									@endif	
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
									@if($notification->link)
									</a>
									@else
									</div>
									@endif	
								</li>
								@endforeach
								@endif
							</ul>
					</div>
					@endif
			</div>
	</div>
	</ul>
</div>
<style type="text/css" media="screen">
	.item-notification a.btn{
		margin-right: 10px;
		width: 45%;
	}
</style>
<!-- end notification-mobile -->
<script>
	var _token = $("meta[name='_token']").prop('content');
	var current_page = 1;
	var max_page = true;
	var stop = true;
	var current_url = window.location;
	var currentLocation = window.sessionStorage.getItem('currentLocation')?window.sessionStorage.getItem('currentLocation'):false;
	// $(function(){
	// 	$(".text_over_choose").on("click",function(){
	// 		$("#text_over_search").hide();
	// 		$("#project").focus();
	// 		var type = $(this).data('type');
	// 		$("#type_search").val(type);
	// 		// alert($("#type_search").val());
	// 	})
	// 	$("#text_over_search").on("click",function(e){
	// 		if(!$(e.target).is('.text_over_choose')){
	// 			$("#text_over_search").hide();
	// 			$("#project").focus();
	// 		}
	// 	})
	// 	$(document).on("click",function(e){
 //        if(!$(e.target).is('#text_over_search,.text_over_choose,.ui-autocomplete-input,.ui-autocomplete *')){
 //            $(this).find('#text_over_search').show();
 //            $("#project").css({color:"#ffffff"});
 //        }else{
 //        	$("#project").css({color:"#757f8b"});
 //        }
 	//  })
	// });
	function loadContentSearch(loadNew, loadMore){
		// window.event.preventDefault();
		if(loadNew !== false){
			var loadNew = loadNew || true;
		}
		var loadMore = loadMore || false;
		if(max_page){
			if(loadMore){
				current_page++;
			}
			// var query = $("#form_search").serializeArray();
			var query = [];
			var _choosen = [];
			if(currentLocation){
				query.push({name:'currentLocation',value:currentLocation});
			}
			if(current_page){
				query.push({name:'page',value:current_page});
			}else{
				query.push({name:'page',value:1});
			}
			query.push({name:'_token',value:_token});

			if($("input#project").val()){
				query.push({name:'q',value:$("input#project").val()});
				_choosen.push({name:'q',value:$("input#project").val()});
			}

			if($("#category_search").val()){
				query.push({name:'category',value:$("#category_search").val()});
				_choosen.push({name:'category',value:$("#category_search option:selected").text()});
			}

			if($("#category_item_search option:selected").length){
				var arr_category_item = [];
				var arr_category_item_text = [];
				$("#category_item_search option:selected").each(function(i){
					arr_category_item.push($(this).val());
					arr_category_item_text.push($(this).text());
				})
				if(arr_category_item.length){
					var str = arr_category_item.join(',');
					query.push({name:'category_item',value:str});
					_choosen.push({name:'category_item',value:arr_category_item_text});
				}
			}

			// if($("#district_search").val()){
			// 	query.push({name:'district',value:$("#district_search").val()});
			// 	//_choosen.push({name:'district',value:$("#district_search option:selected").text()});
			// }

			// if($("#city_search").val()){
			// 	query.push({name:'city',value:$("#city_search").val()});
			// 	//_choosen.push({name:'city',value:$("#city_search option:selected").text()});
			// }

			if($(".country_search").val()){
				query.push({name:'country',value:$("#country_search").val()});
				//_choosen.push({name:'country',value:$("#country_search option:selected").text()});
			}
			if(currentLocation){
				//alert(123);
				$.ajax({
					url: 'https://maps.googleapis.com/maps/api/geocode/json?latlng='+currentLocation+"&language=vi&key=AIzaSyCCCOoPlN2D-mfrYEMWkz-eN7MZnOsnZ44",
					type: 'GET',
					success:function(res){
						if(res.results.length){
							_choosen.push({name:'location',value:res.results[0].formatted_address});
						}
						$("#choosen_value").html(create_badge(_choosen));
						$('.mobile_badge').html(create_badge(_choosen));
						$(".mobile_badge .badge").css("max-width",($(window).width()-90)+'px');
					}
				});
			}else{
					$("#choosen_value").html(create_badge(_choosen));
					$('.mobile_badge').html(create_badge(_choosen));
					$(".mobile_badge .badge").css("max-width",($(window).width()-90)+'px');
			}
			
			$("#choosen_value").html(create_badge(_choosen));
			$('.mobile_badge').html(create_badge(_choosen));
			$(".mobile_badge .badge").css("max-width",($(window).width()-90)+'px');
			var url = [];
			query.forEach(function(obj,key){
				if(obj.value != "" && obj['name'] !='_token' && obj['name'] !='page'){
					if(obj['name']=='q'){
						url.push({name:'q',value:$("input#project").val()});
					}else{
						url.push(obj)
					}
					
				}
			});
			url = $.param(url)
			url = '{{url('/')}}/search2'+'?'+url;
			window.location = url;
		}
	}

	function changeCurrentLocation(callback){
		var key = '';
		if($("#country_search").val()){
			key += $("#country_search option:selected").text();
		}
		if($("#city_search").val()){
			key +=' ' + $("#city_search option:selected").text();
		}
		if($("#district_search").val()){
			key +=' ' + $("#district_search option:selected").text();
		}
		$.ajax({
			url : 'https://maps.googleapis.com/maps/api/geocode/json?address='+key+'&key=AIzaSyCCCOoPlN2D-mfrYEMWkz-eN7MZnOsnZ44',
			type: 'GET',
			success: function(res){
				if(res.results.length && res.results[0] && res.results[0]['geometry'] && res.results[0]['geometry']['location']){
					lat = res.results[0]['geometry']['location']['lat'];
					lng = res.results[0]['geometry']['location']['lng'];
					currentLocation = lat+','+lng;
				}
				//callback();
			}
		})
	}
	function searchKeyword(){
		var keyword = $("input[name='q']").val();
		// alert('{{url('/')}}'+'/search?q='+encodeURIComponentss(keyword));
		//return;
		// if(keyword.length){
		// 	var type = $("#type_search").val();
		// 	window.location = '{{url('/')}}'+'/search2?q='+encodeURIComponentss(keyword)+'&type='+type;
		// }
		if(keyword.length){
			window.location = '{{url('/')}}'+'/search2?q='+encodeURIComponentss(keyword);
		}
		
	}
	function readNotifi(id_user){
		$.ajax({
			url : '{{url("/")}}/readNotifi',
			type : 'POST',
			data : {
				_token 	: $("[name='_token']").prop('content'),
				id_user	: id_user				
			},
			beforeSend:function(){
				$('#notification-header-tabs').closest('.dropdown').addClass('show');
				$('#notification-header-tabs').closest('.dropdown').addClass('dontClose');
			}
		});
	}

	function showDropdown(obj){
    $(obj).parent().find('.dropdown-menu-notify').toggle('fast')
  }

	$(function(){
		$('body').on("click",function(e){
      var container = $(".dropdown-notify");

      if (!container.is(e.target) && container.has(e.target).length === 0) 
      {
          container.find('.dropdown-menu-notify').hide();
      }

      var container_plus_location = $("#plus_location");

            if (!container_plus_location.is(e.target) && container_plus_location.has(e.target).length === 0)
            {
                $(".group-action").hide("slide", {direction : "right"},500);
            }

            var container_social_popup = $("#social-footer-button");

            if (!container_social_popup.is(e.target) && container_social_popup.has(e.target).length === 0)
            {
                $('#social-footer-popup').hide('500');
            }

            var container_map_select_nav = $(".select-custom-li .select-nav");
            var container_map_select = $(".select-custom-li .select");

            if (!container_map_select_nav.is(e.target) && container_map_select_nav.has(e.target).length === 0 &&
                !container_map_select.is(e.target) && container_map_select.has(e.target).length === 0)
            {
                container_map_select.css('display', 'none');
                container_map_select_nav.removeClass('active');
            }
            if($(window).width()<=991) {
                var container_footer_popup = $(".btn-show-footer");

                if (!container_footer_popup.is(e.target) && container_footer_popup.has(e.target).length === 0) {
                    $('.info-footer-sub').hide('300');
                }
            }
    })
        $('#box-my-profile').on("click",function(e){
            var container = $(".dropdown-notify");

            if (!container.is(e.target) && container.has(e.target).length === 0)
            {
                container.find('.dropdown-menu-notify').hide();
            }
        })
		// $('#notification-header-tabs').on('blur','#notify_tab',function(){
		$('#notify_tab').on("click",function(){
				$('#notification-header-tabs').closest('.dropdown').addClass('dontClose');
    });

		$('[data-toggle="tooltip"]').tooltip();

		$("header input[name='q']").keypress(function (e) {
			if (e.which == 13 || e.keyCode == 13) {
				// $("#type_search").focus();
				if($(this).val().length)
					searchKeyword();
			}
		});

		$(".category_item_search").select2({
			dropdown:true
		});
	})

	// function changeCountrySearch(obj){
	// 		var country = $(obj).val();
	// 		$(".country_search").val(country);
	// 		current_page = 1;
	// 		max_page = true;
	// 		loadCitySearch(country);
	// 		// alert('change country')
	// 	}

	// 	function loadCitySearch(country){
	// 		$.ajax({
	// 			url : '/search/loadCity',
	// 			type: 'POST',
	// 			data: {
	// 				_token: _token,
	// 				country: country
	// 			},
	// 			success: function(response){
	// 				$(".city_search").html(response);
	// 				//$(".city_search").trigger("change");
	// 			}
	// 		})
	// 	}

	// 	function changeCitySearch(obj){
	// 		var city = $(obj).val();
	// 		$(".city_search").val(city);
	// 		current_page = 1;
	// 		max_page = true;
	// 		loadDistrictSearch(city);
	// 		changeCurrentLocation(loadContentSearch);
	// 	}

	// 	function loadDistrictSearch(city){
	// 		$.ajax({
	// 			url : '/search/loadDistrict',
	// 			type: 'POST',
	// 			data: {
	// 				_token: _token,
	// 				city: city
	// 			},
	// 			success: function(response){
	// 				$(".district_search").html(response);
	// 				//$(".district_search").trigger("change");
	// 			}
	// 		})
	// 	}

	// 	function changeDistrictSearch(obj){
	// 		var district = $(obj).val();
	// 		$(".district_search").val(district);
	// 		current_page = 1;
	// 		max_page = true;
	// 		// alert('change district')
	// 		changeCurrentLocation(loadContentSearch);
	// 	}

	// 	function changeCategorySearch(obj){
	// 		var category = $(obj).val();
	// 		$(".category_search").val(category);
	// 		current_page = 1;
	// 		max_page = true;
	// 		loadCategoryItemSearch(category);
	// 		// alert('change category')
	// 	}

	// 	function loadCategoryItemSearch(category){
	// 		$.ajax({
	// 			url : '/search/loadCategoryItemNew',
	// 			type: 'POST',
	// 			data: {
	// 				_token: _token,
	// 				category: category
	// 			},
	// 			success: function(response){
	// 				$(".category_item_search").html(response);
	// 			}
	// 		})
	// 	}

	// 	function chooseCategoryItem(obj){
	// 		$(".category_item_search").val($(obj).val());
	// 		// $(".category_item_search").trigger("change");
	// 		current_page = 1;
	// 		max_page = true;
	// 		// alert('change item')
	// 	}

		$(function(){
			$(".btn-notifi").on("click",function(e){
				e.preventDefault();
				loadAjax({
					url : $(this).attr('href'),
					type: 'GET',
					success: function(){
						window.location.reload();
					}
				})
			})
		})
</script>


<link rel="stylesheet" href="/frontend/vendor/select2/select2.min.css">
<script src="/frontend/vendor/select2/select2.min.js"></script>

<style type="text/css" media="screen">
	/*.content-menu .dropdown-menu{
     display: none;
     margin-top: -1px;
  }*/

  /*.content-menu .dropdown:hover .dropdown-menu,
  .content-menu .dropdown:focus .dropdown-menu,
  .content-menu .dropdown:active .dropdown-menu{
    display: block;
  }*/
  .item-notification.not_read{
  	background: #0275d820;
  }
  @media screen and (-webkit-min-device-pixel-ratio:0) and (max-device-width:1024px){
  	input#project{
  		font-size: 1rem !important;
  	}
  }
</style>
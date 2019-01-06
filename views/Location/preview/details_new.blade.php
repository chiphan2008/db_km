@section('body')
	<body>
	@endsection
	<div class="content-location-detail content-page" style="opacity: 0;">
			<div class="sidebar-top hidden-sm-down mb-2 mb-sm-4 mt-2 mt-sm-3 py-2">
					<div class="container d-flex align-items-md-start align-items-center">
							<a class="come-back" href="" title="">
									<i class="icon-left mr-2"></i>
									{!! $content['breadcrumb'] !!}
							</a>
					</div>
			</div>
			<header class="header-detail ">
				<div class="container d-lg-flex justify-lg-content-between">
					<div class="header-detail-left d-flex align-items-center align-items-lg-start align-items-center">
						<div class="avata text-center">
							<a href="" title="">
								<div class="rounded-circle" style="
												background-image: url('{!! $content['avatar'] !!}');
												width:80px; height:80px;
												background-size: 152px 80px;
												background-repeat: no-repeat;
												background-size: cover;
												background-position: center;
												">
													
												</div>
								<div class="online status-location">
									<i class="icon-circle"></i>
									<span>{{trans('Location'.DS.'content.opening')}}</span>
								</div>
							</a>
						</div>
						<h1 class="title-restaurant hidden-lg-up">{{$content['name']}}</h1>
					</div>
					<div class="content mb-4">
						<div class="d-lg-flex justify-content-lg-between align-items-lg-start ">
							<div class="content-left pr-0 pr-lg-3">
								<h1 class="title-restaurant hidden-md-down">{{$content['name']}}</h1>
								<ol class="info-contact list-unstyled mb-3 mb-lg-0">
									<li>
										<i class="icon-location"></i>
										<p>
											{{$content['address']}}, {{$content['district']->name}}, {{$content['city']->name}}, {{$content['country']->name}}
										</p>
									</li>
									@if($content['phone'])
									<li>
										<i class="icon-phone"></i>
										<p>
											{{$content['phone']}}
										</p>
									</li>
									@endif
									@if($content['email'])
									<li>
										<i class="icon-mail"></i>
										<p>
											{{$content['email']}}
										</p>
									</li>
									@endif
									<li>
										<i class="icon-time"></i>
										<p>
											{{$content['open_time']}}
										</p>
									</li>
									<li style="text-align: justify;">
										{{$content['description']?$content['description']:trans('global.content_is_update')}}
									</li>
								</ol>
								<!-- start user interaction -->
							</div>
							<ol class="user-interaction content-right list-unstyled hidden-md-down">
								<li>
									<div class="meta-post d-flex align-items-center">
										<div class="add-like d-flex align-items-center">
											<i class="icon-heart-empty"></i>
											<span>({{0}})</span>
										</div>
										<div class="meta-post-distance">
										</div>
										<div class="rating d-flex align-items-center" data-vote="0">
											<div class="star hidden">
												<span class="full {{(isset($content['vote']) && $content['vote']>=1) ? 'star-colour' : ''}}"
															data-value="1"></span>
												<span class="half {{(isset($content['vote']) && $content['vote']>=0.5) ? 'star-colour' : ''}}"
															data-value="0.5"></span>
											</div>
											<div class="star">
												<span class="full {{(isset($content['vote']) && $content['vote']>=2) ? 'star-colour' : ''}}"
															data-value="2"></span>
												<span class="half {{(isset($content['vote']) && $content['vote']>=1.5) ? 'star-colour' : ''}}"
															data-value="1.5"></span>
												<span class="selected"></span>
											</div>
											<div class="star">
												<span class="full {{(isset($content['vote']) && $content['vote']>=3) ? 'star-colour' : ''}}"
															data-value="3"></span>
												<span class="half {{(isset($content['vote']) && $content['vote']>=2.5) ? 'star-colour' : ''}}"
															data-value="2.5"></span>
												<span class="selected"></span>
											</div>
											<div class="star">
												<span class="full {{(isset($content['vote']) && $content['vote']>=4) ? 'star-colour' : ''}}"
															data-value="4"></span>
												<span class="half {{(isset($content['vote']) && $content['vote']>=3.5) ? 'star-colour' : ''}}"
															data-value="3.5"></span>
												<span class="selected"></span>
											</div>
											<div class="star">
												<span class="full {{(isset($content['vote']) && $content['vote']>=5) ? 'star-colour' : ''}}"
															data-value="5"></span>
												<span class="half {{(isset($content['vote']) && $content['vote']>=4.5) ? 'star-colour' : ''}}"
															data-value="4.5"></span>
												<span class="selected"></span>
											</div>
											<span class="star-number">&nbsp;&nbsp;({{$content['vote']}})</span>
										</div>
									</div>
									<!-- end  meta -->
								</li>
								<li>
									<span><i class="icon-location"></i></span>
									<a class="cursor" id="checkin">
										Check in 
									</a>
									(<span id="checkin_total">0</span>)
								</li>
								<li>
									<span><i class="icon-target"></i></span>
									<a class="cursor">
										{{trans('Location'.DS.'content.save_favorites')}}
									</a>
									(<span id="save_like_content_total">0</span>)
								</li>
								<li>	
									<div class="dropdown-collection">
											<span><i class="icon-save"></i></span> <a style="cursor: pointer;" title="{{trans('Location'.DS.'content.add_collection')}}"
											>{{trans('Location'.DS.'content.add_collection')}}</a> (<span>0</span>)
									</div>
								</li>
								<!-- <li>
									<span><i class="icon-commenting-o"></i></span>
									Chat Online
								</li> -->
								<li>
									<!-- <span><i class="icon-share-grey"></i></span> {{trans('Location'.DS.'content.share')}}: -->
									<a href="#" onclick="sharePopup('https://plus.google.com/share?url={{urlencode(url()->current())}}')">
									<i class="icon-google"></i>&nbsp;&nbsp;&nbsp;&nbsp;
									</a>
									<a href="#" onclick="sharePopup('https://www.facebook.com/sharer/sharer.php?u={{urlencode(url()->current())}}&amp;src=sdkpreparse')">
									<i class="icon-facebook"></i>&nbsp;&nbsp;&nbsp;&nbsp;
									</a>
									<a href="#" onclick="sharePopup('https://twitter.com/share?text={!! clear_str($content['name']) !!}&url={{urlencode(url()->current())}}&hashtags=Kingmap')">
									<i class="icon-twitter-bird"></i>&nbsp;&nbsp;&nbsp;&nbsp;
									</a>
								</li>
							</ol>
							<!-- end user interaction -->
							<div class="content-right-mobile hidden-lg-up">
								<!-- <div class="mb-3">
									 <a class="btn btn-primary" href="" title="">Chat trực tuyến</a>
									<a href="" class="btn btn-share"><i class="icon-share-grey"></i></a>
								</div> -->

								<ol class="user-interaction content-right list-unstyled ">
									<li>
										<div class="meta-post d-flex align-items-center">
											<div class="add-like d-flex align-items-center">
												<i class="icon-heart-empty"></i>
												<span>({{0}})</span>
											</div>
											<div class="meta-post-distance">
											</div>
											<div class="rating d-flex align-items-center" data-vote="0">
												<div class="star hidden">
												<span class="full {{(isset($content['vote']) && $content['vote']>=1) ? 'star-colour' : ''}}"
													  data-value="1"></span>
													<span class="half {{(isset($content['vote']) && $content['vote']>=0.5) ? 'star-colour' : ''}}"
														  data-value="0.5"></span>
												</div>
												<div class="star">
												<span class="full {{(isset($content['vote']) && $content['vote']>=2) ? 'star-colour' : ''}}"
													  data-value="2"></span>
													<span class="half {{(isset($content['vote']) && $content['vote']>=1.5) ? 'star-colour' : ''}}"
														  data-value="1.5"></span>
													<span class="selected"></span>
												</div>
												<div class="star">
												<span class="full {{(isset($content['vote']) && $content['vote']>=3) ? 'star-colour' : ''}}"
													  data-value="3"></span>
													<span class="half {{(isset($content['vote']) && $content['vote']>=2.5) ? 'star-colour' : ''}}"
														  data-value="2.5"></span>
													<span class="selected"></span>
												</div>
												<div class="star">
												<span class="full {{(isset($content['vote']) && $content['vote']>=4) ? 'star-colour' : ''}}"
													  data-value="4"></span>
													<span class="half {{(isset($content['vote']) && $content['vote']>=3.5) ? 'star-colour' : ''}}"
														  data-value="3.5"></span>
													<span class="selected"></span>
												</div>
												<div class="star">
												<span class="full {{(isset($content['vote']) && $content['vote']>=5) ? 'star-colour' : ''}}"
													  data-value="5"></span>
													<span class="half {{(isset($content['vote']) && $content['vote']>=4.5) ? 'star-colour' : ''}}"
														  data-value="4.5"></span>
													<span class="selected"></span>
												</div>
												<span class="star-number">&nbsp;&nbsp;({{$content['vote']}})</span>
											</div>
										</div>
										<!-- end  meta -->
									</li>
									<li>
										<span><i class="icon-location"></i></span>
										<a class="cursor" id="checkin">
											Check in
										</a>
										(<span id="checkin_total">0</span>)
									</li>
									<li>
										<span><i class="icon-target"></i></span>
										<a class="cursor">
											{{trans('Location'.DS.'content.save_favorites')}}
										</a>
										(<span id="save_like_content_total">0</span>)
									</li>
									<li>
										<div class="dropdown-collection">
											<span><i class="icon-save"></i></span> <a style="cursor: pointer;" title="{{trans('Location'.DS.'content.add_collection')}}"
											>{{trans('Location'.DS.'content.add_collection')}}</a> (<span>0</span>)
										</div>
									</li>
								</ol>
								<!-- end  list group pd -->
								<ul class="list-info-restaurant list-unstyled clearfix ">
									@foreach($content['all_service'] as $value)
										<li class="{{!in_array($value->id_service_item, $content['service']) ? 'disabled':''}}">@lang(mb_ucfirst($value->_service_item->name))
										</li>
									@endforeach
								</ul>
							</div>
							<!-- end  content right mobile -->
						</div>
					</div>
				</div>
			</header>
			@php
				$list_product = [];
				$discounts = [];
		  @endphp
			<section class="section-space bg-gray my-4 px-md-4">
				<div class="container">
						
						<div class="box-gallery">
							<div class="title-gallery d-flex justify-content-between align-items-start">
								<h4 class="text-uppercase mb-4">{{mb_strtoupper(trans('global.space'))}} ({{count($content['space'])}})</h4>
								@if(count($content['space']) > 0)
								<!-- <a href="" title="">
									{{ucfirst(trans('global.view_all'))}} 
									<i class="icon-ic-arrow"></i>
								</a> -->
								@endif
							</div>
							@if(count($content['space']) > 0)
							<ul class="list-gallery list-unstyled row">
								@foreach($content['space'] as $value)
									<li>
										<a data-fancybox="images_space"  href="{{$value}}">
											<img style="width:100%; height:134px;" class="img-fluid" src="{{str_replace('img_content','img_content_thumbnail',$value)}}" alt="">
										</a>
									</li>
								@endforeach
							</ul>
							@else
							<h6 style="color: #5b89abb8;" class="font-italic">{{trans('global.content_is_update')}}</h6>
							@endif
						</div>

						<div class="box-gallery">
							<div class="title-gallery d-flex justify-content-between align-items-start">
								<h4 class="text-uppercase mb-4">{{mb_strtoupper(trans('global.image'))}} ({{count($content['menu'])}})</h4>
								@if(count($content['menu']) > 0)
								<!-- <a href=""
									 title="">
										{{ucfirst(trans('global.view_all'))}} 
										<i class="icon-ic-arrow"></i>
								</a> -->
								@endif
							</div>
							@if(count($content['menu']) > 0)
							<ul class="list-gallery list-unstyled row">
								 @foreach($content['menu'] as $value)
									<li>
										<a data-fancybox="images_menu"  href="{{$value}}">
											<img style="width:100%; height:134px;" class="img-fluid" src="{{str_replace('img_content','img_content_thumbnail',$value)}}" alt="">
										</a>
									</li>
								 @endforeach
							</ul>
							@else
							<h6 style="color: #5b89abb8;" class="font-italic">{{trans('global.content_is_update')}}</h6>
							@endif
						</div>

						<div class="box-gallery">
							<div class="title-gallery d-flex justify-content-between align-items-start">
								<h4 class="text-uppercase mb-4">{{mb_strtoupper(trans('global.video'))}} ({{count($content['link'])}})</h4>
								@if(count($content['link']) > 0)
								<!-- <a href=""
									 title="">{{ucfirst(trans('global.view_all'))}}
									<i class="icon-ic-arrow"></i>
								</a> -->
								@endif
							</div>
							@if(count($content['link']) > 0)
							<ul class="list-gallery list-unstyled row">
								@foreach($content['link'] as $value)
									@if ($value['type'] == 'facebook')
										<li class="iframe-video">
											<a data-video-facebook href="https://www.facebook.com/plugins/video.php?height=232&href={{$value['link']}}">
												 <img src="{{$value['thumbnail']?$value['thumbnail']:''}}" alt="">
												 <span class="ytp-time-duration">{{$value['time']?$value['time']:''}}</span>
											</a>
											<p>
												<a data-video-facebook href="https://www.facebook.com/plugins/video.php?height=232&href={{$value['link']}}">
													{{$value['title']?$value['title']:''}}
												</a>
											</p>
										</li>
									@elseif($value['type'] == 'youtube')
										@php
											$link = $value['link'];
											$link = str_replace('watch?v=','',$link);
											$link = str_replace('youtube.com/','youtube.com/embed/',$link);
											$link = str_replace('youtu.be/','youtube.com/embed/',$link);
											$link = clear_youtube_link($link);
										@endphp
										<li class="iframe-video">
											<a data-video href="{{$link}}">
												 <img src="{{$value['thumbnail']?$value['thumbnail']:''}}" alt="">
												 <span class="ytp-time-duration">{{$value['time']?$value['time']:''}}</span>
											</a>
											<p>
												<a data-video href="{{$link}}">
													{{$value['title']?$value['title']:''}}
												</a>
											</p>
										</li>
									@endif
								@endforeach
							</ul>
							@else
							<h6 style="color: #5b89abb8;" class="font-italic">{{trans('global.content_is_update')}}</h6>
							@endif
						</div>

						<div class="box-gallery">
							<div class="title-gallery">
								<h4 class="text-uppercase mb-4">{{mb_strtoupper(trans('global.product_service'))}} ({{count($list_product)}})</h4>
							</div>
							@if(count($list_product) > 0)
							<div class="section-menu-content">
								@foreach($list_product as $key_group => $group)
								<!-- start  location list menu -->
								<div class="location-list-menu">
									@if($key_group !== 'no_group')
									<h5 class="location-list-menu-title">{{$group['group_name']}}</h5>
									@endif
									<ul class="list-product-location list-unstyled row">
										@foreach($group as $key_product => $product)
										@if($key_product !== 'group_name')
										<li class="content-product-location col-6 col-sm-4 col-lg-2">
												<div class="img mb-2">
													<a data-fancybox="images_product"  href="{{$product->image}}" title="{{mb_ucfirst($product->name)}} - {{money_number($product->price)}}  {{$product->currency}}">
															<img style="width:100%; height:134px;" class="img-fluid" src="{{$product->image}}" alt="">
													</a>
												</div>
												<div class="content">
													<div class="title mb-2">
														<a href="#">
															{{mb_ucfirst($product->name)}}
														</a>
													</div>
													<div class="price">
														{{money_number($product->price)}}  {{$product->currency}}
													</div>
												</div>
										</li>
										@endif
										@endforeach
									</ul>
								</div>
								<!-- end  location list menu -->
								@endforeach
								<!-- <div class="readmore text-center">
									<a href="">Xem thêm</a>
								</div> -->
							</div>
							@else
							<h6 style="color: #5b89abb8;" class="font-italic">{{trans('global.content_is_update')}}</h6>
							@endif
						</div>
						
				</div>
			</section>
		
		<section class="my-4 my-md-5">
			<div class="container">
				<div class="box-gallery">
							<div class="title-gallery">
								<h4 class="text-uppercase mb-4">{{mb_strtoupper(trans('global.discount'))}} ({{count($discounts)}})</h4>
							</div>
							@if(count($discounts) > 0)
							<div class="section-menu-content">
								<div class="location-list-menu">
									@foreach($discounts as $discount)
									<div class="location-list-menu-content clearfix">
										<div class="card-horizontal-sm d-flex align-items-center pb-3 mb-3">
											<div class="img">
												<!-- <a href=""> -->
													<img style="width:100%; height:134px;" src="{{$discount->image}}" alt="{{mb_ucfirst($discount->name)}}">
												<!-- </a> -->
											</div>
											<div class="content pl-2">
												<!-- <a class="title d-block mb-1" href=""> -->
													<span class="title d-block mb-1">{{mb_ucfirst($discount->name)}}</span>
												<!-- </a> -->
												<span class="desscription">{{$discount->description}}</span>
											</div>
										</div>               
									</div>
									@endforeach
								</div>
							</div>
							@else
							<h6 style="color: #5b89abb8;" class="font-italic">{{trans('global.content_is_update')}}</h6>
							@endif
						</div>
			</div>
		</section>
		<section class="my-4 my-md-5">
			<div class="container">
				<div class="row">
					<div class="col-md-12 flex-lg-first">
						<ul class="list-info-restaurant list-unstyled clearfix">
							@foreach($content['all_service'] as $value)
								<li class="{{!in_array($value->id_service_item, $content['service']) ? 'disabled':''}}">@lang(mb_ucfirst($value->_service_item->name))
								</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- end -->
		<section class="my-4 my-md-5">
			<div class="container">
				<div id="map-2"></div>
			</div>
		</section>
	</div>


	@section('JS')
		<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js"></script>

		<script type="text/javascript">
			$(document).ready(function () {

				$(".content-location-detail").css({'opacity':1});

				$('body').on("click",function(e){
					var container = $(".dropdown-collection");

					if (!container.is(e.target) && container.has(e.target).length === 0) 
					{
							container.find('.dropdown-menu-collection').hide();
					}
				})

				$( '[data-fancybox]' ).fancybox({
					loop:true
				});
				$( '[data-video]' ).fancybox();
				$( '[data-video-facebook]' ).fancybox({
					type:"iframe"
				});
				// slider
				$('.slider-gallery').slick({
					dots: true,
					infinite: true,
					speed: 300,
					slidesToShow: 4,
					slidesToScroll: 1,
					arrows: true,
                    autoplay: true,
                    autoplaySpeed: 4000,
                    prevArrow: '<button type="button" class="slick-prev btn"><i class="icon-left-open-big"></i></button>',
					nextArrow: '<button type="button" class="slick-next btn "><i class="icon-right-open-big"></i></button>',
					responsive: [{
						breakpoint: 1024,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 3,
							infinite: true,
						}
					},
						{
							breakpoint: 600,
							settings: {
								slidesToShow: 2,
								slidesToScroll: 2
							}
						}
					]
				});
				$('.list-gallery').slick({
					dots: false,
					infinite: true,
					speed: 300,
					slidesToShow: 6,
					slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 4000,
                    prevArrow: '<button type="button" class="slick-prev btn"><i class="icon-left-open-big"></i></button>',
					nextArrow: '<button type="button" class="slick-next btn "><i class="icon-right-open-big"></i></button>',
					responsive: [{
						breakpoint: 1024,
						settings: {
							slidesToShow: 4,
							slidesToScroll: 4,
							infinite: true,
						}
					},
						{
							breakpoint: 600,
							settings: {
								slidesToShow: 2,
								slidesToScroll: 2
							}
						}
					]
				});
			});
		</script>
		<script>
			var base_url = {!! json_encode(url('/')) !!};
			// var mapHandling = 'cooperative'; // dung 2 ngon tay
			var mapHandling = 'greedy';
			if($(window).width()>768){
				mapHandling = 'greedy';
			}
			var geocoder_detail = new google.maps.Geocoder();
			var directionsService = new google.maps.DirectionsService();
			var directionsDisplay = new google.maps.DirectionsRenderer({
				'draggable': false,
				polylineOptions: {
					strokeColor: "#d0021b",
					strokeWeight: 4,
					strokeOpacity: 1
				},
			});

			var style_map =[
				{
					"featureType": "administrative",
					"elementType": "geometry",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "administrative.locality",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"visibility": "on"
						}
					]
				},
				{
					"featureType": "administrative.locality",
					"elementType": "labels.text",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "poi",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "road",
					"elementType": "labels.icon",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "transit",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				}
			];

			google.maps.event.addDomListener(window, 'load', init);

			function init() {
				var mapOptions = {
					gestureHandling: mapHandling,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					mapTypeControl: false,
					zoom: 14,
					styles: style_map,
					zoomControl: true,
					mapTypeControl: false,
					scaleControl: false,
					streetViewControl: false,
					rotateControl: true,
					fullscreenControl: true,
					center: new google.maps.LatLng({{$content['lat']}}, {{$content['lng']}})
				};
				var image = '{{asset('frontend/assets/img/logo/Logo-maps.png')}}';
				var mapElement = document.getElementById('map-2');
				var map = new google.maps.Map(mapElement, mapOptions);
				var marker = new google.maps.Marker({
					position: new google.maps.LatLng({{$content['lat']}}, {{$content['lng']}}),
					map: map,
					title: '{{$content['name']}}',
					icon: image
				});

				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(showPosition,function(error){
						console.log(error);
						var currentLocation = window.sessionStorage.getItem('currentLocation');
						var coord = currentLocation.split(',');
						var lat = parseFloat(coord[0]);
						var lng = parseFloat(coord[1]);
						var position = {
							coords:{
								latitude: lat,
								longitude: lng
							}
						};
						showPosition(position);
					},{enableHighAccuracy: true,  timeout: 5000,  maximumAge: 60000});
				}

			}

			function showPosition(position) {
				var latLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
				var mapOptions = {
					zoom: 14,
					gestureHandling: mapHandling,
					styles: style_map,
					zoomControl: true,
					mapTypeControl: false,
					scaleControl: false,
					streetViewControl: false,
					rotateControl: true,
					fullscreenControl: true,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					mapTypeControl: false,
					center: new google.maps.LatLng({{$content['lat']}}, {{$content['lng']}}),
				};
				var image = '{{asset('frontend/assets/img/logo/Logo-maps.png')}}';
				var bg_maker1 = {
					url: '{{asset('frontend/assets/img/icon/blank.png')}}',
					anchor: new google.maps.Point(0,80)
				};

				var bg_maker2 = {
					url: '{{asset('frontend/assets/img/icon/blank.png')}}',
					anchor: new google.maps.Point(0,-10)
				};
				var mapElement = document.getElementById('map-2');
				var map = new google.maps.Map(mapElement, mapOptions);
				if (geocoder_detail) {
					geocoder_detail.geocode({'latLng': latLng}, function (results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
							var startMarker = new google.maps.Marker({position: latLng, map: map});
							var stopMarker = new google.maps.Marker({
								position: new google.maps.LatLng({{$content['lat']}}, {{$content['lng']}}),
								map: map,
								icon: image
							});


							if(parseFloat("{{$content['lat']}}") > latLng.lat()){
								var distanceMarker = new google.maps.Marker({
									position: new google.maps.LatLng({{$content['lat']}}, {{$content['lng']}}),
									map: map,
									icon: bg_maker1,
									zIndex: 999,
									label: {
										text: '',
										color: "#d0021b",
										fontSize: "18px",
										fontWeight: "bolder",
									}
								});

								var hereMarker = new google.maps.Marker({
									position:latLng,
									map: map,
									icon: bg_maker2,
									zIndex: 999,
									label: {
										text: "{{trans('global.current_location')}}",
										color: "#d0021b",
										fontSize: "18px",
										fontWeight: "bolder"
									}
								});
							}else{
								var distanceMarker = new google.maps.Marker({
									position: new google.maps.LatLng({{$content['lat']}}, {{$content['lng']}}),
									map: map,
									icon: bg_maker2,
									zIndex: 999,
									label: {
										text: '',
										color: "#d0021b",
										fontSize: "18px",
										fontWeight: "bolder",
									}
								});

								var hereMarker = new google.maps.Marker({
									position:latLng,
									map: map,
									icon: bg_maker1,
									zIndex: 999,
									label: {
										text: "{{trans('global.current_location')}}",
										color: "#d0021b",
										fontSize: "18px",
										fontWeight: "bolder"
									}
								});
							}

							

							directionsDisplay.setMap(map);
							directionsDisplay.setOptions({suppressMarkers: true});
							var request = {
								origin: latLng,
								destination: new google.maps.LatLng({{$content['lat']}}, {{$content['lng']}}),
								travelMode: google.maps.DirectionsTravelMode.DRIVING
							};

							directionsService.route(request, function (response, status) {
								if (status == google.maps.DirectionsStatus.OK) {
									var distannce_element = document.getElementsByClassName('meta-post-distance');
									var text_distance = computeTotalDistance(response);
									for(var i = 0; i < distannce_element.length; i++){
										distannce_element[i].innerText=text_distance;
									}
									directionsDisplay.setDirections(response);
									distanceMarker.setLabel({
										text: text_distance,
										color: "#d0021b",
										fontSize: "16px",
										fontWeight: "bold"
									});
									// if(centerRoute(response)){
									//   distanceMarker.setPosition(centerRoute(response));
									// }
								}else{
									var line = new google.maps.Polyline({
									    path: [
									        new google.maps.LatLng(position.coords.latitude, position.coords.longitude), 
									        new google.maps.LatLng({{$content['lat']}}, {{$content['lng']}})
									    ],
									    strokeColor: "#d0021b",
									    strokeOpacity: 1.0,
									    strokeWeight: 3,
									    map: map
									});
									var text_distance = calculateDistance(position.coords.latitude, position.coords.longitude,{{$content['lat']}}, {{$content['lng']}});

									distanceMarker.setLabel({
										text: text_distance,
										color: "#d0021b",
										fontSize: "16px",
										fontWeight: "bold"
									});
								}
							});
						}
						else {
							return false;
						}
					});
				}
			}

			function computeTotalDistance(result) {
				var total = 0;
				var myroute = result.routes[0];
				for (var i = 0; i < myroute.legs.length; i++) {
					total += myroute.legs[i].distance.value;
				}
				if(total > 1000){
					total = total / 1000;
					return total.toFixed(1)+' Km';
				}else{
					return total +' m';
				}
			}

			function calculateDistance(lat1, lon1, lat2, lon2)
  		{    
  			var radlat1 = Math.PI * lat1/180
				var radlat2 = Math.PI * lat2/180
				var theta = lon1-lon2
				var radtheta = Math.PI * theta/180
				var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
				dist = Math.acos(dist)
				dist = dist * 180/Math.PI
				dist = dist * 60 * 1.1515
				dist = dist * 1.609344 * 1000;
				if(dist > 1000){
					dist = dist / 1000;
					return dist.toFixed(1)+' Km';
				}else{
					return dist +' m';
				}
	    }

			function centerRoute(result) {
				var myroute = result.routes[0];
				for (var i = 0; i < myroute.legs.length; i++) {
					var stepts = myroute.legs[i].steps;
					var average = Math.floor((stepts.length-1)/2);
					if(stepts[average]){
						return stepts[average].end_location;
					}else{
						return false;
					}
				}
				return false;
			}

			function getLikeContent(id_content, id_user) {
				if (id_user === undefined) {
					$('#modal-signin').modal('show');
				}
				else {
					$.ajax({
						type: "POST",
						data: {
							id_content: id_content,
							id_user: id_user,
							_token: $('meta[name="_token"]').attr('content')
						},
						url: base_url + '/like-content',
						success: function (data) {
							if (data.mess == true) {
								$('div.content .point_like').text('(' + data.value + ')');
								if ($('div.content .add-like i').hasClass('icon-heart-empty')) {
									$('div.content .add-like i').removeClass('icon-heart-empty').addClass('icon-heart')
								} else {
									$('div.content .add-like i').removeClass('icon-heart').addClass('icon-heart-empty')
								}
							}
						}
					})
				}
			}

		</script>
@endsection

@section('body')
	<body class="maps">
		<!-- <div id="popup_ads" style="display:none;" class="">
			<div class="horizontal div1">
				<div class="vertical">
					<div class="content_ads">
						<div class="header_ads">{{trans('global.close_ads_after')}} <span id="timer_ads">000</span>s</div>
						<div class="body_ads">
							@if($ads)
							<a href="{{$ads->choose_type=='content'?url($ads->_base_content->alias):$ads->link}}">
								<img src="{{url($ads->image)}}" alt="" style="max-width: 500px; max-height: 275px; width:100%;">
							</a>
							@else
							<img src="{{url($type_ads->img_default)}}" alt="" style="max-width: 500px; max-height: 275px; width:100%;">
							@endif
						</div>
					</div>
				</div>
			</div>
		</div> -->
@endsection

<link rel="stylesheet" type="text/css" href="/frontend/vendor/select2/select2.min.css">
<link rel="stylesheet" type="text/css" href="/frontend/vendor/dropdown-hover.css">

<style type="text/css" media="screen">
	@media(max-width: 480px){
		.mobile_badge{
			display: none !important;
		}	
	}
	@media (max-width: 768px){
		.menu-categories {
		  padding: 0 !important; 
		}
	}
/*	.content-maps-page .dropdown-menu{
		max-width: 285px;
	}
  .box-maps-select .content, .box-maps-select ol{
    max-height: 300px;
    overflow-y: auto;
  }*/
</style>

<div class="container-maps-page">
	<nav class="menu-categories-desktop menu-categories  hidden-lg-up">
		<div class="container">
			<div class="menu-cate-show-map ">
				<div class="list-menu-categories-mobile  hidden-lg-up " style="display:none;">
					<div class="d-flex justify-content">
						<a class="come-back" href="javascript:history.back()" title=""><i class="icon-left"></i></a>
						<!-- end come back -->
						<!-- end dropdown -->
						<span class="mobile_badge">
							
						</span>
						@if(count($extra_types)>0)
						<div class="dropdown">
							<a class="dropdown-toggle text-truncate" href="https://example.com" id="dropdownMenuLink" data-toggle="dropdown">
							{{$current_extra_type?$current_extra_type:''}}
							</a>

							<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
								@foreach($extra_types as $type)
								<a class="dropdown-item"  onclick="loadContent(1,true,false,'{{$type}}');">{{$type}}</a></a>
								@endforeach
							</div>
						</div>
						@endif
						<!-- end dropdown -->
						<input name="advance" type="hidden">
						<!-- <a class="btn-show-list" href="" title=""><i class="fa fa-filter"></i></a> -->
					</div>
				</div>
			</div>
			<!-- end menu-cate-show-map -->
			{{--<a class="come-back-map come-back" href="" title=""><i class="icon-left"></i> {{trans('global.map')}}</a>--}}
		</div>
	</nav>
	<!-- end menu cate -->
	<div class="content-maps-page">
		<div class="row px-3">
			<div id="choosen_value" class="pull-left hidden-md-down">
			</div>
		</div>
		<div class="custom-gmap box-gmap">
			<div class="siderbar-left siderbar px-md-3 px-sm-0">
					<div class="header-sider-bar d-flex justify-content-between">
							<!-- <h3 class="text-uppercase">Nhà hàng</h3> -->
							<!-- <ul class="nav text-uppercase" role="tablist">
									<li class="nav-item">
											<a class=" active" data-toggle="tab" href="#phonggiaodich" role="tab">Phòng giao dịch</a>
									</li>
									<li class="nav-item">
											<a data-toggle="tab" href="#atm" role="tab">ATM</a>
									</li>
							</ul> -->
							<!-- end nav tab -->
							<!-- <a class="view-all" href="" alt="">{{trans('global.view_all')}} <img src="/frontend/assets/img/icon/ic-arrow.png" alt=""></a> -->
					</div>
					<!-- end  header sidebar -->
					<div class="tab-content py-2">
							<div class="tab-pane active" id="phonggiaodich" role="tabpanel">
									<div class=" container-siderbar">
											<ul class="list-restaurant list-unstyled" id="listContent">
													@include('Location.category.content_item_list')
											</ul>
									</div>
									<!-- end  container siderbar -->
							</div>
					</div>
					<!-- end tab content -->
			</div>
			<!-- end siderbar-left -->
			<div class="search-advanced d-flex align-items-center p-2">
					<!-- <h6 class="pl-2 m-0 d-none d-sm-block">{{trans('global.advance_search')}}:</h6> -->
					<form action="" class="form-search-advanced d-flex align-items-center justify-content-between">
							<!-- start category -->
							<div class="box-maps-select category">
									<!-- start step 1 -->
									<div class="select-step1 select-custom-li" id="category_step">
											<h2 class="select-nav">{{isset($category_search)?app('translator')->getFromJson($category_search->name):trans('global.category')}}</h2>
											<div class="select">
													<div class="scroll-content">
														<ol>
														</ol>
													</div>
													
													<div class="select-next-step d-flex justify-content-end">
															<!-- <a class="prev" href=""><i class="icon-angle-double-left"></i></a> -->
															<a class="next" href=""><i class="icon-angle-double-right"></i></a>
													</div>
											</div>
									</div>
									<!-- end  step 1 -->
									<!-- start select step 2 -->
									<div class="select-step2 select-custom-li dropdown mega-dropdown location px-sm-3 px-2" id="category_item_step">
											<h2 class="select-nav">
												{{trans('Location'.DS.'search.category_item')}}
											</h2>
											<div class="select dropdown-menu dropdown-menu-right">
													<div class="scroll-content px-2">
														<div class="content"></div>
													</div>
													<div class="select-next-step  d-flex justify-content-between">
															<a class="prev" href=""><i class="icon-angle-double-left"></i></a>
															<a class="next" href=""><i class="icon-angle-double-right"></i></a>
													</div>
											</div>
									</div>
									<!-- end  select step 2 -->
									<!-- start select step 3 -->
									<div class="select-step3 select-custom-li dropdown mega-dropdown location px-sm-3 px-2" id="service_step">
											<h2 class="select-nav">
												{{trans('global.service')}}
											</h2>
											<div class="select dropdown-menu dropdown-menu-right ">
													<div class="scroll-content px-2">
														<div class="content"></div>
													</div>
													<!-- end content -->
													<div class="select-next-step d-flex justify-content-between">
															<a class="prev" href=""><i class="icon-angle-double-left"></i></a>
															<a class="next" href="">{{trans('global.search')}}</a>
													</div>
											</div>
									</div>
									<!-- end  select step 3 -->
							</div>

              <div class="box-maps-select location">
                  <!-- start step 1 -->
                  <div class="select-step1 select-custom-li" id="country_step">
                      <h2 class="select-nav">{{isset($country_search)?$country_search->name:trans('global.country')}}</h2>
                      <div class="select">
                          <div class="scroll-content">
                          	<ol></ol>
                          </div>
                          <div class="select-next-step d-flex justify-content-end">
                              <!-- <a class="prev" href=""><i class="icon-angle-double-left"></i></a> -->
                              <a class="next" href=""><i class="icon-angle-double-right"></i></a>
                          </div>
                      </div>
                  </div>
                  <!-- end  step 1 -->
                  <!-- start select step 2 -->
                  <div class="select-step2 select-custom-li dropdown mega-dropdown location px-sm-3 px-2" id="city_step">
                      <h2 class="select-nav">{{trans('global.city')}}</h2>
                      <div class="select">
                          <div class="scroll-content">
                          	<ol></ol>
                          </div>
                          <div class="select-next-step d-flex justify-content-between">
                              <a class="prev" href=""><i class="icon-angle-double-left"></i></a>
                              <a class="next" href=""><i class="icon-angle-double-right"></i></a>
                          </div>
                      </div>
                  </div>
                  <!-- end  select step 2 -->
                  <!-- start select step 3 -->
                  <div class="select-step3 select-custom-li dropdown mega-dropdown location px-sm-3 px-2" id="district_step">
                      <h2 class="select-nav">{{trans('global.district')}}</h2>
                      <div class="select">
                          <div class="scroll-content">
                          	<ol></ol>
                          </div>
                          <div class="select-next-step d-flex justify-content-start">
                              <a class="prev" href=""><i class="icon-angle-double-left"></i></a>
                              <!-- <a class="next" href="">{{trans('global.search')}}</a> -->
                          </div>
                      </div>
                  </div>
                  <!-- end  select step 3 -->
              </div>
							<div style="display:none;">
									<a class="dropdown-toggle" data-group="category" href="javascript:;">
											{{trans('global.category')}}
									</a>
									<div class="dropdown-menu px-2">
											<!-- start category child -->
											<div class="dropdown category-child mb-2">
													<select class="form-control w-100 category_search" name="category_search" id="category_search"  onchange="changeCategorySearch(this)">
														<option value="0"></option>
														@if(isset($categories))
														@foreach($categories as $category_one)
														<option value="{{$category_one->id}}" {{isset($category_search)&&$category_one->id==$category_search->id?'selected':''}}>@lang(ucfirst($category_one->name))</option>
														@endforeach
														@endif
													</select>
											</div>
											<!--  end category child -->
											<label>{{trans('Location'.DS.'search.category_item')}}</label>
											<br/>
											<select class="form-control w-100 category_item_search" name="category_item_search" id="category_item_search" onchange="chooseCategoryItem(this)" multiple style="min-width:270px;max-width:270px;">
												<!-- <option value="">{{trans('Location'.DS.'search.choose_category_item')}}</option> -->
												@if(isset($category_search)&&isset($category_search->category_items))
												@foreach($category_search->category_items as $item_one)
												<option value="{{$item_one->id}}" {{isset($category_items)&&in_array($item_one->id, $category_items)?'selected':''}}>@lang(ucfirst($item_one->name))</option>
												@endforeach
												@endif
											</select>

                      <select class="form-control w-100 service_search" name="service_search" id="service_search" onchange="chooseCategoryItem(this)" multiple style="min-width:270px;max-width:270px;">
                        <!-- <option value="">{{trans('Location'.DS.'search.choose_service')}}</option> -->
                        @if(isset($category_search)&&isset($category_search->service_items))
                        @foreach($category_search->service_items as $item_one)
                        <option value="{{$item_one->id}}" {{isset($services)&&in_array($item_one->id, $services)?'selected':''}}>@lang(ucfirst($item_one->name))</option>
                        @endforeach
                        @endif
                      </select>

											<!-- <label>{{trans('global.service')}}</label>
											<br/>
											<select class="form-control w-100 service_search" name="service_search" id="service_search" id="service_search" onchange="chooseCategoryItem(this)" multiple style="min-width:270px;max-width:270px;">
												@if(isset($category_search)&&isset($category_search->services))
												@foreach($category_search->services as $item_one)
												<option value="{{$item_one->id}}" {{$services&&in_array($item_one->id, $services)?'selected':''}}>@lang(ucfirst($item_one->name))</option>
												@endforeach
												@endif
											</select> -->
											<!-- end select cte search -->
									</div>
							</div>
							<!-- end category -->

							<!-- start dropdown location -->
							<div style="display:none;">
								<a class=" dropdown-toggle" data-group="location" href="javascript:;">
									{{trans('global.location')}}
								</a>
									<div class="dropdown-menu dropdown-menu-right px-2">
											<div class="dropdown location-country mb-2">
												<select class="form-control w-100 country_search" name="country_search" id="country_search" onchange="changeCountrySearch(this)">
													<option value=""></option>
													<!-- <option value="">{{trans('Location'.DS.'search.choose_country')}}</option> -->
													@if(isset($countries))
													@foreach($countries as $country_one)
													<option value="{{$country_one->id}}"  {{isset($country_search)&&$country_one->id==$country_search->id?'selected':''}}>{{$country_one->name}}</option>
													@endforeach
													@endif
												</select>
				
				
											</div>
											<!--  end location country -->
											<div class="dropdown location-city mb-2">
												<select class="form-control w-100 city_search" name="city_search" id="city_search" onchange="changeCitySearch(this)">
													<option value=""></option>
													<!-- <option value="">{{trans('Location'.DS.'search.choose_city')}}</option> -->
													@if(isset($cities))
													@foreach($cities as $citi_one)
													<option value="{{$citi_one->id}}" {{$city&&$citi_one->id==$city->id?'selected':''}}>{{$citi_one->name}}</option>
													@endforeach
													@endif
												</select>
											</div>
											<!--  end location city -->
											<div class="dropdown location-township mb-2">
												<select class="form-control w-100 district_search" name="district_search" id="district_search" onchange="changeDistrictSearch(this)">
													<option value=""></option>
													<option value="">{{trans('Location'.DS.'search.choose_district')}}</option>
													@if(isset($districts))
													@foreach($districts as $district_one)
													<option value="{{$district_one->id}}" {{$district&&$district_one->id==$district->id?'selected':''}}>{{$district_one->name}}</option>
													@endforeach
													@endif
												</select>
											</div>
											<!--  end location township -->
									</div>
							</div>
							<!-- end  dropdown location -->

							<!-- start view list -->
							<span class="cursor view-list-search  px-sm-3 px-2" href="" title="{{trans('global.list')}}" data-show="{{request()->cookie('show_list_map') && request()->cookie('show_list_map')=='hide'?'show':'hide'}}">

								<span id="text_list">
									<p class="hidden-sm-down ml-1 change_text" style="margin-bottom: 0;">{{request()->cookie('show_list_map') && request()->cookie('show_list_map')=='hide'?trans('global.list'):trans('global.map')}}</p>
									<i class="{{request()->cookie('show_list_map') && request()->cookie('show_list_map')=='hide'?'icon-list':'icon-location'}} hidden-md-up"></i>
								</span>
							</span>
							<!-- end  view list -->
					</form>
					<!-- end  form search advanced -->
			</div>
			<div id="gmap"></div>
		</div>
		<!-- end custom-gmap -->
	</div>
	<!-- end url -->
</div>

@section('JS')
<!-- Script run popup ads -->
<script>
	$(function(){
		setTimeout(function(){
			var time_ads = 3;
			$("#timer_ads").text(time_ads);
			$("#popup_ads").show();
			var watch_ads = setInterval(function(){
				 time_ads--;
				 $("#timer_ads").text(time_ads);
				 if(time_ads===0){
					$("#popup_ads").hide();
					clearInterval(watch_ads);
				 }
			},1050)
		},3000)
	});
</script>

<script src="/frontend/vendor/select2/select2.min.js"></script>
<script src="/frontend/vendor/dropdown-hover.js"></script>
<script type="text/javascript">
	var first_latlng = {};
	var category_item_id = {{$category_item?$category_item->id:0}};
	var category_id = {{$category_search?$category_search->id:0}};
	if($(window).width() < 768){
		var map_data = {
			"address": "Hồ Chí Minh",
			"center": "10.806273, 106.714477",
			"zoom": "15",
			"scrollwheel": "false",

			"ui": "true",
			"css_class": "gmap"
		};
	}else{
		var map_data = {
			"address": "Hồ Chí Minh",
			"center": "10.806273, 106.714477",
			"zoom": "16",
			"scrollwheel": "false",

			"ui": "true",
			"css_class": "gmap"
		};
	}
	@if(!app('request')->input('currentLocation'))

		@if(\Session::has('currentLocation'))
			@php 
				$arr_location = explode(',',\Session::get('currentLocation'));
			@endphp
			var lat = {{$arr_location[0]}}
			var lng = {{$arr_location[1]}}
		@else
		var lat = 10.806273;
		var lng = 106.714477;
		@endif
	@else
		@php 
			$arr_location = explode(',',app('request')->input('currentLocation'));
		@endphp
		var lat = {{$arr_location[0]}}
		var lng = {{$arr_location[1]}}
	@endif

	var pos = {
		lat: lat,
		lng: lng
	};
	var lstAdd = {!!$json!!};
  var category_item_selected = {!!json_encode($category_items)!!};
  var service_selected = {!!json_encode($services)!!};
  var category_selected = {!!$category_search?$category_search->id:0!!};

	var _token = $("meta[name='_token']").prop('content');
	var extra_type = '{{$current_extra_type?$current_extra_type:''}}';
	var currentPage = 2;
	var totalPage = {{$total_content?$total_content/30:0}};
	var lstMarker = [];
	var lstCord = [];
	var map = null;
	var first_load = true;
	var max_page = true;
	var stop = true;
	var latlgn = map_data.center.split(',');
	var position_icon;
	var mapHandling = 'greedy';
	var marker = null;
	var keyword_has_changed = false;
	var currentLocation = '';
	if($(window).width()>768){
		position_icon = google.maps.ControlPosition.LEFT_TOP;
		mapHandling = 'greedy';
	}else{
		mapHandling = 'greedy';
		position_icon = google.maps.ControlPosition.RIGHT_TOP;
	}
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
	var mapObject = {
			zoom: JSON.parse(map_data.zoom),
			center: new google.maps.LatLng(latlgn[0], latlgn[1]),
			gestureHandling: mapHandling,
			disableDefaultUI: true,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			zoomControl: false,
			mapTypeControl: false,
			scaleControl: false,
			streetViewControl: false,
			rotateControl: true,
			fullscreenControl: true,
			fullscreenControlOptions:{
				position: position_icon,
				index: 3
			},
			styles: style_map
	};
	//map = new google.maps.Map(document.getElementById('gmap'), mapObject);
	var drag_load = false;
	var centerMarker = null;
	var mapCircle = new google.maps.Circle({
						strokeColor: '#FF0000',
						strokeOpacity: 0,
						strokeWeight: 2,
						fillColor: '#d9534f',
						fillOpacity: 0.25,
						radius: 520,
						clickable: false
					});
	function CenterControl(controlDiv, map) {

		// Set CSS for the control border.
		var controlUI = document.createElement('div');
		controlUI.style.backgroundColor = '#fff';
		controlUI.style.border = '2px solid #fff';
		controlUI.style.cursor =  'pointer'; 
		controlUI.style.width =  '25px'; 
		controlUI.style.height =  '25px'; 
		controlUI.style.overflow =  'hidden'; 
		controlUI.style.margin =  '10px 14px'; 
		controlUI.style.position =  'absolute';
		controlUI.title =  'You current location'; 
		
		if($(window).width()>768){
			controlUI.style.left =  '0px';
			controlUI.style.top =  '-10px'; 
		}else{
			controlUI.style.right =  '0px';
			// controlUI.style.top =  '-40px'; 
		}
		controlUI.style.textAlign =  'center';
		controlUI.style.backgroundImage = 'url(/img_default/location.png)';
		controlUI.style.backgroundSize = '220px 22px';
		controlDiv.appendChild(controlUI);

		// Set CSS for the control interior.
		// var controlImg = document.createElement('img');
		// controlImg.src = '/img_default/location.png';
		// controlImg.style.maxHeight = '100%';
		// controlImg.style.maxWidth = '100%';
		// controlImg.style.height = '25px';
		// controlUI.appendChild(controlImg);

		// Setup the click event listeners: simply set the map to Chicago.
		controlUI.addEventListener('click', function() {
			var location = new google.maps.LatLng(first_latlng.lat, first_latlng.lng);
			map.setCenter(location);
			placeMarker(location);
		});

		controlUI.addEventListener('mouseover', function() {
			if($(window).width()>768)
				this.style.backgroundPositionX = '44px';
		});
		controlUI.addEventListener('mouseout', function() {
			if($(window).width()>768)
				this.style.backgroundPositionX = '0px';
		});
	}

	function initMap(pos,first){
		var first = first || false;
		// var bounds = new google.maps.LatLngBounds();
		if(!map){
			map = new google.maps.Map(document.getElementById('gmap'), mapObject);
			// map.getUiSettings().setMyLocationButtonEnabled(true);
			if(!centerMarker){
				centerMarker = new CustomMarker(
					new google.maps.LatLng(pos),
					map, { title: "center" }
				);
			}
		}

		if(first){
			map.setCenter(pos);
			map.set('oldCenter',map.getCenter())
		}
		mapCircle.setMap(map);
		mapCircle.setCenter(pos);


		var centerControlDiv = document.createElement('div');
		var centerControl = new CenterControl(centerControlDiv, map);

		centerControlDiv.index = 2;
		map.controls[position_icon].push(centerControlDiv);
		map.set('im_dragging',false);
		// var bounds = new google.maps.LatLngBounds();
		// for(i=0;i<lstMarker.length;i++) {
		//    bounds.extend(lstMarker[i].getPosition());
		// }
		// // map.setCenter(bounds.getCenter());

		// map.fitBounds(bounds);

		
		//if map loaded success load list marker
		// google.maps.event.addListener(map,'idle',function(e){
		// 	if(!this.get('dragging') && this.get('oldCenter') && this.get('oldCenter')!==this.getCenter()) {
		// 		addMarker();
		// 		drag_load = true;
		// 	}

		// 	if(!this.get('dragging')){
		// 		if(drag_load && calculateDistance(this.get('oldCenter'),this.getCenter())){
		// 			placeMarker(this.getCenter());
		// 		}
		// 		this.set('oldCenter',this.getCenter())
		// 	}
		// });

			google.maps.event.addListener(map, 'click', function(e) {
				var event = window.event || e;
				// event.preventDefault();
				if (!$(event.target).is('.room-price-pin, .room-price-pin *')){
					resetLocation();
					placeMarker(e.latLng);
					map.setCenter(e.latLng);
				}
			});

			// if($(window).width()<1025){
			// 	google.maps.event.addListener(map, 'mouseup', function(e) {
			//  		var event = window.event || e;
			//  		if(map.get('im_dragging') === true){
			//  			// event.preventDefault();
			//  			map.set('im_dragging',false);
			//  		}else{
			//  			if (!$(event.target).is('.room-price-pin, .room-price-pin *')){
			// 				placeMarker(e.latLng);
			// 				map.setCenter(e.latLng);
			// 			}
			//  		}
			// 	});

			// 	google.maps.event.addListener(map, 'drag', function(e) {
			// 		map.set('im_dragging',true);
			// 	});
		 //  }	



		// google.maps.event.addListener(map, 'dblclick', function(event) {
		// 	 placeMarker(event.latLng);
		// });

		// if($(window).width()<1025){
		// 	google.maps.event.addListener(map,'dragstart',function(){
		// 		this.set('dragging',true);
		// 	});

		// 	google.maps.event.addListener(map,'dragend',function(){
		// 		if(calculateDistance(this.get('oldCenter'),this.getCenter())){
		// 			placeMarker(this.getCenter());
		// 		}
		// 		this.set('oldCenter',this.getCenter())
		// 	});
		// }

		
		

		google.maps.event.addListener(map, 'bounds_changed', function(event) {
				//console.log(this.getZoom());
				if(this.getZoom()>16 && first_load){
					map.setZoom(16);
				}
				if(this.getZoom()>19){
					map.setZoom(19);
				}
				first_load = false;

				if(this.getZoom()<13){
					mapCircle.setOptions({fillOpacity:0});
				}else{
					mapCircle.setOptions({fillOpacity:0.25});
				}
		});


		$(document).on('mousemove', '.room-price-pin', function(event) {
				event.preventDefault();
				var selector = $(this).attr('href')?$(this).attr('href'):$(this).attr('posthref');
				$(this).addClass('active');
				$(this).siblings().removeClass('active');
		});
		
		$('.post-horizontal').hover(function() {
				var id = $(this).attr('id');
				if ($('.room-price-pin').hasClass('active')) {
						$('.room-price-pin').removeClass('active');
						$('.room-price-pin[data-id="' + id + '"]').addClass('active');
				} else {
						$('.room-price-pin[data-id="' + id + '"]').addClass('active');
				}
		}, function() {
				$('.room-price-pin').removeClass('active');
		});

		$('#gmap').on('mousemove', function(event) {
				event.preventDefault();
				var _that = event.target;
				if ($(_that).is('.room-price-pin')) {
						var selector = $(_that).data('target');
						$(selector).addClass('active');
						$(selector).siblings().removeClass('active');
				} else {
						$('.list-restaurant .post-horizontal').removeClass('active');
				}
		});

		$(document).on('click', '.room-price-pin', function(event) {

				event.preventDefault();
				var selector = $(this).attr('href')?$(this).attr('href'):$(this).attr('posthref');
				if(selector && $(window).width() > 768){
					window.location = selector;
				}
		});

		$(document).on('click', '.room-price-pin a', function(event) {

				event.preventDefault();
				var selector = $(this).attr('href')?$(this).attr('href'):$(this).attr('posthref');
				if(selector){
					window.location = selector;
				}
		});
		$(document).on('touchstart', '.room-price-pin', function(event) {

				event.preventDefault();
				var selector = $(this).attr('href')?$(this).attr('href'):$(this).attr('posthref');
				if(selector && $(window).width() > 768){
					window.location = selector;
				}
		});

		$(document).on('touchstart', '.room-price-pin a', function(event) {

				event.preventDefault();
				var selector = $(this).attr('href')?$(this).attr('href'):$(this).attr('posthref');
				if(selector){
					window.location = selector;
				}
		});
	}

	function calculateDistance(oldCor, newCor){
		var o_lat = oldCor.lat();
		var o_lng = oldCor.lng();
		var n_lat = newCor.lat();
		var n_lng = newCor.lng();
		var p = 0.017453292519943295;    // Math.PI / 180
		var c = Math.cos;
		var a = 0.5 - c((n_lat - o_lat) * p)/2 + 
						c(o_lat * p) * c(n_lat * p) * 
						(1 - c((n_lng - o_lng) * p))/2;

		var d = 12742 * Math.asin(Math.sqrt(a));
		// //console.log(d);
		if(d>0.39){
			return true;
		}else{
			return false;
		}
	}

	function addMarker() {
		clearMarker();
		var bounds = new google.maps.LatLngBounds();
		if(centerMarker)
			centerMarker.remove();
		for (var i = 0; i < lstAdd.length; i++) {
			var obj = lstAdd[i];
			var latlgn = obj.center.split(',');
			var marker = new CustomMarker(
					new google.maps.LatLng(latlgn[0], latlgn[1]),
					map, obj
			);
			bounds.extend(new google.maps.LatLng(latlgn[0], latlgn[1]));
			lstMarker.push(marker);
		}
		
		drag_load = false;
		$(".client-location").remove();
		centerMarker = new CustomMarker(
			new google.maps.LatLng(pos),
			map, { title: "center" }
		);
		// console.log(pos);
		var new_pos = new google.maps.LatLng(pos.lat, pos.lng);
		bounds.extend(new_pos);
		map.setCenter(new_pos);
		mapCircle.setCenter(new_pos);
		// console.log(lstAdd);
		if(lstAdd.length==0){
			map.setCenter(new_pos);
		}else{
			map.fitBounds(bounds);
		}
	}

	function clearMarker() {
		for (var i = 0; i < lstMarker.length; i++) {
				lstMarker[i].setMap(null);
		}
		 lstMarker=[];
	}

	function placeMarker(location) {
		currentPage = 1;
		max_page = true;
		stop = true;
		pos = {
			lat: parseFloat(location.lat()),
			lng: parseFloat(location.lng())
		};
		lat = pos.lat;
		lng = pos.lng;
		window.sessionStorage.setItem('currentLocation', lat+','+lng);
		currentLocation = lat+','+lng;

		// marker.remove();
		// marker = new CustomMarker(
		// 	new google.maps.LatLng(pos),
		// 	map, { title: "center" }
		// );

		loadContent(currentPage,true,false,extra_type);
		// //console.log('xx');
	}
	

	function getLocation() {
		// map = new google.maps.Map(document.getElementById('gmap'), {
		// 	zoom : 16,
		// 	center : new google.maps.LatLng(10.806273,106.714477)
		// });
		@if(!app('request')->input('currentLocation'))

			@if(\Session::has('currentLocation'))
				var coord = "{{Session::get('currentLocation')}}";
				coord = coord.split(',');
				var lat = parseFloat(coord[0]);
				var lng = parseFloat(coord[1]);
				var position = {
					coords:{
						latitude: lat,
						longitude: lng
					}
				};
				setPosition(position);
				// navigator.geolocation.getCurrentPosition(
				// 	function(position){
				// 		lat = position.coords.latitude.toFixed(6);
				// 		lng = position.coords.longitude.toFixed(6);
				// 		first_latlng = {
				// 			lat: parseFloat(lat),
				// 			lng: parseFloat(lng)
				// 		};
				// 	},
				// 	function(){
				// 		$.getJSON("/getLocation", function(data) {
				// 			lat = parseFloat(data.latitude).toFixed(6);
				// 			lng = parseFloat(data.longitude).toFixed(6);
				// 			first_latlng = {
				// 				lat: parseFloat(lat),
				// 				lng: parseFloat(lng)
				// 			};
				// 		});
				// 	},
				// 	{ maximumAge: 100000, timeout: 3000, enableHighAccuracy: true });
				// if(navigator.permissions === undefined){
				// 	if(navigator.geolocation===undefined){
				// 		getLocationByIP()
				// 	}else{
				// 		navigator.geolocation.getCurrentPosition(setPosition,getLocationByIP);
				// 	}
				// }else{
				// 	navigator.permissions.query({'name': 'geolocation'})
				// 	.then(function(permission){
				// 		if (permission.state === 'granted') {
				// 			if (navigator.geolocation) {
				// 				navigator.geolocation.getCurrentPosition(setPosition,getLocationByIP);
				// 			} else {
				// 				getLocationByIP()
				// 			}
				// 		}else if(permission.state === 'denied'){
				// 			getLocationByIP()
				// 		}else if(permission.state === 'prompt'){
				// 			navigator.geolocation.getCurrentPosition(setPosition,getLocationByIP);
				// 		}else{
				// 			getLocationByIP()
				// 		}
				// 	});
				// }
			@else
			if(navigator.permissions === undefined){
				if(navigator.geolocation===undefined){
					getLocationByIP()
				}else{
					navigator.geolocation.getCurrentPosition(setPosition,getLocationByIP);
				}
			}else{
				navigator.permissions.query({'name': 'geolocation'})
				.then(function(permission){
					if (permission.state === 'granted') {
						if (navigator.geolocation) {
							navigator.geolocation.getCurrentPosition(setPosition,getLocationByIP);
						} else {
							getLocationByIP()
						}
					}else if(permission.state === 'denied'){
						getLocationByIP()
					}else if(permission.state === 'prompt'){
						navigator.geolocation.getCurrentPosition(setPosition,getLocationByIP);
					}else{
						getLocationByIP()
					}
				});
			}
			@endif
		@else
			var coord = "{{app('request')->input('currentLocation')}}";
			coord = coord.split(',');
			var lat = parseFloat(coord[0]);
			var lng = parseFloat(coord[1]);
			var position = {
				coords:{
					latitude: lat,
					longitude: lng
				}
			};
			setPosition(position);
		@endif
	}


	function setPosition(position){
		lat = position.coords.latitude.toFixed(6);
		lng = position.coords.longitude.toFixed(6);

		//console.log("Current location setPosition: "+lat+' '+lng);
		pos = {
			lat: parseFloat(lat),
			lng: parseFloat(lng)
		};
		first_latlng = {
			lat: parseFloat(lat),
			lng: parseFloat(lng)
		};
		// @if(!app('request')->input('currentLocation'))
		// first_latlng = {
		// 	lat: parseFloat(lat),
		// 	lng: parseFloat(lng)
		// };
		// @endif
		map_data['center'] = lat+','+lng;
		window.sessionStorage.setItem('currentLocation', lat+','+lng);
		currentLocation = lat+','+lng;
		
		initMap(pos,true);
		addMarker();
		setScroll();
		var _choosen = [];
		_choosen.push({name:'q',value:$("input#project").val()});
		
		if($("#category_search option:selected").length){
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
				_choosen.push({name:'category_item',value:arr_category_item_text});
			}
		}
    if($("#service_search option:selected").length){
      var arr_service = [];
      var arr_service_text = [];
      $("#service_search option:selected").each(function(i){
        arr_service.push($(this).val());
        arr_service_text.push($(this).text());
      })
      if(arr_service.length){
        var str = arr_service.join(',');
        _choosen.push({name:'service',value:arr_service_text});
      }
    }
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
		// loadContentSearch();
	}

	function loadContent(page, newload, loadMore, extra_type_data){
		var newload = newload || false;
		var loadMore = loadMore || false;
		var extra_type_data = extra_type_data || '';
		if(max_page){
			if(loadMore){
				currentPage++;
			}

			if(extra_type_data != ''){
				extra_type = extra_type_data;
			}else{
				extra_type_data = extra_type;
			}

			@if($category_item)
			$('.view-all').attr('href',"{{url('/')}}/list/{{$category->alias}}/{{$category_item->alias}}/#"+extra_type_data)
			@endif
			clearMarker();
			loadContentSearch(newload,false);
		}
	}
	function create_badge(arr){
		var html = '';
		// //console.log(arr);
		$.each(arr,function(key,value){
			////console.log(key, typeof value.value);
			if (typeof value.value === 'string' || value.value instanceof String)
				html+='<span class="badge badge-danger">'+value.value+'</span>';
			else{
				$.each(value.value,function(key,value2){
					html+='<span class="badge badge-danger">'+value2+'</span>';
				});
			}	
		})
		return html;
	}
	setScroll();

	getLocation();

	function setScroll(){
		$('.post-horizontal').hover(function() {
				var id = $(this).attr('id');
				if ($('.room-price-pin').hasClass('active')) {
						$('.room-price-pin').removeClass('active');
						$('.room-price-pin[data-id="' + id + '"]').addClass('active');
				} else {
						$('.room-price-pin[data-id="' + id + '"]').addClass('active');
				}
		}, function() {
				$('.room-price-pin').removeClass('active');
		});

		// end select categories on mobile
		// $(".container-maps-page .container-siderbar").mCustomScrollbar({
		// 		theme: "dark",
  //       contentTouchScroll: true,
  //       mouseWheel:{ scrollAmount: 160 }
		// //     callbacks:{
		// 		// 	onTotalScrollOffset:150,
		// 		// 	onTotalScroll:function(){
		// 		// 		loadContent(currentPage,false, true);
		// 		// 	}
		// 		// }
		// });

		// setTimeout(function(){
			var redundancy = $('#header').height() + $('#footer').height();
			var height_content_maps = $(window).height() - redundancy;
			$('.container-maps-page .content-maps-page').css("height", height_content_maps);

			var height_sidebar_map = height_content_maps - $('.header-sider-bar').height();
			$('.container-siderbar').css('height', height_sidebar_map);
			// if($(window).width() > 768){
			// 	var width_map = $(window).width()- $(".siderbar-left").width();
			// 	var left_map = $(".siderbar-left").width()+30;
			// 	$("#gmap").width(width_map);
			// 	$("#gmap").css({'margin-left':left_map+'px'});
			// }
			
		// },1500)
		
	}

	searchKeyword = function(){
		// setTimeout(function(){
			// var time_ads = 3;
			// $("#timer_ads").text(time_ads);
			// $("#popup_ads").show();
			// var watch_ads = setInterval(function(){
			// 	 time_ads--;
			// 	 $("#timer_ads").text(time_ads);
			// 	 if(time_ads===0){
			// 		$("#popup_ads").hide();
			// 		clearInterval(watch_ads);
			// 	 }
			// },1050)
		// },3000)
		window.event.preventDefault();
		if($("#project").val().length){
			getAds();
			changeKeyword($("#project"));
		}
	}
		


	function getLocationByIP(error){
		if(error){
			console.log(error);
		}else{
			alert('{{trans("global.alert_gps")}}');
		}
		
		$.getJSON("/getLocation", function(data) {
			lat = parseFloat(data.latitude).toFixed(6);
			lng = parseFloat(data.longitude).toFixed(6);
			//console.log("Current location getLocationByIP: "+lat+' '+lng);
			pos = {
				lat: parseFloat(lat),
				lng: parseFloat(lng)
			};
			first_latlng = {
				lat: parseFloat(lat),
				lng: parseFloat(lng)
			};
			// @if(!app('request')->input('currentLocation'))
			// first_latlng = {
			// 	lat: parseFloat(lat),
			// 	lng: parseFloat(lng)
			// };
			// @endif
			map_data['center'] = lat+','+lng;
			window.sessionStorage.setItem('currentLocation', lat+','+lng);
			currentLocation = lat+','+lng;
			// loadContent(currentPage);
			initMap(pos,true);
			addMarker();
			setScroll();
			var _choosen = [];
			_choosen.push({name:'q',value:$("input#project").val()});
			
			if($("#category_search option:selected").length){
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
					_choosen.push({name:'category_item',value:arr_category_item_text});
				}
			}
	    if($("#service_search option:selected").length){
	      var arr_service = [];
	      var arr_service_text = [];
	      $("#service_search option:selected").each(function(i){
	        arr_service.push($(this).val());
	        arr_service_text.push($(this).text());
	      })
	      if(arr_service.length){
	        var str = arr_service.join(',');
	        _choosen.push({name:'service',value:arr_service_text});
	      }
	    }
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

		});
	}
</script>

<script type="text/javascript">
	jQuery(document).ready(function() {
			// sticky
			var window_width = $(window).width();
			make_sticky();
			function make_sticky() {
					$(".form-search-restaurant").stick_in_parent({
							parent: '.content-search-page',
							offset_top: 90
					});
			}
			$('.list-menu-categories-mobile .btn-show-list').click(function(event) {
					event.preventDefault();
					$('.container-maps-page .siderbar-left').toggleClass('show');
					$(".container-maps-page .menu-cate-show-map").slideToggle('fast');
					$(".container-maps-page .come-back-map").slideToggle('fast');
			});
			$(".container-maps-page .come-back-map").click(function(event) {
					event.preventDefault();
					$('.container-maps-page .siderbar-left').toggleClass('show');
					$(".container-maps-page .menu-cate-show-map").slideToggle('fast');
					$(".container-maps-page .come-back-map").slideToggle('fast');
					var show = $('.view-list-search').attr('data-show');
					if(show=='show'){
						$('.view-list-search').attr('data-show','hide')
						$("#text_list .change_text").html("{{trans('global.map')}}");
                        $("#text_list i").removeClass('icon-list').addClass('icon-location');
					}else{
						$('.view-list-search').attr('data-show','show')
						$("#text_list .change_text").html("{{trans('global.list')}}");
                        $("#text_list i").removeClass('icon-location').addClass('icon-list');
					}
					loadAjax({
						url: '/save-cookie',
						type: 'GET',
						data:{
							'show_list_map': show
						},
						success:function(res){
							// console.log(res);
						}
					});
			});

			$('.list-menu-categories-mobile ul li').click(function(event) {
					var value = $(this).data('value');
					$(this).closest('ul').slideUp('fast').next('input').val(value);
					$(this).closest('.list-menu-categories-mobile').find('.btn-select').text($(this).text());
					// console.log($('.list-menu-categories-mobile input').val());
			});

	});
</script>

<script>
	suggestSearch = function(obj){
		return false;
	}

	viewList = function(){
		// alert(current_url);
		var url_list = current_url.toString().replace('search2','search');
		window.location = url_list;
	}
	
	changeCountrySearch = function(obj){
			var country = $(obj).val();
			$(".country_search").val(country);
			current_page = 1;
			max_page = true;
			loadCitySearch(country);
			// alert('change country')
			// loadContentSearch();
		}

	loadCitySearch=	function(country){
			loadAjax({
				url : '/search/loadCity',
				type: 'POST',
				data: {
					_token: _token,
					country: country
				},
				success: function(response){
					$(".city_search").html(response);
          render_select('#city_step','#city_search');
					// $(".city_search").trigger("change");
				}
			})
		}

	changeCitySearch=	function(obj){
			var city = $(obj).val();
			$(".city_search").val(city);
			current_page = 1;
			max_page = true;
			loadDistrictSearch(city);
			// changeCurrentLocation(loadContentSearch(true,false));
		}

	loadDistrictSearch=	function(city){
			loadAjax({
				url : '/search/loadDistrict',
				type: 'POST',
				data: {
					_token: _token,
					city: city
				},
				success: function(response){
					$(".district_search").html(response);
          render_select('#district_step','#district_search');
					//$(".district_search").trigger("change");
				}
			})
		}

	changeDistrictSearch=	function(obj){
			var district = $(obj).val();
			$(".district_search").val(district);
			current_page = 1;
			max_page = true;
			// alert('change district')
			changeCurrentLocation();
		}

	changeCategorySearch=	function(obj){
			var category = $(obj).val();
			$(".category_search").val(category);
			current_page = 1;
			max_page = true;
      // console.log('category: ',category,$(obj).html(),$(obj).val());
			loadCategoryItemSearch(category);
			// alert('change category')
			// loadContentSearch();
		}

	loadCategoryItemSearch=	function(category){
			loadAjax({
				url : '/search/loadCategoryItemNew',
				type: 'POST',
				data: {
					_token: _token,
					category: category,
          category_item_selected:category_item_selected,
          service_selected:service_selected
				},
				success: function(response){
          $(".category_item_search").html(response.category_item);
					$(".service_search").html(response.service);
          render_select('#category_item_step','#category_item_search');
          render_select('#service_step','#service_search');
				}
			})
		}

		chooseCategoryItem = function(obj){
			$(".category_item_search").val($(obj).val());
			// $(".category_item_search").trigger("change");
			current_page = 1;
			max_page = true;
			// alert('change item')
			loadContentSearch();
		}

		changeKeyword = function(obj){
			var key = $(obj).val();
			// $("input#project").val(key);
			current_page = 1;
			max_page = true;
			// alert('chaneg keyword')
			// if($("#district_search").val() || $("#city_search").val()){
			// 	changeCurrentLocation(loadContentSearch(true,false));
			// }else{
			// 	loadContentSearch();
			// }
			keyword_has_changed = true;
			// navigator.geolocation.getCurrentPosition(setPosition,getLocationByIP);
			loadContentSearch();
		}



		function setHeightMap(){
			if($(window).width()>768){
				if($(window).width()>1024){
					var height_map = $(window).height() - $("#choosen_value").height() -157 ;
					// console.log(143)
				}else{
					var height_map = $(window).height() - $("#choosen_value").height() -213 ;
					// console.log(213)
				}

				
				$("#gmap").height(height_map);
				if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
					$("#gmap").css('min-height',height_map);
				}
			}else{
				var height_map = $(window).height() - $(".mobile_badge").height() -  $("#pick_value").height() - $("#header").height() - $("#footer").height() - 40;
				$("#gmap").height(height_map);
				if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
					$("#gmap").css('min-height',height_map);
				}
			}
		}

		var _token = $("meta[name='_token']").prop('content');
		var current_page = 1;
		var stop = true;
		var totalPage = {{$totalPage?$totalPage:1}};
		var current_url = window.location;
		currentLocation = window.sessionStorage.getItem('currentLocation')?window.sessionStorage.getItem('currentLocation'):false;
		loadContentSearch = function(loadNew, loadMore){
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
				query.push({name:'_token',value:_token});
				if($("input#project").val()){
					$("#view_list").css({'display':'inline-block'});
					query.push({name:'q',value:$("input#project").val()});
					_choosen.push({name:'q',value:$("input#project").val()});
				}
				if($("#type_search").val()){
					query.push({name:'type',value:$("#type_search").val()});
				}
				// alert(keyword_has_changed);

				if(!keyword_has_changed){
						if(currentLocation){
							query.push({name:'currentLocation',value:currentLocation});
						}
						if(current_page){
							query.push({name:'page',value:current_page});
						}else{
							query.push({name:'page',value:1});
						}
						
						if($("#category_search option:selected").length){
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

            if($("#service_search option:selected").length){
              var arr_service = [];
              var arr_service_text = [];
              $("#service_search option:selected").each(function(i){
                arr_service.push($(this).val());
                arr_service_text.push($(this).text());
              })
              if(arr_service.length){
                var str = arr_service.join(',');
                query.push({name:'service',value:str});
                _choosen.push({name:'service',value:arr_service_text});
              }
            }

						if($("#district_search").val()){
							query.push({name:'district',value:$("#district_search").val()});
							//_choosen.push({name:'district',value:$("#district_search option:selected").text()});
						}

						if($("#city_search").val()){
							query.push({name:'city',value:$("#city_search").val()});
							//_choosen.push({name:'city',value:$("#city_search option:selected").text()});
						}

						if($("#country_search").val()){
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
				}else{
					resetSelect();
					// console.log("Fuck:", keyword_has_changed,first_latlng);
					pos = first_latlng;
					var find_location = pos.lat+','+pos.lng;
					// console.log("pos: ",pos);
					// console.log("first_latlng: ",first_latlng);
					if(currentLocation){
						query.push({name:'currentLocation',value:find_location});
					}
					$.ajax({
						url: 'https://maps.googleapis.com/maps/api/geocode/json?latlng='+find_location+"&language=vi&key=AIzaSyCCCOoPlN2D-mfrYEMWkz-eN7MZnOsnZ44",
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
				}
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
				history.pushState({}, null, url);
				current_url = url;
				loadAjax({
					url : '/search2',
					type: 'POST',
					scriptCharset: "utf-8" ,
					contentType: "application/x-www-form-urlencoded; charset=UTF-8",
					data: query,
					beforeSend: function(){
						max_page = false;
						stop = false;
						$("#loading").show();
						clearMarker();
					},
					success: function(response){
						setTimeout(function(){
							$("#loading").hide();
						},500);

						$("#keyq").text('"'+response.q+'"');
						clearMarker();


						stop = true;
						max_page = true;
						$("#total_content").html(response.total_content)
						$("#total").html(response.total_content)

						if(loadNew){
							$("#listContent").html(response.html);
							lstAdd = [];
							lstAdd = lstAdd.concat(JSON.parse(response.json));
							first_load = true;
						}else{
							$("#listContent").append(response.html);
							lstAdd = lstAdd.concat(JSON.parse(response.json));
						}

						addMarker();
						setScroll();

						// currentPage = response.nextPage;
						if(currentPage==totalPage){
							max_page = false;
						}
						setHeightMap();
						keyword_has_changed = false;
					}
				})
			}
		}

	changeCurrentLocation = function(callback){
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
			beforeSend:function(){
				$("#loading").show();
			},
			success: function(res){
				$("#loading").hide();
				if(res.results.length && res.results[0] && res.results[0]['geometry'] && res.results[0]['geometry']['location']){
					lat = res.results[0]['geometry']['location']['lat'];
					lng = res.results[0]['geometry']['location']['lng'];
					pos = {
						lat: parseFloat(lat),
						lng: parseFloat(lng)
					};
					var location = new google.maps.LatLng(lat, lng);
					placeMarker(location);
					map.setCenter(location);
				}
			}
		})
	}
	function resetLocation(){
		$("#district_search").val(0);
		$("#city_search").val(0);
		$(".country_search").val(0);

    
    $('.location #country_step h2.select-nav').text("{{trans('global.country')}}");
   
    $('.location .select-step3 .select-nav').removeClass('active');
    $('.location .select-step2 .select-nav').removeClass('active');
    $('.location .select-step1 .select-nav').removeClass('active');

    $('.location .select-step3 .select').hide();
    $('.location .select-step2 .select').hide();
    $('.location .select-step1 .select').hide();

    $('.location .select-step3').hide();
    $('.location .select-step2').hide();
    $('.location .select-step1').show();

    render_select('#country_step','#country_search');
    render_select('#city_step','#city_search');
    render_select('#district_step','#district_search');
	}
	function resetSelect(){
		// $("#category_search").val(0);
		$("#category_search option").attr('selected',false);
		$(".category_item_search").html(0);
		resetLocation();
		// showHideNextBack();
		setTimeout(function(){
			var container = $(".ui-autocomplete");
			container.hide();
			container.hide();
		},2000);
	}

	function getAds(){
		$.ajax({
			url: '/ads/getAds',
			type: 'GET',
			data:{
				type_ads:'quang_cao_popup_trang_map',
				limit: 1
			},
			success:function(res){
				$("#popup_ads .body_ads img").attr('src',res.ads[0]?res.ads[0].image:res.type_ads.img_default);
				var time_ads = 3;
				$("#timer_ads").text(time_ads);
				$("#popup_ads").show();
				var watch_ads = setInterval(function(){
					 time_ads--;
					 $("#timer_ads").text(time_ads);
					 if(time_ads===0){
						$("#popup_ads").hide();
						clearInterval(watch_ads);
					 }
				},1050);
			}
		})
	}
</script>
<script type="text/javascript">
	/**
	 *
	 * Avoid dropdown menu close on click inside
	 *
	 */

	$('.form-search-advanced > .mega-dropdown > a').on('click', function(event) {
			var group_dropdown = $(this).attr('data-group');
			if(group_dropdown == 'category'){
				$('.form-search-advanced > .mega-dropdown.location').removeClass('show');
			}
			if(group_dropdown == 'location'){
				$('.form-search-advanced > .mega-dropdown.category').removeClass('show');
			}
			//$('.form-search-advanced > .mega-dropdown').removeClass('show');
			$(this).parent().toggleClass('show');
	});
	$('body').on('click', function(e) {
			if (!$('.form-search-advanced > .mega-dropdown').is(e.target) && 
					!$(e.target).hasClass('select2-selection__choice__remove') &&
					!$(e.target).hasClass('select2-results__option') &&
					$('.form-search-advanced > .mega-dropdown').has(e.target).length === 0 &&
					$('.show').has(e.target).length === 0
			) {
					$('.form-search-advanced > .mega-dropdown').removeClass('show');
			}
	});
	/**
	 *
	 * end Avoid dropdown menu close on click inside
	 *
	 */

	/**
	 *
	 * custom select cate search
	 *
	 */

/*	$(".select-cate-search").select2({
			tags: true
	});*/
	/**
	 *
	 * end custom select cate search
	 *
	 */

	/**
	 *
	 * display show map or view list
	 *
	 */
	@if(request()->cookie('show_list_map')=="show")
		$(function(){
			$('.container-maps-page .siderbar-left').toggleClass('show');
			$(".container-maps-page .menu-cate-show-map").slideToggle('fast');
			$(".container-maps-page .come-back-map").slideToggle('fast');
			$('#gmap').toggleClass('gmap_small');

		});
	@endif
	$(".view-list-search").click(function(event) {
			event.preventDefault();
			$('.container-maps-page .siderbar-left').toggleClass('show');
			$(".container-maps-page .menu-cate-show-map").slideToggle('fast');
			$(".container-maps-page .come-back-map").slideToggle('fast');
			$('#gmap').toggleClass('gmap_small');
			var show = $('.view-list-search').attr('data-show');
			if(show=='show'){
				$('.view-list-search').attr('data-show','hide')
				$("#text_list .change_text").html("{{trans('global.map')}}");
                $("#text_list i").removeClass('icon-list').addClass('icon-location');
			}else{
				$('.view-list-search').attr('data-show','show')
				$("#text_list .change_text").html("{{trans('global.list')}}");
                $("#text_list i").removeClass('icon-location').addClass('icon-list');
			}
			loadAjax({
				url: '/save-cookie',
				type: 'GET',
				data:{
					'show_list_map': show
				},
				success:function(res){
					console.log(res);
				}
			});

	});
	

	/**
   *
   * start select step
   *
  */

  $('.select-next-step .next').click(function(e){
  		e.preventDefault();
  		max_page = true;
      var select_next = $(this).closest('.select-custom-li');
      if(select_next.hasClass('select-step3') === true){
          select_next.find('.select').slideUp('400');
          select_next.find('.select-nav').removeClass('active');
          loadContentSearch();
      } else {
          select_next.css('display', 'none');
          select_next.next().css('display', 'block');
          select_next.next().find('.select').css('display', 'block');
          select_next.next().find('.select-nav').addClass('active');
      }
      showHideNextBack()
  });
   
   // prev

  $('.select-next-step .prev').click(function(e){
  		max_page = true;
      var select_prev = $(this).closest('.select-custom-li');
      e.preventDefault();
      if(select_prev.hasClass('select-step1')){
          var abc = 1;
      } else {
          select_prev.css('display', 'none');
          select_prev.prev().css('display', 'block');
          select_prev.prev().find('.select').css('display', 'block');
          select_prev.prev().find('.select-nav').addClass('active');
      }
      showHideNextBack();
  });


</script>
<script>
	$(function(){
		// $(".category_item_search").select2({
		// 	dropdown:true,
		// 	language : {
		// 		noResults : function(params) {
		// 				return "{{trans('global.no_content')}}";
		// 		}
		// 	}
		// });
		$('.custom-gmap').css('opacity',1);
		$(window).resize(setScroll);
		// setTimeout(function(){
		// 	$('.custom-gmap').css('opacity',1);
		// 	@if($district)
		// 		changeCurrentLocation(loadContentSearch(true,false));
		// 	@else
		// 		@if($city)
		// 			changeCurrentLocation(loadContentSearch(true,false));
		// 		@else
		// 			clearMarker();
		// 			loadContentSearch(false,false);
		// 		@endif
		// 	@endif
		// },3000)
    render_select('#category_step','#category_search');
    render_select('#category_item_step','#category_item_search');
    render_select('#service_step','#service_search');

    render_select('#country_step','#country_search');
    render_select('#city_step','#city_search');
    render_select('#district_step','#district_search');

    showHideNextBack();

	})
</script>

<script>
	function render_select(target,source){
    var select = $(source);
    var multiple = $(source).attr('multiple');
    var html = '';
    if(multiple){
      var content = $(target).find('.content');
      $(source).find('option').each(function(key,elem){
        var selected = $(elem).attr('selected');
        if(selected){
          html+='<div class="form-group">';
          html+=  '<label class="custom-control custom-checkbox">';
          html+=    '<input type="checkbox" class="custom-control-input" checked data-value="'+$(elem).attr('value')+'" onchange="trigger_select(this,\''+source+'\')">';
          html+=    '<span class="custom-control-indicator"></span>';
          html+=    '<span class="custom-control-description"> '+$(elem).text()+'</span>';
          html+=  '</label>';
          html+='</div>';
        }else{
          html+='<div class="form-group">';
          html+=  '<label class="custom-control custom-checkbox">';
          html+=    '<input type="checkbox" class="custom-control-input" data-value="'+$(elem).attr('value')+'" onchange="trigger_select(this,\''+source+'\')">';
          html+=    '<span class="custom-control-indicator"></span>';
          html+=    '<span class="custom-control-description"> '+$(elem).text()+'</span>';
          html+=  '</label>';
          html+='</div>';
        }
      });
      content.html(html);
    }else{
      var content = $(target).find('ol');
      $(source).find('option').each(function(key,elem){
      	if($(elem).html()){
	        var selected = $(elem).attr('selected');
	        if(selected){
	          html+='<li class="active" data-value="'+$(elem).attr('value')+'" onclick="trigger_select(this,\''+source+'\')">'+$(elem).text()+'</li>';
	        }else{
	          html+='<li data-value="'+$(elem).attr('value')+'" onclick="trigger_select(this,\''+source+'\')">'+$(elem).text()+'</li>';
	        }
	      }
      });
      content.html(html);
    }
    apply_select(target);
	}

  function trigger_select(obj,source){
    var value = $(obj).attr('data-value');
    var multiple = $(source).attr('multiple');
    if(!multiple){
      $(source).find('option').attr('selected',false);
      $(source).find('option[value='+value+']').attr('selected',true);
    }else{
      var selected = $(obj).is(':checked');
      $(source).find('option[value='+value+']').attr('selected',selected);
    }

    if(source=='#category_search' || source=='#country_search' || source=='#city_search' || source=='#district_search'){
      setTimeout(function(){
      	if($(source).val()){
      		$(source).trigger('change');
      	}
        showHideNextBack();
      },150)
    }

    if(source=='#category_search'){
    	$('.category h2.select-nav').text($(source).find('option[value='+value+']').text());
    	// return false;
    }

    if(source=='#district_search'){
    	$('.location #district_step h2.select-nav').text($(source).find('option[value='+value+']').text());
    	// return false;
    }
  }

  function apply_select(target){
    var nav = $('.select-custom-li .select-nav');
    var selection = $('.select-custom-li .select');
    var select = selection.find('li');

    // nav.click(function(event) {
    //     $('.select-custom-li .select').css('display', 'none');
    //     $('.select-custom-li .select-nav').removeClass('active');
    //     if (nav.hasClass('active')) {
    //         nav.removeClass('active');
    //         $(this).next().stop().slideUp(200);
    //     } else {
    //         $(this).addClass('active');
    //         $(this).next().stop().slideDown(200);
    //     }
    //     // event.preventDefault();
    // });

    select.click(function(event) {
        // updated code to select the current language
        select.removeClass('active');
        $(this).addClass('active');

        // alert ("location.href = 'index.php?lang=" + $(this).attr('data-value'));
    });

    $('.select-custom-li ol li').click(function(e){
      var select_next = $(this).closest('.select-custom-li');
      e.preventDefault();
      if(select_next.hasClass('select-step3')){
          select_next.find('.select').slideUp('400');
          select_next.find('.select-nav').removeClass('active');
      } else {
          select_next.css('display', 'none');
          select_next.next().css('display', 'block');
          select_next.next().find('.select').css('display', 'block');
      }

    });
    if(target=='#category_step' || target=='#country_step' || target=='#city_step' || target=='#district_step'){
    	// cai nay o category, country, city, district
    	$(target+" .scroll-content").mCustomScrollbar({
        theme: "dark",
        contentTouchScroll: true,
        mouseWheel:{ scrollAmount: 160 }
    	});
    }else{
    	// cai nay o category con và dich vu
    	$(target+" .scroll-content").mCustomScrollbar({
        theme: "dark",
        contentTouchScroll: true,
        mouseWheel:{ scrollAmount: 160 },
        setHeight: "300px"
    	});
    }

  }

  function showHideNextBack(){

  	if($("#category_search").val() !== ""){
  		$("#category_step .select-next-step").show().addClass('d-flex');
  		$("#category_step .select").css({'height':'auto'})
  	}else{
  		$("#category_step .select-next-step").hide().removeClass('d-flex');
  		$("#category_step .select").css({'height':'auto'})
  	}

  	if($("#country_search").val() !== ""){
  		$("#country_step .select-next-step").show().addClass('d-flex');
  		$("#country_step .select").css({'height':'auto'})
  	}else{
  		$("#country_step .select-next-step").hide().removeClass('d-flex');
  		$("#country_step .select").css({'height':'auto'})
  	}

    if($("#city_search").val() !== ""){
        $("#city_step .select-next-step .next").show().addClass('d-flex');
        $("#city_step .select").css({'height':'auto'})
    }else{
        $("#city_step .select-next-step .next").hide().removeClass('d-flex');
        $("#city_step .select").css({'height':'auto'})
    }

  }

  $('.select-custom-li .select-nav').click(function() {
      $('.select-custom-li .select').not(this).css('display', 'none');
      $('.select-custom-li .select-nav').not(this).removeClass('active');
      if ($(this).hasClass('active')) {
          $(this).removeClass('active');
          $(this).next().slideUp(200);
      } else {
          $(this).addClass('active');
          $(this).next().slideDown(200);
      }
  });

</script>
@endsection
<style>
	.select-custom-li .select{
		min-width: 180px;
	}
</style>
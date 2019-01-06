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

<!-- <link rel="stylesheet" type="text/css" href="/frontend/vendor/select2/select2.min.css">
<link rel="stylesheet" type="text/css" href="/frontend/vendor/dropdown-hover.css"> -->



<div class="container-maps-page">
	<nav class="menu-categories-desktop menu-categories  hidden-lg-up " style="padding-top: 10px;">
		<div class="container">
			<div class="menu-cate-show-map ">
				<div class="list-menu-categories-mobile  hidden-lg-up ">
					<div class="d-flex justify-content">
						<a class="come-back" href="javascript:history.back()" title=""><i class="icon-left"></i></a>
						<!-- end come back -->
						<!-- end dropdown -->
						<span class="mobile_badge">
							
						</span>
						<!-- @if(count($extra_types)>0)
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
						@endif -->
						<!-- end dropdown -->
						<input name="advance" type="hidden">
						<!-- <a class="btn-show-list" href="" title=""><i class="fa fa-filter"></i></a> -->
					</div>
				</div>
			</div>
			<!-- end menu-cate-show-map -->
			<a class="come-back-map come-back" href="" title=""><i class="icon-left"></i>{{trans('Location'.DS.'category.back')}}</a>
		</div>
	</nav>
	<!-- end menu cate -->
	<div class="content-maps-page">
		<div class="row px-3">
			<div id="choosen_value" class="pull-left hidden-md-down">
			</div>
		</div>
		<div class="custom-gmap box-gmap">
			<div id="gmap"></div>
			<div id="advance_search">

				<div class="dropdown-hover text_advance_search" style="">{{trans('global.advance_search')}}:</div>
				<div class="dropdown-hover">
					<button class="btn btn-secondary dropdown-toggle" type="button">
						{{trans('global.category')}}
					</button>
					<div class="dropdown-hover-menu" aria-labelledby="dropdown">
						<div class="dropdown-hover-content">
							<select class="form-control w-100 category_search" name="category_search" id="category_search"  onchange="changeCategorySearch(this)">
								<option value="">{{trans('Location'.DS.'search.choose_category')}}</option>
								@if(isset($categories))
								@foreach($categories as $category_one)
								<option value="{{$category_one->id}}" {{isset($category_search)&&$category_one->id==$category_search->id?'selected':''}}>@lang(ucfirst($category_one->name))</option>
								@endforeach
								@endif
							</select>
							<label>{{trans('Location'.DS.'search.category_item')}}</label>
							<br/>

							<select class="form-control w-100 category_item_search" name="category_item_search" id="category_item_search" id="category_item_search" onchange="chooseCategoryItem(this)" multiple style="min-width:270px;max-width:270px;">
								<!-- <option value="">{{trans('Location'.DS.'search.choose_category_item')}}</option> -->
								@if(isset($category)&&isset($category_search->category_items))
								@foreach($category_search->category_items as $item_one)
								<option value="{{$item_one->id}}" {{$category_items&&in_array($item_one->id, $category_items)?'selected':''}}>@lang(ucfirst($item_one->name))</option>
								@endforeach
								@endif
							</select>
						</div>	
					</div>
				</div>
				<div class="dropdown-hover">
					<button class="btn btn-secondary dropdown-toggle" type="button">
						{{trans('global.location')}}
					</button>

					<div class="dropdown-hover-menu" aria-labelledby="dropdown">
						<div class="dropdown-hover-content" >
							<select class="form-control w-100 country_search" name="country_search" id="country_search" onchange="changeCountrySearch(this)">
								<option value="">{{trans('Location'.DS.'search.choose_country')}}</option>
								@if(isset($countries))
								@foreach($countries as $country_one)
								<option value="{{$country_one->id}}"  {{isset($country_search)&&$country_one->id==$country_search->id?'selected':''}}>{{$country_one->name}}</option>
								@endforeach
								@endif
							</select>
							<select class="form-control w-100 city_search" name="city_search" id="city_search" onchange="changeCitySearch(this)">
								<option value="">{{trans('Location'.DS.'search.choose_city')}}</option>
								@if(isset($cities))
								@foreach($cities as $citi_one)
								<option value="{{$citi_one->id}}" {{$city&&$citi_one->id==$city->id?'selected':''}}>{{$citi_one->name}}</option>
								@endforeach
								@endif
							</select>
							<select class="form-control w-100 district_search" name="district_search" id="district_search" onchange="changeDistrictSearch(this)">
								<option value="">{{trans('Location'.DS.'search.choose_district')}}</option>
								@if(isset($districts))
								@foreach($districts as $district_one)
								<option value="{{$district_one->id}}" {{$district&&$district_one->id==$district->id?'selected':''}}>{{$district_one->name}}</option>
								@endforeach
								@endif
							</select>
						</div>
					</div>
				</div>

				<div class="dropdown-hover" id="view_list" style="display:{{app('request')->input('q')?'inline-block':'none'}};">
					<button class="btn btn-secondary" type="button" onclick="viewList()">
						View List
					</button>
				</div>

			</div>
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
	var pos = {
		lat: lat,
		lng: lng
	};
	var lstAdd = {!!$json!!};
	var _token = $("meta[name='_token']").prop('content');
	var extra_type = '{{$current_extra_type?$current_extra_type:''}}';
	var currentPage = 2;
	var totalPage = {{$total_content?$total_content/30:0}};
	var lstMarker = [];
	var lstCord = [];
	var map = null;
	var max_page = true;
	var stop = true;
	var latlgn = map_data.center.split(',');
	var position_icon;
	var mapHandling = 'greedy';
	var marker = null;
	var keyword_has_changed = false;
	if($(window).width()>768){
		position_icon = google.maps.ControlPosition.LEFT_TOP;
		mapHandling = 'greedy';
	}else{
		mapHandling = 'greedy';
		position_icon = google.maps.ControlPosition.RIGHT_CENTER;
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
					placeMarker(e.latLng);
					map.setCenter(e.latLng);
				}
			});

			if($(window).width()<1025){
				google.maps.event.addListener(map, 'mouseup', function(e) {
			 		var event = window.event || e;
			 		if(map.get('im_dragging') === true){
			 			// event.preventDefault();
			 			map.set('im_dragging',false);
			 		}else{
			 			if (!$(event.target).is('.room-price-pin, .room-price-pin *')){
							placeMarker(e.latLng);
							map.setCenter(e.latLng);
						}
			 		}
				});

				google.maps.event.addListener(map, 'drag', function(e) {
					map.set('im_dragging',true);
				});
		  }	



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
				if(this.getZoom()>16){
					map.setZoom(16);
				}

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
		console.log(pos);
		var new_pos = new google.maps.LatLng(pos.lat, pos.lng);
		bounds.extend(new_pos);
		map.setCenter(new_pos);
		map.fitBounds(bounds);
		mapCircle.setCenter(new_pos);
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
		//marker.remove();
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
			loadContentSearch(false,false);
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


		$.mCustomScrollbar.defaults.theme = "inset";
		$.mCustomScrollbar.defaults.scrollButtons.enable = true;

		$(".list-menu-categories").mCustomScrollbar({
				theme: "inset",
				axis: "x",
				advanced: { autoExpandHorizontalScroll: true },
				scrollTo:'.active',
				scrollButtons: {
						enable: true,
						scrollType: "stepped",
						scrollAmount: 500
				}
		});
		
		// if ($(window).width() > 768) {
			// scroll post on map page
			// $(".container-maps-page .container-siderbar").mCustomScrollbar({
			// 	theme: "minimal",
			// 	callbacks:{
			// 		onTotalScrollOffset:100,
			// 		onTotalScroll:function(){
			// 			if(currentPage<totalPage && stop){
			// 				loadContent(currentPage,false, true);
			// 			}
			// 		}
			// 	}
			// });
		// }
		

		// end select categories on mobile
		// $(".container-maps-page .container-siderbar").mCustomScrollbar({
		//     theme: "minimal"
		// });
	}

	function rstAnimateRight(element, number) {
		$(element).animate({
			left: number
		}, 300);
	}


	$(function(){
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
			if($("#project").val().length){
				getAds();
				changeKeyword($("#project"));
			}
		}
		
		//display show map or view list
		$('.list-menu-categories-mobile .btn-show-list').click(function(event) {
				event.preventDefault();
				rstAnimateRight($('.container-maps-page .siderbar-left'), 0);
				$(".container-maps-page .menu-cate-show-map").slideToggle('fast');
				$(".container-maps-page .come-back-map").slideToggle('fast');
		});
		$(".container-maps-page .come-back-map").click(function(event) {
				event.preventDefault();
				rstAnimateRight($('.container-maps-page .siderbar-left'), -1200);
				$(".container-maps-page .menu-cate-show-map").slideToggle('fast');
				$(".container-maps-page .come-back-map").slideToggle('fast');
		});

		$(".search-map").click(function(event) {
				// event.preventDefault();
				rstAnimateRight($('.container-maps-page .siderbar-left'), -1200);
				$(".container-maps-page .menu-cate-show-map").slideToggle('fast');
				$(".container-maps-page .come-back-map").slideToggle('fast');
		});

		//slider menu categories
		// $('.slider-menu-categories').slick({
		//   dots: false,
		//   infinite: true,
		//   speed: 300,
		//   slidesToShow: 6,
		//   slidesToScroll: 6,
		//   variableWidth: true,
		//   prevArrow: '<a class="slick-prev" href="#" title="Slick Preview"><i class="icon-left-open-big"></i></a>',
		//   nextArrow: '<a class="slick-next" href="#" title="Slick Next"><i class="icon-right-open-big"></i></a>'
		// });
		$('.list-menu-categories-mobile ul li').click(function(event) {
				var value = $(this).data('value');
				$(this).closest('ul').slideUp('fast').next('input').val(value);
				$(this).closest('.list-menu-categories-mobile').find('.btn-select').text($(this).text());
				//console.log($('.list-menu-categories-mobile input').val());
		});
		// end select categories on mobile
		$(".container-maps-page .container-siderbar").mCustomScrollbar({
				theme: "dark"
		});
		//custom
		$('.box-list-menu-categories').on('click', '.arrow-right', function(event) {
				event.preventDefault();
				$('.mCSB_buttonRight').click();
		});
		$('.box-list-menu-categories').on('click', '.arrow-left', function(event) {
				event.preventDefault();
				$('.mCSB_buttonLeft').click();
		});
		///custom scroll cate mobile
		$('.menu-categories .list-menu-categories-mobile ul').mCustomScrollbar({
				theme: "dark"
		});
		$(".slider-menu-categories").show();
		var h_header = $('#header').height();
		var h_footer = $('#footer').height();
		var h_menucate = $('.menu-categories-desktop').height();
		var h_maps = $(window).height() - h_header - h_footer - h_menucate;
		// if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
		// 		// alert('Its Safari '+$(window).height() + ' ' +h_header + ' ' +h_footer + ' ' +h_menucate);
		// 		$('.custom-gmap #gmap').css('min-height', h_maps);
		// }
		$(".box-list-menu-categories").show();

		////console.log('h_header:',h_header,'h_footer:',h_footer,'h_menucate:',h_menucate,'h_maps:',h_maps);
		// if($(window).width() < 768){
		// 	var height_map = $(window).height() - $("#header").height() - $(".menu-categories").height() - $("#footer").height();
		// 	$(".custom-gmap #gmap").height(height_map);
		// }
	});

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
		});
	}
</script>

<script type="text/javascript">
	jQuery(document).ready(function() {
			// sticky
			var window_width = $(window).width();

			// $(document).on('click', function(event){
			// 	if (window_width < 768) {
			// 		if (!$(event.target).is('.form-search-restaurant, .form-search-restaurant *')) {
			// 				$(".form-search-filter").hide();
			// 				$('.form-search-restaurant h3').removeClass('show');
			// 		}
			// 	}
			// });
			

			// if (window_width < 768) {
			// 		$('.form-search-restaurant h3').click(function(event) {
	 //          event.preventDefault();
	 //          $(this).toggleClass('show');
	 //          $('.form-search-filter').slideToggle('fast');
		//       });
		//       $(".form-search-restaurant").trigger("sticky_kit:detach");
			// } else {
			// 		make_sticky();
			// }

			make_sticky();
			function make_sticky() {
					$(".form-search-restaurant").stick_in_parent({
							parent: '.content-search-page',
							offset_top: 90
					});
			}

	});
</script>

<script>
	suggestSearch = function(obj){
		return false;
	}

	viewList = function(){
		// alert(current_url);
		var url_list = current_url.toString().replace('search1','search');
		window.location = url_list;
	}
	
	changeCountrySearch = function(obj){
			var country = $(obj).val();
			$(".country_search").val(country);
			current_page = 1;
			max_page = true;
			loadCitySearch(country);
			// alert('change country')
			loadContentSearch();
		}

	loadCitySearch=	function(country){
			$.ajax({
				url : '/search/loadCity',
				type: 'POST',
				data: {
					_token: _token,
					country: country
				},
				success: function(response){
					$(".city_search").html(response);
					//$(".city_search").trigger("change");
				}
			})
		}

	changeCitySearch=	function(obj){
			var city = $(obj).val();
			$(".city_search").val(city);
			current_page = 1;
			max_page = true;
			loadDistrictSearch(city);
			changeCurrentLocation(loadContentSearch);
		}

	loadDistrictSearch=	function(city){
			$.ajax({
				url : '/search/loadDistrict',
				type: 'POST',
				data: {
					_token: _token,
					city: city
				},
				success: function(response){
					$(".district_search").html(response);
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
			changeCurrentLocation(loadContentSearch);
		}

	changeCategorySearch=	function(obj){
			var category = $(obj).val();
			$(".category_search").val(category);
			current_page = 1;
			max_page = true;
			loadCategoryItemSearch(category);
			// alert('change category')
			loadContentSearch();
		}

	loadCategoryItemSearch=	function(category){
			$.ajax({
				url : '/search/loadCategoryItemNew',
				type: 'POST',
				data: {
					_token: _token,
					category: category
				},
				success: function(response){
					$(".category_item_search").html(response);
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
			// 	changeCurrentLocation(loadContentSearch);
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
		var max_page = true;
		var stop = true;
		var totalPage = {{$totalPage?$totalPage:1}};
		var current_url = window.location;
		var currentLocation = window.sessionStorage.getItem('currentLocation')?window.sessionStorage.getItem('currentLocation'):false;
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
				}else{
					resetSelect();
					// console.log("Fuck:", keyword_has_changed,first_latlng);
					pos = first_latlng;
					var find_location = pos.lat+','+pos.lng;
					// console.log("pos: ",pos);
					// console.log("first_latlng: ",first_latlng);
					// if(currentLocation){
						query.push({name:'currentLocation',value:find_location});
					// }
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
				url = '{{url('/')}}/search1'+'?'+url;
				history.pushState({}, null, url);
				current_url = url;
				$.ajax({
					url : '/search1',
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
						$("#loading").hide();
						$("#keyq").text('"'+response.q+'"');
						clearMarker();
						lstAdd = JSON.parse(response.json);
						addMarker();
						setScroll();
						stop = true;
						max_page = true;
						$("#listContent").html(response.html);
						$("#total_content").html(response.total_content)
						$("#total").html(response.total_content)
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
						map.setCenter(location);
						placeMarker(location);
					}
					//callback();
				}
			})
		}

	function resetSelect(){
		console.log('reset');
		$("#category_search").val(null);
		$(".category_item_search").html('');
		$("#district_search").val(null);
		$("#city_search").val(null);
		$(".country_search").val(null);
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
<script>
	$(function(){
		$(".category_item_search").select2({
			dropdown:true
		});
		$('.custom-gmap').css('opacity',1);
		// setTimeout(function(){
		// 	$('.custom-gmap').css('opacity',1);
		// 	@if($district)
		// 		changeCurrentLocation(loadContentSearch);
		// 	@else
		// 		@if($city)
		// 			changeCurrentLocation(loadContentSearch);
		// 		@else
		// 			clearMarker();
		// 			loadContentSearch(false,false);
		// 		@endif
		// 	@endif
		// },3000)
	})
</script>
@endsection

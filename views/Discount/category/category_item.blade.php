@section('body')
	<body class="maps">
@endsection


<div class="container-maps-page">
	<nav class="menu-categories-desktop menu-categories ">
		<div class="container">
			<div class="menu-cate-show-map ">
				<div class="list-menu-categories-mobile hidden-lg-up ">
					<div class="d-flex justify-content-between">
						<a class="come-back" href="javascript:history.back()" title=""><i class="icon-left"></i></a>
						<!-- end come back -->
						<div class="dropdown ">
							@if($category_item)
							<a class="dropdown-toggle text-truncate" href="#" id="dropdownMenuLink" data-toggle="dropdown" title="@lang(mb_ucfirst($category_item->name))">
							@lang(mb_ucfirst($category_item->name))
							</a>
							@else
							<a class="dropdown-toggle text-truncate" href="#" id="dropdownMenuLink" data-toggle="dropdown" title="{{mb_ucfirst(trans('global.all'))}}">
							{{mb_ucfirst(trans('global.all'))}}
							</a>
							@endif
							<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
							@if($category->category_items)
								@if($category_item)
								<a class="dropdown-item" href="{{url('/')}}/{{$category->alias}}" title="{{mb_ucfirst(trans('global.all'))}}">{{mb_ucfirst(trans('global.all'))}}</a>
								@endif
							@foreach($category->category_items as $item)
								@if($category_item)
									@if($item->id != $category_item->id)
									<a class="dropdown-item" href="{{url('/')}}/{{$category->alias}}/{{$item->alias}}" title="@lang(mb_ucfirst($item->name))">@lang(mb_ucfirst($item->name))</a>
									@endif
								@else
									<a class="dropdown-item" href="{{url('/')}}/{{$category->alias}}/{{$item->alias}}" title="@lang(mb_ucfirst($item->name))">@lang(mb_ucfirst($item->name))</a>
								@endif
							@endforeach
							@endif
							</div>
						</div>
						<!-- end dropdown -->
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
						<a class="btn-show-list" href="" title=""><i class="icon-list"></i></a>
					</div>
				</div>
				<a class="come-back hidden-md-down" href="javascript:history.back()" title=""><i class="icon-left"></i>{{trans('Location'.DS.'category.back')}}</a>
				@if(count($category->category_items))
					<div class="box-list-menu-categories hidden-md-down" style="display:none;">
						<a class="arrow arrow-left" href="" title="">
							<i class="icon-left-open-big"></i>
						</a> 								
						<div class="list-menu-categories">
							<ul class="list-unstyled clearfix">
								@if($category_item)
								<li class=""><a href="{{url('/')}}/{{$category->alias}}" title="@lang(mb_ucfirst($category->name))" >{{mb_ucfirst(trans('global.all'))}}</a></li>
								@else
								<li class="active"><a href="{{url('/')}}/{{$category->alias}}" title="@lang(mb_ucfirst($category->name))" >{{mb_ucfirst(trans('global.all'))}}</a></li>
								@endif
								@foreach($category->category_items as $item)
									@if($category_item)
									<li class="{{$item->id==$category_item->id?'active':''}}"><a href="{{url('/')}}/{{$category->alias}}/{{$item->alias}}" title="@lang(mb_ucfirst($item->name))" >@lang(mb_ucfirst($item->name))</a></li>
									@else
									<li class=""><a href="{{url('/')}}/{{$category->alias}}/{{$item->alias}}" title="@lang(mb_ucfirst($item->name))" >@lang(mb_ucfirst($item->name))</a></li>
									@endif
								@endforeach
							</ul>
						</div>
						<a class="arrow arrow-right" href="">
							<i class="icon-right-open-big"></i>
						</a>
					</div>
				@endif
			</div>
			<!-- end menu-cate-show-map -->
			<a class="come-back-map come-back" href="" title=""><i class="icon-left"></i>{{trans('Location'.DS.'category.back')}}</a>
		</div>
	</nav>
	<!-- end menu cate -->
	<div class="content-maps-page d-flex flex-row">
		<div class="siderbar-left siderbar px-3">
			<div class="header-sider-bar d-flex justify-content-between py-4">
				<!-- <h3 class="text-uppercase">Nhà hàng</h3> -->
				<ul class="nav text-uppercase" role="tablist">
					@if(count($extra_types)>0)
						@foreach($extra_types as $type)
						<li class="nav-item">
							<a class="{{$current_extra_type==$type?'active':''}}" data-toggle="tab" href="" role="tab" onclick="loadContent(1,true,false,'{{$type}}');">{{$type}}</a>
						</li>
						@endforeach
					@else
						@if($category_item)
							<li class="nav-item">
								<a class="active" data-toggle="tab" href="#{{str_slug($category_item->name)}}" role="tab">{{$category_item->name}}<inline id="total">({{$total?$total:0}})</inline></a>
							</li>
						@else
							<li class="nav-item">
								<a class="active" data-toggle="tab" href="#{{str_slug($category->name)}}" role="tab">{{$category->name}}<inline id="total">({{$total?$total:0}})</inline></a>
							</li>
						@endif
					
					@endif
				</ul>
				<!-- end nav tab -->
				@if($category_item)
				<a class="view-all" href="/list/{{$category->alias}}/{{$category_item->alias}}/{{$current_extra_type?'#'.$current_extra_type:''}}" alt="">{{trans('Location'.DS.'category.view_all')}} <img src="/frontend/assets/img/icon/ic-arrow.png" alt=""></a>
				@else
				<a class="view-all" href="/list/{{$category->alias}}/all/{{$current_extra_type?'#'.$current_extra_type:''}}" alt="">{{trans('Location'.DS.'category.view_all')}} <img src="/frontend/assets/img/icon/ic-arrow.png" alt=""></a>
				@endif
			</div>
			<!-- end  header sidebar -->
			<div class="tab-content">
				<div class=" container-siderbar">
					<ul class="list-restaurant list-unstyled" id="listContent">
						@include('Discount.category.content_item_list')
					</ul>
				</div>
			</div>
			<!-- end tab content -->
		</div>
		<!-- end siderbar-left -->
		<div class="custom-gmap box-gmap">
			<div id="gmap"></div>
		</div>
		<!-- end custom-gmap -->
	</div>
<!-- 	<a style="zoom:150%;" class="url-cread-address" href="" title="">
		<i class="icon-new-white"></i>
		<span class="hidden-xs-down">{{trans('Location'.DS.'category.create_location')}}</span>
	</a> -->
	<!-- end url -->
</div>

@section('JS')
<script type="text/javascript">
	var first_latlng = {};
	var category_item_id = {{$category_item?$category_item->id:0}};
	var category_id = {{$category?$category->id:0}};
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
	var lat = 10.806273;
	var lng = 106.714477;
	var pos = {
		lat: lat,
		lng: lng
	};
	var lstAdd = {!!$json!!};
	var _token = $("meta[name='_token']").prop('content');
	var extra_type = '{{$current_extra_type?$current_extra_type:''}}';
	var currentPage = 2;
	var totalPage = {{$total?$total/30:0}};
	var lstMarker = [];
	var lstCord = [];
	var map;
	var max_page = true;
	var stop = true;
	var latlgn = map_data.center.split(',');
	var position_icon;
	var mapHandling = 'greedy';
	if($(window).width()>768){
		position_icon = google.maps.ControlPosition.LEFT_TOP;
		mapHandling = 'greedy';
	}else{
		position_icon = google.maps.ControlPosition.RIGHT_TOP;
	}
	var style_map =[
		{
	    "elementType": "labels.icon",
	    "stylers": [{
	        "visibility": "off"
	    }]
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
				index: 1
			},
			styles: style_map
	};
	var drag_load = false;
	var maker = null;

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
	    map.setCenter(first_latlng);
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
			if(!maker){
				marker = new CustomMarker(
					new google.maps.LatLng(pos),
					map, { title: "center" }
				);
			}
		}

		if(first){
			map.setCenter(pos);
			map.set('oldCenter',map.getCenter())
		}

		var centerControlDiv = document.createElement('div');
	  var centerControl = new CenterControl(centerControlDiv, map);

	  centerControlDiv.index = 2;
	  map.controls[position_icon].push(centerControlDiv);

		// var bounds = new google.maps.LatLngBounds();
		// for(i=0;i<lstMarker.length;i++) {
		//    bounds.extend(lstMarker[i].getPosition());
		// }
		// // map.setCenter(bounds.getCenter());

		// map.fitBounds(bounds);

		
		//if map loaded success load list marker
		 google.maps.event.addListener(map,'idle',function(e){
			if(!this.get('dragging') && this.get('oldCenter') && this.get('oldCenter')!==this.getCenter()) {
				addMarker();
				drag_load = true;
			}

			if(!this.get('dragging')){
				if(drag_load && calculateDistance(this.get('oldCenter'),this.getCenter())){
					placeMarker(this.getCenter());
				}
				this.set('oldCenter',this.getCenter())
			}
		});

		// google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
		// 		if(this.getZoom()<6){
		// 			map.setZoom(6);
		// 			map.setCenter(pos);
		// 		}else{
		// 			map.setZoom(this.getZoom()+1);
		// 		}
		// });

		// google.maps.event.addListener(map, 'dblclick', function(event) {
		// 	 placeMarker(event.latLng);
		// });

		google.maps.event.addListener(map,'dragstart',function(){
			this.set('dragging',true);
		});

		google.maps.event.addListener(map,'dragend',function(){
			this.set('dragging',false);
			if(drag_load){
				google.maps.event.trigger(this,'idle',{});
			}
		});
		// google.maps.MouseEvent .


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
				if(selector && $(window).width() >= 768){
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
		// console.log(d);
		if(d>0.39){
			return true;
		}else{
			return false;
		}
	}

	function addMarker() {
		clearMarker();
		// console.log(lstAdd);
		for (var i = 0; i < lstAdd.length; i++) {
			var obj = lstAdd[i];
			var latlgn = obj.center.split(',');
			var marker = new CustomMarker(
					new google.maps.LatLng(latlgn[0], latlgn[1]),
					map, obj
			);
			//bounds.extend(new google.maps.LatLng(latlgn[0], latlgn[1]));
			lstMarker.push(marker);
		}
		drag_load = false;
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
		//marker.remove();
		// marker = new CustomMarker(
		// 	new google.maps.LatLng(pos),
		// 	map, { title: "center" }
		// );
		loadContent(currentPage,true,false,extra_type);
		// console.log('xx');
	}
	

	function getLocation() {
		// map = new google.maps.Map(document.getElementById('gmap'), {
		// 	zoom : 16,
		// 	center : new google.maps.LatLng(10.806273,106.714477)
		// });
		if(navigator.permissions === undefined){
			if(navigator.geolocation===undefined){
				{{-- alert('{{trans("global.browser_not_gps")}}');--}}
				getLocationByIP()
			}else{
				navigator.geolocation.getCurrentPosition(setPosition,getLocationByIP,{ maximumAge: 600000, timeout: 2000 });
			}
		}else{
			navigator.permissions.query({'name': 'geolocation'})
			.then(function(permission){
				if (permission.state === 'granted') {
					if (navigator.geolocation) {
						navigator.geolocation.getCurrentPosition(setPosition,getLocationByIP,{ maximumAge: 600000, timeout: 2000 });
					} else {
						{{-- alert('{{trans("global.browser_not_gps")}}');--}}
						getLocationByIP()
					}
				}else if(permission.state === 'denied'){
					//alert('{{trans("global.alert_gps")}}');
					{{-- @if($category_item) --}}
					{{-- window.location = '/list/{{$category->alias}}/{{$category_item->alias}}'--}}
					{{-- @else --}}
					{{-- window.location = '/list/{{$category->alias}}'--}}
					{{-- @endif --}}
					getLocationByIP()
				}else if(permission.state === 'prompt'){
					navigator.geolocation.getCurrentPosition(setPosition,getLocationByIP,{ maximumAge: 600000, timeout: 2000 });
					// window.location.reload();
				}else{
					//alert('{{trans("global.alert_gps")}}');
					{{-- @if($category_item) --}}
					{{-- window.location = '/list/{{$category->alias}}/{{$category_item->alias}}'--}}
					{{-- @else --}}
					{{-- window.location = '/list/{{$category->alias}}'--}}
					{{-- @endif --}}
					getLocationByIP()
				}
			});
		}
	}


	function setPosition(position){
		lat = position.coords.latitude.toFixed(6);
		lng = position.coords.longitude.toFixed(6);

		console.log("Current location setPosition: "+lat+' '+lng);
		pos = {
			lat: parseFloat(lat),
			lng: parseFloat(lng)
		};
		first_latlng = {
			lat: parseFloat(lat),
			lng: parseFloat(lng)
		};
		map_data['center'] = lat+','+lng;
		window.sessionStorage.setItem('currentLocation', lat+','+lng);
		// loadContent(currentPage);
		initMap(pos,true);
		addMarker();
		setScroll();
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
			@else
			$('.view-all').attr('href',"{{url('/')}}/list/{{$category->alias}}/all/#"+extra_type_data)
			@endif
			$.ajax({
				url : '/getContentByCategory',
				type: 'POST',
				// async: false,
				data: {
					_token: _token,
					lat : lat,
					lng : lng,
					category_item_id : category_item_id,
					category_id : category_id,
					extra_type: extra_type_data,
					page: page
				},
				beforeSend: function(){
					max_page = false;
					stop = false;
					$("#loading").show();
				},
				success: function(response){
					$("#loading").hide();
					$("#total").html('('+response.total+')')
					totalPage = response.totalPage;
					pos = {
						lat: parseFloat(lat),
						lng: parseFloat(lng)
					};
					if(newload){
						clearMarker();
						$("#listContent").html(response.html);
						lstAdd = [];
						lstAdd = lstAdd.concat(JSON.parse(response.json));
					}else{
						$("#listContent").append(response.html);
						lstAdd = lstAdd.concat(JSON.parse(response.json));
					}
					// initMap(pos);
					addMarker();
					setScroll();
					stop = true;
					max_page = true;
					// currentPage = response.nextPage;
					if(currentPage==totalPage){
						max_page = false;
					}
				}
			});
		}
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
			$(".container-maps-page .container-siderbar").mCustomScrollbar({
				theme: "dark",
				callbacks:{
					onTotalScrollOffset:100,
					onTotalScroll:function(){
						if(currentPage<totalPage && stop){
							loadContent(currentPage,false, true);
						}
					}
				}
			});
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
        console.log($('.list-menu-categories-mobile input').val());
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
    if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
        // alert('Its Safari '+$(window).height() + ' ' +h_header + ' ' +h_footer + ' ' +h_menucate);
        $('.custom-gmap #gmap').css('min-height', h_maps);
    }
    $(".box-list-menu-categories").show();
    // if($(window).width() < 768){
    // 	var height_map = $(window).height() - $("#header").height() - $(".menu-categories").height() - $("#footer").height();
    // 	$(".custom-gmap #gmap").height(height_map);
    // }
	});

	function getLocationByIP(){
		alert('{{trans("global.alert_gps")}}');
		$.getJSON("/getLocation", function(data) {
			lat = parseFloat(data.latitude).toFixed(6);
			lng = parseFloat(data.longitude).toFixed(6);
			console.log("Current location getLocationByIP: "+lat+' '+lng);
			pos = {
				lat: parseFloat(lat),
				lng: parseFloat(lng)
			};
			first_latlng = {
				lat: parseFloat(lat),
				lng: parseFloat(lng)
			};
			map_data['center'] = lat+','+lng;
			initMap(pos,true);
			addMarker();
			setScroll();
		});
	}
</script>

@endsection

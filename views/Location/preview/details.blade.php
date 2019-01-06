@section('body')
  <body>
  @endsection

  <div class="sidebar-top  py-2" style="background: #D0021B">
    <div class="container">
       <a class="come-back" href="javascript:history.back()" title="">
        <img src="{{asset('frontend/assets/img/icon/ic-back.png')}}" alt="{{$content['name']}}">
      </a>
      {!! $content['breadcrumb'] !!}
    </div>
  </div>
  <div class="content-location-detail content-page">
    <header class="header-detail ">
      <div class="container d-md-flex justify-md-content-between">
        <div class="header-detail-left d-flex align-items-md-start align-items-center">
          <div class="avata text-center">
            <a href="" title="">
              <!-- <img class="rounded-circle"
                   src=""
                   alt=""> -->
              <div class="rounded-circle" style="
              background-image: url('{{$content['avatar']}}');
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
        <h1 class="title-restaurant hidden-md-up">{{$content['name']}}</h1>
        </div>
        <div class="content">
          <div class="d-md-flex justify-content-lg-between">
            <h1 class="title-restaurant hidden-sm-down col-md-7">{{$content['name']}}</h1>
            <div class="meta-post d-flex justify-content-md-end hidden-lg-down col-md-5 align-items-center pr-md-0">
              <div class="add-like d-flex align-items-center">
                <i class="icon-heart-empty"
                     onclick=""></i>
                <span class="point_like">&nbsp;&nbsp;(0)</span>
              </div>
              <div class="meta-post-distance">
              	&nbsp;
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
          </div>

          <div class="d-md-flex justify-content-md-between align-items-md-start row">
            <ol class="info-contact list-unstyled my-3 mb-lg-0 col-md-7 col-lg-7">
              <li><i class="icon-location"></i>{{$content['address']}}, {{$content['district']->name}}, {{$content['city']->name}}, {{$content['country']->name}}</li>
              <li><i class="icon-time"></i>{{$content['open_time']}}</li>
              <li>{{$content['description']}}</li>
              <!-- <li>              
                <button class="btn btn-primary" id="btn-chat" href=""
                   title=""><i class="icon-commenting-o">
                   </i>{{-- trans('Location'.DS.'content.chat_with_us') --}}Chat Online
                </button>
              <li> -->
            </ol>

            

            <!-- end  meta -->
            <div class="box-right col-md-5 col-lg-4">
              <div class="meta-post d-flex align-items-center hidden-lg-up" style="width: 120%">
                <div class="add-like d-flex align-items-center">
                  <i class="icon-heart-empty"
                     onclick=""></i>
                  <span class="point_like">&nbsp;&nbsp;(0)</span>
                </div>
                <div class="meta-post-distance">
                	&nbsp;
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
              <div class="flex-lg-last my-3 mb-lg-0 checkin-block align-items-md-end">
                <ul class="list-group-pd list-unstyled">
                  <li><a class="cursor" id="checkin"
                         onclick="">Check in </a><span id="checkin_total">(0)</span></li>
                  <li><a
                      onclick=""
                      class="cursor">{{trans('Location'.DS.'content.save_favorites')}}</a> <span
                      id="save_like_content_total">(0)</span></li>
                  <li>
                      <div class="dropdown-collection">
                          <a style="cursor: pointer;" title="{{trans('Location'.DS.'content.add_collection')}}">{{trans('Location'.DS.'content.add_collection')}}</a> <span>(0)</span>
                      </div>
                  </li>
                  <!-- <li>Chia sẻ địa điểm <span></span></li> -->
                </ul>
              </div>
              <div class="share d-flex flex-row-reverse mt-3 share-block">
                <ul class="list-unstyled d-flex flex-row">
                  <li>
                    <a href="#" onclick="sharePopup('https://plus.google.com/share?url={{urlencode(url()->current())}}')">
                      <i class="icon-google"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                    </a>
                  </li>
                  <li>
                    <a href="#" onclick="sharePopup('https://www.facebook.com/sharer/sharer.php?u={{urlencode(url()->current())}}&amp;src=sdkpreparse')">
                      <i class="icon-facebook"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                    </a>
                  </li>
                  <li>
                    <a href="#" onclick="sharePopup('https://twitter.com/share?text={!! clear_str($content['name']) !!}&url={{urlencode(url()->current())}}&hashtags=Kingmap')">
                      <i class="icon-twitter-bird"></i>&nbsp;&nbsp;&nbsp;&nbsp;
                    </a>
                  </li>
                </ul>
                <span>{{trans('Location'.DS.'content.share')}}:</span>
              </div>
            </div>

            <!-- end box-right -->
          </div>
        </div>
      </div>
    </header>
    <!-- /header -->
	  @php
			$list_product = [];
			$discounts = [];
	  @endphp
   {{-- @if(count($content['space']) > 0 || count($content['menu']) > 0 || count($content['link']) > 0) --}}
      <section class="section-space bg-gray my-4 px-md-4">
        <div class="container">
            <div class="box-gallery">
              <div class="title-gallery">
                <h4 class="text-uppercase mb-4">{{mb_strtoupper(trans('global.product_service'))}} ({{count($list_product)}})</h4>
              </div>
              <div class="section-menu-content">
              @if(count($list_product)>0)
                @foreach($list_product as $key_group => $group)
                <!-- start  location list menu -->
                <div class="location-list-menu">
                  @if($key_group !== 'no_group')
                  <h5 class="location-list-menu-title">{{$group['group_name']}}</h5>
                  @endif
                  <div class="location-list-menu-content clearfix row">
                    @foreach($group as $key_product => $product)
                    @if($key_product !== 'group_name')
                    <div class="card-horizontal-sm d-flex align-items-center pb-3 mb-3 col-md-3 col-sm-4">
                      <!-- <div class="img">
                        <a href="">
                          <img src="{{$product->image}}" alt="">
                        </a>
                      </div> -->
                      <div class="content pl-2">
                        <span class="title d-block mb-1" href="">{{mb_ucfirst($product->name)}}</span>
                        <!-- <span class="price">
                          {{money_number($product->price)}} {{$product->currency}}
                        </span> -->
                      </div>
                    </div>
                    @endif
                    @endforeach
                  </div>
                </div>
                <!-- end  location list menu -->
                @endforeach

              @else
              <h6 style="color: #5b89abb8;" class="font-italic">{{trans('global.content_is_update')}}</h6>
              @endif
                <!-- <div class="readmore text-center">
                  <a href="">Xem thêm</a>
                </div> -->
              </div>
            </div>
          {{-- @if(count($content['space']) > 0) --}}
            <div class="box-gallery">
              <div class="title-gallery d-flex justify-content-between align-items-start">
                <h4 class="text-uppercase mb-4">{{mb_strtoupper(trans('global.space'))}} ({{count($content['space'])}})</h4>
                @if(count($content['space']) > 0)
                <a href="" title="">
                  {{ucfirst(trans('global.view_all'))}} 
                  <i class="icon-arrow"></i>
                </a>
                @endif
              </div>
              @if(count($content['space']) > 0)
              <ul class="list-gallery list-unstyled row">
                @foreach($content['space'] as $value)
                  <li>
                    <a data-fancybox="images_space"  href="{{$value}}">
                      <img class="img-fluid" src="{{str_replace('img_content','img_content_thumbnail',$value)}}" alt="" style="width:170px; height:127px;">
                    </a>
                  </li>
                @endforeach
              </ul>
              @else
              <h6 style="color: #5b89abb8;" class="font-italic">{{trans('global.content_is_update')}}</h6>
              @endif
              <!-- end list-gallery -->
            </div>
          {{-- @endif --}}
        <!-- end box-gallery -->
          {{-- @if(count($content['menu']) > 0) --}}
            <div class="box-gallery">
              <div class="title-gallery d-flex justify-content-between align-items-start">
                <h4 class="text-uppercase mb-4">{{mb_strtoupper(trans('global.image'))}} ({{count($content['menu'])}})</h4>
                @if(count($content['menu']) > 0)
                <a href=""
                   title="">{{ucfirst(trans('global.view_all'))}} <i
                    class="icon-arrow"></i></a>
                @endif
              </div>
              @if(count($content['menu']) > 0)
              <ul class="list-gallery list-unstyled row">
                 @foreach($content['menu'] as $value)
                  <li>
                    <a data-fancybox="images_menu"  href="{{$value}}">
                      <img class="img-fluid" src="{{str_replace('img_content','img_content_thumbnail',$value)}}" alt="" style="width:170px; height:127px;">
                    </a>
                  </li>
                 @endforeach
              </ul>
              @else
              <h6 style="color: #5b89abb8;" class="font-italic">{{trans('global.content_is_update')}}</h6>
              @endif
            </div>
          {{-- @endif --}}

          {{-- @if(count($content['link']) > 0) --}}
            <div class="box-gallery">
              <div class="title-gallery d-flex justify-content-between align-items-start">
                <h4 class="text-uppercase mb-4">{{mb_strtoupper(trans('global.video'))}} ({{count($content['link'])}})</h4>
                @if(count($content['link']) > 0)
                <a href=""
                   title="">{{ucfirst(trans('global.view_all'))}} <i
                    class="icon-arrow"></i></a>
                @endif
              </div>
              @if(count($content['link']) > 0)
              <ul class="list-gallery list-unstyled row">
                @foreach($content['link'] as $value)
                  @if (strpos($value,'facebook.com') == TRUE)
                    <li class="iframe-video">
                      <a data-video-facebook href="https://www.facebook.com/plugins/video.php?height=232&href={{$value}}"></a>
                      <iframe width="179" height="130" src="https://www.facebook.com/plugins/video.php?height=464&href={{$value}}" frameborder="0" allowfullscreen></iframe>
                    </li>
                  @elseif(strpos($value,'vimeo.com') == TRUE)
                    <li class="iframe-video">
                      <a data-fancybox href="{{$value}}"></a>
                      <iframe src="{{str_replace('vimeo.com','player.vimeo.com/video',$value)}}" width="179" height="130" allowfullscreen></iframe>
                    </li>
                  @else
                  @php
                      $value = str_replace('watch?v=','',$value);
                      $value = str_replace('youtube.com/','youtube.com/embed/',$value);
                      $value = str_replace('youtu.be/','youtube.com/embed/',$value);
                      $value = clear_youtube_link($value);
                    @endphp
                    <li class="iframe-video">
                      <a data-fancybox href="{{$value}}"></a>
                      <iframe width="179" height="130" type="text/html" src="{{$value}}" frameborder="0" allowfullscreen></iframe>
                    </li>
                  @endif
                @endforeach
              </ul>
              @else
              <h6 style="color: #5b89abb8;" class="font-italic">{{trans('global.content_is_update')}}</h6>
              @endif
              <!-- end list-gallery -->
            </div>
          {{-- @endif --}}
        <!-- end box-gallery -->
            <div class="box-gallery">
              <div class="title-gallery">
                <h4 class="text-uppercase mb-4">{{mb_strtoupper(trans('global.discount'))}} ({{count($discounts)}})</h4>
              </div>
              <div class="section-menu-content">
                @if(count($discounts)>0)
                <div class="location-list-menu">
                  @foreach($discounts as $discount)
                  <div class="location-list-menu-content clearfix">
                    <div class="card-horizontal-sm d-flex align-items-center pb-3 mb-3">
                      <div class="img">
                        <!-- <a href=""> -->
                          <img src="{{$discount->image}}" alt="{{mb_ucfirst($discount->name)}}">
                        <!-- </a> -->
                      </div>
                      <div class="content pl-2">
                        <!-- <a class="title d-block mb-1" href=""> -->
                          <span class="title d-block mb-1">{{mb_ucfirst($discount->name)}}</span>
                        <!-- </a> -->
                        <span class="price">{{$discount->description}}</span>
                      </div>
                    </div>               
                  </div>
                  @endforeach
                </div>
                @else
                <h6 style="color: #5b89abb8;" class="font-italic">{{trans('global.content_is_update')}}</h6>
                @endif
              </div>
            </div>
            
        </div>
      </section>
    {{-- @endif --}}

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
    <!-- end  -->
  </div>

  <div id="modal-notify-content" class="modal fade   modal-report show modal-animation" data-backdrop="false"  tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" style='background:#fff;'>
      <div class="modal-content p-4">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <!-- close -->
        <div class="modal-logo pt-4 text-center">
          <img src="{{isset($notify_content) ? $content['avatar'] : ''}}" alt="">
        </div>
        <!-- end logo -->
        <h4 class="text-uppercase text-center">{{trans('Location'.DS.'content.notification')}}</h4>
        <hr>
        <p>{{isset($notify_content) ? $notify_content : ''}}</p>
        <!-- end  form nitification location -->
      </div>
      <!-- end  modal content -->
    </div>
  </div>

  
  @section('JS')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js"></script>
    <script type="text/javascript">
      $(window).load(function(){
        var check = '{{isset($notify_content) ? $notify_content : ''}}';
        if(check)
        {
          setTimeout(function(){ $('#modal-notify-content').modal('show'); }, 1000);
        }
      });
    </script>
    <script type="text/javascript">
      $(document).ready(function () {

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
          customPaging : function(slider, i) {
              return '';
          },
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
          prevArrow: '<button type="button" class="slick-prev btn"><i class="icon-left-open-big"></i></button>',
          nextArrow: '<button type="button" class="slick-next btn "><i class="icon-right-open-big"></i></button>',
          autoplay: true,
          autoplaySpeed: 4000,
          responsive: [
            {
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
      var mapHandling = 'cooperative';
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
          zoomControl: false,
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
          },{maximumAge:10000, timeout:5000});
        }

      }

      function showPosition(position) {
        var latLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        var mapOptions = {
          zoom: 14,
          gestureHandling: mapHandling,
          styles: style_map,
        	zoomControl: false,
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

      var starClicked = false;
      // var check = {{isset($content['vote']) ? $content['vote'] : 'null'}};
      // if (check != null) {
      //   var starClicked = true;
      // }


      function updateStarState(target) {
        $(target).parent().prevAll().addClass('animate');
        $(target).parent().prevAll().children().addClass('star-colour');

        $(target).parent().nextAll().removeClass('animate');
        $(target).parent().nextAll().children().removeClass('star-colour');
      }

      function setHalfStarState(target) {
        $(target).addClass('star-colour');
        $(target).siblings('.full').removeClass('star-colour');
        updateStarState(target);
      }

      function setFullStarState(target) {
        $(target).addClass('star-colour');
        $(target).parent().addClass('animate');
        $(target).siblings('.half').addClass('star-colour');
        updateStarState(target);
      }

      function calculateAverage(id_content, id_user, point) {

        $.ajax({
          type: "POST",
          data: {
            id_content: id_content,
            id_user: id_user,
            point: point,
            _token: $('meta[name="_token"]').attr('content')
          },
          url: base_url + '/vote-content',
          success: function (data) {
            if (data.mess == true) {
              $('.star-number').text('(' + data.value + ')')
            }
          }
        })
      }
    </script>

    <!-- Check in script -->
    <script>
      function confirmLocation(id_content, id_user) {
        if (id_user === undefined) {
          $('#modal-signin').modal('show');
          return false;
        }
        window.location = '/confirm-location/'+id_content;
      }

      function checkinContent(id_content, id_user) {
        if (id_user === undefined) {
          $('#modal-signin').modal('show');
          return false;
        }
        var old_checkin = parseInt($("#checkin_total").text());
        $.ajax({
          type: "POST",
          data: {id_content: id_content, id_user: id_user, _token: $('meta[name="_token"]').attr('content')},
          url: base_url + '/checkin-content',
          success: function (response) {
            if (response.mess == true) {
              $("#checkin_total").text(response.value);
              if (old_checkin < response.value) {
                toastr.info('{{trans('Location'.DS.'content.have_checked')}}');
              } else {
                toastr.warning('{{trans('Location'.DS.'content.have_unchecked')}}');
              }
            }
          }
        })
      }

      function saveLikeContent(id_content, id_user) {
        if (id_user === undefined) {
          $('#modal-signin').modal('show');
          return false;
        }
        var old_checkin = parseInt($("#save_like_content_total").text());
        $.ajax({
          type: "POST",
          data: {id_content: id_content, id_user: id_user, _token: $('meta[name="_token"]').attr('content')},
          url: base_url + '/save-like-content',
          success: function (response) {
            if (response.mess == true) {
              $("#save_like_content_total").text(response.value);
              if (old_checkin < response.value) {
                toastr.info('{{trans('Location'.DS.'content.have_saved')}}');
              } else {
                toastr.warning('{{trans('Location'.DS.'content.have_unsaved')}}');
              }
            }
          }
        })
      }

      function showLogin(){
        $("#modal-signin").modal("show");
      }

    function showDropdown(obj){
      $(obj).parent().find('.dropdown-menu-collection').toggle('fast')
    }
    </script>
    @yield('JSComment')
@endsection

@section('body')
  <body>
  @endsection
  <?php
    $content['vote'] = rand(0,0);
    $content['line'] = rand(0,0);
  ?>
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
              <img class="rounded-circle"
                   src="{{$content['avatar']}}"
                   alt="">

                <div class="online status-location">
                  <i class="icon-circle"></i>
                  <span>{{trans('Location'.DS.'preview.opening')}}</span>
                </div>
            </a>
          </div>
        <h1 class="title-restaurant hidden-md-up">{{$content['name']}}</h1>
        </div>
        <div class="content">
          <div class="d-lg-flex justify-content-lg-between">
            <h1 class="title-restaurant hidden-sm-down">{{$content['name']}}</h1>
            <div class="meta-post d-flex align-items-center hidden-sm-down">
              <div class="add-like d-flex align-items-center">
                <i class="{{rand(10,10000) ? 'icon-heart' : 'icon-heart-empty' }}"
                   ></i>
                <span class="point_like">({{rand(0,0)}})</span>
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
                <span class="star-number">({{$content['vote']}})</span>
              </div>
              <div class="meta-post-distance">
                  @if($content['line'])
                    @if($content['line'] > 1000)
                    {{round($content['line']/1000,2)}} km
                    @else
                    {{round($content['line'],0)}} m
                    @endif
                  @endif
              </div>
            </div>
          </div>

          <div class="d-lg-flex justify-content-lg-between align-items-lg-end ">
            <ol class="info-contact list-unstyled mb-3 mb-lg-0">
              <li><i class="icon-location"></i>{{$content['address']}}, {{$content['district']->name}}, {{$content['city']->name}}, {{$content['country']->name}}</li>
              <li><i class="icon-phone"></i>{{($content['phone']) == '' ? 'Chưa Xác Định' : $content['phone']}}</li>
              <!-- <li><i class="icon-time"></i>{{$content['open_from']}} - {{$content['open_to']}}</li> -->
              <li><i class="icon-time"></i>{{$content['open_time']}}</li>
              <li><i class="icon-price"></i>{{$content['price_from']}}
                - {{$content['price_to']}} {{$content['currency']}}
              </li>
            </ol>

            <div class="meta-post d-flex align-items-center hidden-md-up">
              <div class="add-like d-flex align-items-center">
                <i class="{{rand(10,10000) ? 'icon-heart' : 'icon-heart-empty' }}"
                   ></i>
                <span class="point_like">({{rand(10,10000)}})</span>
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
                <span class="star-number">({{$content['vote']}})</span>
              </div>
              <div class="meta-post-distance">
                  @if($content['line'])
                    @if($content['line'] > 1000)
                    {{round($content['line']/1000,2)}} km
                    @else
                    {{round($content['line'],0)}} m
                    @endif
                  @endif
              </div>
            </div>

            <!-- end  meta -->
            <div class="box-right ">
              <div class="share d-flex flex-row-reverse mt-3 hidden-md-down">
                <ul class="list-unstyled d-flex flex-row">
                  <li>
                    <a href="#">
                      <i class="icon-google"></i>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="icon-facebook"></i>
                    </a>
                  </li>
                  <li>
                    <a href="#">
                      <i class="icon-twitter-bird"></i>
                    </a>
                  </li>
                </ul>
                <span>{{trans('Location'.DS.'preview.share')}}:</span>
              </div>
            </div>
            <!-- end box-right -->
          </div>
        </div>
      </div>
    </header>
    <!-- /header -->

    @if(count($content['space']) > 0 || count($content['menu']) > 0 || count($content['link']) > 0)
      <section class="section-space bg-gray my-4 px-md-4">
        <div class="container">
          @if(count($content['space']) > 0)
            <div class="box-gallery">
              <div class="title-gallery d-flex justify-content-between align-items-start">
                <h4 class="text-uppercase mb-3">KHÔNG GIAN ({{count($content['space'])}})</h4>
                <a title="">{{ucfirst(trans('global.view_all'))}} <i
                    class="icon-arrow"></i></a>
              </div>
              <ul class="list-gallery list-unstyled row">
                @foreach($content['space'] as $value)
                  <li>
                    <a data-fancybox="images_space">
                      <img class="img-fluid" src="{{$value}}" alt="">
                    </a>
                  </li>
                @endforeach
              </ul>
              <!-- end list-gallery -->
            </div>
          @endif
        <!-- end box-gallery -->
          @if(count($content['menu']) > 0)
            <div class="box-gallery">
              <div class="title-gallery d-flex justify-content-between align-items-start">
                <h4 class="text-uppercase mb-3">MENU ({{count($content['menu'])}})</h4>
                <a 
                   title="">{{ucfirst(trans('global.view_all'))}} <i
                    class="icon-arrow"></i></a>
              </div>
              <ul class="list-gallery list-unstyled row">
                @foreach($content['menu'] as $value)
                  <li>
                    <a data-fancybox="images_menu">
                      <img class="img-fluid" src="{{$value}}" alt="">
                    </a>
                  </li>
                @endforeach
              </ul>
              <!-- end list-gallery -->
            </div>
          @endif

          @if(count($content['link']) > 0)
            <div class="box-gallery">
              <div class="title-gallery d-flex justify-content-between align-items-start">
                <h4 class="text-uppercase mb-3">Video ({{count($content['link'])}})</h4>
                <a 
                   title="">{{ucfirst(trans('global.view_all'))}} <i
                    class="icon-arrow"></i></a>
              </div>
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
                    <li class="iframe-video">
                      <a data-fancybox href="{{$value}}"></a>
                      <iframe width="179" height="130" src="{{str_replace('watch?v=','embed/',$value)}}" frameborder="0" allowfullscreen></iframe>
                    </li>
                  @endif
                @endforeach
              </ul>
              <!-- end list-gallery -->
            </div>
          @endif
        <!-- end box-gallery -->
        </div>
      </section>
    @endif
    
    <!-- end  -->
    <section class="my-4 my-md-5">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 flex-lg-last mb-3 mb-lg-0">
            <ul class="list-group-pd list-unstyled">
              <li><a class="cursor" id="checkin">Check
                  in </a><span id="checkin_total">{{rand(10,2000)}}</span></li>
              <li><a class="cursor">{{trans('Location'.DS.'preview.save_favorites')}}</a> <span
                  id="save_like_content_total">{{rand(10,2000)}}</span></li>
              <li>{{trans('Location'.DS.'preview.add_collection')}} <span></span></li>
              <!-- <li>Chia sẻ địa điểm <span></span></li> -->
            </ul>
          </div>
          <div class="col-lg-8 flex-lg-first">
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
    
    <!-- <section class="my-4 my-md-5">
        <div class="container">
          <div class="box-gallery mb-4">
            <div class="title-gallery">
              <h4 class="text-uppercase mb-3">CHI NHÁNH KHÁC</h4>
            </div>
          </div>
          <ul class="group-card-vertical row list-unstyled">
            @for($i=0;$i<4;$i++)
              <li class="col-lg-3 col-md-4 col-6">
                <div class="card-vertical card">
                  <div class="card-img-top">
                    <a href="" title="">
                      <img class="img-fluid"
                           src="{{$content['avatar']}}"
                           alt="{{$content['name']}}">
                    </a>
                  </div>
                  <div class="card-block py-2 px-0">
                    <div class="card-description">
                      <h6 class="card-title "><a href="" title="{{$content['name']}}">{{$content['name']}} </a>
                      </h6>
                      <p class="card-address text-truncate">{{$content['address']}}, {{$content['district']->name}}, {{$content['city']->name}}, {{$content['country']->name}}</p>
                    </div>
                    <div class="meta-post d-flex align-items-center">
                      <div class="add-like d-flex align-items-center">
                        <i class="icon-heart-empty"></i>
                        <span>({{rand(10,1000)}})</span>
                      </div>
                      <div class="rating d-flex align-items-center">
                        <div class="star-rating hidden-xs-down">
                          <span style="width:{{(rand(1,5)).'%'}}"></span>
                        </div>
                        <i class="icon-star-yellow hidden-sm-up"></i>
                        <span>({{rand(1,5)}})</span>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            @endfor
          </ul>
        </div>
    </section> -->
    <!-- <section class="my-4 my-md-5">
        <div class="container">
          <div class="box-gallery mb-4">
            <div class="title-gallery">
              <h4 class="m-0 text-uppercase mb-2">{{trans('Location'.DS.'preview.suggest')}}</h4>
            </div>
          </div>

          <ul class="group-card-vertical row list-unstyled">
            @for($i=0;$i<4;$i++)
              <li class="col-lg-3 col-md-4 col-6">
                <div class="card-vertical card">
                  <div class="card-img-top">
                    <a href="" title="">
                      <img class="img-fluid"
                           src="{{$content['avatar']}}"
                           alt="{{$content['name']}}">
                    </a>
                  </div>
                  <div class="card-block py-2 px-0">
                    <div class="card-description">
                      <h6 class="card-title "><a href="" title="{{$content['name']}}">{{$content['name']}} </a>
                      </h6>
                      <p class="card-address text-truncate">{{$content['address']}}, {{$content['district']->name}}, {{$content['city']->name}}, {{$content['country']->name}}</p>
                    </div>
                    <div class="meta-post d-flex align-items-center">
                      <div class="add-like d-flex align-items-center">
                        <i class="icon-heart-empty"></i>
                        <span>({{rand(10,1000)}})</span>
                      </div>
                      <div class="rating d-flex align-items-center">
                        <div class="star-rating hidden-xs-down">
                          <span style="width:{{(rand(1,5)).'%'}}"></span>
                        </div>
                        <i class="icon-star-yellow hidden-sm-up"></i>
                        <span>({{rand(1,5)}})</span>
                      </div>
                    </div>
                  </div>
                </div>

              </li>
            @endfor
          </ul>
        </div>
    </section> -->
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

        $( '[data-fancybox]' ).fancybox();
        $( '[data-video]' ).fancybox();
        $( '[data-video-facebook]' ).fancybox({
          type:"iframe"
        });
        // slider
        $('.slider-gallery').slick({
          dots: true,
          infinite: false,
          speed: 300,
          slidesToShow: 4,
          slidesToScroll: 4,
          arrows: true,
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
          infinite: false,
          speed: 300,
          slidesToShow: 6,
          slidesToScroll: 6,
          arrows: false,
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
          "elementType": "labels.icon",
          "stylers": [{
            "visibility": "off"
          }]
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
          navigator.geolocation.getCurrentPosition(showPosition);
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
              directionsDisplay.setMap(map);
              directionsDisplay.setOptions({suppressMarkers: true});
              var request = {
                origin: latLng,
                destination: new google.maps.LatLng({{$content['lat']}}, {{$content['lng']}}),
                travelMode: google.maps.DirectionsTravelMode.DRIVING
              };

              directionsService.route(request, function (response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                  directionsDisplay.setDirections(response);
                }
              });
            }
            else {
              return false;
            }
          });
        }
      }
    </script>
@endsection


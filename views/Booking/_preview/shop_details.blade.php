@section('body')
  <body>
  @endsection

  <div class="sidebar-top  py-2" style="background: #D0021B">
    <div class="container">
       <a class="come-back" href="javascript:history.back()" title="">
        <img src="{{asset('frontend/assets/img/icon/ic-back.png')}}" alt="{{$content->name}}">
      </a>
      {!! $breadcrumb !!}
    </div>
  </div>
  <div class="content-location-detail content-page">
    <header class="header-detail ">
      <div class="container d-md-flex justify-md-content-between">
        <div class="header-detail-left d-flex align-items-md-start align-items-center">
          <div class="avata text-center">
            <a href="" title="">
              <img class="rounded-circle"
                   src="{{asset($content->avatar)}}"
                   alt="">
              @if($open)
                <div class="online status-location">
                  <i class="icon-circle"></i>
                  <span>{{trans('Location'.DS.'preview.opening')}}</span>
                </div>
              @else
                <div class="offline status-location">
                  <i class="icon-circle"></i>
                  <span>{{trans('Location'.DS.'preview.closing')}}</span>
                </div>
              @endif

              <!-- Code cũ -->
              <!-- @if($content->open_from === $content->open_to)
                <div class="online status-location">
                  <i class="icon-circle"></i>
                  <span>{{trans('Location'.DS.'preview.opening')}}</span>
                </div>
              @else
                @if(strtotime($content->open_from) < strtotime($content->open_to))
                  @if(($datetime>$content->open_from) && ($datetime < $content->open_to))
                    <div class="online status-location">
                      <i class="icon-circle"></i>
                      <span>{{trans('Location'.DS.'preview.opening')}}</span>
                    </div>
                  @else
                    <div class="offline status-location">
                      <i class="icon-circle"></i>
                      <span>{{trans('Location'.DS.'preview.closing')}}</span>
                    </div>
                  @endif
                @else
                  @if(strtotime($datetime) < strtotime($content->open_from) && strtotime($datetime) > strtotime($content->open_to))
                    <div class="offline status-location">
                      <i class="icon-circle"></i>
                      <span>{{trans('Location'.DS.'preview.closing')}}</span>
                    </div>
                  @else
                    <div class="online status-location">
                      <i class="icon-circle"></i>
                      <span>{{trans('Location'.DS.'preview.opening')}}</span>
                    </div>
                  @endif
                @endif
              @endif -->
              <!-- End code cũ -->
            </a>
          </div>
          <h1 class="title-restaurant hidden-md-up">{{$content->name}}</h1>
        </div>
        <div class="content">
          <div class="d-lg-flex justify-content-lg-between">
            <h1 class="title-restaurant hidden-sm-down">{{$content->name}}</h1>
            <div class="meta-post d-flex align-items-center hidden-sm-down">
              <div class="add-like d-flex align-items-center">
                <i class="{{isset($like_point) ? 'icon-heart' : 'icon-heart-empty' }}"
                   onclick="getLikeContent({{$content->id}},{{ isset(Auth::guard('web_client')->user()->id)?Auth::guard('web_client')->user()->id:'' }})"></i>
                <span class="point_like">({{$content->like}})</span>
              </div>
              <div class="rating d-flex align-items-center" data-vote="0">
                <div class="star hidden">
                                    <span class="full {{(isset($vote_point) && $vote_point>=1) ? 'star-colour' : ''}}"
                                          data-value="1"></span>
                  <span class="half {{(isset($vote_point) && $vote_point>=0.5) ? 'star-colour' : ''}}"
                        data-value="0.5"></span>
                </div>
                <div class="star">
                                    <span class="full {{(isset($vote_point) && $vote_point>=2) ? 'star-colour' : ''}}"
                                          data-value="2"></span>
                  <span class="half {{(isset($vote_point) && $vote_point>=1.5) ? 'star-colour' : ''}}"
                        data-value="1.5"></span>
                  <span class="selected"></span>
                </div>
                <div class="star">
                                    <span class="full {{(isset($vote_point) && $vote_point>=3) ? 'star-colour' : ''}}"
                                          data-value="3"></span>
                  <span class="half {{(isset($vote_point) && $vote_point>=2.5) ? 'star-colour' : ''}}"
                        data-value="2.5"></span>
                  <span class="selected"></span>
                </div>
                <div class="star">
                                    <span class="full {{(isset($vote_point) && $vote_point>=4) ? 'star-colour' : ''}}"
                                          data-value="4"></span>
                  <span class="half {{(isset($vote_point) && $vote_point>=3.5) ? 'star-colour' : ''}}"
                        data-value="3.5"></span>
                  <span class="selected"></span>
                </div>
                <div class="star">
                                    <span class="full {{(isset($vote_point) && $vote_point>=5) ? 'star-colour' : ''}}"
                                          data-value="5"></span>
                  <span class="half {{(isset($vote_point) && $vote_point>=4.5) ? 'star-colour' : ''}}"
                        data-value="4.5"></span>
                  <span class="selected"></span>
                </div>
                <span class="star-number">({{$content->vote}})</span>
              </div>
              <div class="meta-post-distance">
                  @if($content->line)
                    @if($content->line > 1000)
                    {{round($content->line/1000,2)}} km
                    @else
                    {{round($content->line,0)}} m
                    @endif
                  @endif
              </div>
            </div>
          </div>

          <div class="d-lg-flex justify-content-lg-between align-items-lg-end ">
            <ol class="info-contact list-unstyled mb-3 mb-lg-0">
              <li><i class="icon-location"></i>{{$content->address}}, {{$content->_district->name}}, {{$content->_city->name}}, {{$content->_country->name}}</li>
              <li><i class="icon-phone"></i>{{($content->phone) == '' ? 'Chưa Xác Định' : $content->phone}}</li>
              <!-- <li><i class="icon-mail"></i>{{($content->email) == '' ? 'Chưa Xác Định' : $content->email}}</li> -->
              <li><i class="icon-time"></i>{{ $open_time }}</li>
              <li><i class="icon-price"></i>{{$content->price_from}}
                - {{$content->price_to}} {{$content->currency}}
              </li>

              @if(!$content->confirm)
              <li>
                <a href="#" onclick="confirmLocation({{$content->id}},{{ isset(Auth::guard('web_client')->user()->id)?Auth::guard('web_client')->user()->id:'' }})"  title="{{trans('Location'.DS.'preview.confirm_your_location')}}">
                  <i class="icon-help-circled"></i>
                  {{trans('Location'.DS.'preview.confirm_your_location')}}
                </a>
              </li>
              @endif
            </ol>

            <div class="meta-post d-flex align-items-center hidden-md-up">
              <div class="add-like d-flex align-items-center">
                <i class="{{isset($like_point) ? 'icon-heart' : 'icon-heart-empty' }}"
                   onclick="getLikeContent({{$content->id}},{{ isset(Auth::guard('web_client')->user()->id)?Auth::guard('web_client')->user()->id:'' }})"></i>
                <span class="point_like">({{$content->like}})</span>
              </div>
              <div class="rating d-flex align-items-center" data-vote="0">
                <div class="star hidden">
                                    <span class="full {{(isset($vote_point) && $vote_point>=1) ? 'star-colour' : ''}}"
                                          data-value="1"></span>
                  <span class="half {{(isset($vote_point) && $vote_point>=0.5) ? 'star-colour' : ''}}"
                        data-value="0.5"></span>
                </div>
                <div class="star">
                                    <span class="full {{(isset($vote_point) && $vote_point>=2) ? 'star-colour' : ''}}"
                                          data-value="2"></span>
                  <span class="half {{(isset($vote_point) && $vote_point>=1.5) ? 'star-colour' : ''}}"
                        data-value="1.5"></span>
                  <span class="selected"></span>
                </div>
                <div class="star">
                                    <span class="full {{(isset($vote_point) && $vote_point>=3) ? 'star-colour' : ''}}"
                                          data-value="3"></span>
                  <span class="half {{(isset($vote_point) && $vote_point>=2.5) ? 'star-colour' : ''}}"
                        data-value="2.5"></span>
                  <span class="selected"></span>
                </div>
                <div class="star">
                                    <span class="full {{(isset($vote_point) && $vote_point>=4) ? 'star-colour' : ''}}"
                                          data-value="4"></span>
                  <span class="half {{(isset($vote_point) && $vote_point>=3.5) ? 'star-colour' : ''}}"
                        data-value="3.5"></span>
                  <span class="selected"></span>
                </div>
                <div class="star">
                                    <span class="full {{(isset($vote_point) && $vote_point>=5) ? 'star-colour' : ''}}"
                                          data-value="5"></span>
                  <span class="half {{(isset($vote_point) && $vote_point>=4.5) ? 'star-colour' : ''}}"
                        data-value="4.5"></span>
                  <span class="selected"></span>
                </div>
                <span class="star-number">({{$content->vote}})</span>
              </div>
              <div class="meta-post-distance">
                  @if($content->line)
                    @if($content->line > 1000)
                    {{round($content->line/1000,2)}} km
                    @else
                    {{round($content->line,0)}} m
                    @endif
                  @endif
              </div>
            </div>

            <!-- end  meta -->
            <div class="box-right ">
              <!--<a class="btn btn-primary" href="" title="">Chat trực tuyến</a>-->
              <div class="share d-flex flex-row-reverse mt-3 hidden-md-down">
                <ul class="list-unstyled d-flex flex-row">
                  <li>
                    <a href="#" onclick="sharePopup('https://plus.google.com/share?url={{urlencode(url()->current())}}')">
                      <i class="icon-google"></i>
                    </a>
                  </li>
                  <li>
                    <a href="#" onclick="sharePopup('https://www.facebook.com/sharer/sharer.php?u={{urlencode(url()->current())}}&amp;src=sdkpreparse')">
                      <i class="icon-facebook"></i>
                    </a>
                  </li>
                  <li>
                    <a href="#" onclick="sharePopup('https://twitter.com/share?text={!! clear_str($content->name) !!}&url={{urlencode(url()->current())}}&hashtags=Kingmap')">
                      <i class="icon-twitter-bird"></i>
                    </a>
                  </li>
                </ul>
                <span>Chia sẻ:</span>
              </div>
            </div>
            <!-- end box-right -->
          </div>
        </div>
      </div>
    </header>
    <!-- /header -->

    @if(count($image_space) > 0 || count($link_video) > 0)
      <section class="section-space bg-gray my-4 px-md-4">
        <div class="container">
          @if(count($image_space) > 0)
            <div class="box-gallery">
              <div class="title-gallery d-flex justify-content-between align-items-start">
                <h4 class="text-uppercase mb-3">KHÔNG GIAN ({{count($image_space)}})</h4>
                <a href="{{url('detail-photo/'.$content->alias.'/space')}}" title="">{{ucfirst(trans('global.view_all'))}} <i
                    class="icon-arrow"></i></a>
              </div>
              <ul class="list-gallery list-unstyled row">
                @foreach($image_space as $value)
                  <li>
                    <a data-fancybox="images_space"  href="{{$value}}">
                      <img class="img-fluid" src="{{str_replace('img_content','img_content_thumbnail',$value)}}" alt="">
                    </a>
                  </li>
                @endforeach
              </ul>
              <!-- end list-gallery -->
            </div>
          @endif
        <!-- end box-gallery -->

          @if(count($link_video) > 0)
            <div class="box-gallery">
              <div class="title-gallery d-flex justify-content-between align-items-start">
                <h4 class="text-uppercase mb-3">Video ({{count($link_video)}})</h4>
                <a href="{{url('detail-photo/'.$content->alias.'/menu')}}"
                   title="">{{ucfirst(trans('global.view_all'))}} <i
                    class="icon-arrow"></i></a>
              </div>
              <ul class="list-gallery list-unstyled row">
                @foreach($link_video as $value)
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
              <li><a class="cursor" id="checkin"
                     onclick="checkinContent({{$content->id}},{{ isset(Auth::guard('web_client')->user()->id)?Auth::guard('web_client')->user()->id:'' }})">Check
                  in </a><span id="checkin_total">{{$content->checkin}}</span></li>
              <li><a
                  onclick="saveLikeContent({{$content->id}},{{ isset(Auth::guard('web_client')->user()->id)?Auth::guard('web_client')->user()->id:'' }})"
                  class="cursor">Lưu vào yêu thích</a> <span
                  id="save_like_content_total">{{$content->save_like_content}}</span></li>
              <li>Thêm vào bộ sưu tập <span></span></li>
              <!-- <li>Chia sẻ địa điểm <span>12</span></li> -->
            </ul>
          </div>
          <div class="col-lg-8 flex-lg-first">
            <ul class="list-info-restaurant list-unstyled clearfix">
              @foreach($list_service as $value)
                <li class="{{!in_array($value->id_service_item, $service_content) ? 'disabled':''}}">@lang(mb_ucfirst($value->_service_item->name))
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
        <div class="header-style1 header mb-3">
          <h3>ĐỊA ĐIỂM GẦN BẠN</h3>
        </div>
        <div id="map-2"></div>
      </div>
    </section>
    <!-- end  -->
    @isset($list_group)
      @if(count($list_group) > 0)
        <section class="my-4 my-md-5">
          <div class="container">
            <div class="box-gallery mb-4">
              <div class="title-gallery">
                <h4 class="text-uppercase mb-3">CHI NHÁNH KHÁC</h4>
              </div>
              <div class="slider-gallery">

                @foreach($list_group as $value)
                  <div class="item-gallery">
                    <div class="img">
                      <a href="{{url($value->alias)}}" title="">
                        <img src="{{asset($value->avatar)}}"
                             alt="">
                      </a>
                    </div>
                    <div class="description">
                      <h6><a href="{{url($value->alias)}}" title="">{{$value->_district->name}}</a>
                      </h6>
                      <p class="mb-0 address text-truncate">{{$value->address}}, {{$value->_district->name}}, {{$value->_city->name}}, {{$value->_country->name}}</p>
                    </div>
                  </div>
              @endforeach
              <!-- end  item gallery -->
              </div>
            </div>
          </div>
        </section>
      @endif
    @endisset
  <!-- end -->
    @if(count($list_suggest) > 0)
      <section class="my-4 my-md-5">
        <div class="container">
          <div class="box-gallery mb-4">
            <div class="title-gallery">
              <h4 class="m-0 text-uppercase mb-2">{{trans('Location'.DS.'preview.suggest')}}</h4>
            </div>
          </div>
          <!-- end box-gallery -->
          <ul class="group-card-vertical row list-unstyled">
            @foreach($list_suggest as $value)
              <li class="col-lg-3 col-md-4 col-6">
                <div class="card-vertical card">
                  <div class="card-img-top">
                    <a href="{{url($value->alias)}}" title="{{url($value->alias)}}">
                      <img class="img-fluid"
                           src="{{asset($value->avatar)}}"
                           alt="Card image cap">
                    </a>
                  </div>
                  <div class="card-block py-2 px-0">
                    <div class="card-description">
                      <h6 class="card-title "><a href="{{url($value->alias)}}"
                                                 title="{{$value->name}}">{{$value->name}} </a>
                      </h6>
                      <p class="card-address text-truncate">{{$value->address}}, {{$value->_district->name}}, {{$value->_city->name}}, {{$value->_country->name}}</p>
                    </div>
                    <div class="meta-post d-flex align-items-center">
                      <div class="add-like d-flex align-items-center">
                        <i class="icon-heart"></i>
                        <span>({{$value->like}})</span>
                      </div>
                      <div class="rating d-flex align-items-center">
                        <div class="star-rating hidden-xs-down">
                          <span style="width:{{($value->vote*20).'%'}}"></span>
                        </div>
                        <i class="icon-star-yellow hidden-sm-up"></i>
                        <span>({{$value->vote}})</span>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end card post -->
              </li>
            @endforeach
          </ul>
        </div>
      </section>
    @endif
  </div>

  <div id="modal-notify-content" class="modal fade  modal-vertical-middle modal-report show modal-animation" data-backdrop="false"  tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content p-4">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <!-- close -->
        <div class="modal-logo pt-4 text-center">
          <img src="{{isset($notify_content) ? asset($content->avatar) : ''}}" alt="">
        </div>
        <!-- end logo -->
        <h4 class="text-uppercase text-center">Thông báo</h4>
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
    <script>
      $(document).ready(function () {

        $( '[data-fancybox]' ).fancybox();
        $( '[data-video]' ).fancybox();
        $( '[data-video-facebook]' ).fancybox({
          type:"iframe"
        });
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
              dots: true
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
          styles: style_map,
          zoomControl: false,
          mapTypeControl: false,
          scaleControl: false,
          streetViewControl: false,
          rotateControl: true,
          fullscreenControl: true,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          mapTypeControl: false,
          center: new google.maps.LatLng({{$content->lat}}, {{$content->lng}}),
        };
        var image = '{{asset('frontend/assets/img/logo/Logo-maps.png')}}';
        var mapElement = document.getElementById('map-2');
        var map = new google.maps.Map(mapElement, mapOptions);
        var marker = new google.maps.Marker({
          position: new google.maps.LatLng({{$content->lat}}, {{$content->lng}}),
          map: map,
          title: '{{$content->name}}',
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
          center: new google.maps.LatLng({{$content->lat}}, {{$content->lng}}),
        };
        var image = '{{asset('frontend/assets/img/logo/Logo-maps.png')}}';
        var mapElement = document.getElementById('map-2');
        var map = new google.maps.Map(mapElement, mapOptions);
        if (geocoder_detail) {
          geocoder_detail.geocode({'latLng': latLng}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              var startMarker = new google.maps.Marker({position: latLng, map: map});
              var stopMarker = new google.maps.Marker({
                position: new google.maps.LatLng({{$content->lat}}, {{$content->lng}}),
                map: map,
                icon: image
              });
              directionsDisplay.setMap(map);
              directionsDisplay.setOptions({suppressMarkers: true});
              var request = {
                origin: latLng,
                destination: new google.maps.LatLng({{$content->lat}}, {{$content->lng}}),
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
      var check = {{isset($vote_point) ? $vote_point : 'null'}};
      if (check != null) {
        var starClicked = true;
      }

      $(function () {

        $('.star').click(function () {
          var id_user = {{isset(Auth::guard('web_client')->user()->id) ? Auth::guard('web_client')->user()->id : 'null'}};
          if (id_user == null) {
            $('#modal-signin').modal('show');
          }
          else {

            $(this).children('.selected').addClass('is-animated');
            $(this).children('.selected').addClass('pulse');

            var target = this;

            setTimeout(function () {
              $(target).children('.selected').removeClass('is-animated');
              $(target).children('.selected').removeClass('pulse');
            }, 1000);

            starClicked = true;
          }
        });

        $('.half').click(function () {
          if (starClicked === true) {
            // default
            // setHalfStarState(this);
            return false;
          }
          $(this).closest('.rating').find('.js-score').text($(this).data('value'));

          $(this).closest('.rating').data('vote', $(this).data('value'));
          calculateAverage({{$content->id}},{{isset(Auth::guard('web_client')->user()->id) ? Auth::guard('web_client')->user()->id : 'null'}}, $(this).data('value'));
        });

        $('.full').click(function () {

          if (starClicked === true) {
            // default
            // setHalfStarState(this);
            return false;
          }
          $(this).closest('.rating').find('.js-score').text($(this).data('value'));

          $(this).find('js-average').text(parseInt($(this).data('value')));

          $(this).closest('.rating').data('vote', $(this).data('value'));
          calculateAverage({{$content->id}},{{isset(Auth::guard('web_client')->user()->id) ? Auth::guard('web_client')->user()->id : 'null'}}, $(this).data('value'));

        });

        $('.half').hover(function () {
          if (starClicked === false) {
            setHalfStarState(this);
          }
        });

        $('.full').hover(function () {
          if (starClicked === false) {
            setFullStarState(this);
          }
        });

      });

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
                toastr.info('{{trans('Location'.DS.'preview.have_checked')}}');
              } else {
                toastr.warning('{{trans('Location'.DS.'preview.have_unchecked')}}');
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
                toastr.info('{{trans('Location'.DS.'preview.have_saved')}}');
              } else {
                toastr.warning('{{trans('Location'.DS.'preview.have_unsaved')}}');
              }
            }
          }
        })
      }
    </script>
@endsection


<!DOCTYPE html>
<html class="no-js " lang="en">
<head>
  <!-- Basic Page Needs -->
  <meta charset="utf-8">
  <meta name="robots" content="noindex">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no,maximum-scale=1, user-scalable=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta itemprop="url" content="{{url()->current()}}">
  <link rel="shortcut icon" type="image/gif/png" href="{{asset($favicon)}}">
  <title>{!! $title !!}</title>
  <meta name="description" content="{!! isset($meta_description) ? $meta_description : 'King Map' !!}">
  <meta name="keywords" content="{!! isset($meta_tag) ? $meta_tag : 'King Map' !!}">
  <meta name="author" content="Kingmap">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="_token" content="{{ csrf_token() }}">
  <!-- Social Meta -->

  <!-- Twitter Card data -->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:site" content="@Kingmap2">
  <meta name="twitter:title" content="{!! $title !!}">
  <meta name="twitter:description" content="{!! isset($meta_description) ? $meta_description : 'King Map' !!}">
  <meta name="twitter:creator" content="@Kingmap2">
  <!-- Twitter summary card with large image must be at least 280x150px -->
  <meta name="twitter:image"
        content="{{isset($meta_image)?$meta_image:url('/').'/img_default/share_image.png'}}">

  <!-- FB -->
  <meta property="og:url" content="{{url()->current()}}"/>
  <meta property="og:site_name" content="KingMap.vn">
  <meta property="og:locale" content="vi_VN">
  <meta property="og:type" content="article"/>
  <meta property="og:title" content="{!! $title !!}"/>
  <meta property="og:description" content="{!! isset($meta_description) ? $meta_description : 'King Map' !!}"/>
  <meta property="og:image" content="{{isset($meta_image)?$meta_image:url('/').'/img_default/share_image.png'}}"/>
  <meta property="og:image:url" content="{{isset($meta_image)?$meta_image:url('/').'/img_default/share_image.png'}}"/>

  <meta property="og:image:width" content="270" />
  <meta property="og:image:height" content="202" />

  <meta property="og:image" content="{{url('/').'/img_default/share_image.png'}}"/>
  <meta property="og:image:url" content="{{url('/').'/img_default/share_image.png'}}"/>

  <meta property="og:image:width" content="634" />
  <meta property="og:image:height" content="347" />
  <!-- <meta property="og:image:secure_url" content="{{isset($meta_image)?$meta_image:url('/').'/img_default/share_image.png'}}"/> -->
  <meta property="fb:app_id" content="1989720184605994" />
  <meta property="fb:admins" content="100018744699790"/>
  <meta property="article:section" content="{!! isset($meta_section) ? $meta_section : 'King Map' !!}"/>
  @if(isset($meta_tag))
    @foreach(explode(',',$meta_tag) as $tag)
  <meta property="article:tag" content="{{$tag}}"/>
    @endforeach
  @endif
  

  <!-- Schema.org markup for Google+ -->
  <meta itemprop="name" content="{!! $title !!}">
  <meta itemprop="description" content="{!! isset($meta_description) ? $meta_description : 'King Map' !!}">
  <meta itemprop="image" content="{{isset($meta_image)?$meta_image:url('/').'/img_default/share_image.png'}}">

  <!-- End Social Meta -->
  <!--<link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">-->
  <link href="{{asset('template/vendors/jquery.tagsinput/src/jquery.tagsinput.css')}}" rel="stylesheet" />
  <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="/frontend/assets/fonts/stylesheet.css">
  <link rel="stylesheet" href="/frontend/assets/fonts/ionicons/css/fontello.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css">
  <link href="{{asset('template/js/toastr/build/toastr.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="/frontend/assets/css/jquery.mCustomScrollbar.min.css">
  <link rel="stylesheet" href="/frontend/assets/css/animate.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.2/chosen.css">
  
  <!-- Custom CSS  -->
  <link rel="stylesheet" href="/frontend/assets/css/main.min.css">
  <link rel="stylesheet" href="/frontend/assets/css/discount.min.css">
  <style type="text/css" media="screen">
    .cursor {
      cursor: pointer;
    }
  </style>
  {!! isset($google_analytics) ? $google_analytics : '' !!}
  @yield('CSS')
		<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!--Javascript Library -->
  <script src="/frontend/assets/js/jquery-3.2.1.min.js"></script>
  <script src="/frontend/assets/js/jquery-migrate-3.0.0.js"></script>
  <!-- Modernizr js -->
  <script src="/frontend/assets/js/modernizr-2.8.3.min.js"></script>
  <script src="/frontend/assets/js/jquery-ui.min.js"></script>
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- Modernizr js -->
  <script src="/frontend/assets/js/modernizr.custom.js"></script>

</head>
@yield('body')

@yield('modal')
@include('Location.layout.loading')
@include('Location.layout.add-location')

<div id="wrapper">
  <div class="mp-pusher" id="mp-pusher">
    @include('Location.layout.menu_mobile')
    <div class="scroller">
      <div class="scroller-inner">
        <!-- header -->
        @include('Location.layout.header')
        <!-- /header -->
        <!--  Content -->
        {!! $content !!}
        <!-- /Content -->
        <!-- footer -->
        <a style="zoom:150%;" class="url-cread-address {{ \Request::path() == '/' || \Request::is('user*') ? 'create-address-active' : '' }}" href="javascript: getFromCreateLocation({{ isset(Auth::guard('web_client')->user()->id)?Auth::guard('web_client')->user()->id:'' }});" title="Tạo địa điểm">
          <i class="icon-new-white"></i>
        </a>
        @include('Location.layout.footer')
        <!-- /footer -->
      </div>
    </div>
  </div>
</div>

@include('Location.layout.modal-login')
@include('Location.layout.modal-signup')
@include('Location.layout.modal-forgotpassword')
<!-- <a id="back-to-top" class="back-top" href="" title=""><i class="icon-back-top"></i></a> -->

<!-- getbootstrap -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
<script src="{{asset('template/vendors/jquery.tagsinput/src/jquery.tagsinput.js')}}"></script>
<script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyA4_lZ8uw0hpJfJxVHnK_vBBXZckA-0Tr0"></script>
<script src="/frontend/assets/js/jquery.mCustomScrollbar.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js"></script>
<script src="{{asset('template/js/toastr/build/toastr.min.js')}}"></script>
<script src="https://cdn.rawgit.com/leafo/sticky-kit/v1.1.2/jquery.sticky-kit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.2/chosen.jquery.js"></script>


<!-- Main Script -->
<script src="/frontend/assets/vendor/menumobile/classie.js"></script>

<script src="/frontend/assets/vendor/menumobile/mlpushmenu.js"></script>
<script src="/frontend/assets/js/main_edit.js"></script>
<script src="/frontend/assets/js/CustomGoogleMapMarker.js"></script>
<script src="/frontend/assets/vendor/file-upload/jquery.iframe-transport.js"></script>
<script src="/frontend/assets/vendor/file-upload/jquery.fileupload.js"></script>
<script src="{{asset('template/function.js')}}"></script>

<script type="text/javascript">
  var search_header = "{{trans('global.view_all')}}";
  function changeLanguage(obj) {
    var lang = $(obj).val();
    $.ajax({
      url: '/setlanguage/' + lang,
      type: 'GET',
      success: function (data) {
        window.location.reload();
      }
    })
  }
  function sharePopup(url) {
    var isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
    if(isFirefox){
      newwindow = window.open(url, 'new', 'height=800','width=600');
      if (window.focus) {
        newwindow.focus()
      }
    }else{
      newwindow = window.open(url, 'new', 'height=800','width=600');
      if (window.focus) {
        newwindow.focus()
      }
    }
    return false;
  }
  toastr.options = {
    "positionClass": "toast-bottom-right"
  }

  function getFromCreateLocation(id_user) {
    if (id_user === undefined) {
      $('#modal-signin').modal('show');
    }
    else {
      $('#modal-new-location').modal('show');
    }
  }
</script>

<script src="/frontend/assets/js/jquery.cropit.js"></script>

<script>
    new mlPushMenu( document.getElementById( 'mp-menu' ), document.getElementById( 'trigger' ) );
</script>
<!-- Section JS -->
@yield('JS')
<!-- End Section JS -->

<!-- Section JS -->
@include('Location.layout.add-location-js') 
<!-- End Section JS -->
</body>
</html>
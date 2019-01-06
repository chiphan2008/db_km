<!DOCTYPE html>
<html class="no-js " lang="en">

<head>
  <!-- Basic Page Needs -->
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>King Maps</title>
  <meta name="description" content="#">
  <meta name="keywords" content="#">
  <meta name="author" content="#">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{asset('frontend/assets/fonts/stylesheet.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/assets/fonts/ionicons/css/fontello.css')}}">
  <!-- bổ sung trang này -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css">
  <!-- Custom CSS  -->
  <link rel="stylesheet" href="{{asset('frontend/assets/css/main.min.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/assets/css/jquery.mCustomScrollbar.min.css')}}">
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- Modernizr js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
</head>
<body class="location-detail-photo">
<div id="wrapper">
  <div class="box-wrapper">
    <div class="location-detail-photo-page">
      <div class="sidebar-top  py-2">
        <div class="container d-flex align-items-center justify-content-between">
          <a class="come-back" href="{{url($content->alias)}}" title="{{$content->name}}">
            <img src="{{asset('frontend/assets/img/icon/ic-back.png')}}" alt="{{$content->name}}">{{$content->name}}
          </a>
          @if(isset($data['user']))
            <a class="profile-avata hidden-xs-down" href="{{url('/')}}/user/{{$data['user']->id}}">
              <img class="rounded-circle" src="{{$data['user']->avatar}}" alt="{{$data['user']->full_name}}">
              {{$data['user']->full_name}}
            </a>
          @endif
        </div>
      </div>
      <!-- end  Banner -->
      <div class="location-detail-photo-content">
        <div class="container">
          <!-- Nav tabs -->
          <ul class="list-unstyled nav-tab" role="tablist">
            @if($data['count_image_space'] > 0)
              <li class="nav-item">
                <a class="nav-link {{$type == 'space' ? 'active' : ''}}" data-toggle="tab" href="#space" role="tab">
                KHÔNG GIAN ({{$data['count_image_space']}})</a>
              </li>
            @endif
            @if($data['count_image_menu'] > 0)
              <li class="nav-item">
                <a class="nav-link {{$type == 'menu' ? 'active' : ''}}" data-toggle="tab" href="#menu" role="tab">
                MENU ({{$data['count_image_menu']}})</a>
              </li>
            @endif
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div class="tab-pane {{$type == 'space' ? 'active' : ''}}" id="space" role="tabpanel">
              <div class="scroll-gallery">
                <div class="list-gallery">
                  @foreach($data['image_space'] as $value)
                    <div class="item-gallery">
                      <a data-fancybox="images_space"  href="{{$value}}">
                      <img src="{{str_replace('img_content','img_content_thumbnail',$value)}}" alt="">
                      </a>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
            <div class="tab-pane {{$type == 'menu' ? 'active' : ''}}" id="menu" role="tabpanel">
              <div class="scroll-gallery">
                <div class="list-gallery">
                  @foreach($data['image_menu'] as $value)
                    <div class="item-gallery">
                      <a data-fancybox="images_menu"  href="{{$value}}">
                        <img src="{{str_replace('img_content','img_content_thumbnail',$value)}}" alt="">
                      </a>
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<!--Javascript Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- getbootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>

<!-- Main Script -->
{{--<<script src="assets/js/main.js"></script>--}}
<!-- bổ sung trang này -->
<!-- css de o tren -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.js"></script>

<script src="{{asset('frontend/assets/js/jquery.mCustomScrollbar.js')}}"></script>
<script>
  $('.location-detail-photo-content .scroll-gallery').mCustomScrollbar({
    theme: "dark"
  });
</script>

</body>

</html>

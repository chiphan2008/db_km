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
  <link rel="stylesheet" href="{{asset('frontend/assets/fonts/ionicons/css/fontello.css')}}../../">
  <link rel="stylesheet" href="{{asset('frontend/assets/fonts/stylesheet.css')}}">
  <link rel="stylesheet" href="{{asset('frontend/assets/css/jquery.mCustomScrollbar.min.css')}}">
  <!-- Custom CSS  -->
  <link rel="stylesheet" href="{{asset('frontend/assets/css/main.min.css')}}">
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <!-- Modernizr js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
</head>

<body class="page-sign">
<div id="wrapper">
  <div class="box-wrapper">
    <!-- /header -->
    <div class="bg-gray">
      <div class="modal-dialog my-0 py-5">
        <!-- modal-header -->
        <div class="modal-header">
          <a class="modal-logo" href="" title="">
            <img src="{{asset('frontend/assets/img/logo/logo-large.svg')}}" alt="">
          </a>
          <button type="button" class="close hidden-sm-up" data-dismiss="modal" aria-label="Close">
            <img src="{{asset('frontend/assets/img/icon/dark.png')}}" alt="">
          </button>
        </div>
        <div class="modal-body">
          <h3 class="title-form mr-0 text-uppercase text-center mb-3">{{trans('Location'.DS.'layout.change_pwd')}}</h3>
          <form class="form-sin" method="post" action="{{ url('client-password/reset') }}">
            {{ csrf_field() }}
            <input type="hidden" name="token" value="{{ $token }}">
            @if(isset($email))
              <fieldset class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                <input id="email" type="text" class="form-control" name="email" value="{{$email}}" style="display: none" required placeholder="Email">
                @if ($errors->has('email'))
                  <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                @endif
              </fieldset>
            @else
              <fieldset class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                <input id="email" type="text" class="form-control" name="email" required placeholder="Email">
                @if ($errors->has('email'))
                  <div class="form-control-feedback">{{ $errors->first('email') }}</div>
                @endif
              </fieldset>
            @endif

            <fieldset class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
              <input id="password" type="password" class="form-control" name="password" placeholder="{{trans('Location'.DS.'preview.pwd')}}" required>
              @if ($errors->has('password'))
                <div class="form-control-feedback">{{ $errors->first('password') }}</div>
              @endif
            </fieldset>
            <fieldset class="form-group {{ $errors->has('password_confirmation') ? ' has-danger' : '' }}">
              <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{trans('Location'.DS.'preview.re_pwd')}}" required>
              @if ($errors->has('password_confirmation'))
                <div class="form-control-feedback">{{ $errors->first('password_confirmation') }}</div>
              @endif
            </fieldset>
            <div class="form-group">
              <input type="submit" class="btn-sin btn btn-primary d-block" value="{{trans('Location'.DS.'preview.login')}}"/>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- end  footer -->
  </div>
</div>
<!--Javascript Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- getbootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
<script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrq3yYhDW_SWahQL86CRgcDq6sfpjgZKg"></script>
<!-- <script src="assets/js/CustomGoogleMapMarker.js"></script> -->
<script src="../../frontend/assets/js/jquery.mCustomScrollbar.js"></script>
<!-- Main Script -->
<script src="../../frontend/assets/js/main.js"></script>
</body>

</html>

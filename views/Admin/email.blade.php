<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta name="robots" content="noindex">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>King Map</title>

  <!-- Bootstrap -->
  <link href="{{asset('template/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="{{asset('template/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
  <!-- NProgress -->
  <link href="{{asset('template/vendors/nprogress/nprogress.css')}}" rel="stylesheet">
  <!-- Animate.css -->
  <link href="{{asset('template/vendors/animate.css/animate.min.css')}}" rel="stylesheet">
  <!-- Custom Theme Style -->
  <link href="{{asset('template/build/css/custom.min.css')}}" rel="stylesheet">
</head>

<body class="login">
<div>
  <div class="login_wrapper">
    <div class="animate form login_form">
      <section class="login_content">
        <form method="POST" action="{{ url('send/email') }}">
          {{ csrf_field() }}
          <h1><img style="line-height: 20px;margin: -10px 0 10px;" src="{{asset('frontend/assets/img/logo/Logo.png')}}">
          </h1>
          @if (session('status'))
            <span style="color: #009926">Email has sent , Please check your email</span>
          @endif
          @if ($errors->has('errorlogin'))
            <span style="color: red">{{ $errors->first('errorlogin') }}</span>
          @endif
          <div>
            <input type="email" class="form-control" name="email" id="email" placeholder="Email" />
          </div>
          @if ($errors->has('email'))
            <span style="color: red">{{ $errors->first('email') }}</span>
          @endif
          <div>
            <button type="submit" class="btn btn-danger submit">
              Send Mail
            </button>
          </div>

          <div class="clearfix"></div>

        </form>
      </section>
    </div>
  </div>
</div>
</body>
</html>

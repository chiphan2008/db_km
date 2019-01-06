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
        <form method="POST" action="{{ route('login') }}">
          {{ csrf_field() }}
          <h1><img style="line-height: 20px;margin: -10px 0 10px;" src="{{asset('frontend/assets/img/logo/Logo.png')}}"></h1>
          <div>
            <input type="email" class="form-control" name="email" id="email" placeholder="Email" />
          </div>
          <div>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" />
          </div>
          @if ($errors->has('password') || $errors->has('email'))
            <div style="color: red">Email or Password is incorrect</div>
          @endif
          <div>
            <button type="submit" class="btn btn-default submit">
              Log in
            </button>
            <a class="reset_pass" href="{{route('forgot_pass')}}">Lost your password?</a>
          </div>

          <div class="clearfix"></div>

        </form>
      </section>
    </div>
  </div>
</div>
</body>
</html>

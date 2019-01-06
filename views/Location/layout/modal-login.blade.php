<!-- popup login  -->
<div class="modal-transform modal-sig modal  modal-vertical-middle " id="modal-signin" tabindex="-1" role="dialog"
     aria-labelledby="modal-login">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <!-- modal-header -->
      <div class="modal-header">
        <a class="modal-logo" href="" title="">
          <img src="/frontend/assets/img/logo/logo-large.svg" alt="">
        </a>
        <button type="button" class="close hidden-sm-up" data-dismiss="modal" aria-label="Close">
          <img src="/frontend/assets/img/icon/dark.png" alt="">
        </button>
      </div>
      <div class="modal-body">
        <h3 class="title-form mr-0 text-uppercase">{{trans('global.login')}}</h3>
        <form class="form-sin" id="form-sin" method="post" action="{{ url('/login') }}">

          <fieldset class="form-group" id="fieldset-email-li">
            <input type="text" class="form-control" id="email-li" name="email" placeholder="Email">
            <div class="form-control-feedback text-danger" id="div-email-li" style="display: none"></div>
          </fieldset>
          <fieldset class="form-group" id="fieldset-password-li">
            <input type="password" class="form-control" id="password-li" name="password" placeholder="{{trans('global.password')}}">
            <div class="form-control-feedback text-danger" id="div-password-li" style="display: none"></div>
          </fieldset>
          <div class="row">
            <div class="form-group h mb-0 col-7">
              <label class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="remember">
                <span class="custom-control-indicator"></span>
                <span class="custom-control-description">{{trans('Location'.DS.'layout.remember_me')}}</span>
              </label>
            </div>
            <label class="forgot-pass col-5 text-right"><a href="" title="" data-toggle="modal" data-target="#modal-forget-pass">{{trans('global.forgot_password')}}?</a></label>
          </div>
          <div class="form-group">
            <button type="submit" class="btn-sin btn btn-primary d-block">{{trans('global.login')}}</button>
          </div>
        </form>
      </div>
      <!-- modal-body -->
      <div class="modal-footer d-flex justify-content-center">
        <a href="{{ URL::to('client/google') }}" title=""><img src="/frontend/assets/img/icon/google.svg" alt=""></a>
        <a href="{{ URL::to('client/facebook') }}" title=""><img src="/frontend/assets/img/icon/facebook.svg" alt=""></a>
      </div>
    </div>
    <p class="text text-center">{{trans('global.no_account')}}? <a href="" title="" data-toggle="modal" data-target="#modal-signup">{{trans('global.register_now')}}!</a></p>

    {{--<p class="text text-center">--}}
      {{--<a href="#" title="" data-toggle="modal" data-target="#modal-register-invite">--}}
      {{--{{trans('global.make_money')}}!</a>--}}
      <!-- <a href="#" title="">{{trans('global.policy_invite')}}!</a> -->
    {{--</p>--}}
  </div>
</div>

<script type="text/javascript">

  $(document).ready(function () {
    $("#form-sin").on('submit', function (event) {
      event.preventDefault();

      var _token = $("meta[name='_token']").prop('content');
      var email = $('input[id="email-li"]').val();
      var password = $('input[id="password-li"]').val();
      var remember = $("[name=remember]").is(":checked")?true:null;
      if(email==''){
        $("#fieldset-email-li").addClass("has-danger");
        $('#div-email-li').html('{{trans('valid.email_required')}}').show();
        return false;
      }

      if(password==''){
        $("#fieldset-password-li").addClass("has-danger");
        $('#div-password-li').html('{{trans('valid.password_required')}}').show();
        return false;
      }
      $.ajax({
        url: '/login',
        type: 'POST',
        data: {_token: _token, email: email, password: password, remember:remember},
        success: function (data) {
          $('#modal-signin').modal('hide');
          location.reload(true);
        },
        error: function (data) {
          var obj = jQuery.parseJSON(data.responseText);
          if (obj.email) {
            $("#fieldset-email-li").addClass("has-danger");
            $("#fieldset-password-li").addClass("has-danger");
            $('#div-password-li').html('{{trans('global.login_incorrect')}}').show();
          }
        }
      });
    });
  });
</script>

<!-- end modal login -->

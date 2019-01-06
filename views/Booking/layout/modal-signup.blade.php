<!-- popup signup  -->
<div class="modal-transform modal-sigup modal-sig modal fade modal-vertical-middle" id="modal-signup" tabindex="-1"
     role="dialog" aria-labelledby="modal-signup">
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
        <h3 class="title-form mr-0 text-uppercase">{{trans(DS.'global.register')}}</h3>
        <form class="form-sin" id="form-login" method="post" action="{{ url('register') }}">
          
          <fieldset class="form-group" id="fieldset-full-name-lg">
            <input type="text" class="form-control" id="full_name_lg" name="full_name" placeholder="{{trans(DS.'global.full_name')}}">
            <div class="form-control-feedback" id="div-full-name-lg" style="display: none"></div>
          </fieldset>
          <fieldset class="form-group" id="fieldset-email-lg">
            <input type="email" class="form-control" id="email_lg" name="email" placeholder="Email">
            <div class="form-control-feedback" id="div-email-lg" style="display: none"></div>
          </fieldset>
          <fieldset class="form-group" id="fieldset-password-lg">
            <input type="password" class="form-control" id="password_lg" name="password" placeholder="{{trans(DS.'global.password')}}">
            <div class="form-control-feedback" id="div-password-lg" style="display: none"></div>
          </fieldset>
          <fieldset class="form-group">
            <input type="password" class="form-control" id="password_confirmation_lg" name="password_confirmation"
                   placeholder="{{trans(DS.'global.confirm_password')}}">
          </fieldset>
          <div class="form-group">
            <button id="register" type="submit" class="btn-sin btn btn-primary d-block"  >{{trans(DS.'global.register')}}</button>
          </div>
          <div class="form-control-feedback" id="register-success" style="display: none; text-align: center; color: red"></div>
        </form>
      </div>
      <!-- modal-body -->
      <div class="modal-footer d-flex justify-content-center">
        <a href="{{ URL::to('client/google') }}" title=""><img src="/frontend/assets/img/icon/Google+.png" alt=""></a>
        <a href="{{ URL::to('client/facebook') }}" title=""><img src="/frontend/assets/img/icon/Facebook.png"
                                                                 alt=""></a>
      </div>
    </div>
    <p class="text text-center">{{trans(DS.'global.no_account')}}? <a href="" title="" data-toggle="modal" data-target="#modal-signin">{{trans(DS.'global.register_now')}}!</a></p>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function () {
    $("#form-login").on('submit', function (event) {
      $("#register").attr("disabled", true);
      event.preventDefault();

      var _token = $("meta[name='_token']").prop('content');
      var full_name = $('input[id="full_name_lg"]').val();
      var email = $('input[id="email_lg"]').val();
      var password = $('input[id="password_lg"]').val();
      var pass_confirm = $('input[id="password_confirmation_lg"]').val();

      $.ajax({
        url: '/register',
        type: 'POST',
        data: {_token: _token, full_name: full_name, email: email, password: password, password_confirmation: pass_confirm},
        success: function (data) {
          if(data.mess === false)
          {
            var obj = data.errors;
            if (obj.email) {
              $("#fieldset-email-lg").addClass("has-danger");
              $('#div-email-lg').html(obj.email[0]).show();
              $("#register").removeAttr("disabled");
            }
            if (obj.full_name) {
              $("#fieldset-full-name-lg").addClass("has-danger");
              $('#div-full-name-lg').html(obj.full_name[0]).show();
              $("#register").removeAttr("disabled");
            }
            if (obj.password) {
              $("#fieldset-password-lg").addClass("has-danger");
              $('#div-password-lg').html(obj.password[0]).show();
              $("#register").removeAttr("disabled");
            }
            if (obj.check_mail) {
              $('#register-success').html(obj.check_mail).show();
              //$("#register").attr("disabled", true);
            }
          }
          if(data.mess === true){
            $('#modal-signup').modal('hide');
            location.reload(true);
          }
        }
      });
    });
  });
</script>

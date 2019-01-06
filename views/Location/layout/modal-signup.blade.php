<!-- popup signup  -->
<div class="modal-transform modal-sigup modal-sig modal mb-4" id="modal-signup" tabindex="-1"
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
        <h3 class="title-form mr-0 text-uppercase">{{trans('global.register')}}</h3>
        <form class="form-sin" id="form-login" method="post" action="{{ url('register') }}">

          <fieldset class="form-group" id="fieldset-full-name-lg">
            <input type="text" class="form-control" id="full_name_lg" name="full_name" placeholder="{{trans('global.full_name')}}">
            <div class="form-control-feedback" id="div-full-name-lg" style="display: none"></div>
          </fieldset>
          <fieldset class="form-group" id="fieldset-email-lg">
            <input type="text" class="form-control" id="email_lg" name="email" placeholder="Email">
            <div class="form-control-feedback" id="div-email-lg" style="display: none"></div>
          </fieldset>
          <fieldset class="form-group" id="fieldset-password-lg">
            <input type="password" onkeyup="checkPassword(this);" class="form-control" id="password_lg" name="password" placeholder="{{trans('global.password')}}">
            <div class="form-control-feedback" id="password-point" style="display: none"></div>
            <div class="form-control-feedback" id="div-password-lg" style="display: none"></div>
          </fieldset>
          <fieldset class="form-group">
            <input type="password" class="form-control" id="password_confirmation_lg" name="password_confirmation"
                   placeholder="{{trans('global.confirm_password')}}">
          </fieldset>
          <fieldset class="form-group">
            {!! Lang::get('global.policy', [ 'url' => URL::to('page/policy') ]) !!}
          </fieldset>
          <div class="form-group">
            <button id="register" type="submit" class="btn-sin btn btn-primary d-block"  >{{trans('global.register')}}</button>
          </div>
          <div class="form-control-feedback" id="register-success" style="display: none; text-align: center; color: red"></div>
        </form>
      </div>

      <div class="modal-footer d-flex justify-content-center">
        <a href="{{ URL::to('client/google') }}" title=""><img src="/frontend/assets/img/icon/google.svg" alt=""></a>
        <a href="{{ URL::to('client/facebook') }}" title=""><img src="/frontend/assets/img/icon/facebook.svg" alt=""></a>
      </div>

    </div>
    <p class="text text-center">{{trans('global.has_account')}}? <a href="" title="" data-toggle="modal" data-target="#modal-signin">{{trans('global.login_now')}}!</a></p>
    {{--<p class="text text-center">--}}
      {{--<a href="" title="" data-toggle="modal" data-target="#modal-register-invite">--}}
      {{--{{trans('global.make_money')}}!</a>--}}
      {{--&nbsp;&nbsp;&nbsp;--}}
      {{--<!-- <a href="#" title="">{{trans('global.policy_invite')}}!</a> -->--}}
    {{--</p>--}}
  </div>
</div>
<div id="modal-email" class="modal  modal-submit-payment   modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content text-center">
      <div class="modal-logo pt-4 text-center">
        <img src="/frontend/assets/img/logo/logo-large.svg" alt="">
      </div>
      <h4>&nbsp;</h4>
      <p id="text_push"></p>
      <div class="modal-button d-flex justify-content-center">
        <a class="btn btn-secorady" href="#" data-dismiss="modal">{{trans('global.close')}}</a>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  // function showRegisterInvite(){
  //   setTimeout(function(){
  //     $("#modal-signup").modal('hide');
  //     $("#modal-signin").modal('hide');
  //   },10);

  //   setTimeout(function(){
  //     $("#modal-register-invite").modal('show');
  //   },500); 
  // }
  function checkPassword(obj){
    var pass = $(obj).val();
    var strongRegex = new RegExp("^(?=.{10,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).*$", "g");
    var mediumRegex = new RegExp("^(?=.{8,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
    var enoughRegex = new RegExp("(?=.{6,}).*", "g");
    if (false == enoughRegex.test(pass)) {
      $("#fieldset-password-lg").addClass("has-danger");
      $('#div-password-lg').html('<i class="fa fa-remove"></i> {{trans('valid.password_6_char')}}').show();
      // return false;
    }
    else if (strongRegex.test(pass)) {
      $("#fieldset-password-lg").removeClass("has-danger");
      $('#div-password-lg').html('<span class="text-success"><i class="fa fa-check"></i> {{trans('valid.password_strong')}}</span>').show();
    }
    else if (mediumRegex.test(pass)) {
      $("#fieldset-password-lg").removeClass("has-danger");
      $('#div-password-lg').html('<span class="text-info"><i class="fa fa-check"></i> {{trans('valid.password_medium')}}</span>').show();
      // return false;
    }
    else {
      $("#fieldset-password-lg").removeClass("has-danger");
      $('#div-password-lg').html('<span class="text-warning"><i class="fa fa-exclamation-circle"></i> {{trans('valid.password_weak')}}</span>').show();
      // return false;
    }
  }
  $(document).ready(function () {
    $("#form-login").on('submit', function (event) {
      $("#register").attr("disabled", true);
      event.preventDefault();

      var _token = $("meta[name='_token']").prop('content');
      var full_name = $('input[id="full_name_lg"]').val();
      var email = $('input[id="email_lg"]').val();
      var password = $('input[id="password_lg"]').val();
      var pass_confirm = $('input[id="password_confirmation_lg"]').val();
      var enoughRegex = new RegExp("(?=.{6,}).*", "g");
      // if (false == enoughRegex.test(password)) {
      //   $("#fieldset-password-lg").addClass("has-danger");
      //   $('#div-password-lg').html('<i class="fa fa-remove"></i> {{trans('valid.password_6_char')}}').show();
      //   return false;
      // }
      $.ajax({
        url: '/register',
        type: 'POST',
        data: {_token: _token, full_name: full_name, email: email, password: password, password_confirmation: pass_confirm},
        success: function (data) {
          if(typeof data !== 'object'){
            data = JSON.parse(data);
          }
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
              $('#modal-signup').modal('hide');
              $('#modal-email #text_push').html(obj.check_mail).show();
              $("#modal-email").modal();
                $("#fieldset-email-lg").removeClass("has-danger");
                $("#fieldset-full-name-lg").removeClass("has-danger");
                $("#fieldset-password-lg").removeClass("has-danger");
                $('#div-email-lg').hide();
                $('#div-full-name-lg').hide();
                $('#div-password-lg').hide();
                $('input[id="full_name_lg"]').val('');
                $('input[id="email_lg"]').val('');
                $('input[id="password_lg"]').val('');
                $('input[id="password_confirmation_lg"]').val('');
                $("#register").removeAttr("disabled");
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

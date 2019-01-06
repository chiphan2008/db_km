<!-- popup register invite  -->
<div class="modal-transform modal-sigup modal-sig modal  modal-vertical-middle " id="modal-register-invite" tabindex="-1"
     role="dialog" aria-labelledby="modal-register-invite" >
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
        <h3 class="title-form mr-0 text-uppercase">{{trans('global.register_invite')}}</h3>
        <form class="form-sin" id="form-register-invite" method="post" action="{{ url('register') }}">

          <fieldset class="form-group" id="fieldset-full-name-lg-invite">
            <input type="text" class="form-control" id="full_name_lg_invite" name="full_name" placeholder="{{trans('global.full_name')}}">
            <div class="form-control-feedback" id="div-full-name-lg-invite" style="display: none"></div>
          </fieldset>
          <fieldset class="form-group" id="fieldset-email-lg-invite">
            <input type="text" class="form-control" id="email_lg_invite" name="email" placeholder="Email">
            <div class="form-control-feedback" id="div-email-lg-invite" style="display: none"></div>
          </fieldset>
          <fieldset class="form-group" id="fieldset-password-lg-invite">
            <input type="password" class="form-control" id="password_lg_invite" name="password" placeholder="{{trans('global.password')}}">
            <div class="form-control-feedback" id="div-password-lg-invite" style="display: none"></div>
          </fieldset>
          <fieldset class="form-group">
            <input type="password" class="form-control" id="password_confirmation_lg_invite" name="password_confirmation"
                   placeholder="{{trans('global.confirm_password')}}">
          </fieldset>
          <fieldset class="form-group" id="fieldset-phone-lg-invite">
            <input type="text" class="form-control number" id="phone_lg" name="phone" maxlength="14" 
                   placeholder="{{trans('global.phone')}}">
                   <div class="form-control-feedback" id="div-phone-lg-invite" style="display: none"></div>
          </fieldset>
          <fieldset class="form-group" id="fieldset-cmnd-lg-invite">
            <input type="text" class="form-control number" id="cmnd_lg" name="cmnd" maxlength="12" 
                   placeholder="{{trans('global.cmnd')}}">
                   <div class="form-control-feedback" id="div-cmnd-lg-invite" style="display: none"></div>
          </fieldset>
          <div class="form-group">
            <button id="register-invite" type="submit" class="btn-sin btn btn-primary d-block"  >{{trans('global.register')}}</button>
          </div>
          <div class="form-control-feedback" id="register-success" style="display: none; text-align: center; color: red"></div>
        </form>
      </div>
      <!-- modal-body -->

    </div>
    
  </div>
</div>
<div id="modal-email-invite" class="modal  modal-submit-payment   modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment" aria-hidden="true">
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
  
  $(document).ready(function () {
  	$("input.number").on("keypress",function(e){
			return (e.charCode >= 48 && e.charCode <= 57) || e.charCode == 43;
		})

    $("#form-register-invite").on('submit', function (event) {
      $("#register-invite").attr("disabled", true);
      event.preventDefault();

      var _token = $("meta[name='_token']").prop('content');
      var full_name = $('input[id="full_name_lg_invite"]').val();
      var email = $('input[id="email_lg_invite"]').val();
      var password = $('input[id="password_lg_invite"]').val();
      var pass_confirm = $('input[id="password_confirmation_lg_invite"]').val();

      var phone = $("#phone_lg").val();
      var cmnd = $("#cmnd_lg").val();

      $.ajax({
        url: '/registerInvite',
        type: 'POST',
        data: {
					_token: _token,
					full_name: full_name,
					email: email,
					password: password,
					phone : phone,
					cmnd : cmnd,
					password_confirmation: pass_confirm
       },
        success: function (data) {
          if(typeof data !== 'object'){
            data = JSON.parse(data);
          }
          if(data.mess === false)
          {
            var obj = data.errors;
            if (obj.email) {
              $("#fieldset-email-lg-invite").addClass("has-danger");
              $('#div-email-lg-invite').html(obj.email[0]).show();
              $("#register-invite").removeAttr("disabled");
            }
            if (obj.full_name) {
              $("#fieldset-full-name-lg-invite").addClass("has-danger");
              $('#div-full-name-lg-invite').html(obj.full_name[0]).show();
              $("#register-invite").removeAttr("disabled");
            }
            if (obj.password) {
              $("#fieldset-password-lg-invite").addClass("has-danger");
              $('#div-password-lg-invite').html(obj.password[0]).show();
              $("#register-invite").removeAttr("disabled");
            }
            if (obj.phone) {
              $("#fieldset-phone-lg-invite").addClass("has-danger");
              console.log(obj.phone[0]);
              $('#div-phone-lg-invite').html(obj.phone[0]).show();
              $("#register-invite").removeAttr("disabled");
            }
            if (obj.cmnd) {
              $("#fieldset-cmnd-lg-invite").addClass("has-danger");
              $('#div-cmnd-lg-invite').html(obj.cmnd[0]).show();
              $("#register-invite").removeAttr("disabled");
            }
            if (obj.check_mail) {
            	console.log(obj.check_mail);
              $('#modal-register-invite').modal('hide');
              $('#modal-email-invite #text_push').html(obj.check_mail).show();
              $("#modal-email-invite").modal();
              //$("#register-invite").attr("disabled", true);
            }
          }
          if(data.mess === true){
            $('#modal-register-invite').modal('hide');
            location.reload(true);
          }
        }
      });
    });
  });
</script>













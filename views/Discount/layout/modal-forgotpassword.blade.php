<!-- forgot pass -->
<div class="modal-transform modal-forget-pass modal-sig modal fade modal-vertical-middle" id="modal-forget-pass"
     tabindex="-1" role="dialog" aria-labelledby="modal-forget-pass">
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
        <h3 class="title-form mr-0 text-uppercase">{{strtoupper(trans('global.forgot_password'))}}</h3>
        <form class="form-sin" method="post" id="form-forgot" action="{{ url('client-password/email') }}">

          <fieldset class="form-group" id="fieldset-email-fg">
            <input type="text" class="form-control" id="email-fg" name="email" placeholder="Email">
            <div class="form-control-feedback" id="div-email-fg" style="display: none"></div>
          </fieldset>
          <label class="forgot-pass text-center px-5">{{trans('global.send_mail_forgot')}}.</label>
          <div class="form-group">
            <button id="send_mail_forgot"  type="submit" class="btn-sin btn btn-primary d-block">{{trans('global.send')}}</button>
          </div>
        </form>
      </div>
      <!-- modal-body -->
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function () {
    $("#form-forgot").on('submit', function (event) {
      $('#send_mail_forgot').prop('disabled', true);
      event.preventDefault();

      var _token = $("meta[name='_token']").prop('content');
      var email = $('input[id="email-fg"]').val();
if(email==''){
        $("#fieldset-email-fg").addClass("has-danger");
        $('#div-email-fg').html('{{trans('valid.email_required')}}').show();
        $('#send_mail_forgot').prop('disabled', false);
        return false
      }
      $.ajax({
        url: '/client-password/email',
        type: 'POST',
        data: {_token: _token, email: email},
        success: function (data) {
          if(data.errors)
          {
            $("#fieldset-email-fg").addClass("has-danger");
            $("#form-forgot .forgot-pass").css("color","red").text(data.errors);
          }
          else{
            $('input[id="email-fg"]').val('');
            $("#fieldset-email-fg").removeClass("has-danger");
            $('#div-email-fg').hide();
            $("#form-forgot .forgot-pass").css("color","#009926").text('{{trans('Location'.DS.'layout.forgot_password')}}');
          }
        },
        error: function (data) {
          $("#fieldset-email-fg").addClass("has-danger");
          $('#div-email-fg').html('{{trans('valid.email_incorrect')}}').show();
          $('#send_mail_forgot').prop('disabled', false);
        }
      });
    });
  });
</script>
<!-- end forgot pass -->

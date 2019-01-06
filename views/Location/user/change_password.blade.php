<div class="content-edit-profile-manager">
	<h3>{{mb_strtoupper(trans('Location'.DS.'user.change_password'))}}</h3>
	<p class="text-danger" id="text-error"></p>
	<form class="form-edit-profile" onsubmit="return false;">
		<div class="row">
			@if(Auth::guard('web_client')->user()->password)
			<div class="col-md-7">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.old_password')}}</label>
					<input type="password" class="form-control" name="old_password" placeholder="{{trans('Location'.DS.'user.old_password')}}">
				</div>
				<!-- end form-group -->
			</div>
			@endif
			<div class="col-md-7">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.new_password')}}</label>
					<input type="password" class="form-control" onkeyup="checkPasswordChange(this)" name="new_password" id="new_password" placeholder="{{trans('Location'.DS.'user.new_password')}}">
					<div id="div-password-change"></div>
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-md-7">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.renew_password')}}</label>
					<input type="password" class="form-control" name="renew_password" id="renew_password" placeholder="{{trans('Location'.DS.'user.renew_password')}}">
				</div>
			</div>
			<div class="col-md-7">
				<button type="button" onclick="changePassword()" class="btn-submit btn btn-primary">{{trans('Location'.DS.'user.change_password')}}</button>
			</div>
		</div>
	</form>
</div>
<div id="modal-update-success" class="modal modal-vertical-middle fade modal-submit-payment  modal-vertical-middle modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment modal-vertical-middle modal-vertical-middle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content text-center">
      <div class="modal-logo pt-4 text-center">
        <img src="/frontend/assets/img/logo/logo-large.svg" alt="">
      </div>
      <h4>&nbsp;</h4>
      <p class="text_1 text-center" id="message"></p>
      <div class="modal-button d-flex justify-content-center">
        <a class="btn btn-secorady" data-dismiss="modal">{{trans('global.close')}}</a>
      </div>
    </div>
  </div>
</div>
@section('JS')
	@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
		@include('Location.user.crop_image')
	@endif
	<script>
		function checkPasswordChange(obj){
	    var pass = $(obj).val();
	    var strongRegex = new RegExp("^(?=.{10,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).*$", "g");
	    var mediumRegex = new RegExp("^(?=.{8,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
	    var enoughRegex = new RegExp("(?=.{6,}).*", "g");
	    if (false == enoughRegex.test(pass)) {
	      
	      $('#div-password-change').html('<span class="text-danger"><i class="fa fa-remove"></i> {{trans('valid.password_6_char')}}</span>').show();
	      // return false;
	    }
	    else if (strongRegex.test(pass)) {
	      
	      $('#div-password-change').html('<span class="text-success"><i class="fa fa-check"></i> {{trans('valid.password_strong')}}</span>').show();
	    }
	    else if (mediumRegex.test(pass)) {
	      
	      $('#div-password-change').html('<span class="text-info"><i class="fa fa-check"></i> {{trans('valid.password_medium')}}</span>').show();
	      // return false;
	    }
	    else {
	      
	      $('#div-password-change').html('<span class="text-warning"><i class="fa fa-exclamation-circle"></i> {{trans('valid.password_weak')}}</span>').show();
	      // return false;
	    }
	  }
		function changePassword(){
			if($("#new_password").val().length<6){
				$("#message").text('{{trans('Location'.DS.'user.password_enough_length')}}');
				$("#modal-update-success").modal();
				//$("#text-error").removeClass('text-success').addClass('text-danger').text('{{trans('Location'.DS.'user.password_enough_length')}}');
				return false;
			}
			if($("#new_password").val() != $("#renew_password").val()){
				$("#message").text('{{trans('Location'.DS.'user.repassword_wrong')}}');
				$("#modal-update-success").modal();
				//$("#text-error").removeClass('text-success').addClass('text-danger').text('{{trans('Location'.DS.'user.repassword_wrong')}}');
				return false;
			}

			var data = $('.form-edit-profile').serializeArray();
			data.push({
				name: "_token",
				value: $("[name='_token']").prop('content')
			})
			$.ajax({
				url: '/user/{{$user->id}}/change-password',
				type: 'POST',
				data: data,
				success: function(response){
					if(response.error){
						$("#message").text(response.message);
						$("#modal-update-success").modal();
						// $("#text-error").removeClass('text-success').addClass('text-danger').text(response.message);
					}else{
						$("#message").text(response.message);
						$("#modal-update-success").modal();
						// $("#text-error").removeClass('text-danger').addClass('text-success').text(response.message);
						$('.form-edit-profile input').val('');
					}
				}
			})
		}
	</script>
@endsection

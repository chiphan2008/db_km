<div class="content-edit-profile-manager">
	<h3>{{mb_strtoupper(trans('Location'.DS.'user.profile'))}}</h3>
	<p class="text-danger" id="text-error"></p>
	<form class="form-edit-profile" onsubmit="return false;">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.full_name')}} <span class="text-danger">*</span></label>
					<input type="text" value="{{$user->full_name?$user->full_name:''}}" name="full_name" class="form-control" placeholder="{{trans('Location'.DS.'user.full_name')}}">
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.email')}} <span class="text-danger">*</span></label>
					<input type="text" value="{{$user->email?$user->email:''}}" name="email" class="form-control" placeholder="{{trans('Location'.DS.'user.email')}}" readonly="true">
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.birthday')}} <span class="text-danger">*</span></label>
					<br/>
					<div class="form-inline"  id='birthday'>
					</div>
          <!-- <input value="" name="birthday" class="form-control" placeholder="dd-mm-yyyy"> -->
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.phone')}} <span class="text-danger">*</span></label>
					<input type="tel" value="{{$user->phone?$user->phone:''}}" minlength="10" maxlength="11" name="phone_user" class="form-control" placeholder="{{trans('Location'.DS.'user.phone')}}">
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.address')}}</label>
					<input type="text" value="{{$user->address?$user->address:''}}" name="address" class="form-control" placeholder="{{trans('Location'.DS.'user.address')}}">
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.description_user')}}</label>
					<textarea class="form-control" id="exampleTextarea" rows="5"  name="description" placeholder="{{trans('Location'.DS.'user.description_user')}}">{{$user->description?$user->description:''}}</textarea>
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-md-12 text-center">
				<button type="button" onclick="updateProfile()" class="btn-submit btn btn-primary">{{trans('global.update')}}</button>
				<!-- end form-group -->
			</div>
			<!-- <div class="col-md-12">
				<div class="notification-form">
					 <i class="icon-check-grey"></i> Auto save
				</div>
			</div> -->
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
        <a class="btn btn-secorady" data-dismiss="modal" id="btn-close">{{trans('global.close')}}</a>
      </div>
    </div>
  </div>
</div>
@section('CSS')
<link href="{{asset('template/js/datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">

@endsection

@section('JS')
@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
	@include('Location.user.crop_image')
@endif
</script>
<!-- <script src="{{asset('template/vendors/moment/min/moment-with-locales.min.js')}}"></script> -->`
<script src="/frontend/assets/js/jquery-birthday-picker.min.js"></script>
<!-- <script src="/frontend/assets/js/bootstrap-datetimepicker.min.js"></script> -->
<script type="text/javascript">
$(function(){
	$("input[name='phone_user']").on("keypress",function(e){
		return (e.charCode >= 48 && e.charCode <= 57) || e.charCode == 43;
	})
})
function updateProfile(){
	var data = $('.form-edit-profile').serializeArray();
	var phone = $("input[name='phone_user']").val();
	var name = $("input[name='full_name']").val();
	var birthday = $("input[name='birthday_birthDay']").val();
	if(name.length==0){
		//$("#text-error").removeClass('text-success').addClass('text-danger').text('{{trans('Location'.DS.'user.name_require')}}');
		$("#message").text('{{trans('Location'.DS.'user.name_require')}}');
				$("#modal-update-success").modal();
		return false;
	}
	if(birthday.length==0){
		//$("#text-error").removeClass('text-success').addClass('text-danger').text('{{trans('Location'.DS.'user.birthday_require')}}');
		$("#message").text('{{trans('Location'.DS.'user.birthday_require')}}');
				$("#modal-update-success").modal();
		return false;
	}
	if(phone.length > 0){
		if(!((phone.charAt(0)==='0') || (phone.charAt(0)==='+')) ){
			//$("#text-error").removeClass('text-success').addClass('text-danger').text('{{trans('Location'.DS.'user.phone_wrong')}}');
			$("#message").text('{{trans('Location'.DS.'user.phone_wrong')}}');
				$("#modal-update-success").modal();
			return false;
		}
	}else{
		//$("#text-error").removeClass('text-success').addClass('text-danger').text('{{trans('Location'.DS.'user.phone_require')}}');
		$("#message").text('{{trans('Location'.DS.'user.phone_require')}}');
		$("#modal-update-success").modal();
		return false;
	}

	data.push({
		name: "_token",
		value: $("[name='_token']").prop('content')
	})
	$.ajax({
		url: '/user/{{$user->id}}',
		type: 'POST',
		data: data,
		success: function(response){
			if(response.error){
				$("#message").text(response.message);
				$("#modal-update-success").modal();
				// $("#text-error").removeClass('text-success').addClass('text-danger').text(response.message);
			}else{
				$("#message").text(response.message);
				$("#btn-close").removeAttr('data-dismiss');
				$("#btn-close").on("click",function(){
					window.scrollTo(0,0);
					setTimeout(function(){
						window.location.reload();
					},100);
				})
				$("#modal-update-success").modal();
			}
		}
	})

}
$(function(){
	$("#birthday").birthdayPicker({
		maxAge : 120,
		minAge : 0,
		name: 'birthday',
		"dateFormat" : "littleEndian",
		"monthFormat" : "number",
		"placeholder" : false,
		"defaultDate" : '{{$user->birthday?$user->birthday:''}}',
		"sizeClass"	: "form-control span3 col-xs-3"
	})
})

</script>
@endsection
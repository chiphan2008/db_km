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
					<input type="tel" value="{{$user->phone?$user->phone:''}}" minlength="10" maxlength="15" name="phone_user" class="form-control" placeholder="{{trans('Location'.DS.'user.phone')}}">
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
@section('CSS')
<link href="{{asset('template/js/datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
<style type="text/css" media="screen">
	.input-group .form-control{
		z-index: 0 !important;
	}
	.birthdayPicker{
		width: 100%;
	}
	.birthdayPicker select:nth-child(n+2){
		margin-left: 15px;
	}
	.span3{
		display:inline-block;
		width: 27%;
	}
</style>
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
	$("input[name='phone']").on("keypress",function(e){
		return (e.charCode >= 48 && e.charCode <= 57) || e.charCode == 43;
	})
})
function updateProfile(){
	var data = $('.form-edit-profile').serializeArray();
	var phone = $("input[name='phone_user']").val();
	var name = $("input[name='full_name']").val();
	var birthday = $("input[name='birthday_birthDay']").val();
	if(name.length==0){
		$("#text-error").removeClass('text-success').addClass('text-danger').text('{{trans('Location'.DS.'user.name_require')}}');
		return false;
	}
	if(birthday.length==0){
		$("#text-error").removeClass('text-success').addClass('text-danger').text('{{trans('Location'.DS.'user.birthday_require')}}');
		return false;
	}
	if(phone.length > 0){
		if(!((phone.charAt(0)==='0') || (phone.charAt(0)==='+')) ){
			$("#text-error").removeClass('text-success').addClass('text-danger').text('{{trans('Location'.DS.'user.phone_wrong')}}');
			return false;
		}
	}else{
		$("#text-error").removeClass('text-success').addClass('text-danger').text('{{trans('Location'.DS.'user.phone_require')}}');
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
				$("#text-error").removeClass('text-success').addClass('text-danger').text(response.message);
			}else{
				$("#text-error").removeClass('text-danger').addClass('text-success').text(response.message);

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
		"placeholder" : true,
		"defaultDate" : '{{$user->birthday?$user->birthday:''}}',
		"sizeClass"	: "form-control span3 col-xs-3"
	})
})

</script>
@endsection
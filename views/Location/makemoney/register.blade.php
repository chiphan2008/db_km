<style type="text/css" media="screen">
	.step-2{
		display: none;
	}
	.image_url{
		margin-top: 20px;
		margin-bottom: 20px;
	}
</style>

<div class="content-edit-profile-manager">
	<div class="process-create-content w-100">
		<h3>{{mb_strtoupper(trans('Location'.DS.'makemoney.register'))}}</h3>
		
		<form action="" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
			{!! csrf_field() !!}

				<div class="step-1">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="" for="content">{{trans('global.country')}}</label>       
								<select class="form-control" name="country" id="country_register" onchange="loadCityRegister(this)">
									@foreach($countries as $country)
									<option value="{{$country->id}}">{{$country->name}}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label class="" for="content">{{trans('global.city')}}</label>       
								<select class="form-control" name="city" id="city_register" onchange="loadDistrictRegister(this)">
								</select>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label class="" for="content">{{trans('global.district')}}</label>       
								<select class="form-control" name="district" id="district_register" onchange="loadDaily()">
								</select>
							</div>
						</div>
					</div>
				</div>
				
				<div class="step-2">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="" for="content">{{trans('Location'.DS.'makemoney.daily')}}: </label>       
								<b class="lead" id="daily_name">{{old('daily_name')?old('daily_name'):''}}</b>
								&nbsp;&nbsp;&nbsp;
								<button class="btn btn-secondary" type="button" onclick="step1()">{{trans('Location'.DS.'layout.choose_category_again')}} <i class="fa fa-refresh"></i></button>
							</div>
						</div>

						<div class="col-md-6">
							<input type="hidden" id="daily" value="{{old('daily_id')?old('daily_id'):''}}" name="daily_id">
							<input type="hidden" id="daily_name_input" value="{{old('daily_name')?old('daily_name'):''}}" name="daily_name">
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="" for="content">{{trans('Location'.DS.'makemoney.full_name')}} <span class="text-danger">*</span></label>       
								<input maxlength="128" type="text" class="form-control" name="full_name" value="{{old('full_name')?old('full_name'):$client->full_name}}">
								@if ($errors->has('full_name'))
									<span style="color: red">{{ $errors->first('full_name') }}</span>
								@endif
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
								<label>{{trans('Location'.DS.'user.birthday')}} <span class="text-danger">*</span></label>
								<br/>
								<div class="form-inline"  id='birthday'>
								</div>
								<!-- <input value="" name="birthday" class="form-control" placeholder="dd-mm-yyyy"> -->
								@if ($errors->has('birthday_birthDay'))
									<span style="color: red">{{ $errors->first('birthday_birthDay') }}</span>
								@endif
							</div>
							<!-- end form-group -->
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<label class="" for="content">{{trans('Location'.DS.'makemoney.address')}} <span class="text-danger">*</span></label>       
								<input maxlength="128" type="text" class="form-control" name="address" value="{{old('address')?old('address'):$client->address}}">
								@if ($errors->has('address'))
									<span style="color: red">{{ $errors->first('address') }}</span>
								@endif
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="" for="content">{{trans('Location'.DS.'makemoney.phone')}} <span class="text-danger">*</span></label>       
								<input maxlength="20" type="text" class="form-control" name="phone" value="{{old('phone')?old('phone'):$client->phone}}">
								@if ($errors->has('phone'))
									<span style="color: red">{{ $errors->first('phone') }}</span>
								@endif
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="" for="content">{{trans('Location'.DS.'makemoney.cmnd')}} <span class="text-danger">*</span></label>       
								<input maxlength="20" type="text" class="form-control" name="cmnd" value="{{old('cmnd')?old('cmnd'):$client->cmnd}}">
								@if ($errors->has('cmnd'))
									<span style="color: red">{{ $errors->first('cmnd') }}</span>
								@endif
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="" for="content">{{trans('Location'.DS.'makemoney.cmnd_image_front')}} <span class="text-danger">*</span></label>  
								{!! old('cmnd_image_front') !!}     
								<input type="file" class="form-control" name="cmnd_image_front" value="{{old('cmnd_image_front')?old('cmnd_image_front'):$client->cmnd_image_front}}" onchange="readURL(this)">
								<img src="" alt="" id="cmnd_image_front_show" height="200" class="image_url">
								@if ($errors->has('cmnd_image_front'))
									<span style="color: red">{{ $errors->first('cmnd_image_front') }}</span>
								@endif
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label class="" for="content">{{trans('Location'.DS.'makemoney.cmnd_image_back')}} <span class="text-danger">*</span></label>       
								<input type="file" class="form-control" name="cmnd_image_back" value="{{old('cmnd_image_back')?old('cmnd_image_back'):$client->cmnd_image_back}}" onchange="readURL(this)">
								<img src="" alt="" id="cmnd_image_back_show" height="200" class="image_url">
								@if ($errors->has('cmnd_image_back'))
									<span style="color: red">{{ $errors->first('cmnd_image_back') }}</span>
								@endif
							</div>
						</div>

						<div class="col-md-12 text-center">
							<button class="btn btn-primary" type="submit">
								{{mb_strtoupper(trans('Location'.DS.'makemoney.register'))}}
							</button>
						</div>
					</div>
				</div>

		</form>
		<div class="table-responsive">
			<table class="table table-striped w-100 mt-3 table-bordered step-1" id="table_register">
				<thead>
					<tr>
						<!-- <td class="align-middle">ID</td> -->
						<th>{{trans('global.full_name')}}</th>
						<th>{{trans('global.email')}}</th>
						<th>{{trans('global.phone')}}</th>
						<th class="text-center">{{trans('global.avatar')}}</th>
						<th width="220">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>
	</div>
</div>
@section('JS')
<script src="/frontend/assets/js/jquery-birthday-picker.min.js"></script>
<script>
	$(function(){
		@if(!$errors->isEmpty())
			step2();
		@endif
		
		$("#birthday").birthdayPicker({
			maxAge : 120,
			minAge : 0,
			name: 'birthday',
			"dateFormat" : "littleEndian",
			"monthFormat" : "number",
			"placeholder" : false,
			"defaultDate" : '{{old('birthday_birthDay')?old('birthday_birthDay'):$client->birthday}}',
			"sizeClass"	: "form-control span3 col-xs-3"
		})
		setTimeout(function(){
			$("#country_register").trigger("change");
		},1500)
		
	});
	function loadCityRegister(obj){
		var country = $(obj).val();
		$.ajax({
			url : '/search/loadCity',
			type: 'POST',
			data: {
				_token: _token,
				country: country
			},
			success: function(response){
				$("#city_register").html(response);
				$("#city_register option:first").attr("selected",true);
				$("#city_register").trigger("change");
			}
		})
	}

	function loadDistrictRegister(obj){
		var city = $(obj).val();
		$.ajax({
			url : '/search/loadDistrict',
			type: 'POST',
			data: {
				_token: _token,
				city: city
			},
			success: function(response){
				$("#district_register").html(response);
				$("#district_register option:first").attr("selected",true);
				$("#district_register").trigger("change");
			}
		})
	}

	function loadDaily(){
		var country = $("#country_register").val()!='all'?$("#country_register").val():'';
		var city = $("#city_register").val()!='all'?$("#city_register").val():'';
		var district = $("#district_register").val()!='all'?$("#district_register").val():'';
		var arr_data = {
			'city' 			: city,
			'district' 	: district,
			'country' 	: country,
			'limit' 		: 40
		};
		$.ajax({
			url : '/apis/static/search-daily',
			type: 'POST',
			data: arr_data,
			success: function(response){
				if(response.code==200){
					var data = response.data;
					renderTable(data);
				}
			}
		})
	}

	function renderTable(data){
		var html = '';
		data.forEach(function(value,key){
			html += '<tr>';
			// html += '<td class="align-middle">'+value.id+'</td>';
			html += '<td class="align-middle">'+value.full_name+'</td>';
			html += '<td class="align-middle">'+value.email+'</td>';
			value.phone?html += '<td class="text-center align-middle">'+value.phone+'</td>':html += '<td class="text-center align-middle">&nbsp;&nbsp;</td>';
			html += '<td class="text-center align-middle"><img src="'+value.avatar+'" alt="" height="50"></td>';
			html += '<td class="align-middle">	<button class="btn btn-primary" onclick="registerCTV('+value.id+',\''+value.full_name+'\')">Đăng ký</button>'
			html += '&nbsp;&nbsp;&nbsp;<button class="btn btn-secondary"><i class="icon-chat"></i>Liên hệ</button>'
			html += '</td>';
			html += '</tr>';
		});
		$("#table_register tbody").html(html);
	}

	function registerCTV(id,name){
		$("#daily").val(id);
		$("#daily_name_input").val(name);
		$("#daily_name").text(name);
		step2();
	}

	function step1(){
		$(".step-1").show();
		$(".step-2").hide();
	}

	function step2(){
		$(".step-1").hide();
		$(".step-2").show();
	}

	function readURL(obj){
		var input = $(obj).get(0);
		var reader = new FileReader();

    reader.onload = function(e) {
      $(obj).next().attr('src', e.target.result);
      $(obj).next().next().hide();
    }

    reader.readAsDataURL(input.files[0]);
	}
</script>
@endsection
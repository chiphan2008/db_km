@extends('Admin..layout_admin.master_admin')

@section('content')
	<div class="row">
		<div class="col-md-12">
			@if(session('status'))
        <div class="alert alert-success alert-dismissible fade in" style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          {!! session('status') !!}
        </div>
      @endif
		</div>
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h2> {{trans('Admin'.DS.'client.area')}}</h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<form class="form-horizontal form-label-left" method="post" action="" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'content.country')}}
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="country" id="country" class="form-control" onchange="get_location(this, 'city')">
									@foreach($countries as $country)
									<option value="{{$country->id}}" {{$country->id==$old_country?"selected":''}}>{{$country->name}}</option>
									@endforeach
								</select>
							</div>
							@if ($errors->has('country'))
              <span style="color: red">{{ $errors->first('country') }}</span>
              @endif
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'content.city')}}
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="city" id="city" class="form-control" onchange="get_location(this, 'district')" multiple="">
								</select>
							</div>
							@if ($errors->has('city'))
              <span style="color: red">{{ $errors->first('city') }}</span>
              @endif
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'content.district')}}
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="district[]" id="district" class="form-control" multiple="">
									<option value="">-- {{trans('global.district')}} --</option>
								</select>
							</div>
							@if ($errors->has('district'))
              <span style="color: red">{{ $errors->first('district') }}</span>
              @endif
						</div>
						<div class="form-group col-md-12">
							<div class="col-md-4 col-sm-4 col-xs-12 col-md-offset-4">
								<button type="submit" class="btn btn-success">{{trans('Admin'.DS.'client.save')}}</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
@endsection

@section('JS')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>

<script>
	var old_city = {!! json_encode($old_city) !!};
	var old_district = {!! json_encode($old_district) !!};
	$(function(){
		$("#country").trigger("change");
		$("#city").select2();
		$("#district").select2();
	});
	function get_location(obj, type) {
    var CSRF_TOKEN = $('input[name="_token"]').val();
    var value = $(obj).val();
    $.ajax({
    	type: "POST",
      data: {value: value, type: type, _token: CSRF_TOKEN},
      url:  '/admin/content/ajaxLocation',
      success: function (data) {
      	// alert(type);
        $("#" + type).html(data);
				if(type=='city'){
					$("#city option").each(function(key,elem){
						if(key==0){
							$(elem).remove();
						}
						var value_option = parseInt($(elem).attr('value'));
						console.log($(elem).attr('value'),old_city.indexOf($(elem).attr('value')));
						
						if(old_city.indexOf(value_option) > -1){
							$(elem).attr('selected',true);
						}
					});
					$("#city").trigger("change");
				}
				if (type == 'district'){
					$("#district option").each(function(key, elem){
						if(key==0){
							$(elem).remove();
						}
						var value_option = parseInt($(elem).attr('value'));
						if (old_district.indexOf(value_option) > -1){
							$(elem).attr('selected', true);
						}
					})
					$("#district").trigger("change");
				}
			}
    })
  }
</script>
@endsection
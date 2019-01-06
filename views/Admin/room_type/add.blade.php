@extends('Admin..layout_admin.master_admin')

@section('content')

	<form class="form-horizontal form-label-left" method="post" action="{{route('add_room_type',['hotel_id'=>$hotel_id])}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		@if ($errors->has('error'))
			<span style="color: red">{{ $errors->first('error') }}</span>
		@endif
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'room_type.name')}} <span
					class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="name" name="name" class="form-control col-md-7 col-xs-12" value="{{ old('name') }}" >
			</div>
			@if ($errors->has('name'))
				<span style="color: red">{{ $errors->first('name') }}</span>
			@endif
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="customer">{{trans('Admin'.DS.'room_type.customer')}} <span
					class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="number" max="99" min="1" maxlength="2" id="customer" name="customer" class="form-control col-md-7 col-xs-12" value="{{ old('customer')?old('customer'):1 }}">
			</div>
			@if ($errors->has('customer'))
				<span style="color: red">{{ $errors->first('customer') }}</span>
			@endif
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="weight">{{trans('Admin'.DS.'room_type.weight')}}
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="number" min="0" id="weight" name="weight" class="form-control col-md-7 col-xs-12" value="{{ old('weight')?old('weight'):0 }}">
			</div>
			@if ($errors->has('weight'))
				<span style="color: red">{{ $errors->first('weight') }}</span>
			@endif
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">{{trans('Admin'.DS.'room_type.description')}}</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<textarea type="text" id="description" name="description" class="form-control col-md-7 col-xs-12">{{ old('description') }}</textarea>
			</div>
			@if ($errors->has('description'))
				<span style="color: red">{{ $errors->first('description') }}</span>
			@endif
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				{{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ old('active') == '1' ? 'checked' : '' }} name="active"> {{trans('global.active')}}
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="image">{{trans('Admin'.DS.'room_type.image')}}
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="file" id="image" name="image[]" accept="image/*"
							 multiple
							 onchange="readURL(this)"/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
			<div class="col-md-6 col-sm-6 col-xs-12" id="list_image">
			</div>
		</div>
		@if($options && count($options)>0)
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="option_no_price">{{trans('Admin'.DS.'room_type.option_no_price')}}</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select class="form-control" id="option_no_price" name="option_no_price[]" multiple>

					@foreach($options as $option)
					<option value="{{$option->id}}">{{$option->name}}</option>
					@endforeach

				</select>
			</div>
			@if ($errors->has('option_no_price'))
				<span style="color: red">{{ $errors->first('option_no_price') }}</span>
			@endif
		</div>
		@endif

		@if($options_extra && count($options_extra)>0)
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="option_extra">{{trans('Admin'.DS.'room_type.option_extra')}}</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select class="form-control" id="option_extra" name="option_extra[]" multiple>

					@foreach($options_extra as $option)
					<option value="{{$option->id}}">{{$option->name}} - {{money_number($option->price_extra)}}</option>
					@endforeach

				</select>
			</div>
			@if ($errors->has('option_extra'))
				<span style="color: red">{{ $errors->first('option_extra') }}</span>
			@endif
		</div>
		@endif

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="price">{{trans('Admin'.DS.'room_type.price')}} <span
					class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="number" min="0" id="price" name="price" class="form-control col-md-7 col-xs-12" value="{{ old('price')?old('price'):0 }}">
			</div>
			@if ($errors->has('price'))
				<span style="color: red">{{ $errors->first('price') }}</span>
			@endif
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="price_km">{{trans('Admin'.DS.'room_type.price_km')}} <span
					class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="number" min="0" id="price_km" name="price_km" class="form-control col-md-7 col-xs-12" value="{{ old('price_km')?old('price_km'):0 }}">
			</div>
			@if ($errors->has('price_km'))
				<span style="color: red">{{ $errors->first('price_km') }}</span>
			@endif
		</div>

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				{{trans('Admin'.DS.'room_type.inactive_no_cancel')}} <input type="checkbox" class="js-switch" {{ old('active_no_cancel') == '1' ? 'checked' : '' }} name="active_no_cancel"> {{trans('Admin'.DS.'room_type.active_no_cancel')}}
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="cancel">{{trans('Admin'.DS.'room_type.cancel')}}
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="checkbox" onclick="showCancel()" id="cancel"   name="cancel" {{ old('cancel')?'checked':'' }}>
			</div>
			@if ($errors->has('cancel'))
				<span style="color: red">{{ $errors->first('cancel') }}</span>
			@endif
		</div>
		<div class="form-group cancel">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="policy_cancel">{{trans('Admin'.DS.'room_type.cancel_policy')}}</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<textarea type="text" id="policy_cancel" name="policy_cancel" rows="5" class="form-control col-md-7 col-xs-12">{{ old('policy_cancel') }}</textarea>
			</div>
			@if ($errors->has('policy_cancel'))
				<span style="color: red">{{ $errors->first('policy_cancel') }}</span>
			@endif
		</div>

		<div class="form-group cancel">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="price_cancel">{{trans('Admin'.DS.'room_type.price_cancel')}} <span
					class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="number" min="0" id="price_cancel" name="price_cancel" class="form-control col-md-7 col-xs-12" value="{{ old('price_cancel')?old('price_cancel'):0 }}">
			</div>
			@if ($errors->has('price_cancel'))
				<span style="color: red">{{ $errors->first('price_cancel') }}</span>
			@endif
		</div>

		<div class="form-group cancel">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="price_cancel_km">{{trans('Admin'.DS.'room_type.price_cancel_km')}} <span
					class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="number" min="0" id="price_cancel_km" name="price_cancel_km" class="form-control col-md-7 col-xs-12" value="{{ old('price_cancel_km')?old('price_cancel_km'):0 }}">
			</div>
			@if ($errors->has('price_cancel_km'))
				<span style="color: red">{{ $errors->first('price_cancel_km') }}</span>
			@endif
		</div>

		<div class="form-group cancel">
			<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				{{trans('Admin'.DS.'room_type.inactive_cancel')}} <input type="checkbox" class="js-switch" {{ old('active_cancel') == '1' ? 'checked' : '' }} name="active_cancel"> {{trans('Admin'.DS.'room_type.active_cancel')}}
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
				<button type="submit" class="btn btn-success">{{trans('Admin'.DS.'room_type.add_room_type')}}</button>
			</div>
		</div>
	</form>
@endsection

@section('JS')
<style>
	.cancel{
		display:none;
	}
</style>
<script type="text/javascript">
	$(function(){

		$("input[type=number]").on("keypress",function(e){
			return (e.charCode >= 48 && e.charCode <= 57) || e.charCode == 13;
		})


		$("#customer").on("keyup",function(e){
			var max = parseInt($(this).attr("max"));
			var min = parseInt($(this).attr("min"));
			if($(this).val()>max){
				$(this).val(max);
			}
			if($(this).val()<min){
				$(this).val(min);
			}
		});

		$('#option_no_price').selectpicker();
		$('#option_extra').selectpicker();

		//$('input[type=checkbox].flat').iCheck({
        //checkboxClass: 'icheckbox_flat-green',
        //radioClass: 'iradio_flat-green'
    //});

		$("#name").on("keyup",function(){
			var name = $(this).val();
			$("#machine_name").val(str_machine(name));
			$("#alias").val(str_slug(name))
		})

		// $("#image").trigger("change")

	})

	function readURL(input) {

		for (var i = 0; i < input.files.length; i++) {
			if (input.files[i]) {
				var reader = new FileReader();

				reader.onload = function (e) {
					var img = $('<img style="height: 90px; width: 90px; border: 1px solid #000; margin: 2px">');
					img.attr('src', e.target.result);
					img.appendTo('#' + 'list_image');
				};
				reader.readAsDataURL(input.files[i]);
			}
		}
	}

	function showCancel(){
		$('.cancel').toggle('fast');
	}
</script>
@endsection

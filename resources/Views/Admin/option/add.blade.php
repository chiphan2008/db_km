@extends('Admin..layout_admin.master_admin')

@section('content')

	<form class="form-horizontal form-label-left" method="post" action="{{route('add_option',['hotel_id'=>$hotel_id])}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		@if ($errors->has('error'))
			<span style="color: red">{{ $errors->first('error') }}</span>
		@endif
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'option.name')}} <span
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
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="weight">{{trans('Admin'.DS.'option.weight')}}
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="number" min="0" step="1" id="weight" name="weight" class="order form-control col-md-7 col-xs-12" value="{{ old('weight')?old('weight'):0 }}" >
      </div>
      @if ($errors->has('weight'))
        <span style="color: red">{{ $errors->first('weight') }}</span>
      @endif
    </div>

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				{{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ old('active') == '1' ? 'checked' : '' }} name="active"> {{trans('global.active')}}
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="extra">{{trans('Admin'.DS.'option.extra')}}
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="checkbox" onclick="showCancel()" id="extra"    name="extra" {{ old('extra')?'checked':'' }}>
			</div>
			@if ($errors->has('extra'))
				<span style="color: red">{{ $errors->first('extra') }}</span>
			@endif
		</div>


		<div class="form-group cancel">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="price_extra">{{trans('Admin'.DS.'option.price_extra')}} <span
					class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="number" min="0" id="price_extra" name="price_extra" class="form-control col-md-7 col-xs-12" value="{{ old('price_extra')?old('price_extra'):0 }}">
			</div>
			@if ($errors->has('price_extra'))
				<span style="color: red">{{ $errors->first('price_extra') }}</span>
			@endif
		</div>

		<div class="form-group">
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
				<button type="submit" class="btn btn-success">{{trans('Admin'.DS.'option.add_option')}}</button>
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

@extends('Admin..layout_admin.master_admin')

@section('content')

	<form class="form-horizontal form-label-left" method="post" action="{{route('add_hotel_from_content')}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		@if ($errors->has('error'))
			<span style="color: red">{{ $errors->first('error') }}</span>
		@endif
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'hotel.search')}}
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="name" name="name" class="form-control col-md-7 col-xs-12" value="{{ old('name') }}" placeholder="{{trans('Admin'.DS.'hotel.search')}}">
			</div>
			@if ($errors->has('name'))
				<span style="color: red">{{ $errors->first('name') }}</span>
			@endif
		</div>

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'hotel.hotel')}}
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="content" name="content" class="form-control col-md-7 col-xs-12" value="{{ old('content') }}" readonly="">
				<input type="hidden" id="content_id" name="content_id" class="form-control col-md-7 col-xs-12" value="{{ old('content_id') }}">
			</div>
			@if ($errors->has('name'))
				<span style="color: red">{{ $errors->first('name') }}</span>
			@endif
		</div>
		
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'hotel.type')}}</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select class="form-control" name="type[]" id="type" multiple>
					@foreach($types as $type)
					<option value="{{$type->id}}">{{$type->name}}</option>
					@endforeach	
				</select>
			</div>
			@if ($errors->has('type'))
				<span style="color: red">{{ $errors->first('type') }}</span>
			@endif
		</div>


		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				{{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ old('active') == '1' ? 'checked' : '' }} name="active"> {{trans('global.active')}}
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
				<button type="submit" class="btn btn-success">{{trans('Admin'.DS.'hotel.add_hotel')}}</button>
			</div>
		</div>
	</form>
@endsection

@section('JS')
<script type="text/javascript">
	$(function(){
		// $("#name").on("keyup",function(){
		//   var name = $(this).val();
		//   $("#machine_name").val(str_machine(name));
		//   $("#alias").val(str_slug(name))
		// })

		$( "#name" ).devbridgeAutocomplete({
			serviceUrl: "{{route('search_hotel')}}",
			"type": "GET",
			"dataType":"json",
			"minChars":3,
			onSelect: function (suggestion) {
					$('#content').val(suggestion.value)
					$('#content_id').val(suggestion.data)
			}
		});

		$('#type').selectpicker({
				// liveSearch: true,
			});
	});
</script>
@endsection
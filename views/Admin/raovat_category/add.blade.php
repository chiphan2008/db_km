@extends('Admin..layout_admin.master_admin')

@section('content')

	<form class="form-horizontal form-label-left" method="post" action="{{route('add_raovat_category')}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		@if ($errors->has('error'))
			<span style="color: red">{{ $errors->first('error') }}</span>
		@endif
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'raovat_category.name')}} <span
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
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="machine_name">{{trans('Admin'.DS.'raovat_category.machine_name')}} <span
					class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="machine_name" name="machine_name" class="form-control col-md-7 col-xs-12" value="{{ old('machine_name') }}" >
			</div>
			@if ($errors->has('machine_name'))
				<span style="color: red">{{ $errors->first('machine_name') }}</span>
			@endif
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="alias">{{trans('Admin'.DS.'raovat_category.alias')}} <span
					class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="alias" name="alias" class="form-control col-md-7 col-xs-12" value="{{ old('alias') }}">
			</div>
			@if ($errors->has('alias'))
				<span style="color: red">{{ $errors->first('alias') }}</span>
			@endif
		</div>

		<div class="form-group">
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
				<button type="submit" class="btn btn-success">{{trans('Admin'.DS.'raovat_category.add_raovat_category')}}</button>
			</div>
		</div>
	</form>
@endsection

@section('JS')
<script type="text/javascript">
	$(function(){


		$("#name").on("keyup",function(){
			var name = $(this).val();
			$("#machine_name").val(str_machine(name));
			$("#alias").val(str_slug(name))
		})

	})
</script>
@endsection

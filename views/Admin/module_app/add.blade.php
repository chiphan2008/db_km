@extends('Admin..layout_admin.master_admin')

@section('content')

	<form class="form-horizontal form-label-left" method="post" action="{{route('add_module_app')}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		@if ($errors->has('error'))
			<span style="color: red">{{ $errors->first('error') }}</span>
		@endif
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'module_app.name')}} <span
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
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="machine_name">{{trans('Admin'.DS.'module_app.machine_name')}} <span
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
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="alias">{{trans('Admin'.DS.'module_app.alias')}} <span
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
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="parent">{{trans('Admin'.DS.'module_app.parent')}} </label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select class="form-control" name="parent" id="parent">
				 <option value="0" {{ old('parent') == '0' ? 'selected' : '' }}>-- {{trans('Admin'.DS.'module_app.no_parent')}} --</option>
				 @if (isset($list_module))
						@foreach ($list_module as $module)
							<option value="{{$module->id}}" {{ old('parent') == $module->id ? 'selected' : '' }}>{{$module->name}}</option>
						@endforeach
					@endif
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="language">{{trans('global.language')}} </label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select class="form-control" name="language" id="language">
				 <option value="vn" {{ old('language') == 'vn' ? 'selected' : '' }}>Tiếng Việt</option>
				 <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">{{trans('Admin'.DS.'module_app.description')}}</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<textarea type="text" id="description" name="description" class="form-control col-md-7 col-xs-12">{{ old('description') }}</textarea>
			</div>
			@if ($errors->has('description'))
				<span style="color: red">{{ $errors->first('description') }}</span>
			@endif
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'module_app.image')}}</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="imgupload panel panel-default">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left">{{trans('Admin'.DS.'module_app.upload_image')}}</h3>
						</div>
						<div class="file-tab panel-body">
							<div>
								<a type="button" class="btn btn-default btn-file">
								<span>{{trans('Admin'.DS.'module_app.browse')}}</span>
								<input type="file" name="image" id="image">
								</a>
								<button type="button" class="btn btn-default">{{trans('Admin'.DS.'module_app.remove')}}</button>
							</div>
						</div>
					</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'module_app.background')}}</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="imgupload panel panel-default">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left">{{trans('Admin'.DS.'module_app.upload_background')}}</h3>
						</div>
						<div class="file-tab panel-body">
							<div>
								<a type="button" class="btn btn-default btn-file">
								<span>{{trans('Admin'.DS.'module_app.browse')}}</span>
								<input type="file" name="background" id="background">
								</a>
								<button type="button" class="btn btn-default">{{trans('Admin'.DS.'module_app.remove')}}</button>
							</div>
						</div>
					</div>
			</div>
		</div>

		<!-- <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'module_app.marker')}}</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="imgupload panel panel-default">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left">{{trans('Admin'.DS.'module_app.upload_marker')}}</h3>
						</div>
						<div class="file-tab panel-body">
							<div>
								<a type="button" class="btn btn-default btn-file">
								<span>{{trans('Admin'.DS.'module_app.browse')}}</span>
								<input type="file" name="marker" id="marker">
								</a>
								<button type="button" class="btn btn-default">{{trans('Admin'.DS.'module_app.remove')}}</button>
							</div>
						</div>
					</div>
			</div>
		</div> -->
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="checkbox"  {{ old('noibat') == '1' ? 'checked' : '' }} name="noibat"> {{trans('Admin'.DS.'module_app.noibat')}} 
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				{{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ old('active') == '1' ? 'checked' : '' }} name="active"> {{trans('global.active')}}
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
				<button type="submit" class="btn btn-success">{{trans('Admin'.DS.'module_app.add_module')}}</button>
			</div>
		</div>
	</form>
@endsection

@section('JS')
<script type="text/javascript">
	$(function(){
		$('.imgupload').imageupload({
			allowedFormats: [ "jpg", "jpeg", "png", "gif", "svg" , "bmp"],
			previewWidth: 250,
			previewHeight: 250,
			maxFileSizeKb: 2048
		});

		$("#name").on("keyup",function(){
			var name = $(this).val();
			$("#machine_name").val(str_machine(name));
			$("#alias").val(str_slug(name))
		})

		$(".btn-file").on("click",function(){
			$(this).find("input").click();
		})
	})
</script>
@endsection

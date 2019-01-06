@extends('Admin..layout_admin.master_admin')

@section('content')

	<form class="form-horizontal form-label-left" method="post" action="{{route('add_category')}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		@if ($errors->has('error'))
			<span style="color: red">{{ $errors->first('error') }}</span>
		@endif
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'category.name')}} <span
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
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="machine_name">{{trans('Admin'.DS.'category.machine_name')}} <span
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
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="alias">{{trans('Admin'.DS.'category.alias')}} <span
					class="required">*</span>
			</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<input type="text" id="alias" name="alias" class="form-control col-md-7 col-xs-12" value="{{ old('alias') }}">
			</div>
			@if ($errors->has('alias'))
				<span style="color: red">{{ $errors->first('alias') }}</span>
			@endif
		</div>
		<!-- <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="parent">{{trans('Admin'.DS.'category.parent')}} </label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select class="form-control" name="parent" id="parent">
				 <option value="0" {{ old('parent') == '0' ? 'selected' : '' }}>-- {{trans('Admin'.DS.'category.no_parent')}} --</option>
				 @if (isset($list_category))
						@foreach ($list_category as $category)
							<option value="{{$category->id}}" {{ old('parent') == $category->id ? 'selected' : '' }}>{{$category->name}}</option>
						@endforeach
					@endif
				</select>
			</div>
		</div> -->
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="type">{{trans('global.type')}} <span class="required">*</span></label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select class="form-control" name="type" id="type">
				 <option value="service" {{ old('type') == 'service' ? 'selected' : '' }}>{{trans('global.service')}}</option>
				 <option value="product" {{ old('type') == 'product' ? 'selected' : '' }}>{{trans('global.product')}}</option>
				 <option value="location" {{ old('type') == 'location' ? 'selected' : '' }}>{{trans('global.locations')}}</option>
				</select>
			</div>
		</div>
		<!-- <div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="language">{{trans('global.language')}} </label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select class="form-control" name="language" id="language">
				 <option value="vn" {{ old('language') == 'vn' ? 'selected' : '' }}>Tiếng Việt</option>
				 <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
				</select>
			</div>
		</div> -->
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">{{trans('Admin'.DS.'category.description')}}</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<textarea type="text" id="description" name="description" class="form-control col-md-7 col-xs-12">{{ old('description') }}</textarea>
			</div>
			@if ($errors->has('description'))
				<span style="color: red">{{ $errors->first('description') }}</span>
			@endif
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'category.image')}}</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="imgupload panel panel-default">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left">{{trans('Admin'.DS.'category.upload_image')}}</h3>
						</div>
						<div class="file-tab panel-body">
							<div>
								<a type="button" class="btn btn-default btn-file">
								<span>{{trans('Admin'.DS.'category.browse')}}</span>
								<input type="file" name="image" id="image">
								</a>
								<button type="button" class="btn btn-default">{{trans('Admin'.DS.'category.remove')}}</button>
							</div>
						</div>
					</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'category.background')}}</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="imgupload panel panel-default">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left">{{trans('Admin'.DS.'category.upload_background')}}</h3>
						</div>
						<div class="file-tab panel-body">
							<div>
								<a type="button" class="btn btn-default btn-file">
								<span>{{trans('Admin'.DS.'category.browse')}}</span>
								<input type="file" name="background" id="background">
								</a>
								<button type="button" class="btn btn-default">{{trans('Admin'.DS.'category.remove')}}</button>
							</div>
						</div>
					</div>
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'category.marker')}}</label>
			<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="imgupload panel panel-default">
						<div class="panel-heading clearfix">
							<h3 class="panel-title pull-left">{{trans('Admin'.DS.'category.upload_marker')}}</h3>
						</div>
						<div class="file-tab panel-body">
							<div>
								<a type="button" class="btn btn-default btn-file">
								<span>{{trans('Admin'.DS.'category.browse')}}</span>
								<input type="file" name="marker" id="marker">
								</a>
								<button type="button" class="btn btn-default">{{trans('Admin'.DS.'category.remove')}}</button>
							</div>
						</div>
					</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-sm-offset-3">
				<input type="checkbox" {{ old('show_khong_gian') == 'on' || !old('_token') ? 'checked' : '' }} name="show_khong_gian"> {{trans('Admin'.DS.'category.show_khong_gian')}} 
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-sm-offset-3">
				<input type="checkbox" {{ old('show_hinh_anh') == 'on' || !old('_token') ? 'checked' : '' }} name="show_hinh_anh"> {{trans('Admin'.DS.'category.show_hinh_anh')}} 
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-sm-offset-3">
				<input type="checkbox" {{ old('show_video') == 'on' || !old('_token') ? 'checked' : '' }} name="show_video"> {{trans('Admin'.DS.'category.show_video')}} 
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-sm-offset-3">
				<input type="checkbox" {{ old('show_san_pham') == 'on' || !old('_token') ? 'checked' : '' }} name="show_san_pham"> {{trans('Admin'.DS.'category.show_san_pham')}} 
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-sm-offset-3">
				<input type="checkbox" {{ old('show_khuyen_mai') == 'on' || !old('_token') ? 'checked' : '' }} name="show_khuyen_mai"> {{trans('Admin'.DS.'category.show_khuyen_mai')}} 
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-sm-offset-3">
				<input type="checkbox" {{ old('show_chi_nhanh') == 'on' || !old('_token') ? 'checked' : '' }} name="show_chi_nhanh"> {{trans('Admin'.DS.'category.show_chi_nhanh')}} 
			</div>

		</div>


		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				{{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ old('active') == 'on' ? 'checked' : '' }} name="active"> {{trans('global.active')}}
			</div>
		</div>

		<div class="form-group">
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
				<button type="submit" class="btn btn-success">{{trans('Admin'.DS.'category.add_category')}}</button>
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

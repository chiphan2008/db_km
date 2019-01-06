@extends('Admin..layout_admin.master_admin')

@section('content')

	<form class="form-horizontal form-label-left" method="post" action="{{route('add_module_category_item',['module_id' => $module_id,'category_id' => $category_id])}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		@if ($errors->has('error'))
			<span style="color: red">{{ $errors->first('error') }}</span>
		@endif

		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_item">{{trans('global.category_item')}} </label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select class="form-control" name="category_item" id="category_item">
				 @if (isset($list_category_item))
						@foreach ($list_category_item as $category_item)
							<option value="{{$category_item->id}}" {{ old('category_item') == $category_item->id ? 'selected' : '' }}>{{$category_item->name}}</option>
						@endforeach
					@endif
				</select>
			</div>
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
			<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				{{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ old('active') == '1' ? 'checked' : '' }} name="active"> {{trans('global.active')}}
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>

<script type="text/javascript">
	$(function(){
		$("#category_item").select2();
		
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

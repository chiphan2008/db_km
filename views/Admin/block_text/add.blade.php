@extends('Admin..layout_admin.master_admin')

@section('content')

	<form class="form-horizontal form-label-left" method="post" action="{{route('add_block_text')}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		@if ($errors->has('error'))
			<span style="color: red">{{ $errors->first('error') }}</span>
		@endif
		<div class="form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'block_text.name')}} <span
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
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="machine_name">{{trans('Admin'.DS.'block_text.machine_name')}} <span
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
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="alias">{{trans('Admin'.DS.'block_text.alias')}} <span
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
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="type">{{trans('global.type')}} <span class="required">*</span></label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<select class="form-control" name="type" id="type" onchange="changeType(this)">
				 <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>{{trans('global.text')}}</option>
				 <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>{{trans('global.image')}}</option>
				</select>
			</div>
		</div>

		<div class="form-group block_type" data-type="text">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">{{trans('Admin'.DS.'block_text.content_vn')}} <span class="required">*</span></label>

			<div class="col-md-6 col-sm-6 col-xs-12">
				<textarea type="text" id="content_vn" rows="6" name="content_vn" class="form-control col-md-7 col-xs-12">{{ old('content_vn') }}</textarea>
			</div>
			@if ($errors->has('content_vn'))
				<span style="color: red">{{ $errors->first('content_vn') }}</span>
			@endif
		</div>

		<div class="form-group block_type" data-type="text">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">{{trans('Admin'.DS.'block_text.content_en')}} <span class="required">*</span></label>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<textarea type="text" id="content_en" rows="6" name="content_en" class="form-control col-md-7 col-xs-12">{{ old('content_en') }}</textarea>
			</div>
			@if ($errors->has('content_en'))
				<span style="color: red">{{ $errors->first('content_en') }}</span>
			@endif
		</div>
		
		<div class="form-group block_type" data-type="image">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'category.image')}} <span class="required">*</span></label>
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
			<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
				<button type="submit" class="btn btn-success">{{trans('Admin'.DS.'block_text.add_block_text')}}</button>
			</div>
		</div>
	</form>
@endsection

@section('JS')
<script type="text/javascript">
	function changeType(obj){
		var type = $(obj).val();
		$(".block_type").hide();
		$("[data-type="+type+"]").show();
		if(type=="text"){
			$("#image").val("");
		}
		if(type=="image"){
			$("#content_vn").val("");
			$("#content_en").val("");
		}
	}
	$(function(){
		$("#type").trigger("change");
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

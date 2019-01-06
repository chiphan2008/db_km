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
					<h2> {{trans('Admin'.DS.'category.move_category')}}</h2>
					<ul class="nav navbar-right panel_toolbox">
						<a href="{{route('list_dai_ly')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
					</ul>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<form class="form-horizontal form-label-left" id="form_move_category" method="post" action="" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'category.from_category')}}
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="from_category" id="from_category" class="form-control" onchange="get_category_item(this)">
									@foreach($list_category as $category)
									<option data-avatar="{{$category->image}}" value="{{$category->id}}" {{$category->id==old('from_category')?"selected":''}}>{{$category->name}}</option>
									@endforeach
								</select>
							</div>
							@if ($errors->has('from_category'))
							<span style="color: red">{{ $errors->first('from_category') }}</span>
							@endif
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'content.cat_item')}}
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="category_item[]" id="category_item" class="form-control" multiple>
								</select>
								@if ($errors->has('category_item'))
								<span style="color: red">{{ $errors->first('category_item') }}</span>
								@endif
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'category.to_category')}}
							</label>
							<div class="col-md-6 col-sm-6 col-xs-12">
								<select name="to_category" id="to_category" class="form-control">
									@foreach($list_category as $category)
									<option data-avatar="{{$category->image}}" value="{{$category->id}}" {{$category->id==old('to_category')?"selected":''}}>{{$category->name}}</option>
									@endforeach
								</select>
							</div>
							@if ($errors->has('to_category'))
							<span style="color: red">{{ $errors->first('to_category') }}</span>
							@endif
						</div>

						<div class="form-group col-md-12">
							<div class="col-md-4 col-sm-4 col-xs-12 col-md-offset-4">
								<button type="button" onclick="submit_form()" class="btn btn-success">{{trans('Admin'.DS.'category.move_category')}}</button>
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
<style>
	.select2-container--default .select2-results__option[aria-disabled=true] {
		display: none;
	}
</style>
<script>
	var old_category_item = {!! old('category_item')?json_encode(old('category_item'),true):json_encode([]) !!};
	$(function(){
		$("#from_category,#to_category,#category_item").select2({
			templateResult: formatData,
			templateSelection: formatData,
			escapeMarkup: function (m) {
				return m;
			},
			minimumInputLength: -1,
			placeholder: "{{trans('Admin'.DS.DS.'category.input_user')}}",
			language: "vi",
			closeOnSelect: true
		})



		$("#from_category").on("change",function(){
			$("#to_category option").each(function(key,elem){
				var from_category = $("#from_category").val();
				var to_category = $(elem).attr('value');
				if(from_category == to_category){
					$(elem).attr('disabled',true);
				}else{
					$(elem).attr('disabled',false);
				}
				$("#to_category").select2({
					templateResult: formatData,
					templateSelection: formatData,
					escapeMarkup: function (m) {
						return m;
					},
					minimumInputLength: -1,
					placeholder: "{{trans('Admin'.DS.'category.input_user')}}",
					language: "vi",
					closeOnSelect: true
				})
			})
		});

		$("#to_category").on("change",function(){
			$("#from_category option").each(function(key,elem){
				var to_category = $("#to_category").val();
				var from_category = $(elem).attr('value');
				if(to_category == from_category){
					$(elem).attr('disabled',true);
				}else{
					$(elem).attr('disabled',false);
				}
				$("#from_category").select2({
					templateResult: formatData,
					templateSelection: formatData,
					escapeMarkup: function (m) {
						return m;
					},
					minimumInputLength: -1,
					placeholder: "{{trans('Admin'.DS.'category.input_user')}}",
					language: "vi",
					closeOnSelect: true
				})
			})
		});

		$("#from_category option:nth-child(1)").attr("selected",true);
		$("#to_category option:nth-child(2)").attr("selected",true);
		$("#from_category").trigger("change");
		$("#to_category").trigger("change");

		function formatData (option) {
			var optimage = $(option.element).data('avatar'); 
			if (!option.id) { return option.text; }
			var ob = '&nbsp; ' +  option.id + ' - ' +option.text; // replace image source with option.img (available in JSON)
			return ob;
		};	
	});

	function submit_form() {
		if(confirm("{{trans('Admin'.DS.'category.confirm_update')}}")){
			$('#form_move_category').submit();
		}
	}
	function get_category_item(obj){
		var value = $(obj).val();
		var CSRF_TOKEN = $('input[name="_token"]').val();
		$.ajax({
		type: "POST",
				data: {value: value, _token: CSRF_TOKEN},
				url:  '/admin/content/ajaxCategoryItem',
				success: function (data) {
					$("#category_item").html(data);
					$("#category_item option:first").remove();
					console.log(old_category_item.length);
					if(old_category_item.length){
						$("#category_item option").each(function(key,elem){
							if(old_category_item.indexOf($(elem).attr('value')) > -1){
								$(elem).attr('selected',true);
							}
						});
					}else{
						$("#category_item option").attr('selected',true);
					}
				}
		})
	}
</script>
@endsection
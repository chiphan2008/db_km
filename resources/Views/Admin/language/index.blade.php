@extends('Admin..layout_admin.master_admin')

@section('content')
	@if(session('status'))
	<div class="alert alert-success alert-dismissible fade in" style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
		</button>
		{!! session('status') !!}
	</div>
	@endif
	@if(session('error'))
	<div class="alert alert-danger alert-dismissible fade in" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
		</button>
		{!! session('error') !!}
	</div>
	@endif
	<form class="form-horizontal form-label-left" method="post" action="{{route('save_language')}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		<div class="row">
			<div class="col-md-4 text-center"><label for="">English</label></div>
			<div class="col-md-4 text-center"><label for="">Tiếng Việt</label></div>
		</div>
		<div id="list_lang">
		@if($lang)
		@foreach($lang as $en => $vn)
		<div class="row form-group">
			<div class="col-md-4">
				<input type="text" name="en[]" class="form-control" required value="{{$en}}">
			</div>
			<div class="col-md-4">
				<input type="text" name="vn[]" class="form-control" required value="{{$vn}}">
			</div>
			@if(Auth::guard('web')->user()->can('delete_Language'))
			<a href="#" onclick="deleteLang(this)" title=""><i class="fa fa-remove"></i></a>
			@endif
		</div>
		@endforeach
		@endif
		</div>
		<div class="row form-group">
			<div class="col-md-8 text-center">
				@if(Auth::guard('web')->user()->can('edit_Language'))
				<button type="submit" class="btn btn-success">
					{{trans('global.save')}}
				</button>
				@endif
				@if(Auth::guard('web')->user()->can('add_Language'))
				<button type="button" class="btn btn-success" onclick="addLang()">
					{{trans('global.add')}} 
				</button>
				@endif
			</div>
		</div>
	</form>
@endsection

@section('JS')
	<script>
		function addLang(){
			var html = '<div class="row form-group">'
			html +='	<div class="col-md-4">'
			html +='		<input type="text" name="en[]" class="form-control" required value="">'
			html +='	</div>'
			html +='	<div class="col-md-4">'
			html +='		<input type="text" name="vn[]" class="form-control" required value="">'
			html +='	</div>'
			@if(Auth::guard('web')->user()->can('delete_Language'))
			html +='<a href="#" onclick="deleteLang(this)" title=""><i class="fa fa-remove"></i></a>';
			@endif
			html +='</div>';
			$("#list_lang").append(html);
		}
		function deleteLang(obj){
			if( confirm("{{trans('Admin'.DS.'language.confirm_delete')}}") ) {
				$(obj).parent().remove();
			}
		}
	</script>
@endsection
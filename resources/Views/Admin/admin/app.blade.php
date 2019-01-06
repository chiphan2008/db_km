@extends('Admin..layout_admin.master_admin')

@section('content')
<div class="row">
    <div class="col-xs-12">
    	@if(session('status'))
        <div class="alert alert-success alert-dismissible fade in" style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          {!! session('status') !!}
        </div>
      @endif
      <div class="x_panel" style="min-height: 250px;">
        <div class="x_title">
          <h2>Upload file app</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
						<form class="form-horizontal form-label-left" method="POST" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-12">File APP: </label>
									<div class="col-md-6 col-sm-6 col-xs-12">
										<input type="file" accept="" name="app_file" value="{{old('app_file')}}" />
										@if ($errors->has('app_file'))
			             		<span style="color: red">{{ $errors->first('app_file') }}</span>
			              @endif
									</div>
							</div>
							<div class="form-group">
									<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
										<button type="submit" class="btn btn-success">{{trans('global.save')}}</button>
									</div>
								</div>
						</form>
        </div>
      </div>
    </div>
</div>
@endsection
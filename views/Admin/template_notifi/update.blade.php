@extends('Admin..layout_admin.master_admin')

@section('content')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Update Template Notifi </h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('list_template_notifi')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
        	@if(session('status'))
          <div class="alert alert-success alert-dismissible fade in" style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            {!! session('status') !!}
          </div>
          @endif
          <br>
          <form id="#demo-form22" method="post" action="{{route('update_template_notifi',['id'=>$template->id])}}" data-parsley-validate=""
                class="form-horizontal form-label-left" novalidate="">

            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="name">{{trans('Admin'.DS.'template_notifi.template_notifi_name')}}
                <span class="required">*</span>
              </label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" id="name" name="name"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('name')?'parsley-error':''}}"
                       value="{{ $template->name }}" >
                @if ($errors->has('name'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('name') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="machine_name">{{trans('Admin'.DS.'template_notifi.machine_name')}}
                <span class="required">*</span>
              </label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" id="machine_name" name="machine_name"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('machine_name')?'parsley-error':''}}"
                       value="{{ $template->machine_name }}" readonly="" disabled>
                @if ($errors->has('machine_name'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('machine_name') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="language">{{trans('global.language')}} </label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control" name="language" id="language" disabled="">
                 <option value="vn" {{ $template->language == 'vn' ? 'selected' : '' }}>Tiếng Việt</option>
                 <option value="en" {{ $template->language == 'en' ? 'selected' : '' }}>English</option>
                </select>
              </div>
            </div>

						<div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content">{{trans('Admin'.DS.'template_notifi.content')}} <span class="required">*</span>
              </label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                <textarea id="content" name="content">{{ $template->content }}</textarea>
              </div>
            </div>

            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-3">
                <button type="submit"
                        class="btn btn-success">{{trans('Admin'.DS.'template_notifi.update_template_notifi')}}</button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
	@ckeditor('content', ['height' => 300])
  <script>
    $(function(){
    	CKEDITOR.editorConfig = function( config ) {
		    config.removePlugins = 'image,save,newpage,preview,print,templates,pastefromword,language,tableresize,liststyle,tabletools,scayt,menubutton,contextmenu,flash';
		  };
      $("#name").on("keyup",function(){
        var name = $(this).val();
        $("#machine_name").val(str_machine(name));
        $("#alias").val(str_slug(name));
      });
    })
  </script>
@endsection

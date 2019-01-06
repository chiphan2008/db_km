@extends('Admin..layout_admin.master_admin')

@section('content')
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Update Custom Page</h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('list_custom_page')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
          <form id="#demo-form22" method="post" action="{{route('update_custom_page',['id'=>$custom_page->id])}}" data-parsley-validate=""
                class="form-horizontal form-label-left" novalidate="" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12"
                     for="title">{{trans('Admin'.DS.'custom_page.title')}} <span
                  class="required">*</span>
              </label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" id="title" name="title"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('title')?'parsley-error':''}}"
                       value="{{ $custom_page->title }}" >
                @if ($errors->has('title'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('title') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12" for="machine_name">{{trans('Admin'.DS.'custom_page.machine_name')}}
              </label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" id="machine_name" name="machine_name" class="form-control col-md-7 col-xs-12" value="{{$custom_page->machine_name}}" disabled>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12" for="alias">{{trans('Admin'.DS.'custom_page.alias')}} <span
                  class="required">*</span>
              </label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" id="alias" name="alias"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('alias')?'parsley-error':''}}"
                       value="{{$custom_page->alias}}" >
                @if ($errors->has('alias'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('alias') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12" for="content_custom">{{trans('Admin'.DS.'custom_page.content')}} <span class="required">*</span>
              </label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                <textarea id="content_custom" name="content_custom">{{isset($custom_page->content) ? $custom_page->content : ''}}</textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12"></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                {{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ $custom_page->status == '1' ? 'checked' : '' }} name="active"> {{trans('global.active')}}
              </div>
            </div>
            <br/>

            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-3">
                <button type="submit"
                        class="btn btn-success">{{trans('Admin'.DS.'custom_page.update_custom_page')}}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @ckeditor('content_custom', ['height' => 400])
  <script type="text/javascript">
    CKEDITOR.editorConfig = function( config ) {
      config.removePlugins = 'image,save,newpage,preview,print,templates,pastefromword,language,tableresize,liststyle,tabletools,scayt,menubutton,contextmenu,flash';
    };
  </script>
@endsection




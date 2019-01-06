@extends('Admin..layout_admin.master_admin')

@section('content')
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Create Custom Page Langauge</h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('list_custom_page')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
          <form id="#demo-form22" method="post" action="{{route('custom_page_lang',['id'=>$id,'lang'=>$lang])}}" data-parsley-validate=""
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
                       value="{{isset($custom_page) ? $custom_page->title : ''}}" >
                @if ($errors->has('title'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('title') }}</li>
                  </ul>
                @endif
              </div>
            </div>


            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12" for="content_custom">{{trans('Admin'.DS.'custom_page.content')}} <span class="required">*</span>
              </label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                <textarea id="content_custom" name="content_custom">{{isset($custom_page) ? $custom_page->content : ''}}</textarea>
              </div>
            </div>

            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">Save</button>
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




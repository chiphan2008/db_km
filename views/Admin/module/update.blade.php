@extends('Admin..layout_admin.master_admin')

@section('content')

  <form id="form-module" class="form-horizontal form-label-left" method="post" action="{{route('update_module',['id' => $module->id])}}" enctype="multipart/form-data">
    {{ csrf_field() }}
    @if ($errors->has('error'))
      <span style="color: red">{{ $errors->first('error') }}</span>
    @endif
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'module_app.name')}} <span
          class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="name" name="name" class="form-control col-md-7 col-xs-12" value="{{ $module->name?$module->name:'' }}" >
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
        <input type="text" id="machine_name" name="machine_name" class="form-control col-md-7 col-xs-12" value="{{ $module->machine_name?$module->machine_name:'' }}" required readonly="">
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
        <input type="text" id="alias" name="alias" class="form-control col-md-7 col-xs-12" value="{{ $module->alias?$module->alias:'' }}" >
      </div>
      @if ($errors->has('alias'))
        <span style="color: red">{{ $errors->first('alias') }}</span>
      @endif
    </div>

    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        {{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ $module->active == '1' ? 'checked' : '' }} id="active" name="active"> {{trans('global.active')}}
      </div>
    </div>

    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <button type="button" onclick="submit_form()" class="btn btn-success">{{trans('Admin'.DS.'module_app.update_module')}}</button>
      </div>
    </div>
  </form>
@endsection

@section('JS')
<script type="text/javascript">

  function submit_form() {
    if(confirm("{{trans('Admin'.DS.'module_app.confirm_update')}}")){
      $('#form-module').submit();
    }
  }

  $(function(){
    $('.imgupload').imageupload({
      allowedFormats: [ "jpg", "jpeg", "png", "gif", "svg" , "bmp"],
      previewWidth: 250,
      previewHeight: 250,
      maxFileSizeKb: 2048
    });
    if($("#alias").val() == ''){
      $("#alias").val(str_slug($("#name").val()))
    }
    $("#name").on("keyup",function(){
      var name = $(this).val();
      $("#alias").val(str_slug(name))
    })
    var html_image = '<img src="{{$module->image?$module->image:'' }}" alt="Image preview" class="thumbnail" style="max-width: 250px; max-height: 250px">';
    $('.file-tab.image').prepend(html_image)

    var html_background = '<img src="{{$module->background?$module->background:'' }}" alt="Background preview" class="thumbnail" style="max-width: 250px; max-height: 250px">';
    $('.file-tab.background').prepend(html_background)

    var html_marker = '<img src="{{$module->marker?$module->marker:'' }}" alt="Marker preview" class="thumbnail" style="max-width: 250px; max-height: 250px">';
    $('.file-tab.marker').prepend(html_marker)


  });
</script>
@endsection

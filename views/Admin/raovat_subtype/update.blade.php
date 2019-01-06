@extends('Admin..layout_admin.master_admin')

@section('content')

  <form id="form-category-item" class="form-horizontal form-label-left" method="post" action="{{route('save_raovat_subtype',['raovat_type_id' => $raovat_type_id, 'id' => $raovat_subtype->id])}}" enctype="multipart/form-data">
    {{ csrf_field() }}
    @if ($errors->has('error'))
      <span style="color: red">{{ $errors->first('error') }}</span>
    @endif
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'raovat_subtype.name')}} <span
          class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="name" name="name" class="form-control col-md-7 col-xs-12" value="{{ $raovat_subtype->name?$raovat_subtype->name:'' }}" >
      </div>
      @if ($errors->has('name'))
        <span style="color: red">{{ $errors->first('name') }}</span>
      @endif
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="machine_name">{{trans('Admin'.DS.'raovat_subtype.machine_name')}} <span
          class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="machine_name" name="machine_name" class="form-control col-md-7 col-xs-12" value="{{ $raovat_subtype->machine_name?$raovat_subtype->machine_name:'' }}" required readonly="">
      </div>
      @if ($errors->has('machine_name'))
        <span style="color: red">{{ $errors->first('machine_name') }}</span>
      @endif
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alias">{{trans('Admin'.DS.'raovat_subtype.alias')}} <span
          class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="alias" name="alias" class="form-control col-md-7 col-xs-12" value="{{ $raovat_subtype->alias?$raovat_subtype->alias:'' }}" >
      </div>
      @if ($errors->has('alias'))
        <span style="color: red">{{ $errors->first('alias') }}</span>
      @endif
    </div>
<!--     <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="raovat_type_id">{{trans('Admin'.DS.'raovat_subtype.parent')}} </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="form-control" name="raovat_type_id" id="raovat_type_id">
         <option value="0" {{ $raovat_subtype->raovat_type_id == '0' ? 'selected' : '' }}>-- {{trans('Admin'.DS.'raovat_subtype.no_parent')}} --</option>
         @if (isset($list_category))
            @foreach ($list_category as $one_raovat_subtype)
              <option value="{{$one_raovat_subtype->id}}" {{ $raovat_subtype->raovat_type_id == $one_raovat_subtype->id ? 'selected' : '' }}>{{$one_raovat_subtype->name}}</option>
            @endforeach
          @endif
        </select>
      </div>
    </div> -->
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="language">{{trans('global.language')}} </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="form-control" name="language" id="language">
         <option value="vn" {{ $raovat_subtype->language == 'vn' ? 'selected' : '' }}>Tiếng Việt</option>
         <option value="en" {{ $raovat_subtype->language == 'en' ? 'selected' : '' }}>English</option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">{{trans('Admin'.DS.'raovat_subtype.description')}}</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <textarea type="text" id="description" name="description" class="form-control col-md-7 col-xs-12">{{ $raovat_subtype->description?$raovat_subtype->description:'' }}</textarea>
      </div>
      @if ($errors->has('description'))
        <span style="color: red">{{ $errors->first('description') }}</span>
      @endif
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'raovat_subtype.image')}}</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="imgupload panel panel-default">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">{{trans('Admin'.DS.'raovat_subtype.upload_image')}}</h3>
            </div>
            <div class="file-tab panel-body">
              <div>
                <a type="button" class="btn btn-default btn-file">
                <span>{{trans('Admin'.DS.'raovat_subtype.browse')}}</span>
                <input type="file" name="image" id="image">
                </a>
                <button type="button" class="btn btn-default">{{trans('Admin'.DS.'raovat_subtype.remove')}}</button>
              </div>
            </div>
          </div>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        {{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ $raovat_subtype->active == '1' ? 'checked' : '' }} name="active"> {{trans('global.active')}}
      </div>
    </div>

    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <button type="button" onclick="submit_form()" class="btn btn-success">{{trans('Admin'.DS.'raovat_subtype.update_raovat_subtype')}}</button>
      </div>
    </div>
  </form>
@endsection

@section('JS')
<script type="text/javascript">

  function submit_form() {
    if(confirm("{{trans('Admin'.DS.'raovat_subtype.confirm_update')}}")){
      $('#form-category-item').submit();
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
    var html = '<img src="{{$raovat_subtype->image?$raovat_subtype->image:'' }}" alt="Image preview" class="thumbnail" style="max-width: 250px; max-height: 250px">';
    $('.file-tab').prepend(html)
  });
</script>
@endsection

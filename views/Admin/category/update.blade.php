@extends('Admin..layout_admin.master_admin')

@section('content')

  <form id="form-category" class="form-horizontal form-label-left" method="post" action="{{route('update_category',['id' => $category->id])}}" enctype="multipart/form-data">
    {{ csrf_field() }}
    @if ($errors->has('error'))
      <span style="color: red">{{ $errors->first('error') }}</span>
    @endif
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'category.name')}} <span
          class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="name" name="name" class="form-control col-md-7 col-xs-12" value="{{ $category->name?$category->name:'' }}" >
      </div>
      @if ($errors->has('name'))
        <span style="color: red">{{ $errors->first('name') }}</span>
      @endif
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="machine_name">{{trans('Admin'.DS.'category.machine_name')}} <span
          class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="machine_name" name="machine_name" class="form-control col-md-7 col-xs-12" value="{{ $category->machine_name?$category->machine_name:'' }}" required readonly="">
      </div>
      @if ($errors->has('machine_name'))
        <span style="color: red">{{ $errors->first('machine_name') }}</span>
      @endif
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alias">{{trans('Admin'.DS.'category.alias')}} <span
          class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="alias" name="alias" class="form-control col-md-7 col-xs-12" value="{{ $category->alias?$category->alias:'' }}" >
      </div>
      @if ($errors->has('alias'))
        <span style="color: red">{{ $errors->first('alias') }}</span>
      @endif
    </div>
    <!-- <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="parent">{{trans('Admin'.DS.'category.parent')}} </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="form-control" name="parent" id="parent">
         <option value="0" {{ $category->parent == '0' ? 'selected' : '' }}>-- {{trans('Admin'.DS.'category.no_parent')}} --</option>
         @if (isset($list_category))
            @foreach ($list_category as $one_category)
              <option value="{{$one_category->id}}" {{ $category->parent == $one_category->id ? 'selected' : '' }}>{{$one_category->name}}</option>
            @endforeach
          @endif
        </select>
      </div>
    </div> -->
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="type">{{trans('global.type')}} <span class="required">*</span></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="form-control" name="type" id="type">
         <option value="service" {{ $category->type == 'service' ? 'selected' : '' }}>{{trans('global.service')}}</option>
         <option value="product" {{ $category->type == 'product' ? 'selected' : '' }}>{{trans('global.product')}}</option>
         <option value="location" {{ $category->type == 'location' ? 'selected' : '' }}>{{trans('global.locations')}}</option>
        </select>
      </div>
    </div>

    <!-- <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="language">{{trans('global.language')}} </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="form-control" name="language" id="language">
         <option value="vn" {{ $category->language == 'vn' ? 'selected' : '' }}>Tiếng Việt</option>
         <option value="en" {{ $category->language == 'en' ? 'selected' : '' }}>English</option>
        </select>
      </div>
    </div> -->
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">{{trans('Admin'.DS.'category.description')}}</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <textarea type="text" id="description" name="description" class="form-control col-md-7 col-xs-12">{{ $category->description?$category->description:'' }}</textarea>
      </div>
      @if ($errors->has('description'))
        <span style="color: red">{{ $errors->first('description') }}</span>
      @endif
    </div>

    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'category.image')}}</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="imgupload panel panel-default">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">{{trans('Admin'.DS.'category.upload_image')}}</h3>
            </div>
            <div class="file-tab panel-body image">
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
            <div class="file-tab panel-body background">
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
      <label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'category.marker')}}</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="imgupload panel panel-default">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">{{trans('Admin'.DS.'category.upload_marker')}}</h3>
            </div>
            <div class="file-tab panel-body marker">
              <div>
                <a type="button" class="btn btn-default btn-file">
                <span>{{trans('Admin'.DS.'category.browse')}}</span>
                <input type="file" name="marker" id="marker">
                </a>
                <button type="button" class="btn btn-default">{{trans('Admin'.DS.'category.remove')}}</button>
              </div>
            </div>
          </div>
      </div>
    </div>
  

    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-sm-offset-3">
        <input type="checkbox" {{ $category->show_khong_gian ? 'checked' : '' }} name="show_khong_gian"> {{trans('Admin'.DS.'category.show_khong_gian')}} 
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-sm-offset-3">
        <input type="checkbox" {{ $category->show_hinh_anh ? 'checked' : '' }} name="show_hinh_anh"> {{trans('Admin'.DS.'category.show_hinh_anh')}} 
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-sm-offset-3">
        <input type="checkbox" {{ $category->show_video ? 'checked' : '' }} name="show_video"> {{trans('Admin'.DS.'category.show_video')}} 
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-sm-offset-3">
        <input type="checkbox" {{ $category->show_san_pham ? 'checked' : '' }} name="show_san_pham"> {{trans('Admin'.DS.'category.show_san_pham')}} 
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-sm-offset-3">
        <input type="checkbox" {{ $category->show_khuyen_mai ? 'checked' : '' }} name="show_khuyen_mai"> {{trans('Admin'.DS.'category.show_khuyen_mai')}} 
      </div>
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3 col-sm-offset-3">
        <input type="checkbox" {{ $category->show_chi_nhanh ? 'checked' : '' }} name="show_chi_nhanh"> {{trans('Admin'.DS.'category.show_chi_nhanh')}} 
      </div>
    </div>


    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        {{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ $category->active == '1' ? 'checked' : '' }} id="active" name="active"> {{trans('global.active')}}
      </div>
    </div>

    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <button type="button" onclick="submit_form()" class="btn btn-success">{{trans('Admin'.DS.'category.update_category')}}</button>
      </div>
    </div>
  </form>
@endsection

@section('JS')
<script type="text/javascript">

  function submit_form() {
    if(confirm("{{trans('Admin'.DS.'category.confirm_update')}}")){
      $('#form-category').submit();
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
    var html_image = '<img src="{{$category->image?$category->image:'' }}" alt="Image preview" class="thumbnail" style="max-width: 250px; max-height: 250px">';
    $('.file-tab.image').prepend(html_image)

    var html_background = '<img src="{{$category->background?$category->background:'' }}" alt="Background preview" class="thumbnail" style="max-width: 250px; max-height: 250px">';
    $('.file-tab.background').prepend(html_background)

    var html_marker = '<img src="{{$category->marker?$category->marker:'' }}" alt="Marker preview" class="thumbnail" style="max-width: 250px; max-height: 250px">';
    $('.file-tab.marker').prepend(html_marker)


  });
</script>
@endsection

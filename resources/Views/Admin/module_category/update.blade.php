@extends('Admin..layout_admin.master_admin')

@section('content')

  <form id="form-category" class="form-horizontal form-label-left" method="post" action="{{route('update_module_category',['module_id'=>$module_id,'category_id' => $category->category_id])}}" enctype="multipart/form-data">
    {{ csrf_field() }}
    @if ($errors->has('error'))
      <span style="color: red">{{ $errors->first('error') }}</span>
    @endif

    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category">{{trans('global.category')}} </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="form-control" name="category" id="category">
         @if (isset($list_category))
            @foreach ($list_category as $one_category)
              <option value="{{$one_category->id}}" {{ $category->id == $one_category->id ? 'selected' : '' }}>{{$one_category->name}}</option>
            @endforeach
          @endif
        </select>
      </div>
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>

<script type="text/javascript">

  function submit_form() {
    if(confirm("{{trans('Admin'.DS.'category.confirm_update')}}")){
      $('#form-category').submit();
    }
  }

  $(function(){
    $("#category").select2();
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


  });
</script>
@endsection

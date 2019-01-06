@extends('Admin..layout_admin.master_admin')

@section('content')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Update Content Type</h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('list_content_type')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
          <form id="form-content-type" method="post" action="{{route('update_content_type',['id' => $content_type->id])}}"
                data-parsley-validate=""
                class="form-horizontal form-label-left" novalidate="" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="name">{{trans('Admin'.DS.'content_type.name')}} <span
                  class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="name" name="name"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('name')?'parsley-error':''}}"
                       value="{{$content_type->name}}" >
                @if ($errors->has('name'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('name') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="machine_name">Machine name
                <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="machine_name" name="machine_name" readonly
                       class="form-control col-md-7 col-xs-12 {{$errors->has('machine_name')?'parsley-error':''}}"
                       value="{{ $content_type->machine_name }}" >
                @if ($errors->has('machine_name'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('machine_name') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="alias">Alias
                <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="alias" name="alias"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('alias')?'parsley-error':''}}"
                       value="{{ $content_type->alias }}" >
                @if ($errors->has('alias'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('alias') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="image">{{trans('global.image')}}
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="file" id="image" name="image" accept="image/gif, image/jpeg, image/png"
                       onchange="readURL(this,'image_content_type')"/>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
              <div class="col-md-6 col-sm-6 col-xs-12" id="image_content_type">
                <img style="height: 90px; width: 90px; border: 1px solid #000; margin: 2px"
                     src="{{asset($content_type->image)}}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="language">{{trans('global.language')}} </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="language" id="language">
                  <option value="vn" {{ $content_type->language == 'vn' ? 'selected' : '' }}>Tiếng Việt</option>
                  <option value="en" {{ $content_type->language == 'en' ? 'selected' : '' }}>English</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="description">{{trans('Admin'.DS.'content_type.description')}}</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea type="text" id="description" name="description"
                          class="form-control col-md-7 col-xs-12">{{ $content_type->description ? $content_type->description : '' }}</textarea>
              </div>
            </div>
            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit"
                        class="btn btn-success">{{trans('Admin'.DS.'content_type.add_content_type')}}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script>
    var old_src = $('#image_content_type img')[0].src;
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $('#image_content_type img')
            .attr('src', e.target.result)
        };
        reader.readAsDataURL(input.files[0]);
      }
      else {
        $('#image_content_type img').attr('src', old_src)
      }
    }

    $(function(){
      $("#name").on("keyup",function(){
        var name = $(this).val();
        $("#alias").val(str_slug(name));
      });
    })
  </script>
@endsection
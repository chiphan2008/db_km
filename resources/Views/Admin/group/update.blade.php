@extends('Admin..layout_admin.master_admin')

@section('content')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Update Group</h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('list_group')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
          <form id="#demo-form22" method="post" action="{{route('update_group',['id' => $group->id])}}" data-parsley-validate=""
                class="form-horizontal form-label-left" novalidate="" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="name">{{trans('Admin'.DS.'group'.DS.'all.name')}} <span
                  class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="name" name="name"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('name')?'parsley-error':''}}"
                       value="{{ $group->name }}" >
                @if ($errors->has('name'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('name') }}</li>
                  </ul>
                @endif
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="machine_name">{{trans('Admin'.DS.'group'.DS.'all.machine_name')}} <span
                  class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="machine_name" name="machine_name" readonly
                       class="form-control col-md-7 col-xs-12 {{$errors->has('machine_name')?'parsley-error':''}}"
                       value="{{ $group->machine_name }}">
                @if ($errors->has('machine_name'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('machine_name') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="alias">{{trans('Admin'.DS.'group'.DS.'all.alias')}}
                <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="alias" name="alias"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('alias')?'parsley-error':''}}"
                       value="{{ $group->alias }}" >
                @if ($errors->has('alias'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('alias') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_category">{{trans('Admin'.DS.'group.cat_type')}} <span
                  class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control {{$errors->has('content_type')?'parsley-error':''}}" name="id_category"
                        id="id_category" >
                  <option value="">-- {{trans('Admin'.DS.'group.type')}} --</option>
                  @foreach($data['list_category_type'] as $value => $name)
                    <option
                      value="{{$value}}" {{ $group->id_category == $value ? 'selected' : '' }}>{{$name}}</option>
                  @endforeach
                </select>
                @if ($errors->has('id_category'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('id_category') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit"
                        class="btn btn-success">{{trans('Admin'.DS.'group'.DS.'all.update_group')}}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script>

    $(function(){
      $("#name").on("keyup",function(){
        var name = $(this).val();
        $("#alias").val(str_slug(name));
      });
    })
  </script>
@endsection
@extends('Admin..layout_admin.master_admin')

@section('content')

  <form class="form-horizontal form-label-left" method="post" action="{{route('update_client_group',['id' => $group->id])}}" enctype="multipart/form-data">
    {{ csrf_field() }}
    @if ($errors->has('error'))
      <span style="color: red">{{ $errors->first('error') }}</span>
    @endif
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'role.name')}} <span
          class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="name" name="name" class="form-control col-md-7 col-xs-12" value="{{ $group->name?$group->name:'' }}" >
      </div>
      @if ($errors->has('name'))
        <span style="color: red">{{ $errors->first('name') }}</span>
      @endif
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="machine_name">{{trans('Admin'.DS.'role.machine_name')}} <span
          class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="machine_name" name="machine_name" class="form-control col-md-7 col-xs-12" value="{{ $group->machine_name?$group->machine_name:'' }}" readonly="">
      </div>
      @if ($errors->has('machine_name'))
        <span style="color: red">{{ $errors->first('machine_name') }}</span>
      @endif
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">{{trans('Admin'.DS.'role.description')}}</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <textarea type="text" id="description" name="description" class="form-control col-md-7 col-xs-12">{{ $group->description?$group->description:'' }}</textarea>
      </div>
      @if ($errors->has('description'))
        <span style="color: red">{{ $errors->first('description') }}</span>
      @endif
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        {{trans('Admin'.DS.'role.undefault')}} <input type="checkbox" class="js-switch" {{ $group->default == '1' ? 'checked' : '' }} name="default"> {{trans('Admin'.DS.'role.default')}}
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        {{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ $group->active == '1' ? 'checked' : '' }} name="active"> {{trans('global.active')}}
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'role.update_group')}}</button>
      </div>
    </div>
  </form>
@endsection
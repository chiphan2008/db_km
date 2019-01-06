@extends('Admin..layout_admin.master_admin')

@section('content')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Update Service Item</h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('list_service_item')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
          <form id="#demo-form22" method="post" action="{{route('update_service_item',['id'=>$service_item->id])}}" data-parsley-validate=""
                class="form-horizontal form-label-left" novalidate="" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="name">{{trans('Admin'.DS.'service_item.name')}} <span
                  class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="name" name="name"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('name')?'parsley-error':''}}"
                       value="{{ $service_item->name }}" >
                @if ($errors->has('name'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('name') }}</li>
                  </ul>
                @endif
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="machine_name">{{trans('Admin'.DS.'service_item.machine_name')}} <span
                  class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="machine_name" name="machine_name"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('machine_name')?'parsley-error':''}}"
                       value="{{ $service_item->machine_name }}" readonly>
                @if ($errors->has('machine_name'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('machine_name') }}</li>
                  </ul>
                @endif
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                {{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ $service_item->active == '1' ? 'checked' : '' }} name="active"> {{trans('global.active')}}
              </div>
            </div>

            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'service_item.update_service_item')}}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

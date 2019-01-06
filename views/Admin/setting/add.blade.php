@extends('Admin..layout_admin.master_admin')
@section('content')
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Create Setting</h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('list_setting')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
          <form id="#demo-form22" method="post" action="{{route('add_setting')}}"
                data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="key">Key<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="key" name="key"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('key')?'parsley-error':''}}"
                       value="{{ old('key') }}" >
                @if ($errors->has('key'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('key') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="value">Value<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="value" name="value"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('value')?'parsley-error':''}}"
                       value="{{ old('value') }}" >
                @if ($errors->has('value'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('value') }}</li>
                  </ul>
                @endif
              </div>
            </div>
            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">Add Setting</button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
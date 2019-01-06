@extends('Admin..layout_admin.master_admin')

@section('content')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Update Service Item</h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('list_notifi_type')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
          <form id="#demo-form22" method="post" action="{{route('update_notifi_type',['id'=>$notifi_type->id])}}" data-parsley-validate=""
                class="form-horizontal form-label-left" novalidate="" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="title">Title <span
                  class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="title" name="title"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('title')?'parsley-error':''}}"
                       value="{{ $notifi_type->title }}" >
                @if ($errors->has('title'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('title') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                {{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ $notifi_type->status == '1' ? 'checked' : '' }} name="active"> {{trans('global.active')}}
              </div>
            </div>

            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">Update Notifi Type</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
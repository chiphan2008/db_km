@extends('Admin..layout_admin.master_admin')

@section('content')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Create Country </h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('list_country')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
          <form id="#demo-form22" method="post" action="" data-parsley-validate=""
                class="form-horizontal form-label-left" novalidate="">

            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="name">{{trans('Admin'.DS.'page_location.country_name')}}
                <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="name" name="name"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('name')?'parsley-error':''}}"
                       value="{{ old('name') }}" >
                @if ($errors->has('name'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('name') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="machine_name">{{trans('Admin'.DS.'page_location.machine_name')}}
                <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="machine_name" name="machine_name"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('machine_name')?'parsley-error':''}}"
                       value="{{ old('machine_name') }}" >
                @if ($errors->has('machine_name'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('machine_name') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="machine_name">{{trans('Admin'.DS.'page_location.alias')}}
                <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="alias" name="alias"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('alias')?'parsley-error':''}}"
                       value="{{ old('alias') }}" >
                @if ($errors->has('alias'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('alias') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="username">{{trans('Admin'.DS.'page_location.zip_code')}}
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="zipcode" name="zipcode" class="form-control col-md-7 col-xs-12"
                       value="{{ old('zipcode') }}">
              </div>
              @if ($errors->has('zipcode'))
                <span style="color: red">{{ $errors->first('zipcode') }}</span>
              @endif
            </div>
            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit"
                        class="btn btn-success">{{trans('Admin'.DS.'page_location.add_country')}}</button>
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
        $("#machine_name").val(str_machine(name));
        $("#alias").val(str_slug(name));
      });
    })
  </script>
@endsection

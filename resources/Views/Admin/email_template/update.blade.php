@extends('Admin..layout_admin.master_admin')

@section('content')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Update Email Template </h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('list_email')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
          <form id="#demo-form22" method="post" action="{{route('update_email',['id'=>$email->id])}}" data-parsley-validate=""
                class="form-horizontal form-label-left" novalidate="">

            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="machine_name">{{trans('Admin'.DS.'email_template.name')}}
                <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="name" name="name"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('name')?'parsley-error':''}}"
                       value="{{ $email->name }}" >
                @if ($errors->has('name'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('name') }}</li>
                  </ul>
                @endif
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="machine_name">{{trans('Admin'.DS.'email_template.machine_name')}}
                <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="machine_name" name="machine_name" class="form-control col-md-7 col-xs-12"
                       value="{{ $email->machine_name }}" required readonly>
              </div>
              @if ($errors->has('machine_name'))
                <span style="color: red">{{ $errors->first('machine_name') }}</span>
              @endif
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="subject">{{trans('Admin'.DS.'email_template.subject')}}
                <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="subject" name="subject"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('subject')?'parsley-error':''}}"
                       value="{{ $email->subject }}" >
                @if ($errors->has('subject'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('subject') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="subject">{{trans('Admin'.DS.'email_template.body')}} <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea type="text" id="body" name="body" rows="20"
                          class="form-control col-md-7 col-xs-12" >{{ $email->body }}</textarea>
              </div>
            </div>

            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit"
                        class="btn btn-success">{{trans('Admin'.DS.'email_template.update_email_template')}}</button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
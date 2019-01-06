@extends('Admin..layout_admin.master_admin')

@section('content')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Setting Notify</h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('list_notifi')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
          <form id="#demo-form22" method="post" action="{{route('setting_notifi',['id'=>$id])}}" data-parsley-validate=""
                class="form-horizontal form-label-left" novalidate="" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12" for="time_setting">Time</label>
              <div class='input-group date col-md-5 col-sm-5 col-xs-12'>
                <input type='text' class="form-control {{$errors->has('time_setting')?'parsley-error':''}}" id="time_setting" name="time_setting" value="{{isset($setting_notifi) ? $setting_notifi->time : ''}}" />
                @if ($errors->has('time_setting'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('time_setting') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12"></label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                Gửi theo thời gian <input type="checkbox" onchange="changeSendType()" class="js-switch" id="active" {{isset($setting_notifi) && $setting_notifi->send_type == '1' ? 'checked' : '' }} name="active"> Gửi liền
              </div>
            </div>

            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">Add Seting Notifi</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script type="application/javascript">
    function changeSendType()
    {
      if($('#active').is(':checked')){
        $("#time_setting").attr("disabled", true);
      } else {
        $("#time_setting").attr("disabled", false);
      }
    }

    $(function () {
      $('#time_setting').datetimepicker({
        minDate: moment(),
        format: 'YYYY-MM-DD H:mm'
      });
      if($('#active').is(':checked')){
        $("#time_setting").attr("disabled", true);
      } else {
        $("#time_setting").attr("disabled", false);
      }
    });
  </script>
@endsection



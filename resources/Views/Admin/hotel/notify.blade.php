@extends('Admin..layout_admin.master_admin')

@section('content')
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Create Notify Content</h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('list_content')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
          <form id="#demo-form22" method="post" action="{{route('notify_content',['id'=>$id])}}" data-parsley-validate=""
                class="form-horizontal form-label-left" novalidate="" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12" for="title">Title <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input class="form-control col-md-7 col-xs-12" type="text" value="{{isset($notify) ? $notify->title : ''}}" name="title" required autocomplete="off"/>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12" for="description">Description <span class="required">*</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea rows="4" type="text" name="description" class="form-control col-md-7 col-xs-12" >{{isset($notify) ? $notify->description : ''}}</textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12">Date From</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <div class='input-group date' style="margin-bottom: 0px" id='start_date'>
                  <input type='text' class="form-control" value="{{isset($notify) ? $notify->start : ''}}" name="start_date" />
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
              </span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12">Date To</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <div class='input-group date' style="margin-bottom: 0px" id='end_date'>
                  <input type='text' class="form-control" value="{{isset($notify) ? $notify->end : ''}}" name="end_date" />
                  <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
              </span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12"></label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                None Active <input {{ isset($notify) && $notify->active == 1 ? 'checked' : '' }} type="checkbox" class="js-switch" name="active" /> Active
              </div>
            </div>

            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-4 col-sm-4 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('JS')
  <script>
    $(function () {
      $('#start_date').datetimepicker({
        format: 'YYYY-MM-DD HH:mm'
      });

      $('#end_date').datetimepicker({
        format: 'YYYY-MM-DD HH:mm'
      });

      $("#start_date").on("dp.change", function (e) {
        $('#end_date').data("DateTimePicker").minDate(e.date);
      });
      $("#end_date").on("dp.change", function (e) {
        $('#start_date').data("DateTimePicker").maxDate(e.date);
      });
    });
  </script>
@endsection



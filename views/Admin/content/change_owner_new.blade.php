@extends('Admin..layout_admin.master_admin')

@section('content')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>{{trans('Admin'.DS.'content.owner_change')}}</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
          <div class="col-md-12">
            @if(session('status'))
            <div class="alert alert-success alert-dismissible fade in" style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                </button>
                {!! session('status') !!}
            </div>
            @endif
          </div>
          <form id="form-note-content" method="post" action="{{route('change_owner')}}"
                enctype="multipart/form-data" autocomplete="off"
                data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content">{{trans('global.locations')}} <span
                  class="required">*</span></label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <select class="form-control {{$errors->has('content')?'parsley-error':''}}" name="content[]" id="content" multiple >
                </select>
                @if ($errors->has('content'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('content') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content">{{trans('global.user')}} <span
                  class="required">*</span></label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <select class="form-control {{$errors->has('user')?'parsley-error':''}}" name="user" id="user">
                </select>
                @if ($errors->has('user'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('user') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group text-center">
              <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'content.owner_change')}}</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
@endsection
@section('JS')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/i18n/vi.js"></script>

  <script type="text/javascript">
    $(function () {
      $("#content").select2({
        ajax:{
          url       : "{{route('search_content')}}",
          type      : "GET",
          delay     : 800,
          dataType  :'json',
          data : function(params){
            var query = {
              query: params.term
            }
            return query;
          },
          cache:true
          // processResults: function (data) {
          //   return {
          //     results: data.items
          //   };
          // }
        },
        minimumInputLength: 1,
        placeholder: "{{trans('Admin'.DS.'content.input_content')}}",
        templateResult: formatDataContent,
        templateSelection: formatDataContent,
        escapeMarkup: function (m) {
          return m;
        },
        language: "vi",
        closeOnSelect: false
      })

      $("#user").select2({
        ajax:{
          url       : "{{route('search_user')}}",
          type      : "GET",
          delay     : 800,
          dataType  :'json',
          data : function(params){
            var query = {
              query: params.term
            }
            return query;
          },
          cache:true
          // processResults: function (data) {
          //   return {
          //     results: data.items
          //   };
          // }
        },
        templateResult: formatData,
        templateSelection: formatData,
        escapeMarkup: function (m) {
          return m;
        },
        minimumInputLength: 1,
        placeholder: "{{trans('Admin'.DS.'content.input_user')}}",
        language: "vi",
        closeOnSelect: true
      })
    })

    function formatData (option) {
      if (!option.id) { return option.text; }
      var ob = '<img width="28" height="28" src="'+ option.avatar +'" />&nbsp;&nbsp;&nbsp;' + option.text; // replace image source with option.img (available in JSON)
      return ob;
    };
    function formatDataContent (option) {
      if (!option.id) { return option.text; }
      var ob = '<img width="42" height="32" src="'+ option.avatar +'" />&nbsp;&nbsp;&nbsp;' + option.text + ' - ' + option.address; // replace image source with option.img (available in JSON)
      return ob;
    };
  </script>
@endsection
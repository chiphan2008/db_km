@extends('Admin..layout_admin.master_admin')

@section('content')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>{{trans('Admin'.DS.'content.ctv_change')}}</h2>
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
          <form id="form-note-content" method="post" action="{{route('change_ctv')}}"
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
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content">{{trans('Admin'.DS.'client.daily')}} <span
                  class="required">*</span></label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <select class="form-control {{$errors->has('daily')?'parsley-error':''}}" name="daily" id="daily">
                </select>
                @if ($errors->has('daily'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('daily') }}</li>
                  </ul>
                @endif
              </div>
            </div>


            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content">{{trans('Admin'.DS.'client.ctv')}} <span
                  class="required">*</span></label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <select class="form-control {{$errors->has('ctv')?'parsley-error':''}}" name="ctv" id="ctv">
                </select>
                @if ($errors->has('ctv'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('ctv') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group text-center">
              <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'content.ctv_change')}}</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
@endsection
@section('JS')
  <style>
    .select2-choices,.select2-selection__choice{
      display:-webkit-inline-box;
      width: 100%;
      background: #FFF !important;
    }
    .select2-selection__arrow {
      height: 34px !important;
    }
    .select2-selection__rendered {
      line-height: 32px !important;
    }

    .select2-selection--single {
      height: 36px !important;
    }
    .select2-search__field {
      min-width: auto !important;
    }
    .select2-container .select2-search--inline{
      width: 100%;
    }

    #form_change_owner .select2-container--default .select2-selection--multiple .select2-selection__rendered{
      border: none;
      box-shadow: none;
    }

    #form_change_owner .select2-container--default .select2-selection--multiple{
      border: none;
    }

    #form_change_owner .select2-selection__rendered.ui-autocomplete-owner{
      padding: 0;
      max-height: 259px;
      overflow-y: auto;
      margin-bottom: 40px;
    }

    #form_change_owner .select2-search.select2-search--inline{
      border: 1px solid #aaa; 
      padding-left: 8px;
      border-radius: 5px;
      margin-top: 10px;
      position: absolute;
      bottom: 0;
    }
    #form_change_owner .select2-search.select2-search--inline input{
      margin-top: 0;
      line-height: 36px;
    }
    #test_con{
      position: relative;
    }
    #test_con .select2-container{
      top:-3px !important;
    }
    #form_change_owner .ui-autocomplete-owner .ui-menu-item .content-search p{
        overflow: hidden;
        margin-bottom: 0;
        white-space: nowrap;
        text-overflow: ellipsis;
        color: #5b89ab;
    }
    #form_change_owner .select2-selection__choice .content-search{
      display: block;
      width: 94%;
    }
  </style>
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

      $("#daily").select2({
        ajax:{
          url       : "{{route('search_daily_content')}}",
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
        placeholder: "{{trans('Admin'.DS.'content.input_daily')}}",
        language: "vi",
        closeOnSelect: true
      })

      $("#ctv").select2({
        ajax:{
          url       : "{{route('search_ctv_content')}}",
          type      : "GET",
          delay     : 800,
          dataType  :'json',
          data : function(params){
            var query = {
              query: params.term,
              daily: $("#daily").val()
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
        placeholder: "{{trans('Admin'.DS.'content.input_ctv')}}",
        language: "vi",
        closeOnSelect: true
      })
    })

    function formatData (option) {
      if (!option.id) { return option.text; }
      var ob = '<img width="28" height="28" src="'+ option.avatar +'" />&nbsp;&nbsp;&nbsp;<span class="word_break">' + option.text + '</span>'; // replace image source with option.img (available in JSON)
      return ob;
    };
    function formatDataContent (option) {
      if (!option.id) { return option.text; }
      var ob = '<img width="42" height="32" src="'+ option.avatar +'" />&nbsp;&nbsp;&nbsp;<span class="word_break">' + option.text + ' - ' + option.address + '</span>'; // replace image source with option.img (available in JSON)
      return ob;
    };
  </script>
@endsection
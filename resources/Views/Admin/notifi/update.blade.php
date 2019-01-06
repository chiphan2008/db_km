@extends('Admin..layout_admin.master_admin')

@section('content')
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Update Notify</h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('list_notifi')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
          <form id="#demo-form22" method="post" action="{{route('update_notifi',['id'=>$notifi->id])}}" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12"
                     for="title">Title <span class="required">*</span>
              </label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" id="title" name="title"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('title')?'parsley-error':''}}"
                       value="{{ $notifi->title }}" >
                @if ($errors->has('title'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('title') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12">{{trans('Admin'.DS.'notifi.image')}}</label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                  <div class="imageupload panel panel-default">
                    <div class="panel-heading clearfix">
                      <h3 class="panel-title pull-left">{{trans('Admin'.DS.'notifi.upload_image')}}</h3>
                    </div>
                    <div class="file-tab panel-body">
                      <div>
                        <a type="button" class="btn btn-default btn-file">
                        <span>Browse</span>
                        <input type="file" name="image" id="image">
                        </a>
                        <button type="button" class="btn btn-default">Remove</button>
                      </div>
                    </div>
                  </div>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12"></label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                {{trans('Admin'.DS.'notifi.not_use_template')}} <input name="use_template" onchange="showTemplate(this)" type="checkbox" class="js-switch" {{ $notifi->template_notifi_id ? 'checked' : '' }}  > {{trans('Admin'.DS.'notifi.use_template')}}
              </div>
            </div>

            <div class="form-group" id="template" style='display:none;'>
              <label class="control-label col-md-2 col-sm-2 col-xs-12">Template</label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                <select name="template" id="template_select" class="form-control" onchange="loadTemplate(this)">
                  @if($templates)
                  @foreach($templates as $template)
                  <option value="{{$template->id}}" {{$template->id==$notifi->template_notifi_id?'selected':''}} data-template="{{json_encode($template->toArray())}}">{{$template->name}}</option>
                  @endforeach
                  @endif
                </select>
                <input type="hidden" name="machine_name_template" id="machine_name_template">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12" for="content">{{trans('Admin'.DS.'notifi.content')}} <span class="required">*</span>
              </label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                <textarea id="content" name="content" required="required">{{ $notifi->content }}</textarea>
                @if ($errors->has('content'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('content') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div id="data" style='display:none;'>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12" for="title">Link</label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                <input type="text" id="link" name="link"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('link')?'parsley-error':''}}"
                       value="{{ $notifi->link }}">
                @if ($errors->has('link'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('link') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12"></label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                {{trans('Admin'.DS.'notifi.send_now')}} <input onchange="showSchedule(this)" type="checkbox" class="js-switch" {{ $notifi->schedule == 1 ? 'checked' : '' }} name="schedule"> {{trans('Admin'.DS.'notifi.send_schedule')}}
              </div>
            </div>

            <div class="form-group" id="schedule" style='display:none;'>
              <label class="control-label col-md-2 col-sm-2 col-xs-12" for="from">{{trans('Admin'.DS.'notifi.from_date')}} <span
                  class="required">*</span>
              </label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="text" id="from" name="from"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('from')?'parsley-error':''}}"
                       value="{{ date('d-m-Y H:i',strtotime($notifi->start)) }}" placeholder="{{trans('global.from')}}"/>
                @if ($errors->has('from') || $errors->has('to'))
                  <ul class="parsley-errors-list filled">
                    <li
                      class="parsley-required">{{ $errors->first('from') ? $errors->first('from') : $errors->first('to') }}</li>
                  </ul>
                @endif
              </div>

              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="text" id="to" name="to"
                       class="form-control col-md-7 col-xs-12  {{$errors->has('to')?'parsley-error':''}}"
                       value="{{ date('d-m-Y H:i',strtotime($notifi->end)) }}" placeholder="{{trans('global.to')}}">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12"></label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                {{trans('Admin'.DS.'notifi.send_everyone')}} <input onchange="showClient(this)" type="checkbox" class="js-switch" {{ $notifi->is_everyone == 0 ? 'checked' : '' }} name="everyone"> {{trans('Admin'.DS.'notifi.send_client')}}
              </div>
            </div>

            <div id="client" style="display:none;">
            </div>

            <div class="form-group">
              <label class="control-label col-md-2 col-sm-2 col-xs-12"></label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                {{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ $notifi->active == 1 ? 'checked' : '' }} name="active"> {{trans('global.active')}}
              </div>
            </div>

            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'notifi.update_notifi')}}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

@section('JS')
  @ckeditor('content', ['height' => 400])
  <script type="text/javascript">
    var old_data = '{!! $notifi->data?$notifi->data:'' !!}';
    function showTemplate(obj){
      var check = $(obj).is(':checked');
      $("#template").toggle();
      if(check){
        $("#template_select").trigger("change");
      }else{
        CKEDITOR.instances['content'].setData('');
        $("#data").html('');
        $("#data").hide();
      }
      CKEDITOR.instances['content'].setReadOnly(check);
    }

    function loadTemplate(obj){
      var id_template = $(obj).val();
      var data = JSON.parse($(obj).find('option[value='+id_template+']').attr('data-template'));
      var content = data.content?data.content:'';
      var machine_name = data.machine_name?data.machine_name:'';
      $("#machine_name_template").val(machine_name);
      CKEDITOR.instances['content'].setData(content);
      addInput(content);
    }

    function addInput(content){
      content = jQuery(content).text();
      var regex = /:[0-9a-zA-Z\_]*/g;
      var match = [];
      match = content.match(regex);
      var html = '';
      $("#data").html('');
      if(match.length){
        match.forEach(function(value,key){
          key_data = value.replace(':','');
          html+='<div class="form-group">';
          html+=    '<label class="control-label col-md-2 col-sm-2 col-xs-12" for="'+key_data+'">'+key_data.toUpperCase()+' <span class="required">*</span></label>';
          html+=    '<div class="col-md-8 col-sm-8 col-xs-12">';
          if(old_data[key_data]){
             html+=      '<input type="text" value="'+old_data[key_data]+'" id="'+key_data+'" name="data['+key_data+']" class="form-control col-md-7 col-xs-12 value="" >';
          }else{
             html+=      '<input type="text" id="'+key_data+'" name="data['+key_data+']" class="form-control col-md-7 col-xs-12 value="" >';
          }
          html+=    '</div>';
          html+=  '</div>';
        })
      }
      $("#data").html(html);
      $("#data").show();
    }

    function showSchedule(obj){
      var check = $(obj).is(':checked');
      $("#schedule").toggle();
      if(check){
        $("#from").attr('required','required');
        $("#to").attr('required','required');
      }else{
        $("#from").removeAttr('required');
        $("#to").removeAttr('required');
      }
    }

    function showClient(obj){
      var check = $(obj).is(':checked');
      $("#client").toggle();
    }

  $(function(){
    $('#from').datetimepicker({
      format: 'DD-MM-Y HH:mm',
      // inline: true,
      sideBySide: true
    });
    $('#to').datetimepicker({
      format: 'DD-MM-Y HH:mm',
      // inline: true,
      sideBySide: true
    });
    $('.imageupload').imageupload({
      allowedFormats: [ "jpg", "jpeg", "png", "gif", "svg" , "bmp"],
      previewWidth: 150,
      previewHeight: 150,
      maxFileSizeKb: 2048
    });
    CKEDITOR.editorConfig = function( config ) {
      config.removePlugins = 'image,save,newpage,preview,print,templates,pastefromword,language,tableresize,liststyle,tabletools,scayt,menubutton,contextmenu,flash';
    };
    var html = '<img src="{{$notifi->image?$notifi->image:'' }}" alt="Image preview" class="thumbnail" style="max-width: 250px; max-height: 250px">';
    $('.file-tab').prepend(html)
    setTimeout(function(){
      @if($notifi->template_notifi_id)
        $("input[name='use_template'").trigger("change");
        $("input[name='template'").trigger("change");
      @endif
      @if($notifi->schedule)
        $("input[name='schedule'").trigger("change");
      @endif
      @if($notifi->is_everyone==0)
        $("input[name='everyone'").trigger("change");
      @endif
      a = 1;
    }, 500);
  });
  </script>
  @endsection
@endsection

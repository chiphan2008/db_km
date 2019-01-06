@extends('Admin..layout_admin.master_admin')

@section('content')

  <div class="col-md-12 col-sm-12 col-xs-12">
    @if(session('successInsert'))
      <div class="alert alert-success alert-dismissible fade in" style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        {!! session('successInsert') !!}
      </div>
    @endif
    @if(session('errorInsert'))
      <div class="alert alert-danger alert-dismissible fade in" style="color: #a94442;background-color: #f2dede;border-color: #ebccd1;" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        {!! session('errorInsert') !!}
      </div>
    @endif

    <div class="x_panel">
      <div class="x_title">
        <h2>Insert Content From Site {{ucfirst($site)}}</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <form class="form-horizontal form-label-left" onsubmit="checkIsExcel('{{$site}}')" action="{{route('insert_content', ['site' => $site])}}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
          {{ csrf_field() }}

          @if($site == 'vietbando' || $site == 'mytour')
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="link">Link</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input class="form-control col-md-7 col-xs-12" type="url" name="link" required autocomplete="off"/>
              </div>
            </div>

            @if($site == 'mytour')
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="link">Start Page</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <input class="form-control col-md-7 col-xs-12" type="number" name="start_page" value="1" min="1"/>
                </div>
              </div>
            @endif

            @if($site == 'vietbando')
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content_type">Category</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <select class="form-control" name="id_category" id="id_category" onchange="getCategoryAjax(this.value,'category_item')" >
                    <option value="">-- {{trans('Admin'.DS.'content.category')}} --</option>
                    @foreach($list_category as $value => $name)
                      <option value="{{$value}}">{{$name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-group" id="category_item_vietbando" style="display: none" disabled="disabled">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content_type">Category Item</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <select class="form-control" name="category_item" id="category_item">
                    <option value="">-- {{trans('Admin'.DS.'content.cat_item')}} --</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Image</label>
                <div class="col-md-5 col-sm-5 col-xs-12">
                  <div class="imgupload panel panel-default">
                    <div class="panel-heading clearfix">
                      <h3 class="panel-title pull-left">Upload image</h3>
                    </div>
                    <div class="file-tab panel-body">
                      <div>
                        <a type="button" class="btn btn-default btn-file">
                          <span>Browse</span>
                          <input type="file" name="avatar" id="avatar">
                        </a>
                        <button type="button" class="btn btn-default">Remove</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endif
          @else
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fileExcel"></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="file" name="fileExcel" id="fileExcel" accept=".csv,.xls,.xlsx">
              </div>
            </div>
          @endif

          <input type="hidden" name="site" value="{{$site}}">
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="moderation">{{trans('Admin'.DS.'content.moderation')}}
            </label>
            <div class="col-md-3 col-sm-3 col-xs-12">
              <select class="form-control" name="moderation" id="moderation">
                <option value="request_publish">{{trans('Admin'.DS.'content.request_publish')}}</option>
                <option value="publish">{{trans('Admin'.DS.'content.publish')}}</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'content.date')}}</label>
            <div class="col-md-3 col-sm-3 col-xs-12">
              <div class='input-group date' style="margin-bottom: 0px" id='date_created'>
                <input type='text' class="form-control" name="date_created" />
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
              </span>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <button type="submit" class="btn btn-success">Upload</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>

@endsection
@section('JS')
  <script>
    var base_url = {!! json_encode(url('/')) !!};

    function checkIsExcel(site,type) {
      if(site != 'vietbando' && site != 'mytour')
      {
        var file = $('#fileExcel').val();
        if ((!/.*\.xlsx$/.test(file)) && (!/.*\.xls$/.test(file)) && (!/.*\.csv$/.test(file))) {
          alert("Please upload file excel");
          event.preventDefault();
          return false;
        }
        var file_upload = $('#fileExcel').get(0).files[0];
        if(file_upload.size > (1024*1024*8)){
          alert("File size must be less than 8MB");
          event.preventDefault();
          return false;
        }
      }
      return true;

    }

    function getCategoryAjax(value,type)
    {
      var CSRF_TOKEN = $('input[name="_token"]').val();

      $.ajax({
        type: "POST",
        data: {value: value, _token: CSRF_TOKEN},
        url: base_url + '/admin/content/ajaxCategoryItem',
        success: function (data) {

          if(data == 'err')
          {
            $('#category_item_vietbando').hide().attr( "disabled", "disabled" );
          }
          else{
            $('#category_item_vietbando').show().removeAttr("disabled");
            $("#" + type).html(data);
          }
        }
      })
    }

    $(function () {
      $('.imgupload').imageupload({
        allowedFormats: [ "jpg", "jpeg", "png", "gif", "svg" , "bmp"],
        previewWidth: 250,
        previewHeight: 250,
        maxFileSizeKb: 2048
      });

      $('#date_created').datetimepicker({
        format: 'YYYY-MM-DD HH:mm'
      });
    });
  </script>
@endsection

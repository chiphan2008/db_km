@extends('Admin..layout_admin.master_admin')
@section('content')
  <form id="#demo-form22" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" method="post"
        action="{{route('save_setting')}}" enctype="multipart/form-data">

    {{ csrf_field() }}

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Information </h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content" style="display: block;">
            <br>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="site_name">Site Name
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" id="site_name" name="site_name" class="form-control col-md-7 col-xs-12"
                       value="{{isset($list_setting['site_name'])?$list_setting['site_name']:''}}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="favicon">Favicon</label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <div class="imgupload panel panel-default">
                  <div class="panel-heading clearfix">
                    <h3 class="panel-title pull-left">Upload image</h3>
                  </div>
                  <div class="file-tab panel-body favicon">
                    <div>
                      <a type="button" class="btn btn-default btn-file">
                        <span>Browse</span>
                        <input type="file" name="favicon" id="favicon">
                      </a>
                      <button type="button" class="btn btn-default">Remove</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="logo">Logo</label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <div class="imgupload_logo panel panel-default">
                  <div class="panel-heading clearfix">
                    <h3 class="panel-title pull-left">Upload image</h3>
                  </div>
                  <div class="file-tab panel-body logo">
                    <div>
                      <a type="button" class="btn btn-default btn-file">
                        <span>Browse</span>
                        <input type="file" name="logo" id="logo">
                      </a>
                      <button type="button" class="btn btn-default">Remove</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="site_description">Site Description
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <textarea rows="5" type="text" id="site_description" name="site_description" class="form-control col-md-7 col-xs-12">{{isset($list_setting['site_description'])?$list_setting['site_description']:''}}</textarea> 
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Setting Google API </h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content" style="display: block;">
            <br>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="client_id_google">Client Id
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" id="client_id_google" name="client_id_google" class="form-control col-md-7 col-xs-12"
                       value="{{isset($list_setting['client_id_google'])?$list_setting['client_id_google']:''}}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="client_secret_google">Client Secret
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" id="client_secret_google" name="client_secret_google"
                       class="form-control col-md-7 col-xs-12" value="{{isset($list_setting['client_secret_google'])?$list_setting['client_secret_google']:''}}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="redirect_google">Redirect Callback
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" id="redirect_google" name="redirect_google"
                       class="form-control col-md-7 col-xs-12" value="{{isset($list_setting['redirect_google'])?$list_setting['redirect_google']:''}}">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Setting FaceBook API </h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content" style="display: block;">
            <br>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="client_id_facebook">Client Id
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" id="client_id_facebook" name="client_id_facebook"
                       class="form-control col-md-7 col-xs-12" value="{{isset($list_setting['client_id_facebook'])?$list_setting['client_id_facebook']:''}}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="client_secret_facebook">Client Secret
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" id="client_secret_facebook" name="client_secret_facebook"
                       class="form-control col-md-7 col-xs-12" value="{{isset($list_setting['client_secret_facebook'])?$list_setting['client_secret_facebook']:''}}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="redirect_facebook">Redirect Callback
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" id="redirect_facebook" name="redirect_facebook"
                       class="form-control col-md-7 col-xs-12" value="{{isset($list_setting['redirect_facebook'])?$list_setting['redirect_facebook']:''}}">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Setting Mail </h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content" style="display: block;">
            <br>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mail_driver">Mail Driver
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" id="mail_driver" name="mail_driver" class="form-control col-md-7 col-xs-12"
                       value="{{isset($list_setting['mail_driver'])?$list_setting['mail_driver']:''}}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mail_host">Mail Host
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" id="mail_host" name="mail_host" class="form-control col-md-7 col-xs-12"
                       value="{{isset($list_setting['mail_host'])?$list_setting['mail_host']:''}}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mail_port">Mail Port
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" id="mail_port" name="mail_port" class="form-control col-md-7 col-xs-12"
                       value="{{isset($list_setting['mail_port'])?$list_setting['mail_port']:''}}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mail_username">Mail UserName
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" id="mail_username" name="mail_username" class="form-control col-md-7 col-xs-12"
                       value="{{isset($list_setting['mail_username'])?$list_setting['mail_username']:''}}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mail_password">Mail Password
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" id="mail_password" name="mail_password" class="form-control col-md-7 col-xs-12"
                       value="{{isset($list_setting['mail_password'])?$list_setting['mail_password']:''}}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mail_encryption">Mail Encryption
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" id="mail_encryption" name="mail_encryption" class="form-control col-md-7 col-xs-12"
                       value="{{isset($list_setting['mail_encryption'])?$list_setting['mail_encryption']:''}}">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Script Google Analytics </h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content" style="display: block;">
            <br>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="google_analytics">Script
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <textarea id="note" class="form-control" name="google_analytics" rows="7" maxlength="1000">{{isset($list_setting['google_analytics'])?$list_setting['google_analytics']:''}}</textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Setting Information Site </h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content" style="display: block;">
            <br>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mail_driver">Address
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" id="mail_driver" name="info_address" class="form-control col-md-7 col-xs-12"
                       value="{{isset($list_setting['info_address'])?$list_setting['info_address']:''}}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="info_phone">Phone
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" id="info_phone" name="info_phone" class="form-control col-md-7 col-xs-12"
                       value="{{isset($list_setting['info_phone'])?$list_setting['info_phone']:''}}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="info_mail">Mail
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" id="info_mail" name="info_mail" class="form-control col-md-7 col-xs-12"
                       value="{{isset($list_setting['info_mail'])?$list_setting['info_mail']:''}}">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="info_description">Description
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <textarea rows="7" maxlength="1000" id="info_description" name="info_description" class="form-control col-md-7 col-xs-12">{{isset($list_setting['info_description'])?$list_setting['info_description']:''}}</textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Slogan Site </h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content" style="display: block;">
            <br>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="LOCATION_SLOGAN">Location
              </label>
              <div class="col-md-7 col-sm-7 col-xs-12">
                <input type="text" id="LOCATION_SLOGAN" name="LOCATION_SLOGAN" class="form-control col-md-7 col-xs-12"
                       value="{{isset($list_setting['LOCATION_SLOGAN'])?$list_setting['LOCATION_SLOGAN']:''}}">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @if(Auth::guard('web')->user()->can('edit_Setting'))
    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <button type="submit" class="btn btn-success">Add Setting</button>
      </div>
    </div>
    @endif
  </form>

@endsection

@section('JS')
  <script type="text/javascript">
    $(function () {
      $('.imgupload').imageupload({
        allowedFormats: ["ico"],
        previewWidth: 250,
        previewHeight: 250,
        maxFileSizeKb: 2048
      });

      $('.imgupload_logo').imageupload({
        allowedFormats: ["jpg", "jpeg", "png", "gif"],
        previewWidth: 250,
        previewHeight: 250,
        maxFileSizeKb: 2048
      });

      var favicon = '<img src="{{isset($list_setting['favicon'])?$list_setting['favicon']:'' }}" alt="Image preview" class="thumbnail" style="max-width: 250px; max-height: 250px">';
      $('.favicon').prepend(favicon);

      var logo = '<img src="{{isset($list_setting['logo'])?$list_setting['logo']:'' }}" alt="Image preview" class="thumbnail" style="max-width: 250px; max-height: 250px">';
      $('.logo').prepend(logo);
    })
  </script>
@endsection

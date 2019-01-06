<div class="modal fade modal-procces cread-location" id="modal-new-location" tabindex="-1" role="dialog" aria-labelledby="modal-food" data-backdrop="static">
  <a class="back-step" href="" onclick="return false;">
    <i class="icon-left"></i>  {{trans('Location'.DS.'layout.back')}}</a>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  <div class="modal-dialog km-modal-lg" role="document">
    <div class="modal-header">
      <div class="process-create-header">
        <div class="logo text-center"><img src="/frontend/assets/img/logo/logo-large.svg" alt=""></div>
        <ul class="nav nav-tabs bs-wizard" role="tablist" style="border-bottom:0;">
          <li class="nav-item col-4 bs-wizard-step highlight">
            <div class="progress">
              <div class="progress-bar"></div>
            </div>
            <a class="nav-link bs-wizard-dot" data-toggle="tab" role="tab"></a>
            <div class="bs-wizard-info text-center">{{trans('Location'.DS.'layout.category')}}</div>
          </li>
          <li class="nav-item col-4 bs-wizard-step disabled">
            <div class="progress">
              <div class="progress-bar"></div>
            </div>
            <a class="nav-link bs-wizard-dot" data-toggle="tab" role="tab"></a>
            <div class="bs-wizard-info text-center">{{trans('Location'.DS.'layout.information')}}</div>
          </li>
          <li class="nav-item col-4 bs-wizard-step disabled">
            <div class="progress">
              <div class="progress-bar"></div>
            </div>
            <a class="nav-link bs-wizard-dot" data-toggle="tab" role="tab"></a>
            <div class="bs-wizard-info text-center">{{trans('Location'.DS.'layout.image')}}</div>
          </li>
        </ul>
      </div>
    </div>
    <div class="modal-content">
      <!-- modal-header -->
      <div class="process-create-content">
        <form id="form-creat-location" action="{{url('createLocationFrontend/postCreateLocation')}}" class="form-creat-location" enctype="multipart/form-data">
          <div class="tab-content">

            <div class="tab-pane active" id="step1" role="tabpanel">
              <h4>{{trans('Location'.DS.'layout.information')}}</h4>
              <fieldset class="form-group col-md-12">
                <label class="mb-3">{{trans('Location'.DS.'layout.category')}} <span style="color: #d9534f">*</span></label>
                <div class="bg-gray-light px-4 pt-4 pb-0">
                  <ul class="list-checkbox-location row list-unstyled ">
                    @foreach($category as $value)
                      <li class="form-group  col-md-4">
                        <label class="custom-control custom-radio">
                          <input type="radio" name="id_category" value="{{$value->id}}" class="custom-control-input">
                          <span class="custom-control-indicator"></span>
                          <span class="custom-control-description">{{$value->name}}</span>
                        </label>
                      </li>
                    @endforeach
                  </ul>
                  <div id="err_id_category" style="color: #d9534f;display: none">{{trans('Location'.DS.'layout.validate_category')}}</div>
                </div>
              </fieldset>
              <div class="btn-step w-100 d-flex aline justify-content-center">
                <button type="button" id="button_step2" class="next-step btn btn-primary btn-lg">{{trans('Location'.DS.'layout.button_step2')}}</button>
              </div>
            </div>

            <div class="tab-pane" id="step2" role="tabpanel">
              <div class="row">
                <fieldset class="form-group col-md-12">
                  {{trans('Location'.DS.'layout.category')}}: <span id="label_name_category"></span>
                </fieldset>
                <fieldset id="fieldset_feedback_name" class="form-group col-md-6">
                  <label>{{trans('Location'.DS.'layout.name_location')}} <span style="color: #d9534f">*</span></label>
                  <input type="text" name="name" id="name" class="form-control input-md-6" autocomplete="off" required>
                  <div class="form-control-feedback" id="feedback_name" style="color: #d9534f;display: none"></div>
                </fieldset>
                <!-- <fieldset class="form-group col-md-6" id="list_group"></fieldset> -->

                <div class="col-md-12">
                  <label>{{trans('Location'.DS.'layout.type_category')}} <span style="color: #d9534f">*</span></label>
                  <div class="form-control-feedback" id="feedback_category_item" style="color: #d9534f;display: none"></div>
                  <div class="list-cate-child bg-gray-light px-4 pt-4 pb-0" id="cate-1">
                    <ul class="row list-unstyled" id="list_category_item">
                    </ul>
                  </div>
                </div>

                <fieldset id="fieldset_feedback_country" class="form-group col-md-4">
                  <label>{{trans('Location'.DS.'layout.country')}} <span style="color: #d9534f">*</span></label>
                  <select class="custom-select form-control" name="country" id="country" onchange="getLocationAjax(this.value,'city')" required>
                    <option value="">-- Country --</option>
                    @foreach($country as $key => $name)
                      <option value="{{$key}}">{{$name}}</option>
                    @endforeach
                  </select>
                  <div class="form-control-feedback" id="feedback_country" style="color: #d9534f;display: none"></div>
                </fieldset>
                <fieldset class="form-group col-md-4" id="fieldset_feedback_city">
                  <label>{{trans('Location'.DS.'layout.city')}} <span style="color: #d9534f">*</span></label>
                  <select class="custom-select form-control" name="city" id="city" onchange="getLocationAjax(this.value,'district')" required>
                    <option value="">-- City --</option>
                  </select>
                  <div class="form-control-feedback" id="feedback_city" style="color: #d9534f;display: none"></div>
                </fieldset>
                <fieldset class="form-group col-md-4" id="fieldset_feedback_district">
                  <label>{{trans('Location'.DS.'layout.district')}} <span style="color: #d9534f">*</span></label>
                  <select class="custom-select form-control"  name="district" id="district" required>
                    <option value="">-- District --</option>
                  </select>
                  <div class="form-control-feedback" id="feedback_district" style="color: #d9534f;display: none"></div>
                </fieldset>


                <fieldset class="form-group col-md-12" id="fieldset_feedback_address">
                  <label>{{trans('Location'.DS.'layout.address')}} <span style="color: #d9534f">*  ({{trans('Location'.DS.'layout.note_add_create')}})</span> </label>
                  <input type="text" id="address" name="address" class="form-control input-md-6" required>
                  <div class="form-control-feedback" id="feedback_address" style="color: #d9534f; display: none"></div>
                </fieldset>

                <input type="hidden" value="" name="alias" id="alias">
                <input type="hidden" value="" name="lat" id="lat">
                <input type="hidden" value="" name="lng" id="lng">
                <fieldset class="form-group col-md-12">
                  <div id="google_map" style="height: 300px; display: none"></div>
                </fieldset>

                <fieldset class="form-group col-md-12 box-hastag">
                  <label>{{trans('Location'.DS.'layout.tag')}} <span style="color: #d9534f">  ({{trans('Location'.DS.'layout.note_add_tags_create')}})</span></label>
                  <select class="tokeHastag" multiple="true" name="tag[]"></select>
                </fieldset>
                <div class="form-group col-md-12">
                  <label>{{trans('Location'.DS.'layout.description')}}</label>
                  <textarea class="form-control" name="description" rows="5" style="height:auto;"></textarea>
                </div>

                <!-- <fieldset class="form-group col-md-6" id="fieldset_feedback_email">
                  <label>Email</label>
                  <input type="email" name="email" class="form-control" autocomplete="off">
                  <div class="form-control-feedback" id="feedback_email" style="color: #d9534f;display: none"></div>
                </fieldset> -->
                <fieldset class="form-group col-md-6">
                  <label>{{trans('Location'.DS.'layout.phone')}}</label>
                  <input type="text" name="phone" id="phone" class="form-control" autocomplete="off" placeholder="(099)-9999-9999">
                </fieldset>
                <fieldset class="form-group col-md-3" id="fieldset_feedback_open_from">
                  <label>{{trans('Location'.DS.'layout.open_from')}} <span style="color: #d9534f">*</span></label>
                  <input type="time" name="open_from" id="open_from" class="form-control" autocomplete="off">
                  <div class="form-control-feedback" id="feedback_open_from" style="color: #d9534f;display: none"></div>
                </fieldset>
                <fieldset class="form-group col-md-3" id="fieldset_feedback_open_to">
                  <label>{{trans('Location'.DS.'layout.open_to')}} <span style="color: #d9534f">*</span></label>
                  <input type="time" name="open_to" id="open_to" class="form-control" autocomplete="off">
                  <div class="form-control-feedback" id="feedback_open_to" style="color: #d9534f;display: none"></div>
                </fieldset>
                <!-- <fieldset class="form-group col-md-6"></fieldset> -->
                <fieldset class="form-group col-md-3 price_location" id="fieldset_feedback_price_from">
                  <label>{{trans('Location'.DS.'layout.price_from')}} <span style="color: #d9534f">*</span></label>
                  <input type="number" name="price_from" id="price_from" min="1" class="form-control" autocomplete="off">
                  <div class="form-control-feedback" id="feedback_price_from" style="color: #d9534f;display: none"></div>
                </fieldset>
                <fieldset class="form-group col-md-3 price_location" id="fieldset_feedback_price_to">
                  <label>{{trans('Location'.DS.'layout.price_to')}} <span style="color: #d9534f">*</span></label>
                  <input type="number" name="price_to" id="price_to" min="1" class="form-control" autocomplete="off">
                  <div class="form-control-feedback" id="feedback_price_to" style="color: #d9534f;display: none"></div>
                </fieldset>
                <fieldset class="form-group col-md-3 price_location">
                  <label>{{trans('Location'.DS.'layout.currency')}}</label>
                  <select class="custom-select form-control" name="currency">
                    <option value="VND"> VND </option>
                    <option value="USD"> USD </option>
                  </select>
                </fieldset>

                <fieldset class="box-upload-avata form-group col-md-4" id="avatar_create">
                  <label>Upload Avata <span style="color: #d9534f">*</span></label>
                  <div class="upload-avata box-img-upload" >
                    <div class="box-img-upload-content">
                      <i class="icon-new-white"></i>
                      <p>Choose Image</p>
                    </div>
                  </div>
                  <input id="file-upload-avata" type="file" name="avatar" accept=".png, .jpg, .jpeg">
                  <div class="form-control-feedback" id="feedback_avatar" style="color: #d9534f; display: none"></div>
                </fieldset>
              </div>
              <div class="row" id="list_service">
                <div class="col-md-12">
                  <h4>{{trans('Location'.DS.'layout.service')}}</h4>
                  <div class="list-utilities">
                    <ul class="row list-unstyled">
                    </ul>
                  </div>
                </div>
              </div>
              <div class="btn-step w-100 d-flex aline justify-content-center">
                <button type="button" class="prev-step btn  btn-outline-primary btn-lg">{{trans('Location'.DS.'layout.button_step1')}}</button>
                <button type="button" id="button_step3" class="next-step btn btn-primary btn-lg">{{trans('Location'.DS.'layout.button_step3')}}</button>
              </div>
            </div>

            <div class="tab-pane tab-upload-image" id="step3" role="tabpanel">
              <!-- Nav tabs -->
              <ul class="nav tab-upload-image-nav" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" data-toggle="tab" href="#khong-gian" role="tab">Space</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#menu" role="tab">Menu</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#video" role="tab">Video</a>
                </li>
              </ul>

              <!-- Tab panes -->
              <div class="tab-content">
                <div class="tab-pane active" id="khong-gian" role="tabpanel">
                  <div class="upload-placeholder">
                    <div class="upload-img-post" id="image_khong_gian_create">
                      <ul class="list-unstyled row">
                        <li class="col-md-4 col-6">
                          <div class="box-img-upload upload-begin upload-image-disabled">
                            <div class="box-img-upload-content">
                              <i class="icon-new-white"></i>
                              <p>Choose Image</p>
                            </div>
                          </div>
                        </li>
                      </ul>
                      <input id="file-upload" type="file" name="image_space[]" multiple="" accept=".png, .jpg, .jpeg">
                    </div>
                  </div>
                </div>
                <div class="tab-pane" id="menu" role="tabpanel">
                  <div class="upload-placeholder">
                    <div class="upload-img-post" id="image_menu_create">
                      <ul class="list-unstyled row">
                        <li class="col-md-4 col-6">
                          <div class="box-img-upload upload-begin upload-image-disabled">
                            <div class="box-img-upload-content">
                              <i class="icon-new-white"></i>
                              <p>Choose Image</p>
                            </div>
                          </div>
                        </li>
                      </ul>
                      <input id="file-upload1" type="file" name="image_menu[]" multiple="" accept=".png, .jpg, .jpeg">
                    </div>
                  </div>
                </div>
                <div class="tab-pane" id="video" role="tabpanel">
                  <fieldset class="form-group">
                    <label>Upload Video</label>
                    <input type="text" name="link[]" class="form-control" placeholder="https://www.youtube.com....">
                  </fieldset>
                  <!-- end form group -->
                  <button class="btn btn-primary btn-addvideo"><i class="icon-new-white"></i> {{trans('Location'.DS.'layout.add_link')}}</button>
                </div>
              </div>

              <fieldset class="form-group col-md-12">
                <label>Captcha <span style="color: #d9534f">*</span></label>
                <div class="col-md-6" style="float:left;">
                  <canvas id="canvas_captcha" width="200" height="75" style="display:inline-block;border:1px solid #ddd;"></canvas>
                  <button class="btn btn-xs" title="Renew captcha code" onclick="createCaptcha()" type="button" style="display:inline-block;margin-top: -35px;"><i
                      class="fa fa-refresh"></i></button>
                </div>
                <div class="col-md-6"  style="float:left;">
                  <input type="text" placeholder="{{trans('valid.captcha_code')}}" name="captcha_code" id="captcha_code"
                         class="form-control required_field" maxlength="5">
                  <span class="error_captcha text-danger col-xs-12"></span>
                </div>
              </fieldset>

              <div class="btn-step w-100 d-sm-flex aline justify-content-sm-center">
                <button type="button" class="prev-step btn  btn  btn-outline-primary  btn-lg">Step 2</button>
                <button type="button" id="preview_location_fe" class="preview-step btn  btn  btn-outline-primary  btn-lg">Preview</button>
                <button type="button" id="create_location_fe" class="creat-step btn btn-primary btn-lg">{{trans('Location'.DS.'layout.create')}}</button>

              </div>
            </div>

          </div>
        </form>
      </div>
      <!-- modal process create header -->
    </div>
  </div>
</div>

<style>
  .pac-container{
    z-index: 1060 !important;
  }
</style>

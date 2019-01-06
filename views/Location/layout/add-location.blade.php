<div class="modal  modal-procces cread-location" id="modal-new-location" tabindex="-1" role="dialog" aria-labelledby="modal-food" data-backdrop="static">
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
        <form id="form-creat-location" action="{{url('createLocationFrontend/postCreateLocation')}}" class="form-creat-location"  enctype="multipart/form-data" method="POST">
          <div class="tab-content">
            
            <div class="tab-pane active" id="step1" role="tabpanel">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
                          <span class="custom-control-description">@lang($value->name)</span>
                        </label>
                      </li>
                    @endforeach
                  </ul>
                  <div id="err_id_category" style="color: #d9534f;display: none">{{trans('Location'.DS.'layout.validate_category')}}</div>
                  <div class="box-location-add-offer col-md-12">
                      <a class="text-right d-block mb-2" href="#" onclick="$('#suggest_category').toggle()">
                          <i class="demo-icon icon-new-white mr-2"></i>
                          {{trans('Location'.DS.'layout.add_category')}}
                      </a>
                  </div>
                  <div class="form-add-offer-location w-100" id="suggest_category" style="display: none;">
                      <div class=" form-inline row pb-4">
                          <div class="form-group col-sm-9 mb-0">
                              <input type="text"  id="category_input" class="form-control w-100" autocomplete="off">
                          </div>
                          <div class="col-sm-3">
                              <button type="button" onclick="addCategory()" class="btn-add-offer-location btn btn-primary w-100"><i class="icon-new-white"></i></button>
                          </div>
                      </div>
                  </div>
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
                  <input onchange="searchCreateContent();" type="text" name="name" id="name" data-history="0" class="form-control input-md-6" autocomplete="off" required>
                  <div class="form-control-feedback" id="feedback_name" style="color: #d9534f;display: none"></div>
                </fieldset>
                <div class="col-md-12 mb-2">
                    <ul class="list-unstyled" id="list_created">
                      
                    </ul>
                  </div>
                <!-- <fieldset class="form-group col-md-6" id="list_group"></fieldset> -->

                <div class="col-md-12" id="fieldset_feedback_category_item">
                  <label>{{trans('Location'.DS.'layout.type_category')}} <span id="category_item_require" style="color: #d9534f">*</span></label>
                  <div class="form-control-feedback" id="feedback_category_item" style="color: #d9534f;display: none"></div>
                  <div class="list-cate-child bg-gray-light px-4 pt-4 pb-0" id="cate-1">
                    <ul class="row list-unstyled" id="list_category_item">
                    </ul>
                    <div class="form-add-offer-location w-100" id="suggest_category_item" style="display: none;">
                        <div class=" form-inline row pb-4">
                            <div class="form-group col-sm-9 mb-0">
                                <input type="text" id="category_item_input" class="form-control w-100" autocomplete="off">
                            </div>
                            <div class="col-sm-3">
                                <button type="button" onclick="addCategoryItem()" class="btn-add-offer-location btn btn-primary w-100"><i class="icon-new-white"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="box-location-add-offer col-md-12">
                        <a class="text-right d-block mb-2" href="#"  onclick="$('#suggest_category_item').toggle()">
                            <i class="demo-icon icon-new-white mr-2"></i>
                            {{trans('Location'.DS.'layout.add_type_category')}}
                        </a>
                    </div>
                  </div>
                </div>
                

                <fieldset id="fieldset_feedback_country" class="form-group col-md-4">
                  <label>{{trans('Location'.DS.'layout.country')}} <span style="color: #d9534f">*</span></label>
                  <select class="custom-select form-control" name="country" id="country" onchange="getLocationAjax(this.value,'city')" required>
                    <option value="">-- {{trans('Location'.DS.'layout.country')}} --</option>
                    @foreach($country as $key => $name)
                      <option value="{{$key}}">{{$name}}</option>
                    @endforeach
                  </select>
                  <div class="form-control-feedback" id="feedback_country" style="color: #d9534f;display: none"></div>
                </fieldset>
                <fieldset class="form-group col-md-4" id="fieldset_feedback_city">
                  <label>{{trans('Location'.DS.'layout.city')}} <span style="color: #d9534f">*</span></label>
                  <select class="custom-select form-control" name="city" id="city" onchange="getLocationAjax(this.value,'district')" required>
                    <option value="">-- {{trans('Location'.DS.'layout.city')}} --</option>
                  </select>
                  <div class="form-control-feedback" id="feedback_city" style="color: #d9534f;display: none"></div>
                </fieldset>
                <fieldset class="form-group col-md-4" id="fieldset_feedback_district">
                  <label>{{trans('Location'.DS.'layout.district')}} <span style="color: #d9534f">*</span></label>
                  <select class="custom-select form-control"  name="district" id="district" required>
                    <option value="">-- {{trans('Location'.DS.'layout.district')}} --</option>
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
                  <label>{!! trans('Location'.DS.'layout.place_tags') !!}
                  <span style="color: #d9534f">  ({{trans('Location'.DS.'layout.note_add_tags_create')}})</span></label>
                  <!-- <select class="tokeHastag" multiple="true" name="tag[]"></select> -->
                  <input type="text" name="tag[]" class="tokeHastag form-control" style="min-width: 100%;"/>
                </fieldset>
                <div class="form-group col-md-12">
                  <label>{{trans('Location'.DS.'layout.description')}}</label>
                  <textarea class="form-control" name="description" rows="5" style="height:auto;"></textarea>
                </div>

                <!-- <fieldset class="form-group col-md-6" id="fieldset_feedback_email">
                  <label>Email</label>
                  <input type="text" name="email" class="form-control" autocomplete="off">
                  <div class="form-control-feedback" id="feedback_email" style="color: #d9534f;display: none"></div>
                </fieldset> -->
                <fieldset class="form-group col-md-6" style="display:none;">
                  <label>{{trans('Location'.DS.'layout.phone')}}</label>
                  <input type="text" name="phone" id="phone" class="form-control" autocomplete="off" placeholder="(099)-9999-9999">
                </fieldset>
                <fieldset class="form-group col-md-3" id="fieldset_feedback_open_from">
                  <label>{{trans('Location'.DS.'layout.open_from')}} <span style="color: #d9534f">*</span></label>
                  <input type="time" name="open_from" id="open_from" value="08:00:00" class="form-control" autocomplete="off">
                  <div class="form-control-feedback" id="feedback_open_from" style="color: #d9534f;display: none"></div>
                </fieldset>
                <fieldset class="form-group col-md-3" id="fieldset_feedback_open_to">
                  <label>{{trans('Location'.DS.'layout.open_to')}} <span style="color: #d9534f">*</span></label>
                  <input type="time" name="open_to" id="open_to" value="22:00:00" class="form-control" autocomplete="off">
                  <div class="form-control-feedback" id="feedback_open_to" style="color: #d9534f;display: none"></div>
                </fieldset>
                <!-- <fieldset class="form-group col-md-6"></fieldset> -->
                <fieldset class="form-group col-md-3 price_location" id="fieldset_feedback_price_from">
                  <label>{{trans('Location'.DS.'layout.price_from')}} <span style="color: #d9534f">*</span></label>
                  <input type="number" name="price_from" value="1" id="price_from" min="1" class="form-control" autocomplete="off">
                  <div class="form-control-feedback" id="feedback_price_from" style="color: #d9534f;display: none"></div>
                </fieldset>
                <fieldset class="form-group col-md-3 price_location" id="fieldset_feedback_price_to">
                  <label>{{trans('Location'.DS.'layout.price_to')}} <span style="color: #d9534f">*</span></label>
                  <input type="number" name="price_to" value="2" id="price_to" min="1" class="form-control" autocomplete="off">
                  <div class="form-control-feedback" id="feedback_price_to" style="color: #d9534f;display: none"></div>
                </fieldset>
                <fieldset class="form-group col-md-3 price_location">
                  <label>{{trans('Location'.DS.'layout.currency')}}</label>
                  <select class="custom-select form-control" name="currency">
                    <option value="VND"> VND </option>
                    <option value="USD"> USD </option>
                  </select>
                </fieldset>
                
                <fieldset class="form-group col-md-12" id="product_create">
                  <h4 class="text-center" style="margin-bottom: 25px;">{{trans('Admin'.DS.'content.product')}}</span> <button type="button" class="btn btn-primary" onclick="addGroup()">{{trans('Admin'.DS.'content.add_product_group')}}</button></h4>
                  <div class="col-xs-12" id="list_group_product">
                    @if(old('product'))
                      @foreach(old('product') as $index_group => $group)
                        <div class="group_product" id="group_product_{{$index_group}}" style="">
                          <div class="form-group row align-item-center">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12">
                            {{trans('Admin'.DS.'content.product_group')}}  <span style="color: #d9534f">*</span>
                            </label>
                            <div class="col-md-7 col-sm-7 col-xs-12">
                              <input class="form-control" type="text" name="product[{{$index_group}}][group_name]" value="{{$group['group_name']}}" required="required"/>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12">
                              <a class="remove_custom_open" onclick="removeGroupProduct({{$index_group}})"><i class="fa fa-remove"></i></a>
                            </div>
                          </div>
                          <div class="header_product row">
                            <div class="col-md-4"><label>{{trans('Admin'.DS.'content.name')}}  <span style="color: #d9534f">*</span></label></div>
                            <div class="col-md-4"><label>{{trans('Admin'.DS.'content.price')}}  <span style="color: #d9534f">*</span></label></div>
                            <div class="col-md-4"><label>{{trans('Admin'.DS.'content.image')}}  <span style="color: #d9534f">*</span></label></div>
                          </div>
                          <div id="list_product_{{$index_group}}">
                            @php $count = 0; @endphp
                            @foreach($group as $key => $product)
                            @if($key !== 'group_name')
                            <div class="item_product row">
                              <input type="hidden" name="product[{{$index_group}}][{{$key}}][id]" value="0" placeholder="">
                              <div class="col-xs-4">
                                <input type="text" value="{{$product['name']}}" class="form-control" name="product[{{$index_group}}][{{$key}}][name]" placeholder="Tên" required="required">
                              </div>
                              <div class="col-xs-2">
                                <input type="number" value="{{$product['price']}}" min="0" class="form-control" name="product[{{$index_group}}][{{$key}}][price]" placeholder="Giá" required="required">
                              </div>
                              <div class="col-xs-2">
                                <select class="form-control" name="product[{{$index_group}}][{{$key}}][currency]">
                                  <option value="VND" {{$product['currency']=='VND'?'selected':''}}>VND</option>
                                  <option value="USD" {{$product['currency']=='USD'?'selected':''}}>USD</option>
                                </select>
                              </div>
                              <div class="col-xs-2">

                                <input type="file" class="" value="" name="product[{{$index_group}}][{{$key}}][image]" onchange="readImageProduct(this)" style="width: 88px;" required="required">

                              </div>
                              <div class="col-xs-2 img_product text-center"></div>
                              @if($count>0)
                              <a class="remove_custom_open" onclick="removeProduct(this)"><i class="fa fa-remove"></i></a>
                              @endif
                            </div>
                            @php $count++; @endphp
                            @endif
                            @endforeach
                          </div>
                          <div class="text-center" style="margin-top: 15px;">
                            <button class="btn btn-primary" type="button" onclick="addProduct({{$index_group}})">{{trans('Admin'.DS.'content.add_product')}}</button>
                          </div>
                        </div>
                      @endforeach
                    @endif
                  </div>
                  <div class="form-control-feedback" id="feedback_product" style="color: #d9534f; display: none"></div>
                </fieldset>

                
              </div>
              <div class="row" id="list_service">
                <div class="col-md-12">
                  <h4>{{trans('Location'.DS.'layout.service')}}</h4>
                  <div class="list-utilities bg-gray-light px-4 pt-4 pb-0">
                    <ul class="row list-unstyled" id="list_service_item">
                    </ul>
                    <div class="box-location-add-offer col-md-12">
                        <a class="text-right d-block mb-2" href="#" onclick="$('#suggest_service').toggle()">
                            <i class="demo-icon icon-new-white mr-2"></i>
                            {{trans('Location'.DS.'layout.add_service')}}
                        </a>
                    </div>
                    <div class="form-add-offer-location w-100" id="suggest_service" style="display: none;">
                        <div class=" form-inline row pb-4">
                            <div class="form-group col-sm-9 mb-0">
                                 <input type="text" id="service_input" class="form-control w-100" autocomplete="off">
                            </div>
                            <div class="col-sm-3">
                                <button type="button" onclick="addService()" class="btn-add-offer-location btn btn-primary w-100"><i class="icon-new-white"></i></button>
                            </div>
                        </div>
                    </div>
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
                  <a class="nav-link active" id="avatar_nav" data-toggle="tab" href="#avatar" role="tab">{{trans('global.avatar')}}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#khong-gian" role="tab">{{trans('global.space')}}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#menu" role="tab">{{trans('global.image')}}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" data-toggle="tab" href="#video" role="tab">{{trans('global.video')}}</a>
                </li>
         
              </ul>

              <!-- Tab panes -->
              <div class="tab-content">
                <div class="tab-pane" id="khong-gian" role="tabpanel">
                  <div class="upload-placeholder">
                    <div class="upload-img-post" id="image_khong_gian_create">
                      <ul class="list-unstyled row">
                        <li class="col-md-4 col-6">
                          <div class="box-img-upload upload-begin upload-image-disabled">
                            <div class="box-img-upload-content">
                              <i class="icon-new-white"></i>
                              <p>{{trans('Location'.DS.'layout.choose_image')}}</p>
                            </div>
                          </div>
                        </li>
                      </ul>
                      <input id="file-upload" type="file"  multiple="" accept="image/*">
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
                              <p>{{trans('Location'.DS.'layout.choose_image')}}</p>
                            </div>
                          </div>
                        </li>
                      </ul>
                      <input id="file-upload1" type="file" multiple="" accept="image/*">
                    </div>
                  </div>
                </div>
                <div class="tab-pane" id="video" role="tabpanel">
                  <fieldset class="form-group">
                    <label>{{trans('Location'.DS.'layout.upload_video')}}</label>
                    <div><input type="text" name="link[]" class="form-control input-link-video" onchange="loadThumb(this)" placeholder="https://www.youtube.com...."><i class="fa fa-remove remove-video" onclick="removeVideo(this)"></i></div>
                  </fieldset>
                  <!-- end form group -->
                  <button class="btn btn-primary btn-addvideo"><i class="icon-new-white"></i> {{trans('Location'.DS.'layout.add_link')}}</button>
                </div>
                <div class="tab-pane active" id="avatar" role="tabpanel">
                  <fieldset class="box-upload-avata form-group col-md-4" id="avatar_create">
                    <label>{{trans('Location'.DS.'layout.upload_avatar')}} <span style="color: #d9534f">*</span></label>
                    <div class="upload-avata box-img-upload avata-border">
                      <div class="box-img-upload-content">
                        <i class="icon-new-white"></i>
                        <p>{{trans('Location'.DS.'layout.choose_image')}}</p>
                      </div>
                    </div>
                    <input id="file-upload-avata" type="file" accept="image/*">
                    <div class="form-control-feedback" id="feedback_avatar" style="color: #d9534f; display: none"></div>
                  </fieldset>  
                </div>
              </div>
              
              <fieldset class="form-group col-md-6" id="fieldset_feedback_code_invite">
                  <label>{{mb_ucfirst(trans('Location'.DS.'layout.code_invite'))}} ({{trans('Location'.DS.'layout.note_code_invite')}})</label>
                  <input type="text" id="code_invite" name="code_invite" class="form-control input-md-6">
                  <div class="form-control-feedback" id="feedback_code_invite" style="color: #d9534f; display: none"></div>
                </fieldset>

              <fieldset class="form-group col-md-12">
                <label>{{trans('Location'.DS.'layout.captcha')}} <span style="color: #d9534f">*</span></label>
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
                <button type="button" class="prev-step btn  btn  btn-outline-primary  btn-lg">{{trans('Location'.DS.'layout.button_step2')}}</button>
                <button type="button" id="preview_location_fe" class="preview-step btn  btn  btn-outline-primary  btn-lg">{{trans('Location'.DS.'layout.prev')}}</button>
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

<div id="modal-create-success" class="modal  modal-submit-payment  modal-vertical-middle modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content text-center">
      <div class="modal-logo pt-4 text-center">
        <img src="/frontend/assets/img/logo/logo-large.svg" alt="">
      </div>
      <h4>&nbsp;</h4>
      <p class="text_1 text-center">{{trans('Location'.DS.'layout.create_location_success')}}</p>
      <p class="text_2 text-center">{{trans('Location'.DS.'layout.create_location_success_information')}}</p>
      <div class="modal-button d-flex justify-content-center">
        <a class="btn btn-secorady" href="#" data-dismiss="modal">{{trans('global.close')}}</a>
      </div>
    </div>
  </div>
</div>


<div id="modal-create-category" class="modal modal-vertical-middle  modal-submit-payment   modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content text-center">
      <div class="modal-logo pt-4 text-center">
        <img src="/frontend/assets/img/logo/logo-large.svg" alt="">
      </div>
      <h4>&nbsp;</h4>
      <p id="message_category"></p>
      <div class="modal-button d-flex justify-content-center">
        <a class="btn btn-secorady" href="#" data-dismiss="modal">{{trans('global.close')}}</a>
      </div>
    </div>
  </div>
</div>

<style>
  .remove_custom_open{
    position: absolute;
    cursor: pointer;
    margin-top: 0;
    right: 0;
  }
  .group_product input[type=file]{
    cursor: pointer !important;
  }
</style>
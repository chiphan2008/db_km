<div class="content-edit-profile-manager cread-location">


  <div class="page-edit-location">

    <div class="process-create-header">
      <div class="logo"><img src="/frontend/assets/img/logo/logo-large.svg" alt=""></div>
      <a class="back-step-edit" href="{{Auth::guard('web_client')->user()?url('user/'.Auth::guard('web_client')->user()->id.'/management-location/'):''}}">
        <i class="icon-left"></i>  {{trans('Location'.DS.'user.management_location')}}
      </a>
      <ul class="nav nav-tabs bs-wizard d-flex justify-content-center" role="tablist" style="border-bottom:0;">
        <li class="nav-item col-4 bs-wizard-step highlight">
          <div class="progress"><div class="progress-bar"></div></div>
          <a class="nav-link bs-wizard-dot" data-toggle="tab" role="tab"></a>
          <div class="bs-wizard-info text-center">{{trans('Location'.DS.'user.general_info')}}</div>
        </li>
        <li class="nav-item col-4 bs-wizard-step disabled">
          <div class="progress"><div class="progress-bar"></div></div>
          <a class="nav-link bs-wizard-dot" data-toggle="tab"  role="tab"></a>
          <div class="bs-wizard-info text-center">{{trans('Location'.DS.'user.image')}}</div>
        </li>
      </ul>
    </div>
    <!-- end process create header -->
    <!-- modal-header -->
    <div class="process-create-content">
      <form id="form-creat-location-edit" action="" class="form-creat-location"  enctype="multipart/form-data" method="POST">

        <div class="tab-content">
          <div class="tab-pane active" id="step2" role="tabpanel">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row">
              <fieldset class="form-group col-md-12">
                <label>{{trans('Location'.DS.'user.category')}}: @lang($arrData['category_name'])</label>
              </fieldset>
              

              <div class="col-md-12" id="fieldset_feedback_category_item_edit">
                <label>{{trans('Location'.DS.'layout.type_category')}} <span style="color: #d9534f">*</span></label>
                <div class="form-control-feedback" id="feedback_category_item_edit" style="color: #d9534f;display: none"></div>
                <div class="list-cate-child bg-gray-light px-4 pt-4 pb-0" id="cate-1">
                  <ul class="row list-unstyled">
                    @if($arrData['content']->id_category == 5)
                      @foreach($arrData['list_category_item'] as $key => $value)
                        <li class="form-group  col-md-4">
                          <label class="custom-control custom-radio">
                            <input type="radio" name="category_item" value="{{$key}}" {{in_array($key, $arrData['list_category_item_content']) ? 'checked':''}} class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">@lang($value)</span>
                          </label>
                        </li>
                      @endforeach
                    @else
                      @foreach($arrData['list_category_item'] as $key => $value)
                        <li class="form-group  col-md-4">
                          <label class="custom-control custom-checkbox">
                            <input type="checkbox" name="category_item[]" value="{{$key}}" {{in_array($key, $arrData['list_category_item_content']) ? 'checked':''}} class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">@lang($value)</span>
                          </label>
                        </li>
                      @endforeach
                    @endif
                  </ul>
                </div>
              </div>

              <div class="col-md-12">
                <label>{{trans('Location'.DS.'layout.service')}} <span style="color: #d9534f">*</span></label>
                <div class="form-control-feedback" id="feedback_service_edit" style="color: #d9534f;display: none"></div>
                <div class="list-cate-child bg-gray-light px-4 pt-4 pb-0" id="cate-1">
                  <ul class="row list-unstyled">
                      @foreach($arrData['list_service'] as $key => $value)
                        <li class="form-group  col-md-4">
                          <label class="custom-control custom-checkbox">
                            <input type="checkbox" name="service[]" value="{{$value->_service_item->id}}" {{in_array($value->_service_item->id, $arrData['list_service_content']) ? 'checked':''}} class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">@lang($value->_service_item->name)</span>
                          </label>
                        </li>
                      @endforeach
                  </ul>
                </div>
              </div>


              <fieldset class="form-group col-md-6" id="fieldset_feedback_name_edit">
                <label>{{trans('Location'.DS.'user.name_location')}} <span style="color: #d9534f">*</span></label>
                <input type="text" maxlength="128" name="name" id="name_edit" class="form-control input-md-6" value="{{$arrData['content']->name}}" autocomplete="off" required>
                <div class="form-control-feedback" id="feedback_name_edit" style="color: #d9534f;display: none"></div>
              </fieldset>
              
              <fieldset class="form-group col-md-12" id="fieldset_feedback_address_edit_map">
                <label>{{trans('Location'.DS.'layout.address_map')}} <span style="color: #d9534f">*</span></label>
                <input type="text" maxlength="128" id="address_edit_map" name="address" class="form-control input-md-6" value="{{$arrData['content']->address}}" required>
                <div id="map_div_edit">
                  <div class="pac-container pac-edit"></div>
                </div>
                <div class="form-control-feedback" id="feedback_address_edit_map" style="color: #d9534f;display: none"></div>
              </fieldset>

              <fieldset class="form-group col-md-12">
                <div id="google_map_edit" style="height: 300px;"></div>
              </fieldset>
              
              <fieldset class="form-group col-md-12" id="fieldset_feedback_date_open_edit">
                <label>{{trans('Location'.DS.'layout.date_open')}} <span style="color: #d9534f">*</span></label>
                <div class="row hidden-sm-down">
                  <div class="col-md-3"><label for="">{{trans('Admin'.DS.'content.from_date')}}</label></div>
                  <div class="col-md-3"><label for="">{{trans('Admin'.DS.'content.to_date')}}</label></div>
                  <div class="col-md-3"><label for="">{{trans('Admin'.DS.'content.from_hour')}}</label></div>
                  <div class="col-md-3"><label for="">{{trans('Admin'.DS.'content.to_hour')}}</label></div>
                </div>
                @if($arrData['content']->_date_open)
                @php ($i = 0)
                @foreach($arrData['content']->_date_open as $date)
                <div class="row">
                  <div class="col-md-3 col-6 mb-2">
                    <label class="hidden-md-up">{{trans('Admin'.DS.'content.from_date')}}</label>
                    <select class="form-control" name="date_open[{{$i}}][from_date]">
                      <option value="1" {{$date->date_from==1?'selected':''}}>{{trans('Admin'.DS.'content.monday')}}</option>
                      <option value="2" {{$date->date_from==2?'selected':''}}>{{trans('Admin'.DS.'content.tuesday')}}</option>
                      <option value="3" {{$date->date_from==3?'selected':''}}>{{trans('Admin'.DS.'content.wednesday')}}</option>
                      <option value="4" {{$date->date_from==4?'selected':''}}>{{trans('Admin'.DS.'content.thursday')}}</option>
                      <option value="5" {{$date->date_from==5?'selected':''}}>{{trans('Admin'.DS.'content.friday')}}</option>
                      <option value="6" {{$date->date_from==6?'selected':''}}>{{trans('Admin'.DS.'content.saturday')}}</option>
                      <option value="0" {{$date->date_from==0?'selected':''}}>{{trans('Admin'.DS.'content.sunday')}}</option>
                    </select>
                  </div>
                  <div class="col-md-3 col-6 mb-2">
                    <label class="hidden-md-up">{{trans('Admin'.DS.'content.to_date')}}</label>
                    <select class="form-control" name="date_open[{{$i}}][to_date]">
                      <option value="1" {{$date->date_to==1?'selected':''}}>{{trans('Admin'.DS.'content.monday')}}</option>
                      <option value="2" {{$date->date_to==2?'selected':''}}>{{trans('Admin'.DS.'content.tuesday')}}</option>
                      <option value="3" {{$date->date_to==3?'selected':''}}>{{trans('Admin'.DS.'content.wednesday')}}</option>
                      <option value="4" {{$date->date_to==4?'selected':''}}>{{trans('Admin'.DS.'content.thursday')}}</option>
                      <option value="5" {{$date->date_to==5?'selected':''}}>{{trans('Admin'.DS.'content.friday')}}</option>
                      <option value="6" {{$date->date_to==6?'selected':''}}>{{trans('Admin'.DS.'content.saturday')}}</option>
                      <option value="0" {{$date->date_to==0?'selected':''}}>{{trans('Admin'.DS.'content.sunday')}}</option>
                    </select>
                  </div>
                  <div class="col-md-3 col-6 mb-2">
                    <label class="hidden-md-up">{{trans('Admin'.DS.'content.from_hour')}}</label>
                    <input class="form-control choose_hour" type="text" name="date_open[{{$i}}][from_hour]" value="{{$date->open_from}}">
                  </div>
                  <div class="col-md-3 col-6 mb-2">
                    <label class="hidden-md-up">{{trans('Admin'.DS.'content.to_hour')}}</label>
                    <input class="form-control choose_hour" type="text" name="date_open[{{$i}}][to_hour]" value="{{$date->open_to}}">
                  </div>
                  @if($i>0)
                  <span><i class="remove_custom_open fa fa-remove" onclick="removeCustomOpenEdit(this)"></i></span>
                  @endif
                </div>
                @php ($i += 1)
                @endforeach
                @endif
                <div id="append_custom_open_edit">

                </div>
                <div class="w-100 text-center" id="add_custom_open">
                  <br/>
                  <button class="btn btn-default" type="button" onclick="addCustomOpenEdit()">
                   {{trans('Admin'.DS.'content.add_hour_open')}}
                  </button>
                  <br/>
                </div>
              </fieldset>
              
              <div class="form-group col-md-12">
                <label>{{trans('Location'.DS.'user.des')}}</label>
                <textarea maxLength="512" class="form-control max" name="description" rows="5" style="height:auto;">{{isset($arrData['content']->description) ? $arrData['content']->description : ''}}</textarea>
              </div>

              <fieldset class="form-group col-md-12" id="fieldset_feedback_address_edit">
                <label>{{trans('Location'.DS.'user.address')}} <span style="color: #d9534f">*</span></label>
                <input type="text" maxlength="128" id="address_edit" name="address" class="form-control input-md-6" value="{{$arrData['content']->address}}" required>
                <div class="form-control-feedback" id="feedback_address_edit" style="color: #d9534f;display: none"></div>
              </fieldset>     
              <fieldset class="form-group col-md-4" id="fieldset_feedback_country_edit">
                <label>{{trans('Location'.DS.'user.country')}} <span style="color: #d9534f">*</span></label>
                <select class="custom-select form-control" name="country" id="country_edit" onchange="getEditLocationAjax(this.value,'city')" required>
                  <option value="">-- {{trans('Location'.DS.'user.country')}} --</option>
                  @foreach($arrData['list_country'] as $key => $name)
                    <option value="{{$key}}" {{ $arrData['content']->country == $key ? 'selected' : '' }}>{{$name}}</option>
                  @endforeach
                </select>
                <div class="form-control-feedback" id="feedback_country_edit" style="color: #d9534f;display: none"></div>
              </fieldset>
              <fieldset class="form-group col-md-4" id="fieldset_feedback_city_edit">
                <label>{{trans('Location'.DS.'user.city')}} <span style="color: #d9534f">*</span></label>
                <select class="custom-select form-control" name="city" id="city_edit" onchange="getEditLocationAjax(this.value,'district')" required>
                  <option value="">-- {{trans('Location'.DS.'user.city')}} --</option>
                  @foreach($arrData['list_city'] as $key => $name)
                    <option value="{{$key}}" {{ $arrData['content']->city == $key ? 'selected' : '' }}>{{$name}}</option>
                  @endforeach
                </select>
                <div class="form-control-feedback" id="feedback_city_edit" style="color: #d9534f;display: none"></div>
              </fieldset>
              <fieldset class="form-group col-md-4" id="fieldset_feedback_district_edit">
                <label>{{trans('Location'.DS.'user.district')}} <span style="color: #d9534f">*</span></label>
                <select class="custom-select form-control"  name="district" id="district_edit" required>
                  <option value="">-- {{trans('Location'.DS.'user.district')}} --</option>
                  @foreach($arrData['list_districts'] as $key => $name)
                    <option value="{{$key}}" {{ $arrData['content']->district == $key ? 'selected' : '' }} >{{$name}}</option>
                  @endforeach
                </select>
                <div class="form-control-feedback" id="feedback_district_edit" style="color: #d9534f;display: none"></div>
              </fieldset>

              <input type="hidden" value="{{$arrData['content']->alias}}" name="alias" id="alias_edit">
              <input type="hidden" value="{{$arrData['content']->lat}}" name="lat" id="lat_edit">
              <input type="hidden" value="{{$arrData['content']->lng}}" name="lng" id="lng_edit">
              <input type="hidden" value="{{$arrData['content']->id}}" name="id_edit_content">
              <input type="hidden" value="{{$arrData['content']->id_category}}" name="id_category">
              <input type="hidden" value="update" name="type_submit">

              <fieldset class="form-group col-md-6">
                <label>Wifi</label>
                <input type="text" name="wifi" value="{{isset($arrData['content']->wifi) ? $arrData['content']->wifi : ''}}" maxlength="128"  id="wifi_edit" class="form-control" autocomplete="new-wifi" placeholder="Wifi">
              </fieldset>
              <fieldset class="form-group col-md-6" id="fieldset_feedback_wifi_edit">
                <label>{{trans('Location'.DS.'layout.pass_wifi')}}</label>
                <input type="text"  maxlength="128" name="pass_wifi" value="{{isset($arrData['content']->pass_wifi) ? $arrData['content']->pass_wifi : ''}}" class="form-control" autocomplete="new-password" placeholder="{{trans('Location'.DS.'layout.pass_wifi')}}">
              </fieldset>
              <fieldset class="form-group col-md-6">
                <label>{{trans('Location'.DS.'layout.phone')}}</label>
                <input value="{{isset($arrData['content']->phone) ? $arrData['content']->phone : ''}}" type="text" maxlength="20" name="phone" id="phone_edit" class="form-control" autocomplete="new-phone" placeholder="{{trans('Location'.DS.'layout.phone')}}">
              </fieldset>
              <fieldset class="form-group col-md-6" id="fieldset_feedback_email_edit">
                <label>Email</label>
                <input value="{{isset($arrData['content']->email) ? $arrData['content']->email : ''}}" type="text" maxlength="128" name="email_edit" id="email_edit" class="form-control" autocomplete="new-email" placeholder="Email">
                <div class="form-control-feedback" id="feedback_email_edit" style="color: #d9534f;display: none"></div>
              </fieldset>
              <fieldset class="form-group col-md-12 box-hastag" id="fieldset_feedback_tag_edit">
                <label>{!! trans('Location'.DS.'layout.place_tags') !!} <span style="color: #d9534f">*</span><span style="color: #d9534f">  ({{trans('Location'.DS.'layout.note_add_tags_create')}})</span></label>
                <input type="text" name="tag" value="{{isset($arrData['content']->tag) ? $arrData['content']->tag : ''}}" class="tokeHastagEdit form-control" style="min-width: 100%;"/>
                <div class="form-control-feedback" id="feedback_tag_edit" style="color: #d9534f;display: none"></div>
              </fieldset>
            </div>
            <div class="btn-step w-100 d-sm-flex aline justify-content-sm-center">
              <button type="button" class="next-step btn btn-primary btn-lg mb-2 mb-sm-0" onclick="$('#content_id_create').val({{$arrData['content']->id}});closeSuccess();" >{{trans('global.update')}} {{trans('Location'.DS.'user.general_info')}}</button>
              <button type="button" class="next-step btn btn-primary btn-lg" id="button_step2_edit">{{trans('Location'.DS.'user.step_2')}}</button>
            </div>
          </div>

          <div class="tab-pane tab-upload-image" id="step3" role="tabpanel">
            <ul class="nav tab-upload-image-nav" role="tablist">
              <li class="nav-item">
                  <a class="nav-link active" id="avatar_nav_edit" data-toggle="tab" href="#avatar-edit" role="tab">{{trans('global.avatar')}}</a>
                </li>
              <li class="nav-item" style="border-width: 2px">
                <a class="nav-link" data-toggle="tab" href="#khong-gian-edit" role="tab">{{trans('global.space')}}
                ({{count($arrData['list_image_space'])}})
              </a>
              </li>
              <li class="nav-item" style="border-width: 2px">
                <a class="nav-link" data-toggle="tab" href="#menu-edit" role="tab">{{trans('global.image')}}
                ({{count($arrData['list_image_menu'])}})
              </a>
              </li>
              <li class="nav-item" style="border-left: 2px solid #e0e8ed;">
                <a class="nav-link" data-toggle="tab" href="#video-edit" role="tab">{{trans('global.video')}}
                ({{count($arrData['list_link_video'])}})
              </a>
              </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
              <div class="tab-pane" id="khong-gian-edit" role="tabpanel">
                <div  class="space-remove">
                  <a href="">{{trans('global.delete_all')}}</a>
                </div>

                <div class="upload-placeholder">
                  <div class="upload-img-post" id="edit_image_khong_gian">
                    <ul class="list-unstyled row">
                      <li class="col-md-4 col-6">
                        <div class="box-img-upload upload-begin upload-image-disabled">
                          <div class="box-img-upload-content">
                            <i class="icon-new-white"></i>
                            <p>{{trans('Location'.DS.'user.choose_image')}}</p>
                          </div>
                        </div>
                      </li>
                      @foreach($arrData['list_image_space'] as $src)
                        <li class="col-md-4 col-6">
                          <!-- <div class="box-img-upload box-img-upload-success">
                            <a class="remove-img" data-typename="edit_image_spaces" data-field="{{$src['id']}}" data-filename="{{$src['name']}}">
                              <i class="icon-cancel"></i>
                            </a>
                            <img src="{{asset($src['name'])}}">
                          </div> -->
                          <div class="box-img-upload box-img-upload-success">
                              <a class="remove-img" data-typename="edit_image_khong_gian" data-field="{{$src['id']}}" data-filename="{{$src['name']}}">
                                <i class="icon-cancel"></i>
                              </a>
                              <img src="{{asset($src['name'])}}">
                              <div class="box-img-upload-descript">
                                <input type="text" maxlength="128" value="{{$src['title']}}" class="form-control title_edit_image_space" placeholder="Viết tiêu đề" data-field="{{$src['id']}}" onchange="updateImg(this,'space')">

                                <input type="text" maxlength="128" value="{{$src['description']}}" class="form-control des_edit_image_space" placeholder="Viết mô tả" data-field="{{$src['id']}}" onchange="updateImg(this,'space')">
                              </div>
                          </div>
                        </li>
                      @endforeach
                    </ul>
                    <input id="file-upload-edit" type="file" multiple="" accept=".png, .jpg, .jpeg">
                  </div>
                </div>
              </div>

              <div class="tab-pane" id="menu-edit" role="tabpanel">
                <div  class="space-remove">
                  <a href="">{{trans('global.delete_all')}}</a>
                </div>
                <div class="upload-placeholder">
                  <div class="upload-img-post" id="edit_image_menu">
                    <ul class="list-unstyled row">
                      <li class="col-md-4 col-6">
                        <div class="box-img-upload upload-begin upload-image-disabled">
                          <div class="box-img-upload-content">
                            <i class="icon-new-white"></i>
                            <p>{{trans('Location'.DS.'user.choose_image')}}</p>
                          </div>
                        </div>
                      </li>

                      @foreach($arrData['list_image_menu'] as $src)
                        <li class="col-md-4 col-6">
                          <!-- <div class="box-img-upload box-img-upload-success">
                            <a class="remove-img" data-typename="edit_image_menu" data-field="{{$src['id']}}" data-filename="{{$src['name']}}">
                              <i class="icon-cancel"></i>
                            </a>
                            <img src="{{asset($src['name'])}}">
                          </div> -->
                          <div class="box-img-upload box-img-upload-success">
                              <a class="remove-img" data-typename="edit_image_menu" data-field="{{$src['id']}}" data-filename="{{$src['name']}}">
                                <i class="icon-cancel"></i>
                              </a>
                              <img src="{{asset($src['name'])}}">
                              <div class="box-img-upload-descript">
                                <input type="text" maxlength="128" value="{{$src['title']}}" class="form-control title_edit_image_menu" placeholder="Viết tiêu đề" data-field="{{$src['id']}}" onchange="updateImg(this,'menu')">

                                <input type="text" maxlength="128" value="{{$src['description']}}" class="form-control des_edit_image_menu" placeholder="Viết mô tả" data-field="{{$src['id']}}" onchange="updateImg(this,'menu')">
                              </div>
                          </div>
                        </li>
                      @endforeach
                    </ul>
                    <input id="file-upload1-edit" type="file" multiple="" accept=".png, .jpg, .jpeg">
                  </div>
                </div>
              </div>

              <div class="tab-pane" id="video-edit" role="tabpanel">
                <fieldset class="form-group">
                  <label>{{trans('Location'.DS.'user.upload_video')}}</label>
                  <div class="row">
                    <div class="col-md-6" style="padding-top: 4px;">
                  <input id="edit_new_video" placeholder="{{trans('Location'.DS.'layout.input_video')}}" data-type="edit" type="text" maxlength="128"  value="" class="form-control input-link-video">
                    </div>
                    <div class="col-md-6">
                  <input type="button" class="btn btn-primary btn-addvideo" onclick="loadThumbNew('edit')" value="{{trans('Location'.DS.'user.add_video')}}">
                    </div>
                  </div>

                </fieldset>
                <!-- end form group -->
                <div class="row content-video-location mt-3" id="content-video-location-edit">
                  @foreach($arrData['list_link_video'] as $src)
                    <div class="col-md-4 col-sm-6 mb-4" id="old_video_edit_{{$src->id}}">
                      <i class="fa fa-remove remove-video" onclick="removeVideo(this)" data-id="{{$src->id}}" data-type="old_edit" style="position: absolute;
                        right: 25px;
                        top: 5px;
                        z-index: 1000;
                        color: red;
                        font-size: 25px;"></i>
                      <div class="iframe-video">
                        <a href="javascript:void(0)" onclick="getUrlToButton(this)" data-url="{{$src->link}}" data-type="edit" >
                        {{--@if($src->type == 'facebook')--}}
                        {{--<a data-fancybox data-type="iframe" href="https://www.facebook.com/plugins/video.php?height=232&href={{$src->link}}">--}}
                        {{--@else--}}
                        {{--<a data-fancybox href="{{$src->link}}">--}}
                        {{--@endif--}}
                          <img src="{{$src->thumbnail}}" alt="">
                          <span class="ytp-time-duration">{{$src->time}}</span>
                        </a>
                        <p>
                          <a href="">
                            {{$src->title}}
                          </a>
                        </p>
                      </div>
                      <input type="hidden" name="link[]" value="{{$src->link}}">
                    </div>
                  @endforeach
                </div>
              </div>

              <div class="tab-pane active" id="avatar-edit" role="tabpanel">
                <fieldset class="box-upload-avata-edit form-group col-md-4" id="fieldset_feedback_avatar_edit">
                  <label>{{trans('Location'.DS.'user.upload_avatar')}}</label>
                  <div class="upload-avata-edit box-img-upload">
                    <div class="box-img-upload-success">
                      <img src="{{str_replace('img_content','img_content_thumbnail',$arrData['content']->avatar)}}" style="max-width: 200px;"/>
                    </div>
                  </div>
                  <input id="file-upload-avata-edit" type="file" accept=".png, .jpg, .jpeg">
                  <div class="form-control-feedback" id="feedback_avatar_edit" style="color: #d9534f; display: none"></div>
                </fieldset>
                </div>
            </div>
            <div class="btn-step w-100 d-sm-flex aline justify-content-sm-center">
              <button type="button" class="next-step btn btn-primary btn-lg mb-2 mb-sm-0" onclick="$('#content_id_create').val({{$arrData['content']->id}});closeSuccess();" >{{trans('global.update')}} {{trans('Location'.DS.'user.general_info')}}</button>
              <button type="button" class="prev-step btn  btn  btn-outline-primary  btn-lg">{{trans('Location'.DS.'user.step_1')}}</button>
              <button type="button" class="btn btn-primary btn-lg" id="update_location_fe">{{trans('Location'.DS.'user.save')}}</button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <!-- modal-body -->
  </div>
  <!-- end page edit location -->
</div>

<div id="modal-update-success" class="modal modal-vertical-middle fade modal-submit-payment  modal-vertical-middle modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment modal-vertical-middle modal-vertical-middle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content text-center">
      <div class="modal-logo pt-4 text-center">
        <img src="/frontend/assets/img/logo/logo-large.svg" alt="">
      </div>
      <h4>&nbsp;</h4>
      <p class="text_1 text-center">{{trans('Location'.DS.'layout.update_location_success')}}</p>
      <div class="modal-button d-flex justify-content-center">
        <a class="btn btn-secorady" href="{{url()->previous()?url()->previous():url('user/'.Auth::guard('web_client')->user()->id.'/management-location/')}}" >{{trans('global.close')}}</a>
      </div>
    </div>
  </div>
</div>

@section('JS')
<script type="text/javascript" charset="utf-8">
  function getEditLocationAjax(value, type) {
    if (type == 'city') {
      $('#district_edit').html('<option value="">-- {{trans('Location'.DS.'user.district')}} --</option>');
    }
    $.ajax({
      type: "POST",
      data: {value: value, type: type, _token: $("meta[name='_token']").prop('content')},
      url: '/createLocationFrontend/postLocation',
      success: function (data) {
        $("#" + type + '_edit').html(data);
      }
    })
  }
</script>
<script type="text/javascript">
  var geocoder_edit = new google.maps.Geocoder();
  var marker_edit = new google.maps.Marker();
  var infowindow_edit = new google.maps.InfoWindow({
    size: new google.maps.Size(150, 50)
  });
  var lat_edit = {!! json_encode($arrData['content']->lat) !!};
  var lng_edit = {!! json_encode($arrData['content']->lng) !!};

  $(function(){
    $("#address_edit_map").on("focus",function(){
      var pacContainer = $('.pac-container');
      $("#map_div_edit").append(pacContainer);
    })
  })

  function CenterControlEdit(controlDiv, map) {

    // Set CSS for the control border.
    var controlUI = document.createElement('div');
    controlUI.style.backgroundColor = '#fff';
    controlUI.style.border = '2px solid #fff';
    controlUI.style.cursor =  'pointer'; 
    controlUI.style.width =  '25px'; 
    controlUI.style.height =  '25px'; 
    controlUI.style.overflow =  'hidden'; 
    controlUI.style.margin =  '10px 14px'; 
    controlUI.style.position =  'absolute';
    controlUI.title =  'You current location'; 

    controlUI.style.top =  '10px'; 
    controlUI.style.right =  '0px';

    controlUI.style.textAlign =  'center';
    controlUI.style.backgroundImage = 'url(/img_default/location.png)';
    controlUI.style.backgroundSize = '220px 22px';
    controlDiv.appendChild(controlUI);

    // Set CSS for the control interior.
    // var controlImg = document.createElement('img');
    // controlImg.src = '/img_default/location.png';
    // controlImg.style.maxHeight = '100%';
    // controlImg.style.maxWidth = '100%';
    // controlImg.style.height = '25px';
    // controlUI.appendChild(controlImg);

    // Setup the click event listeners: simply set the map to Chicago.
    controlUI.addEventListener('click', function() {
      var current_location = window.sessionStorage.getItem('currentLocation');
      current_location = current_location.split(",");
      var location = new google.maps.LatLng(current_location[0], current_location[1]);
      map_edit.setCenter(location);
      marker_edit.setPosition(location);
    });

    controlUI.addEventListener('mouseover', function() {
      if($(window).width()>768)
        this.style.backgroundPositionX = '44px';
    });
    controlUI.addEventListener('mouseout', function() {
      if($(window).width()>768)
        this.style.backgroundPositionX = '0px';
    });
  }
  function initialize_edit() {
    var latLng = new google.maps.LatLng(lat_edit, lng_edit);
    map_edit = new google.maps.Map(document.getElementById('google_map_edit'), {
      zoom: 15,
      center: latLng,
      zoomControl: true,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      draggable: true
    });

    marker_edit = new google.maps.Marker({
      position: latLng,
      map: map_edit,
      draggable: true
    });

    var centerControlDiv = document.createElement('div');
    var centerControl = new CenterControlEdit(centerControlDiv, map_edit);

    centerControlDiv.index = 2;
    map_edit.controls[google.maps.ControlPosition.RIGHT_TOP].push(centerControlDiv);

    
    google.maps.event.addListener(marker_edit, 'dragend', function () {
      geocodePosition_edit(marker_edit.getPosition());
      map_edit.setCenter(marker_edit.getPosition());
    });



    var input_edit = document.getElementById('address_edit_map');
    autocomplete_edit = new google.maps.places.Autocomplete(input_edit);


    console.log(autocomplete_edit);
    google.maps.event.addListener(autocomplete_edit, 'place_changed', function () {
      codeAddress_edit(autocomplete_edit.getPlace().formatted_address);
    });

    // $("#map_div_edit").find(".pac-container").remove();
    // setTimeout(function(){ 
    //     let clone = $(".pac-container").last();
    //     $("#map_div_edit").append(clone);
    // }, 500);

    google.maps.event.addListener(marker_edit, 'click', function () {
      infowindow_edit.setContent('{{$arrData['content']->address}}');
      infowindow_edit.open(map_edit, marker_edit);
    });

    google.maps.event.trigger(marker_edit, 'click');

  };
  google.maps.event.addDomListener(window, "load", initialize_edit);

  function codeAddress_edit(address) {
    geocoder_edit.geocode({
      'address': address
    }, function (results, status) {

      if (status == google.maps.GeocoderStatus.OK) {

        $("#google_map_edit").show();
        initialize_edit();

        map_edit.setCenter(results[0].geometry.location);
        if (marker_edit) {
          marker_edit.setMap(null);
          if (infowindow_edit) infowindow_edit.close();
        }

        marker_edit = new google.maps.Marker({
          map: map_edit,
          draggable: true,
          position: results[0].geometry.location
        });

        google.maps.event.addListener(marker_edit, 'dragend', function () {
          geocodePosition_edit(marker_edit.getPosition());
          map_edit.setCenter(marker_edit.getPosition());
        });

        google.maps.event.addListener(marker_edit, 'click', function () {
          if (marker_edit.formatted_address) {
            infowindow_edit.setContent(marker_edit.formatted_address);
          } else {
            infowindow_edit.setContent(address);
          }
          infowindow_edit.open(map_edit, marker_edit);
        });

        var addr = '';
        // console.log(results[0]);
        for (var i = 0; i < results[0].address_components.length; i++) {
          if (results[0].address_components[i].types[0] == 'street_number' || results[0].address_components[i].types[0] == 'premise') {
            addr = results[0].address_components[i].long_name
          }
          if (results[0].address_components[i].types[0] == 'route') {
            addr = addr + ' ' + results[0].address_components[i].long_name
          }
        }

        $("#address_edit").val(addr);
        $("#lat_edit").val(results[0].geometry.location.lat().toFixed(6));
        $("#lng_edit").val(results[0].geometry.location.lng().toFixed(6));
        google.maps.event.trigger(marker_edit, 'click');
      } else {
        $("#address_edit").val('');
        $("#lat_edit").val('');
        $("#lng_edit").val('');
      }
    });
  }

  function geocodePosition_edit(pos) {
    geocoder_edit.geocode({
      latLng: pos
    }, function (responses) {
      if (responses && responses.length > 0) {
        marker_edit.formatted_address = responses[0].formatted_address;
        //$("#address").val(responses[0].formatted_address);
        $("#lat_edit").val(marker_edit.getPosition().lat().toFixed(6));
        $("#lng_edit").val(marker_edit.getPosition().lng().toFixed(6));
      } else {
        marker_edit.formatted_address = 'Cannot determine address at this location.';
      }
    });
  }


  $(function () {

    $('#form-creat-location-edit .tokeHastagEdit').tagsInput({
      width: 'auto',
      defaultText: "{{trans('global.add_keyword')}}",
      onChange: function(){
        var input = $(this).siblings('.tagsinput');
        var maxLen = 100; // e.g.
        if(input.children('span.tag').length >= maxLen){
          input.children('div').hide();
        }
        else{
          input.children('div').show();
        }
      },
      onRemoveTag: function(){
        $("#form-creat-location-edit div.tagsinput input").focus();
      },
      onAddTag: function(){
        $("#form-creat-location-edit div.tagsinput input").focus();
      }
    });

    $("#form-creat-location-edit div.tagsinput input").on('paste',function(e){
        var element=this;
        setTimeout(function () {
            var text = $(element).val();
            var target=$("#form-creat-location-edit .tokeHastagEdit");
            var tags = (text).split(/[,]+/);
            for (var i = 0, z = tags.length; i<z; i++) {
                  var tag = $.trim(tags[i]);
                  if (!target.tagExist(tag)) {
                        target.addTag(tag);
                  }
                  else
                  {
                      $("#form-creat-location-edit div.tagsinput input").val('');
                  }
            }
            $("#form-creat-location-edit div.tagsinput input").focus();
        },10);
    });
    $("#form-creat-location-edit div.tagsinput input").on('textInput',function(e){
        var element=this;
        setTimeout(function () {
          var text = $(element).val();
          var target=$("#form-creat-location-edit .tokeHastagEdit");
          if(text.indexOf(',') > -1){
            var tag = text.replace(',','');
            if (!target.tagExist(tag)) {
                target.addTag(tag);
            }else{
                $("#form-creat-location-edit div.tagsinput input").val('');
                $("#form-creat-location-edit div.tagsinput input").focus();
            }
          }
        },10);
    });
      

    $("#name_edit").on("keyup", function () {
      var name = $(this).val();
      $("#alias_edit").val(str_slug(name));
    });

    // $("#phone_edit").on("blur", function (e) {
    //   var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,4})(\d{0,4})/);
    //   e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    // });

      $("#phone_edit").on("keypress",function(e){
          return (e.charCode >= 48 && e.charCode <= 57) || e.charCode == 43 || event.charCode == 0 ;
      })

    $(".input-link-video").trigger('change');
  })

  function removeProductOld(id){
    if( confirm('{{trans('valid.confirm_delete_product')}}') ) {
      var CSRF_TOKEN = $("meta[name='_token']").prop('content');
      $.ajax({
        type: "POST",
        data: {id: id, _token: CSRF_TOKEN},
        url: '/createLocationFrontend/deleteProduct',
        success: function (data) {
          if (data == 'sussess') {
            $("#product_" + id).remove();
          }
        }
      })
    }
  }

  function removeGroupProductOld(index){
    if( confirm('{{trans('valid.confirm_delete_group_product')}}') ) {
      var arr_id = [];
      $("#group_product_"+index+' .input_id').each(function(key,elem){
        arr_id.push($(elem).val());
      })

      var CSRF_TOKEN = $("meta[name='_token']").prop('content');
      $.ajax({
        type: "POST",
        data: {id: arr_id, _token: CSRF_TOKEN},
        url: '/createLocationFrontend/deleteGroupProduct',
        success: function (data) {
          if (data == 'sussess') {
            $("#group_product_" + index).remove();
          }
        }
      })
    }
  }

  function readImageProductEdit(input) {
    for (var i = 0; i < input.files.length; i++) {
      if (input.files[i]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          var img = $('<img style="height: 40px; width: 40px; border: 1px solid #000; margin: 2px">');
          img.attr('src', e.target.result);
          $(input).parent().next().html(img);
          console.log($(input).parent().find('.img_product'));
        };
        reader.readAsDataURL(input.files[i]);
      }
    }
  }

  function addProductEdit(index_group){
    var index = $("#form-creat-location-edit .item_product").length+1;

    $('#form-creat-location-edit .header_product').show();
    html='';
    html+='<div class="item_product row">';
    html+='<input type="hidden" name="product['+index_group+']['+index+'][id]" value="0" placeholder="">';
    html+='<div class="col-md-4 text-center">';
    html+='<input type="text" maxlength="128" class="form-control" name="product['+index_group+']['+index+'][name]"    placeholder="{{trans('Admin'.DS.'content.name')}}">';
    html+='</div>';
    html+='<div class="col-md-2 text-center">';
    html+='<input type="number" min="0" max="9999999999" class="form-control" name="product['+index_group+']['+index+'][price]" placeholder="{{trans('Admin'.DS.'content.price')}}">';
    html+='</div>';
    html+='<div class="col-md-2 text-center">';
    html+='<select class="form-control custom-select" name="product['+index_group+']['+index+'][currency]">';
    html+=' <option value="VND">VND</option>';
    html+=' <option value="USD">USD</option>';
    html+='</select>';
    html+='</div>';
    html+='<div class="col-md-2 text-center relative">';
    html+='<button class="btn btn-default">{{trans('Location'.DS.'layout.choose_image')}}</button><input type="file" class="" name="product['+index_group+']['+index+'][image]" onchange="readImageProductEdit(this)">';
    html+='</div>';
    html+='<div class="col-md-2 img_product text-center">';
    html+='</div>';
    // html+='<div class="col-md-1">';
    html+='<a class="remove_custom_open" onclick="removeProductEdit(this)"><i class="fa fa-remove"></i></a>';
    // html+='</div>';
    html+='</div>';
    $("#form-creat-location-edit #list_product_"+index_group).append(html);
  }

  function removeProductEdit(obj){
    $(obj).parent().remove();
    if($("#form-creat-location-edit .item_product").length==0){
      $('#form-creat-location-edit .header_product').hide();
    }
  }

  function removeGroupProductEdit(index){
    $('#form-creat-location-edit #group_product_'+index).remove();
  }

  function addGroupEdit(){
    var index = $("#form-creat-location-edit .item_product").length+1;
    var html='';
    html+='<div class="group_product" id="group_product_'+index+'" style="">';
    html+='<div class="form-group row">';
    html+='<label class="control-label col-md-4 col-sm-4 col-xs-12">';
    html+='{{trans('Admin'.DS.'content.product_group')}}';
    html+='</label>';
    html+='<div class="col-md-7 col-sm-7 col-xs-12">';
    html+='<input class="form-control" type="text" maxlength="128" name="product['+index+'][group_name]"/>';
    html+='</div>';
    html+='<div class="col-md-1 col-sm-1 col-xs-12">';
    html+='<a class="remove_custom_open" onclick="removeGroupProductEdit('+index+')"><i class="fa fa-remove"></i></a>';
    html+='</div>';
    html+='</div>';
    html+='<div class="header_product row">';
    html+='<div class="col-md-4"><label>{{trans('Admin'.DS.'content.name')}}</label></div>';
    html+='<div class="col-md-4"><label>{{trans('Admin'.DS.'content.price')}}</label></div>';
    html+='<div class="col-md-4"><label>{{trans('Admin'.DS.'content.image')}}</label></div>';
    html+='</div>';
    html+='<div id="list_product_'+index+'">';
    html+='<div class="item_product row">';
    html+='<input type="hidden" name="product['+index+'][1][id]" value="0" placeholder="">';
    html+='<div class="col-md-4 text-center">';
    html+='<input type="text" maxlength="128" class="form-control" name="product['+index+'][1][name]" placeholder="Tên">';
    html+='</div>';
    html+='<div class="col-md-2 text-center">';
    html+='<input type="number" min="0" max="9999999999" class="form-control" name="product['+index+'][1][price]" placeholder="Giá">';
    html+='</div>';
    html+='<div class="col-md-2 text-center">';
    html+='<select class="form-control custom-select" name="product['+index+'][1][currency]">';
    html+='<option value="VND">VND</option>';
    html+='<option value="USD">USD</option>';
    html+='</select>';
    html+='</div>';
    html+='<div class="col-md-2 text-center relative">';
    html+='<button class="btn btn-default">{{trans('Location'.DS.'layout.choose_image')}}</button><input type="file" class="" name="product['+index+'][1][image]" onchange="readImageProductEdit(this)">';
    html+='</div>';
    html+='<div class="col-md-2 img_product text-center"></div>';
    html+='</div>';
    html+='</div>';
    html+='<div class="text-center" style="margin-top: 15px;">';
    html+='<button class="btn btn-primary" type="button" onclick="addProductEdit('+index+')">{{trans('Admin'.DS.'content.add_product')}}</button>';
    html+='</div>';
    html+='</div>';
    $("#form-creat-location-edit #list_group_product").append(html);
  }
</script>
@endsection

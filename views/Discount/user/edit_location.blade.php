<div class="content-edit-profile-manager cread-location">
  <div class="page-edit-location">
    <div class="process-create-header">
      <div class="logo"><img src="/frontend/assets/img/logo/logo-large.svg" alt=""></div>
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
      <form id="form-creat-location-edit" action="" class="form-creat-location">
        <div class="tab-content">
          <div class="tab-pane active" id="step2" role="tabpanel">
            <div class="row">
              <fieldset class="form-group col-md-12">
                <label>{{trans('Location'.DS.'user.category')}}: @lang($arrData['category_name'])</label>
              </fieldset>
              <fieldset class="form-group col-md-6" id="fieldset_feedback_name_edit">
                <label>{{trans('Location'.DS.'user.name_location')}} <span style="color: #d9534f">*</span></label>
                <input type="text" name="name" id="name_edit" class="form-control input-md-6" value="{{$arrData['content']->name}}" autocomplete="off" required>
                <div class="form-control-feedback" id="feedback_name_edit" style="color: #d9534f;display: none"></div>
              </fieldset>
              @if($arrData['content']->id_category == 5)
                <fieldset class="form-group col-md-6">
                  <label>{{trans('Location'.DS.'user.gender')}}</label>
                  <select class="custom-select form-control" name="bank_type">
                    <option value="BANK"> BANK </option>
                    <option value="ATM"> ATM </option>
                  </select>
                </fieldset>
              @else
                <fieldset class="form-group col-md-6">
                  @if(isset($arrData['list_group']))
                    <label>{{trans('Location'.DS.'user.branch')}}</label>
                    <select class="custom-select form-control" name="group">
                      <option value="">{{trans('Location'.DS.'user.plz_choose')}}</option>
                      @foreach($arrData['list_group'] as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                      @endforeach
                    </select>
                  @endif
                </fieldset>
              @endif

              <div class="col-md-12">
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


              <fieldset class="form-group col-md-12" id="fieldset_feedback_address_edit">
                <label>{{trans('Location'.DS.'user.address')}} <span style="color: #d9534f">*</span></label>
                <input type="text" id="address_edit" name="address" class="form-control input-md-6" value="{{$arrData['content']->address}}" required>
                <div class="form-control-feedback" id="feedback_address_edit" style="color: #d9534f;display: none"></div>
              </fieldset>

              <input type="hidden" value="{{$arrData['content']->alias}}" name="alias" id="alias_edit">
              <input type="hidden" value="{{$arrData['content']->lat}}" name="lat" id="lat_edit">
              <input type="hidden" value="{{$arrData['content']->lng}}" name="lng" id="lng_edit">
              <input type="hidden" value="{{$arrData['content']->id}}" name="id_edit_content">
              <input type="hidden" value="{{$arrData['content']->id_category}}" name="id_category">
              <input type="hidden" value="update" name="type_submit">

              <fieldset class="form-group col-md-12">
                <div id="google_map_edit" style="height: 300px;"></div>
              </fieldset>

              <fieldset class="form-group col-md-12 box-hastag">
                <label>{!! trans('Location'.DS.'layout.place_tags') !!}  </label>
                <input type="text" name="tag[]" value="{{isset($arrData['content']->tag) ? $arrData['content']->tag : ''}}" class="tokeHastagEdit form-control" style="min-width: 100%;"/>
              </fieldset>
              <div class="form-group col-md-12">
                <label>{{trans('Location'.DS.'user.des')}}</label>
                <textarea class="form-control" name="description" rows="5" style="height:auto;">{{isset($arrData['content']->description) ? $arrData['content']->description : ''}}</textarea>
              </div>

              <!-- <fieldset class="form-group col-md-6" id="fieldset_feedback_email_edit">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{isset($arrData['content']->email) ? $arrData['content']->email : ''}}" autocomplete="off">
                <div class="form-control-feedback" id="feedback_email_edit" style="color: #d9534f;display: none"></div>
              </fieldset> -->
              <fieldset class="form-group col-md-6">
                <label>{{trans('Location'.DS.'user.phone')}}</label>
                <input type="text" name="phone" id="phone_edit" value="{{isset($arrData['content']->phone) ? $arrData['content']->phone : ''}}" class="form-control" autocomplete="off" placeholder="(099)-9999-9999">
              </fieldset>

              <fieldset class="form-group col-md-3" id="fieldset_feedback_open_from_edit">
                <label>{{trans('Location'.DS.'user.open_hour')}} <span style="color: #d9534f">*</span></label>
                <input type="time" name="open_from" id="open_from-edit" class="form-control" value="{{$arrData['content']->open_from}}" autocomplete="off">
                <div class="form-control-feedback" id="feedback_open_from_edit" style="color: #d9534f;display: none"></div>
              </fieldset>
              <fieldset class="form-group col-md-3" id="fieldset_feedback_open_to_edit">
                <label>{{trans('Location'.DS.'user.close_hour')}} <span style="color: #d9534f">*</span></label>
                <input type="time" name="open_to" id="open_to-edit" class="form-control" value="{{$arrData['content']->open_to}}" autocomplete="off">
                <div class="form-control-feedback" id="feedback_open_to_edit" style="color: #d9534f;display: none"></div>
              </fieldset>

              <div class="col-md-12 row">
                @if($arrData['content']->id_category !=5)
                  <fieldset class="form-group col-md-3" id="fieldset_feedback_price_from_edit">
                    <label>{{trans('Location'.DS.'user.smallest_price')}} <span style="color: #d9534f">*</span></label>
                    <input type="number" name="price_from" id="price_from-edit" value="{{$arrData['content']->price_from}}" min="1" class="form-control" autocomplete="off">
                    <div class="form-control-feedback" id="feedback_price_from_edit" style="color: #d9534f;display: none"></div>
                  </fieldset>
                  <fieldset class="form-group col-md-3" id="fieldset_feedback_price_to_edit">
                    <label>{{trans('Location'.DS.'user.biggest_price')}} <span style="color: #d9534f">*</span></label>
                    <input type="number" name="price_to" id="price_to-edit" min="1" value="{{$arrData['content']->price_to}}" class="form-control" autocomplete="off">
                    <div class="form-control-feedback" id="feedback_price_to_edit" style="color: #d9534f;display: none"></div>
                  </fieldset>
                  <fieldset class="form-group col-md-3">
                    <label>{{trans('Location'.DS.'user.currency')}}</label>
                    <select class="custom-select form-control" name="currency">
                      <option value="VND" {{isset($arrData['content']->currency) && $arrData['content']->currency == 'VND' ? 'selected' : ''}}> VND </option>
                      <option value="USA" {{isset($arrData['content']->currency) && $arrData['content']->currency == 'USA' ? 'selected' : ''}}> USA </option>
                    </select>
                  </fieldset>
                @endif
              </div>

              <!-- <div class="row" id="list_service">
                <div class="col-md-12">
                  <h4>{{trans('Location'.DS.'layout.service')}}</h4>
                  <div class="list-utilities">
                    <ul class="row list-unstyled">
                    </ul>
                  </div>
                </div>
              </div> -->


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


              <fieldset class="box-upload-avata-edit form-group col-md-4" id="fieldset_feedback_avatar_edit">
                <label>{{trans('Location'.DS.'user.upload_avatar')}}</label>
                <div class="upload-avata-edit box-img-upload">
                  <div class="box-img-upload-content">
                    <img src="{{$arrData['content']->avatar}}"/>
                  </div>
                </div>
                <input id="file-upload-avata-edit" type="file" name="file-upload-avata-edit" accept=".png, .jpg, .jpeg">
                <div class="form-control-feedback" id="feedback_avatar_edit" style="color: #d9534f; display: none"></div>
              </fieldset>

            </div>
            <div class="btn-step w-100 d-flex aline justify-content-center">
              <button type="button" class="next-step btn btn-primary btn-lg" id="button_step2_edit">{{trans('Location'.DS.'user.step_2')}}</button>
            </div>
          </div>

          <div class="tab-pane tab-upload-image" id="step3" role="tabpanel">
            <ul class="nav tab-upload-image-nav" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#khong-gian-edit" role="tab">{{trans('global.space')}}
                ({{count($arrData['list_image_space'])}})
              </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#menu-edit" role="tab">{{trans('global.menu')}}
                ({{count($arrData['list_image_menu'])}})
              </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#video-edit" role="tab">{{trans('global.video')}}
                ({{count($arrData['list_link_video'])}})
              </a>
              </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
              <div class="tab-pane active" id="khong-gian-edit" role="tabpanel">
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
                          <div class="box-img-upload box-img-upload-success">
                            <a class="remove-img" data-typename="edit_image_spaces" data-field="{{$src['id']}}" data-filename="{{$src['name']}}">
                              <i class="icon-cancel"></i>
                            </a>
                            <img src="{{asset($src['name'])}}">
                          </div>
                        </li>
                      @endforeach
                    </ul>
                    <input id="file-upload-edit" type="file" name="image_space[]" multiple="" accept=".png, .jpg, .jpeg">
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
                          <div class="box-img-upload box-img-upload-success">
                            <a class="remove-img" data-typename="edit_image_menu" data-field="{{$src['id']}}" data-filename="{{$src['name']}}">
                              <i class="icon-cancel"></i>
                            </a>
                            <img src="{{asset($src['name'])}}">
                          </div>
                        </li>
                      @endforeach
                    </ul>
                    <input id="file-upload1-edit" type="file" name="image_menu[]" multiple="" accept=".png, .jpg, .jpeg">
                  </div>
                </div>
              </div>

              <div class="tab-pane" id="video-edit" role="tabpanel">
                <fieldset class="form-group">
                  <label>{{trans('Location'.DS.'user.upload_video')}}</label>
                  @foreach($arrData['list_link_video'] as $src)
                  <div>
                    <input type="text" name="link[]" onchange="loadThumb(this)" value="{{$src['link']}}" class="form-control input-link-video"><i class="fa fa-remove remove-video" onclick="removeVideo(this)"></i>
                  </div>
                  @endforeach
                </fieldset>
                <!-- end form group -->
                <button class="btn btn-primary btn-addvideo"><i class="icon-new-white"></i>  {{trans('Location'.DS.'user.add_video')}}</button>
              </div>
            </div>
            <div class="btn-step w-100 d-sm-flex aline justify-content-sm-center">
              <button type="button" class="prev-step btn  btn  btn-outline-primary  btn-lg">{{trans('Location'.DS.'user.step_1')}}</button>
              <button type="button" class="creat-step btn btn-primary btn-lg" id="update_location_fe">{{trans('Location'.DS.'user.save')}}</button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <!-- modal-body -->
  </div>
  <!-- end page edit location -->
</div>
<div id="modal-update-success" class="modal fade modal-submit-payment  modal-vertical-middle modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment modal-vertical-middle modal-vertical-middle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content text-center">
      <div class="modal-logo pt-4 text-center">
        <img src="/frontend/assets/img/logo/logo-large.svg" alt="">
      </div>
      <h4>&nbsp;</h4>
      <p class="text_1 text-center">{{trans('Location'.DS.'layout.update_location_success')}}</p>
      <div class="modal-button d-flex justify-content-center">
        <a class="btn btn-secorady" onclick="window.location.reload()" data-dismiss="modal">{{trans('global.close')}}</a>
      </div>
    </div>
  </div>
</div>
<style type="text/css" media="screen">
  #file-upload-edit, #file-upload1-edit{
    visibility: hidden;
  }
</style>
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
    google.maps.event.addListener(marker_edit, 'dragend', function () {
      geocodePosition_edit(marker_edit.getPosition());
      map_edit.setCenter(marker_edit.getPosition());
    });


    var input_edit = document.getElementById('address_edit');
    autocomplete_edit = new google.maps.places.Autocomplete(input_edit);

    google.maps.event.addListener(autocomplete_edit, 'place_changed', function () {
      codeAddress_edit(autocomplete_edit.getPlace().formatted_address);
    });

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
        for (var i = 0; i < results[0].address_components.length; i++)
        {
          if(results[0].address_components[i].types[0] == 'street_number')
          {
            addr = results[0].address_components[i].long_name
          }
          if(results[0].address_components[i].types[0] == 'route')
          {
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

    // $(".tokeHastagEdit").select2({
    //   tags: true,
    //     placeholder: "{{trans('global.keyword')}}",
    //     tokenSeparators: ['/',',',';'],
    //     maximumSelectionLength: 10
    //   }).on("change",function(e){
    //       if($(this).val().length>10){
    //           $(this).val($(this).val().slice(0,10));
    //           alert("Chỉ được chọn tối đa 10 từ khóa");
    //       }
    // });
    $(".tokeHastagEdit").tagsInput({
      width: 'auto',
      defaultText: "{{trans('global.add_keyword')}}",
      onChange: function(){
        var input = $(this).siblings('.tagsinput');
        var maxLen = 10; // e.g.
        if(input.children('span.tag').length >= maxLen){
          input.children('div').hide();
        }
        else{
          input.children('div').show();
        }
      }
    });


    $("#name_edit").on("keyup", function () {
      var name = $(this).val();
      $("#alias_edit").val(str_slug(name));
    });

    $("#phone_edit").on("blur", function (e) {
      var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,4})(\d{0,4})/);
      e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    });

    $(".input-link-video").trigger('change');
  })

</script>
@endsection

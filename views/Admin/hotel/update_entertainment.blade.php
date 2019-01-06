@extends('Admin..layout_admin.master_admin')

@section('content')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Update Entertainment Content</h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('list_content')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
          <form id="form-update-content" method="post" action="{{route('update_entertainment_content',['id'=>$content->id])}}"
                enctype="multipart/form-data" autocomplete="off"
                data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            {{ csrf_field() }}

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'hotel.name')}} <span
                  class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="name" name="name"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('name')?'parsley-error':''}}"
                       value="{{$content->name }}" >
                @if ($errors->has('name'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('name') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alias">{{trans('Admin'.DS.'hotel.alias')}} <span
                  class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="alias" name="alias"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('alias')?'parsley-error':''}}"
                       value="{{$content->alias }}" >
                @if ($errors->has('alias'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('alias') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <input type="hidden" value="{{$content->content_type_id}}" name="content_type" id="content_type">

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category">Category <span
                  class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control {{$errors->has('category')?'parsley-error':''}}" name="category"
                        id="category" onchange="getCategoryItem(this.value)" >
                  <option value="">-- Type --</option>
                  @foreach($data['list_category'] as $value => $name)
                    <option
                      value="{{$value}}" {{ $data['list_category_content'] == $value ? 'selected' : '' }}>{{$name}}</option>
                  @endforeach
                </select>
                @if ($errors->has('category'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('category') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_item">Category Item</label>
              <div class="col-md-6 col-sm-6 col-xs-12">

                <select class="form-control" name="category_item[]" id="category_item" multiple>
                  @foreach($data['list_category_item'] as $value => $name)
                    <option
                      value="{{$value}}" {{in_array($value, $data['list_category_item_content']) ? 'selected':''}}>{{$name}}
                    </option>
                  @endforeach
                </select>

                @if ($errors->has('category_item'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('category_item') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="group">{{trans('Admin'.DS.'hotel.group')}}</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="group" id="group">
                  <option value="">{{trans('Admin'.DS.'hotel.nothing_selected')}}</option>
                  @foreach($data['list_group'] as $value => $name)
                    <option
                      value="{{$value}}" {{ $data['list_group_content'] == $value ? 'selected' : '' }}>{{$name}}</option>
                  @endforeach
                </select>
                @if ($errors->has('group'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('group') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="open_form">Open <span
                  class="required">*</span>
              </label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="text" id="open_from" name="open_from"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('open_from')?'parsley-error':''}}"
                       value="{{ $content->open_from }}" placeholder="{{trans('global.from')}}" >
                @if ($errors->has('open_form') || $errors->has('open_to'))
                  <ul class="parsley-errors-list filled">
                    <li
                      class="parsley-required">{{ $errors->first('open_form') ? $errors->first('open_form') : $errors->first('open_to') }}</li>
                  </ul>
                @endif
              </div>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="text" id="open_to" name="open_to"
                       class="form-control col-md-7 col-xs-12  {{$errors->has('open_to')?'parsley-error':''}}"
                       value="{{ $content->open_to }}" placeholder="{{trans('global.to')}}" >
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="price_form">{{trans('Admin'.DS.'hotel.price')}} <span
                  class="required">*</span>
              </label>
              <div class="col-md-2 col-sm-3 col-xs-12">
                <input type="number" id="price_from" name="price_from"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('price_from')?'parsley-error':''}}"
                       value="{{ $content->price_from }}" placeholder="{{trans('global.from')}}" >
                @if ($errors->has('price_from') || $errors->has('price_to'))
                  <ul class="parsley-errors-list filled">
                    <li
                      class="parsley-required">{{ $errors->first('price_from') ? $errors->first('price_from') : $errors->first('price_to') }}</li>
                  </ul>
                @endif
              </div>
              <div class="col-md-2 col-sm-3 col-xs-12">
                <input type="number" id="price_to" name="price_to"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('price_to')?'parsley-error':''}}"
                       value="{{ $content->price_to }}" placeholder="{{trans('global.to')}}" >
              </div>
              <div class="col-md-2 col-sm-3 col-xs-12">
                <select class="form-control" name="currency" id="currency">
                  <option value="VND" {{ ($content->currency == 'VND') ? 'selected':'' }}>VND</option>
                  <option value="USA" {{ ($content->currency == 'USA') ? 'selected':'' }}>USD</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">{{trans('Admin'.DS.'hotel.phone')}} </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="phone" name="phone"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('phone')?'parsley-error':''}}"
                       value="{{ $content->phone }}" >
                @if ($errors->has('phone'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('phone') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <!-- <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="email" id="email" name="email"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('email')?'parsley-error':''}}"
                       value="{{ $content->email }}">
                @if ($errors->has('email'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('email') }}</li>
                  </ul>
                @endif
              </div>
            </div> -->

            @if(count($data['list_service']) > 0)
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="service">{{trans('Admin'.DS.'hotel.service')}}</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  @foreach($data['list_service'] as $key => $value)
                    <div class="col-md-4 col-sm-3 col-xs-12" style="padding-left: 0px;">
                      <div class="checkbox">
                        <label style="padding-left: 0px;">
                          <input type="checkbox" class="flat" name="service[]" value="{{$key}}" {{in_array($key, $data['list_service_content']) ? 'checked':''}}> {{$value}}
                        </label>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Website
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="website" name="website" class="form-control col-md-7 col-xs-12"
                       value="{{ $content->website }}">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content_type">{{trans('Admin'.DS.'hotel.location')}} <span
                  class="required">*</span>
              </label>
              <div class="col-md-2 col-sm-3 col-xs-12">
                <select class="form-control {{$errors->has('country')?'parsley-error':''}}" name="country" id="country"
                        onchange="getLocationAjax(this.value,'city')" >
                  <option value="">-- {{trans('Admin'.DS.'hotel.country')}} --</option>
                  @foreach($data['list_country'] as $value => $name)
                    <option value="{{$value}}" {{ $content->country == $value ? 'selected' : '' }}>{{$name}}</option>
                  @endforeach
                </select>
                @if ($errors->has('country') || $errors->has('city') || $errors->has('district'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{trans('Admin'.DS.'hotel.required_location')}}</li>
                  </ul>
                @endif
              </div>
              <div class="col-md-2 col-sm-3 col-xs-12">
                <select class="form-control {{$errors->has('city')?'parsley-error':''}}" name="city" id="city"
                        onchange="getLocationAjax(this.value,'district')" >
                  <option value="">-- {{trans('Admin'.DS.'hotel.city')}} --</option>
                  @foreach($data['list_city'] as $value => $name)
                    <option value="{{$value}}" {{ $content->city == $value ? 'selected' : '' }}>{{$name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-2 col-sm-3 col-xs-12">
                <select class="form-control {{$errors->has('district')?'parsley-error':''}}" name="district"
                        id="district" >
                  <option value="">-- {{trans('Admin'.DS.'hotel.district')}} --</option>
                  @foreach($data['list_districts'] as $value => $name)
                    <option value="{{$value}}" {{ $content->district == $value ? 'selected' : '' }}>{{$name}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
              <div class="col-md-6 col-sm-6 col-xs-12"> {{trans('Admin'.DS.'hotel.drag_location')}}</div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">{{trans('Admin'.DS.'hotel.address')}} <span
                  class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="address" name="address"
                       class="form-control col-md-7 col-xs-12 {{($errors->has('address') || $errors->has('lat') || $errors->has('lng'))?'parsley-error':''}}"
                       value="{{ $content->address }}" >
                @if ($errors->has('address') || $errors->has('lat') || $errors->has('lng'))
                  <li class="parsley-required">{{trans('Admin'.DS.'hotel.address_not_found')}} </li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
              <div class="col-md-6 col-sm-6 col-xs-12" id="google_map"
                   style="width: 520px;height: 300px; margin-left: 10px;">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'hotel.tags')}} </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="tags_1" name="tag" type="text" class="tags form-control" value="{{$content->tag}}"/>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">{{trans('Admin'.DS.'hotel.description')}} </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea type="text" id="description" name="description"
                          class="form-control col-md-7 col-xs-12">{{ $content->description?$content->description:'' }}</textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="avatar">{{trans('Admin'.DS.'hotel.avatar')}}  <span
                  class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="file" id="avatar" name="avatar" accept="image/gif, image/jpeg, image/png"
                       onchange="readURL(this,'list_image_avatar')"/>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
              <div class="col-md-6 col-sm-6 col-xs-12" id="list_image_avatar">
                <img style="height: 90px; width: 90px; border: 1px solid #000; margin: 2px"
                     src="{{$content->avatar}}">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="image_space">{{trans('Admin'.DS.'hotel.image_space')}}
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="file" id="image_space" name="image_space[]" accept="image/gif, image/jpeg, image/png"
                       multiple
                       onchange="readURL(this,'list_image_space')"/>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
              <div class="col-md-6 col-sm-6 col-xs-12" id="list_image_space">
                @foreach($data['list_image_space'] as $src)
                  <img style="height: 90px; width: 90px; border: 1px solid #000; margin: 2px"
                       src="{{$src}}">
                @endforeach
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="image_menu">Image Menu
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="file" id="image_menu" name="image_menu[]" accept="image/gif, image/jpeg, image/png"
                       multiple
                       onchange="readURL(this,'list_image_menu')"/>
              </div>
            </div>

            @if(in_array($content->moderation, array('reject_publish','publish','trash')))
              @if($data['role_user'] < 4)
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="moderation">{{trans('Admin'.DS.'hotel.moderation')}}
                  </label>
                  <div class="col-md-3 col-sm-3 col-xs-12">
                    <select class="form-control" name="moderation" id="moderation">
                      <option value="in_progress" {{$content->moderation == 'in_progress' ? 'selected' : ''}}>{{trans('Admin'.DS.'hotel.in_progress')}}</option>
                      <option value="request_publish" {{$content->moderation == 'request_publish' ? 'selected' : ''}}>{{trans('Admin'.DS.'hotel.request_publish')}}</option>
                      <option value="reject_publish" {{$content->moderation == 'reject_publish' ? 'selected' : ''}}>{{trans('Admin'.DS.'hotel.reject_publish')}}</option>
                      <option value="publish" {{$content->moderation == 'publish' ? 'selected' : ''}}>{{trans('Admin'.DS.'hotel.publish')}}</option>
                      <option value="trash" {{$content->moderation == 'trash' ? 'selected' : ''}}>{{trans('Admin'.DS.'hotel.trash')}}</option>
                    </select>
                  </div>
                </div>
              @endif
            @else
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="moderation">{{trans('Admin'.DS.'hotel.moderation')}}
                </label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <select class="form-control" name="moderation" id="moderation">
                    <option value="in_progress" {{$content->moderation == 'in_progress' ? 'selected' : ''}}>{{trans('Admin'.DS.'hotel.in_progress')}}</option>
                    <option value="request_publish" {{$content->moderation == 'request_publish' ? 'selected' : ''}}>{{trans('Admin'.DS.'hotel.request_publish')}}</option>
                    @if($data['role_user'] < 4)
                      <option value="reject_publish" {{$content->moderation == 'reject_publish' ? 'selected' : ''}}>{{trans('Admin'.DS.'hotel.reject_publish')}}</option>
                      <option value="publish" {{$content->moderation == 'publish' ? 'selected' : ''}}>{{trans('Admin'.DS.'hotel.publish')}}</option>
                      <option value="trash" {{$content->moderation == 'trash' ? 'selected' : ''}}>{{trans('Admin'.DS.'hotel.trash')}}</option>
                    @endif
                  </select>
                </div>
              </div>
            @endif

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="active">Status
              </label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <select class="form-control" name="active" id="active">
                  <option value="0" {{$content->active == 0 ? 'selected' : ''}}>None Active</option>
                  <option value="1" {{$content->active == 1 ? 'selected' : ''}}>Active</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <input type="hidden" value="{{$content->lat}}" name="lat" id="lat">
              <input type="hidden" value="{{$content->lng}}" name="lng" id="lng">
            </div>

            <div class="ln_solid"></div>
            <div class="form-group" style="text-align: left;"><h2>SEO</h2></div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="seo_keyword">KeyWord</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea type="text" id="seo_keyword" name="seo_keyword" maxlength="80"
                          class="form-control col-md-7 col-xs-12">{{isset($data['seo']) ? $data['seo']->key_word : ''}}</textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="seo_description">Description</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <textarea type="text" id="seo_description" name="seo_description" maxlength="160"
                          class="form-control col-md-7 col-xs-12">{{isset($data['seo']) ? $data['seo']->description : ''}}</textarea>
              </div>
            </div>

            <div class="ln_solid"></div>

            @if(in_array($content->moderation, array('reject_publish','publish','trash')))
              @if($data['role_user'] != 4)
                <div class="form-group">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'hotel.update_content')}}</button>
                  </div>
                </div>
              @endif
            @else
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'hotel.update_content')}}</button>
                </div>
              </div>
            @endif

          </form>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript"
          src="https://maps.google.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyAW2C_vPiyDmY8pQXk9-LYTQe58B526te4"></script>

  <script type="text/javascript">
    var geocoder = new google.maps.Geocoder();
    var marker = new google.maps.Marker();
    var infowindow = new google.maps.InfoWindow({
      size: new google.maps.Size(150, 50)
    });
    var base_url = {!! json_encode(url('/')) !!};
    var lat = {!! json_encode($content->lat) !!};
    var lng = {!! json_encode($content->lng) !!};
    var old_src = $('#list_image_avatar img')[0].src;

    function initialize() {
      var latLng = new google.maps.LatLng(lat, lng);
      map = new google.maps.Map(document.getElementById('google_map'), {
        zoom: 15,
        center: latLng,
        zoomControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        draggable: true
      });

      marker = new google.maps.Marker({
        position: latLng,
        map: map,
        draggable: true
      });
      google.maps.event.addListener(marker, 'dragend', function () {
        geocodePosition(marker.getPosition());
        map.setCenter(marker.getPosition());
      });


      var input = document.getElementById('address');
      autocomplete = new google.maps.places.Autocomplete(input);

      google.maps.event.addListener(autocomplete, 'place_changed', function () {
        codeAddress(autocomplete.getPlace().formatted_address);
      });

      google.maps.event.addListener(marker, 'click', function () {
        infowindow.setContent('{{$content->address}}');
        infowindow.open(map, marker);
      });

      google.maps.event.trigger(marker, 'click');

    };
    google.maps.event.addDomListener(window, "load", initialize);

    function readURL(input, type) {
      $('#' + type).text('');

      if (type == 'list_image_avatar' && !input.files[0]) {
        var img_avatar = $('<img style="height: 90px; width: 90px; border: 1px solid #000; margin: 2px">');
        img_avatar.attr('src', old_src);
        img_avatar.appendTo('#' + type);
      }

      for (var i = 0; i < input.files.length; i++) {
        if (input.files[i]) {
          var reader = new FileReader();

          reader.onload = function (e) {
            var img = $('<img style="height: 90px; width: 90px; border: 1px solid #000; margin: 2px">');
            img.attr('src', e.target.result);
            img.appendTo('#' + type);
          }
          reader.readAsDataURL(input.files[i]);
        }
      }
    }

    function getLocationAjax(value, type) {
      var CSRF_TOKEN = $('input[name="_token"]').val();

      if (type == 'city') {
        $('#district').html('<option value="">-- {{trans('Admin'.DS.'hotel.district')}} --</option>');
      }
      $.ajax({
        type: "POST",
        data: {value: value, type: type, _token: CSRF_TOKEN},
        url: base_url + '/admin/content/ajaxLocation',
        success: function (data) {
          $("#" + type).html(data);
        }
      })
    }

    function getCategoryItem(value) {
      var CSRF_TOKEN = $('input[name="_token"]').val();
      $.ajax({
        type: "POST",
        data: {value: value, _token: CSRF_TOKEN},
        url: base_url + '/admin/content/ajaxCategoryItem',
        success: function (data) {

          $("#category_item").html(data);
          $('#category_item').selectpicker('refresh');
        }
      })
    }

    function codeAddress(address) {
      geocoder.geocode({
        'address': address
      }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {

          $("#google_map").show();
          initialize();

          map.setCenter(results[0].geometry.location);
          if (marker) {
            marker.setMap(null);
            if (infowindow) infowindow.close();
          }

          marker = new google.maps.Marker({
            map: map,
            draggable: true,
            position: results[0].geometry.location
          });

          google.maps.event.addListener(marker, 'dragend', function () {
            geocodePosition(marker.getPosition());
            map.setCenter(marker.getPosition());
          });

          google.maps.event.addListener(marker, 'click', function () {
            if (marker.formatted_address) {
              infowindow.setContent(marker.formatted_address);
            } else {
              infowindow.setContent(address);
            }
            infowindow.open(map, marker);
          });

          $("#lat").val(results[0].geometry.location.lat().toFixed(6));
          $("#lng").val(results[0].geometry.location.lng().toFixed(6));
          google.maps.event.trigger(marker, 'click');
        } else {
          $("#lat").val('');
          $("#lng").val('');
        }
      });
    }

    function geocodePosition(pos) {
      geocoder.geocode({
        latLng: pos
      }, function (responses) {
        if (responses && responses.length > 0) {
          marker.formatted_address = responses[0].formatted_address;
          //$("#address").val(responses[0].formatted_address);
          $("#lat").val(marker.getPosition().lat().toFixed(6));
          $("#lng").val(marker.getPosition().lng().toFixed(6));
        } else {
          marker.formatted_address = '{{trans('Admin'.DS.'hotel.undefined_address')}}';
        }
//        infowindow.setContent(marker.formatted_address);
//        infowindow.open(map, marker);
      });
    }

    $(function () {
      $("#name").on("keyup", function () {
        var name = $(this).val();
        $("#alias").val(str_slug(name));
      });

      $('#category_item').selectpicker({liveSearch: true}).trigger("change");
      $('#group').selectpicker({
        liveSearch: true,
      });

      $('#open_from').datetimepicker({
        format: 'LT'
      });
      $('#open_to').datetimepicker({
        format: 'LT'
      });
    })

  </script>
@endsection
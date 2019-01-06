@extends('Admin..layout_admin.master_admin')

@section('content')
  <!-- page content -->
  <div class="row">
    <form id="form-content" method="post" onsubmit="checkLinkIsVideo()" action="{{route('add_food_content')}}" enctype="multipart/form-data"
                data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" autocomplete="off">
      <div class="col-md-8 col-sm-8 col-xs-12">
        <div class="x_panel">
          <!-- <div class="x_title">
            <h2>Form Create {{ucwords($data['type'])}} Content</h2>
            <ul class="nav navbar-right panel_toolbox">
              <a href="{{route('list_content')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
            </ul>
            <div class="clearfix"></div>
          </div> -->
          <div class="x_content">
            
              {{ csrf_field() }}

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'content.name')}} <span
                    class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" id="name" name="name"
                         class="form-control col-md-7 col-xs-12 {{$errors->has('name')?'parsley-error':''}}"
                         value="{{ old('name') }}" >
                  @if ($errors->has('name'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{ $errors->first('name') }}</li>
                    </ul>
                  @endif
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alias">{{trans('Admin'.DS.'content.alias')}} <span
                    class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" id="alias" name="alias"
                         class="form-control col-md-7 col-xs-12 {{$errors->has('alias')?'parsley-error':''}}"
                         value="{{ old('alias') }}" >
                  @if ($errors->has('alias'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{ $errors->first('alias') }}</li>
                    </ul>
                  @endif
                </div>
              </div>

              <input type="hidden" value="{{$data['id_category']}}" name="id_category" id="id_category">

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_item">{{trans('Admin'.DS.'content.cat_item')}}<span
                    class="required">*</span></label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <select class="form-control" name="category_item[]" id="category_item" multiple >
                  @foreach($data['list_category_item'] as $value => $name)
                    <option value="{{$value}}">{{$name}}</option>
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="group">{{trans('Admin'.DS.'content.group')}}</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <select class="form-control" name="group" id="group">
                    <option value="">{{trans('Admin'.DS.'content.nothing_selected')}}</option>
                    @foreach($data['list_group'] as $value => $name)
                      <option value="{{$value}}">{{$name}}</option>
                    @endforeach
                  </select>
                  @if ($errors->has('group'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{ $errors->first('group') }}</li>
                    </ul>
                  @endif
                </div>
              </div>

              <!-- <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="open_form">{{trans('global.open')}} <span
                    class="required">*</span>
                </label>
                <div class="col-md-2 col-sm-2 col-xs-12">
                  <input type="text" id="open_from" name="open_from"
                         class="form-control col-md-7 col-xs-12 {{$errors->has('open_from')?'parsley-error':''}}"
                         value="{{ old('open_from') }}" placeholder="{{trans('global.from')}}" />
                  @if ($errors->has('open_form') || $errors->has('open_to'))
                    <ul class="parsley-errors-list filled">
                      <li
                        class="parsley-required">{{ $errors->first('open_form') ? $errors->first('open_form') : $errors->first('open_to') }}</li>
                    </ul>
                  @endif
                </div>

                <div class="col-md-2 col-sm-2 col-xs-12">
                  <input type="text" id="open_to" name="open_to"
                         class="form-control col-md-7 col-xs-12  {{$errors->has('open_to')?'parsley-error':''}}"
                         value="{{ old('open_to') }}" placeholder="{{trans('global.to')}}" >
                </div>

                <div class="col-md-2 col-sm-2 col-xs-12">
                  <input type="checkbox" name="custom_date_open" {{ old("custom_date_open")?'checked':'' }} onchange='showCustomOpen()'> Custom Open Time
                </div>
              </div> -->

              <div class="form-group" id="custom_open">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="group">{{trans('global.open')}}<span class="required">*</span></label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <div class="col-md-3"><label for="">{{trans('Admin'.DS.'content.from_date')}}</label></div>
                  <div class="col-md-3"><label for="">{{trans('Admin'.DS.'content.to_date')}}</label></div>
                  <div class="col-md-3"><label for="">{{trans('Admin'.DS.'content.from_hour')}}</label></div>
                  <div class="col-md-3"><label for="">{{trans('Admin'.DS.'content.to_hour')}}</label></div>
                </div>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <div class="col-md-3">
                    <select class="form-control" name="date_open[0][from_date]" id="">
                      <option value="1">{{trans('Admin'.DS.'content.monday')}}</option>
                      <option value="2">{{trans('Admin'.DS.'content.tuesday')}}</option>
                      <option value="3">{{trans('Admin'.DS.'content.wednesday')}}</option>
                      <option value="4">{{trans('Admin'.DS.'content.thursday')}}</option>
                      <option value="5">{{trans('Admin'.DS.'content.friday')}}</option>
                      <option value="6">{{trans('Admin'.DS.'content.saturday')}}</option>
                      <option value="0">{{trans('Admin'.DS.'content.sunday')}}</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <select class="form-control" name="date_open[0][to_date]" id="">
                      <option value="1">{{trans('Admin'.DS.'content.monday')}}</option>
                      <option value="2">{{trans('Admin'.DS.'content.tuesday')}}</option>
                      <option value="3">{{trans('Admin'.DS.'content.wednesday')}}</option>
                      <option value="4">{{trans('Admin'.DS.'content.thursday')}}</option>
                      <option value="5">{{trans('Admin'.DS.'content.friday')}}</option>
                      <option value="6">{{trans('Admin'.DS.'content.saturday')}}</option>
                      <option value="0">{{trans('Admin'.DS.'content.sunday')}}</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <input class="form-control choose_hour" type="text" name="date_open[0][from_hour]" value="" required="">
                  </div>
                  <div class="col-md-3">
                    <input class="form-control choose_hour" type="text" name="date_open[0][to_hour]" value="" required="">
                  </div>
                </div>
                <div id="append_custom_open">

                </div>
                <div class="col-md-9 col-md-offset-3 text-center col-xs-12" id="add_custom_open">
                  <br/>
                  <button class="btn btn-default" type="button" onclick="addCustomOpen()">
                   {{trans('Admin'.DS.'content.add_hour_open')}}
                  </button>
                  <br/>
                </div>
              </div>
              <br/>
              {{--<div class="form-group">--}}
                {{--<label class="control-label col-md-3 col-sm-3 col-xs-12" for="price_form">{{trans('Admin'.DS.'content.price')}} <span--}}
                    {{--class="required">*</span>--}}
                {{--</label>--}}
                {{--<div class="col-md-3 col-sm-3 col-xs-12 location-div">--}}
                  {{--<input type="number" id="price_from" name="price_from" min="1" onchange="min_number_price()"--}}
                         {{--class="form-control col-md-7 col-xs-12 {{$errors->has('price_from')?'parsley-error':''}}"--}}
                         {{--value="{{ old('price_from') }}" placeholder="{{trans('global.from')}}" >--}}
                  {{--@if ($errors->has('price_from') || $errors->has('price_to'))--}}
                    {{--<ul class="parsley-errors-list filled">--}}
                      {{--<li--}}
                        {{--class="parsley-required">{{ $errors->first('price_from') ? $errors->first('price_from') : $errors->first('price_to') }}</li>--}}
                    {{--</ul>--}}
                  {{--@endif--}}
                {{--</div>--}}
                {{--<div class="col-md-3 col-sm-3 col-xs-12 location-div">--}}
                  {{--<input type="number" id="price_to" name="price_to" min="1" onchange="max_number_price()"--}}
                         {{--class="form-control col-md-7 col-xs-12 {{$errors->has('price_to')?'parsley-error':''}}"--}}
                         {{--value="{{ old('price_to') }}" placeholder="{{trans('global.to')}}" >--}}
                {{--</div>--}}
                {{--<div class="col-md-3 col-sm-3 col-xs-12 location-div">--}}
                  {{--<select class="form-control" name="currency" id="currency">--}}
                    {{--<option value="VND">VND</option>--}}
                    {{--<option value="USD">USD</option>--}}
                  {{--</select>--}}
                {{--</div>--}}
              {{--</div>--}}

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">{{trans('Admin'.DS.'content.phone')}} </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="phone" maxLength="20" id="phone" name="phone"
                         class="form-control col-md-7 col-xs-12 {{$errors->has('phone')?'parsley-error':''}}"
                         value="{{ old('phone') }}" placeholder="0123456789">
                  @if ($errors->has('phone'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{ $errors->first('phone') }}</li>
                    </ul>
                  @endif
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="email" id="email" name="email"
                         class="form-control col-md-7 col-xs-12 {{$errors->has('email')?'parsley-error':''}}"
                         value="{{ old('email') }}">
                  @if ($errors->has('email'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{ $errors->first('email') }}</li>
                    </ul>
                  @endif
                </div>
              </div>

              @if(count($data['list_service']) > 0)
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="service">{{trans('Admin'.DS.'content.service')}}</label>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    @foreach($data['list_service'] as $value)
                      <div class="col-md-4 col-sm-3 col-xs-12" style="padding-left: 0px;">
                        <div class="checkbox">
                          <label style="padding-left: 0px;">
                            <input type="checkbox" class="flat" name="service[]" value="{{$value->id_service_item}}"> {{$value->_service_item->name}}
                          </label>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif

              <div class="form-group" style="display: none;">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Website
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="url" id="website" name="website" class="form-control col-md-7 col-xs-12" value="{{ old('website') }}">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content_type">{{trans('Admin'.DS.'content.location')}} <span
                    class="required">*</span>
                </label>
                <div class="col-md-3 col-sm-3 col-xs-12 location-div">
                  <select class="form-control {{$errors->has('country')?'parsley-error':''}}" name="country" id="country"
                          onchange="getLocationAjax(this.value,'city')" >
                    <option value="">-- {{trans('Admin'.DS.'content.country')}} --</option>
                    @foreach($data['list_country'] as $value => $name)
                      <option value="{{$value}}">{{$name}}</option>
                    @endforeach
                  </select>
                  @if ($errors->has('country') || $errors->has('city') || $errors->has('district'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{trans('valid.country_required')}}</li>
                    </ul>
                  @endif
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12 location-div">
                  <select class="form-control {{$errors->has('city')?'parsley-error':''}}" name="city" id="city"
                          onchange="getLocationAjax(this.value,'district')" >
                    <option value="">-- {{trans('Admin'.DS.'content.city')}} --</option>
                  </select>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12 location-div">
                  <select class="form-control {{$errors->has('district')?'parsley-error':''}}" name="district"
                          id="district" >
                    <option value="">-- {{trans('Admin'.DS.'content.district')}} --</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                <div class="col-md-9 col-sm-9 col-xs-12"> {{trans('Admin'.DS.'content.drag_location')}}</div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">{{trans('Admin'.DS.'content.address')}} <span
                    class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" id="address" name="address"
                         class="form-control col-md-7 col-xs-12 {{($errors->has('address') || $errors->has('lat') || $errors->has('lng'))?'parsley-error':''}}"
                          placeholder="{{trans('Admin'.DS.'content.enter_address')}}">
                  @if ($errors->has('address') || $errors->has('lat') || $errors->has('lng'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{trans('Admin'.DS.'content.address_not_found')}} </li>
                    </ul>
                  @endif
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                <div class="col-md-9 col-sm-9 col-xs-12" id="google_map"
                     style="max-width: 520px;height: 300px; display: none">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'content.tags')}}</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input id="tags_1" name="tag" type="text" class="tags form-control" value=""/>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">{{trans('Admin'.DS.'content.description')}}</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <textarea type="text" id="description" name="description"
                            class="form-control col-md-7 col-xs-12">{{ old('description') }}</textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="avatar">{{trans('Admin'.DS.'content.avatar')}} <span
                    class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="file" id="avatar" name="avatar" accept="image/*"
                         onchange="readURL(this,'list_image_avatar')" />
                  @if ($errors->has('avatar'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{trans('Admin'.DS.'content.invalid_image')}} </li>
                    </ul>
                  @endif
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                <div class="col-md-9 col-sm-9 col-xs-12" id="list_image_avatar">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="image_space">{{trans('Admin'.DS.'content.image_space')}}
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="file" id="image_space" name="image_space[]" accept="image/*"
                         multiple
                         onchange="readURL(this,'list_image_space')"/>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                <div class="col-md-9 col-sm-9 col-xs-12" id="list_image_space">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="image_menu">{{trans('global.image')}}
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="file" id="image_menu" name="image_menu[]" accept="image/*"
                         multiple
                         onchange="readURL(this,'list_image_menu')"/>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                <div class="col-md-9 col-sm-9 col-xs-12" id="list_image_menu">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">{{trans('Admin'.DS.'content.link')}} </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" name="link[]" class="form-control col-md-7 col-xs-12">
                </div>
                <span><i class="remove_custom_open fa fa-remove" onclick="removeCustomOpen(this)"></i></span>
              </div>

              <div id="append_custom_link"></div>

              <div id="err_link_video" class="form-group" style="display: none;">
                <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{trans('valid.invalid_video_link')}}</li>
                  </ul>
                </div>
              </div>

              <div style="text-align: center">
                <br>
                <button class="btn btn-default" type="button" onclick="addCustomLink()">
                  {{trans('Admin'.DS.'content.add_link')}}
                </button>
                <br>
              </div>
              </br>

              

              <div class="form-group">
                <input type="hidden" value="" name="lat" id="lat">
                <input type="hidden" value="" name="lng" id="lng">
              </div>

              <div class="form-group">
                <h4 class="text-center" style="margin-bottom: 25px;">{{trans('Admin'.DS.'content.product')}}</span> <button type="button" class="btn btn-primary" onclick="addGroup()">{{trans('Admin'.DS.'content.add_product_group')}}</button></h4>
                <div class="col-xs-12" id="list_group_product">
                  @if(old('product'))
                    @foreach(old('product') as $index_group => $group)
                      <div class="group_product" id="group_product_{{$index_group}}" style="">
                        <div class="form-group">
                          <label class="control-label col-md-4 col-sm-4 col-xs-12">
                          {{trans('Admin'.DS.'content.product_group')}}
                          </label>
                          <div class="col-md-7 col-sm-7 col-xs-12">
                            <input class="form-control" type="text" name="product[{{$index_group}}][group_name]" value="{{$group['group_name']}}"/>
                          </div>
                          <div class="col-md-1 col-sm-1 col-xs-12">
                            <a class="remove_custom_open" onclick="removeGroupProduct({{$index_group}})"><i class="fa fa-remove"></i></a>
                          </div>
                        </div>
                        <div class="header_product row">
                          <div class="col-md-4"><label>{{trans('Admin'.DS.'content.name')}}</label></div>
                          <div class="col-md-4"><label>{{trans('Admin'.DS.'content.price')}}</label></div>
                          <div class="col-md-4"><label>{{trans('Admin'.DS.'content.image')}}</label></div>
                        </div>
                        <div id="list_product_{{$index_group}}">
                          @php $count = 0; @endphp
                          @foreach($group as $key => $product)
                          @if($key !== 'group_name')
                          <div class="item_product row">
                            <input type="hidden" name="product[{{$index_group}}][{{$key}}][id]" value="0" placeholder="">
                            <div class="col-xs-4">
                              <input type="text" value="{{$product['name']}}" class="form-control" name="product[{{$index_group}}][{{$key}}][name]" placeholder="Tên">
                            </div>
                            <div class="col-xs-2">
                              <input type="number" value="{{$product['price']}}" min="0" class="form-control" name="product[{{$index_group}}][{{$key}}][price]" placeholder="Giá">
                            </div>
                            <div class="col-xs-2">
                              <select class="form-control" name="product[{{$index_group}}][{{$key}}][currency]">
                                <option value="VND" {{$product['currency']=='VND'?'selected':''}}>VND</option>
                                <option value="USD" {{$product['currency']=='USD'?'selected':''}}>USD</option>
                              </select>
                            </div>
                            <div class="col-xs-2">

                              <input type="file" class="" value="" name="product[{{$index_group}}][{{$key}}][image]" onchange="readImageProduct(this)" style="width: 88px;">

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
              </div>

          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
              <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="moderation">{{trans('Admin'.DS.'content.moderation')}}
                </label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <select class="form-control" name="moderation" id="moderation">
                    <option value="in_progress">{{trans('Admin'.DS.'content.in_progress')}}</option>
                    <option value="request_publish">{{trans('Admin'.DS.'content.request_publish')}}</option>
                    @if($data['role_user'] < 4)
                      <option value="reject_publish">{{trans('Admin'.DS.'content.reject_publish')}}</option>
                      <option value="publish">{{trans('Admin'.DS.'content.publish')}}</option>
                      <option value="un_publish">{{trans('Admin'.DS.'content.unpublish')}}</option>
                      <option value="trash">{{trans('Admin'.DS.'content.trash')}}</option>
                    @endif
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-4 col-xs-12">
                {{trans('Admin'.DS.'content.create_at')}}</label>
                <div class="col-md-8 col-xs-12">
                  <label class="control-label">{{date('d-m-Y H:i:s')}}</label>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-4 col-xs-12">
                {{trans('Admin'.DS.'content.create_by')}}</label>
                <div class="col-md-8 col-xs-12">
                  <label class="control-label">{{Auth::guard('web')->user()->full_name}}</label>
                </div>
              </div>
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                  <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'content.add_content')}}</button>
                </div>
              </div>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection
@section('CSS')
<style type="text/css" media="screen">
  .remove_custom_open{
    position: absolute;
    cursor: pointer;
    margin-top: 10px;
  }
  .item_custom_open{
    margin-top: 10px;
  }
  .item_product{
    margin-top:10px;
  }
  .group_product{
    padding:15px 0 25px 0; 
    border-bottom: 1px solid #aaa;
  }
</style>
@endsection

@section('JS')
  <script type="text/javascript"
          src="https://maps.google.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyA4_lZ8uw0hpJfJxVHnK_vBBXZckA-0Tr0"></script>

  <script src="{{asset('backend/assets/custom/js/validate.link.social.js')}}"></script>
  <script type="text/javascript">
    var geocoder = new google.maps.Geocoder();
    var marker = new google.maps.Marker();
    var infowindow = new google.maps.InfoWindow({
      size: new google.maps.Size(150, 50)
    });
    var base_url = {!! json_encode(url('/')) !!};


    function initialize() {
      var latLng = new google.maps.LatLng(10.773234, 10.773234);
      map = new google.maps.Map(document.getElementById('google_map'), {
        zoom: 15,
        center: latLng,
        zoomControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        draggable: true
      });

      var input = document.getElementById('address');
      autocomplete = new google.maps.places.Autocomplete(input);

      google.maps.event.addListener(autocomplete, 'place_changed', function () {
        codeAddress(autocomplete.getPlace().formatted_address);
      });

    };
    google.maps.event.addDomListener(window, "load", initialize);

    function readURL(input, type) {
      $('#' + type).text('');

      for (var i = 0; i < input.files.length; i++) {
        if (input.files[i]) {
          var reader = new FileReader();

          reader.onload = function (e) {
            var img = $('<img style="height: 90px; width: 90px; border: 1px solid #000; margin: 2px">');
            img.attr('src', e.target.result);
            img.appendTo('#' + type);
          };
          reader.readAsDataURL(input.files[i]);
        }
      }
    }

    function getLocationAjax(value, type) {
      var CSRF_TOKEN = $('input[name="_token"]').val();

      if (type == 'city') {
        $('#district').html('<option value="">-- {{trans('Admin'.DS.'content.district')}} --</option>');
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

          $("#address").val(addr);
          $("#lat").val(results[0].geometry.location.lat().toFixed(6));
          $("#lng").val(results[0].geometry.location.lng().toFixed(6));
          google.maps.event.trigger(marker, 'click');
        } else {
          $("#address").val('');
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
          $("#lat").val(marker.getPosition().lat().toFixed(6));
          $("#lng").val(marker.getPosition().lng().toFixed(6));
        } else {
          marker.formatted_address = '{{trans('Admin'.DS.'content.undefined_address')}}';
        }
      });
    }

    function min_number_price()
    {
      if($("#price_to").val() != '')
      {
        if (parseInt($("#price_from").val()) >= parseInt($("#price_to").val())) {
          $("#price_from").val('');
alert("{{trans('valid.price_from_smaller_price_to')}}");
        }
      }
    }

    function max_number_price()
    {
      if (parseInt($("#price_to").val()) <= parseInt($("#price_from").val())) {
        $("#price_to").val('');
alert("{{trans('valid.price_from_smaller_price_to')}}");
      }
    }

    $(function () {
      $("#name").on("keyup", function () {
        var name = $(this).val();
        $("#alias").val(str_slug(name));
      })

      $('#category_item').selectpicker({
        liveSearch: true,
      });

      $('.choose_hour').datetimepicker({
        format: 'HH:mm',
        defaultDate: moment().hours(8).minutes(0).seconds(0).milliseconds(0)
      });

      $('#open_from').datetimepicker({
        format: 'HH:mm',
      });
      $('#open_to').datetimepicker({
        format: 'HH:mm',
      });

      // $("#phone").on("blur", function(e) {
      //   var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,4})(\d{0,4})/);
      //   e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
      // });
    })

    function showCustomOpen(){
      $("#custom_open").toggle();
    }

    function addCustomOpen(){
      var index = $(".item_custom_open").length;
      index++;

      html = '<div class="col-md-9 col-sm-9 col-md-offset-3 col-xs-12 item_custom_open">';
      html +='          <div class="col-md-3">';
      html +='            <select class="form-control" name="date_open['+index+'][from_date]" id="">';
      html +='              <option value="1">{{trans('Admin'.DS.'content.monday')}}</option>';
      html +='              <option value="2">{{trans('Admin'.DS.'content.tuesday')}}</option>';
      html +='              <option value="3">{{trans('Admin'.DS.'content.wednesday')}}</option>';
      html +='              <option value="4">{{trans('Admin'.DS.'content.thursday')}}</option>';
      html +='              <option value="5">{{trans('Admin'.DS.'content.friday')}}</option>';
      html +='              <option value="6">{{trans('Admin'.DS.'content.saturday')}}</option>';
      html +='              <option value="0">{{trans('Admin'.DS.'content.sunday')}}</option>';
      html +='            </select>';
      html +='          </div>';
      html +='          <div class="col-md-3">';
      html +='            <select class="form-control" name="date_open['+index+'][to_date]" id="">';
      html +='              <option value="1">{{trans('Admin'.DS.'content.monday')}}</option>';
      html +='              <option value="2">{{trans('Admin'.DS.'content.tuesday')}}</option>';
      html +='              <option value="3">{{trans('Admin'.DS.'content.wednesday')}}</option>';
      html +='              <option value="4">{{trans('Admin'.DS.'content.thursday')}}</option>';
      html +='              <option value="5">{{trans('Admin'.DS.'content.friday')}}</option>';
      html +='              <option value="6">{{trans('Admin'.DS.'content.saturday')}}</option>';
      html +='              <option value="0">{{trans('Admin'.DS.'content.sunday')}}</option>';
      html +='            </select>';
      html +='          </div>';
      html +='          <div class="col-md-3">';
      html +='            <input class="form-control choose_hour" type="text" name="date_open['+index+'][from_hour]" value="" >';
      html +='          </div>';
      html +='          <div class="col-md-3">';
      html +='            <input class="form-control choose_hour" type="text" name="date_open['+index+'][to_hour]" value="" >';
      html +='          </div>';
      html +='  <span><i class="remove_custom_open fa fa-remove" onclick="removeCustomOpen(this)"></i></span>';
      html +='</div>';
      $("#append_custom_open").append(html);

      $('.choose_hour').datetimepicker({
        format: 'HH:mm',
        defaultDate: moment().hours(8).minutes(0).seconds(0).milliseconds(0)
      });
    }

    function removeCustomOpen(obj){
      $(obj).parent().parent().remove();
    }

    function addCustomLink()
    {
      html_link = '<div class="form-group" class="item_custom_link">';
      html_link += '<label class="control-label col-md-3 col-sm-3 col-xs-12"></label>';
      html_link += '<div class="col-md-9 col-sm-9 col-xs-12">';
      html_link += '<input type="text" name="link[]" class="form-control col-md-7 col-xs-12">';
      html_link += '</div>';
      html_link += '<span><i class="remove_custom_open fa fa-remove" onclick="removeCustomOpen(this)"></i></span>';
      html_link += '</div>';

      $("#append_custom_link").append(html_link);
    }

    function readImageProduct(input) {
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

    function addProduct(index_group){
      var index = $(".item_product").length+1;

      $('.header_product').show();
      html='';
      html+='<div class="item_product row">';
      html+='<input type="hidden" name="product['+index_group+']['+index+'][id]" value="0" placeholder="">';
      html+='<div class="col-xs-4">';
      html+='<input type="text" class="form-control" name="product['+index_group+']['+index+'][name]"    placeholder="{{trans('Admin'.DS.'content.name')}}">';
      html+='</div>';
      html+='<div class="col-xs-2">';
      html+='<input type="number" min="0" class="form-control" name="product['+index_group+']['+index+'][price]" placeholder="{{trans('Admin'.DS.'content.price')}}">';
      html+='</div>';
      html+='<div class="col-xs-2">';
      html+='<select class="form-control" name="product['+index_group+']['+index+'][currency]">';
      html+=' <option value="VND">VND</option>';
      html+=' <option value="USD">USD</option>';
      html+='</select>';
      html+='</div>';
      html+='<div class="col-xs-2">';
      html+='<input type="file" class="" name="product['+index_group+']['+index+'][image]" onchange="readImageProduct(this)" style="width:88px;">';
      html+='</div>';
      html+='<div class="col-xs-2 img_product text-center">';
      html+='</div>';
      // html+='<div class="col-xs-1">';
      html+='<a class="remove_custom_open" onclick="removeProduct(this)"><i class="fa fa-remove"></i></a>';
      // html+='</div>';
      html+='</div>';
      $("#list_product_"+index_group).append(html);
    }

    function removeProduct(obj){
      $(obj).parent().remove();
      if($(".item_product").length==0){
        $('.header_product').hide();
      }
    }

    function removeGroupProduct(index){
      $('#group_product_'+index).remove();
    }

    function addGroup(){
      var index = $(".item_product").length+1;
      var html='';
      html+='<div class="group_product" id="group_product_'+index+'" style="">';
      html+='<div class="form-group">';
      html+='<label class="control-label col-md-4 col-sm-4 col-xs-12">';
      html+='{{trans('Admin'.DS.'content.product_group')}}';
      html+='</label>';
      html+='<div class="col-md-7 col-sm-7 col-xs-12">';
      html+='<input class="form-control" type="text" name="product['+index+'][group_name]"/>';
      html+='</div>';
      html+='<div class="col-md-1 col-sm-1 col-xs-12">';
      html+='<a class="remove_custom_open" onclick="removeGroupProduct('+index+')"><i class="fa fa-remove"></i></a>';
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
      html+='<div class="col-xs-4">';
      html+='<input type="text" class="form-control" name="product['+index+'][1][name]" placeholder="Tên">';
      html+='</div>';
      html+='<div class="col-xs-2">';
      html+='<input type="number" min="0" class="form-control" name="product['+index+'][1][price]" placeholder="Giá">';
      html+='</div>';
      html+='<div class="col-xs-2">';
      html+='<select class="form-control" name="product['+index+'][1][currency]">';
      html+='<option value="VND">VND</option>';
      html+='<option value="USD">USD</option>';
      html+='</select>';
      html+='</div>';
      html+='<div class="col-xs-2">';
      html+='<input type="file" class="" name="product['+index+'][1][image]" onchange="readImageProduct(this)" style="width:88px;">';
      html+='</div>';
      html+='<div class="col-xs-2 img_product text-center"></div>';
      html+='</div>';
      html+='</div>';
      html+='<div class="text-center" style="margin-top: 15px;">';
      html+='<button class="btn btn-primary" type="button" onclick="addProduct('+index+')">{{trans('Admin'.DS.'content.add_product')}}</button>';
      html+='</div>';
      html+='</div>';
      $("#list_group_product").append(html);
    }
  </script>
@endsection

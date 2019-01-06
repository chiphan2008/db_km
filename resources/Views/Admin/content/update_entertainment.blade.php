@extends('Admin..layout_admin.master_admin')

@section('content')

  <div class="row">
    <div class="col-xs-12">
      <ul class="nav navbar-right panel_toolbox">
        <a href="{{route('list_content')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
        @if($data['role_user'] < 4 || Auth::guard('web')->user()->id == $content->created_by)
          <a href="{{route('note_content',['id'=>$content->id])}}" style="float: right" class="btn btn-info">{{trans('Admin'.DS.'content.note')}}</a>
          <a href="{{route('change_owner',['id'=>$content->id])}}" style="float: right" class="btn btn-info">{{trans('Admin'.DS.'content.owner_change')}}</a>
        @endif
      </ul>
    </div>
    <form id="form-update-content" method="post" action="{{route('update_entertainment_content',['id'=>$content->id])}}"
                enctype="multipart/form-data" autocomplete="off"
                data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
      <div class="col-md-8 col-sm-8 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
              {{ csrf_field() }}
              <input type="hidden" name="url_previous"
                     value="{{isset($data['url_previous']) ? $data['url_previous'] : 'admin/content'}}">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'content.name')}} <span
                    class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alias">{{trans('Admin'.DS.'content.alias')}} <span
                    class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
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
                <div class="col-md-9 col-sm-9 col-xs-12">
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
                <div class="col-md-9 col-sm-9 col-xs-12">

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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="group">{{trans('Admin'.DS.'content.group')}}</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <select class="form-control" name="group" id="group">
                    <option value="">{{trans('Admin'.DS.'content.nothing_selected')}}</option>
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="open_form">{{trans('global.open')}} <span
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

              {{--<div class="form-group">--}}
                {{--<label class="control-label col-md-3 col-sm-3 col-xs-12" for="price_form">{{trans('Admin'.DS.'content.price')}} <span--}}
                    {{--class="required">*</span>--}}
                {{--</label>--}}
                {{--<div class="col-md-3 col-sm-3 col-xs-12 location-div">--}}
                  {{--<input type="number" id="price_from" name="price_from" required--}}
                         {{--class="form-control col-md-7 col-xs-12 {{$errors->has('price_from')?'parsley-error':''}}"--}}
                         {{--value="{{ $content->price_from }}" placeholder="{{trans('global.from')}}" >--}}
                  {{--@if ($errors->has('price_from') || $errors->has('price_to'))--}}
                    {{--<ul class="parsley-errors-list filled">--}}
                      {{--<li--}}
                        {{--class="parsley-required">{{ $errors->first('price_from') ? $errors->first('price_from') : $errors->first('price_to') }}</li>--}}
                    {{--</ul>--}}
                  {{--@endif--}}
                {{--</div>--}}
                {{--<div class="col-md-3 col-sm-3 col-xs-12 location-div">--}}
                  {{--<input type="number" id="price_to" name="price_to" required--}}
                         {{--class="form-control col-md-7 col-xs-12 {{$errors->has('price_to')?'parsley-error':''}}"--}}
                         {{--value="{{ $content->price_to }}" placeholder="{{trans('global.to')}}" >--}}
                {{--</div>--}}
                {{--<div class="col-md-3 col-sm-3 col-xs-12 location-div">--}}
                  {{--<select class="form-control" name="currency" id="currency">--}}
                    {{--<option value="VND" {{ ($content->currency == 'VND') ? 'selected':'' }}>VND</option>--}}
                    {{--<option value="USA" {{ ($content->currency == 'USA') ? 'selected':'' }}>USD</option>--}}
                  {{--</select>--}}
                {{--</div>--}}
              {{--</div>--}}

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone">{{trans('Admin'.DS.'content.phone')}} </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
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

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Email</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="email" id="email" name="email"
                         class="form-control col-md-7 col-xs-12 {{$errors->has('email')?'parsley-error':''}}"
                         value="{{ $content->email }}">
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
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="text" id="website" name="website" class="form-control col-md-7 col-xs-12"
                         value="{{ $content->website }}">
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
                      <option value="{{$value}}" {{ $content->country == $value ? 'selected' : '' }}>{{$name}}</option>
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
                    @foreach($data['list_city'] as $value => $name)
                      <option value="{{$value}}" {{ $content->city == $value ? 'selected' : '' }}>{{$name}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12 location-div">
                  <select class="form-control {{$errors->has('district')?'parsley-error':''}}" name="district"
                          id="district" >
                    <option value="">-- {{trans('Admin'.DS.'content.district')}} --</option>
                    @foreach($data['list_districts'] as $value => $name)
                      <option value="{{$value}}" {{ $content->district == $value ? 'selected' : '' }}>{{$name}}</option>
                    @endforeach
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
                         value="{{ $content->address }}" >
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
                     style="max-width: 520px;height: 300px;">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'content.tags')}}</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input id="tags_1" name="tag" type="text" class="tags form-control" value="{{$content->tag}}"/>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">{{trans('Admin'.DS.'content.description')}}</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <textarea type="text" id="description" name="description"
                            class="form-control col-md-7 col-xs-12">{{ $content->description?$content->description:'' }}</textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="avatar">{{trans('Admin'.DS.'content.avatar')}} <span
                    class="required">*</span>
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="file" id="avatar" name="avatar" accept="image/gif, image/jpeg, image/png"
                         onchange="readURL(this,'list_image_avatar')"/>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                <div class="col-md-9 col-sm-9 col-xs-12" id="list_image_avatar">
                  <img style="height: 90px; width: 90px; border: 1px solid #000; margin: 2px"
                       src="{{$content->avatar}}">
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
                <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  @if($data['role_user'] < 4)
                    @foreach($data['list_image_space'] as $src)
                      <div class="col-md-1 col-sm-1 col-xs-12" style="width: 110px; padding-left: 0px;" id="image_spaces_{{$src['id']}}">
                        <a data-fancybox="image_space" href="{{asset($src['name'])}}">
                          <img style="height: 90px; width: 90px; border: 1px solid #000; margin: 2px" src="{{$src['name']}}">
                        </a>
                        <span style="background-color: rgb(35, 179, 119);cursor: pointer;color: white;box-shadow: 2px 2px 7px rgb(74, 72, 72);position: absolute;left: 77px;" id="image_spaces_{{$src['id']}}" onClick="deleteImage('{{$src['id']}}','image_spaces')">[X]</span>
                      </div>
                    @endforeach
                  @else
                    @if(!in_array($content->moderation, array('publish','un_publish')))
                      @foreach($data['list_image_space'] as $src)
                        <div class="col-md-1 col-sm-1 col-xs-12" style="width: 110px; padding-left: 0px " id="image_spaces_{{$src['id']}}">
                          <a data-fancybox="image_space" href="{{asset($src['name'])}}">
                            <img style="height: 90px; width: 90px; border: 1px solid #000; margin: 2px" src="{{$src['name']}}">
                          </a>
                          <span style="background-color: rgb(35, 179, 119);cursor: pointer;color: white;box-shadow: 2px 2px 7px rgb(74, 72, 72);position: absolute;left: 77px;" id="image_spaces_{{$src['id']}}" onClick="deleteImage('{{$src['id']}}','image_spaces')">[X]</span>
                        </div>
                      @endforeach
                    @else
                      @foreach($data['list_image_space'] as $src)
                        <div class="col-md-1 col-sm-1 col-xs-12" style="width: 110px; padding-left: 0px" id="image_spaces_{{$src['id']}}">
                          <a data-fancybox="image_space" href="{{asset($src['name'])}}">
                            <img style="height: 90px; width: 90px; border: 1px solid #000; margin: 2px" src="{{$src['name']}}">
                          </a>
                        </div>
                      @endforeach
                    @endif
                  @endif
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="image_menu">{{trans('global.image')}}
                </label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <input type="file" id="image_menu" name="image_menu[]" accept="image/*" multiple onchange="readURL(this,'list_image_menu')"/>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                <div class="col-md-9 col-sm-9 col-xs-12" id="list_image_menu">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  @if($data['role_user'] < 4)
                    @foreach($data['list_image_menu'] as $src)
                      <div class="col-md-1 col-sm-1 col-xs-12" style="width: 110px; padding-left: 0px;" id="image_menu_{{$src['id']}}">
                        <a data-fancybox="image_menu" href="{{asset($src['name'])}}">
                          <img style="height: 90px; width: 90px; border: 1px solid #000; margin: 2px" src="{{$src['name']}}">
                        </a>
                        <span style="background-color: rgb(35, 179, 119);cursor: pointer;color: white;box-shadow: 2px 2px 7px rgb(74, 72, 72);position: absolute;left: 77px;" id="image_menu_{{$src['id']}}" onClick="deleteImage('{{$src['id']}}','image_menu')">[X]</span>
                      </div>
                    @endforeach
                  @else
                    @if(!in_array($content->moderation, array('publish','un_publish')))
                      @foreach($data['list_image_menu'] as $src)
                        <div class="col-md-1 col-sm-1 col-xs-12" style="width: 110px; padding-left: 0px " id="image_menu_{{$src['id']}}">
                          <a data-fancybox="image_menu" href="{{asset($src['name'])}}">
                            <img style="height: 90px; width: 90px; border: 1px solid #000; margin: 2px" src="{{$src['name']}}">
                          </a>
                          <span style="background-color: rgb(35, 179, 119);cursor: pointer;color: white;box-shadow: 2px 2px 7px rgb(74, 72, 72);position: absolute;left: 77px;" id="image_menu_{{$src['id']}}" onClick="deleteImage('{{$src['id']}}','image_menu')">[X]</span>
                        </div>
                      @endforeach
                    @else
                      @foreach($data['list_image_menu'] as $src)
                        <div class="col-md-1 col-sm-1 col-xs-12" style="width: 110px; padding-left: 0px" id="image_menu_{{$src['id']}}">
                          <a data-fancybox="image_menu" href="{{asset($src['name'])}}">
                            <img style="height: 90px; width: 90px; border: 1px solid #000; margin: 2px" src="{{$src['name']}}">
                          </a>
                        </div>
                      @endforeach
                    @endif
                  @endif
                </div>
              </div>

               @if(count($data['link_content']) > 0)
                @foreach(array_slice($data['link_content'], 0, 1) as $value)
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">{{trans('Admin'.DS.'content.link')}} </label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <input type="text" name="link[]" value="{{$value['link']}}" class="form-control col-md-7 col-xs-12">
                    </div>
                    <span><i class="remove_custom_open fa fa-remove" onclick="removeCustomOpen(this)"></i></span>
                  </div>
                @endforeach
              @else
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">{{trans('Admin'.DS.'content.link')}} </label>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" name="link[]" value="" class="form-control col-md-7 col-xs-12">
                  </div>
                  <span><i class="remove_custom_open fa fa-remove" onclick="removeCustomOpen(this)"></i></span>
                </div>
              @endif



              <div id="append_custom_link">
                @if(count($data['link_content']) > 0)
                  @foreach(array_slice($data['link_content'], 1) as $value)
                    <div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="text" name="link[]" value="{{$value['link']}}" class="form-control col-md-7 col-xs-12">
                      </div>
                      <span><i class="remove_custom_open fa fa-remove" onclick="removeCustomOpen(this)"></i></span>
                    </div>
                  @endforeach
                @endif
              </div>

              <div style="text-align: center">
                <br>
                <button class="btn btn-default" type="button" onclick="addCustomLink()">
                  {{trans('Admin'.DS.'content.add_link')}}
                </button>
                <br>
              </div>

              <div class="form-group">
                <input type="hidden" value="{{$content->lat}}" name="lat" id="lat">
                <input type="hidden" value="{{$content->lng}}" name="lng" id="lng">
              </div>

              <div class="ln_solid"></div>
              <div class="form-group" style="text-align: left;"><h2>SEO</h2></div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="seo_keyword">KeyWord</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <textarea type="text" id="seo_keyword" name="seo_keyword" maxlength="80"
                            class="form-control col-md-7 col-xs-12">{{isset($data['seo']) ? $data['seo']->key_word : ''}}</textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="seo_description">Description</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <textarea type="text" id="seo_description" name="seo_description" maxlength="160"
                            class="form-control col-md-7 col-xs-12">{{isset($data['seo']) ? $data['seo']->description : ''}}</textarea>
                </div>
              </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
            @if($content->moderation=='in_progress')
            <button type="button" class="btn btn-block btn-lg btn-primary" style="background: #5bc0de;border:none;">
              {{trans('Admin'.DS.'content.in_progress')}}
            </button>
            <br/>
            @endif
            @if($content->moderation=='request_publish')
            <button type="button" class="btn btn-block btn-lg btn-primary" style="background: #dc3545;border:none;">
              {{trans('Admin'.DS.'content.request_publish')}}
            </button>
            <br/>
            @endif
            @if($content->moderation=='publish')
            <button type="button" class="btn btn-block btn-lg btn-primary" style="background: #337ab7;border:none;">
              {{trans('Admin'.DS.'content.publish')}}
            </button>
            <br/>
            @endif
            @if($content->moderation=='reject_publish')
            <button type="button" class="btn btn-block btn-lg btn-primary" style="background: #cd4378;border:none;">
              {{trans('Admin'.DS.'content.reject_publish')}}
            </button>
            <br/>
            @endif
            @if($content->moderation=='un_publish')
            <button type="button" class="btn btn-block btn-lg btn-primary" style="background: #ffc107;border:none;">
              {{trans('Admin'.DS.'content.unpublish')}}
            </button>
            <br/>
            @endif
            @if($content->moderation=='trash')
            <button type="button" class="btn btn-block btn-lg btn-primary" style="background: #868e96;border:none;">
              {{trans('Admin'.DS.'content.trash')}}
            </button>
            <br/>
            @endif
            @if(in_array($content->moderation, array('publish','un_publish')))
                @if($data['role_user'] != 4)
                  <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12" for="moderation">{{trans('Admin'.DS.'content.moderation')}}
                    </label>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                      <select class="form-control" name="moderation" id="moderation">
                        <option value="in_progress" {{$content->moderation == 'in_progress' ? 'selected' : ''}}>{{trans('Admin'.DS.'content.in_progress')}}</option>
                        <option value="request_publish" {{$content->moderation == 'request_publish' ? 'selected' : ''}}>{{trans('Admin'.DS.'content.request_publish')}}</option>
                        <option value="reject_publish" {{$content->moderation == 'reject_publish' ? 'selected' : ''}}>{{trans('Admin'.DS.'content.reject_publish')}}</option>
                        <option value="publish" {{$content->moderation == 'publish' ? 'selected' : ''}}>{{trans('Admin'.DS.'content.publish')}}</option>
                        <option value="un_publish" {{$content->moderation == 'un_publish' ? 'selected' : ''}}>{{trans('Admin'.DS.'content.unpublish')}}</option>
                        <option value="trash" {{$content->moderation == 'trash' ? 'selected' : ''}}>{{trans('Admin'.DS.'content.trash')}}</option>
                      </select>
                    </div>
                  </div>
                @endif
            @else
              <div class="form-group">
                <label class="control-label col-md-4 col-sm-4 col-xs-12" for="moderation">{{trans('Admin'.DS.'content.moderation')}}
                </label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <select class="form-control" name="moderation" id="moderation">
                    <option value="in_progress" {{$content->moderation == 'in_progress' ? 'selected' : ''}}>{{trans('Admin'.DS.'content.in_progress')}}</option>
                    <option value="request_publish" {{$content->moderation == 'request_publish' ? 'selected' : ''}}>{{trans('Admin'.DS.'content.request_publish')}}</option>
                    @if($data['role_user'] < 4)
                      <option value="reject_publish" {{$content->moderation == 'reject_publish' ? 'selected' : ''}}>{{trans('Admin'.DS.'content.reject_publish')}}</option>
                      <option value="publish" {{$content->moderation == 'publish' ? 'selected' : ''}}>{{trans('Admin'.DS.'content.publish')}}</option>
                      <option value="un_publish" {{$content->moderation == 'un_publish' ? 'selected' : ''}}>{{trans('Admin'.DS.'content.unpublish')}}</option>
                    @endif
                    <option value="trash" {{$content->moderation == 'trash' ? 'selected' : ''}}>{{trans('Admin'.DS.'content.trash')}}</option>
                  </select>
                </div>
              </div>
            @endif
            <div class="form-group">
              <label class="control-label col-md-4 col-xs-12">
              {{trans('Admin'.DS.'content.create_at')}}</label>
              <div class="col-md-8 col-xs-12">
                <label class="control-label">{{date('d-m-Y H:i:s',strtotime($content->created_at))}}</label>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-xs-12">
              {{trans('Admin'.DS.'content.create_by')}}</label>
              <div class="col-md-8 col-xs-12">
                <label class="control-label">@if($content->type_user == 1)
                  {{$content->_created_by?$content->_created_by->full_name:''}}
                  @else
                  {{$content->_created_by_client?$content->_created_by_client->full_name:''}}
                  @endif</label>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-xs-12">
              {{trans('Admin'.DS.'content.update_at')}}</label>
              <div class="col-md-8 col-xs-12">
                <label class="control-label">{{date('d-m-Y H:i:s',strtotime($content->updated_at))}}</label>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-xs-12">
              {{trans('Admin'.DS.'content.update_by')}}</label>
              <div class="col-md-8 col-xs-12">
                <label class="control-label">@if($content->type_user_update == 1)
                  {{$content->_updated_by?$content->_updated_by->full_name:''}}
                  @else
                  {{$content->_updated_by_client?$content->_updated_by_client->full_name:''}}
                  @endif</label>
              </div>
            </div>
            <div class="ln_solid"></div>
            @if(in_array($content->moderation, array('publish','un_publish')))
              @if($data['role_user'] != 4)
                <div class="form-group">
                  <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                    <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'content.update_content')}}</button>
                  </div>
                </div>
              @endif
            @else
              <div class="form-group">
                <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                  <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'content.update_content')}}</button>
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>
    </form>
  </div>

  <script type="text/javascript"
          src="https://maps.google.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyA4_lZ8uw0hpJfJxVHnK_vBBXZckA-0Tr0"></script>

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
          marker.formatted_address = '{{trans('Admin'.DS.'content.undefined_address')}}';
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

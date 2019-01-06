@extends('Admin..layout_admin.master_admin')

@section('content')
  <div class="row">
    <form class="form-horizontal form-label-left" method="post" action="{{route('add_type_ads')}}" enctype="multipart/form-data">
      <div class="col-md-8 col-sm-8 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
              {{ csrf_field() }}
              @if ($errors->has('error'))
                <span style="color: red">{{ $errors->first('error') }}</span>
              @endif
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">{{trans('Admin'.DS.'type_ads.name')}} <span
                    class="required">*</span>
                </label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                  <input type="text" id="name" name="name" class="form-control col-md-7 col-xs-12" value="{{ old('name') }}" >
                </div>
                @if ($errors->has('name'))
                  <span style="color: red">{{ $errors->first('name') }}</span>
                @endif
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="machine_name">{{trans('Admin'.DS.'type_ads.machine_name')}} <span
                    class="required">*</span>
                </label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                  <input type="text" id="machine_name" name="machine_name" class="form-control col-md-7 col-xs-12" value="{{ old('machine_name') }}" >
                </div>
                @if ($errors->has('machine_name'))
                  <span style="color: red">{{ $errors->first('machine_name') }}</span>
                @endif
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="kind">{{trans('Admin'.DS.'type_ads.kind')}}</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                  <select name="kind" id="kind" class="form-control">
                    <option value="web">Web</option>
                    <option value="app">App</option>
                    <!-- <option value="mobile">Mobile</option> -->
                    <!-- <option value="keyword">{{trans('global.keyword')}}</option> -->
                  </select>
                </div>
                @if ($errors->has('kind'))
                  <span style="color: red">{{ $errors->first('kind') }}</span>
                @endif
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="description">{{trans('Admin'.DS.'type_ads.description')}}</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                  <textarea type="text" id="description" name="description" class="form-control col-md-7 col-xs-12">{{ old('description') }}</textarea>
                </div>
                @if ($errors->has('description'))
                  <span style="color: red">{{ $errors->first('description') }}</span>
                @endif
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="size">{{trans('Admin'.DS.'type_ads.size')}}</label>
                <div class="col-md-10 col-sm-10 col-xs-12 row">
                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon" id="basic-addon1"><b>{{trans('Admin'.DS.'type_ads.width')}}</b></span>
                      <input type="number" name="width" value="{{old('width')?old('width'):0}}" class="form-control">
                      <span class="input-group-addon" id="basic-addon1"><b>px</b></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon" id="basic-addon1"><b>{{trans('Admin'.DS.'type_ads.height')}}</b></span>
                      <input type="number" name="height" value="{{old('height')?old('height'):0}}" class="form-control">
                      <span class="input-group-addon" id="basic-addon1"><b>px</b></span>
                    </div>
                  </div>
                </div>
                @if ($errors->has('size'))
                  <span style="color: red">{{ $errors->first('size') }}</span>
                @endif
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">{{trans('Admin'.DS.'type_ads.image_default')}}</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <div class="imgupload panel panel-default">
                      <div class="panel-heading clearfix">
                        <h3 class="panel-title pull-left">{{trans('Admin'.DS.'type_ads.upload_image')}}</h3>
                      </div>
                      <div class="file-tab panel-body">
                        <div>
                          <a type="button" class="btn btn-default btn-file">
                          <span>{{trans('Admin'.DS.'type_ads.browse')}}</span>
                          <input type="file" name="image_default" id="image_default">
                          </a>
                          <button type="button" class="btn btn-default">{{trans('Admin'.DS.'type_ads.remove')}}</button>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12">{{trans('Admin'.DS.'type_ads.image_demo')}}</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                    <div class="imgupload panel panel-default">
                      <div class="panel-heading clearfix">
                        <h3 class="panel-title pull-left">{{trans('Admin'.DS.'type_ads.upload_image')}}</h3>
                      </div>
                      <div class="file-tab panel-body">
                        <div>
                          <a type="button" class="btn btn-default btn-file">
                          <span>{{trans('Admin'.DS.'type_ads.browse')}}</span>
                          <input type="file" name="image_demo" id="image_demo">
                          </a>
                          <button type="button" class="btn btn-default">{{trans('Admin'.DS.'type_ads.remove')}}</button>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="price">{{trans('Admin'.DS.'type_ads.price_default')}}</label>
                <div class="col-md-10 col-sm-10 col-xs-12">
                  <div class="col-md-4"><label for="">{{trans('Admin'.DS.'type_ads.type_apply')}}</label></div>
                  <div class="col-md-8"><label for="">{{trans('Admin'.DS.'type_ads.price')}}</label></div>
                </div>
                <div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2">
                  <div class="col-md-4">
                    <input type="checkbox" name="type_apply[date]" value="1" checked>
                    {{trans('Admin'.DS.'type_ads.apply_by_date')}}
                    <input type="hidden" class="form-control" name="price_default[0][type_apply]" value="date">
                    <input type="hidden" class="form-control" name="price_default[0][min]" value="0">
                    <input type="hidden" class="form-control" name="price_default[0][max]" value="0">
                  </div>
                  <div class="col-md-8">
                    <input type="number" class="form-control" name="price_default[0][price]" value="0">
                  </div>
                </div>
                <div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2">
                  <div class="col-md-4">
                    <input type="checkbox" name="type_apply[click]" value="1" checked>
                    {{trans('Admin'.DS.'type_ads.apply_by_click')}}
                    <input type="hidden" class="form-control" name="price_default[1][type_apply]" value="click">
                    <input type="hidden" class="form-control" name="price_default[1][min]" value="0">
                    <input type="hidden" class="form-control" name="price_default[1][max]" value="0">
                  </div>
                  <div class="col-md-8">
                    <input type="number" class="form-control" name="price_default[1][price]" value="0">
                  </div>
                </div>
                <div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2">
                  <div class="col-md-4">
                    <input type="checkbox" name="type_apply[view]" value="1" checked>
                    {{trans('Admin'.DS.'type_ads.apply_by_view')}}
                    <input type="hidden" class="form-control" name="price_default[2][type_apply]" value="view">
                    <input type="hidden" class="form-control" name="price_default[2][min]" value="0">
                    <input type="hidden" class="form-control" name="price_default[2][max]" value="0">
                  </div>
                  <div class="col-md-8">
                    <input type="number" class="form-control" name="price_default[2][price]" value="0">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2 col-sm-2 col-xs-12" for="price">{{trans('Admin'.DS.'type_ads.price_custom')}}</label>
                <div class="col-md-10 col-sm-10 col-xs-12 custom_price_header" style="display:none;">
                  <div class="col-md-4"><label for="">{{trans('Admin'.DS.'type_ads.type_apply')}}</label></div>
                  <div class="col-md-4"><label for="">{{trans('Admin'.DS.'type_ads.price')}}</label></div>
                  <div class="col-md-2"><label for="">{{trans('Admin'.DS.'type_ads.min')}}</label></div>
                  <div class="col-md-2"><label for="">{{trans('Admin'.DS.'type_ads.max')}}</label></div>
                </div>
                <div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2" id="custom_price">
                  <div class="custom_price_item">
                  </div>
                </div>
                <div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2 text-center">
                  <button class="btn btn-primary" type="button" onclick="addPriceCustom()">
                   {{trans('Admin'.DS.'type_ads.add_price_custom')}}
                  </button>
                </div>
              </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
            <div class="form-group">
              <!-- <label class="control-label col-md-2 col-sm-2 col-xs-12"></label> -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                {{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ old('active') == '1' ? 'checked' : '' }} name="active"> {{trans('global.active')}}
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
              <div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2">
                <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'type_ads.add_type_ads')}}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

@section('JS')
<style>
  .custom_price_item{
    margin-bottom: 15px;
    float:left;
  }
  .remove_price_custom{
    position: absolute;
    cursor: pointer;
    margin-top: 10px;
  }
</style>
<script type="text/javascript">
  $(function(){
    $('.imgupload').imageupload({
      allowedFormats: [ "jpg", "jpeg", "png", "gif", "svg" , "bmp"],
      previewWidth: 250,
      previewHeight: 250,
      maxFileSizeKb: 2048
    });

    $("#name").on("keyup",function(){
      var name = $(this).val();
      $("#machine_name").val(str_machine(name));
    })

    $(".btn-file").on("click",function(){
      $(this).find("input").click();
    })
  })

  function addPriceCustom(){
    var index = $(".custom_price_item").length;
    if($('[data-index='+index+']').length){
      index +=1;
    }
    $(".custom_price_header").show();
    html='';
    html+='<div class="custom_price_item" data-index="'+index+'">';
    html+='  <div class="col-md-4">';
    html+='    <select name="custom_price['+index+'][type_apply]" id="" class="form-control">';
    html+='      <option value="date">{{trans('Location'.DS.'user.apply_by_date')}}</option>';
    html+='      <option value="click">{{trans('Location'.DS.'user.apply_by_click')}}</option>';
    html+='      <option value="view">{{trans('Location'.DS.'user.apply_by_view')}}</option>';
    html+='    </select>';
    html+='  </div>';
    html+='  <div class="col-md-4">';
    html+='    <input type="number" class="form-control" name="custom_price['+index+'][price]" value="0" min="0">';
    html+='  </div>';
    html+='  <div class="col-md-2">';
    html+='    <input type="number" class="form-control" name="custom_price['+index+'][min]" value="0" min="0">';
    html+='  </div>';
    html+='  <div class="col-md-2">';
    html+='    <input type="number" class="form-control" name="custom_price['+index+'][max]" value="0" min="0">';
    html+='  </div>';
    html+='  <a class="remove_price_custom"  onclick="removePriceCustom(this)"><i class="fa fa-remove"></i></a>';
    html+='</div>';
    $("#custom_price").append(html);
  }

  function removePriceCustom(obj){
    $(obj).parent().remove();
    if($(".custom_price_item").length ==1){
      $(".custom_price_header").hide();
    }
  }
</script>
@endsection

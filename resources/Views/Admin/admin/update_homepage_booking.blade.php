@extends('Admin..layout_admin.master_admin')

@section('content')
  @if(session('status'))
  <div class="alert alert-success alert-dismissible fade in" style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
    {!! session('status') !!}
  </div>
  @endif
  <form class="form-horizontal form-label-left" method="post" action="{{route('update_home_booking',['id' => $home_booking->id])}}" enctype="multipart/form-data">
    {{ csrf_field() }}
    @if ($errors->has('error'))
      <span style="color: red">{{ $errors->first('error') }}</span>
    @endif
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="country">{{trans('Admin'.DS.'home_booking'.DS.'add.country')}} <span
          class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="form-control {{$errors->has('country')?'parsley-error':''}}" name="country" id="country"
                        onchange="getLocationAjax(this.value)" >
          <option value="">-- Country --</option>
          @foreach($list_country as $country)
            <option value="{{$country->id}}" {{$home_booking->country_id==$country->id?'selected':''}}>{{$country->name}}</option>
          @endforeach
        </select>
      </div>
      @if ($errors->has('country'))
        <span style="color: red">{{ $errors->first('country') }}</span>
      @endif
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="city">{{trans('Admin'.DS.'home_booking'.DS.'add.city')}} <span
          class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select  onchange="changeName(this)" class="form-control {{$errors->has('city')?'parsley-error':''}}" name="city" id="city" >
          <option value="">-- City --</option>
          @foreach($list_city as $city)
            <option value="{{$city->id}}" {{$home_booking->city_id==$city->id?'selected':''}}>{{$city->name}}</option>
          @endforeach
        </select>
      </div>
      @if ($errors->has('city'))
        <span style="color: red">{{ $errors->first('city') }}</span>
      @endif
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'home_booking'.DS.'add.name')}} <span
          class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="name" name="name" class="form-control col-md-7 col-xs-12" value="{{ $home_booking->name }}" >
      </div>
      @if ($errors->has('name'))
        <span style="color: red">{{ $errors->first('name') }}</span>
      @endif
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="weight">{{trans('Admin'.DS.'home_booking'.DS.'add.weight')}}
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="number" min="0" id="weight" name="weight" class="form-control col-md-7 col-xs-12" value="{{ $home_booking->weight?$home_booking->weight:0 }}">
      </div>
      @if ($errors->has('weight'))
        <span style="color: red">{{ $errors->first('weight') }}</span>
      @endif
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'home_booking'.DS.'add.image')}}</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="imgupload panel panel-default">
            <div class="panel-heading clearfix">
              <h3 class="panel-title pull-left">Upload image</h3>
            </div>
            <div class="file-tab panel-body">
              <div>
                <a type="button" class="btn btn-default btn-file">
                <span>Browse</span>
                <input type="file" name="image" id="image">
                </a>
                <button type="button" class="btn btn-default">Remove</button>
              </div>
            </div>
          </div>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        {{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ $home_booking->active == '1' ? 'checked' : '' }} name="active"> {{trans('global.active')}}
      </div>
    </div>

    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'home_booking'.DS.'add.update')}}</button>
      </div>
    </div>
  </form>
@endsection

@section('JS')
<script type="text/javascript">
  $(function(){
    $("input[type=number]").on("keypress",function(e){
      return (e.charCode >= 48 && e.charCode <= 57) || e.charCode == 13;
    })

    $('.imgupload').imageupload({
      allowedFormats: [ "jpg", "jpeg", "png", "gif", "svg" , "bmp"],
      previewWidth: 250,
      previewHeight: 250,
      maxFileSizeKb: 2048
    });

    $("#name").on("keyup",function(){
      var name = $(this).val();
      $("#machine_name").val(str_machine(name));
      $("#alias").val(str_slug(name))
    })

    $(".btn-file").on("click",function(){
      $(this).find("input").click();
    })
    var html_image = '<img src="{{$home_booking->image?$home_booking->image:'' }}" alt="Image preview" class="thumbnail" style="max-width: 250px; max-height: 250px">';
    $('.file-tab').prepend(html_image)
  })

  function getLocationAjax(value, type) {
    var CSRF_TOKEN = $('input[name="_token"]').val();
    $.ajax({
      type: "POST",
      data: {value: value,  _token: CSRF_TOKEN},
      url: '/booking/homepage/ajaxLocation',
      success: function (data) {
        $("#city").html(data);
      }
    })
  }

  function changeName(obj){
    var city = $(obj).val();
    var name = $("#city option[value="+city+"]").text();
    $("#name").val(name);
  }
</script>
@endsection
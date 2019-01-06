@extends('Admin..layout_admin.master_admin')

@section('content')
<div class="row">
    <div class="col-xs-12">
      <div class="x_panel" style="min-height: 250px;">
        <div class="x_title">
          <h2>{{trans('Admin'.DS.'client.add_daily')}}</h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('list_dai_ly')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <form class="form-horizontal form-label-left" method="post" action="{{route('add_dai_ly')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            @if ($errors->has('error'))
              <span style="color: red">{{ $errors->first('error') }}</span>
            @endif
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('global.user')}} <span class="required">*</span> </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="user" id="user" class="form-control">
                  
                </select>
              </div>
              @if ($errors->has('user'))
              <span style="color: red">{{ $errors->first('user') }}</span>
              @endif
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('global.country')}} <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="country" id="country" class="form-control" onchange="get_location(this, 'city')">
                  @foreach($countries as $country)
                  <option value="{{$country->id}}" {{$country->id == old('country')?'selected':'' }}>{{$country->name}}</option>
                  @endforeach
                </select>
              </div>
              @if ($errors->has('country'))
              <span style="color: red">{{ $errors->first('country') }}</span>
              @endif
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('global.city')}} <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="city[]" id="city" class="form-control" onchange="get_location(this, 'district')" multiple="">
                </select>
              </div>
              @if ($errors->has('city'))
              <span style="color: red">{{ $errors->first('city') }}</span>
              @endif
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('global.district')}} <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select name="district[]" id="district" class="form-control" multiple="">
                  <!-- <option value="">-- {{trans('global.district')}} --</option> -->
                </select>
              </div>
              @if ($errors->has('district'))
              <span style="color: red">{{ $errors->first('district') }}</span>
              @endif
            </div>

            <div class="form-group col-md-9 text-center">
                <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'role.save_role')}}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
  
@endsection
@section('JS')
  <style>
    .select2-container--default .select2-results__group{
      font-size: 110%;
    }
  </style>
  <link rel="stylesheet" href="/frontend/vendor/select2/select2.min.css">
  <script src="/frontend/vendor/select2/select2.min.js"></script>
  <script src="/frontend/vendor/select2/vi.js"></script>

  <script type="text/javascript">

    function loadRole(){
      var client_id = $("#user").val();
      var client_group_id = $("#client_group").val();
      $.ajax({
        url : '/client/loadRole',
        type: 'POST',
        data: {
          'client_id' : client_id,
          'client_group_id' : client_group_id,
          '_token' : $("input[name=_token]").val()
        },
        success: function(res){
          $("#client_role").html(res);
        }
      })
    }

    $(function () {
      $("#user").select2({
        ajax:{
          url       : "{{route('search_user_add_daily')}}",
          type      : "GET",
          delay     : 800,
          dataType  :'json',
          data : function(params){
            var query = {
              query: params.term
            }
            return query;
          },
          cache:true
          // processResults: function (data) {
          //   return {
          //     results: data.items
          //   };
          // }
        },
        templateResult: formatData,
        templateSelection: formatData,
        escapeMarkup: function (m) {
          return m;
        },
        minimumInputLength: 1,
        placeholder: "{{trans('Admin'.DS.'client.input_user')}}",
        language: "vi",
        closeOnSelect: true
      })
    })


    function formatData (option) {
      if (!option.id) { return option.text; }
      var ob = '<img width="28" height="28" src="'+ option.avatar +'" />&nbsp;&nbsp;&nbsp;' + option.text; // replace image source with option.img (available in JSON)
      return ob;
    };
  </script>


<script>
  var old_city = {!! json_encode(old('city'),true) !!};
  var old_district = {!! json_encode(old('district'),true) !!};

  if(!old_city){
    old_city = [];
  }
  if(!old_district){
    old_district = [];
  }

  $(function(){
    $("#country").trigger("change");
    $("#city").select2();
    $("#district").select2();
  });

  function get_location(obj, type) {
    var CSRF_TOKEN = $('input[name="_token"]').val();
    var value = $(obj).val();
    $.ajax({
      type: "POST",
      data: {value: value, type: type, _token: CSRF_TOKEN},
      url:  '/admin/content/ajaxLocation',
      success: function (data) {
        // alert(type);
        
        $("#" + type).html(data);

        if(type=='city'){
          $("#city option").each(function(key,elem){
            if(key==0){
              $(elem).remove();
            }
            var value_option = $(elem).attr('value');
            console.log(value_option,old_city.indexOf(value_option));
            
            if(old_city.indexOf(value_option) > -1){
              $(elem).attr('selected',true);
            }
          });
          $("#city").trigger("change");
        }
        if (type == 'district'){
          $("#district option").each(function(key, elem){
            if(key==0){
              $(elem).remove();
            }
            var value_option = $(elem).attr('value');
            if (old_district.indexOf(value_option) > -1){
              $(elem).attr('selected', true);
            }
          })
          $("#district").trigger("change");
        }
      }
    })
  }
</script>
@endsection
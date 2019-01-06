@extends('Admin..layout_admin.master_admin')

@section('content')
<div class="row">
    <div class="col-xs-12">
      <div class="x_panel" style="min-height: 250px;">
        <div class="x_title">
          <h2>{{trans('Admin'.DS.'client.add_ctv_button')}}</h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('list_ctv',['code'=>$code])}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <form class="form-horizontal form-label-left" method="post" action="{{route('add_ctv',['code'=>$code])}}" enctype="multipart/form-data">
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
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('global.district')}} <span class="required">*</span> </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="col-md-12"><input type="checkbox" id="check_all">{{trans('global.all')}}</div class="col-md-12">
                @foreach($city as $key => $ct)
                  <div class="col-md-12"><br/><b>{{$ct->name}}:</b><br/></div>
                  @foreach($districts as $key => $district)
                    @if($district->id_city == $ct->id)
                    <div class="col-md-4 col-xs-6">
                      <input class="district" type="checkbox" name="district[]" value="{{$district->id}}" {{old('district') && in_array($district->id,old('district'))?"checked":''}}> {{$district->name}}
                    </div>
                    @endif
                  @endforeach
                @endforeach
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/i18n/vi.js"></script>

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
      $("#check_all").on("change",function(){
        var check = $(this).is(":checked");
        $(".district").prop('checked',check);
      });
      $(".district").on("change",function(){
        var check = true;
        $(".district").each(function(key,elem){
          if($(elem).is(":checked") == false){
            check = false;
          }
        });
        $("#check_all").prop("checked",check);
      });
      $(".district").trigger("change");
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
@endsection
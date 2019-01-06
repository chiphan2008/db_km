@extends('Admin..layout_admin.master_admin')

@section('content')
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2> {{trans('Admin'.DS.'client.area')}}</h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <div class="col-md-12">
            @if ($errors->has('district'))
            <span style="color: red">{{ $errors->first('district') }}</span>
            @endif
          </div>
          <form class="form-horizontal form-label-left" method="post" action="" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="country" value="{{$country}}">
            <div class="form-group col-md-12">
              <div class="col-md-12"><input type="checkbox" id="check_all">{{trans('global.all')}}</div class="col-md-12">
              @foreach($city as $key => $ct)
                <div class="col-md-12"><br/><b>{{$ct->name}}:</b><br/></div>
                @foreach($districts as $key => $district)
                  @if($district->id_city == $ct->id)
                  <div class="col-md-3 col-xs-6">
                    <input class="district" type="checkbox" name="district[]" value="{{$district->id}}" {{in_array($district->id,$old_district)?"checked":''}}> {{$district->name}}
                  </div>
                  @endif
                @endforeach
              @endforeach
            </div>
            <div class="form-group col-md-12">
              <div class="col-md-4 col-sm-4 col-xs-12 col-md-offset-4">
                <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'client.save')}}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
	
@endsection

@section('JS')
<script>
  $(function(){

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
    
  })
</script>
@endsection
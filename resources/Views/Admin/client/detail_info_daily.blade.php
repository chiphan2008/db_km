@extends('Admin.layout_admin.master_admin')

@section('content')
  <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
    <div class="row">

      <div class="col-sm-12">
        <ul class="nav navbar-right panel_toolbox">
          <a href="{{route('list_dai_ly')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
        </ul>
        <ul class="nav nav-tabs">
          <li class="{{isset($type)&&$type =='info'?'active':''}}"><a href="{{route('detail_dai_ly',['id'=>$client->id,'type'=>'info'])}}">{{trans('Admin'.DS.'client.info')}}</a></li>
          <li class="{{isset($type)&&$type =='content'?'active':''}}"><a href="{{route('detail_dai_ly',['id'=>$client->id,'type'=>'content'])}}">{{trans('global.locations')}} ({{$count_content}})</a></li>
          <li class="{{isset($type)&&$type =='static'?'active':''}}"><a href="{{route('detail_dai_ly',['id'=>$client->id,'type'=>'static'])}}">{{trans('Admin'.DS.'client.static')}}</a></li>
        </ul>

        <div class="x_panel">
          <form id="detail_client" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group row col-md-10" style="margin-bottom: 10px;">
              <div class="form-group row col-md-12" style="margin-bottom: 10px;">
                <label class="text-right" style="min-width:180px;">{{trans('Admin'.DS.'client.full_name')}} : </label>

                {{$client->full_name}}

              </div>
              <div class="form-group row col-md-12" style="margin-bottom: 10px;">
                <label class="text-right" style="min-width:180px;">Email : </label>

                {{$client->email}}

              </div>
              <div class="form-group row col-md-12" style="margin-bottom: 10px;">
                <label class="text-right" style="min-width:180px;">{{trans('Admin'.DS.'client.phone')}} : </label>

                {{$client->phone}}

              </div>
              <div class="form-group row col-md-12" style="margin-bottom: 10px;">
                <label class="text-right" style="min-width:180px;">{{trans('Admin'.DS.'client.birthday')}} : </label>

                {{$client->birthday}}

              </div>
              <div class="form-group row col-md-12" style="margin-bottom: 10px;">
                <label class="text-right" style="min-width:180px;">{{trans('Admin'.DS.'client.address')}} : </label>

                {{$client->address}}

              </div>
              <div class="form-group row col-md-12" style="margin-bottom: 10px;">
                <label class="text-right" style="min-width:180px;">{{trans('Admin'.DS.'client.id_client')}} : </label>

                {{$client->ma_dinh_danh}}

              </div>
              <div class="form-group row col-md-12" style="margin-bottom: 10px;">
                <label class="text-right" style="min-width:180px;">{{trans('global.cmnd')}} : {{$client->cmnd}}</label>
              </div>
              @if($client->cmnd_image_front)
                <div class="form-group row col-md-6" style="margin-bottom: 10px;">
                  <a data-fancybox href="{{$client->cmnd_image_front}}">
                    <img src="{{$client->cmnd_image_front}}" alt="" class="img-responsive" style="max-height: 400px;">
                  </a>
                </div>
              @endif
              @if($client->cmnd_image_back)
                <div class="form-group row col-md-6" style="margin-bottom: 10px;">
                  <a data-fancybox href="{{$client->cmnd_image_back}}">
                    <img src="{{$client->cmnd_image_back}}" alt="" class="img-responsive" style="max-height: 400px;">
                  </a>
                </div>
              @endif  
            </div>
            <div class="form-group row col-md-2" style="margin-bottom: 10px;text-align: right">
                <img src="{{$client->avatar}}" alt="Image Client" style="max-width: 100%;max-height: 100%;">
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
@endsection

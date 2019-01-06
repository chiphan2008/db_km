@extends('Admin.layout_admin.master_admin')

@section('content')
  <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
    <div class="row">

      <div class="col-sm-12">

        <ul class="nav nav-tabs">
          <li class="{{isset($type)&&$type =='info'?'active':''}}"><a href="{{route('detail_client',['id'=>$client->id,'type'=>'info'])}}">{{trans('Admin'.DS.'client.info')}}</a></li>
          <li class="{{isset($type)&&$type =='content'?'active':''}}"><a href="{{route('detail_client',['id'=>$client->id,'type'=>'content'])}}">{{trans('Admin'.DS.'client.content')}}</a></li>
          <li class="{{isset($type)&&$type =='like'?'active':''}}"><a href="{{route('detail_client',['id'=>$client->id,'type'=>'like'])}}">{{trans('Admin'.DS.'client.like')}}</a></li>
          <li class="{{isset($type)&&$type =='vote'?'active':''}}"><a href="{{route('detail_client',['id'=>$client->id,'type'=>'vote'])}}">{{trans('Admin'.DS.'client.vote')}}</a></li>
        </ul>

        <div class="x_panel">
          <form id="detail_client" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group row col-md-10" style="margin-bottom: 10px;">
              <div class="form-group row col-md-12" style="margin-bottom: 10px;">
                <label class="text-right" style="min-width:100px;">{{trans('Admin'.DS.'client.full_name')}} : </label>

                {{$client->full_name}}

              </div>
              <div class="form-group row col-md-12" style="margin-bottom: 10px;">
                <label class="text-right" style="min-width:100px;">Email : </label>

                {{$client->email}}

              </div>
              <div class="form-group row col-md-12" style="margin-bottom: 10px;">
                <label class="text-right" style="min-width:100px;">{{trans('Admin'.DS.'client.phone')}} : </label>

                {{$client->phone}}

              </div>
              <div class="form-group row col-md-12" style="margin-bottom: 10px;">
                <label class="text-right" style="min-width:100px;">{{trans('Admin'.DS.'client.birthday')}} : </label>

                {{$client->birthday}}

              </div>
              <div class="form-group row col-md-12" style="margin-bottom: 10px;">
                <label class="text-right" style="min-width:100px;">{{trans('Admin'.DS.'client.address')}} : </label>

                {{$client->address}}

              </div>
              <div class="form-group row col-md-12" style="margin-bottom: 10px;">
                <label class="text-right" style="min-width:100px;">{{trans('Admin'.DS.'client.id_client')}} : </label>

                {{$client->ma_dinh_danh}}

              </div>
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

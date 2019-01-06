@extends('Admin..layout_admin.master_admin')

@section('content')

  <form class="form-horizontal form-label-left" method="post" action="{{route('list_category_service',['category_id'=>$category_id])}}">
    {{ csrf_field() }}
    @if ($errors->has('error'))
      <span style="color: red">{{ $errors->first('error') }}</span>
    @endif

    @foreach($list_service_item as $service_item)
      <div class="form-group col-md-3 col-xs-6">
        <input type="checkbox" name="service_item[]" value="{{$service_item->id}}" id="{{$service_item->machine_name}}"
               class="checkbox-inline pull-left" {{in_array($service_item->id, $list_service_of_cate)?'checked':''}}>
        <label class="control-label pointer" for="{{$service_item->machine_name}}" >{{$service_item->name}}</label>
      </div>
    @endforeach

    <div class="form-group col-md-12">
      <div class="col-md-4 col-sm-4 col-xs-12 col-md-offset-4">
        <button type="submit" class="btn btn-success">{{trans('global.save')}}</button>
      </div>
    </div>
  </form>
  <style type="text/css">
    .checkbox-inline {
      margin-top: 10px !important;
      margin-right: 10px !important;
    }

    .pointer {
      cursor: pointer !important;
    }
  </style>
@endsection

@extends('Admin..layout_admin.master_admin')

@section('content')
  <input type="checkbox" id="check_all" onclick="checkAll(this)"> <label class="control-label pointer" for="check_all">{{trans('Admin'.DS.'role.check_all')}}</label>
  <form class="form-horizontal form-label-left" method="post" action="{{route('grant_client_role',[ 'id'=> $id])}}" enctype="multipart/form-data">
    {{ csrf_field() }}
    @if ($errors->has('error'))
      <span style="color: red">{{ $errors->first('error') }}</span>
    @endif

    @foreach($permissions as $permission)
    <div class="form-group col-md-3 col-xs-6">
        <input type="checkbox" name="permission[]" value="{{$permission->id}}" id="{{$permission->machine_name}}" class="checkbox-inline pull-left" {{$permission->checked?'checked':''}}> <label class="control-label pointer" for="{{$permission->machine_name}}">{{$permission->display_name}}</label>
    </div>
    @endforeach

    <div class="form-group col-md-12">
      <div class="col-md-4 col-sm-4 col-xs-12 col-md-offset-4">
        <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'role.save_role')}}</button>
      </div>
    </div>
  </form>
  <style type="text/css">
    .checkbox-inline{
      margin-top: 10px !important;
      margin-right: 10px !important;
    }
    .pointer{
      cursor: pointer !important;
    }
  </style>
@endsection

@section('JS')
  <script type="text/javascript">
    function checkAll(obj){
      $(".checkbox-inline").prop("checked", $(obj).is(":checked"));
    }
  </script>
@endsection

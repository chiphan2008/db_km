@extends('Admin..layout_admin.master_admin')

@section('content')
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2> {{trans('Admin'.DS.'role.grant_client')}} </h2>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <form class="form-horizontal form-label-left" method="post" action="" enctype="multipart/form-data">
            {{ csrf_field() }}
            @foreach($groups as $key => $group)
            <div class="form-group col-md-12">
              <div class="col-md-12">
                <label for="" class="label-control"><h4>{{trans('global.group')}}: {{$group->name}}</h4></label>
              </div>
              <div class="col-md-3">
                <input type="radio" name="role[{{$group->id}}]" value="0" {{isset($client_role[$group->id])?'':'checked'}}> {{trans('Admin'.DS.'role.no_role')}}
              </div>
              @foreach($group->_roles as $role)
              <div class="col-md-3">

                <input type="radio" name="role[{{$group->id}}]" value="{{$role->id}}" {{isset($client_role[$group->id])&&$client_role[$group->id]==$role->id?'checked':''}}> {{$role->name}}

              </div>
              @endforeach
            </div>
            @endforeach


            <div class="form-group col-md-12">
              <div class="col-md-4 col-sm-4 col-xs-12 col-md-offset-4">
                <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'role.save_role')}}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
	
@endsection

@section('JS')

@endsection
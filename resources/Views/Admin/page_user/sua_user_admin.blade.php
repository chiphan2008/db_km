@extends('Admin..layout_admin.master_admin')

@section('content')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Update User</h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('list_user')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
          <form id="#demo-form22" method="post" action="{{route('update_user', ['id' => $user->id])}}" enctype="multipart/form-data"
                data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="full_name">{{trans('Admin'.DS.'page_user.full_name')}} <span
                  class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="full_name" name="full_name"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('full_name')?'parsley-error':''}}"
                       value="{{$user->full_name }}" >
                @if ($errors->has('full_name'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('full_name') }}</li>
                  </ul>
                @endif
              </div>

            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="password">{{trans('Admin'.DS.'page_user.password')}} <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="password" id="password" name="password"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('password')?'parsley-error':''}}"
                       value="{{$user->password }}"
                       >
                @if ($errors->has('password'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('password') }}</li>
                  </ul>
                @endif
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="password_confirmation">{{trans('Admin'.DS.'page_user.confirm_password')}}
                <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="form-control col-md-7 col-xs-12" value="{{$user->password }}" >
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="email">{{trans('Admin'.DS.'page_user.email')}}
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="text" id="email" name="email"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('email')?'parsley-error':''}}"
                       value="{{$user->email }}" disabled>
                @if ($errors->has('email'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('email') }}</li>
                  </ul>
                @endif
              </div>
            </div>
            @if($user->hasRole('super_admin') == false)
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="role">{{trans('Admin'.DS.'page_user.role')}}
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" name="role" id="role">
                  @foreach($role as $value => $name)
                    <option value="{{$value}}" {{ $role_of_user == $value ? 'selected' : '' }}>{{$name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            @endif
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="avatar">{{trans('Admin'.DS.'page_user.avatar_current')}}
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <img style="width: 30%" src="{{asset('img_user/'.$user->avatar)}}" alt="image">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12"
                     for="avatar">{{trans('Admin'.DS.'page_user.avatar')}}
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input type="file" id="avatar" name="avatar" class="form-control col-md-7 col-xs-12"
                       accept="image/gif, image/jpeg, image/png">
              </div>
            </div>
            @if($user->hasRole('super_admin') == false)
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('global.active')}}</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <label>
                  <input type="radio" {{ $user->active == '1' ? 'checked' : '' }} value="1" id="active1" name="active">
                  {{trans('global.active')}}
                </label>
                <label>
                  <input type="radio" {{ $user->active == '0' ? 'checked' : '' }} value="0" id="active0" name="active"> None
                  {{trans('global.inactive')}}
                </label>
              </div>
            </div>
            @endif
            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'page_user.update_user')}}</button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@extends('Admin..layout_admin.master_admin')

@section('content')
  <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
    <div class="row">

      <div class="col-sm-8">
        <div class="x_panel">
          <form class="form-horizontal form-label-left" method="get" action="{{url()->current()}}">
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('global.keyword')}}</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" id="keyword" value="{{ isset($keyword) ? $keyword : '' }}" name="keyword"
                       class="form-control" placeholder="Keyword">
                <input type="hidden" name="sort" id="sort" value="">
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-9 col-sm-9 col-xs-12">
                <button type="submit" class="btn btn-success">{{trans('global.search')}}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      @if(Auth::guard('web')->user()->can('add_User'))
      <div class="pull-right" style="margin-right: 15px;">
        <a href="{{route('add_user')}}" style="float: right" class="btn btn-primary">{{trans('Admin'.DS.'page_user.add_user')}}</a>
      </div>
      @endif
      <form action="" method="post">
        {{ csrf_field() }}
        <div class="pull-right" style="margin-right: 15px;">
          <select style="float: right; margin-bottom: 5px" class="form-control" onchange="change_pagination(this.value,'{{\Route::currentRouteName()}}')">
            <option value="10" {{\Session::has('pagination.'.\Route::currentRouteName()) && session('pagination.'.\Route::currentRouteName()) == 10 ? 'selected' : ''}}>10</option>
            <option value="20" {{\Session::has('pagination.'.\Route::currentRouteName()) && session('pagination.'.\Route::currentRouteName()) == 20 ? 'selected' : ''}}>20</option>
            <option value="50" {{\Session::has('pagination.'.\Route::currentRouteName()) && session('pagination.'.\Route::currentRouteName()) == 50 ? 'selected' : ''}}>50</option>
            <option value="100" {{\Session::has('pagination.'.\Route::currentRouteName()) && session('pagination.'.\Route::currentRouteName()) == 100 ? 'selected' : ''}}>100</option>
          </select>
        </div>
      </form>

      {{--///////////////////////////////////////////// --}}
      <div class="col-sm-12">
        @if(session('status'))
          <div class="alert alert-success alert-dismissible fade in" style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            {!! session('status') !!}
          </div>
        @endif
        {{--////////////////////////////////////////////////////////--}}
        <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid"
               aria-describedby="datatable_info">
          <thead>
          <tr role="row">
            <th class="sorting"  type-sort="{{isset($sort['id'])?$sort['id']:''}}" data-sort="id"   tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'page_user.id')}}
                @if(isset($sort['id']))
                  @if($sort['id']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th class="sorting"  type-sort="{{isset($sort['full_name'])?$sort['full_name']:''}}" data-sort="full_name"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Position: activate to sort column ascending" aria-sort="descending">
              {{trans('Admin'.DS.'page_user.full_name')}}
              @if(isset($sort['full_name']))
                  @if($sort['full_name']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th class="sorting"  type-sort="{{isset($sort['email'])?$sort['email']:''}}" data-sort="email"   tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Office: activate to sort column ascending">{{trans('Admin'.DS.'page_user.email')}}
                @if(isset($sort['email']))
                  @if($sort['email']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th class="sorting"  type-sort="{{isset($sort['role'])?$sort['role']:''}}" data-sort="role"   tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Age: activate to sort column ascending">{{trans('Admin'.DS.'page_user.role')}}
                @if(isset($sort['role']))
                  @if($sort['role']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th class="sorting"  type-sort="{{isset($sort['active'])?$sort['active']:''}}" data-sort="active"   tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Start date: activate to sort column ascending">{{trans('Admin'.DS.'page_user.active')}}
                @if(isset($sort['active']))
                  @if($sort['active']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Salary: activate to sort column ascending">{{trans('Admin'.DS.'page_user.operation')}}
            </th>
          </tr>
          </thead>

          <tbody>
          @if (isset($list_user))
            @foreach ($list_user as $user)
              <tr role="row">
                <td>{{$user->id}}</td>
                <td>{{$user->full_name}}</td>
                <td>{{$user->email}}</td>
                <td>{{$list_role[$user->_role_user->role_id]}}</td>
                <td>{{($user->active) == '1' ? trans('Admin'.DS.'page_user.active') : trans('Admin'.DS.'page_user.none_active')}}</td>
                <td>
                  <a href="{{route('list_content_user', ['id' => $user->id,'moderation' => 'all'])}}">{{trans('Admin'.DS.'page_user.list_content')}}</a> /
                  @if(Auth::guard('web')->user()->can('edit_User'))
                  <a href="{{route('update_user', ['id' => $user->id])}}">{{trans('global.edit')}}</a> /
                  @endif
                  @if(Auth::guard('web')->user()->can('delete_User'))
                  {{--<a href="javascript: deleteUser({{$user->id}})">{{trans('global.delete')}}</a>--}}
                  <a href="" data-toggle="modal" data-target="#delete_user" onclick="deleteUser('{{$user->full_name}}','{{route('delete_user', ['id' => $user->id])}}')">{{trans('global.delete')}}</a>
                  @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr role="row" class="odd">
              <td colspan="6">No Data</td>
            </tr>
          @endif
          </tbody>
        </table>
        <div class="modal fade" id="delete_user" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Delete User</h4>
              </div>
              <div class="modal-body">
                <p id="name"></p>
              </div>
              <div class="modal-footer">
                <a id="link_delete" href="" class="btn btn-primary">{{trans('global.delete')}}</a>
                <button type="button" data-dismiss="modal" class="btn">{{trans('global.cancel')}}</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-12" style="text-align: right">
        {!! $list_user->appends(['keyword' => isset($keyword) ? $keyword : '' , 'role'=> isset($role) ? $role : ''])->render() !!}
      </div>
    </div>
  </div>
  <script type="text/javascript">
    var json_sort = {!! json_encode($sort,JSON_FORCE_OBJECT) !!};
    function deleteUser(id) {
      if( confirm('{{trans('valid.confirm_delete')}}') ) {
        window.location = '/admin/user/delete/'+id
      }
    }

    function deleteUser(name,link)
    {
      $('#name').text('{{trans('valid.confirm_delete_user')}}' + name);
      $('#link_delete').attr('href', link);
    }
  </script>
@endsection

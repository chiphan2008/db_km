@extends('Admin.layout_admin.master_admin')

@section('content')
  <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
    <div class="row">

      <div class="col-sm-8">
        <div class="x_panel">
          <form class="form-horizontal form-label-left" method="get" action="{{url()->current()}}">
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('global.keyword')}}</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" id="keyword" value="{{ isset($keyword) ? $keyword : '' }}" name="keyword" class="form-control" placeholder="{{trans('global.keyword')}}">
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
      @if(Auth::guard('web')->user()->can('add_Permission'))
      <div class="pull-right" style="margin-right: 15px;">
        <a href="{{route('add_permission')}}" style="float: right" class="btn btn-primary">{{trans('Admin'.DS.'permission.add_permission')}}</a>
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
            <th class="sorting"  type-sort="{{isset($sort['id'])?$sort['id']:''}}" data-sort="id"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="ID: activate to sort column ascending" style="width: 50px;">ID
                @if(isset($sort['id']))
                  @if($sort['id']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th class="sorting"  type-sort="{{isset($sort['name'])?$sort['name']:''}}" data-sort="name"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name: activate to sort column ascending" style="width: 117px;">{{trans('Admin'.DS.'permission.name')}}
                @if(isset($sort['name']))
                  @if($sort['name']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th class="sorting"  type-sort="{{isset($sort['created_at'])?$sort['created_at']:''}}" data-sort="created_at"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Create at: activate to sort column ascending" style="width: 75px;">{{trans('Admin'.DS.'permission.created_at')}}
                @if(isset($sort['created_at']))
                  @if($sort['created_at']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th class="sorting"  type-sort="{{isset($sort['created_by'])?$sort['created_by']:''}}" data-sort="created_by"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Create by: activate to sort column ascending" style="width: 75px;">{{trans('Admin'.DS.'permission.created_by')}}
                @if(isset($sort['created_by']))
                  @if($sort['created_by']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Operation: activate to sort column ascending" style="width: 90px;">{{trans('global.operation')}}
            </th>
          </tr>
          </thead>

          <tbody>
          @if (isset($list_permission))
            @foreach ($list_permission as $permission)
              <tr role="row" class="odd">
                <td>{{$permission->id}}</td>
                <td>{{$permission->display_name}}</td>
                <!-- <td>{{$permission->description}}</td> -->
                <td>{{date('d-m-Y',strtotime($permission->created_at))}}</td>
                <td>{{$permission->_created_by->full_name}}</td>
                <td>
                  @if(Auth::guard('web')->user()->can('edit_Permission'))
                  <a href="{{route('update_permission', ['id' => $permission->id])}}">{{trans('global.edit')}}</a>
                  @endif
                  @if(Auth::guard('web')->user()->can('delete_Permission'))
                   /
                  <a href="javascript: deletePermission({{$permission->id}})">{{trans('global.delete')}}</a>
                  @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr role="row" class="odd">
              <td colspan="6">{{trans('global.no_data')}}</td>
            </tr>
          @endif
          </tbody>
        </table>
      </div>
      <div class="col-sm-12" style="text-align: right">
        {!! $list_permission->appends(['keyword' => isset($keyword) ? $keyword : '', 'sort'=>isset($qsort)?$qsort:''])->render() !!}
      </div>
    </div>
  </div>
  <script type="text/javascript">
    var json_sort = {!! json_encode($sort,JSON_FORCE_OBJECT) !!};
    function deletePermission(id) {
      if( confirm('{{trans('Admin'.DS.'permission.confirm_delete')}}') ) {
        window.location = '/admin/permission/delete/'+id
      }
    }
  </script>
@endsection

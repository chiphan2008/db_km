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
                <input type="text" id="keyword" value="{{ isset($keyword) ? $keyword : '' }}" name="keyword"
                       class="form-control" placeholder="{{trans('global.keyword')}}">
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
      @if(Auth::guard('web')->user()->can('add_Group'))
      <div class="pull-right">
        <a href="{{route('add_group')}}" style="float: right"
           class="btn btn-primary">{{trans('Admin'.DS.'group.add_group')}}</a>
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
      <div class="col-sm-12">
        @if(session('status'))
          <div class="alert alert-success alert-dismissible fade in" style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            {!! session('status') !!}
          </div>
        @endif
        <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid"
               aria-describedby="datatable_info">
          <thead>
          <tr role="row">
            <th class="sorting" data-sort="id"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'group.id')}}

            </th>
            <th class="sorting" data-sort="name"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'group.name')}}

            </th>

            <th class="sorting" data-sort="type"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'group.content_type')}}
            </th>
            <th class="sorting" data-sort="created_at"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Create at: activate to sort column ascending">{{trans('Admin'.DS.'group.created_at')}}

            </th>
            <th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Operation: activate to sort column ascending"
                style="width: 90px;">{{trans('global.operation')}}
            </th>
          </tr>
          </thead>

          <tbody>
          @if (isset($list_group))
            @foreach ($list_group as $group)
              <tr role="row" class="odd">
                <td>{{$group->id}}</td>
                <td>{{$group->name}}</td>
                <td>@lang($group->_category_type->name)</td>
                <td>{{date('d-m-Y',strtotime($group->created_at))}}</td>
                <td>
                  @if(Auth::guard('web')->user()->can('edit_Group'))
                  <a href="{{route('update_group', ['id' => $group->id])}}">{{trans('global.edit')}}</a>
                  @endif
                  {{--@if(Auth::guard('web')->user()->can('delete_Group'))--}}
                   {{--/ --}}
                  {{--<a href="javascript: deleteGroup({{$group->id}})">{{trans('global.delete')}}</a>--}}
                  {{--@endif--}}
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
        {!! $list_group->appends(['keyword' => isset($keyword) ? $keyword : ''])->render() !!}
      </div>
    </div>
  </div>
  <script type="text/javascript">
    {{--function deleteGroup(id) {--}}
      {{--if( confirm('{{trans('Admin'.DS.'group.confirm_delete')}}') ) {--}}
        {{--window.location = '/admin/group/delete/'+id--}}
      {{--}--}}
    {{--}--}}
  </script>
@endsection
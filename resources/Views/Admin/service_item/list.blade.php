@extends('Admin.layout_admin.master_admin')

@section('content')
  <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
    <div class="row">

      <div class="col-sm-8">
        <div class="x_panel">
          <form class="form-horizontal form-label-left" method="GET" action="{{route('list_service_item')}}">
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('global.keyword')}}</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" id="keyword" value="{{ isset($keyword) ? $keyword : '' }}" name="keyword"
                       class="form-control" placeholder="{{trans('global.keyword')}}">
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

      <div class="pull-right" style="margin-right: 15px;">
        <a href="{{route('add_service_item')}}" style="float: right"
           class="btn btn-primary">{{trans('global.add')}}</a>
        <a href="{{route('list_approve_service_item')}}" style="float: right"
           class="btn btn-success">{{trans('global.approve')}}</a>
      </div>
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
        {{--////////////////////////////////////////////////////////--}}
        <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid"
               aria-describedby="datatable_info">
          <thead>
          <tr role="row">
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name: activate to sort column ascending">ID
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'service_item.name')}}
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'service_item.machine_name')}}
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'service_item.status')}}
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Create at: activate to sort column ascending">{{trans('Admin'.DS.'service_item.created_at')}}
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Create at: activate to sort column ascending">{{trans('Admin'.DS.'service_item.created_by')}}
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Operation: activate to sort column ascending"
                style="width: 90px;">{{trans('global.operation')}}
            </th>
          </tr>
          </thead>

          <tbody>
          @if (isset($list_service_item))
            @foreach ($list_service_item as $service_item)
              <tr role="row" class="odd">
                <td>{{$service_item->id}}</td>
                <td>{{$service_item->name}}</td>
                <td>{{$service_item->machine_name}}</td>
                <td>
                  @if($service_item->active)
                    <b class="text-success">{{trans('global.active')}}</b>
                  @else
                    <b class="text-danger">{{trans('global.inactive')}}</b>
                  @endif
                </td>
                <td>{{date('d-m-Y',strtotime($service_item->created_at))}}</td>
                <td>{{date('d-m-Y',strtotime($service_item->updated_at))}}</td>
                <td>
                  @if(Auth::guard('web')->user()->can('edit_ServiceItem'))
                    <a href="{{route('update_service_item', ['id' => $service_item->id])}}">{{trans('global.edit')}}</a>
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
        {!! $list_service_item->appends(['keyword' => isset($keyword) ? $keyword : ''])->render() !!}
      </div>
    </div>
  </div>
@endsection

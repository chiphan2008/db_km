@extends('Admin.layout_admin.master_admin')
@section('content')

  <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
    <div class="row" style="min-height: 500px">

      <div class="col-sm-8">
        <div class="x_panel">
          <form class="form-horizontal form-label-left" method="get" action="{{url()->current()}}">
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
      @if(Auth::guard('web')->user()->can('add_CustomPage'))
        <div class="col-sm-4">
          <a href="{{route('add_custom_page')}}" style="float: right"
             class="btn btn-primary">{{trans('Admin'.DS.'custom_page.create_custom_page')}}</a>
        </div>
      @endif
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
                aria-label="Name: activate to sort column ascending">ID

            </th>
            <th class="sorting" data-sort="name"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'custom_page.title')}}

            </th>

            <th class="sorting" data-sort="type"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'custom_page.create_by')}}
            </th>
            <th class="sorting" data-sort="created_at"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Create at: activate to sort column ascending">{{trans('Admin'.DS.'custom_page.status')}}

            </th>
            <th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Operation: activate to sort column ascending"
                style="width: 190px;">{{trans('global.operation')}}
            </th>
          </tr>
          </thead>

          <tbody>
          @if (isset($list_custompage))
            @foreach ($list_custompage as $custompage)
              <tr role="row" class="odd">
                <td style="display: table-cell;vertical-align: middle;">{{$custompage->id}}</td>
                <td style="display: table-cell;vertical-align: middle;">{{$custompage->title}}</td>
                <td style="display: table-cell;vertical-align: middle;">{{$custompage->_created_by->full_name}}</td>
                <td style="display: table-cell;vertical-align: middle;">
                  @if($custompage->status == 1)
                    <b class="text-success">{{trans('global.active')}}</b>
                  @else
                    <b class="text-danger">{{trans('global.inactive')}}</b>
                  @endif
                </td>
                <td style="display: table-cell;vertical-align: middle;">
                  @if(Auth::guard('web')->user()->can('edit_CustomPage'))
                    <a class="btn btn-default" href="{{route('update_custom_page', ['id' => $custompage->id])}}">{{trans('global.edit')}}</a>
                  @endif
                  @if(Auth::guard('web')->user()->can('edit_CustomPage'))
                    <div class="pull-right">
                      <div class="btn-group">
                        <button type="button" class="btn btn-default">{{trans('Admin'.DS.'custom_page.translate')}}</button>
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                aria-expanded="false">
                          <span class="caret"></span>
                          <span class="sr-only">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu" style="right: 0;left: auto;" role="menu">
                          <li><a href="{{route('custom_page_lang',['id'=>$custompage->id,'lang'=>'vn'])}}">VN</a></li>
                          <li><a href="{{route('custom_page_lang',['id'=>$custompage->id,'lang'=>'en'])}}">ENG</a></li>
                        </ul>
                      </div>
                    </div>
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
        {!! $list_custompage->appends(['keyword' => isset($keyword) ? $keyword : ''])->render() !!}
      </div>
    </div>
  </div>

@endsection
@extends('Admin.layout_admin.master_admin')

@section('content')
  <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
    <div class="row">

      <div class="col-sm-8">
        <div class="x_panel">
          <form class="form-horizontal form-label-left" method="post" action="{{route('list_content_type')}}">
            {{ csrf_field() }}
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
      @if(Auth::guard('web')->user()->can('add_ContentType'))
      <div class="col-sm-4">
        <a href="{{route('add_content_type')}}" style="float: right"
           class="btn btn-primary">{{trans('Admin'.DS.'content_type.add_content_type')}}</a>
      </div>
      @endif
      {{--///////////////////////////////////////////// --}}
      <div class="col-sm-12">
        @if(session('status'))
          {{session('status')}}
        @endif
        {{--////////////////////////////////////////////////////////--}}
        <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid"
               aria-describedby="datatable_info">
          <thead>
          <tr role="row">
            <th class="sorting" type-sort="{{isset($sort['id'])?$sort['id']:''}}" data-sort="id" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'content_type.id')}}
                @if(isset($sort['id']))
                  @if($sort['id']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th class="sorting" type-sort="{{isset($sort['name'])?$sort['name']:''}}" data-sort="name" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'content_type.name')}}
                @if(isset($sort['name']))
                  @if($sort['name']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
<!--             <th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'content_type.machine_name')}}
            </th> -->
            <th class="sorting" type-sort="{{isset($sort['created_at'])?$sort['created_at']:''}}" data-sort="created_at" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Create at: activate to sort column ascending">{{trans('Admin'.DS.'content_type.create_at')}}
                @if(isset($sort['created_at']))
                  @if($sort['created_at']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
<!--             <th class="sorting" type-sort="{{isset($sort['id'])?$sort['id']:''}}" data-sort="id" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Create at: activate to sort column ascending">{{trans('Admin'.DS.'content_type.update_at')}}
            </th> -->
            <th  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Operation: activate to sort column ascending"
                style="width: 90px;">{{trans('global.operation')}}
            </th>
          </tr>
          </thead>

          <tbody>
          @if (isset($list_content_type))
            @foreach ($list_content_type as $content_type)
              <tr role="row" class="odd">
                <td>{{$content_type->id}}</td>
                <td>{{$content_type->name}}</td>
                <!-- <td>{{$content_type->machine_name}}</td> -->
                <td>{{date('d-m-Y',strtotime($content_type->created_at))}}</td>
                <!-- <td>{{date('d-m-Y',strtotime($content_type->updated_at))}}</td> -->
                <td>
                  @if(Auth::guard('web')->user()->can('edit_ContentType'))
                  <a href="{{route('update_content_type', ['id' => $content_type->id])}}">{{trans('global.edit')}}</a>
                  @endif
                  @if(Auth::guard('web')->user()->can('delete_ContentType'))
                   / 
                  <a href="javascript: deleteContentType({{$content_type->id}})">{{trans('global.delete')}}</a>
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
        {!! $list_content_type->appends(['keyword' => isset($keyword) ? $keyword : '', 'sort'=>isset($qsort)?$qsort:''])->render() !!}
      </div>
    </div>
  </div>
  <script type="text/javascript">
      var json_sort = {!! json_encode($sort,JSON_FORCE_OBJECT) !!};
      function deleteContentType(id) {
          if( confirm('{{trans('Admin'.DS.'content_type.confirm_delete')}}') ) {
              window.location = '/admin/content_type/delete/'+id
          }
      }
  </script>
@endsection
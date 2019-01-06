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
      @if(Auth::guard('web')->user()->can('add_EmailTemplate'))
      <div class="col-sm-4">
        <a href="{{route('add_email')}}" style="float: right" class="btn btn-primary">{{trans('Admin'.DS.'email_template.add_email_template')}}</a>
      </div>
      @endif

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
            <th class="sorting" type-sort="{{isset($sort['id'])?$sort['id']:''}}" data-sort="id" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name: activate to sort column ascending">ID
                @if(isset($sort['id']))
                  @if($sort['id']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th class="sorting" type-sort="{{isset($sort['name'])?$sort['name']:''}}" data-sort="name" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'email_template.name')}}
                @if(isset($sort['name']))
                  @if($sort['name']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <!-- <th class="sorting_desc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Position: activate to sort column ascending" aria-sort="descending">Machine Name
            </th> -->
            <th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Salary: activate to sort column ascending">{{trans('global.operation')}}
            </th>
          </tr>
          </thead>

          <tbody>
          @if (isset($list_email))
            @foreach ($list_email as $email)
              <tr role="row">
                <td>{{$email->id}}</td>
                <td>{{$email->name}}</td>
                <!-- <td>{{$email->machine_name}}</td> -->
                <td>
                  @if(Auth::guard('web')->user()->can('edit_EmailTemplate'))
                  <a href="{{route('update_email', ['id' => $email->id])}}">{{trans('global.edit')}}</a>
                  @endif
                  @if(Auth::guard('web')->user()->can('delete_EmailTemplate'))
                   /
                  {{--<a href="javascript: deleteEmail({{$email->id}})">{{trans('global.delete')}}</a>--}}
                  <a href="" data-toggle="modal" data-target="#delete_email" onclick="deleteEmailTemplate('{{$email->name}}','{{route('delete_email', ['id' => $email->id])}}')">{{trans('global.delete')}}</a>
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
          <div class="modal fade" id="delete_email" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">{{trans('Admin'.DS.'email_template.del_email_template')}}</h4>
                </div>
                <div class="modal-body">
                  <p id="name_client"></p>
                </div>
                <div class="modal-footer">
                  <a id="link_delete" href="" class="btn btn-primary">{{trans('global.delete')}}</a>
                  <button type="button" data-dismiss="modal" class="btn">{{trans('global.cancel')}}</button>
                </div>
              </div>
            </div>
          </div>
      </div>
      @if (isset($list_email))
        <div class="col-sm-12" style="text-align: right">
          {!! $list_email->appends(['keyword' => isset($keyword) ? $keyword : '', 'sort'=>isset($qsort)?$qsort:''])->render() !!}
        </div>
      @endif
    </div>
  </div>
  <script type="text/javascript">
    var json_sort = {!! json_encode($sort,JSON_FORCE_OBJECT) !!};
    function deleteEmail(id) {
      if( confirm('Are you sure') ) {
        window.location = '/admin/email/delete/'+id
      }
    }

    function deleteEmailTemplate(name,link)
    {
      $('#name_client').text('{{trans('Admin'.DS.'email_template.confirm_delete')}} : ' + name);
      $('#link_delete').attr('href', link);
    }
  </script>
@endsection

@extends('Admin..layout_admin.master_admin')

@section('content')
  <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
    <div class="row"  style="min-height: 565px;">
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
                <button type="submit" class="btn btn-success">{{trans('global.keyword')}}</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      @if(Auth::guard('web')->user()->can('add_TemplateNotifi'))
      <div class="col-sm-4">
        <a href="{{route('add_template_notifi')}}" style="float: right" class="btn btn-primary">{{trans('Admin'.DS.'template_notifi.add_template_notifi')}}</a>
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
            <th class="sorting" type-sort="{{isset($sort['id'])?$sort['id']:''}}" data-sort="id" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width:75px;"
                aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'template_notifi.id')}}
                @if(isset($sort['id']))
                  @if($sort['id']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th class="sorting" type-sort="{{isset($sort['name'])?$sort['name']:''}}" data-sort="name" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 125px;"
                aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'template_notifi.template_notifi_name')}}
                @if(isset($sort['name']))
                  @if($sort['name']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>

            <th class="sorting" type-sort="{{isset($sort['machine_name'])?$sort['machine_name']:''}}" data-sort="machine_name" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 125px;"
                aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'template_notifi.template_key')}}
                @if(isset($sort['machine_name']))
                  @if($sort['machine_name']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <!-- <th class="sorting"  type-sort="{{isset($sort['content'])?$sort['content']:''}}" data-sort="content"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="content: activate to sort column ascending">
                {{trans('Admin'.DS.'template_notifi.content')}}
                @if(isset($sort['content']))
                  @if($sort['content']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th> -->

            <th class="sorting" type-sort="{{isset($sort['language'])?$sort['language']:''}}" data-sort="language" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 125px;"
                aria-label="Name: activate to sort column ascending">{{trans('global.language')}}
                @if(isset($sort['language']))
                  @if($sort['language']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 75px;"
                aria-label="Salary: activate to sort column ascending">{{trans('global.operation')}}
            </th>
            <th style="width: 35px;"></th>
          </tr>
          </thead>

          <tbody>
          @if (isset($list_template))
            @foreach ($list_template as $template)
              <tr role="row">
                <td>{{$template->id}}</td>
                <td>{{$template->name}}</td>
                <td>{{$template->machine_name}}</td>
                <!-- <td>
                  {!! $template->content  !!}
                </td> -->
                <td>
                  {{ $template->language == 'vn' ? 'Tiếng Việt' : '' }}
                  {{ $template->language == 'en' ? 'English' : '' }}
                </td>

                <td>
                  @if(Auth::guard('web')->user()->can('edit_TemplateNotifi'))
                  <a href="{{route('update_template_notifi',['id_template'=>$template->id])}}">{{trans('global.edit')}}</a>
                  @endif
                  {{--@if(Auth::guard('web')->user()->can('delete_TemplateNotifi'))--}}
                   {{--/ --}}
                  {{--<a href="javascript: deletetemplate({{$template->id}})">{{trans('global.delete')}}</a>--}}
                  {{--@endif--}}
                </td>

                <td>
                  @if(Auth::guard('web')->user()->can('edit_TemplateNotifi'))
                  <div class="btn-group">
                    <button type="button" class="btn btn-default">{{trans('global.translate')}}</button>
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                            aria-expanded="false">
                      <span class="caret"></span>
                      <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" style="right: 0;left: auto;" role="menu">
                      <li><a href="{{route('translate_template_notifi',['id'=>$template->id,'lang'=>'vn'])}}">VN</a></li>
                      <li><a href="{{route('translate_template_notifi',['id'=>$template->id,'lang'=>'en'])}}">ENG</a></li>
                    </ul>
                  </div>
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
      </div>
      @if (isset($list_template))
        <div class="col-sm-12" style="text-align: right">
          {!! $list_template->appends(['keyword' => isset($keyword) ? $keyword : '', 'sort'=>isset($qsort)?$qsort:''])->render() !!}
        </div>
      @endif
    </div>
  </div>
  <script type="text/javascript">
      var json_sort = {!! json_encode($sort,JSON_FORCE_OBJECT) !!};
  </script>
@endsection

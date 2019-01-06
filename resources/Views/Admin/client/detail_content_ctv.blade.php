@extends('Admin.layout_admin.master_admin')

@section('content')
  <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
    <div class="row">

      

      {{--<div class="x_content">--}}
      {{--<div class="" role="tabpanel" data-example-id="togglable-tabs">--}}

      <div class="col-sm-12">
        <ul class="nav navbar-right panel_toolbox">
          <a href="{{route('list_ctv',['code'=>$client->daily_code])}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
        </ul>
        <ul class="nav nav-tabs">
          <li class="{{isset($type)&&$type =='info'?'active':''}}"><a href="{{route('detail_ctv',['id'=>$client->id,'type'=>'info'])}}">{{trans('Admin'.DS.'client.info')}}</a></li>
          <li class="{{isset($type)&&$type =='content'?'active':''}}"><a href="{{route('detail_ctv',['id'=>$client->id,'type'=>'content'])}}">{{trans('global.locations')}} ({{$count_content}})</a></li>
          <li class="{{isset($type)&&$type =='static'?'active':''}}"><a href="{{route('detail_ctv',['id'=>$client->id,'type'=>'static'])}}">{{trans('Admin'.DS.'client.static')}}</a></li>
        </ul>
        <div class="x_panel">
          <div class="col-sm-12">
            {{--<div class="pull-left">--}}
              <form class="form-horizontal form-label-left" method="get" action="#">
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('global.keyword')}}</label>
                  <div class="col-md-9 col-sm-9 col-xs-12">
                    <input type="text" id="keyword" value="{{ isset($keyword) ? $keyword : '' }}" name="keyword"
                           class="form-control" placeholder="{{trans('global.keyword')}}">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-8 col-sm-8 col-xs-12">
                    <button type="submit" class="btn btn-success">{{trans('global.search')}}</button>
                  </div>
                </div>
              </form>
            {{--</div>--}}
            {{--<div class="pull-right">--}}

            {{--</div>--}}
          </div>
        </div>
        <form id="change_status_content" action="{{route('changeStatus')}}" method="post">
          {{ csrf_field() }}
          @if(Auth::guard('web')->user()->hasRole('content') == false && (isset($type) && $type =='content'))
            <input type="hidden" name="current_url" value="{{$_SERVER['REQUEST_URI']}}">
            <div class="input-group">
              <select class="form-control" name="type_status">
                <option value="">-- {{trans('Admin'.DS.'content.status')}} --</option>
                <option value="0">{{trans('global.inactive')}}</option>
                <option value="1">{{trans('global.active')}}</option>
                @if(Auth::guard('web')->user()->hasRole('super_admin') == true)
                  <option value="2">{{trans('Admin'.DS.'content.del_content')}}</option>
                @endif
              </select>
              <span class="input-group-btn">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirm_status">{{trans('Admin'.DS.'content.update')}}</button>
                    </span>
            </div>
          @endif
          <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid"
               aria-describedby="datatable_info">
          <thead>
          <tr role="row">
            <th>
              <input type="checkbox" onclick="toggle_checkbox(this)"/>
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'client.id')}}
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'client.name')}}
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'client.type')}}
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'client.address')}}
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Parent: activate to sort column ascending">{{trans('global.open')}}
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Create by: activate to sort column ascending">{{trans('Admin'.DS.'client.moderation')}}
            </th>
            @if($type == 'vote')
              <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                  aria-label="Create by: activate to sort column ascending">{{trans('Admin'.DS.'client.votes')}}
              </th>
            @endif
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Operation: activate to sort column ascending">{{trans('global.operation')}}
            </th>
          </tr>
          </thead>

          <tbody>
          @if (isset($content_of_client) && $content_of_client->total() > 0)
            @foreach ($content_of_client as $content)
              <tr role="row" class="odd">
                <td>
                  <input type="checkbox" name="change_status[]" value="{{$content->id}}">
                </td>
                <td>{{$content->id}}</td>
                <td>{{$content->name}}</td>
                <td>{{isset($content->_category_type->name)?$content->_category_type->name:''}}</td>
                <td>{{$content->address}}</td>
                <td>{{$content->open_from }} - {{ $content->open_to}}</td>
                <td>@lang(str_replace("_"," ",$content->moderation))</td>
                @if($type == 'vote')
                  <td>{{ $client_content_vote[$content->id]}}</td>
                @endif
                <td>
                  <a href="{{route('update_content', ['content_type'=>$content->_category_type->machine_name,'id' => $content->id])}}">{{trans('Admin'.DS.'client.view_content')}}</a>
                </td>
              </tr>
            @endforeach
          @else
            <tr role="row" class="odd">
              <td colspan="7">{{trans('global.no_data')}}</td>
            </tr>
          @endif
          </tbody>
        </table>
        </form>
      </div>
    </div>
    <div class="col-sm-12" style="text-align: right">
      {!! $content_of_client->appends(['keyword' => isset($keyword) ? $keyword : '', 'category'=> isset($category) ? $category : ''])->render() !!}
    </div>
  </div>
  <div class="modal fade" id="confirm_status" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3 class="modal-title">{{trans('Admin'.DS.'content.change_content')}}</h3>
        </div>
        <div class="modal-body">
          <p>{{trans('Admin'.DS.'content.confirm_change')}}</p>
        </div>
        <div class="modal-footer">
          {{--<a id="link_delete" href="" class="btn btn-primary">{{trans(DS.'global.save')}}</a>--}}
          <button type="button" data-dismiss="modal"  onclick="$('#change_status_content').submit()" class="btn btn-primary">{{trans(DS.'global.save')}}</button>
          <button type="button" data-dismiss="modal" class="btn">{{trans(DS.'global.cancel')}}</button>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('JS')
  <script type="text/javascript">
      function toggle_checkbox(source) {
          var checkboxes = document.querySelectorAll('input[type="checkbox"]');
          for (var i = 0; i < checkboxes.length; i++) {
              if (checkboxes[i] != source)
                  checkboxes[i].checked = source.checked;
          }
      }
  </script>
@endsection

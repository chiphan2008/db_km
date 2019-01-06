@extends('Admin.layout_admin.master_admin')

@section('content')
  <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
    <div class="row">

      <div class="col-sm-8">
        <div class="x_panel">
          <form class="form-horizontal form-label-left" method="post" action="#">
            {{ csrf_field() }}
            <div class="form-group">
              {{trans('Admin'.DS.'page_user.user')}} : {{$user->full_name}} {{trans('Admin'.DS.'page_user.created_total')}} {{$content_of_user->total()}} {{trans('Admin'.DS.'page_user.posts')}}.
              
            </div>
          </form>
        </div>
      </div>

      {{--<div class="x_content">--}}
      {{--<div class="" role="tabpanel" data-example-id="togglable-tabs">--}}

      <div class="col-sm-12">

        <ul class="nav nav-tabs">
          <li class="{{isset($moderation)&&$moderation =='all'?'active':''}}"><a href="{{route('content_user',['moderation'=>'all'])}}">{{trans('global.all')}}</a></li>
          <li class="{{isset($moderation)&&$moderation =='in_progress'?'active':''}}"><a href="{{route('content_user',['moderation'=>'in_progress'])}}">{{trans('Admin'.DS.'page_user.in_progress')}}</a></li>
          <li class="{{isset($moderation)&&$moderation =='request_publish'?'active':''}}"><a href="{{route('content_user',['moderation'=>'request_publish'])}}">{{trans('Admin'.DS.'page_user.request_publish')}}</a></li>
          <li class="{{isset($moderation)&&$moderation =='reject_publish'?'active':''}}"><a href="{{route('content_user',['moderation'=>'reject_publish'])}}">{{trans('Admin'.DS.'page_user.reject_publish')}}</a></li>
          <li class="{{isset($moderation)&&$moderation =='publish'?'active':''}}"><a href="{{route('content_user',['moderation'=>'publish'])}}">{{trans('Admin'.DS.'page_user.publish')}}</a></li>
          <li class="{{isset($moderation)&&$moderation =='trash'?'active':''}}"><a href="{{route('content_user',['moderation'=>'trash'])}}">{{trans('Admin'.DS.'page_user.trash')}}</a></li>
        </ul>

        <div class="x_panel">
          <form class="form-horizontal form-label-left search_group" method="get" action="#">
            <div class="form-group item">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('global.keyword')}}</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" id="keyword" value="{{ isset($keyword) ? $keyword : '' }}" name="keyword"
                       class="form-control" placeholder="{{trans('global.keyword')}}">
              </div>
            </div>
            <div class="form-group item">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('global.keyword')}}</label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                <select class="form-control" name="category" id="category">
                  <option value="">-- {{trans('global.category')}} --</option>
                  @foreach($list_category as $value => $name)
                    <option
                      value="{{$value}}" {{ isset($category) && $category == $value ? 'selected' : '' }}>{{$name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group item">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('global.from')}}</label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                <div class='input-group date' style="margin-bottom: 0px" id='date_from'>
                  <input type='text' class="form-control" name="date_from" value="{{ isset($date_from) ? $date_from : '' }}" />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
                </div>
              </div>
            </div>
            <div class="form-group item">
              <label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('global.to')}}</label>
              <div class="col-md-8 col-sm-8 col-xs-12">
                <div class='input-group date' style="margin-bottom: 0px" id='date_to'>
                  <input type='text' class="form-control" name="date_to" value="{{ isset($date_to) ? $date_to : '' }}" />
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
                </div>
              </div>
            </div>
            <div class="form-group col-md-12 text-center item">
              <button type="submit" class="btn btn-success">{{trans('global.search')}}</button>
            </div>
          </form>
        </div>

        <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid"
               aria-describedby="datatable_info">
          <thead>
          <tr role="row">
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name: activate to sort column ascending">ID
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'page_user.name')}}
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'page_user.type')}}
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'page_user.address')}}
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Parent: activate to sort column ascending">{{trans('global.open')}}
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Create by: activate to sort column ascending">{{trans('Admin'.DS.'page_user.moderation')}}
            </th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Operation: activate to sort column ascending">{{trans('global.operation')}}
            </th>
          </tr>
          </thead>

          <tbody>
          @if (isset($content_of_user) && $content_of_user->total() > 0)
            @foreach ($content_of_user as $content)
              <tr role="row" class="odd">
                <td>{{$content->id}}</td>
                <td>{{$content->name}}</td>
                <td>{{isset($content->_category_type->name)?$content->_category_type->name:''}}</td>
                <td>{{$content->address}}</td>
                <td>{{$content->open_from }} - {{ $content->open_to}}</td>
                <td>{{ str_replace("_"," ",$content->moderation)}}</td>
                <td>
                  <a href="{{route('update_content', ['content_type'=>$content->_category_type->machine_name,'id' => $content->id])}}">{{trans('Admin'.DS.'page_user.view_content')}}</a>
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
      </div>
    </div>
    <div class="col-sm-12" style="text-align: right">
      {!! $content_of_user->appends([
        'keyword' => isset($keyword) ? $keyword : '',
        'category'=> isset($category) ? $category : '',
        'date_from'=> isset($date_from) ? $date_from : '',
        'date_to'=> isset($date_to) ? $date_to : '',
        ])->render() !!}
    </div>
  </div>
  <style>
    .search_group .item{
      padding-top: 10px;
      padding-bottom: 10px;
    }
    @media (min-width: 768px){
      .search_group .item:nth-child(1){
        width: 30%;
      }
      .search_group .item:nth-child(2){
        width: 25%;
      }
      .search_group .item:nth-child(3){
        width: 21.5%;
      }
      .search_group .item:nth-child(4){
        width: 21.5%;
      }
    }
    .search_group input,
    .search_group select{
      max-width: 200px;
      min-width: 100px;
    }
  </style>
  <script type="text/javascript">
    $(function () {
      $('#date_from').datetimepicker({
        format: 'YYYY-MM-DD',
      });
      $('#date_to').datetimepicker({
        format: 'YYYY-MM-DD',
      });
    });
  </script>
@endsection
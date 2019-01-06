@extends('Admin..layout_admin.master_admin')

@section('content')
  <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
    <div class="row">

      <div class="col-sm-8">
        <div class="x_panel">
          <form class="form-horizontal form-label-left"  method="get" action="{{url()->current()}}">
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
      @if(Auth::guard('web')->user()->can('add_Location'))
      <div class="col-sm-4">
        <a href="{{route('add_country')}}" style="float: right" class="btn btn-primary">{{trans('Admin'.DS.'page_location.add_country')}}</a>
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
            <th class="sorting"  type-sort="{{isset($sort['id'])?$sort['id']:''}}" data-sort="id"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'page_location.id')}}
                @if(isset($sort['id']))
                  @if($sort['id']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th class="sorting"  type-sort="{{isset($sort['name'])?$sort['name']:''}}" data-sort="name"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'page_location.country_name')}}
                @if(isset($sort['name']))
                  @if($sort['name']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th class="sorting"  type-sort="{{isset($sort['weight'])?$sort['weight']:''}}" data-sort="weight"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 75px;"
                aria-label="weight: activate to sort column ascending">
                {{trans('Admin'.DS.'page_location.weight')}}
                @if(isset($sort['weight']))
                  @if($sort['weight']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th class="sorting"  type-sort="{{isset($sort['zipcode'])?$sort['zipcode']:''}}" data-sort="zipcode"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Position: activate to sort column ascending" aria-sort="descending">{{trans('Admin'.DS.'page_location.zip_code')}}
                @if(isset($sort['zipcode']))
                  @if($sort['zipcode']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Salary: activate to sort column ascending">{{trans('global.operation')}}
            </th>
          </tr>
          </thead>

          <tbody>
          @if (isset($list_country))
            @foreach ($list_country as $country)
              <tr role="row">
                <td>{{$country->id}}</td>
                <td>{{$country->name}}</td>
                <td>
                  @if(Auth::guard('web')->user()->can('edit_Location'))
                  <input onchange="changeWeight(this)" data-id="{{$country->id}}" style="width: 75px;" type="number" class="order form-control" max="9999" min="1"
                   value="{{$country->weight}}" maxlength="4">
                  @else
                  {{$country->weight}}
                  @endif
                </td>
                <td>{{$country->zipcode}}</td>
                <td>
                  @if(Auth::guard('web')->user()->can('view_Location'))
                  <a href="{{route('list_city', ['id' => $country->id])}}">{{trans('global.list')}}</a>
                  @endif
                  @if(Auth::guard('web')->user()->can('edit_Location'))
                   /
                  <a href="{{route('update_country', ['id' => $country->id])}}">{{trans('global.edit')}}</a>
                  @endif
                  {{--@if(Auth::guard('web')->user()->can('delete_Location'))--}}
                   {{--/ --}}
                  {{--<a href="javascript: deleteCountry({{$country->id}})">{{trans('global.delete')}}</a>--}}
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
      @if (isset($list_country))
        <div class="col-sm-12" style="text-align: right">
          {!! $list_country->appends(['keyword' => isset($keyword) ? $keyword : '', 'sort'=>isset($qsort)?$qsort:''])->render() !!}
        </div>
      @endif
    </div>
  </div>
  <script type="text/javascript">
      $(function(){
        $("input.order").on("keypress",function(e){
          return (e.charCode >= 48 && e.charCode <= 57) || e.charCode == 13;
        })
      })

      function changeWeight(obj){
        var id = $(obj).attr('data-id');
        var weight = $(obj).val();
        if(weight && id){
          window.location = '/admin/location/country/change-weight/'+id+'/'+weight;
        }
      }
      var json_sort = {!! json_encode($sort,JSON_FORCE_OBJECT) !!};
      {{--function deleteCountry(id) {--}}
          {{--if( confirm('{{trans('Admin'.DS.'page_location.confirm_delete')}}') ) {--}}
              {{--window.location = '/admin/location/country/delete/'+id--}}
          {{--}--}}
      {{--}--}}
  </script>
@endsection

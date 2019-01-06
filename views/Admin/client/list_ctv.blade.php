@extends('Admin..layout_admin.master_admin')

@section('content')
  <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
    <div class="row">
      
      <div class="col-sm-8">
        <div class="x_panel">
          <form class="form-horizontal form-label-left" method="get" action="{{url()->current()}}">
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('global.keyword')}}</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" id="keyword" value="{{ isset($keyword) ? $keyword : '' }}" name="keyword"
                       class="form-control" placeholder="Keyword">
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

      <div class="col-sm-4">
        <a href="{{route('add_ctv',['code'=>$code])}}" style="float: right" class="btn btn-primary">{{trans('Admin'.DS.'client.add_ctv_button')}}</a>
        <a href="{{route('list_dai_ly')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
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
      <!-- <div class="col-md-12 form-group hidden">
        <form action="{{route('add_ctv',['code'=>$code])}}" method="post">
          {{ csrf_field() }}
          <input type="text" class="hidden" name="code_daily" value="{{$code}}">
          <select class=" form-control w-100" name="ctv_id" id="select_ctv" style="width: 350px;">
            <option value="">Select user</option>
          </select>
          <button class="btn btn-primary">{{trans('global.add')}}</button>  
        </form>
      </div> -->
      <div class="col-sm-12">

        @if(session('status'))
          <div class="alert alert-success alert-dismissible fade in" style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            {!! session('status') !!}
          </div>
        @endif
        @if(session('err'))
          <div class="alert alert-danger alert-dismissible fade in" style="color: #a94442;background-color: #f2dede;border-color: #ebccd1;" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            {!! session('err') !!}
          </div>
        @endif
        <h4 class="col-md-6" >Danh sách cộng tác viên</h4>
        <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid"
               aria-describedby="datatable_info">
          <thead>
          <tr role="row">
            <th class="sorting" type-sort="{{isset($sort['id'])?$sort['id']:''}}" data-sort="id"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'client.id')}}
                @if(isset($sort['id']))
                  @if($sort['id']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th class="sorting" type-sort="{{isset($sort['full_name'])?$sort['full_name']:''}}" data-sort="full_name" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Position: activate to sort column ascending" aria-sort="descending">
              {{trans('Admin'.DS.'client.full_name')}}
              @if(isset($sort['full_name']))
                  @if($sort['full_name']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th class="sorting text-center" type-sort="{{isset($sort['rate_revenue'])?$sort['rate_revenue']:''}}" data-sort="rate_revenue"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Office: activate to sort column ascending">{{trans('Admin'.DS.'client.rate_revenue')}}
                @if(isset($sort['rate_revenue']))
                  @if($sort['rate_revenue']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th class="sorting" type-sort="{{isset($sort['email'])?$sort['email']:''}}" data-sort="email"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Office: activate to sort column ascending">{{trans('Admin'.DS.'client.email')}}
                @if(isset($sort['email']))
                  @if($sort['email']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th class="sorting" type-sort="{{isset($sort['active'])?$sort['active']:''}}" data-sort="active"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Office: activate to sort column ascending">{{trans('Admin'.DS.'client.status')}}
                @if(isset($sort['active']))
                  @if($sort['active']=='asc')
                  <i class="fa fa-sort-asc"></i>
                  @else
                  <i class="fa fa-sort-desc"></i>
                  @endif
                @endif
            </th>
            <th class="sorting"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                aria-label="Salary: activate to sort column ascending">{{trans('global.operation')}}
            </th>
          </tr>
          </thead>

          <tbody>
          {{ csrf_field() }}
          @if (isset($list_client))
            @foreach ($list_client as $client)
              <? //dd($client); ?>
              <tr role="row">
                <td>{{$client->id}}</td>
                <td>{{$client->full_name}}</td>
                <td class="text-center">
                  <input onchange="changeRate(this)" data-id="{{$client->id}}" style="width: 75px;" type="percent" class="order form-control text-center" max="100" min="1"
                   value="{{$client->rate_revenue}}" maxlength="5">
                </td>
                <td>{{$client->email}}</td>
                <td>
                  @if($client->temp_daily_code)
                      <b class="text-warning">{{trans('Admin'.DS.'client.request')}}</b>
                  @else
                    @if($client->role_active == 1)
                      <b class="text-success">{{trans('global.active')}}</b>
                    @else
                      <b class="text-danger">{{trans('global.inactive')}}</b>
                    @endif
                  @endif 
                </td>
                <td>
                  
                  @if($client->temp_daily_code)
                  <a href="{{route('accept_ctv',['id'=>$client->id,'code'=>$code])}}">{{trans('Admin'.DS.'client.accept')}}</a>
                   / 
                  <a href="{{route('decline_ctv',['id'=>$client->id,'code'=>$code])}}">{{trans('Admin'.DS.'client.decline')}}</a>
                  @else
                  @if($client->role_active == 1)
                  <a href ="{{route('lock_ctv',['id'=>$client->id,'code'=>$code])}}">{{trans('Admin'.DS.'client.lock')}}</a>
                  / 
                  @else
                  <a href ="{{route('unlock_ctv',['id'=>$client->id,'code'=>$code])}}">{{trans('Admin'.DS.'client.unlock')}}</a>
                  / 
                  @endif
                  <a href="{{route('detail_ctv',['id'=>$client->id, 'type'=>'info'])}}"> {{trans('Admin'.DS.'client.detail')}}</a>
                  /
                  <a href ="{{route('client_area',['id'=>$client->id])}}">{{trans('Admin'.DS.'client.area')}}</a>
                  / 
                  <a href ="" data-toggle="modal" data-target="#delete_client" onclick="deleteClient('{!! str_replace('\'','',htmlentities(htmlspecialchars($client->full_name))) !!}','{{route('remove_ctv', ['id' => $client->id, 'code'=>$code])}}')">{{trans('global.delete')}}</a>
                  @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr role="row" class="odd">
              <td colspan="5">No Data</td>
            </tr>
          @endif
          </tbody>
        </table>
      </div>

      <div class="modal fade" id="delete_client" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Update Client</h4>
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

      <div class="col-sm-12" style="text-align: right">
        {!! $list_client->appends(['keyword' => isset($keyword) ? $keyword : '', 'sort'=>isset($qsort)?$qsort:''])->render() !!}
      </div>
    </div>
  </div>
@endsection
@section('JS')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
  <script>
    $("#select_ctv").select2({
      ajax:{
        url       : "{{route('find_ctv')}}",
        type      : "GET",
        delay     : 800,
        dataType  :'json',
        data : function(params){
          var query = {
            query: params.term
          }
          return query;
        },
        cache:true
        // processResults: function (data) {
        //   return {
        //     results: data.items
        //   };
        // }
      },
      templateResult: formatData,
      templateSelection: formatData,
      escapeMarkup: function (m) {
        return m;
      },
      minimumInputLength: 1,
      placeholder: "{{trans('Admin'.DS.'client.ctv_input')}}",
      language: "vi",
      closeOnSelect: true
    })

    function formatData (option) {
      if (!option.id) { return option.text; }
      var ob = '<img width="28" height="28" src="'+ option.avatar +'" />&nbsp;&nbsp;&nbsp;' + option.text; // replace image source with option.img (available in JSON)
      return ob;
    };
    var json_sort = {!! json_encode($sort,JSON_FORCE_OBJECT) !!};
    var base_url = {!! json_encode(url('/')) !!};

    function deleteClient(name,link)
    {
      $('#name_client').text('{{trans('valid.confirm_delete_ctv')}} ' + name+'?');
      $('#link_delete').attr('href', link);
      $('#link_delete').text('{{trans('global.delete')}}')
    }
    function lockClient(name,link)
    {
      $('#name_client').text('{{trans('valid.confirm_lock_client')}} ' + name+'?');
      $('#link_delete').attr('href', link);
      $('#link_delete').text('{{trans('global.update')}}')
    }
    function unlockClient(name,link)
    {
      $('#name_client').text('{{trans('valid.confirm_unlock_client')}} ' + name+'?');
      $('#link_delete').attr('href', link);
      $('#link_delete').text('{{trans('global.update')}}')
    }
    function changeRate(obj){
      var id = $(obj).attr('data-id');
      var rate = $(obj).val();
      if(rate && id){
        window.location = '/admin/client/change-rate/'+id+'/'+rate;
      }
    }
  </script>
@endsection

@extends('Admin.layout_admin.master_admin')

@section('content')

<div class="form-inline dt-bootstrap no-footer">
	<div class="row">
		<div class="col-sm-12">
			<div class="x_panel">
				<form class="form-horizontal form-label-left" method="get" action="{{url()->current()}}">
					<div class="row search_group">
						<div class="col-md-6">
							<label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('global.keyword')}}</label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<input type="text" id="keyword" value="{{ isset($keyword) ? $keyword : '' }}" name="keyword"
									   class="form-control" placeholder="{{trans('global.keyword')}}" style="width:100%">
							</div>
						</div>
						<div class="col-md-3">
							<input type="hidden" name="sort" id="sort" value="">
							<button type="submit" class="btn btn-success">{{trans('global.search')}}</button>
						</div>
					</div>
				</form>
			</div>
		</div>

		<div class="col-sm-12">
			{{ csrf_field() }}
			@if(Auth::guard('web')->user()->can('add_TypeAds'))
      <div class="pull-right">
          <div class="btn-group">
              <a href="{{route('add_type_ads')}}" type="button" class="btn btn-primary">{{trans('Admin'.DS.'type_ads.add_type_ads')}}</a>
          </div>
      </div>
      @endif
			<div class="pull-right" style="margin-right: 15px;">
				<select style="float: right; margin-bottom: 5px" class="form-control" onchange="change_pagination(this.value,'{{\Route::currentRouteName()}}')">
					<option value="10" {{\Session::has('pagination.'.\Route::currentRouteName()) && session('pagination.'.\Route::currentRouteName()) == 10 ? 'selected' : ''}}>10</option>
					<option value="20" {{\Session::has('pagination.'.\Route::currentRouteName()) && session('pagination.'.\Route::currentRouteName()) == 20 ? 'selected' : ''}}>20</option>
					<option value="50" {{\Session::has('pagination.'.\Route::currentRouteName()) && session('pagination.'.\Route::currentRouteName()) == 50 ? 'selected' : ''}}>50</option>
					<option value="100" {{\Session::has('pagination.'.\Route::currentRouteName()) && session('pagination.'.\Route::currentRouteName()) == 100 ? 'selected' : ''}}>100</option>
				</select>
			</div>
		</div>

		<div class="col-sm-12">
			@if(session('status'))
			<div class="alert alert-success alert-dismissible fade in" style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
				{!! session('status') !!}
			</div>
			@endif
			
			@if(session('errors'))
			<div class="alert alert-danger alert-dismissible fade in" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
				@foreach( session('errors')->all() as $message )
				{{ $message }}
				@endforeach
			</div>
			@endif
			<table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid"
				   aria-describedby="datatable_info">
				<thead>
					<tr role="row">
						<th data-sort="id" tabindex="0"
							aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Name: activate to sort column ascending">ID
						</th>
						<th data-sort="name" tabindex="0"
							aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'type_ads.name')}}
						</th>
						<th data-sort="created_at"
							tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'type_ads.created_at')}}
						</th>
						<th data-sort="created_at"
							tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'type_ads.created_by')}}
						</th>
						<th data-sort="active"
							tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width:190px;"
							aria-label="Name machine: activate to sort column ascending">{{trans('global.status')}}
						</th>
						<th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Operation: activate to sort column ascending">{{trans('global.operation')}}
						</th>
					</tr>
				</thead>

				<tbody>
					@if (isset($list_type_ads))
					@foreach ($list_type_ads as $type_ads)
					<tr role="row" class="odd">
						<td>{{$type_ads->id}}</td>
						<td>{{$type_ads->name}}</td>
						<td>{{date('d-m-Y H:i',strtotime($type_ads->created_at))}}</td>
						<td>
							{{$type_ads->_created_by?$type_ads->_created_by->full_name:''}}
						</td>
						<td>
								@if($type_ads->active == 1)
								<b class="text-success">{{trans('global.active')}}</b>
								@else
								<b class="text-danger">{{trans('global.inactive')}}</b>
								@endif
						</td>
						<td>
							@if(Auth::guard('web')->user()->can('edit_TypeAds'))
							<a href="{{route('update_type_ads', ['id' => $type_ads->id])}}">{{trans('global.edit')}}</a>
							@endif
							@if(Auth::guard('web')->user()->can('delete_TypeAds'))
							/ <a href="" data-toggle="modal" data-target="#delete_type_ads" onclick="deleteTypeAds('{{$type_ads->name}}','{{route('delete_type_ads', ['id' => $type_ads->id])}}')">{{trans('global.delete')}}</a>
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
			{!! $list_type_ads->appends([
				'keyword' => isset($keyword) ? $keyword : ''
			])->render() !!}
		</div>
	</div>
</div>
<div class="modal fade" id="delete_type_ads" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">{{trans('Admin'.DS.'type_ads.confirm_del')}}</h3>
            </div>
            <div class="modal-body">
                <p id="name_client"></p>
            </div>
            <div class="modal-footer">
                <a id="link_delete" href="" class="btn btn-primary">{{trans(DS.'global.delete')}}</a>
                <button type="button" data-dismiss="modal" class="btn">{{trans(DS.'global.cancel')}}</button>
            </div>
        </div>
    </div>
</div>

<style>
	.search_group .col-md-4{
		padding-top: 10px;
	}
	.search_group input,
	.search_group select{
		min-width: 150px;
	}
	.like_update{
		max-width:90px;
	}
</style>
@endsection

@section('JS')
<script type="text/javascript">
	var json_sort = [];
	function deleteTypeAds(name, link){
		$('#name_client').text('{{trans('valid.confirm_delete_type_ads')}} ' + name + '?');
		$('#link_delete').attr('href', link);
	}

</script>
@endsection

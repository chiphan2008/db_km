@extends('Admin.layout_admin.master_admin')

@section('content')

<div class="form-inline dt-bootstrap no-footer">
	<div class="row">


		<div class="col-sm-12">
			{{ csrf_field() }}
			@if(Auth::guard('web')->user()->can('add_Option'))
      <div class="pull-right">
          <!-- <div class="btn-group"> -->
          	<a type="button" href="{{route('list_room_type',['hotel_id'=>$hotel_id])}}" class="btn btn-success">Room type</a>
            <a type="button" href="{{route('add_option',['hotel_id'=>$hotel_id])}}" class="btn btn-primary">Add</a>

          <!-- </div> -->
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
							aria-label="Name: activate to sort column ascending">Name
						</th>
						<th data-sort="created_at"
							tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'option.created_at')}}
						</th>
						<th data-sort="created_at"
							tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'option.created_by')}}
						</th>
						<th data-sort="updated_at"
							tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'option.updated_by')}}
						</th>
						<th data-sort="active"
							tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Name machine: activate to sort column ascending">{{trans('global.active')}}
						</th>
						<th data-sort="moderation"
							tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Create by: activate to sort column ascending">{{trans('Admin'.DS.'option.extra')}}
						</th>
						<th data-sort="moderation"
							tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Create by: activate to sort column ascending">{{trans('Admin'.DS.'option.extra')}}
						</th>
						<th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Operation: activate to sort column ascending">{{trans('global.operation')}}
						</th>
					</tr>
				</thead>

				<tbody>
					@if (isset($list_option))
					@foreach ($list_option as $option)
					<tr role="row" class="odd">
						<td>{{$option->id}}</td>
						<td>{{$option->name}}</td>
						<td>{{date('d-m-Y H:i',strtotime($option->created_at))}}</td>
						<td>
							{{$option->_created_by->full_name}}
						</td>
						<td>
							{{$option->_updated_by->full_name}}
						</td>
						<td>
							@if($option->active == 1)
							<b class="text-success">{{trans('global.active')}}</b>
							@else
							<b class="text-danger">{{trans('global.inactive')}}</b>
							@endif
						</td>
						<td>
							@if($option->extra == 0)
							<b class="text-success">{{trans('Admin'.DS.'option.no_extra')}}</b>
							@else
							<b class="text-danger">{{trans('Admin'.DS.'option.extra')}}</b>
							@endif
						</td>
						<td>
							{{money_number($option->price_extra)}}
						</td>
						<td>
							@if((Auth::guard('web')->user()->can('edit_Option') && Auth::guard('web')->user()->id == $option->created_by) ||
							Auth::guard('web')->user()->hasRole('super_admin') || Auth::guard('web')->user()->hasRole('admin') ||
							Auth::guard('web')->user()->hasRole('admin_content') || Auth::guard('web')->user()->hasRole('content-update'))
							<a href="{{route('update_option', ['id' => $option->id])}}">{{trans('global.edit')}}</a>
							@endif
							@if((Auth::guard('web')->user()->can('delete_Option') &&
							Auth::guard('web')->user()->id == $option->created_by) || Auth::guard('web')->user()->hasRole('super_admin')
							|| Auth::guard('web')->user()->hasRole('admin'))
							/ <a href="" data-toggle="modal" data-target="#delete_content" onclick='deleteContent("{{$option->name}}","{{route('delete_option', ['id' => $option->id])}}")'>{{trans('global.delete')}}</a>
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


		<div class="modal fade" id="delete_content" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h3 class="modal-title">Xóa Hotel</h3>
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

		<div class="modal fade" id="confirm_status" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h3 class="modal-title">Thay đổi Hotel</h3>
					</div>
					<div class="modal-body">
						<p>Bạn có chắc chắn về sự thay đổi</p>
					</div>
					<div class="modal-footer">
						{{--<a id="link_delete" href="" class="btn btn-primary">Save</a>--}}
						<button type="button" data-dismiss="modal"  onclick="$('#change_status_content').submit()" class="btn btn-primary">Save</button>
						<button type="button" data-dismiss="modal" class="btn">{{trans('global.cancel')}}</button>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-12" style="text-align: right">
			{!! $list_option->appends([
				'keyword' => isset($keyword) ? $keyword : ''
			])->render() !!}
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
	function deleteContent(name, link){
		$('#name_client').text('{{trans('valid.confirm_delete_option')}}' + name);
		$('#link_delete').attr('href', link);
	}
</script>
@endsection

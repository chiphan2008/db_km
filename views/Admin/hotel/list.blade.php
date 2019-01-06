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
						<div class="col-md-6">
							<label class="control-label col-md-4 col-sm-4 col-xs-12">Type</label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<select class="form-control" name="type" id="type"  style="width:100%">
									<option value="0">-- Type --</option>
									@foreach($types as $type_one)
									<option value="{{$type_one->id}}" {{$type_one->id==$type?'selected':''}}>{{$type_one->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>


					<div class="col-md-12 text-center" style="padding-top: 10px;width:100%;">
						<input type="hidden" name="sort" id="sort" value="">
						<button type="submit" class="btn btn-success">{{trans('global.search')}}</button>
					</div>

				</form>
			</div>
		</div>

		<div class="col-sm-12">
			{{ csrf_field() }}
			@if(Auth::guard('web')->user()->can('add_Hotel'))
      <div class="pull-right">
          <div class="btn-group">
              <button type="button" class="btn btn-primary">Add</button>
              <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
              </button>
              <ul class="dropdown-menu" style="right: 0;left: auto;" role="menu">
              	<li>
              		<a href="{{route('add_hotel_from_content')}}">
              			Add from content
	              	</a>
	              </li>
              	<!-- <li>
              		<a href="">
              			Add new
              		</a>
              	</li> -->
              </ul>
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
							aria-label="Name machine: activate to sort column ascending">Create At
						</th>
						<th data-sort="created_at"
							tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Name machine: activate to sort column ascending">Create By
						</th>
						<th data-sort="updated_at"
							tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Name machine: activate to sort column ascending">Updated By
						</th>
						<th data-sort="active"
							tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Name machine: activate to sort column ascending">Status
						</th>
						<th data-sort="moderation"
							tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Create by: activate to sort column ascending">
						</th>
						<th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Operation: activate to sort column ascending">{{trans('global.operation')}}
						</th>
					</tr>
				</thead>

				<tbody>
					@if (isset($list_hotel))
					@foreach ($list_hotel as $hotel)
					<tr role="row" class="odd">
						<td>{{$hotel->id}}</td>
						<td>{{$hotel->_content->name}}</td>
						<td>{{date('d-m-Y H:i',strtotime($hotel->created_at))}}</td>
						<td>
							{{$hotel->_created_by->full_name}}
						</td>
						<td>
							{{$hotel->_updated_by->full_name}}
						</td>
						<td>
							@if($hotel->active == 1)
							<b class="text-success">{{trans('global.active')}}</b>
							@else
							<b class="text-danger">{{trans('global.inactive')}}</b>
							@endif
						</td>
						<td>

							@if((Auth::guard('web')->user()->can('edit_Hotel') && Auth::guard('web')->user()->id == $hotel->_content->created_by) ||
							Auth::guard('web')->user()->hasRole('super_admin') || Auth::guard('web')->user()->hasRole('admin') ||
							Auth::guard('web')->user()->hasRole('admin_content') || Auth::guard('web')->user()->hasRole('content-update'))
							<a class='btn btn-primary' href="{{route('list_room_type', ['hotel_id' => $hotel->id])}}">Manage room type</a>
							@endif

						</td>
						<td>
							@if((Auth::guard('web')->user()->can('edit_Hotel') && Auth::guard('web')->user()->id == $hotel->_content->created_by) ||
							Auth::guard('web')->user()->hasRole('super_admin') || Auth::guard('web')->user()->hasRole('admin') ||
							Auth::guard('web')->user()->hasRole('admin_content') || Auth::guard('web')->user()->hasRole('content-update'))
							<a href="{{route('update_hotel', ['id' => $hotel->id])}}">{{trans('global.edit')}}</a>
							@endif
							@if((Auth::guard('web')->user()->can('delete_Hotel') &&
							Auth::guard('web')->user()->id == $hotel->_content->created_by) || Auth::guard('web')->user()->hasRole('super_admin')
							|| Auth::guard('web')->user()->hasRole('admin'))
							/ <a href="" data-toggle="modal" data-target="#delete_content" onclick='deleteContent("{{$hotel->_content->name}}","{{route('delete_hotel', ['id' => $hotel->id])}}")'>{{trans('global.delete')}}</a>
							@endif

							@if((Auth::guard('web')->user()->can('edit_Content') && Auth::guard('web')->user()->id == $hotel->_content->created_by) ||
							Auth::guard('web')->user()->hasRole('super_admin') || Auth::guard('web')->user()->hasRole('admin') ||
							Auth::guard('web')->user()->hasRole('admin_content') || Auth::guard('web')->user()->hasRole('content-update'))
							/ <a href="{{route('update_content', ['category_type'=>$hotel->_content->_category_type->machine_name,'id' => $hotel->_content->id])}}">{{trans('global.edit')}} {{strtolower(trans('global.content'))}} </a>
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
			{!! $list_hotel->appends([
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
		$('#name_client').text('{{trans('valid.confirm_delete_hotel')}}' + name);
		$('#link_delete').attr('href', link);
	}
</script>
@endsection

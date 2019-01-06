@extends('Admin.layout_admin.master_admin')
@section('content')

	<div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
		<div class="row">

			<div class="col-sm-8">
				<div class="x_panel">
					<form class="form-horizontal form-label-left" method="get" action="{{route('list_notifi')}}">
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
			@if(Auth::guard('web')->user()->can('add_Notifi'))
				<div class="col-sm-4">
					<a href="{{route('add_notifi')}}" style="float: right"
						 class="btn btn-primary">{{trans('Admin'.DS.'notifi.add_notifi')}}</a>
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
						<th class="sorting" data-sort="id"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'notifi.id')}}

						</th>
						<th class="sorting" data-sort="name"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'notifi.title')}}
						</th>

						<th class="sorting" data-sort="active"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Name machine: activate to sort column ascending">{{trans('global.active')}}
						</th>

						<th class="sorting" data-sort="active"  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Name machine: activate to sort column ascending">{{trans('global.status')}}
						</th>

						<th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Operation: activate to sort column ascending"
								style="width: 150px;">{{trans('global.operation')}}
						</th>
					</tr>
					</thead>

					<tbody>
					@if (isset($list_notifi))
						@foreach ($list_notifi as $notifi)
							<tr role="row" class="odd">
								<td>{{$notifi->id}}</td>
								<td>{{$notifi->title != '' ? $notifi->title : 'Content'}}</td>
								<td>
									@if($notifi->active)
										<b class="text-success">{{trans('global.active')}}</b>
									@else
										<b class="text-danger">{{trans('global.inactive')}}</b>
									@endif
								</td>
								<td>
									@if($notifi->show)
										<b class="text-success">{{trans('global.show')}}</b>
									@else
										<b class="text-danger">{{trans('global.hide')}}</b>
									@endif
								</td>
								<td>
									@if(Auth::guard('web')->user()->can('edit_Notifi'))
										<a href="{{route('update_notifi', ['id' => $notifi->id])}}">{{trans('global.edit')}}</a>
									@endif

									@if(Auth::guard('web')->user()->can('delete_Notifi'))
										/ <a href="" data-toggle="modal" data-target="#delete_notifi" onclick='deleteNotifi("{{$notifi->title}}","{{route('delete_notifi', ['id' => $notifi->id])}}")'>{{trans('global.delete')}}</a>
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
				{!! $list_notifi->appends(['keyword' => isset($keyword) ? $keyword : ''])->render() !!}
			</div>
		</div>
	</div>
	<div class="modal fade" id="delete_notifi" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h3 class="modal-title">Xóa Notify</h3>
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
@endsection

@section('JS')
<script>
	var json_sort = [];
	function deleteNotifi(name,link)
	{
		$('#name_client').text('{{trans('valid.confirm_delete_notify')}}' + name);
		$('#link_delete').attr('href', link);
	}
</script>
@endsection

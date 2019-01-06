@extends('Admin.layout_admin.master_admin')

@section('content')
	<div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
		<div class="row">

			<div class="col-sm-8">
				<div class="x_panel">
					<form class="form-horizontal form-label-left" method="get" action="{{url()->current()}}">

						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('global.keyword')}}</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<input type="text" id="keyword" value="{{ isset($keyword) ? $keyword : '' }}" name="keyword" class="form-control" placeholder="{{trans('global.keyword')}}">
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

			@if(Auth::guard('web')->user()->can('add_Type'))
			<div class="pull-right" style="margin-right: 15px;">
				<a href="{{route('add_type')}}" style="float: right" class="btn btn-primary">{{trans('Admin'.DS.'type.add_type')}}</a>
			</div>
			@endif
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

			{{--///////////////////////////////////////////// --}}
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
				{{--////////////////////////////////////////////////////////--}}
				<table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid"
							 aria-describedby="datatable_info">
					<thead>
					<tr role="row">
						<th data-sort="id" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Name: activate to sort column ascending" style="width: 50px;">ID
						</th>
						<th data-sort="name" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Name: activate to sort column ascending" style="width: 117px;">{{trans('Admin'.DS.'type.name')}}
						</th>
						<th data-sort="weight" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Weight: activate to sort column ascending" style="width: 50px;">{{trans('Admin'.DS.'type.weight')}}
						</th>
						<th data-sort="created_at" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Create at: activate to sort column ascending" style="width: 75px;">{{trans('Admin'.DS.'type.created_at')}}
						</th>
						<th data-sort="created_by" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Create by: activate to sort column ascending" style="width: 75px;">{{trans('Admin'.DS.'type.created_by')}}
						</th>
						<th data-sort="active" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Active: activate to sort column ascending" style="width: 60px;">{{trans('global.active')}}

						</th>
						<th  tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Operation: activate to sort column ascending" style="width: 90px;">{{trans('global.operation')}}
						</th>
					</tr>
					</thead>

					<tbody>
					@if (isset($list_type))
						@foreach ($list_type as $key => $type)
							<tr role="row" class="odd">
								<td>{{$type->id}}</td>
								<td>{{$type->name}}</td>
								<td style="text-align: center">
									@if(Auth::guard('web')->user()->can('edit_Type'))
									<input onchange="changeWeight(this)" data-id="{{$type->id}}" style="width: 75px;" type="number" class="order form-control" max="9999" min="1"
									 value="{{$type->weight}}" maxlength="4">
									@else
									{{$type->weight}}
									@endif
								</td>
								<td>{{date('d-m-Y',strtotime($type->created_at))}}</td>
								<td>{{$type->_created_by->full_name}}</td>
								<td>
									@if($type->active)
										<b class="text-success">{{trans('global.active')}}</b>
									@else
										<b class="text-danger">{{trans('global.inactive')}}</b>
									@endif
								</td>
								<td>
									@if(Auth::guard('web')->user()->can('edit_Type'))
									<a href="{{route('update_type',['id' => $type->id])}}">{{trans('global.edit')}}</a>
									@endif
									@if(Auth::guard('web')->user()->can('delete_Type'))
									 / <a href="" data-toggle="modal" data-target="#delete_type" onclick="deleteType('{{$type->name}}','{{route('delete_type',['id' => $type->id])}}')">{{trans('global.delete')}}</a>
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

					<div class="modal fade" id="delete_type" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title">Xóa Type Item</h4>
								</div>
								<div class="modal-body">
									<p id="name_type"></p>
								</div>
								<div class="modal-footer">
									<a id="link_delete" href="" class="btn btn-primary">{{trans('global.delete')}}</a>
									<button type="button" data-dismiss="modal" class="btn">{{trans('global.cancel')}}</button>
								</div>
							</div>
						</div>
					</div>
			</div>
			<div class="col-sm-12" style="text-align: right">
				{!! $list_type->appends(['keyword' => isset($keyword) ? $keyword : '', 'sort'=>isset($qsort)?$qsort:''])->render() !!}
			</div>
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
				window.location = '/booking/type/change-weight/'+id+'/'+weight;
			}
		}


    function deleteType(name,link)
    {
      $('#name_type').text('{{trans('valid.confirm_delete_type')}}' + name);
      $('#link_delete').attr('href', link);
    }
		var json_sort = [];

	</script>
@endsection

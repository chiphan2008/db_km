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
								<input type="hidden" name="sort" id="sort" value="">
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
			@if(Auth::guard('web')->user()->can('add_Module'))
			<div class="col-sm-4">
				<a href="{{route('add_module')}}" style="float: right" class="btn btn-primary">{{trans('Admin'.DS.'module.add_module')}}</a>
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
						<th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="ID: activate to sort column ascending" type-sort="{{isset($sort['id'])?$sort['id']:''}}" data-sort="id" style="width: 50px;">ID
								@if(isset($sort['id']))
									@if($sort['id']=='asc')
									<i class="fa fa-sort-asc"></i>
									@else
									<i class="fa fa-sort-desc"></i>
									@endif
								@endif
						</th>
						<th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Name: activate to sort column ascending" type-sort="{{isset($sort['name'])?$sort['name']:''}}" data-sort="name" style="width: 117px;">{{trans('Admin'.DS.'module.name')}}
								@if(isset($sort['name']))
									@if($sort['name']=='asc')
									<i class="fa fa-sort-asc"></i>
									@else
									<i class="fa fa-sort-desc"></i>
									@endif
								@endif
						</th>
            <!-- <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Name machine: activate to sort column ascending" type-sort="" data-sort="" style="width: 117px;">{{trans('Admin'.DS.'module.machine_name')}}
						</th> -->
            <!-- <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Parent: activate to sort column ascending" type-sort="" data-sort="" style="width: 117px;">{{trans('Admin'.DS.'module.parent')}}
						</th> -->

						<th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Create at: activate to sort column ascending" type-sort="{{isset($sort['created_at'])?$sort['created_at']:''}}" data-sort="created_at" style="width: 75px;">{{trans('Admin'.DS.'module.created_at')}}
								@if(isset($sort['created_at']))
									@if($sort['created_at']=='asc')
									<i class="fa fa-sort-asc"></i>
									@else
									<i class="fa fa-sort-desc"></i>
									@endif
								@endif
						</th>
						<th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Create by: activate to sort column ascending" type-sort="{{isset($sort['created_by'])?$sort['created_by']:''}}" data-sort="created_by" style="width: 75px;">{{trans('Admin'.DS.'module.created_by')}}
								@if(isset($sort['created_by']))
									@if($sort['created_by']=='asc')
									<i class="fa fa-sort-asc"></i>
									@else
									<i class="fa fa-sort-desc"></i>
									@endif
								@endif
						</th>
						<th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Active: activate to sort column ascending" type-sort="{{isset($sort['active'])?$sort['active']:''}}" data-sort="active" style="width: 60px;">{{trans('global.active')}}
								@if(isset($sort['active']))
									@if($sort['active']=='asc')
									<i class="fa fa-sort-asc"></i>
									@else
									<i class="fa fa-sort-desc"></i>
									@endif
								@endif
						</th>
						<th class="" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Operation: activate to sort column ascending" style="width: 90px;">{{trans('global.operation')}}
						</th>
					</tr>
					</thead>

					<tbody>
					@if (isset($list_module))
						@foreach ($list_module as $key => $module)
							<tr role="row" class="odd">
								<td>{{$module->id}}</td>
								<td>{{$module->name}}</td>
								<td>{{date('d-m-Y',strtotime($module->created_at))}}</td>
								<td>{{$module->_created_by->full_name}}</td>
								<td>
									@if($module->active)
										<b class="text-success">{{trans('global.active')}}</b>
									@else
										<b class="text-danger">{{trans('global.inactive')}}</b>
									@endif
								</td>
								<td>
									@if(Auth::guard('web')->user()->can('edit_Module'))
									<a href="{{route('list_module_category', ['id' => $module->id])}}">{{trans('global.category')}}</a>
									@endif
									@if(Auth::guard('web')->user()->can('edit_Module'))
									/ <a href="{{route('update_module', ['id' => $module->id])}}">{{trans('global.edit')}}</a>
									@endif
									@if(Auth::guard('web')->user()->can('delete_Module'))
									{{--/ <a href="javascript: deleteModule({{$module->id}})">{{trans('global.delete')}}</a>--}}
									/ <a href="" data-toggle="modal" data-target="#delete_module" onclick="deleteModule('{{$module->name}}','{{route('delete_module', ['id' => $module->id])}}')">{{trans('global.delete')}}</a>
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

			<div class="modal fade" id="delete_module" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Xóa Module</h4>
						</div>
						<div class="modal-body">
							<p id="name_module"></p>
						</div>
						<div class="modal-footer">
							<a id="link_delete" href="" class="btn btn-primary">{{trans('global.delete')}}</a>
							<button type="button" data-dismiss="modal" class="btn">{{trans('global.cancel')}}</button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12" style="text-align: right">
				{!! $list_module->appends(['keyword' => isset($keyword) ? $keyword : '', 'sort'=>isset($qsort)?$qsort:''])->render() !!}
			</div>
		</div>
	</div>
	<style>
		.cursor{
			cursor: pointer;
		}
	</style>
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
				window.location = '/admin/module/change-weight/'+id+'/'+weight;
			}
		}

		var json_sort = {!! json_encode($sort,JSON_FORCE_OBJECT) !!};

    function deleteModule(name,link)
    {
      $('#name_module').text('{{trans('Admin'.DS.'module.confirm_del')}} : ' + name);
      $('#link_delete').attr('href', link);
    }
		{{--function deleteModule(id) {--}}
			{{--if( confirm('{{trans('Admin'.DS.'module.confirm_delete')}}') ) {--}}
				{{--window.location = '/admin/module/delete/'+id--}}
			{{--}--}}
		{{--}--}}
	</script>
@endsection
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
			@if(Auth::guard('web')->user()->can('add_RaovatType'))
			<div class="col-sm-4">
				<a href="{{route('add_raovat_type')}}" style="float: right" class="btn btn-primary">{{trans('Admin'.DS.'raovat_type.add_raovat_type')}}</a>
				<!-- <a href="{{''}}" style="float: right" class="btn btn-success">{{trans('Admin'.DS.'raovat_type.approve_raovat_type')}}</a> -->
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
								aria-label="Name: activate to sort column ascending" type-sort="{{isset($sort['name'])?$sort['name']:''}}" data-sort="name" style="width: 117px;">{{trans('Admin'.DS.'raovat_type.name')}}
								@if(isset($sort['name']))
									@if($sort['name']=='asc')
									<i class="fa fa-sort-asc"></i>
									@else
									<i class="fa fa-sort-desc"></i>
									@endif
								@endif
						</th>
            <!-- <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Name machine: activate to sort column ascending" type-sort="" data-sort="" style="width: 117px;">{{trans('Admin'.DS.'raovat_type.machine_name')}}
						</th> -->
            <!-- <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Parent: activate to sort column ascending" type-sort="" data-sort="" style="width: 117px;">{{trans('Admin'.DS.'raovat_type.parent')}}
						</th> -->
						<th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Parent: activate to sort column ascending" type-sort="{{isset($sort['weight'])?$sort['weight']:''}}" data-sort="weight" style="width: 75px;">{{trans('Admin'.DS.'raovat_type.weight')}}
								@if(isset($sort['weight']))
									@if($sort['weight']=='asc')
									<i class="fa fa-sort-asc"></i>
									@else
									<i class="fa fa-sort-desc"></i>
									@endif
								@endif
						</th>
						<th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Create at: activate to sort column ascending" type-sort="{{isset($sort['created_at'])?$sort['created_at']:''}}" data-sort="created_at" style="width: 75px;">{{trans('Admin'.DS.'raovat_type.created_at')}}
								@if(isset($sort['created_at']))
									@if($sort['created_at']=='asc')
									<i class="fa fa-sort-asc"></i>
									@else
									<i class="fa fa-sort-desc"></i>
									@endif
								@endif
						</th>
						<th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Create by: activate to sort column ascending" type-sort="{{isset($sort['created_by'])?$sort['created_by']:''}}" data-sort="created_by" style="width: 75px;">{{trans('Admin'.DS.'raovat_type.created_by')}}
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
					@if (isset($list_raovat_type))
						@foreach ($list_raovat_type as $key => $raovat_type)
							<tr role="row" class="odd">
								<td>{{$raovat_type->id}}</td>
								<td>{{$raovat_type->name}}</td>
								<!-- <td>{{$raovat_type->machine_name}}</td> -->
								<!-- <td>{{$raovat_type->parent != 0 ? $raovat_type->_parent->name : ''}}</td> -->
								<td>
									<!-- @if($key!=0) 
									<a href="/raovat/raovat_type/up-weight/{{$raovat_type->id}}" title=""><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span> </a>
									@else
									&nbsp;&nbsp;&nbsp;
									@endif
									&nbsp;&nbsp;&nbsp;
									@if($key!= count($list_raovat_type)-1)
									<a href="/raovat/raovat_type/down-weight/{{$raovat_type->id}}" title=""><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span> </a>
									@else
									&nbsp;&nbsp;&nbsp;
									@endif -->
									@if(Auth::guard('web')->user()->can('edit_RaovatType'))
									<input onchange="changeWeight(this)" data-id="{{$raovat_type->id}}" style="width: 75px;" type="number" class="order form-control" max="9999" min="1"
									 value="{{$raovat_type->weight}}" maxlength="4">
									@else
									{{$raovat_type->weight}}
									@endif
								</td>
								<td>{{date('d-m-Y',strtotime($raovat_type->created_at))}}</td>
								<td>{{$raovat_type->_created_by?$raovat_type->_created_by->full_name:''}}</td>
								<td>
									@if($raovat_type->active)
										<b class="text-success">{{trans('global.active')}}</b>
									@else
										<b class="text-danger">{{trans('global.inactive')}}</b>
									@endif
								</td>
								<td>
									@if(Auth::guard('web')->user()->can('view_RaovatSubType'))
									<a href="{{route('list_raovat_subtype', ['id' => $raovat_type->id])}}">{{trans('global.list')}}</a> / 
									@endif
									@if(Auth::guard('web')->user()->can('edit_RaovatType'))
									<a href="{{route('update_raovat_type', ['id' => $raovat_type->id])}}">{{trans('global.edit')}}</a>
									@endif
									@if(Auth::guard('web')->user()->can('delete_RaovatType'))
									{{--/ <a href="javascript: deleteCategory({{$raovat_type->id}})">{{trans('global.delete')}}</a>--}}
									/ <a href="" data-toggle="modal" data-target="#delete_raovat_type" onclick="deleteCategory('{{$raovat_type->name}}','{{route('delete_raovat_type', ['id' => $raovat_type->id])}}')">{{trans('global.delete')}}</a>
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

			<div class="modal fade" id="delete_raovat_type" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Xóa Category</h4>
						</div>
						<div class="modal-body">
							<p id="name_raovat_type"></p>
						</div>
						<div class="modal-footer">
							<a id="link_delete" href="" class="btn btn-primary">{{trans('global.delete')}}</a>
							<button type="button" data-dismiss="modal" class="btn">{{trans('global.cancel')}}</button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12" style="text-align: right">
				{!! $list_raovat_type->appends(['keyword' => isset($keyword) ? $keyword : '', 'sort'=>isset($qsort)?$qsort:''])->render() !!}
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
				window.location = '/raovat/raovat_type/change-weight/'+id+'/'+weight;
			}
		}

		var json_sort = {!! json_encode($sort,JSON_FORCE_OBJECT) !!};

    function deleteCategory(name,link)
    {
      $('#name_raovat_type').text('{{trans('Admin'.DS.'raovat_type.confirm_del')}} : ' + name);
      $('#link_delete').attr('href', link);
    }
		{{--function deleteCategory(id) {--}}
			{{--if( confirm('{{trans('Admin'.DS.'raovat_type.confirm_delete')}}') ) {--}}
				{{--window.location = '/raovat/raovat_type/delete/'+id--}}
			{{--}--}}
		{{--}--}}
	</script>
@endsection
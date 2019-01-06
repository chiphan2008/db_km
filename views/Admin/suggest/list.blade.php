@extends('Admin.layout_admin.master_admin')

@section('content')
	<div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
		<div class="row">
			<div class="col-sm-5">
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
			@if(Auth::guard('web')->user()->can('add_Suggest'))
			<div class="col-sm-7">
				<div class="x_panel">
					<form class="form-inline form-label-left" method="post" action="{{route('add_suggest')}}" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="col-md-6 form-group">
							<label class="control-label">{{trans('Admin'.DS.'suggest.keyword')}}&nbsp;&nbsp;</label>
							<input class="form-control" type="text" id="keyword" name="keyword" placeholder="{{trans('Admin'.DS.'suggest.keyword')}}">
						</div>
						<div class="col-md-3 form-group">
							<label class="control-label">{{trans('Admin'.DS.'suggest.weight')}}&nbsp;&nbsp;</label>
							<input style="max-width: 70px;" class="form-control" type="number" id="weight" value="0" min="0" name="weight" placeholder="{{trans('Admin'.DS.'suggest.weight')}}">
						</div>
						<div class="col-md-2 text-center form-group">
							<button type="submit" class="btn btn-primary">{{trans('Admin'.DS.'suggest.add_suggest')}}</button>
						</div>
					</form>
				</div>
			</div>
			@else
			{{ csrf_field() }}
			@endif
			<div class="col-md-12">
				<div class="pull-left">
					@if(Auth::guard('web')->user()->can('delete_Suggest'))
					<a  data-toggle="modal" data-target="#delete_multi_suggest" style="float: right" class="btn btn-danger">{{trans('global.delete')}}</a>
					@endif
				</div>
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
				@if(session('error'))
					<div class="alert alert-danger alert-dismissible fade in" style="color: #a94442;background-color: #f2dede;border-color: #ebccd1;" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
						</button>
						{!! session('error') !!}
					</div>
				@endif
				{{--////////////////////////////////////////////////////////--}}
				<table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid"
							 aria-describedby="datatable_info">
					<thead>
					<tr role="row">
						<th style="width: 10px;">
							<input type="checkbox" class="check_all" onclick="checkAll(this)">
						</th>
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
								aria-label="Name: activate to sort column ascending" type-sort="{{isset($sort['keyword'])?$sort['keyword']:''}}" data-sort="keyword" style="width: 217px;">{{trans('Admin'.DS.'suggest.keyword')}}
								@if(isset($sort['keyword']))
									@if($sort['keyword']=='asc')
									<i class="fa fa-sort-asc"></i>
									@else
									<i class="fa fa-sort-desc"></i>
									@endif
								@endif
						</th>
						<!-- <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Name machine: activate to sort column ascending" type-sort="" data-sort="" style="width: 117px;">{{trans('Admin'.DS.'suggest.machine_name')}}
						</th> -->
						<!-- <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Parent: activate to sort column ascending" type-sort="" data-sort="" style="width: 117px;">{{trans('Admin'.DS.'suggest.parent')}}
						</th> -->
						<th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Parent: activate to sort column ascending" type-sort="{{isset($sort['weight'])?$sort['weight']:''}}" data-sort="weight" style="width: 75px;">{{trans('Admin'.DS.'suggest.weight')}}
								@if(isset($sort['weight']))
									@if($sort['weight']=='asc')
									<i class="fa fa-sort-asc"></i>
									@else
									<i class="fa fa-sort-desc"></i>
									@endif
								@endif
						</th>
						<th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Create at: activate to sort column ascending" type-sort="{{isset($sort['created_at'])?$sort['created_at']:''}}" data-sort="created_at" style="width: 75px;">{{trans('Admin'.DS.'suggest.created_at')}}
								@if(isset($sort['created_at']))
									@if($sort['created_at']=='asc')
									<i class="fa fa-sort-asc"></i>
									@else
									<i class="fa fa-sort-desc"></i>
									@endif
								@endif
						</th>
						<th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
								aria-label="Create by: activate to sort column ascending" type-sort="{{isset($sort['created_by'])?$sort['created_by']:''}}" data-sort="created_by" style="width: 75px;">{{trans('Admin'.DS.'suggest.created_by')}}
								@if(isset($sort['created_by']))
									@if($sort['created_by']=='asc')
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
					@if (isset($list_suggest))
						@foreach ($list_suggest as $key => $suggest)
							<tr role="row" class="odd">
								<td>
									<input class="check-one" type="checkbox" value="{{$suggest->id}}"/>
								</td>
								<td>{{$suggest->id}}</td>
								<td>
									@if(Auth::guard('web')->user()->can('edit_Suggest'))
									<input onchange="changeKeyword(this)" data-id="{{$suggest->id}}" style="width: 100%;" type="text" class="form-control" value="{{$suggest->keyword}}" maxlength="124">
									@else
									{{$suggest->keyword}}
									@endif
								</td>
								<!-- <td>{{$suggest->machine_name}}</td> -->
								<!-- <td>{{$suggest->parent != 0 ? $suggest->_parent->name : ''}}</td> -->
								<td>
									<!-- @if($key!=0) 
									<a href="/admin/suggest/up-weight/{{$suggest->id}}" title=""><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span> </a>
									@else
									&nbsp;&nbsp;&nbsp;
									@endif
									&nbsp;&nbsp;&nbsp;
									@if($key!= count($list_suggest)-1)
									<a href="/admin/suggest/down-weight/{{$suggest->id}}" title=""><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span> </a>
									@else
									&nbsp;&nbsp;&nbsp;
									@endif -->
									@if(Auth::guard('web')->user()->can('edit_Suggest'))
									<input onchange="changeWeight(this)" data-id="{{$suggest->id}}" style="width: 75px;" type="number" class="order form-control" max="9999" min="1"
									 value="{{$suggest->weight}}" maxlength="6">
									@else
									{{$suggest->weight}}
									@endif
								</td>
								<td>{{date('d-m-Y',strtotime($suggest->created_at))}}</td>
								<td>{{$suggest->_created_by?$suggest->_created_by->full_name:''}}</td>
								<td>
									@if(Auth::guard('web')->user()->can('edit_Suggest'))
									<a href="{{route('update_suggest', ['id' => $suggest->id])}}">{{trans('global.edit')}}</a>
									@endif
									@if(Auth::guard('web')->user()->can('delete_Suggest'))
									{{--/ <a href="javascript: deleteSuggest({{$suggest->id}})">{{trans('global.delete')}}</a>--}}
									/ <a href="" data-toggle="modal" data-target="#delete_suggest" onclick="deleteSuggest('{{$suggest->keyword}}','{{route('delete_suggest', ['id' => $suggest->id])}}')">{{trans('global.delete')}}</a>
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

			<div class="modal fade" id="delete_suggest" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<!-- <h4 class="modal-title">{{trans('global.delete')}} {{trans('Admin'.DS.'suggest.keyword')}}</h4> -->
						</div>
						<div class="modal-body">
							<p id="name_suggest"></p>
						</div>
						<div class="modal-footer">
							<a id="link_delete" href="" class="btn btn-primary">{{trans('global.delete')}}</a>
							<button type="button" data-dismiss="modal" class="btn">{{trans('global.cancel')}}</button>
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade" id="delete_multi_suggest" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<!-- <h4 class="modal-title">{{trans('global.delete')}} {{trans('Admin'.DS.'suggest.keyword')}}</h4> -->
						</div>
						<div class="modal-body">
							<p>{{trans('Admin'.DS.'suggest.confirm_del')}}</p>
						</div>
						<div class="modal-footer">
							<button type="button" onclick="deleteMultiSuggest()" href="" class="btn btn-primary">{{trans('global.delete')}}</button>
							<button type="button" data-dismiss="modal" class="btn">{{trans('global.cancel')}}</button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-12" style="text-align: right">
				{!! $list_suggest->appends(['keyword' => isset($keyword) ? $keyword : '', 'sort'=>isset($qsort)?$qsort:''])->render() !!}
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
				window.location = '/admin/suggest/change-weight/'+id+'/'+weight;
			}
		}

		function changeKeyword(obj){
			var id = $(obj).attr('data-id');
			var keyword = $(obj).val();
			if(keyword && id){
				window.location = '/admin/suggest/change-keyword/'+id+'/'+keyword;
			}
		}

		var json_sort = {!! json_encode($sort,JSON_FORCE_OBJECT) !!};

		function deleteSuggest(name,link)
		{
			$('#name_suggest').text('{{trans('Admin'.DS.'suggest.confirm_del')}} : ' + name);
			$('#link_delete').attr('href', link);
		}

		function checkAll(obj){
			$(".check-one").prop("checked",$(obj).is(":checked"))
		}

		function deleteMultiSuggest()
		{
			var data = {};
			if($(".check-one:checked").length){
				data['id'] = [];
				$(".check-one:checked").each(function(key,elem){
					data['id'].push($(elem).val())
				})
				data['_token'] = $('input[name="_token"]').val();
				$.ajax({
					url : '/admin/suggest/deleteMulti',
					type: 'POST',
					data:data,
					success:function(res){
						window.location.reload();
					}
				});
				console.log('data:',data);
			}
		}
	</script>
@endsection
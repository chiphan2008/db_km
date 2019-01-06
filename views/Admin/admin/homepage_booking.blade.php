@extends('Admin.layout_admin.master_admin')

@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="pull-right">
		      <a type="button" href="{{route('add_home_booking')}}" class="btn btn-primary">Add</a>
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
				<th data-sort="name" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
						aria-label="Name: activate to sort column ascending" style="width: 117px;">{{trans('Admin'.DS.'home_booking'.DS.'list.name')}}
				</th>
				<th data-sort="weight" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
						aria-label="Weight: activate to sort column ascending" style="width: 50px;">{{trans('Admin'.DS.'home_booking'.DS.'list.weight')}}
				</th>
				<th data-sort="weight" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
						aria-label="Weight: activate to sort column ascending" style="width: 50px;">{{trans('Admin'.DS.'home_booking'.DS.'list.image')}}
				</th>
				<th data-sort="created_at" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
						aria-label="Create at: activate to sort column ascending" style="width: 75px;">{{trans('Admin'.DS.'home_booking'.DS.'list.created_at')}}
				</th>
				<th data-sort="created_by" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
						aria-label="Create by: activate to sort column ascending" style="width: 75px;">{{trans('Admin'.DS.'home_booking'.DS.'list.created_by')}}
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
			@if (isset($list_home_booking))
				@foreach ($list_home_booking as $key => $home_booking)
					<tr role="row" class="odd">
						<td>{{$home_booking->name}}</td>
						<td style="text-align: center">
							<input onchange="changeWeight(this)" data-id="{{$home_booking->id}}" style="width: 75px;" type="number" class="order form-control" max="9999" min="1"
							 value="{{$home_booking->weight}}" maxlength="4">
						</td>
						<td>
							<img src="{{$home_booking->image}}" alt="" height="100">
						</td>
						<td>{{date('d-m-Y',strtotime($home_booking->created_at))}}</td>
						<td>{{$home_booking->_created_by->full_name}}</td>
						<td>
							@if($home_booking->active)
								<b class="text-success">{{trans('global.active')}}</b>
							@else
								<b class="text-danger">{{trans('global.inactive')}}</b>
							@endif
						</td>
						<td>
							<a href="{{route('update_home_booking',['id' => $home_booking->id])}}">{{trans('global.edit')}}</a>
							 /
							<a href="" data-toggle="modal" data-target="#delete_home_booking" onclick="deleteHomeBooking('{{$home_booking->name}}','{{route('delete_home_booking',['id' => $home_booking->id])}}')">{{trans('global.delete')}}</a>
						</td>
					</tr>
				@endforeach
			@else
				<tr role="row" class="odd">
					<td colspan="7">{{trans('global.no_data')}}</td>
				</tr>
			@endif
			</tbody>
		</table>
	</div>
</div>
<div class="modal fade" id="delete_home_booking" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<p id="name_home_booking"></p>
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
	function deleteHomeBooking(name,link){
    $('#name_home_booking').text('{{trans('valid.confirm_delete_city')}}' + name);
    $('#link_delete').attr('href', link);
  }
	function changeWeight(obj){
		var id = $(obj).attr('data-id');
		var weight = $(obj).val();
		if(weight && id){
			window.location = '/booking/homepage/change-weight/'+id+'/'+weight;
		}
	}
</script>
@endsection

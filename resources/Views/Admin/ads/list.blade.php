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
			<!-- @if(Auth::guard('web')->user()->can('add_Ads'))
      <div class="pull-right">
          <div class="btn-group">
              <a href="{{route('add_ads')}}" type="button" class="btn btn-primary">{{trans('Admin'.DS.'ads.add_ads')}}</a>
          </div>
      </div>
      @endif -->
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
							aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'ads.content')}}
						</th>
						<th data-sort="created_at"
							tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'ads.create_at')}}
						</th>
						<th data-sort="created_at"
							tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'ads.create_by')}}
						</th>
						<th data-sort="active"
							tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width:240px;"
							aria-label="Name machine: activate to sort column ascending">{{trans('global.status')}}
						</th>
						<!-- <th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Operation: activate to sort column ascending">{{trans('global.operation')}}
						</th> -->
					</tr>
				</thead>

				<tbody>
					@if (isset($list_ads))
					@foreach ($list_ads as $ads)
					<tr role="row" class="odd">
						<td>{{$ads->id}}</td>
						<td>{{$ads->name}}</td>
						<td>{{date('d-m-Y H:i',strtotime($ads->created_at))}}</td>
						<td>
								{{$ads->_created_by_client?$ads->_created_by_client->full_name:''}}
						</td>
						<td>
							@if($ads->approved == 0 && $ads->declined == 0)
									<button class="btn btn-success" onclick="approveAds({{$ads->id}})">{{trans('Admin'.DS.'ads.approve')}}</button>
									<button class="btn btn-danger" onclick="declineAds({{$ads->id}})">{{trans('Admin'.DS.'ads.decline')}}</button>
									@if(Auth::guard('web')->user()->can('delete_Ads'))
									<a class='btn btn-danger' href="" data-toggle="modal" data-target="#delete_ads" onclick="deleteAds('{{$ads->name}}','{{route('delete_ads', ['id' => $ads->id])}}')">{{trans('global.delete')}}</a>
									@endif
							@elseif($ads->declined)
								<b class="text-warning" data-toggle="tooltip" title="">{{ trans('Admin'.DS.'ads.declined') }}</b>
							@elseif($ads->approved)
								<b class="text-success" data-toggle="tooltip" title="">{{ trans('Admin'.DS.'ads.approved') }}</b>
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
			{!! $list_ads->appends([
				'keyword' => isset($keyword) ? $keyword : ''
			])->render() !!}
		</div>
	</div>
</div>
<div class="modal fade" id="delete_ads" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">{{trans('Admin'.DS.'ads.del_ads')}}</h3>
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

<div class="modal fade" id="approve_ads" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">{{trans('Admin'.DS.'ads.approve')}}</h3>
            </div>
            <div class="modal-body">
              <div class="content_ads">
              	
              </div>
            </div>
            <div class="modal-footer">
                <a id="link_approve" href="" class="btn btn-success">{{trans('Admin'.DS.'ads.approve')}}</a>
                <button type="button" data-dismiss="modal" class="btn">{{trans(DS.'global.cancel')}}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="decline_ads" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">{{trans('Admin'.DS.'ads.decline')}}</h3>
            </div>
            <div class="modal-body">
              <div class="content_ads" style="margin-bottom: 10px;">
              	
              </div>
              <form action="{{route('decline_ads')}}" method="POST" id="form_decline">
              	{{ csrf_field() }}
              	<input type="hidden" id="id_decline" name="id" value="">
              	<label class="control-label col-md-4 col-sm-4 col-xs-12 text-right">{{trans('Admin'.DS.'ads.note')}}</label>
								<div class="col-md-8 col-sm-8 col-xs-12">
									<textarea rows="4" name="declined_content" class="form-control" id="declined_content"></textarea>
								</div>
              </form>
            </div>
            <div class="modal-footer">
                <a id="link_decline" href="#" onclick="$('#form_decline').submit();" class="btn btn-danger">{{trans('Admin'.DS.'ads.decline')}}</a>
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
	function deleteAds(name, link){
		$('#name_client').text('{{trans('valid.confirm_delete_ads')}}' + name);
		$('#link_delete').attr('href', link);
	}

	function approveAds(id){
		$.ajax({
			url : '/ads/ads/getAds/'+id,
			type : 'GET',
			success: function(html){
				$('.content_ads').html(html);
				$("#link_approve").prop('href', '/ads/ads/approveAds/'+id);
				$("#approve_ads").modal();
			}
		})
	}

	function declineAds(id){
		$.ajax({
			url : '/ads/ads/getAds/'+id,
			type : 'GET',
			success: function(html){
				$('.content_ads').html(html);
				$('#id_decline').val(id);
				$("#approve_ads").modal('hide');
				$("#decline_ads").modal();
			}
		})
	}

</script>
@endsection

@extends('Admin.layout_admin.master_admin')

@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="x_panel">
			<form class="form-horizontal form-label-left" method="get" action="{{url()->current()}}">
				<div class="form-group col-md-4">
					<label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('global.keyword')}}</label>
					<div class="col-md-9 col-sm-9 col-xs-12">
						<input type="text" id="keyword" value="{{ isset($keyword) ? $keyword : '' }}" name="keyword" class="form-control" placeholder="{{trans('global.keyword')}}">
					</div>
				</div>
				<div class="form-group col-md-5">
					<label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('Admin'.DS.'comment.comment_by')}}</label>
					<div class="col-md-8 col-sm-8 col-xs-12">
						<select name="created_by" class="form-control selectpicker" id="select_client" data-live-search="true">
							<option value=""></option>
							@foreach($clients as $client)
							<option data-content="<img width='32' height='32' src='{{$client->avatar}}'> {{$client->full_name}}" value="{{$client->id}}" {{$client->id==$created_by?'selected':''}} >{{$client->full_name}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group col-md-3">
					<div class="col-md-9 col-sm-9 col-xs-12">
						<button type="submit" class="btn btn-success">{{trans('global.search')}}</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<a href="{{route('approve_comment')}}" style="float: right" class="btn btn-warning">{{trans('Admin'.DS.'comment.approve_comment')}}</a>
	</div>
</div>
<div class="row">
	@if(session('success'))
	<div class="alert alert-success alert-dismissible fade in" style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
	    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
	    </button>
	    {{session('success')}}
	</div>
	@endif
	@if(session('error'))
	<div class="alert alert-warning alert-dismissible fade in"  role="alert">
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
	  </button>
	  {{session('error')}}
	</div>
	@endif
</div>
<div class="row">
		<div class="col-md-12">
			<table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid"
								 aria-describedby="datatable_info">
				<thead>
				<tr role="row">
					<!-- <th class="" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Operation: activate to sort column ascending" style="width:12%;">{{trans('Admin'.DS.'comment.comment_by')}}
					</th> -->
					<th class="" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Operation: activate to sort column ascending" style="width:40%;">{{trans('Admin'.DS.'comment.content')}}
					</th>
					<th class="" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Operation: activate to sort column ascending" style="width: 8%">{{trans('global.status')}}
					</th>
					<th class="" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Operation: activate to sort column ascending" style="width: 12%">{{trans('global.active')}}
					</th>
					<th class="text-center" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Operation: activate to sort column ascending" style="width: 8%">{{trans('Admin'.DS.'comment.created_at')}}
					</th>
					<th class="text-center" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
							aria-label="Operation: activate to sort column ascending" style="width: 12%;">{{trans('global.operation')}}
					</th>
				</tr>
				</thead>

				<tbody>
				@if (isset($list_comments))
					@foreach ($list_comments as $key => $comment)
						@if($comment->_content)
						<tr role="row" class="odd">
							<td>
								<ul class="messages">
									<li>
                    <img src="{{$comment->_comment_by?$comment->_comment_by->avatar:''}}" class="avatar" alt="Avatar">
                    <div class="message_wrapper">
                    	<b>{{trans('Admin'.DS.'comment.location')}}:
                        <a class="heading label label-success label-cm" target="_blank" href="{{LOCATION_URL.'/'.$comment->_content->alias}}">
                        	{{$comment->_content->name}}
                        </a>
                  		</b><br/>
                      <b class='label label-info label-cm'>
                      	<i class="fa fa-user"></i> {{$comment->_comment_by?$comment->_comment_by->full_name:''}}
                      </b>
                      <blockquote class="message">
                      	{{$comment->content}}
                      	@if($comment->_images)
	                      <div class="row">
	                      	@foreach($comment->_images as $image)
	                      	<div class="col-md-2 col-xs-4 thumb-cm">
	                      		<a data-fancybox="images_comment-{{$comment->id}}" rel="comment-{{$comment->id}}" data-caption="" href="{{$image->link}}">
	                      			<img src="{{$image->thumb}}" alt="">
	                      		</a>
	                      	</div>
	                      	@endforeach
	                      </div>
	                      @endif
                    	</blockquote>
                    </div>
                  </li>
								</ul>
							</td>
							<td>
								@if($comment->approved)
									<b class="text-success">{{trans('Admin'.DS.'comment.approved')}}</b>
								@else
									@if($comment->declined)
									<b class="text-danger">{{trans('Admin'.DS.'comment.declined')}}</b>
									@else
									<b class="text-warning">{{trans('Admin'.DS.'comment.pending')}}</b>
									@endif
								@endif
							</td>
							<td>
								@if($comment->active)
								<b class="text-success">{{trans('global.active')}}</b>
								@else
								<b class="text-danger">{{trans('global.inactive')}}</b>
								@endif
							</td>
							<td class="text-center" >
								{{date('H:i:s',strtotime($comment->created_at))}}<br/>{{date('d-m-Y',strtotime($comment->created_at))}}
							</td>
							<td class="text-center">
								<!-- @if($comment->approved==0 && $comment->declined==0)
								<button class="btn btn-success btn-sm" type="button">
									{{trans('Admin'.DS.'comment.approve')}}
								</button>
								<button class="btn btn-danger btn-sm" type="button">
									{{trans('Admin'.DS.'comment.decline')}}
								</button>
								@endif -->
								@if(Auth::guard('web')->user()->can('delete_Comment'))
								<a href="/admin/comment/delete/{{$comment->id}}" class="btn btn-danger btn-sm" type="button">
									{{trans('global.delete')}}
								</a>
								@endif
							</td>
						</tr>
						@endif
					@endforeach
				@else
					<tr role="row" class="odd">
						<td colspan="6">{{trans('global.no_data')}}</td>
					</tr>
				@endif
				</tbody>
			</table>
			<div class="col-sm-12" style="text-align: right">
				{!! $list_comments->appends(['created_by' => isset($created_by) ? $created_by : '','keyword' => isset($keyword) ? $keyword : ''])->render() !!}
			</div>
		</div>
</div>
<style>
	.label-cm{
		font-size: 13px !important;
    margin-right: 10px;
    display: inline-block !important;
    text-overflow: ellipsis;
    margin-bottom: 5px;
	}
	blockquote{
		margin-top: 5px !important;
		font-size: 13px !important;
	}
	.thumb-cm{
		margin-top: 10px;
	}
	.thumb-cm img{
		width: 100%;
	}
</style>
@endsection

@section('JS')
<script>
	var json_sort = [];
	$(function(){
		$('#select_client').selectpicker({
      iconBase: 'fa',
    }).trigger("change");
	})
</script>
@endsection
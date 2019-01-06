<div class="content-edit-profile-manager">
	<div class="row mb-3">
		<div class="col-md-4 col-xs-12">
			<h3>
				{{mb_strtoupper(trans('Location'.DS.'makemoney.count_daily'))}} (<t id="save_like_content_total">{{$count_daily}}</t>)
			</h3>
		</div>
		<div class="col-md-4 col-xs-12">
			<form action="" method="get" accept-charset="utf-8">
				<div class="form-group">
					<div class="input-group">
						<input class="form-control" type="text" name="keyword" value="{{$keyword?$keyword:''}}" placeholder="{{trans('global.keyword')}}">
						<button class="btn btn-primary ml-2" type="submit">
							<i class="fa fa-search"></i>
						</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-sm-12">
				@if(session('status'))
					<div class="alert alert-success alert-dismissible" style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
						</button>
						{!! session('status') !!}
					</div>
				@endif
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 mb-3">
			@if(count($daily))
			<div class="table-responsive">
				<table class="table table-striped">
					<tr>
						<th>&nbsp;</th>
						<th>{{trans('global.full_name')}}</th>
<!-- 						<th>{{trans('global.email')}}</th>
						<th class="text-center">{{trans('global.phone')}}</th> -->
						<th class="text-center">{{trans('global.avatar')}}</th>
						<th width="285">&nbsp;</th>
					</tr>
					@foreach($daily as $key => $cv)
					<tr>
						<td class="align-middle">{{$key+1}}</td>
						<td class="align-middle">{{$cv->full_name}}</td>
<!-- 						<td class="align-middle">{{$cv->email}}</td>
						<td class="text-center align-middle">{!! $cv->phone?$cv->phone:'&nbsp;&nbsp;&nbsp;' !!}</td> -->
						<td class="text-center align-middle"><img src="{{$cv->avatar}}" alt="" height="50"></td>
						<td class="align-middle text-right">
							<a class="btn btn-primary" href="{{route('info_daily',['id'=>$cv->id])}}">
								{{trans('Location'.DS.'makemoney.ctv_info')}}
							</a>
						</td>
					</tr>
					@endforeach
				</table>
			</div>
			@else
			<h5 class="text_no">{{trans('Location'.DS.'makemoney.no_daily')}}</h5>
			@endif
		</div>
		@if($daily)
		<div class="col-sm-12">
			{!! $daily->appends(request()->query())->links('vendor.pagination.bootstrap-4') !!}
		</div>
		@endif
	</div>
</div>
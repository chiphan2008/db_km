<div class="content-edit-profile-manager">
	<div class="row mb-3">
		<div class="col-md-4 col-xs-12">
			<h3>
				{{mb_strtoupper(trans('Location'.DS.'makemoney.count_location_pending'))}} (<t id="save_like_content_total">{{$count_location_pending}}</t>)
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
	<div class="list-content-profile">
		<ul class="row list-unstyled">
			@if(count($contents))
			@foreach($contents as $content)
				<li class="col-lg-4 col-6" id="item_{{$content->id}}">
					<div class="card-vertical card">
						<div class="card-img-top">
							@if($content->moderation == 'publish')
							<a href="{{LOCATION_URL}}/{{$content->alias}}" title="{{$content->name}}">
							@else
							<a href="{{LOCATION_URL}}/{{$content->alias}}" title="{{$content->name}}">
							@endif
								<img class="img-fluid" src="{{str_replace('img_content','img_content_thumbnail',$content->avatar)}}" alt="{{$content->name}}" style="max-height: 202px;">
							</a>
						</div>
						<div class="card-block py-2 px-0">
							<div class="card-description">
								<h6 class="card-title ">
									@if($content->moderation == 'publish')
									<a href="{{LOCATION_URL}}/{{$content->alias}}" title="{{$content->name}}">{{$content->name}}</a>
									@else
									<a href="{{LOCATION_URL}}/{{$content->alias}}" title="{{$content->name}}">{{$content->name}}</a>
									@endif
								</h6>
								<p class="card-address text-truncate">{{$content->address}}, {{$content->_district?$content->_district->name:''}}, {{$content->_city?$content->_city->name:''}}, {{$content->_country?$content->_country->name:''}}</p>
							</div>
							<div class="meta-post d-flex align-items-center">
								<div class="meta-post d-flex align-items-center">
									<div class="rating d-flex align-items-center">
										<i class="icon-star-yellow"></i>
										<span>({{$content->vote?$content->vote:0}})</span>
									</div>
									<!-- end rating -->
									@if($content->moderation == 'publish')
										<div class="meta-post-status meta-status-open">
											<i class="icon-circle"></i>
											{{trans('Location'.DS.'user.opening')}}
										</div>
									@elseif($content->moderation == 'un_publish')
										<div class="meta-post-status meta-status-close">
											<i class="icon-circle"></i>
											{{trans('Location'.DS.'user.closing')}}
										</div>
									@else
										<div class="meta-post-status meta-status-inspection">
											<i class="icon-circle"></i>
											{{trans('Location'.DS.'user.pending')}}
										</div>
									@endif
								</div>
								<!-- end rating -->
							</div>
							@if($content->moderation == 'request_publish')
							<div class="d-flex align-items-center">
								<a href="{{route('daily_accept_location',['id'=>$content->id])}}" class="btn btn-primary">{{trans('global.approve')}}</a>
							</div>
							@else
							<div class="d-flex align-items-center">
								<a href="#" class="btn btn-secondary">{{trans('Location'.DS.'makemoney.ctv_info')}}</a>
							</div>
							@endif
						</div>
					</div>
					<!-- end card post -->
				</li>
			@endforeach
			@else
			<li class="col-12"><h5 class="text_no">{{trans('Location'.DS.'makemoney.no_location_pending')}}</h5></li>
			@endif
		</ul>
		@if($contents)
		<div class="col-sm-12">
			{!! $contents->appends(request()->query())->links('vendor.pagination.bootstrap-4') !!}
		</div>
		@endif
	</div>
</div>

@section('JS')
	<script>
	</script>
@endsection
<div class="content-edit-profile-manager">
	<h3>{{mb_strtoupper(trans('Location'.DS.'user.like_location'))}} ({{$total}})</h3>
	<div class="list-content-profile">
		<ul class="row list-unstyled">
			@if($contents)
			@foreach($contents as $content)
				<li class="col-lg-4 col-6">
					<div class="card-vertical card">
						<div class="card-img-top">
							<a href="{{url('/')}}/{{$content->alias}}" alt="{{$content->name}}">
								<img class="img-fluid" src="{{str_replace('img_content','img_content_thumbnail',$content->avatar)}}" alt="{{$content->name}}">
							</a>
						</div>
						<div class="card-block py-2 px-0">
							<div class="card-description">
								<h6 class="card-title "><a href="{{url('/')}}/{{$content->alias}}" title="{{$content->name}}">{{$content->name}}</a></h6>
								<p class="card-address text-truncate">{{$content->address}}, {{$content->_district->name}}, {{$content->_city->name}}, {{$content->_country->name}}</p>
							</div>
							<div class="meta-post d-flex align-items-center">
								<div class="add-like d-flex align-items-center">
									<i class="icon-heart-empty"></i>
									<span>({{$content->like?$content->like:0}})</span>
								</div>
								<div class="rating d-flex align-items-center">
								  <div class="star-rating hidden-xs-down">
									<span style="width:{{$content->vote?($content->vote*100)/5:0}}%"></span>
								  </div>
								  <i class="icon-star-yellow hidden-sm-up"></i>
								  <span>({{$content->vote?$content->vote:0}})</span>
								</div>
								<!-- end rating -->
							</div>
						</div>
					</div>
					<!-- end card post -->
				</li>
			@endforeach
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
	@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
		@include('Location.user.crop_image')
	@endif
@endsection
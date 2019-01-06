@if(count($contents))
	@foreach($contents as $content)

	<div class="col-lg-3 col-md-4 col-6 fadeIn animated">
<!-- 		<p>{!! $content->end_push !!}</p>
		<p>{!! strtotime($content->end_push) !!}</p>
		<p>{!! strtotime($content->end_push)>time()?'ADS':'NO-ADS' !!}</p> -->
		<div class="card-vertical card">
				<div class="card-img-top">
						<a href="{{url('/')}}/{{$content->alias}}" title="{{$content->name}}">
									<img class="img-fluid" src="{{str_replace('img_content','img_content_thumbnail',$content->avatar)}}" alt="{{$content->name}}">
							</a>
				</div>
				<div class="card-block py-2 px-0">
						<div class="card-description">
									<h6 class="card-title "><a href="{{url('/')}}/{{$content->alias}}" title="{{$content->name}}">
									{!! strtotime($content->end_push)>time()?'<span class="text-danger">[AD]</span>':'' !!} {{$content->name}}
									</a></h6>
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
	</div>
	@endforeach
@else
	<div class="col-sm-12 text-center" style="height:24.7vh;">
		<h3>{{trans('global.no_content')}}</h3>
	</div>
@endif
@if($contents)
@foreach($contents as $content)
<li id="room-{{$content->id}}" class="post-horizontal post post d-flex align-items-center justify-content-between fadeIn animated">
	<div class="post-img">
			<a href="{{url('/')}}/{{$content->alias}}" title="{{$content->name}}">
					<img class="d-flex mr-3" src="{{str_replace('img_content','img_content_thumbnail',$content->avatar)}}" alt="{{$content->name}}">
			</a>
	</div>
	<div class="post-body">
			<h5 class="mt-0 mb-1 text-truncate"><a href="{{url('/')}}/{{$content->alias}}" title="{{$content->name}}">
			{!! strtotime($content->end_push)>time()?'<span class="text-danger">[AD]</span>':'' !!} {{$content->name}}
			</a></h5>
			<p class="address text-truncate" title="{{$content->address}}, {{$content->_district->name}}, {{$content->_city->name}}, {{$content->_country->name}}">{{$content->address}}, {{$content->_district->name}}, {{$content->_city->name}}, {{$content->_country->name}}</p>
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
        	@if($content->line > 1000)
					<span  class="distance ml-auto">{{round($content->line/1000,2)}} km</span>
					@else
					<span  class="distance ml-auto">{{round($content->line,0)}} m</span>
					@endif
	    </div>
	</div>
</li>
@endforeach
@endif
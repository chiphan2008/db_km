<div class="talk">
	<div class="talk-header d-sm-flex justify-content-sm-between mb-3">
		 <div class="talk-header-left d-flex align-items-center">
			 <div class="avata mr-2">
				 <img src="{{$reply->_comment_by->avatar}}" alt="">
			 </div>
			 <div class="title">
				 <h6 class="mb-0"><a href="/user/{{$reply->_comment_by->id}}">{{$reply->_comment_by->full_name}}</a></h6>
				 <p>{{date('H:i',strtotime($reply->created_at))}} | {{date('d/m/Y',strtotime($reply->created_at))}}</p>
			 </div>
		 </div> 
		 <!-- end talk header left -->
		<div class="talk-header-right d-flex align-items-center d-flex align-items-center hidden-sm-down">
			 <div onclick="likeComment(this)" class="talk-total total-like mx-2 {{count($reply->_has_liked)>0?'active':''}}" data-id-comment="{{$reply->id}}">
				 <i class="icon-heart-empty"></i>
				 <span>{{$reply->like_comment}}</span>
			 </div>
			 <!-- <div class="talk-total total-commit mx-2">
				 <i class="icon-commenting-o"></i>
			 </div> -->
		 </div>
		 <!-- end talk header right -->
	</div>
	<!-- end talk header -->
	<div class="talk-content">
		<p>
			{{$reply->content}}
		</p>
	</div>
	<!-- end  talk content -->
	@if($reply->_images)
	<ul class="talk-images list-unstyled">
		@foreach($reply->_images as $image)
		<li class="{{count($reply->_images)>5?'':'less5'}}">
			<a data-fancybox="images_comment-{{$reply->id}}"  rel="comment-{{$reply->id}}"  data-caption="" href="{{$image->link}}" title="">
				<img src="{{$image->thumb}}" alt="" width="{{count($reply->_images)>5?'80':130}}">
			</a>
		</li>
		@endforeach
	</ul>
	<!-- end talk images -->
	@endif
	<div class="talk-header-right d-flex align-items-center hidden-md-up">
			<div onclick="likeComment(this)" class="talk-total total-like mx-2 {{count($reply->_has_liked)>0?'active':''}}" data-id-comment="{{$reply->id}}">
					<i class="icon-heart-empty"></i>
					<span>{{$reply->like_comment}}</span> Like
			</div>
	</div>
</div>
<div class="talk" id="comment{{$comment->id}}">
	<div class="talk-header d-sm-flex justify-content-sm-between mb-3">
		 <div class="talk-header-left d-flex align-items-center">
			 <div class="avata mr-2">
				 <img src="{{$comment->_comment_by->avatar}}" alt="">
			 </div>
			 <div class="title">
				 <h6 class="mb-0"><a href="/user/{{$comment->_comment_by->id}}">{{$comment->_comment_by->full_name}}</a></h6>
				 <p>{{date('H:i',strtotime($comment->created_at))}} | {{date('d/m/Y',strtotime($comment->created_at))}}</p>
			 </div>
		 </div> 
		 <!-- end talk header left -->
		 <div class="talk-header-right d-flex align-items-center d-flex align-items-center hidden-sm-down">
			 <div onclick="likeComment(this)" class="talk-total total-like mx-2 {{count($comment->_has_liked)?'active':''}}" data-id-comment="{{$comment->id}}">
				 <i class="icon-heart-empty"></i>
				 <span>{{$comment->like_comment}}</span>
			 </div>
			 <div onclick="replyComment(this)" class="talk-total total-commit mx-2">
				 <i class="icon-commenting-o"></i>
			 </div>
		 </div>
		 <!-- end talk header right -->
	</div>
	<!-- end talk header -->
	<div class="talk-content">
		<p>
			{{$comment->content}}
		</p>
	</div>
	<!-- end  talk content -->
	@if($comment->_images)
	<ul class="talk-images list-unstyled">
		@foreach($comment->_images as $image)
		<li class="{{count($comment->_images)>5?'':'less5'}}">
			<a data-fancybox="images_comment-{{$comment->id}}" rel="comment-{{$comment->id}}" data-caption="" href="{{$image->link}}" title="">
				<img src="{{$image->thumb}}" alt="" width="">
			</a>
		</li>
		@endforeach
	</ul>
	<!-- end talk images -->
	@endif
	<div class="talk-header-right d-flex align-items-center hidden-md-up">
			<div onclick="likeComment(this)" class="talk-total total-like mx-2 {{count($comment->_has_liked)?'active':''}}" data-id-comment="{{$comment->id}}">
					<i class="icon-heart-empty"></i>
					<span>{{$comment->like_comment}}</span> Like
			</div>
			<div onclick="replyComment(this)" class="talk-total total-commit mx-2 active">
					<i class="icon-commenting-o"></i>
					{{trans('Location'.DS.'content.reply')}}
			</div>
	</div>
	<div class="talk-reply">
			<div class="align-items-sm-center">
					<div class="avata mr-2 ">
							&nbsp;
					</div>
					<form class="form-commit w-100 clearfix" data-type="reply" data-id-comment="{{$comment->id}}">
							<div class="emoji-picker-container">
									<input type="hidden" name="content_id" value="{{$content->id}}">
									<input type="hidden" name="comment_id" value="{{$comment->id}}">
									<input type="text form-control"  name="content"  max-length="10000"  placeholder="{{trans('Location'.DS.'content.comment')}}" data-emojiable="true">
									<div class="button-commit d-flex align-items-center">
											<a class="upload-image-cmt" href="" class="hidden-sm-down">
																	<i class="icon-picture"></i>
															</a>
											<button type="button" onclick="commentFunction(this)" class="btn btn-primary">
												<span class="hidden-sm-down">{{trans('global.send')}}</span> 
												<i class="icon-direction hidden-md-up"></i>
											</button>
									</div>
							</div>
							<div class="box-image-commit">
							</div>
							<!-- end box image commit -->
							<input type="file" style="display: none" name="file[]" multiple="" accept="image/*">
					</form>
					<div class="text-info comment-error clearfix"></div>
					<!-- end form-commit -->
			</div>
	</div>
	<!-- end talk reply -->
	@if($comment->_replies)
	<div class="talk-sub">
		@foreach($comment->_replies as $reply)
			@include('Location.content.comment_item_sub')
		@endforeach
	</div>
	@if($comment->_all_replies->count()>5)
	<div class="talk-loadmore text-center mt-3">
		<button data-page="2" data-total="{{$comment->_all_replies->count()}}" data-current="5" data-id-comment="{{$comment->id}}" data-id-content="{{$content->id}}" onclick="loadMoreComment(this)" class="btn btn-primary" href="" title="">{{trans('global.view_more')}}</button>
	</div>
	@endif
	@endif
	<!-- end sub -->
</div>
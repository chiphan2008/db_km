<section class="my-4 my-md-5">
		<div class="container">
				<div class="row">
						<div class="col-md-8">
								<div class="title-gallery">
										<h4 class="text-uppercase mb-3">{{trans('Location'.DS.'content.comment')}}</h4>
								</div>
								<div class="location-commit">
										<form class="form-commit w-100 clearfix" data-type="comment" id="main_comment">
												<div class="emoji-picker-container">
														<input type="hidden" name="content_id" value="{{$content->id}}">
														<input type="hidden" name="comment_id" value="0">
														<input id="comment_main" max-length="10000" name="content" type="text form-control" placeholder="{{trans('Location'.DS.'content.comment')}}" data-emojiable="{{Auth::guard('web_client')->user()?'true':'false'}}">
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
												<div class="box-image-commit mt-2">             
												</div>
												<!-- end box image commit -->
												<input type="file" style="display: none" name="file[]" multiple="" accept="image/*">
										</form>
										<div class="text-info comment-error"></div>
										<!-- end form-commit -->
										@if($content->_comments)
										<div class="talk-list">
												@foreach($content->_comments as $comment)
													@include('Location.content.comment_item')
												@endforeach
										</div>

										@if($content->_all_comments->count()>5)
										<div class="talk-loadmore text-center mt-3">
											<button data-page="2" data-total="{{$content->_all_comments->count()}}" data-current="5" data-id-comment="0" data-id-content="{{$content->id}}" onclick="loadMoreComment(this)" class="btn btn-primary">{{trans('global.view_more')}}</button>
										</div>
										@endif
										@endif
										<!-- enn talk list -->
								</div>
						</div>
				</div>
		</div>
</section>
<style>
	@media (min-width: 576px){
		.talk-images li.less5 {
		  width: 19% !important;
		}
	}
</style>

<link href="/frontend/assets/vendor/emoji-picker-master/css/emoji.css" rel="stylesheet">
@section('JSComment')
<!-- Section JSComment -->
<script src="/frontend/assets/vendor/emoji-picker-master/js/config.js"></script>
<script src="/frontend/assets/vendor/emoji-picker-master/js/util.js"></script>
<script src="/frontend/assets/vendor/emoji-picker-master/js/jquery.emojiarea.js"></script>
<script src="/frontend/assets/vendor/emoji-picker-master/js/emoji-picker.js"></script>
<script>
	var dataImage = {};
	$(function(){
		$("#main_comment").on("click",function(e){
			@if(Auth::guard('web_client')->user())
			var a = 1;
			@else
			e.preventDefault();
			$('#modal-signin').modal('show');
			@endif
		});
		
		//
		// Initializes and creates emoji set from sprite sheet
		window.emojiPicker = new EmojiPicker({
			emojiable_selector: '[data-emojiable=true]',
			assetsPath: '/frontend/assets/vendor/emoji-picker-master/img/',
			popupButtonClasses: 'icon-smile'
		});

		setUpComment();

		if(location.hash){
			$('html, body').animate({
				scrollTop: $(location.hash).offset().top - 51
			}, 750);
		}
		//remove img talk image
    $('.box-image-commit ').on('click', '.remove-img', function(event) {
        event.preventDefault();
        var comment_id = $(this).attr("data-comment-id");
	      var filename = $(this).attr('data-filename')?$(this).attr('data-filename'):'';
	      for(var i=0; i<dataImage[comment_id].length; i++){
          if(filename == dataImage[comment_id][i].name){
            dataImage[comment_id].splice(i,1);
          }
        }
        $(this).closest('.upload-img-commit').remove();
    });
	});

	function commentFunction(obj){
		@if(Auth::guard('web_client')->user())
		var form = $(obj).closest('form').get(0);
		// console.log(form);
		var formData = new FormData(form);
		formData.append('_token',$('meta[name="_token"]').attr('content'))
		if(dataImage[$(form).find('[name=comment_id]').val()]){
			var img_list = dataImage[$(form).find('[name=comment_id]').val()];
			for (var i = 0; i < img_list.length; i++) {
        formData.append('image[' + i + ']', img_list[i]);
      }
		}
		$.ajax({
			type: "POST",
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			url: '/comment/createCommentContent',
			beforeSend:function(){
				$("#loading").show();
			},
			success: function (res) {
				if(res.error==0){
					$(form).next().removeClass('text-danger').addClass('text-info').text(res.message).show().delay(10000).fadeOut(100);
					form.reset();
					$(form).find(".emoji-wysiwyg-editor").html('');
					$(form).find(".box-image-commit").html('');
					dataImage[$(form).find('[name=comment_id]').val()] = [];
				}else{
					$(form).next().removeClass('text-info').addClass('text-danger').text(res.message).show().delay(10000).fadeOut(100);
				}
				$("#loading").hide();
			}
		})
		@else
		$('#modal-signin').modal('show');
		@endif
	}

	function loadMoreComment(obj){
		var page = parseInt($(obj).attr('data-page'));
		var total = parseInt($(obj).attr('data-total'));
		var take = total-((page-1)*5);
		if(take>5)
			take = 5;
		var comment_id = $(obj).attr('data-id-comment');
		var content_id = $(obj).attr('data-id-content');
		var talk_list = $(obj).parent().prev();
		var button = $(obj);
		$.ajax({
			type: "POST",
			cache: false,
			data: {
				'_token'	 : $('meta[name="_token"]').attr('content'),
				page 			 : page,
				take 			 : take,
				comment_id : comment_id,
				content_id : content_id
			},
			url: '/comment/loadComment',
			beforeSend:function(){
				$("#loading").show();
			},
			success: function (res) {
				talk_list.append(res.html);
				setUpComment();
				if((page*5)>=total){
					button.remove();
				}else{
					button.attr('data-page',res.nextPage);
				}
				$("#loading").hide();
			}
		})
	}

	function likeComment(obj){
		var comment_id = $(obj).attr('data-id-comment');
		var btn_like = $(obj);
		@if(Auth::guard('web_client')->user())
		$.ajax({
			url : '/comment/likeComment',
			type: 'POST',
			data:{
				comment_id : comment_id,
				'_token'	 : $('meta[name="_token"]').attr('content')
			},
			success:function(res){
				if(res.info=='unlike'){
					btn_like.removeClass('active');
				}else{
					btn_like.addClass('active');
				}
				btn_like.find('span').text(res.data.like);
			}
		})
		@else
		$('#modal-signin').modal('show');
		@endif
	}

	function replyComment(obj){
		@if(Auth::guard('web_client')->user())
		event.preventDefault();
		$(obj).addClass('active');
		$(obj).closest('.talk').find('.talk-reply').slideToggle('fast');
		$(obj).closest('.talk-sub').next('.talk-reply').slideToggle('fast');
		@else
		$('#modal-signin').modal('show');
		@endif
	}

	function setUpComment(){
		var UploadImgCmt = 0;
		var ulImgCmt = $('.form-commit');
		
		window.emojiPicker.discover();
		// upload

		$('.form-commit').on('click', '.upload-image-cmt', function(event) {
				event.preventDefault();
				//console.log('click');
				$(this).closest('.form-commit').find('input[type="file"]').trigger('click');
		});
		
		
		$('.form-commit').fileupload({
				autoUpload: true,
				sequentialUploads: true,
				add: function(e, data) {
					var uploadFileAvataEdit = data.files[0];
					if(uploadFileAvataEdit.type.split('/')[0]=='image'){
						if($(this).attr("data-type") === 'comment'){
							if(!dataImage[0]){
								dataImage[0] = [];
							}
							dataImage[0].push(uploadFileAvataEdit);
							var comment_id = 0;
						}else{
							var comment_id = $(this).attr('data-id-comment');
							if(!dataImage[comment_id]){
								dataImage[comment_id] = [];
							}
							dataImage[comment_id].push(uploadFileAvataEdit);
						}
						UploadImgCmt++;
						var tplavataedit = $('<div class="upload-img-commit"><a data-comment-id="'+comment_id+'" class="remove-img" href="" title="" data-filename="'+data.files[0].name+'"><i class="icon-cancel"></i></a><img /></div>');
                 $(this).find('.box-image-commit').append(tplavataedit);
						var currentLi = ulImgCmt.find('.upload-img-commit').last();
						// currentLi.before('<div class="upload-img-commit"></div>');
						data.context = currentLi;
						var reader = new FileReader();
						reader.onloadend = function(e) {
								var tempImg = new Image();
								tempImg.src = reader.result;
								tempImg.onload = function() {
										var max_size = 150; // TODO : pull max size from a site config
										var width = this.width;
										var height = this.height;
										if (width > height) {
												if (width > max_size) {
														height *= max_size / width;
														width = max_size;
												}
										} else {
												if (height > max_size) {
														width *= max_size / height;
														height = max_size;
												}
										}
										var canvas = document.createElement('canvas');
										canvas.width = width;
										canvas.height = height;
										var ctx = canvas.getContext("2d");
										ctx.drawImage(this, 0, 0, width, height);
										var dataURL = canvas.toDataURL("image/jpeg");
										data.context.find('img').attr('src', dataURL);
								};
						};
						reader.readAsDataURL(uploadFileAvataEdit);
						// var jqXHR = data.submit();
						//console.log(dataImage);
					}
				},
				fail: function(e, data) {
					//console.log(data);
					data.context.addClass('error');
				}
		});
	}
</script>
@endsection
<style type="text/css" media="screen">
	.cropit-preview-image-container:after{
		content:"{{trans('global.choose_image')}}"
	}
	.slider-wrapper{
		margin-top: 1.2em;
	}
	.cropit-preview {
	  /* You can specify preview size in CSS */
	  width: 300px !important;
	  height: 300px !important;
	}
	.modal-upload-avata .cropit-image-zoom-input{
		height: 2px !important;
	}
</style>
<script src="/frontend/assets/js/jquery.cropit.js"></script>
<script>
	function getBase64Image(img) {
	  var canvas = document.createElement("canvas");
	  canvas.width = img.width;
	  canvas.height = img.height;
	  var ctx = canvas.getContext("2d");
	  ctx.drawImage(img, 0, 0,img.width, img.height);
	  var dataURL = canvas.toDataURL("image/png");
	  return dataURL;
	}
	$(function() {

		// upload avata
		$('.modal-upload-avata').on('click', '.btn-upload-avata', function(event) {
				event.preventDefault();
				$('.cropit-image-input').click();
		});

		//crop avata
		var base64_avatar = getBase64Image(document.getElementById("avatar_source"));
		$('.image-editor').cropit({
				imageState: {
						// src: 'http://placekitten.com/g/1280/800'
						src: base64_avatar
				}
		});
		$(".cropit-preview-image").attr('src',base64_avatar);

		$('.rotate-cw').click(function() {
				$('.image-editor').cropit('rotateCW');
		});
		$('.rotate-ccw').click(function() {
				$('.image-editor').cropit('rotateCCW');
		});
		//change image
		$('.manager-profile').on('click', '.export', function(event) {
				event.preventDefault();
				$('.modal-upload-avata .close').click();
				var imageData = $('.image-editor').cropit('export');
				$.ajax({
					url: '/user/{{$user->id}}/update-avatar',
					type: 'POST',
					data: {
						img : imageData,
						_token: $("[name='_token']").prop('content')
					},
					success: function(response){
						if(response.error){
							toastr.error(response.message,"");
						}else{
							toastr.success(response.message,"");
							$('.form-edit-profile input').val('');
							window.location.reload();
						}
					}
				})
				$(this).closest('.manager-profile').find('.form-upload-avata .upload-img-avata img').attr('src', imageData);
		});
		// close
		$('.btn-close').click(function(event) {
				event.preventDefault();
				$('.modal-upload-avata .close').click();
		});
		
});
</script>`
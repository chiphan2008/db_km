<style>
	@media (min-width: 767px){
		.sub-nav-manager-profile{
			display: none !important;
			opacity: 0 !important;
		}
	}
</style>
<script src="/frontend/assets/js/jquery.cropit.js"></script>
<script>
	$(function() {
		// upload avata
		$('.modal-upload-avata').on('click', '.btn-upload-avata', function(event) {
				event.preventDefault();
				$('.cropit-image-input').click();
		});
		//crop avata
		$('.image-editor').cropit({
				// imageState: {
				// 		src: 'https://lorempixel.com/500/400/',
				// },
		});

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
							toastr.error(response.message,"Error");
						}else{
							toastr.success(response.message,"Message");
							$('.form-edit-profile input').val('');
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
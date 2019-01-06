<div id="modal_app" class="modal  modal-submit-payment  modal-vertical-middle  modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-header">
				<div class="modal-logo pt-4 text-center">
					<img src="/frontend/assets/img/logo/logo-large.svg" alt="">
				</div>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12">
						<h4>{{trans('global.download_app_popup')}}</h4>
					</div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-center">
				<a class="btn btn-primary" href="https://play.google.com/store/apps/details?id=com.kingmap_app" id="link_download_app">{{trans('global.download_app')}}</a>
				<a class="btn btn-secondary" href="#" data-dismiss="modal" aria-label="Close">{{trans('global.close')}}</a>
			</div>
		</div>
	</div>
</div>

<script>
	var open_popup_app = true;
	$(function(){
		if(window.sessionStorage.openPopup){
			open_popup_app = false;
		}else{
			open_popup_app = true;
		}

		var device = 'desktop';
		var os_device = '{{\Browser::platformName()}}';
		var os = 'window';
		@if(\Browser::isMobile())
			device = 'mobile';
		@elseif(\Browser::isTablet())
			device = 'tablet';
		@else
			device = 'desktop';
		@endif

		if(os_device.indexOf('iOS') > -1){
			os = 'ios';
		}
		if(os_device.indexOf('Android') > -1){
			os = 'android';
		}

		if(os=='android'){
			openAndroidApp();
			setTimeout(goToCHPlay(os,device), 250);
		}
		
		
	})

	function goToCHPlay(os,device){

		if(os==='android'){
			$("#modal_app #link_download_app").prop('href','http://play.google.com/store/apps/details?id=com.kingmap_app');
			if(device==='mobile' || device==='tablet'){
				if(open_popup_app){
					window.sessionStorage.openPopup = true;
					$("#modal_app").modal();
				}
			}
		}
	}

	var openAndroidApp = function() {
		var iframe = document.createElement("iframe");
		iframe.style.border = "none";
		iframe.style.width = "1px";
		iframe.style.height = "1px";
		iframe.src = 'kingmap://home';
		document.body.appendChild(iframe);
	};

</script>
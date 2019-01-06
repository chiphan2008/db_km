<div class="modal fade modal-procces  modal-report" id="modal-manager-location" tabindex="-1" role="dialog" aria-labelledby="modal-manager-location" data-backdrop="static">
	<!-- <a href="#" class="back-step" data-dismiss="modal">
	<i class="icon-left"></i>  {{trans('Location'.DS.'layout.back')}}</a> -->
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	<div class="modal-dialog km-modal-lg" role="document">
		<div class="modal-header">
			<div class="process-create-header">
				<div class="logo"><img src="/frontend/assets/img/logo/logo-large.svg" alt=""></div>
				<h4>{{trans('Location'.DS.'layout.update_info')}}</h4>
			</div>
		</div>
		<div class="modal-content">
			<input type="hidden" id="content_id_create">
			<div id="content-manager-location">
			
			</div>
		</div>
		<div class="modal-footer justify-content-center">
			<button type="button" class="btn btn-secondary btn-info" data-dismiss="modal" aria-label="{{trans('global.close')}}">
				{{trans('global.close')}}
			</button>
		</div>
	</div>
</div>

<div class="modal fade modal-submit-payment modal-report modal-add-location-same-sytem" id="modal-add-location-same-sytem" tabindex="-1" role="dialog" aria-labelledby="modal-add-location-same-sytem" aria-hidden="true" data-backdrop="static">
		<div class="modal-dialog pb-4">
			<!-- <div class="modal-header">
				<div class="process-create-header">
					<div class="logo"><img src="/frontend/assets/img/logo/logo-large.svg" alt=""></div>
					<h4>Thông tin chung</h4>
				</div>
			</div> -->
			<div class="modal-content text-center">
					<div id="content-add-same-location">
						 
					</div>
					<div class="modal-button d-flex justify-content-center">
							<a class="btn btn-secorady mx-3" onclick="$('#modal-add-location-same-sytem').one('hidden.bs.modal', function() {$('body').addClass('modal-open');}).modal('hide');$('#modal-manager-location').modal('show');">{{trans('Location'.DS.'layout.cancel')}}</a>
							<button type='button' class="btn btn-primary mx-3" onclick="addBranch();">{{trans('Location'.DS.'layout.done')}}</button>
					</div>
			</div>
		</div>
</div>
<style type="text/css" media="screen">
	.remove-manager{
		position: absolute;
    z-index: 9999;
    right: 0;
    color: white;
    background:#d0021bdd;
    border-radius:50%;
    padding:4px;
    width:22px;
    height:22px;
    line-height:12px;
    text-align:center;
	}
</style>	
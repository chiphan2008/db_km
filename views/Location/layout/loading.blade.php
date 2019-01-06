<div id="loading" style="display:none;">
	<div class="horizontal div1">
		<div class="vertical">
			<img src="/img_default/loading.gif" alt="" width="80">
		</div>
	</div>
</div>


<div id="progress" style="display:none;">
	<div class="horizontal div1">
		<div class="vertical">
			<div class="progress">
			  <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:0%">0%</div>
			</div>
		</div>
	</div>
</div>


<div id="modal-error" class="modal  modal-submit-payment  modal-vertical-middle  modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-logo pt-4 text-center">
				<img src="/frontend/assets/img/logo/logo-large.svg" alt="">
			</div>
			<h4>{{trans('global.error')}}</h4>
			<div class="row">
	      <div class="col-12 error_content">
	      	
	      </div>
			</div>
      <p></p>
      <div class="modal-button d-flex justify-content-center d-flex">
          <div class="col-6">
              <a class="btn btn-outline-primary" href="#" data-dismiss="modal">{{trans('global.close')}}</a>
          </div>
			</div>
		</div>
	</div>
</div>
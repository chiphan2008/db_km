<div class="modal  modal-procces cread-location" id="modal-new-location" tabindex="-1" role="dialog" aria-labelledby="modal-food" data-backdrop="static">
	<a class="back-step" href="" onclick="return false;">
		<i class="icon-left"></i>  {{trans('Location'.DS.'layout.back')}}</a>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
	<div class="modal-dialog km-modal-lg" role="document">
		<div class="modal-header">
			<div class="process-create-header">
				<div class="logo text-center"><img src="/frontend/assets/img/logo/logo-large.svg" alt=""></div>
				<ul class="nav nav-tabs bs-wizard d-flex justify-content-center" role="tablist" style="border-bottom:0;">
					<li class="nav-item col-4 bs-wizard-step highlight">
						<div class="progress">
							<div class="progress-bar"></div>
						</div>
						<a class="nav-link bs-wizard-dot" data-toggle="tab" role="tab"></a>
						<div class="bs-wizard-info text-center">{{trans('Location'.DS.'layout.information')}}</div>
					</li>
					<li class="nav-item col-4 bs-wizard-step disabled">
						<div class="progress">
							<div class="progress-bar"></div>
						</div>
						<a class="nav-link bs-wizard-dot" data-toggle="tab" role="tab"></a>
						<div class="bs-wizard-info text-center">{{trans('Location'.DS.'layout.image')}}</div>
					</li>
				</ul>
			</div>
		</div>
		<div class="modal-content">
			<!-- modal-header -->
			<div class="process-create-content">
				<form id="form-creat-location" action="{{url('createLocationFrontend/postCreateLocation')}}" class="form-creat-location"  enctype="multipart/form-data" method="POST">
					<div class="tab-content">
						
						<div class="tab-pane active" id="step1" role="tabpanel">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="col-md-12"><h4>{{trans('Location'.DS.'layout.information')}}</h4></div>
							<fieldset class="form-group col-md-12">
								<label class="mb-3">{{trans('Location'.DS.'layout.category')}} <span style="color: #d9534f">*</span></label>
								<div class="bg-gray-light px-4 pt-4 pb-0">
									<ul class="list-checkbox-location row list-unstyled ">
										@foreach($category as $value)
											<li class="form-group  col-md-4">
												<label class="custom-control custom-radio">
													<input type="radio" name="id_category" value="{{$value->id}}" class="custom-control-input">
													<span class="custom-control-indicator"></span>
													<span class="custom-control-description">@lang($value->name)</span>
												</label>
											</li>
										@endforeach
									</ul>
									<div id="err_id_category" style="color: #d9534f;display: none">{{trans('Location'.DS.'layout.validate_category')}}</div>
									<div class="box-location-add-offer col-md-12" style="display: none;">
											<a class="text-right d-block mb-2" href="#" onclick="$('#suggest_category').toggle()">
													<i class="demo-icon icon-new-white mr-2"></i>
													{{trans('Location'.DS.'layout.add_category')}}
											</a>
									</div>
									<div class="form-add-offer-location w-100" id="suggest_category" style="display: none;">
											<div class=" form-inline row pb-4">
													<div class="form-group col-sm-9 mb-0">
															<input type="text" maxlength="128"  id="category_input" class="form-control w-100" autocomplete="off">
													</div>
													<div class="col-sm-3">
															<button type="button" onclick="addCategory()" class="btn-add-offer-location btn btn-primary w-100"><i class="icon-new-white"></i></button>
													</div>
											</div>
									</div>
								</div>
							</fieldset>
						</div>

						<div class="tab-pane" id="step2" role="tabpanel">
							<div class="row">
								<fieldset class="form-group col-md-9">
									{{trans('Location'.DS.'layout.category')}}: <span id="label_name_category"></span>&nbsp;&nbsp;<button class="btn btn-secondary" type="button" onclick="choose_category_again()">{{trans('Location'.DS.'layout.choose_category_again')}} <i class="fa fa-refresh"></i></button>
								</fieldset>
								@if(Auth::guard('web_client')->user())
								<fieldset class="form-group col-md-3 px-0">
									<label>{{trans('Location'.DS.'layout.id_client')}} : <b class="head">{{ Auth::guard('web_client')->user()->ma_dinh_danh}}</b></label>
								</fieldset>
								@endif
								
								<!-- <fieldset class="form-group col-md-6" id="list_group"></fieldset> -->

								<div class="col-md-12" id="fieldset_feedback_category_item">
									<label>{{trans('Location'.DS.'layout.type_category')}} <span id="category_item_require" style="color: #d9534f">*</span></label>
									<div class="form-control-feedback" id="feedback_category_item" style="color: #d9534f;display: none"></div>
									<div class="list-cate-child bg-gray-light px-4 pt-4 pb-0" id="cate-1">
										<ul class="row list-unstyled" id="list_category_item">
										</ul>
										<div class="form-add-offer-location w-100" id="suggest_category_item" style="display: none;">
												<div class=" form-inline row pb-4">
														<div class="form-group col-sm-9 mb-0">
																<input type="text" maxlength="128" id="category_item_input" class="form-control w-100" autocomplete="off">
														</div>
														<div class="col-sm-3">
																<button type="button" onclick="addCategoryItem()" class="btn-add-offer-location btn btn-primary w-100"><i class="icon-new-white"></i></button>
														</div>
												</div>
										</div>
										<div class="box-location-add-offer col-md-12" style="display: none;">
												<a class="text-right d-block mb-2" href="#"  onclick="$('#suggest_category_item').toggle()">
														<i class="demo-icon icon-new-white mr-2"></i>
														{{trans('Location'.DS.'layout.add_type_category')}}
												</a>
										</div>
									</div>
								</div>

								<div class="row w-100" id="list_service">
									<div class="col-md-12">
										<h4>{{trans('Location'.DS.'layout.service')}}</h4>
										<div class="list-utilities bg-gray-light px-4 pt-4 pb-0">
											<ul class="row list-unstyled" id="list_service_item">
											</ul>
											<div class="box-location-add-offer col-md-12" style="display: none;">
													<a class="text-right d-block mb-2" href="#" onclick="$('#suggest_service').toggle()">
															<i class="demo-icon icon-new-white mr-2"></i>
															{{trans('Location'.DS.'layout.add_service')}}
													</a>
											</div>
											<div class="form-add-offer-location w-100" id="suggest_service" style="display: none;">
													<div class=" form-inline row pb-4">
															<div class="form-group col-sm-9 mb-0">
																	 <input type="text" maxlength="128" id="service_input" class="form-control w-100" autocomplete="off">
															</div>
															<div class="col-sm-3">
																	<button type="button" onclick="addService()" class="btn-add-offer-location btn btn-primary w-100"><i class="icon-new-white"></i></button>
															</div>
													</div>
											</div>
										</div>
									</div>
								</div>

								<fieldset id="fieldset_feedback_name" class="form-group col-md-6">
									<label>{{trans('Location'.DS.'layout.name_location')}} <span style="color: #d9534f">*</span></label>
									<input onchange="searchCreateContent();" type="text" maxlength="128" name="name" id="name" data-history="0" class="form-control input-md-6" autocomplete="new-name" required>
									<div class="form-control-feedback" id="feedback_name" style="color: #d9534f;display: none"></div>
								</fieldset>
								<div class="col-md-12 mb-2">
									<ul class="list-unstyled" id="list_created">
										
									</ul>
								</div>

								<fieldset class="form-group col-md-12 pac-card" id="fieldset_feedback_address_map">
									<label>{{trans('Location'.DS.'layout.address_map')}} <span style="color: #d9534f">*</span> </label>
									<input type="text" maxlength="128" id="address_map" name="address_map"  placeholder="{{trans('Location'.DS.'layout.address_map_input')}}" class="form-control input-md-6" required>
									<div id="map_div">
										<div class="pac-container pac-logo hdpi pac-create"></div>
									</div>
									<div class="form-control-feedback" id="feedback_address_map" style="color: #d9534f; display: none"></div>
								</fieldset>
												
								<input type="hidden" value="" name="alias" id="alias">
								<input type="hidden" value="" name="lat" id="lat">
								<input type="hidden" value="" name="lng" id="lng">
								<fieldset class="form-group col-md-12">
									<div id="google_map" style="height: 300px; display: none"></div>
								</fieldset>

								<fieldset class="form-group col-md-12" id="fieldset_feedback_date_open">
									<label>{{trans('Location'.DS.'layout.date_open')}} <span style="color: #d9534f">*</span></label>
									<div class="row hidden-sm-down">
										<div class="col-md-3 col-6"><label for="">{{trans('Admin'.DS.'content.from_date')}}</label></div>
	                  <div class="col-md-3 col-6"><label for="">{{trans('Admin'.DS.'content.to_date')}}</label></div>
	                  <div class="col-md-3 col-6"><label for="">{{trans('Admin'.DS.'content.from_hour')}}</label></div>
	                  <div class="col-md-3 col-6"><label for="">{{trans('Admin'.DS.'content.to_hour')}}</label></div>
									</div>
									<div class="row">
										<div class="col-md-3 col-6 mb-2">
											<label class="hidden-md-up">{{trans('Admin'.DS.'content.from_date')}}</label>
	                    <select class="form-control" name="date_open[0][from_date]">
	                      <option value="1">{{trans('Admin'.DS.'content.monday')}}</option>
	                      <option value="2">{{trans('Admin'.DS.'content.tuesday')}}</option>
	                      <option value="3">{{trans('Admin'.DS.'content.wednesday')}}</option>
	                      <option value="4">{{trans('Admin'.DS.'content.thursday')}}</option>
	                      <option value="5">{{trans('Admin'.DS.'content.friday')}}</option>
	                      <option value="6">{{trans('Admin'.DS.'content.saturday')}}</option>
	                      <option value="0">{{trans('Admin'.DS.'content.sunday')}}</option>
	                    </select>
	                  </div>
	                  <div class="col-md-3 col-6 mb-2">
	                  	<label class="hidden-md-up">{{trans('Admin'.DS.'content.to_date')}}</label>
	                    <select class="form-control" name="date_open[0][to_date]">
	                      <option value="1">{{trans('Admin'.DS.'content.monday')}}</option>
	                      <option value="2">{{trans('Admin'.DS.'content.tuesday')}}</option>
	                      <option value="3">{{trans('Admin'.DS.'content.wednesday')}}</option>
	                      <option value="4">{{trans('Admin'.DS.'content.thursday')}}</option>
	                      <option value="5">{{trans('Admin'.DS.'content.friday')}}</option>
	                      <option value="6">{{trans('Admin'.DS.'content.saturday')}}</option>
	                      <option value="0" selected="">{{trans('Admin'.DS.'content.sunday')}}</option>
	                    </select>
	                  </div>
	                  <div class="col-md-3 col-6 mb-2">
	                  	<label class="hidden-md-up">{{trans('Admin'.DS.'content.from_hour')}}</label>
	                    <input class="form-control choose_hour" type="text" name="date_open[0][from_hour]" value="">
	                  </div>
	                  <div class="col-md-3 col-6 mb-2">
	                  	<label class="hidden-md-up">{{trans('Admin'.DS.'content.to_hour')}}</label>
	                    <input class="form-control choose_hour" type="text" name="date_open[0][to_hour]" value="">
	                  </div>
									</div>
									<div id="append_custom_open">

	                </div>
	                <div class="w-100 text-center" id="add_custom_open">
	                  <br/>
	                  <button class="btn btn-default" type="button" onclick="addCustomOpen()">
	                   {{trans('Admin'.DS.'content.add_hour_open')}}
	                  </button>
	                  <br/>
	                </div>
								</fieldset>
								
								<div class="form-group col-md-12">
									<label>{{trans('Location'.DS.'layout.description')}}</label>
									<textarea maxLength="512" class="form-control max" name="description" rows="5" style="height:auto;"></textarea>
								</div>

								<fieldset class="form-group col-md-12" id="fieldset_feedback_address">
									<label>{{trans('Location'.DS.'layout.address')}} <span style="color: #d9534f">*  ({{trans('Location'.DS.'layout.note_add_create')}})</span> </label>
									<input type="text" id="address" maxlength="128" name="address" class="form-control input-md-6" required>
									<div class="form-control-feedback" id="feedback_address" style="color: #d9534f; display: none"></div>
								</fieldset>
								<fieldset id="fieldset_feedback_country" class="form-group col-md-4">
									<label>{{trans('Location'.DS.'layout.country')}} <span style="color: #d9534f">*</span></label>
									<select class="custom-select form-control" name="country" id="country" onchange="getLocationAjax(this.value,'city')" required>
										<option value="">-- {{trans('Location'.DS.'layout.country')}} --</option>
										@foreach($country as $key => $name)
											<option value="{{$key}}">{{$name}}</option>
										@endforeach
									</select>
									<div class="form-control-feedback" id="feedback_country" style="color: #d9534f;display: none"></div>
								</fieldset>
								<fieldset class="form-group col-md-4" id="fieldset_feedback_city">
									<label>{{trans('Location'.DS.'layout.city')}} <span style="color: #d9534f">*</span></label>
									<select class="custom-select form-control" name="city" id="city" onchange="getLocationAjax(this.value,'district')" required>
										<option value="">-- {{trans('Location'.DS.'layout.city')}} --</option>
									</select>
									<div class="form-control-feedback" id="feedback_city" style="color: #d9534f;display: none"></div>
								</fieldset>
								<fieldset class="form-group col-md-4" id="fieldset_feedback_district">
									<label>{{trans('Location'.DS.'layout.district')}} <span style="color: #d9534f">*</span></label>
									<select class="custom-select form-control"  name="district" id="district" required>
										<option value="">-- {{trans('Location'.DS.'layout.district')}} --</option>
									</select>
									<div class="form-control-feedback" id="feedback_district" style="color: #d9534f;display: none"></div>
								</fieldset>				
								<fieldset class="form-group col-md-6">
									<label>Wifi</label>
									<input type="text" name="wifi" maxlength="128"  id="wifi" class="form-control" autocomplete="false" placeholder="Wifi">
								</fieldset>
								<fieldset class="form-group col-md-6" id="fieldset_feedback_wifi">
									<label>{{trans('Location'.DS.'layout.pass_wifi')}}</label>
									<input type="text" name="pass_wifi"  maxlength="128" class="form-control" autocomplete="new-password" placeholder="{{trans('Location'.DS.'layout.pass_wifi')}}">
									<div class="form-control-feedback" id="feedback_pass_wifi" style="color: #d9534f;display: none"></div>
								</fieldset>
								<fieldset class="form-group col-md-6">
									<label>{{trans('Location'.DS.'layout.phone')}}</label>
									<input type="text" maxlength="20" name="phone" id="phone" class="form-control" autocomplete="new-phone" placeholder="{{trans('Location'.DS.'layout.phone')}}">
								</fieldset>
								<fieldset class="form-group col-md-6" id="fieldset_feedback_email">
									<label>Email</label>
									<input type="text" maxlength="128" name="email" id="email" class="form-control" autocomplete="new-email" placeholder="Email">
									<div class="form-control-feedback" id="feedback_email" style="color: #d9534f;display: none"></div>
								</fieldset>
								<fieldset class="form-group col-md-12 box-hastag" id="fieldset_feedback_tag">
									<label>{!! trans('Location'.DS.'layout.place_tags') !!} <span style="color: #d9534f">*</span>
									<span style="color: #d9534f">  ({{trans('Location'.DS.'layout.note_add_tags_create')}})</span></label>
									<!-- <select class="tokeHastag" multiple="true" name="tag[]"></select> -->
									<input type="text" name="tag" class="tokeHastag form-control" style="min-width: 100%;"/>
									<div class="form-control-feedback" id="feedback_tag" style="color: #d9534f; display: none"></div>
								</fieldset>
							</div>
							
							<div class="btn-step w-100 d-flex aline justify-content-center">
								<button type="button" id="button_step3" class="next-step btn btn-primary btn-lg">{{trans('Location'.DS.'layout.button_step2')}}</button>
							</div>
						</div>

						<div class="tab-pane tab-upload-image" id="step3" role="tabpanel">
							<!-- Nav tabs -->
							<ul class="nav tab-upload-image-nav" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="avatar_nav" data-toggle="tab" href="#avatar" role="tab">{{trans('global.avatar')}}</a>
								</li>
								<li class="nav-item" style="border-width: 2px">
									<a class="nav-link" data-toggle="tab" href="#khong-gian" role="tab">{{trans('global.space')}}</a>
								</li>
								<li class="nav-item" style="border-width: 2px">
									<a class="nav-link" data-toggle="tab" href="#menu" role="tab">{{trans('global.image')}}</a>
								</li>
								<li class="nav-item" style="border-left: 2px solid #e0e8ed;">
									<a class="nav-link" data-toggle="tab" href="#video" role="tab">{{trans('global.video')}}</a>
								</li>
				 
							</ul>

							<!-- Tab panes -->
							<div class="tab-content">
								<div class="tab-pane" id="khong-gian" role="tabpanel">
									<div class="upload-placeholder">
										<div class="upload-img-post" id="image_khong_gian_create">
											<ul class="list-unstyled row">
												<li class="col-md-4 col-6">
													<div class="box-img-upload upload-begin upload-image-disabled">
														<div class="box-img-upload-content">
															<i class="icon-new-white"></i>
															<p>{{trans('Location'.DS.'layout.choose_image')}}</p>
														</div>
													</div>
												</li>
											</ul>
											<input id="file-upload" type="file"  multiple="" accept="image/*">
										</div>
									</div>
								</div>
								<div class="tab-pane" id="menu" role="tabpanel">
									<div class="upload-placeholder">
										<div class="upload-img-post" id="image_menu_create">
											<ul class="list-unstyled row">
												<li class="col-md-4 col-6">
													<div class="box-img-upload upload-begin upload-image-disabled">
														<div class="box-img-upload-content">
															<i class="icon-new-white"></i>
															<p>{{trans('Location'.DS.'layout.choose_image')}}</p>
														</div>
													</div>
												</li>
											</ul>
											<input id="file-upload1" type="file" multiple="" accept="image/*">
										</div>
									</div>
								</div>
								<div class="tab-pane" id="video" role="tabpanel">
									<fieldset class="form-group">
										<label>{{trans('Location'.DS.'layout.upload_video')}}</label>
										<div class="row">
											<div class="col-md-6" style="padding-top: 4px;">
												<input id="add_new_video" placeholder="{{trans('Location'.DS.'layout.input_video')}}" data-type="create" type="text" maxlength="128"  value="" class="form-control input-link-video">
											</div>
											<div class="col-md-6">
												<input type="button" class="btn btn-primary btn-addvideo" onclick="loadThumbNew('add')" value="{{trans('Location'.DS.'user.add_video')}}">
											</div>
										</div>
									</fieldset>
									<!-- end form group -->
									<div class="row content-video-location mt-3" id="content-video-location-create">
										<!-- <div class="col-md-4 col-sm-6 mb-4">
	                      <div class="iframe-video">
	                          <a data-fancybox href="https://www.youtube.com/watch?v=oqkjfNE0CW4">
	                              <img src="https://img.youtube.com/vi/dSs3ya1ppp4/maxresdefault.jpg" alt="">
	                              <span class="ytp-time-duration">3:07</span>
	                          </a>
	                          <p>
	                              <a href="">
	                                  Đại tiệc bibimbap - cơm trộn 9 loại 
	                              </a>
	                          </p>
	                      </div>
	                  </div> -->
									</div>
								</div>
								<div class="tab-pane active" id="avatar" role="tabpanel">
									<fieldset class="box-upload-avata form-group col-md-4" id="avatar_create">
										<label>{{trans('Location'.DS.'layout.upload_avatar')}} <span style="color: #d9534f">*</span></label>
										<div class="upload-avata box-img-upload avata-border">
											<div class="box-img-upload-content">
												<i class="icon-new-white"></i>
												<p>{{trans('Location'.DS.'layout.choose_image')}}</p>
											</div>
										</div>
										<input id="file-upload-avata" type="file" accept="image/*">
										<div class="form-control-feedback" id="feedback_avatar" style="color: #d9534f; display: none"></div>
									</fieldset>  
								</div>
							</div>
							
							<!-- <fieldset class="form-group col-md-6" id="fieldset_feedback_code_invite">
								<label>{{mb_ucfirst(trans('Location'.DS.'layout.code_invite'))}} ({{trans('Location'.DS.'layout.note_code_invite')}})</label>
								<input type="text" id="code_invite" name="code_invite" class="form-control input-md-6">
								<div class="form-control-feedback" id="feedback_code_invite" style="color: #d9534f; display: none"></div>
							</fieldset> -->

							<fieldset class="form-group col-md-12">
								<label>{{trans('Location'.DS.'layout.captcha')}} <span style="color: #d9534f">*</span></label>
								<div class="col-md-6" style="float:left;">
									<canvas id="canvas_captcha" width="200" height="75" style="display:inline-block;border:1px solid #ddd;"></canvas>
									<button class="btn btn-xs" title="Renew captcha code" onclick="createCaptcha()" type="button" style="display:inline-block;margin-top: -35px;"><i
											class="fa fa-refresh"></i></button>
								</div>
								<div class="col-md-6"  style="float:left;">
									<input type="text" placeholder="{{trans('valid.captcha_code')}}" name="captcha_code" id="captcha_code"
												 class="form-control required_field" maxlength="5">
									<span class="error_captcha text-danger col-xs-12"></span>
								</div>
							</fieldset>

							<div class="btn-step w-100 d-sm-flex aline justify-content-sm-center">
								<button type="button" class="prev-step btn  btn  btn-outline-primary  btn-lg">{{trans('Location'.DS.'layout.button_step1')}}</button>
								<button type="button" id="preview_location_fe" class="preview-step btn  btn  btn-outline-primary btn-lg">{{trans('Location'.DS.'layout.prev')}}</button>
								<button type="button" id="create_location_fe" class="creat-step btn btn-primary btn-lg">{{trans('Location'.DS.'layout.create')}}</button>

							</div>
						</div>

					</div>
				</form>
			</div>
			<!-- modal process create header -->
		</div>
	</div>
</div>

<div id="modal-create-success" class="modal  modal-submit-payment  modal-vertical-middle  modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-logo pt-4 text-center">
				<img src="/frontend/assets/img/logo/logo-large.svg" alt="">
			</div>
			<h4>&nbsp;</h4>
			<p class="text_1 text-center"><span class="glyphicon glyphicon-ok" style="color: green;padding-right: 5px;"></span><span style="text-transform: uppercase;font-weight: bold;">{{trans('Location'.DS.'layout.create_location_success')}}</span>
				<br><span style="font-style: italic;">{{trans('Location'.DS.'layout.create_location_success_information')}}</span>
			</p>

            <h6 class="text_2 text-center" style="font-weight: bold;">{{trans('Location'.DS.'layout.update_location_confirm')}}</h6>
			<p></p>
            <div class="modal-button d-flex justify-content-center d-flex">
                <div class="col-6">
                    <a class="btn btn-outline-primary" href="#" data-dismiss="modal">{{trans('Location'.DS.'layout.exit')}}</a>
                </div>
                <div class="col-6">
				    <a class="btn btn-primary" href="#" onclick="closeSuccess()">{{trans('Location'.DS.'layout.confirm')}}</a>
                </div>
			</div>
		</div>
	</div>
</div>


<div id="modal-create-category" class="modal modal-vertical-middle  modal-submit-payment   modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-logo pt-4 text-center">
				<img src="/frontend/assets/img/logo/logo-large.svg" alt="">
			</div>
			<h4>&nbsp;</h4>
			<p id="message_category"></p>
			<div class="modal-button d-flex justify-content-center">
				<a class="btn btn-secorady" href="#" data-dismiss="modal">{{trans('global.close')}}</a>
			</div>
		</div>
	</div>
</div>

<style>
	.item_custom_open {
		position: relative;
	}
	.remove_custom_open{
		position: absolute;
		cursor: pointer;
		margin-top: 12px;
		right: 0;
	}
	.scroll-content-modal{
		height: 300px;
	}
	@media(max-width: 720px){
		.remove_custom_open{
			top: -15px;
			right: 5px;
			font-size: 150%;
		}
		.scroll-content-modal{
			height: 100% !important;
		}
/*		.pac-container{
		  position: fixed !important;
    	top: 30px !important;
		}*/
	}
	.group_product input[type=file]{
		cursor: pointer !important;
	}
/*	.pac-container.pac-logo {
		width: 100% !important;
		position: unset !important;
		top: 60px !important;
		left: 0 !important;
	}*/
	.pac-container {  
	  z-index: 1999 !important;  
	  top: 0 !important;
	  left: 0 !important; 
	}
	#map_div_edit, #map_div{
		position: relative;
	}
	
</style>
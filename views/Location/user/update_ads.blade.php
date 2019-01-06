<div class="content-edit-profile-manager">
	<h3>{{mb_strtoupper(trans('Location'.DS.'user.update_ads'))}}</h3>
	<p class="text-danger" id="text-error"></p>
	<form id="form-create-ads" onsubmit="return false;" enctype="multipart/form-data" method="POST">
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<input type="hidden" id="width" value="{{$ads->_type_ads->width}}">
          <input type="hidden" id="height" value="{{$ads->_type_ads->height}}">
					<label>{{trans('Location'.DS.'user.ads_image')}} <span class="text-danger">*</span>
	          <br/>{{trans('Location'.DS.'user.size')}}: 
	          <span id="banner_width"></span>{{$ads->_type_ads->width}}px x
	          <span id="banner_height"></span>{{$ads->_type_ads->height}}px
	        </label>
	        <input class="form-control" type="file" name="ads_image" id="ads_image"  accept=".png, .jpg, .jpeg" onchange="readURL(this)">
          <p>
            <button class="btn btn-default" onclick="$('#ads_image').click()">
              {{trans('Location'.DS.'user.choose_image')}}
            </button>
          </p>
	        <div id="div-image-ads" class="pt-4 w-100 text-center">
	          @if($ads->_media_ads)
	            @if($ads->_type_ads->kind=='banner')
	            <img src="{{$ads->_media_ads[0]->link}}" style="max-width:100%;width:{{$ads->_type_ads->width}}px;height:{{$ads->_type_ads->height}}px;">
	            @endif
	          @endif
	        </div>
				</div>
			<div class="col-md-12 text-center">
				<button type="button" onclick="createAds()" class="btn-submit btn btn-primary">{{trans('Location'.DS.'user.update_ads')}}</button>
			</div>
			<!-- <div class="col-md-12">
				<div class="notification-form">
					 <i class="icon-check-grey"></i> Auto save
				</div>
			</div> -->
		</div>
	</form>
</div>
<div id="modal-message" class="modal modal-vertical-middle fade modal-submit-payment  modal-vertical-middle modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment modal-vertical-middle modal-vertical-middle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content text-center">
      <div class="modal-logo pt-4 text-center">
        <img src="/frontend/assets/img/logo/logo-large.svg" alt="">
      </div>
      <h4>&nbsp;</h4>
      <p class="text_1 text-center" id="message"></p>
      <div class="modal-button d-flex justify-content-center">
        <a class="btn btn-secorady" data-dismiss="modal" id="link_modal">{{trans('global.close')}}</a>
      </div>
    </div>
  </div>
</div>
@include('Location.user.discount-style')
<link href="{{asset('template/js/datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
@section('JS')
	<script src="{{asset('template/vendors/moment/min/moment.min.js')}}"></script>
	<script src="{{asset('template/vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
	<script src="{{asset('template/js/datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
	<script src="https://cdn.ckeditor.com/4.8.0/standard/ckeditor.js"></script>
	@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
		@include('Location.user.crop_image')
	@endif
	<script>
    function readURL(input) {
      for (var i = 0; i < input.files.length; i++) {
        if (input.files[i]) {
          var reader = new FileReader();

          reader.onload = function (e) {
            var width = $("#width").val();
            var height = $("#height").val();
            var img = $('<img style="max-width:100%;width:'+width+'px;height:'+height+'px;">');
            img.attr('src', e.target.result);
            $("#div-image-ads").html(img);
          };
          reader.readAsDataURL(input.files[i]);
        }
      }
    }

    function createAds(){
      var data = new FormData($("#form-create-ads").get(0));
      data.append('_token',$("[name='_token']").prop('content'));
      $.ajax({
        url : '/ads/postUpdateAds/'+{{$ads->id}},
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        data: data, 
        beforeSend: function(){
          $("#loading").show();
        },
        success: function (data) {
          $("#loading").hide();
          if(typeof data !== 'object'){
            data = JSON.parse(data);
          }
          if(data.error==0){
            // $("#form-create-discount").get(0).reset();
            $("#message").html(data.message);
            $("#modal-message").modal();
            $("#link_modal").attr('href',"{{route('list-ads',['id_user'=>$user->id])}}");
            $("#link_modal").attr('data-dismiss',"");
          }else{
            // $("#form-create-discount").get(0).reset();
            $("#message").html(data.message);
            $("#modal-message").modal();
          }
        }
      });
    }
	</script>
@endsection

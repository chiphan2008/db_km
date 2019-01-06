<div class="content-edit-profile-manager">
	<h3>{{mb_strtoupper(trans('Location'.DS.'user.publish_ads'))}}</h3>
	<p class="text-danger" id="text-error"></p>
	<form id="form-create-ads" onsubmit="return false;" enctype="multipart/form-data" method="POST">
		<div class="row">
			<div class="col-6">
				<div class="form-group">
          <input type="hidden" name="id" id="id" value="{{$ads->id}}">
          <input type="hidden" name="type" id="type" value="{{$ads->_type_ads->id}}">

          <input type="hidden" name="price" id="price" value="0">
          <input type="hidden" name="total" id="total" value="0">
					<label>{{trans('Location'.DS.'user.ads_name')}}
					<div><b>{{$ads->name}}</b></div>
				</div>
			</div>
			@php
       $type_apply = $ads->_type_ads->type_apply;
       $date_enable = $type_apply[0]=='1';
       $click_enable = $type_apply[1]=='1';
       $view_enable = $type_apply[2]=='1';
      @endphp
      <div class="col-md-6">
        <div class="form-group">
          <label>{{trans('Location'.DS.'user.type_apply')}} <span class="text-danger">*</span></label>
          <select  name="type_apply"  id="type_apply" class="custom-select form-control" onchange="changeTypeApply(this)">
						@if($date_enable)
            <option value="date">{{trans('Location'.DS.'user.apply_by_date')}}</option>
            @endif
						@if($click_enable)
            <option value="click">{{trans('Location'.DS.'user.apply_by_click')}}</option>
            @endif
						@if($view_enable)
            <option value="view">{{trans('Location'.DS.'user.apply_by_view')}}</option>
            @endif
          </select>
        </div>
      </div>

      <div class="col-md-6">
        <div class="row">
          <div class="col-6 input-date input-apply">
            <div class="form-group">
              <label>{{trans('Location'.DS.'user.ads_from')}}</label>
              <input type="text" value="" name="ads_from" class="choose_date form-control ads_from" placeholder="{{trans('Location'.DS.'user.ads_from')}}">
            </div>
          </div>
          <div class="col-6 input-date input-apply">
            <div class="form-group">
              <label>{{trans('Location'.DS.'user.ads_to')}}</label>
              <input type="text" value="" name="ads_to" class="choose_date form-control ads_to" placeholder="{{trans('Location'.DS.'user.ads_to')}}">
            </div>
          </div>
          <div class="col-6 input-click input-apply" style="display:none;">
            <div class="form-group">
              <label>{{trans('Location'.DS.'user.click')}}</label>
              <input type="number" min="1" max="999999999" maxlength="9" value="{{$ads->click}}" name="click" class="number form-control click" placeholder="{{trans('Location'.DS.'user.click')}}">
            </div>
          </div>
          <div class="col-6 input-view input-apply" style="display:none;">
            <div class="form-group">
              <label>{{trans('Location'.DS.'user.view')}}</label>
              <input type="number" min="1" max="999999999" maxlength="9" value="{{$ads->view}}" name="view" class="number form-control view" placeholder="{{trans('Location'.DS.'user.view')}}">
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="row">
          <div class="col-6">
            <div class="form-group">
		          <label>{{trans('Location'.DS.'layout.country')}}</label>
		          <select class="form-control custom-select" name="country_ads" id="country_ads" onchange="selectCountry(this)">
			          @foreach($countries as $key => $name)
			            <option value="{{$key}}">{{$name}}</option>
			          @endforeach
		          </select>
		        </div>
          </div>
          <div class="col-6 input-date input-apply">
            <div class="form-group">
		          <label>{{trans('Location'.DS.'layout.city')}}</label>
		          <select class="form-control custom-select" name="city_ads" id="city_ads">
		          </select>
		        </div>
          </div>
        </div>
      </div>


    	<div class="col-md-6">
        <h4 class="text-danger">{{trans('Location'.DS.'user.price')}}: <span id="price_text">0</span>K</h4 class="text-danger">
      </div>
      <div class="col-md-6">
        <h4 class="text-danger">{{trans('Location'.DS.'user.total')}}: <span id="total_text">0</span>K</h4 class="text-danger">
      </div>

			<div class="col-md-12 text-center">
				<button type="button" onclick="createAds()" class="btn-submit btn btn-primary">{{trans('Location'.DS.'user.publish_ads')}}</button>
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

		$(function(){
			$("#country_ads").trigger("change");
      $("input[type='number']").on("keypress",function(e){
        var maxlength = $(this).prop('maxlength');
        var current = $(this).val().length;
        if(current >= maxlength){
          return false;
        }
        return (e.charCode >= 48 && e.charCode <= 57) || e.charCode == 43 || event.charCode == 46 || event.charCode == 0 ;
      })

      $("input[type='number']").on("change",function(e){
        var text = parseFloat($(this).val().toLowerCase());
        var min = parseFloat($(this).prop("min"));
        var max = parseFloat($(this).prop("max"));
        if(text>max){
          $(this).val(max);
        }

        if(text<min){
          $(this).val(min);
        }
      })

      $('.ads_from').datetimepicker({
        format: 'DD-MM-Y',
        defaultDate: moment(),
        minDate: moment().millisecond(0).second(0).minute(0).hour(0).format()
      }).on('dp.change',calPrice);

      $('.ads_to').datetimepicker({
        format: 'DD-MM-Y',
        defaultDate: moment().add(1, 'd').format(),
        minDate: moment().millisecond(0).second(59).minute(59).hour(23).format()
      }).on('dp.change',calPrice);

      $("#type").trigger("change");
      $("#type_apply").trigger("change");
      $("#form-create-ads input").on("change",calPrice);
      $("#form-create-ads select").on("change",calPrice);
		})

    function changeType(obj){
      var type = $(obj).val();
      var opt = $("#type option[value="+type+"]");
      $("#kind").val(opt.data('kind'));
      $("#width").val(opt.data('width'));
      $("#height").val(opt.data('height'));
      $("#banner_width").text($("#width").val())
      $("#banner_height").text($("#height").val())
      calPrice();
    }

		function changeTypeApply(obj){
      var type = $(obj).val();
      $(".input-apply").hide();
      $(".input-"+type).show();
      calPrice();
    }

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
        url : '/ads/postPublishAds',
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

    function calPrice(){
      var data = new FormData($("#form-create-ads").get(0));
      data.append('_token',$("[name='_token']").prop('content'));
      $.ajax({
        url : '/ads/postCalPriceAds',
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        data: data, 
        success:function(res){
          $("#price").val(res.price);
          $("#total").val(res.total);
          $("#price_text").text(parseFloat(res.price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
          $("#total_text").text(parseFloat(res.total).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        }
      });
    }

    function selectCountry(obj){
    	var country = $(obj).val();
			$.ajax({
				url : '/search/loadCity',
				type: 'POST',
				data: {
					_token: _token,
					country: country
				},
				success: function(response){
					$("#city_ads").html(response);
					//$(".city_search").trigger("change");
				}
			})
    }
	</script>
@endsection

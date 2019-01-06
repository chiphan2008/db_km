<div class="content-edit-profile-manager">
	<h3>{{mb_strtoupper(trans('Location'.DS.'user.create_ads'))}}</h3>
	<p class="text-danger" id="text-error"></p>
	<form id="form-create-ads" onsubmit="return false;" enctype="multipart/form-data" method="POST">
		<div class="row">
			<div class="col-6">
				<div class="form-group">
          <input type="hidden" id="width" value="0" name="width">
          <input type="hidden" id="height" value="0" name="height">

					<label>{{trans('Location'.DS.'user.ads_type')}} <span class="text-danger">*</span></label>
          <div class="row w-100 ml-0">
            <select name="kind"  id="kind" class="col-md-3 custom-select form-control" onchange="changeKind(this)">
              <option value="web">Web</option>
              <option value="app">App</option>
              <option value="mobile">Mobile</option>
            </select>
  					<select  name="type"  id="type" class="col-md-9 custom-select form-control" onchange="changeType(this)">
  					</select>
          </div>
				</div>
			</div>

      <div class="col-md-6">
        <div class="form-group">
          <label>{{trans('Location'.DS.'user.ads_name')}} <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" placeholder="{{trans('Location'.DS.'user.ads_name')}}">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <div>&nbsp;</div>
          <input type="radio" class="choose_type check-box" data-href="#input_link" value="link" name="choose_type" checked="">
          {{trans('Location'.DS.'user.ads_link')}}
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type="radio" class="choose_type check-box" data-href="#input_content" value="content" name="choose_type">
          {{trans('Location'.DS.'user.ads_content')}}
        </div>
      </div>

      <div class="col-md-6 input_ads" id="input_link">
        <div class="form-group">
          <label>{{trans('Location'.DS.'user.ads_link')}} <span class="text-danger">*</span></label>
          <input type="text" name="link" placeholder="https://kingmap.com" class="form-control" placeholder="{{trans('Location'.DS.'user.ads_link')}}">
        </div>
      </div>

      <div class="col-md-6 input_ads" id="input_content" style="display:none;">
        <div class="form-group">
          <label>{{trans('Location'.DS.'user.ads_content')}} <span class="text-danger">*</span></label>
          <select  name="content"  id="content" class="custom-select form-control" onchange="changeTypeApply(this)">
            @foreach($contents as $content)
              <option value="{{$content->id}}">{{$content->name}}</option>
            @endforeach
          </select>
        </div>
      </div>


			<div class="col-md-12">
				<div class="form-group">
				<label>{{trans('Location'.DS.'user.ads_image')}} <span class="text-danger">*</span>
          <br/>{{trans('Location'.DS.'user.size')}}: 
          <span id="banner_width"></span>px x
          <span id="banner_height"></span>px
        </label>
        <input class="form-control" type="file" name="ads_image" id="ads_image" accept=".png, .jpg, .jpeg" onchange="readURL(this)">
        <p>
          <button class="btn btn-default" onclick="$('#ads_image').click()">
            {{trans('Location'.DS.'user.choose_image')}}
          </button>
        </p>
        <div id="div-image-ads" class="pt-4 w-100 text-center">
        </div>
				<!-- <div class="upload-placeholder"  id="ads_image_upload" style="">
          <div class="upload-img-post" id="create_ads_image">
            <ul class="list-unstyled row">
              <li class="col-md-4 col-6">
                <div class="box-img-upload upload-begin upload-image-disabled">
                  <div class="box-img-upload-content">
                    <i class="icon-new-white"></i>
                    <p>{{trans('Location'.DS.'user.choose_image')}}</p>
                  </div>
                </div>
              </li>
            </ul>
            <input style="visibility: hidden;" type="file" name="ads_image" accept=".png, .jpg, .jpeg">
          </div>
        </div> -->
			</div>
			<div class="col-md-12 text-center">
				<button type="button" onclick="createAds()" class="btn-submit btn btn-primary">{{trans('Location'.DS.'user.create_ads')}}</button>
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

      $("#kind").trigger("change");
      // $("#type_apply").trigger("change");
      // $("#form-create-ads input").on("change",calPrice);
      // $("#form-create-ads select").on("change",calPrice);

      $(".choose_type").on("click",function(){
        obj = $(this).data('href');
        $(".input_ads").hide();
        $(obj).show();
      })
		})

    function changeType(obj){
      var type = $(obj).val();
      var opt = $("#type option[value="+type+"]");
      // $("#kind").val(opt.data('kind'));
      $("#width").val(opt.data('width'));
      $("#height").val(opt.data('height'));
      $("#banner_width").text($("#width").val())
      $("#banner_height").text($("#height").val())
      // calPrice();
    }

    function changeKind(obj){
      var kind = $(obj).val();
      $.ajax({
        url : '/ads/getTypeAds',
        type: 'GET',
        data: {
          kind: kind
        },
        success:function(res){
          var html = '';
          if(res.length){
            $.each(res,function(key,value){
              html += '<option data-kind="'+value.kind+'" data-width="'+value.width+'" data-height="'+value.height+'" value="'+value.id+'" data-type-apply="'+value.type_apply+'">'+value.name+'</option>'
            })
            
          }
          $("#type").html(html).trigger("change");
        }
      });
    }

		// function changeTypeApply(obj){
  //     var type = $(obj).val();
  //     $(".input-apply").hide();
  //     $(".input-"+type).show();
  //     calPrice();
  //   }

    function readURL(input) {
      for (var i = 0; i < input.files.length; i++) {
        if (input.files[i]) {
          var reader = new FileReader();

          reader.onload = function (e) {
            var width = $("#width").val()?$("#width").val():0;
            var height = $("#height").val()?$("#height").val():0;
            if(width==0 || height==0){
              $("#message").html("{{trans('Location'.DS.'user.no_choose_ads_type')}}");
              $("#modal-message").modal();
            }
            var img = $('<img style="width:'+width+'px;height:'+height+'px;">');
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
        url : '/ads/postCreateAds',
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

    // function calPrice(){
    //   var data = new FormData($("#form-create-ads").get(0));
    //   data.append('_token',$("[name='_token']").prop('content'));
    //   $.ajax({
    //     url : '/ads/postCalPriceAds',
    //     type: 'POST',
    //     cache: false,
    //     contentType: false,
    //     processData: false,
    //     data: data, 
    //     success:function(res){
    //       $("#price").val(res.price);
    //       $("#total").val(res.total);
    //       $("#price_text").text(parseFloat(res.price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    //       $("#total_text").text(parseFloat(res.total).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    //     }
    //   });
    // }
	</script>
@endsection

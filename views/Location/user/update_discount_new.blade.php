
<div class="content-edit-profile-manager">
	<h3>{{mb_strtoupper(trans('Location'.DS.'user.update_discount'))}}</h3>
	<p class="text-danger" id="text-error"></p>
	<form id="form-create-discount" onsubmit="return false;">
		<div class="row">
			<div class="col-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.discount_name')}} <span class="text-danger">*</span></label>
					<input type="text" value="{{$discount->name}}" name="name" class="form-control" placeholder="{{trans('Location'.DS.'user.discount_name')}}">
				</div>
			</div>
			<div class="col-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.discount_text')}} <span class="text-danger">*</span></label>
					<textarea maxlength="128" colspans="2" value="" name="description" class="form-control" placeholder="{{trans('Location'.DS.'user.discount_text')}}">{{$discount->description}}</textarea>
				</div>
			</div>

			<div class="col-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.discount_content')}} <span class="text-danger">*</span></label>
					<select  name="content" class="form-control chosen-select" id="list_content_discount" data-placeholder="{{trans('Location'.DS.'user.discount_content')}}" onchange="changeContent()">
						@foreach($contents as $content)
						<option value="{{$content->id}}" {{$content->id==$discount->id_content?'selected':''}}>{{$content->name}}</option>
						@endforeach
					</select>
				</div>
			</div>

      <div class="col-6">
        <div class="form-group">
          <label>{{trans('Location'.DS.'user.discount_product')}} <span class="text-danger">*</span></label>
          <select  name="product[]" class="form-control chosen-select" id="list_product_discount" multiple="" data-placeholder="{{trans('Location'.DS.'user.discount_product')}}">
          	@foreach($products as $key => $product)
						<option value="{{$product->id}}" {{$arrProduct[$key]?'selected':''}}>{{$product->name}}</option>
						@endforeach
          </select>
        </div>
      </div>


			<!-- <div class="col-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.discount_phone')}}</label>
					<input type="tel" value="" maxlength="15" name="discount_phone" class="form-control" placeholder="{{trans('Location'.DS.'user.discount_phone')}}">
				</div>
			</div>
			<div class="col-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.discount_link')}}</label>
					<input type="text" value="{{$user->discount_link?$user->discount_link:''}}" name="discount_link" class="form-control" placeholder="{{trans('Location'.DS.'user.discount_link')}}">
				</div>
			</div> -->

			<div class="col-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.discount_from')}}</label>
					<input type="text" value="{{date('d-m-Y',strtotime($discount->date_from))}}" name="discount_from" class="choose_date form-control discount_from" placeholder="{{trans('Location'.DS.'user.discount_from')}}">
				</div>
			</div>
			<div class="col-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.discount_to')}}</label>
					<input type="text" value="{{date('d-m-Y',strtotime($discount->date_to))}}" name="discount_to" class="choose_date form-control discount_to" placeholder="{{trans('Location'.DS.'user.discount_to')}}">
				</div>
			</div>


			<div class="col-md-4">
				<div class="form-group" id="create_discount_image">
  				<label>{{trans('Location'.DS.'user.discount_image')}}</label>	
  				<div class="upload-image box-img-upload" >
            <div id="div-image">
            	<img src="{{$discount->image}}" style="max-width:100%;
            </div>
            <div class="box-img-upload-content">
              <i class="icon-new-white"></i>
              <p>{{trans('Location'.DS.'layout.choose_image')}}</p>
            </div>
          </div>
          <input id="discount_image" type="file" name="discount_image" accept="image/*" onchange="readImage(this)">
  			</div>
      </div>
			<div class="col-md-12 text-center">
				<button type="button" onclick="updateDiscount()" class="btn-submit btn btn-primary">{{trans('Location'.DS.'user.update_discount')}}</button>
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
      // changeContent();
      $("#list_product_discount").chosen({
        placeholder:""
      });
      $('#create_discount_image').on('click','.upload-image', function(event) {
        event.preventDefault();
        $('#discount_image').click();
      });

      $('.discount_from').datetimepicker({
        format: 'DD-MM-Y',
        date: moment('{{$discount->date_from}}').format(),
        minDate: moment('{{$discount->date_from}}').millisecond(0).second(0).minute(0).hour(0).format()
      });

      $('.discount_to').datetimepicker({
        format: 'DD-MM-Y',
        date: moment('{{$discount->date_to}}').format(),
        minDate: moment('{{$discount->date_from}}').millisecond(0).second(59).minute(59).hour(23).format()
      });



      
      // CKEDITOR.replace( 'discount_description',{
      //      language: '{{\App::getLocale()=='vn'?'vi':'en'}}'
      // });
      //$("#list_content_discount").val($("#list_content_discount option").first().prop('value')).trigger("change");
		})

    function readImage(input) {
      $(".box-img-upload-content").hide();
      for (var i = 0; i < input.files.length; i++) {
        if (input.files[i]) {
          var reader = new FileReader();

          reader.onload = function (e) {
            var img = $('<img style="max-width:100%;">');
            img.attr('src', e.target.result);
            $("#div-image").html(img);
          };
          reader.readAsDataURL(input.files[i]);
        }
      }
    }


		// function addCustomOpen() {
  //     var index = $(".item_custom_open").length;
  //     index++;

  //     html = '<div class="item_custom_open col-12 row">';
  //     html += '          <div class="col-3">';
  //     html += '            <select class="form-control" name="date_open[' + index + '][from_date]" id="">';
  //     html += '              <option value="1">{{trans('Admin'.DS.'content.monday')}}</option>';
  //     html += '              <option value="2">{{trans('Admin'.DS.'content.tuesday')}}</option>';
  //     html += '              <option value="3">{{trans('Admin'.DS.'content.wednesday')}}</option>';
  //     html += '              <option value="4">{{trans('Admin'.DS.'content.thursday')}}</option>';
  //     html += '              <option value="5">{{trans('Admin'.DS.'content.friday')}}</option>';
  //     html += '              <option value="6">{{trans('Admin'.DS.'content.saturday')}}</option>';
  //     html += '              <option value="0">{{trans('Admin'.DS.'content.sunday')}}</option>';
  //     html += '            </select>';
  //     html += '          </div>';
  //     html += '          <div class="col-3">';
  //     html += '            <select class="form-control" name="date_open[' + index + '][to_date]" id="">';
  //     html += '              <option value="1">{{trans('Admin'.DS.'content.monday')}}</option>';
  //     html += '              <option value="2">{{trans('Admin'.DS.'content.tuesday')}}</option>';
  //     html += '              <option value="3">{{trans('Admin'.DS.'content.wednesday')}}</option>';
  //     html += '              <option value="4">{{trans('Admin'.DS.'content.thursday')}}</option>';
  //     html += '              <option value="5">{{trans('Admin'.DS.'content.friday')}}</option>';
  //     html += '              <option value="6">{{trans('Admin'.DS.'content.saturday')}}</option>';
  //     html += '              <option value="0">{{trans('Admin'.DS.'content.sunday')}}</option>';
  //     html += '            </select>';
  //     html += '          </div>';
  //     html += '          <div class="col-3">';
  //     html += '            <input class="form-control choose_hour" type="text" name="date_open[' + index + '][from_hour]" value="" >';
  //     html += '          </div>';
  //     html += '          <div class="col-3">';
  //     html += '            <input class="form-control choose_hour" type="text" name="date_open[' + index + '][to_hour]" value="" >';
  //     html += '          </div>';
  //     html += '  <span><i class="remove_custom_open fa fa-remove" onclick="removeCustomOpen(this)"></i></span></div>';
  //     $("#append_custom_open").append(html);

  //     $('.choose_hour').datetimepicker({
  //       format: 'HH:mm',
  //       defaultDate: moment().hours(8).minutes(0).seconds(0).milliseconds(0)
  //     });
  //   }

  //   function removeCustomOpen(obj) {
  //     $(obj).parent().parent().remove();
  //   }

    function updateDiscount(){
    	var form = $('#form-create-discount')[0];
    	var dataPost = new FormData(form);
      dataPost.append('_token', $("meta[name='_token']").prop('content'));
			$.ajax({
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data: dataPost,
        url: '/discount/postUpdateDiscount/{{$discount->id}}',
        beforeSend: function(){
        	$("#loading").show();
        },
        success: function (data) {
        	$("#loading").hide();
        	console.log(data);
        	if(data.error==0){
        		// $("#form-create-discount").get(0).reset();
        		$("#message").html(data.message);
            $("#modal-message").modal();
            $("#link_modal").attr('href',"{{route('list-discount',['id_user'=>$user->id])}}");
            $("#link_modal").attr('data-dismiss',"");
        	}else{
        		// $("#form-create-discount").get(0).reset();
        		$("#message").html(data.message);
            $("#modal-message").modal();
        	}
        }
      })
    }

    function showImageUpload(){
    	$("#discount_image_upload").toggle('fast');
    }

  function changeContent(){
    var content = $("#list_content_discount").val();
    $.ajax({
        type: "POST",
        data: {
          content: content,
          _token: $("meta[name='_token']").prop('content')
        },
        url: '/discount/postLoadProduct',
        beforeSend: function(){
          $("#loading").show();
        },
        success: function (data) {
          $("#loading").hide();
          if(data.error==0){
            if(data.data.length===0){
              $("#message").html("{{trans('Location'.DS.'user.product_not_found')}}");
              $("#modal-message").modal();
            }else{
              var html ='';
              $.each(data.data, function(key,value){
                html+='<option value="'+value.id+'">'+value.name+'</option>';
              });
              $("#list_product_discount").html(html);
              $("#list_product_discount").chosen({
                placeholder:""
              });
            }
          }else{
            $("#message").html(data.message);
            $("#modal-message").modal();
          }
        }
      })
  }

  // function hideAllType(){
  //   $('.from_percent').hide();
  //   $('.from_price').hide();
  //   $('.split').hide();
  //   $('.to_percent').hide();
  //   $('.to_price').hide();
  //   $('.currency').hide();
  //   $('.other').hide();
  // }
	</script>
@endsection

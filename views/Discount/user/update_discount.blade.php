<?php
$arrContent = [];
if($discount->_contents){
  foreach ($discount->_contents as $key => $content) {
    $arrContent[] = $content->id;
  }
}
?>
<div class="content-edit-profile-manager" id="update-discount-page">
	<h3>{{mb_strtoupper(trans('Location'.DS.'user.create_discount'))}}</h3>
	<p class="text-danger" id="text-error"></p>
	<form id="form-create-discount" onsubmit="return false;">
		<div class="row">
			<div class="col-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.discount_name')}} <span class="text-danger">*</span></label>
					<input type="text" value="{{$discount->name}}" name="name" class="form-control" placeholder="{{trans('Location'.DS.'user.discount_name')}}">
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.discount_text')}} <span class="text-danger">*</span></label>
					<input type="text" maxlength="20" value="{{$discount->slogan}}" name="slogan" class="form-control" placeholder="{{trans('Location'.DS.'user.discount_text')}}">
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.discount_type')}} <span class="text-danger">*</span></label>
					<select  name="type" class="custom-select form-control">
						<option value="cua_hang" {{$discount->type=='cua_hang'?'selected':''}}>{{trans('Location'.DS.'user.discount_store')}}</option>
						<option value="online" {{$discount->type=='online'?'selected':''}}>{{trans('Location'.DS.'user.discount_online')}}</option>
						<option value="tong_hop" {{$discount->type=='tong_hop'?'selected':''}}>{{trans('Location'.DS.'user.discount_general')}}</option>
					</select>
				</div>
				<!-- end form-group -->
			</div>

			<div class="col-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.discount_content')}} <span class="text-danger">*</span></label>
					<select  name="content[]" class="form-control chosen-select" id="list_content_discount" multiple="" data-placeholder="{{trans('Location'.DS.'user.discount_content')}}">
						@foreach($contents as $content)
						<option value="{{$content->id}}" {{in_array($content->id,$arrContent)?'selected':''}}>{{$content->name}}</option>
						@endforeach
					</select>
				</div>
				<!-- end form-group -->
			</div>


			<div class="col-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.discount_phone')}}</label>
					<input type="text" value="{{$discount->phone}}" maxlength="15" name="discount_phone" class="form-control" placeholder="{{trans('Location'.DS.'user.discount_phone')}}">
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.discount_link')}}</label>
					<input type="text" value="{{$discount->link}}" name="discount_link" class="form-control" placeholder="{{trans('Location'.DS.'user.discount_link')}}">
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.discount_require')}} <span class="text-danger">*</span></label>
					<textarea class="form-control" id="discount_description" rows="5"  name="description" placeholder="{{trans('Location'.DS.'user.discount_require')}} <span class="text-danger">*</span>">{!! $discount->description !!}</textarea>
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.discount_from')}}</label>
					<input type="text" value="{{date('d-m-Y',strtotime($discount->date_from))}}" name="discount_from" class="choose_date form-control discount_from" placeholder="{{trans('Location'.DS.'user.discount_from')}}">
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-6">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.discount_to')}}</label>
					<input type="text" value="{{date('d-m-Y',strtotime($discount->date_to))}}" name="discount_to" class="choose_date form-control discount_to" placeholder="{{trans('Location'.DS.'user.discount_to')}}">
				</div>
				<!-- end form-group -->
			</div>
			<div class="col-12">
				<div class="form-group">
					<label>{{trans('Location'.DS.'user.discount_open')}}</label>
					<div class="row">
						<div class="col-12 row">
	                <div class="col-3"><label for="">{{trans('Admin'.DS.'content.from_date')}}</label></div>
	                <div class="col-3"><label for="">{{trans('Admin'.DS.'content.to_date')}}</label></div>
	                <div class="col-3"><label for="">{{trans('Admin'.DS.'content.from_hour')}}</label></div>
	                <div class="col-3"><label for="">{{trans('Admin'.DS.'content.to_hour')}}</label></div>
	          </div>
            @if($discount->_date_open)
            @foreach($discount->_date_open as $key => $date)
	          <div class="col-12 row">
	            <div class="col-3">
	              <select class="form-control" name="date_open[0][from_date]">
	                <option value="1" {{$date->date_from==1?'selected':''}}>{{trans('Admin'.DS.'content.monday')}}</option>
	                <option value="2" {{$date->date_from==2?'selected':''}}>{{trans('Admin'.DS.'content.tuesday')}}</option>
	                <option value="3" {{$date->date_from==3?'selected':''}}>{{trans('Admin'.DS.'content.wednesday')}}</option>
	                <option value="4" {{$date->date_from==4?'selected':''}}>{{trans('Admin'.DS.'content.thursday')}}</option>
	                <option value="5" {{$date->date_from==5?'selected':''}}>{{trans('Admin'.DS.'content.friday')}}</option>
	                <option value="6" {{$date->date_from==6?'selected':''}}>{{trans('Admin'.DS.'content.saturday')}}</option>
	                <option value="0" {{$date->date_from==0?'selected':''}}>{{trans('Admin'.DS.'content.sunday')}}</option>
	              </select>
	            </div>
	            <div class="col-3">
	              <select class="form-control" name="date_open[0][to_date]">
	                <option value="1" {{$date->date_to==1?'selected':''}}>{{trans('Admin'.DS.'content.monday')}}</option>
	                <option value="2" {{$date->date_to==2?'selected':''}}>{{trans('Admin'.DS.'content.tuesday')}}</option>
	                <option value="3" {{$date->date_to==3?'selected':''}}>{{trans('Admin'.DS.'content.wednesday')}}</option>
	                <option value="4" {{$date->date_to==4?'selected':''}}>{{trans('Admin'.DS.'content.thursday')}}</option>
	                <option value="5" {{$date->date_to==5?'selected':''}}>{{trans('Admin'.DS.'content.friday')}}</option>
	                <option value="6" {{$date->date_to==6?'selected':''}}>{{trans('Admin'.DS.'content.saturday')}}</option>
	                <option value="0" {{$date->date_to==0?'selected':''}}>{{trans('Admin'.DS.'content.sunday')}}</option>
	              </select>
	            </div>
	            <div class="col-3">
	              <input class="form-control choose_hour" type="text" name="date_open[0][from_hour]" value="{{$date->time_from}}">
	            </div>
	            <div class="col-3">
	              <input class="form-control choose_hour" type="text" name="date_open[0][to_hour]" value="{{$date->time_to}}">
	            </div>
              @if($key>0)
              <span><i class="remove_custom_open fa fa-remove" onclick="removeCustomOpen(this)"></i></span>
              @endif
	          </div>
            @endforeach
            @else
            <div class="col-12 row">
              <div class="col-3">
                <select class="form-control" name="date_open[0][from_date]">
                  <option value="1" selected>{{trans('Admin'.DS.'content.monday')}}</option>
                  <option value="2">{{trans('Admin'.DS.'content.tuesday')}}</option>
                  <option value="3">{{trans('Admin'.DS.'content.wednesday')}}</option>
                  <option value="4">{{trans('Admin'.DS.'content.thursday')}}</option>
                  <option value="5">{{trans('Admin'.DS.'content.friday')}}</option>
                  <option value="6">{{trans('Admin'.DS.'content.saturday')}}</option>
                  <option value="0">{{trans('Admin'.DS.'content.sunday')}}</option>
                </select>
              </div>
              <div class="col-3">
                <select class="form-control" name="date_open[0][to_date]">
                  <option value="1">{{trans('Admin'.DS.'content.monday')}}</option>
                  <option value="2">{{trans('Admin'.DS.'content.tuesday')}}</option>
                  <option value="3">{{trans('Admin'.DS.'content.wednesday')}}</option>
                  <option value="4">{{trans('Admin'.DS.'content.thursday')}}</option>
                  <option value="5">{{trans('Admin'.DS.'content.friday')}}</option>
                  <option value="6">{{trans('Admin'.DS.'content.saturday')}}</option>
                  <option value="0" selected>{{trans('Admin'.DS.'content.sunday')}}</option>
                </select>
              </div>
              <div class="col-3">
                <input class="form-control choose_hour" type="text" name="date_open[0][from_hour]" value="">
              </div>
              <div class="col-3">
                <input class="form-control choose_hour" type="text" name="date_open[0][to_hour]" value="">
              </div>
            </div>
            @endif
	          <div id="append_custom_open" class="w-100"></div>
	          <div class="col-12 text-center" id="add_custom_open">
	            <br/>
	            <button class="btn btn-default" type="button" onclick="addCustomOpen()">
	             {{trans('Location'.DS.'user.add_discount_open')}}
	            </button>
	            <br/>
	          </div>
					</div>
				</div><!-- end form-group -->
			</div><!-- end col-12 -->

			<div class="col-md-12">
				<div class="form-group">
				<label>{{trans('Location'.DS.'user.discount_image')}}</label>
				<br/>
        <label class="custom-control custom-radio">
          <input type="checkbox" onclick="showImageUpload()" name="img_from_content" id="img_from_content" class="custom-control-input" {{$discount->img_from_content?'checked':''}}>
          <span class="custom-control-description">{{trans('Location'.DS.'user.choose_image_image_from_content')}}</span>
          <span class="custom-control-indicator"></span>
        </label>			
				<div class="upload-placeholder"  id="discount_image_upload" style="{{$discount->img_from_content?'display:none':''}}" >
          <div class="upload-img-post" id="create_discount_image">
            <ul class="list-unstyled row">
              <li class="col-md-4 col-6">
                <div class="box-img-upload upload-begin upload-image-disabled">
                  <div class="box-img-upload-content">
                    <i class="icon-new-white"></i>
                    <p>{{trans('Location'.DS.'user.choose_image')}}</p>
                  </div>
                </div>
              </li>
              @if($discount->_images)
              @foreach($discount->_images as $image)
              <li class="col-md-4 col-6">
                <div class="box-img-upload box-img-upload-success">
                  <a class="remove-img" data-typename="create_discount_image" data-field="{{$image->id}}" data-filename="{{$image->link}}"><i class="icon-cancel"></i></a>
                  <img src="{{$image->link}}">
                </div>
              </li>
              @endforeach
              @endif
            </ul>
            <input style="visibility: hidden;" type="file" name="discount_image[]" multiple="" accept=".png, .jpg, .jpeg">
          </div>
        </div>
			</div>
			<div class="col-md-12 text-center">
				<button type="button" onclick="updateDiscount()" class="btn-submit btn btn-primary">{{trans('Location'.DS.'user.update_discount')}}</button>
				<!-- end form-group -->
			</div>
			<!-- <div class="col-md-12">
				<div class="notification-form">
					 <i class="icon-check-grey"></i> Auto save
				</div>
			</div> -->
		</div>
	</form>
</div>
<div id="modal-message" class="modal fade modal-submit-payment  modal-vertical-middle modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment modal-vertical-middle modal-vertical-middle" aria-hidden="true">
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
@include('Location.user.crop_image')
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
			$('.choose_hour').datetimepicker({
        format: 'HH:mm',
        defaultDate: moment().hours(8).minutes(0).seconds(0).milliseconds(0)
      });

      $('.discount_from').datetimepicker({
        format: 'DD-MM-Y',
        // defaultDate: moment(),
        minDate: moment('{{$discount->date_from}}').format()
      });

      $('.discount_to').datetimepicker({
        format: 'DD-MM-Y',
        // defaultDate: moment().add(1, 'd').format(),
        minDate: moment('{{$discount->date_to}}').format()
      });

      $("#list_content_discount").chosen({
      	placeholder:""
      });

      CKEDITOR.replace( 'discount_description',{
           language: '{{\App::getLocale()=='vn'?'vi':'en'}}'
      });
      $("#img_from_content").trigger("change");

      //$("#list_content_discount").val($("#list_content_discount option").first().prop('value')).trigger("change");
		})

    $('#update-discount-page .upload-img-post').on('click','.remove-img',function(event){
      event.preventDefault();
      var li_remo = this;
      var filename = $(this).attr('data-filename')?$(this).attr('data-filename'):'';
      var typename = $(this).attr('data-typename')?$(this).attr('data-typename'):'';
      var id = $(this).attr('data-field')?$(this).attr('data-field'):'';

      $.ajax({
        type: "POST",
        data: {id: id, _token: $("meta[name='_token']").prop('content')},
        url: '/discount/postDeleteImage',
        success: function (data) {
          if (data == 'sussess') {
            $(li_remo).closest('li').remove();
          }
        }
      });
    });
		function addCustomOpen() {
      var index = $(".item_custom_open").length;
      index++;

      html = '<div class="item_custom_open col-12 row">';
      html += '          <div class="col-3">';
      html += '            <select class="form-control" name="date_open[' + index + '][from_date]" id="">';
      html += '              <option value="1">{{trans('Admin'.DS.'content.monday')}}</option>';
      html += '              <option value="2">{{trans('Admin'.DS.'content.tuesday')}}</option>';
      html += '              <option value="3">{{trans('Admin'.DS.'content.wednesday')}}</option>';
      html += '              <option value="4">{{trans('Admin'.DS.'content.thursday')}}</option>';
      html += '              <option value="5">{{trans('Admin'.DS.'content.friday')}}</option>';
      html += '              <option value="6">{{trans('Admin'.DS.'content.saturday')}}</option>';
      html += '              <option value="0">{{trans('Admin'.DS.'content.sunday')}}</option>';
      html += '            </select>';
      html += '          </div>';
      html += '          <div class="col-3">';
      html += '            <select class="form-control" name="date_open[' + index + '][to_date]" id="">';
      html += '              <option value="1">{{trans('Admin'.DS.'content.monday')}}</option>';
      html += '              <option value="2">{{trans('Admin'.DS.'content.tuesday')}}</option>';
      html += '              <option value="3">{{trans('Admin'.DS.'content.wednesday')}}</option>';
      html += '              <option value="4">{{trans('Admin'.DS.'content.thursday')}}</option>';
      html += '              <option value="5">{{trans('Admin'.DS.'content.friday')}}</option>';
      html += '              <option value="6">{{trans('Admin'.DS.'content.saturday')}}</option>';
      html += '              <option value="0">{{trans('Admin'.DS.'content.sunday')}}</option>';
      html += '            </select>';
      html += '          </div>';
      html += '          <div class="col-3">';
      html += '            <input class="form-control choose_hour" type="text" name="date_open[' + index + '][from_hour]" value="" >';
      html += '          </div>';
      html += '          <div class="col-3">';
      html += '            <input class="form-control choose_hour" type="text" name="date_open[' + index + '][to_hour]" value="" >';
      html += '          </div>';
      html += '  <span><i class="remove_custom_open fa fa-remove" onclick="removeCustomOpen(this)"></i></span></div>';
      $("#append_custom_open").append(html);

      $('.choose_hour').datetimepicker({
        format: 'HH:mm',
        defaultDate: moment().hours(8).minutes(0).seconds(0).milliseconds(0)
      });
    }

    function removeCustomOpen(obj) {
      $(obj).parent().parent().remove();
    }

    function updateDiscount(){
    	var form = $('#form-create-discount')[0];
    	var dataPost = new FormData(form);
      dataPost.append('_token', $("meta[name='_token']").prop('content'));
      if(editAvatar !== undefined)
      {
        dataPost.append('avatar', editAvatar);
      }
      for (var i = 0; i < create_discount_image.length; i++) {
        dataPost.append('discount_image[' + i + ']', create_discount_image[i]);
      }

      dataPost.append('description', CKEDITOR.instances['discount_description'].getData());
      dataPost.append('img_from_content', $("#img_from_content").is(":checked")?1:0);

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
        		$("#message").text(data.message);
            $("#modal-message").modal();
            $("#link_modal").attr('href',"{{route('list-discount',['id_user'=>$user->id])}}");
            $("#link_modal").attr('data-dismiss',"");
        	}else{
        		// $("#form-create-discount").get(0).reset();
        		$("#message").text(data.message);
            $("#modal-message").modal();
        	}
        }
      })
    }

    function showImageUpload(){
    	$("#discount_image_upload").toggle('fast');

    }
	</script>
@endsection

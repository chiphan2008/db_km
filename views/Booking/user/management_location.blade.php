<div class="content-edit-profile-manager">
	<h3>{{mb_strtoupper(trans('Location'.DS.'user.management_location'))}} ({{$total}})</h3>
	<div class="list-content-profile  ">
		<ul class="row list-unstyled">
			@if($contents)
				@foreach($contents as $content)
					<li class="col-lg-4 col-6">
						<div class="card-vertical card">
							<div class="card-img-top">
								@if($content->moderation == 'publish')
									<a href="{{url($content->alias)}}" title="{{$content->name}}">
										<img class="img-fluid" src="{{str_replace('img_content','img_content_thumbnail',$content->avatar)}}" alt="{{$content->name}}">
									</a>
								@else
									<img class="img-fluid" src="{{str_replace('img_content','img_content_thumbnail',$content->avatar)}}" alt="{{$content->name}}">
								@endif

								@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
									<div class="dropdown">
										<a class="btn btn-secondary" href="" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<i class="icon-more"></i>
										</a>
										<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
											<a class="dropdown-item" title="Edit" href="{{url('edit/location/'.$content->id)}}">Edit</a>
											@if($content->moderation == 'publish')
												<a class="dropdown-item" title="Dừng" href="{{url('changeStatusClose/location/'.$content->id)}}">Tạm Dừng</a>
												<a class="dropdown-item" data-toggle="modal" data-target="#modal-submit-push" class="dropdown-item" title="Quảng cáo địa điểm" onclick="popupContent('{{$content->name}}',{{$content->id}})" href="#">Quảng cáo địa điểm</a>
												@if($content->view_ad==0)
												<a class="dropdown-item" id="ad_keywords_button{{$content->id}}" data-toggle="modal" data-target="#modal-submit-ad" class="dropdown-item" title="Quảng cáo từ khóa" onclick="popupAdContent('{{$content->name}}',{{$content->id}},'{{$content->keyword_ad?$content->keyword_ad:$content->tag}}')" href="#">Quảng cáo từ khóa</a>
												@endif
												<a class="dropdown-item" href="#" data-toggle="modal" onclick="popupNotifyContent({{$content->id}})" data-target="#modal-notification">Thông báo</a>
											@endif
											@if($content->moderation == 'un_publish')
												<a class="dropdown-item" title="Mở" href="{{url('changeStatusOpen/location/'.$content->id)}}">Mở Lại</a>
											@endif
											{{--<a class="dropdown-item" href="{{url('delete/location/'.$content->id)}}">Delete</a>--}}
											<a data-toggle="modal" data-target="#modal-submit-payment" class="dropdown-item" href="" onclick='deleteContentPageEdit("{{$content->name}}","{{url('delete/location/'.$content->id)}}")'>Delete</a>
										</div>
									</div>
								@endif
							</div>
							<div class="card-block py-2 px-0">
								<div class="card-description">
									<h6 class="card-title ">
										@if($content->moderation == 'publish')
											<a href="{{url($content->alias)}}" title="">{{$content->name}}</a>
										@else
											{{$content->name}}
										@endif
									</h6>
									<p class="card-address text-truncate">{{$content->address}}, {{$content->_district->name}}, {{$content->_city->name}}, {{$content->_country->name}}</p>
								</div>
								<div class="meta-post d-flex align-items-center">

									<div class="meta-post d-flex align-items-center">
										<div class="rating d-flex align-items-center">
											<i class="icon-star-yellow"></i>
											<span>({{$content->vote?$content->vote:0}})</span>
										</div>
										<!-- end rating -->
										@if($content->moderation == 'publish')
											<div class="meta-post-status meta-status-open">
												<i class="icon-circle"></i>
												{{trans('Location'.DS.'user.opening')}}
											</div>
										@elseif($content->moderation == 'un_publish')
											<div class="meta-post-status meta-status-close">
												<i class="icon-circle"></i>
												{{trans('Location'.DS.'user.closing')}}
											</div>
										@else
											<div class="meta-post-status meta-status-inspection">
												<i class="icon-circle"></i>
												{{trans('Location'.DS.'user.pending')}}
											</div>
										@endif
									</div>
									<!-- end rating -->
								</div>
							</div>
						</div>
						<!-- end card post -->
					</li>
				@endforeach
			@endif
		</ul>
		<div id="modal-submit-payment" class="modal fade modal-submit-payment  modal-vertical-middle modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content text-center">
					<div class="modal-logo pt-4 text-center">
						<img src="/frontend/assets/img/logo/logo-large.svg" alt="">
					</div>
					<h4>XÁC NHẬN XÓA!</h4>
					<hr>
					<p id="name_delete"></p>
					<div class="modal-button justify-content-between">
						<a class="btn btn-secorady" href="#" data-dismiss="modal">Hủy</a>
						<a class="btn btn-primary" id="link_delete" href="#">Xác Nhận</a>
					</div>
				</div>
			</div>
		</div>

		<div id="modal-submit-push" class="modal fade modal-submit-payment  modal-vertical-middle modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content text-center">
					<div class="modal-logo pt-4 text-center">
						<img src="/frontend/assets/img/logo/logo-large.svg" alt="">
					</div>
					<h4>XÁC NHẬN</h4>
					<hr>
					<p id="text_push"></p>
					<div class="modal-button justify-content-between">
						<a class="btn btn-secorady" href="#" data-dismiss="modal">Hủy</a>
						<a class="btn btn-primary" onclick="pushContent(this)" id="data_push" data-id="" href="#">Xác Nhận</a>
					</div>
				</div>
			</div>
		</div>


		<div class="modal fade  modal-vertical-middle modal-report" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
			<div class="modal-dialog modal-md" style="max-width: 540px;">
				<div class="modal-content p-4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<!-- close -->
					<div class="modal-logo pt-4 text-center">
						<img src="/frontend/assets/img/logo/logo-large.svg" alt="">
					</div>
					<!-- end logo -->
					<h4 class="text-uppercase text-center">Thông báo</h4>
					<hr>
					<form id="popupNotifyContent" class="form-notification-locaiton" method="post" action="{{route('postPopupNotifyContent')}}">
						{{ csrf_field() }}
						<input type="hidden" name="id_content" id="id_content" value="">
						<div class="form-group row">
							<label for="title" class="col-sm-3 col-form-label">Tiêu đề</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="title" name="title" required>
							</div>
						</div>
						<!-- end  form group -->
						<div class="form-group row">
							<label for="description" class="col-sm-3 col-form-label">Mô tả</label>
							<div class="col-sm-9">
								<textarea class="form-control" id="except" name="except" rows="5" style="height:auto;" required></textarea>
							</div>
						</div>
						<!-- end  form group -->
						
						
						<div class="form-group row">
							<label for="description" class="col-sm-3 col-form-label">Thời gian</label>
							<div class="col-sm-9">
								<div class="row">
									<div class="col-sm-6 mb-2 mb-sm-0">
										<input type="text" placeholder="dd-mm-yyyy" name="time_start" id="time-start" class="form-control" autocomplete="off" required>
									</div>
									<div class="col-sm-6">
										<input type="text" placeholder="dd-mm-yyyy" name="time_end" id="time-end" class="form-control" autocomplete="off" required>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label for="description" class="col-sm-3 col-form-label">Tắt mở</label>
							<div class="col-sm-9">
								<input type="checkbox" id="active_notify_content" name="active" value="active"/>
							</div>
						</div>
						<!-- end  form group -->
						<div class="modal-button d-sm-flex justify-content-sm-between mt-4">
							<a class="btn btn-secorady" href="">Hủy</a>
							<button type="submit" class="btn-sin btn btn-primary d-block"  >Gửi</button>
						</div>
						<!-- end  report modal -->
					</form>
					<!-- end  form nitification location -->
				</div>
				<!-- end  modal content -->
			</div>
		</div>

		<div id="modal-submit-ad" class="modal fade modal-submit-payment  modal-vertical-middle modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
			<div class="modal-dialog modal-lg" style="max-width: 640px;">
				<div class="modal-content text-center p4">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<!-- close -->
					<div class="modal-logo pt-4 text-center">
						<img src="/frontend/assets/img/logo/logo-large.svg" alt="">
					</div>
					<!-- end logo -->
					<h4 class="text-uppercase text-center" id="name_content"></h4>
					<hr>
					<div class="form-group row">
						<label for="title" class="col-sm-3 col-form-label">Số tiền</label>
						<div class="col-sm-9">
							<div class="input-group">
								<input type="number" class="form-control" name="coin_ad" value="1" min="1" max="2000000000" id="coin_ad" required>
								<span class="input-group-addon">K</span>
							</div>
						</div>
					</div>
					<!-- end  form group -->
					<div class="form-group row">
						<label for="description" class="col-sm-3 col-form-label">{{trans('global.keyword')}}</label>
						<div class="col-sm-9 box-hastag box_keyword_ad">
							<select name="keyword_ad" id="keyword_ad" multiple="true" name="keyword_ad[]" required>
							</select>
							<!-- <textarea class="form-control"  rows="5" style="height:auto;"></textarea> -->
						</div>
					</div>
					<p style="color: rgb(217, 83, 79);" id="error_ad"></p>
					<!-- end  form group -->
					<div class="modal-button justify-content-sm-between mt-4">
						<a class="btn btn-secorady" href="" data-dismiss="modal">Hủy</a>
						<button class="btn btn-primary" onclick="adContent(this)" data-id="" id="data_ad">Xác Nhận</button>
					</div>

					<!-- end  report modal -->
				</div>
				<!-- end  modal content -->
			</div>
		</div>

		@if($contents)
			<div class="col-sm-12">
				{!! $contents->appends(request()->query())->links('vendor.pagination.bootstrap-4') !!}
			</div>
		@endif
	</div>
</div>
@section('CSS')
<style type="text/css" media="screen">
	.box_keyword_ad .select2{
		height: 200px;
	}
</style>
@endsection

@section('JS')
	@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
		@include('Location.user.crop_image')
	@endif
	<script type="text/javascript">
		$(function(){
			$("input[name='coin_ad']").on("keypress",function(e){
				return (e.charCode >= 48 && e.charCode <= 57) || e.charCode == 43;
			})
			$("input[name='coin_ad']").on("keyup",function(e){
				if($("input[name='coin_ad']").val() > 2000000000){
					$("input[name='coin_ad']").val(2000000000);
				}

				if($("input[name='coin_ad']").val() < 1){
					$("input[name='coin_ad']").val(1);
				}
			});

			$("#keyword_ad").select2({
				tags: true,
				placeholder: "{{trans('global.keyword')}}",
				tokenSeparators: ['/',',',';'],
				maximumSelectionLength: 10
			}).on("change",function(e){
					if($(this).val().length>10){
							$(this).val($(this).val().slice(0,10));
							alert("Chỉ được chọn tối đa 10 từ khóa");
					}
			});

			$( "#time-start" ).datepicker({
				dateFormat: "dd-mm-yy"
			});

			$( "#time-end" ).datepicker({
				dateFormat: "dd-mm-yy"
			});
		})

		function deleteContentPageEdit(name,link)
		{
			$('#name_delete').text(' Bạn có chắc chắn muốn xóa địa điểm này : ' + name);
			$('#link_delete').attr('href', link);
		}

		function popupContent(name, id){
			$("#text_push").text(' Bạn có chắc chắn muốn quảng cáo địa điểm này : ' + name);
			$("#data_push").attr('data-id',id);
		}
		function popupAdContent(name, id, keyword){
			$("#name_content").text('Quảng cáo địa điểm: '+name);
			$("#data_ad").attr('data-id',id);
			var html = '';
			keyword = keyword.split(',');
			for(var i=0; i<keyword.length; i++){
				if(i<10){
					html+='<option value="'+keyword[i]+'" selected>'+keyword[i]+'</option>';
				}
			}
			$("#keyword_ad").html(html);
		}

		function pushContent(obj){
			var id = $(obj).attr('data-id');
			$.ajax({
				url     : '/push-content/'+id,
				type    : 'GET',
				success : function(response){
					$("#modal-submit-push").modal('hide');
					if (typeof response === 'string' || response instanceof String)
							response = JSON.parse(response);
					if(response.error==1){
						toastr.error(response.message,"Error");
					}else{
						toastr.success(response.message,"Message");
					}
				}
			});
		}


		function adContent(obj){
			var id = $(obj).attr('data-id');
			if($("#coin_ad").get(0).checkValidity() && $("#keyword_ad").get(0).checkValidity()){
				$.ajax({
					url     : '/ad-content',
					type    : 'POST',
					data    : {
						'id'          :   id,
						'_token'      :   $("meta[name='_token']").prop('content'),
						'coin'        :   $("#coin_ad").val(),
						'keyword'     :   $("#keyword_ad").val(),
					},
					success : function(response){
						if (typeof response === 'string' || response instanceof String)
							response = JSON.parse(response);
						if(response.error==1){
							$("#error_ad").text(response.message);
						}else{
							toastr.success(response.message,"Message",{ timeOut: 9500 });
							$("#modal-submit-ad").modal('hide');
							$("#ad_keywords_button"+id).remove();
						}
					}
				});
			}else{
				if(!$("#coin_ad").get(0).checkValidity()){
					$("#error_ad").text('Vui lòng nhập số tiền');
					return false;
				}
				if(!$("#keyword_ad").get(0).checkValidity()){
					$("#error_ad").text('Vui lòng nhập từ khóa');
					return false;
				}
			}
			
		}

		function popupNotifyContent(id_content){
			$.ajax({
				type: "POST",
				data: {id_content: id_content, _token: $("meta[name='_token']").prop('content')},
				url: '/popupNotifyContent',
				success: function (data) {
					$('#id_content').val(id_content);
					if(data.mess === true)
					{
						var obj = data.data;
						$('#title').val(obj.title);
						$('#except').val(obj.description);
						$('#time-start').val(obj.start.slice(0,10));
						$('#time-end').val(obj.end.slice(0,10));
						if(obj.active == 1)
						{
							$('#active_notify_content').prop('checked', true);
						}
					}
					else{
						$('#title').val('');
						$('#except').val('');
						$('#time-start').val('');
						$('#time-end').val('');
						$('#active_notify_content').prop('checked', false);
					}
				}
			})
		}
	</script>
@endsection
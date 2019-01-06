<div class="content-edit-profile-manager">
	<h3>{{mb_strtoupper(trans('Admin'.DS.'content.owner_change'))}}</h3>
	<div class="">
		<form id="form_change_owner" method="post" action="/user/{{$user->id}}/change-owner" enctype="multipart/form-data" autocomplete="off" data-parsley-validate="" class="form-label-left" novalidate="">
			{{ csrf_field() }}
			<div class="form-group">
				<label class="" for="content">{{trans('global.locations')}} <span class="text-danger">*</span></label>       
				<select class="form-control" name="content[]" id="content" multiple >
				</select>
				<div id="test_con"></div>
			</div>

			<div class="form-group">
				<label class="" for="content">{{trans('global.user')}} <span class="text-danger">*</span></label>
				<select class="form-control"  name="user" id="user">
				</select>
			</div>

			<div class="form-group text-center">
				<button type="button" class="btn btn-primary" onclick="modalChangeOwner()">{{trans('Admin'.DS.'content.owner_change')}}</button>
			</div>
		</form>
	</div>
</div>
<div id="modal-update-success" class="modal modal-vertical-middle fade modal-submit-payment  modal-vertical-middle modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment modal-vertical-middle modal-vertical-middle" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-logo pt-4 text-center">
				<img src="/frontend/assets/img/logo/logo-large.svg" alt="">
			</div>
			<h4>&nbsp;</h4>
			<p class="text_1 text-center" id="message"></p>
			<div class="modal-button d-flex justify-content-center">
				<a class="btn btn-secorady" data-dismiss="modal">{{trans('global.close')}}</a>
			</div>
		</div>
	</div>
</div>

<div id="modal-submit-push" class="modal fade modal-submit-payment  modal-vertical-middle modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment modal-vertical-middle modal-vertical-middle" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-logo pt-4 text-center">
				<img src="/frontend/assets/img/logo/logo-large.svg" alt="">
			</div>
			<h4>{{trans('Location'.DS.'user.change_owner')}}</h4>
			<hr>
			<p id="text_push"></p>
			<div class="modal-button justify-content-between d-flex">
				<div class="col-6">
					<a class="btn btn-secorady" href="#" data-dismiss="modal">{{trans('Location'.DS.'user.cancel')}}</a>
				</div>
				<div class="col-6">
					<a class="btn btn-primary" onclick="submitChangeOwner()" id="data_push" data-id="" href="#">
					{{trans('Location'.DS.'user.confirm')}}</a>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.select2-choices,.select2-selection__choice{
		display:-webkit-inline-box;
		width: 100%;
		background: #FFF !important;
	}
	.select2-selection__arrow {
		height: 34px !important;
	}
	.select2-selection__rendered {
		line-height: 32px !important;
	}

	.select2-selection--single {
		height: 36px !important;
	}
	.select2-search__field {
		min-width: auto !important;
	}
	.select2-container .select2-search--inline{
		width: 100%;
	}

/*	#select2-content-results{
		max-height: 480px !important;
	}*/
	#form_change_owner .select2-container--default .select2-selection--multiple .select2-selection__rendered{
		border: none;
		box-shadow: none;
	}

	#form_change_owner .select2-container--default .select2-selection--multiple{
		border: none;
	}

	#form_change_owner .select2-selection__rendered.ui-autocomplete-owner{
		padding: 0;
		max-height: 259px;
		overflow-y: auto;
		margin-bottom: 40px;
	}

	#form_change_owner .select2-search.select2-search--inline{
		border: 1px solid #aaa; 
		padding-left: 8px;
		border-radius: 5px;
		margin-top: 10px;
		position: absolute;
		bottom: 0;
	}
	#form_change_owner .select2-search.select2-search--inline input{
		margin-top: 0;
		line-height: 36px;
	}
	#test_con{
		position: relative;
	}
	#test_con .select2-container{
		top:-3px !important;
	}
	#form_change_owner .ui-autocomplete-owner .ui-menu-item .content-search p{
			overflow: hidden;
			margin-bottom: 0;
			white-space: nowrap;
			text-overflow: ellipsis;
			color: #5b89ab;
	}
	#form_change_owner .select2-selection__choice .content-search{
		display: block;
		width: 94%;
	}
</style>
@section('JS')
 	@if(\App::getLocale() == "vn")
	<script src="/frontend/vendor/select2/vi.js"></script>
	@endif
	<script type="text/javascript">
		$(function () {
			var scrollTop;
			$('#content').on("select2:selecting", function( event ){
			    var $pr = $( '#'+event.params.args.data._resultId ).parent();
			    scrollTop = $pr.prop('scrollTop');
			});
			$('#content').on("select2:select", function( event ){
			    var $pr = $( '#'+event.params.data._resultId ).parent();
			    $pr.prop('scrollTop', scrollTop );
			});

			$("#content").select2({
				ajax:{
					url       : "/searchContent",
					type      : "GET",
					delay     : 800,
					dataType  :'json',
					data : function(params){
						var query = {
							query: params.term,
							id_user: {{$user->id?$user->id:0}}
						}
						return query;
					},
					cache:true
				},
				minimumInputLength: 1,
				placeholder: "{{trans('Admin'.DS.'content.input_content')}}",
				templateResult: formatDataContent,
				templateSelection: formatDataContent,
				dropdownParent: $('#test_con'),
				escapeMarkup: function (m) {
					return m;
				},
				language: "vi",
				closeOnSelect: false
			}).data('select2').$dropdown.find("ul").addClass("ui-autocomplete-owner");

			$("#user").select2({
				ajax:{
					url       : "/searchUser",
					type      : "GET",
					delay     : 800,
					dataType  :'json',
					data : function(params){
						var query = {
							query: params.term,
							id_user: {{$user->id?$user->id:0}}
						}
						return query;
					},
					cache:true
					// processResults: function (data) {
					//   return {
					//     results: data.items
					//   };
					// }
				},
				templateResult: formatData,
				templateSelection: formatData,
				escapeMarkup: function (m) {
					return m;
				},
				minimumInputLength: 1,
				placeholder: "{{trans('Admin'.DS.'content.input_user')}}",
				language: "vi",
				closeOnSelect: true
			})
			setTimeout(function(){
				$("#content").next().find(".select2-selection__rendered").addClass("ui-autocomplete-owner");
				// /$('#content').select2().data('select2').$dropdown.find("ul").addClass("ui-autocomplete-owner");

				// .addClass("ui-autocomplete-owner");
			},100)
			// $("#content").next().find(".select2-selection__rendered").addClass("ui-autocomplete-owner");
			// $("ul#select2-content-results").addClass("ui-autocomplete-owner");

		})

		function formatData (option) {
			if (!option.id) { return option.text; }
			var ob = '<img width="28" height="28" src="'+ option.avatar +'" />&nbsp;&nbsp;&nbsp;' + option.text; // replace image source with option.img (available in JSON)
			return ob;
		};
		function formatDataContent (option,container) {
			if (!option.id) { return option.text; }
			$(container).addClass('item-location ui-menu-item')
			var ob = '';
			ob+='<div class="img ui-menu-item-wrapper">';
			ob+='			<img style="width:85px;" src="'+option.avatar+'" alt="'+option.text+'">';
			ob+='		</div>';
	 		ob+='		<div class="content-search ui-menu-item-wrapper">';
			ob+='	 		<h3>'+option.text+'</h3>';
			ob+='	 		<p>'+option.address+'</p>';
	 		ob+='		</div>'
			return ob;
		};
		function modalChangeOwner(){
			var content = $("#content").val();
			var user = $("#user").val();
			if(content.length==0){
				$("#message").text('{{trans('valid.content_required')}}');
				$("#modal-update-success").modal();
				return false;
			}
			if(!user){
				$("#message").text('{{trans('valid.user_required')}}');
				$("#modal-update-success").modal();
				return false;
			}
			$("#modal-submit-push").modal();
		}
		function submitChangeOwner(){
			$("#modal-submit-push").modal("hide");
			var content = $("#content").val();
			var user = $("#user").val();
			if(content.length==0){
				$("#message").text('{{trans('valid.content_required')}}');
				$("#modal-update-success").modal();
				return false;
			}
			if(!user){
				$("#message").text('{{trans('valid.user_required')}}');
				$("#modal-update-success").modal();
				return false;
			}

			$.ajax({
				url : "/user/{{$user->id}}/change-owner",
				type: "POST",
				dataType: "json",
				data:{
					'_token': $("[name='_token']").prop('content'),
					content: content,
					user: user
				},
				beforeSend(){
					$("#loading").show();
				},
				success: function(response){
					$("#loading").hide();
					//response = JSON.parse(response); 
					if(response.error){
						$("#message").text(response.message);
						$("#modal-update-success").modal();
					}else{
						$("#message").text(response.message);
						$("#modal-update-success").modal();
						$('#form_change_owner select').val(null).trigger("change");
					}
				}
			})
		}
	</script>
	@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
		@include('Location.user.crop_image')
	@endif
@endsection

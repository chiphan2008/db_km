<div class="content-edit-profile-manager">
	<h3>{{mb_strtoupper(trans('Location'.DS.'user.like_location'))}} (<t id="save_like_content_total">{{$total}}</t>)</h3>
	<div class="list-content-profile">
		<ul class="row list-unstyled">
			@if($contents)
			@foreach($contents as $content)
				<li class="col-lg-4 col-6" id="item_{{$content->id}}">
					<div class="card-vertical card">
						<div class="card-img-top">
							<a href="{{url('/')}}/{{$content->alias}}" alt="{{$content->name}}">
								<img class="img-fluid" src="{{str_replace('img_content','img_content_thumbnail',$content->avatar)}}" alt="{{$content->name}}">
							</a>
							@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
							<div class="dropdown">
								<a class="btn btn-secondary" href="#" data-toggle="modal" data-target="#modal-submit-payment" class="dropdown-item" href="" onclick="modalLikeContent({{$content->id}},{{Auth::guard('web_client')->user()->id}})">
									<i class="fa fa-remove"></i>
								</a>
							</div>
							@endif
						</div>
						<div class="card-block py-2 px-0">
							<div class="card-description">
								<h6 class="card-title "><a href="{{url('/')}}/{{$content->alias}}" title="{{$content->name}}">{{$content->name}}</a></h6>
								<p class="card-address text-truncate">{{$content->address}}, {{$content->_district?$content->_district->name:''}}, {{$content->_city?$content->_city->name:''}}, {{$content->_country?$content->_country->name:''}}</p>
							</div>
							<div class="meta-post d-flex align-items-center">
								<div class="add-like d-flex align-items-center">
									<i class="icon-heart-empty"></i>
									<span>({{$content->like?$content->like:0}})</span>
								</div>
								<div class="rating d-flex align-items-center">
								  <div class="star-rating hidden-xs-down">
									<span style="width:{{$content->vote?($content->vote*100)/5:0}}%"></span>
								  </div>
								  <i class="icon-star-yellow hidden-sm-up"></i>
								  <span>({{$content->vote?$content->vote:0}})</span>
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
		@if($contents)
		<div class="col-sm-12">
			{!! $contents->appends(request()->query())->links('vendor.pagination.bootstrap-4') !!}
		</div>
		@endif
	</div>
</div>
<div id="modal-submit-payment" class="modal fade modal-submit-payment  modal-vertical-middle modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment modal-vertical-middle modal-vertical-middle" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-logo pt-4 text-center">
				<img src="/frontend/assets/img/logo/logo-large.svg" alt="">
			</div>
			<h4>{{trans('Location'.DS.'user.delete_confirm')}}</h4>
			<hr>
			<p id="name_delete"></p>
			<div class="modal-button justify-content-between">
				<a class="btn btn-secorady" href="#" data-dismiss="modal">{{trans('Location'.DS.'user.cancel')}}</a>
				<a class="btn btn-primary" id="link_delete" href="#">{{trans('Location'.DS.'user.confirm')}}</a>
			</div>
		</div>
	</div>
</div>

<div id="modal-update-success" class="modal fade modal-submit-payment  modal-vertical-middle modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment modal-vertical-middle modal-vertical-middle" aria-hidden="true">
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

@section('JS')
	@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
		@include('Location.user.crop_image')
	@endif
	<script>
	 function modalLikeContent(id_content, id_user){
	 	$("#link_delete").attr('id_content',id_content);
	 	$("#link_delete").attr('id_user',id_user);
	 }
	 function saveLikeContent(id_content, id_user) {
      if (id_user === undefined) {
        $('#modal-signin').modal('show');
        return false;
      }
      var old_checkin = parseInt($("#save_like_content_total").text());
      $.ajax({
        type: "POST",
        data: {id_content: id_content, id_user: id_user, _token: $('meta[name="_token"]').attr('content')},
        url: 	"{{url('/')}}" + '/save-like-content',
        success: function (response) {
          if (response.mess == true) {
            $("#save_like_content_total").text(old_checkin-1);
            // if (old_checkin > response.value) {
              $("#item_"+id_content).remove();
              $("#message").text("{{trans('Location'.DS.'content.have_unsaved')}}");
							$("#modal-update-success").modal();
							$("#modal-submit-payment").modal('hide');
            // }
          }
        }
      })
   }

   $(function(){
   	$("#link_delete").on('click',function(e){
   		e.preventDefault();
   		var id_content = $(this).attr('id_content');
   		var id_user = $(this).attr('id_user');
   		saveLikeContent(id_content, id_user)
   	})
   })
	</script>
@endsection
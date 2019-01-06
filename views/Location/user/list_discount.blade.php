<div class="content-edit-profile-manager">
	@if(count($discounts))
	<h3>{{ mb_strtoupper(trans('Location'.DS.'user.list_discount')) }}</h3>
	@endif
	<!-- Nav tabs -->
	<div class="row">
		<div class="col-12">
			@if(count($discounts))
			<table class="payment-history-table table table-striped ">
				<thead>
				<tr>
					<th  class="text-truncate">{{ trans('Location'.DS.'user.discount_name') }}</th>
					<th  class="text-truncate">{{ trans('Location'.DS.'user.discount_content') }}</th>
					<th  class="text-truncate">{{ trans('Location'.DS.'user.created_at') }}</th>
					<th  class="text-truncate">{{ trans('global.status') }}</th>
					<th  class="text-truncate" style="width: 230px">{{ trans('global.action') }}</th>
				</tr>
				</thead>
				<tbody>
					@if($discounts)
					@foreach($discounts as $discount)
					<tr>
						<th scope="row">{{$discount->name}}</th>
						<th scope="row">{{$discount->_base_content?$discount->_base_content->name:''}}</th>
						<td>{{date('d-m-Y H:m:i', strtotime($discount->created_at))}}</td>
						<td>
							@if(strtotime($discount->date_from)<= time() && strtotime($discount->date_to)>= time())
								@if($discount->active)
								<b class="text-success">{{ trans('global.active') }}</b>
								@else
								<b class="text-danger">{{ trans('global.inactive') }}</b>
								@endif
							@else
								@if(strtotime($discount->date_from) >= time())
									<b class="text-info">{{ trans('Location'.DS.'user.not_yet_date') }}</b>
								@endif

								@if(strtotime($discount->date_to) <= time())
									<b class="text-warning">{{ trans('Location'.DS.'user.end_date') }}</b>
								@endif
							@endif
						</td>
						<td>
								<a class="btn btn-primary" href="{{route('update-discount',['id_user'=>$user->id,'id_discount'=>$discount->id])}}">{{trans('global.update')}}</a>
								&nbsp;&nbsp;&nbsp;
								<a class="btn btn-primary" href="#" onclick="deleteDiscount({{$discount->id}},'{{$discount->name}}')">{{trans('global.delete')}}</a>
						</td>
					</tr>
					@endforeach
					@endif
				</tbody>
			</table>
			@else
			<div class="w-100 text-center pt-4">
				<a href="{{route('create-discount',['id_user'=>$user->id])}}" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> {{trans('Location'.DS.'user.create_discount')}}</a>
			</div>
			@endif
		</div>
	</div>
</div>
<div id="modal-message" class="modal modal-vertical-middle fade modal-submit-payment  modal-vertical-middle modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment modal-vertical-middle modal-vertical-middle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content text-center">
      <div class="modal-logo pt-4 text-center">
        <img src="/frontend/assets/img/logo/logo-large.svg" alt="">
      </div>
      <h4>&nbsp;</h4>
      <p class="text_1 text-center" id="message">{{trans('valid.confirm_delete')}}</p>
      <div class="modal-button justify-content-between">
      	<a class="btn btn-primary"  id="link_modal">{{trans('global.ok')}}</a>
        <a class="btn btn-secorady" data-dismiss="modal">{{trans('global.cancel')}}</a>
      </div>
    </div>
  </div>
</div>
@section('JS')
	@if(Auth::guard('web_client')->user())
		@include('Location.user.crop_image')
	@endif
	<script>
		function deleteDiscount(id){
			$("#link_modal").attr('href',"/user/{{$user->id}}/delete-discount/"+id);
			$("#modal-message").modal();
		}
	</script>
@endsection
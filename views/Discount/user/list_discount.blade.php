<div class="content-edit-profile-manager">
	<h3>{{ mb_strtoupper(trans('Location'.DS.'user.list_discount')) }}</h3>
	<!-- Nav tabs -->
	<div class="row">
		<div class="col-12">
			<table class="payment-history-table table table-striped ">
				<thead>
				<tr>
					<th  class="text-truncate">{{ trans('Location'.DS.'user.discount_name') }}</th>
					<th  class="text-truncate">{{ trans('Location'.DS.'user.created_at') }}</th>
					<th  class="text-truncate" style="width: 200px">{{ trans('global.action') }}</th>
				</tr>
				</thead>
				<tbody>
					@if($discounts)
					@foreach($discounts as $discount)
					<tr>
						<th scope="row">{{$discount->name}}</th>
						<td>{{date('d-m-Y H:m:i', strtotime($discount->created_at))}}</td>
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
		</div>
	</div>
</div>
<div id="modal-message" class="modal fade modal-submit-payment  modal-vertical-middle modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment modal-vertical-middle modal-vertical-middle" aria-hidden="true">
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
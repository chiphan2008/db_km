<div class="content-edit-profile-manager">
	@if(count($ads))
	<h3>{{ mb_strtoupper(trans('Location'.DS.'user.list_ads')) }}</h3>
	@endif
	<!-- Nav tabs -->
	<div class="row">
		<div class="col-12">
			@if(count($ads))
			<table class="payment-history-table table table-striped ">
				<thead>
				<tr>
					<th  class="text-truncate">{{ trans('Location'.DS.'user.ads_content') }}</th>
					<th  class="text-truncate">{{ trans('Location'.DS.'user.ads_type') }}</th>
					<th  class="text-truncate">{{ trans('Location'.DS.'user.price') }}</th>
					<th  class="text-truncate">{{ trans('Location'.DS.'user.total') }}</th>
					<th  class="text-truncate">{{ trans('global.status') }}</th>
					<th  class="text-truncate" style="width: 250px">{{ trans('global.action') }}</th>
				</tr>
				</thead>
				<tbody>
					@if($ads)
					@foreach($ads as $ad)
					<tr>
						<th scope="row">{{$ad->_base_content->name}}</th>
						<th scope="row">{{$ad->_type_ads->name}}</th>
						<!-- <td>{{date('d-m-Y H:m:i', strtotime($ad->created_at))}}</td> -->
						<td>{{money_number($ad->price)}}K</td>
						<td>{{money_number($ad->total)}}K</td>
						<td>
							@if($ad->approved == 0 && $ad->declined == 0)
									<b class="text-warning">{{ trans('Location'.DS.'user.pending') }}</b>
							@elseif($ad->declined)
									<b class="text-warning" data-toggle="tooltip" title="{!! $ad->declined_content !!}">{{ trans('Admin'.DS.'ads.declined') }}</b>
							@else
								@if($ad->active)
									@if(
									($ad->viewed == $ad->view && $ad->type_apply=='view') ||
									($ad->clicked == $ad->click && $ad->type_apply=='click') ||
									(strtotime($ad->date_to) < time() && $ad->type_apply=='date')
									)
									<b class="text-warning">{{ trans('Location'.DS.'user.end_date') }}</b>
									@elseif(strtotime($ad->date_from) > time() && $ad->type_apply=='date')
									<b class="text-info">{{ trans('Location'.DS.'user.not_yet_date') }}</b>
									@else
									<b class="text-success">{{ trans('global.active') }}</b>
									@endif
								@else
								<b class="text-danger">{{ trans('global.inactive') }}</b>
								@endif
							@endif


							
						</td>
						<td>
							@if(!$ad->active)
								<a class="btn btn-primary" href="{{route('update-ads',['id_user'=>$user->id,'id_ads'=>$ad->id])}}">{{trans('global.update')}}</a>
								&nbsp;&nbsp;
								<!-- <a class="btn btn-primary" href="#" onclick="deleteAds({{$ad->id}},'{{$ad->name}}')">{{trans('global.delete')}}</a> -->
							@endif
								
								<a class="btn btn-primary" href="{{route('re-create-ads',['id_user'=>$user->id,'id_ads'=>$ad->id])}}">{{ trans('Location'.DS.'user.create_same') }}</a>
						</td>
					</tr>
					@endforeach
					@endif
				</tbody>
			</table>
			@else
			<div class="w-100 text-center pt-4">
				<a href="{{route('create-ads',['id_user'=>$user->id])}}" class="btn btn-primary btn-lg"><i class="fa fa-plus"></i> {{trans('Location'.DS.'user.create_ads')}}</a>
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
		function deleteAds(id){
			$("#link_modal").attr('href',"/user/{{$user->id}}/delete-ads/"+id);
			$("#modal-message").modal();
		}
	</script>
@endsection
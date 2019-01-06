<div class="content-edit-profile-manager">
	<h3 class="text-danger">{{trans('Location'.DS.'user.code_invite')}}: <b>{{$user->code_invite}}</b></h3>
	<h3>{{ mb_strtoupper(trans('Location'.DS.'user.revenue_invite')) }}</h3>
	<!-- Nav tabs -->
	<div class="box-payment">
		<ul class="nav box-payment-nav-tab" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#list-location" role="tab">{{trans('Location'.DS.'user.list_location')}} ({{$total_location?$total_location:0}})</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#revenue" role="tab">{{trans('Location'.DS.'user.revenue')}}</a>
			</li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content box-payment-tab-content">
			<div class="tab-pane active" id="list-location" role="tabpanel">
				<ul class="row list-unstyled">
					@if($contents)
					@foreach($contents as $content)
						<li class="col-lg-4 col-6" id="item_{{$content->id}}">
							<div class="card-vertical card">
								<div class="card-img-top">
									<a href="{{LOCATION_URL}}/{{$content->alias}}" alt="{{$content->name}}">
										<img class="img-fluid" src="{{str_replace('img_content','img_content_thumbnail',$content->avatar)}}" alt="{{$content->name}}">
									</a>
								</div>
								<div class="card-block py-2 px-0">
									<div class="card-description">
										<h6 class="card-title "><a href="{{LOCATION_URL}}/{{$content->alias}}" title="{{$content->name}}">{{$content->name}}</a></h6>
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
			<div class="tab-pane" id="revenue" role="tabpanel">
				<h5>{{ trans('global.month') }} {{date('m')}} {{ trans('global.year') }} {{date('Y')}}</h5>
				<table class="payment-history-table table table-striped ">
					<thead>
					<tr>
						<th  class="text-truncate">{{ trans('Location'.DS.'user.location') }}</th>
						<th  class="text-truncate">{{ trans('Location'.DS.'user.type') }}</th>
						<th class="text-truncate">{{ trans('Location'.DS.'user.payment') }}</th>
						<th  class="text-truncate">{{ trans('Location'.DS.'user.revenue') }}</th>
					</tr>
					</thead>
					<tbody>
						@if($transactions)
						@foreach($transactions as $transaction)
						<tr>
							<th scope="row">{{$transaction['name']}}</th>
							<th scope="row">{{$transaction['type']}}</th>
							<!-- <td>{{date('d-m-Y H:m:i', strtotime($transaction['created_at']))}}</td> -->
							<td>{{money_number($transaction['total']?$transaction['total']:0)}} K</td>
							<td>{{money_number($transaction['revenue']?$transaction['revenue']:0)}} K</td>
						</tr>
						@endforeach
						<tr style="border-top: 2px solid #ddd;">
							<th>&nbsp;</th>
							<th scope="row" class="text-right"><h5>{{ trans('Location'.DS.'user.total') }}</h5></th>
							<th scope="row" class="text-danger"><h5>{{money_number($total?$total:0)}} K</h5></th>
							<th scope="row" class="text-danger"><h5>{{money_number($total_revenue?$total_revenue:0)}} K</h5></th>
						</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@section('JS')
	@if(Auth::guard('web_client')->user())
		@include('Location.user.crop_image')
	@endif
@endsection
<div class="content-edit-profile-manager">
	<h3>{{ mb_strtoupper(trans('Location'.DS.'user.your_wallet_kingmap')) }}</h3>
	<!-- Nav tabs -->
	<div class="box-payment">
		<ul class="nav box-payment-nav-tab" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" data-toggle="tab" href="#lich-su-giao-dich" role="tab">{{trans('Location'.DS.'user.history_transaction')}}</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#nap-tien" role="tab">{{trans('Location'.DS.'user.add_funds')}}</a>
			</li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content box-payment-tab-content">
			<div class="tab-pane" id="nap-tien" role="tabpanel">
				<h3>Chức năng đang cập nhật</h3>
				{{-- <p>Chọn 1 trong 2 cách sau để nạp tiền vào ví King Map của bạn bằng thẻ Visa/ MasterCard. </p>
				<ul class="list-unstyled option-payment">
					<li class="row d-flex align-items-center">
						<div class="col-md-3">
							<label class="custom-control custom-radio d-flex align-items-center">
									<input id="radio1" name="radio" type="radio" class="custom-control-input">
									<span class="custom-control-indicator"></span>
									<span class="custom-control-description">Nhập số tiền cần nạp</span>
								</label>
						</div>
						<div class="col-md-4">
							<input type="number" class="form-control" placeholder="100.000"> 
						</div>
					</li>
					<li class="row d-flex align-items-center">
						<div class="col-md-3">
							<label class="custom-control custom-radio d-flex align-items-center">
								<input id="radio2" name="radio" type="radio" class="custom-control-input" checked>
								<span class="custom-control-indicator"></span>
								<span class="custom-control-description">số tiền cần nạp</span>
							</label>
						</div>
						<div class="col-md-9">
							<ul class="list-unstyled">
								<li class="item-number-card">
									<a href="">
										<div class="item-number-card-header">
											<img src="assets/img/logo/logo-large.svg" alt="">
										</div>
										<div class="item-number-card-content">
											<h4>150K</h4>
											<span>= 100.000 vnđ</span>
										</div>
									</a>
								</li>
								<!-- end item number card -->
								<li class="item-number-card">
									<a href="">
										<div class="item-number-card-header">
											<img src="assets/img/logo/logo-large.svg" alt="">
										</div>
										<div class="item-number-card-content">
											<h4>150K</h4>
											<span>= 100.000 vnđ</span>
										</div>
									</a>
								</li>
								<!-- end item number card -->
								<li class="item-number-card">
									<a href="">
										<div class="item-number-card-header">
											<img src="assets/img/logo/logo-large.svg" alt="">
										</div>
										<div class="item-number-card-content">
											<h4>150K</h4>
											<span>= 100.000 vnđ</span>
										</div>
									</a>
								</li>
								<!-- end item number card -->
								<li class="item-number-card">
									<a href="">
										<div class="item-number-card-header">
											<img src="assets/img/logo/logo-large.svg" alt="">
										</div>
										<div class="item-number-card-content">
											<h4>150K</h4>
											<span>= 100.000 vnđ</span>
										</div>
									</a>
								</li>
								<!-- end item number card -->
								
							</ul>
						</div>
					</li>
				</ul>
				<div class="text-center mt-5">
					<a data-toggle="modal" data-target=".modal-submit-payment modal-vertical-middle modal-vertical-middle" class="btn-payment btn btn-primary" href="">Tiếp theo</a>
				</div> --}}
			</div>
			<div class="tab-pane active" id="lich-su-giao-dich" role="tabpanel">
				<div class="payment-history">
					<ul class="nav mb-4" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" data-toggle="tab" href="#lich-thanh-toan" role="tab">{{ trans('Location'.DS.'user.history_payment') }}</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#lich-su-nap-tien" role="tab">{{ trans('Location'.DS.'user.history_add_funds') }}</a>
						</li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
						<div class="tab-pane" id="lich-su-nap-tien" role="tabpanel">
							<h3>Chức năng đang cập nhật</h3>
							<!-- <table class="payment-history-table table table-striped">
								<thead>
								<tr>
									<th class="text-truncate">ID Number</th>
									<th class="text-truncate">Time/ Date</th>
									<th class="text-truncate">Số credit đã nạp</th>
									<th class="text-truncate">VISA</th>
								</tr>
								</thead>
								<tbody>
								<tr>
									<th scope="row">KM982763</th>
									<td>09:00 AM, 17/07/2017</td>
									<td>450K</td>
									<td>************0909</td>
								</tr>
								<tr>
									<th scope="row">KM982763</th>
									<td>09:00 AM, 17/07/2017</td>
									<td>450K</td>
									<td>************0909</td>
								</tr>
								<tr>
									<th scope="row">KM982763</th>
									<td>09:00 AM, 17/07/2017</td>
									<td>450K</td>
									<td>************0909</td>
								</tr>
								</tbody>
							</table> -->
							<!-- end payment history table -->
						</div>
						<div class="tab-pane active" id="lich-thanh-toan" role="tabpanel">
							<table class="payment-history-table table table-striped ">
								<thead>
								<tr>
									<th  class="text-truncate">{{ trans('Location'.DS.'user.description') }}</th>
									<th  class="text-truncate">{{ trans('Location'.DS.'user.time') }}</th>
									<th class="text-truncate">{{ trans('Location'.DS.'user.payment') }}</th>
								</tr>
								</thead>
								<tbody>
									@if($transactions)
									@foreach($transactions as $transaction)
									<tr>
										<th scope="row">{{$transaction->description}}</th>
										<td>{{date('d-m-Y H:m:i', strtotime($transaction->created_at))}}</td>
										<td>{{money_number($transaction->adjust?$transaction->adjust:0)}} K</td>
									</tr>
									@endforeach
									@endif
								</tbody>
							</table>
							<!-- end payment history table -->
							@if($transactions)
							<div class="col-sm-12 text-center">
								{!! $transactions->appends(request()->query())->links('vendor.pagination.bootstrap-4') !!}
							</div>
							@endif
						</div>
					</div>
				</div>
				<!-- end  payment history -->
			</div>
		</div>
	</div>
</div>
@section('JS')
	@if(Auth::guard('web_client')->user())
		@include('Location.user.crop_image')
	@endif
@endsection
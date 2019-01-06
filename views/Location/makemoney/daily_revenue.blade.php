<div class="content-edit-profile-manager">
	<div class="process-create-content w-100 mb-4">
		<h3>{{mb_strtoupper(trans('Location'.DS.'makemoney.revenue_month'))}}: {{money_number($revenue)}} K</h3>
	</div>
	@php
		$arr_type = ["booking","duy_tri","khuyen_mai","quang_cao","rao_vat","shop" ,"software","website" ];
		$total = 0;
		$total_sum = 0;
	@endphp
	<div class="row">
		<div class="col-md-12 mb-3">
			<div class="table-responsive">
				<table class="table table-striped">
					<thead>
						<tr>
							<th></th>
							<th>{{mb_strtoupper(trans('Location'.DS.'makemoney.revenue_area'))}}</th>
							<th>{{mb_strtoupper(trans('Location'.DS.'makemoney.revenue_month'))}}</th>
						</tr>
					</thead>
					@foreach($arr_type as $type)
						@php
						if(isset($static[$type])){
							$total += $static[$type]['value'];
							$total_sum += $static[$type]['sum'];
						}
						@endphp

						{{-- @if(isset($static[$type])) --}}
						<tr>
							<td class="align-middle">{{trans('global.'.$type)}}</td>
							<td class="align-middle">{{isset($static[$type])?money_number($static[$type]['sum']):0}}</td>
							<td class="align-middle">{{isset($static[$type])?money_number($static[$type]['value']):0}}</td>
						</tr>
						{{-- @endif --}}
					@endforeach
					<tr>
						<td class="align-middle"><h3>{{trans('global.total')}}</h3></td>
						<td class="align-middle"><h3>{{money_number($total_sum)}}</h3></td>
						<td class="align-middle"><h3>{{money_number($total)}}</h3></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
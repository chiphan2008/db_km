<div class="content-edit-profile-manager">
	<!-- <div class="process-create-content w-100 mb-4">
		<h3>{{mb_strtoupper(trans('Location'.DS.'makemoney.general'))}}</h3>
	</div> -->
	<div class="row">
		<div class="col-md-12 mb-3">
			<form class="form-upload-avata">
				<a class="upload-img-avata" href="#" title="" data-toggle="modal" data-target="#modal-upload-avata">
					<img class="rounded-circle" src="{{$daily->avatar?$daily->avatar:''}}" alt="{{$daily->full_name?$daily->full_name:''}}">
				</a>
				<img id="avatar_source" crossOrigin="Anonymous" src="{{$daily->avatar?$daily->avatar:''}}" style="visibility: hidden; position: absolute;">
			</form>
			<div class="info-name align-middle">
				<span class="text-uppercase  text-truncates ml-2 h5">{{$daily->full_name?$daily->full_name:''}}</span><br/>
				@if($daily->address)
				<!-- <span class="follow  text-truncates ml-2">{{$daily->address?$daily->address:''}}&nbsp;</span><br/> -->
				@endif
				@if($daily->email)
				<span class="follow  text-truncates ml-2">{{$daily->email?$daily->email:''}}&nbsp;</span><br/>
				@endif
				@if($daily->phone)
				<span class="follow  text-truncates ml-2">{{$daily->phone?$daily->phone:''}}&nbsp;</span><br/>
				@endif
			</div>
		</div>
		<div class="col-md-12 mb-3">
			@php
				$arr_type = ["booking","duy_tri","khuyen_mai","quang_cao","rao_vat","shop" ,"software","website" ];
				$total = 0;
			@endphp
			@foreach($arr_type as $type)
				@php
				if(isset($static[$type])){
					$total += $static[$type]['value'];
				}
				@endphp
			@endforeach
			<h3 data-toggle="collapse" href="#revenue" role="button" aria-expanded="false" aria-controls="revenue" >{{trans('Location'.DS.'makemoney.revenue_month')}}: <t class="text-danger">{{money_number($total)}} K </t><i class="indicator fa fa-angle-right"></i></h3>
			<div class="collapse" id="revenue">
				<div class="col-md-12">
					<table class="table table-striped table-responsive w-100">
						@foreach($arr_type as $type)
							@if(isset($static[$type]))
							<tr>
								<td class="align-middle">{{trans('global.'.$type)}}</td>
								<td class="align-middle">{{isset($static[$type])?money_number($static[$type]['value']):0}}</td>
							</tr>
							@endif
						@endforeach
					</table>
				</div>
			</div>
		</div>

		<div class="col-md-12 mb-3">
			<h3>{{trans('Location'.DS.'makemoney.count_location')}}:
				<a href="#" class="text-danger">{{money_number($count_location_daily)}}</a>
			</h3>
		</div>


		<div class="col-md-12 mb-3">
			<h3 data-toggle="collapse" href="#area" role="button" aria-expanded="false" aria-controls="area" >{{trans('Location'.DS.'makemoney.location')}}: <t class="text-danger">{{count($daily->_area)}}</t> <i class="indicator fa fa-angle-right"></i></h3>
			<div class="collapse" id="area">
				<div class="row">
					@foreach($daily->_area as $district)
					<div class="col-xs-6 col-md-3">
						{{$district->name}}
					</div>
					@endforeach
				</div>
			</div>
		</div>

		<div class="col-md-12 mb-3">
			<h3 data-toggle="collapse" href="#quyenloi" role="button" aria-expanded="true" aria-controls="quyenloi" >{{trans('Location'.DS.'makemoney.quyenloi')}} <i class="indicator fa fa-angle-down"></i></h3>
			<div class="collapse show" id="quyenloi">
				<div class="col-md-12">
					{!! nl2br($quyenloi) !!}
				</div>
			</div>
		</div>

		<div class="col-md-12 mb-3">
			<h3 data-toggle="collapse" href="#giaoviec" role="button" aria-expanded="true" aria-controls="giaoviec" >{{trans('Location'.DS.'makemoney.giaoviec')}} <i class="indicator fa fa-angle-down"></i></h3>
			<div class="collapse show" id="giaoviec">
				<div class="col-md-12">
					{!! nl2br($giaoviec) !!}
				</div>
			</div>
		</div>
	</div>

</div>

<script>
function toggleChevron(e) {
    $(e.target)
        .find("i.indicator")
        .toggleClass('fa-angle-down fa-angle-right');
}
$(function(){
	$('[data-toggle=collapse]').on('click',toggleChevron);
})
</script>
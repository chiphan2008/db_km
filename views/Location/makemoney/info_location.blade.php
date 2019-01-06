<div class="content-edit-profile-manager">
	<!-- <div class="process-create-content w-100 mb-4">
		<h3>{{mb_strtoupper(trans('Location'.DS.'makemoney.general'))}}</h3>
	</div> -->
	<div class="row">
		<div class="col-md-12 mb-3">
			<form class="form-upload-avata">
				<a class="upload-img-avata" href="#" title="" data-toggle="modal" data-target="#modal-upload-avata">
					<img class="rounded-circle" src="{{$content->avatar?$content->avatar:''}}" alt="{{$content->name?$content->name:''}}">
				</a>
				<img id="avatar_source" crossOrigin="Anonymous" src="{{$content->avatar?$content->avatar:''}}" style="visibility: hidden; position: absolute;">
			</form>
			<div class="info-name">
				<h3 class="text-uppercase  text-truncate">{{$content->name?$content->name:''}}</h3>
				<span class="addres  text-truncates">{{$content->address}}, {{$content->_district?$content->_district->name:''}}, {{$content->_city?$content->_city->name:''}}, {{$content->_country?$content->_country->name:''}}&nbsp;</span>
				<span class="follow  text-truncate">
					{{-- <i class="icon-eye"></i>200 {{mb_strtolower(trans('Location'.DS.'user.follow'))}} --}}
					&nbsp;
				</span>
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
			<h3 data-toggle="collapse" href="#revenue" role="button" aria-expanded="true" aria-controls="revenue" >{{trans('Location'.DS.'makemoney.revenue_month')}}: <t class="text-danger">{{money_number($total)}} K </t><i class="indicator fa fa-angle-right"></i></h3>
			<div class="collapse show" id="revenue">
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
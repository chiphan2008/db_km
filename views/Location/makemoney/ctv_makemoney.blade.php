<div class="content-edit-profile-manager">
	<div class="process-create-content w-100 mb-4">
		<h3>{{mb_strtoupper(trans('Location'.DS.'makemoney.general'))}}</h3>
	</div>
	<div class="row">
		<!-- <div class="col-md-12 mb-3">
			<form class="form-upload-avata">
				<a class="upload-img-avata" href="#" title="" data-toggle="modal" data-target="#modal-upload-avata">
					<img class="rounded-circle" src="{{$client->avatar?$client->avatar:''}}" alt="{{$client->full_name?$client->full_name:''}}">
				</a>
				<img id="avatar_source" crossOrigin="Anonymous" src="{{$client->avatar?$client->avatar:''}}" style="visibility: hidden; position: absolute;">
			</form>
			<div class="info-name">
				<h3 class="text-uppercase  text-truncate">{{$client->full_name?$client->full_name:''}}</h3>
				<span class="addres  text-truncates">{{$client->address?$client->address:''}}&nbsp;</span>
				<span class="follow  text-truncate">
					{{-- <i class="icon-eye"></i>200 {{mb_strtolower(trans('Location'.DS.'user.follow'))}} --}}
					&nbsp;
				</span>
			</div>
		</div> -->
		<div class="col-md-12 mb-3">
			<h3>{{trans('Location'.DS.'makemoney.manager')}}:
				<a href="#" class="text-danger">{{$client->_daily->full_name}}</a>
			</h3>
		</div>
		<div class="col-md-12 mb-3">
			<h3>{{trans('Location'.DS.'makemoney.revenue_month')}}:
				<a href="#" class="text-danger">{{money_number($revenue)}} K</a>
			</h3>
		</div>

		<div class="col-md-12 mb-3">
			<h3>{{trans('Location'.DS.'makemoney.count_location')}}:
				<a href="#" class="text-danger">{{money_number($count_location)}}</a>
			</h3>
		</div>

		<div class="col-md-12 mb-3">
			<h3 data-toggle="collapse" href="#area" role="button" aria-expanded="false" aria-controls="area" >{{trans('Location'.DS.'makemoney.location')}}: <t class="text-danger">{{count($client->_area)}}</t> <i class="indicator fa fa-angle-right"></i></h3>
			<div class="collapse" id="area">
				<div class="row">
					@foreach($client->_area as $district)
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
<div class="content-edit-profile-manager">
	<!-- <div class="process-create-content w-100 mb-4">
		<h3>{{mb_strtoupper(trans('Location'.DS.'makemoney.general'))}}</h3>
	</div> -->

		<div class="row">
			{{csrf_field()}}
			<div class="col-md-12 mb-3">
				<form class="form-upload-avata">
					<a class="upload-img-avata" href="#" title="" data-toggle="modal" data-target="#modal-upload-avata">
						<img class="rounded-circle" src="{{$ctv->avatar?$ctv->avatar:''}}" alt="{{$ctv->full_name?$ctv->full_name:''}}">
					</a>
					<img id="avatar_source" crossOrigin="Anonymous" src="{{$ctv->avatar?$ctv->avatar:''}}" style="visibility: hidden; position: absolute;">
				</form>
				<div class="info-name">
					<h3 class="text-uppercase  text-truncate">{{$ctv->full_name?$ctv->full_name:''}}</h3>
					<span class="addres  text-truncates">{{$ctv->address?$ctv->address:''}}&nbsp;</span>
					<span class="follow  text-truncate">
						{{-- <i class="icon-eye"></i>200 {{mb_strtolower(trans('Location'.DS.'user.follow'))}} --}}
						&nbsp;
					</span>
				</div>
			</div>
			<div class="col-md-12">
				<form action="" method="POST" accept-charset="utf-8" class="row">
					{{csrf_field()}}
					<div class="col-md-12">
						<h3>{{trans('Location'.DS.'makemoney.area')}}</h3>
					</div>
					<input type="hidden" name="country" value="{{$country}}">
					<div class="col-md-12"><input type="checkbox" id="check_all">{{trans('global.all')}}</div class="col-md-12">
					@foreach($city as $key => $ct)
						<div class="col-md-12 mb-2"><br/><b>{{$ct->name}}:</b><br/></div>
						@foreach($districts as $key => $district)
							@if($district->id_city == $ct->id)
							<div class="col-md-3 col-xs-6">
								<input class="district" type="checkbox" name="district[]" value="{{$district->id}}" {{in_array($district->id,$old_district)?"checked":''}}> {{$district->name}}
							</div>
							@endif
						@endforeach
					@endforeach
					<div class="col-md-12 mt-3">
						<h3>&nbsp;</h3>
					</div>
					<div class="col-md-12 mb-3">
						<textarea name="giaoviec" class="form-control" rows="5" placeholder="{{trans('Location'.DS.'makemoney.input_giaoviec')}}">{{$giaoviec?$giaoviec:''}}</textarea>
					</div>
					<div class="col-md-12 text-center mt-2">
						<button type="submit" class="btn btn-primary">{{trans('Location'.DS.'makemoney.phanquyen')}}</button>
					</div>	
				</form>
			</div>
		</div>
</div>

<script>
	$(function(){

		$("#check_all").on("change",function(){
			var check = $(this).is(":checked");
			$(".district").prop('checked',check);
		});
		$(".district").on("change",function(){
			var check = true;
			$(".district").each(function(key,elem){
				if($(elem).is(":checked") == false){
					check = false;
				}
			});
			$("#check_all").prop("checked",check);
		});
		$(".district").trigger("change");
		
	})
</script>
<div class="content-edit-profile-manager">
	<div class="row mb-3">
		<div class="col-md-4 col-xs-12">
			<h3>
				{{mb_strtoupper(trans('Location'.DS.'makemoney.count_ctv'))}} (<t id="save_like_content_total">{{$count_ctv}}</t>)
			</h3>
		</div>
		<div class="col-md-4 col-xs-12">
			<form action="" method="get" accept-charset="utf-8">
				<div class="form-group">
					<div class="input-group">
						<input class="form-control" type="text" name="keyword" value="{{$keyword?$keyword:''}}" placeholder="{{trans('global.keyword')}}">
						<button class="btn btn-primary ml-2" type="submit">
							<i class="fa fa-search"></i>
						</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-sm-12">
				@if(session('status'))
					<div class="alert alert-success alert-dismissible" style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
						</button>
						{!! session('status') !!}
					</div>
				@endif
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 mb-3">
			@if(count($ctv))
			<div class="table-responsive">
				<table class="table table-striped">
					<tr>
						<th>{{trans('global.full_name')}}</th>
<!-- 						<th>{{trans('global.email')}}</th>
						<th class="text-center">{{trans('global.phone')}}</th> -->
						<th class="text-center">{{trans('global.avatar')}}</th>
						<th style="min-width:275px;">&nbsp;</th>
					</tr>
					@foreach($ctv as $cv)
					<tr>
						<td class="align-middle">{{$cv->full_name}}</td>
<!-- 						<td class="align-middle">{{$cv->email}}</td>
						<td class="text-center align-middle">{!! $cv->phone?$cv->phone:'&nbsp;&nbsp;&nbsp;' !!}</td> -->
						<td class="text-center align-middle"><img src="{{$cv->avatar}}" alt="" height="50"></td>
						<td style="min-width:275px;" class="align-middle text-right">
							<a class="btn btn-primary" href="{{route('info_ctv',['id'=>$cv->id])}}" title="{{ trans('Location'.DS.'makemoney.ctv_info') }}">
								{{-- trans('Location'.DS.'makemoney.ctv_info') --}}
								<i class="fa fa-info" style="width:25px"></i>
							</a>
							<a class="btn btn-primary" href="{{route('grant_ctv',['id'=>$cv->id])}}" title="{{ trans('Location'.DS.'makemoney.phanquyen') }}">
								{{-- trans('Location'.DS.'makemoney.phanquyen') --}}
								<i class="fa fa-group" style="width:25px"></i>
							</a>
							@if($cv->role_active == 1)
							<a class="btn btn-primary" href="{{route('daily_lock_ctv',['id'=>$cv->id])}}" title="{{ trans('Location'.DS.'makemoney.lock') }}">
								{{-- trans('Location'.DS.'makemoney.lock') --}}
								<i class="fa fa-lock" style="width:25px"></i>
							</a>
							@else
							<a class="btn btn-primary" href="{{route('daily_unlock_ctv',['id'=>$cv->id])}}" title="{{ trans('Location'.DS.'makemoney.unlock') }}">
								{{-- trans('Location'.DS.'makemoney.unlock') --}}
								<i class="fa fa-unlock" style="width:25px"></i>
							</a>
							@endif
							<a class="btn btn-primary" href="#" onclick="delete_ctv('{{$cv->full_name}}','{{route('daily_remove_ctv',['id'=>$cv->id])}}')" title="{{ trans('global.delete') }}">
								{{-- trans('global.delete') --}}
								<i class="fa fa-remove" style="width:25px"></i>
							</a>
						</td>
					</tr>
					@endforeach
				</table>
			</div>
			@else
			<h5 class="text_no">{{trans('Location'.DS.'makemoney.no_ctv')}}</h5>
			@endif
		</div>
		@if($ctv)
		<div class="col-sm-12">
			{!! $ctv->appends(request()->query())->links('vendor.pagination.bootstrap-4') !!}
		</div>
		@endif
	</div>
</div>
<div id="modal-remove-ctv" class="modal modal-vertical-middle fade modal-submit-payment  modal-vertical-middle modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment modal-vertical-middle modal-vertical-middle" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content text-center">
					<div class="modal-logo pt-4 text-center">
						<img src="/frontend/assets/img/logo/logo-large.svg" alt="">
					</div>
					<h4>{{trans('Location'.DS.'user.delete_confirm')}}</h4>
					<hr>
					<p id="name_delete"></p>
					<div class="modal-button justify-content-between">
						<a class="btn btn-secorady" href="#" data-dismiss="modal">{{trans('global.cancel')}}</a>
						<a class="btn btn-primary" id="link_delete" href="#">{{trans('global.ok')}}</a>
					</div>
				</div>
			</div>
		</div>
<script>
	function delete_ctv(name,link){
		$("#name_delete").text('{{trans('valid.confirm_delete_ctv')}} ' + name+'?');
		$("#link_delete").attr('href',link);
		$("#modal-remove-ctv").modal();
	}
</script>
<div class="manager-location">
		@if(!Auth::guard('web_client')->user())
		<a  href="javascript:getFromCreateLocation()">
		@endif
			<span class="icon" id="plus_location"><i class="icon-new-white"></i></span>
		@if(!Auth::guard('web_client')->user())
		</a>
		@endif

		@if(!Auth::guard('web_client')->user())
		<!-- <a  href="javascript:getFromCreateLocation()"> -->
		@endif
			<!-- <span id="chat_button" style="
					bottom: 200px !important;
					line-height: 50px;
					display: block;
					width: 50px;
					height: 50px;
					position: fixed;
					z-index: 10;
					right: 0;
					cursor: pointer;
					-webkit-transform: rotate(-90deg);
					transform: rotate(-90deg);
					text-align: center;
					color: #fff;
					border-radius: 4px 4px 0 0;
					background: #d0021b;
					font-size: 22px;
			" onclick="$('#chat-box').toggle('fast')"><i class="icon-chat"></i></span> -->
		@if(!Auth::guard('web_client')->user())
		<!-- </a> -->
		@endif


		@php $area=0; @endphp
		@if(Auth::guard('web_client')->user())
		@php
				$role = Auth::guard('web_client')->user()->getRole('cong_tac_vien')->get();
				$role = isset($role[0])?$role[0]:null;
				$area = Auth::guard('web_client')->user()->getArea()->count();
		@endphp
		<ul class="group-action group-nav list-unstyled">
				<li>
					@if($role)
						@if($role->active)
							@if($area == 0)
								<a data-toggle="modal" data-target="#modal-lock-ctv">{{trans('Location'.DS.'user.create_location')}}</a>
							@else
								<a class="{{ \Request::path() == '/' || \Request::is('user*') ? 'create-address-active' : '' }}" href="javascript: getFromCreateLocation({{ isset(Auth::guard('web_client')->user()->id)?Auth::guard('web_client')->user()->id:'' }});" title="{{trans('Location'.DS.'user.management_location')}}">{{trans('Location'.DS.'user.create_location')}}</a>
							@endif
						@else
						<a data-toggle="modal" data-target="#modal-lock-ctv">{{trans('Location'.DS.'user.create_location')}}</a>
						@endif
					@else
						<a class="{{ \Request::path() == '/' || \Request::is('user*') ? 'create-address-active' : '' }}" href="javascript: getFromCreateLocation({{ isset(Auth::guard('web_client')->user()->id)?Auth::guard('web_client')->user()->id:'' }});" title="{{trans('Location'.DS.'user.management_location')}}">{{trans('Location'.DS.'user.create_location')}}</a>
					@endif

				</li>
				<li>
					<a href="{{url('/')}}/user/{{Auth::guard('web_client')->user()->id}}/management-location" title="{{trans('Location'.DS.'user.management_location')}}">{{trans('Location'.DS.'user.management_location')}}</a>
				</li>
				<li>
						<a href ="{{url('/')}}/user/{{Auth::guard('web_client')->user()->id}}/change-owner" title="{{trans('Admin'.DS.'content.owner_change')}}">{{trans('Admin'.DS.'content.owner_change')}}</a>
				</li>
		</ul>
		@endif
</div>

<div id="modal-lock-ctv" class="modal  modal-submit-payment   modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-logo pt-4 text-center">
				<img src="/frontend/assets/img/logo/logo-large.svg" alt="">
			</div>
			<h4>&nbsp;</h4>
			<p id="text_push">
				@if($area == 0)
				{!! trans('Location'.DS.'user.no_area') !!}
				@else
				{!! trans('Location'.DS.'user.lock_ctv') !!}
				@endif
			</p>
			<div class="modal-button d-flex justify-content-center">
				<a class="btn btn-secorady" href="#" data-dismiss="modal">{{trans('global.close')}}</a>
			</div>
		</div>
	</div>
</div>




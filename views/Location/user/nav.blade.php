<div class="nav-manager-profile">
	<ul class="list-unstyled">

		@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->active_invite)
		<li class="{{$module=='revenue-invite'?'active':'hidden-sm-down'}}"><a href="{{url('/')}}/user/{{$user->id}}/revenue-invite" title="{{trans('global.make_money')}}">{{trans('global.make_money')}}</a></li>
		@endif

		
		@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
		<li class="{{$module=='view'?'active':'hidden-sm-down'}}"><a href="{{url('/')}}/user/{{$user->id}}/" title="{{trans('Location'.DS.'user.profile')}}">{{trans('Location'.DS.'user.profile')}}</a></li>
		<li class="{{$module=='management-location'?'active':'hidden-sm-down'}}"><a href="{{url('/')}}/user/{{$user->id}}/management-location" title="{{trans('Location'.DS.'user.management_location')}}">{{trans('Location'.DS.'user.management_location')}}</a></li>

		<li class="{{$module=='change-owner'?'active':'hidden-sm-down'}}"><a href="{{url('/')}}/user/{{$user->id}}/change-owner" title="{{trans('Admin'.DS.'content.owner_change')}}">{{trans('Admin'.DS.'content.owner_change')}}</a></li>

		<li class="{{$module=='create-discount'?'active':'hidden-sm-down'}}"><a href="{{url('/')}}/user/{{$user->id}}/create-discount" title="{{trans('Location'.DS.'user.create_discount')}}">{{trans('Location'.DS.'user.create_discount')}}</a></li>
		<li class="{{$module=='list-discount'?'active':'hidden-sm-down'}}"><a href="{{url('/')}}/user/{{$user->id}}/list-discount" title="{{trans('Location'.DS.'user.list_discount')}}">{{trans('Location'.DS.'user.list_discount')}}</a></li>
		<li class="{{$module=='create-ads'?'active':'hidden-sm-down'}}"><a href="{{url('/')}}/user/{{$user->id}}/create-ads" title="{{trans('Location'.DS.'user.create_ads')}}">{{trans('Location'.DS.'user.create_ads')}}</a></li>
		<li class="{{$module=='list-ads'?'active':'hidden-sm-down'}}"><a href="{{url('/')}}/user/{{$user->id}}/list-ads" title="{{trans('Location'.DS.'user.list_ads')}}">{{trans('Location'.DS.'user.list_ads')}}</a></li>
		@else
		<li class="{{$module=='management-location'?'active':'hidden-sm-down'}}"><a href="{{url('/')}}/user/{{$user->id}}/management-location" title="{{trans('Location'.DS.'user.location')}}">{{trans('Location'.DS.'user.location')}}</a></li>
		@endif
		<li class="{{$module=='check-in'?'active':'hidden-sm-down'}}"><a href="{{url('/')}}/user/{{$user->id}}/check-in" title="{{trans('Location'.DS.'user.check_in')}}">{{trans('Location'.DS.'user.check_in')}}</a></li>
		<li class="{{$module=='like-location'?'active':'hidden-sm-down'}}"><a href="{{url('/')}}/user/{{$user->id}}/like-location" title="{{trans('Location'.DS.'user.like_location')}}">{{trans('Location'.DS.'user.like_location')}}</a></li>
		<li class="{{$module=='collection'?'active':'hidden-sm-down'}}"><a href="{{url('/')}}/user/{{$user->id}}/collection" title="{{trans('Location'.DS.'user.collection')}}">{{trans('Location'.DS.'user.collection')}}</a></li>
		<!-- <li class="{{$module=='like'?'active':'hidden-sm-down'}}"><a href="{{url('/')}}/user/{{$user->id}}/like" title="{{trans('Location'.DS.'user.like')}}">{{trans('Location'.DS.'user.like')}}</a></li> -->
		<!-- <li class="hidden-sm-down"><a href="{{url('/')}}/user/{{$user->id}}/friend" title="{{trans('Location'.DS.'user.follow_friend')}}">{{trans('Location'.DS.'user.follow_friend')}}</a></li> -->
		@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
		<li class="{{$module=='change-password'?'active':'hidden-sm-down'}}"><a href="{{url('/')}}/user/{{$user->id}}/change-password" title="{{trans('Location'.DS.'user.change_password')}}">{{trans('Location'.DS.'user.change_password')}} </a></li>
		@endif
		<li><a href="https://play.google.com/store/apps/details?id=com.kingmap_app"><img src="/frontend/assets/img/icon/Googleplay.png" alt=""></a></li>
	</ul>
	<ul class="sub-nav-manager-profile nav-manager-profile list-unstyled">

		@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->active_invite)
		<li><a href="{{url('/')}}/user/{{$user->id}}/revenue-invite" title="{{trans('global.make_money')}}">{{trans('global.make_money')}}</a></li>
		@endif
		
		@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
		<li><a href="{{url('/')}}/user/{{$user->id}}/" title="{{trans('Location'.DS.'user.profile')}}">{{trans('Location'.DS.'user.profile')}}</a></li>
		<li><a href="{{url('/')}}/user/{{$user->id}}/management-location" title="{{trans('Location'.DS.'user.management_location')}}">{{trans('Location'.DS.'user.management_location')}}</a></li>

		<li><a href="{{url('/')}}/user/{{$user->id}}/change-owner" title="{{trans('Admin'.DS.'content.owner_change')}}">{{trans('Admin'.DS.'content.owner_change')}}</a></li>

		<li><a href="{{url('/')}}/user/{{$user->id}}/create-discount" title="{{trans('Location'.DS.'user.create_discount')}}">{{trans('Location'.DS.'user.create_discount')}}</a></li>
		<li><a href="{{url('/')}}/user/{{$user->id}}/list-discount" title="{{trans('Location'.DS.'user.list_discount')}}">{{trans('Location'.DS.'user.list_discount')}}</a></li>
		<li><a href="{{url('/')}}/user/{{$user->id}}/create-ads" title="{{trans('Location'.DS.'user.create_ads')}}">{{trans('Location'.DS.'user.create_ads')}}</a></li>
		<li><a href="{{url('/')}}/user/{{$user->id}}/list-ads" title="{{trans('Location'.DS.'user.list_ads')}}">{{trans('Location'.DS.'user.list_ads')}}</a></li>
		@else
		<li><a href="{{url('/')}}/user/{{$user->id}}/management-location" title="{{trans('Location'.DS.'user.location')}}">{{trans('Location'.DS.'user.location')}}</a></li>
		@endif
		<li><a href="{{url('/')}}/user/{{$user->id}}/check-in" title="{{trans('Location'.DS.'user.check_in')}}">{{trans('Location'.DS.'user.check_in')}}</a></li>
		<li><a href="{{url('/')}}/user/{{$user->id}}/like-location" title="{{trans('Location'.DS.'user.like_location')}}">{{trans('Location'.DS.'user.like_location')}}</a></li>
		<li><a href="{{url('/')}}/user/{{$user->id}}/collection" title="{{trans('Location'.DS.'user.collection')}}">{{trans('Location'.DS.'user.collection')}}</a></li>
		<!-- <li><a href="{{url('/')}}/user/{{$user->id}}/like" title="{{trans('Location'.DS.'user.like')}}">{{trans('Location'.DS.'user.like')}}</a></li> -->
		<!-- <li><a href="{{url('/')}}/user/{{$user->id}}/friend" title="{{trans('Location'.DS.'user.follow_friend')}}">{{trans('Location'.DS.'user.follow_friend')}}</a></li> -->
		@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
		<li><a href="{{url('/')}}/user/{{$user->id}}/change-password" title="{{trans('Location'.DS.'user.change_password')}}">{{trans('Location'.DS.'user.change_password')}} </a></li>
		@endif
		<li><a href="https://play.google.com/store/apps/details?id=com.kingmap_app"><img src="/frontend/assets/img/icon/Googleplay.png" alt=""></a></li>
	</ul>
	<!-- end sub-nav-manager-profile -->
	@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
	<div class="total-payment w-100 text-center  hidden-sm-down">
			<p>
					{{trans('Location'.DS.'user.you_have')}}
			</p>
			<h3>
					{{$user->coin?money_number($user->coin):0}} K
			</h3>
			<a class="btn btn-primary" title="{{trans('Location'.DS.'user.view_detail')}}" href="{{url('/')}}/user/{{$user->id}}/wallet">{{trans('Location'.DS.'user.view_detail')}}</a>
	</div>
	@endif
</div>


<script>
	$(function(){
		//click nav mobile
			$('.nav-manager-profile li.active').click(function(event) {
					event.preventDefault();
					$('.sub-nav-manager-profile').slideToggle('fast');
			});
	})
</script>

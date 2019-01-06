<div class="nav-manager-profile">
	<ul class="list-unstyled">
		@if($client->hasRole('cong_tac_vien') > 0)
			<li class="{{$module=='ctv_makemoney'?'active':'hidden-sm-down'}}"><a href="{{route('ctv_makemoney')}}" title="{{trans('Location'.DS.'makemoney.general')}}">{{trans('Location'.DS.'makemoney.general')}}</a></li>

			<li class="{{$module=='ctv_revenue'?'active':'hidden-sm-down'}}">
				<a href="{{route('ctv_revenue')}}" title="{{trans('Location'.DS.'makemoney.revenue_month')}}">
					{{trans('Location'.DS.'makemoney.revenue_month')}} <!-- <span class="lead">{{money_number($revenue)}} K</span> -->
				</a>
			</li>

			<li class="{{$module=='ctv_location'?'active':'hidden-sm-down'}}">
				<a href="{{route('ctv_location')}}" title="{{trans('Location'.DS.'makemoney.count_location')}}">
							{{trans('Location'.DS.'makemoney.count_location')}} ({{money_number($count_location)}}) <!-- <span class="lead">{{money_number($count_location)}}</span> -->
				</a>
			</li>
		@endif

		@if($client->hasRole('tong_dai_ly') > 0)
			<li class="{{$module=='daily_makemoney'?'active':'hidden-sm-down'}}"><a href="{{route('daily_makemoney')}}" title="{{trans('Location'.DS.'makemoney.general')}}">{{trans('Location'.DS.'makemoney.general')}}</a></li>

			<li class="{{$module=='daily_revenue'?'active':'hidden-sm-down'}}">
				<a href="{{route('daily_revenue')}}" title="{{trans('Location'.DS.'makemoney.revenue_month')}}">
					{{trans('Location'.DS.'makemoney.revenue_month')}} <!-- <span class="lead">{{money_number($revenue)}} K</span> -->
				</a>
			</li>

			<li class="{{$module=='daily_location'?'active':'hidden-sm-down'}}">
				<a href="{{route('daily_location')}}" title="{{trans('Location'.DS.'makemoney.count_location')}}">
							{{trans('Location'.DS.'makemoney.count_location')}} ({{money_number($count_location)}}) <!-- <span class="lead">{{money_number($count_location)}}</span> -->
				</a>
			</li>
			
			<li class="{{$module=='daily_ctv'?'active':'hidden-sm-down'}}">
				<a href="{{route('daily_ctv')}}" title="{{trans('Location'.DS.'makemoney.count_ctv')}}">
							{{trans('Location'.DS.'makemoney.count_ctv')}} ({{money_number($count_ctv)}}) <!-- <span class="lead">{{money_number($count_location)}}</span> -->
				</a>
			</li>


			<li class="{{$module=='daily_location_pending'?'active':'hidden-sm-down'}}">
				<a href="{{route('daily_location_pending')}}" title="{{trans('Location'.DS.'makemoney.count_location_pending')}}">
							{{trans('Location'.DS.'makemoney.count_location_pending')}} ({{money_number($count_location_pending)}}) <!-- <span class="lead">{{money_number($count_location)}}</span> -->
				</a>
			</li>

			<li class="{{$module=='daily_ctv_pending'?'active':'hidden-sm-down'}}">
				<a href="{{route('daily_ctv_pending')}}" title="{{trans('Location'.DS.'makemoney.count_ctv_pending')}}">
							{{trans('Location'.DS.'makemoney.count_ctv_pending')}} ({{money_number($count_ctv_pending)}}) <!-- <span class="lead">{{money_number($count_location)}}</span> -->
				</a>
			</li>
		@endif

		@if($client->hasRole('ceo') > 0)

			<li class="{{$module=='ceo_makemoney'?'active':'hidden-sm-down'}}"><a href="{{route('ceo_makemoney')}}" title="{{trans('Location'.DS.'makemoney.general')}}">{{trans('Location'.DS.'makemoney.general')}}</a></li>

			<li class="{{$module=='ceo_revenue'?'active':'hidden-sm-down'}}">
				<a href="{{route('ceo_revenue')}}" title="{{trans('Location'.DS.'makemoney.revenue_month')}}">
					{{trans('Location'.DS.'makemoney.revenue_month')}} <!-- <span class="lead">{{money_number($revenue)}} K</span> -->
				</a>
			</li>

			<li class="{{$module=='ceo_location'?'active':'hidden-sm-down'}}">
				<a href="{{route('ceo_location')}}" title="{{trans('Location'.DS.'makemoney.count_location')}}">
							{{trans('Location'.DS.'makemoney.count_location')}} ({{money_number($count_location)}}) <!-- <span class="lead">{{money_number($count_location)}}</span> -->
				</a>
			</li>
			
			<li class="{{$module=='ceo_ctv'?'active':'hidden-sm-down'}}">
				<a href="{{route('ceo_ctv')}}" title="{{trans('Location'.DS.'makemoney.count_ctv')}}">
							{{trans('Location'.DS.'makemoney.count_ctv')}} ({{money_number($count_ctv)}}) <!-- <span class="lead">{{money_number($count_location)}}</span> -->
				</a>
			</li>


			<li class="{{$module=='ceo_daily'?'active':'hidden-sm-down'}}">
				<a href="{{route('ceo_daily')}}" title="{{trans('Location'.DS.'makemoney.count_daily')}}">
							{{trans('Location'.DS.'makemoney.count_daily')}} ({{money_number($count_daily)}}) <!-- <span class="lead">{{money_number($count_location)}}</span> -->
				</a>
			</li>

		@endif
	</ul>

	<ul class="sub-nav-manager-profile nav-manager-profile list-unstyled">
		@if($client->hasRole('cong_tac_vien') > 0)
			<li><a href="{{route('ctv_makemoney')}}" title="{{trans('Location'.DS.'makemoney.general')}}">{{trans('Location'.DS.'makemoney.general')}}</a></li>

			<li><a href="{{route('ctv_revenue')}}" title="{{trans('Location'.DS.'makemoney.revenue_month')}}">{{trans('Location'.DS.'makemoney.revenue_month')}}: <b>{{money_number($revenue)}} K</b></a></li>

			<li><a href="{{route('ctv_location')}}" title="{{trans('Location'.DS.'makemoney.count_location')}}">{{trans('Location'.DS.'makemoney.count_location')}}: <b>{{money_number($count_location)}}</b></a></li>
		@endif

		@if($client->hasRole('tong_dai_ly') > 0)
			<li><a href="{{route('daily_makemoney')}}" title="{{trans('Location'.DS.'makemoney.general')}}">{{trans('Location'.DS.'makemoney.general')}}</a></li>

			<li><a href="{{route('daily_revenue')}}" title="{{trans('Location'.DS.'makemoney.revenue_month')}}">{{trans('Location'.DS.'makemoney.revenue_month')}}: <b>{{money_number($revenue)}} K</b></a></li>

			<li><a href="{{route('daily_location')}}" title="{{trans('Location'.DS.'makemoney.count_location')}}">{{trans('Location'.DS.'makemoney.count_location')}}: <b>{{money_number($count_location)}}</b></a></li>

			<li><a href="{{route('daily_location_pending')}}" title="{{trans('Location'.DS.'makemoney.count_location_pending')}}">{{trans('Location'.DS.'makemoney.count_location_pending')}}: <b>{{money_number($count_location_pending)}}</b></a></li>

			<li><a href="{{route('daily_ctv')}}" title="{{trans('Location'.DS.'makemoney.count_ctv')}}">{{trans('Location'.DS.'makemoney.count_ctv')}}: <b>{{money_number($count_ctv)}}</b></a></li>

			<li><a href="{{route('daily_ctv_pending')}}" title="{{trans('Location'.DS.'makemoney.count_ctv_pending')}}">{{trans('Location'.DS.'makemoney.count_ctv_pending')}}: <b>{{money_number($count_ctv_pending)}}</b></a></li>
		@endif

		@if($client->hasRole('ceo') > 0)
			
			<li><a href="{{route('ceo_makemoney')}}" title="{{trans('Location'.DS.'makemoney.general')}}">{{trans('Location'.DS.'makemoney.general')}}</a></li>

			<li><a href="{{route('ceo_revenue')}}" title="{{trans('Location'.DS.'makemoney.revenue_month')}}">{{trans('Location'.DS.'makemoney.revenue_month')}}: <b>{{money_number($revenue)}} K</b></a></li>
			
			<li><a href="{{route('ceo_location')}}" title="{{trans('Location'.DS.'makemoney.count_location')}}">{{trans('Location'.DS.'makemoney.count_location')}}: <b>{{money_number($count_location)}}</b></a></li>
			
			<li><a href="{{route('ceo_ctv')}}" title="{{trans('Location'.DS.'makemoney.count_ctv')}}">{{trans('Location'.DS.'makemoney.count_ctv')}}: <b>{{money_number($count_ctv)}}</b></a></li>

			<li><a href="{{route('ceo_daily')}}" title="{{trans('Location'.DS.'makemoney.count_daily')}}">{{trans('Location'.DS.'makemoney.count_daily')}}: <b>{{money_number($count_daily)}}</b></a></li>

		@endif
	</ul>
	<!-- end sub-nav-manager-profile -->
	@if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $client->id)
	<div class="total-payment w-100 text-center  hidden-sm-down">
			<p>
					{{trans('Location'.DS.'user.you_have')}}
			</p>
			<h3>
					{{$client->coin?money_number($client->coin):0}} K
			</h3>
			<a class="btn btn-primary" title="{{trans('Location'.DS.'user.view_detail')}}" href="{{url('/')}}/user/{{$client->id}}/wallet">{{trans('Location'.DS.'user.view_detail')}}</a>
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
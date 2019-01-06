@if(isset($menu[0]))
	@foreach($menu[0] as $k => $sub_menu)
		@if(isset($menu[$sub_menu['id']]))
		<div class="nav-dropdown dropdown">
				<a class="dropdown-toggle" href="{{$sub_menu['link']}}" id="dropdownMenuLink_{{$sub_menu['id']}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<img src="{{isset($sub_menu['icon_img'])?$sub_menu['icon_img']:'/frontend/assets/img/logo/logo-large.svg'}}" width="40" height="40">
				</a>
				@php
					$check = true;
					$count_child = 0;
					foreach($menu[$sub_menu['id']] as $value){
						$check = $check && isset($menu[$value['id']]);
						if(isset($menu[$value['id']])){
							$count_child++;
						}
					}
				@endphp
				<div class="dropdown-menu" style="min-width: {{$check?($count_child*200).'px':'auto'}};" aria-labelledby="dropdownMenuLink_{{$sub_menu['id']}}">
					@if($check)
						@foreach($menu[$sub_menu['id']] as $value)
							<div class="menu-line">
								@foreach($menu[$value['id']] as $value2)
									<a class="dropdown-item" href="{{$value2['link']}}" title="{{app('translator')->getFromJson($value2['name'])}}">
										<img src="{{isset($value2['icon_img'])?$value2['icon_img']:'/frontend/assets/img/logo/logo-large.svg'}}" width="30" height="30">
										{{app('translator')->getFromJson($value2['name'])}}
									</a>
								@endforeach
							</div>
						@endforeach
					@else
						@foreach($menu[$sub_menu['id']] as $value)
						<div class="menu-line">
							<a class="dropdown-item" href="{{$value['link']}}" title="{{app('translator')->getFromJson($value['name'])}}">
									<img src="{{isset($value['icon_img'])?$value['icon_img']:'/frontend/assets/img/logo/logo-large.svg'}}" width="30" height="30">
									{{app('translator')->getFromJson($value['name'])}}
							</a>
						</div>
						@endforeach
					@endif	
				</div>
		</div>
		@else
		<div class="nav-dropdown dropdown">
				@if($sub_menu['link'] == '/makemoney')
					@if(!Auth::guard('web_client')->user())
					<a  href="javascript:getFromCreateLocation()">
					@else
					<a href="{{$sub_menu['link']}}" >
					@endif
						<img src="{{isset($sub_menu['icon_img'])?$sub_menu['icon_img']:'/frontend/assets/img/logo/logo-large.svg'}}" width="40" height="40">
					</a>
				@else
					<a href="{{$sub_menu['link']}}" >
						<img src="{{isset($sub_menu['icon_img'])?$sub_menu['icon_img']:'/frontend/assets/img/logo/logo-large.svg'}}" width="40" height="40">
					</a>
				@endif
		</div>
		@endif
	@endforeach
@endif
<style>
	.content-menu .dropdown-toggle::after {
		display:none;
	}
	.content-menu .dropdown-toggle{
		margin-right: 30px;
	}

	@media screen and (min-width: 720px) {
		.content-menu .menu-line{
			height: 74px;
		}
	}
</style>


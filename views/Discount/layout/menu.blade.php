<ul class="d-flex justify-content-between align-items-center list-unstyled m-0">
@if(isset($menu[0]))
	@foreach($menu[0] as $k => $sub_menu)
		@if(isset($menu[$sub_menu['id']]))
		<li>
			<div class="nav-dropdown dropdown">
				<a class="dropdown-toggle" href="https://example.com" id="dropdownMenuLink_{{$sub_menu['id']}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					@lang($sub_menu['name'])
				</a>
				<div class="dropdown-menu" aria-labelledby="dropdownMenuLink_{{$sub_menu['id']}}">
					@foreach($menu[$sub_menu['id']] as $value)
						<a class="dropdown-item" href="{{$value['link']}}"><i class="{{$value['icon_class']}}"></i>{{app('translator')->getFromJson($value['name'])}}</a>
					@endforeach
				</div>
			</div>
		</li>
		@else
		<li>
			<a href="{{$sub_menu['link']}}" >@lang($sub_menu['name'])</a>
		</li>
		@endif
	@endforeach
@endif
</ul>

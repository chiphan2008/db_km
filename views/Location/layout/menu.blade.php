<ul class="d-flex justify-content-between align-items-center list-unstyled m-0">
@if(isset($menu[0]))
	@foreach($menu[0] as $k => $sub_menu)
		@if(isset($menu[$sub_menu['id']]))
		<li>
			<div class="nav-dropdown dropdown">
				<a class="" href="#" id="dropdownMenuLink_{{$sub_menu['id']}}">
					<img src="{{isset($sub_menu['icon_img'])?$sub_menu['icon_img']:'/frontend/assets/img/logo/logo-large.svg'}}" width="40" height="40">
					{{-- @lang($sub_menu['name']) --}}
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
				<div class="dropdown-menu menu-line-parent" style="min-width: {{$check?($count_child*200).'px':'auto'}};" aria-labelledby="dropdownMenuLink_{{$sub_menu['id']}}">
					@if($check)
						@foreach($menu[$sub_menu['id']] as $value)
						<div class="menu-line">
								<p><b>{{app('translator')->getFromJson($value['name'])}}</b></p>
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
		</li>
		@else
		<li>
			<!-- <a href="{{$sub_menu['link']}}" >@lang($sub_menu['name'])</a> -->
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
		</li>
		@endif
	@endforeach
@endif
</ul>
<style>
	/*.wrap-main-navigation-desktop .nav-dropdown .dropdown-item{
		height:54px;
    overflow: hidden;
    text-overflow: ellipsis;
	}*/
</style>
<script>
	$(function(){
		$(document).bind("touchstart",function(e){
			// console.log($(e.target), $(e.target).is('.nav-dropdown,.nav-dropdown .dropdown-menu,.nav-dropdown *,.nav-dropdown .dropdown-menu *'));
			// console.log($(e.target).is('.nav-dropdown,.nav-dropdown .dropdown-menu,.nav-dropdown *,.nav-dropdown .dropdown-menu *'));
			// console.log($(e.target).closest('.dropdown-menu'));
			
			//alert($(e.target).attr("class"));
      if(!$(e.target).is('.nav-dropdown,.nav-dropdown .dropdown-menu,.nav-dropdown *,.nav-dropdown .dropdown-menu *')){
      	$(".nav-dropdown .dropdown-menu").hide();
      }else{
      	$(".nav-dropdown .dropdown-menu").hide();
      	if($(e.target).is('.dropdown-item')){
      		$(e.target).parent().parent().show();
      	}else{
      		//console.log($(e.target),$(e.target).is('.menu-line, .menu-line *'),$(e.target).parent().parent())
      		if($(e.target).is('.menu-line, .menu-line *')){
      			$(e.target).parent().parent().parent().show();
      		}else{
      			$(e.target).parent().parent().find('.dropdown-menu').show();
      		}
      	}
      }
		})
		// $(document).bind("click",function(e){
  //     if(!$(e.target).is('.nav-dropdown,.nav-dropdown .dropdown-menu,.dropdown *,.nav-dropdown .dropdown-menu *')){
  //     	$(".nav-dropdown .dropdown-menu").toggle();
  //     }else{
  //     	console.log($(e.target));
  //     	$(e.target).find('.dropdown-menu').show();
  //     }
		// })
	})
</script>
<div class="banner-fill banner-page banner text-center" style="background-image: url({{$category->background?$category->background:''}});">
	<div class="container">
			<div class="main-banner">
					<h2>@lang(ucfirst($category->name))</h2>
					<ol class="breadcrumb text-center hidden-xs-down">
						@if($category->category_items)
							<li class="{{!$category_item?'active':''}}"><a href="#" onclick="loadListCategory(this)" title="{{ucfirst(trans('global.all'))}}" data-id="all">{{mb_ucfirst(trans('global.all'))}}</a></li>
						@foreach($category->category_items as $item)
							<li class="{{$category_item&&$category_item->alias==$item->alias?'active':''}}"><a href="#" onclick="loadListCategory(this)" title="@lang(mb_ucfirst($item->name))" data-id="{{$item->alias}}">@lang(mb_ucfirst($item->name))</a></li>
						@endforeach
						@endif
					</ol>
					<!-- end -->
					<div class="breadcrumb-mobile custom-select-option hidden-sm-up">
							<p id="textClick">{{$category_item?$category_item->name:mb_ucfirst(trans('global.all'))}}</p>
							<ol class="list-unstyled" style="display: none">
									@if($category->category_items)
										<li class="{{!$category_item?'active':''}}"><a href="#" onclick="loadListCategory(this)" title="{{mb_ucfirst(trans('global.all'))}}" data-id="all">{{mb_ucfirst(trans('global.all'))}}</a></li>
									@foreach($category->category_items as $item)
										<li><a href="#" onclick="loadListCategory(this)" title="@lang(mb_ucfirst($item->name))" data-id="{{$item->alias}}">@lang(mb_ucfirst($item->name))</a></li>
									@endforeach
									@endif
							</ol>
							<input style="display: none" type="hidden" name="" value="" placeholder="">
					</div>
			</div>
	</div>
</div>
<!-- end  Banner -->
<div class="content-page">
	<div class="container">
			<div class="menu-filter d-lg-flex justify-content-lg- {{$extra_types?'justify-content-lg-between':'justify-content-lg-center'}} my-sm-4 my-3">
					@if($extra_types)
					<ul class="nav nav-tab tab-style1-nav" role="tablist">
					@foreach($extra_types as $extra_type)
						<?php 
							$arr_change = [
								'BANK'=>'Transaction banking',
							];
							if(isset($arr_change[$extra_type])){
								$extra_type_name = $arr_change[$extra_type];
							}else{
								$extra_type_name = $extra_type;
							}
						?>
						<li class="nav-item nav-extra-type" data-extra-type="{{$extra_type?$extra_type:'
						'}}">
							<a href="#{{$extra_type}}" class="nav-link {{$extra_type==$current_extra_type?'active':''}}" data-toggle="tab"
 role="tab">@lang(ucfirst($extra_type_name))</a>
						</li>
					@endforeach
					</ul>
					@endif
					<div class="box-option">
						<select class="select-bg-gray custom-select mx-sm-2 mx-1" id="country_cat" onchange="changeCountryCat(this)">
								<option value="all">{{trans('Location'.DS.'category.country')}}</option>
								@if($countries)
								@foreach($countries as $country_one)
								<option value="{{$country_one->alias}}" {{$country&&$country_one->alias==$country->alias?'selected':''}}>{{$country_one->name}}</option>
								@endforeach
								@endif
						</select>

						<select class="select-bg-gray custom-select mx-sm-2 mx-1" id="city_cat" onchange="changeCityCat(this)">
								<option value="all">{{trans('Location'.DS.'category.city')}}</option>
								@if($cities)
								@foreach($cities as $citi_one)
								<option value="{{$citi_one->alias}}" {{$city&&$citi_one->alias==$city->alias?'selected':''}}>{{$citi_one->name}}</option>
								@endforeach
								@endif
						</select>

						<select class="select-bg-gray custom-select  mx-sm-2 mx-1" id="district_cat" onchange="changeDistrictCat(this)">
								<option value="all">{{trans('Location'.DS.'category.district')}}</option>
								@if($districts)
								@foreach($districts as $district_one)
								<option value="{{$district_one->alias}}" {{$district&&$district_one->alias==$district->alias?'selected':''}}>{{$district_one->name}}</option>
								@endforeach
								@endif
						</select>
					</div>
			</div>

			<div class="row" id="listContent">
					@include('Location.category.category_list')
			</div>
	</div>
</div>


@section('CSS')
<style>
	.nav-block{
		height:50px;
		text-align: center;
		display: block;
	}
	.nav-block .nav-item{
		padding: 10px 25px;
		display: inline;
		font-weight: 600;
	}
	.nav-block .nav-item.active,.nav-block .nav-item:hover{
		background: #ddd;
	}
	.nav-block .nav-item:nth-child(n+2){
		border-left: 1px solid #ddd;
	}

</style>
@endsection
@section('JS')
<script type="text/javascript">
	var current_page = 1;
	var extra_type = '{{$current_extra_type?$current_extra_type:''}}';
	var max_page = true;
	var stop = true;
	var totalPage = {{$totalPage?$totalPage:1}};
	var current_url = window.location.pathname;
	var _token = $("meta[name='_token']").prop('content');

	$(function(){
		var pageObj = JSON.parse(sessionStorage.page);
		old_page = pageObj.page;
		old_url = pageObj.url;
		if(old_page>1 && old_url==current_url){
			if(old_page > 6){
				old_page = 6;
			}
			for(var i=1; i<old_page; i++){
				setTimeout(function(){
					window.scrollTo(0,$(document).height());
				},750*i);
			}
		}
	})
	
	function setUpUrl(index,value){
		var index = index || 1;
		var value = value || 'all';
		index++;
		var url = window.location.pathname;
		url = url.replace('#','');
		url = url.split('\/');
		if(!url[index-1]){
			url[index-1] = 'all';
		}
		if(index>3){
			for(var i = index; i<=url.length+1; i++){
				url.splice(index, 1);
			}
		}
		if(value!='all' || index<=3){
			if(url[index]){
				url[index] = value;
				url = url.join("\/");
				url = window.location.protocol+'//'+window.location.hostname+url.replace(',','\/');
			}else{
				url = url.join("\/");
				url = window.location.protocol+'//'+window.location.hostname+url.replace(',','\/')+'\/'+value;
			}
		}else{
			url = url.join("\/");
			url = window.location.protocol+'//'+window.location.hostname+url.replace(',','\/');
		}
		
		url = url.replace('#','');
		return url;
	}
	function loadContent(url, loadNew,  loadMore){
		var url = url || '';
		var loadNew = loadNew || false;
		var loadMore = loadMore || false;
		if(max_page){
			if(loadMore){
				current_page++;
			}
			current_url = url;
			history.pushState({}, null, current_url);
			sessionStorage.page = JSON.stringify({page:current_page,url:current_url});
			$.ajax({
				url: url,
				type: 'POST',
				// async: false,
				data: {
					_token: _token,
					page: current_page,
					extra_type: extra_type
				},
				beforeSend: function(){
					max_page = false;
					stop = false;
					$("#loading").show();
				},
				success: function(response){
					$("#loading").hide();
					$("#city_cat").html(response.cities);
					$("#district_cat").html(response.districts);
					if(loadNew){
						$("#listContent").html(response.html);
					}else{
						$("#listContent").append(response.html);
					}
					totalPage = response.totalPage;
					stop = true;
					max_page = true;
					// current_page = response.nextPage;
					if(current_page==totalPage){
						max_page = false;
					}
				}
			})
		}
	}
	function loadListCategory(obj){
		$(".main-banner ol li").removeClass('active');
		$(obj).parent().addClass('active');
		var textClick = $(obj).text();
		$("#textClick").text(textClick);
		$(".main-banner .breadcrumb li").each(function(key,elem){
			if($(elem).text() == textClick){
				$(elem).addClass('active');
			}
		});
		// console.log(textClick)
		// $(".main-banner .breadcrumb-mobile ol li").each(function(key, element){
		// 	console.log($(element).text());
		// 	if($(element).text() == textClick){
		// 		$(element).addClass('active');
		// 	}
		// })
		item_id = $(obj).attr('data-id');
		var url = setUpUrl(2,item_id);
		current_page = 1;
		max_page = true;
		loadContent(url, true);
	}
	function changeCountryCat(obj){
		var id = $(obj).val();
		var url = setUpUrl(3,id);
		current_page = 1;
		max_page = true;
		loadContent(url, true);
	}
	function changeCityCat(obj){
		var id = $(obj).val();
		var url = setUpUrl(4,id);
		current_page = 1;
		max_page = true;
		loadContent(url, true);
	}
	function changeDistrictCat(obj){
		var id = $("#district_cat").val();
		var url = setUpUrl(5,id);
		current_page = 1;
		max_page = true;
		loadContent(url, true);
	}
	$(function(){
		if(window.location.hash) {
			extra_type = window.location.hash.replace('#','');
			$(".nav-extra-type").each(function(key,elem){
				if($(elem).attr('data-extra-type') === extra_type){
					$(".nav-extra-type a").removeClass('active');
					$(elem).find("a").addClass('active');
					current_page = 1;
					max_page = true;
					loadContent(current_url,true);
				}
			});
		}

		$(".nav-extra-type").on("click",function(){
			$(".nav-extra-type").removeClass('active');
			// $(this).addClass('active');
			extra_type = $(this).attr('data-extra-type');
			current_page = 1;
			max_page = true;
			loadContent(current_url,true);
		})

		$(window).scroll(function() {
			//console.log(($(document).height() - $(window).height()) - $(window).scrollTop());
			if($(window).width()>1024){
				if($(window).scrollTop() > ($(document).height() - $(window).height()) - 100) {
					if(current_page<totalPage && stop){
						loadContent(current_url, false, true);
					}
				}
			}else{
				if($(window).scrollTop() > ($(document).height() - $(window).height()) - 100) {
					if(current_page<totalPage && stop){
						loadContent(current_url, false, true);
					}
				}
			}
		});
		@if($districts)
			url = setUpUrl(3,'{{$country->alias?$country->alias:"all"}}');
			history.pushState({}, null, url);
			url = setUpUrl(4,'{{$city?$city->alias:"all"}}');
			history.pushState({}, null, url);
			url = setUpUrl(5,'{{$district?$district->alias:"all"}}');
			history.pushState({}, null, url);
			current_url = window.location.pathname;
		@else
			@if($city)
				url = setUpUrl(3,'{{$country->alias?$country->alias:"all"}}');
				history.pushState({}, null, url);
				url = setUpUrl(4,'{{$city->alias?$city->alias:"all"}}');
				history.pushState({}, null, url);
				current_url = window.location.pathname;
			@else
				@if($country)
					url = setUpUrl(3,'{{$country->alias?$country->alias:"all"}}');
					history.pushState({}, null, url);
					current_url = window.location.pathname;
				@endif
			@endif
		@endif
	})
</script>
@endsection

@section('CSS')
<style>
	.breadcrumb li.active{
		padding: 0 !important;
	}
</style>
@endsection
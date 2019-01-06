<div class="content-search-page my-5">
	<div class="container clearfix">
		<div class="row">
			<div class="col-md-4 mb-4 mb-md-0">
					<div class="form-search-restaurant form-search pb-2">
						<h3 class=" px-3 pt-3 pb-2 mb-0">{{trans('Location'.DS.'search.search_filter')}}</h3>
						<form id="form_search" class="form-search-filter" onsubmit="return false;">
							
							<div class="input-search form-group  px-3 pt-3 pb-2">
									<input type="text" name="q" class="form-control" placeholder="{{trans('global.keyword')}}" onchange="changeKeyword()" value="{{$requests&&isset($requests['q'])?$requests['q']:''}}"/>
							</div>
							 <!-- end  form group -->
							<hr>
							<div class="form-group px-3 mb-4">
								<label>{{trans('Location'.DS.'search.country')}}</label>
								<select class="custom-select-style custom-select w-100" name="country_search" id="country_search" onchange="changeCountrySearch()">
									<option value="">{{trans('Location'.DS.'search.choose_country')}}</option>
									@if($countries)
									@foreach($countries as $country_one)
									<option value="{{$country_one->id}}"  {{$country&&$country_one->id==$country->id?'selected':''}}>{{$country_one->name}}</option>
									@endforeach
									@endif
								</select>
							</div>
		
							 <!-- end  form group -->
							<div class="form-group  px-3 mb-4">
								<label>{{trans('Location'.DS.'search.city')}}</label>
								<select class="custom-select-style custom-select w-100" name="city_search" id="city_search" onchange="changeCitySearch()">
									<option value="">{{trans('Location'.DS.'search.choose_city')}}</option>
									@if($cities)
									@foreach($cities as $citi_one)
									<option value="{{$citi_one->id}}" {{$city&&$citi_one->id==$city->id?'selected':''}}>{{$citi_one->name}}</option>
									@endforeach
									@endif
								</select>
							</div>

							<div class="form-group  px-3 mb-4">
								<label>{{trans('Location'.DS.'search.district')}}</label>
								<select class="custom-select-style custom-select w-100" name="district_search" id="district_search" onchange="changeDistrictSearch()">
									<option value="">{{trans('Location'.DS.'search.choose_district')}}</option>
									@if($districts)
									@foreach($districts as $district_one)
									<option value="{{$district_one->id}}" {{$district&&$district_one->id==$district->id?'selected':''}}>{{$district_one->name}}</option>
									@endforeach
									@endif
								</select>
							</div>
							 <!-- end  form group -->
							<div class="form-group  px-3 mb-4">
								<label>{{trans('Location'.DS.'search.category')}}</label>
								<select class="custom-select-style custom-select w-100" name="category_search" id="category_search"  onchange="changeCategorySearch()">
									<option value="">{{trans('Location'.DS.'search.choose_category')}}</option>
									@if($categories)
									@foreach($categories as $category_one)
									<option value="{{$category_one->id}}" {{$category&&$category_one->id==$category->id?'selected':''}}>@lang(ucfirst($category_one->name))</option>
									@endforeach
									@endif
								</select>
							</div>
							<!-- end  form group -->
							<div class="form-group  px-3 mb-4" id="category_item_search">
								@if($category&&$category->category_items)
								@foreach($category->category_items as $item_one)
									<label class="custom-control custom-checkbox mb-3">
										<input onclick="chooseCategoryItem()" {{$category_items&&in_array($item_one->id, $category_items)?'checked':''}} type="checkbox" name="category_item_search" class="custom-control-input" value="{{$item_one->id}}">
										<span class="custom-control-indicator"></span>
										<span class="custom-control-description">@lang(ucfirst($item_one->name))</span>
									</label>
								
								@endforeach
								@endif
							</div>
							<!-- end  form group -->

							<div class="form-group  px-3 pt-3 pb-2 hidden-md-up text-center">
								<button class="btn btn-primary" onclick="changeKeyword()">
									{{trans('global.search')}}
								</button>
							</div>
						</form>
					</div>
			</div>
			<div class="col-md-8">
				<div class="siderbar-left siderbar px-2 px-sm-3 row">
					<div class="header-sider-bar mb-3">
						<h3 class="text-uppercase mb-1" id="keyq">"{{$requests&&isset($requests['q'])?$requests['q']:''}}"</h3>
						<span><span id="total">{{$total_content?$total_content:0}}</span> {{trans('Location'.DS.'search.results')}}</span>
							<!-- <select class="select-bg-gray custom-select-style-1 custom-select-style custom-select">
								<option selected>Sắp xếp</option>
								<option value="1">Lớn nhỏ</option>
								<option value="2">Nhỏ tới lơn</option>
								<option value="3">invo</option>
							</select> -->
					</div>
					<!-- end  header sidebar -->
					<nav class="menu-siderbar mb-3">
						<a class="active" href="" title="">{{trans('Location'.DS.'search.locations')}} (<span id="total_content">{{$total_content?$total_content:0}}</span>)</a>
						<!-- <a href="" title="">Bộ sưu tập(71)</a>
						<a href="" title="">Thành viên (0)</a> -->
					</nav>
					<!-- end menu-siderbar -->
					<div class="container-siderbar">
						<ul class="list-unstyled" id="contentList">
							@include('Location.search.search_list')
							<!-- end post horixontal -->
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end url -->
</div>

@section('JS')
	<script>
		var _token = $("meta[name='_token']").prop('content');
		var current_page = 1;
		var max_page = true;
		var stop = true;
		var totalPage = {{$totalPage?$totalPage:1}};
		var current_url = window.location;
		var currentLocation = window.sessionStorage.getItem('currentLocation')?window.sessionStorage.getItem('currentLocation'):false;
		function loadContentSearch(loadNew, loadMore){
			// window.event.preventDefault();
			if(loadNew !== false){
				var loadNew = loadNew || true;
			}
			var loadMore = loadMore || false;
			if(max_page){
				if(loadMore){
					current_page++;
				}
				// var query = $("#form_search").serializeArray();
				var query = [];
				if(currentLocation){
					query.push({name:'currentLocation',value:currentLocation});
				}
				if(current_page){
					query.push({name:'page',value:current_page});
				}else{
					query.push({name:'page',value:1});
				}
				query.push({name:'_token',value:_token});
				if($("input[name='q']").val()){
					query.push({name:'q',value:$("input[name='q']").val()});
				}
				if($("[name='country_search']").val()){
					query.push({name:'country',value:$("[name='country_search']").val()});
				}
				if($("[name='city_search']").val()){
					query.push({name:'city',value:$("[name='city_search']").val()});
				}
				if($("[name='district_search']").val()){
					query.push({name:'district',value:$("[name='district_search']").val()});
				}
				if($("[name='category_search']").val()){
					query.push({name:'category',value:$("[name='category_search']").val()});
				}
				if($("[name='category_item_search']:checked").length){
					var arr_category_item = [];
					$("[name='category_item_search']:checked").each(function(i){
						arr_category_item.push($(this).val());
					})
					if(arr_category_item.length){
						var str = arr_category_item.join(',');
						query.push({name:'category_item',value:str});
					}
				}
				
				var url = [];
				query.forEach(function(obj,key){
					if(obj.value != "" && obj['name'] !='_token' && obj['name'] !='page'){
						if(obj['name']=='q'){
							url.push({name:'q',value:$("input[name='q']").val()});
						}else{
							url.push(obj)
						}
						
					}
				});
				url = $.param(url)
				url = '{{url('/')}}/search'+'?'+url;
				history.pushState({}, null, url);
				current_url = url;
				$.ajax({
					url : '/search',
					type: 'POST',
					scriptCharset: "utf-8" ,
					contentType: "application/x-www-form-urlencoded; charset=UTF-8",
					data: query,
					beforeSend: function(){
						max_page = false;
						stop = false;
						$("#loading").show();
					},
					success: function(response){
						$("#loading").hide();
						$("#keyq").text('"'+response.q+'"');
						stop = true;
						max_page = true;
						if(loadNew){
							$("#contentList").html(response.html);
						}else{
							$("#contentList").append(response.html);
						}
						
						$("#total_content").html(response.total_content)
						$("#total").html(response.total_content)
						totalPage = response.totalPage;
						if(current_page==totalPage){
							max_page = false;
						}

					}
				})
			}
		}

		function changeCountrySearch(){
			var country = $("#country_search").val();
			current_page = 1;
			max_page = true;
			loadCitySearch(country);
			loadContentSearch();
		}

		function loadCitySearch(country){
			$.ajax({
				url : '/search/loadCity',
				type: 'POST',
				data: {
					_token: _token,
					country: country
				},
				success: function(response){
					$("#city_search").html(response);
					// $("#city_search").trigger("change");
				}
			})
		}

		function changeCitySearch(){
			var city = $("#city_search").val();
			current_page = 1;
			max_page = true;
			loadDistrictSearch(city);
			loadContentSearch();
		}

		function loadDistrictSearch(city){
			$.ajax({
				url : '/search/loadDistrict',
				type: 'POST',
				data: {
					_token: _token,
					city: city
				},
				success: function(response){
					$("#district_search").html(response);
					// $("#district_search").trigger("change");
				}
			})
		}

		function changeDistrictSearch(){
			current_page = 1;
			max_page = true;
			loadContentSearch();
		}

		function changeCategorySearch(){
			var category = $("#category_search").val();
			current_page = 1;
			max_page = true;
			loadCategoryItemSearch(category);
			loadContentSearch();
		}

		function loadCategoryItemSearch(category){
			$.ajax({
				url : '/search/loadCategoryItem',
				type: 'POST',
				data: {
					_token: _token,
					category: category
				},
				success: function(response){
					$("#category_item").html(response);
				}
			})
		}

		function chooseCategoryItem(){
			current_page = 1;
			max_page = true;
			loadContentSearch();
		}

		function changeKeyword(){
			current_page = 1;
			max_page = true;
			loadContentSearch();
		}

		$(function(){
			$(window).scroll(function() {
				if($(window).scrollTop() > ($(document).height() - $(window).height())-160) {
					if(current_page<totalPage && stop){
						// current_page++;
						loadContentSearch(false,true);
					}
				}
			});
		})
	</script>

<script type="text/javascript">
	jQuery(document).ready(function() {
			// sticky
			var window_width = $(window).width();

			$(document).on('click', function(event){
				if (window_width < 768) {
					if (!$(event.target).is('.form-search-restaurant, .form-search-restaurant *')) {
							$(".form-search-filter").hide();
							$('.form-search-restaurant h3').removeClass('show');
					}
				}
			});
			

			if (window_width < 768) {
					$('.form-search-restaurant h3').click(function(event) {
            event.preventDefault();
            $(this).toggleClass('show');
            $('.form-search-filter').slideToggle('fast');
	        });
	        $(".form-search-restaurant").trigger("sticky_kit:detach");
			} else {
					make_sticky();
			}

			function make_sticky() {
					$(".form-search-restaurant").stick_in_parent({
							parent: '.content-search-page',
							offset_top: 90
					});
			}

	});
</script>
@endsection

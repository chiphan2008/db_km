<div class="page-booking-content">
	<div class="container">
		<h6 class="page-title m-0 mb-4 text-center text-uppercase">
			KHÁCH SẠN {{mb_strtoupper($city->name)}}
		</h6>
		<!-- end  page title -->
		<div class="menu-filter d-md-flex justify-content-md-center my-sm-5 my-4">
			<select class="select-bg-gray custom-select-style-1 custom-select mx-sm-2 mx-1">
				<option value="all">Khu vực</option>
				@if($list_city)
				@foreach($list_city as $city_one)
				<option value="{{$city_one->alias}}" {{$city&&$city->id==$city_one->id?'selected':''}}>{{$city_one->name}}</option>
				@endforeach
				@endif
			</select>
			<!-- end select -->
			<select class="select-bg-gray custom-select-style-1 custom-select  mx-sm-2 mx-1">
				<option value="all">Loại</option>
				@if($types)
				@foreach($types as $type_one)
				<option value="{{$type_one->alias}}" {{$type&&$type->id==$type_one->id?'selected':''}}>{{$type_one->name}}</option>
				@endforeach
				@endif
			</select>
			<!-- end select -->
			<select class="select-bg-gray custom-select-style-1 custom-select  mx-sm-2 mx-1">
				<option value="">Tiện nghi</option>
				@if($list_service)
				@foreach($list_service as $service_one)
				<option value="{{$service_one->alias}}">{{$service_one->name}}</option>
				@endforeach
				@endif
			</select>
			<!-- end select -->
			<select class="select-bg-gray custom-select-style-1 custom-select  mx-sm-2 mx-1">
				<option selected>Giá</option>
				<option value="1">< 500.000</option>
				<option value="2">500.000 - 1.000.000</option>
				<option value="3">1.000.000 - 1.500.000</option>
				<option value="4">1.500.000 - 2.000.000</option>
				<option value="5">2.000.000 - 2.500.000</option>
				<option value="6">2.500.000 - 3.000.000</option>
				<option value="6">3.000.000</option>
			</select>
			<!-- end select -->
		</div>
		<!-- end booking filter -->
		<div class="page-content grid-layla">
			<div class="row">
				@if(count($hotels))
				@foreach($hotels as $hotel)
				<div class="col-lg-3 col-md-4 col-6">
					<div class="card-vertical-booking card-vertical card">
						<div class="card-img-top">
							<a href="" title="">
								<img class="img-fluid" src="{{$hotel->_content->avatar}}" alt="Card image cap">
							</a>
							 <div class="price">
								<span>{{isset($hotel->_room_types[0])?money_number($hotel->_room_types[0]->price):0}} VNĐ</span>
								<s>{{isset($hotel->_room_types[0])?money_number($hotel->_room_types[0]->price_km):0}} VNĐ</s>
							</div>
						</div>
						<div class="card-block py-2 px-0">
							<div class="card-description">
								<h6 class="card-title "><a href="" title="">{{$hotel->_content->name}}</a></h6>
								<p class="card-address text-truncate">{{$hotel->_content->address}}, {{$hotel->_content->_district->name}}, {{$hotel->_content->_city->name}}, {{$hotel->_content->_country->name}}</p>
							</div>
							<div class="meta-post d-flex align-items-center">
								<div class="add-like d-flex align-items-center">
									<i class="icon-heart-empty"></i>
									<span>{{$hotel->_content->like?$hotel->_content->like:0}}</span>
								</div>
								<div class="rating d-flex align-items-center">
									<div class="star-rating hidden-xs-down">
										<span style="width:{{$hotel->_content->vote?($hotel->_content->vote*100)/5:0}}%"></span>
									</div>
									<i class="icon-star-yellow hidden-sm-up"></i>
									<span>({{$hotel->_content->vote?$hotel->_content->vote:0}})</span>
								</div>
							</div>
						</div>
					</div>
					<!-- end card post -->
				</div>
				@endforeach
				@else
				<h5 class="text-center col-12">Không tìm thấy khách sạn</h5>
				@endif
			</div>
		</div>
		<!-- end  page content -->
	</div>
</div>
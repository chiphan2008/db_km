<div class="p-sm-4">
	<!-- Nav tabs -->
	<ul class="list-unstyled tab-upload-image-nav" id="tab-manager-location" role="tablist">
		<li class="nav-item">
			<a class="nav-link text-uppercase active" data-toggle="tab" href="#san-pham-dich-vu" role="tab">{{trans('Location'.DS.'layout.products_services')}}</a>
		</li>
		<li class="nav-item">
			<a class="nav-link text-uppercase" data-toggle="tab" href="#khuyen-mai" role="tab">{{trans('Location'.DS.'layout.discount')}}</a>
		</li>
		<li class="nav-item">
			<a class="nav-link text-uppercase" data-toggle="tab" href="#chi-nhanh" role="tab">{{trans('Location'.DS.'layout.branch')}}</a>
		</li>
	</ul>
	<!-- Tab panes -->
	<div class="tab-content">
		<div class="tab-pane active" id="san-pham-dich-vu" role="tabpanel">
			<div class="item-add-service d-flex align-self-center mb-3">
				<div class="img">
					<div class="box-img-upload" onclick="$('#product_image').click()">
						<div class="box-img-upload-content">
							<i class="icon-new-white"></i>
							<p>{{trans('Location'.DS.'layout.choose_image')}}</p>
						</div>
					</div>
					<input id="product_image" class="manage-upload" type="file" accept="image/*" style="width: 0;height:0;visibility: hidden;">
				</div>
				<div class="content">
					<input type="text" maxlength="128" id="product_name" autocomplete="new-value" class="form-control w-100" placeholder="{{trans('Location'.DS.'layout.name')}}:">
					<input type="text" maxlength="128" id="product_des" autocomplete="new-value" class="form-control w-100" placeholder="{{trans('Location'.DS.'layout.description_product')}}:">
					<input type="number" max="999999999" min="1" id="product_price" class="form-control w-100" placeholder="{{trans('Location'.DS.'layout.price')}}:" autocomplete="new-value">
					<input type="hidden" id="product_id" value="0">
					<div class="text-danger py-3" id="product_error"></div>
				</div>
			</div>
			<div class="mt-2 mt-sm-2">
				<button class="btn btn-create" onclick="addProduct()" id="product_button">
					<i class='icon-new-white'></i> {{trans('Location'.DS.'layout.add_products_services')}}
				</button>
			</div>
			<div class=" mt-4">
				<div class="scroll-content-modal" style="height: 230px;">
				<div class="row">
					<? $list_product = isset($list_product)?$list_product:[]; ?>
					@foreach($list_product as $group)
						@foreach($group as $key => $product)
							@if($key !== 'group_name')
							<div class="col-md-4 col-6">
								<div class="card-vertical card">
									<a href="#" class="remove-manager" onclick="removeProduct({{$product->id}})"><i class="fa fa-remove"></i></a>
									<div data-json='{!! json_encode($product->toArray()); !!}' onclick="updateProduct(this)">
										<div class="card-img-top">
											<a href="#" title="" >
												<img class="img-fluid" src="{{$product->image}}" alt="{{$product->name}}">
											</a>
										</div>
										<div class="card-block py-2 px-0">
											<div class="card-description">
												<h6 class="card-title "><a href="#" title="">{{$product->name}}</a></h6>
												<p class="card-address text-truncate">{{money_number($product->price)}} {{$product->currency}}</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							@endif
						@endforeach
					@endforeach
				</div>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="khuyen-mai" role="tabpanel">
			<div class="item-add-service d-flex align-self-center mb-3">
				<div class="img">
					<div class="box-img-upload" onclick="$('#discount_manager_image').click()">
						<div class="box-img-upload-content">
							<i class="icon-new-white"></i>
							<p>{{trans('Location'.DS.'layout.choose_image')}}</p>
						</div>
					</div>
					<input id="discount_manager_image" class="manage-upload" type="file" accept="image/*" style="width: 0;height:0;visibility: hidden;">
				</div>
				<div class="content">
					<input type="text" maxlength="128" id="discount_manager_name" autocomplete="new-value" class="form-control w-100" placeholder="{{trans('Location'.DS.'layout.name')}}:">
					<input type="text" maxlength="128" id="discount_manager_des" autocomplete="new-value" class="form-control w-100" placeholder="{{trans('Location'.DS.'layout.description_product')}}:">
					<input type="number" max="999999999" min="1" id="discount_manager_price" class="form-control w-100" placeholder="{{trans('Location'.DS.'layout.price')}}:" autocomplete="new-value">
					<input type="hidden" id="discount_manager_id" value="0">
					<div class="text-danger py-3" id="discount_manager_error"></div>
				</div>
			</div>
			<div class="mt-2 mt-sm-2">
				<button class="btn btn-create" onclick="addDiscount()" id="discount_manager_button">
					<i class='icon-new-white'></i> {{trans('Location'.DS.'layout.add_discount')}}
				</button>
			</div>
			<div class="mt-4">
				<div class="scroll-content-modal" style="height: 230px;">
				<div class="row ">
					<? $list_discount = isset($list_discount)?$list_discount:[]; ?>
					@foreach($list_discount as $discount)
					<div class="col-md-4 col-6">
						<div class="card-vertical card">
							<a href="#" class="remove-manager" onclick="removeDiscount({{$discount->id}})"><i class="fa fa-remove"></i></a>
							<div  data-json='{!! json_encode($discount->toArray()); !!}'  onclick="updateDiscount(this)">
								<div class="card-img-top">
									<a href="#" title="">
										<img class="img-fluid" src="{{$discount->image}}" alt="{{$discount->name}}">
									</a>
								</div>
								<div class="card-block py-2 px-0">
									<div class="card-description">
										<h6 class="card-title "><a href="#" title="">{{$discount->name}}</a></h6>
										<p class="card-address text-truncate">{!! $discount->description !!}</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					@endforeach
				</div>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="chi-nhanh" role="tabpanel">
			<div class="">
			<div class="mt-4">
				<div class="row">
					<? $list_group = isset($list_group)?$list_group:[]; ?>
					@foreach($list_group as $content)
					<div class="col-md-4 col-6">
						<div class="card-vertical card">
							<a href="#" class="remove-manager" onclick="removeBranch({{$content->id}})"><i class="fa fa-remove"></i></a>
							<div class="card-img-top">
								<a href="#" title="">
									<img class="img-fluid" src="{{str_replace('img_content','img_content_thumbnail',$content->avatar)}}" alt="{{$content->name}}">
								</a>
							</div>
							<div class="card-block py-2 px-0">
								<div class="card-description">
									<h6 class="card-title "><a href="#" title="">{{$content->name}}</a></h6>
									<p class="card-address text-truncate">{{$content->address}}, {{$content->_district->name}}, {{$content->_city->name}}, {{$content->_country->name}}</p>
								</div>
							</div>
						</div>
						<!-- end card post -->
					</div>
					@endforeach
				</div>
			</div>
			</div>
			<button data-toggle="modal" data-target="#modal-add-location-same-sytem" onclick="$('#modal-manager-location').modal('hide');$('body').addClass('modal-open-custom');" class="btn btn-create btn-create-service mt-2 mt-sm-5"><i class="icon-new-white"></i> {{trans('Location'.DS.'layout.add_same_system')}}</button>
		</div>
	</div>
</div>

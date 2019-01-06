@section('body')
	<body>
@endsection
	<div class="content-location-detail content-page">
			<div class="sidebar-top hidden-sm-down mb-2 mb-sm-4 mt-2 mt-sm-3 py-2">
					<div class="container d-flex align-items-md-start align-items-center">
							<a class="come-back" href="{{url()->previous()}}" title="">
									<i class="icon-left mr-2"></i>
									{!! $breadcrumb !!}
							</a>
					</div>
			</div>
			<header class="header-detail ">
				<div class="container d-lg-flex justify-lg-content-between">
					<div class="header-detail-left d-flex align-items-center align-items-lg-start align-items-center">
						<div class="avata text-center">
							<a href="" title="">
								<div class="rounded-circle" style="
												background-image: url('{{asset($content->avatar)}}');
												width:80px; height:80px;
												background-size: 152px 80px;
												background-repeat: no-repeat;
												background-size: cover;
												background-position: center;
												">
													
												</div>

												<div class="offline status-location">
													<i class="icon-circle"></i>
													<span>{{trans('Admin'.DS.'content.'.$content->moderation)}}</span>
												</div>
							</a>
						</div>
						<h1 class="title-restaurant hidden-lg-up">{{$content->name}}</h1>
					</div>
					<div class="content mb-4">
						<div class="d-lg-flex justify-content-lg-between align-items-lg-start ">
							<div class="content-left pr-0 pr-lg-3" style="width: 100%;">
								<h1 class="title-restaurant hidden-md-down">{{$content->name}}</h1>
								<ol class="info-contact list-unstyled mb-3 mb-lg-0">
									<li>
										<i class="icon-location"></i>
										<p>
											{{$content->address}}, {{$content->_district?$content->_district->name:''}}, {{$content->_city?$content->_city->name:''}}, {{$content->_country?$content->_country->name:''}}
										</p>
									</li>
									@if($content->phone)
									<!-- <li>
										<i class="icon-phone"></i>
										<p>
											{{$content->phone}}
										</p>
									</li> -->
									@endif
									@if($content->email)
									<!-- <li>
										<i class="icon-mail"></i>
										<p>
											{{$content->email}}
										</p>
									</li> -->
									@endif
									<li>
										@foreach(explode(",",$open_time) as $key => $open_line)
										@if($key==0)
										<p>
											<i class="icon-time"></i>
											{{ $open_line }}
										</p>
										@else
										<p>
											<span style="width:20px;display:inline-block;">&nbsp;</span>
											{{ $open_line }}
										</p>
										@endif
										@endforeach
									</li>
									<li>
										{{$content->description?$content->description:trans('global.content_is_update')}}
									</li>
								</ol>
								<!-- start user interaction -->
							</div>
							<ol class="user-interaction content-right list-unstyled hidden-md-down">
								<li>
									<div class="meta-post d-flex align-items-center">
										<div class="add-like d-flex align-items-center">
											<i class="{{isset($like_point) ? 'icon-heart' : 'icon-heart-empty' }}"></i>
											<span class='point_like'>({{$content->like}})</span>
										</div>
										<div class="">
										</div>
										<div class="rating d-flex align-items-center" data-vote="0">
											<div class="star hidden">
												<span class="full {{(isset($vote_point) && $vote_point>=1) ? 'star-colour' : ''}}"
															data-value="1"></span>
												<span class="half {{(isset($vote_point) && $vote_point>=0.5) ? 'star-colour' : ''}}"
															data-value="0.5"></span>
											</div>
											<div class="star">
												<span class="full {{(isset($vote_point) && $vote_point>=2) ? 'star-colour' : ''}}"
															data-value="2"></span>
												<span class="half {{(isset($vote_point) && $vote_point>=1.5) ? 'star-colour' : ''}}"
															data-value="1.5"></span>
												<span class="selected"></span>
											</div>
											<div class="star">
												<span class="full {{(isset($vote_point) && $vote_point>=3) ? 'star-colour' : ''}}"
															data-value="3"></span>
												<span class="half {{(isset($vote_point) && $vote_point>=2.5) ? 'star-colour' : ''}}"
															data-value="2.5"></span>
												<span class="selected"></span>
											</div>
											<div class="star">
												<span class="full {{(isset($vote_point) && $vote_point>=4) ? 'star-colour' : ''}}"
															data-value="4"></span>
												<span class="half {{(isset($vote_point) && $vote_point>=3.5) ? 'star-colour' : ''}}"
															data-value="3.5"></span>
												<span class="selected"></span>
											</div>
											<div class="star">
												<span class="full {{(isset($vote_point) && $vote_point>=5) ? 'star-colour' : ''}}"
															data-value="5"></span>
												<span class="half {{(isset($vote_point) && $vote_point>=4.5) ? 'star-colour' : ''}}"
															data-value="4.5"></span>
												<span class="selected"></span>
											</div>
											<span class="star-number">&nbsp;&nbsp;({{$content->vote}})</span>
										</div>
									</div>
									<!-- end  meta -->
								</li>
								<li>
                    <span><i class="icon-location"></i></span>
                    {{trans('Location'.DS.'content.distance')}} <b><span class="distance"></span></b>
                </li>
								<li>
									<span><i class="icon-check"></i></span>
									<a class="cursor" id="checkin">Check in 
									</a>
									(<span class="checkin_total">{{$content->checkin}}</span>)
								</li>
								<li>
									<span><i class="icon-target"></i></span>
									<a class="cursor">
										{{trans('Location'.DS.'content.save_favorites')}}
									</a>
									(<span class="save_like_content_total">{{$content->save_like_content>0?$content->save_like_content:0}}</span>)
								</li>
								<li>	
									<div class="dropdown-collection">
											<span><i class="icon-save"></i></span> <a style="cursor: pointer;" title="{{trans('Location'.DS.'content.add_collection')}}"
											
											>{{trans('Location'.DS.'content.add_collection')}}</a> (<span>{{$count_collection?$count_collection:0}}</span>)
											<div class="dropdown-menu-collection dropdown-menu  p-3 pr-4">
													<h5 class="text-uppercase my-3">{{trans('Location'.DS.'content.create_collection')}}</h5>
													<form action="" class="form-create-gallery ">
															<div class="form-inline row">
																	<div class="form-group col-sm-9">
																			<input type="text" class="form-control w-100 collectionName_1" placeholder="{{trans('Location'.DS.'content.collection_name')}}">
																	</div>
																	<button type="button" class="btn btn-primary col-sm-3"><i class="icon-new-white"></i></button>
															</div>
															<!-- end  form inline -->
														<div class="">
															@if($collections)
															<h5 class="text-uppercase my-3">{{trans('Location'.DS.'content.add_collection')}}</h5>
															<div class="form-group mb-0 collectionList">
																	@foreach($collections as $col)
																	<div class="form-check">
																			<label class="custom-control custom-radio">
																					<input
																					data-collection="{{$col->id}}"
																					data-content="{{$content->id}}"
																					onchange="changeCollection(this)" name="checkbox" type="checkbox" class="custom-control-input" {{$col->check?'checked="checked"':''}}>
																					<span class="custom-control-indicator"></span>
																					<span class="custom-control-description">{{$col->name}} - ({{$col->_contents->count()}})</span>
																			</label>
																	</div>
																	@endforeach
															</div>
															@endif
														</div>
															<!-- end  form group -->
															<!-- <button type="submit" class="btn btn-primary w-100">Save</button> -->
													</form>
													<!-- end form create gallery -->
											</div>
									</div>
								</li>
								<!-- <li>
									<span><i class="icon-commenting-o"></i></span>
									Chat Online
								</li> -->
								<li>
									<!-- <span><i class="icon-share-grey"></i></span> {{trans('Location'.DS.'content.share')}}: -->
									<a href="#"><i class="icon-google"></i>&nbsp;&nbsp;&nbsp;&nbsp;
									</a>
									<a href="#">
									<i class="icon-facebook"></i>&nbsp;&nbsp;&nbsp;&nbsp;
									</a>
									<a href="#">
									<i class="icon-twitter-bird"></i>&nbsp;&nbsp;&nbsp;&nbsp;
									</a>
								</li>
							</ol>
							<!-- end user interaction -->
							<div class="content-right-mobile hidden-lg-up">
								<!-- <div class="mb-3">
									 <a class="btn btn-primary" href="" title="">Chat trực tuyến</a>
									<a href="" class="btn btn-share"><i class="icon-share-grey"></i></a>
								</div> -->

								<ol class="user-interaction content-right list-unstyled ">
									<li>
										<div class="meta-post d-flex align-items-center">
											<div class="add-like d-flex align-items-center">
												<i class="{{isset($like_point) ? 'icon-heart' : 'icon-heart-empty' }}"></i>
												<span class='point_like'>{{$content->like}}</span>
											</div>
											<div class="rating d-flex align-items-center" data-vote="0">
												<div class="star hidden">
												<span class="full {{(isset($vote_point) && $vote_point>=1) ? 'star-colour' : ''}}"
													  data-value="1"></span>
													<span class="half {{(isset($vote_point) && $vote_point>=0.5) ? 'star-colour' : ''}}"
														  data-value="0.5"></span>
												</div>
												<div class="star">
												<span class="full {{(isset($vote_point) && $vote_point>=2) ? 'star-colour' : ''}}"
													  data-value="2"></span>
													<span class="half {{(isset($vote_point) && $vote_point>=1.5) ? 'star-colour' : ''}}"
														  data-value="1.5"></span>
													<span class="selected"></span>
												</div>
												<div class="star">
												<span class="full {{(isset($vote_point) && $vote_point>=3) ? 'star-colour' : ''}}"
													  data-value="3"></span>
													<span class="half {{(isset($vote_point) && $vote_point>=2.5) ? 'star-colour' : ''}}"
														  data-value="2.5"></span>
													<span class="selected"></span>
												</div>
												<div class="star">
												<span class="full {{(isset($vote_point) && $vote_point>=4) ? 'star-colour' : ''}}"
													  data-value="4"></span>
													<span class="half {{(isset($vote_point) && $vote_point>=3.5) ? 'star-colour' : ''}}"
														  data-value="3.5"></span>
													<span class="selected"></span>
												</div>
												<div class="star">
												<span class="full {{(isset($vote_point) && $vote_point>=5) ? 'star-colour' : ''}}"
													  data-value="5"></span>
													<span class="half {{(isset($vote_point) && $vote_point>=4.5) ? 'star-colour' : ''}}"
														  data-value="4.5"></span>
													<span class="selected"></span>
												</div>
												<span class="star-number">&nbsp;&nbsp;({{$content->vote}})</span>
											</div>
										</div>
										<!-- end  meta -->
									</li>
									<li>
	                    <span><i class="icon-location"></i></span>
	                    {{trans('Location'.DS.'content.distance')}} <b><span class="distance"></span></b>
	                </li>
									<li>
										<span><i class="icon-check"></i></span>
										<a class="cursor" id="checkin">Check in
										</a>
										(<span class="checkin_total">{{$content->checkin}}</span>)
									</li>
									<li>
										<span><i class="icon-target"></i></span>
										<a class="cursor">
											{{trans('Location'.DS.'content.save_favorites')}}
										</a>
										(<span class="save_like_content_total">{{$content->save_like_content>0?$content->save_like_content:0}}</span>)
									</li>
									<li>
										<div class="dropdown-collection">
											<span><i class="icon-save"></i></span> <a style="cursor: pointer;" title="{{trans('Location'.DS.'content.add_collection')}}"
											>{{trans('Location'.DS.'content.add_collection')}}</a> (<span>{{$count_collection?$count_collection:0}}</span>)
											<div class="dropdown-menu-collection dropdown-menu  p-3 pr-4">
												<h5 class="text-uppercase my-3">{{trans('Location'.DS.'content.create_collection')}}</h5>
												<form action="" class="form-create-gallery ">
													<div class="form-inline row">
														<div class="form-group col-sm-9">
															<input type="text" class="form-control w-100 collectionName_1 collectionName_mobile_1" placeholder="{{trans('Location'.DS.'content.collection_name')}}">
														</div>
														<button type="button" class="btn btn-primary col-sm-3"><i class="icon-new-white"></i></button>
													</div>
													<!-- end  form inline -->
													<div class="">
													@if($collections)
														<h5 class="text-uppercase my-3">{{trans('Location'.DS.'content.add_collection')}}</h5>
														<div class="form-group mb-0 collectionList">
															@foreach($collections as $col)
																<div class="form-check">
																	<label class="custom-control custom-radio">
																		<input
																				data-collection="{{$col->id}}"
																				data-content="{{$content->id}}"
																				onchange="changeCollection(this)" name="checkbox" type="checkbox" class="custom-control-input" {{$col->check?'checked="checked"':''}}>
																		<span class="custom-control-indicator"></span>
																		<span class="custom-control-description">{{$col->name}} - ({{$col->_contents->count()}})</span>
																	</label>
																</div>
															@endforeach
														</div>
												@endif
													</div>
												<!-- end  form group -->
													<!-- <button type="submit" class="btn btn-primary w-100">Save</button> -->
												</form>
												<!-- end form create gallery -->
											</div>
										</div>
									</li>
								</ol>
								<!-- end  list group pd -->
								<ul class="list-info-restaurant list-unstyled clearfix ">
									@foreach($list_service as $value)
										<li class="{{!in_array($value->id_service_item, $service_content) ? 'disabled':''}}">@lang(mb_ucfirst($value->_service_item->name))
										</li>
									@endforeach
								</ul>
							</div>
							<!-- end  content right mobile -->
						</div>
					</div>
				</div>
			</header>
			
			@if($category->show_khong_gian||$category->show_hinh_anh||$category->show_video||$category->show_san_pham)
			<section class="section-space bg-gray my-4 px-md-4">
				<div class="container">
						
						@if($category->show_khong_gian)
						<div class="box-gallery">
							<div class="title-gallery d-flex justify-content-between align-items-start">
								<h4 class="text-uppercase mb-4">{{mb_strtoupper(trans('global.space'))}} ({{count($image_space)}})</h4>
								@if(count($image_space) > 0)
								<a href="{{url('detail-photo/'.$content->alias.'/space')}}" title="">
									{{ucfirst(trans('global.view_all'))}} 
									<i class="icon-ic-arrow"></i>
								</a>
								@endif
							</div>
							@if(count($image_space) > 0)
							<ul class="list-gallery list-unstyled row gallery-item">
								@foreach($image_space as $value)
									<li>
										<a data-fancybox="space" data-caption="sss" href="{{$value->name}}">
											<img class="img-fluid" data-lazy="{{str_replace('img_content','img_content_thumbnail',$value->name)}}" src="{{str_replace('img_content','img_content_thumbnail',$value->name)}}" alt="">
											<figcaption>
												<h2 class="gallery-item-title">
													{{$value->title}}
												</h2>
												<p class="gallery-item-description">
													{{$value->description}}
												</p>
												{{--<span class="gallery-item-price font-weight-bold">--}}
												{{--600,000đ--}}
												{{--</span>--}}
											</figcaption>

										</a>
									</li>
								@endforeach
							</ul>
							@else
							<h6 style="color: #5b89abb8;" class="font-italic">{{trans('global.content_is_update')}}</h6>
							@endif
						</div>
						@endif

						@if($category->show_hinh_anh)
						<div class="box-gallery">
							<div class="title-gallery d-flex justify-content-between align-items-start">
								<h4 class="text-uppercase mb-4">{{mb_strtoupper(trans('global.image'))}} ({{count($image_menu)}})</h4>
								@if(count($image_menu) > 0)
								<a href="{{url('detail-photo/'.$content->alias.'/menu')}}"
									 title="">
										{{ucfirst(trans('global.view_all'))}} 
										<i class="icon-ic-arrow"></i>
								</a>
								@endif
							</div>
							@if(count($image_menu) > 0)
							<ul class="list-gallery list-unstyled row gallery-item">
								 @foreach($image_menu as $value)
									<li>
										<a data-fancybox="menu" data-caption="" href="{{$value->name}}">
											<img class="img-fluid" data-lazy="{{str_replace('img_content','img_content_thumbnail',$value->name)}}" src="{{str_replace('img_content','img_content_thumbnail',$value->name)}}" alt="">
											<figcaption>
												<h2 class="gallery-item-title">
													{{$value->title}}
												</h2>
												<p class="gallery-item-description">
													{{$value->description}}
												</p>
												{{--<span class="gallery-item-price font-weight-bold">--}}
												{{--600,000đ--}}
												{{--</span>--}}
											</figcaption>
										</a>
									</li>
								 @endforeach
							</ul>
							@else
							<h6 style="color: #5b89abb8;" class="font-italic">{{trans('global.content_is_update')}}</h6>
							@endif
						</div>
						@endif
						
						@if($category->show_video)
						<div class="box-gallery">
							<div class="title-gallery d-flex justify-content-between align-items-start">
								<h4 class="text-uppercase mb-4">{{mb_strtoupper(trans('global.video'))}} ({{count($link_video)}})</h4>
								@if(count($link_video) > 0)
								<a href="{{url('detail-photo/'.$content->alias.'/video')}}"
									 title="">{{ucfirst(trans('global.view_all'))}}
									<i class="icon-ic-arrow"></i>
								</a>
								@endif
							</div>
							@if(count($link_video) > 0)
							<ul class="list-gallery list-unstyled row">
								@foreach($link_video as $value)
									@if ($value->type == 'facebook')
										<li class="iframe-video">
											<a data-video-facebook data-type="iframe" href="https://www.facebook.com/plugins/video.php?href={{$value->link}}&show_text=false&height=232&width=auto&allowfullscreen=false">
												 <img src="{{$value->thumbnail?$value->thumbnail:''}}" alt="">
												 <span class="ytp-time-duration">{{$value->time?$value->time:''}}</span>
											</a>
											<p>
												<a data-video-facebook data-type="iframe" href="https://www.facebook.com/plugins/video.php?href={{$value->link}}&show_text=false&height=232&width=auto&allowfullscreen=false">
													{{$value->title?$value->title:''}}
												</a>
											</p>
										</li>
									@elseif($value->type == 'youtube')
										@php
											$link = $value->link;
											$link = str_replace('watch?v=','',$link);
											$link = str_replace('youtube.com/','youtube.com/embed/',$link);
											$link = str_replace('youtu.be/','youtube.com/embed/',$link);
											$link = clear_youtube_link($link);
										@endphp
										<li class="iframe-video">
											<a data-video href="{{$link}}">
												 <img src="{{$value->thumbnail?$value->thumbnail:''}}" alt="">
												 <span class="ytp-time-duration">{{$value->time?$value->time:''}}</span>
											</a>
											<p>
												<a data-video href="{{$link}}">
													{{$value->title?$value->title:''}}
												</a>
											</p>
										</li>
									@endif
								@endforeach
							</ul>
							@else
							<h6 style="color: #5b89abb8;" class="font-italic">{{trans('global.content_is_update')}}</h6>
							@endif
						</div>
						@endif
						
						@if($category->show_san_pham)
						<div class="box-gallery">
							<div class="title-gallery d-flex justify-content-between align-items-start">
								<h4 class="text-uppercase mb-4">{{mb_strtoupper(trans('global.product_service'))}} ({{$count_list_product}})</h4>
								@if(count($list_product) > 0)
								<a href="{{url('detail-photo/'.$content->alias.'/product')}}" title="">
									{{ucfirst(trans('global.view_all'))}} 
									<i class="icon-ic-arrow"></i>
								</a>
								@endif
							</div>
							@if(count($list_product) > 0)
							<div class="section-menu-content">
								@foreach($list_product as $key_group => $group)
								<!-- start  location list menu -->
								<div class="location-list-menu">
									@if($key_group !== 'no_group')
									<h5 class="location-list-menu-title">{{$group['group_name']}}</h5>
									@endif
									<ul class="list-product-location list-unstyled row list-gallery gallery-item">
										@foreach($group as $key_product => $product)
										@if($key_product !== 'group_name')
										<li class="content-product-location col-6 col-sm-4 col-lg-2">
												<div class="img mb-2">
													<a data-fancybox="product" data-caption="" href="{{$product->image}}" title="{{mb_ucfirst($product->name)}} - {{money_number($product->price)}}  {{$product->currency}}">
															<img class="img-fluid" data-lazy="{{str_replace('/product/','/product_thumbnail/',$product->image)}}" src="{{str_replace('/product/','/product_thumbnail/',$product->image)}}" alt="">
														<figcaption>
															<h2 class="gallery-item-title">
																{{mb_ucfirst($product->name)}}
															</h2>
															<p class="gallery-item-description">
																{{$product->description}}
															</p>
															<span class="gallery-item-price font-weight-bold">
                                    							{{money_number($product->price)}} {{$product->currency}}
                                							</span>
														</figcaption>
													</a>
												</div>
												<div class="content">
													<div class="title mb-2">
														<a href="#">
															{{(strlen(mb_ucfirst($product->name)) > 17) ? mb_substr(mb_ucfirst($product->name),0,17).'...' :mb_ucfirst($product->name)}}
														</a>
													</div>
													<div class="price">
														{{money_number($product->price)}}  {{$product->currency}}
													</div>
												</div>
										</li>
										@endif
										@endforeach
									</ul>
								</div>
								<!-- end  location list menu -->
								@endforeach
								<!-- <div class="readmore text-center">
									<a href="">Xem thêm</a>
								</div> -->
							</div>
							@else
							<h6 style="color: #5b89abb8;" class="font-italic">{{trans('global.content_is_update')}}</h6>
							@endif
						</div>
						@endif
						
				</div>
			</section>
			@endif
		@if($category->show_khuyen_mai)
		<section class="my-4 my-md-5">
			<div class="container">
				<div class="title-gallery">
					<h4 class="text-uppercase mb-4">{{mb_strtoupper(trans('global.discount'))}} ({{count($discounts)}})</h4>
				</div>
				@if(count($discounts) > 0)
				<div class="section-menu-content">
					<div class="location-list-menu">
						<div class="location-list-menu-content clearfix gallery-item">
							@foreach($discounts as $discount)
							<div class="card-horizontal-sm d-flex align-items-top pb-3 mb-3">
								<div class="img">
									<a data-fancybox="discount" data-caption="sss" href="{{$discount->image}}" title="{{mb_ucfirst($discount->name)}}">
										<img data-lazy="{{$discount->image}}" src="{{$discount->image}}" alt="{{mb_ucfirst($discount->name)}}">
										<figcaption>
											<h2 class="gallery-item-title">
												{{mb_ucfirst($discount->name)}}
											</h2>
											<p class="gallery-item-description">
												{{$discount->description}}
											</p>
											<span class="gallery-item-price font-weight-bold">
                                    							{{money_number($discount->price)}} {{$discount->currency}}
                                							</span>
										</figcaption>
									</a>
								</div>
								<div class="content pl-2">
									<!-- <a class="title d-block mb-1" href=""> -->
										<span class="title d-block mb-1">
											{{(strlen(mb_ucfirst($discount->name)) > 25) ? mb_substr(mb_ucfirst($discount->name),0,25).'...' :mb_ucfirst($discount->name)}}
										</span>
									<!-- </a> -->
									<span class="desscription">
										{{(strlen($discount->description) > 34) ? mb_substr($discount->description,0,34).'...' :$discount->description}}
									</span>
								</div>
							</div>     
							@endforeach          
						</div>
					</div>
				</div>
				@else
				<h6 style="color: #5b89abb8;" class="font-italic">{{trans('global.content_is_update')}}</h6>
				@endif
			</div>
		</section>
		@endif

		<section class="my-4 my-md-5 hidden-lg-down">
			<div class="container">
				<div class="row">
					<div class="col-md-12 flex-lg-first">
						<ul class="list-info-restaurant list-unstyled clearfix">
							@foreach($list_service as $value)
								<li class="{{!in_array($value->id_service_item, $service_content) ? 'disabled':''}}">@lang(mb_ucfirst($value->_service_item->name))
								</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- end -->
		<section class="my-4 my-md-5">
			<div class="container">
				<div id="map-2"></div>
			</div>
		</section>

		<section class="my-2 my-md-2">
			<div class="container">
				<div class="row">
					<div class="col-12">
						@if(isset($ads[0]))
							@php
							 $ads_show = $ads[0]; 
							@endphp
							<a href="{{$ads_show->choose_type=='content'?url($ads_show->_base_content->alias):$ads_show->link}}">
								<img src="{{$ads_show->image}}" alt=""  style="max-width: 1170px; max-height: 230px; width:100%;">
							</a>
						@else
							<!-- <a href=""> -->
								<img src="{{url($type_ads->img_default)}}" alt="" style="max-width: 1170px; max-height: 230px; width:100%;">
							<!-- </a> -->
						@endif
					</div>
				</div>
			</div>
		</section>
		<!-- end  -->
				
		@if($category->show_chi_nhanh)
		<section class="my-4 my-md-5">
			<div class="container">
				<div class="box-gallery mb-4">
					<div class="title-gallery">
						<h4 class="m-0 text-uppercase mb-2">{{trans('Location'.DS.'content.other_branch')}}</h4>
					</div>
				</div>
				@if(count($list_group) > 0)
				<ul class="slider-gallery group-card-vertical row list-unstyled">
				@foreach($list_group as $value)
					<li class="col-lg-3 col-md-4 col-6">
						<div class="card-vertical card">
							<div class="card-img-top">
								<a href="{{url($value->alias)}}" title="{{url($value->alias)}}">
									<img class="img-fluid"
											 src="{{str_replace('img_content','img_content_thumbnail',$value->avatar)}}"
											 alt="{{$value->name}}" style='max-width: 270px; max-height:202px;'>
								</a>
							</div>
							<div class="card-block py-2 px-0">
								<div class="card-description">
									<h6 class="card-title "><a href="{{url($value->alias)}}" title="{{$value->name}}">{{$value->name}} </a>
									</h6>
									<p class="card-address text-truncate">{{$value->address}}, {{$value->_district->name}}, {{$value->_city->name}}, {{$value->_country->name}}</p>
								</div>
								<div class="meta-post d-flex align-items-center">
									<div class="add-like d-flex align-items-center">
										<i class="icon-heart-empty"></i>
										<span class='point_like'>({{$value->like}})</span>
									</div>
									<div class="rating d-flex align-items-center">
										<div class="star-rating hidden-xs-down">
											<span style="width:{{($value->vote*20).'%'}}"></span>
										</div>
										<i class="icon-star-yellow hidden-sm-up"></i>
										<span>({{$value->vote}})</span>
									</div>
								</div>
							</div>
						</div>
						<!-- end card post -->
					</li>
				@endforeach
				</ul>
				@else
				<h6 style="color: #5b89abb8;" class="font-italic">{{trans('global.content_is_update')}}</h6>
				@endif
			</div>
		</section> 
		@endif

			<!-- end -->
		@if(count($list_suggest) > 0)
			<section class="my-4 my-md-5">
				<div class="container">
					<div class="box-gallery mb-4">
						<div class="title-gallery">
							<h4 class="m-0 text-uppercase mb-2">{{trans('Location'.DS.'content.suggest')}}</h4>
						</div>
					</div>
					
					<ul class="slider-gallery group-card-vertical row list-unstyled">
						@foreach($list_suggest as $value)
							<li class="col-lg-3 col-md-4 col-6">
								<div class="card-vertical card">
									<div class="card-img-top">
										<a href="{{url($value->alias)}}" title="{{url($value->alias)}}">
											<img class="img-fluid"
													 src="{{str_replace('img_content','img_content_thumbnail',$value->avatar)}}"
													 alt="{{$value->name}}" style='max-width: 270px; max-height:202px;'>
										</a>
									</div>
									<div class="card-block py-2 px-0">
										<div class="card-description">
											<h6 class="card-title "><a href="{{url($value->alias)}}" title="{{$value->name}}">{{$value->name}} </a>
											</h6>
											<p class="card-address text-truncate">{{$value->address}}, {{$value->_district->name}}, {{$value->_city->name}}, {{$value->_country->name}}</p>
										</div>
										<div class="meta-post d-flex align-items-center">
											<div class="add-like d-flex align-items-center">
												<i class="icon-heart-empty"></i>
												<span>({{$value->like}})</span>
											</div>
											<div class="rating d-flex align-items-center">
												<div class="star-rating hidden-xs-down">
													<span style="width:{{($value->vote*20).'%'}}"></span>
												</div>
												<i class="icon-star-yellow hidden-sm-up"></i>
												<span>({{$value->vote}})</span>
											</div>
										</div>
									</div>
								</div>
								<!-- end card post -->
							</li>
						@endforeach
					</ul>
				</div>
			</section>
		@endif
	</div>

	<div id="modal-notify-content" class="modal fade modal-vertical-middle modal-report show modal-animation" data-backdrop="false"  tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-md" style='background:#fff;'>
			<div class="modal-content p-4">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<!-- close -->
				<div class="modal-logo pt-4 text-center">
					<img src="{{isset($notify_content) ? asset($content->avatar) : ''}}" alt="">
				</div>
				<!-- end logo -->
				<h4 class="text-uppercase text-center">{{trans('Location'.DS.'content.notification')}}</h4>
				<hr>
				<p>{{isset($notify_content) ? $notify_content : ''}}</p>
				<!-- end  form nitification location -->
			</div>
			<!-- end  modal content -->
		</div>
	</div>
	@if(Auth::guard('web_client')->user() && $content->type_user == 0 && $content->created_by ==  Auth::guard('web_client')->user()->id)
	<a class="btn-edit-location btn-edit btn btn-radius" target="_blank" href="/edit/location/{{$content->id}}" title="{{trans('Location'.DS.'content.update_location')}}">
		<i class="icon-ic-edit"></i>
	</a>
	@endif

	@section('JS')
		<style>
			.fancybox-caption-wrap{
				pointer-events: auto !important;
				min-height: 150px;
			}
			.fancybox-slide--iframe .fancybox-content{
				background-color: transparent;
			}
		</style>
    <script src="/frontend/vendor/matchheight/jquery.matchHeight-min.js"></script>
		<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.0/slick.min.js"></script> -->
		<script type="text/javascript">
			$(window).load(function(){
				var check = '{{isset($notify_content) && ($notify_show_1st == 'on') ? $notify_content : ''}}';
				if(check)
				{
					setTimeout(function(){ $('#modal-notify-content').modal('show'); }, 1000);
				}
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function () {
                /**
                 *
                 * set match height
                 *
                 */
                $('.card-horizontal-sm').matchHeight();

          //       $(".collectionList").mCustomScrollbar({
										// theme: "dark",
										// contentTouchScroll: true,
										// mouseWheel:{ scrollAmount: 160 }
          //       });
        var create_click_hide = true;
				$('body').on("click",function(e){
					var container = $(".dropdown-collection");

					if (!container.is(e.target) && container.has(e.target).length === 0) 
					{
							container.find('.dropdown-menu-collection').hide();
					}
				})

				if($(window).width() > 720){
					$().fancybox({
						selector: '[data-fancybox]',
						thumbs: false,
						hash: false,
						loop:true,
						'autoDimensions': true,
						'autoScale': true,
						idleTime: false,
						caption : function( instance, item ) {
	            return $(this).find('figcaption').html();
	          },
	          'clickSlide': false,
	          'afterShow' : function(){
	          	if(create_click_hide){
	          		$(".fancybox-caption-wrap").on("click",function(){
									$(this).find('.fancybox-caption').toggle();
								});
								create_click_hide = false;
	          	}
	          }
					});

					$( '[data-video]' ).fancybox();

					$( '[data-video-facebook]' ).fancybox({
						loop:true,
						iframe:{
							css: {

							},
							attr:{
								scrolling:'no',
								frameborder: 2,
								style:"position:absolute;top:0;left:0;width:100%;height:100%;"
							}
						}
					});
				}else{
					$().fancybox({
						selector: '[data-fancybox]',
						loop:true,
						'autoDimensions': true,
						'autoScale': true,
						idleTime: 900,
						caption : function( instance, item ) {
	            return $(this).find('figcaption').html();
	          }
					});

					$( '[data-video]' ).fancybox();

					$( '[data-video-facebook]' ).fancybox({
						loop:true,
						idleTime: 900,
						'autoDimensions': true,
						'autoScale': true,
						iframe:{
							tpl:'<iframe width="'+($(window).width()*80/100 - 1)+'" height="'+($(window).height()*80/100 - 1)+'" style="border:none;overflow:hidden" scrolling="no" allowTransparency="true" allowFullScreen="true"></iframe>',
							css: {
								// height: "auto",
								verticalAlign: "middle"
							},
							attr:{
								style:"width:100%;",
								frameborder: 0
							}
						}

					});
				}
				
				// slider
				$('.slider-gallery').slick({
					dots: true,
          customPaging : function(slider, i) {
              return '';
          },
					infinite: true,
					speed: 300,
					slidesToShow: 4,
					slidesToScroll: 4,
					swipeToSlide: true,
					arrows: true,
          autoplay: true,
          autoplaySpeed: 4000,
          prevArrow: '<button type="button" class="slick-prev btn"><i class="icon-left-open-big"></i></button>',
					nextArrow: '<button type="button" class="slick-next btn "><i class="icon-right-open-big"></i></button>',
					responsive: [{
						breakpoint: 1024,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 3,
							infinite: true,
						}
					},
						{
							breakpoint: 600,
							settings: {
								slidesToShow: 2,
								slidesToScroll: 2
							}
						}
					]
				});
				$('.list-gallery').slick({
					dots: false,
					infinite: true,
					speed: 300,
					slidesToShow: 6,
					slidesToScroll: 6,
          prevArrow: '<button type="button" class="slick-prev btn"><i class="icon-left-open-big"></i></button>',
          nextArrow: '<button type="button" class="slick-next btn "><i class="icon-right-open-big"></i></button>',
          autoplay: true,
          autoplaySpeed: 4000,
          responsive: [
	          {
							breakpoint: 1024,
							settings: {
								slidesToShow: 4,
								slidesToScroll: 4,
								infinite: true,
							}
						},
						{
							breakpoint: 600,
							settings: {
								slidesToShow: 2,
								slidesToScroll: 2
							}
						}
					]
				});
				

                // // fancybox
                // $('[data-fancybox="images"]').fancybox({
                // 		idleTime: false,
                //     caption : function( instance, item ) {
                //         return $(this).find('figcaption').html();
                //     }
                // });



            });
		</script>
		<script>
			var base_url = {!! json_encode(url('/')) !!};
			// var mapHandling = 'cooperative'; // dung 2 ngon tay
			var mapHandling = 'greedy';
			if($(window).width()>768){
				mapHandling = 'greedy';
			}
			var geocoder_detail = new google.maps.Geocoder();
			var directionsService = new google.maps.DirectionsService();
			var directionsDisplay = new google.maps.DirectionsRenderer({
				'draggable': false,
				polylineOptions: {
					strokeColor: "#d0021b",
					strokeWeight: 4,
					strokeOpacity: 1
				},
			});

			var style_map =[
				{
					"featureType": "administrative",
					"elementType": "geometry",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "administrative.locality",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"visibility": "on"
						}
					]
				},
				{
					"featureType": "administrative.locality",
					"elementType": "labels.text",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "poi",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "road",
					"elementType": "labels.icon",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "transit",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				}
			];

			google.maps.event.addDomListener(window, 'load', init);

			function init() {
				var mapOptions = {
					gestureHandling: mapHandling,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					mapTypeControl: false,
					zoom: 14,
					styles: style_map,
					zoomControl: true,
					mapTypeControl: false,
					scaleControl: false,
					streetViewControl: false,
					rotateControl: true,
					fullscreenControl: true,
					center: new google.maps.LatLng({{$content->lat}}, {{$content->lng}})
				};
				var image = '{{asset('frontend/assets/img/logo/Logo-maps.png')}}';
				var mapElement = document.getElementById('map-2');
				var map = new google.maps.Map(mapElement, mapOptions);
				var marker = new google.maps.Marker({
					position: new google.maps.LatLng({{$content->lat}}, {{$content->lng}}),
					map: map,
					title: '{{$content->name}}',
					icon: image
				});

				if (navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(showPosition,function(error){
						console.log(error);
						var currentLocation = window.sessionStorage.getItem('currentLocation');
						var coord = currentLocation.split(',');
						var lat = parseFloat(coord[0]);
						var lng = parseFloat(coord[1]);
						var position = {
							coords:{
								latitude: lat,
								longitude: lng
							}
						};
						showPosition(position);
					},{enableHighAccuracy: true,  timeout: 5000,  maximumAge: 60000});
				}

			}

			function showPosition(position) {
				var latLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
				var mapOptions = {
					zoom: 14,
					gestureHandling: mapHandling,
					styles: style_map,
					zoomControl: true,
					mapTypeControl: false,
					scaleControl: false,
					streetViewControl: false,
					rotateControl: true,
					fullscreenControl: true,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					mapTypeControl: false,
					center: new google.maps.LatLng({{$content->lat}}, {{$content->lng}}),
				};
				var image = '{{asset('frontend/assets/img/logo/Logo-maps.png')}}';
				var bg_maker1 = {
					url: '{{asset('frontend/assets/img/icon/blank.png')}}',
					anchor: new google.maps.Point(0,80)
				};

				var bg_maker2 = {
					url: '{{asset('frontend/assets/img/icon/blank.png')}}',
					anchor: new google.maps.Point(0,-10)
				};
				var mapElement = document.getElementById('map-2');
				var map = new google.maps.Map(mapElement, mapOptions);
				if (geocoder_detail) {
					geocoder_detail.geocode({'latLng': latLng}, function (results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
							var startMarker = new google.maps.Marker({position: latLng, map: map});
							var stopMarker = new google.maps.Marker({
								position: new google.maps.LatLng({{$content->lat}}, {{$content->lng}}),
								map: map,
								icon: image
							});

							
							if(parseFloat("{{$content->lat}}") > latLng.lat()){
								var distanceMarker = new google.maps.Marker({
									position: new google.maps.LatLng({{$content->lat}}, {{$content->lng}}),
									map: map,
									icon: bg_maker1,
									zIndex: 999,
									label: {
										text: '',
										color: "#d0021b",
										fontSize: "18px",
										fontWeight: "bolder",
									}
								});

								var hereMarker = new google.maps.Marker({
									position:latLng,
									map: map,
									icon: bg_maker2,
									zIndex: 999,
									label: {
										text: "{{trans('global.current_location')}}",
										color: "#d0021b",
										fontSize: "18px",
										fontWeight: "bolder"
									}
								});
							}else{
								var distanceMarker = new google.maps.Marker({
									position: new google.maps.LatLng({{$content->lat}}, {{$content->lng}}),
									map: map,
									icon: bg_maker2,
									zIndex: 999,
									label: {
										text: '',
										color: "#d0021b",
										fontSize: "18px",
										fontWeight: "bolder",
									}
								});

								var hereMarker = new google.maps.Marker({
									position:latLng,
									map: map,
									icon: bg_maker1,
									zIndex: 999,
									label: {
										text: "{{trans('global.current_location')}}",
										color: "#d0021b",
										fontSize: "18px",
										fontWeight: "bolder"
									}
								});
							}

							

							directionsDisplay.setMap(map);
							directionsDisplay.setOptions({suppressMarkers: true});
							var request = {
								origin: latLng,
								destination: new google.maps.LatLng({{$content->lat}}, {{$content->lng}}),
								travelMode: google.maps.DirectionsTravelMode.DRIVING
							};

							directionsService.route(request, function (response, status) {

								if (status == google.maps.DirectionsStatus.OK) {
									var distannce_element = document.getElementsByClassName('distance');
									var text_distance = computeTotalDistance(response);
									for(var i = 0; i < distannce_element.length; i++){
										distannce_element[i].innerText=text_distance;
									}
									directionsDisplay.setDirections(response);
									distanceMarker.setLabel({
										text: text_distance,
										color: "#d0021b",
										fontSize: "16px",
										fontWeight: "bold"
									});
									// if(centerRoute(response)){
									//   distanceMarker.setPosition(centerRoute(response));
									// }
								}else{
									var line = new google.maps.Polyline({
									    path: [
									        new google.maps.LatLng(position.coords.latitude, position.coords.longitude), 
									        new google.maps.LatLng({{$content->lat}}, {{$content->lng}})
									    ],
									    strokeColor: "#d0021b",
									    strokeOpacity: 1.0,
									    strokeWeight: 3,
									    map: map
									});
									var text_distance = calculateDistance(position.coords.latitude, position.coords.longitude,{{$content->lat}}, {{$content->lng}});

									distanceMarker.setLabel({
										text: text_distance,
										color: "#d0021b",
										fontSize: "16px",
										fontWeight: "bold"
									});
								}
							});
						}
						else {
							return false;
						}
					});
				}
			}

			function computeTotalDistance(result) {
				var total = 0;
				var myroute = result.routes[0];
				for (var i = 0; i < myroute.legs.length; i++) {
					total += myroute.legs[i].distance.value;
				}
				if(total > 1000){
					total = total / 1000;
					return total.toFixed(1)+' Km';
				}else{
					return total +' m';
				}
			}

			function calculateDistance(lat1, lon1, lat2, lon2)
  		{    
  			var radlat1 = Math.PI * lat1/180
				var radlat2 = Math.PI * lat2/180
				var theta = lon1-lon2
				var radtheta = Math.PI * theta/180
				var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
				dist = Math.acos(dist)
				dist = dist * 180/Math.PI
				dist = dist * 60 * 1.1515
				dist = dist * 1.609344 * 1000;
				if(dist > 1000){
					dist = dist / 1000;
					return dist.toFixed(1)+' Km';
				}else{
					return dist +' m';
				}
	    }

			function centerRoute(result) {
				var myroute = result.routes[0];
				for (var i = 0; i < myroute.legs.length; i++) {
					var stepts = myroute.legs[i].steps;
					var average = Math.floor((stepts.length-1)/2);
					if(stepts[average]){
						return stepts[average].end_location;
					}else{
						return false;
					}
				}
				return false;
			}

			function getLikeContent(id_content, id_user) {
				if (id_user === undefined) {
					$('#modal-signin').modal('show');
				}
				else {
					$.ajax({
						type: "POST",
						data: {
							id_content: id_content,
							id_user: id_user,
							_token: $('meta[name="_token"]').attr('content')
						},
						url: base_url + '/like-content',
						success: function (data) {
							if (data.mess == true) {
								$('div.content .point_like').text('(' + data.value + ')');
								if ($('div.content .add-like i').hasClass('icon-heart-empty')) {
									$('div.content .add-like i').removeClass('icon-heart-empty').addClass('icon-heart')
								} else {
									$('div.content .add-like i').removeClass('icon-heart').addClass('icon-heart-empty')
								}
							}
						}
					})
				}
			}

			function calculateAverage(id_content, id_user, point) {

				$.ajax({
					type: "POST",
					data: {
						id_content: id_content,
						id_user: id_user,
						point: point,
						_token: $('meta[name="_token"]').attr('content')
					},
					url: base_url + '/vote-content',
					success: function (data) {
						if (data.mess == true) {
							$('.star-number').text('(' + data.value + ')')
						}
					}
				})
			}
		</script>

		<!-- Check in script -->
		<script>
			function confirmLocation(id_content, id_user) {
				if (id_user === undefined) {
					$('#modal-signin').modal('show');
					return false;
				}
				window.location = '/confirm-location/'+id_content;
			}

			function checkinContent(id_content, id_user) {
				if (id_user === undefined) {
					$('#modal-signin').modal('show');
					return false;
				}
				var old_checkin = parseInt($(".checkin_total").text());
				$.ajax({
					type: "POST",
					data: {id_content: id_content, id_user: id_user, _token: $('meta[name="_token"]').attr('content')},
					url: base_url + '/checkin-content',
					success: function (response) {
						if (response.mess == true) {
							$(".checkin_total").text(response.value);
							if (old_checkin < response.value) {
								toastr.info('{{trans('Location'.DS.'content.have_checked')}}');
							} else {
								toastr.warning('{{trans('Location'.DS.'content.have_unchecked')}}');
							}
						}
					}
				})
			}

			function saveLikeContent(id_content, id_user) {
				if (id_user === undefined) {
					$('#modal-signin').modal('show');
					return false;
				}
				var old_checkin = parseInt($(".save_like_content_total").text());
				$.ajax({
					type: "POST",
					data: {id_content: id_content, id_user: id_user, _token: $('meta[name="_token"]').attr('content')},
					url: base_url + '/save-like-content',
					success: function (response) {
						if (response.mess == true) {
							$(".save_like_content_total").text(response.value);
							if (old_checkin < response.value) {
								toastr.info('{{trans('Location'.DS.'content.have_saved')}}');
							} else {
								toastr.warning('{{trans('Location'.DS.'content.have_unsaved')}}');
							}
						}
					}
				})
			}

			function showLogin(){
				$("#modal-signin").modal("show");
			}

			function newCollection(id){
				if($(".collectionName_"+id).val() || $(".collectionName_mobile_"+id).val() ){
					var content_id = {{$content->id?$content->id:0}};
					if($(".collectionName_"+id).val()){
                        var name = $(".collectionName_"+id).val();
					}else{
                        var name = $(".collectionName_mobile_"+id).val();
					}
					$.ajax({
						url:'/collection/createCollection',
						type:'POST',
						data:{
							name:name,
							content_id: content_id,
							_token: $('meta[name="_token"]').attr('content')
						},
						success:function(res){
							if (res.error == 0) {
								toastr.info(res.message);
								var html = '';
								for(var i=0; i<res.collections.length; i++){
									html+='<div class="form-check">'
									html+='                    <label class="custom-control custom-radio">'
									if(res.collections[i].check){
										html+='                        <input checked="checked" data-content="'+content_id+'" data-collection="'+res.collections[i].id+'" onchange="changeCollection(this)" name="checkbox" type="checkbox" class="custom-control-input">'
									}else{
										html+='                        <input data-content="'+content_id+'" data-collection="'+res.collections[i].id+'" onchange="changeCollection(this)" name="checkbox" type="checkbox" class="custom-control-input">'
									}


									html+='                        <span class="custom-control-indicator"></span>'
									html+='                        <span class="custom-control-description">'+res.collections[i].name+' - ('+res.collections[i]._contents.length+')</span>'
									html+='                    </label>'
									html+='                </div>'
								}
								$(".collectionList").html(html);
								$(".collectionName_"+id).val('')
							} else {
								toastr.warning(res.message);
							}
						}
					})
				}
			}

			function changeCollection(obj){
				var collection_id = $(obj).attr('data-collection');
				var content_id = $(obj).attr('data-content');
				var check = $(obj).is(':checked');
				if(check){
					$.ajax({
						url:'/collection/addCollection',
						type:'POST',
						data:{
							collection_id : collection_id,
							content_id  : content_id,
							_token: $('meta[name="_token"]').attr('content')
						},
						success:function(res){
							if (res.error == 0) {
								toastr.info(res.message);
								var html = '';
								for(var i=0; i<res.collections.length; i++){
									html+='<div class="form-check">'
									html+='                    <label class="custom-control custom-radio">'
									if(res.collections[i].check){
										html+='                        <input checked="checked" data-content="'+content_id+'" data-collection="'+res.collections[i].id+'" onchange="changeCollection(this)" name="checkbox" type="checkbox" class="custom-control-input">'
									}else{
										html+='                        <input data-content="'+content_id+'" data-collection="'+res.collections[i].id+'" onchange="changeCollection(this)" name="checkbox" type="checkbox" class="custom-control-input">'
									}


									html+='                        <span class="custom-control-indicator"></span>'
									html+='                        <span class="custom-control-description">'+res.collections[i].name+' - ('+res.collections[i]._contents.length+')</span>'
									html+='                    </label>'
									html+='                </div>'
								}
								$(".collectionList").html(html);
							} else {
								toastr.warning(res.message);
							}
						}
					});
				}else{
					$.ajax({
						url:'/collection/removeCollection',
						type:'POST',
						data:{
							collection_id : collection_id,
							content_id  : content_id,
							_token: $('meta[name="_token"]').attr('content')
						},
						success:function(res){
							if (res.error == 0) {
								toastr.info(res.message);
								var html = '';
								for(var i=0; i<res.collections.length; i++){
									html+='<div class="form-check">'
									html+='                    <label class="custom-control custom-radio">'
									if(res.collections[i].check){
										html+='                        <input checked="checked" data-content="'+content_id+'" data-collection="'+res.collections[i].id+'" onchange="changeCollection(this)" name="checkbox" type="checkbox" class="custom-control-input">'
									}else{
										html+='                        <input data-content="'+content_id+'" data-collection="'+res.collections[i].id+'" onchange="changeCollection(this)" name="checkbox" type="checkbox" class="custom-control-input">'
									}


									html+='                        <span class="custom-control-indicator"></span>'
									html+='                        <span class="custom-control-description">'+res.collections[i].name+' - ('+res.collections[i]._contents.length+')</span>'
									html+='                    </label>'
									html+='                </div>'
								}
								$(".collectionList").html(html);
							} else {
								toastr.warning(res.message);
							}
						}
					});
				}

			}

		function showDropdownCollection(obj){
			$(obj).parent().find('.dropdown-menu-collection').toggle('fast')
		}
		</script>
		@yield('JSComment')
@endsection

<style>
	.collectionList{
		max-height: 280px;
		overflow-y: auto;
	}
</style>
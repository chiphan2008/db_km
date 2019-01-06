<div class="sidebar-top  py-2">
  <div class="container">
     <a class="come-back" href="javascript:history.back()" title="">
      <img src="{{asset('frontend/assets/img/icon/ic-back.png')}}" alt="{{$content->name}}">
    </a>
    {!! $breadcrumb !!}
  </div>
</div>
<div class="content-discount-detail content-page">
		<!-- start discount detail content -->
		<div class="discount-detail">
				<div class="container">
						<div class="row">
								<div class="col-md-7 mb-4 mb-md-0">
										<div class="discount-detail-left">
												<!-- start number sale hightline -->
												<div class="discount-detail-number-hightline text-center">
														<span>{{mb_ucfirst($content->_discount[0]->slogan)}}</span>
												</div>
												<!-- end number sale hightline -->
												
												<div class="custom-tabs-wrapper">
													<div id="myTabContent" class="tab-content">
														@if($content->_discount[0]->img_from_content || count($content->_discount[0]->_images)==0)

															<!-- co hinh menu -->
															@if(count($image_menu))
															<div class="tab-pane fade in active" id="menu_slider">
																<div class="discount-detail-slider discount-detail-slider-menu">
																	@foreach($image_menu as $value)
																		<div class="item">
																				<a data-fancybox="images" data-caption="" href="{{$value}}">
																						<img src="{{$value}}" alt="">
																				</a>
																		</div>
																	@endforeach
																</div>
															</div>
															@endif
	
															<!-- co hinh space -->
															@if(count($image_space))
															<div class="tab-pane fade {{count($image_menu)==0?'in active':''}}" id="space_slider">
																<div class="discount-detail-slider discount-detail-slider-space">
																	@foreach($image_space as $value)
																		<div class="item">
																				<a data-fancybox="images" data-caption="" href="{{$value}}">
																						<img src="{{$value}}" alt="">
																				</a>
																		</div>
																	@endforeach
																</div>
															</div>
															@endif

															
															
															<!-- ko co hinh menu space -->
															@if(count($image_menu) == 0 && count($image_space) == 0)
															<div class="tab-pane fade in active" id="image_slider">
																<div class="discount-detail-slider discount-detail-slider-image">
																	<div class="item">
																		<a data-fancybox="images" data-caption="no space no menu" href="{{$content->avatar}}" href="" title="">
																				<img src="{{$content->avatar}}" alt="">
																		</a>
																	</div>
																</div>
															</div>
															@endif
														@else
															<div class="tab-pane fade in active" id="image_slider">
																<div class="discount-detail-slider discount-detail-slider-image">
																	@if(count($content->_discount[0]->_images))
																	@foreach($content->_discount[0]->_images as $value)
																	<div class="item">
																			<a data-fancybox="images" data-caption="" href="{{$value->link}}">
																					<img src="{{$value->link}}" alt="">
																			</a>
																	</div>
																	@endforeach
																	@else
																	<div class="item">
																			<a data-fancybox="images" data-caption="no image discount" href="{{$content->avatar}}" href="" title="">
																					<img src="{{$content->avatar}}" alt="">
																			</a>
																	</div>
																	@endif
																</div>
															</div>
														@endif
													</div>
													<!-- Nav tabs -->
													<ul class="nav nav-tabs">
														@if($content->_discount[0]->img_from_content || count($content->_discount[0]->_images)==0)

															@if(count($image_menu))
															<li class="active">
																<a  data-toggle="tab" data-target="#menu,#menu_slider" href="#menu">{{mb_strtoupper(trans('global.menu'))}}</a>
															</li>
															@endif
															@if(count($image_space))
															<li>
																<a data-toggle="tab" data-target="#khong-gian,#space_slider" href="#khong-gian">{{mb_strtoupper(trans('global.space'))}}</a>
															</li>
															@endif
														@else
														<li class="active">
															<a  data-toggle="tab" data-target="#image,#image_slider" href="#image">{{mb_strtoupper(trans('global.image'))}}</a>
														</li>
														@endif
													</ul>
													<!-- Tab panes -->
													<div id="myTabContent_1" class="tab-content">
														@if($content->_discount[0]->img_from_content || count($content->_discount[0]->_images)==0)
																@if(count($image_menu))
																<div class="tab-pane fade in active" id="menu">
																		<div class="discount-detail-slider-sub discount-detail-slider-menu-sub">
																			@foreach($image_menu as $value)
																				<div class="item">
																						<img src="{{str_replace('img_content','img_content_thumbnail',$value)}}" alt="">
																				</div>
																			@endforeach
																		</div>
																</div>
																@endif

																@if(count($image_space))
																<div class="tab-pane fade {{count($image_menu)==0?'in active':''}}" id="khong-gian">
																		<div class="discount-detail-slider-sub discount-detail-slider-space-sub">
																				@foreach($image_space as $value)
																				<div class="item">
																						<img src="{{str_replace('img_content','img_content_thumbnail',$value)}}" alt="">
																				</div>
																			@endforeach
																		</div>
																</div>
																@endif

																@if(count($image_menu)== 0 && count($image_space)== 0)
																<div class="tab-pane fade in active" id="image">
																	<div class="discount-detail-slider-sub discount-detail-slider-image-sub">
																		<div class="item">
																				<img src="{{$content->avatar}}" alt="">
																		</div>
																	</div>
																</div>
																@endif
														@else
																<div class="tab-pane fade in active" id="image">
																	<div class="discount-detail-slider-sub discount-detail-slider-image-sub">
																				@if(count($content->_discount[0]->_images))
																				@foreach($content->_discount[0]->_images as $value)
																					<div class="item">
																							<img src="{{preg_replace('/discount/','/discount_thumbnail/',$value->link,1)}}" alt="">
																					</div>
																				@endforeach
																				@else
																				<div class="item">
																						<img src="{{$content->avatar}}" alt="">
																				</div>
																				@endif
																		</div>
																</div>
														@endif
													</div>
												</div>
										</div>
								</div>
								<div class="col-md-5">
										<div class="discount-detail-right bg-white p-3">
												<!-- start top -->
												<div class="discount-detail-right-top">
														<div class="header-detail mb-4  text-center">
																<a href="{{LOCATION_URL}}/{{$content->alias}}">
																		<div class="avata">
																				<img class="rounded-circle" width="80" height="80" src="{{str_replace('img_content','img_content_thumbnail',$content->avatar)}}" alt="">
																				@if($open)
																					<div class="online status-location">
																						<i class="icon-circle"></i>
																						<span>{{trans('Discount'.DS.'content.opening')}}</span>
																					</div>
																				@else
																					<div class="offline status-location">
																						<i class="icon-circle"></i>
																						<span>{{trans('Discount'.DS.'content.closing')}}</span>
																					</div>
																				@endif
																		</div>
																		<!-- end  avata -->
																		<h1 class="title-restaurant hidden-sm-down">{{$content->name}}</h1>
																 </a>
														</div>
														<ol class="info-contact list-unstyled mb-3 mb-lg-0">
															<li><i class="icon-location"></i>{{$content->address}}, {{$content->_district?$content->_district->name:''}}, {{$content->_city?$content->_city->name:''}}, {{$content->_country?$content->_country->name:''}} <a href="#" data-toggle="modal" data-target="#modal-maps"><i class="icon-direction-white"></i></a></li>
															<li><i class="icon-phone"></i>{{($content->phone) == '' ? trans('Discount'.DS.'content.undefined') : $content->phone}} </li>
															<!-- <li><i class="icon-mail"></i>{{($content->email) == '' ? trans('Discount'.DS.'content.undefined') : $content->email}}</li> -->
															<li><i class="icon-time"></i>{{ $open_time }}</li>
															<li><i class="icon-price"></i>{{$content->price_from}}
																- {{$content->price_to}} {{$content->currency}}
															</li>
														</ol>
														<!-- end contact -->
														<div class="share mb-3 mb-md-4">
															<span>{{trans('Discount'.DS.'content.share')}}:</span>
															<ul class="list-unstyled d-flex flex-row">
																<li>
																	<a href="#" onclick="sharePopup('https://plus.google.com/share?url={{urlencode(url()->current())}}')">
																		<i class="icon-google"></i>
																	</a>
																</li>
																<li>
																	<a href="#" onclick="sharePopup('https://www.facebook.com/sharer/sharer.php?u={{urlencode(url()->current())}}&amp;src=sdkpreparse')">
																		<i class="icon-facebook"></i>
																	</a>
																</li>
																<li>
																	<a href="#" onclick="sharePopup('https://twitter.com/share?text={!! clear_str($content->name) !!}&url={{urlencode(url()->current())}}&hashtags=Kingmap')">
																		<i class="icon-twitter-bird"></i>
																	</a>
																</li>
															</ul>
														</div>
														<!-- end share -->
														<!-- <div class="button-tell"> -->
																{{-- @if($content->phone) --}}
																<!-- <a class="btn btn-primary w-100" href="" title="">
																		<i class="icon-phone"></i> {{trans('global.contact')}}
																</a> -->
																{{-- @endif --}}
														<!-- </div> -->
														<!-- end button -->
												</div>
												<!-- end top -->
												<!-- start week-acitve -->
												<div class="week-acitve">
														<p class="mb-3">
																{{trans('Discount'.DS.'content.period_discount')}}:
														</p>
														{{-- @php pr($apply_date); @endphp --}}
														<ol class="list-week d-flex align-items-start list-unstyled mb-0 text-center text-capitalize">
																<li class="{{!empty($apply_date[1])?'active':''}}" data-toggle="tooltip" title="{{!empty($apply_date[1])?$apply_date[1]['time']:''}}">
																		<span></span>
																		{{trans('Admin'.DS.'content.monday_short')}}
																</li>
																<li class="{{!empty($apply_date[2])?'active':''}}" data-toggle="tooltip" title="{{!empty($apply_date[1])?$apply_date[2]['time']:''}}">
																		<span></span>
																		{{trans('Admin'.DS.'content.tuesday_short')}}
																</li>
																<li class="{{!empty($apply_date[3])?'active':''}}" data-toggle="tooltip" title="{{!empty($apply_date[1])?$apply_date[3]['time']:''}}">
																		<span></span>
																		{{trans('Admin'.DS.'content.wednesday_short')}}
																</li>
																<li class="{{!empty($apply_date[4])?'active':''}}" data-toggle="tooltip" title="{{!empty($apply_date[1])?$apply_date[4]['time']:''}}">
																		<span></span>
																		{{trans('Admin'.DS.'content.thursday_short')}}
																</li>
																<li class="{{!empty($apply_date[5])?'active':''}}" data-toggle="tooltip" title="{{!empty($apply_date[1])?$apply_date[5]['time']:''}}">
																		<span></span>
																		{{trans('Admin'.DS.'content.friday_short')}}
																</li>
																<li class="{{!empty($apply_date[6])?'active':''}}" data-toggle="tooltip" title="{{!empty($apply_date[1])?$apply_date[6]['time']:''}}">
																		<span></span>
																		{{trans('Admin'.DS.'content.saturday_short')}}
																</li>
																<li class="{{!empty($apply_date[0])?'active':''}}" data-toggle="tooltip" title="{{!empty($apply_date[1])?$apply_date[0]['time']:''}}">
																		<span></span>
																		{{trans('Admin'.DS.'content.sunday_short')}}
																</li>
														</ol>
														<!-- end list weeek -->
												</div>
												<!-- end week-acitve -->
										</div>
								</div>
						</div>
				</div>
		</div>
		<!-- end discount detail -->

		<!-- start discount detail bottom-->
		<div class="discount-detail-bottom bg-white">
				<div class="container">
						<div class="row">
								<div class="col-md-7 ">
										<!-- start deiscuont detail bottom description -->
										<div class="discount-detail-bottom-description mb-5 pr-md-3">
												<h4 class="discount-detail-title text-uppercase mb-3">{{mb_strtoupper(trans('Discount'.DS.'content.condition'))}}</h4>
												<h6>{{trans('Discount'.DS.'content.time_apply')}}</h6>
												<ol>
														<li>
																{{trans('Discount'.DS.'content.from_date')}} {{date('d-m-Y',strtotime($content->_discount[0]->date_from))}} {{mb_strtolower(trans('Discount'.DS.'content.to_date'))}} {{date('d-m-Y',strtotime($content->_discount[0]->date_to))}} 
														</li>
												</ol>
												<h6>
														{{trans('Discount'.DS.'content.condition')}}
												</h6>
												<div id="detail">
													{!! $content->_discount[0]->description !!}
												</div>
										</div>
										<!-- end deiscuont detail bottom description -->
										<!-- start list discount related -->
										@if(count($content->_discount[0]->_contents))
										<div class="discount-detail-bottom-related pr-md-3">
												<div class="discount-detail-header clearfix mb-3 d-flex  justify-content-between">
														<h4 class="discount-detail-title text-uppercase mb-0">{{mb_strtoupper(trans('Discount'.DS.'content.other_contents'))}}</h4>
														<!-- <a class="btn-view-all ml-auto" href="" title="">{{trans('global.view_all')}} <i class="icon-angle-double-right"></i></a> -->
												</div>
												<!-- end header -->
												<div class="content">
													@php
														$contents_in_km = [];
														foreach($content->_discount[0]->_contents as $ct){
															if($ct->id != $content->id){
																$contents_in_km[] = $ct;
															}
														}

														$total_contents_discount = count($contents_in_km)-1;
														$total_page = ceil($total_contents_discount/3);
													@endphp
													@for($i=1;$i<=$total_page;$i++)
													@php $index_c=($i-1)*3; @endphp
													<div class="show_content {{$i==1?'show':''}}" id="show_content_{{$i}}">
														@foreach($contents_in_km as $index => $cont)													
															@if($index <= ($i*3) && $index > $index_c)
																<div class="card-horizontal d-flex align-items-center mb-4">
																		<div class="card-horizontal-img">
																				<a href="{{url($cont->alias)}}">
																						<img src="{{str_replace('img_content','img_content_thumbnail',$cont->avatar)}}" alt="">
																				</a>
																		</div>
																		<div class="card-horizontal-content pl-3">
																				<h4 class="name"><a href="{{url($cont->alias)}}">{{$cont->name}}</a></h4>
																				<p class="address">
																						{{$cont->address}}, {{$cont->_district?$cont->_district->name:''}}, {{$cont->_city?$cont->_city->name:''}}
																				</p>
																		</div>
																</div>
															<!-- end card horizontal --> 
															@php $index_c++; @endphp  
															@endif 
														@endforeach
													</div>
													@endfor                                    
												</div>
												<div class="pager">
													@if($total_page > 1)
													<nav aria-label="">
													  <div class="pagination" data-total="{{$total_page}}">
													    <!-- <li class="prev" style="display: none;"><a class="page-link btn prev-link"  data-total="{{$total_page}}" href="#"><<</a></li> -->
													    {{-- @for($i=1;$i<=$total_page;$i++) --}}
														    {{-- @if($i==1) --}}
														    <!-- <li class="page-item  active"><a class="page-link btn" href="#" data-total="{{$total_page}}" data-page="{{$i}}">{{$i}}</a></li> -->
														    {{-- @else --}}
														    <!-- <li class="page-item"><a class="page-link btn" href="" data-total="{{$total_page}}" data-page="{{$i}}">{{$i}}</a></li> -->
														    {{--@endif--}}
													    {{-- @endfor --}}
													    <!-- <li class="next"><a class="page-link btn next-link"  data-total="{{$total_page}}" href="">>></a></li> -->
													  </div>
													</nav>
													@endif
												</div>
										</div>
										<!-- end list discount related -->
										@endif
								</div>
								<div class="col-md-5">
										@if(count($list_suggest))
										<div class="discount-detail-bottom-right">
												<h4 class="discount-detail-title text-uppercase mb-3">{{mb_strtoupper(trans('Discount'.DS.'content.near_contents'))}}</h4>
												<div class="content">
													@foreach($list_suggest as $cont)
														@if($cont->id != $content->id)
														<div class="card-horizontal d-flex align-items-center mb-4">
																<div class="card-horizontal-img">
																		<a href="{{url($cont->alias)}}">
																				<img src="{{str_replace('img_content','img_content_thumbnail',$cont->avatar)}}" alt="">
																		</a>
																		@if($cont->_discount_basic)
																		<span class="number-off">{{$cont->_discount_basic[0]->slogan}}</span>
																		@endif
																</div>
																<div class="card-horizontal-content pl-3">
																		<h4 class="name"><a href="{{url($cont->alias)}}">{{$cont->name}}</a></h4>
																		<p class="address">
																				{{$cont->address}}, {{$cont->_district?$cont->_district->name:''}}, {{$cont->_city?$cont->_city->name:''}}
																		</p>
																</div>
														</div>
														<!-- end card horizontal --> 
														@endif    
													@endforeach 
												</div>
										</div>
										<!-- end right    -->
										@endif
								</div>
								@include('Location.content.comment')
						</div>
				</div>
		</div>
		<!-- end discount detail bottom-->
</div>
<div id="modal-maps" class="modal fade  modal-report show modal-animation" data-backdrop="false"  tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md" style='background:#fff;max-width: 95%;'>
    <div class="modal-content p-4" style="width:100%;">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <br/>
      <!-- close -->
      <div class="modal-logo pt-4 text-center">
        <!-- <img src="{{isset($notify_content) ? asset($content->avatar) : ''}}" alt=""> -->
      </div>
      <!-- end logo -->
      <div class="container">
      	<div id="map-2" style="min-height:95%;"></div>
      </div>
      <!-- end  form nitification location -->
    </div>
    <!-- end  modal content -->
  </div>
</div>
<style>
	.show_content{
		display: none;
	}
	.show_content.show{
		display: block;
	}
</style>
@section('JS')
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js"></script>
<script src="/frontend/vendor/custom-tab.js"></script>
<script src="/frontend/vendor/jquery.bootpag.min.js"></script>
<script>
$(document).ready(function() {

		$('[data-toggle="tooltip"]').tooltip()
		/**
		 *
		 * discount detal slider
		 *
		 */
		if($('.discount-detail-slider-menu').length){
			$('.discount-detail-slider-menu').slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				fade: true,
				asNavFor: '.discount-detail-slider-menu-sub',
				nextArrow: '<a class="slick-next" href="" title=""><i class="icon-right-open-big"></i></a>',
				prevArrow: '<a class="slick-prev" href="" title=""><i class="icon-left-open-big"></i></a>'
			});
		}

		if($('.discount-detail-slider-space').length){
			$('.discount-detail-slider-space').slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				fade: true,
				asNavFor: '.discount-detail-slider-space-sub',
				nextArrow: '<a class="slick-next" href="" title=""><i class="icon-right-open-big"></i></a>',
				prevArrow: '<a class="slick-prev" href="" title=""><i class="icon-left-open-big"></i></a>'
			});
		}

		if($('.discount-detail-slider-image').length){
			$('.discount-detail-slider-image').slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				fade: true,
				asNavFor: '.discount-detail-slider-image-sub',
				nextArrow: '<a class="slick-next" href="" title=""><i class="icon-right-open-big"></i></a>',
				prevArrow: '<a class="slick-prev" href="" title=""><i class="icon-left-open-big"></i></a>'
			});
		}


		if($('.discount-detail-slider-menu-sub').length){
			$('.discount-detail-slider-menu-sub').slick({
					slidesToShow: 5,
					slidesToScroll: 1,
					asNavFor: '.discount-detail-slider-menu',
					dots: false,
					arrows: false,
					focusOnSelect: true,
					 responsive: [
							{
								breakpoint: 1024,
								settings: {
									slidesToShow: 4,
									slidesToScroll: 4
								}
							},
							{
								breakpoint: 600,
								settings: {
									slidesToShow: 3,
									slidesToScroll: 3
								}
							}
						]
			});
		}

		if($('.discount-detail-slider-space-sub').length){
			$('.discount-detail-slider-space-sub').slick({
					slidesToShow: 5,
					slidesToScroll: 1,
					asNavFor: '.discount-detail-slider-space',
					dots: false,
					arrows: false,
					focusOnSelect: true,
					 responsive: [
							{
								breakpoint: 1024,
								settings: {
									slidesToShow: 4,
									slidesToScroll: 4
								}
							},
							{
								breakpoint: 600,
								settings: {
									slidesToShow: 3,
									slidesToScroll: 3
								}
							}
						]
			});
		}

		if($('.discount-detail-slider-image-sub').length){
			$('.discount-detail-slider-image-sub').slick({
					slidesToShow: 5,
					slidesToScroll: 1,
					asNavFor: '.discount-detail-slider-image',
					dots: false,
					arrows: false,
					focusOnSelect: true,
					 responsive: [
							{
								breakpoint: 1024,
								settings: {
									slidesToShow: 4,
									slidesToScroll: 4
								}
							},
							{
								breakpoint: 600,
								settings: {
									slidesToShow: 3,
									slidesToScroll: 3
								}
							}
						]
			});
		}

		$('.nav-tabs a').click(function (e) {
			console.log('resize');
			// $('.discount-detail-slider-sub-menu').resize();
			setTimeout(function(){
				// $('.discount-detail-slider-menu-sub').slick('resize');
				$('.discount-detail-slider-space-sub').slick('resize');
				// $('.discount-detail-slider-image-sub').slick('resize');

				// $('.discount-detail-slider-menu').slick('resize');
				$('.discount-detail-slider-space').slick('resize');
				// $('.discount-detail-slider-image').slick('resize');
			},200)
			
		});

		$('.btn-show-discount-img').click(function(event) {
				event.preventDefault();
				$(this).closest('.discount-detail-content').find('.gallery li').slideDown('slow', function() {

				});
		});

		// $(".page-item .page-link").on("click",function(e){
		// 	e.preventDefault();
		// 	var page = $(this).data('page');
		// 	var total = $(this).data('total');
		// 	if(page==1){
		// 		$(".prev").hide();
		// 	}else{
		// 		$(".prev").show();
		// 	}

		// 	if(page==total){
		// 		$(".next").hide();
		// 	}else{
		// 		$(".next").show();
		// 	}

		// 	$(".page-item").removeClass('active');
		// 	$(this).parent().addClass('active');
		// 	$('.show_content').removeClass('show');
		// 	$("#show_content_"+page).addClass('show');
		// })

		// $(".prev-link").on("click",function(e){
		// 	e.preventDefault();
		// 	var page = $('.page-item.active .page-link').data('page');
			
		// 	var total = $(this).data('total');
		// 	page--;
		// 	if(page<=0){
		// 		page=1;
		// 	}

		// 	if(page==1){
		// 		$(".prev").hide();
		// 	}else{
		// 		$(".prev").show();
		// 	}

		// 	if(page==total){
		// 		$(".next").hide();
		// 	}else{
		// 		$(".next").show();
		// 	}

		// 	$(".page-item").removeClass('active');
		// 	$('.page-item .page-link[data-page='+page+']').parent().addClass('active');
		// 	$('.show_content').removeClass('show');
		// 	$("#show_content_"+page).addClass('show');
		// })

		// $(".next-link").on("click",function(e){
		// 	e.preventDefault();
		// 	var page = $('.page-item.active .page-link').data('page');
			
		// 	var total = $(this).data('total');
		// 	page++;
		// 	if(page>total){
		// 		page=total;
		// 	}

		// 	if(page==1){
		// 		$(".prev").hide();
		// 	}else{
		// 		$(".prev").show();
		// 	}

		// 	if(page==total){
		// 		$(".next").hide();
		// 	}else{
		// 		$(".next").show();
		// 	}

		// 	$(".page-item").removeClass('active');
		// 	$('.page-item .page-link[data-page='+page+']').parent().addClass('active');
		// 	$('.show_content').removeClass('show');
		// 	$("#show_content_"+page).addClass('show');
		// })

		$('.pagination').bootpag({
			total: $('.pagination').data('total'),
			page: 1,
			maxVisible: 10,
			leaps: true,
			nextClass: 'next',
			prevClass: 'prev',
			lastClass: 'last',
			firstClass: 'first'
		}).on('page', function(event, num){
			$('.show_content').removeClass('show');
			$("#show_content_"+num).addClass('show');
		});
});
</script>
<script>
  var base_url = {!! json_encode(url('/')) !!};
  var mapHandling = 'cooperative';
  if($(window).width()>768){
    mapHandling = 'greedy';
  }
  var map;
  var currentCenter;
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
      "elementType": "labels.icon",
      "stylers": [{
        "visibility": "off"
      }]
    }
  ];

  google.maps.event.addDomListener(window, 'load', init);

  $("#modal-maps").on("shown.bs.modal", function () {
	    if (navigator.geolocation) {
	      navigator.geolocation.getCurrentPosition(showPosition,function(){
	      	google.maps.event.trigger(map, 'resize');
		    	//map.setZoom(15);
		    	map.setCenter(new google.maps.LatLng({{$content->lat}}, {{$content->lng}}));
	      });
	    }
	});

  function init() {
    var mapOptions = {
      gestureHandling: mapHandling,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      mapTypeControl: false,
      zoom: 14,
      styles: style_map,
      zoomControl: false,
      mapTypeControl: false,
      scaleControl: false,
      streetViewControl: false,
      rotateControl: true,
      fullscreenControl: true,
      center: new google.maps.LatLng({{$content->lat}}, {{$content->lng}})
    };
    var image = '{{asset('frontend/assets/img/logo/Logo-maps.png')}}';
    var mapElement = document.getElementById('map-2');
    map = new google.maps.Map(mapElement, mapOptions);
    var marker = new google.maps.Marker({
      position: new google.maps.LatLng({{$content->lat}}, {{$content->lng}}),
      map: map,
      title: '{{$content->name}}',
      icon: image
    });
  }

  function showPosition(position) {
    var latLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
    var mapOptions = {
      zoom: 14,
      gestureHandling: mapHandling,
      styles: style_map,
    	zoomControl: false,
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
    var mapElement = document.getElementById('map-2');
    map = new google.maps.Map(mapElement, mapOptions);
    if (geocoder_detail) {
      geocoder_detail.geocode({'latLng': latLng}, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          var startMarker = new google.maps.Marker({position: latLng, map: map});
          var stopMarker = new google.maps.Marker({
            position: new google.maps.LatLng({{$content->lat}}, {{$content->lng}}),
            map: map,
            icon: image
          });
          directionsDisplay.setMap(map);
          directionsDisplay.setOptions({suppressMarkers: true});
          var request = {
            origin: latLng,
            destination: new google.maps.LatLng({{$content->lat}}, {{$content->lng}}),
            travelMode: google.maps.DirectionsTravelMode.DRIVING
          };

          directionsService.route(request, function (response, status) {
            if (status == google.maps.DirectionsStatus.OK) {
              directionsDisplay.setDirections(response);
            }
          });
        }
        else {
          return false;
        }
      });
    }
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

  var starClicked = false;
  var check = {{isset($vote_point) ? $vote_point : 'null'}};
  if (check != null) {
    var starClicked = true;
  }

  $(function () {

    $('.star').click(function () {
      var id_user = {{isset(Auth::guard('web_client')->user()->id) ? Auth::guard('web_client')->user()->id : 'null'}};
      if (id_user == null) {
        $('#modal-signin').modal('show');
      }
      else {

        $(this).children('.selected').addClass('is-animated');
        $(this).children('.selected').addClass('pulse');

        var target = this;

        setTimeout(function () {
          $(target).children('.selected').removeClass('is-animated');
          $(target).children('.selected').removeClass('pulse');
        }, 1000);

        starClicked = true;
      }
    });

    $('.half').click(function () {
      if (starClicked === true) {
        // default
        // setHalfStarState(this);
        return false;
      }
      $(this).closest('.rating').find('.js-score').text($(this).data('value'));

      $(this).closest('.rating').data('vote', $(this).data('value'));
      calculateAverage({{$content->id}},{{isset(Auth::guard('web_client')->user()->id) ? Auth::guard('web_client')->user()->id : 'null'}}, $(this).data('value'));
    });

    $('.full').click(function () {

      if (starClicked === true) {
        // default
        // setHalfStarState(this);
        return false;
      }
      $(this).closest('.rating').find('.js-score').text($(this).data('value'));

      $(this).find('js-average').text(parseInt($(this).data('value')));

      $(this).closest('.rating').data('vote', $(this).data('value'));
      calculateAverage({{$content->id}},{{isset(Auth::guard('web_client')->user()->id) ? Auth::guard('web_client')->user()->id : 'null'}}, $(this).data('value'));

    });

    $('.half').hover(function () {
      if (starClicked === false) {
        setHalfStarState(this);
      }
    });

    $('.full').hover(function () {
      if (starClicked === false) {
        setFullStarState(this);
      }
    });

  });

  function updateStarState(target) {
    $(target).parent().prevAll().addClass('animate');
    $(target).parent().prevAll().children().addClass('star-colour');

    $(target).parent().nextAll().removeClass('animate');
    $(target).parent().nextAll().children().removeClass('star-colour');
  }

  function setHalfStarState(target) {
    $(target).addClass('star-colour');
    $(target).siblings('.full').removeClass('star-colour');
    updateStarState(target);
  }

  function setFullStarState(target) {
    $(target).addClass('star-colour');
    $(target).parent().addClass('animate');
    $(target).siblings('.half').addClass('star-colour');

    updateStarState(target);
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

  function getLocationByIP(){
		$.getJSON("/getLocation", function(data) {
			lat = parseFloat(data.latitude).toFixed(6);
			lng = parseFloat(data.longitude).toFixed(6);
			console.log("Current location getLocationByIP: "+lat+' '+lng);
			pos = {
				coords:{
					latitude: parseFloat(lat),
					longitude: parseFloat(lng),
				}
			};
			showPosition(pos);
		});
	}
</script>
@endsection
<!DOCTYPE html>
<html class="no-js " lang="en">

<head>
	<!-- Basic Page Needs -->
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>King Maps</title>
	<meta name="description" content="#">
	<meta name="keywords" content="#">
	<meta name="author" content="#">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
	<link rel="stylesheet" href="{{asset('frontend/assets/fonts/stylesheet.css')}}">
	<link rel="stylesheet" href="{{asset('frontend/assets/fonts/ionicons/css/fontello.css')}}">
	<!-- bổ sung trang này -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css">
	<!-- Custom CSS  -->
	<link rel="stylesheet" href="{{asset('frontend/assets/css/main.min.css')}}">
	<link rel="stylesheet" href="{{asset('frontend/assets/css/jquery.mCustomScrollbar.min.css')}}">
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<!-- Modernizr js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
</head>
<body class="location-detail-photo">
<div id="wrapper">
	<div class="box-wrapper">
		<div class="location-detail-photo-page">
			<div class="sidebar-top  py-2">
				<div class="container d-flex align-items-center justify-content-between">
					<a class="come-back" href="{{LOCATION_URL}}/{{$content->alias}}" title="{{$content->name}}">
						<img src="{{asset('frontend/assets/img/icon/ic-back.png')}}" alt="{{$content->name}}">{{$content->name}}
					</a>
					@if(isset($data['user']))
						<a class="profile-avata hidden-xs-down" href="{{url('/')}}/user/{{$data['user']->id}}">
							<img class="rounded-circle" src="{{$data['user']->avatar}}" alt="{{$data['user']->full_name}}">
							{{$data['user']->full_name}}
						</a>
					@endif
				</div>
			</div>
			<!-- end  Banner -->
			<div class="location-detail-photo-content">
				<div class="container">
					<!-- Nav tabs -->
					<ul class="list-unstyled nav-tab" role="tablist">
						{{-- @if($data['count_image_space'] > 0) --}}
							<li class="nav-item">
								<a class="nav-link {{$type == 'space' ? 'active' : ''}}" data-toggle="tab" href="#space" role="tab">
								{{mb_strtoupper(trans('global.space'))}} ({{$data['count_image_space']}})</a>
							</li>
						{{-- @endif --}}
						{{-- @if($data['count_image_menu'] > 0) --}}
							<li class="nav-item">
								<a class="nav-link {{$type == 'menu' ? 'active' : ''}}" data-toggle="tab" href="#menu" role="tab">
								{{mb_strtoupper(trans('global.image'))}} ({{$data['count_image_menu']}})</a>
							</li>
						{{-- @endif --}}
						{{-- @if($data['count_video'] > 0) --}}
							<li class="nav-item">
								<a class="nav-link {{$type == 'video' ? 'active' : ''}}" data-toggle="tab" href="#video" role="tab">
								{{mb_strtoupper(trans('global.video'))}} ({{$data['count_video']}})</a>
							</li>

							<li class="nav-item">
								<a class="nav-link {{$type == 'product' ? 'active' : ''}}" data-toggle="tab" href="#product" role="tab">
								{{mb_strtoupper(trans('global.product_service'))}} ({{$data['count_list_product']}})</a>
							</li>
						{{-- @endif --}}
					</ul>

					<!-- Tab<div class="sidebar-top  py-2">…</div> panes -->
					<div class="tab-content">
						<div class="tab-pane {{$type == 'space' ? 'active' : ''}}" id="space" role="tabpanel">
							<div class="scroll-gallery">
								<div class="list-gallery gallery-item">
									@foreach($data['image_space'] as $value)
										<div class="item-gallery">
												<a data-fancybox="space" href="{{$value->name}}">
												<img src="{{str_replace('img_content','img_content_thumbnail',$value->name)}}" alt="">
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
										</div>
									@endforeach
								</div>
							</div>
						</div>
						<div class="tab-pane {{$type == 'menu' ? 'active' : ''}}" id="menu" role="tabpanel">
							<div class="scroll-gallery">
								<div class="list-gallery gallery-item">
									@foreach($data['image_menu'] as $value)
										<div class="item-gallery">
											<a data-fancybox="menu" href="{{$value->name}}">
												<img src="{{str_replace('img_content','img_content_thumbnail',$value->name)}}" alt="">
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
										</div>
									@endforeach
								</div>
							</div>
						</div>
						<div class="tab-pane {{$type == 'video' ? 'active' : ''}}" id="video" role="tabpanel">
							<div class="scroll-gallery">
								<div class="list-gallery">
									@foreach($data['video'] as $value)
										<div class="item-gallery iframe-video">
										@if ($value->type == 'facebook')
												<a data-video-facebook data-type="iframe" href="https://www.facebook.com/plugins/video.php?height=232&href={{$value->link}}">
													 <img src="{{$value->thumbnail?$value->thumbnail:''}}" alt="">
													 
												</a>
										@elseif($value->type == 'youtube')
											@php
												$link = $value->link;
												$link = str_replace('watch?v=','',$link);
												$link = str_replace('youtube.com/','youtube.com/embed/',$link);
												$link = str_replace('youtu.be/','youtube.com/embed/',$link);
												$link = clear_youtube_link($link);
											@endphp
												<a data-video href="{{$link}}">
													 <img src="{{$value->thumbnail?$value->thumbnail:''}}" alt="">
													 
												</a>
										@endif
										</div>
									@endforeach
								</div>
							</div>
						</div>

						<div class="tab-pane {{$type == 'product' ? 'active' : ''}}" id="product" role="tabpanel">
							<div class="scroll-gallery">
								<div class="list-gallery">
									@foreach($data['list_product'] as $key_group => $group)
										@foreach($group as $key_product => $product)
											@if($key_product !== 'group_name')
											<div class="item-gallery gallery-item">
												<a data-fancybox="product" href="{{$product->image}}">
													<img src="{{str_replace('/product/','/product_thumbnail/',$product->image)}}" alt="{{$product->name}}">
														<figcaption>
																<h2 class="gallery-item-title">
																		{{$product->name}}
																</h2>
																<p class="gallery-item-description">
																		{{$product->description}}
																</p>
																<span class="gallery-item-price font-weight-bold">
																		{{money_number($product->price)}}{{$product->currency}}
																</span>
														</figcaption>
												</a>
											</div>
											@endif
										@endforeach
									@endforeach
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.fancybox-caption-wrap{
		pointer-events: auto !important;
		min-height: 150px;
	}
</style>
<!--Javascript Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- getbootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>

<!-- Main Script -->
{{--<<script src="assets/js/main.js"></script>--}}
<!-- bổ sung trang này -->
<!-- css de o tren -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.js"></script>

<script src="{{asset('frontend/assets/js/jquery.mCustomScrollbar.js')}}"></script>
<script>
	$('.location-detail-photo-content .scroll-gallery').mCustomScrollbar({
		theme: "dark-3"
	});
	$(function(){
		// fancybox
		var create_click_hide = true;
		if($(window).width() > 720){
			$( '[data-fancybox]' ).fancybox({
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
			$( '[data-fancybox]' ).fancybox({
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
	})
	
</script>

</body>

</html>

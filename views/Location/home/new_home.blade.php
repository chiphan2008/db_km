@section('body')
	<body>
@endsection
<div class="banner">
	<div class="container">
		<div class="main-banner">
			<div class="content-banner">
					<div class="col-md-12" style="padding-bottom: 50px;">
						@foreach($list_category as $key => $cat)
							<div class="box-gallery pt-3 pb-2">
								<div class="title-gallery d-flex justify-content-between align-items-start">
	                <h4 class="text-uppercase mb-3">@lang(mb_ucfirst($cat->name))</h4>
	              </div>
	              <ul class="list-gallery list-unstyled row">
	                @foreach($list_image[$key] as $key2 => $value)
	                  <li>
	                    <a href="{{url($list_link[$key][$key2])}}">
	                      <img class="img-fluid" src="{{$value}}" alt="">
	                    </a>
	                  </li>
	                @endforeach
	              </ul>
	              <!-- end list-gallery -->
	            </div>
	          @endforeach
					</div>
			</div>
		</div>
	</div>
</div>
<!-- end  Banner -->
<style type="text/css" media="screen">
.img-fluid{
	width: 120px !important;
	height: 89px !important;
	border-radius: 5px;
	border:1px solid #ddd;
}
.list-gallery li{
	margin: 0 3px !important;
}
@media (min-width: 768px){
	.img-fluid{
		width: 110px !important;
		height: 82px !important;
	}
}
</style>
@section('JS')
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js"></script>
<script type="text/javascript" charset="utf-8">
	$(function(){
	  $('.list-gallery').slick({
	    dots: false,
	    infinite: false,
	    speed: 300,
	    slidesToShow: 10,
	    slidesToScroll: 10,
	    arrows: false,
	    responsive: [{
	      breakpoint: 1024,
	      settings: {
	        slidesToShow: 8,
	        slidesToScroll: 8,
	        infinite: true,
	      }
	    },
	      {
	        breakpoint: 600,
	        settings: {
	          slidesToShow: 4,
	          slidesToScroll: 4
	        }
	      }
	    ]
	  });
	});
 	
</script>
@endsection
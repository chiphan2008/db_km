@section('body')
	<body class="home">
@endsection
<div class="banner-home banner">
	<div class="container">
		<div class="main-banner">
			<div class="header-banner text-center text-uppercase">
				<!-- <h4>DEAR MY FRIEND!</h4> -->
				<h1>DEAL AROUND YOU</h1>
				<!-- <span class="icon-Oval-entertainment"></span> -->
			</div>
			<div class="content-banner">
				 <div class="wrap-circle-cate">
					<div class="circle-logo  circle">
						<img src="/frontend/assets/img/logo/logo-large.svg" alt="">
            <span>Khác</span>
					</div>

					@if(isset($categories))
					  @for ($i = 0; $i < count($categories); $i++)
						  @if($i == 0)
								 <div class="cate-home-step active">
							@elseif($i%7 == 0)
								 </div>
								 <div class="cate-home-step">
							@endif
								 <div class="circle-cate-item circle">
									 {{-- @if(count($categories[$i]->category_items)) --}}
										 <!-- <a href="" title="{{$categories[$i]->name}}" data-toggle="modal" data-target="#modal-{{$categories[$i]->alias}}"> -->
									 {{-- @else --}}
										 <a href="{{url('/')}}/{{$categories[$i]->alias}}" title="{{$categories[$i]->name}}">
									 {{-- @endif --}}
											 <img src="{{$categories[$i]->image}}" alt="{{$categories[$i]->name}}" width="65">
											 <span>{{$categories[$i]->name}}</span>
										 </a>
								 </div>
						  @if($i+1 == count($categories))
								 </div>
							@endif
						@endfor
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end  Banner -->

@section('modal')
	{{-- @if(isset($categories)) --}}
	{{-- @foreach($categories as $category) --}}
		{{-- @if(count($category->category_items)) --}}
			{{-- @include('Location.home.modal-category') --}}
		{{-- @endif --}}
	{{-- @endforeach --}}
	{{-- @endif --}}
@endsection

@section('JS')
<script type="text/javascript" charset="utf-8">
  $('.circle-logo').click(function(){
    var totalStep = $('.cate-home-step').length-1;
    var currentStep = $('.cate-home-step.active').index('.cate-home-step');
    var nextStep =0;
    if(currentStep<totalStep){
      nextStep = currentStep+1;
    }
    console.log(nextStep,currentStep,totalStep);
    $('.cate-home-step.active').removeClass('active');
    $('.cate-home-step').eq(nextStep).addClass('active');

    animatePoint();
  });

  animatePoint();
  function animatePoint(){
    var el = document.getElementsByClassName('cate-home-step active')[0];
    var circle = el.getElementsByClassName('circle-cate-item');
    var wrapCircle = document.getElementsByClassName('wrap-circle-cate')[0];
    var x1 = wrapCircle.offsetWidth / 2;
    var y1 = wrapCircle.offsetHeight / 2;
    var Ang = -55;
    var svg = 110 / circle.length;
    for (var i = 0; i < circle.length; i++) {
      var tag = circle[i];
      var point = findNewPoint(x1, y1, Ang, 130);
      tag.style.top = point.y + 'px';
      tag.style.left = point.x + 'px';
      tag.style.opacity = '1';
      Ang += svg * 2;
    }
  }

  function findNewPoint(x, y, angle, distance) {
    var result = {};
    result.x = Math.round(Math.cos(angle * Math.PI / 110) * distance + x);
    result.y = Math.round(Math.sin(angle * Math.PI / 110) * distance + y);
    return result;
  }
</script>
@endsection
@section('body')
	<body class="booking">
@endsection
<div class="page-booking-content">
    <div class="container">
        <h6 class="page-title m-0 mb-4 text-center text-uppercase">
            ĐỊA ĐIỂM PHỔ BIẾN
        </h6>
        <!-- end  page title -->
        <div class="page-content grid-layla">
            <div class="row">
            	@if($home_bookings)
            	@foreach($home_bookings as $home_booking)
                <div class="col-lg-4 col-md-6">
                    <figure class="effect-layla" >
                        <img src="{{$home_booking->image}}" alt=""/>
                        <figcaption>
                            <a href="/list/{{$home_booking->alias}}">
                                <h2>{{mb_strtoupper($home_booking->name)}}</h2>
                            </a>
                        </figcaption>           
                    </figure>
                    <!-- end effect-layla -->
                </div>
              @endforeach
              @endif
            </div>
        </div>
        <!-- end  page content -->
    </div>
</div>

@section('JS')

@endsection
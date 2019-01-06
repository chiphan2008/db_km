@foreach($list_suggest as $value)
  <div class="card-item card-img-zoom">
    <a href="{{url($value->alias)}}" title="">
      <div class="img">
        <img src="{{asset($value->avatar)}}" alt="">
      </div>
      <div class="description">
        <h5 class="text-truncate">Chi nhánh {{$value->_district->name}}</h5>
        <p>
          {{$value->address}}
        </p>
      </div>
      <!-- end description -->
    </a>
  </div>
@endforeach

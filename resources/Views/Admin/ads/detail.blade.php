<div class="row">	
	<label for="" class="label-control col-md-4 text-right">
		{{trans('Location'.DS.'user.ads_name')}}
	</label>
	<div class="col-md-8">
		{{$ads->name}}
	</div>
</div>
<div class="row">	
	<label for="" class="label-control col-md-4 text-right">
		{{trans('Location'.DS.'user.ads_type')}}
	</label>
	<div class="col-md-8">
		{{$ads->_type_ads->name}}
	</div>
</div>

@if($ads->choose_type=='link')
<div class="row">	
	<label for="" class="label-control col-md-4 text-right">
		{{trans('Location'.DS.'user.ads_link')}}
	</label>
	<div class="col-md-8">
		<a href="{{$ads->link}}">{{$ads->link}}</a>
	</div>
</div>
@endif

@if($ads->choose_type=='content')
<div class="row">	
	<label for="" class="label-control col-md-4 text-right">
		{{trans('Location'.DS.'user.ads_content')}}
	</label>
	<div class="col-md-8">
		{{$ads->_base_content->name}}
	</div>
</div>
@endif

<div class="row">	
	<label for="" class="label-control col-md-4 text-right">
		{{trans('Location'.DS.'user.ads_image')}}
	</label>
	<div class="col-md-8">
      <img src="{{$ads->image}}" style="width:100%;max-width:{{$ads->_type_ads->width}}px;max-height:{{$ads->_type_ads->height}}px;">
	</div>
</div>
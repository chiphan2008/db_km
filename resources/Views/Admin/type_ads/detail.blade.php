<div class="row">
<div class="row">	
	<label for="" class="label-control col-md-4 text-right">
		{{trans('Location'.DS.'user.ads_type')}}
	</label>
	<div class="col-md-8">
		{{$ads->_type_ads->name}}
	</div>
</div>
</div>

<div class="row">	
	<label for="" class="label-control col-md-4 text-right">
		{{trans('Location'.DS.'user.type_apply')}}
	</label>
	<div class="col-md-8">
		{{trans('Location'.DS.'user.apply_by_'.$ads->type_apply)}}
	</div>
</div>

@if($ads->type_apply=='date')
	<div class="row">	
		<label for="" class="label-control col-md-4 text-right">
			{{trans('Location'.DS.'user.ads_from')}}
		</label>
		<div class="col-md-8">
			{{date('d-m-Y',strtotime($ads->date_from))}}
		</div>
	</div>

	<div class="row">	
		<label for="" class="label-control col-md-4 text-right">
			{{trans('Location'.DS.'user.ads_to')}}
		</label>
		<div class="col-md-8">
			{{date('d-m-Y',strtotime($ads->date_to))}}
		</div>
	</div>
@endif

@if($ads->type_apply=='click')
<div class="row">	
	<label for="" class="label-control col-md-4 text-right">
		{{trans('Location'.DS.'user.click')}}
	</label>
	<div class="col-md-8">
		{{$ads->click}}
	</div>
</div>
@endif

@if($ads->type_apply=='view')
<div class="row">	
	<label for="" class="label-control col-md-4 text-right">
		{{trans('Location'.DS.'user.view')}}
	</label>
	<div class="col-md-8">
		{{$ads->view}}
	</div>
</div>
@endif

<div class="row">	
	<label for="" class="label-control col-md-4 text-right">
		{{trans('Location'.DS.'user.ads_content')}}
	</label>
	<div class="col-md-8">
		{{$ads->_base_content->name}}
	</div>
</div>

<div class="row">	
	<label for="" class="label-control col-md-4 text-right">
		{{trans('Location'.DS.'user.price')}}
	</label>
	<div class="col-md-8">
		{{money_number($ads->price)}}K
	</div>
</div>

<div class="row">	
	<label for="" class="label-control col-md-4 text-right">
		{{trans('Location'.DS.'user.total')}}
	</label>
	<div class="col-md-8">
		{{money_number($ads->total)}}K
	</div>
</div>

<div class="row">	
	<label for="" class="label-control col-md-4 text-right">
		{{trans('Location'.DS.'user.ads_image')}}
	</label>
	<div class="col-md-8">
		@if($ads->_media_ads)
      @if($ads->_type_ads->kind=='banner')
      <img src="{{$ads->_media_ads[0]->link}}" style="max-width:100%;width:{{$ads->_type_ads->width}}px;height:{{$ads->_type_ads->height}}px;">
      @endif
    @endif
	</div>
</div>
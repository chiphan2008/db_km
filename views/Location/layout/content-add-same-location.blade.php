
<div class="modal-logo pt-4">
  <img src="/frontend/assets/img/logo/logo-large.svg" alt="">
</div>
<h4>{{mb_strtoupper(trans('Location'.DS.'layout.add_same_location'))}}</h4>
<hr>
<div class="scroll-content-modal">
	<div class="row">
		@foreach($list_content as $content)
		<div class="col-6">
			<div class="card-vertical card">
				<div class="card-img-top">
					<label for="checkbox_{{$content->id}}">
						<img class="img-fluid" src="{{str_replace('img_content','img_content_thumbnail',$content->avatar)}}" alt="{{$content->name}}">
					</label>
					<label class="custom-control custom-checkbox">
						<input id="checkbox_{{$content->id}}" type="checkbox" class="custom-control-input cate_check_content" value="{{$content->id}}">
						<span class="custom-control-indicator"></span>
					</label>
				</div>
				<div class="card-block py-2 px-0">
					<div class="card-description">
						<h6 class="card-title ">
							<label for="checkbox_{{$content->id}}">
								{{$content->name}}
							</label>
						</h6>
						<p class="card-address text-truncate">{{$content->address}}, {{$content->_district->name}}, {{$content->_city->name}}, {{$content->_country->name}}</p>
					</div>
				</div>
			</div>
		</div>
		@endforeach
	</div>
</div>
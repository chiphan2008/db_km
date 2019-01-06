<!-- category {{$category->alias}} -->
	<div class="modal-cate modal fade " id="modal-{{$category->alias}}" tabindex="-1" role="dialog" aria-labelledby="modal-{{$category->alias}}">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<!-- modal-header -->
				<div class="modal-header">
					<a class="modal-logo" href="" title="">
					<img src="{{$category->image}}" alt="">
					<span class="text-uppercase">@lang($category->name)</span>
					</a>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<img src="/frontend/assets/img/icon/dark.png" alt="">
					</button>
				</div>
				<div class="modal-body">
					<div class="container">
						<div class="box-select-cate-step2 box-select-style2">
							<div class="row">

								<!-- <div class="col-lg-3 col-6">
									<div class="item-select" style="background-image: url({{$category->background}})">
										<a href="/{{$category->alias}}" title="">{{mb_ucfirst(trans('global.all'))}}</a>
									</div>
								</div> -->

								@if($category->category_items)
								@foreach($category->category_items as $item)
								<div class="col-lg-3 col-6">
									<div class="item-select" style="background-image: url({{$item->image}})">
										<a href="/{{$category->alias}}/{{$item->alias}}" title="">@lang(mb_ucfirst($item->name))</a>
									</div>
								</div>
								@endforeach
								@endif
							</div>
						</div>
					</div>
				</div>
				<!-- modal-body -->
			</div>
		</div>
	</div>
<!-- end cate {{$category->alias}} -->
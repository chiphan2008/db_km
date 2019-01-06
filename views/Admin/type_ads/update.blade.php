@extends('Admin..layout_admin.master_admin')

@section('content')
	<div class="row">
		<form class="form-horizontal form-label-left" method="post" action="{{route('update_type_ads',['id'=>$type_ads->id])}}" enctype="multipart/form-data">
			<div class="col-md-8 col-sm-8 col-xs-12">
				<div class="x_panel">
					<div class="x_content">
							{{ csrf_field() }}
							@if ($errors->has('error'))
								<span style="color: red">{{ $errors->first('error') }}</span>
							@endif
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="name">{{trans('Admin'.DS.'type_ads.name')}} <span
										class="required">*</span>
								</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<input type="text" id="name" name="name" class="form-control col-md-7 col-xs-12" value="{{ $type_ads->name }}" >
								</div>
								@if ($errors->has('name'))
									<span style="color: red">{{ $errors->first('name') }}</span>
								@endif
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="machine_name">{{trans('Admin'.DS.'type_ads.machine_name')}} <span
										class="required">*</span>
								</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<input type="text" id="machine_name" name="machine_name" readonly="" class="form-control col-md-7 col-xs-12" value="{{ $type_ads->machine_name }}" >
								</div>
								@if ($errors->has('machine_name'))
									<span style="color: red">{{ $errors->first('machine_name') }}</span>
								@endif
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="kind">{{trans('Admin'.DS.'type_ads.kind')}}</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<select name="kind" id="kind" class="form-control">
										<option value="web" {{$type_ads->kind =='web'?'selected':''}}>Web</option>
                    <option value="app {{$type_ads->kind =='app'?'selected':''}}">App</option>
                    <!-- <option value="mobile" {{$type_ads->kind =='mobile'?'selected':''}}>Mobile</option> -->
										<!-- <option value="keyword">{{trans('global.keyword')}}</option> -->
									</select>
								</div>
								@if ($errors->has('kind'))
									<span style="color: red">{{ $errors->first('kind') }}</span>
								@endif
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="description">{{trans('Admin'.DS.'type_ads.description')}}</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<textarea type="text" id="description" name="description" class="form-control col-md-7 col-xs-12">{{ $type_ads->description }}</textarea>
								</div>
								@if ($errors->has('description'))
									<span style="color: red">{{ $errors->first('description') }}</span>
								@endif
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="size">{{trans('Admin'.DS.'type_ads.size')}}</label>
								<div class="col-md-10 col-sm-10 col-xs-12 row">
									<div class="col-md-6">
										<div class="input-group">
											<span class="input-group-addon" id="basic-addon1"><b>{{trans('Admin'.DS.'type_ads.width')}}</b></span>
											<input type="number" name="width" value="{{$type_ads->width}}" class="form-control">
											<span class="input-group-addon" id="basic-addon1"><b>px</b></span>
										</div>
									</div>
									<div class="col-md-6">
										<div class="input-group">
											<span class="input-group-addon" id="basic-addon1"><b>{{trans('Admin'.DS.'type_ads.height')}}</b></span>
											<input type="number" name="height" value="{{$type_ads->height}}" class="form-control">
											<span class="input-group-addon" id="basic-addon1"><b>px</b></span>
										</div>
									</div>
								</div>
								@if ($errors->has('size'))
									<span style="color: red">{{ $errors->first('size') }}</span>
								@endif
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">{{trans('Admin'.DS.'type_ads.image_default')}}</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
										<div class="imgupload panel panel-default">
											<div class="panel-heading clearfix">
												<h3 class="panel-title pull-left">{{trans('Admin'.DS.'type_ads.upload_image')}}</h3>
											</div>
											<div class="file-tab panel-body default">

												<div>
													<a type="button" class="btn btn-default btn-file">
													<span>{{trans('Admin'.DS.'type_ads.browse')}}</span>
													<input type="file" name="image_default" id="image_default">
													</a>
													<button type="button" class="btn btn-default">{{trans('Admin'.DS.'type_ads.remove')}}</button>
												</div>
											</div>
										</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12">{{trans('Admin'.DS.'type_ads.image_demo')}}</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
										<div class="imgupload panel panel-default">
											<div class="panel-heading clearfix">
												<h3 class="panel-title pull-left">{{trans('Admin'.DS.'type_ads.upload_image')}}</h3>
											</div>
											<div class="file-tab panel-body demo">
												
												<div>
													<a type="button" class="btn btn-default btn-file">
													<span>{{trans('Admin'.DS.'type_ads.browse')}}</span>
													<input type="file" name="image_demo" id="image_demo">
													</a>
													<button type="button" class="btn btn-default">{{trans('Admin'.DS.'type_ads.remove')}}</button>
												</div>
											</div>
										</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="price">{{trans('Admin'.DS.'type_ads.price_default')}}</label>
								<div class="col-md-10 col-sm-10 col-xs-12">
									<div class="col-md-4"><label for="">{{trans('Admin'.DS.'type_ads.type_apply')}}</label></div>
									<div class="col-md-8"><label for="">{{trans('Admin'.DS.'type_ads.price')}}</label></div>
								</div>
								@foreach($price_default as $price)
								@if($price->type_apply=='date')
								<div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2">
									<div class="col-md-4">
										<input type="checkbox" name="type_apply[date]" value="1" {{$type_ads->type_apply[0]=='1'?'checked':''}}>
										{{trans('Admin'.DS.'type_ads.apply_by_date')}}
										<input type="hidden" class="form-control" name="price_default[0][type_apply]" value="date">
										<input type="hidden" class="form-control" name="price_default[0][min]" value="{{$price->min}}">
										<input type="hidden" class="form-control" name="price_default[0][max]" value="{{$price->max}}">
									</div>
									<div class="col-md-8">
										<input type="number" class="form-control" name="price_default[0][price]" value="{{$price->price}}">
									</div>
								</div>
								
								@endif
								@endforeach
								@foreach($price_default as $price)
								@if($price->type_apply=='click')
								<div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2">
									<div class="col-md-4">
										<input type="checkbox" name="type_apply[click]" value="1" {{$type_ads->type_apply[1]=='1'?'checked':''}}>
										{{trans('Admin'.DS.'type_ads.apply_by_click')}}
										<input type="hidden" class="form-control" name="price_default[1][type_apply]" value="click">
										<input type="hidden" class="form-control" name="price_default[1][min]" value="{{$price->min}}">
										<input type="hidden" class="form-control" name="price_default[1][max]" value="{{$price->max}}">
									</div>
									<div class="col-md-8">
										<input type="number" class="form-control" name="price_default[1][price]" value="{{$price->price}}">
									</div>
								</div>
								
								@endif
								@endforeach
								@foreach($price_default as $price)
								@if($price->type_apply=='view')
								<div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2">
									<div class="col-md-4">
										<input type="checkbox" name="type_apply[view]" value="1" {{$type_ads->type_apply[2]=='1'?'checked':''}}>
										{{trans('Admin'.DS.'type_ads.apply_by_view')}}
										<input type="hidden" class="form-control" name="price_default[2][type_apply]" value="view">
										<input type="hidden" class="form-control" name="price_default[2][min]" value="{{$price->min}}">
										<input type="hidden" class="form-control" name="price_default[2][max]" value="{{$price->max}}">
									</div>
									<div class="col-md-8">
										<input type="number" class="form-control" name="price_default[2][price]" value="{{$price->price}}">
									</div>
								</div>
								@endif
								@endforeach
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="price">{{trans('Admin'.DS.'type_ads.price_custom')}}</label>
								<div class="col-md-10 col-sm-10 col-xs-12 custom_price_header" style="{{count($price_custom)?'':'display:none;'}}">
									<div class="col-md-4"><label for="">{{trans('Admin'.DS.'type_ads.type_apply')}}</label></div>
									<div class="col-md-4"><label for="">{{trans('Admin'.DS.'type_ads.price')}}</label></div>
									<div class="col-md-2"><label for="">{{trans('Admin'.DS.'type_ads.min')}}</label></div>
									<div class="col-md-2"><label for="">{{trans('Admin'.DS.'type_ads.max')}}</label></div>
								</div>
								<div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2" id="custom_price">
									<div class="custom_price_item">
										@if(count($price_custom))
										@foreach($price_custom as $key => $price)
											<div class="custom_price_item" data-index="{{$key}}">
												<div class="col-md-4">
													<select name="custom_price[{{$key}}][type_apply]" id="" class="form-control">
														<option value="date" {{$price->type_apply=='date'?'selected':''}}>{{trans('Location'.DS.'user.apply_by_date')}}</option>
														<option value="click" {{$price->type_apply=='click'?'selected':''}}>{{trans('Location'.DS.'user.apply_by_click')}}</option>
														<option value="view" {{$price->type_apply=='view'?'selected':''}}>{{trans('Location'.DS.'user.apply_by_view')}}</option>
													</select>
												</div>
												<div class="col-md-4">
													<input type="number" class="form-control" name="custom_price[{{$key}}][price]" value="{{$price->price}}" min="0">
												</div>
												<div class="col-md-2">
													<input type="number" class="form-control" name="custom_price[{{$key}}][min]" value="{{$price->min}}" min="0">
												</div>
												<div class="col-md-2">
													<input type="number" class="form-control" name="custom_price[{{$key}}][max]" value="{{$price->max}}" min="0">
												</div>
												<a class="remove_price_custom" onclick="removeOldPriceCustom(this)" data-id="{{$price->id}}"><i class="fa fa-remove"></i></a>
											</div>
										@endforeach
										@endif
									</div>
								</div>
								<div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2 text-center">
									<button class="btn btn-primary" type="button" onclick="addPriceCustom()">
									 {{trans('Admin'.DS.'type_ads.add_price_custom')}}
									</button>
								</div>
							</div>
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12">
				<div class="x_panel">
					<div class="x_content">
						<div class="form-group">
							<!-- <label class="control-label col-md-2 col-sm-2 col-xs-12"></label> -->
							<div class="col-md-12 col-sm-12 col-xs-12">
								{{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ $type_ads->active ? 'checked' : '' }} name="active"> {{trans('global.active')}}
							</div>
						</div>
						<div class="form-group">
              <label class="control-label col-md-4 col-xs-12">
              {{trans('Admin'.DS.'type_ads.created_at')}}</label>
              <div class="col-md-8 col-xs-12">
                <label class="control-label">{{date('d-m-Y H:i:s',strtotime($type_ads->created_at))}}</label>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-xs-12">
              {{trans('Admin'.DS.'type_ads.created_by')}}</label>
              <div class="col-md-8 col-xs-12">
                <label class="control-label">{{$type_ads->_created_by?$type_ads->_created_by->full_name:''}}</label>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-xs-12">
              {{trans('Admin'.DS.'type_ads.updated_at')}}</label>
              <div class="col-md-8 col-xs-12">
                <label class="control-label">{{date('d-m-Y H:i:s',strtotime($type_ads->updated_at))}}</label>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-4 col-xs-12">
              {{trans('Admin'.DS.'type_ads.updated_by')}}</label>
              <div class="col-md-8 col-xs-12">
                <label class="control-label">{{$type_ads->_updated_by?$type_ads->_updated_by->full_name:''}}</label>
              </div>
            </div>
						<div class="ln_solid"></div>
						<div class="form-group">
							<div class="col-md-10 col-sm-10 col-xs-12 col-md-offset-2">
								<button type="submit" class="btn btn-success">{{trans('Admin'.DS.'type_ads.update_type_ads')}}</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
@endsection

@section('JS')
<style>
	.custom_price_item{
		margin-bottom: 15px;
		float:left;
	}
	.remove_price_custom{
		position: absolute;
		cursor: pointer;
		margin-top: 10px;
	}
</style>
<script type="text/javascript">
	$(function(){
		$('.imgupload').imageupload({
			allowedFormats: [ "jpg", "jpeg", "png", "gif", "svg" , "bmp"],
			previewWidth: 250,
			previewHeight: 250,
			maxFileSizeKb: 2048
		});

		$("#name").on("keyup",function(){
			var name = $(this).val();
			$("#machine_name").val(str_machine(name));
		})

		$(".btn-file").on("click",function(){
			$(this).find("input").click();
		})

		var image_default = '<img src="{{$type_ads->img_default?$type_ads->img_default:'' }}" alt="Image preview" class="thumbnail" style="max-width: 250px; max-height: 250px">';
		$('.file-tab.default').prepend(image_default)

		var image_demo = '<img src="{{$type_ads->img_demo?$type_ads->img_demo:'' }}" alt="Background preview" class="thumbnail" style="max-width: 250px; max-height: 250px">';
		$('.file-tab.demo').prepend(image_demo)
	})

	function addPriceCustom(){
		var index = $(".custom_price_item").length;
		if($('[data-index='+index+']').length){
			index +=1;
		}
		$(".custom_price_header").show();
		html='';
		html+='<div class="custom_price_item" data-index="'+index+'">';
		html+='  <div class="col-md-4">';
		html+='    <select name="custom_price['+index+'][type_apply]" id="" class="form-control">';
		html+='      <option value="date">{{trans('Location'.DS.'user.apply_by_date')}}</option>';
		html+='      <option value="click">{{trans('Location'.DS.'user.apply_by_click')}}</option>';
		html+='      <option value="view">{{trans('Location'.DS.'user.apply_by_view')}}</option>';
		html+='    </select>';
		html+='  </div>';
		html+='  <div class="col-md-4">';
		html+='    <input type="number" class="form-control" name="custom_price['+index+'][price]" value="0" min="0">';
		html+='  </div>';
		html+='  <div class="col-md-2">';
		html+='    <input type="number" class="form-control" name="custom_price['+index+'][min]" value="0" min="0">';
		html+='  </div>';
		html+='  <div class="col-md-2">';
		html+='    <input type="number" class="form-control" name="custom_price['+index+'][max]" value="0" min="0">';
		html+='  </div>';
		html+='  <a class="remove_price_custom"  onclick="removePriceCustom(this)"><i class="fa fa-remove"></i></a>';
		html+='</div>';
		$("#custom_price").append(html);
	}

	function removePriceCustom(obj){
		$(obj).parent().remove();
		if($(".custom_price_item").length ==1){
			$(".custom_price_header").hide();
		}
	}

	function removeOldPriceCustom(obj){
		var id = $(obj).data('id');
		if( confirm('{{trans('valid.confirm_delete_price')}}') ) {
      var CSRF_TOKEN = $('input[name="_token"]').val();
      $.ajax({
        type: "POST",
        data: {id: id, _token: CSRF_TOKEN},
        url: '/ads/type-ads/deletePrice',
        success: function (data) {
          if (data == 'sussess') {
            $(obj).parent().remove();
						if($(".custom_price_item").length ==1){
							$(".custom_price_header").hide();
						}
					}
        }
      });
    }	
	}
</script>
@endsection

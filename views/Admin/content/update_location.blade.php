@extends('Admin.layout_admin.master_admin')

@section('content')
<div class="col-sm-12">
@if(session('status'))
<div class="alert alert-success alert-dismissible fade in" style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
	</button>
	{!! session('status') !!}
</div>
@endif
<div class="form-inline dt-bootstrap no-footer">
	<div class="row">
		<div class="col-sm-12">
			<div class="x_panel">
				<form class="form-horizontal form-label-left" method="post" action="{{url()->current()}}">
					{{csrf_field()}}
					<div class="row search_group">
						<div class="col-md-4">
							<label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('global.category')}}</label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<select class="form-control" name="category" onchange="get_category_item(this);" id="category" style="width:100%">
									<option value="">-- {{trans('global.category')}} --</option>
									@foreach($list_category as $value )
									<option value="{{$value->id}}" {{old('category')&&$value->id==old('category')?'selected':''}}>{{$value->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('global.category_item')}}</label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<select  onchange="check_update()" class="form-control" name="category_item" id="category_item" style="width:100%">
									<option value="">-- {{trans('global.category_item')}} --</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('global.country')}}</label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<select class="form-control" name="country" onchange="get_location(this, 'city')" id="country" style="width:100%">
									<option value="">-- {{trans('global.country')}} --</option>
									@foreach($list_country as $value )
									<option value="{{$value->id}}"  {{old('country')&&$value->id==old('country')?'selected':''}}>{{$value->name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('global.city')}}</label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<select class="form-control" name="city" onchange="get_location(this, 'district')" id="city" style="width:100%">
									<option value="">-- {{trans('global.city')}} --</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('global.district')}}</label>
							<div class="col-md-8 col-sm-8 col-xs-12">
								<select class="form-control" name="district"  onchange="check_update()"  id="district" style="width:100%">
									<option value="">-- {{trans('global.district')}} --</option>
								</select>
							</div>
						</div>
						<div class="col-md-4 text-center">
							<p><h5 id="count"></h5></p>
						</div>
					</div>
					<div class="col-md-12 text-center" style="padding-top: 10px;width:100%;">
						<button type="submit" class="btn btn-info">{{trans('global.update')}} Location</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>



<style>
	.search_group .col-md-4{
		padding-top: 10px;
	}
	.search_group input,
	.search_group select{
		min-width: 150px;
	}
	.like_update{
		max-width:90px;
	}
</style>
@endsection

@section('JS')
<script type="text/javascript">
	var json_sort = [];
	var old_country = "{{old('country')}}";
  var old_city = "{{old('city')}}";
  var old_district = "{{old('district')}}";
  var old_category_item = "{{old('category_item')}}";
  if (old_country != ''){
  	old_country = parseInt(old_country);
  }
  if (old_city != ''){
  	old_city = parseInt(old_city);
  }
  if (old_district != ''){
  	old_district = parseInt(old_district);
  }
  if (old_category_item != ''){
  	old_category_item = parseInt(old_category_item);
  }
	$(function(){
		setTimeout(function(){
			@if (old('country') != '')
				$("#country").trigger("change");
			@endif

			@if (old('city') != '')
				$("#city").trigger("change");
			@endif

			@if (old('category') != '')
				$("#category").trigger("change");
			@endif
		}, 100);
	});

	function get_category_item(obj){
		var value = $(obj).val();
		var CSRF_TOKEN = $('input[name="_token"]').val();
		$('#category_item').html('<option value="">-- {{trans('global.category_item')}} --</option>');
		$.ajax({
			type: "POST",
			data: {value: value, _token: CSRF_TOKEN},
			url:  '/admin/content/ajaxCategoryItem',
			success: function (data) {
				$("#category_item").html(data);
				$("#category_item option").each(function(key,elem){
					if($(elem).attr('value') == old_category_item){
						$(elem).attr('selected',true);
					}
        })
				check_update();
			}
		})
	}

	function get_location(obj, type) {
		var CSRF_TOKEN = $('input[name="_token"]').val();
		var value = $(obj).val();
		if (type == 'city') {
			$('#district').html('<option value="">-- {{trans('global.district')}} --</option>');
		}
		$.ajax({
			type: "POST",
			data: {value: value, type: type, _token: CSRF_TOKEN},
			url:  '/admin/content/ajaxLocation',
			success: function (data) {
				$("#" + type).html(data);
				if(type=='city'){
					$("#city option").each(function(key,elem){
						if($(elem).attr('value') == old_city){
							$(elem).attr('selected',true);
						}
					});
					$("#city").trigger("change");
					check_update();
				}
				if (type == 'district'){
					$("#district option").each(function(key, elem){
						if ($(elem).attr('value') == old_district){
							$(elem).attr('selected', true);
						}
					});
					check_update();
				}
			}
		})
	}

	function check_update(){
		var category = $("#category").val();
		var category_item = $("#category_item").val();
		var country = $("#country").val();
		var city = $("#city").val();
		var district = $("#district").val();
		var CSRF_TOKEN = $('input[name="_token"]').val();
		$.ajax({
			type: "POST",
			data: {
				category:category,
				category_item:category_item,
				country:country,
				city:city,
				district:district,
				_token: CSRF_TOKEN
			},
			url:  '/admin/content/check_update_location',
			success: function (data) {
				html = 'Total '+data.count+' results.';
				$("#count").html(html);
			}
		})
	}
</script>
@endsection
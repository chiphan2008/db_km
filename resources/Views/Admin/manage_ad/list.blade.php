@extends('Admin.layout_admin.master_admin')

@section('content')
<div class="row">

	<form class="form-horizontal form-label-left" method="get" action="{{route('list_manage_ad')}}" accept-charset="utf-8">
		<div class="form-group col-md-4 col-xs-12">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('global.keyword')}}</label>
			<div class="col-md-9 col-sm-9 col-xs-12">
				<input type="text" id="keyword" value="{{ isset($keyword) ? $keyword : '' }}" name="keyword" class="form-control" placeholder="{{trans('global.keyword')}}">
			</div>
		</div>
		<div class="form-group col-md-4 col-xs-12">
			<label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('global.status')}}</label>
			<div class="col-md-9 col-sm-9 col-xs-12">
				<select name="status" id="status" class="form-control">
					<option value="">{{trans('global.all')}}</option>
					<option value="pending" {{$status=='pending'?'selected':''}}>
						{{trans('Admin'.DS.'manage_ad.pending')}}
					</option>
					<option value="approved" {{$status=='approved'?'selected':''}}>
						{{trans('Admin'.DS.'manage_ad.approved')}}
					</option>
					<option value="declined" {{$status=='declined'?'selected':''}}>
						{{trans('Admin'.DS.'manage_ad.declined')}}
					</option>
				</select>
			</div>
		</div>
		<div class="form-group col-md-4 col-xs-12">
			<div class="col-md-9 col-sm-9 col-xs-12">
				<button type="submit" class="btn btn-success">{{trans('global.search')}}</button>
			</div>
		</div>
	</form>
	 @if(session('success'))
  <div class="alert alert-success alert-dismissible fade in" style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
      </button>
      {{session('success')}}
  </div>
	@endif
	@if(session('error'))
  <div class="alert alert-warning alert-dismissible fade in"  role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
    {{session('error')}}
  </div>
  @endif
</div>
<div class="row">
	<div class="col-md-6">
		<table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid"
							 aria-describedby="datatable_info">
			<thead>
			<tr role="row">
				<th class="" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
						aria-label="Operation: activate to sort column ascending">{{trans('Admin'.DS.'manage_ad.location')}}
				</th>
				<th class="" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
						aria-label="Operation: activate to sort column ascending" style="width: 90px;">{{trans('global.status')}}
				</th>
				<th class="" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
						aria-label="Operation: activate to sort column ascending" style="width: 90px;">{{trans('global.active')}}
				</th>
				<th class="text-center" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
						aria-label="Operation: activate to sort column ascending" style="width: 90px;">{{trans('Admin'.DS.'manage_ad.created_at')}}
				</th>
				<th class="text-center" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
						aria-label="Operation: activate to sort column ascending" style="width: 60px;">{{trans('global.operation')}}
				</th>
			</tr>
			</thead>

			<tbody>
			@if (isset($list_ad))
				@foreach ($list_ad as $key => $ad)
					@if($ad->_content)
					<tr role="row" class="odd">
						<td>{{$ad->_content->name}}</td>
						<td>
							@if($ad->approved)
								<b class="text-success">{{trans('Admin'.DS.'manage_ad.approved')}}</b>
							@else
								@if($ad->declined)
								<b class="text-danger">{{trans('Admin'.DS.'manage_ad.declined')}}</b>
								@else
								<b class="text-warning">{{trans('Admin'.DS.'manage_ad.pending')}}</b>
								@endif
							@endif
						</td>
						<td>
							@if($ad->_content->active_ad)
							<b class="text-success">{{trans('global.active')}}</b>
							@else
							<b class="text-danger">{{trans('global.inactive')}}</b>
							@endif
						</td>
						<td class="text-center" >
							{{date('H:m:s',strtotime($ad->created_at))}}<br/>{{date('d-m-Y',strtotime($ad->created_at))}}
						</td>
						<td class="text-center">
							<!-- @if($ad->approved==0 && $ad->declined==0)
							<button class="btn btn-success btn-sm" type="button">
								{{trans('Admin'.DS.'manage_ad.approve')}}
							</button>
							<button class="btn btn-danger btn-sm" type="button">
								{{trans('Admin'.DS.'manage_ad.decline')}}
							</button>
							@endif -->
							<button class="btn btn-info btn-sm" type="button" data-ad="{{json_encode($ad->toArray())}}" onclick="viewDetail(this);">
								{{trans('Admin'.DS.'manage_ad.detail')}}
							</button>
						</td>
					</tr>
					@endif
				@endforeach
			@else
				<tr role="row" class="odd">
					<td colspan="6">{{trans('global.no_data')}}</td>
				</tr>
			@endif
			</tbody>
		</table>
		<div class="col-sm-12" style="text-align: right">
			{!! $list_ad->appends(['status' => isset($status) ? $status : '','keyword' => isset($keyword) ? $keyword : ''])->render() !!}
		</div>
	</div>

	<div class="col-md-6">
		<div class="x_panel">
			<div class="row">
				<label class="label-control col-md-4 text-right">{{trans('Admin'.DS.'manage_ad.name')}}: </label>
				<div class="col-md-8" id="name"></div>
			</div>
			<div class="row">
				<label class="label-control col-md-4 text-right">{{trans('Admin'.DS.'manage_ad.category')}}: </label>
				<div class="col-md-8" id="category"></div>
			</div>
			<div class="row">
				<label class="label-control col-md-4 text-right">{{trans('Admin'.DS.'manage_ad.category_item')}}: </label>
				<div class="col-md-8" id="category_item"></div>
			</div>
			<div class="row">
				<label class="label-control col-md-4 text-right">{{trans('Admin'.DS.'manage_ad.tag')}}: </label>
				<div class="col-md-8" id="tag"></div>
			</div>
			<div class="row">
				<label class="label-control col-md-4 text-right">{{trans('Admin'.DS.'manage_ad.view_ad')}}: </label>
				<div class="col-md-8" id="view_ad"></div>
			</div>
			<div class="row" id="ka_div">
				<label class="label-control col-md-4 text-right">{{trans('Admin'.DS.'manage_ad.keyword_ad')}}: </label>
				<div class="col-md-8" id="ka"></div>
			</div>
			<div class="row" id="frm_ad" style="display:none;margin-top: 10px;">
				<form action="{{route('update_manage_ad')}}" method="POST">
					{{ csrf_field() }}
					<input type="hidden" id="id_ad" value="" name="id_ad">
					<div class="form-group col-md-12">
						<label class="label-control col-md-4 text-right">{{trans('Admin'.DS.'manage_ad.keyword_ad')}}: </label>
						<div class="col-md-8">
							<input type="text" class="form-control tags" rows="8"  name="keyword_ad" id="keyword_ad"/>
						</div>
					</div>
					<div class="form-group col-md-12">
						<label class="label-control col-md-4 text-right">{{trans('Admin'.DS.'manage_ad.note')}}: </label>
						<div class="col-md-8">
							<textarea rows="4" name="declined_content" class="form-control" id="declined_content"></textarea>
						</div>
					</div>
					<div class="form-group col-md-12" id="active_div">
						<label class="label-control col-md-4 text-right">{{trans('Admin'.DS.'manage_ad.active_ad')}}: </label>
						<div class="col-md-8">
							<input type="checkbox" class="" id="active_ad" name="active_ad">
						</div>
					</div>
					<div class="text-center form-group col-md-12" id="button_submit">
						<button class="btn btn-success btn-sm" type="submit" value="approve" name="approve">
								{{trans('Admin'.DS.'manage_ad.approve')}}
							</button>
							<button class="btn btn-danger btn-sm" type="submit" value="decline" name="decline">
								{{trans('Admin'.DS.'manage_ad.decline')}}
							</button>
					</div>
					<div class="text-center form-group col-md-12" id="button_update">
						<button class="btn btn-success btn-sm" type="submit" value="update" name="update">
								{{trans('global.update')}}
							</button>
					</div>
				</form>
		</div>
		</div>
	</div>

</div>
@endsection
@section('CSS')
<style>
	.tag_kw{
		font-size: 12px !important;
		margin-right: 10px;
		display: inline-block !important;
		text-overflow: ellipsis;
		margin-bottom: 5px;
	}
</style>
@endsection
@section('JS')
	<script>
		var json_sort = [];
		$(function(){
			$("#keyword_ad").tagsInput({
				width:'auto',
				height:'auto',
				maxTags:10,
			});
		})
		function viewDetail(obj){
			var data = [];
			var json_str = $(obj).attr('data-ad');
			data = JSON.parse(json_str);
			var html_name = '<b><a  target="_blank" href="{{url("/")}}/'+data['_content']['alias']+'">'+data['_content']['name']+'</a></b>';
			$("#name").html(html_name);
			$("#category").text(data['_content']['_category_type']['name']);
			var category_item = '';
			for(var i=0; i< data['_content']['_category_items'].length; i++){
				if(i==0)
					category_item = data['_content']['_category_items'][i]['name'];
				else
					category_item +=', ' + data['_content']['_category_items'][i]['name'];
			}
			$("#category_item").text(category_item);
			//console.log(data['_content']);

			var tag = [];
			if(data['_content']['tag']){
				if (data['_content']['tag'].indexOf(',') > -1) {
					tag = data['_content']['tag'].split(',');
				}else{
					tag = [data['_content']['tag']];
				}
			}

			var keyword_ad = [];
			if(data['_content']['keyword_ad']){
				if (data['_content']['keyword_ad'].indexOf(',') > -1) {
					keyword_ad = data['_content']['keyword_ad'].split(',');
				}else{
					keyword_ad = [data['_content']['keyword_ad']];
				}
			}

			var html_tag = '';
			var html_ka = '';
			for(var i=0; i<tag.length; i++){
				html_tag+='<span class="label label-info tag_kw">'+tag[i]+'</span>'
			}

			for(var i=0; i<keyword_ad.length; i++){
				html_ka+='<span class="label label-success tag_kw">'+keyword_ad[i]+'</span>'
			}
			$("#tag").html('');
			$("#keyword_ad").html('');
			$("#view_ad").text('');
			$("#id_ad").val('');
			$("#declined_content").val('');

			$("#tag").html(html_tag);
			$("#ka").html(html_ka);
			setTimeout(function(){
				$("#declined_content").val(data['declined_content']);
			},50)
			$("#keyword_ad").importTags(data['_content']['keyword_ad']);

			$("#active_ad").attr("checked",data['_content']['active_ad']==1?true:false);
			$("#active_ad").prop("checked",data['_content']['active_ad']==1?true:false);

			$("#view_ad").text(data['_content']['view_ad']);
			$("#id_ad").val(data['id']);
			$("#declined_content").val("");
			if(data['approved']==0 && data['declined']==0){
				$("#active_div").hide();
			}else{
				if(data['approved']==1){
					$("#active_div").show();
				}
			}
			if(data['declined']==1){
				$("#frm_ad").hide();
				$("#ka_div").show();
			}else{
				$("#frm_ad").show();
				$("#ka_div").hide();
			}
			if(data['declined']==1 || data['approved']==1){
				$("#button_submit").hide();
				$("#button_update").show();
			}else{
				$("#button_submit").show();
				$("#button_update").hide();
			}

			// }
		}
	</script>
@endsection

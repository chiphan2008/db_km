@extends('Admin..layout_admin.master_admin')

@section('content')

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			@if(session('successInsert'))
	      <div class="alert alert-success alert-dismissible fade in"
	           style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
	        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
	        </button>
	        {!! session('successInsert') !!}
	      </div>
	    @endif
	    @if(session('errorInsert'))
	      <div class="alert alert-danger alert-dismissible fade in"
	           style="color: #a94442;background-color: #f2dede;border-color: #ebccd1;" role="alert">
	        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
	        </button>
	        {!! session('errorInsert') !!}
	      </div>
	    @endif

			<div class="x_panel">
				<div class="x_title">
					<h2>Import data from thongtincongty.com</h2>
					<ul class="nav navbar-right panel_toolbox">
						<a href="{{route('list_content')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
					</ul>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<br>
					<form id="form-note-content" method="post" action=""
								enctype="multipart/form-data" autocomplete="off"
								data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
						{{ csrf_field() }}
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"> {{trans('global.city')}} <span class="required">*</span>
							</label>
							<div class="col-md-3 col-sm-3 col-xs-12">
								<select class="form-control" name="link_city" id="link_city"  data-size="8" onchange="getTotalPage(this)">
									@foreach($arr_city as $city)
										<option value="{{$city['link']}}" {{old('link_city')==$city['link']?'selected':''}}>{{$city['name']}}</option>
									@endforeach
								</select>
								<input type="hidden" name="city" id="city">
								<p><br/><b>Tổng số page: <span id="page">0</span></b></p>
							</div>
						</div>

						<div class="form-group form-inline">
							<label class="control-label col-md-3 col-sm-3 col-xs-12" for="note"></label>
							<div class="col-md-6 col-sm-6 col-xs-12">
									<label for="" class="label-control">Từ trang</label>
									<input type="number" class="form-control" min="1" max="999999" name="from_page" value="{{old('from_page')?old('from_page'):1}}">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<label for="" class="label-control">Đến trang</label>
									<input type="number" class="form-control" min="1" max="999999" name="to_page" value="{{old('to_page')?old('to_page'):1}}">
							</div>
						</div>
						<div class="form-group">
	            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="moderation">{{trans('Admin'.DS.'content.moderation')}}
	            </label>
	            <div class="col-md-3 col-sm-3 col-xs-12">
	              <select class="form-control" name="moderation" id="moderation">
	                <option value="request_publish" {{old('moderation')=='request_publish'?'selected':''}}>{{trans('Admin'.DS.'content.request_publish')}}</option>
	                <option value="publish" {{old('moderation')=='publish'?'selected':''}}>{{trans('Admin'.DS.'content.publish')}}</option>
	              </select>
	            </div>
	          </div>
	          <div class="form-group">
	            <label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'content.date')}}</label>
	            <div class="col-md-3 col-sm-3 col-xs-12">
	              <div class='input-group date' style="margin-bottom: 0px" id='date_created'>
	                <input type='text' class="form-control" name="date_created" value="{{old('date_created')}}"/>
	                <span class="input-group-addon">
	                  <span class="glyphicon glyphicon-calendar"></span>
	              </span>
	              </div>
	            </div>
	          </div>
						<div class="form-group text-center">
								<button type="submit" class="btn btn-success">Get Data</button>
						</div>
					</form>
				</div>
			</div>
		</div>

	</div>
@endsection
@section('JS')
	<script type="text/javascript">
		$(function () {
			$('#link_city').selectpicker({liveSearch: true});
			$("#link_city").trigger("change");
			$("input[type='number']").on('change',function(){
	      var max = $(this).attr('max');
	      var min = $(this).attr('min');
	      var length = max.toString().length;
	      var text = parseFloat($(this).val().toLowerCase());
	      if(text>max){
	        $(this).val(this.value.slice(0,length));
	        if($(this).val() > max){
	        	$(this).val(max);
	        }
	      }

	      if(text<min){
	        $(this).val(min);
	      }
	    });
	    $('#date_created').datetimepicker({
        format: 'DD-MM-YYYY'
      });
		})

		function getTotalPage(obj){
			var link = $(obj).val();
			$("#city").val($("#link_city option:selected").text());
			$.ajax({
				url : '/admin/clone/getPageThongTinCongTy',
				type: 'POST',
				data: {
						'_token':$('input[name="_token"]').val(),
						'link':link
				},
				success: function(res){
					$("#page").text(res);
					$("input[type=number]").attr("max",res);
					$("input[type='number']").on('change',function(){
			      var max = $(this).attr('max');
			      var min = $(this).attr('min');
			      var length = max.toString().length;
			      var text = parseFloat($(this).val().toLowerCase());
			      if(text>max){
			        $(this).val(this.value.slice(0,length));
			      }

			      if(text<min){
			        $(this).val(min);
			      }
			    });
				}
			});
		}
	</script>
@endsection
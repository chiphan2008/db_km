@extends('Admin..layout_admin.master_admin')

@section('content')
<!-- <div class="col-md-6 col-sm-6 col-xs-12">
	<div class="x_panel">
		<div class="x_title">
			<h2>Tạo file excel mẫu</h2>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
		</div>
	</div>
</div> -->
@if(session('status'))
  <div class="alert alert-warning alert-dismissible fade in"  role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
    {!! session('status') !!}
  </div>
@endif
<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
		<div class="x_title">
			<h2>Upload file excel</h2>
			<div class="clearfix"></div>
		</div>
		<div class="x_content">
			<form class="form-horizontal form-label-left" onsubmit="checkIsExcel()" action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">
				{{ csrf_field() }}
				<input type="hidden" name="id_category" value="{{$data['id_category']}}">
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="fileExcel">File Excel</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<input type="file" name="fileExcel" id="fileExcel" accept=".csv,.xls,.xlsx" >
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="fileImage">Image Avatar</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<input type="file" name="fileImage" id="fileImage" accept=".jpg,.png,.bmp,.jpeg" >
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
						<button type="submit" class="btn btn-success">Upload</button>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>
@endsection
@section('JS')
<script>
	function checkIsExcel() {
		var file = $('#fileExcel').val();
		var fileImage = $('#fileImage').val();
		if ((!/.*\.xlsx$/.test(file)) && (!/.*\.xls$/.test(file)) && (!/.*\.csv$/.test(file))) {
				alert("Please upload file excel");
				event.preventDefault();
				$('#fileExcel').focus();
				return false;
		}

		if (!/\.(jpe?g|png|gif|bmp)$/i.test(fileImage)) {
				alert("Please upload file image");
				event.preventDefault();
				$('#fileImage').focus();
				return false;
		}
		
		var file_upload = $('#fileExcel').get(0).files[0];
		if(file_upload.size > (1024*1024*8)){
			alert("File size must be less than 8MB");
			event.preventDefault();
			$('#fileExcel').focus();
			return false;
		}	
		var file_upload = $('#fileImage').get(0).files[0];
		if(file_upload.size > (1024*1024*8)){
			alert("File size must be less than 8MB");
			event.preventDefault();
			$('#fileImage').focus();
			return false;
		}	
		return true;
	}
</script>
@endsection
@extends('Admin..layout_admin.master_admin')

@section('content')

  <div class="col-md-12 col-sm-12 col-xs-12">


    <div class="x_panel">
      <div class="x_title">
        <h2>Update</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <form class="form-horizontal form-label-left" action="{{route('update_location_foody')}}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="fileExcel"></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input type="file" name="fileExcel" id="fileExcel" accept=".csv,.xls,.xlsx">
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <button type="submit" class="btn btn-success">Update</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>

@endsection
@section('JS')
  <script>
    var base_url = {!! json_encode(url('/')) !!};

    function checkIsExcel(site,type) {
      if(site != 'vietbando')
      {
        var file = $('#fileExcel').val();
        if ((!/.*\.xlsx$/.test(file)) && (!/.*\.xls$/.test(file)) && (!/.*\.csv$/.test(file))) {
          alert("Please upload file excel");
          event.preventDefault();
          return false;
        }
        var file_upload = $('#fileExcel').get(0).files[0];
        if(file_upload.size > (1024*1024*8)){
          alert("File size must be less than 8MB");
          event.preventDefault();
          return false;
        }
      }
      return true;

    }
  </script>
@endsection
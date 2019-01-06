@extends('Admin..layout_admin.master_admin')

@section('content')

  <form class="form-horizontal form-label-left" method="post" action="{{route('update_client_permission',['id' => $permission->id])}}" enctype="multipart/form-data">
    {{ csrf_field() }}
    @if ($errors->has('error'))
      <span style="color: red">{{ $errors->first('error') }}</span>
    @endif
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'permission.name')}} <span
          class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="name" name="name" class="form-control col-md-7 col-xs-12" value="{{ $permission->name?$permission->name:'' }}" >
      </div>
      @if ($errors->has('name'))
        <span style="color: red">{{ $errors->first('name') }}</span>
      @endif
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="machine_name">{{trans('Admin'.DS.'permission.machine_name')}} <span
          class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="machine_name" name="machine_name" class="form-control col-md-7 col-xs-12" value="{{ $permission->machine_name?$permission->machine_name:'' }}" readonly="">
      </div>
      @if ($errors->has('machine_name'))
        <span style="color: red">{{ $errors->first('machine_name') }}</span>
      @endif
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">{{trans('Admin'.DS.'permission.description')}}</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <textarea type="text" id="description" name="description" class="form-control col-md-7 col-xs-12">{{ $permission->description?$permission->description:'' }}</textarea>
      </div>
      @if ($errors->has('description'))
        <span style="color: red">{{ $errors->first('description') }}</span>
      @endif
    </div>
    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'permission.update_permission')}}</button>
      </div>
    </div>
  </form>
@endsection

@section('JS')
<script type="text/javascript">
  $(function(){
    $("#module").change();
  })
  var white_list = <? echo $permission->white_list; ?>;
  var black_list = <? echo $permission->black_list; ?>;
  function loadListContent(obj) {
    var module = $(obj).val();
    $.ajax({
      url : '/admin/permission/list-content',
      type: 'POST',
      data: {
        module : module,
        _token : '{{ csrf_token() }}'
      },
      success: function(res) {
        if(res.error == 0){
          var list = res.data;
          var html_white_list = '';
          var html_black_list = '';
          list.forEach(function(value,key) {
            if(white_list.indexOf(value.machine_name)==-1){
              html_white_list += '<option value="'+value.machine_name+'">'+value.name+'</option>';
            }else{
              html_white_list += '<option value="'+value.machine_name+'" selected>'+value.name+'</option>';
            }

             if(black_list.indexOf(value.machine_name)==-1){
              html_black_list += '<option value="'+value.machine_name+'">'+value.name+'</option>';
            }else{
              html_black_list += '<option value="'+value.machine_name+'" selected>'+value.name+'</option>';
            }
          })
          $('#white_list').html(html_white_list);
          $('#black_list').html(html_black_list);
        }else{
          alert(res.message);
        }
      }
    });
  }
</script>
@endsection

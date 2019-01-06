@extends('Admin..layout_admin.master_admin')

@section('content')

  <form class="form-horizontal form-label-left" method="post" action="{{route('add_permission')}}" enctype="multipart/form-data">
    {{ csrf_field() }}
    @if ($errors->has('error'))
      <span style="color: red">{{ $errors->first('error') }}</span>
    @endif
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="display_name">{{trans('Admin'.DS.'permission.name')}} <span
          class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="display_name" name="display_name" class="form-control col-md-7 col-xs-12" value="{{ old('display_name') }}" >
      </div>
      @if ($errors->has('display_name'))
        <span style="color: red">{{ $errors->first('display_name') }}</span>
      @endif
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="type">{{trans('Admin'.DS.'permission.type')}} </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="form-control" name="type" id="type">
          <option value="all" {{old('type')=='all'?'selected':''}}>{{trans('Admin'.DS.'permission.all_type')}}</option>
          <option value="view" {{old('type')=='view'?'selected':''}}>{{trans('global.view')}}</option>
          <option value="add" {{old('type')=='add'?'selected':''}}>{{trans('global.add')}}</option>
          <option value="edit" {{old('type')=='edit'?'selected':''}}>{{trans('global.edit')}}</option>
          <option value="delete" {{old('type')=='delete'?'selected':''}}>{{trans('global.delete')}}</option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="module">{{trans('Admin'.DS.'permission.module')}} </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="form-control" name="module" id="module" onchange="loadListContent(this)">
         @if (isset($modules))
            @foreach ($modules as $module)
              <option value="{{$module['value']}}" {{ old('module') == $module['value'] ? 'selected' : '' }}>{{$module['name']}}</option>
            @endforeach
          @endif
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="white_list">{{trans('Admin'.DS.'permission.white_list')}} </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="form-control" name="white_list[]" id="white_list" multiple>
          <!-- <option value="">-- No content --</option> -->
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="white_list">{{trans('Admin'.DS.'permission.black_list')}} </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <select class="form-control" name="black_list[]" id="black_list" multiple>
          <!-- <option value="">-- No content --</option> -->
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">{{trans('Admin'.DS.'permission.description')}}</label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <textarea type="text" id="description" name="description" class="form-control col-md-7 col-xs-12">{{ old('description') }}</textarea>
      </div>
      @if ($errors->has('description'))
        <span style="color: red">{{ $errors->first('description') }}</span>
      @endif
    </div>

    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'permission.add_permission')}}</button>
      </div>
    </div>
  </form>
@endsection
@section('JS')
<script type="text/javascript">
  $(function(){
    $("#module").change();
  })
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
          var html = '';
          list.forEach(function(value,key) {
            html += '<option value="'+value.machine_name+'">'+value.name+'</option>';
          })
          $('#white_list').html(html);
          $('#black_list').html(html);
        }else{
          alert(res.message);
        }
      }
    });
  }
</script>
@endsection

@extends('Admin..layout_admin.master_admin')

@section('content')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Change Owner Content</h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('update_content', ['category_type'=>$content->_category_type->machine_name,'id' => $content->id])}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
          <form id="form-note-content" method="post" action="{{route('change_owner',['id'=>$content->id])}}"
                enctype="multipart/form-data" autocomplete="off"
                data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label col-md-1 col-sm-1 col-xs-12" for="note">Change Owner <span class="required">*</span>
              </label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <select class="form-control" name="change_owner" data-size="8" id="change_owner" size="2" >
                  <option value="">{{trans('Admin'.DS.'content.nothing_selected')}}</option>
                  @foreach($client as $value)
                    <option value="{{$value->id}}" {{ $content->type_user == 0 && $value->id == $content->created_by ? 'selected' : '' }}>{{$value->full_name}}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-2 col-sm-2 col-xs-12">
                <button type="submit" class="btn btn-success">Update</button>
              </div>
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
      $('#change_owner').selectpicker({liveSearch: true});
    })
  </script>
@endsection
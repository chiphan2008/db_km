@extends('Admin..layout_admin.master_admin')

@section('content')

  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2>Form Note Content</h2>
          <ul class="nav navbar-right panel_toolbox">
            <a href="{{route('update_content', ['category_type'=>$content->_category_type->machine_name,'id' => $content->id])}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <br>
          <form id="form-note-content" method="post" action="{{route('note_content',['id'=>$content->id])}}"
                enctype="multipart/form-data" autocomplete="off"
                data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
          {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label col-md-1 col-sm-1 col-xs-12" for="note">Message <span class="required">*</span>
              </label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <textarea id="note" required="required" class="form-control" name="note" rows="5" maxlength="1000"></textarea>
              </div>
              <div class="col-md-2 col-sm-2 col-xs-12">
                <button type="submit" class="btn btn-success">Send</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="x_content">
      <div class="dashboard-widget-content">
        <ul class="list-unstyled timeline widget">
          @foreach($note_content as $note)
            <li>
              <div class="block">
                <div class="block_content">
                  <h2 class="title byline">
                    <span>{{date('d-m-Y H:i',strtotime($note->created_at))}}</span> by <a>{{$note->_user_create->full_name}}</a>
                  </h2>
                  <p class="excerpt">
                    {{$note->note}}
                  </p>
                </div>
              </div>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
@endsection
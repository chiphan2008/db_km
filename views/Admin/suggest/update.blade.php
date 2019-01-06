@extends('Admin..layout_admin.master_admin')

@section('content')

  <form id="form-suggest" class="form-horizontal form-label-left" method="post" action="{{route('update_suggest',['id' => $suggest->id])}}" enctype="multipart/form-data">
    {{ csrf_field() }}
    @if ($errors->has('error'))
      <span style="color: red">{{ $errors->first('error') }}</span>
    @endif
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keyword">{{trans('Admin'.DS.'suggest.keyword')}} <span
          class="required">*</span>
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="text" id="keyword" name="keyword" class="form-control col-md-7 col-xs-12" value="{{ $suggest->keyword?$suggest->keyword:'' }}" >
      </div>
      @if ($errors->has('keyword'))
        <span style="color: red">{{ $errors->first('keyword') }}</span>
      @endif
    </div>
    <div class="form-group">
      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="weight">{{trans('Admin'.DS.'suggest.weight')}}
      </label>
      <div class="col-md-6 col-sm-6 col-xs-12">
        <input type="number" min="0" id="weight" name="weight" class="form-control col-md-7 col-xs-12" value="{{ $suggest->weight?$suggest->weight:0 }}">
      </div>
      @if ($errors->has('weight'))
        <span style="color: red">{{ $errors->first('weight') }}</span>
      @endif
    </div>

    <div class="form-group">
      <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
        <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'suggest.update_suggest')}}</button>
      </div>
    </div>
  </form>
@endsection

@section('JS')
<script type="text/javascript">
</script>
@endsection

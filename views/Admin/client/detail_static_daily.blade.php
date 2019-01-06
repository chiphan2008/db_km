@extends('Admin.layout_admin.master_admin')

@section('content')
  <div id="datatable_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
    <div class="row">

      <div class="col-sm-12">
        <ul class="nav navbar-right panel_toolbox">
          <a href="{{route('list_dai_ly')}}" style="float: right" class="btn btn-primary">{{trans('global.back')}}</a>
        </ul>
        <ul class="nav nav-tabs">
          <li class="{{isset($type)&&$type =='info'?'active':''}}"><a href="{{route('detail_dai_ly',['id'=>$client->id,'type'=>'info'])}}">{{trans('Admin'.DS.'client.info')}}</a></li>
          <li class="{{isset($type)&&$type =='content'?'active':''}}"><a href="{{route('detail_dai_ly',['id'=>$client->id,'type'=>'content'])}}">{{trans('global.locations')}} ({{$count_content}})</a></li>
          <li class="{{isset($type)&&$type =='static'?'active':''}}"><a href="{{route('detail_dai_ly',['id'=>$client->id,'type'=>'static'])}}">{{trans('Admin'.DS.'client.static')}}</a></li>
        </ul>

        <div class="x_panel">
          <form id="detail_client" data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
            <div class="form-group row col-md-10" style="margin-bottom: 10px;">
              <?php $total = 0; ?>
              <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info">
                <tr>
                  @foreach($static as $st)
                    <th>{{trans('global.'.$st->type)}}</th>
                  <?php $total += $st->sum; ?>
                  @endforeach
                </tr>
                <tr>
                  @foreach($static as $st)
                    <td>{{number_format($st->sum)}}</td>
                  @endforeach
                </tr>
              </table>
              
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
@endsection

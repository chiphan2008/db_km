@extends('Admin..layout_admin.master_admin')

@section('content')
  <div class="row">
    <form id="form-update-content" method="post" action="{{route('post_update_category_of_content',['id'=>$content->id])}}"
                enctype="multipart/form-data" autocomplete="off"
                data-parsley-validate="" class="form-horizontal form-label-left" novalidate="">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
              {{ csrf_field() }}

            <h1> {{$content->name }} </h1>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_category">{{trans('Admin'.DS.'category_item.parent')}}<span
                        class="required">*</span></label>
              <div class="col-md-9 col-sm-9 col-xs-12">

                <select class="form-control" name="id_category" id="id_category">
                  @foreach($data['categories'] as $value => $name)
                    <option
                            value="{{$value}}" {{$value==$content->id_category ? 'selected':''}}>{{$name}}
                    </option>
                  @endforeach
                </select>

                @if ($errors->has('category_items'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('category_items') }}</li>
                  </ul>
                @endif
              </div>
            </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_item">{{trans('Admin'.DS.'content.cat_item')}}<span
                    class="required">*</span></label>
                <div class="col-md-9 col-sm-9 col-xs-12">

                  <select class="form-control" name="category_item[]" id="category_item" multiple >
                    @foreach($data['list_category_item'] as $value => $name)
                      <option
                        value="{{$value}}" {{in_array($value, $data['list_category_item_content']) ? 'selected':''}}>{{$name}}
                      </option>
                    @endforeach
                  </select>

                  @if ($errors->has('category_item'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{ $errors->first('category_item') }}</li>
                    </ul>
                  @endif
                </div>
              </div>

              @if(count($data['list_service']) > 0)
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="service">{{trans('Admin'.DS.'content.service')}}</label>
                  <div class="col-md-9 col-sm-9 col-xs-12" id="show_list_service">
                    @foreach($data['list_service'] as $value)
                      <div class="col-md-4 col-sm-3 col-xs-12" style="padding-left: 0px;">
                        <div class="checkbox">
                          <label style="padding-left: 0px;">
                            <input type="checkbox" class="flat" name="service[]" value="{{$value->id_service_item}}" {{in_array($value->id_service_item, $data['list_service_content']) ? 'checked':''}}> {{$value->_service_item->name}}
                          </label>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @endif

            <div class="form-group">
              <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'content.update_content')}}</button>
              </div>
            </div>

          </div>
        </div>
      </div>

    </form>
  </div>
@endsection

@section('JS')

  <script type="text/javascript">
    $(function () {
        var base_url = {!! json_encode(url('/')) !!};
      $('#category_item').selectpicker({liveSearch: true});

      $('#id_category').selectpicker({liveSearch: true});

        $('#id_category').on('change', function() {
            $.ajax({
                type: "GET",
                url: base_url + '/admin/content/ajaxListService/'+this.value,
                success: function (data) {
                    $("#category_item").html(data.html_category_item);
                    $('#category_item').selectpicker('refresh');
                    $("#show_list_service").html(data.html_category_services);
                    $('input.flat').iCheck({
                        checkboxClass: 'icheckbox_flat-green',
                        radioClass: 'iradio_flat-green'
                    });
                }
            })
        });
    });

  </script>
@endsection

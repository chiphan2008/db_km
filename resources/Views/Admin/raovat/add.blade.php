@extends('Admin..layout_admin.master_admin')

@section('content')
<!-- page content -->
<div class="row">
  <form id="form-content" method="post"  action="{{route('add_raovat')}}" enctype="multipart/form-data"
              data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" autocomplete="off">
    <div class="col-md-8 col-sm-8 col-xs-12">
      <div class="x_panel" style="min-height: 250px;">
        <div class="x_content">
          <br/>  
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'raovat.name')}} <span
                  class="required">*</span>
              </label>
              <div class="col-md-9 col-xs-12">
                <input type="text" id="name" name="name"
                       class="form-control col-md-7 col-xs-12 {{$errors->has('name')?'parsley-error':''}}"
                       value="{{ old('name') }}" >
                @if ($errors->has('name'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('name') }}</li>
                  </ul>
                @endif
              </div>
            </div>


            
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content">{{trans('Admin'.DS.'raovat.raovat_content')}} <span class="required">*</span></label>
              <div class="col-md-9 col-xs-12">
                <textarea type="text" id="raovat_content" name="content"
                          class="form-control col-md-7 col-xs-12 {{$errors->has('content')?'parsley-error':''}}"  placeholder="{{trans('Admin'.DS.'raovat.raovat_require')}}">{!!  old('content') !!}</textarea>
                @if ($errors->has('content'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('content') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="raovat_from">{{trans('Admin'.DS.'raovat.raovat_from')}}</label>
              <div class="col-md-9 col-xs-12">
                <input type="text" id="raovat_from" name="raovat_from"
                       class="raovat_from form-control col-md-7 col-xs-12 {{$errors->has('raovat_from')?'parsley-error':''}}"
                       value="{{ old('raovat_from') }}" >
                @if ($errors->has('raovat_from'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('raovat_from') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="raovat_to">{{trans('Admin'.DS.'raovat.raovat_to')}}</label>
              <div class="col-md-9 col-xs-12">
                <input type="text" id="raovat_to" name="raovat_to"
                       class="raovat_to form-control col-md-7 col-xs-12 {{$errors->has('raovat_to')?'parsley-error':''}}"
                       value="{{ old('raovat_to') }}" >
                @if ($errors->has('raovat_to'))
                  <ul class="parsley-errors-list filled">
                    <li class="parsley-required">{{ $errors->first('raovat_to') }}</li>
                  </ul>
                @endif
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="raovat_image">{{trans('Admin'.DS.'raovat.raovat_image')}}
              </label>
              <div class="col-md-9 col-xs-12">
                <div  id="raovat_image_upload">
                  <input type="file" id="raovat_image" name="raovat_image[]" accept="image/*"
                         multiple
                         onchange="readURL(this,'list_raovat_image')"/>
                  <br/>
                  <div class="col-md-12 row" id="list_raovat_image">
                  </div>
                </div>
              </div>
            </div>

            <br/>
        </div>
      </div>
    </div>

    <div class="col-md-4 col-sm-4 col-xs-12">
      <div class="x_panel" style="min-height: 250px;">
        <div class="x_content">
          <div class="form-group">
            <!-- <label class="control-label col-md-3 col-sm-3 col-xs-12"></label> -->
            <div class="col-xs-12">
              {{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ old('active') == '1' ? 'checked' : '' }} name="active"> {{trans('global.active')}}
            </div>
          </div>
          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-8 col-xs-12 col-md-offset-3">
              <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'raovat.add_raovat')}}</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection
@section('CSS')
<style type="text/css" media="screen">
  .remove_custom_open{
    position: absolute;
    cursor: pointer;
    margin-top: 10px;
  }
  .item_custom_open{
    margin-top: 10px;
  }
</style>

<link href="{{asset('template/js/datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
@endsection

@section('JS')

  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
  <script src="{{asset('template/vendors/moment/min/moment.min.js')}}"></script>
  <script src="{{asset('template/vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
  <script src="{{asset('template/js/datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
  <script src="https://cdn.ckeditor.com/4.8.0/standard/ckeditor.js"></script>
  <script>
    var arrContent = [];
    $(function(){
      $("#type").trigger("change");
      $('#select_client').selectpicker({
        iconBase: 'fa',
      }).trigger("change");

      $("#content").select2({
        ajax:{
          url       : "{{route('search_content')}}",
          type      : "GET",
          delay     : 800,
          dataType  :'json',
          data : function(params){
            var query = {
              query: params.term
            }
            return query;
          },
          cache:true
          // processResults: function (data) {
          //   return {
          //     results: data.items
          //   };
          // }
        },
        minimumInputLength: 1,
        closeOnSelect: false
      })

      $('.choose_hour').datetimepicker({
        format: 'HH:mm',
        defaultDate: moment().hours(8).minutes(0).seconds(0).milliseconds(0)
      });

      $('.raovat_from').datetimepicker({
        format: 'DD-MM-Y',
        defaultDate: moment(),
        minDate: moment().millisecond(0).second(0).minute(0).hour(0).format()
      });

      $('.raovat_to').datetimepicker({
        format: 'DD-MM-Y',
        defaultDate: moment().add(1, 'd').format(),
        minDate: moment().millisecond(0).second(59).minute(59).hour(23).format()
      });

      // CKEDITOR.replace( 'raovat_content',{
      //      language: '{{\App::getLocale()=='vn'?'vi':'en'}}'
      // });

      //$("#list_content_raovat").val($("#list_content_raovat option").first().prop('value')).trigger("change");
    })


    function addCustomOpen(){
      var index = $(".item_custom_open").length;
      index++;

      html = '<div class="col-md-9 col-sm-9 col-md-offset-3 col-xs-12 item_custom_open">';
      html +='          <div class="col-md-3">';
      html +='            <select class="form-control" name="date_open['+index+'][from_date]" id="">';
      html +='              <option value="1">{{trans('Admin'.DS.'content.monday')}}</option>';
      html +='              <option value="2">{{trans('Admin'.DS.'content.tuesday')}}</option>';
      html +='              <option value="3">{{trans('Admin'.DS.'content.wednesday')}}</option>';
      html +='              <option value="4">{{trans('Admin'.DS.'content.thursday')}}</option>';
      html +='              <option value="5">{{trans('Admin'.DS.'content.friday')}}</option>';
      html +='              <option value="6">{{trans('Admin'.DS.'content.saturday')}}</option>';
      html +='              <option value="0">{{trans('Admin'.DS.'content.sunday')}}</option>';
      html +='            </select>';
      html +='          </div>';
      html +='          <div class="col-md-3">';
      html +='            <select class="form-control" name="date_open['+index+'][to_date]" id="">';
      html +='              <option value="1">{{trans('Admin'.DS.'content.monday')}}</option>';
      html +='              <option value="2">{{trans('Admin'.DS.'content.tuesday')}}</option>';
      html +='              <option value="3">{{trans('Admin'.DS.'content.wednesday')}}</option>';
      html +='              <option value="4">{{trans('Admin'.DS.'content.thursday')}}</option>';
      html +='              <option value="5">{{trans('Admin'.DS.'content.friday')}}</option>';
      html +='              <option value="6">{{trans('Admin'.DS.'content.saturday')}}</option>';
      html +='              <option value="0">{{trans('Admin'.DS.'content.sunday')}}</option>';
      html +='            </select>';
      html +='          </div>';
      html +='          <div class="col-md-3">';
      html +='            <input class="form-control choose_hour" type="text" name="date_open['+index+'][from_hour]" value="" >';
      html +='          </div>';
      html +='          <div class="col-md-3">';
      html +='            <input class="form-control choose_hour" type="text" name="date_open['+index+'][to_hour]" value="" >';
      html +='          </div>';
      html +='  <span><i class="remove_custom_open fa fa-remove" onclick="removeCustomOpen(this)"></i></span>';
      html +='</div>';
      $("#append_custom_open").append(html);

      $('.choose_hour').datetimepicker({
        format: 'HH:mm',
        defaultDate: moment().hours(8).minutes(0).seconds(0).milliseconds(0)
      });
    }

    function removeCustomOpen(obj) {
      $(obj).parent().parent().remove();
    }

    function createDiscount(){
      var form = $('#form-create-raovat')[0];
      var dataPost = new FormData(form);
      dataPost.append('_token', $("meta[name='_token']").prop('content'));
      if(editAvatar !== undefined)
      {
        dataPost.append('avatar', editAvatar);
      }
      for (var i = 0; i < create_raovat_image.length; i++) {
        dataPost.append('raovat_image[' + i + ']', create_raovat_image[i]);
      }

      // dataPost.append('content', CKEDITOR.instances['raovat_content'].getData());
      dataPost.append('img_from_content', $("#img_from_content").is(":checked")?1:0);

      $.ajax({
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data: dataPost,
        url: '/raovat/postCreateDiscount',
        beforeSend: function(){
          $("#loading").show();
        },
        success: function (data) {
          $("#loading").hide();
          console.log(data);
        }
      })
    }

    function showImageUpload(){
      $("#raovat_image_upload").toggle('fast');

    }

    function readURL(input, type) {
      $('#' + type).text('');

      for (var i = 0; i < input.files.length; i++) {
        if (input.files[i]) {
          var reader = new FileReader();

          reader.onload = function (e) {
            var img = $('<img style="height: 90px; width: 90px; border: 1px solid #000; margin: 10px 18px 10px 2px">');
            img.attr('src', e.target.result);
            img.appendTo('#' + type);
          };
          reader.readAsDataURL(input.files[i]);
        }
      }
    }

    function changeType(obj){
      var type = $(obj).val();
      switch (type){
        case 'percent':
          hideAllType();
          $('.from_percent').show();
          break;
        case 'percent_fromto':
          hideAllType();
          $('.from_percent').show();
          $('.split').show();
          $('.to_percent').show();
          break;
        case 'price':
          hideAllType();
          $('.from_price').show();
          $('.currency').show();
          break;
        case 'price_fromto':
          hideAllType();
          $('.from_price').show();
          $('.split').show();
          $('.to_price').show();
          $('.currency').show();
          break;
        case 'other':
          hideAllType();
          $('.other').show();
          break;
        default:
          hideAllType();
          $('.other').show();
          break;

      }
    }
    function hideAllType(){
      $('.from_percent').hide();
      $('.from_price').hide();
      $('.split').hide();
      $('.to_percent').hide();
      $('.to_price').hide();
      $('.currency').hide();
      $('.other').hide();
    }
  </script>
@endsection

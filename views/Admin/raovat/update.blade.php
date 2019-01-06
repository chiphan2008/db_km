@extends('Admin..layout_admin.master_admin')

@section('content')
  <!-- page content -->
  <div class="row">
    <form id="form-content" method="post"  action="{{route('update_discount',['id'=>$discount->id])}}" enctype="multipart/form-data"
                data-parsley-validate="" class="form-horizontal form-label-left" novalidate="" autocomplete="off">
      <div class="col-md-8 col-sm-8 col-xs-12">
        <div class="x_panel" style="min-height: 250px;">
          <div class="x_content">
            <br/>  
              {{ csrf_field() }}
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">{{trans('Admin'.DS.'discount.name')}} <span
                    class="required">*</span>
                </label>
                <div class="col-md-9 col-xs-12">
                  <input type="text" id="name" name="name"
                         class="form-control col-md-7 col-xs-12 {{$errors->has('name')?'parsley-error':''}}"
                         value="{{ $discount->name }}" >
                  @if ($errors->has('name'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{ $errors->first('name') }}</li>
                    </ul>
                  @endif
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alias">{{trans('Admin'.DS.'discount.discount_text')}} <span
                    class="required">*</span>
                </label>
                <div class="col-md-9 col-xs-12">
                  <input type="text" id="slogan" name="slogan"
                         class="form-control col-md-7 col-xs-12 {{$errors->has('slogan')?'parsley-error':''}}"
                         value="{{ $discount->slogan }}"  maxlength="20">
                  @if ($errors->has('slogan'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{ $errors->first('slogan') }}</li>
                    </ul>
                  @endif
                </div>
              </div>  

              <!-- <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="type">{{trans('Admin'.DS.'discount.discount_type')}}</label>
                <div class="col-md-9 col-xs-12">
                  <select class="form-control" name="type" id="type">
                    <option value="cua_hang" {{$discount->type == 'cua_hang'?'selected':''}}>{{trans('Admin'.DS.'discount.discount_store')}}</option>
                    <option value="online" {{$discount->type == 'online'?'selected':''}}>{{trans('Admin'.DS.'discount.discount_online')}}</option>
                    <option value="tong_hop" {{$discount->type == 'tong_hop'?'selected':''}}>{{trans('Admin'.DS.'discount.discount_general')}}</option>
                  </select>
                  @if ($errors->has('type'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{ $errors->first('type') }}</li>
                    </ul>
                  @endif
                </div>
              </div> -->

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content">{{trans('Admin'.DS.'discount.content')}} <span
                    class="required">*</span></label>
                <div class="col-md-9 col-xs-12">
                  <select class="form-control" name="content[]" id="content" multiple >
                  	@if($discount->_contents)
                  	@foreach($discount->_contents as $content)
                  	<option value="{{$content->id}}" selected>{{$content->name}}</option>
                  	@endforeach
                  	@endif
                  </select>
                  @if ($errors->has('content'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{ $errors->first('content') }}</li>
                    </ul>
                  @endif
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="type">{{trans('Admin'.DS.'discount.discount_type')}}</label>
                <div class="col-md-9 col-xs-12">
                  <select class="form-control" name="type" id="type" onchange="changeType(this)">
                    <!-- <option value="cua_hang">{{trans('Admin'.DS.'discount.discount_store')}}</option>
                    <option value="online">{{trans('Admin'.DS.'discount.discount_online')}}</option>
                    <option value="tong_hop">{{trans('Admin'.DS.'discount.discount_general')}}</option> -->
                    <option value="percent" {{$discount->type=='percent'?'selected':''}}>{{trans('Admin'.DS.'discount.percent')}}</option>
                    <option value="percent_fromto" {{$discount->type=='percent_fromto'?'selected':''}}>{{trans('Admin'.DS.'discount.percent_fromto')}}</option>
                    <option value="price" {{$discount->type=='price'?'selected':''}}>{{trans('Admin'.DS.'discount.price')}}</option>
                    <option value="price_fromto" {{$discount->type=='price_fromto'?'selected':''}}>{{trans('Admin'.DS.'discount.price_fromto')}}</option>
                    <option value="other" {{$discount->type=='other'?'selected':''}}>{{trans('Admin'.DS.'discount.other')}}</option>
                  </select>
                  @if ($errors->has('type'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{ $errors->first('type') }}</li>
                    </ul>
                  @endif
                </div>
                <br><br/>
                <div class="col-md-9 col-md-offset-3 col-xs-12">
                    <div class="from_percent col-xs-3" style="display: none;">
                      <div class="input-group row">
                        <input value="{{$discount->from_percent}}" name="from_percent" type="percent"  maxlength="5" class="form-control" placeholder="" aria-describedby="basic-addon1">
                        <span class="input-group-addon" id="basic-addon1">%</span>
                      </div>
                    </div>
                    <div class="from_price col-xs-3" style="display: none;">
                      <div class="input-group row">
                        <input value="{{$discount->from_price}}" name="from_price" type="number" class="form-control" placeholder="" aria-describedby="basic-addon1">
                      </div>
                    </div>
                    <div class="split col-xs-1 text-center" style="display: none;">
                            -
                    </div>
                    <div class="to_percent col-xs-3" style="display: none;">
                      <div class="input-group row">
                        <input value="{{$discount->to_percent}}" name="to_percent" type="percent"  maxlength="5" class="form-control" placeholder="" aria-describedby="basic-addon1">
                        <span class="input-group-addon" id="basic-addon1">%</span>
                      </div>
                    </div>
                    <div class="to_price col-xs-3" style="display: none;">
                      <div class="input-group row">
                        <input value="{{$discount->to_price}}" name="to_price" type="number" class="form-control" placeholder="" aria-describedby="basic-addon1">
                      </div>
                    </div>
                    <div class="currency col-xs-3" style="display: none;">
                        <select class="form-control">
                          <option value="VND" {{$discount->currency=='VND'?'selected':''}}>VND</option>
                          <option value="USD" {{$discount->currency=='USD'?'selected':''}}>USD</option>
                        </select>
                    </div>

                    <div class="other col-xs-12" style="display: none;">
                      <div class="row">
                        <input value="{{$discount->short_text}}" name="short_text" type="text" class="form-control" placeholder="{{trans('Admin'.DS.'discount.other_example')}}" aria-describedby="basic-addon1">
                      </div>
                    </div>
                </div>
              </div>

              <!-- <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="discount_phone">{{trans('Admin'.DS.'discount.discount_phone')}}</label>
                <div class="col-md-9 col-xs-12">
                  <input type="text" id="discount_phone" name="discount_phone"
                         class="form-control col-md-7 col-xs-12 {{$errors->has('discount_phone')?'parsley-error':''}}"
                         value="{{ $discount->phone }}" >
                  @if ($errors->has('discount_phone'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{ $errors->first('discount_phone') }}</li>
                    </ul>
                  @endif
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="discount_link">{{trans('Admin'.DS.'discount.discount_link')}}</label>
                <div class="col-md-9 col-xs-12">
                  <input type="text" id="discount_link" name="discount_link"
                         class="form-control col-md-7 col-xs-12 {{$errors->has('discount_link')?'parsley-error':''}}"
                         value="{{ $discount->link }}" >
                  @if ($errors->has('discount_link'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{ $errors->first('discount_link') }}</li>
                    </ul>
                  @endif
                </div>
              </div>
 -->
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="discount_from">{{trans('Admin'.DS.'discount.discount_from')}}</label>
                <div class="col-md-9 col-xs-12">
                  <input type="text" id="discount_from" name="discount_from"
                         class="discount_from form-control col-md-7 col-xs-12 {{$errors->has('discount_from')?'parsley-error':''}}"
                         value="{{date('d-m-Y',strtotime($discount->date_from))}}" >
                  @if ($errors->has('discount_from'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{ $errors->first('discount_from') }}</li>
                    </ul>
                  @endif
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="discount_to">{{trans('Admin'.DS.'discount.discount_to')}}</label>
                <div class="col-md-9 col-xs-12">
                  <input type="text" id="discount_to" name="discount_to"
                         class="discount_to form-control col-md-7 col-xs-12 {{$errors->has('discount_to')?'parsley-error':''}}"
                         value="{{date('d-m-Y',strtotime($discount->date_to))}}" >
                  @if ($errors->has('discount_to'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{ $errors->first('discount_to') }}</li>
                    </ul>
                  @endif
                </div>
              </div>
              
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="type">{{trans('Admin'.DS.'discount.user')}}</label>
                <div class="col-md-9 col-xs-12">
                  <select name="user" class="form-control selectpicker" id="select_client" data-live-search="true">
                    <option value=""></option>
                    @foreach($clients as $client)
                    <option data-content="{{$client->full_name}}" value="{{$client->id}}" {{$discount->type_user==0 && $discount->created_by==$client->id?'selected':''}}>{{$client->full_name}}</option>
                    @endforeach
                  </select>
                  @if ($errors->has('user'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{ $errors->first('user') }}</li>
                    </ul>
                  @endif
                </div>
              </div>

              <div class="form-group" id="custom_open">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="group">{{trans('Admin'.DS.'discount.discount_open')}} <span class="required">*</span></label>
                <div class="col-md-9 col-xs-12">
                  <div class="col-md-3"><label for="">{{trans('Admin'.DS.'discount.from_date')}}</label></div>
                  <div class="col-md-3"><label for="">{{trans('Admin'.DS.'discount.to_date')}}</label></div>
                  <div class="col-md-3"><label for="">{{trans('Admin'.DS.'discount.from_hour')}}</label></div>
                  <div class="col-md-3"><label for="">{{trans('Admin'.DS.'discount.to_hour')}}</label></div>
                </div>
                @if($discount->_date_open)
                @foreach($discount->_date_open as $key => $date)
                <div class="col-md-9 col-xs-12 {{$key>0?'col-md-offset-3':''}}">
                  <div class="col-md-3">
                    <select class="form-control" name="date_open[0][from_date]" id="">
                      <option value="1" {{$date->date_from==1?'selected':''}}>{{trans('Admin'.DS.'discount.monday')}}</option>
                      <option value="2" {{$date->date_from==2?'selected':''}}>{{trans('Admin'.DS.'discount.tuesday')}}</option>
                      <option value="3" {{$date->date_from==3?'selected':''}}>{{trans('Admin'.DS.'discount.wednesday')}}</option>
                      <option value="4" {{$date->date_from==4?'selected':''}}>{{trans('Admin'.DS.'discount.thursday')}}</option>
                      <option value="5" {{$date->date_from==5?'selected':''}}>{{trans('Admin'.DS.'discount.friday')}}</option>
                      <option value="6" {{$date->date_from==6?'selected':''}}>{{trans('Admin'.DS.'discount.saturday')}}</option>
                      <option value="0" {{$date->date_from==0?'selected':''}}>{{trans('Admin'.DS.'discount.sunday')}}</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <select class="form-control" name="date_open[0][to_date]" id="">
                      <option value="1" {{$date->date_to==1?'selected':''}}>{{trans('Admin'.DS.'discount.monday')}}</option>
                      <option value="2" {{$date->date_to==2?'selected':''}}>{{trans('Admin'.DS.'discount.tuesday')}}</option>
                      <option value="3" {{$date->date_to==3?'selected':''}}>{{trans('Admin'.DS.'discount.wednesday')}}</option>
                      <option value="4" {{$date->date_to==4?'selected':''}}>{{trans('Admin'.DS.'discount.thursday')}}</option>
                      <option value="5" {{$date->date_to==5?'selected':''}}>{{trans('Admin'.DS.'discount.friday')}}</option>
                      <option value="6" {{$date->date_to==6?'selected':''}}>{{trans('Admin'.DS.'discount.saturday')}}</option>
                      <option value="0" {{$date->date_to==0?'selected':''}}>{{trans('Admin'.DS.'discount.sunday')}}</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <input class="form-control choose_hour" type="text" name="date_open[0][from_hour]" value="{{$date->time_from}}" required="">
                  </div>
                  <div class="col-md-3">
                    <input class="form-control choose_hour" type="text" name="date_open[0][to_hour]" value="{{$date->time_to}}" required="">
                  </div>

                  @if($key>0)
                  <span><i class="remove_custom_open fa fa-remove" onclick="removeCustomOpen(this)"></i></span>
                  @endif
                </div>
                @endforeach
                @endif
                <div id="append_custom_open"></div>
                <div class="col-md-6 col-md-offset-4 text-center col-xs-12" id="add_custom_open">
                  <br/>
                  <button class="btn btn-default" type="button" onclick="addCustomOpen()">
                   {{trans('Admin'.DS.'discount.add_hour_open')}}
                  </button>
                  <br/>
                </div>
              </div>

              <br/>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">{{trans('Admin'.DS.'discount.discount_require')}} <span class="required">*</span></label>
                <div class="col-md-9 col-xs-12">
                  <textarea type="text" id="discount_description" name="description"
                            class="form-control col-md-7 col-xs-12"  placeholder="{{trans('Admin'.DS.'discount.discount_require')}}">{!! $discount->description !!}</textarea>
                  @if ($errors->has('description'))
                    <ul class="parsley-errors-list filled">
                      <li class="parsley-required">{{ $errors->first('description') }}</li>
                    </ul>
                  @endif
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="discount_image">{{trans('Admin'.DS.'discount.discount_image')}}
                </label>
                <div class="col-md-9 col-xs-12">
                  <label class="custom-control custom-radio">
                    <input type="checkbox" onclick="showImageUpload()" name="img_from_content" id="img_from_content" class="custom-control-input" {{$discount->img_from_content?'checked':''}}>
                    <span class="custom-control-description">{{trans('Location'.DS.'user.choose_image_image_from_content')}}</span>
                    <span class="custom-control-indicator"></span>
                  </label>
                  <div  id="discount_image_upload" style="{{$discount->img_from_content?'display:none':''}}">
                    <input type="file" id="discount_image" name="discount_image[]" accept="image/*"
                           multiple
                           onchange="readURL(this,'list_discount_image')"/>
                    <br/>
                    <div class="col-md-12 row" id="">
                      @if($discount->_images)
                      @foreach($discount->_images as $image)
                        <div class="col-md-1 col-sm-1 col-xs-12" style="width: 110px; padding-left: 0px;" id="image_{{$image->id}}">
                          <a data-fancybox="image_space" href="{{$image->link}}">
                            <img style="height: 90px; width: 90px; border: 1px solid #000; margin: 2px" src="{{$image->link}}">
                          </a>
                          <span style="background-color: rgb(35, 179, 119);cursor: pointer;color: white;box-shadow: 2px 2px 7px rgb(74, 72, 72);position: absolute;left: 77px;" class="remove-img" data-typename="create_discount_image" data-field="{{$image->id}}" data-filename="{{$image->link}}">[X]</span>
                        </div>
                      @endforeach
                      @endif
                    </div>
                    <div class="col-md-12 row" id="list_discount_image">
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
                {{trans('global.inactive')}} <input type="checkbox" class="js-switch" {{ $discount->active == '1' ? 'checked' : '' }} name="active"> {{trans('global.active')}}
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-4 col-xs-12">
              {{trans('Admin'.DS.'discount.create_at')}}</label>
              <div class="col-md-8 col-xs-12">
                <input type="text" class="form-control"  value="{{date('d-m-Y H:i:s',strtotime($discount->created_at))}}" readonly="">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-4 col-xs-12">
              {{trans('Admin'.DS.'discount.create_by')}}</label>
              <div class="col-md-8 col-xs-12">
                <input type="text" class="form-control"  value="{{$discount->type_user==1?$discount->_created_by->full_name:$discount->_created_by_client->full_name}}" readonly="">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-4 col-xs-12">
              {{trans('Admin'.DS.'discount.update_at')}}</label>
              <div class="col-md-8 col-xs-12">
                <input type="text" class="form-control"  value="{{date('d-m-Y H:i:s')}}" readonly="">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-4 col-xs-12">
              {{trans('Admin'.DS.'discount.update_by')}}</label>
              <div class="col-md-8 col-xs-12">
                <input type="text" class="form-control"  value="{{Auth::guard('web')->user()->full_name}}" readonly="">
              </div>
            </div>

            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-9 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-success">{{trans('Admin'.DS.'discount.update_discount')}}</button>
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
@include('Location.user.discount-style')
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

      $('.discount_from').datetimepicker({
        format: 'DD-MM-Y',
        date: moment('{{$discount->date_from}}').format(),
        minDate: moment('{{$discount->date_from}}').millisecond(0).second(0).minute(0).hour(0).format()
      });

      $('.discount_to').datetimepicker({
        format: 'DD-MM-Y',
        date: moment('{{$discount->date_to}}').format(),
        minDate: moment('{{$discount->date_from}}').millisecond(0).second(59).minute(59).hour(23).format()
      });

      CKEDITOR.replace( 'discount_description',{
           language: '{{\App::getLocale()=='vn'?'vi':'en'}}'
      });

      $('.remove-img').on('click',function(event){
	      event.preventDefault();
	      var li_remo = this;
	      var filename = $(this).attr('data-filename')?$(this).attr('data-filename'):'';
	      var typename = $(this).attr('data-typename')?$(this).attr('data-typename'):'';
	      var id = $(this).attr('data-field')?$(this).attr('data-field'):'';

	      $.ajax({
	        type: "POST",
	        data: {id: id, _token: $("input[name='_token']").val()},
	        url: '/discount/postDeleteImage',
	        success: function (data) {
	          if (data == 'sussess') {
	            $("#image_" + id).remove();
	          }
	        }
	      });
	    });

      //$("#list_content_discount").val($("#list_content_discount option").first().prop('value')).trigger("change");
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
      var form = $('#form-create-discount')[0];
      var dataPost = new FormData(form);
      dataPost.append('_token', $("meta[name='_token']").prop('content'));
      if(editAvatar !== undefined)
      {
        dataPost.append('avatar', editAvatar);
      }
      for (var i = 0; i < create_discount_image.length; i++) {
        dataPost.append('discount_image[' + i + ']', create_discount_image[i]);
      }

      dataPost.append('description', CKEDITOR.instances['discount_description'].getData());
      dataPost.append('img_from_content', $("#img_from_content").is(":checked")?1:0);

      $.ajax({
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data: dataPost,
        url: '/discount/postCreateDiscount',
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
      $("#discount_image_upload").toggle('fast');

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

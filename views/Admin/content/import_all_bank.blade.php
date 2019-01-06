@extends('Admin..layout_admin.master_admin')

@section('content')

  <div class="col-md-12 col-sm-12 col-xs-12">
    @if(session('successInsert'))
      <div class="alert alert-success alert-dismissible fade in"
           style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        {!! session('successInsert') !!}
      </div>
    @endif
    @if(session('errorInsert'))
      <div class="alert alert-danger alert-dismissible fade in"
           style="color: #a94442;background-color: #f2dede;border-color: #ebccd1;" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        {!! session('errorInsert') !!}
      </div>
    @endif

    <div class="x_panel">
      <div class="x_title">
        <h2>Insert Content From All {{ucfirst($site)}}</h2>
        <div class="clearfix"></div>
      </div>

      <div class="x_content">
        <form class="form-horizontal form-label-left" action="{{route('insert_content', ['site' => $site])}}"
              method="post" accept-charset="utf-8" enctype="multipart/form-data">
          {{ csrf_field() }}

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              BANK <input type="checkbox" class="js-switch" name="bank_type" id="bank_type"/> ATM
            </div>
          </div>

          <div id="type_atm" style="display: none">
            <div class="form-group">
              <input type="hidden" value="" id="hidden_atm_bank" name="hidden_atm_bank"/>
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content_type">Ngân Hàng</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <select class="form-control" name="atm_bank" id="atm_bank" onchange="getTypeBankAjax(this.value,'atm_bank','atm_card');">
                  <option value="">Chọn ngân hàng</option>
                  <option value="15">ABBank - Ngân Hàng TMCP An Bình</option>
                  <option value="2">ACB - Ngân Hàng TMCP Á Châu</option>
                  <option value="4">Agribank - Ngân Hàng Nông nghiệp và Phát triển Nông Thôn Việt Nam</option>
                  <option value="39">BacABank - Ngân Hàng TMCP Bắc Á</option>
                  <option value="27">BaoVietBank - Ngân Hàng TMCP Bảo Việt</option>
                  <option value="11">BIDV - Ngân Hàng TMCP Đầu Tư và Phát triển Việt Nam</option>
                  <option value="46">CitiBank - Ngân hàng Citibank Việt Nam</option>
                  <option value="60">Commonwealth Bank - Ngân hàng Commonwealth Bank (Viêt Nam)</option>
                  <option value="36">DaiABank - Ngân hàng TMCP Đại Á</option>
                  <option value="16">DongABank - Ngân Hàng TMCP Đông Á</option>
                  <option value="12">Eximbank - Ngân Hàng TMCP Xuất Nhập Khẩu Việt Nam</option>
                  <option value="14">GP Bank - Ngân Hàng TMCP Dầu Khí Toàn Cầu</option>
                  <option value="50">HDBank - Ngân Hàng TMCP Phát Triển TPHCM</option>
                  <option value="49">Hong Leong Bank - Ngân hàng Hong Leong Việt Nam</option>
                  <option value="20">HSBC - Ngân Hàng TNHH Một Thành Viên HSBC Việt Nam</option>
                  <option value="38">IndovinaBank - Ngân hàng TNHH Indovina</option>
                  <option value="68">KienLongBank - Ngân hàng Thương Mại Cổ Phần Kiên Long</option>
                  <option value="9">LienVietPost Bank - Ngân Hàng TMCP Bưu Điện Liên Việt</option>
                  <option value="31">MaritimeBank - Ngân hàng TMCP Hàng Hải Việt Nam</option>
                  <option value="22">MB - Ngân hàng TMCP Quân đội</option>
                  <option value="40">NamABank - Ngân Hàng TMCP Nam Á</option>
                  <option value="24">NaviBank - Ngân Hàng TMCP Nam Việt</option>
                  <option value="71">Ngân hàng thương mại Cổ phần Bản Việt</option>
                  <option value="21">OCB - Ngân Hàng TMCP Phương Đông</option>
                  <option value="44">OceanBank - Ngân hàng TMCP Đại Dương</option>
                  <option value="37">PGBank - Ngân hàng TMCP Xăng Dầu Petrolimex</option>
                  <option value="43">PhuongNamBank - Ngân hàng TMCP Phương Nam</option>
                  <option value="47">Sacombank - Ngân hàng TMCP Sài Gòn Thương Tín</option>
                  <option value="30">SaiGonbank - Ngân Hàng TMCP Sài Gòn Công Thương</option>
                  <option value="45">SCB - Ngân hàng Thương Mại Cổ Phần Sài Gòn</option>
                  <option value="7">SeABank - Ngân Hàng TMCP Đông Nam Á</option>
                  <option value="26">SHB - Ngân Hàng TMCP Sài gòn – Hà nội</option>
                  <option value="57">Shinhanvina - Ngân hàng TNHH MTV Shinhan Việt Nam</option>
                  <option value="23">StandardChartered - Ngân hàng TNHH MTV Standard Chartered (Việt Nam)</option>
                  <option value="1">TechcomBank - Ngân Hàng TMCP Kỹ thương Việt Nam</option>
                  <option value="29">TienPhongBank - Ngân Hàng TMCP Tiên Phong</option>
                  <option value="35">Trust Bank - Ngân hàng TMCP Xây Dựng Việt Nam (tiền thân là Ngân hàng TMCP Đại Tín)
                  </option>
                  <option value="25">VIB - Ngân Hàng TMCP Quốc Tế Việt Nam</option>
                  <option value="52">VID Public Bank - Ngân hàng VID Public Bank</option>
                  <option value="17">VietcomBank - Ngân Hàng TMCP Ngoại Thương Việt Nam</option>
                  <option value="8">VietinBank - Ngân Hàng TMCP Công Thương Việt Nam</option>
                  <option value="32">VinaSiamBank - Ngân hàng Liên doanh Việt Thái</option>
                  <option value="18">VPbank - Ngân Hàng TMCP Việt Nam Thịnh vượng</option>
                  <option value="58">VRB - Ngân hàng Liên doanh Việt - Nga</option>
                  <option value="48">WesternBank - Ngân hàng TMCP Phương Tây</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Loại Thẻ</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <select class="form-control" name="atm_card" id="atm_card">
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Xem Phí</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <select class="form-control" name="atm_service">
                  <option value="13">Rút tiền</option>
                  <option value="15">Chuyển khoản</option>
                  <option value="16">Vấn tin số dư</option>
                  <option value="17">In sao kê GD rút gọn</option>
                  <option value="18">Phí thu hồi thẻ</option>
                  <option value="23">Ứng tiền mặt</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Thành Phô</label>
              <div class="col-md-3 col-sm-3 col-xs-12">
                <input type="hidden" value="" id="hidden_atm_province" name="hidden_atm_province"/>
                <select class="form-control" name="atm_province" id="atm_province" onchange="getTypeBankAjax(this.value,'atm_province', 'atm_provincelist');">
                  <option value="">Chọn Tỉnh/TP</option>
                  <option value="4">Hà Nội</option>
                  <option value="15">TP.HCM</option>
                  <option value="31">An Giang</option>
                  <option value="32">Bắc Ninh</option>
                  <option value="33">Bạc Liêu</option>
                  <option value="34">Bình Dương</option>
                  <option value="35">Bình Phước</option>
                  <option value="36">Bình Thuận</option>
                  <option value="104">Bình Định</option>
                  <option value="109">Bắc Cạn</option>
                  <option value="110">Bắc Giang</option>
                  <option value="111">Bến tre</option>
                  <option value="37">Cần Thơ</option>
                  <option value="102">Cà Mau</option>
                  <option value="112">Cao Bằng</option>
                  <option value="38">Đà Nẵng</option>
                  <option value="39">Đồng Nai</option>
                  <option value="40">Đồng Tháp</option>
                  <option value="105">Đăk Lăk</option>
                  <option value="113">Đăk Nông</option>
                  <option value="114">Điện Biên</option>
                  <option value="55">Gia Lai</option>
                  <option value="41">Hưng Yên</option>
                  <option value="43">Hải Phòng</option>
                  <option value="95">Hải Dương</option>
                  <option value="97">Hà Nam</option>
                  <option value="115">Hà Giang</option>
                  <option value="117">Hà Tĩnh</option>
                  <option value="120">Hậu Giang</option>
                  <option value="121">Hòa Bình</option>
                  <option value="42">Kiên Giang</option>
                  <option value="56">Khánh Hòa</option>
                  <option value="123">Kon Tum</option>
                  <option value="44">Long An</option>
                  <option value="103">Lạng Sơn</option>
                  <option value="107">Lâm Đồng</option>
                  <option value="118">Lào cai</option>
                  <option value="124">Lai Châu</option>
                  <option value="98">Nghệ An</option>
                  <option value="99">Ninh Bình</option>
                  <option value="106">Ninh Thuận</option>
                  <option value="119">Nam Định</option>
                  <option value="96">Phú Thọ</option>
                  <option value="126">Phú Yên</option>
                  <option value="46">Quảng Nam</option>
                  <option value="47">Quảng Ninh</option>
                  <option value="108">Quảng Ngãi</option>
                  <option value="127">Quảng Bình</option>
                  <option value="128">Quảng Trị</option>
                  <option value="48">Sơn La</option>
                  <option value="129">Sóc Trăng</option>
                  <option value="49">Tây Ninh</option>
                  <option value="50">Thái Nguyên</option>
                  <option value="51">Thừa Thiên Huế</option>
                  <option value="54">Tiền Giang</option>
                  <option value="100">Thái Bình</option>
                  <option value="101">Thanh Hóa</option>
                  <option value="130">Trà Vinh</option>
                  <option value="131">Tuyên Quang</option>
                  <option value="52">Vĩnh Phúc</option>
                  <option value="53">Vĩnh Long</option>
                  <option value="57">Vũng Tàu</option>
                  <option value="132">Yên Bái</option>
                </select>
              </div>
              {{--<div class="col-md-3 col-sm-3 col-xs-12">--}}
                {{--<input type="hidden" value="" id="hidden_atm_provincelist" name="hidden_atm_provincelist"/>--}}
                {{--<select class="form-control" name="atm_provincelist" id="atm_provincelist" onchange="getTypeBankAjax('','atm_provincelist', '');">--}}
                {{--</select>--}}
              {{--</div>--}}
            </div>
          </div>

          <div class="form-group" id="type_bank">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="link">Link</label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input class="form-control col-md-7 col-xs-12" type="url" name="link" autocomplete="off"/>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content_type">Category Item</label>
            <div class="col-md-3 col-sm-3 col-xs-12">
              <select class="form-control" name="category_item" id="category_item" >
                <option value="">-- {{trans('Admin'.DS.'content.cat_item')}} --</option>
                @foreach($list_category as $key => $name)
                  <option value="{{$key}}">{{$name}}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">Image</label>
            <div class="col-md-5 col-sm-5 col-xs-12">
              <div class="imgupload panel panel-default">
                <div class="panel-heading clearfix">
                  <h3 class="panel-title pull-left">Upload image</h3>
                </div>
                <div class="file-tab panel-body">
                  <div>
                    <a type="button" class="btn btn-default btn-file">
                      <span>Browse</span>
                      <input type="file" name="avatar" id="avatar" >
                    </a>
                    <button type="button" class="btn btn-default">Remove</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <input type="hidden" name="site" value="{{$site}}">

          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="moderation">{{trans('Admin'.DS.'content.moderation')}} 
            </label>
            <div class="col-md-3 col-sm-3 col-xs-12">
              <select class="form-control" name="moderation" id="moderation">
                <option value="request_publish">{{trans('Admin'.DS.'content.request_publish')}}</option>
                <option value="publish">{{trans('Admin'.DS.'content.publish')}}</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'content.date')}}</label>
            <div class="col-md-3 col-sm-3 col-xs-12">
              <div class='input-group date' style="margin-bottom: 0px" id='date_created'>
                <input type='text' class="form-control" name="date_created" />
                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
              </span>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <button type="submit" class="btn btn-success">Upload</button>
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

    function getTypeBankAjax(value,current_id, type) {
      var CSRF_TOKEN = $('input[name="_token"]').val();

      if(current_id == 'atm_bank')
      {
        var bank_name = $("#"+current_id).find("option:selected").text();
        $("#hidden_"+current_id).val(bank_name);

        $.ajax({
          type: "POST",
          data: {value: value, type:type, _token: CSRF_TOKEN},
          url: base_url + '/admin/content/ajax_post_amt',
          success: function (data) {
            $("#" + type).html(data);
          }
        })
      }
      else if(current_id == 'atm_province')
      {
        var city_name = $("#"+current_id).find("option:selected").text();
        $("#hidden_"+current_id).val(city_name);
      }
    }

    $(function () {
      $('.imgupload').imageupload({
        allowedFormats: ["jpg", "jpeg", "png", "gif"],
        previewWidth: 250,
        previewHeight: 250,
        maxFileSizeKb: 2048
      });

      $('#date_created').datetimepicker({
        format: 'YYYY-MM-DD HH:mm'
      });

      $('#bank_type').change(function() {
        if($(this).is(":checked")) {
          $('#type_atm').show();
          $('#type_bank').hide();
        }
        else{
          $('#type_atm').hide();
          $('#type_bank').show();
        }
      });
    });
  </script>
@endsection
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta name="robots" content="noindex">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/gif/png" href="{{asset($favicon)}}">
  <meta name="google-signin-client_id" content="{{$client_id_google}}">
  <meta name="google-signin-scope" content="https://www.googleapis.com/auth/analytics.readonly">
  <title>{{$title}}</title>

  <!-- Bootstrap -->

  <link href="{{asset('template/vendors/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="{{asset('template/vendors/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
  <!-- NProgress -->
  <link href="{{asset('template/vendors/nprogress/nprogress.css')}}" rel="stylesheet">
  <!-- iCheck -->
  <link href="{{asset('template/vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">
  <!-- Switch Plugin -->
  <link href="{{asset('template/vendors/switchery/dist/switchery.min.css')}}" rel="stylesheet">

  <!-- bootstrap-progressbar -->
  <link href="{{asset('template/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet">
  <!-- JQVMap -->
  <link href="{{asset('template/vendors/jqvmap/dist/jqvmap.min.css')}}" rel="stylesheet"/>
  <!-- bootstrap-daterangepicker -->
  <link href="{{asset('template/vendors/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
  <!-- nestable -->
  <link href="{{asset('template/js/nestable/jquery.nestable.css')}}" rel="stylesheet">
  <!-- toastr -->
  <link href="{{asset('template/js/toastr/build/toastr.min.css')}}" rel="stylesheet">
  <!-- boostrap-select -->
  <link href="{{asset('template/js/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
  <!-- boostrap-datetime_picker -->
  <link href="{{asset('template/js/datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
  <!-- img-upload -->
  <link href="{{asset('template/js/img-upload/dist/css/bootstrap-imageupload.min.css')}}" rel="stylesheet">

  <!-- jAlert -->
  <link href="{{asset('template/vendors/jalert/dist/jAlert.css')}}" rel="stylesheet">
  

  <!-- Custom Theme Style -->
  <link href="{{asset('template/build/css/custom.min.css')}}" rel="stylesheet">

  <!-- Custom KingMap Style -->
  <link href="{{asset('css/kingmap.css')}}" rel="stylesheet">

  <!-- jQuery -->
  <script src="{{asset('template/vendors/jquery/dist/jquery.min.js')}}"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css">

  @yield('CSS')
</head>

<body class="nav-md">
<div class="container body">
  <div class="main_container">
    <!-- left menu -->
    @include('Admin.layout_admin.left_menu')
    <!-- left menu -->

    <!-- top navigation -->
    @include('Admin.layout_admin.user_infomation')
    <!-- /top navigation -->

    <!-- page content -->
    <div class="right_col" role="main" style="min-height: 1704px;">
      @yield('content')
    </div>
    <!-- /page content -->

  <!-- footer content -->
  @include('Admin.layout_admin.footer')
  <!-- /footer content -->
  </div>
</div>

<!-- Bootstrap -->
<script src="{{asset('template/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('template/vendors/fastclick/lib/fastclick.js')}}"></script>
<!-- NProgress -->
<script src="{{asset('template/vendors/nprogress/nprogress.js')}}"></script>
<!-- Chart.js -->
<script src="{{asset('template/vendors/Chart.js/dist/Chart.min.js')}}"></script>
<!-- gauge.js -->
<script src="{{asset('template/vendors/gauge.js/dist/gauge.min.js')}}"></script>
<!-- bootstrap-progressbar -->
<script src="{{asset('template/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js')}}"></script>
<!-- iCheck -->
<script src="{{asset('template/vendors/iCheck/icheck.min.js')}}"></script>
<!-- Skycons -->
<script src="{{asset('template/vendors/skycons/skycons.js')}}"></script>
<!-- Flot -->
<script src="{{asset('template/vendors/Flot/jquery.flot.js')}}"></script>
<script src="{{asset('template/vendors/Flot/jquery.flot.pie.js')}}"></script>
<script src="{{asset('template/vendors/Flot/jquery.flot.time.js')}}"></script>
<script src="{{asset('template/vendors/Flot/jquery.flot.stack.js')}}"></script>
<script src="{{asset('template/vendors/Flot/jquery.flot.resize.js')}}"></script>
<!-- Flot plugins -->
<script src="{{asset('template/vendors/flot.orderbars/js/jquery.flot.orderBars.js')}}"></script>
<script src="{{asset('template/vendors/flot-spline/js/jquery.flot.spline.min.js')}}"></script>
<script src="{{asset('template/vendors/flot.curvedlines/curvedLines.js')}}"></script>
<!-- Switch Plugin -->
<script src="{{asset('template/vendors/switchery/dist/switchery.min.js')}}"></script>

<!-- DateJS -->
<script src="{{asset('template/vendors/DateJS/build/date.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('template/vendors/jqvmap/dist/jquery.vmap.js')}}"></script>
<script src="{{asset('template/vendors/jqvmap/dist/maps/jquery.vmap.world.js')}}"></script>
<script src="{{asset('template/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js')}}"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{asset('template/vendors/moment/min/moment.min.js')}}"></script>
<script src="{{asset('template/vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<!-- nestable -->
<script src="{{asset('template/js/nestable/jquery.nestable.js')}}"></script>
<!-- toastr -->
<script src="{{asset('template/js/toastr/build/toastr.min.js')}}"></script>
<!-- boostrap-select -->
<script src="{{asset('template/js/bootstrap-select/dist/js/bootstrap-select.min.js')}}"></script>
<!-- boostrap-datetime_picker -->
<script src="{{asset('template/js/datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
<!-- img-upload -->
<script src="{{asset('template/js/img-upload/dist/js/bootstrap-imageupload.js')}}"></script>
<!-- Parsley -->
<script src="{{asset('template/vendors/parsleyjs/dist/parsley.min.js')}}"></script>
<!-- jQuery Tags Input -->
<script src="{{asset('template/vendors/jquery.tagsinput/src/jquery.tagsinput.js')}}"></script>
<!-- jQuery autocomplete -->
<script src="{{asset('template/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.js"></script>
<!-- jAlert -->
<!-- <script src="{{asset('template/vendors/jalert/dist/jAlert.min.js')}}"></script>
<script src="{{asset('template/vendors/jalert/dist/jAlert-functions.min.js')}}"></script> -->

<!-- Custom Theme Scripts -->
<script src="{{asset('template/build/js/custom.js')}}"></script>
<script src="{{asset('template/function.js')}}"></script>
<script type="text/javascript">
    $(function(){
      $("input[type='phone']").on("keypress",function(e){
        return (e.charCode >= 48 && e.charCode <= 57) || e.charCode == 43 || event.charCode == 0 ;
      })

      $("input[type='number'], input[type='percent']").on("keypress",function(e){
        return (e.charCode >= 48 && e.charCode <= 57) || e.charCode == 43 || event.charCode == 46 || event.charCode == 0 ;
      })
      $("input[type='percent']").on('change',function(){
        var text = parseFloat($(this).val().toLowerCase());
        if(text>100){
          $(this).val(100);
        }
      });
      $("input[name=alias]").on('keypress',function(e){
        var theEvent = e || window.event;
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode( key );
        var regex = /[0-9A-Za-z\-]/;
        if( !regex.test(key) ) {
          theEvent.returnValue = false;
          if(theEvent.preventDefault) theEvent.preventDefault();
        }
        var text = $(this).val().toLowerCase()
          .replace(/_+/g,'-')
          .replace(/\s+/g, '-')           // Replace spaces with -
          .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
          .replace(/\-\-+/g, '-')         // Replace multiple - with single -
        $(this).val(text.replace(/_+/g,'-'));
      });
      $("input[name=machine_name]").on('keypress',function(e){
        var theEvent = e || window.event;
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode( key );
        var regex = /[0-9A-Za-z\_]/;
        if( !regex.test(key) ) {
          theEvent.returnValue = false;
          if(theEvent.preventDefault) theEvent.preventDefault();
        }
        var text = $(this).val().toLowerCase()
          .replace(/-+/g,'_')
          .replace(/\s+/g, '-')           // Replace spaces with -
          .replace(/[^\w\_]+/g, '')       // Remove all non-word chars
          .replace(/\_\_+/g, '-')         // Replace multiple - with single -
        $(this).val(text.replace(/-+/g,'_'));
      });
      $("input[name=alias]").on('change',function(){
        var text = $(this).val().toLowerCase()
          .replace(/_+/g,'-')
          .replace(/\s+/g, '-')           // Replace spaces with -
          .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
          .replace(/\-\-+/g, '-')         // Replace multiple - with single -
          .replace(/^-+/, '')             // Trim - from start of text
          .replace(/-+$/, '');            // Trim - from end of text
        $(this).val(text.replace(/_+/g,'-'));
      });

      $("input[name=machine_name]").on('change',function(){
        var text = $(this).val().toLowerCase()
          .replace(/-+/g,'_')
          .replace(/\s+/g, '-')           // Replace spaces with -
          .replace(/[^\w\_]+/g, '')       // Remove all non-word chars
          .replace(/\_\_+/g, '-')         // Replace multiple - with single -
          .replace(/^_+/, '')             // Trim _ from start of text
          .replace(/_+$/, '');            // Trim _ from end of text
        $(this).val(text.replace(/-+/g,'_'));
      });
    })
    function changeLanguage(obj) {
      var lang = $(obj).val();
      $.ajax({
        url : '/language/'+lang,
        type: 'GET',
        success: function(data) {
          window.location.reload();
        }
      })
    }

    function change_pagination(value,path)
    {
      $.ajax({
        type: "POST",
        data: {pagination: value, path : path, _token: $('input[name="_token"]').val()},
        url: {!! json_encode(url('/')) !!} + '/admin/session_pagination',
        success: function (data) {
          if(data == true)
          {
            location.reload();
          }
        }
      })
    }


</script>
@yield('JS')
</body>
</html>

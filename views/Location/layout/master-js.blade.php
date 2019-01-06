<script type="text/javascript">
  $(function(){
    var refresh_time = 1000*60 // 60s
    setInterval(refresh_token,refresh_time);

    $("input[type='number'], input[type='percent']").on("keypress",function(e){
      return (e.charCode >= 48 && e.charCode <= 57) || e.charCode == 43 || event.charCode == 46 || event.charCode == 0 ;
    })
    $("input[type='percent']").on('change',function(){
      var text = parseFloat($(this).val().toLowerCase());
      if(text>100){
        $(this).val(100);
      }
    });
    $("input[type='number']").on('change',function(){
      var max = $(this).attr('max');
      var min = $(this).attr('min');
      var length = max.toString().length;
      var text = parseFloat($(this).val().toLowerCase());
      if(text>max){
        $(this).val(this.value.slice(0,length));
      }

      if(text<min){
        $(this).val(min);
      }
    });

    $("input[type='number']").on('blur',function(){
      var max = $(this).attr('max');
      var min = $(this).attr('min');
        var length = max.toString().length;
      var text = parseFloat($(this).val().toLowerCase());
      if(text>max){
        $(this).val(this.value.slice(0,length));
      }

      if(text<min){
        $(this).val(min);
      }
    });
  })
  var search_header = "{{trans('global.view_all')}}";
  var no_content = "{{trans('global.no_content')}}";
  function changeLanguage(obj) {
    var lang = $(obj).val();
    $.ajax({
      url: '/setlanguage/' + lang,
      type: 'GET',
      success: function (data) {
        window.location.reload();
      }
    })
  }
  function sharePopup(url) {
    var isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
    if(isFirefox){
      newwindow = window.open(url, 'new', 'height=800','width=600');
      if (window.focus) {
        newwindow.focus()
      }
    }else{
      newwindow = window.open(url, 'new', 'height=800','width=600');
      if (window.focus) {
        newwindow.focus()
      }
    }
    return false;
  }
  toastr.options = {
    "positionClass": "toast-bottom-right"
  }

  function getFromCreateLocation(id_user) {
    if (id_user === undefined) {
      $('#modal-signin').modal('show');
    }
    else {
      $('#modal-new-location').modal('show');
    }
  }
</script>

<script>
    //new mlPushMenu( document.getElementById( 'mp-menu' ), document.getElementById( 'trigger' ) );
</script>

<script>
  // loadAjax({
  //   url : 'http://develop.kingmap.vn/getLocation',
  //   type : 'GET'
  // });
  
  function loadAjax(option={
    url: '',
    type: 'GET',
    data: {},
    cache: true,
    contentType: true,
    processData: true,
  }){
    $("#progress .progress-bar").css('width',0+"%");
    $("#progress .progress-bar").text(0+"%");
    $.ajax({
      url : option.url,
      type : option.type,
      data : option.data,
      cache: option.cache,
      contentType: option.contentType,
      processData: option.processData,
      beforeSend:function(){
        if(option.progress){
          $("#progress").show();
        }else{
          $("#loading").show();
        }
      },
      xhr:function(){
        var xhr = new window.XMLHttpRequest();
        if(option.progress){
          xhr.upload.addEventListener("progress", function(evt){
            if (evt.lengthComputable) {
              var percentComplete = (evt.loaded / evt.total)*100;
              percentComplete = Math.round(percentComplete);
              if(percentComplete<98){
                $("#progress .progress-bar").css('width',percentComplete+"%");
                $("#progress .progress-bar").text(percentComplete+"%");
              }
            }
          }, false);
          xhr.addEventListener("progress", function(evt){
            if (evt.lengthComputable) {
              var percentComplete = (evt.loaded / evt.total)*100;
              percentComplete = Math.round(percentComplete);
              if(percentComplete<98){
                $("#progress .progress-bar").css('width',percentComplete+"%");
                $("#progress .progress-bar").text(percentComplete+"%");
              }
            }
          }, false);
        }
        return xhr;
      },
      success:function(response){
        $("#loading").hide();
        $("#progress").hide();
        if(option.success){
          option.success(response);
        }
      },
      error:function(response){
        $("#loading").hide();
        $("#progress").hide();
        if(option.error){
          option.error(response);
        }else{
          $("#modal-error").modal();
        }
      }
    })
  }

  function refresh_token(){
    $.get('/refresh-token').done(function(data){
      $("[name=_token]").attr('content',data);
      $("input[name=_token]").val(data);
      console.log("refresh token");
    });
  }
</script>
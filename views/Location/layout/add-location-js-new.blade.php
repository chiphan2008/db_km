<link href="{{asset('template/js/datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
<script src="{{asset('template/vendors/moment/min/moment.min.js')}}"></script>
<script src="{{asset('template/js/datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>

<script type="text/javascript">
  var avatar_create;
  var imageSpace_create = [];
  var imageMenu_create = [];

  var editAvatar;
  var editimageSpace = [];
  var editimageMenu = [];
  var captcha_code = "";
  var create_discount_image = [];

  var timerSearch;

  function choose_category_again(){
    $("#step1").addClass('active');
    $("#step2").removeClass('active');
  }

  function createCaptcha(){
    if($("#canvas_captcha").length){
      var canvas = document.getElementById('canvas_captcha');
      var context = canvas.getContext('2d');
      context.fillStyle = '#FFF';
      context.fillRect(0,0,400,150);
      var possible = "abcdefghijklmnopqrstuvwxyz";
      var captcha_code_create = "";
      for( var i=0; i < 5; i++ )
        captcha_code_create += possible.charAt(Math.floor(Math.random() * possible.length));
      captcha_code = captcha_code_create;
      captcha_view = captcha_code.toUpperCase();
      arr_color = ["blue","green","red","brown","magenta","orange","peru","crimson","yellowgreen","cyan","black"];
      arr_fontsize = [32];
      arr_font = ["Times New Roman","Tahoma","Courier","Helvetica","Trebuchet MS","Arial Black","Georgia","Verdana"];
      arr_style=["","bold","italic","bold italic"];
      for(i=0; i<captcha_code.length;i++){
        var fillStyle = arr_color[Math.floor(Math.random() * arr_color.length)];
        context.fillStyle = fillStyle;
        context.font = arr_style[Math.floor(Math.random() * arr_style.length)]+" "+arr_fontsize[Math.floor(Math.random() * arr_fontsize.length)]+'px '+arr_font[Math.floor(Math.random() * arr_font.length)];
        context.fillText(captcha_view[i],40*i,35+(Math.random()*20));
      }
    }
  }


  $(function() {

    createCaptcha();
    


    $('#file-upload-avata').on('change', function(event) {
        event.preventDefault();
        if($(this).val()){
          $('.box-img-upload').removeClass('avata-border');
        }
    });
    $('#modal-new-location input[id=name]').on('keyup',function (e) {
      var keyword = $(this).val();
      if (e.which == 13 || e.keyCode == 13) {
        if(keyword.length > 2)
          searchCreateContent();
      }else{
        clearTimeout(timerSearch);
        if(keyword.length > 2){
          timerSearch = setTimeout(function() {
              searchCreateContent();
          }, 1000);
        }
      }
    });

    $("#create_location_fe").on("click", function () {
      
      your_cap = $('#captcha_code').val().toLowerCase();
      my_cap = captcha_code.toLowerCase();

      var form = $('#form-creat-location')[0]; // You need to use standard javascript object here
      var data = new FormData(form);
      data.set('_token', $("meta[name='_token']").prop('content'));

      if(avatar_create){
        data.set('avatar', avatar_create);
        $("#feedback_avatar").hide();
      }else{
        $(".tab-upload-image-nav .nav-link").removeClass('active');
        $("#avatar_nav").addClass("active");
        $(".tab-upload-image .tab-pane").removeClass('active');
        $(".tab-pane#avatar").addClass("active");
        $("#feedback_avatar").show().html("{{trans('Location'.DS.'layout.avatar_required')}}");

        $("#modal-new-location").scrollTo('#feedback_avatar');

        return false;
      }
      if(your_cap != my_cap){
          if(your_cap != '') {
              $(".error_captcha").text("{{trans('valid.captcha_wrong')}}");
          }else{
              $(".error_captcha").text("{{trans('valid.captcha_required')}}");
          }
        createCaptcha();
        $("#modal-new-location").scrollTo('#error_captcha');
        $('#captcha_code').val("").focus();
        return false;
      }
      
      for (var i = 0; i < imageSpace_create.length; i++) {
        data.set('image_space[' + i + ']', imageSpace_create[i]);
        if($("#des_image_khong_gian_create_"+i).length){
          data.set('des_space['+i+']',$("#des_image_khong_gian_create_"+i).val());
        }else{
          data.set('des_space['+i+']','');
        }

        if($("#title_image_khong_gian_create_"+i).length){
          data.set('title_space['+i+']',$("#title_image_khong_gian_create_"+i).val());
        }else{
          data.set('title_space['+i+']','');
        }
      }
      for (var i = 0; i < imageMenu_create.length; i++) {
        data.set('image_menu[' + i + ']', imageMenu_create[i]);
        if($("#des_image_menu_create_"+i).length){
          data.set('des_menu['+i+']',$("#des_image_menu_create_"+i).val());
        }else{
          data.set('des_menu['+i+']','');
        }
        if($("#title_image_menu_create_"+i).length){
          data.set('title_menu['+i+']',$("#title_image_menu_create_"+i).val());
        }else{
          data.set('title_menu['+i+']','');
        }
      }

      loadAjax({
        type: "POST",
        progress: true,
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        url: '/createLocationFrontend/postCreateLocation',
        beforeSend: function(xhr){
          xhr.setRequestHeader("Content-Type", "multipart/form-data");
          $("#create_location_fe").attr("disabled", true);
        },
        success: function (data) {
          $("#create_location_fe").attr("disabled", false);
          if(data.status == 'success')
          {
            $("#content_id_create").val(data.id);
            //location.reload();
            // toastr.success("Tạo địa điểm thành công");
            $("#modal-new-location").modal('hide');
            $("#form-creat-location").get(0).reset();
            $("#modal-create-success").modal();
            $("#step1").addClass('active');
            $("#step2").removeClass('active');
            $("#step3").removeClass('active');
            $(".upload-avata").html('<div class="box-img-upload-content"><i class="icon-new-white"></i><p>{{trans('Location'.DS.'layout.choose_image')}}</p></div>');
            $(".upload-img-post ul li").not(":first-child").remove();
            $("#list_group_product").html('');
            $(".tagsinput .tag").remove();
            $(".tagsinput input").val('');
            sessionStorage.category = [];
            sessionStorage.service = [];
            avatar_create = null;
            imageSpace_create = [];
            imageMenu_create = [];

            resetFormCreate();

            {{--window.location = {!! json_encode(url('edit/location/'.Auth::guard('web_client')->user()->id)) !!};--}}
          }
        },
        complete: function(data){
          createCaptcha();
        }
      })
    });

    $("#update_location_fe").on("click", function () {
        $("#update_location_fe").attr("disabled", true);
        var button = $("#update_location_fe");
        var form = $('#form-creat-location-edit')[0];
        var data = new FormData(form);
        data.set('_token', $("meta[name='_token']").prop('content'));
        if(editAvatar !== undefined)
        {
            data.set('avatar', editAvatar);
        }


        for (var i = 0; i < editimageSpace.length; i++) {
          data.set('image_space[' + i + ']', editimageSpace[i]);
          if($("#des_edit_image_khong_gian_"+i).length){
            data.set('des_space['+i+']',$("#des_edit_image_khong_gian_"+i).val());
          }else{
            data.set('des_space['+i+']','');
          }

          if($("#title_edit_image_khong_gian_"+i).length){
            data.set('title_space['+i+']',$("#title_edit_image_khong_gian_"+i).val());
          }else{
            data.set('title_space['+i+']','');
          }
        }
        for (var i = 0; i < editimageMenu.length; i++) {
          data.set('image_menu[' + i + ']', editimageMenu[i]);
          if($("#des_edit_image_menu_"+i).length){
            data.set('des_menu['+i+']',$("#des_edit_image_menu_"+i).val());
          }else{
            data.set('des_menu['+i+']','');
          }
          if($("#title_edit_image_menu_"+i).length){
            data.set('title_menu['+i+']',$("#title_edit_image_menu_"+i).val());
          }else{
            data.set('title_menu['+i+']','');
          }
        }

        loadAjax({
            type: "POST",
            cache: false,
            progress: true,
            contentType: false,
            processData: false,
            data: data,
            url: '/editLocationFrontend/postEditLocation',
            beforeSend:function(xhr){
                xhr.setRequestHeader("Content-Type", "multipart/form-data");
            },
            success: function (data) {
                $("#update_location_fe").attr("disabled", false);
                if(data.status == 'success')
                {
                    //location.reload();
                    //button.prev().trigger('click');
                    $("#modal-update-success").modal();
                    $("#update_location_fe").attr("disabled", false);
                    {{--@if(Auth::guard('web_client')->user())--}}
                        {{--window.location = {!! json_encode(url('user/'.Auth::guard('web_client')->user()->id.'/management-location/')) !!};--}}
                    {{--@endif--}}
                }else{
                    button.prev().trigger('click');
                    toastr.warning(data);
                    $("#update_location_fe").attr("disabled", false);
                }
            }
        })
    });


    $("#preview_location_fe").on("click",function(){


    	var form = $('#form-creat-location')[0];
    	var data = new FormData(form);

      data.set('_token', $("meta[name='_token']").prop('content'));
      data.set('avatar', avatar_create);
      for (var i = 0; i < imageSpace_create.length; i++) {
        data.set('image_space[' + i + ']', imageSpace_create[i]);
      }
      for (var i = 0; i < imageMenu_create.length; i++) {
        data.set('image_menu[' + i + ']', imageMenu_create[i]);
      }
			loadAjax({
        progress: true,
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				data: data,
				url: '/createLocationFrontend/previewLocation',
        beforeSend:function(xhr){
          xhr.setRequestHeader("Content-Type", "multipart/form-data");
        },
				success: function (data) {
					if(data.type){
						var newWin = window.open('/createLocationFrontend/previewLocation',"_blank");
            if(!newWin || newWin.closed || typeof newWin.closed=='undefined') 
            { 
              alert("{{trans('valid.enable_popup')}}")
            }
					}
				}
			})
    })
  });

  $(function() {
    //upload avata
    $('.box-upload-avata').on('click', '.upload-avata', function(event) {
      event.preventDefault();
      $(this).closest('.box-upload-avata').find('input[type="file"]').click();
    });
    $('.box-upload-avata-edit').on('click', '.upload-avata-edit', function(event) {
      event.preventDefault();
      $(this).closest('.box-upload-avata-edit').find('input[type="file"]').click();
    });
    //upload avata
    var uploadDingAvata = 0;
    var ulAvata = $('.box-upload-avata');
    $('.box-upload-avata').fileupload({
      add: function(e, data) {
        var goUpload = true;
        var uploadFileAvata = data.files[0];
        var ext = uploadFileAvata.name.split('.').pop().toLowerCase();
        if(!ext.match(/(jpg|jpeg|png|gif|bmp)$/i)){
          goUpload = false;
        }
        var avatar_type_id = $(this).attr("id");
        if(avatar_type_id == 'avatar_create' && goUpload)
        {
          avatar_create = uploadFileAvata;
        }
        if (goUpload) {
          uploadDingAvata++;
          var tplavata = $('<div class="box-img-upload-success"><img /></div>');
          var currentLi = ulAvata.find('.upload-avata').html(tplavata);
          data.context = currentLi;
          // data.context = tpl.appendTo(ul);
          // draw preview image
          var reader = new FileReader();
          reader.onloadend = function(e) {
            var tempImg = new Image();
            tempImg.src = reader.result;
            tempImg.onload = function() {
              var width = 200;
              var height = 150;
              var canvas = document.createElement('canvas');
              canvas.width = width;
              canvas.height = height;
              var ctx = canvas.getContext("2d");
              ctx.drawImage(this, 0, 0, width, height);
              var dataURL = canvas.toDataURL("image/jpeg");
              data.context.find('img').attr('src', dataURL);
            };
          };
          reader.readAsDataURL(uploadFileAvata);
        }
      },
    });

    //upload avataedit
    var uploadDingAvataEdit = 0;
    var ulAvataEdit = $('.box-upload-avata-edit');
    $('.box-upload-avata-edit').fileupload({
      add: function(e, data) {
        var goUpload = true;
        var uploadFileAvataEdit = data.files[0];
        var ext = uploadFileAvataEdit.name.split('.').pop().toLowerCase();
        if(!ext.match(/(jpg|jpeg|png|gif|bmp)$/i)){
          goUpload = false;
        }
        
        if (goUpload) {
          editAvatar = uploadFileAvataEdit;

          uploadDingAvataEdit++;
          var tplavataedit = $('<div class="box-img-upload-success"><img /></div>');
          var currentLi = ulAvataEdit.find('.upload-avata-edit').html(tplavataedit);
          data.context = currentLi;
          // data.context = tpl.appendTo(ul);
          // draw preview image
          var reader = new FileReader();
          reader.onloadend = function(e) {
            var tempImg = new Image();
            tempImg.src = reader.result;
            tempImg.onload = function() {
              var width = 200;
              var height = 150;
              var canvas = document.createElement('canvas');
              canvas.width = width;
              canvas.height = height;
              var ctx = canvas.getContext("2d");
              ctx.drawImage(this, 0, 0, width, height);
              var dataURL = canvas.toDataURL("image/jpeg");
              data.context.find('img').attr('src', dataURL);
            };
          };
          reader.readAsDataURL(uploadFileAvataEdit);
        }
      },
    });
  });

  $(function() {
    if($(window).width()>=768){
      $('.upload-placeholder').mCustomScrollbar({
        theme: "dark",
        contentTouchScroll: true,
        mouseWheel:{ scrollAmount: 160 }
      });
    }else{
      $('.upload-placeholder').css("height","auto");
    }
    
    $('.upload-img-post ').on('click', '.upload-begin', function(event) {
      event.preventDefault();
      $(this).closest('.upload-img-post').find('input[type="file"]').click();
    });


    //upload file
    var uploadDing = 0;
    var ul;
    $('.upload-img-post').fileupload({
      add: function(e, data) {
        var _that = $(e.target);
        ul = _that.find('ul');
        var goUpload = true;
        var uploadFile = data.files[0];
        var index_input = 0;
        var ext = uploadFile.name.split('.').pop().toLowerCase();
        if(!ext.match(/(jpg|jpeg|png|gif|bmp)$/i)){
          goUpload = false;
        }
        var type_image_id = $(this).attr("id");
        if(type_image_id == 'image_khong_gian_create')
        {
          if(imageSpace_create.length>=50){
            alert("{{trans('valid.max_file_upload')}}");
            goUpload = false;
          }else{
            if(goUpload){
              index_input = imageSpace_create.length;
              imageSpace_create.push(data.files[0]);
            }
          }
        }
        if(type_image_id == 'image_menu_create')
        {
          if(imageMenu_create.length>=50){
            alert("{{trans('valid.max_file_upload')}}");
            goUpload = false;
          }else{
            if(goUpload){
              index_input = imageMenu_create.length;
              imageMenu_create.push(data.files[0]);
            }
          }         
        }
        if(type_image_id == 'edit_image_khong_gian')
        {
          if(editimageSpace.length>=50){
            alert("{{trans('valid.max_file_upload')}}");
            goUpload = false;
          }else{
            if(goUpload){
              index_input = editimageSpace.length;
              editimageSpace.push(data.files[0]);
            }
          }
        }
        if(type_image_id == 'edit_image_menu')
        {
          if(editimageMenu.length>=50){
            alert("{{trans('valid.max_file_upload')}}");
            goUpload = false;
          }else{
            if(goUpload){
              index_input = editimageMenu.length;
              editimageMenu.push(data.files[0]);
            }
          }
        }
        if(type_image_id == 'create_discount_image'){
          if(create_discount_image.length<1){
            if(goUpload){
              create_discount_image.push(data.files[0]);
            }
          }else{
            goUpload = false;
          }
        }
        if (uploadDing >= 1) {
          $('.upload-img-post').removeClass('upload-placeholder');
        }
        if (goUpload) {
          uploadDing++;
          // var tpl = $('<div class="box-img-upload box-img-upload-success"><a class="remove-img" data-typename="'+type_image_id+'" data-filename="'+data.files[0].name+'" href="" title=""><i class="icon-cancel"></i></a><img /></div>');
          var tpl = $('<div class="box-img-upload box-img-upload-success"><a class="remove-img" data-typename="'+type_image_id+'" id="'+type_image_id+'_'+index_input+'" data-filename="'+data.files[0].name+'"><i class="icon-cancel"></i></a><img /><div class="box-img-upload-descript"><input type="text" maxLength="128" class="form-control title_'+type_image_id+'" id="title_'+type_image_id+'_'+index_input+'" placeholder="{{trans('Location'.DS.'layout.title_input')}}"><input type="text" maxLength="128" class="form-control des_'+type_image_id+'" id="des_'+type_image_id+'_'+index_input+'" placeholder="{{trans('Location'.DS.'layout.description_input')}}"></div></div>');
          var currentLi = ul.find('.upload-begin').parent().html(tpl);
          // currentLi.removeClass('upload-begin upload-image-disabled').addClass('');
          currentLi.before('<li class="col-md-4 col-6"><div class="box-img-upload upload-begin upload-image-disabled "><div class="box-img-upload-content"><i class="icon-new-white"></i><p>{{trans('Location'.DS.'layout.choose_image')}}</p></div></div></li>');
          data.context = currentLi;
          // data.context = tpl.appendTo(ul);
          // draw preview image
          var reader = new FileReader();
          reader.onloadend = function(e) {
            var tempImg = new Image();
            tempImg.src = reader.result;
            tempImg.onload = function() {
              var width = 270;
              var height = 202;
              var canvas = document.createElement('canvas');
              canvas.width = width;
              canvas.height = height;
              var ctx = canvas.getContext("2d");
              ctx.drawImage(this, 0, 0, width, height);
              var dataURL = canvas.toDataURL("image/jpeg");
              data.context.find('img').attr('src', dataURL);
            };
          };
          reader.readAsDataURL(uploadFile);
        }else{
          return false;
        }
      },
      progress: function(e, data) {
          // Calculate the completion percentage of the upload
          var progress = parseInt(data.loaded / data.total * 100, 10);
          data.context.find('.progress-bar').css('width', progress + '%');
          if (progress == 100) {
              data.context.removeClass('upload-processing').addClass('success');
          }
      },
      fail: function(e, data) {
          data.context.addClass('error');
      }
    });
    // $("#khong-gian-edit").on('click','.space-remove',function(e){
    //     e.preventDefault();
    //     if (confirm('{{trans('Location'.DS.'layout.confirm_delete')}}')) {
    //         $("#khong-gian-edit").find("a.remove-img").trigger('click');
    //     }
    // });
    // $("#menu-edit").on('click','.space-remove',function(e){
    //     e.preventDefault();
    //     if (confirm('{{trans('Location'.DS.'layout.confirm_delete')}}')) {
    //         $("#menu-edit").find("a.remove-img").trigger('click');
    //     }
    // });
    var ar = 123;
    $('.upload-img-post').on('click','a.remove-img',function(event){
      event.preventDefault();
      var li_remo = this;
      var filename = $(this).attr('data-filename')?$(this).attr('data-filename'):'';
      var typename = $(this).attr('data-typename')?$(this).attr('data-typename'):'';
      if(typename == 'image_khong_gian_create')
      {
        for(var i=0; i<imageSpace_create.length; i++){
          if(filename == imageSpace_create[i].name){
            imageSpace_create.splice(i,1);
          }
        }
        $(this).closest('li').remove();
      }

      if(typename == 'image_menu_create')
      {
        for(var i=0; i<imageMenu_create.length; i++){
          if(filename == imageMenu_create[i].name){
            imageMenu_create.splice(i,1);
          }
        }
        $(this).closest('li').remove();
      }

      if(typename == 'create_discount_image')
      {
        for(var i=0; i<create_discount_image.length; i++){
          if(filename == create_discount_image[i].name){
            create_discount_image.splice(i,1);
          }
        }
        $(this).closest('li').remove();
      }

     

      if(typename == 'edit_image_khong_gian'){
        if (confirm('{{trans('Location'.DS.'layout.confirm_delete')}}')) {
          var field = $(this).attr('data-field')?$(this).attr('data-field'):'';
          if(field)
          {
            $.ajax({
              type: "POST",
              data: {id: field, type: typename, _token: $("meta[name='_token']").prop('content')},
              url: '/editLocationFrontend/deleteImage',
              success: function (data) {
                if (data == 'sussess') {
                  $(li_remo).closest('li').remove();
                }
              }
            })
          }
          else{
            for(var i=0; i<editimageSpace.length; i++){
              if(filename == editimageSpace[i].name){
                editimageSpace.splice(i,1);
              }
            }
            $(li_remo).closest('li').remove();
          }

          var field_menu = $(this).attr('data-field')?$(this).attr('data-field'):'';
          if(field_menu)
          {
            $.ajax({
              type: "POST",
              data: {id: field_menu, type: typename, _token: $("meta[name='_token']").prop('content')},
              url: '/editLocationFrontend/deleteImage',
              success: function (data) {
                if (data == 'sussess') {
                  $(li_remo).closest('li').remove();
                }
              }
            })
          }
          else{
            for(var i=0; i<editimageMenu.length; i++){
              if(filename == editimageMenu[i].name){
                editimageMenu.splice(i,1);
              }
            }
            $(li_remo).closest('li').remove();
          }
        }
      }

      if(typename == 'edit_image_menu'){
        if (confirm('{{trans('Location'.DS.'layout.confirm_delete')}}')) {
          var field = $(this).attr('data-field')?$(this).attr('data-field'):'';
          if(field)
          {
            $.ajax({
              type: "POST",
              data: {id: field, type: typename, _token: $("meta[name='_token']").prop('content')},
              url: '/editLocationFrontend/deleteImage',
              success: function (data) {
                if (data == 'sussess') {
                  $(li_remo).closest('li').remove();
                }
              }
            })
          }
          else{
            for(var i=0; i<editimageMenu.length; i++){
              if(filename == editimageMenu[i].name){
                editimageMenu.splice(i,1);
              }
            }
            $(li_remo).closest('li').remove();
          }
        }
      }

    });
  });



  $(function() {
    $('input[name="id_category"]').on('change',function(){
      // console.log("i'm here 1")
      if($('input[name="id_category"]:checked').val()){
        // console.log("i'm here 2")
        var _id_category = $('input[name="id_category"]:checked').val();
        if (_id_category) {
          // console.log("i'm here 3")
          var index = $(this).closest('.tab-pane').index();
          $(this).closest('.tab-pane').removeClass('active');
          $(this).closest('.cread-location').find('.tab-pane').eq(index + 1).addClass('active');

          loadAjax({
            type: "POST",
            data: {id_category: _id_category, _token: $("meta[name='_token']").prop('content')},
            url: '/createLocationFrontend/StepOne',
            success: function (data) {

              var obj = jQuery.parseJSON(data);
              $("#label_name_category").text(obj.category_name);
              $("#list_category_item").html(obj.list_category_item);
              if(obj.list_category_item != ''){
                $("#category_item_require").show();
                $("#fieldset_feedback_category_item").show();
              }else{
                $("#category_item_require").hide();
                $("#fieldset_feedback_category_item").hide();
              }
              if(obj.list_service)
              {
                $("#list_service").show();
                $("#list_service ul").html(obj.list_service);
              }
              else{
                $("#list_service").hide();
              }

              $('.price_location').show();
              $('#label_menu').show();
              if (obj.list_group) {
                $("#list_group").html(obj.list_group);
                $('.price_location').show();
              }
              else {
                if (_id_category == 5) {
                  $("#list_group").html(obj.bank_type);
                  $('.price_location').hide();
                  $('#label_menu').hide();
                }
                else {
                  $("#list_group").html('');
                }
              }

              reloadCatService();
            }
          })
        }
      }
    })
    // new location
    $('.btn-step .next-step').click(function() {

      // if ($(this).attr("id") == 'button_step2') {
      //   var _id_category = $('input[name="id_category"]:checked').val();
      //   if (_id_category) {
      //     var index = $(this).closest('.tab-pane').index();
      //     $(this).closest('.tab-pane').removeClass('active');
      //     $(this).closest('.cread-location').find('.tab-pane').eq(index + 1).addClass('active');
      //     $(this).closest('.cread-location').find('.nav-item').eq(index).removeClass('highlight').addClass('complete');
      //     $(this).closest('.cread-location').find('.nav-item').eq(index + 1).removeClass('disabled').addClass('highlight');

      //     loadAjax({
      //       type: "POST",
      //       data: {id_category: _id_category, _token: $("meta[name='_token']").prop('content')},
      //       url: '/createLocationFrontend/StepOne',
      //       success: function (data) {

      //         var obj = jQuery.parseJSON(data);
      //         $("#label_name_category").text(obj.category_name);
      //         $("#list_category_item").html(obj.list_category_item);
      //         if(obj.list_category_item != ''){
      //           $("#category_item_require").show();
      //         }else{
      //           $("#category_item_require").hide();
      //         }
      //         if(obj.list_service)
      //         {
      //           $("#list_service").show();
      //           $("#list_service ul").html(obj.list_service);
      //         }
      //         else{
      //           $("#list_service").hide();
      //         }

      //         $('.price_location').show();
      //         $('#label_menu').show();
      //         if (obj.list_group) {
      //           $("#list_group").html(obj.list_group);
      //           $('.price_location').show();
      //         }
      //         else {
      //           if (_id_category == 5) {
      //             $("#list_group").html(obj.bank_type);
      //             $('.price_location').hide();
      //             $('#label_menu').hide();
      //           }
      //           else {
      //             $("#list_group").html('');
      //           }
      //         }

      //         reloadCatService();
      //       }
      //     })
      //   }
      //   else {
      //     $('#err_id_category').show();
      //   }
      // }

      if ($(this).attr("id") == 'button_step3') {
        $("#form-creat-location fieldset").removeClass('has-danger');
        var current_button = this;
        var index = $(this).closest('.tab-pane').index();
        $('.form-control-feedback').hide();

        var form = $('#form-creat-location')[0];
        var data = new FormData(form);
        data.set('_token', $("meta[name='_token']").prop('content'));
        if(avatar_create !== undefined)
        {
          data.set('avatar', avatar_create);
        }
        var prod_error = false;
        $('.group_product input').each(function(key,elem){
          if($(elem).val() == ''){
            $error = true;
            var name = $(elem).attr("name");

            if(name.indexOf('image') > 0){
              $('#feedback_product').show().html('{{trans("valid.product_image")}}');
            }
            if(name.indexOf('price') > 0){
              $('#feedback_product').show().html('{{trans("valid.product_price")}}');
            }
            if(name.indexOf('name') > 0){
              $('#feedback_product').show().html('{{trans("valid.product_name")}}');
            }
            if(name.indexOf('group_name') > 0){
              $('#feedback_product').show().html('{{trans("valid.product_group_name")}}');
            }
            $("#product_create").addClass('has-danger');

            prod_error = true;
            return false;
          }
        });
        if(prod_error){
          return false;
        }
        loadAjax({
          type: "POST",
          cache: false,
          contentType: false,
          processData: false,
          data: data,
          url: '/createLocationFrontend/StepTwo',
          success: function (data) {
            if(data.status == 'error')
            {
              var first_err = Object.keys(data.message)[0];
              $('#'+first_err).focus();
              
              for (var prop in data.message) {
                if (data.message.hasOwnProperty(prop)) {
                  if(prop == 'address_map' || prop == 'lat' || prop == 'lng')
                  {
                    $('#feedback_address_map').show().html(data.message[prop]);
                    $('#fieldset_feedback_address_map').addClass('has-danger');
                    $("#modal-new-location").scrollTo('#fieldset_feedback_address_map');
                    $("#modal-new-location #address_map").focus();
                  }
                  else if(prop == 'category_item')
                  {
                    $('#feedback_category_item').show().html(data.message['category_item']);
                    $('#fieldset_feedback_category_item').addClass('has-danger');
                    $("#modal-new-location").scrollTo('#fieldset_feedback_category_item');
                  }
                  else if(prop == 'alias' || prop == 'name')
                  {
                    $('#feedback_name').show().html(data.message['name']);
                    $('#fieldset_feedback_name').addClass('has-danger');
                    $("#modal-new-location").scrollTo('#fieldset_feedback_name');
                    $("#modal-new-location #name").focus();
                  }
                  else if(prop == 'tag')
                  {
                    $('#feedback_tag').show().html(data.message['tag']);
                    $('#fieldset_feedback_tag').addClass('has-danger');
                    $("#modal-new-location").scrollTo('#fieldset_feedback_tag');
                    $(".tagsinput input").focus();
                  }
                  else{
                    $('#feedback_'+prop).show().html(data.message[prop]);
                    $('#fieldset_feedback_'+prop).addClass('has-danger');
                    $('#'+first_err).focus();
                    $("#modal-new-location").scrollTo('#fieldset_feedback_'+prop);
                  }
                }
              }
            }
            else{
              $('.form-control-feedback').hide();
              $("#form-creat-location fieldset").removeClass('has-danger');

              $(current_button).closest('.tab-pane').removeClass('active');
              $(current_button).closest('.cread-location').find('.tab-pane').eq(index + 1).addClass('active');
              $(current_button).closest('.cread-location').find('.nav-item').eq(0).removeClass('highlight').addClass('complete');
              $(current_button).closest('.cread-location').find('.nav-item').eq(1).removeClass('disabled').addClass('highlight');
            }
          },
        });
      }

      if ($(this).attr("id") == 'button_step2_edit')
      {
        $("#form-creat-location-edit fieldset").removeClass('has-danger');
        var current_button = this;
        var index = $(this).closest('.tab-pane').index();
        $('.form-control-feedback').hide();

        var form = $('#form-creat-location-edit')[0];
        var data = new FormData(form);
        data.set('_token', $("meta[name='_token']").prop('content'));
        if(editAvatar !== undefined)
        {
          data.set('avatar', editAvatar);
        }

        loadAjax({
          type: "POST",
          cache: false,
          contentType: false,
          processData: false,
          data: data,
          url: '/createLocationFrontend/StepTwo',
          success: function (data) {
            if(data.status == 'error')
            {
              var first_err = Object.keys(data.message)[0];
              $('#'+first_err).focus();
              for (var prop in data.message) {
                if (data.message.hasOwnProperty(prop)) {

                  if(prop == 'address_map' || prop == 'lat' || prop == 'lng')
                  {
                    $('#feedback_address_edit_map').show().html(data.message[prop]);
                    $('#fieldset_feedback_address_edit_map').addClass('has-danger');
                    $("body").scrollTo('#fieldset_feedback_address_edit_map');
                    $("#form-creat-location-edit #address_edit_map").focus();
                  }
                  else if(prop == 'category_item')
                  {
                    $('#feedback_category_item_edit').show().html(data.message['prop']);
                    $('#fieldset_feedback_category_item_edit').addClass('has-danger');
                    $("body").scrollTo('#fieldset_feedback_category_item_edit');
                  }
                  else if(prop == 'alias' || prop == 'name')
                  {
                    $('#feedback_name_edit').show().html(data.message['name']);
                    $('#fieldset_feedback_name_edit').addClass('has-danger');
                    $("body").scrollTo('#fieldset_feedback_name_edit');
                    $("#form-creat-location-edit #name_edit").focus();
                  }
                  else if(prop == 'tag')
                  {
                    $('#feedback_tag_edit').show().html(data.message['tag']);
                    $('#fieldset_feedback_tag_edit').addClass('has-danger');
                    $("body").scrollTo('#fieldset_feedback_tag_edit');
                    $("#form-creat-location-edit .tagsinput input").focus();
                  }
                  else{
                    $('#feedback_'+prop+'_edit').show().html(data.message[prop]);
                    $('#fieldset_feedback_'+prop+'_edit').addClass('has-danger');
                    $('#'+first_err+'_edit').focus();
                    $("body").scrollTo('#fieldset_feedback_'+prop+'_edit');
                  }

                }
              }
            }
            else{
              $('.form-control-feedback').hide();
              $("#form-creat-location-edit fieldset").removeClass('has-danger');

              $(current_button).closest('.tab-pane').removeClass('active');
              $(current_button).closest('.cread-location').find('.tab-pane').eq(index + 1).addClass('active');
              $(current_button).closest('.cread-location').find('.nav-item').eq(index).removeClass('highlight').addClass('complete');
              $(current_button).closest('.cread-location').find('.nav-item').eq(index + 1).removeClass('disabled').addClass('highlight');
            }
          }
        });
      }

    });



    $('.btn-step .prev-step').click(function() {
      $('#err_id_category').hide();
      $('.form-control-feedback').hide();
      $("#form-creat-location fieldset").removeClass('has-danger');
      var index = $(this).closest('.tab-pane').index();
      $(this).closest('.tab-pane').removeClass('active');
      $(this).closest('.cread-location').find('.tab-pane').eq(index - 1).addClass('active');
      $(this).closest('.cread-location').find('.nav-item').eq(index).removeClass('highlight').addClass('disabled');
      $(this).closest('.cread-location').find('.nav-item').eq(index - 1).removeClass('complete').addClass('highlight');
    });

    $('.back-step').click(function(event) {
      $('#modal-new-location').modal('hide');
    });
    //add link youtobe

    // $('.box-location-add-offer a').click(function(e){
    //     e.preventDefault();
    //     $('.form-add-offer-location').slideToggle('400');
    // });
    //tag Automatic
    $(function(){
      $("#address_map").on("focus",function(){
        var pacContainer = $('.pac-container');
        $("#map_div").append(pacContainer);
      })
      $('#form-creat-location .tokeHastag').tagsInput({
        width: 'auto',
        defaultText: "{{trans('global.add_keyword')}}",
        onChange: function(){
          var input = $(this).siblings('.tagsinput');
          var maxLen = 100; // e.g.
          if(input.children('span.tag').length >= maxLen){
            input.children('div').hide();
          }
          else{
            input.children('div').show();
          }
        },
        onRemoveTag:function(){
          $("#form-creat-location div.tagsinput input").focus();
        },
        onAddTag:function(){
          $("#form-creat-location div.tagsinput input").focus();
        }
      });

      $("#form-creat-location div.tagsinput input").on('paste',function(e){
          var element=this;
          setTimeout(function () {
              var text = $(element).val();
              var target=$("#form-creat-location .tokeHastag");
              var tags = (text).split(/[,]+/);
              for (var i = 0, z = tags.length; i<z; i++) {
                    var tag = $.trim(tags[i]);
                    if (!target.tagExist(tag)) {
                          target.addTag(tag);
                    }
                    else
                    {
                        $("#form-creat-location div.tagsinput input").val('');
                    }
              }
              $("#form-creat-location div.tagsinput input").focus();
          },10);
      });
      $("#form-creat-location div.tagsinput input").on('textInput',function(e){
          var element=this;
          setTimeout(function () {
            var text = $(element).val();
            var target=$("#form-creat-location .tokeHastag");
            if(text.indexOf(',') > -1){
              var tag = text.replace(',','');
              if (!target.tagExist(tag)) {
                  target.addTag(tag);
              }else{
                  $("#form-creat-location div.tagsinput input").val('');
                  $("#form-creat-location div.tagsinput input").focus();
              }
            }
          },10);
      });
      
    })
  });



  var geocoder_create_location = new google.maps.Geocoder();
  var marker_create_location = new google.maps.Marker();
  var infowindow_create_location = new google.maps.InfoWindow({
    size: new google.maps.Size(150, 50)
  });
  function CenterControlCreate(controlDiv, map) {

    // Set CSS for the control border.
    var controlUI = document.createElement('div');
    controlUI.style.backgroundColor = '#fff';
    controlUI.style.border = '2px solid #fff';
    controlUI.style.cursor =  'pointer'; 
    controlUI.style.width =  '25px'; 
    controlUI.style.height =  '25px'; 
    controlUI.style.overflow =  'hidden'; 
    controlUI.style.margin =  '10px 14px'; 
    controlUI.style.position =  'absolute';
    controlUI.title =  'You current location'; 

    controlUI.style.top =  '10px'; 
    controlUI.style.right =  '0px';

    controlUI.style.textAlign =  'center';
    controlUI.style.backgroundImage = 'url(/img_default/location.png)';
    controlUI.style.backgroundSize = '220px 22px';
    controlDiv.appendChild(controlUI);

    // Set CSS for the control interior.
    // var controlImg = document.createElement('img');
    // controlImg.src = '/img_default/location.png';
    // controlImg.style.maxHeight = '100%';
    // controlImg.style.maxWidth = '100%';
    // controlImg.style.height = '25px';
    // controlUI.appendChild(controlImg);

    // Setup the click event listeners: simply set the map to Chicago.
    controlUI.addEventListener('click', function() {
      var current_location = window.sessionStorage.getItem('currentLocation');
      current_location = current_location.split(",");
      var location = new google.maps.LatLng(current_location[0], current_location[1]);
      map_create_location.setCenter(location);
      marker_create_location.setPosition(location);
    });

    controlUI.addEventListener('mouseover', function() {
      if($(window).width()>768)
        this.style.backgroundPositionX = '44px';
    });
    controlUI.addEventListener('mouseout', function() {
      if($(window).width()>768)
        this.style.backgroundPositionX = '0px';
    });
  }
  function initialize_create_location() {
    var latLng_create_location = new google.maps.LatLng(10.773234, 10.773234);
    map_create_location = new google.maps.Map(document.getElementById('google_map'), {
      zoom: 15,
      center: latLng_create_location,
      zoomControl: true,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      draggable: true
    });
    var centerControlDiv = document.createElement('div');
    var centerControl = new CenterControlCreate(centerControlDiv, map_create_location);

    centerControlDiv.index = 2;
    map_create_location.controls[google.maps.ControlPosition.RIGHT_TOP].push(centerControlDiv);

    var input = document.getElementById('address_map');
    autocomplete = new google.maps.places.Autocomplete(input);

    google.maps.event.addListener(autocomplete, 'place_changed', function () {
      codeAddress(autocomplete.getPlace().formatted_address);
    });

    // $("#map_div").find(".pac-container").remove();
    // setTimeout(function(){ 
    //     let clone = $(".pac-container").last();
    //     $("#map_div").append(clone);
    // }, 300);

  };
  google.maps.event.addDomListener(window, "load", initialize_create_location);

  function getLocationAjax(value, type) {
    if (type == 'city') {
      $('#district').html('<option value="">-- {{trans('Location'.DS.'layout.district')}} --</option>');
    }
    $.ajax({
      type: "POST",
      data: {value: value, type: type, _token: $("meta[name='_token']").prop('content')},
      url: '/createLocationFrontend/postLocation',
      success: function (data) {
        $("#" + type).html(data);
      }
    })
  }

  function getFromCreateLocation(id_user) {
    if (id_user === undefined) {
      $('#modal-signin').modal('show');
    }
    else {
      $('#modal-new-location').modal('show');
    }
  }

  function codeAddress(address) {
    geocoder_create_location.geocode({
      'address': address
    }, function (results, status) {
      if (status == google.maps.GeocoderStatus.OK) {

        $("#google_map").show();
        initialize_create_location();

        map_create_location.setCenter(results[0].geometry.location);
        if (marker_create_location) {
          marker_create_location.setMap(null);
          if (infowindow_create_location) infowindow_create_location.close();
        }

        marker_create_location = new google.maps.Marker({
          map: map_create_location,
          draggable: true,
          position: results[0].geometry.location
        });

        google.maps.event.addListener(marker_create_location, 'dragend', function () {
          geocodePosition(marker_create_location.getPosition());
          map_create_location.setCenter(marker_create_location.getPosition());
        });

        google.maps.event.addListener(marker_create_location, 'click', function () {
          if (marker_create_location.formatted_address) {
            infowindow_create_location.setContent(marker_create_location.formatted_address);
          } else {
            infowindow_create_location.setContent(address);
          }
          infowindow_create_location.open(map_create_location, marker_create_location);
        });

        var addr = '';
        // console.log(results[0]);
        for (var i = 0; i < results[0].address_components.length; i++) {
          if (results[0].address_components[i].types[0] == 'street_number' || results[0].address_components[i].types[0] == 'premise') {
            addr = results[0].address_components[i].long_name
          }
          if (results[0].address_components[i].types[0] == 'route') {
            addr = addr + ' ' + results[0].address_components[i].long_name
          }
        }

        $("#address").val(addr);
        $("#lat").val(results[0].geometry.location.lat().toFixed(6));
        $("#lng").val(results[0].geometry.location.lng().toFixed(6));
        google.maps.event.trigger(marker_create_location, 'click');
      } else {
        $("#address_map").val('');
        $("#lat").val('');
        $("#lng").val('');
      }
    });
  }

  function geocodePosition(pos) {
    geocoder_create_location.geocode({
      latLng: pos
    }, function (responses) {
      if (responses && responses.length > 0) {
        $("#lat").val(marker_create_location.getPosition().lat().toFixed(6));
        $("#lng").val(marker_create_location.getPosition().lng().toFixed(6));
      } else {
        marker_create_location.formatted_address = 'Cannot determine address at this location.';
      }
    });
  }

  function removeVideo(obj){


    var type = $(obj).attr('data-type');
    if(type === 'old_edit'){
        var id = '#old_video_edit_'+$(obj).attr('data-id');
        $.ajax({
            url : '/createLocationFrontend/deleteInfoVideo',
            type: 'GET',
            data:{
                id : id
            },
            success:function(res){
                console.log('done');
            }
        });
    }else{
        if(type === 'edit') {
            var id = '#video_edit_' + $(obj).attr('data-id');
        }else{
            var id = '#video_create_' + $(obj).attr('data-id');
        }
    }
    if($(id).length){
        $(id).remove();
    }
  }

  function getUrlToButton(obj){
      var type = $(obj).attr('data-type');
      var url = $(obj).attr('data-url');
      if(type === 'edit'){
          var code = '#edit_new_video';
      }else{
          var code = '#add_new_video';
      }
      $(code).val(url);
      return false;
  }

  function saveCategory(obj){
    var arr = [];
    $('input[name^=category_item]').each(function(key,elem){
      if($(elem).is(":checked")){
        arr.push($(elem).val());
      }
    })
    sessionStorage.category = arr;
  }

  function saveService(obj){
    var arr = [];
    $('input[name^=service]').each(function(key,elem){
      if($(elem).is(":checked")){
        arr.push($(elem).val());
      }
    })
    sessionStorage.service = arr;
  }

  function reloadCatService(){
    if(sessionStorage.category){
      var arr = sessionStorage.category.split(',') 
      for(var i=0; i<arr.length;i++){
        $('input[name^=category_item]').each(function(key,elem){
          if(parseInt($(elem).val()) == parseInt(arr[i])){
            $(elem).attr('checked',true);
            $(elem).change();
          }
        })
      }
    }
    
    if(sessionStorage.service){
      var arr = sessionStorage.service.split(',')
      for(var i=0; i<arr.length;i++){
        $('input[name^=service]').each(function(key,elem){
          if(parseInt($(elem).val()) == parseInt(arr[i])){
            $(elem).attr('checked',true);
            $(elem).change();
          }
        })
      }
    }

    //console.log(sessionStorage.category,sessionStorage.service);
  }

  function loadThumb(obj){
    var old_img = $(obj).next().next();
    console.log(old_img);
    var url = $(obj).val();
    if (url != undefined || url != '') {
      var p = /^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
      if(url.match(p)){
        var youtube_video_id = url.match(/^.*(youtu.be\/|v\/|e\/|u\/\w+\/|embed\/|v=)([^#\&\?]*).*/).pop();
        if (youtube_video_id.length == 11) {
          var video_thumbnail = $('<div class="text-center"><img height="200" src="//img.youtube.com/vi/'+youtube_video_id+'/0.jpg"></div><br/>');
          if(old_img.length){
            old_img.html('<img height="200" src="//img.youtube.com/vi/'+youtube_video_id+'/0.jpg">');
          }else{
            $(obj).parent().append(video_thumbnail);
          }
          
        }
      }

      var p = /^https:\/\/www\.facebook\.com\/([^\/?].+\/)?video(s|\.php)[\/?].*$/gm;
      if(url.match(p)){
        var fb_video_id = url.match(/videos\/(\d+)/).pop();
        console.log(fb_video_id)
        var video_thumbnail = $('<div class="text-center"><img height="200" src="https://graph.facebook.com/'+fb_video_id+'/picture"></div><br/>');
        if(old_img.length){
          old_img.html('<img height="200" src="https://graph.facebook.com/'+fb_video_id+'/picture">');
        }else{
          $(obj).parent().append(video_thumbnail);
        }
      }
    }
  };
  var id_new_video = 0;
  function loadThumbNew(check){
      if(check === 'edit'){
          var code = '#edit_new_video';
      }else{
          var code = '#add_new_video';
      }
    var id = id_new_video;
    var type = $(code).attr('data-type');
    // console.log(id,type);
    var url = $(code).val();

    var y_match = /^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
    var f_match = /^https:\/\/www\.facebook\.com\/([^\/?].+\/)?video(s|\.php)[\/?].*$/gm;
    if (url != undefined || url != '') {
      if(url.match(y_match) || url.match(f_match)){
        $.ajax({
          url : '/createLocationFrontend/getInfoVideo',
          type: 'GET',
          data:{
            url : url
          },
          success:function(res){
            var html = '';
            if($('#video_'+type+'_'+id).length){
              html+= '';
            }else{
              html+='<div class="col-md-4 col-sm-6 mb-4" id="video_'+type+'_'+id+'">';
            }
            
            html+='    <div class="iframe-video">';
            html+='<i class="fa fa-remove remove-video" onclick="removeVideo(this)" data-id="'+id+'" data-type="'+type+'" style="position: absolute;' +
                '    right: 10px;' +
                '    top: 5px;' +
                '    z-index: 1000;' +
                '    color: red;' +
                '    font-size: 25px;"></i>';
            html += '<a href="javascript:void(0)" onclick="getUrlToButton(this)" data-url="'+url+'" data-type="'+type+'">';
            // if(res.type=='facebook'){
            //   html+='        <a data-fancybox data-type="iframe" href="'+res.player+'">';
            // }else{
            //   html+='        <a data-fancybox href="'+url+'">';
            // }
            html+='            <img src="'+res.thumbnail+'" alt="">';
            html+='            <span class="ytp-time-duration">'+res.time+'</span>';
            html+='        </a>';
            html+='        <p>';
            html+='            <a href="javascript:void(0)">';
            html+='                '+res.title+' ';
            html+='            </a>';
            html+='        </p>';
            html+='    </div>';
            html+='<input type="hidden" name="link[]" value="'+url+'">';
            if($('#video_'+type+'_'+id).length){
              html+= '';
              $('#video_'+type+'_'+id).html(html);
            }else{
              html+='</div>';
              if($('#video_'+type+'_'+(id-1)).length){
                $('#video_'+type+'_'+(id-1)).after($(html));
              }else{
                if($('#video_'+type+'_'+(id+1)).length){
                  $('#video_'+type+'_'+(id+1)).before($(html));
                }else{
                  if(type=='create'){
                    $("#content-video-location-create").append(html);
                  }else{
                    $("#video-edit .content-video-location").append(html);
                  }
                }
              }
            }
              id_new_video = id_new_video+1;
          }
        })
      }else{
        if($('#video_'+type+'_'+id).length){
          $('#video_'+type+'_'+id).remove();
        }
      }
    }else{
      if($('#video_'+type+'_'+id).length){
        $('#video_'+type+'_'+id).remove();
      }
    }
      $(code).val("");
  };

  $(function () {
    sessionStorage.category = [];
    sessionStorage.service = [];
    $("#form-creat-location #name").on("keyup", function () {
      var name = $(this).val();
      $("#form-creat-location #alias").val(str_slug(name));
    })

    // $("#phone").on("blur", function (e) {
    //   var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,4})(\d{0,4})/);
    //   e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    // });
      $("#phone").on("keypress",function(e){
          return (e.charCode >= 48 && e.charCode <= 57) || e.charCode == 43 || event.charCode == 0 ;
      });


      $('textarea.max').keypress(function(e) {
          var max = $(this).attr('maxLength');
          var length = max;
          if (e.which < 0x20) {
              // e.which < 0x20, then it's not a printable character
              // e.which === 0 - Not a character
              return;     // Do nothing
          }
          if (this.value.length === length) {
              e.preventDefault();
          } else if (this.value.length > length) {
              // Maximum exceeded
              this.value = this.value.substring(0, length);
          }
      });

  })

  function addCategoryItem(){
    var _token = $("meta[name='_token']").prop('content');
    var category_item = $("#category_item_input").val();
    var category = $('input[name="id_category"]:checked').val();
    if(!category_item){
      alert('{{trans('valid.category_item_input')}}');
      return false;
    }else{
      $.ajax({
        url : '/createLocationFrontend/postCreateCategoryItem',
        type: 'POST',
        data: {
          category_item : category_item,
          category      : category,
          _token        : _token
        },
        success:function(res){
          if(res.error==1){
            alert(res.message);
          }else{
            $("#list_category_item").append(res.data);
          }
          console.log(res);
        }
      })
    }
  }

  function addService(){
    var _token = $("meta[name='_token']").prop('content');
    var service = $("#service_input").val();
    var category = $('input[name="id_category"]:checked').val();
    if(!service){
      alert('{{trans('valid.service_input')}}');
      return false;
    }else{
      $.ajax({
        url : '/createLocationFrontend/postCreateService',
        type: 'POST',
        data: {
          service : service,
          category      : category,
          _token        : _token
        },
        success:function(res){
          if(res.error==1){
            alert(res.message);
          }else{
            $("#list_service_item").append(res.data);
          }
          console.log(res);
        }
      })
    }
  }

  function addCategory(){
    var _token = $("meta[name='_token']").prop('content');
    var category = $("#category_input").val();
    if(!category){
      alert('{{trans('valid.category_input')}}');
      return false;
    }else{
      $.ajax({
        url : '/createLocationFrontend/postCreateCategory',
        type: 'POST',
        data: {
          category : category,
          _token        : _token
        },
        success:function(res){
          $("#message_category").text(res.message);
          $("#modal-create-category").modal();
          $("#modal-new-location").css({'overflow':'scroll'})
          console.log(res);
        }
      })
    }
  }


  function readImageProduct(input) {
    for (var i = 0; i < input.files.length; i++) {
      if (input.files[i]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          var img = $('<img style="height: 40px; width: 40px; border: 1px solid #000; margin: 2px">');
          img.attr('src', e.target.result);
          $(input).parent().next().html(img);
          console.log($(input).parent().find('.img_product'));
        };
        reader.readAsDataURL(input.files[i]);
      }
    }
  }

  function addProduct(index_group){
    var index = $("#form-creat-location .item_product").length+1;

    $('#form-creat-location .header_product').show();
    html='';
    html+='<div class="item_product row align-items-center">';
    html+='<input type="hidden" name="product['+index_group+']['+index+'][id]" value="0" placeholder="">';
    html+='<div class="col-md-4 text-center">';
    html+='<input type="text" maxLength="128" class="form-control" name="product['+index_group+']['+index+'][name]"    placeholder="{{trans('Admin'.DS.'content.name')}}" required="required">';
    html+='</div>';
    html+='<div class="col-md-2 text-center">';
    html+='<input type="number" min="0" max="9999999999" class="form-control" name="product['+index_group+']['+index+'][price]" placeholder="{{trans('Admin'.DS.'content.price')}}" required="required">';
    html+='</div>';
    html+='<div class="col-md-2 text-center">';
    html+='<select class="form-control custom-select" name="product['+index_group+']['+index+'][currency]">';
    html+=' <option value="VND">VND</option>';
    html+=' <option value="USD">USD</option>';
    html+='</select>';
    html+='</div>';
    html+='<div class="col-md-2 text-center relative">';
    html+='<button class="btn btn-default">{{trans('Location'.DS.'layout.choose_image')}}</button><input type="file" class="" name="product['+index_group+']['+index+'][image]" onchange="readImageProduct(this)" required="required">';
    html+='</div>';
    html+='<div class="col-md-2 img_product text-center">';
    html+='</div>';
    // html+='<div class="col-md-1">';
    html+='<a class="remove_custom_open" onclick="removeProduct(this)"><i class="fa fa-remove"></i></a>';
    // html+='</div>';
    html+='</div>';
    $("#form-creat-location #list_product_"+index_group).append(html);
  }

  function removeProduct(obj){
    $(obj).parent().remove();
    if($("#form-creat-location .item_product").length==0){
      $('#form-creat-location .header_product').hide();
    }
  }

  function removeGroupProduct(index){
    $('#form-creat-location #group_product_'+index).remove();
  }

  function addGroup(){
    var index = $("#form-creat-location .item_product").length+1;
    var html='';
    html+='<div class="group_product" id="group_product_'+index+'" style="">';
    html+='<div class="form-group  row align-items-center">';
    html+='<label class="control-label col-md-4 col-sm-4 col-xs-12">';
    html+='{{trans('Admin'.DS.'content.product_group')}} <span style="color: #d9534f">*</span>';
    html+='</label>';
    html+='<div class="col-md-7 col-sm-7 col-xs-12">';
    html+='<input class="form-control" type="text" maxLength="128" name="product['+index+'][group_name]" required="required"/>';
    html+='</div>';
    html+='<div class="col-md-1 col-sm-1 col-xs-12">';
    html+='<a class="remove_custom_open" onclick="removeGroupProduct('+index+')"><i class="fa fa-remove"></i></a>';
    html+='</div>';
    html+='</div>';
    html+='<div class="header_product row">';
    html+='<div class="col-md-4"><label>{{trans('Admin'.DS.'content.name')}} <span style="color: #d9534f">*</span></label></div>';
    html+='<div class="col-md-4"><label>{{trans('Admin'.DS.'content.price')}} <span style="color: #d9534f">*</span></label></div>';
    html+='<div class="col-md-4"><label>{{trans('Admin'.DS.'content.image')}} <span style="color: #d9534f">*</span></label></div>';
    html+='</div>';
    html+='<div id="list_product_'+index+'">';
    html+='<div class="item_product row align-items-center">';
    html+='<input type="hidden" name="product['+index+'][1][id]" value="0" placeholder="">';
    html+='<div class="col-md-4 text-center">';
    html+='<input type="text" maxLength="128" class="form-control" name="product['+index+'][1][name]" placeholder="Tên" required="required">';
    html+='</div>';
    html+='<div class="col-md-2 text-center">';
    html+='<input type="number" min="0" max="9999999999" class="form-control" name="product['+index+'][1][price]" placeholder="Giá" required="required">';
    html+='</div>';
    html+='<div class="col-md-2 text-center">';
    html+='<select class="form-control custom-select" name="product['+index+'][1][currency]">';
    html+='<option value="VND">VND</option>';
    html+='<option value="USD">USD</option>';
    html+='</select>';
    html+='</div>';
    html+='<div class="col-md-2 text-center relative">';
    html+='<button class="btn btn-default">{{trans('Location'.DS.'layout.choose_image')}}</button><input type="file" class="" name="product['+index+'][1][image]" onchange="readImageProduct(this)" required="required">';
    html+='</div>';
    html+='<div class="col-md-2 img_product text-center"></div>';
    html+='</div>';
    html+='</div>';
    html+='<div class="text-center" style="margin-top: 15px;">';
    html+='<button class="btn btn-primary" type="button" onclick="addProduct('+index+')">{{trans('Admin'.DS.'content.add_product')}}</button>';
    html+='</div>';
    html+='</div>';
    $("#form-creat-location #list_group_product").append(html);
  }

  function searchCreateContent(){
    var _token = $("meta[name='_token']").prop('content');
    $.ajax({
      url : '/createLocationFrontend/getCreated',
      type: 'POST',
      data: {
        'q': $('#modal-new-location input[id=name]').val(),
        _token        : _token
      },
      success:function(res){
        $("#list_created").html(res);
      }
    });
  }
</script>

<!-- Code js cho phan dateopen -->
<script>
  $('.choose_hour:even').datetimepicker({
    format: 'HH:mm',
    defaultDate: moment().hours(8).minutes(0).seconds(0).milliseconds(0)
  });

  $('.choose_hour:odd').datetimepicker({
    format: 'HH:mm',
    defaultDate: moment().hours(22).minutes(0).seconds(0).milliseconds(0)
  });

  $('#open_from').datetimepicker({
    format: 'HH:mm',
  });
  $('#open_to').datetimepicker({
    format: 'HH:mm',
  });

  function showCustomOpen(){
    $("#custom_open").toggle();
  }

  function addCustomOpen(){
    var index = $(".item_custom_open").length;
    index++;

    html = '<div class="item_custom_open row mt-4">';
    html +='          <div class="col-md-3 col-6 mb-2">';
    html +='            <label class="hidden-md-up">{{trans('Admin'.DS.'content.from_date')}}</label>';
    html +='            <select class="form-control" name="date_open['+index+'][from_date]">';
    html +='              <option value="1">{{trans('Admin'.DS.'content.monday')}}</option>';
    html +='              <option value="2">{{trans('Admin'.DS.'content.tuesday')}}</option>';
    html +='              <option value="3">{{trans('Admin'.DS.'content.wednesday')}}</option>';
    html +='              <option value="4">{{trans('Admin'.DS.'content.thursday')}}</option>';
    html +='              <option value="5">{{trans('Admin'.DS.'content.friday')}}</option>';
    html +='              <option value="6">{{trans('Admin'.DS.'content.saturday')}}</option>';
    html +='              <option value="0">{{trans('Admin'.DS.'content.sunday')}}</option>';
    html +='            </select>';
    html +='          </div>';
    html +='          <div class="col-md-3 col-6 mb-2">';
    html +='            <label class="hidden-md-up">{{trans('Admin'.DS.'content.to_date')}}</label>';
    html +='            <select class="form-control" name="date_open['+index+'][to_date]">';
    html +='              <option value="1">{{trans('Admin'.DS.'content.monday')}}</option>';
    html +='              <option value="2">{{trans('Admin'.DS.'content.tuesday')}}</option>';
    html +='              <option value="3">{{trans('Admin'.DS.'content.wednesday')}}</option>';
    html +='              <option value="4">{{trans('Admin'.DS.'content.thursday')}}</option>';
    html +='              <option value="5">{{trans('Admin'.DS.'content.friday')}}</option>';
    html +='              <option value="6">{{trans('Admin'.DS.'content.saturday')}}</option>';
    html +='              <option value="0" selected>{{trans('Admin'.DS.'content.sunday')}}</option>';
    html +='            </select>';
    html +='          </div>';
    html +='          <div class="col-md-3 col-6 mb-2">';
    html +='            <label class="hidden-md-up">{{trans('Admin'.DS.'content.from_hour')}}</label>';
    html +='            <input class="form-control choose_hour" type="text" name="date_open['+index+'][from_hour]" value="" >';
    html +='          </div>';
    html +='          <div class="col-md-3 col-6 mb-2">';
    html +='            <label class="hidden-md-up">{{trans('Admin'.DS.'content.to_hour')}}</label>';
    html +='            <input class="form-control choose_hour" type="text" name="date_open['+index+'][to_hour]" value="" >';
    html +='          </div>';
    html +='  <span><i class="remove_custom_open fa fa-remove" onclick="removeCustomOpen(this)"></i></span>';
    html +='</div>';
    $("#append_custom_open").append(html);

    $('.choose_hour:even').datetimepicker({
    format: 'HH:mm',
    defaultDate: moment().hours(8).minutes(0).seconds(0).milliseconds(0)
  });

  $('.choose_hour:odd').datetimepicker({
    format: 'HH:mm',
    defaultDate: moment().hours(22).minutes(0).seconds(0).milliseconds(0)
  });
  }

  function removeCustomOpenEdit(obj){
    $(obj).parent().parent().remove();
  }

  function addCustomOpenEdit(){
    var index = $(".item_custom_open").length;
    index++;

    html = '<div class="item_custom_open row mt-4">';
    html +='          <div class="col-md-3 col-6 mb-2">';
    html +='            <label class="hidden-md-up">{{trans('Admin'.DS.'content.from_date')}}</label>';
    html +='            <select class="form-control" name="date_open['+index+'][from_date]">';
    html +='              <option value="1">{{trans('Admin'.DS.'content.monday')}}</option>';
    html +='              <option value="2">{{trans('Admin'.DS.'content.tuesday')}}</option>';
    html +='              <option value="3">{{trans('Admin'.DS.'content.wednesday')}}</option>';
    html +='              <option value="4">{{trans('Admin'.DS.'content.thursday')}}</option>';
    html +='              <option value="5">{{trans('Admin'.DS.'content.friday')}}</option>';
    html +='              <option value="6">{{trans('Admin'.DS.'content.saturday')}}</option>';
    html +='              <option value="0">{{trans('Admin'.DS.'content.sunday')}}</option>';
    html +='            </select>';
    html +='          </div>';
    html +='          <div class="col-md-3 col-6 mb-2">';
    html +='            <label class="hidden-md-up">{{trans('Admin'.DS.'content.to_date')}}</label>';
    html +='            <select class="form-control" name="date_open['+index+'][to_date]">';
    html +='              <option value="1">{{trans('Admin'.DS.'content.monday')}}</option>';
    html +='              <option value="2">{{trans('Admin'.DS.'content.tuesday')}}</option>';
    html +='              <option value="3">{{trans('Admin'.DS.'content.wednesday')}}</option>';
    html +='              <option value="4">{{trans('Admin'.DS.'content.thursday')}}</option>';
    html +='              <option value="5">{{trans('Admin'.DS.'content.friday')}}</option>';
    html +='              <option value="6">{{trans('Admin'.DS.'content.saturday')}}</option>';
    html +='              <option value="0" selected>{{trans('Admin'.DS.'content.sunday')}}</option>';
    html +='            </select>';
    html +='          </div>';
    html +='          <div class="col-md-3 col-6 mb-2">';
    html +='            <label class="hidden-md-up">{{trans('Admin'.DS.'content.from_hour')}}</label>';
    html +='            <input class="form-control choose_hour" type="text" name="date_open['+index+'][from_hour]" value="" >';
    html +='          </div>';
    html +='          <div class="col-md-3 col-6 mb-2">';
    html +='            <label class="hidden-md-up">{{trans('Admin'.DS.'content.to_hour')}}</label>';
    html +='            <input class="form-control choose_hour" type="text" name="date_open['+index+'][to_hour]" value="" >';
    html +='          </div>';
    html +='  <span><i class="remove_custom_open fa fa-remove" onclick="removeCustomOpen(this)"></i></span>';
    html +='</div>';
    $("#append_custom_open_edit").append(html);

    $('.choose_hour:even').datetimepicker({
    format: 'HH:mm',
    defaultDate: moment().hours(8).minutes(0).seconds(0).milliseconds(0)
  });

  $('.choose_hour:odd').datetimepicker({
    format: 'HH:mm',
    defaultDate: moment().hours(22).minutes(0).seconds(0).milliseconds(0)
  });
  }

  function removeCustomOpen(obj){
    $(obj).parent().parent().remove();
  }

  function updateImg(obj,type='space'){
    var id = $(obj).attr('data-field');
    var parent = $(obj).parent();
    var title = $(parent.find('input').get(0)).val();
    var des = $(parent.find('input').get(1)).val();
    var _token = $("meta[name='_token']").prop('content');

    $.ajax({
      type: "POST",
      data: {
        'id'     : id,
        'title'  : title,
        'des'    : des,
        '_token' : _token,
        'type'   : type
      },
      url: '/editLocationFrontend/updateImage',
    })
  }

  function closeSuccess(){
    $("#modal-create-success").modal('hide');
    setTimeout(function(){
      $("#modal-manager-location").modal('show');
    },300);
    loadManager();
  }

  function loadManager(tab){
    if (typeof tab === 'undefined' || tab == null){
      tab=0;
    }
    var id = $("#content_id_create").val();
    $.ajax({
      url : '/createLocationFrontend/getManageLocaiton/'+id,
      type: 'GET',
      success:function(res){
        $("#content-manager-location").html(res.manager)
        $("#content-add-same-location").html(res.location)
        renderApplyUpload();
        $("#tab-manager-location .nav-item").eq(tab).find('a').click();
      },
      error:function(){
        $("#modal-manager-location").modal('hide');
      }
    })
  }

  function renderApplyUpload(){
    $(".manage-upload").on('change',function(){
      var file = $(this).get(0).files[0];
      if(typeof file != 'undefined') {
          var tplavata = $('<div class="box-img-upload-success"><img /></div>');
          var currentLi = $(this).parent().find('.box-img-upload').html(tplavata);
          // data.context = tpl.appendTo(ul);
          // draw preview image
          var reader = new FileReader();
          reader.onloadend = function (e) {
              var tempImg = new Image();
              tempImg.src = reader.result;
              tempImg.onload = function () {
                  var width = 270;
                  var height = 202;
                  var canvas = document.createElement('canvas');
                  canvas.width = width;
                  canvas.height = height;
                  var ctx = canvas.getContext("2d");
                  ctx.drawImage(this, 0, 0, width, height);
                  var dataURL = canvas.toDataURL("image/jpeg");
                  currentLi.find('img').attr('src', dataURL);
              };
          };
          reader.readAsDataURL(file);
      }else{
          $(this).parent().find('.box-img-upload').html('<div class="box-img-upload-content"><i class="icon-new-white"></i><p>{{trans('Location'.DS.'layout.choose_image')}}</p></div>');
      }
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
    $("input[type='number']").on('keyup',function(){
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

    $("#product_id").on("change",function(){
      if($("#product_id").val==0){
        $("#product_button").text("<i class='icon-new-white'></i> {{trans('Location'.DS.'layout.add_products_services')}}")
      }else{
        $("#product_button").text("{{trans('Location'.DS.'layout.update_products_services')}}")
      }
    })

    $("#discount_manager_id").on("change",function(){
      if($("#discount_manager_id").val==0){
        $("#discount_manager_button").text("<i class='icon-new-white'></i> {{trans('Location'.DS.'layout.add_discount')}}")
      }else{
        $("#discount_manager_button").text("{{trans('Location'.DS.'layout.update_discount')}}")
      }
    })
    // scroll tab on notific
      if($(window).width() > 720){
        // $(".scroll-content-modal").mCustomScrollbar({
        //     theme: "dark",
        //     contentTouchScroll: true,
        //     mouseWheel:{ scrollAmount: 160 }
        // });
      }else{
        $(".scroll-content-modal").css({
          'max-height':"100%",
          'margin-bottom':0
        });
      }
      

      // $("#notification-header-tabs .tab-content .tab-pane, .notification-content-mobile  .tab-content ul").mCustomScrollbar({
      //     theme: "dark",
      //     contentTouchScroll: true,
      //     mouseWheel:{ scrollAmount: 160 }
      // });

      $(".slide-manage").mCustomScrollbar({
          theme: "dark",
          contentTouchScroll: true,
          mouseWheel:{ scrollAmount: 160 }
      });
    //custom modal create success
       $('#modal-new-location button[data-target="#modal-success-create-location"]').click(function(event) {
          $('#modal-new-location').modal('hide');
      });

       $('#modal-manager-location button[data-target="#modal-add-location-same-sytem"], #modal-manager-location button[data-target="#modal-success-create-location"]').click(function(event) {
          $('#modal-manager-location').modal('hide');
          $('body').addClass('modal-open-custom');
      });

       $('#modal-add-location-same-sytem .btn-done').click(function(event) {
          $('#modal-add-location-same-sytem').modal('show');
      }); 
  }
  function resetProduct(){
    $("#product_name").val('');
    $("#product_price").val('');
    $("#product_image").val('')
    $("#product_image").parent().find('.box-img-upload').html('<div class="box-img-upload-content"><i class="icon-new-white"></i><p>{{trans('Location'.DS.'layout.choose_image')}}</p></div>');
    $("#product_des").val('');
    $("#product_id").val(0);
    $("#product_id").trigger("change");
  }

  function updateProduct(obj){
    resetProduct();
    var data = JSON.parse($(obj).attr('data-json'));
    $("#product_name").val(data.name);
    $("#product_price").val(data.price);
    var html = '<div class="box-img-upload-success"><img src="'+data.image+'"/></div>'
    $("#product_image").parent().find('.box-img-upload').html(html);
    $("#product_des").val(data.description);
    $("#product_id").val(data.id);
    $("#product_id").trigger("change");
  }

  function resetDiscount(){
    $("#discount_manager_name").val('');
    $("#discount_manager_price").val('');
    $("#discount_manager_image").val('')
    $("#discount_manager_image").parent().find('.box-img-upload').html('<div class="box-img-upload-content"><i class="icon-new-white"></i><p>{{trans('Location'.DS.'layout.choose_image')}}</p></div>');
    $("#discount_manager_des").val('');
    $("#discount_manager_id").val(0);
    $("#discount_manager_id").trigger("change");
  }

  function updateDiscount(obj){
    resetDiscount();
    var data = JSON.parse($(obj).attr('data-json'));
    $("#discount_manager_name").val(data.name);
    $("#discount_manager_price").val(data.price);
    var html = '<div class="box-img-upload-success"><img src="'+data.image+'"/></div>'
    $("#discount_manager_image").parent().find('.box-img-upload').html(html);
    $("#discount_manager_des").val(data.description);
    $("#discount_manager_id").val(data.id);
    $("#discount_manager_id").trigger("change");
  }

  function addProduct(){
    $("#product_button").attr("disabled",true);
    var content_id = $("#content_id_create").val();
    var name = $("#product_name").val();
    var price = $("#product_price").val();
    var image = $("#product_image").get(0).files[0];
    var des = $("#product_des").val();
    var product_id = $("#product_id").val();
    var _token = $("meta[name='_token']").prop('content');
    
    var check = false;
    if(!name){
      $("#product_error").text("{{trans('valid.name_product')}}")
      $("#product_button").attr("disabled",false);
      return false;
    }
    if(!price){
      $("#product_error").text("{{trans('valid.price_product')}}")
      $("#product_button").attr("disabled",false);
      return false;
    }
    if(!image && product_id==0){
      $("#product_error").text("{{trans('valid.image_product')}}")
      $("#product_button").attr("disabled",false);
      return false;
    }
    if(!des){
      $("#product_error").text("{{trans('valid.des_product')}}")
      $("#product_button").attr("disabled",false);
      return false;
    }
    check = true;
    var frm = new FormData();
    frm.append('content_id',content_id);
    frm.append('name',name);
    frm.append('price',price);
    if(typeof image !== 'undefined' && image != null){
      frm.append('image',image);
    }
    frm.append('des',des);
    frm.append('product_id',product_id);
    frm.append('_token',_token);
    if(check){
      loadAjax({
        url : '/createLocationFrontend/createProduct',
        type: 'POST',
        data:frm,
        progress:true,
        contentType: false,
        processData: false,
        success:function(res){
          loadManager();
        }
      })
    }
  }

  function addDiscount(){
    $("#discount_manager_button").attr('disabled',true);
    var content_id = $("#content_id_create").val();
    var name = $("#discount_manager_name").val();
    var price = $("#discount_manager_price").val();
    var image = $("#discount_manager_image").get(0).files[0];
    var des = $("#discount_manager_des").val();
    var discount_id = $("#discount_manager_id").val();
    var _token = $("meta[name='_token']").prop('content');


    var frm = new FormData();
    frm.append('content_id',content_id);
    frm.append('name',name);
    frm.append('price',price);
    if(typeof image !== 'undefined' && image != null){
      frm.append('image',image);
    }
    frm.append('des',des);
    frm.append('discount_id',discount_id);
    frm.append('_token',_token);
    var check = false;
    if(!name){
      $("#discount_manager_error").text("{{trans('valid.name_discount')}}");
      $("#discount_manager_button").attr('disabled',false);
        return false;
    }
    if(!price){
      $("#discount_manager_error").text("{{trans('valid.price_discount')}}");
      $("#discount_manager_button").attr('disabled',false);
        return false;
    }
    if(!image && discount_id==0){
      $("#discount_manager_error").text("{{trans('valid.image_discount')}}");
      $("#discount_manager_button").attr('disabled',false);
        return false;
    }
    if(!des){
      $("#discount_manager_error").text("{{trans('valid.des_discount')}}");
      $("#discount_manager_button").attr('disabled',false);
        return false;
    }
    check = true;
    if(check){
      loadAjax({
        url : '/createLocationFrontend/createDiscount',
        type: 'POST',
        progress:true,
        data:frm,
        contentType: false,
        processData: false,
        success:function(res){
          loadManager(1);
        }
      })
    }
  }

  function addBranch(){
    var arr_id = [];
    $(".cate_check_content:checked").each(function(key,elem){
      arr_id.push($(elem).val());
    })
    var content_id = $("#content_id_create").val();
    var _token = $("meta[name='_token']").prop('content');
    if(arr_id.length){
      loadAjax({
        url : '/createLocationFrontend/addBranch',
        type: 'POST',
        data:{
          'arr_id'    :arr_id,
          'content_id':content_id,
          '_token'    :_token,
        },
        success:function(res){
            $('#modal-add-location-same-sytem').one('hidden.bs.modal', function() {
                $('body').addClass('modal-open');
            }).modal('hide');
            $('#modal-manager-location').modal('show');
          // $('#modal-add-location-same-sytem').modal('hide');
          loadManager(2);
        }
      })
    }
  }
  
  function removeProduct(id){
    if(confirm('{{trans('valid.delete_product')}}')){
      loadAjax({
        url : '/createLocationFrontend/removeProduct/'+id,
        type: 'GET',
        success:function(res){
          loadManager();
          resetProduct();
        }
      })
    } 
  }

  function removeDiscount(id){
    if(confirm('{{trans('valid.delete_discount')}}')){
      loadAjax({
        url : '/createLocationFrontend/removeDiscount/'+id,
        type: 'GET',
        success:function(res){
          loadManager(1);
          resetDiscount();
        }
      })
    } 
  }

  function removeBranch(id){
    var content_id = $("#content_id_create").val();
    if(confirm('{{trans('valid.delete_branch')}}')){
      loadAjax({
        url : '/createLocationFrontend/removeBranch',
        type: 'POST',
        data:{
          content_id  : content_id,
          id  : id,
          _token : $("meta[name='_token']").prop('content')
        },
        success:function(res){
          loadManager(2);
        }
      })
    } 
  }

  function resetFormCreate(){
    $("#form-creat-location input[name=id_category]").removeAttr('checked')
    $("#form-creat-location input[name=name]").val('')
    $("#form-creat-location input[name=address_map]").val('')
    $("#form-creat-location #google_map").html('');
    $("#form-creat-location input[name=address]").val('')
    $("#form-creat-location select[name=country]").val('')
    $("#form-creat-location select[name=city]").val('')
    $("#form-creat-location select[name=district]").val('')
    $("#form-creat-location #append_custom_open ").html('')
    $("#form-creat-location input[name=tag]").val('')
    $("#form-creat-location textarea[name=description]").val('')
    
    $("#form-creat-location input[name=wifi]").val('')
    $("#form-creat-location input[name=pass_wifi]").val('')
    $("#form-creat-location input[name=phone]").val('')
    $("#form-creat-location input[name=email]").val('')

    $("#form-creat-location input[type=file]").val();
    $("#form-creat-location .upload-avata").addClass('avata-border');
    $("#form-creat-location .box-img-upload-success").remove();
    $('#form-creat-location #google_map').hide();
    $("#content-video-location-create").html('')
    $("#form-creat-location .text-danger").text("");
    createCaptcha();
    $("#form-creat-location input[name=captcha_code]").val('')
    
    $("#form-creat-location .tab-upload-image-nav li a").eq(0).click();
      $(".choose_hour:even").val("08:00");
      $(".choose_hour:odd").val("22:00");
  }

  $(function(){
    $(window).on('resize', function() {
      $('#modal-add-location-same-sytem').height($(window).innerHeight());
    });
  })
</script>

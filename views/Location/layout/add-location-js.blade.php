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
        $('body').scrollTo("#feedback_avatar");
        return false;
      }
      if(your_cap != my_cap){
        $(".error_captcha").text("{{trans('valid.captcha_wrong')}}");
        createCaptcha();
        return false;
      }
      
      for (var i = 0; i < imageSpace_create.length; i++) {
        data.set('image_space[' + i + ']', imageSpace_create[i]);
      }
      for (var i = 0; i < imageMenu_create.length; i++) {
        data.set('image_menu[' + i + ']', imageMenu_create[i]);
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
            avatar_create = null;
            imageSpace_create = [];
            imageMenu_create = [];
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
      }
      for (var i = 0; i < editimageMenu.length; i++) {
        data.set('image_menu[' + i + ']', editimageMenu[i]);
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

          if(data.status == 'success')
          {
            //location.reload();
            //button.prev().trigger('click');
            $("#modal-update-success").modal();
            $("#update_location_fe").attr("disabled", false);
            @if(Auth::guard('web_client')->user())
            // window.location = {!! json_encode(url('user/'.Auth::guard('web_client')->user()->id.'/management-location/')) !!};
            @endif
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
          var tplavata = $('<div class="box-img-upload box-img-upload-success"><img /></div>');
          var currentLi = ulAvata.find('.upload-avata').html(tplavata);
          data.context = currentLi;
          // data.context = tpl.appendTo(ul);
          // draw preview image
          var reader = new FileReader();
          reader.onloadend = function(e) {
            var tempImg = new Image();
            tempImg.src = reader.result;
            tempImg.onload = function() {
              var max_size = 200; // TODO : pull max size from a site config
              var width = this.width;
              var height = this.height;
              if (width > height) {
                if (width > max_size) {
                  height *= max_size / width;
                  width = max_size;
                }
              } else {
                if (height > max_size) {
                  width *= max_size / height;
                  height = max_size;
                }
              }
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
          var tplavataedit = $('<div class="box-img-upload box-img-upload-success"><img /></div>');
          var currentLi = ulAvataEdit.find('.upload-avata-edit').html(tplavataedit);
          data.context = currentLi;
          // data.context = tpl.appendTo(ul);
          // draw preview image
          var reader = new FileReader();
          reader.onloadend = function(e) {
            var tempImg = new Image();
            tempImg.src = reader.result;
            tempImg.onload = function() {
              var max_size = 200; // TODO : pull max size from a site config
              var width = this.width;
              var height = this.height;
              if (width > height) {
                if (width > max_size) {
                  height *= max_size / width;
                  width = max_size;
                }
              } else {
                if (height > max_size) {
                  width *= max_size / height;
                  height = max_size;
                }
              }
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
          var tpl = $('<div class="box-img-upload box-img-upload-success"><a class="remove-img" data-typename="'+type_image_id+'" data-filename="'+data.files[0].name+'" href="" title=""><i class="icon-cancel"></i></a><img /></div>');
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
              var max_size = 200; // TODO : pull max size from a site config
              var width = this.width;
              var height = this.height;
              if (width > height) {
                if (width > max_size) {
                  height *= max_size / width;
                  width = max_size;
                }
              } else {
                if (height > max_size) {
                  width *= max_size / height;
                  height = max_size;
                }
              }
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
    });
    $("#khong-gian-edit").on('click','.space-remove',function(e){
        e.preventDefault();
        if (confirm('{{trans('Location'.DS.'layout.confirm_delete')}}')) {
            $("#khong-gian-edit").find("a.remove-img").trigger('click');
        }
    });
    $("#menu-edit").on('click','.space-remove',function(e){
        e.preventDefault();
        if (confirm('{{trans('Location'.DS.'layout.confirm_delete')}}')) {
            $("#menu-edit").find("a.remove-img").trigger('click');
        }
    });

    $('.upload-img-post ').on('click','.remove-img',function(event){
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

      if(typename == 'edit_image_spaces')
      {
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

      if(typename == 'edit_image_menu')
      {
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
    });
  });



  $(function() {
    // new location
    $('.btn-step .next-step').click(function() {

      if ($(this).attr("id") == 'button_step2') {
        var _id_category = $('input[name="id_category"]:checked').val();
        if (_id_category) {
          var index = $(this).closest('.tab-pane').index();
          $(this).closest('.tab-pane').removeClass('active');
          $(this).closest('.cread-location').find('.tab-pane').eq(index + 1).addClass('active');
          $(this).closest('.cread-location').find('.nav-item').eq(index).removeClass('highlight').addClass('complete');
          $(this).closest('.cread-location').find('.nav-item').eq(index + 1).removeClass('disabled').addClass('highlight');

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
              }else{
                $("#category_item_require").hide();
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
        else {
          $('#err_id_category').show();
        }
      }

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

            $('body').scrollTo('#product_create');
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
                  if(prop == 'address' || prop == 'lat' || prop == 'lng')
                  {
                    $('#feedback_address').show().html(data.message[prop]);
                    $('#fieldset_feedback_address').addClass('has-danger');
                    $('body').scrollTo('#fieldset_feedback_address');

                  }
                  else if(prop == 'alias' || prop == 'name')
                  {
                    $('#feedback_name').show().html(data.message['name']);
                    $('#fieldset_feedback_name').addClass('has-danger');
                   $('body').scrollTo('#fieldset_feedback_name');
                  }
                  else{
                    $('#feedback_'+prop).show().html(data.message[prop]);
                    $('#fieldset_feedback_'+prop).addClass('has-danger');
                    $('body').scrollTo('#fieldset_feedback_'+prop);
                  }
                }
              }
            }
            else{
              $('.form-control-feedback').hide();
              $("#form-creat-location fieldset").removeClass('has-danger');

              $(current_button).closest('.tab-pane').removeClass('active');
              $(current_button).closest('.cread-location').find('.tab-pane').eq(index + 1).addClass('active');
              $(current_button).closest('.cread-location').find('.nav-item').eq(index).removeClass('highlight').addClass('complete');
              $(current_button).closest('.cread-location').find('.nav-item').eq(index + 1).removeClass('disabled').addClass('highlight');
            }
          }
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
                  if(prop == 'address' || prop == 'lat' || prop == 'lng')
                  {
                    $('#feedback_address_edit').show().html(data.message[prop]);
                    $('#fieldset_feedback_address_edit').addClass('has-danger');

                  }
                  else if(prop == 'alias' || prop == 'name')
                  {
                    $('#feedback_name_edit').show().html(data.message['name']);
                    $('#fieldset_feedback_name_edit').addClass('has-danger');
                  }
                  else{
                    $('#feedback_'+prop+'_edit').show().html(data.message[prop]);
                    $('#fieldset_feedback_'+prop+'_edit').addClass('has-danger');
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
    $('.form-creat-location .btn-addvideo').click(function(event) {
      event.preventDefault();
      $(this).closest('#video').find(' .form-group').append('<div><input type="text" name="link[]" class="form-control input-link-video" onchange="loadThumb(this)" placeholder="https://www.youtube.com...."><i class="fa fa-remove remove-video" onclick="removeVideo(this)"></i></div>')
    });
    //add link youtobe edit
    $('.form-creat-location .btn-addvideo').click(function(event) {
      event.preventDefault();
      $(this).closest('#video-edit').find(' .form-group').append('<div><input type="text" name="link[]" class="form-control input-link-video" onchange="loadThumb(this)" placeholder="https://www.youtube.com...."><i class="fa fa-remove remove-video" onclick="removeVideo(this)"></i></div>')
    });
    // $('.box-location-add-offer a').click(function(e){
    //     e.preventDefault();
    //     $('.form-add-offer-location').slideToggle('400');
    // });
    //tag Automatic
    $(function(){
      $('.tokeHastag').tagsInput({
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
        }
      });

      $("div.tagsinput input").on('paste',function(e){
          var element=this;
          setTimeout(function () {
              var text = $(element).val();
              var target=$(".tokeHastag");
              var tags = (text).split(/[,]+/);
              for (var i = 0, z = tags.length; i<z; i++) {
                    var tag = $.trim(tags[i]);
                    if (!target.tagExist(tag)) {
                          target.addTag(tag);
                    }
                    else
                    {
                        $("div.tagsinput input").val('');
                    }
               }
          }, 0);
      });
      $("div.tagsinput input").on('textInput',function(e){
          var element=this;
          setTimeout(function () {
            var text = $(element).val();
            var target=$(".tokeHastag");
            if(text.indexOf(',') > -1){
              var tag = text.replace(',','');
              if (!target.tagExist(tag)) {
                  target.addTag(tag);
              }else{
                  $("div.tagsinput input").val('');
                  $("div.tagsinput input").focus();
              }
            }
          },10);
      });
      
      // $("div.tagsinput input").keyup(function(e){
      //   alert(event.keyCode);
      // });


      // $(".tokeHastag").select2({
      //   tags: true,
      //   placeholder: "{!! trans('Location'.DS.'layout.place_tags') !!}",
      //   tokenSeparators: ['/',',',';'],
      //   maximumSelectionLength: 10
      // }).on("change",function(e){
      //     if($(this).val().length>10){
      //         $(this).val($(this).val().slice(0,10));
      //         alert("Chỉ được chọn tối đa 10 từ khóa");
      //     }
      // });
    })
  });



  var geocoder_create_location = new google.maps.Geocoder();
  var marker_create_location = new google.maps.Marker();
  var infowindow_create_location = new google.maps.InfoWindow({
    size: new google.maps.Size(150, 50)
  });

  function initialize_create_location() {
    var latLng_create_location = new google.maps.LatLng(10.773234, 10.773234);
    map_create_location = new google.maps.Map(document.getElementById('google_map'), {
      zoom: 15,
      center: latLng_create_location,
      zoomControl: true,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      draggable: true
    });

    var input = document.getElementById('address');
    autocomplete = new google.maps.places.Autocomplete(input);

    google.maps.event.addListener(autocomplete, 'place_changed', function () {
      codeAddress(autocomplete.getPlace().formatted_address);
    });

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
        for (var i = 0; i < results[0].address_components.length; i++) {
          if (results[0].address_components[i].types[0] == 'street_number') {
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
        $("#address").val('');
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
    $(obj).parent().remove();
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

    console.log(sessionStorage.category,sessionStorage.service);
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

  $(function () {
    sessionStorage.category = [];
    sessionStorage.service = [];
    $("#form-creat-location #name").on("keyup", function () {
      var name = $(this).val();
      $("#form-creat-location #alias").val(str_slug(name));
    })

    $("#phone").on("blur", function (e) {
      var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,4})(\d{0,4})/);
      e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
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
    html+='<input type="text" class="form-control" name="product['+index_group+']['+index+'][name]"    placeholder="{{trans('Admin'.DS.'content.name')}}" required="required">';
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
    html+='<input class="form-control" type="text" name="product['+index+'][group_name]" required="required"/>';
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
    html+='<input type="text" class="form-control" name="product['+index+'][1][name]" placeholder="Tên" required="required">';
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

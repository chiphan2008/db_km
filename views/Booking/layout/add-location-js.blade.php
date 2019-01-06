<script type="text/javascript">
  var avatar_create;
  var imageSpace_create = [];
  var imageMenu_create = [];

  var editAvatar;
  var editimageSpace = [];
  var editimageMenu = [];
  var captcha_code = "";

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

    $("#create_location_fe").on("click", function () {
      $("#create_location_fe").attr("disabled", true);
      your_cap = $('#captcha_code').val().toLowerCase();
      my_cap = captcha_code.toLowerCase();
      if(your_cap != my_cap){
        $("#create_location_fe").removeAttr("disabled");
        $(".error_captcha").text("{{trans('valid.captcha_wrong')}}");
      }else{
        var form = $('#form-creat-location')[0]; // You need to use standard javascript object here
        var data = new FormData(form);
        data.append('_token', $("meta[name='_token']").prop('content'));
        data.append('avatar', avatar_create);
        for (var i = 0; i < imageSpace_create.length; i++) {
          data.append('image_space[' + i + ']', imageSpace_create[i]);
        }
        for (var i = 0; i < imageMenu_create.length; i++) {
          data.append('image_menu[' + i + ']', imageMenu_create[i]);
        }

        $.ajax({
          type: "POST",
          cache: false,
          contentType: false,
          processData: false,
          data: data,
          url: '/createLocationFrontend/postCreateLocation',
          success: function (data) {

            if(data.status == 'success')
            {
              location.reload();
              {{--window.location = {!! json_encode(url('edit/location/'.Auth::guard('web_client')->user()->id)) !!};--}}
            }
          }
        })
      }
    });

    $("#update_location_fe").on("click", function () {
      $("#update_location_fe").attr("disabled", true);

      var form = $('#form-creat-location-edit')[0];
      var data = new FormData(form);
      data.append('_token', $("meta[name='_token']").prop('content'));
      if(editAvatar !== undefined)
      {
        data.append('avatar', editAvatar);
      }
      for (var i = 0; i < editimageSpace.length; i++) {
        data.append('image_space[' + i + ']', editimageSpace[i]);
      }
      for (var i = 0; i < editimageMenu.length; i++) {
        data.append('image_menu[' + i + ']', editimageMenu[i]);
      }

      $.ajax({
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        url: '/editLocationFrontend/postEditLocation',
        success: function (data) {

          if(data.status == 'success')
          {
            location.reload();
            {{--window.location = {!! json_encode(url('edit/location/'.Auth::guard('web_client')->user()->id)) !!};--}}
          }
        }
      })
    });

    $("#preview_location_fe").on("click",function(){


    	var form = $('#form-creat-location')[0];
    	var data = new FormData(form);

      data.append('_token', $("meta[name='_token']").prop('content'));
      data.append('avatar', avatar_create);
      for (var i = 0; i < imageSpace_create.length; i++) {
        data.append('image_space[' + i + ']', imageSpace_create[i]);
      }
      for (var i = 0; i < imageMenu_create.length; i++) {
        data.append('image_menu[' + i + ']', imageMenu_create[i]);
      }
			$.ajax({
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				data: data,
				url: '/createLocationFrontend/previewLocation',
				success: function (data) {
					if(data.type){
						window.open('/createLocationFrontend/previewLocation',"_blank");
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
        var avatar_type_id = $(this).attr("id");
        if(avatar_type_id == 'avatar_create')
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
        console.log(uploadFileAvataEdit);
        editAvatar = uploadFileAvataEdit;
        if (goUpload) {
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
    $('.upload-placeholder').mCustomScrollbar({
      theme: "dark"
    });
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
        var type_image_id = $(this).attr("id");
        if(type_image_id == 'image_khong_gian_create')
        {
          imageSpace_create.push(data.files[0]);
        }
        if(type_image_id == 'image_menu_create')
        {
          imageMenu_create.push(data.files[0]);
        }
        if(type_image_id == 'edit_image_khong_gian')
        {
          editimageSpace.push(data.files[0]);
        }
        if(type_image_id == 'edit_image_menu')
        {
          editimageMenu.push(data.files[0]);
        }
        if (uploadDing >= 1) {
          $('.upload-img-post').removeClass('upload-placeholder');
        }
        if (goUpload) {
          uploadDing++;
          var tpl = $('<div class="box-img-upload box-img-upload-success"><a class="remove-img" data-typename="'+type_image_id+'" data-filename="'+data.files[0].name+'" href="" title=""><i class="icon-cancel"></i></a><img /></div>');
          var currentLi = ul.find('.upload-begin').parent().html(tpl);
          // currentLi.removeClass('upload-begin upload-image-disabled').addClass('');
          currentLi.before('<li class="col-md-4 col-6"><div class="box-img-upload upload-begin upload-image-disabled "><div class="box-img-upload-content"><i class="icon-new-white"></i><p>chọn hình ảnh</p></div></div></li>');
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
        }
      },
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

          $.ajax({
            type: "POST",
            data: {id_category: _id_category, _token: $("meta[name='_token']").prop('content')},
            url: '/createLocationFrontend/StepOne',
            success: function (data) {

              var obj = jQuery.parseJSON(data);
              $("#label_name_category").text(obj.category_name);
              $("#list_category_item").html(obj.list_category_item);

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
        data.append('_token', $("meta[name='_token']").prop('content'));
        if(avatar_create !== undefined)
        {
          data.append('avatar', avatar_create);
        }

        $.ajax({
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

                  }
                  else if(prop == 'alias' || prop == 'name')
                  {
                    $('#feedback_name').show().html(data.message['name']);
                    $('#fieldset_feedback_name').addClass('has-danger');
                  }
                  else{
                    $('#feedback_'+prop).show().html(data.message[prop]);
                    $('#fieldset_feedback_'+prop).addClass('has-danger');
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
        data.append('_token', $("meta[name='_token']").prop('content'));
        if(editAvatar !== undefined)
        {
          data.append('avatar', editAvatar);
        }

        $.ajax({
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
      $(this).closest('#video').find(' .form-group label').after('<input type="text" name="link[]" class="form-control  mb-3" placeholder="https://www.youtube.com....">')
    });
    //add link youtobe edit
    $('.form-creat-location .btn-addvideo').click(function(event) {
      event.preventDefault();
      $(this).closest('#video-edit').find(' .form-group label').after('<input type="text" name="link[]" class="form-control  mb-3" placeholder="https://www.youtube.com....">')
    });
    //tag Automatic
    $(".tokeHastag").select2({
      tags: true,
      placeholder: "{{trans('Location'.DS.'layout.place_tags')}}",
      tokenSeparators: ['/',',',';']
    });
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
      $('#district').html('<option value="">-- District --</option>');
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

  $(function () {
    $("#form-creat-location #name").on("keyup", function () {
      var name = $(this).val();
      $("#form-creat-location #alias").val(str_slug(name));
    })

    $("#phone").on("blur", function (e) {
      var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,4})(\d{0,4})/);
      e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    });
  })
</script>

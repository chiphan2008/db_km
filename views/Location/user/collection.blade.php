<div class="overlay-mobile"></div>
<div class="content-edit-profile-manager">
  <h3>{{mb_strtoupper(trans('Location'.DS.'user.collection'))}} ({{$total?$total:0}})</h3>
  @if($collections)
  <div class="list-content-profile">
  		@foreach($collections as $collection)
      <div class="box-gallery-collection box-gallery" id="box-gallery-{{$collection->id}}">
          <div class="title-gallery d-flex justify-content-between align-items-start align-items-center mb-3">
            <label class="editable mr-2 mb-0 label-name">{{$collection->name}}</label>
            @if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
            <input class="editable mr-2 form-control w-50 input-name" data-id="{{$collection->id}}" type="text" name="name" style="display:none"/>
            <a class="title-edit mr-auto" href=""><i class="icon-ic-edit"></i></a>
            @endif
             
              <div class="dropdown-action-gallery dropdown ">
                <a  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="icon-more"></i>
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                  @if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
                    <a class="dropdown-item btn-collection-edit" data-done="0" href="#">{{trans('global.edit')}}</a>
                    <a class="dropdown-item" href="#" onclick="modalDelete({{$collection->id}})">{{trans('global.delete')}}</a>
                  @endif
                  <a class="dropdown-item btn-collection-viewall" href="#">{{trans('global.view_all')}}</a>
                  <!-- <a class="dropdown-item" href="#">Xem tất cả</a> -->
                </div>
              </div>
              
          </div>
          @if($collection->_contents)
          <div class="box-list-gallery">
              <ul class="list-gallery list-unstyled ">
              	@foreach($collection->_contents as $content)
                  <li class="item-gallery mb-4" id="item-{{$content->id}}-{{$collection->id}}">
                      <a href="{{LOCATION_URL}}/{{$content->alias}}" title="{{$content->name}}">
                          <img class="img-fluid" src="{{str_replace('img_content','img_content_thumbnail',$content->avatar)}}" alt="">
                          <h6>{{$content->name}}</h6>
                      </a>
                      <a class="remove-collection" href="" data-id-content="{{$content->id}}" data-id-collection="{{$collection->id}}"><i class="icon-cancel"></i></a>
                  </li>
                @endforeach
              </ul>
          </div>
          @endif
          <!-- end list-gallery -->
      </div>
      <!-- end box gallery -->
      @endforeach
  </div>
  @endif
    @if($collections)
        <div class="col-sm-12">
            {!! $collections->appends(request()->query())->links('vendor.pagination.bootstrap-4') !!}
        </div>
    @endif
</div>
<div id="modal-submit-remove" class="modal modal-vertical-middle fade modal-submit-payment  modal-vertical-middle modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment modal-vertical-middle modal-vertical-middle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content text-center">
      <div class="modal-logo pt-4 text-center">
        <img src="/frontend/assets/img/logo/logo-large.svg" alt="">
      </div>
      <!-- <h4>XÁC NHẬN</h4> -->
      <!-- <hr> -->
      <p id="text_push">{{trans('Location'.DS.'user.confirm_remove_collection')}}</p>
      <div class="modal-button justify-content-between">
        <a class="btn btn-secorady" href="#" data-dismiss="modal">{{trans('global.cancel')}}</a>
        <a class="btn btn-primary" onclick="removeCollection(this)" id="data_remove" data-id-content=""  data-id-collection="" href="#">{{trans('global.ok')}}</a>
      </div>
    </div>
  </div>
</div>
<div id="modal-submit-delete" class="modal modal-vertical-middle fade modal-submit-payment  modal-vertical-middle modal-report" tabindex="-1" role="dialog" aria-labelledby="modal-submit-payment modal-vertical-middle modal-vertical-middle" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content text-center">
      <div class="modal-logo pt-4 text-center">
        <img src="/frontend/assets/img/logo/logo-large.svg" alt="">
      </div>
      <!-- <h4>XÁC NHẬN</h4> -->
      <!-- <hr> -->
      <p id="text_push">{{trans('Location'.DS.'user.confirm_delete_collection')}}</p>
      <div class="modal-button justify-content-between">
        <a class="btn btn-secorady" href="#" data-dismiss="modal">{{trans('global.cancel')}}</a>
        <a class="btn btn-primary" onclick="deleteCollection(this)" id="data_delete"  data-id-collection="" href="#">{{trans('global.ok')}}</a>
      </div>
    </div>
  </div>
</div>
<!-- end profile-page  -->
@section('JS')
	@if(Auth::guard('web_client')->user())
		@include('Location.user.crop_image')
	@endif

<script type="text/javascript">
$(function() {

    //scroll
    $(window).on("load resize",function(){
        var winWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
        if(winWidth>767){
            $(".list-content-profile").mCustomScrollbar({
                theme: "dark",
                contentTouchScroll: true,
                mouseWheel:{ scrollAmount: 160 }
            });
        }else{
             $(".list-content-profile").mCustomScrollbar("destroy");
        }
    });
    
    $(".list-content-profile").mCustomScrollbar({
        theme: "dark",
        contentTouchScroll: true,
        mouseWheel:{ scrollAmount: 160 }
    });
    //click nav mobile
    // $('.nav-manager-profile li.active').click(function(event) {
    //     event.preventDefault();
    //     $('.sub-nav-manager-profile').slideToggle('fast');
    // });
    //active option payment
    $('.option-payment .item-number-card a').click(function(event) {
        event.preventDefault();
        $('.option-payment .item-number-card').removeClass('active');
        $(this).closest('.item-number-card').addClass('active');
    });

    //page collection
    // $('.more-gallery a').click(function(event) {
    //     event.preventDefault();
    //     var i;
    //     for (i = 0; i < 5 ; ++i) {
    //         $(this).closest('.list-gallery').prepend('<li class="item-gallery mb-4"><a href="" title=""><img class="img-fluid" src="https://via.placeholder.com/170x117" alt=""><h6>Yellow Cab Pizza Vietnam</h6></a></li>');
    //     }
        
    // });
    $(".box-list-gallery .list-gallery").mCustomScrollbar({
        axis: "x",
        theme: "dark",
        contentTouchScroll: true,
        scrollButtons: {
            enable: true,
            scrollType: "stepped",
            scrollAmount: 500
        },
        advanced: {autoExpandHorizontalScroll: true}
    });
    $('.btn-collection-edit').click(function(event) {
        event.preventDefault();
        var done = $(this).attr('data-done');
        if(done==0){
          $(this).closest('.box-gallery-collection').find('.item-gallery').addClass('edit');
          $(this).text("{{trans('global.done')}}")
          $(this).attr('data-done',1);
        }else{
          $(this).closest('.box-gallery-collection').find('.item-gallery').removeClass('edit');
          $(this).text("{{trans('global.edit')}}")
          $(this).attr('data-done',0);
        }
        
    });
    //delete collection
    $('.remove-collection').click(function(event) {
        event.preventDefault();
        var id_content = $(this).attr('data-id-content');
        var id_collection = $(this).attr('data-id-collection');
        modalRemove(id_content,id_collection);
        // $(this).closest('.item-gallery').remove();
    });

    //edit title
     $(".title-edit").click(function(event){
        event.preventDefault();
        var text = $(this).find('i');
        var label = $(this);
        if(text.hasClass('icon-ic-edit')){
          text.removeClass('icon-ic-edit');
          text.addClass('fa fa-save');
          var name = $(this).parent().find('.label-name').text();
          $(this).parent().find('.input-name').val(name);
        }
        else{
          var name = $(this).parent().find('.input-name').val();
          var id = $(this).parent().find('.input-name').attr('data-id');
          $.ajax({
            url:'/collection/updateCollection',
            type:'POST',
            data:{
              collection_id : id,
              name : name,
              _token: $('meta[name="_token"]').attr('content')
            },
            success:function(res){
              if (res.error == 0) {
                toastr.info(res.message);
                label.parent().find('.label-name').text(name);
              } else {
                toastr.warning(res.message);
              }
              text.removeClass('fa fa-save');
              text.addClass('icon-ic-edit');
            }
          });
        }
        setTimeout(function(){
          label.closest('.title-gallery').find(".editable").toggle();
        }(label),200)
    });

    $("input.editable").change(function(){
        $(this).prev().text($(this).text());

    });
    //view all collection
    $('.btn-collection-viewall').click(function(event) {
        event.preventDefault();
        $(this).closest('.box-gallery-collection').find('.list-gallery').css('min-width', 'auto');
        $(this).closest('.box-gallery-collection').find('.list-gallery').mCustomScrollbar('destroy');
    });
});

function modalRemove(id_content,id_collection){
  $("#modal-submit-remove").modal('show');
  $("#data_remove").attr('data-id-content',id_content);
  $("#data_remove").attr('data-id-collection',id_collection);
  
}

function modalDelete(id_collection){
  $("#modal-submit-delete").modal('show');
  $("#data_delete").attr('data-id-collection',id_collection);
  
}

function removeCollection(obj){
  var id_collection = $(obj).attr('data-id-collection');
  var id_content = $(obj).attr('data-id-content');
  $.ajax({
    url:'/collection/removeCollection',
    type:'POST',
    data:{
      collection_id : id_collection,
      content_id  : id_content,
      _token: $('meta[name="_token"]').attr('content')
    },
    success:function(res){
      if (res.error == 0) {
        toastr.info(res.message);
        $("#item-"+id_content+'-'+id_collection).remove();
      } else {
        toastr.warning(res.message);
      }
      $("#modal-submit-remove").modal('hide');
    }
  });
}

function deleteCollection(obj){
  var id_collection = $(obj).attr('data-id-collection');
  $.ajax({
    url:'/collection/deleteCollection',
    type:'POST',
    data:{
      collection_id : id_collection,
      _token: $('meta[name="_token"]').attr('content')
    },
    success:function(res){
      if (res.error == 0) {
        toastr.info(res.message);
        $("#box-gallery-"+id_collection).remove();
      } else {
        toastr.warning(res.message);
      }
      $("#modal-submit-delete").modal('hide');
    }
  });
}
</script>
@endsection
<div class="content-edit-profile-manager">
  <h3>{{mb_strtoupper(trans('Location'.DS.'user.location'))}} ({{$total}})</h3>
  @if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
  <div class="profile-manager-empty text-center mt-3">
    <p>
      {{trans('Location'.DS.'user.empty_1')}}<br>
      {{trans('Location'.DS.'user.empty_2')}}
    </p>
    
    <a class="btn-create btn btn-primary" data-toggle="modal" data-target="#modal-new-location" href="" title="Tạo địa điểm">
      <i class="icon-new-white"></i>
      {{trans('Location'.DS.'user.empty_3')}}
    </a>
  </div>
  @endif
</div>
@section('JS')
  @if(Auth::guard('web_client')->user() && Auth::guard('web_client')->user()->id == $user->id)
    @include('Location.user.crop_image')
  @endif
@endsection
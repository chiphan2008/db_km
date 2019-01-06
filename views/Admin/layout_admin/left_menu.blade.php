<div class="col-md-3 left_col">
  <div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;">
      <a href="{{url('/')}}/admin" class="site_title"><img class="image_leftmenu" src="/frontend/assets/img/logo/logo-icon.png" alt="King Map"> <span>KingMap</span></a>
    </div>

    <div class="clearfix"></div>

    <!-- menu profile quick info -->
    <div class="profile clearfix">
      <div class="profile_pic">
        {{--<img src="images/img.jpg" alt="..." class="img-circle profile_img">--}}
        <img width="56px" height="56px" src="{{asset('img_user/'.Auth::guard('web')->user()->avatar)}}" alt=""
             class="img-circle profile_img">
      </div>
      <div class="profile_info">
        <span>Welcome</span>
        <h2>{{Auth::guard('web')->user()->full_name}}</h2>
      </div>
    </div>
    <!-- /menu profile quick info -->

    <br/>
    
    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
       <!--  <h3>General</h3> -->
        <ul class="nav side-menu">
          {!! $sidebar !!}
        </ul>
      </div>
    </div>
    <!-- /sidebar menu -->

    <!-- /menu footer buttons -->
    <div class="sidebar-footer hidden-small">
      <div class="form-inline col-xs-12">
        <div class="form-group">
          <label class="label-control">{{trans('global.language')}} </label>
          <select onchange="changeLanguage(this)">
            <option value="vn" {{\App::getLocale() == 'vn' ? 'selected' : ''}}>Tiếng Việt</option>
            <option value="en" {{\App::getLocale() == 'en' ? 'selected' : ''}}>English</option>
          </select>
        </div>
      </div>
    </div>
    <!-- /menu footer buttons -->
  </div>
</div>

<style type="text/css">
  .child_menu > li > a {
    display: inline !important;
    line-height: 35px;
  }
</style>
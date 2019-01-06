<nav id="mp-menu" class="mp-menu">
    <div class="mp-level">
      <div class="wrap-main-navigation-mobile wrap-main-navigation clearfix hidden-md-up">
        <div class="container">
          <div class="content-menu">
            <div class="menu-location-mobile">
              <a class="d-flex align-items-center justify-content-between" href="#" title="{{trans('Location'.DS.'layout.location')}}"><span>{{trans('Location'.DS.'layout.location')}}</span>
                <i class="icon-circle"></i>
              </a>
            </div>
            <nav class="main-navigation">
              <ul class="main-menu list-unstyled">
                @if(isset($menu[0]))
                  @foreach($menu[0] as $k => $sub_menu)
                    @if(isset($menu[$sub_menu['id']]))
                      @foreach($menu[$sub_menu['id']] as $value)
                        <li>
                          <a href="{{$value['link']}}" title="">{{app('translator')->getFromJson($value['name'])}}</a>
                        </li>
                      @endforeach
                    @endif
                  @endforeach
                @endif
              </ul>
            </nav>
            <div class="more-nav-mobile">
              <div class="select-lang">
                <select class="custom-select" onchange="changeLanguage(this)">
                  <option {{\App::getLocale() == 'vn' ? 'selected' : ''}} value="vn">VIE</option>
                  <option {{\App::getLocale() == 'en' ? 'selected' : ''}} value="en">ENG</option>
                </select>
              </div>
              <!-- end lang-header -->
            </div>
            <!-- end more nav mobile -->
          </div>
        </div>
      </div>
    </div>
</nav>
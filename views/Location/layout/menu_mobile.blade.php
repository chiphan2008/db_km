<nav id="mp-menu" class="mp-menu">
    <div class="mp-level">
      <div class="wrap-main-navigation-mobile wrap-main-navigation clearfix hidden-md-up">
        <div class="container">
          <div class="content-menu">
            @if(isset($menu[0]))
              @foreach($menu[0] as $k => $sub_menu)
                @if(isset($menu[$sub_menu['id']]))
                  @php
                    $check = true;
                    $count_child = 0;
                    foreach($menu[$sub_menu['id']] as $value){
                      $check = $check && isset($menu[$value['id']]);
                      if(isset($menu[$value['id']])){
                        $count_child++;
                      }
                    }
                  @endphp
                  <!-- <div class="menu-location-mobile">
                    <a class="btn btn-primary btn-block" data-toggle="collapse" href="#menuCollape_{{$sub_menu['id']}}" aria-expanded="false" aria-controls="menuCollape_{{$sub_menu['id']}}"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      @lang($sub_menu['name'])
                       <i class="icon-circle"></i> 
                    </a>
                  </div> -->
                  <!-- <nav class="main-navigation collapse" id="menuCollape_{{$sub_menu['id']}}">
                    <ul class="main-menu list-unstyled">
                        @foreach($menu[$sub_menu['id']] as $value)
                          @if($check)
                            @foreach($menu[$value['id']] as $value2)
                              <li>
                                <a href="{{$value2['link']}}">{{app('translator')->getFromJson($value2['name'])}}</a>
                              </li>
                            @endforeach
                          @else
                              <li>
                                <a href="{{$value['link']}}">{{app('translator')->getFromJson($value['name'])}}</a>
                              </li>
                          @endif
                        @endforeach
                    </ul>
                  </nav> -->
                @endif
              @endforeach
            @endif
        <!--<div class="menu-location-mobile">
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
            </nav> -->
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
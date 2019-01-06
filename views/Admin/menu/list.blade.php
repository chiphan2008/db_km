@extends('Admin.layout_admin.master_admin')

@section('content')
<div class="row">
  <div class="col-md-6" id="menu-order">
    <div class="portlet tab-pane">
      <div class="portlet-title">
        <div class="caption">
          <span class="header">{{ trans('Admin'.DS.'menu.menu_order') }}</span>
        </div>
        <div class="tools">
          <a href="javascript:;" class="fullscreen">
          </a>
        </div>
      </div>
      <div class="portlet-body tabbable-custom">
        <form id='form-reorder' >
        {{ csrf_field() }}
        <div id="menu-order-content">
          <ul class="nav nav-tabs">
            @foreach($arrMenu as $type => $value)
            <li <?php if(!isset($active)){ echo 'class="active"'; $active = 1; } ?>><a href="#tab-{{$type}}" data-toggle="tab">{{ ucfirst($type) }}</a></li>
            @endforeach
            <?php unset($active); ?>
          </ul>
          <div class="tab-content">
            @foreach($arrMenu as $type => $menu)
            <div id="{{ 'tab-'.$type }}" class=" dd tab-pane <?php if(!isset($active)){ echo 'active'; $active = 1; } ?>" style="min-height: 325px;">
              {!! $menu !!}
              <input type="hidden" id="{{$type}}_store" name="{{$type}}_store" value="" />
            </div>
            @endforeach
          </div>
        </div>
        <div class="form-actions">
          <div class="col-md-12">
            <div class="row">
              <div class="text-right">
                @if(Auth::guard('web')->user()->can('edit_Menu'))
                <button id="reorder" type="submit" class="btn btn-primary">{{ trans('Admin'.DS.'menu.re_order') }}</button>
                @endif
              </div>
            </div>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
  @if(Auth::guard('web')->user()->can('add_Menu') || Auth::guard('web')->user()->can('edit_Menu'))
  <div class="col-md-6">
    <div class="col-md-12" id="menu-info">
      <div class="portlet tab-pane">
        <div class="portlet-title">
          <div class="caption">
            <span class="header">{{ trans('Admin'.DS.'menu.menu_info') }}</span>
          </div>
          <div class="pull-right add_button">
            @if(Auth::guard('web')->user()->can('add_Menu'))
              <button class="btn btn-primary" id="add-menu">{{ trans('Admin'.DS.'menu.add_menu') }}</button>
            @endif
          </div>
        </div>
        <div class="portlet-body form">
          <form id="update-menu" class="form-horizontal" role="form" id="menu-info">
             {{ csrf_field() }}
            <div class="form-body">
              <input type="hidden" id="id" name="id" value="0">
              <div class="form-group">
                <label class="col-md-3 control-label">{{ trans('Admin'.DS.'menu.name') }}</label>
                <div class="col-md-9">
                  <input type="text" class="form-control" id="name" name="name" placeholder="">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">{{ trans('Admin'.DS.'menu.type') }}</label>
                <div class="col-md-9">
                  <select class="form-control" name="type" id="type">
                    <option value="backend">BackEnd</option>
                    <option value="frontend">Frontend</option>
                  </select>
                </div>
              </div>
              <div class="form-group" id="module-div">
                <label class="col-md-3 control-label">{{ trans('Admin'.DS.'menu.module') }}</label>
                <div class="col-md-9">
                  <select class="form-control" name="module" id="module" onchange="changeModule(this)">
                    <option value="location">Location</option>
                    <option value="booking">Booking</option>
                    <option value="discount">Discount</option>
                    <option value="ads">Advertisement</option>
                    <option value="raovat">Rao vặt</option>
                    <option value="showroom">Showroom</option>
                  </select>
                </div>
              </div>
              <div class="form-group" id="icon-image-div">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">{{trans('Admin'.DS.'category.image')}}</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                    <div class="imgupload panel panel-default">
                      <div class="panel-heading clearfix">
                        <h3 class="panel-title pull-left">{{trans('Admin'.DS.'category.upload_image')}}</h3>
                      </div>
                      <div class="file-tab panel-body image">
                        <div>
                          <a type="button" class="btn btn-default btn-file" style="margin-bottom: 15px;">
                          <span>{{trans('Admin'.DS.'category.browse')}}</span>
                          <input type="file" name="image" id="icon_img" accept="image/*">
                          </a>
                          <button type="button" class="btn btn-default">{{trans('Admin'.DS.'category.remove')}}</button>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
              <div class="form-group" id="icon-div">
                <label class="col-md-3 control-label">{{ trans('Admin'.DS.'menu.icon') }}</label>
                <div class="col-md-9">
                  <div class="input-inline" style="width:100%;">
                    <div class="input-group col-md-12">
                    <select class="form-control" name="icon_class" id="icon_class" data-live-search="true" data-size="6" data-dropup-auto="false">
                      <option value="fa-adjust" data-icon="fa-adjust">Adjust</option>
                      <option value="fa-adn" data-icon="fa-adn">Adn</option>
                      <option value="fa-align-center" data-icon="fa-align-center">Align center</option>
                      <option value="fa-align-justify" data-icon="fa-align-justify">Align justify</option>
                      <option value="fa-align-left" data-icon="fa-align-left">Align left</option>
                      <option value="fa-align-right" data-icon="fa-align-right">Align right</option>
                      <option value="fa-amazon" data-icon="fa-amazon">Amazon</option>
                      <option value="fa-ambulance" data-icon="fa-ambulance">Ambulance</option>
                      <option value="fa-american-sign-language-interpreting" data-icon="fa-american-sign-language-interpreting">American sign language interpreting</option>
                      <option value="fa-anchor" data-icon="fa-anchor">Anchor</option>
                      <option value="fa-android" data-icon="fa-android">Android</option>
                      <option value="fa-angellist" data-icon="fa-angellist">Angellist</option>
                      <option value="fa-angle-double-down" data-icon="fa-angle-double-down">Angle double down</option>
                      <option value="fa-angle-double-left" data-icon="fa-angle-double-left">Angle double left</option>
                      <option value="fa-angle-double-right" data-icon="fa-angle-double-right">Angle double right</option>
                      <option value="fa-angle-double-up" data-icon="fa-angle-double-up">Angle double up</option>
                      <option value="fa-angle-down" data-icon="fa-angle-down">Angle down</option>
                      <option value="fa-angle-left" data-icon="fa-angle-left">Angle left</option>
                      <option value="fa-angle-right" data-icon="fa-angle-right">Angle right</option>
                      <option value="fa-angle-up" data-icon="fa-angle-up">Angle up</option>
                      <option value="fa-apple" data-icon="fa-apple">Apple</option>
                      <option value="fa-archive" data-icon="fa-archive">Archive</option>
                      <option value="fa-area-chart" data-icon="fa-area-chart">Area chart</option>
                      <option value="fa-arrow-circle-down" data-icon="fa-arrow-circle-down">Arrow circle down</option>
                      <option value="fa-arrow-circle-left" data-icon="fa-arrow-circle-left">Arrow circle left</option>
                      <option value="fa-arrow-circle-o-down" data-icon="fa-arrow-circle-o-down">Arrow circle down</option>
                      <option value="fa-arrow-circle-o-left" data-icon="fa-arrow-circle-o-left">Arrow circle left</option>
                      <option value="fa-arrow-circle-o-right" data-icon="fa-arrow-circle-o-right">Arrow circle right</option>
                      <option value="fa-arrow-circle-o-up" data-icon="fa-arrow-circle-o-up">Arrow circle up</option>
                      <option value="fa-arrow-circle-right" data-icon="fa-arrow-circle-right">Arrow circle right</option>
                      <option value="fa-arrow-circle-up" data-icon="fa-arrow-circle-up">Arrow circle up</option>
                      <option value="fa-arrow-down" data-icon="fa-arrow-down">Arrow down</option>
                      <option value="fa-arrow-left" data-icon="fa-arrow-left">Arrow left</option>
                      <option value="fa-arrow-right" data-icon="fa-arrow-right">Arrow right</option>
                      <option value="fa-arrow-up" data-icon="fa-arrow-up">Arrow up</option>
                      <option value="fa-arrows" data-icon="fa-arrows">Arrows</option>
                      <option value="fa-arrows-alt" data-icon="fa-arrows-alt">Arrows alt</option>
                      <option value="fa-arrows-h" data-icon="fa-arrows-h">Arrows h</option>
                      <option value="fa-arrows-v" data-icon="fa-arrows-v">Arrows v</option>
                      <option value="fa-asl-interpreting" data-icon="fa-asl-interpreting">Asl interpreting</option>
                      <option value="fa-assistive-listening-systems" data-icon="fa-assistive-listening-systems">Assistive listening systems</option>
                      <option value="fa-asterisk" data-icon="fa-asterisk">Asterisk</option>
                      <option value="fa-at" data-icon="fa-at">At</option>
                      <option value="fa-audio-description" data-icon="fa-audio-description">Audio description</option>
                      <option value="fa-automobile" data-icon="fa-automobile">Automobile</option>
                      <option value="fa-backward" data-icon="fa-backward">Backward</option>
                      <option value="fa-balance-scale" data-icon="fa-balance-scale">Balance scale</option>
                      <option value="fa-ban" data-icon="fa-ban">Ban</option>
                      <option value="fa-bank" data-icon="fa-bank">Bank</option>
                      <option value="fa-bar-chart" data-icon="fa-bar-chart">Bar chart</option>
                      <option value="fa-bar-chart-o" data-icon="fa-bar-chart-o">Bar chart</option>
                      <option value="fa-barcode" data-icon="fa-barcode">Barcode</option>
                      <option value="fa-bars" data-icon="fa-bars">Bars</option>
                      <option value="fa-battery-empty" data-icon="fa-battery-empty">Battery empty</option>
                      <option value="fa-battery-full" data-icon="fa-battery-full">Battery full</option>
                      <option value="fa-battery-half" data-icon="fa-battery-half">Battery half</option>
                      <option value="fa-battery-quarter" data-icon="fa-battery-quarter">Battery quarter</option>
                      <option value="fa-battery-three-quarters" data-icon="fa-battery-three-quarters">Battery three quarters</option>
                      <option value="fa-bed" data-icon="fa-bed">Bed</option>
                      <option value="fa-beer" data-icon="fa-beer">Beer</option>
                      <option value="fa-behance" data-icon="fa-behance">Behance</option>
                      <option value="fa-behance-square" data-icon="fa-behance-square">Behance square</option>
                      <option value="fa-bell" data-icon="fa-bell">Bell</option>
                      <option value="fa-bell-o" data-icon="fa-bell-o">Bell</option>
                      <option value="fa-bell-slash" data-icon="fa-bell-slash">Bell slash</option>
                      <option value="fa-bell-slash-o" data-icon="fa-bell-slash-o">Bell slash</option>
                      <option value="fa-bicycle" data-icon="fa-bicycle">Bicycle</option>
                      <option value="fa-binoculars" data-icon="fa-binoculars">Binoculars</option>
                      <option value="fa-birthday-cake" data-icon="fa-birthday-cake">Birthday cake</option>
                      <option value="fa-bitbucket" data-icon="fa-bitbucket">Bitbucket</option>
                      <option value="fa-bitbucket-square" data-icon="fa-bitbucket-square">Bitbucket square</option>
                      <option value="fa-bitcoin" data-icon="fa-bitcoin">Bitcoin</option>
                      <option value="fa-black-tie" data-icon="fa-black-tie">Black tie</option>
                      <option value="fa-blind" data-icon="fa-blind">Blind</option>
                      <option value="fa-bluetooth" data-icon="fa-bluetooth">Bluetooth</option>
                      <option value="fa-bluetooth-b" data-icon="fa-bluetooth-b">Bluetooth b</option>
                      <option value="fa-bold" data-icon="fa-bold">Bold</option>
                      <option value="fa-bolt" data-icon="fa-bolt">Bolt</option>
                      <option value="fa-bomb" data-icon="fa-bomb">Bomb</option>
                      <option value="fa-book" data-icon="fa-book">Book</option>
                      <option value="fa-bookmark" data-icon="fa-bookmark">Bookmark</option>
                      <option value="fa-bookmark-o" data-icon="fa-bookmark-o">Bookmark</option>
                      <option value="fa-braille" data-icon="fa-braille">Braille</option>
                      <option value="fa-briefcase" data-icon="fa-briefcase">Briefcase</option>
                      <option value="fa-btc" data-icon="fa-btc">Btc</option>
                      <option value="fa-bug" data-icon="fa-bug">Bug</option>
                      <option value="fa-building" data-icon="fa-building">Building</option>
                      <option value="fa-building-o" data-icon="fa-building-o">Building</option>
                      <option value="fa-bullhorn" data-icon="fa-bullhorn">Bullhorn</option>
                      <option value="fa-bullseye" data-icon="fa-bullseye">Bullseye</option>
                      <option value="fa-bus" data-icon="fa-bus">Bus</option>
                      <option value="fa-buysellads" data-icon="fa-buysellads">Buysellads</option>
                      <option value="fa-cab" data-icon="fa-cab">Cab</option>
                      <option value="fa-calculator" data-icon="fa-calculator">Calculator</option>
                      <option value="fa-calendar" data-icon="fa-calendar">Calendar</option>
                      <option value="fa-calendar-check-o" data-icon="fa-calendar-check-o">Calendar check</option>
                      <option value="fa-calendar-minus-o" data-icon="fa-calendar-minus-o">Calendar minus</option>
                      <option value="fa-calendar-o" data-icon="fa-calendar-o">Calendar</option>
                      <option value="fa-calendar-plus-o" data-icon="fa-calendar-plus-o">Calendar plus</option>
                      <option value="fa-calendar-times-o" data-icon="fa-calendar-times-o">Calendar times</option>
                      <option value="fa-camera" data-icon="fa-camera">Camera</option>
                      <option value="fa-camera-retro" data-icon="fa-camera-retro">Camera retro</option>
                      <option value="fa-car" data-icon="fa-car">Car</option>
                      <option value="fa-caret-down" data-icon="fa-caret-down">Caret down</option>
                      <option value="fa-caret-left" data-icon="fa-caret-left">Caret left</option>
                      <option value="fa-caret-right" data-icon="fa-caret-right">Caret right</option>
                      <option value="fa-caret-square-o-down" data-icon="fa-caret-square-o-down">Caret square down</option>
                      <option value="fa-caret-square-o-left" data-icon="fa-caret-square-o-left">Caret square left</option>
                      <option value="fa-caret-square-o-right" data-icon="fa-caret-square-o-right">Caret square right</option>
                      <option value="fa-caret-square-o-up" data-icon="fa-caret-square-o-up">Caret square up</option>
                      <option value="fa-caret-up" data-icon="fa-caret-up">Caret up</option>
                      <option value="fa-cart-arrow-down" data-icon="fa-cart-arrow-down">Cart arrow down</option>
                      <option value="fa-cart-plus" data-icon="fa-cart-plus">Cart plus</option>
                      <option value="fa-cc" data-icon="fa-cc">Cc</option>
                      <option value="fa-cc-amex" data-icon="fa-cc-amex">Cc amex</option>
                      <option value="fa-cc-diners-club" data-icon="fa-cc-diners-club">Cc diners club</option>
                      <option value="fa-cc-discover" data-icon="fa-cc-discover">Cc discover</option>
                      <option value="fa-cc-jcb" data-icon="fa-cc-jcb">Cc jcb</option>
                      <option value="fa-cc-mastercard" data-icon="fa-cc-mastercard">Cc mastercard</option>
                      <option value="fa-cc-paypal" data-icon="fa-cc-paypal">Cc paypal</option>
                      <option value="fa-cc-stripe" data-icon="fa-cc-stripe">Cc stripe</option>
                      <option value="fa-cc-visa" data-icon="fa-cc-visa">Cc visa</option>
                      <option value="fa-certificate" data-icon="fa-certificate">Certificate</option>
                      <option value="fa-chain" data-icon="fa-chain">Chain</option>
                      <option value="fa-chain-broken" data-icon="fa-chain-broken">Chain broken</option>
                      <option value="fa-check" data-icon="fa-check">Check</option>
                      <option value="fa-check-circle" data-icon="fa-check-circle">Check circle</option>
                      <option value="fa-check-circle-o" data-icon="fa-check-circle-o">Check circle</option>
                      <option value="fa-check-square" data-icon="fa-check-square">Check square</option>
                      <option value="fa-check-square-o" data-icon="fa-check-square-o">Check square</option>
                      <option value="fa-chevron-circle-down" data-icon="fa-chevron-circle-down">Chevron circle down</option>
                      <option value="fa-chevron-circle-left" data-icon="fa-chevron-circle-left">Chevron circle left</option>
                      <option value="fa-chevron-circle-right" data-icon="fa-chevron-circle-right">Chevron circle right</option>
                      <option value="fa-chevron-circle-up" data-icon="fa-chevron-circle-up">Chevron circle up</option>
                      <option value="fa-chevron-down" data-icon="fa-chevron-down">Chevron down</option>
                      <option value="fa-chevron-left" data-icon="fa-chevron-left">Chevron left</option>
                      <option value="fa-chevron-right" data-icon="fa-chevron-right">Chevron right</option>
                      <option value="fa-chevron-up" data-icon="fa-chevron-up">Chevron up</option>
                      <option value="fa-child" data-icon="fa-child">Child</option>
                      <option value="fa-chrome" data-icon="fa-chrome">Chrome</option>
                      <option value="fa-circle" data-icon="fa-circle">Circle</option>
                      <option value="fa-circle-o" data-icon="fa-circle-o">Circle</option>
                      <option value="fa-circle-o-notch" data-icon="fa-circle-o-notch">Circle notch</option>
                      <option value="fa-circle-thin" data-icon="fa-circle-thin">Circle thin</option>
                      <option value="fa-clipboard" data-icon="fa-clipboard">Clipboard</option>
                      <option value="fa-clock-o" data-icon="fa-clock-o">Clock</option>
                      <option value="fa-clone" data-icon="fa-clone">Clone</option>
                      <option value="fa-close" data-icon="fa-close">Close</option>
                      <option value="fa-cloud" data-icon="fa-cloud">Cloud</option>
                      <option value="fa-cloud-download" data-icon="fa-cloud-download">Cloud download</option>
                      <option value="fa-cloud-upload" data-icon="fa-cloud-upload">Cloud upload</option>
                      <option value="fa-cny" data-icon="fa-cny">Cny</option>
                      <option value="fa-code" data-icon="fa-code">Code</option>
                      <option value="fa-code-fork" data-icon="fa-code-fork">Code fork</option>
                      <option value="fa-codepen" data-icon="fa-codepen">Codepen</option>
                      <option value="fa-codiepie" data-icon="fa-codiepie">Codiepie</option>
                      <option value="fa-coffee" data-icon="fa-coffee">Coffee</option>
                      <option value="fa-cog" data-icon="fa-cog">Cog</option>
                      <option value="fa-cogs" data-icon="fa-cogs">Cogs</option>
                      <option value="fa-columns" data-icon="fa-columns">Columns</option>
                      <option value="fa-comment" data-icon="fa-comment">Comment</option>
                      <option value="fa-comment-o" data-icon="fa-comment-o">Comment</option>
                      <option value="fa-commenting" data-icon="fa-commenting">Commenting</option>
                      <option value="fa-commenting-o" data-icon="fa-commenting-o">Commenting</option>
                      <option value="fa-comments" data-icon="fa-comments">Comments</option>
                      <option value="fa-comments-o" data-icon="fa-comments-o">Comments</option>
                      <option value="fa-compass" data-icon="fa-compass">Compass</option>
                      <option value="fa-compress" data-icon="fa-compress">Compress</option>
                      <option value="fa-connectdevelop" data-icon="fa-connectdevelop">Connectdevelop</option>
                      <option value="fa-contao" data-icon="fa-contao">Contao</option>
                      <option value="fa-copy" data-icon="fa-copy">Copy</option>
                      <option value="fa-copyright" data-icon="fa-copyright">Copyright</option>
                      <option value="fa-creative-commons" data-icon="fa-creative-commons">Creative commons</option>
                      <option value="fa-credit-card" data-icon="fa-credit-card">Credit card</option>
                      <option value="fa-credit-card-alt" data-icon="fa-credit-card-alt">Credit card alt</option>
                      <option value="fa-crop" data-icon="fa-crop">Crop</option>
                      <option value="fa-crosshairs" data-icon="fa-crosshairs">Crosshairs</option>
                      <option value="fa-cube" data-icon="fa-cube">Cube</option>
                      <option value="fa-cubes" data-icon="fa-cubes">Cubes</option>
                      <option value="fa-cut" data-icon="fa-cut">Cut</option>
                      <option value="fa-cutlery" data-icon="fa-cutlery">Cutlery</option>
                      <option value="fa-dashboard" data-icon="fa-dashboard">Dashboard</option>
                      <option value="fa-dashcube" data-icon="fa-dashcube">Dashcube</option>
                      <option value="fa-database" data-icon="fa-database">Database</option>
                      <option value="fa-deaf" data-icon="fa-deaf">Deaf</option>
                      <option value="fa-deafness" data-icon="fa-deafness">Deafness</option>
                      <option value="fa-dedent" data-icon="fa-dedent">Dedent</option>
                      <option value="fa-delicious" data-icon="fa-delicious">Delicious</option>
                      <option value="fa-desktop" data-icon="fa-desktop">Desktop</option>
                      <option value="fa-deviantart" data-icon="fa-deviantart">Deviantart</option>
                      <option value="fa-diamond" data-icon="fa-diamond">Diamond</option>
                      <option value="fa-digg" data-icon="fa-digg">Digg</option>
                      <option value="fa-dollar" data-icon="fa-dollar">Dollar</option>
                      <option value="fa-dot-circle-o" data-icon="fa-dot-circle-o">Dot circle</option>
                      <option value="fa-download" data-icon="fa-download">Download</option>
                      <option value="fa-dribbble" data-icon="fa-dribbble">Dribbble</option>
                      <option value="fa-dropbox" data-icon="fa-dropbox">Dropbox</option>
                      <option value="fa-drupal" data-icon="fa-drupal">Drupal</option>
                      <option value="fa-edge" data-icon="fa-edge">Edge</option>
                      <option value="fa-edit" data-icon="fa-edit">Edit</option>
                      <option value="fa-eject" data-icon="fa-eject">Eject</option>
                      <option value="fa-ellipsis-h" data-icon="fa-ellipsis-h">Ellipsis h</option>
                      <option value="fa-ellipsis-v" data-icon="fa-ellipsis-v">Ellipsis v</option>
                      <option value="fa-empire" data-icon="fa-empire">Empire</option>
                      <option value="fa-envelope" data-icon="fa-envelope">Envelope</option>
                      <option value="fa-envelope-o" data-icon="fa-envelope-o">Envelope</option>
                      <option value="fa-envelope-square" data-icon="fa-envelope-square">Envelope square</option>
                      <option value="fa-envira" data-icon="fa-envira">Envira</option>
                      <option value="fa-eraser" data-icon="fa-eraser">Eraser</option>
                      <option value="fa-eur" data-icon="fa-eur">Eur</option>
                      <option value="fa-euro" data-icon="fa-euro">Euro</option>
                      <option value="fa-exchange" data-icon="fa-exchange">Exchange</option>
                      <option value="fa-exclamation" data-icon="fa-exclamation">Exclamation</option>
                      <option value="fa-exclamation-circle" data-icon="fa-exclamation-circle">Exclamation circle</option>
                      <option value="fa-exclamation-triangle" data-icon="fa-exclamation-triangle">Exclamation triangle</option>
                      <option value="fa-expand" data-icon="fa-expand">Expand</option>
                      <option value="fa-expeditedssl" data-icon="fa-expeditedssl">Expeditedssl</option>
                      <option value="fa-external-link" data-icon="fa-external-link">External link</option>
                      <option value="fa-external-link-square" data-icon="fa-external-link-square">External link square</option>
                      <option value="fa-eye" data-icon="fa-eye">Eye</option>
                      <option value="fa-eye-slash" data-icon="fa-eye-slash">Eye slash</option>
                      <option value="fa-eyedropper" data-icon="fa-eyedropper">Eyedropper</option>
                      <option value="fa-fa" data-icon="fa-fa">Fa</option>
                      <option value="fa-facebook" data-icon="fa-facebook">Facebook</option>
                      <option value="fa-facebook-f" data-icon="fa-facebook-f">Facebook f</option>
                      <option value="fa-facebook-official" data-icon="fa-facebook-official">Facebookfficial</option>
                      <option value="fa-facebook-square" data-icon="fa-facebook-square">Facebook square</option>
                      <option value="fa-fast-backward" data-icon="fa-fast-backward">Fast backward</option>
                      <option value="fa-fast-forward" data-icon="fa-fast-forward">Fast forward</option>
                      <option value="fa-fax" data-icon="fa-fax">Fax</option>
                      <option value="fa-feed" data-icon="fa-feed">Feed</option>
                      <option value="fa-female" data-icon="fa-female">Female</option>
                      <option value="fa-fighter-jet" data-icon="fa-fighter-jet">Fighter jet</option>
                      <option value="fa-file" data-icon="fa-file">File</option>
                      <option value="fa-file-archive-o" data-icon="fa-file-archive-o">File archive</option>
                      <option value="fa-file-audio-o" data-icon="fa-file-audio-o">File audio</option>
                      <option value="fa-file-code-o" data-icon="fa-file-code-o">File code</option>
                      <option value="fa-file-excel-o" data-icon="fa-file-excel-o">File excel</option>
                      <option value="fa-file-image-o" data-icon="fa-file-image-o">File image</option>
                      <option value="fa-file-movie-o" data-icon="fa-file-movie-o">File movie</option>
                      <option value="fa-file-o" data-icon="fa-file-o">File</option>
                      <option value="fa-file-pdf-o" data-icon="fa-file-pdf-o">File pdf</option>
                      <option value="fa-file-photo-o" data-icon="fa-file-photo-o">File photo</option>
                      <option value="fa-file-picture-o" data-icon="fa-file-picture-o">File picture</option>
                      <option value="fa-file-powerpoint-o" data-icon="fa-file-powerpoint-o">File powerpoint</option>
                      <option value="fa-file-sound-o" data-icon="fa-file-sound-o">File sound</option>
                      <option value="fa-file-text" data-icon="fa-file-text">File text</option>
                      <option value="fa-file-text-o" data-icon="fa-file-text-o">File text</option>
                      <option value="fa-file-video-o" data-icon="fa-file-video-o">File video</option>
                      <option value="fa-file-word-o" data-icon="fa-file-word-o">File word</option>
                      <option value="fa-file-zip-o" data-icon="fa-file-zip-o">File zip</option>
                      <option value="fa-files-o" data-icon="fa-files-o">Files</option>
                      <option value="fa-film" data-icon="fa-film">Film</option>
                      <option value="fa-filter" data-icon="fa-filter">Filter</option>
                      <option value="fa-fire" data-icon="fa-fire">Fire</option>
                      <option value="fa-fire-extinguisher" data-icon="fa-fire-extinguisher">Fire extinguisher</option>
                      <option value="fa-firefox" data-icon="fa-firefox">Firefox</option>
                      <option value="fa-first-order" data-icon="fa-first-order">Firstrder</option>
                      <option value="fa-flag" data-icon="fa-flag">Flag</option>
                      <option value="fa-flag-checkered" data-icon="fa-flag-checkered">Flag checkered</option>
                      <option value="fa-flag-o" data-icon="fa-flag-o">Flag</option>
                      <option value="fa-flash" data-icon="fa-flash">Flash</option>
                      <option value="fa-flask" data-icon="fa-flask">Flask</option>
                      <option value="fa-flickr" data-icon="fa-flickr">Flickr</option>
                      <option value="fa-floppy-o" data-icon="fa-floppy-o">Floppy</option>
                      <option value="fa-folder" data-icon="fa-folder">Folder</option>
                      <option value="fa-folder-o" data-icon="fa-folder-o">Folder</option>
                      <option value="fa-folder-open" data-icon="fa-folder-open">Folderpen</option>
                      <option value="fa-folder-open-o" data-icon="fa-folder-open-o">Folderpen</option>
                      <option value="fa-font" data-icon="fa-font">Font</option>
                      <option value="fa-font-awesome" data-icon="fa-font-awesome">Font awesome</option>
                      <option value="fa-fonticons" data-icon="fa-fonticons">Fonticons</option>
                      <option value="fa-fort-awesome" data-icon="fa-fort-awesome">Fort awesome</option>
                      <option value="fa-forumbee" data-icon="fa-forumbee">Forumbee</option>
                      <option value="fa-forward" data-icon="fa-forward">Forward</option>
                      <option value="fa-foursquare" data-icon="fa-foursquare">Foursquare</option>
                      <option value="fa-frown-o" data-icon="fa-frown-o">Frown</option>
                      <option value="fa-futbol-o" data-icon="fa-futbol-o">Futbol</option>
                      <option value="fa-gamepad" data-icon="fa-gamepad">Gamepad</option>
                      <option value="fa-gavel" data-icon="fa-gavel">Gavel</option>
                      <option value="fa-gbp" data-icon="fa-gbp">Gbp</option>
                      <option value="fa-ge" data-icon="fa-ge">Ge</option>
                      <option value="fa-gear" data-icon="fa-gear">Gear</option>
                      <option value="fa-gears" data-icon="fa-gears">Gears</option>
                      <option value="fa-genderless" data-icon="fa-genderless">Genderless</option>
                      <option value="fa-get-pocket" data-icon="fa-get-pocket">Get pocket</option>
                      <option value="fa-gg" data-icon="fa-gg">Gg</option>
                      <option value="fa-gg-circle" data-icon="fa-gg-circle">Gg circle</option>
                      <option value="fa-gift" data-icon="fa-gift">Gift</option>
                      <option value="fa-git" data-icon="fa-git">Git</option>
                      <option value="fa-git-square" data-icon="fa-git-square">Git square</option>
                      <option value="fa-github" data-icon="fa-github">Github</option>
                      <option value="fa-github-alt" data-icon="fa-github-alt">Github alt</option>
                      <option value="fa-github-square" data-icon="fa-github-square">Github square</option>
                      <option value="fa-gitlab" data-icon="fa-gitlab">Gitlab</option>
                      <option value="fa-gittip" data-icon="fa-gittip">Gittip</option>
                      <option value="fa-glass" data-icon="fa-glass">Glass</option>
                      <option value="fa-glide" data-icon="fa-glide">Glide</option>
                      <option value="fa-glide-g" data-icon="fa-glide-g">Glide g</option>
                      <option value="fa-globe" data-icon="fa-globe">Globe</option>
                      <option value="fa-google" data-icon="fa-google">Google</option>
                      <option value="fa-google-plus" data-icon="fa-google-plus">Google plus</option>
                      <option value="fa-google-plus-circle" data-icon="fa-google-plus-circle">Google plus circle</option>
                      <option value="fa-google-plus-official" data-icon="fa-google-plus-official">Google plusfficial</option>
                      <option value="fa-google-plus-square" data-icon="fa-google-plus-square">Google plus square</option>
                      <option value="fa-google-wallet" data-icon="fa-google-wallet">Google wallet</option>
                      <option value="fa-graduation-cap" data-icon="fa-graduation-cap">Graduation cap</option>
                      <option value="fa-gratipay" data-icon="fa-gratipay">Gratipay</option>
                      <option value="fa-group" data-icon="fa-group">Group</option>
                      <option value="fa-h-square" data-icon="fa-h-square">H square</option>
                      <option value="fa-hacker-news" data-icon="fa-hacker-news">Hacker news</option>
                      <option value="fa-hand-grab-o" data-icon="fa-hand-grab-o">Hand grab</option>
                      <option value="fa-hand-lizard-o" data-icon="fa-hand-lizard-o">Hand lizard</option>
                      <option value="fa-hand-o-down" data-icon="fa-hand-o-down">Hand down</option>
                      <option value="fa-hand-o-left" data-icon="fa-hand-o-left">Hand left</option>
                      <option value="fa-hand-o-right" data-icon="fa-hand-o-right">Hand right</option>
                      <option value="fa-hand-o-up" data-icon="fa-hand-o-up">Hand up</option>
                      <option value="fa-hand-paper-o" data-icon="fa-hand-paper-o">Hand paper</option>
                      <option value="fa-hand-peace-o" data-icon="fa-hand-peace-o">Hand peace</option>
                      <option value="fa-hand-pointer-o" data-icon="fa-hand-pointer-o">Hand pointer</option>
                      <option value="fa-hand-rock-o" data-icon="fa-hand-rock-o">Hand rock</option>
                      <option value="fa-hand-scissors-o" data-icon="fa-hand-scissors-o">Hand scissors</option>
                      <option value="fa-hand-spock-o" data-icon="fa-hand-spock-o">Hand spock</option>
                      <option value="fa-hand-stop-o" data-icon="fa-hand-stop-o">Hand stop</option>
                      <option value="fa-hard-of-hearing" data-icon="fa-hard-of-hearing">Hardf hearing</option>
                      <option value="fa-hashtag" data-icon="fa-hashtag">Hashtag</option>
                      <option value="fa-hdd-o" data-icon="fa-hdd-o">Hdd</option>
                      <option value="fa-header" data-icon="fa-header">Header</option>
                      <option value="fa-headphones" data-icon="fa-headphones">Headphones</option>
                      <option value="fa-heart" data-icon="fa-heart">Heart</option>
                      <option value="fa-heart-o" data-icon="fa-heart-o">Heart</option>
                      <option value="fa-heartbeat" data-icon="fa-heartbeat">Heartbeat</option>
                      <option value="fa-history" data-icon="fa-history">History</option>
                      <option value="fa-home" data-icon="fa-home">Home</option>
                      <option value="fa-hospital-o" data-icon="fa-hospital-o">Hospital</option>
                      <option value="fa-hotel" data-icon="fa-hotel">Hotel</option>
                      <option value="fa-hourglass" data-icon="fa-hourglass">Hourglass</option>
                      <option value="fa-hourglass-end" data-icon="fa-hourglass-end">Hourglass end</option>
                      <option value="fa-hourglass-half" data-icon="fa-hourglass-half">Hourglass half</option>
                      <option value="fa-hourglass-o" data-icon="fa-hourglass-o">Hourglass</option>
                      <option value="fa-hourglass-start" data-icon="fa-hourglass-start">Hourglass start</option>
                      <option value="fa-houzz" data-icon="fa-houzz">Houzz</option>
                      <option value="fa-i-cursor" data-icon="fa-i-cursor">I cursor</option>
                      <option value="fa-ils" data-icon="fa-ils">Ils</option>
                      <option value="fa-image" data-icon="fa-image">Image</option>
                      <option value="fa-inbox" data-icon="fa-inbox">Inbox</option>
                      <option value="fa-indent" data-icon="fa-indent">Indent</option>
                      <option value="fa-industry" data-icon="fa-industry">Industry</option>
                      <option value="fa-info" data-icon="fa-info">Info</option>
                      <option value="fa-info-circle" data-icon="fa-info-circle">Info circle</option>
                      <option value="fa-inr" data-icon="fa-inr">Inr</option>
                      <option value="fa-instagram" data-icon="fa-instagram">Instagram</option>
                      <option value="fa-institution" data-icon="fa-institution">Institution</option>
                      <option value="fa-internet-explorer" data-icon="fa-internet-explorer">Internet explorer</option>
                      <option value="fa-intersex" data-icon="fa-intersex">Intersex</option>
                      <option value="fa-ioxhost" data-icon="fa-ioxhost">Ioxhost</option>
                      <option value="fa-italic" data-icon="fa-italic">Italic</option>
                      <option value="fa-joomla" data-icon="fa-joomla">Joomla</option>
                      <option value="fa-jpy" data-icon="fa-jpy">Jpy</option>
                      <option value="fa-jsfiddle" data-icon="fa-jsfiddle">Jsfiddle</option>
                      <option value="fa-key" data-icon="fa-key">Key</option>
                      <option value="fa-keyboard-o" data-icon="fa-keyboard-o">Keyboard</option>
                      <option value="fa-krw" data-icon="fa-krw">Krw</option>
                      <option value="fa-language" data-icon="fa-language">Language</option>
                      <option value="fa-laptop" data-icon="fa-laptop">Laptop</option>
                      <option value="fa-lastfm" data-icon="fa-lastfm">Lastfm</option>
                      <option value="fa-lastfm-square" data-icon="fa-lastfm-square">Lastfm square</option>
                      <option value="fa-leaf" data-icon="fa-leaf">Leaf</option>
                      <option value="fa-leanpub" data-icon="fa-leanpub">Leanpub</option>
                      <option value="fa-legal" data-icon="fa-legal">Legal</option>
                      <option value="fa-lemon-o" data-icon="fa-lemon-o">Lemon</option>
                      <option value="fa-level-down" data-icon="fa-level-down">Level down</option>
                      <option value="fa-level-up" data-icon="fa-level-up">Level up</option>
                      <option value="fa-life-bouy" data-icon="fa-life-bouy">Life bouy</option>
                      <option value="fa-life-buoy" data-icon="fa-life-buoy">Life buoy</option>
                      <option value="fa-life-ring" data-icon="fa-life-ring">Life ring</option>
                      <option value="fa-life-saver" data-icon="fa-life-saver">Life saver</option>
                      <option value="fa-lightbulb-o" data-icon="fa-lightbulb-o">Lightbulb</option>
                      <option value="fa-line-chart" data-icon="fa-line-chart">Line chart</option>
                      <option value="fa-link" data-icon="fa-link">Link</option>
                      <option value="fa-linkedin" data-icon="fa-linkedin">Linkedin</option>
                      <option value="fa-linkedin-square" data-icon="fa-linkedin-square">Linkedin square</option>
                      <option value="fa-linux" data-icon="fa-linux">Linux</option>
                      <option value="fa-list" data-icon="fa-list">List</option>
                      <option value="fa-list-alt" data-icon="fa-list-alt">List alt</option>
                      <option value="fa-list-ol" data-icon="fa-list-ol">Listl</option>
                      <option value="fa-list-ul" data-icon="fa-list-ul">List ul</option>
                      <option value="fa-location-arrow" data-icon="fa-location-arrow">Location arrow</option>
                      <option value="fa-lock" data-icon="fa-lock">Lock</option>
                      <option value="fa-long-arrow-down" data-icon="fa-long-arrow-down">Long arrow down</option>
                      <option value="fa-long-arrow-left" data-icon="fa-long-arrow-left">Long arrow left</option>
                      <option value="fa-long-arrow-right" data-icon="fa-long-arrow-right">Long arrow right</option>
                      <option value="fa-long-arrow-up" data-icon="fa-long-arrow-up">Long arrow up</option>
                      <option value="fa-low-vision" data-icon="fa-low-vision">Low vision</option>
                      <option value="fa-magic" data-icon="fa-magic">Magic</option>
                      <option value="fa-magnet" data-icon="fa-magnet">Magnet</option>
                      <option value="fa-mail-forward" data-icon="fa-mail-forward">Mail forward</option>
                      <option value="fa-mail-reply" data-icon="fa-mail-reply">Mail reply</option>
                      <option value="fa-mail-reply-all" data-icon="fa-mail-reply-all">Mail reply all</option>
                      <option value="fa-male" data-icon="fa-male">Male</option>
                      <option value="fa-map" data-icon="fa-map">Map</option>
                      <option value="fa-map-marker" data-icon="fa-map-marker">Map marker</option>
                      <option value="fa-map-o" data-icon="fa-map-o">Map</option>
                      <option value="fa-map-pin" data-icon="fa-map-pin">Map pin</option>
                      <option value="fa-map-signs" data-icon="fa-map-signs">Map signs</option>
                      <option value="fa-mars" data-icon="fa-mars">Mars</option>
                      <option value="fa-mars-double" data-icon="fa-mars-double">Mars double</option>
                      <option value="fa-mars-stroke" data-icon="fa-mars-stroke">Mars stroke</option>
                      <option value="fa-mars-stroke-h" data-icon="fa-mars-stroke-h">Mars stroke h</option>
                      <option value="fa-mars-stroke-v" data-icon="fa-mars-stroke-v">Mars stroke v</option>
                      <option value="fa-maxcdn" data-icon="fa-maxcdn">Maxcdn</option>
                      <option value="fa-meanpath" data-icon="fa-meanpath">Meanpath</option>
                      <option value="fa-medium" data-icon="fa-medium">Medium</option>
                      <option value="fa-medkit" data-icon="fa-medkit">Medkit</option>
                      <option value="fa-meh-o" data-icon="fa-meh-o">Meh</option>
                      <option value="fa-mercury" data-icon="fa-mercury">Mercury</option>
                      <option value="fa-microphone" data-icon="fa-microphone">Microphone</option>
                      <option value="fa-microphone-slash" data-icon="fa-microphone-slash">Microphone slash</option>
                      <option value="fa-minus" data-icon="fa-minus">Minus</option>
                      <option value="fa-minus-circle" data-icon="fa-minus-circle">Minus circle</option>
                      <option value="fa-minus-square" data-icon="fa-minus-square">Minus square</option>
                      <option value="fa-minus-square-o" data-icon="fa-minus-square-o">Minus square</option>
                      <option value="fa-mixcloud" data-icon="fa-mixcloud">Mixcloud</option>
                      <option value="fa-mobile" data-icon="fa-mobile">Mobile</option>
                      <option value="fa-mobile-phone" data-icon="fa-mobile-phone">Mobile phone</option>
                      <option value="fa-modx" data-icon="fa-modx">Modx</option>
                      <option value="fa-money" data-icon="fa-money">Money</option>
                      <option value="fa-moon-o" data-icon="fa-moon-o">Moon</option>
                      <option value="fa-mortar-board" data-icon="fa-mortar-board">Mortar board</option>
                      <option value="fa-motorcycle" data-icon="fa-motorcycle">Motorcycle</option>
                      <option value="fa-mouse-pointer" data-icon="fa-mouse-pointer">Mouse pointer</option>
                      <option value="fa-music" data-icon="fa-music">Music</option>
                      <option value="fa-navicon" data-icon="fa-navicon">Navicon</option>
                      <option value="fa-neuter" data-icon="fa-neuter">Neuter</option>
                      <option value="fa-newspaper-o" data-icon="fa-newspaper-o">Newspaper</option>
                      <option value="fa-object-group" data-icon="fa-object-group">Object group</option>
                      <option value="fa-object-ungroup" data-icon="fa-object-ungroup">Object ungroup</option>
                      <option value="fa-odnoklassniki" data-icon="fa-odnoklassniki">Odnoklassniki</option>
                      <option value="fa-odnoklassniki-square" data-icon="fa-odnoklassniki-square">Odnoklassniki square</option>
                      <option value="fa-opencart" data-icon="fa-opencart">Opencart</option>
                      <option value="fa-openid" data-icon="fa-openid">Openid</option>
                      <option value="fa-opera" data-icon="fa-opera">Opera</option>
                      <option value="fa-optin-monster" data-icon="fa-optin-monster">Optin monster</option>
                      <option value="fa-outdent" data-icon="fa-outdent">Outdent</option>
                      <option value="fa-pagelines" data-icon="fa-pagelines">Pagelines</option>
                      <option value="fa-paint-brush" data-icon="fa-paint-brush">Paint brush</option>
                      <option value="fa-paper-plane" data-icon="fa-paper-plane">Paper plane</option>
                      <option value="fa-paper-plane-o" data-icon="fa-paper-plane-o">Paper plane</option>
                      <option value="fa-paperclip" data-icon="fa-paperclip">Paperclip</option>
                      <option value="fa-paragraph" data-icon="fa-paragraph">Paragraph</option>
                      <option value="fa-paste" data-icon="fa-paste">Paste</option>
                      <option value="fa-pause" data-icon="fa-pause">Pause</option>
                      <option value="fa-pause-circle" data-icon="fa-pause-circle">Pause circle</option>
                      <option value="fa-pause-circle-o" data-icon="fa-pause-circle-o">Pause circle</option>
                      <option value="fa-paw" data-icon="fa-paw">Paw</option>
                      <option value="fa-paypal" data-icon="fa-paypal">Paypal</option>
                      <option value="fa-pencil" data-icon="fa-pencil">Pencil</option>
                      <option value="fa-pencil-square" data-icon="fa-pencil-square">Pencil square</option>
                      <option value="fa-pencil-square-o" data-icon="fa-pencil-square-o">Pencil square</option>
                      <option value="fa-percent" data-icon="fa-percent">Percent</option>
                      <option value="fa-phone" data-icon="fa-phone">Phone</option>
                      <option value="fa-phone-square" data-icon="fa-phone-square">Phone square</option>
                      <option value="fa-photo" data-icon="fa-photo">Photo</option>
                      <option value="fa-picture-o" data-icon="fa-picture-o">Picture</option>
                      <option value="fa-pie-chart" data-icon="fa-pie-chart">Pie chart</option>
                      <option value="fa-pied-piper" data-icon="fa-pied-piper">Pied piper</option>
                      <option value="fa-pied-piper-alt" data-icon="fa-pied-piper-alt">Pied piper alt</option>
                      <option value="fa-pied-piper-pp" data-icon="fa-pied-piper-pp">Pied piper pp</option>
                      <option value="fa-pinterest" data-icon="fa-pinterest">Pinterest</option>
                      <option value="fa-pinterest-p" data-icon="fa-pinterest-p">Pinterest p</option>
                      <option value="fa-pinterest-square" data-icon="fa-pinterest-square">Pinterest square</option>
                      <option value="fa-plane" data-icon="fa-plane">Plane</option>
                      <option value="fa-play" data-icon="fa-play">Play</option>
                      <option value="fa-play-circle" data-icon="fa-play-circle">Play circle</option>
                      <option value="fa-play-circle-o" data-icon="fa-play-circle-o">Play circle</option>
                      <option value="fa-plug" data-icon="fa-plug">Plug</option>
                      <option value="fa-plus" data-icon="fa-plus">Plus</option>
                      <option value="fa-plus-circle" data-icon="fa-plus-circle">Plus circle</option>
                      <option value="fa-plus-square" data-icon="fa-plus-square">Plus square</option>
                      <option value="fa-plus-square-o" data-icon="fa-plus-square-o">Plus square</option>
                      <option value="fa-power-off" data-icon="fa-power-off">Powerff</option>
                      <option value="fa-print" data-icon="fa-print">Print</option>
                      <option value="fa-product-hunt" data-icon="fa-product-hunt">Product hunt</option>
                      <option value="fa-puzzle-piece" data-icon="fa-puzzle-piece">Puzzle piece</option>
                      <option value="fa-qq" data-icon="fa-qq">Qq</option>
                      <option value="fa-qrcode" data-icon="fa-qrcode">Qrcode</option>
                      <option value="fa-question" data-icon="fa-question">Question</option>
                      <option value="fa-question-circle" data-icon="fa-question-circle">Question circle</option>
                      <option value="fa-question-circle-o" data-icon="fa-question-circle-o">Question circle</option>
                      <option value="fa-quote-left" data-icon="fa-quote-left">Quote left</option>
                      <option value="fa-quote-right" data-icon="fa-quote-right">Quote right</option>
                      <option value="fa-ra" data-icon="fa-ra">Ra</option>
                      <option value="fa-random" data-icon="fa-random">Random</option>
                      <option value="fa-rebel" data-icon="fa-rebel">Rebel</option>
                      <option value="fa-recycle" data-icon="fa-recycle">Recycle</option>
                      <option value="fa-reddit" data-icon="fa-reddit">Reddit</option>
                      <option value="fa-reddit-alien" data-icon="fa-reddit-alien">Reddit alien</option>
                      <option value="fa-reddit-square" data-icon="fa-reddit-square">Reddit square</option>
                      <option value="fa-refresh" data-icon="fa-refresh">Refresh</option>
                      <option value="fa-registered" data-icon="fa-registered">Registered</option>
                      <option value="fa-remove" data-icon="fa-remove">Remove</option>
                      <option value="fa-renren" data-icon="fa-renren">Renren</option>
                      <option value="fa-reorder" data-icon="fa-reorder">Reorder</option>
                      <option value="fa-repeat" data-icon="fa-repeat">Repeat</option>
                      <option value="fa-reply" data-icon="fa-reply">Reply</option>
                      <option value="fa-reply-all" data-icon="fa-reply-all">Reply all</option>
                      <option value="fa-resistance" data-icon="fa-resistance">Resistance</option>
                      <option value="fa-retweet" data-icon="fa-retweet">Retweet</option>
                      <option value="fa-rmb" data-icon="fa-rmb">Rmb</option>
                      <option value="fa-road" data-icon="fa-road">Road</option>
                      <option value="fa-rocket" data-icon="fa-rocket">Rocket</option>
                      <option value="fa-rotate-left" data-icon="fa-rotate-left">Rotate left</option>
                      <option value="fa-rotate-right" data-icon="fa-rotate-right">Rotate right</option>
                      <option value="fa-rouble" data-icon="fa-rouble">Rouble</option>
                      <option value="fa-rss" data-icon="fa-rss">Rss</option>
                      <option value="fa-rss-square" data-icon="fa-rss-square">Rss square</option>
                      <option value="fa-rub" data-icon="fa-rub">Rub</option>
                      <option value="fa-ruble" data-icon="fa-ruble">Ruble</option>
                      <option value="fa-rupee" data-icon="fa-rupee">Rupee</option>
                      <option value="fa-safari" data-icon="fa-safari">Safari</option>
                      <option value="fa-save" data-icon="fa-save">Save</option>
                      <option value="fa-scissors" data-icon="fa-scissors">Scissors</option>
                      <option value="fa-scribd" data-icon="fa-scribd">Scribd</option>
                      <option value="fa-search" data-icon="fa-search">Search</option>
                      <option value="fa-search-minus" data-icon="fa-search-minus">Search minus</option>
                      <option value="fa-search-plus" data-icon="fa-search-plus">Search plus</option>
                      <option value="fa-sellsy" data-icon="fa-sellsy">Sellsy</option>
                      <option value="fa-send" data-icon="fa-send">Send</option>
                      <option value="fa-send-o" data-icon="fa-send-o">Send</option>
                      <option value="fa-server" data-icon="fa-server">Server</option>
                      <option value="fa-share" data-icon="fa-share">Share</option>
                      <option value="fa-share-alt" data-icon="fa-share-alt">Share alt</option>
                      <option value="fa-share-alt-square" data-icon="fa-share-alt-square">Share alt square</option>
                      <option value="fa-share-square" data-icon="fa-share-square">Share square</option>
                      <option value="fa-share-square-o" data-icon="fa-share-square-o">Share square</option>
                      <option value="fa-shekel" data-icon="fa-shekel">Shekel</option>
                      <option value="fa-sheqel" data-icon="fa-sheqel">Sheqel</option>
                      <option value="fa-shield" data-icon="fa-shield">Shield</option>
                      <option value="fa-ship" data-icon="fa-ship">Ship</option>
                      <option value="fa-shirtsinbulk" data-icon="fa-shirtsinbulk">Shirtsinbulk</option>
                      <option value="fa-shopping-bag" data-icon="fa-shopping-bag">Shopping bag</option>
                      <option value="fa-shopping-basket" data-icon="fa-shopping-basket">Shopping basket</option>
                      <option value="fa-shopping-cart" data-icon="fa-shopping-cart">Shopping cart</option>
                      <option value="fa-sign-in" data-icon="fa-sign-in">Sign in</option>
                      <option value="fa-sign-language" data-icon="fa-sign-language">Sign language</option>
                      <option value="fa-sign-out" data-icon="fa-sign-out">Signut</option>
                      <option value="fa-signal" data-icon="fa-signal">Signal</option>
                      <option value="fa-signing" data-icon="fa-signing">Signing</option>
                      <option value="fa-simplybuilt" data-icon="fa-simplybuilt">Simplybuilt</option>
                      <option value="fa-sitemap" data-icon="fa-sitemap">Sitemap</option>
                      <option value="fa-skyatlas" data-icon="fa-skyatlas">Skyatlas</option>
                      <option value="fa-skype" data-icon="fa-skype">Skype</option>
                      <option value="fa-slack" data-icon="fa-slack">Slack</option>
                      <option value="fa-sliders" data-icon="fa-sliders">Sliders</option>
                      <option value="fa-slideshare" data-icon="fa-slideshare">Slideshare</option>
                      <option value="fa-smile-o" data-icon="fa-smile-o">Smile</option>
                      <option value="fa-snapchat" data-icon="fa-snapchat">Snapchat</option>
                      <option value="fa-snapchat-ghost" data-icon="fa-snapchat-ghost">Snapchat ghost</option>
                      <option value="fa-snapchat-square" data-icon="fa-snapchat-square">Snapchat square</option>
                      <option value="fa-soccer-ball-o" data-icon="fa-soccer-ball-o">Soccer ball</option>
                      <option value="fa-sort" data-icon="fa-sort">Sort</option>
                      <option value="fa-sort-alpha-asc" data-icon="fa-sort-alpha-asc">Sort alpha asc</option>
                      <option value="fa-sort-alpha-desc" data-icon="fa-sort-alpha-desc">Sort alpha desc</option>
                      <option value="fa-sort-amount-asc" data-icon="fa-sort-amount-asc">Sort amount asc</option>
                      <option value="fa-sort-amount-desc" data-icon="fa-sort-amount-desc">Sort amount desc</option>
                      <option value="fa-sort-asc" data-icon="fa-sort-asc">Sort asc</option>
                      <option value="fa-sort-desc" data-icon="fa-sort-desc">Sort desc</option>
                      <option value="fa-sort-down" data-icon="fa-sort-down">Sort down</option>
                      <option value="fa-sort-numeric-asc" data-icon="fa-sort-numeric-asc">Sort numeric asc</option>
                      <option value="fa-sort-numeric-desc" data-icon="fa-sort-numeric-desc">Sort numeric desc</option>
                      <option value="fa-sort-up" data-icon="fa-sort-up">Sort up</option>
                      <option value="fa-soundcloud" data-icon="fa-soundcloud">Soundcloud</option>
                      <option value="fa-space-shuttle" data-icon="fa-space-shuttle">Space shuttle</option>
                      <option value="fa-spinner" data-icon="fa-spinner">Spinner</option>
                      <option value="fa-spoon" data-icon="fa-spoon">Spoon</option>
                      <option value="fa-spotify" data-icon="fa-spotify">Spotify</option>
                      <option value="fa-square" data-icon="fa-square">Square</option>
                      <option value="fa-square-o" data-icon="fa-square-o">Square</option>
                      <option value="fa-stack-exchange" data-icon="fa-stack-exchange">Stack exchange</option>
                      <option value="fa-stack-overflow" data-icon="fa-stack-overflow">Stackverflow</option>
                      <option value="fa-star" data-icon="fa-star">Star</option>
                      <option value="fa-star-half" data-icon="fa-star-half">Star half</option>
                      <option value="fa-star-half-empty" data-icon="fa-star-half-empty">Star half empty</option>
                      <option value="fa-star-half-full" data-icon="fa-star-half-full">Star half full</option>
                      <option value="fa-star-half-o" data-icon="fa-star-half-o">Star half</option>
                      <option value="fa-star-o" data-icon="fa-star-o">Star</option>
                      <option value="fa-steam" data-icon="fa-steam">Steam</option>
                      <option value="fa-steam-square" data-icon="fa-steam-square">Steam square</option>
                      <option value="fa-step-backward" data-icon="fa-step-backward">Step backward</option>
                      <option value="fa-step-forward" data-icon="fa-step-forward">Step forward</option>
                      <option value="fa-stethoscope" data-icon="fa-stethoscope">Stethoscope</option>
                      <option value="fa-sticky-note" data-icon="fa-sticky-note">Sticky note</option>
                      <option value="fa-sticky-note-o" data-icon="fa-sticky-note-o">Sticky note</option>
                      <option value="fa-stop" data-icon="fa-stop">Stop</option>
                      <option value="fa-stop-circle" data-icon="fa-stop-circle">Stop circle</option>
                      <option value="fa-stop-circle-o" data-icon="fa-stop-circle-o">Stop circle</option>
                      <option value="fa-street-view" data-icon="fa-street-view">Street view</option>
                      <option value="fa-strikethrough" data-icon="fa-strikethrough">Strikethrough</option>
                      <option value="fa-stumbleupon" data-icon="fa-stumbleupon">Stumbleupon</option>
                      <option value="fa-stumbleupon-circle" data-icon="fa-stumbleupon-circle">Stumbleupon circle</option>
                      <option value="fa-subscript" data-icon="fa-subscript">Subscript</option>
                      <option value="fa-subway" data-icon="fa-subway">Subway</option>
                      <option value="fa-suitcase" data-icon="fa-suitcase">Suitcase</option>
                      <option value="fa-sun-o" data-icon="fa-sun-o">Sun</option>
                      <option value="fa-superscript" data-icon="fa-superscript">Superscript</option>
                      <option value="fa-support" data-icon="fa-support">Support</option>
                      <option value="fa-table" data-icon="fa-table">Table</option>
                      <option value="fa-tablet" data-icon="fa-tablet">Tablet</option>
                      <option value="fa-tachometer" data-icon="fa-tachometer">Tachometer</option>
                      <option value="fa-tag" data-icon="fa-tag">Tag</option>
                      <option value="fa-tags" data-icon="fa-tags">Tags</option>
                      <option value="fa-tasks" data-icon="fa-tasks">Tasks</option>
                      <option value="fa-taxi" data-icon="fa-taxi">Taxi</option>
                      <option value="fa-television" data-icon="fa-television">Television</option>
                      <option value="fa-tencent-weibo" data-icon="fa-tencent-weibo">Tencent weibo</option>
                      <option value="fa-terminal" data-icon="fa-terminal">Terminal</option>
                      <option value="fa-text-height" data-icon="fa-text-height">Text height</option>
                      <option value="fa-text-width" data-icon="fa-text-width">Text width</option>
                      <option value="fa-th" data-icon="fa-th">Th</option>
                      <option value="fa-th-large" data-icon="fa-th-large">Th large</option>
                      <option value="fa-th-list" data-icon="fa-th-list">Th list</option>
                      <option value="fa-themeisle" data-icon="fa-themeisle">Themeisle</option>
                      <option value="fa-thumb-tack" data-icon="fa-thumb-tack">Thumb tack</option>
                      <option value="fa-thumbs-down" data-icon="fa-thumbs-down">Thumbs down</option>
                      <option value="fa-thumbs-o-down" data-icon="fa-thumbs-o-down">Thumbs down</option>
                      <option value="fa-thumbs-o-up" data-icon="fa-thumbs-o-up">Thumbs up</option>
                      <option value="fa-thumbs-up" data-icon="fa-thumbs-up">Thumbs up</option>
                      <option value="fa-ticket" data-icon="fa-ticket">Ticket</option>
                      <option value="fa-times" data-icon="fa-times">Times</option>
                      <option value="fa-times-circle" data-icon="fa-times-circle">Times circle</option>
                      <option value="fa-times-circle-o" data-icon="fa-times-circle-o">Times circle</option>
                      <option value="fa-tint" data-icon="fa-tint">Tint</option>
                      <option value="fa-toggle-down" data-icon="fa-toggle-down">Toggle down</option>
                      <option value="fa-toggle-left" data-icon="fa-toggle-left">Toggle left</option>
                      <option value="fa-toggle-off" data-icon="fa-toggle-off">Toggleff</option>
                      <option value="fa-toggle-on" data-icon="fa-toggle-on">Togglen</option>
                      <option value="fa-toggle-right" data-icon="fa-toggle-right">Toggle right</option>
                      <option value="fa-toggle-up" data-icon="fa-toggle-up">Toggle up</option>
                      <option value="fa-trademark" data-icon="fa-trademark">Trademark</option>
                      <option value="fa-train" data-icon="fa-train">Train</option>
                      <option value="fa-transgender" data-icon="fa-transgender">Transgender</option>
                      <option value="fa-transgender-alt" data-icon="fa-transgender-alt">Transgender alt</option>
                      <option value="fa-trash" data-icon="fa-trash">Trash</option>
                      <option value="fa-trash-o" data-icon="fa-trash-o">Trash</option>
                      <option value="fa-tree" data-icon="fa-tree">Tree</option>
                      <option value="fa-trello" data-icon="fa-trello">Trello</option>
                      <option value="fa-tripadvisor" data-icon="fa-tripadvisor">Tripadvisor</option>
                      <option value="fa-trophy" data-icon="fa-trophy">Trophy</option>
                      <option value="fa-truck" data-icon="fa-truck">Truck</option>
                      <option value="fa-try" data-icon="fa-try">Try</option>
                      <option value="fa-tty" data-icon="fa-tty">Tty</option>
                      <option value="fa-tumblr" data-icon="fa-tumblr">Tumblr</option>
                      <option value="fa-tumblr-square" data-icon="fa-tumblr-square">Tumblr square</option>
                      <option value="fa-turkish-lira" data-icon="fa-turkish-lira">Turkish lira</option>
                      <option value="fa-tv" data-icon="fa-tv">Tv</option>
                      <option value="fa-twitch" data-icon="fa-twitch">Twitch</option>
                      <option value="fa-twitter" data-icon="fa-twitter">Twitter</option>
                      <option value="fa-twitter-square" data-icon="fa-twitter-square">Twitter square</option>
                      <option value="fa-umbrella" data-icon="fa-umbrella">Umbrella</option>
                      <option value="fa-underline" data-icon="fa-underline">Underline</option>
                      <option value="fa-undo" data-icon="fa-undo">Undo</option>
                      <option value="fa-universal-access" data-icon="fa-universal-access">Universal access</option>
                      <option value="fa-university" data-icon="fa-university">University</option>
                      <option value="fa-unlink" data-icon="fa-unlink">Unlink</option>
                      <option value="fa-unlock" data-icon="fa-unlock">Unlock</option>
                      <option value="fa-unlock-alt" data-icon="fa-unlock-alt">Unlock alt</option>
                      <option value="fa-unsorted" data-icon="fa-unsorted">Unsorted</option>
                      <option value="fa-upload" data-icon="fa-upload">Upload</option>
                      <option value="fa-usb" data-icon="fa-usb">Usb</option>
                      <option value="fa-usd" data-icon="fa-usd">Usd</option>
                      <option value="fa-user" data-icon="fa-user">User</option>
                      <option value="fa-user-md" data-icon="fa-user-md">User md</option>
                      <option value="fa-user-plus" data-icon="fa-user-plus">User plus</option>
                      <option value="fa-user-secret" data-icon="fa-user-secret">User secret</option>
                      <option value="fa-user-times" data-icon="fa-user-times">User times</option>
                      <option value="fa-users" data-icon="fa-users">Users</option>
                      <option value="fa-venus" data-icon="fa-venus">Venus</option>
                      <option value="fa-venus-double" data-icon="fa-venus-double">Venus double</option>
                      <option value="fa-venus-mars" data-icon="fa-venus-mars">Venus mars</option>
                      <option value="fa-viacoin" data-icon="fa-viacoin">Viacoin</option>
                      <option value="fa-viadeo" data-icon="fa-viadeo">Viadeo</option>
                      <option value="fa-viadeo-square" data-icon="fa-viadeo-square">Viadeo square</option>
                      <option value="fa-video-camera" data-icon="fa-video-camera">Video camera</option>
                      <option value="fa-vimeo" data-icon="fa-vimeo">Vimeo</option>
                      <option value="fa-vimeo-square" data-icon="fa-vimeo-square">Vimeo square</option>
                      <option value="fa-vine" data-icon="fa-vine">Vine</option>
                      <option value="fa-vk" data-icon="fa-vk">Vk</option>
                      <option value="fa-volume-control-phone" data-icon="fa-volume-control-phone">Volume control phone</option>
                      <option value="fa-volume-down" data-icon="fa-volume-down">Volume down</option>
                      <option value="fa-volume-off" data-icon="fa-volume-off">Volumeff</option>
                      <option value="fa-volume-up" data-icon="fa-volume-up">Volume up</option>
                      <option value="fa-warning" data-icon="fa-warning">Warning</option>
                      <option value="fa-wechat" data-icon="fa-wechat">Wechat</option>
                      <option value="fa-weibo" data-icon="fa-weibo">Weibo</option>
                      <option value="fa-weixin" data-icon="fa-weixin">Weixin</option>
                      <option value="fa-whatsapp" data-icon="fa-whatsapp">Whatsapp</option>
                      <option value="fa-wheelchair" data-icon="fa-wheelchair">Wheelchair</option>
                      <option value="fa-wheelchair-alt" data-icon="fa-wheelchair-alt">Wheelchair alt</option>
                      <option value="fa-wifi" data-icon="fa-wifi">Wifi</option>
                      <option value="fa-wikipedia-w" data-icon="fa-wikipedia-w">Wikipedia w</option>
                      <option value="fa-windows" data-icon="fa-windows">Windows</option>
                      <option value="fa-won" data-icon="fa-won">Won</option>
                      <option value="fa-wordpress" data-icon="fa-wordpress">Wordpress</option>
                      <option value="fa-wpbeginner" data-icon="fa-wpbeginner">Wpbeginner</option>
                      <option value="fa-wpforms" data-icon="fa-wpforms">Wpforms</option>
                      <option value="fa-wrench" data-icon="fa-wrench">Wrench</option>
                      <option value="fa-xing" data-icon="fa-xing">Xing</option>
                      <option value="fa-xing-square" data-icon="fa-xing-square">Xing square</option>
                      <option value="fa-y-combinator" data-icon="fa-y-combinator">Y combinator</option>
                      <option value="fa-y-combinator-square" data-icon="fa-y-combinator-square">Y combinator square</option>
                      <option value="fa-yahoo" data-icon="fa-yahoo">Yahoo</option>
                      <option value="fa-yc" data-icon="fa-yc">Yc</option>
                      <option value="fa-yc-square" data-icon="fa-yc-square">Yc square</option>
                      <option value="fa-yelp" data-icon="fa-yelp">Yelp</option>
                      <option value="fa-yen" data-icon="fa-yen">Yen</option>
                      <option value="fa-yoast" data-icon="fa-yoast">Yoast</option>
                      <option value="fa-youtube" data-icon="fa-youtube">Youtube</option>
                      <option value="fa-youtube-play" data-icon="fa-youtube-play">Youtube play</option>
                      <option value="fa-youtube-square" data-icon="fa-youtube-square">Youtube square</option>
                    </select>
                    </div>
                  </div>
                </div>
              </div>
              <!-- <div class="form-group" id="icon-frontend">
                <label class="col-md-3 control-label">{{ trans('Admin'.DS.'menu.icon') }}</label>
                <div class="col-md-9">
                  <input type="text" class="form-control" id="input_icon_class" name="icon_class_fe">
                </div>
              </div> -->
              <div class="form-group">
                <label class="col-md-3 control-label">{{ trans('Admin'.DS.'menu.link') }}</label>
                <div class="col-md-9">
                  <div class="input-inline">
                    <!-- <div class="input-group"> -->
                      <!-- <span class="input-group-addon" >
                      {{ url('/').'/' }}
                      </span> -->
                      <input type="text" class="form-control" id="link" name="link" placeholder="admin/menus">
                    <!-- </div> -->
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">{{ trans('Admin'.DS.'menu.parent_menu') }}</label>
                <div class="col-md-9">
                  <select class="form-control" name="parent_id" id="parent_id">
                    <option value="0">--{{ trans('Admin'.DS.'menu.root') }}--</option>
                    @if( isset($arrParent['backend']) )
                      @foreach($arrParent['backend'] as $module)
                        @foreach($module as $parent)
                          {{$parent}}
                        @endforeach
                      @endforeach
                    @endif
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">{{ trans('Admin'.DS.'menu.weight') }}</label>
                <div class="col-md-9">
                  <div id="order_spinner">
                    <div class="input-group input-small">
                      <input type="number" class="form-control" min="1" value="1" max="999" id="weight" name="weight">
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">{{trans('global.language')}}</label>
                <div class="col-md-9">
                  <select class="form-control" name="language" id="language">
                   <option value="vn" {{ old('language') == 'vn' ? 'selected' : '' }}>Tiếng Việt</option>
                   <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-3 control-label">Active</label>
                <div class="col-md-9">
                  <div class="checkbox-list">
                    <label class="checkbox-inline">
                    <input type="checkbox" id="active" name="active" value="1" checked>  </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-actions">
              <div class="row">
                <div class="col-md-offset-3 col-md-9">
                  <button type="submit" class="btn btn-success">Submit</button>
                  <!-- <button type="button" id="cancel" class="btn default">{{trans('global.cancel')}}</button> -->
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>
<style type="text/css" media="screen">
  .contain-img{
    
  }

  .contain-img .round{

  }
</style>
@endsection
@section('JS')
<script type="text/javascript">
  var parent_menu = {!! json_encode($arrParent) !!} ;
  $(function(){
    var permission = {};

    $('.imgupload').imageupload({
      allowedFormats: [ "jpg", "jpeg", "png", "gif", "svg" , "bmp"],
      previewWidth: 100,
      previewHeight: 100,
      maxFileSizeKb: 2048
    });

    $('#icon_class').selectpicker({
      iconBase: 'fa',
    }).trigger("change");
    $("#parent_id").change(function(){
      checkParentType();
    });
    $("#type").change(function(){
      checkParentType();
      var type = $(this).val();
      var id = $("#id").val();
      getParentByType(type,"menu_"+id);
      if( type =="backend" ) {
        $("#group-div").hide();
        $("#icon-div").show();
        $("#module-div").show();
        $("#icon-image-div").hide();
        $("#icon-frontend").hide();
      } else {
        $("#group-div").show();
        $("#icon-div").hide();
        $("#module-div").hide();
        $("#module").val("location");
        $("#icon-image-div").show();
        $("#group").val("header");
      }
    }).val("frontend").trigger("change");

    

    $("#menu-info #id").change(function(){
      var id = $(this).val();
      $(".dd3-content").removeClass("active");
      if( id !="" ) {
        $(".dd3-content[data-id="+ id +"]").addClass("active");
      }
    });

    $("#cancel","#menu-info").click(function(){
      if( $(".fullscreen","#menu-info").hasClass("on") ) {
        $(".fullscreen","#menu-info").trigger("click");
      }
    });
    $("#add-menu").click(function(){
      // if( !permission["frontend"].create && !permission["backend"].create ) {
      //   toastr.warning("You do not have permission to do this!","Warning");
      //   return false;
      // }
      resetInput();
      $(".fullscreen","#menu-info").trigger("click");
    });
    $("#form-reorder").on("submit", function( e ) {
      e.preventDefault();
      $.ajax({
          url:"{{ url('/').'/admin/menu/reorder'}}",
          type:"POST",
          data: $('#form-reorder').serialize(),
          success: function(result){
            if( result.status =="ok" ) {
              $("#sidebar-menu .side-menu").html(result.sidebar);
              parent_menu = result.parent;
              $("#menu-info #id").trigger("change");
              toastr.success("{{trans('valid.order_menu_success')}}","Message");
            } else if( result.status =="error" ) {
              toastr.error(result.message,"Error");
            } else {
              toastr.warning(result.message,"Warning");
            }
          }
      });
    });
    $("#update-menu").on("submit", function( e ) {
      e.preventDefault();
      // if( parseInt($("#id", this).val()) == 0 && !permission["frontend"].create && !permission["backend"].create ) {
      //   toastr.warning("You do not have permission to do this!","Warning");
      //   return false;
      // }
      var dataPost = new FormData($('#update-menu')[0]);
      $.ajax({
          url:"{{ url('/').'/admin/menu/update' }}",
          type:"POST",
          cache: false,
          contentType: false,
          processData: false,
          data: dataPost,
          success: function(result){
            if( result.status =="ok" ) {
              toastr.success("Message",result['message']);
              window.location.reload();
            } else {
              toastr.error(result.message,"Error");
            }
          }
      });
    });
    var updateOutput = function(e) {
        var list = e.length ? e : $(e.target),output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));
        } else {
            alert('JSON browser support required for this.');
        }
    };

    menuInit({!! json_encode($arrType) !!});
    function bindChange(name)
    {
      $('#tab-'+ name).change(function(){
          updateOutput($('#tab-'+ name).data('output', $('#'+ name +'_store')));
      });
    }
    function menuInit(arrType)
    {
      var maxDepth = 3;
      for(var i in arrType) {
        var t = arrType[i];
        if( t =="header" || t =="footer" ) {
          t ="frontend";
        }
        // if(arrType[i] == 'frontend'){
        //   maxDepth = 1;
        // }
        $('#tab-'+ arrType[i]).nestable({
            group : 1,
            maxDepth: maxDepth
        });
        bindChange(arrType[i]);
      }
      /*$(".pull-right > a").click(function() {
        var id = $(this).attr("data-id");
        var action = $(this).attr("data-function");
        if( action =="edit" ) {
          editMenu(id);
        } else {
          deleteMenu(id);
        }
      });*/
      $(".dd3-content").click(function(){
        var id = $(this).attr("data-id");
        editMenu(id);
      });
    }

    function checkParentType()
    {
      var parent_id = $("#parent_id").val();
      if( parent_id == 0 )
        return false;
      var parent = $("#menu-"+parent_id).val();
      parent_menu = $.parseJSON(parent_menu);
      if( parent_menu.type != $("#type").val() ) {
        $("#parent_id").val(0);
      }
    }
    function getParentByType(type, id)
    {
      var html ="<option value='0'>*Root</option>";
      for(i in parent_menu[type]) {
        if( i == id ) continue;
        html += parent_menu[type][i];
      }
      $("#parent_id").html(html);
      if(type=="backend"){
        getParentByModule('location', id);
      }
    }

    

    function editMenu(id)
    {

      if( $(".dd3-content[data-id="+ id +"]").hasClass("disabled-link") ){
        return false;
      }
      var info = $("#menu-"+id).val();

      info = $.parseJSON(info);
      
      for(i in info){

        if( !$("#"+i).length ) continue;
        info[i] = $.trim(info[i]);
        
        if( $("#"+i).is(":checkbox") ) {
          if( parseInt(info[i]) ) {
            $("#"+i).prop("checked", true);
            $("#"+i).parent().addClass("checked");
          } else {
            $("#"+i).prop("checked", false);
            $("#"+i).parent().removeClass("checked");
          }
        } else if( i =="parent_id") {
          getParentByType(info["type"], 'menu_'+info['id']);
        } else if(i == "icon_img"){
          var html_image = '';
          if(info[i]){
            html_image = '<img src="'+info[i]+'" alt="Image preview" class="thumbnail" style="max-width: 100px; max-height: 100px">';
          }
          $('.file-tab.image .thumbnail').remove();
          $('.file-tab.image').prepend(html_image)
          $("input#icon_img").val('');
        } else if( i =="weight"){
          $("#order_spinner").val(info[i]);
          $("#"+i).val(info[i]);
        } else {
          $("#"+i).val(info[i]).trigger("change");
        }
      }
      $("#parent_id").val(info["parent_id"]).trigger("change");

      if(info["type"]!="frontend"){
       $("#icon_class").trigger("change");
      }else{
        $("#input_icon_class").val(info["icon_class"]);
      }

      $("#name").focus();
      $("html, body").animate({
            scrollTop: $("#menu-info").offset().top - $(".page-header").height()
        }, 200);
    }

    function createTabs(arrMenu)
    {
      var html = '';
      var active = 0;
      for(var i in arrMenu) {
        html += '<li><a href="#tab-'+ i +'" '+( !active ? 'class="active"' : '' )+'>'+ ucfirst(i) +'</a></li>';
        active = 1;
      }
      return '<ul class="nav nav-tabs">'+ html + '</ul>';
    }

    function createTabContent(arrMenu)
    {
      var html = '';
      for(var type in arrMenu) {
        html += '<div id="tab-'+ type + '" class=" dd tab-pane" style="min-height: 325px;">';
        if( typeof arrMenu[type] !="string" ) {
          html += '<div class="tabbable-line">'+ createTabs(arrMenu[type]) +'</div>' +
              '<div class="tab-content">' +
                createTabContent(arrMenu[type]) +
              '</div>';
        } else {
          html += arrMenu[type] + '<input type="hidden" id="'+ type +'_store" name="'+ type +'_store" value="" />';
        }
        html += '</div>';
      }
      return html;
    }

    function ucfirst(str)
    {
      str += '';
      var f = str.charAt(0).toUpperCase();
      return f + str.substr(1);
    }

    function rsortObjectByKey(obj)
    {
      var keys = [];
      var sorted_obj = {};

      for(var key in obj){
          if(obj.hasOwnProperty(key)){
              keys.push(key);
          }
      }

      // sort keys
      keys.sort().reverse();

      // create new array based on Sorted Keys
      $.each(keys, function(i, key){
          sorted_obj[key] = obj[key];
      });

      return sorted_obj;
    };

    function resetInput()
    {
      var arr = ['id','name', 'icon_class','input_icon_class', 'link','module'];
      for(i in arr) {
        $("#"+arr[i]).val("").trigger("change");
      }
      var html ="<option value='0'>*Root</option>";
      for(i in parent_menu) {
        html += parent_menu[i];
      }
      $("#parent_id").html(html);
      $("#parent_id").val(0);
      $("#type").val("frontend").trigger("change");
      getParentByType("frontend","menu_0");
      $("#weight").val(1);
      $("#order_spinner").val(1);
      $("#active").prop("checked", true);
      $("#active").parent().addClass("checked");
    }
  });
  function deleteMenu(id)
  {
      if(confirm("{{ trans('valid.confirm_delete_menu') }}")){
        $.ajax({
          url:"{{ url('/').'/admin/menu/delete/' }}"+id,
          success: function(result){
            if( result.status =="success" ) {
              $(".dd-item[data-id='" + id +"']").remove();
              if( result.sidebar != undefined ) {
                $("#sidebar-menu .side-menu").html(result.sidebar);
              }
              toastr.success(result.message,"Message");
            } else {
              toastr.error(result.message,"Error");
            }
            resetInput();
          }
        })
      }
  }

  function changeModule(obj){
      checkParentModule();
      console.log("im pass");
      var module = $(obj).val();
      var id = $("#id").val();
      getParentByModule(module,"menu_"+id);
  } 

  function checkParentModule()
  {


    var parent_id = $("#parent_id").val();

    if( parent_id == 0 )
      return false;
    var parent = $("#menu-"+parent_id).val();
    parent_menu = $.parseJSON(parent_menu);
    
    if( parent.module != $("#module").val() ) {
      $("#parent_id").val(0);
    }
  }
  function getParentByModule(module, id)
  {
    var html ="<option value='0'>*Root</option>";
    for(i in parent_menu['backend'][module]) {
      if( i == id ) continue;
      html += parent_menu['backend'][module][i];
    }
    $("#parent_id").html(html);
  }

</script>
@endsection

@section('CSS')
<style type="text/css">
  .pull-right > a:hover{
    text-decoration: none !important;
  }
  .dd3-content.active {
    background: #286090 !important;
    color: #fff !important;
  }
  .dd3-content:hover {
    cursor: pointer !important;
  }
  #menu-order{
    border: 1px solid #ddd;
    padding: 15px;
  }
  #menu-info{
    border: 1px solid #ddd;
    padding: 15px;
  }
  span.header{
    font-size: 200%;
  }
  .add_button{
    position: absolute;
    right: 20px;
    top: 20px;
  }
</style>
@endsection

<footer id="footer" class="wrapper-footer ">
    <div class="container d-flex justify-content-end">
        <div class="info-footer py-1 mr-auto">
            <p class="btn-show-footer m-0 hidden-lg-up  d-flex align-items-center justify-content-bergin">
                <i class="icon-location"></i> {{ucfirst(trans('global.locations'))}}:
                <span>{{$static['countContent']}}</span>
                <i class="icon-up-dir"></i>
            </p>
            <ul class="info-footer-sub list-unstyled">
                <li>
                    <i class="icon-location"></i> {{ucfirst(trans('global.locations'))}}:
                    <span>{{$static['countContent']}}</span>
                </li>
                <li>
                    <i class="icon-online"></i> {{ucfirst(trans('global.online'))}}:
                    <span>{{$static['countOnline']}}</span>
                </li>
                <li>
                    <i class="icon-check-grey"></i> {{ucfirst(trans('global.new_locations'))}}:
                    <span>{{$static['newContent']}}</span>
                </li>
                <li>
                    <i class="icon-like"></i> {{ucfirst(trans('global.like'))}}:
                    <span>{{$static['countLike']}}</span>
                </li>
                <li>
                    <i class="icon-share-grey"></i> {{ucfirst(trans('global.share'))}}:
                    <span>{{$static['countShare']}}</span>
                </li>
            </ul>
            <input name="advance" type="hidden">
        </div>
        <!-- end info footer -->
        
        <div class="footer-notifical hidden-md-up">
            @if (Auth::guard('web_client')->user())
            <a class="icon-notifi {{$count_notifications>0?'notifi-active':''}}" href="#" title="">
                <i class="icon-notification-white"></i>
                @if($count_notifications>0)
                <i class="icon-circle"></i>
                @endif
            </a>
            @else
            <a class="icon-notifi" href="#" title="">
                <i class="icon-notification-white"></i>
            </a>
            @endif
        </div>
        <!-- end footer notifical -->
        <div class="dropdown dropup social-footer social ">
            <a class="dropdown-toggle" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="icon-share-grey"></i>
            </a>
            <div class="dropdown-menu social-footer-sub dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="#"   onclick="sharePopup('https://plus.google.com/share?url={{urlencode(url('/'))}}')"><i class="icon-google"></i>Google</a>
                <a class="dropdown-item" href="#"   onclick="sharePopup('https://www.facebook.com/sharer/sharer.php?u={{urlencode(url('/'))}}&amp;src=sdkpreparse')"><i class="icon-facebook"></i>Facebook</a>
                <a class="dropdown-item" href="#"  onclick="sharePopup('https://twitter.com/share?text=KingMap&url={{urlencode(url('/'))}}&hashtags=Kingmap')"><i class="icon-twitter-bird"></i>Twitter</a>
            </div>
        </div>
        <!-- end social footer -->
    </div>
</footer>
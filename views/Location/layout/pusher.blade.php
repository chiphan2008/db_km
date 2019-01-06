
<!-- Pusher JS -->
<script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('{{env('PUSHER_APP_KEY','cccc47e9fa4d58585b38')}}', {
        cluster: 'ap1',
        encrypted: true
    });

    //Get tin tuc
    var channel = pusher.subscribe('get-new-notifi-0');
    channel.bind('App\\Events\\getNotifi', function(data) {
        $.ajax({
            url : '/getHTMLNotifi/'+data.data.id,
            type: 'GET',
            success: function(html){
                $('.notification-header.new-notification .icon-circle').css('display','block');
                $('#pusher-list-news').prepend(html);
                $(".btn-notifi").on("click",function(e){
                    e.preventDefault();
                    loadAjax({
                        url : $(this).attr('href'),
                        type: 'GET',
                        success: function(){
                            window.location.reload();
                        }
                    })
                })
            }
        })
    });

    @if(\Auth::guard('web_client')->user())
        //Get tin tuc cho user
        var channel_2 = pusher.subscribe('get-new-notifi-all');
        channel_2.bind('App\\Events\\getNotifi', function(data) {
            $.ajax({
                url : '/getHTMLNotifi/'+data.data.id,
                type: 'GET',
                success: function(html){
                    $('.notification-header.new-notification .icon-circle').css('display','block');
                    $('#pusher-list-noti-user').prepend(html);
                    $(".btn-notifi").on("click",function(e){
                        e.preventDefault();
                        loadAjax({
                            url : $(this).attr('href'),
                            type: 'GET',
                            success: function(){
                                window.location.reload();
                            }
                        })
                    })
                }
            })

            
        });
        //Get tin tuc cho user
        var name_channel_3 = 'get-new-notifi-'+{!! \Auth::guard('web_client')->user()->id !!};
        var channel_3 = pusher.subscribe(name_channel_3);
        channel_3.bind('App\\Events\\getNotifi', function(data) {
            $('.notification-header.new-notification .icon-circle').css('display','block');
            var html = '<li class="item-notification">';
                $.ajax({
                    url : '/getHTMLNotifi/'+data.data.id,
                    type: 'GET',
                    success: function(html){
                        $('.notification-header.new-notification .icon-circle').css('display','block');
                        $('#pusher-list-noti-user').prepend(html);
                        $(".btn-notifi").on("click",function(e){
                            e.preventDefault();
                            loadAjax({
                                url : $(this).attr('href'),
                                type: 'GET',
                                success: function(){
                                    window.location.reload();
                                }
                            })
                        })
                    }
                })
        });
    @endif

</script>
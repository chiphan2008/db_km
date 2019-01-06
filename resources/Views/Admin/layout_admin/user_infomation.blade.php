<div class="top_nav">
	<div class="nav_menu">
		<nav>
			<div class="nav toggle">
				<a id="menu_toggle"><i class="fa fa-bars"></i></a>
			</div>
			<ul class="nav navbar-nav navbar-right">
				<li class="">
					<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<img src="{{asset('img_user/'.Auth::guard('web')->user()->avatar)}}" alt="">{{Auth::guard('web')->user()->full_name}}
						<span class=" fa fa-angle-down"></span>
					</a>
					<ul class="dropdown-menu dropdown-usermenu pull-right">
						<li><a href="{{route('profile')}}">Profile</a></li>
						@if(Auth::guard('web')->user()->hasRole('content') == true)
							<li><a href="{{route('content_user',['moderation'=> 'all'])}}">Content</a></li>
						@endif
						{{--<li>--}}
						{{--<a href="javascript:;">--}}
						{{--<span class="badge bg-red pull-right">50%</span>--}}
						{{--<span>Settings</span>--}}
						{{--</a>--}}
						{{--</li>--}}
						{{--<li><a href="javascript:;">Help</a></li>--}}
						<li><a href="{{route('logout')}}"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
					</ul>
				</li>

				<li role="presentation" class="dropdown">
					<a href="#" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
						<i class="fa fa-envelope-o"></i>
						@if($count_notifications > 0)
						<span class="badge bg-green">{{$count_notifications}}</span>
						@endif
					</a>
					<ul id="notifi_admin" class="dropdown-menu list-unstyled msg_list" role="menu" style="height: 600px;overflow-y: auto;">
						@if($notifications)
						@foreach($notifications as $notify)
						<li class="{{$notify->not_read?'not_read':''}}">
							<a href="{{$notify->link}}" class="read_notification" data-id-notification="{{$notify->id}}" alt="{{$notify->not_read?date('d-m-Y H:i:s', strtotime($notify->read_at)):''}}">
								<!-- <span class="image"><img src="images/img.jpg" alt="Profile Image" /></span> -->
								<!-- <span>
									<span>John Smith</span>
									<span class="time">3 mins ago</span>
								</span> -->
								<span class="message">
									<b>{!! $notify->content !!}</b>
								</span>
								<span>
									{{date('d-m-Y H:i:s', strtotime($notify->created_at))}}
								</span>
							</a>
						</li>
						@endforeach
						@endif
					</ul>
				</li>
				<li class="">
					<a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						{{ucfirst(session()->get('module_admin'))}}
						<span class=" fa fa-angle-down"></span>
					</a>
					<ul class="dropdown-menu dropdown-usermenu pull-right">
						<li><a href="{{url('/admin')}}">Location</a></li>
						<li><a href="{{url('/booking/homepage')}}">Booking</a></li>
						<li><a href="{{route('list_discount')}}">Discount</a></li>
						<li><a href="{{route('list_ads')}}">Advertisement</a></li>
						<li><a href="{{route('list_raovat')}}">Rao vặt</a></li>
						<li><a href="{{route('index_showroom')}}">Showroom</a></li>
					</ul>
				</li>
			</ul>
		</nav>
	</div>
</div>

<style>
	ul.msg_list li a .message{
		font-size: 12px !important;
		word-break: break-word;
	}
	#notifi_admin li.not_read a{
		background: #dddddddd;
	}
</style>

<script>
    $("a.read_notification").on("click", function (e) {
        e.preventDefault();
        var id = $(this).attr("data-id-notification");
		var link = this.href;
        readEachNotifi(id,link);
    });
    function readEachNotifi(id,link){
        $.ajax({
            url : '/readEachNotifi/'+id,
            type : 'GET',
            success: function(response){
                console.log(response);
                location.href = link;
            }
        })
    }
	function readNotifi(){
		$.ajax({
			url : '/readNotifi',
			type : 'GET',
			success: function(response){
				console.log(response);
			}
		})
	}
	var offset_notifi = 10;
    $("#notifi_admin").scroll(function() {
        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            $.ajax({
                url : '/getNotifications/'+offset_notifi,
                type : 'GET',
                success: function(response){
                    offset_notifi = offset_notifi + 10;
                    var html = '';
                    var data = response.results;
                    for (var i = 0; i < data.length; i++) {
	                    	if(data[i]['not_read']){
	                    		html += "<li class='not_read'>" ;
	                    	}else{
	                    		html += "<li>" ;
	                    	}
												html += "<a href="+data[i]['link']+" class='read_notification' data-id-notification="+data[i]['id']+">" ;
												html += "<span class='message'>" ;
												html += "<b>" + data[i]['content'] +"</b>" ;
												html += "</span>" ;
												html += "<span>" + data[i]['time'];
												html += "</span>" ;
												html += "</a>" ;
												html += "</li>";
                    }
                    $( "#notifi_admin" ).append( html );
                }
            })
        }
    });
</script>
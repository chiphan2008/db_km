<div class="chat" id="chat-box" style="display: none;">
	<div class="chat-top">
		<div class="chat-top-avata">
			<img src="{{Auth::guard('web_client')->user()->avatar}}" alt="{{Auth::guard('web_client')->user()->full_name}}" onerror="this.src = '/img_user/default.png' ">
		</div>
		<div class="chat-top-name">
			{{Auth::guard('web_client')->user()->full_name}}
		</div>
		<div class="chat-top-close" onclick="$('#chat-box').toggle('fast')">
			<i class="icon-cancel"></i>
		</div>
	</div>
	<!-- end  chat top -->
	<div class="chat-container">
		<ul class="nav" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="chat-tab" data-toggle="tab" href="#chat-mess" role="tab" aria-controls="chat" aria-selected="true">
					<i class="icon-chat"></i>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact-mess" role="tab" aria-controls="contact" aria-selected="false">
					<i class="icon-contact"></i>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="user-tab" data-toggle="tab" href="#user-mess" role="tab" aria-controls="user" aria-selected="false">
					<i class="icon-user-plus"></i>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="bell-tab" data-toggle="tab" href="#bell-mess" role="tab" aria-controls="bell" aria-selected="false">
					<i class="icon-bell-alt"></i>
				</a>
			</li>
		</ul>
		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade show active" id="chat-mess" role="tabpanel" aria-labelledby="chat-tab">
				<div class="chat-message">
					<div class="chat-message-item">
					<img src="http://via.placeholder.com/40x40" alt="Avatar" style="width:100%;">
					<p>Hello. How are you today?</p>
					<span class="time-right">11:00</span>
				</div>

				<div class="chat-message-item darker">
					<img src="http://via.placeholder.com/40x40" alt="Avatar" class="right" style="width:100%;">
					<p>Hey! I'm fine. Thanks for asking!</p>
					<span class="time-left">11:01</span>
				</div>

				<div class="chat-message-item">
					<img src="http://via.placeholder.com/40x40" alt="Avatar" style="width:100%;">
					<p>Sweet! So, what do you wanna do today?</p>
					<span class="time-right">11:02</span>
				</div>

				<div class="chat-message-item darker">
					<input type="text" class="w-100">
				</div>
				</div>
			</div>
			<div class="tab-pane fade" id="contact-mess" role="tabpanel" aria-labelledby="contact-tab">
				<ul class="chat-contact list-unstyled mb-0">

				</ul>
			</div>
			<div class="tab-pane fade" id="user-mess" role="tabpanel" aria-labelledby="user-tab">
				<div class="add-contact">
					<input type="text" placeholder="Add contact">
				</div>
				<ul class="chat-contact list-unstyled mb-0">
				</ul>
			</div>
			<div class="tab-pane fade" id="bell-mess" role="tabpanel" aria-labelledby="bell-tab">
				<ul class="chat-notifil mb-0 list-unstyled">
					<li class="item-notification ">
										<a class="d-flex align-items-center" href="" title="">
												<div class="img">
														<img class="rounded-circle" src="http://via.placeholder.com/46x46" alt="">
												</div>
												<div class="content">
														<div class="title">
																<h3>Andy Trần </h3><span>đã gửi lời kết bạn</span>
														</div>
														<span class="time">10 phút trước</span>
												</div>
												<div class="online">
														<i class="icon-circle"></i>
												</div>
										</a>
								</li>
								<li class="item-notification ">
										<a class="d-flex align-items-center" href="" title="">
												<div class="img">
														<img class="rounded-circle" src="http://via.placeholder.com/46x46" alt="">
												</div>
												<div class="content">
														<div class="title">
																<h3>Andy Trần </h3><span>muốn liên kết với bạn</span>
														</div>
														<span class="time">10 phút trước</span>
												</div>
												<div class="online">
														<i class="icon-circle"></i>
												</div>
										</a>
								</li>
								<li class="item-notification ">
										<a class="d-flex align-items-center" href="" title="">
												<div class="img">
														<img class="rounded-circle" src="http://via.placeholder.com/46x46" alt="">
												</div>
												<div class="content">
														<div class="title">
																<h3>Andy Trần </h3><span>đã gửi lời kết bạn</span>
														</div>
														<span class="time">10 phút trước</span>
												</div>
												<div class="online">
														<i class="icon-circle"></i>
												</div>
										</a>
								</li>
								<li class="item-notification ">
										<a class="d-flex align-items-center" href="" title="">
												<div class="img">
														<img class="rounded-circle" src="http://via.placeholder.com/46x46" alt="">
												</div>
												<div class="content">
														<div class="title">
																<h3>Andy Trần </h3><span>muốn liên kết với bạn</span>
														</div>
														<span class="time">10 phút trước</span>
												</div>
												<div class="online">
														<i class="icon-circle"></i>
												</div>
										</a>
								</li>
								<li class="item-notification ">
										<a class="d-flex align-items-center" href="" title="">
												<div class="img">
														<img class="rounded-circle" src="http://via.placeholder.com/46x46" alt="">
												</div>
												<div class="content">
														<div class="title">
																<h3>Andy Trần </h3><span>đã gửi lời kết bạn</span>
														</div>
														<span class="time">10 phút trước</span>
												</div>
												<div class="online">
														<i class="icon-circle"></i>
												</div>
										</a>
								</li>
								<li class="item-notification ">
										<a class="d-flex align-items-center" href="" title="">
												<div class="img">
														<img class="rounded-circle" src="http://via.placeholder.com/46x46" alt="">
												</div>
												<div class="content">
														<div class="title">
																<h3>Andy Trần </h3><span>muốn liên kết với bạn</span>
														</div>
														<span class="time">10 phút trước</span>
												</div>
												<div class="online">
														<i class="icon-circle"></i>
												</div>
										</a>
								</li>
				</ul>
			</div>
		</div>
	</div>
</div>
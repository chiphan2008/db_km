<div class="contact-page">
	<div class="container">
		<div class="row justify-content-center">
			<div class=" col-lg-8">
				<div class="content-contact d-sm-flex align-items-sm-stretch">
					<div class="contact-box-info">
						<address>
							<h5>LIÊN HỆ</h5>
							<ol class="list-unstyled">
								<li class="d-flex align-items-star">
									<i class="icon-phone"></i>
									<p>
										@foreach(explode('-',$info_phone) as $value)
											<a title="{{trim($value)}}">{{trim($value)}}</a>
										@endforeach
									</p>

								</li>
								<li class="d-flex align-items-star">
									<i class="icon-mail"></i>
									<p>
										@foreach(explode('-',$info_mail) as $value)
											<a title="{{trim($value)}}">{{trim($value)}}</a>
										@endforeach
									</p>
								</li>
							</ol>
						</address>
					</div>
					<!-- end contact box info -->
					<div class="contact-box-form">
						<form class="form-contact" method="post" action="">
							{{ csrf_field() }}
							<div class="form-group">
								<input type="text" class="form-control" name="name" placeholder="{{trans('global.name')}}" value="{{Auth::guard('web_client')->user() ? Auth::guard('web_client')->user()->full_name : ''}}" required>
							</div>
							<div class="form-group">
								<input type="email" class="form-control" name="email" placeholder="Email" value="{{Auth::guard('web_client')->user() ? Auth::guard('web_client')->user()->email : ''}}" required>
							</div>
							<div class="form-group">
								<input type="tel" class="form-control" name="phone" placeholder="{{trans('global.phone')}}" value="{{Auth::guard('web_client')->user() ? Auth::guard('web_client')->user()->phone : ''}}" required>
							</div>
							<div class="form-group">
								<textarea class="form-control" rows="9" name="content" placeholder="Bạn muốn đăng quảng cáo, muốn truyền đạt ý tưởng kinh doanh của mình cho mọi người, muốn mọi người biết đến doannh nghiệp của mình? Hay bạn có chia sẻ góp ý để giúp Kingmap hoàn thiện hơn?" required></textarea>
							</div>
							<button type="submit" class="btn btn-primary">{{trans('global.send')}}</button>
						</form>
					</div>
					<!-- end contact box form -->
				</div>
			</div>

		</div>
	</div>
</div>

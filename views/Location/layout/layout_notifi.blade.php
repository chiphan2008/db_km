@if($notifi)
<li class="item-notification {{$notifi->read_at?'':'not_read'}}">
	@if($notifi->link)
	<a class="d-flex align-items-center" href="{{$notifi->link}}" title="">
	@else
	<span class="w-100 d-flex align-items-center">
	@endif
		<div class="img">
			<img class="rounded-circle" src="{{$notifi->image}}" alt="" width="46" height="46">
		</div>

		<div class="content ">
			<div class="title" data-toggle="tooltip" data-placement="bottom" data-html="true" title="">
				@if($notifi->template_notifi_id>0)
						{!! trans($notifi->content,json_decode($notifi->data,true)) !!}
				@else
						{!! $notifi->content !!}
				@endif
			</div>
			<span class="time">{{date('d-m-Y H:i:s',strtotime($notifi->updated_at))}}</span>
		</div>
	@if($notifi->link)
	</a>
	@else
	</span>
	@endif
</li>
@endif 


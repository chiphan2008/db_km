@extends('Admin.layout_admin.master_admin')

@section('content')
<div class="page-title">
	<div class="title_left">
		<h3>Google Analytics</h3>
	</div>
</div>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="row tile_count">
			<div class="tile_stats_count text-center col-md-2">
				<span class="count_top"><i class="fa fa-user"></i> {{trans('Admin'.DS.'admin'.DS.'all.current_user')}}</span>
				<div class="count text-center">{{$totalCurrentUser?$totalCurrentUser:0}}</div>
			</div> 

			<div class="tile_stats_count text-center col-md-3">
				<span class="count_top"><i class="fa fa-user"></i> {{trans('Admin'.DS.'admin'.DS.'all.total_user_today')}}</span>
				<div class="count text-center">{{$totalUserRegister?$totalUserRegister:0}}</div>
			</div>

			<div class="tile_stats_count text-center col-md-3">
				<span class="count_top"><i class="fa fa-user"></i> {{trans('Admin'.DS.'admin'.DS.'all.total_location_today')}}</span>
				<div class="count text-center">{{$totalCreatedContent?$totalCreatedContent:0}}</div>
			</div>

			<div class="tile_stats_count text-center col-md-3">
				<span class="count_top"><i class="fa fa-user"></i> {{trans('Admin'.DS.'admin'.DS.'all.total_ctv_today')}}</span>
				<div class="count text-center">{{$totalCTVRegister?$totalCTVRegister:0}}</div>
			</div>
			
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="" role="tabpanel" data-example-id="togglable-tabs">
				<ul id="myTab" class="nav nav-tabs tile_count" role="tablist">
					<li  role="presentation" class="active">
						<a href="#tab_user" id="home-tab" role="tab" data-toggle="tab" class="tab_graph_line" aria-expanded="true">
							<div class="tile_stats_count text-center">
								<span class="count_top"><i class="fa fa-user"></i> {{trans('Admin'.DS.'admin'.DS.'all.user')}}</span>
								<div class="count">{{$totalUser}}</div>
							</div>
						</a>
					</li>

					<li  role="presentation" class="">
						<a href="#tab_session" role="tab" id="profile-tab" data-toggle="tab" class="tab_graph_line" aria-expanded="false">
							<div class="tile_stats_count text-center">
								<span class="count_top"><i class="fa fa-refresh"></i> {{trans('Admin'.DS.'admin'.DS.'all.session')}}</span>
								<div class="count">{{$totalSession}}</div>
							</div>
						</a>
					</li>

					<li  role="presentation" class="">
						<a href="#tab_bounce" role="tab" id="profile-tab" data-toggle="tab" class="tab_graph_line" aria-expanded="false">
							<div class="tile_stats_count text-center">
								<span class="count_top"><i class="fa fa-sign-out"></i> {{trans('Admin'.DS.'admin'.DS.'all.bounce_rate')}}</span>
								<div class="count">{{$totalBounce}}%</div>
							</div>
						</a>
					</li>

					<li  role="presentation" class="">
						<a href="#tab_duration" role="tab" id="profile-tab" data-toggle="tab" class="tab_graph_line" aria-expanded="false">
							<div class="tile_stats_count text-center">
								<span class="count_top"><i class="fa fa-clock-o"></i> {{trans('Admin'.DS.'admin'.DS.'all.session_duration')}}</span>
								<div class="count">{{$totalDuration}}</div>
							</div>
						</a>
					</li>
				</ul>
				<div id="myTabContent" class="tab-content">
					<div role="tabpanel" class="tab-pane fade active in" id="tab_user" aria-labelledby="home-tab">
						<div id="user_graph" style="width:100%; height:300px;"></div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="tab_session" aria-labelledby="profile-tab">
						<div id="session_graph" style="width:100%; height:300px;"></div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="tab_bounce" aria-labelledby="profile-tab">
						<div id="bounce_graph" style="width:100%; height:300px;"></div>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="tab_duration" aria-labelledby="profile-tab">
						<div id="duration_graph" style="width:100%; height:300px;"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-6 col-sm-6 col-xs-12">
		<div class="x_panel">
			<div id="usertype_graph" style="width:100%; height:300px;"></div>
		</div>
	</div>
	<div class="col-md-6 col-sm-6 col-xs-12">
		<div class="x_panel">
			<div id="userbrowse_graph" style="width:100%; height:300px;"></div>
		</div>
	</div>

	<div class="col-md-6 col-sm-6 col-xs-12">
		<div class="x_panel">
			<div id="userdevice_graph" style="width:100%; height:300px;"></div>
		</div>
	</div>

	<div class="col-md-6 col-sm-6 col-xs-12">
		<div class="x_panel">
			<div id="usersocial_graph" style="width:100%; height:300px;"></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="x_panel">
			<h3>Thống kê tại Việt Nam</h3>
			<table class="table table-bordered table-stripped col-md-12">
				<thead>
					<tr>
						<th class="text-left">
							{{trans('Admin'.DS.'admin'.DS.'all.city')}}
							<h4>&nbsp;</h4>
						</th>
						<th class="text-left">
							{{trans('Admin'.DS.'admin'.DS.'all.user')}}
							<h4>{{$totalUserByCity}}</h4>
						</th>
						<th class="text-left">
							{{trans('Admin'.DS.'admin'.DS.'all.session')}}
							<h4>{{$totalSessionByCity}}</h4>
						</th>
						<th class="text-left">
							{{trans('Admin'.DS.'admin'.DS.'all.bounce_rate')}}
							<h4>{{$totalBounceByCity}}%</h4>
						</th>
						<th class="text-left">
							{{trans('Admin'.DS.'admin'.DS.'all.session_duration')}}
							<h4>{{$totalDurationByCity}}</h4>
						</th>
					</tr>
				</thead>
				<tbody>
					@if($dataUserByCity)
					@foreach($dataUserByCity as $data)
					<tr>
						<td>{{$data['city']}}</td>
						<td>{{$data['user']}}</td>
						<td>{{$data['session']}}</td>
						<td>{{$data['bounce']}}%</td>
						<td>{{$data['duration']}}</td>
					</tr>
					@endforeach
					@endif
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection

@section('CSS')
	<style>
		.tile_count{
			margin-top: 0;
			margin-bottom: 0;
		}
	</style>
@endsection


@section('JS')
<script src="{{asset('template/vendors/raphael/raphael.min.js')}}"></script>
<script src="{{asset('template/vendors/morris.js/morris.min.js')}}"></script>
<script src="{{asset('template/vendors/echarts/dist/echarts.min.js')}}"></script>

<script>
	if ($('#user_graph').length ){
		var user_graph = Morris.Line({
			element: 'user_graph',
			xkey: 'date',
			ykeys: ['user'],
			labels: ['{{trans('Admin'.DS.'admin'.DS.'all.user')}}'],
			xLabelAngle: 0,
			xLabelFormat: function (x) {
				var ds = new Date(x);
				return setupDate(ds.getDate())+'-'+setupMonth(ds.getMonth());
			},
			dateFormat: function (x) {
				var ds = new Date(x);
				return setupDate(ds.getDate())+'-'+setupMonth(ds.getMonth())+'-'+ds.getFullYear();
			},
			hideHover: 'auto',
			lineColors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
			data: {!! json_encode($dataUser) !!},
			resize: true
		}); 
	}

	if ($('#session_graph').length ){
		var session_graph = Morris.Line({
			element: 'session_graph',
			xkey: 'date',
			ykeys: ['session'],
			labels: ['{{trans('Admin'.DS.'admin'.DS.'all.session')}}'],
			xLabelAngle: 0,
			xLabelFormat: function (x) {
				var ds = new Date(x);
				return setupDate(ds.getDate())+'-'+setupMonth(ds.getMonth());
			},
			dateFormat: function (x) {
				var ds = new Date(x);
				return setupDate(ds.getDate())+'-'+setupMonth(ds.getMonth())+'-'+ds.getFullYear();
			},
			hideHover: 'auto',
			lineColors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
			data: {!! json_encode($dataSession) !!},
			resize: true
		}); 
	}

	if ($('#bounce_graph').length ){
		var bounce_graph = Morris.Line({
			element: 'bounce_graph',
			xkey: 'date',
			ykeys: ['bounce'],
			labels: ['{{trans('Admin'.DS.'admin'.DS.'all.bounce_rate')}}'],
			xLabelAngle: 0,
			xLabelFormat: function (x) {
				var ds = new Date(x);
				return setupDate(ds.getDate())+'-'+setupMonth(ds.getMonth());
			},
			yLabelFormat: function (y) {
				return y+'%';
			},
			dateFormat: function (x) {
				var ds = new Date(x);
				return setupDate(ds.getDate())+'-'+setupMonth(ds.getMonth())+'-'+ds.getFullYear();
			},
			hideHover: 'auto',
			lineColors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
			data: {!! json_encode($dataBounce) !!},
			resize: true
		}); 
	}

	if ($('#duration_graph').length ){
		var duration_graph = Morris.Line({
			element: 'duration_graph',
			xkey: 'date',
			ykeys: ['duration'],
			labels: ['{{trans('Admin'.DS.'admin'.DS.'all.session_duration')}}'],
			xLabelAngle: 0,
			xLabelFormat: function (x) {
				var ds = new Date(x);
				return setupDate(ds.getDate())+'-'+setupMonth(ds.getMonth());
			},
			yLabelFormat: function (y) {
				totalSeconds = y;
				var hours = Math.floor(totalSeconds / 3600);
				totalSeconds %= 3600;
				var minutes = Math.floor(totalSeconds / 60);
				var seconds = totalSeconds % 60;
				if(minutes<10) minutes = '0' + minutes;
				if(seconds<10) seconds = '0' + seconds;
				return hours+':'+ minutes+':'+ seconds;
			},
			dateFormat: function (x) {
				var ds = new Date(x);
				return setupDate(ds.getDate())+'-'+setupMonth(ds.getMonth())+'-'+ds.getFullYear();
			},
			hideHover: 'auto',
			lineColors: ['#26B99A', '#34495E', '#ACADAC', '#3498DB'],
			data: {!! json_encode($dataDuration) !!},
			resize: true
		}); 
	}

	if ($('#usertype_graph').length ){
		Morris.Donut({
			element: 'usertype_graph',
			data: [
				{label: "{{trans('Admin'.DS.'admin'.DS.'all.return_user')}}", value: {{$userByType['return']}}},
				{label: "{{trans('Admin'.DS.'admin'.DS.'all.new_user')}}", value: {{$userByType['new']}}}
			],
			colors: ['#34495E','#26B99A', '#ACADAC', '#3498DB'],
			resize: true
		});
	}


	if ($('#userbrowse_graph').length ){

		var dataBrowse =  {!! json_encode($userByBrowse) !!};
		var arrLabel = [];
		var arrData = [];
		dataBrowse.forEach(function(value,key){
			arrLabel.push(value.browser);
			arrData.push({
				'value'	: value.user,
				'label'	: value.browser
			})
		});
		Morris.Donut({
			element: 'userbrowse_graph',
			data: arrData,
			colors: [
			'#34495E','#26B99A', '#ACADAC', '#3498DB',
			'#495E34','#B99A26', '#DACACA', '#98DB34',
			'#5E3449','#9A26B9', '#CAADAC', '#DB3498'
			],
			resize: true
		});
	} 


	if ($('#userdevice_graph').length ){

		var dataBrowse =  {!! json_encode($userByDevice) !!};
		var arrLabel = [];
		var arrData = [];
		dataBrowse.forEach(function(value,key){
			arrLabel.push(value.device);
			arrData.push({
				'value'	: value.user,
				'label'	: value.device
			})
		});
		Morris.Donut({
			element: 'userdevice_graph',
			data: arrData,
			colors: [
			'#34495E','#26B99A', '#ACADAC', '#3498DB',
			'#495E34','#B99A26', '#DACACA', '#98DB34',
			'#5E3449','#9A26B9', '#CAADAC', '#DB3498'
			],
			resize: true
		});
	} 

	if ($('#usersocial_graph').length ){

		var dataBrowse =  {!! json_encode($userBySocial) !!};
		var arrLabel = [];
		var arrData = [];
		dataBrowse.forEach(function(value,key){
			arrLabel.push(value.social);
			arrData.push({
				'value'	: value.user,
				'label'	: value.social
			})
		});
		Morris.Donut({
			element: 'usersocial_graph',
			data: arrData,
			colors: [
			'#34495E','#26B99A', '#ACADAC', '#3498DB',
			'#495E34','#B99A26', '#DACACA', '#98DB34',
			'#5E3449','#9A26B9', '#CAADAC', '#DB3498'
			],
			resize: true
		});
	} 

	function setupMonth(month){
		month = month+1;
		if(month<10){
			month = '0'+month;
		}
		return month;
	}
	function setupDate(date){
		if(date<10){
			date = '0'+date;
		}
		return date;
	}
	$(function(){
		$('a.tab_graph_line').on('shown.bs.tab', function(e) {
			var target = $(e.target).attr("href") // activated tab

			switch (target) {
				case "#tab_user":
					user_graph.redraw();
					$(window).trigger('resize');
					break;
				case "#tab_session":
					session_graph.redraw();
					$(window).trigger('resize');
					break;
				case "#tab_bounce":
					bounce_graph.redraw();
					$(window).trigger('resize');
					break;
				case "#tab_duration":
					duration_graph.redraw();
					$(window).trigger('resize');
					break;
			}
		});
		$("#usertype_graph svg text").css("font-weight", "500");
		$("#usertype_graph svg text tspan").css("font-weight", "500");

		$("#userbrowse_graph svg text").css("font-weight", "500");
		$("#userbrowse_graph svg text tspan").css("font-weight", "500");

		$("#userdevice_graph svg text").css("font-weight", "500");
		$("#userdevice_graph svg text tspan").css("font-weight", "500");
	})
</script>
@endsection
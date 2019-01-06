@extends('Admin.layout_admin.master_admin')

@section('content')
<table id="routes-table" class="table table-bordered table-responsive">
       <thead>
                <tr>
                		<!-- <th>123</th> -->
                    <th>URL</th>
                    <th>Method</th>
                    <th>Controller</th>
                    <th>Action</th>
                </tr>
       </thead>
       <tbody>
                @foreach ($routes as $route )
									@if(strpos($route->uri,"apis") !== false)
									  @php  
		                	$action = $route->getAction();
		                	$controller_name = '';
		                @endphp
			              @if(isset($action['controller']))
			              	@php
			                	$controller_name = str_replace('@'.$route->getActionMethod(),'',str_replace($action['namespace'].'\\','',$action['controller']));
			                @endphp
	                    <tr>
	                    		<!-- <td>{{$route->getPrefix()}}</td> -->
	                        <td>{{LOCATION_URL.'/'.$route->uri}}</td>
	                        <td>{{$route->methods[0]}}</td>
	                        <td>{{$controller_name}}</td>
	                        <td>{{$route->getActionMethod()}}</td>
	                    </tr>
                    @endif
                  @endif
                @endforeach
        </tbody>
</table>

@endsection
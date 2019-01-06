@extends('Admin.layout_admin.master_admin')

@section('content')
<div class="row text-center">
	<div class="col-md-12">
<!-- 		<h1 class="text-danger">Error {{$code}}</h1> -->
		@include("Admin.error.".$code)
	</div>
</div>
@endsection
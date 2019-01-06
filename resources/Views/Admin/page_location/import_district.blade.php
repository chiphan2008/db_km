@extends('Admin..layout_admin.master_admin')

@section('content')
<form action="{{route('import_district',['id_city' => $id_city])}}" method="post" accept-charset="utf-8">
	{{ csrf_field() }}
	<p><textarea name="list_district" class="form-control" rows="23" cols="50"></textarea></p>
	<p class="text-center"><button class="btn btn-success" type="submit">Submit</button></p>
</form>
@endsection
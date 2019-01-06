@foreach($category_item as $value )
    <option
            value="{{$value->id}}">{{$value->name}}
    </option>
@endforeach
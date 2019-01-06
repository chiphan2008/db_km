@foreach($category_services as $value)
    <div class="col-md-4 col-sm-3 col-xs-12" style="padding-left: 0px;">
        <div class="checkbox">
            <label style="padding-left: 0px;">
                <input type="checkbox" class="flat" name="service[]" value="{{$value->id_service_item}}"> {{$value->_service_item->name}}
            </label>
        </div>
    </div>
@endforeach
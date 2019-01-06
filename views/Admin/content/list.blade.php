@extends('Admin.layout_admin.master_admin')

@section('content')
<div class="form-inline dt-bootstrap no-footer">
    <div class="row">
        <div class="col-sm-12">
            <div class="x_panel">
                <form class="form-horizontal form-label-left" method="get" action="{{url()->current()}}">
                    <div class="row search_group">
                        <div class="col-md-4">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('global.keyword')}}</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <input type="text" id="keyword" value="{{ isset($keyword) ? $keyword : '' }}" name="keyword"
                                       class="form-control" placeholder="{{trans('global.keyword')}}" style="width:100%">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('Admin'.DS.'content.category')}}</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <select class="form-control" name="category" onchange="get_category_item(this);" id="category" style="width:100%">
                                    <option value="">-- {{trans('Admin'.DS.'content.category')}} --</option>
                                    @foreach($list_category as $value )
                                    <option
                                        value="{{$value->id}}" {{ isset($category) && $category == $value->id ? 'selected' : '' }}>{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('Admin'.DS.'content.cat_item')}}</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <select class="form-control" name="category_item" id="category_item" style="width:100%">
                                    <option value="">-- {{trans('Admin'.DS.'content.cat_item')}} --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('Admin'.DS.'content.update_by')}}</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <select class="form-control" name="users"  id="users" style="width:100%">
                                    <option value="">-- {{trans('global.admin')}} --</option>
                                    @foreach($all_user as $value )
                                    <option
                                        value="{{$value->id}}" {{ isset($user) && $user == $value->id ? 'selected' : '' }}>{{$value->full_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('Admin'.DS.'content.create_by')}}</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <select class="form-control" name="client"  id="client" style="width:100%">
                                    <option value="">-- {{trans('global.user')}} --</option>
                                    @foreach($all_client as $value )
                                    <option
                                        value="{{$value->id}}" {{ isset($client) && $client == $value->id ? 'selected' : '' }}>{{$value->id}} - {{$value->full_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('Admin'.DS.'content.country')}}</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <select class="form-control" name="country" onchange="get_location(this, 'city')" id="country" style="width:100%">
                                    <option value="">-- {{trans('Admin'.DS.'content.country')}} --</option>
                                    @foreach($list_country as $value )
                                    <option
                                        value="{{$value->id}}" {{ isset($country) && $country == $value->id ? 'selected' : '' }}>{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('Admin'.DS.'content.city')}}</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <select class="form-control" name="city" onchange="get_location(this, 'district')" id="city" style="width:100%">
                                    <option value="">-- {{trans('Admin'.DS.'content.city')}} --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('Admin'.DS.'content.district')}}</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <select class="form-control" name="district" id="district" style="width:100%">
                                    <option value="">-- {{trans('Admin'.DS.'content.district')}} --</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('global.from')}}</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <div class='input-group date' style="margin-bottom: 0px" id='date_from'>
                                    <input type='text' class="form-control" name="date_from" value="{{ isset($date_from) ? $date_from : '' }}" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('global.to')}}</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <div class='input-group date' style="margin-bottom: 0px" id='date_to'>
                                    <input type='text' class="form-control" name="date_to" value="{{ isset($date_to) ? $date_to : '' }}" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label col-md-4 col-sm-4 col-xs-12">{{trans('Admin'.DS.'content.moderation')}}</label>
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <select class="form-control" name="moderation" id="moderation" style="width:100%">
                                    <option value="">-- {{trans('Admin'.DS.'content.moderation')}} --</option>
                                    <option
                                        value="in_progress" {{ isset($moderation) && $moderation == 'in_progress' ? 'selected' : '' }}>{{trans('Admin'.DS.'content.in_progress')}}</option>
                                <option
                                    value="request_publish" {{ isset($moderation) && $moderation == 'request_publish' ? 'selected' : '' }}>
                                    {{trans('Admin'.DS.'content.request_publish')}}</option>
                            <option
                                value="reject_publish" {{ isset($moderation) && $moderation == 'reject_publish' ? 'selected' : '' }}>
                                {{trans('Admin'.DS.'content.reject_publish')}}</option>
                        <option value="publish" {{ isset($moderation) && $moderation == 'publish' ? 'selected' : '' }}>
                                {{trans('Admin'.DS.'content.publish')}}</option>
                    <option value="trash" {{ isset($moderation) && $moderation == 'trash' ? 'selected' : '' }}>{{trans('Admin'.DS.'content.trash')}}</option>
            </select>
        </div>
    </div>
</div>
<div class="col-md-12 text-center" style="padding-top: 10px;width:100%;">
    <input type="hidden" name="sort" id="sort" value="">
    <button type="submit" class="btn btn-success">{{trans('global.search')}}</button>
</div>

</form>
</div>
</div>
<form id="change_status_content" action="{{route('changeStatus')}}" method="post">
    <div class="col-sm-12">
        <div class="pull-left">
            {{ csrf_field() }}
            @if(Auth::guard('web')->user()->hasRole('content') == false)
            <input type="hidden" name="current_url" value="{{$_SERVER['REQUEST_URI']}}">
            <div class="input-group">
                <select class="form-control" name="type_status">
                    <option value="">-- {{trans('Admin'.DS.'content.status')}} --</option>
                    <option value="0">{{trans('global.inactive')}}</option>
                    <option value="1">{{trans('global.active')}}</option>
                    @if(Auth::guard('web')->user()->hasRole('super_admin') == true)
                    <option value="2">{{trans('Admin'.DS.'content.del_content')}}</option>
                    @endif
                </select>
                <span class="input-group-btn">
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirm_status">{{trans('Admin'.DS.'content.update')}}</button>
                </span>
            </div>
            @endif
        </div>
        <div class="pull-left">
            <label style="padding-top: 8px">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total {{$list_content->total()}} results.
            </label>
        </div>


        @if(Auth::guard('web')->user()->can('add_Content'))
        <div class="pull-right">
            <div class="btn-group">
                <button type="button" class="btn btn-primary">{{trans('Admin'.DS.'content.add')}}</button></button>
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                        aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" style="right: 0;left: auto;height: 500px;overflow-y: auto;" role="menu">
                    {{--<li><a href="{{route('add_content',['category_type' => 'food'])}}">Food</a></li>--}}
					{{--<li><a href="{{route('add_content',['category_type' => 'bank'])}}">Bank</a></li>--}}
					@foreach($list_category as $value)
						<li><a href="{{route('add_content',['category_type' => $value->machine_name])}}">{{$value->name}}</a></li>
					@endforeach
				</ul>
			</div>
		</div>
        @endif
        @if (Auth::guard('web')->user()->hasRole('super_admin') || Auth::guard('web')->user()->hasRole('admin'))
        <div class="pull-right"  style="margin-right: 15px;">
            <div class="btn-group">
                <button type="button" class="btn btn-success">{{trans('Admin'.DS.'content.change_content')}}</button>
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                                            aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" style="right: 0;left: auto;" role="menu">
                    <li><a href="{{route('change_owner')}}" class="btn btn-block">{{trans('Admin'.DS.'content.owner_change')}}</a></li>
                    <li><a href="{{route('change_ctv')}}" class="btn btn-block">{{trans('Admin'.DS.'content.ctv_change')}}</a></li>
                </ul>
            </div>
        </div>
        @endif

					@if(Auth::guard('web')->user()->hasRole('content') == false && Auth::guard('web')->user()->hasRole('content-update') == false && Auth::guard('web')->user()->can('add_Content'))
						<div class="pull-right"  style="margin-right: 15px;">
							<div class="btn-group">
								<button type="button" class="btn btn-success">Clone Content</button>
								<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
												aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" style="right: 0;left: auto;" role="menu">
                        <li><a href="{{route('insert_content','vinalo')}}">Vinalo</a></li>
                        <li><a href="{{route('insert_content','foody')}}">Foody</a></li>
                        <li><a href="{{route('insert_content','replicate_foody')}}">Replicate Foody</a></li>
                        <li><a href="{{route('insert_content','mytour')}}">MyTour</a></li>
                        <li><a href="{{route('insert_content','vietbando')}}">VietBanDo</a></li>
                        <li><a href="{{route('insert_content','sheis')}}">SheIS</a></li>
                        <li><a href="{{route('insert_content','bank')}}">Bank</a></li>
                        <li><a href="{{route('insert_content','offpeak')}}">OffPeak</a></li>
                        <li><a href="{{route('clone_thongtincongty')}}">Thông tin công ty</a></li>
                    </ul>
            </div>
        </div>
        @endif

        @if(Auth::guard('web')->user()->hasRole('content') == false && Auth::guard('web')->user()->hasRole('content-update') == false && Auth::guard('web')->user()->can('add_Content'))
        <div class="pull-right"  style="margin-right: 15px;">
            <div class="btn-group">
                <button type="button" class="btn btn-success">{{trans('Admin'.DS.'content.import_excel')}}</button>
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                        aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" style="right: 0;left: auto;" role="menu">
                    @foreach($list_category as $value)
                    <li><a href="{{route('import_content',['category_type' => $value->machine_name])}}">{{$value->name}}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="pull-right" style="margin-right: 15px;">
            <select style="float: right; margin-bottom: 5px" class="form-control" onchange="change_pagination(this.value,'{{\Route::currentRouteName()}}')">
                <option value="10" {{\Session::has('pagination.'.\Route::currentRouteName()) && session('pagination.'.\Route::currentRouteName()) == 10 ? 'selected' : ''}}>10</option>
                <option value="20" {{\Session::has('pagination.'.\Route::currentRouteName()) && session('pagination.'.\Route::currentRouteName()) == 20 ? 'selected' : ''}}>20</option>
                <option value="50" {{\Session::has('pagination.'.\Route::currentRouteName()) && session('pagination.'.\Route::currentRouteName()) == 50 ? 'selected' : ''}}>50</option>
                <option value="100" {{\Session::has('pagination.'.\Route::currentRouteName()) && session('pagination.'.\Route::currentRouteName()) == 100 ? 'selected' : ''}}>100</option>
            </select>
        </div>
    </div>
    {{--///////////////////////////////////////////// --}}
    <div class="col-sm-12">
        @if(session('status'))
        <div class="alert alert-success alert-dismissible fade in" style="color: #3c763d;background-color: #dff0d8;border-color: #d6e9c6;" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            {!! session('status') !!}
        </div>
        @endif
        <table id="datatable" class="table table-striped table-bordered dataTable no-footer" role="grid"
               aria-describedby="datatable_info">
            <thead>
                <tr role="row">
                    <th>
                        <input type="checkbox" onclick="toggle_checkbox(this)"/>
                    </th>
                    <th data-sort="id" tabindex="0"
                        aria-controls="datatable" rowspan="1" colspan="1"
                        aria-label="Name: activate to sort column ascending">ID
                    </th>
                    <th data-sort="name" tabindex="0"
                        aria-controls="datatable" rowspan="1" colspan="1"
                        aria-label="Name: activate to sort column ascending">{{trans('Admin'.DS.'content.name')}}
                    </th>
                    <th data-sort="type" tabindex="0"
                        aria-controls="datatable" rowspan="1" colspan="1"
                        aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'content.type')}}
                    </th>
                    <th data-sort="type" tabindex="0"
                        aria-controls="datatable" rowspan="1" colspan="1"
                        aria-label="Like: activate to sort column ascending">{{trans('Admin'.DS.'content.like')}}
                    </th>
                    <th data-sort="created_at"
                        tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                        aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'content.create_at')}}
                    </th>
                    <th data-sort="created_at"
                        tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                        aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'content.create_by')}}
                    </th>
                    <th data-sort="updated_at"
                        tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                        aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'content.update_by')}}
                    </th>
                    <th data-sort="active"
                        tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                        aria-label="Name machine: activate to sort column ascending">{{trans('Admin'.DS.'content.status')}}
                    </th>
                    <th data-sort="moderation"
                        tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                        aria-label="Create by: activate to sort column ascending">{{trans('Admin'.DS.'content.moderation')}}
                    </th>
                    <th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1"
                        aria-label="Operation: activate to sort column ascending">{{trans('global.operation')}}
                    </th>
                </tr>
            </thead>

            <tbody>
                @if (isset($list_content))
                @foreach ($list_content as $content)
                <tr role="row" class="odd">
                    <td>
                        <input type="checkbox" name="change_status[]" value="{{$content->id}}">
                    </td>
                    <td>{{$content->id}}</td>
                    <td>{{$content->name}}</td>
                    <td>@lang($content->_category_type->name)</td>
                    @if((Auth::guard('web')->user()->can('edit_Content') && Auth::guard('web')->user()->id == $content->created_by) ||
                        Auth::guard('web')->user()->hasRole('super_admin') || Auth::guard('web')->user()->hasRole('admin') ||
                        Auth::guard('web')->user()->hasRole('admin_content') || Auth::guard('web')->user()->hasRole('content-update'))
                    <td><input type="number" min="0" step="1" onchange="changeLike(this)" class="like_update form-control" data-id="{{$content->id}}" value="{{$content->like}}"></td>
                    @else
                    <td>{{$content->like}}</td>
                    @endif
                    <td>{{date('d-m-Y H:i',strtotime($content->created_at))}}</td>
                    <td>
                        @if($content->type_user == 1)
                        {{$content->_created_by?$content->_created_by->full_name:''}}
                        @else
                        {{$content->_created_by_client?$content->_created_by_client->full_name:''}}
                        @endif
                    </td>
                    <td>
                        @if($content->type_user_update == 1)
                        {{$content->_updated_by?$content->_updated_by->full_name:''}}
                        @else
                        {{$content->_updated_by_client?$content->_updated_by_client->full_name:''}}
                        @endif
                    </td>
                    <td>
                        @if($content->active == 1)
                        <b class="text-success">{{trans('global.active')}}</b>
                        @else
                        <b class="text-danger">{{trans('global.inactive')}}</b>
                        @endif
                    </td>
                    <td>@lang(str_replace("_"," ",$content->moderation))</td>
                    <td>
                        @if((Auth::guard('web')->user()->can('edit_Content') && Auth::guard('web')->user()->id == $content->created_by) ||
                        Auth::guard('web')->user()->hasRole('super_admin') || Auth::guard('web')->user()->hasRole('admin') ||
                        Auth::guard('web')->user()->hasRole('admin_content') || Auth::guard('web')->user()->hasRole('content-update'))
                        <a href="{{route('update_content', ['category_type'=>$content->_category_type->machine_name,'id' => $content->id])}}">{{trans('global.edit')}}</a>
                        / <a href="{{route('update_category_of_content', ['id' => $content->id])}}">{{trans('global.change')}}</a>
                        @endif
                        @if((Auth::guard('web')->user()->can('delete_Content') &&
                        Auth::guard('web')->user()->id == $content->created_by) || Auth::guard('web')->user()->hasRole('super_admin')
                        || Auth::guard('web')->user()->hasRole('admin'))
                        / <a href="" data-toggle="modal" data-target="#delete_content" onclick='deleteContent("{{$content->name}}","{{route('delete_content', ['id' => $content->id])}}")'>{{trans('global.delete')}}</a>
                        @endif
                        @if($content->moderation == 'publish' && $content->active == 1 && Auth::guard('web')->user()->hasRole('super_admin')
                        || Auth::guard('web')->user()->hasRole('admin'))
                        / <a href="{{route('notify_content',['id'=>$content->id])}}">{{trans('global.notifycation')}}</a>
                        @endif
                    </td>
                </tr>
                @endforeach
                @else
                <tr role="row" class="odd">
                    <td colspan="6">{{trans('global.no_data')}}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</form>

<div class="modal fade" id="delete_content" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">{{trans('Admin'.DS.'content.del_content')}}</h3>
            </div>
            <div class="modal-body">
                <p id="name_client"></p>
            </div>
            <div class="modal-footer">
                <a id="link_delete" href="" class="btn btn-primary">{{trans(DS.'global.delete')}}</a>
                <button type="button" data-dismiss="modal" class="btn">{{trans(DS.'global.cancel')}}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm_status" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">{{trans('Admin'.DS.'content.change_content')}}</h3>
            </div>
            <div class="modal-body">
                <p>{{trans('Admin'.DS.'content.confirm_change')}}</p>
            </div>
            <div class="modal-footer">
                {{--<a id="link_delete" href="" class="btn btn-primary">{{trans(DS.'global.save')}}</a>--}}
                <button type="button" data-dismiss="modal"  onclick="$('#change_status_content').submit()" class="btn btn-primary">{{trans(DS.'global.save')}}</button>
                <button type="button" data-dismiss="modal" class="btn">{{trans(DS.'global.cancel')}}</button>
            </div>
        </div>
    </div>
</div>

<div class="col-sm-12" style="text-align: right">
    {!! $list_content->appends([
    'keyword' => isset($keyword) ? $keyword : '',
    'category'=> isset($category) ? $category : '',
    'users'=> isset($user) ? $user : '',
    'client'=> isset($client) ? $client : '',
    'moderation'=> isset($moderation) ? $moderation : '',
    'date_from'=> isset($date_from) ? $date_from : '',
    'date_to'=> isset($date_to) ? $date_to : '',
    'country'=> isset($country) ? $country : '',
    'city'=> isset($city) ? $city : '',
    'district'=> isset($district) ? $district : '',
    'category_item'=> isset($category_item) ? $category_item : '',
    ])->render() !!}
</div>
</div>
</div>
<style>
    .search_group .col-md-4{
        padding-top: 10px;
    }
    .search_group input,
    .search_group select{
        min-width: 150px;
    }
    .like_update{
        max-width:90px;
    }
</style>
@endsection

@section('JS')
<script type="text/javascript">
    var old_country = '{{$country}}';
    var old_city = '{{$city}}';
    var old_district = '{{$district}}';
    var old_category_item = '{{$category_item}}';
    var json_sort = [];
    if (old_country != ''){
    old_country = parseInt(old_country);
    }
    if (old_city != ''){
    old_city = parseInt(old_city);
    }
    if (old_district != ''){
    old_district = parseInt(old_district);
    }
    if (old_category_item != ''){
    old_category_item = parseInt(old_category_item);
    }

    function toggle_checkbox(source) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
    }

    function deleteContent(name, link)
    {
    $('#name_client').text('{{trans('valid.confirm_delete_content')}}' + name);
			$('#link_delete').attr('href', link);
    }

    $(function () {
    setTimeout(function(){
    @if ($country != '')
            $("#country").trigger("change");
    @endif

            @if ($city != '')
            $("#city").trigger("change");
    @endif

            @if ($category != '')
            $("#category").trigger("change");
    @endif
    }, 100);
    $('#date_from').datetimepicker({
				format: 'YYYY-MM-DD',
    });
    $('#date_to').datetimepicker({
				format: 'YYYY-MM-DD',
    });
    });
    function get_category_item(obj){
    var value = $(obj).val();
    var CSRF_TOKEN = $('input[name="_token"]').val();
    $('#category_item').html('<option value="">-- {{trans('Admin'.DS.'content.cat_item')}} --</option>');
    $.ajax({
    type: "POST",
				data: {value: value, _token: CSRF_TOKEN},
				url:  '/admin/content/ajaxCategoryItem',
            success: function (data) {
            $("#category_item").html(data);
					$("#category_item option").each(function(key,elem){
						if($(elem).attr('value') == old_category_item){
							$(elem).attr('selected',true);
            }
            })
            }
    })
    }

    function get_location(obj, type) {
    var CSRF_TOKEN = $('input[name="_token"]').val();
    var value = $(obj).val();
    if (type == 'city') {
        $('#district').html('<option value="">-- {{trans('Admin'.DS.'content.district')}} --</option>');
    }
    $.ajax({
    type: "POST",
        data: {value: value, type: type, _token: CSRF_TOKEN},
        url:  '/admin/content/ajaxLocation',
            success: function (data) {
            $("#" + type).html(data);
          if(type=='city'){
          	$("#city option").each(function(key,elem){
							if($(elem).attr('value') == old_city){
								$(elem).attr('selected',true);
            }
            });
            $("#city").trigger("change");
            }
            if (type == 'district'){
            $("#district option").each(function(key, elem){
            if ($(elem).attr('value') == old_district){
            $(elem).attr('selected', true);
            }
            })
            }
            }
    })
    }
    function changeLike(obj){
        var id = $(obj).attr('data-id');
        var like = $(obj).val();
        $.ajax({
            url : '/admin/content/updateLike',
            type: 'POST',
            data: {
                '_token':$('input[name="_token"]').val(),
                'id':id,
                'like':like
            },
            success: function(res){
                if(res.error){
                    toastr.error(res.message,"Message")
                }else{
                    toastr.success("Content has been update like.","Message");
                }
            }
        });
    }
</script>
@endsection

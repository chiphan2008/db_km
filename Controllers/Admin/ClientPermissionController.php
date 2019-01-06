<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\ClientPermission;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;
use Session;

class ClientPermissionController extends BaseController
{

  public function getListClientPermission(Request $request)
  {
    $per_page = Session::has('pagination.'.\Route::currentRouteName()) ? session('pagination.'.\Route::currentRouteName()) : 10;
    $all_permission = ClientPermission::with('_created_by');
    $sort = $request->sort?$request->sort:'';
    $input = request()->all();

    if (isset($input['keyword'])) {
      $keyword = $input['keyword'];
    } else {
      $keyword = '';
    }

    if (isset($keyword) && $keyword != '') {

      $all_permission->Where(function ($query) use ($keyword) {
        $query->orWhere('machine_name', 'LIKE', '%' . $keyword . '%');
        $query->orWhere('name', 'LIKE', '%' . $keyword . '%');
      });
    }

    $arr_sort = [];
    if($sort!=''){
      $listSort = explode(',',$sort);
      foreach ($listSort as $key => $value) {
        $item = explode('-',$value);
        if(isset($item[1])){
          $arr_sort[$item[0]] = $item[1];
        }
      }
    }
    if(count($arr_sort)){
      foreach ($arr_sort as $key => $value) {
        $all_permission->orderBy($key,$value);
      }
    }else{
      $all_permission->orderBy('id','desc');
    }

    $list_permission = $all_permission->paginate($per_page);
    return view('Admin.client_permission.list', ['list_permission' => $list_permission, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);

  }

  public function getAddClientPermission()
  {
    return view('Admin.client_permission.add');
  }

  function postAddClientPermission(Request $request)
  {
    $rules = [
      'name' => 'required',
      'machine_name' => 'required',
    ];
    $messages = [
      'name.required' => trans('valid.name_required'),
      'machine_name.required' => trans('valid.machine_name_required'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {
        $permission = new ClientPermission();
        $permission->name = $request->name;
        $permission->machine_name = $request->machine_name;
        $permission->description = $request->description?$request->description:'';
        $permission->created_by = Auth::guard('web')->user()->id;
        $permission->updated_by = Auth::guard('web')->user()->id;
        $permission->save();
        return redirect()->route('list_client_permission')->with(['status' => 'Permission đã được thêm thành công ']);
    }
  }

  public function getUpdateClientPermission($id)
  {
    $permission = ClientPermission::find($id);
    return view('Admin.client_permission.update', ['permission' => $permission]);
  }

  function postUpdateClientPermission(Request $request, $id)
  {
    $permission = ClientPermission::find($id);
    $rules = [
      'name' => 'required'
    ];
    $messages = [
      'name.required' => trans('valid.name_required')
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {
      $permission->name = $request->name;
      $permission->description = $request->description?$request->description:'';
      $permission->updated_by = Auth::guard('web')->user()->id;
      $permission->save();
      return redirect()->route('list_client_permission')->with(['status' => 'Permission đã được cập nhật thành công ']);
    }
  }

  public function getDeleteClientPermission($id)
  {
    $permission = ClientPermission::find($id);
    $name = $permission->name;
    $permission->delete();
    return redirect()->route('list_client_permission')->with(['status' => 'Permission ' . $name . ' đã xóa thành công ']);
  }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;
use Session;

class PermissionController extends BaseController
{

  public function getListPermission(Request $request)
  {
    $per_page = Session::has('pagination.'.\Route::currentRouteName()) ? session('pagination.'.\Route::currentRouteName()) : 10;
    $all_permission = Permission::with('_created_by');
    $sort = $request->sort?$request->sort:'';
    $input = request()->all();

    if (isset($input['keyword'])) {
      $keyword = $input['keyword'];
    } else {
      $keyword = '';
    }

    if (isset($keyword) && $keyword != '') {

      $all_permission->Where(function ($query) use ($keyword) {
        $query->where('display_name', 'LIKE', '%' . $keyword . '%');
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
    return view('Admin.permission.list', ['list_permission' => $list_permission, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);

  }

  public function getAddPermission()
  {
    $modules = [];
    $arr_prevent_module = ['Login', 'ForgotPassword', 'ResetPassword', 'Error', 'Base'];
    foreach(glob(app_path().'/Http/Controllers/Admin/*.php') as $file) {
        $file = str_replace(app_path().'/Http/Controllers/Admin/','', $file);
        $file = str_replace('.php','', $file);
        $file = str_replace('Controller','', $file);
        if(!in_array($file, $arr_prevent_module)){
          $arr_tmp['name'] = $file;
          $arr_tmp['value'] = $file;
          $modules[] = $arr_tmp;
        }
    }
    return view('Admin.permission.add',['modules' => $modules]);
  }

  function postAddPermission(Request $request)
  {
    $rules = [
      'display_name' => 'required'
    ];
    $messages = [
      'display_name.required' => 'Tên là trường bắt buộc'
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {
        $check_save = true;
        if($request->type !== 'all'){
            $permission = new Permission();
            $permission->display_name = $request->display_name;
            $permission->machine_name = $request->type.'_'.$request->module;
            $permission->name = $request->type.'_'.$request->module;
            $permission->type = $request->type;
            $permission->description = $request->description;
            $permission->module = $request->module;
            if(isset($request->white_list)){
              $permission->white_list = json_encode(array_values($request->white_list));
            }else{
              $permission->white_list = json_encode([]);
            }
            if(isset($request->black_list)){
              $permission->black_list = json_encode(array_values($request->black_list));
            }else{
              $permission->black_list = json_encode([]);
            }
            $permission->created_by = Auth::guard('web')->user()->id;
            $permission->updated_by = Auth::guard('web')->user()->id;
            $check_save &= $permission->save();
        }else{
            $types = ['view', 'add', 'edit', 'delete'];
            foreach ($types as $key2 => $type) {
                $permission = new Permission();
                $permission->display_name = ucwords($type.' '.$request->display_name);
                $permission->machine_name = $type.'_'.$request->module;
                $permission->name = $type.'_'.$request->module;
                $permission->type = $type;
                $permission->description = $request->description;
                $permission->module = $request->module;
                if(isset($request->white_list)){
                  $permission->white_list = json_encode(array_values($request->white_list));
                }else{
                  $permission->white_list = json_encode([]);
                }
                if(isset($request->black_list)){
                  $permission->black_list = json_encode(array_values($request->black_list));
                }else{
                  $permission->black_list = json_encode([]);
                }
                $permission->created_by = Auth::guard('web')->user()->id;
                $permission->updated_by = Auth::guard('web')->user()->id;
                $check_save &= $permission->save();
            }
        }
        if( $check_save ) {
          return redirect()->route('list_permission')->with(['status' => 'Permission đã được thêm thành công ']);
        } else {
          $errors = new MessageBag(['error' => 'Không tạo được permission']);
          return redirect()->back()->withErrors($errors)->withInput();
        }
    }
  }

  public function getUpdatePermission($id)
  {
    $permission = Permission::find($id);
    $modules = [];
    $arr_prevent_module = ['Login', 'ForgotPassword', 'ResetPassword', 'Error', 'Base'];
    foreach(glob(app_path().'/Http/Controllers/Admin/*.php') as $file) {
        $file = str_replace(app_path().'/Http/Controllers/Admin/','', $file);
        $file = str_replace('.php','', $file);
        $file = str_replace('Controller','', $file);
        if(!in_array($file, $arr_prevent_module)){
          $arr_tmp['name'] = $file;
          $arr_tmp['value'] = $file;
          $modules[] = $arr_tmp;
        }
    }
    return view('Admin.permission.update', ['permission' => $permission, 'modules' => $modules]);
  }

  function postUpdatePermission(Request $request, $id)
  {
    $permission = Permission::find($id);
    $rules = [
      'display_name' => 'required'
    ];
    $messages = [
      'display_name.required' => 'Tên là trường bắt buộc'
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {
      $permission->display_name = $request->display_name;
      $permission->machine_name = $request->type.'_'.$request->module;
      $permission->name = $request->type.'_'.$request->module;
      $permission->type = $request->type;
      $permission->description = $request->description;
      $permission->module = $request->module;
      if(isset($request->white_list)){
        $permission->white_list = json_encode(array_values($request->white_list));
      }else{
        $permission->white_list = json_encode([]);
      }
      if(isset($request->black_list)){
        $permission->black_list = json_encode(array_values($request->black_list));
      }else{
        $permission->black_list = json_encode([]);
      }
      $permission->updated_by = Auth::guard('web')->user()->id;

      if( $permission->save() ) {
        return redirect()->route('list_permission')->with(['status' => 'Permission đã được cập nhật thành công ']);
      } else {
        $errors = new MessageBag(['error' => 'Không cập nhật được permission']);
        return redirect()->back()->withErrors($errors)->withInput();
      }
    }
  }

  public function getDeletePermission($id)
  {
    $permission = Permission::find($id);
    $name = $permission->display_name;
    $permission->delete();
    return redirect()->route('list_permission')->with(['status' => 'Permission ' . $name . ' đã xóa thành công ']);
  }

  public function getListContent(Request $request)
  {
    $module = isset($request->module)?$request->module:'';
    $arr_return = ['error'=>1, 'messages'=>'Error load list content'];
    if($module){
      $model = app("App\\$module");
      $list_content = $model->Where('deleted', '=', 0)->get();
       $arr_return['error'] = 0;
       $arr_return['data'] = $list_content;
    }
    return response()->json($arr_return);
  }

  public function getDefaultPermission()
  {
    $modules = [];
    $arr_prevent_module = ['Login', 'ForgotPassword', 'ResetPassword', 'Error', 'Base'];
    foreach(glob(app_path().'/Http/Controllers/Admin/*.php') as $file) {
        $file = str_replace(app_path().'/Http/Controllers/Admin/','', $file);
        $file = str_replace('.php','', $file);
        $file = str_replace('Controller','', $file);
        if(!in_array($file, $arr_prevent_module)){
          $arr_tmp['name'] = $file;
          $arr_tmp['value'] = $file;
          $modules[] = $arr_tmp;
        }
    }
    $types = ['view', 'add', 'edit', 'delete'];
    foreach ($modules as $key1 => $module) {
      foreach ($types as $key2 => $type) {
        $permission = new Permission();
        $permission->display_name = ucwords($type.' '.$module['name']);
        $permission->machine_name = $type.'_'.$module['value'];
        $permission->name = $type.'_'.$module['value'];
        $permission->type = $type;
        $permission->module = $module['value'];
        $permission->white_list = '';
        $permission->black_list = '';
        $permission->description = $permission->display_name;
        $permission->created_by = Auth::guard('web')->user()->id;
        $permission->updated_by = Auth::guard('web')->user()->id;
        $check = Permission::where('machine_name','=',$permission->machine_name)->first();
        if(!$check){
          $permission->save();
        }
      }
    }
    echo "Done";
    die;
  }

  public function getAddByModulePermission($module)
  {
    $types = ['view', 'add', 'edit', 'delete'];
    foreach ($types as $key2 => $type) {
      $permission = new Permission();
      $permission->display_name = ucwords($type.' '.$module);
      $permission->machine_name = $type.'_'.$module;
      $permission->name = $type.'_'.$module;
      $permission->type = $type;
      $permission->module = $module;
      $permission->white_list = '';
      $permission->black_list = '';
      $permission->description = $permission->display_name;
      $permission->created_by = Auth::guard('web')->user()->id;
      $permission->updated_by = Auth::guard('web')->user()->id;
      $check = Permission::where('machine_name','=',$permission->machine_name)->first();
      if(!$check){
        $permission->save();
      }
    }
    echo "Done";
    die;
  }
}

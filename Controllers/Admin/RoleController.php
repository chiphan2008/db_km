<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\Role;
use App\Models\Location\User;
use App\Models\Location\Permission;
use App\Models\Location\PermissionRole;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;

class RoleController extends BaseController
{

	public function getListRole(Request $request)
	{

		$all_role = Role::with('_created_by');
		// if(!Auth::guard('web')->user()->hasRole('super_admin')){
			$all_role->where('name','<>','super_admin');
		// }
		$sort = $request->sort?$request->sort:'';
		$input = request()->all();

		if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		if (isset($keyword) && $keyword != '') {

			$all_role->Where(function ($query) use ($keyword) {
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
				$all_role->orderBy($key,$value);
			}
		}else{
			$all_role->orderBy('id','desc');
		}

		$list_role = $all_role->paginate(15);
		return view('Admin.role.list', ['list_role' => $list_role, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);

	}

	public function getAddRole()
	{
		return view('Admin.role.add');
	}

	function postAddRole(Request $request)
	{
		$rules = [
			'display_name' => 'required',
			'machine_name' => 'required',
		];
		$messages = [
			'display_name.required' => 'Tên là trường bắt buộc',
			'machine_name.required' => trans('valid.machine_name_required'),
		];
		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$role = new Role();

			$role->display_name = $request->display_name;
			$role->machine_name = $request->machine_name;
			$role->name = $request->machine_name;
			$role->description = $request->description;
			$role->default = isset($request->default);
			$role->active = isset($request->active);
			$role->created_by = Auth::guard('web')->user()->id;
			$role->updated_by = Auth::guard('web')->user()->id;

			if( $role->save() ) {
				return redirect()->route('list_role')->with(['status' => 'Role đã được thêm thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được role']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getUpdateRole($id)
	{
		$role = Role::find($id);
		return view('Admin.role.update', ['role' => $role]);
	}

	function postUpdateRole(Request $request, $id)
	{
		$role = Role::find($id);
		$rules = [
			'display_name' => 'required',
			// 'machine_name' => 'required',
		];
		$messages = [
			'display_name.required' => trans('valid.name_required'),
			// 'machine_name.required' => trans('valid.machine_name_required'),
		];
		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$role->display_name = $request->display_name;
			// $role->machine_name = $request->machine_name;
			$role->name = $request->machine_name;
			$role->description = $request->description;
			$role->default = isset($request->default);
			$role->active = isset($request->active);
			$role->updated_by = Auth::guard('web')->user()->id;
			$user_array = User::leftJoin('role_user','role_user.user_id','=','id')
													->where('role_id','=',$role->id)
													->pluck('id')->toArray();
			$user_array = array_values($user_array);
			if( $role->save() ) {
				if($role->active == 0){
					User::whereIn('id',$user_array)->update(['active' => 0]);
				}else{
					User::whereIn('id',$user_array)->update(['active' => 1]);
				}
				return redirect()->route('list_role')->with(['status' => 'Quyền đã được cập nhật thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được role']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getDeleteRole($id)
	{
		$role = Role::find($id);
		$name = $role->display_name;
		$role->delete();
		return redirect()->route('list_role')->with(['status' => 'Role ' . $name . ' đã xóa thành công ']);
	}

	public function getGrantRole($id)
	{
		$permissions = Permission::orderBy('module')->get();
		$permission_role = PermissionRole::where('role_id','=',$id)->pluck('permission_id')->toArray();
		foreach ($permissions as $key => $value) {
				$permissions[$key]->checked = in_array($value->id, $permission_role);
		}
		return view('Admin.role.grant', ['permissions' => $permissions, 'id' => $id]);
	}

	public function postGrantRole(Request $request, $id)
	{
		$old_permission_role = PermissionRole::where('role_id','=',$id);
		$old_permission_role->delete();
		if(isset($request->permission)){
			foreach ($request->permission as $key => $value) {
				$permission_role = new PermissionRole();
				$permission_role->permission_id = $value;
				$permission_role->role_id = $id;
				$permission_role->save();
			}
		}
		return redirect()->route('list_role')->with(['status' => 'Role đã được phân quyền thành công ']);
	}
}

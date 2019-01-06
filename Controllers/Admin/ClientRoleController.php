<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\ClientRole;
use App\Models\Location\Client;
use App\Models\Location\ClientPermission;
use App\Models\Location\ClientRolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;

class ClientRoleController extends BaseController
{

	public function getListClientRole(Request $request, $group_id)
	{

		$all_role = ClientRole::where('group_id',$group_id)->with('_created_by');
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
		return view('Admin.client_role.list', ['list_role' => $list_role,'group_id'=>$group_id, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);

	}

	public function getAddClientRole($group_id)
	{
		return view('Admin.client_role.add',['group_id'=>$group_id]);
	}

	function postAddClientRole(Request $request, $group_id)
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
			$role = new ClientRole();

			$role->name = $request->name;
			$role->machine_name = $request->machine_name;
			$role->description = $request->description?$request->description:'';
			$role->group_id = $group_id;
			$role->default = isset($request->default);
			$role->active = isset($request->active);
			$role->created_by = Auth::guard('web')->user()->id;
			$role->updated_by = Auth::guard('web')->user()->id;

			if( $role->save() ) {
				return redirect()->route('list_client_role',['group_id'=>$group_id])->with(['status' => 'Role đã được thêm thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được role']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getUpdateClientRole($group_id,$id)
	{
		$role = ClientRole::find($id);
		return view('Admin.client_role.update', ['role' => $role,'group_id'=>$group_id]);
	}

	function postUpdateClientRole(Request $request,$group_id, $id)
	{
		$role = ClientRole::find($id);
		$rules = [
			'name' => 'required',
			// 'machine_name' => 'required',
		];
		$messages = [
			'name.required' => trans('valid.name_required'),
			// 'machine_name.required' => trans('valid.machine_name_required'),
		];
		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$role->name = $request->name;
			$role->description = $request->description?$request->description:'';
			$role->default = isset($request->default);
			$role->active = isset($request->active);
			$role->updated_by = Auth::guard('web')->user()->id;
			$user_array = Client::where('role_id','=',$role->id)
													->pluck('id')->toArray();
			$user_array = array_values($user_array);
			if( $role->save() ) {
				if($role->active == 0){
					Client::whereIn('id',$user_array)->update(['active' => 0]);
				}else{
					Client::whereIn('id',$user_array)->update(['active' => 1]);
				}
				return redirect()->route('list_client_role',['group_id'=>$group_id])->with(['status' => 'Quyền đã được cập nhật thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được role']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getDeleteClientRole($group_id,$id)
	{
		$role = ClientRole::find($id);
		$name = $role->display_name;
		$role->delete();
		ClientInRole::where('role_id',$id)->delete();
		return redirect()->route('list_client_role',['group_id'=>$group_id])->with(['status' => 'Role ' . $name . ' đã xóa thành công ']);
	}
}

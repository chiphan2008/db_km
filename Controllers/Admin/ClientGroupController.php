<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\ClientGroup;
use App\Models\Location\Client;
use App\Models\Location\ClientRole;
use App\Models\Location\ClientInRole;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;

class ClientGroupController extends BaseController
{

	public function getListClientGroup(Request $request)
	{

		$all_group = ClientGroup::with('_created_by');
		// if(!Auth::guard('web')->user()->hasGroup('super_admin')){
			$all_group->where('name','<>','super_admin');
		// }
		$sort = $request->sort?$request->sort:'';
		$input = request()->all();

		if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		if (isset($keyword) && $keyword != '') {

			$all_group->Where(function ($query) use ($keyword) {
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
				$all_group->orderBy($key,$value);
			}
		}else{
			$all_group->orderBy('id','desc');
		}

		$list_group = $all_group->paginate(15);
		return view('Admin.client_group.list', ['list_group' => $list_group, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);

	}

	public function getAddClientGroup()
	{
		return view('Admin.client_group.add');
	}

	function postAddClientGroup(Request $request)
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
			$group = new ClientGroup();

			$group->name = $request->name;
			$group->machine_name = $request->machine_name;
			$group->description = $request->description?$request->description:'';
			$group->default = isset($request->default);
			$group->active = isset($request->active);
			$group->created_by = Auth::guard('web')->user()->id;
			$group->updated_by = Auth::guard('web')->user()->id;

			if( $group->save() ) {
				return redirect()->route('list_client_group')->with(['status' => 'Group '.$request->name.' đã được thêm thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được group']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getUpdateClientGroup($id)
	{
		$group = ClientGroup::find($id);
		return view('Admin.client_group.update', ['group' => $group]);
	}

	function postUpdateClientGroup(Request $request, $id)
	{
		$group = ClientGroup::find($id);
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
			$group->name = $request->name;
			$group->description = $request->description?$request->description:'';
			$group->default = isset($request->default);
			$group->active = isset($request->active);
			$group->updated_by = Auth::guard('web')->user()->id;
			$user_array = ClientRole::where('group_id','=',$group->id)
													->pluck('id')->toArray();
			$user_array = array_values($user_array);
			if( $group->save() ) {
				if($group->active == 0){
					ClientRole::whereIn('id',$user_array)->update(['active' => 0]);
				}else{
					ClientRole::whereIn('id',$user_array)->update(['active' => 1]);
				}
				return redirect()->route('list_client_group')->with(['status' => 'Group ' . $request->name . ' đã được cập nhật thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được group']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getDeleteClientGroup($id)
	{
		$group = ClientGroup::find($id);
		$name = $group->display_name;
		$group->delete();
		$roles = ClientRole::where('group_id',$id)->get();
		foreach ($roles as $key => $value) {
			ClientInRole::where('role_id',$value->id)->delete();
		}
		ClientRole::where('group_id',$id)->delete();
		return redirect()->route('list_client_group')->with(['status' => 'Group ' . $name . ' đã xóa thành công ']);
	}

}

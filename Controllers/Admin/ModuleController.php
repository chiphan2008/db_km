<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\ProModule;
use App\Models\Location\ProModuleCategory;
use App\Models\Location\Content;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;

class ModuleController extends BaseController
{

	public function getListModule(Request $request)
	{
		$all_module = ProModule::with('_created_by');
		$sort = $request->sort?$request->sort:'';
		$input = request()->all();

		if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		if (isset($keyword) && $keyword != '') {

			$all_module->Where(function ($query) use ($keyword) {
				$query->where('name', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('machine_name', 'LIKE', '%' . $keyword . '%');
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
				$all_module->orderBy($key,$value);
			}
		}
		// $all_module->orderBy('weight');
		$list_module = $all_module->paginate(15);
		// pr($list_module->toArray());die;
		return view('Admin.module.list', ['list_module' => $list_module, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);

	}

	public function getAddModule()
	{
		return view('Admin.module.add', []);
	}

	public function postAddModule(Request $request)
	{
		$rules = [
			'name' => 'required',
			'machine_name' => 'required|unique:pro_modules,machine_name',
			'alias'=>'required'
		];
		$messages = [
			'name.required' => trans('valid.name_required'),
			'machine_name.required' => trans('valid.machine_name_required'),
      'machine_name.unique' => trans('valid.machine_name_unique'),
			'alias.required' => trans('valid.alias_required')
		];
		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$module = new ProModule();
			$module->name = $request->name;
			$module->machine_name = $request->machine_name;
			$module->alias = $request->alias;
			$module->active =  isset($request->active);
			$module->created_by = Auth::guard('web')->user()->id;
			$module->updated_by = Auth::guard('web')->user()->id;

			if( $module->save() ) {
				return redirect()->route('list_module')->with(['status' => 'Module đã được thêm thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được module']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getUpdateModule($id)
	{
		$module = ProModule::find($id);
		return view('Admin.module.update', ['module' => $module]);
	}

	public function postUpdateModule(Request $request, $id)
	{
		$module = ProModule::find($id);
		$old_active = $module->active;
		$rules = [
			'name' => 'required',
			'alias' => 'required'

		];
		$messages = [
			'name.required' => trans('valid.name_required'),
			'name.unique' => trans('valid.name_unique')

		];
		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$module->name = $request->name;
			$module->alias = $request->alias;
			$module->active =  isset($request->active);
			$module->updated_by = Auth::guard('web')->user()->id;
			if( $module->save() ) {

        $active = isset($request->active) ? 1 : 0;
				return redirect()->route('list_module')->with(['status' => 'Module đã được cập nhật thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được module']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getDeleteModule($id)
	{
			$check = ProModuleCategory::where('module_id',$id)->count();
			$module = ProModule::find($id);
	    $name = $module->name;
			if($check>0){
				return redirect()->route('list_module')->with(['err' => 'Module ' . $name . ' không thể xóa, Vui lòng kiểm tra lại toàn bộ danh mục của module trước khi xóa !']);
			}else{
	      $module->delete();
	      return redirect()->route('list_module')->with(['status' => 'Module ' . $name . ' đã xóa thành công ']);
			}
      
	}
}

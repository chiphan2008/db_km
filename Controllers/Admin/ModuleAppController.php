<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\ModuleApp;
use App\Models\Location\ModuleAppItem;
use App\Models\Location\Content;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;

class ModuleAppController extends BaseController
{

	public function getListModuleApp(Request $request)
	{
		$all_module = ModuleApp::with('_created_by')->with('_parent')->Where('deleted', '=', 0);
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
				$query->orWhere('description', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('language', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('weight', 'LIKE', '%' . $keyword . '%');
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
		}else{
			$all_module->orderBy('weight','asc');
		}
		// $all_module->orderBy('weight');
		$list_module = $all_module->paginate(15);
		// pr($list_module->toArray());die;
		return view('Admin.module_app.list', ['list_module' => $list_module, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);

	}

	public function getAddModuleApp()
	{
		$list_module = ModuleApp::Where('deleted', '=', 0)->get();
		return view('Admin.module_app.add', ['list_module' => $list_module]);
	}

	public function postAddModuleApp(Request $request)
	{
		$rules = [
			'name' => 'required',
			'machine_name' => 'required|unique:module_app,machine_name',
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
			$module = new ModuleApp();
			$module->name = $request->name;
			$module->machine_name = $request->machine_name;
			$module->alias = $request->alias;
			$module->language = $request->language;
			$module->weight = ModuleApp::max('weight') + 1;
			$module->parent = $request->parent;
			$module->description = $request->description;
			$module->active =  isset($request->active);
			$module->noibat =  isset($request->noibat);
			$module->created_by = Auth::guard('web')->user()->id;
			$module->updated_by = Auth::guard('web')->user()->id;
			if($request->file('image')) {
				$path = public_path().'/upload/module/';
				$file = $request->file('image');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name =time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
$file->move($path,$name);
				$module->image = '/upload/module/'.$name;
			}else{
				$module->image ='/frontend/assets/img/icon/logo-large.png';
			}

			if($request->file('background')) {
				$path = public_path().'/upload/module/';
				$file = $request->file('background');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name ='bg-'.time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
$file->move($path,$name);
				$module->background = '/upload/module/'.$name;
			}else{
				$module->background ='/frontend/assets/img/upload/bg-food2.jpg';
			}

			if( $module->save() ) {
				return redirect()->route('list_module_app')->with(['status' => 'Module đã được thêm thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được module']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getUpdateModuleApp($id)
	{
		$list_module = ModuleApp::Where('deleted', '=', 0)
																				->Where('id', '<>', $id)
																				->get();
		$module = ModuleApp::find($id);
		return view('Admin.module_app.update', ['module' => $module, 'list_module' => $list_module]);
	}

	public function postUpdateModuleApp(Request $request, $id)
	{
		$module = ModuleApp::find($id);
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
			$module->language = $request->language;
			$module->parent = $request->parent;
			$module->description = $request->description;
			$module->active =  isset($request->active);
			$module->noibat =  isset($request->noibat);
			$module->updated_by = Auth::guard('web')->user()->id;
			if($request->file('image')) {
				$path = public_path().'/upload/module/';
				$file = $request->file('image');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name =time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
$file->move($path,$name);
				$module->image = '/upload/module/'.$name;
			}
			// else{
			// 	if(!$module->image)
			// 		$module->image ='/frontend/assets/img/icon/logo-large.png';
			// }

			if($request->file('background')) {
				$path = public_path().'/upload/module/';
				$file = $request->file('background');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name ='bg-'.time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
$file->move($path,$name);
				$module->background = '/upload/module/'.$name;
			}

			if( $module->save() ) {

        $active = isset($request->active) ? 1 : 0;
				return redirect()->route('list_module_app')->with(['status' => 'Module đã được cập nhật thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được module']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getDeleteModuleApp($id)
	{
      $module = ModuleApp::find($id);
      $name = $module->name;
      $module->delete();
      return redirect()->route('list_module_app')->with(['status' => 'Module ' . $name . ' đã xóa thành công ']);
	}

	public function getDownModuleApp($id){
		$current_module = ModuleApp::find($id);
		$change_module = ModuleApp::where('weight','>',$current_module->weight)->orderBy('weight','asc')->first();
		$old_weight = $current_module->weight;
		$current_module->weight = $change_module->weight;
		$change_module->weight = $old_weight;
		$current_module->save();
		$change_module->save();
		return redirect()->route('list_module_app');
	}

	public function getUpModuleApp($id){
		$current_module = ModuleApp::find($id);
		$change_module = ModuleApp::where('weight','<',$current_module->weight)->orderBy('weight','desc')->first();
		$old_weight = $current_module->weight;
		$current_module->weight = $change_module->weight;
		$change_module->weight = $old_weight;
		$current_module->save();
		$change_module->save();
		return redirect()->route('list_module_app');
	}

	public function getChangeWeightModuleApp($id,$weight){
		$current_module = ModuleApp::find($id);
		if($current_module){
			$current_module->weight = $weight;
			$current_module->save();
			return redirect()->route('list_module_app');
		}
	}
}

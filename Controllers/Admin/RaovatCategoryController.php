<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\ProModule;
use App\Models\Location\ProCategory;
use App\Models\Location\ProCategoryItem;
use App\Models\Location\ProModuleCategory;
use App\Models\Location\ProModuleCategoryItem;

use App\Models\Location\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;

class RaovatCategoryController extends BaseController
{

	public function getListRaovatCategory(Request $request)
	{
		$all_raovat_category = ProCategory::with('_created_by');
		$sort = $request->sort?$request->sort:'';
		$input = request()->all();

		if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		if (isset($keyword) && $keyword != '') {

			$all_raovat_category->Where(function ($query) use ($keyword) {
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
				$all_raovat_category->orderBy($key,$value);
			}
		}
		// $all_raovat_category->orderBy('weight');
		$list_raovat_category = $all_raovat_category->paginate(15);
		// pr($list_raovat_category->toArray());die;
		return view('Admin.raovat_category.list', ['list_raovat_category' => $list_raovat_category, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);

	}

	public function getAddRaovatCategory()
	{
		
		return view('Admin.raovat_category.add', []);
	}

	public function postAddRaovatCategory(Request $request)
	{
		$rules = [
			'name' => 'required',
			'machine_name' => 'required|unique:pro_categories,machine_name',
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
			$raovat_category = new ProCategory();
			$raovat_category->name = $request->name;
			$raovat_category->machine_name = $request->machine_name;
			$raovat_category->alias = $request->alias;
			$raovat_category->created_by = Auth::guard('web')->user()->id;
			$raovat_category->updated_by = Auth::guard('web')->user()->id;

			if( $raovat_category->save() ) {
				return redirect()->route('list_raovat_category')->with(['status' => 'Category đã được thêm thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được raovat_category']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getUpdateRaovatCategory($id)
	{

		$raovat_category = ProCategory::find($id);
		return view('Admin.raovat_category.update', ['raovat_category' => $raovat_category]);
	}

	public function postUpdateRaovatCategory(Request $request, $id)
	{
		$raovat_category = ProCategory::find($id);
		$old_active = $raovat_category->active;
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
			$raovat_category->name = $request->name;
			$raovat_category->alias = $request->alias;
			$raovat_category->updated_by = Auth::guard('web')->user()->id;

			if( $raovat_category->save() ) {
				return redirect()->route('list_raovat_category')->with(['status' => 'Category đã được cập nhật thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được raovat_category']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getDeleteRaovatCategory($id)
	{
			$check = ProModuleCategory::where('category_id',$id)
												 				->count();
			$category = ProCategory::find($id);
	    $name = $category->name;
			if($check>0){
	      return redirect()->route('list_raovat_category')->with(['err' => 'Category ' . $name . ' không thể xóa, Vui lòng kiểm tra lại toàn bộ danh mục của module trước khi xóa !']);
			}else{
				$category->delete();
				return redirect()->route('list_raovat_category')->with(['status' => 'Category '.$name.' đã xóa thành công ']);
			}
	}
}

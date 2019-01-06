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

class RaovatCategoryItemController extends BaseController
{

	public function getListRaovatCategoryItem(Request $request)
	{
		$all_raovat_category_item = ProCategoryItem::with('_created_by');
		$sort = $request->sort?$request->sort:'';
		$input = request()->all();

		if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		if (isset($keyword) && $keyword != '') {

			$all_raovat_category_item->Where(function ($query) use ($keyword) {
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
				$all_raovat_category_item->orderBy($key,$value);
			}
		}
		// $all_raovat_category_item->orderBy('weight');
		$list_raovat_category_item = $all_raovat_category_item->paginate(15);
		// pr($list_raovat_category_item->toArray());die;
		return view('Admin.raovat_category_item.list', ['list_raovat_category_item' => $list_raovat_category_item, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);

	}

	public function getAddRaovatCategoryItem()
	{
		
		return view('Admin.raovat_category_item.add', []);
	}

	public function postAddRaovatCategoryItem(Request $request)
	{
		$rules = [
			'name' => 'required',
			'machine_name' => 'required|unique:pro_category_items,machine_name',
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
			$raovat_category_item = new ProCategoryItem();
			$raovat_category_item->name = $request->name;
			$raovat_category_item->machine_name = $request->machine_name;
			$raovat_category_item->alias = $request->alias;

			$raovat_category_item->created_by = Auth::guard('web')->user()->id;
			$raovat_category_item->updated_by = Auth::guard('web')->user()->id;


			if( $raovat_category_item->save() ) {
				return redirect()->route('list_raovat_category_item')->with(['status' => 'Category đã được thêm thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được raovat_category_item']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getUpdateRaovatCategoryItem($id)
	{

		$raovat_category_item = ProCategoryItem::find($id);
		return view('Admin.raovat_category_item.update', ['raovat_category_item' => $raovat_category_item]);
	}

	public function postUpdateRaovatCategoryItem(Request $request, $id)
	{
		$raovat_category_item = ProCategoryItem::find($id);
		$old_active = $raovat_category_item->active;
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
			$raovat_category_item->name = $request->name;
			$raovat_category_item->alias = $request->alias;

			$raovat_category_item->updated_by = Auth::guard('web')->user()->id;

			if( $raovat_category_item->save() ) {
				return redirect()->route('list_raovat_category_item')->with(['status' => 'Category đã được cập nhật thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được raovat_category_item']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getDeleteRaovatCategoryItem($id)
	{
			$check = ProModuleCategoryItem::where('category_item_id',$id)
												 						->count();
			$category = ProCategoryItem::find($id);
	    $name = $category->name;
			if($check>0){
	      return redirect()->route('list_raovat_category_item')->with(['err' => 'Category ' . $name . ' không thể xóa, Vui lòng kiểm tra lại toàn bộ danh mục con của module trước khi xóa !']);
			}else{
				$category->delete();
				return redirect()->route('list_raovat_category_item')->with(['status' => 'Category '.$name.' đã xóa thành công ']);
			}
	}
}

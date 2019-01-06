<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\ProModule;
use App\Models\Location\ProCategory;
use App\Models\Location\ProCategoryItem;
use App\Models\Location\ProModuleCategory;
use App\Models\Location\ProModuleCategoryItem;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;

class ModuleCategoryItemController extends BaseController
{
	public function getListModuleCategoryItem(Request $request,$module_id,$category_id){
		$per_page = \Session::has('pagination.'.\Route::currentRouteName()) ? session('pagination.'.\Route::currentRouteName()) : 10;
		$sort = $request->sort?$request->sort:'';
		$input = request()->all();
		$all_category_item = ProCategoryItem::rightJoin('pro_module_category_item','category_item_id','pro_category_items.id')
															 					->where('pro_module_category_item.module_id',$module_id)
															 					->where('pro_module_category_item.category_id',$category_id);
		if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		if (isset($keyword) && $keyword != '') {
			$all_category_item = $all_category_item->Where(function ($query) use ($keyword) {
				$query->where('name', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('machine_name', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('alias', 'LIKE', '%' . str_slug_custom($keyword) . '%');
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
				$all_category_item->orderBy($key,$value);
			}
		}else{
			$all_category_item->orderBy('weight','asc');
		}

		$list_category_item = $all_category_item->paginate($per_page);
		return view('Admin.module_category_item.list', [
			'list_category_item' => $list_category_item,
			'keyword' => $keyword,
			'sort'=> $arr_sort,
			'qsort'=> $sort,
			'module_id' => $module_id,
			'category_id' => $category_id
		]);
	}

	public function getAddModuleCategoryItem(Request $request,$module_id,$category_id){
		$list_added = ProModuleCategoryItem::where('module_id',$module_id)
																			 ->where('category_id',$category_id)
																			 ->pluck('category_item_id');

		$list_category_item = ProCategoryItem::whereNotIn('id',$list_added)->get();
		return view('Admin.module_category_item.add', ['list_category_item' => $list_category_item,'module_id'=>$module_id,'category_id'=>$category_id]);
	}

	public function postAddModuleCategoryItem(Request $request,$module_id,$category_id){
		ProModuleCategoryItem::where('module_id',$module_id)
												 ->where('category_id',$category_id)
												 ->where('category_item_id',$request->category_item)
												 ->delete();

		$category_item = new ProModuleCategoryItem();
		$category_item->module_id = $module_id;
		$category_item->category_id = $category_id;
		$category_item->category_item_id = $request->category_item;
		$category_item->weight = ProModuleCategoryItem::max('weight') + 1;
		$category_item->active =  isset($request->active);
		$category_item->created_by = Auth::guard('web')->user()->id;
		$category_item->updated_by = Auth::guard('web')->user()->id;
		if($request->file('image')) {
			$path = public_path().'/upload/category_item/';
			$file = $request->file('image');
			if(!\File::exists($path)) {
				\File::makeDirectory($path, $mode = 0777, true, true);
			}
			$name =time(). '.' . $file->getClientOriginalExtension();
			if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0){
				$image = crop_image($file,200,200);
				$image->save($path.$name);
			}else{
				if($file->getClientOriginalExtension() === 'svg'){
					$file->move($path,$name);
				}
			}
			$category_item->image = '/upload/category_item/'.$name;
		}else{
			$category_item->image ='/frontend/assets/img/icon/logo-large.png';
		}

		if($request->file('background')) {
			$path = public_path().'/upload/category_item/';
			$file = $request->file('background');
			if(!\File::exists($path)) {
				\File::makeDirectory($path, $mode = 0777, true, true);
			}
			$name ='bg-'.time(). '.' . $file->getClientOriginalExtension();
			if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
				$file->move($path,$name);
			$category_item->background = '/upload/category_item/'.$name;
		}else{
			$category_item->background ='/frontend/assets/img/upload/bg-food2.jpg';
		}
		if( $category_item->save() ) {
			return redirect()->route('list_module_category_item',['module_id' => $module_id,"category_id"=>$category_id])->with(['status' => 'Category đã được thêm thành công ']);
		} else {
			$errors = new MessageBag(['error' => 'Không tạo được category']);
			return redirect()->back()->withErrors($errors)->withInput();
		}
	}

	public function getUpdateModuleCategoryItem(Request $request,$module_id,$category_id,$category_item_id){
		$list_added = ProModuleCategoryItem::where('module_id',$module_id)
																			 ->where('category_id',$category_id)
																			 ->where('category_item_id','!=',$category_item_id)
																			 ->pluck('category_id');

		$list_category_item = ProCategoryItem::whereNotIn('id',$list_added)->get();
		$category_item = ProModuleCategoryItem::where('category_id',$category_id)
																					->where('module_id',$module_id)
																					->where('category_item_id',$category_item_id)
																					->first();
		return view('Admin.module_category_item.update', [
			'list_category_item' => $list_category_item,
			'module_id'=>$module_id,
			'category_id'=>$category_id,
			'category_item'=>$category_item
		]);
	}

	public function postUpdateModuleCategoryItem(Request $request,$module_id,$category_id,$category_item_id){
		$category_item = ProModuleCategoryItem::where('category_id',$category_id)
																					->where('module_id',$module_id)
																					->where('category_item_id',$category_item_id)
																					->first();
		$category_item->category_item_id = $request->category_item;
		$category_item->active =  isset($request->active);
		$category_item->created_by = Auth::guard('web')->user()->id;
		$category_item->updated_by = Auth::guard('web')->user()->id;
		if($request->file('image')) {
			$path = public_path().'/upload/category_item/';
			$file = $request->file('image');
			if(!\File::exists($path)) {
				\File::makeDirectory($path, $mode = 0777, true, true);
			}
			$name =time(). '.' . $file->getClientOriginalExtension();
			if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0){
				$image = crop_image($file,200,200);
				$image->save($path.$name);
			}else{
				if($file->getClientOriginalExtension() === 'svg'){
					$file->move($path,$name);
				}
			}
			$category_item->image = '/upload/category_item/'.$name;
		}else{
			$category_item->image ='/frontend/assets/img/icon/logo-large.png';
		}

		if($request->file('background')) {
			$path = public_path().'/upload/category_item/';
			$file = $request->file('background');
			if(!\File::exists($path)) {
				\File::makeDirectory($path, $mode = 0777, true, true);
			}
			$name ='bg-'.time(). '.' . $file->getClientOriginalExtension();
			if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
				$file->move($path,$name);
			$category_item->background = '/upload/category_item/'.$name;
		}else{
			$category_item->background ='/frontend/assets/img/upload/bg-food2.jpg';
		}
		if( $category_item->save() ) {
			return redirect()->route('list_module_category_item',['module_id' => $module_id,"category_id"=>$category_id])->with(['status' => 'Category đã được cập nhật thành công ']);
		} else {
			$errors = new MessageBag(['error' => 'Không tạo được category']);
			return redirect()->back()->withErrors($errors)->withInput();
		}
	}

	public function getChangeWeightModuleCategoryItem(Request $request,$module_id,$category_id,$category_item_id,$weight){
		$current_category =  ProModuleCategoryItem::where('category_id',$category_id)
																					->where('module_id',$module_id)
																					->where('category_item_id',$category_item_id)
																					->first();
		if($current_category){
			$current_category->weight = $weight;
			$current_category->save();
			return redirect()->route('list_module_category_item',['module_id' => $module_id,"category_id"=>$category_id]);
		}
	}


	public function getDeleteModuleCategoryItem(Request $request,$module_id,$category_id,$category_item_id){
		$category = ProCategoryItem::find($category_item_id);
		$name = $category->name;
		ProModuleCategoryItem::where('module_id',$module_id)
												 ->where('category_id',$category_id)
												 ->where('category_item_id',$category_item_id)
												 ->delete();
		return redirect()->route('list_module_category_item',['module_id' => $module_id,"category_id"=>$category_id])->with(['status' => 'Category '.$name.' đã xóa thành công ']);
	}
}

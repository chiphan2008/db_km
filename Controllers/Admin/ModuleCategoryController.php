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

class ModuleCategoryController extends BaseController
{
	public function getListModuleCategory(Request $request,$module_id){
		$per_page = \Session::has('pagination.'.\Route::currentRouteName()) ? session('pagination.'.\Route::currentRouteName()) : 10;
		$sort = $request->sort?$request->sort:'';
		$input = request()->all();
		$all_category = ProCategory::rightJoin('pro_module_category','pro_module_category.category_id','pro_categories.id')
															 ->where('pro_module_category.module_id',$module_id);
		if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		if (isset($keyword) && $keyword != '') {
			$all_category = $all_category->Where(function ($query) use ($keyword) {
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
				$all_category->orderBy($key,$value);
			}
		}else{
			$all_category->orderBy('weight','asc');
		}

		$list_category = $all_category->paginate($per_page);
		return view('Admin.module_category.list', ['list_category' => $list_category, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort, 'module_id' => $module_id]);
	}

	public function getAddModuleCategory(Request $request,$module_id){
		$list_added = ProModuleCategory::where('module_id',$module_id)->pluck('category_id');
		$list_category = ProCategory::whereNotIn('id',$list_added)->get();
		return view('Admin.module_category.add', ['list_category' => $list_category,'module_id'=>$module_id]);
	}

	public function postAddModuleCategory(Request $request,$module_id){
		$category = new ProModuleCategory();
		$category->module_id = $module_id;
		$category->category_id = $request->category;
		$category->weight = ProModuleCategory::max('weight') + 1;
		$category->active =  isset($request->active);
		$category->created_by = Auth::guard('web')->user()->id;
		$category->updated_by = Auth::guard('web')->user()->id;
		if($request->file('image')) {
			$path = public_path().'/upload/category/';
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
			$category->image = '/upload/category/'.$name;
		}else{
			$category->image ='/frontend/assets/img/icon/logo-large.png';
		}

		if($request->file('background')) {
			$path = public_path().'/upload/category/';
			$file = $request->file('background');
			if(!\File::exists($path)) {
				\File::makeDirectory($path, $mode = 0777, true, true);
			}
			$name ='bg-'.time(). '.' . $file->getClientOriginalExtension();
			if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
				$file->move($path,$name);
			$category->background = '/upload/category/'.$name;
		}else{
			$category->background ='/frontend/assets/img/upload/bg-food2.jpg';
		}
		if( $category->save() ) {
			return redirect()->route('list_module_category',['module_id' => $module_id])->with(['status' => 'Category đã được thêm thành công ']);
		} else {
			$errors = new MessageBag(['error' => 'Không tạo được category']);
			return redirect()->back()->withErrors($errors)->withInput();
		}
	}

	public function getUpdateModuleCategory(Request $request,$module_id,$category_id){
		$list_added = ProModuleCategory::where('module_id',$module_id)->where('category_id','!=',$category_id)->pluck('category_id');
		$list_category = ProCategory::whereNotIn('id',$list_added)->get();
		$category = ProModuleCategory::where('category_id',$category_id)
																 ->where('module_id',$module_id)
																 ->first();
		return view('Admin.module_category.update', ['list_category' => $list_category,'module_id'=>$module_id,'category'=>$category]);
	}

	public function postUpdateModuleCategory(Request $request,$module_id,$category_id){
		$category = ProModuleCategory::where('category_id',$category_id)
																 ->where('module_id',$module_id)
																 ->first();
		$category->module_id = $module_id;
		$category->category_id = $request->category;
		$category->active =  isset($request->active);
		$category->created_by = Auth::guard('web')->user()->id;
		$category->updated_by = Auth::guard('web')->user()->id;
		if($request->file('image')) {
			$path = public_path().'/upload/category/';
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
			$category->image = '/upload/category/'.$name;
		}

		if($request->file('background')) {
			$path = public_path().'/upload/category/';
			$file = $request->file('background');
			if(!\File::exists($path)) {
				\File::makeDirectory($path, $mode = 0777, true, true);
			}
			$name ='bg-'.time(). '.' . $file->getClientOriginalExtension();
			if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
				$file->move($path,$name);
			$category->background = '/upload/category/'.$name;
		}

		if( $category->save() ) {
			return redirect()->route('list_module_category',['module_id' => $module_id])->with(['status' => 'Category đã được cập nhật thành công ']);
		} else {
			$errors = new MessageBag(['error' => 'Không tạo được category']);
			return redirect()->back()->withErrors($errors)->withInput();
		}
	}

	public function getChangeWeightModuleCategory(Request $request,$module_id,$category_id,$weight){
		$current_category = ProModuleCategory::where('category_id',$category_id)
																				 ->where('module_id',$module_id)
																				 ->first();
		if($current_category){
			$current_category->weight = $weight;
			$current_category->save();
			return redirect()->route('list_module_category',['module_id' => $module_id]);
		}
	}

	public function getDeleteModuleCategory(Request $request,$module_id,$category_id){
		$check = ProModuleCategoryItem::where('module_id',$module_id)
												 					->where('category_id',$category_id)
												 					->count();
		$category = ProCategory::find($category_id);
    $name = $category->name;
		if($check>0){
      return redirect()->route('list_module_category',['module_id' => $module_id])->with(['err' => 'Category ' . $name . ' không thể xóa, Vui lòng kiểm tra lại toàn bộ danh mục con trước khi xóa !']);
		}else{
			ProModuleCategory::where('module_id',$module_id)
											 ->where('category_id',$category_id)
											 ->delete();
			return redirect()->route('list_module_category',['module_id' => $module_id])->with(['status' => 'Category '.$name.' đã xóa thành công ']);
		}
		
	}
}

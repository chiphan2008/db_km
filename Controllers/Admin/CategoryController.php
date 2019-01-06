<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\Category;
use App\Models\Location\CategoryItem;
use App\Models\Location\Content;
use App\Models\Location\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;

class CategoryController extends BaseController
{

	public function getListCategory(Request $request)
	{
		$all_category = Category::with('_created_by')->with('_parent')->Where('deleted', '=', 0)->Where('approved', '=', 1);
		$sort = $request->sort?$request->sort:'';
		$input = request()->all();

		if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		if (isset($keyword) && $keyword != '') {

			$all_category->Where(function ($query) use ($keyword) {
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
				$all_category->orderBy($key,$value);
			}
		}else{
			$all_category->orderBy('weight','asc');
		}
		// $all_category->orderBy('weight');
		$list_category = $all_category->paginate(15);
		// pr($list_category->toArray());die;
		return view('Admin.category.list', ['list_category' => $list_category, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);

	}

	public function getListApproveCategory(Request $request)
	{
		$all_category = Category::Where('approved', '=', 0);
		$sort = $request->sort?$request->sort:'';
		$input = request()->all();

		if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		if (isset($keyword) && $keyword != '') {

			$all_category->Where(function ($query) use ($keyword) {
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
				$all_category->orderBy($key,$value);
			}
		}else{
			$all_category->orderBy('id','desc');
		}
		// $all_category->orderBy('weight');
		$list_category = $all_category->paginate(15);
		// pr($list_category->toArray());die;
		return view('Admin.category.approve', ['list_category' => $list_category, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);

	}

	public function getApproveCategory($id){
		$category = Category::find($id);
		if(!$category){
			abort(404);
		}
		$category->approved = 1;
		$category->active = 1;
		$category->save();
		return redirect()->route('list_category')->with(['status' => 'Category ' . $category->name . ' đã duyệt thành công ']);
	}

	public function getAddCategory()
	{
		$list_category = Category::Where('deleted', '=', 0)->get();
		return view('Admin.category.add', ['list_category' => $list_category]);
	}

	public function postAddCategory(Request $request)
	{
		// dd($request->all());
		$rules = [
			'name' => 'required',
			'machine_name' => 'required|unique:categories,machine_name',
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
			$category = new Category();
			$category->name = $request->name;
			$category->machine_name = $request->machine_name;
			$category->alias = $request->alias;
			$category->language = $request->language?$request->language:'vn';
			$category->type = $request->type;
			$category->weight = Category::max('weight') + 1;
			$category->parent = $request->parent?$request->parent:0;
			$category->description = $request->description;
			$category->active =  isset($request->active);

			$category->show_khong_gian = isset($request->show_khong_gian);
			$category->show_hinh_anh   = isset($request->show_hinh_anh);
			$category->show_video      = isset($request->show_video);
			$category->show_san_pham   = isset($request->show_san_pham);
			$category->show_khuyen_mai = isset($request->show_khuyen_mai);
			$category->show_chi_nhanh  = isset($request->show_chi_nhanh);

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

			if($request->file('marker')) {
				$path = public_path().'/upload/category/';
				$file = $request->file('marker');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name ='mk-'.time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0){
					$image = crop_image($file,125,146);
					$image->save($path.$name);
				}else{
					if($file->getClientOriginalExtension() === 'svg'){
						$file->move($path,$name);
					}
				}
				$category->marker = '/upload/category/'.$name;
			}else{
				$category->marker ='/img_default/marker.svg';
			}

			if( $category->save() ) {
				Menu::updateMenuFrontEnd(46,$category->id,$category->active);
				
				
				return redirect()->route('list_category')->with(['status' => 'Category đã được thêm thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được category']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getUpdateCategory($id)
	{
		$list_category = Category::Where('deleted', '=', 0)
																				->Where('id', '<>', $id)
																				->get();
		$category = Category::find($id);
		return view('Admin.category.update', ['category' => $category, 'list_category' => $list_category]);
	}

	public function postUpdateCategory(Request $request, $id)
	{
		$category = Category::find($id);
		$old_active = $category->active;
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
			$category->name = $request->name;
			$category->alias = $request->alias;
			$category->language = $request->language?$request->language:'vn';
			$category->parent = $request->parent?$request->parent:0;
			$category->type = $request->type;
			$category->description = $request->description;
			$category->active =  isset($request->active);

			$category->show_khong_gian = isset($request->show_khong_gian);
			$category->show_hinh_anh   = isset($request->show_hinh_anh);
			$category->show_video      = isset($request->show_video);
			$category->show_san_pham   = isset($request->show_san_pham);
			$category->show_khuyen_mai = isset($request->show_khuyen_mai);
			$category->show_chi_nhanh  = isset($request->show_chi_nhanh);

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
			// else{
			// 	if(!$category->image)
			// 		$category->image ='/frontend/assets/img/icon/logo-large.png';
			// }

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
			// else{
			// 	$category->background ='/frontend/assets/img/upload/bg-food2.jpg';
			// }

			if($request->file('marker')) {
				$path = public_path().'/upload/category/';
				$file = $request->file('marker');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name ='mk-'.time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0){
					$image = crop_image($file,125,146);
					$image->save($path.$name);
				}else{
					if($file->getClientOriginalExtension() === 'svg'){
						$file->move($path,$name);
					}
				}
				$category->marker = '/upload/category/'.$name;
			}
			// else{
			// 	$category->marker ='/img_default/marker.svg';
			// }

			if( $category->save() ) {
				Menu::updateMenuFrontEnd(46,$category->id,$category->active);
				
				$active = isset($request->active) ? 1 : 0;
				if($old_active != $active)
				{
					Content::where('id_category','=',$id)->chunk(100, function ($contents) use($active){
						foreach ($contents as $content) {
							if($content->moderation == 'publish')
							{
								$content->active = $active;
								$content->save();
							}
						}
					});
				}

				return redirect()->route('list_category')->with(['status' => 'Category đã được cập nhật thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được category']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getDeleteCategory($id)
	{
		$content = Content::where('id_category','=',$id)->count();
		if($content > 0)
		{
			$category = Category::find($id);
			$name = $category->name;
			return redirect()->route('list_category')->with(['err' => 'Category ' . $name . ' không thể xóa, Vui lòng kiểm tra lại toàn bộ content trước khi xóa !']);
		}
		else {
			$category = Category::find($id);
			$name = $category->name;
			$category->delete();
			$category_item = CategoryItem::where('category_id','=',$id)->get();
			foreach ($category_item as $item)
			{
				$item->delete();
			}
			Menu::deleteMenuFrontEnd($id);
			return redirect()->route('list_category')->with(['status' => 'Category ' . $name . ' đã xóa thành công ']);
		}

//		$category->deleted = 1;
//		$category->updated_by = Auth::guard('web')->user()->id;
//		$name = $category->name;
//		if( $category->save() ){
//			$list_child_category = Category::Where('parent', '=', $id)->get();
//			foreach ($list_child_category as $key => $category) {
//				$category->parent = 0;
//				$category->save();
//			}
//		}
//		return redirect()->route('list_category')->with(['status' => 'Category ' . $name . ' đã xóa thành công ']);
	}

	public function getDownCategory($id){
		$current_category = Category::find($id);
		$change_category = Category::where('weight','>',$current_category->weight)->orderBy('weight','asc')->first();
		$old_weight = $current_category->weight;
		$current_category->weight = $change_category->weight;
		$change_category->weight = $old_weight;
		$current_category->save();
		$change_category->save();
		return redirect()->route('list_category');
	}

	public function getUpCategory($id){
		$current_category = Category::find($id);
		$change_category = Category::where('weight','<',$current_category->weight)->orderBy('weight','desc')->first();
		$old_weight = $current_category->weight;
		$current_category->weight = $change_category->weight;
		$change_category->weight = $old_weight;
		$current_category->save();
		$change_category->save();
		return redirect()->route('list_category');
	}

	public function getChangeWeightCategory($id,$weight){
		$current_category = Category::find($id);
		if($current_category){
			$current_category->weight = $weight;
			$current_category->save();
			return redirect()->route('list_category');
		}
	}

	public function getMoveCategory(Request $request){
		$list_category = Category::Where('deleted', '=', 0)
																				->get();
		return view('Admin.category.move', ['list_category' => $list_category]);
	}

	public function postMoveCategory(Request $request){
		$rules = [
			'category_item' => 'required',
		];
		$messages = [
			'category_item.required' => trans('valid.category_item_required'),
		];
		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
		} else {
			set_time_limit(0);
			$from_category = $request->from_category;
			$to_category = $request->to_category;
			$category_item = $request->category_item;

			\DB::table('category_items')
				->where('category_id',$from_category)
				->whereIn('id',$category_item)
				->update([
						'category_id' => $to_category
				]);              
									
			// \DB::table('category_service')
			// 	->where('id_category',$from_category)
			// 	->update([
			// 			'id_category' => $to_category
			// 	]);

			\DB::table('contents')
				->leftJoin('category_content','category_content.id_content','contents.id')
				->whereIn('category_content.id_category_item',$category_item)
				->where('id_category',$from_category)
				->update([
						'id_category' => $to_category
				]);

			return redirect()->back()->with(['status' => 'Chuyển danh mục thành công']);

		}
		
	}
}

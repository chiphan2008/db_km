<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;

use App\Models\Location\Raovat;
use App\Models\Location\RaovatType;
use App\Models\Location\RaovatSubType;
use App\Models\Location\RaovatImage;
use Intervention\Image\Facades\Image;

class RaovatTypeController extends BaseController
{

	public function getListRaovatType(Request $request)
	{
		$all_raovat_type = RaovatType::with('_created_by')->Where('deleted', '=', 0);
		$sort = $request->sort?$request->sort:'';
		$input = request()->all();

		if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		if (isset($keyword) && $keyword != '') {

			$all_raovat_type->Where(function ($query) use ($keyword) {
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
				$all_raovat_type->orderBy($key,$value);
			}
		}else{
			$all_raovat_type->orderBy('weight','asc');
		}
		// $all_raovat_type->orderBy('weight');
		$list_raovat_type = $all_raovat_type->paginate(15);
		// pr($list_raovat_type->toArray());die;
		return view('Admin.raovat_type.list', ['list_raovat_type' => $list_raovat_type, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);

	}

	public function getAddRaovatType()
	{
		$list_raovat_type = RaovatType::Where('deleted', '=', 0)->get();
		return view('Admin.raovat_type.add', ['list_raovat_type' => $list_raovat_type]);
	}

	public function postAddRaovatType(Request $request)
	{
		$rules = [
			'name' => 'required',
			'machine_name' => 'required|unique:raovat_type,machine_name',
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
			$raovat_type = new RaovatType();
			$raovat_type->name = $request->name;
			$raovat_type->machine_name = $request->machine_name;
			$raovat_type->alias = $request->alias;
			$raovat_type->language = $request->language;
			$raovat_type->weight = RaovatType::max('weight') + 1;
			
			$raovat_type->description = $request->description;
			$raovat_type->active =  isset($request->active);
			$raovat_type->created_by = Auth::guard('web')->user()->id;
			$raovat_type->updated_by = Auth::guard('web')->user()->id;
			if($request->file('image')) {
				$path = public_path().'/upload/raovat_type/';
				$file = $request->file('image');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name =time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
$file->move($path,$name);
				$raovat_type->image = '/upload/raovat_type/'.$name;
			}else{
				$raovat_type->image ='/frontend/assets/img/icon/logo-large.png';
			}

			if($request->file('background')) {
				$path = public_path().'/upload/raovat_type/';
				$file = $request->file('background');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name ='bg-'.time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
$file->move($path,$name);
				$raovat_type->background = '/upload/raovat_type/'.$name;
			}else{
				$raovat_type->background ='/frontend/assets/img/upload/bg-food2.jpg';
			}

			if( $raovat_type->save() ) {
				return redirect()->route('list_raovat_type')->with(['status' => 'Danh mục đã được thêm thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được raovat_type']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getUpdateRaovatType($id)
	{
		$list_raovat_type = RaovatType::Where('deleted', '=', 0)
																				->Where('id', '<>', $id)
																				->get();
		$raovat_type = RaovatType::find($id);
		return view('Admin.raovat_type.update', ['raovat_type' => $raovat_type, 'list_raovat_type' => $list_raovat_type]);
	}

	public function postUpdateRaovatType(Request $request, $id)
	{
		$raovat_type = RaovatType::find($id);
		$old_active = $raovat_type->active;
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
			$raovat_type->name = $request->name;
			$raovat_type->alias = $request->alias;
			$raovat_type->language = $request->language;
			
			$raovat_type->description = $request->description;
			$raovat_type->active =  isset($request->active);
			$raovat_type->updated_by = Auth::guard('web')->user()->id;
			if($request->file('image')) {
				$path = public_path().'/upload/raovat_type/';
				$file = $request->file('image');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name =time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
$file->move($path,$name);
				$raovat_type->image = '/upload/raovat_type/'.$name;
			}
			// else{
			// 	if(!$raovat_type->image)
			// 		$raovat_type->image ='/frontend/assets/img/icon/logo-large.png';
			// }

			if($request->file('background')) {
				$path = public_path().'/upload/raovat_type/';
				$file = $request->file('background');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name ='bg-'.time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
$file->move($path,$name);
				$raovat_type->background = '/upload/raovat_type/'.$name;
			}

			if( $raovat_type->save() ) {

        $active = isset($request->active) ? 1 : 0;
				return redirect()->route('list_raovat_type')->with(['status' => 'Danh mục đã được cập nhật thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được raovat_type']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getDeleteRaovatType($id)
	{
      $raovat_type = RaovatType::find($id);
      $name = $raovat_type->name;
      $raovat_type->delete();
      return redirect()->route('list_raovat_type')->with(['status' => 'Danh mục ' . $name . ' đã xóa thành công ']);
	}

	public function getDownRaovatType($id){
		$current_raovat_type = RaovatType::find($id);
		$change_raovat_type = RaovatType::where('weight','>',$current_raovat_type->weight)->orderBy('weight','asc')->first();
		$old_weight = $current_raovat_type->weight;
		$current_raovat_type->weight = $change_raovat_type->weight;
		$change_raovat_type->weight = $old_weight;
		$current_raovat_type->save();
		$change_raovat_type->save();
		return redirect()->route('list_raovat_type');
	}

	public function getUpRaovatType($id){
		$current_raovat_type = RaovatType::find($id);
		$change_raovat_type = RaovatType::where('weight','<',$current_raovat_type->weight)->orderBy('weight','desc')->first();
		$old_weight = $current_raovat_type->weight;
		$current_raovat_type->weight = $change_raovat_type->weight;
		$change_raovat_type->weight = $old_weight;
		$current_raovat_type->save();
		$change_raovat_type->save();
		return redirect()->route('list_raovat_type');
	}

	public function getChangeWeightRaovatType($id,$weight){
		$current_raovat_type = RaovatType::find($id);
		if($current_raovat_type){
			$current_raovat_type->weight = $weight;
			$current_raovat_type->save();
			return redirect()->route('list_raovat_type');
		}
	}
}

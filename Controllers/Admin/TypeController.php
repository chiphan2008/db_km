<?php

namespace App\Http\Controllers\Admin;
use App\Models\Booking\Type;
use App\Models\Booking\HotelType;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;

class TypeController extends BaseController
{
	public function getListType(){
		$per_page = 15;
		$input = request()->all();
		$all_type = Type::orderBy('type.weight','ASC');

		if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}
		if (isset($keyword) && $keyword != '') {
			$all_type->where('type.name','like','%'.$keyword.'%');
		}


		$list_type = $all_type->paginate($per_page);
		return view('Admin.type.list', ['list_type' => $list_type,'keyword'=>$keyword]);
	}


	public function getAddType(){
		return view('Admin.type.add');
	}

	public function postAddType(Request $request){
		$rules = [
			'name' => 'required',
			'machine_name' => 'required|unique:booking.type,machine_name',
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
			$type = new Type();
			$type->name = $request->name;
			$type->machine_name = $request->machine_name;
			$type->alias = $request->alias;
			$type->weight = Type::max('weight') + 1;
			$type->description = $request->description;
			$type->active =  isset($request->active);
			$type->created_by = Auth::guard('web')->user()->id;
			$type->updated_by = Auth::guard('web')->user()->id;
			if($request->file('image')) {
				$path = public_path().'/upload/type/';
				$file = $request->file('image');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name =time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
$file->move($path,$name);
				$type->image = '/upload/type/'.$name;
			}else{
				$type->image ='/frontend/assets/img/upload/bg-header-page.png';
			}

			if( $type->save() ) {
				return redirect()->route('list_type')->with(['status' => trans('valid.add_success_type')]);
			} else {
				$errors = new MessageBag(['error' => trans('valid.not_create_type')]);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getUpdateType($id){
		$type = Type::find($id);
		if(!$type){
			abort(404);
		}
		return view('Admin.type.update',['type'=>$type]);
	}

	public function postUpdateType(Request $request,$id){
		$rules = [
			'name' => 'required',
			'alias'=>'required'
		];
		$messages = [
			'name.required' => trans('valid.name_required'),
			'alias.required' => trans('valid.alias_required')
		];
		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$type = Type::find($id);
			if(!$type){
				abort(404);
			}
			$type->name = $request->name;
			$type->machine_name = $request->machine_name;
			$type->alias = $request->alias;
			$type->weight = $request->weight?$request->weight:0;
			$type->description = $request->description;
			$type->active =  isset($request->active);
			$type->created_by = Auth::guard('web')->user()->id;
			$type->updated_by = Auth::guard('web')->user()->id;
			if($request->file('image')) {
				$path = public_path().'/upload/type/';
				$file = $request->file('image');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name =time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
$file->move($path,$name);
				$type->image = '/upload/type/'.$name;
			}

			if( $type->save() ) {
				return redirect()->route('list_type')->with(['status' => trans('valid.update_success_type')]);
			} else {
				$errors = new MessageBag(['error' => trans('valid.not_update_type') ]);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getDeleteType($id)
	{
		$hotel = HotelType::where('type_id','=',$id)->count();
		if($hotel > 0)
		{
			$type = Type::find($id);
			$name = $type->name;
			return redirect()->route('list_type')->with(['err' => trans('valid.type') . $name . trans('valid.can_not_delete')]);
		}
		else {
			$type = Type::find($id);
			$name = $type->name;
			$type->delete();
			return redirect()->route('list_type')->with(['status' => trans('valid.type') . $name . trans('valid.del_success')]);
		}
	}

	public function getChangeWeightType($id,$weight){
		$current_type = Type::find($id);
		if($current_type){
			$current_type->weight = $weight;
			$current_type->save();
			return redirect()->route('list_type');
		}
	}
}

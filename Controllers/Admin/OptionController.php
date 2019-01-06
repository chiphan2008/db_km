<?php

namespace App\Http\Controllers\Admin;
use App\Models\Booking\Hotel;
use App\Models\Booking\RoomType;
use App\Models\Booking\RoomTypeImage;
use App\Models\Booking\RoomTypeOption;
use App\Models\Booking\Option;
use App\Models\Booking\RoomTypePrice;
use App\Models\Location\Content;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Validator;
use Carbon\Carbon;

class OptionController extends BaseController
{
	public function getListOption($hotel_id){
		$per_page = Session::has('pagination.' . \Route::currentRouteName()) ? session('pagination.' . \Route::currentRouteName()) : 10;
		$input = request()->all();

		$all_option = Option::select('option.*')
											->with('_created_by')
											->with('_updated_by');


		if (isset($input['keyword']) && $input['keyword']!='') {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		$list_option = $all_option->paginate($per_page);

		return view('Admin.option.list', ['list_option' => $list_option,'hotel_id'=>$hotel_id, 'keyword'=>$keyword]);
	}

	public function getAddOption($hotel_id){
		return view('Admin.option.add',['hotel_id'=>$hotel_id]);
	}

	public function postAddOption(Request $request, $hotel_id){
		// dd($request->all());
		$rules = [
        'name' => 'required'
    ];
    $messages = [
        'name.required' => trans('valid.name_required')
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {
    	$id_user = Auth::guard('web')->user()->id;
    	$option = new Option();
			$option->hotel_id = $request->hotel_id;
			$option->name = $request->name;
			$option->weight = $request->weight?$request->weight:0;
			$option->active = $request->has('active');
			if($request->extra){
				$option->extra = $request->has('extra');
				$option->price_extra = $request->price_extra?$request->price_extra:0;
			}
			$option->created_by = $id_user;
	  	$option->updated_by = $id_user;
	  	$option->created_at = Carbon::now();
	  	$option->updated_at = Carbon::now();
			if($option->save()){
				return redirect()->route('list_option',['hotel_id'=>$hotel_id])->with(['status' => 'Option <a href="' . route('update_option',['id' => $option->id]) . '">' . $option->name . '</a> đã được tạo thành công</a>']);
			}
    }
	}

	public function getUpdateOption($id){
		$option = Option::find($id);
		if(!$option){
			abort(404);
		}
		return view('Admin.option.update',['option'=>$option]);
	}

	public function postUpdateOption(Request $request, $id){
		$rules = [
        'name' => 'required'
    ];
    $messages = [
        'name.required' => trans('valid.name_required')
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {
    	$id_user = Auth::guard('web')->user()->id;
    	$option = Option::find($id);
			if(!$option){
				abort(404);
			}
			$option->name = $request->name;
			$option->weight = $request->weight?$request->weight:0;
			$option->active = $request->has('active');
			if($request->has('extra')){
				$option->extra = $request->has('extra');
				$option->price_extra = $request->price_extra?$request->price_extra:0;
			}else{
				$option->extra = $request->has('extra');
				$option->price_extra = 0;
			}
	  	$option->updated_by = $id_user;
	  	$option->updated_at = Carbon::now();
			if($option->save()){
				return redirect()->route('list_option',['hotel_id'=>$option->hotel_id])->with(['status' => 'Option <a href="' . route('update_option',['id' => $option->id]) . '">' . $option->name . '</a> đã được cập nhật thành công</a>']);
			}
    }
	}

	public function getDeleteOption($id){
		$option = Option::find($id);
  	if(!$option){
  		abort(404);
  	}
		Option::where('id',$option->id)->delete();
  	return redirect()->route('list_option',['hotel_id'=>$option->hotel_id])->with(['status' => 'Room type <a href="' . route('update_option',['id' => $option->id]) . '">' . $option->name . '</a> đã được xóa thành công</a>']);
	}
}

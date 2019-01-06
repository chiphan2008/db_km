<?php

namespace App\Http\Controllers\Admin;
use App\Models\Booking\Hotel;
use App\Models\Booking\Type;
use App\Models\Booking\HotelType;
use App\Models\Location\Content;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;

class HotelController extends BaseController
{
	public function getListHotel(){
		$per_page = Session::has('pagination.' . \Route::currentRouteName()) ? session('pagination.' . \Route::currentRouteName()) : 10;
		$input = request()->all();
		$all_hotel = Hotel::with('_content')
											->with('_types')
											->with('_created_by')
											->with('_updated_by')
											->where('hotel.active', '=', 1);

		if (isset($input['keyword']) && $input['keyword']!='') {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}
		if (isset($keyword) && $keyword != '') {
			$all_hotel->leftJoin(\Config::get('database.connections.mysql.database').'.contents','contents.id', 'content_id')
								->where('contents.name','like','%'.$keyword.'%');
		}

		if (isset($input['type']) && $input['type']!='') {
			$type = $input['type'];
		} else {
			$type = 0;
		}

		if (isset($type) && $type != 0) {
			$all_hotel->leftJoin('hotel_type','hotel_id', 'hotel.id')
								->where('type_id',$type);
		}
		
		$list_hotel = $all_hotel->paginate($per_page);

		$types = Type::where('active',1)
								 ->orderBy('weight')
								 ->get();
		return view('Admin.hotel.list', ['list_hotel' => $list_hotel,'keyword'=>$keyword, 'types'=>$types, 'type'=>$type]);
	}

	public function getAddHotel(){
		$types = Type::where('active',1)
								 ->orderBy('weight')
								 ->get();
		return view('Admin.hotel.add',['types'=>$types]);
	}

	public function getSearchHotel(Request $request){
		$arr_return = [];
		$input = request()->all();
		if(isset($input['query']) && $input['query']!=''){
			$keyword = $input['query'];
			$contents = Content::select('contents.*')
											 ->where('name','like','%'.$keyword.'%')
											 ->where('id_category','=',6)
											 ->leftJoin(\Config::get('database.connections.booking.database').'.hotel','hotel.content_id','contents.id')
											 ->whereNull('hotel.content_id')
											 ->limit(10)
											 ->get();
			foreach ($contents as $key => $content) {
				$arr_tmp = [];
				$arr_tmp['value'] = $content->name;
				$arr_tmp['data'] = $content->id;
				$arr_return[] = $arr_tmp;
			}
		}

		return response()->json(['suggestions'=>$arr_return]);
	}

	public function postAddHotel(Request $request){
		
		$rules = [
			'content_id' => 'required|unique:booking.hotel,content_id',
			'type' => 'required'
		];
		$messages = [
			'content_id.required' => 'Chưa chọn khách sạn',
			'content_id.unique' => 'khách sạn đã được tạo',
			'type.required'=>'Type là trường bắt buộc'
		];

		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$hotel = new Hotel();
			$hotel->content_id = $request->content_id;
			$hotel->active =  isset($request->active);
			$hotel->created_by = Auth::guard('web')->user()->id;
			$hotel->updated_by = Auth::guard('web')->user()->id;
			if( $hotel->save() ) {
				$hotel_id = $hotel->id;
				if($request->type){
					foreach ($request->type as $key => $type) {
						HotelType::create([
														'hotel_id' => $hotel_id,
														'type_id' => $type,
												]);
					}
				}
				return redirect()->route('list_hotel')->with(['status' => 'Khách sạn đã được thêm thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được hotel']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getUpdateHotel($id){
		$hotel = Hotel::where('id',$id)
									->with('_content')
									->with('_types')
									->first();
		if(!$hotel){
			abort(404);
		}
		$types = Type::where('active',1)
								 ->orderBy('weight')
								 ->get();
		return view('Admin.hotel.update',['hotel'=>$hotel, 'types'=>$types]);
	}

	public function postUpdateHotel(Request $request, $id){
		
		$rules = [
			'type' => 'required'
		];
		$messages = [
			'type.required'=>'Type là trường bắt buộc'
		];

		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$hotel = Hotel::find($id);
			$hotel->content_id = $request->content_id;
			$hotel->active =  isset($request->active);
			$hotel->updated_by = Auth::guard('web')->user()->id;
			if( $hotel->save() ) {
				$hotel_id = $hotel->id;
				if($request->type){
					HotelType::where('hotel_id',$id)->delete();
					foreach ($request->type as $key => $type) {
						HotelType::create([
														'hotel_id' => $hotel_id,
														'type_id' => $type,
												]);
					}
				}
				$hotel = Hotel::where('id',$id)
									->with('_content')
									->first();

				return redirect()->route('list_hotel')->with(['status' => 'Khách sạn '.$hotel->name.' đã được cập nhật thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không cập nhật được hotel']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getDeleteHotel($id){
		$hotel = Hotel::where('id',$id)->with('_content')->first();
		if(!$hotel){
			abort(404);
		}
		$name = $hotel->_content->name;
		HotelType::where('hotel_id',$id)->delete();
		$hotel->delete();
		return redirect()->route('list_hotel')->with(['status' => 'Khách sạn ' . $name . ' đã xóa thành công ']);
	}
}
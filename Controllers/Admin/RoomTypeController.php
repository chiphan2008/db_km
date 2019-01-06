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

class RoomTypeController extends BaseController
{
	public function getListRoomType($hotel_id){
		$per_page = Session::has('pagination.' . \Route::currentRouteName()) ? session('pagination.' . \Route::currentRouteName()) : 10;
		$input = request()->all();

		$all_room_type = RoomType::select('room_type.*')
											->where('hotel_id', '=', $hotel_id)
											->with('_created_by')
											->with('_updated_by');


		if (isset($input['keyword']) && $input['keyword']!='') {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		$list_room_type = $all_room_type->paginate($per_page);

		return view('Admin.room_type.list', ['list_room_type' => $list_room_type,'hotel_id'=>$hotel_id, 'keyword'=>$keyword]);
	}

	public function getAddRoomType($hotel_id){
		$options = Option::where('active',1)
										 ->where('extra',0)
										 ->get();
		$options_extra = Option::where('active',1)
										 ->where('extra',1)
										 ->get();
		
		return view('Admin.room_type.add',['options'=>$options, 'options_extra'=>$options_extra,'hotel_id'=>$hotel_id]);
	}

	public function postAddRoomType(Request $request, $hotel_id){
		 		$rules = [
            'name' => 'required',
            'customer' => 'required',
        ];
        $messages = [
            'name.required' => trans('valid.name_required'),
            'customer.required' => 'Số người là trường bắt buộc',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
          return redirect()->back()->withErrors($validator)->withInput();
        } else {
        	$id_user = Auth::guard('web')->user()->id;
        	$room_type = new RoomType();
        	$room_type->hotel_id = $hotel_id;
        	$room_type->name = $request->name;
        	$room_type->customer = $request->customer;
        	$room_type->weight = $request->weight?$request->weight:0;
        	$room_type->description = $request->description?$request->description:'';
        	$room_type->active = $request->has('active');
        	$room_type->price = $request->price?$request->price:0;
        	$room_type->price_km = $request->price_km?$request->price_km:0;
        	$room_type->active_no_cancel = $request->has('active_no_cancel');
        	if($request->has('cancel')){
        		$room_type->cancel = $request->has('cancel');
        		$room_type->policy_cancel = $request->policy_cancel?$request->policy_cancel:'';
        		$room_type->price_cancel = $request->price_cancel?$request->price_cancel:0;
        		$room_type->price_cancel_km = $request->price_cancel_km?$request->price_cancel_km:0;
        		$room_type->active_cancel = $request->has('active_cancel');
        	}
        	$room_type->created_by = $id_user;
        	$room_type->updated_by = $id_user;
        	$room_type->created_at = Carbon::now();
        	$room_type->updated_at = Carbon::now();
        	if($room_type->save()){
        		RoomTypeOption::where('room_type_id',$room_type->id)->delete();
        		if($request->has('option_no_price') && count($request->option_no_price)){
        			foreach ($request->option_no_price as $key => $option) {
        				$room_type_option = new RoomTypeOption();
        				$room_type_option->room_type_id = $room_type->id;
        				$room_type_option->option_id = $option;
        				$room_type_option->created_by = $id_user;
			        	$room_type_option->updated_by = $id_user;
			        	$room_type_option->created_at = Carbon::now();
			        	$room_type_option->updated_at = Carbon::now();
			        	$room_type_option->save();
        			}
        		}
        		if($request->has('option_extra') && count($request->option_extra)){
        			foreach ($request->option_extra as $key => $option) {
        				$room_type_option = new RoomTypeOption();
        				$room_type_option->room_type_id = $room_type->id;
        				$room_type_option->option_id = $option;
        				$room_type_option->created_by = $id_user;
			        	$room_type_option->updated_by = $id_user;
			        	$room_type_option->created_at = Carbon::now();
			        	$room_type_option->updated_at = Carbon::now();
			        	$room_type_option->save();
        			}
        		}

        		if($request->image){
        			$path = public_path() . '/upload/room_type/';
              $path_thumbnail = public_path() . '/upload/room_type_thumbnail/';
              if (!\File::exists($path)) {
                  \File::makeDirectory($path, $mode = 0777, true, true);
              }
              if (!\File::exists($path_thumbnail)) {
                  \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
              }
        			foreach ($request->image as $key => $file) {
        				$img_name = time() . '_room_type_' . vn_string($file->getClientOriginalName());

                if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
self::waterMark($file, $img_name, $path, $path_thumbnail);

                $image_room_type = '/upload/room_type/' . $img_name;


                $room_type_image = new RoomTypeImage();
                $room_type_image->room_type_id	= $room_type->id;
                $room_type_image->link        	= $image_room_type;
                $room_type_image->save();
        			}
        		}


        		$room_type_price = new RoomTypePrice();
        		$room_type_price->room_type_id = $room_type->id;
						$room_type_price->price = $room_type->price?$room_type->price:0;
						$room_type_price->price_km = $room_type->price_km?$room_type->price_km:0;

						$room_type_price->price_cancel = $room_type->price_cancel?$room_type->price_cancel:0;
						$room_type_price->price_cancel_km = $room_type->price_cancel_km?$room_type->price_cancel_km:0;

						$room_type_price->created_by = $id_user;
	        	$room_type_price->updated_by = $id_user;
	        	$room_type_price->created_at = Carbon::now();
	        	$room_type_price->updated_at = Carbon::now();
	        	$room_type_price->save();
        	}

        	return redirect()->route('list_room_type',['hotel_id'=>$hotel_id])->with(['status' => 'Room type <a href="' . route('update_room_type',['id' => $room_type->id]) . '">' . $room_type->name . '</a> đã được tạo thành công</a>']);
        }
	}

	public function getUpdateRoomType($id){
		$room_type = RoomType::where('id',$id)
											 ->with('_options')
											 ->with('_options_extra')
											 ->with('_images')
											 ->first();
		if(!$room_type){
			abort(404);
		}
		$options = Option::where('active',1)
										 ->where('extra',0)
										 ->get();
		$options_extra = Option::where('active',1)
										 ->where('extra',1)
										 ->get();
		return view('Admin.room_type.update',['room_type'=>$room_type, 'options'=>$options, 'options_extra'=>$options_extra,'hotel_id'=>$room_type->hotel_id]);
	}

	public function postUpdateRoomType(Request $request,$id){
		 		$rules = [
            'name' => 'required',
            'customer' => 'required',
        ];
        $messages = [
            'name.required' => trans('valid.name_required'),
            'customer.required' => 'Số người là trường bắt buộc',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
          return redirect()->back()->withErrors($validator)->withInput();
        } else {
        	$id_user = Auth::guard('web')->user()->id;
        	$room_type = RoomType::find($id);
        	if(!$room_type){
        		abort(404);
        	}
        	$room_type->name = $request->name;
        	$room_type->customer = $request->customer;
        	$room_type->weight = $request->weight?$request->weight:0;
        	$room_type->description = $request->description?$request->description:'';
        	$room_type->active = $request->has('active');
        	$room_type->price = $request->price?$request->price:0;
        	$room_type->price_km = $request->price_km?$request->price_km:0;
        	$room_type->active_no_cancel = $request->has('active_no_cancel');
        	if($request->has('cancel')){
        		$room_type->cancel = $request->has('cancel');
        		$room_type->policy_cancel = $request->policy_cancel?$request->policy_cancel:'';
        		$room_type->price_cancel = $request->price_cancel?$request->price_cancel:0;
        		$room_type->price_cancel_km = $request->price_cancel_km?$request->price_cancel_km:0;
        		$room_type->active_cancel = $request->has('active_cancel');
        	}else{
        		$room_type->cancel = 0;
						$room_type->price_cancel  = 0;
						$room_type->price_cancel_km = 0;
						$room_type->active_cancel = 0;
        	}
        	$room_type->updated_by = $id_user;
        	$room_type->updated_at = Carbon::now();
        	if($room_type->save()){
        		RoomTypeOption::where('room_type_id',$room_type->id)->delete();
        		if($request->has('option_no_price') && count($request->option_no_price)){
        			foreach ($request->option_no_price as $key => $option) {
        				$room_type_option = new RoomTypeOption();
        				$room_type_option->room_type_id = $room_type->id;
        				$room_type_option->option_id = $option;
        				$room_type_option->created_by = $id_user;
			        	$room_type_option->updated_by = $id_user;
			        	$room_type_option->created_at = Carbon::now();
			        	$room_type_option->updated_at = Carbon::now();
			        	$room_type_option->save();
        			}
        		}
        		if($request->has('option_extra') && count($request->option_extra)){
        			foreach ($request->option_extra as $key => $option) {
        				$room_type_option = new RoomTypeOption();
        				$room_type_option->room_type_id = $room_type->id;
        				$room_type_option->option_id = $option;
        				$room_type_option->created_by = $id_user;
			        	$room_type_option->updated_by = $id_user;
			        	$room_type_option->created_at = Carbon::now();
			        	$room_type_option->updated_at = Carbon::now();
			        	$room_type_option->save();
        			}
        		}

        		if($request->image){
        			$path = public_path() . '/upload/room_type/';
              $path_thumbnail = public_path() . '/upload/room_type_thumbnail/';
              if (!\File::exists($path)) {
                  \File::makeDirectory($path, $mode = 0777, true, true);
              }
              if (!\File::exists($path_thumbnail)) {
                  \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
              }
        			foreach ($request->image as $key => $file) {
        				$img_name = time() . '_room_type_' . vn_string($file->getClientOriginalName());

                if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
self::waterMark($file, $img_name, $path, $path_thumbnail);

                $image_room_type = '/upload/room_type/' . $img_name;


                $room_type_image = new RoomTypeImage();
                $room_type_image->room_type_id	= $room_type->id;
                $room_type_image->link        	= $image_room_type;
                $room_type_image->save();
        			}
        		}


        		$room_type_price = new RoomTypePrice();
        		$room_type_price->room_type_id = $room_type->id;
						$room_type_price->price = $room_type->price?$room_type->price:0;
						$room_type_price->price_km = $room_type->price_km?$room_type->price_km:0;

						$room_type_price->price_cancel = $room_type->price_cancel?$room_type->price_cancel:0;
						$room_type_price->price_cancel_km = $room_type->price_cancel_km?$room_type->price_cancel_km:0;

						$room_type_price->created_by = $id_user;
	        	$room_type_price->updated_by = $id_user;
	        	$room_type_price->created_at = Carbon::now();
	        	$room_type_price->updated_at = Carbon::now();
	        	$room_type_price->save();
        	}

        	return redirect()->route('list_room_type',['hotel_id'=>$room_type->hotel_id])->with(['status' => 'Room type <a href="' . route('update_room_type',['id' => $room_type->id]) . '">' . $room_type->name . '</a> đã được cập nhật thành công</a>']);
        }
	}

	public function getDeleteRoomType($id){
		$room_type = RoomType::find($id);
  	if(!$room_type){
  		abort(404);
  	}

  	RoomTypeOption::where('room_type_id',$room_type->id)->delete();
		RoomTypeImage::where('room_type_id',$room_type->id)->delete();
		RoomTypePrice::where('room_type_id',$room_type->id)->delete();
		RoomType::where('id',$room_type->id)->delete();
  	return redirect()->route('list_room_type',['hotel_id'=>$room_type->hotel_id])->with(['status' => 'Room type <a href="' . route('update_room_type',['id' => $room_type->id]) . '">' . $room_type->name . '</a> đã được xóa thành công</a>']);
	}

	public function postDeleteImg(Request $request){
		$id = $request->id;
		$image = RoomTypeImage::find($id);
    if (file_exists(public_path($image['link']))) {
        unlink(public_path($image['link']));
    }
    if (file_exists(public_path(str_replace('room_type', 'room_type_thumbnail', $image['link'])))) {
        unlink(public_path(str_replace('room_type', 'room_type_thumbnail', $image['link'])));
    }
    $image->delete();
    echo 'success';
	}

	public function waterMark($file, $img_name, $path, $path_thumbnail, $type = null) {
        if ($type == 'import') {
            $img = Image::make($file)->orientate();
            $width = $img->getSize()->getWidth();
            $height = $img->getSize()->getHeight();

            $max_height = 720;
            $max_width = 1280;

            if ($width > $max_width || $height > $max_height) {
                $img = Image::make($file)->orientate()->resize(1280, 720, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            $max = $width > $height ? $width : $height;

            $wt = Image::make(public_path() . DS . 'img_default' . DS . 'kingmap.png');
            $img->insert($wt, 'center');
            $img->insert($wt, 'center');

            $img->save($path . $img_name);

            $img_thumbnail = Image::make($file)->orientate()->fit(270, 202, function ($constraint) {
                        $constraint->upsize();
                    })->insert(Image::make('img_default/kingmap.png')->resize(95, 14), 'center');
            $img_thumbnail->save($path_thumbnail . $img_name);
        } else {
            $img = Image::make($file)->orientate();
            $width = $img->getSize()->getWidth();
            $height = $img->getSize()->getHeight();

            $max_height = 720;
            $max_width = 1280;

            if ($width > $max_width || $height > $max_height) {
                $img = Image::make($file)->orientate()->resize(1280, 720, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            $max = $width > $height ? $width : $height;

            $wt = Image::make(public_path() . DS . 'img_default' . DS . 'kingmap.png');
            $img->insert($wt, 'center');
            $img->insert($wt, 'center');

            $img->save($path . $img_name);

            $img_thumbnail = Image::make($file)->orientate()->fit(270, 202, function ($constraint) {
                        $constraint->upsize();
                    })
                    ->insert(Image::make('img_default/kingmap.png')->resize(95, 14), 'center')
                    ->insert(Image::make('img_default/kingmap.png')->resize(95, 14), 'center');
            $img_thumbnail->save($path_thumbnail . $img_name);
        }
    }
}
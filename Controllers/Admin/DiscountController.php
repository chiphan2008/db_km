<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;

use App\Models\Discount\Discount;
use App\Models\Discount\DiscountImage;
use App\Models\Discount\DiscountContent;
use App\Models\Discount\DateDiscount;
use App\Models\Location\Content;
use App\Models\Location\Client;
use App\Models\Location\Notifi;



class DiscountController extends BaseController
{
	public function getListDiscount(Request $request)
	{
		$per_page = Session::has('pagination.' . \Route::currentRouteName()) ? session('pagination.' . \Route::currentRouteName()) : 10;
		$input = request()->all();

		$all_discount = Discount::select('discount.*')
											->with('_created_by')
											->with('_created_by_client')
                      ->orderBy('date_to','DESC')
                      ->orderBy('id');;


		if (isset($input['keyword']) && $input['keyword']!='') {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}
		if($keyword != ''){
			$all_discount->where('name','LIKE', '%'.$keyword.'%');
		}

		$list_discount = $all_discount->paginate($per_page);

		return view('Admin.discount.list', ['list_discount' => $list_discount, 'keyword'=>$keyword]);
	}

	public function getSearchContent(Request $request){
		$arr_return = [];
		$input = request()->all();
		if(isset($input['query']) && $input['query']!=''){
      $keyword = $input['query'];
      $list_km_running = [];
      $list_km_running = Discount::where('active',1)
                                 ->where('date_from','<=',Carbon::now())
                                 ->where('date_to','>=',Carbon::now())->pluck('id');
      $list_content_km = [];
      $list_content_km = DiscountContent::whereIn('discount_id',$list_km_running)
                                        ->pluck('id_content');

			$contents = Content::select('contents.*')
											 ->where('name','like','%'.$keyword.'%')
											 ->where('active',1)
											 ->where('moderation','publish')
                       ->whereNotIn('id',$list_content_km)
											 ->limit(10)
											 ->get();
			foreach ($contents as $key => $content) {
				$arr_tmp = [];
				$arr_tmp['id'] = $content->id;
				$arr_tmp['text'] = $content->name;
				$arr_return[] = $arr_tmp;
			}
		}

		return response()->json(['results'=>$arr_return]);
	}

	public function getAddDiscount(){
		$contents = [];
		$clients	= $clients = Client::get();
 		return view('Admin.discount.add', [
				'contents' => $contents,
				'clients' => $clients,
		]);
	}

	public function postAddDiscount(Request $request){
    // dd($request->all());
		$arrReturn = [
			'error'=>1,
			'message'=> '',
			'data'=>[]
		];
		$rules = [
      'name' => 'required',
      'slogan'=> 'required',
      'type' => 'required',
      'content' => 'required',
      'description' => 'required',
    ];
    $messages = [
      'name.required' => trans('valid.name_required'),
      'slogan.required' => trans('valid.slogan_required'),
      'type.required' => trans('valid.type_required'),
      'content.required' => trans('valid.content_required'),
      'description.required' => trans('valid.discount_description_required'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
    	return redirect()->back()->withErrors($validator)->withInput();
    } else {
    	$discount = new Discount();
    	$discount->name             = $request->name ;
    	$discount->alias            = '' ;
    	$discount->slogan           = $request->slogan ;
    	$discount->type             = $request->type ;

      $discount->from_percent     = $request->from_percent?$request->from_percent:0;
      $discount->to_percent       = $request->to_percent?$request->to_percent:0;
      $discount->from_price       = $request->from_price?$request->from_price:0;
      $discount->to_price         = $request->to_price?$request->to_price:0;
      $discount->currency         = $request->currency?$request->currency:'VND';
      $discount->short_text       = $request->short_text?$request->short_text:'';

    	$discount->description      = $request->description ;
    	$discount->link             = $request->discount_link?$request->discount_link:'' ;
    	$discount->phone            = $request->discount_phone?$request->discount_phone:'' ;
    	$discount->active 					=  isset($request->active);
    	$discount->img_from_content = $request->img_from_content?1:0;
    	$discount->date_from        = new Carbon($request->discount_from.'00:00:00');
    	$discount->date_to          = new Carbon($request->discount_to.'23:59:59');
    	if($request->user){
    		$discount->created_by       = $request->user;
        $discount->type_user        = 0;
    	}else{
    		$discount->type_user				= 1;
    		$discount->created_by       = Auth::guard('web')->user()->id ;
    	}    	
    	$discount->updated_by       = Auth::guard('web')->user()->id ;
    	if($discount->save()){
    		$discount->alias = str_slug_custom($discount->name).'-'.$discount->id;
    		$discount->save();
    		if($discount->img_from_content==0 && count($request->discount_image)){
    			$path = public_path() . '/upload/discount/';
          $path_thumbnail = public_path() . '/upload/discount_thumbnail/';
          if (!\File::exists($path)) {
              \File::makeDirectory($path, $mode = 0777, true, true);
          }
          if (!\File::exists($path_thumbnail)) {
              \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
          }
          foreach ($request->discount_image as $file) {

              $img_name = time() . '_discount_' . vn_string($file->getClientOriginalName());

              waterMark($file, $img_name, $path, $path_thumbnail);

              $image_space = '/upload/discount/' . $img_name;

              DiscountImage::create([
                  'discount_id' => $discount->id,
                  'link' => $image_space,
              ]);
          }
    		}
    		if($request->content){
    			foreach ($request->content as $key => $content) {
    				DiscountContent::create([
                'discount_id' => $discount->id,
                'id_content' => $content,
            ]);
    			}
    		}
    		// dd($request->date_open);
    		if ($request->date_open) {
            foreach ($request->date_open as $value) {
                if ($value['from_hour'] && $value['to_hour']) {
                    DateDiscount::create([
                        'discount_id' => $discount->id,
                        'date_from' => $value['from_date'],
                        'date_to' => $value['to_date'],
                        'time_from' => $value['from_hour'],
                        'time_to' => $value['to_hour'],
                    ]);
                }
            }
        }
    	}
    	return redirect()->route('list_discount')->with(['status' => trans('Admin'.DS.'discount.discount').' '.'<a href="'.route('update_discount',['id'=>$discount->id]).'">'.$discount->name.'</a>'.' '.trans('valid.added_successful')]);
    }
	}

	public function getUpdateDiscount(Request $request, $id){
		$discount = Discount::where('id',$id)
											->with('_contents')
											->with('_images')
											->with('_created_by')
											->with('_created_by_client')
											->first();
		// dd($discount);
		$contents = [];
		$clients	= $clients = Client::get();
 		return view('Admin.discount.update', [
				'contents' => $contents,
				'clients' => $clients,
				'discount'=> $discount,
		]);
	}

	public function postUpdateDiscount(Request $request, $id){
		$arrReturn = [
			'error'=>1,
			'message'=> '',
			'data'=>[]
		];
		$rules = [
      'name' => 'required',
      'slogan'=> 'required',
      'type' => 'required',
      'content' => 'required',
      'description' => 'required',
    ];
    $messages = [
      'name.required' => trans('valid.name_required'),
      'slogan.required' => trans('valid.slogan_required'),
      'type.required' => trans('valid.type_required'),
      'content.required' => trans('valid.content_required'),
      'description.required' => trans('valid.discount_description_required'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
    	return redirect()->back()->withErrors($validator)->withInput();
    } else {
    	$discount = Discount::find($id);
    	if(!$discount){
    		abort(404);
    	}
      $old_active = $discount->active;
    	$discount->name             = $request->name ;
    	$discount->alias            = '' ;
    	$discount->slogan           = $request->slogan ;
    	$discount->type             = $request->type ;

      $discount->from_percent     = $request->from_percent?$request->from_percent:0;
      $discount->to_percent       = $request->to_percent?$request->to_percent:0;
      $discount->from_price       = $request->from_price?$request->from_price:0;
      $discount->to_price         = $request->to_price?$request->to_price:0;
      $discount->currency         = $request->currency?$request->currency:'VND';
      $discount->short_text       = $request->short_text?$request->short_text:'';

    	$discount->description      = $request->description ;
    	$discount->link             = $request->discount_link?$request->discount_link:'' ;
    	$discount->phone            = $request->discount_phone?$request->discount_phone:'' ;
    	$discount->active 					=  isset($request->active);
    	$discount->img_from_content = $request->img_from_content?1:0;
    	$discount->date_from        = new Carbon($request->discount_from.'00:00:00');
    	$discount->date_to          = new Carbon($request->discount_to.'23:59:59');
    	if($request->user){
    		$discount->created_by       = $request->user;
        $discount->type_user        = 0;
    	}else{
    		$discount->type_user				= 1;
    		$discount->created_by       = Auth::guard('web')->user()->id ;
    	}    	
    	$discount->updated_by       = Auth::guard('web')->user()->id ;
    	if($discount->save()){
    		$discount->alias = str_slug_custom($discount->name).'-'.$discount->id;
    		$discount->save();
    		if($discount->img_from_content==0 && count($request->discount_image)){
    			$path = public_path() . '/upload/discount/';
          $path_thumbnail = public_path() . '/upload/discount_thumbnail/';
          if (!\File::exists($path)) {
              \File::makeDirectory($path, $mode = 0777, true, true);
          }
          if (!\File::exists($path_thumbnail)) {
              \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
          }
          foreach ($request->discount_image as $file) {

              $img_name = time() . '_discount_' . vn_string($file->getClientOriginalName());

              waterMark($file, $img_name, $path, $path_thumbnail);

              $image_space = '/upload/discount/' . $img_name;

              DiscountImage::create([
                  'discount_id' => $discount->id,
                  'link' => $image_space,
              ]);
          }
    		}
    		if($request->content){
    			 DiscountContent::where('discount_id',$id)->delete();
    			foreach ($request->content as $key => $content) {
    				DiscountContent::create([
                'discount_id' => $discount->id,
                'id_content' => $content,
            ]);
    			}
    		}

    		if ($request->date_open) {
    			DateDiscount::where('discount_id',$id)->delete();
            foreach ($request->date_open as $value) {
                if ($value['from_hour'] && $value['to_hour']) {
                    DateDiscount::create([
                        'discount_id' => $discount->id,
                        'date_from' => $value['from_date'],
                        'date_to' => $value['to_date'],
                        'time_from' => $value['from_hour'],
                        'time_to' => $value['to_hour'],
                    ]);
                }
            }
        }

        if($discount->type_user==0){
          if($discount->active && $discount->active != $old_active){
            $notifi = new Notifi();
            $notifi->createNotifiUserByTemplate('Admin'.DS.'discount.active_discount',$discount->created_by,['discount'=>$discount->name]);
          }else{
            $notifi = new Notifi();
            $notifi->createNotifiUserByTemplate('Admin'.DS.'discount.inactive_discount',$discount->created_by,['discount'=>$discount->name]);
          }
        }
    	}
    	return redirect()->route('list_discount')->with(['status' => trans('Admin'.DS.'discount.discount').' '.'<a href="'.route('update_discount',['id'=>$discount->id]).'">'.$discount->name.'</a>'.' '.trans('valid.added_successful')]);
    }
	}

	public function postDeleteImage(Request $request){
    $id = $request->id;
    $image = DiscountImage::find($id);
    if (file_exists(public_path($image['link']))) {
      unlink(public_path($image['link']));
    }
    if (file_exists(public_path(str_replace('discount', 'discount_thumbnail', $image['link'])))) {
      unlink(public_path(str_replace('discount', 'discount_thumbnail', $image['link'])));
    }
    $image->delete();
    echo 'sussess';
	}

	public function getDeleteDiscount($id){
		$discount = Discount::find($id);
		if(!$discount){
			abort(404);
		}
		$discount->delete();
		$listImage = DiscountImage::where('discount_id',$id)->get();
		foreach ($listImage as $key => $value) {
			$image = $value->toArray();
			if (file_exists(public_path($image['link']))) {
	      unlink(public_path($image['link']));
	    }
	    if (file_exists(public_path(str_replace('discount', 'discount_thumbnail', $image['link'])))) {
	      unlink(public_path(str_replace('discount', 'discount_thumbnail', $image['link'])));
	    }
		}
		DiscountImage::where('discount_id',$id)->delete();
		DiscountContent::where('discount_id',$id)->delete();
		DateDiscount::where('discount_id',$id)->delete();
		return redirect()->route('list_discount')->with(['status' => trans('Admin'.DS.'discount.discount').' '.$discount->name.' '.trans('valid.deleted_successful')]);
	}
}
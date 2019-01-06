<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;

use App\Models\Location\Ads;
use App\Models\Location\PublishAds;
use App\Models\Location\PaymentAds;
use App\Models\Location\PriceAds;
use App\Models\Location\TypeAds;

use App\Models\Location\Content;
use App\Models\Location\TransactionCoin;
use App\Models\Location\NotifiAdmin;
use App\Models\Location\Notifi;

class TypeAdsController extends BaseController
{
	public function getListTypeAds(Request $request){
		$per_page = Session::has('pagination.' . \Route::currentRouteName()) ? session('pagination.' . \Route::currentRouteName()) : 10;
		$input = request()->all();

		$all_type_ads = TypeAds::with('_created_by')
													 ->orderBy('id');


		if (isset($input['keyword']) && $input['keyword']!='') {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}
		if($keyword != ''){
			$all_type_ads->where('type_ads.name','like','%'.$keyword.'%');
		}

		$list_type_ads = $all_type_ads->paginate($per_page);

		return view('Admin.type_ads.list', ['list_type_ads' => $list_type_ads, 'keyword'=>$keyword]);
	}

	public function getAddTypeAds(){
		return view('Admin.type_ads.add',[]);
	}

	public function postAddTypeAds(Request $request){
		// dd($request->all());
		$rules = [
			'name' => 'required',
			'machine_name' => 'required|unique:ads.type_ads,machine_name',
		];
		$messages = [
			'name.required' => trans('valid.name_required'),
			'machine_name.required' => trans('valid.machine_name_required'),
      'machine_name.unique' => trans('valid.machine_name_unique'),
		];
		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$type_ads = new TypeAds();
			$type_ads->name = $request->name;
			$type_ads->machine_name = $request->machine_name;
			$type_ads->kind = $request->kind?$request->kind:'web';
			$type_ads->description = $request->description?$request->description:'';
			$type_ads->width = $request->width?$request->width:0;
			$type_ads->height = $request->height?$request->height:0;
			$type_apply = '';
			if($request->type_apply){
				$type_apply .= isset($request->type_apply['date'])?1:0;
				$type_apply .= isset($request->type_apply['click'])?1:0;
				$type_apply .= isset($request->type_apply['view'])?1:0;
			}else{
				$type_apply = '000';
			}
			$type_ads->type_apply = $type_apply;
			if($request->active){
				$type_ads->active = 1;
			}
			if($request->file('image_default')) {
				$path = public_path().'/upload/type_ads/';
				$file = $request->file('image_default');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name ='default-'.time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
					$file->move($path,$name);
				$type_ads->img_default = '/upload/type_ads/'.$name;
			}

			if($request->file('image_demo')) {
				$path = public_path().'/upload/type_ads/';
				$file = $request->file('image_demo');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name ='demo-'.time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
					$file->move($path,$name);
				$type_ads->img_demo = '/upload/type_ads/'.$name;
			}

			$type_ads->created_by = \Auth::guard('web')->user()->id;
			$type_ads->updated_by = \Auth::guard('web')->user()->id;

			if($type_ads->save()){
				foreach ($request->price_default as $key => $price) {
					$new_price = new PriceAds();
					$new_price->type_ads = $type_ads->id;
					$new_price->type_apply = $price['type_apply'];
					$new_price->min = $price['min'];
					$new_price->max = $price['max'];
					$new_price->price = $price['price'];
					$new_price->created_by = \Auth::guard('web')->user()->id;
					$new_price->updated_by = \Auth::guard('web')->user()->id;
					$new_price->default = 1;
					$new_price->save();
				}
				if($request->custom_price){
					foreach ($request->custom_price as $key => $price) {
						$new_price = new PriceAds();
						$new_price->type_ads = $type_ads->id;
						$new_price->type_apply = $price['type_apply'];
						$new_price->min = $price['min'];
						$new_price->max = $price['max'];
						$new_price->price = $price['price'];
						$new_price->created_by = \Auth::guard('web')->user()->id;
						$new_price->updated_by = \Auth::guard('web')->user()->id;
						$new_price->save();
					}
				}

				return redirect()->route('list_type_ads')->with(['status' => trans('Admin'.DS.'type_ads.type_ads').' '.'<a href="'.route('update_type_ads',['id'=>$type_ads->id]).'">'.$type_ads->name.'</a>'.' '.trans('valid.added_successful')]);
			}
		}
	}

	public function getUpdateTypeAds($id){
		$type_ads = TypeAds::where('id',$id)
											 ->first();
		$price_default = PriceAds::where('type_ads',$type_ads->id)
		                         ->where('default',1)
		                         ->get();
		$price_custom = PriceAds::where('type_ads',$type_ads->id)
		                         ->where('default',0)
		                         ->get();

		return view('Admin.type_ads.update',[
			'type_ads'     	=> $type_ads,
			'price_default'	=> $price_default,
			'price_custom'	=> $price_custom,
		]);
	}

	public function postUpdateTypeAds(Request $request, $id){
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
			$type_ads = TypeAds::find($id);
			if(!$type_ads){
				abort(404);
			}
			$type_ads->name = $request->name;
			// $type_ads->machine_name = $request->machine_name;
			$type_ads->kind = $request->kind;
			$type_ads->description = $request->description?$request->description:'';
			$type_ads->width = $request->width?$request->width:0;
			$type_ads->height = $request->height?$request->height:0;
			$type_ads->kind = $request->kind?$request->kind:'web';
			$type_apply = '';
			if($request->type_apply){
				$type_apply .= isset($request->type_apply['date'])?1:0;
				$type_apply .= isset($request->type_apply['click'])?1:0;
				$type_apply .= isset($request->type_apply['view'])?1:0;
			}else{
				$type_apply = '000';
			}
			$type_ads->type_apply = $type_apply;
			if($request->active){
				$type_ads->active = 1;
			}
			if($request->file('image_default')) {
				$path = public_path().'/upload/type_ads/';
				$file = $request->file('image_default');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name ='default-'.time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
					$file->move($path,$name);
				$type_ads->img_default = '/upload/type_ads/'.$name;
			}

			if($request->file('image_demo')) {
				$path = public_path().'/upload/type_ads/';
				$file = $request->file('image_demo');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name ='demo-'.time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
					$file->move($path,$name);
				$type_ads->img_demo = '/upload/type_ads/'.$name;
			}

			$type_ads->updated_by = \Auth::guard('web')->user()->id;

			if($type_ads->save()){
				PriceAds::where('type_ads',$id)->delete();
				if(!$request->price_default){
					$arr = [
						['price'=>0,'type_apply'=>'date','min'=>0,'max'=>0],
						['price'=>0,'type_apply'=>'click','min'=>0,'max'=>0],
						['price'=>0,'type_apply'=>'view','min'=>0,'max'=>0],
					];
					$request->price_default = $arr;
				}
				foreach ($request->price_default as $key => $price) {
					$new_price = new PriceAds();
					$new_price->type_ads = $type_ads->id;
					$new_price->type_apply = $price['type_apply'];
					$new_price->min = $price['min'];
					$new_price->max = $price['max'];
					$new_price->price = $price['price'];
					$new_price->created_by = \Auth::guard('web')->user()->id;
					$new_price->updated_by = \Auth::guard('web')->user()->id;
					$new_price->default = 1;
					$new_price->save();
				}
				if($request->custom_price){
					foreach ($request->custom_price as $key => $price) {
						$new_price = new PriceAds();
						$new_price->type_ads = $type_ads->id;
						$new_price->type_apply = $price['type_apply'];
						$new_price->min = $price['min'];
						$new_price->max = $price['max'];
						$new_price->price = $price['price'];
						$new_price->created_by = \Auth::guard('web')->user()->id;
						$new_price->updated_by = \Auth::guard('web')->user()->id;
						$new_price->save();
					}
				}

				return redirect()->route('list_type_ads')->with(['status' => trans('Admin'.DS.'type_ads.type_ads').' '.'<a href="'.route('update_type_ads',['id'=>$type_ads->id]).'">'.$type_ads->name.'</a>'.' '.trans('valid.updated_successful')]);
			}
		}
	}

	public function getDeleteTypeAds($id){
		$type_ads = TypeAds::find($id);
		if(!$type_ads){
			abort(404);
		}
		$type_ads->delete();
		PriceAds::where('type_ads',$id)->delete();
		return redirect()->route('list_type_ads')->with(['status' => trans('Admin'.DS.'type_ads.type_ads').' '.$type_ads->name.' '.trans('valid.deleted_successful')]);
	}

	public function postDeletePrice(Request $request){
		$id = $request->id;
    $price = PriceAds::find($id);
    $price->delete();
    echo 'sussess';
	}
}
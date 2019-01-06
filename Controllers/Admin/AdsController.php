<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;

// use App\Models\Ads\Ads;
// use App\Models\Ads\MediaAds;
// use App\Models\Ads\PaymentAds;
// use App\Models\Ads\PriceAds;
// use App\Models\Ads\TypeAds;

use App\Models\Location\Ads;
use App\Models\Location\PublishAds;
use App\Models\Location\PaymentAds;
use App\Models\Location\PriceAds;
use App\Models\Location\TypeAds;

use App\Models\Location\Content;
use App\Models\Location\TransactionCoin;
use App\Models\Location\NotifiAdmin;
use App\Models\Location\Notifi;

class AdsController extends BaseController
{
	public function getListAds(Request $request)
	{
		$per_page = Session::has('pagination.' . \Route::currentRouteName()) ? session('pagination.' . \Route::currentRouteName()) : 10;
		$input = request()->all();

		$all_ads = Ads::select('ads.*')
											->with('_base_content')
											->with('_created_by_client')
											->orderBy('approved','ASC')
											->orderBy('declined','ASC')
                      ->orderBy('id');

		if (isset($input['keyword']) && $input['keyword']!='') {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}
		if($keyword != ''){
			$all_ads->leftJoin(\Config::get('database.connections.mysql.database').'.contents','contents.id', 'content_id')
								->where('contents.name','like','%'.$keyword.'%');
		}

		$list_ads = $all_ads->paginate($per_page);
		// dd($list_ads);
		return view('Admin.ads.list', ['list_ads' => $list_ads, 'keyword'=>$keyword]);
	}

	public function getAds($id){
		$ads = Ads::where('id',$id)
							->with('_base_content')
							->with('_created_by_client')
							->with('_type_ads')
							->first();
		if($ads){
			return view('Admin.ads.detail', ['ads' => $ads]);
		}
	}

	public function getApproveAdsOld($id){
		$ads = Ads::where('id',$id)
							->with('_base_content')
							->with('_created_by_client')
							->with('_type_ads')
							->with('_media_ads')
							->first();
		if(!$ads){
			abort(404);
		}else{
			$content = Content::find($ads->content_id);
			

			$payment = new PaymentAds();
			$payment->ads_id = $ads->id;
			$payment->content_id = $ads->content_id;
			$payment->type_apply = $ads->type_apply;
			$payment->created_by = $ads->created_by;

			$payment->price = $ads->price;
			$payment->total = $ads->total;

			$quantity = 0;
	    switch ($ads->type_apply) {
	      case 'date':
	        $date_from        = new Carbon($ads->date_from);
	        $date_to          = new Carbon($ads->date_to);
	        $quantity = $date_to->diffInDays($date_from) + 1;
	        break;
	      case 'click':
	        $quantity       = $ads->click;
	        break;
	      case 'view':
	        $quantity        = $ads->view;
	        break; 
	      default:
	        $date_from        = new Carbon($ads->date_from);
	        $date_to          = new Carbon($ads->date_to);
	        $quantity = $date_to->diffInDays($date_from) + 1;
	        break;
	    }
	    $quantity = (float) $quantity;
	    $payment->quantity = $quantity;
	    $payment->save();

	    $ads->approved = 1;
			$ads->approved_at = new Carbon();
			$ads->approved_by = \Auth::guard('web')->user()->id;
			$ads->active = 1;
			if($ads->type_apply == 'date'){
				$ads->date_from = Carbon::now()->hour(0)->minute(0)->second(0);
				$ads->date_to = Carbon::now()->addDays($quantity)->hour(23)->minute(59)->second(59);
			}
			$ads->save();

			//create Notifi User
			$notifi = new Notifi();
			$notifi->createNotifiUserByTemplate('Admin'.DS.'ads.approved_ad',$content->created_by,['content'=>$content->name]);
			return redirect(route('list_ads'))->with(['success'=>trans('Admin'.DS.'ads.approved_ad',['content'=>$content->name])]);
		}
	}

	public function getApproveAds($id){
		$ads = Ads::find($id);
		if(!$ads){
			abort(404);
		}else{
	    $ads->approved = 1;
			$ads->approved_at = new Carbon();
			$ads->approved_by = \Auth::guard('web')->user()->id;
			$ads->save();

			//create Notifi User
			$notifi = new Notifi();
			if($ads->choose_type == 'content'){
				$content = Content::find($ads->content_id);
				$notifi->createNotifiUserByTemplate('Admin'.DS.'ads.approved_ad',$content->created_by,['content'=>$content->name]);
				return redirect(route('list_ads'))->with(['success'=>trans('Admin'.DS.'ads.approved_ad',['content'=>$content->name])]);
			}else{
				$notifi->createNotifiUserByTemplate('Admin'.DS.'ads.approved_ad',$ads->created_by,['content'=>$ads->name]);
				return redirect(route('list_ads'))->with(['success'=>trans('Admin'.DS.'ads.approved_ad',['content'=>$ads->name])]);
			}
			
		}
	}

	public function postDeclineAdsOld(Request $request){
		// dd($request->all());
		$rules = [
			'id' => 'required',
			'declined_content'=>'required'
		];
		$messages = [
			'id.required' => trans('Admin'.DS.'ads.not_found_ad'),
			'declined_content.required' => trans('Admin'.DS.'ads.declined_content_required')
		];
		$validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator->getMessageBag()->first())->withInput();
		} else {
			$ads = Ads::where('id',$request->id)
								->with('_base_content')
								->with('_created_by_client')
								->with('_type_ads')
								->with('_media_ads')
								->first();
			if(!$ads){
				$errors = new MessageBag(['error' => trans('Admin'.DS.'ads.not_found_ad')]);
        return redirect()->back()->withErrors($errors)->withInput();
			}
			$content = Content::find($ads->content_id);

			$ads->declined = 1;
			$ads->declined_content = $request->declined_content?$request->declined_content:'';
			$ads->declined_at = new Carbon();
			$ads->declined_by = \Auth::guard('web')->user()->id;
			$ads->active = 0;
			$ads->save();

			//create Notifi User
			$notifi = new Notifi();
			$notifi->createNotifiUserByTemplate('Admin'.DS.'manage_ad.declined_ad_because',$content->created_by,[
				'content'=>$content->name,
				'because'=>$request->declined_content?$request->declined_content:''
			]);

			return redirect(route('list_ads'))->with(['success'=>trans('Admin'.DS.'ads.declined_ad',['content'=>$content->name])]);
		}
	}
	public function postDeclineAds(Request $request){
		// dd($request->all());
		$rules = [
			'id' => 'required',
			'declined_content'=>'required'
		];
		$messages = [
			'id.required' => trans('Admin'.DS.'ads.not_found_ad'),
			'declined_content.required' => trans('Admin'.DS.'ads.declined_content_required')
		];
		$validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator->getMessageBag()->first())->withInput();
		} else {
			$ads = Ads::find($request->id);
			if(!$ads){
				abort(404);
			}else{
		    $ads->declined = 1;
				$ads->declined_content = $request->declined_content?$request->declined_content:'';
				$ads->declined_at = new Carbon();
				$ads->declined_by = \Auth::guard('web')->user()->id;
				$ads->save();

				//create Notifi User
				$notifi = new Notifi();
				if($ads->choose_type == 'content'){
					$content = Content::find($ads->content_id);
					$notifi->createNotifiUserByTemplate('Admin'.DS.'manage_ad.declined_ad_because',$content->created_by,[
						'content'=>$content->name,
						'because'=>$request->declined_content?$request->declined_content:''
					]);
					return redirect(route('list_ads'))->with(['success'=>trans('Admin'.DS.'ads.declined_ad',['content'=>$content->name])]);
				}else{
					$notifi->createNotifiUserByTemplate('Admin'.DS.'manage_ad.declined_ad_because',$ads->created_by,[
						'content'=>$ads->name,
						'because'=>$request->declined_content?$request->declined_content:''
					]);
					return redirect(route('list_ads'))->with(['success'=>trans('Admin'.DS.'ads.declined_ad',['content'=>$ads->name])]);
				}
				
			}
		}
	}

	public function getDeleteAds($id){
		$ads = Ads::where('id',$id)
							// ->where('approved',0)
							// ->where('declined',0)
							->first();
		if(!$ads){
			$errors = new MessageBag(['error' => trans('Admin'.DS.'ads.not_found_ad')]);
      return redirect()->back()->withErrors($errors)->withInput();
		}
		$content = Content::find($ads->content_id);
		$ads->delete();
		return redirect(route('list_ads'))->with(['success'=>trans('Admin'.DS.'ads.deleted_ad',['content'=>$content->name])]);
	}
}
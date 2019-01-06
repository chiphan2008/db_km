<?php

namespace App\Http\Controllers\Location;
use App\Models\Location\Client;
use App\Models\Location\Content;
use App\Models\Location\NotifiContent;
use App\Models\Location\TransactionCoin;
use App\Models\Location\Collection;
use App\Models\Location\CollectionContent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use App\Models\Location\Discount;
use App\Models\Location\Product;
use App\Models\Location\Country;
use App\Models\Location\DiscountProduct;

use App\Models\Location\EmailTemplate;
use App\Models\Location\Notifi;
use Illuminate\Support\Facades\Mail;



use App\Models\Location\TypeAds;
use App\Models\Location\Ads;
use App\Models\Location\PriceAds;
use App\Models\Location\PublishAds;
use App\Models\Location\PaymentAds;

use App\Models\Location\ImageSpace;
use App\Models\Location\ImageMenu;
use App\Models\Location\LinkContent;


class UserController extends BaseController
{
	public function getUser($id_user){
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			abort(404);
		}else{
			if(!check_exist($user->avatar)){
				$user->avatar = '/img_user/default.png';
			}
			if(Auth::guard('web_client')->user()){
				if(Auth::guard('web_client')->user()->id == $user->id){
					$this->view->content = view('Location.user.index',['user'=>$user,'module'=>'view']);
					return $this->setContent();
				}else{
					return redirect('/user/'.$user->id.'/location');
				}
				
			}else{
				return redirect('/user/'.$user->id.'/location');
			}
		}
	}

	public function postUser(Request $request,$id_user){
		$arrReturn = ['error'=>1,'message'=>''];
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			$arrReturn['message']=trans('Location'.DS.'user.user_not_found');
		}else{
			$user->full_name = $request->full_name?$request->full_name:'';
		  $user->birthday = $request->birthday_birthDay?$request->birthday_birthDay:null;
		  $user->phone = $request->phone_user?$request->phone_user:'';
		  $user->address = $request->address?$request->address:'';
		  $user->description = $request->description?$request->description:'';

		  $user->updated_by = $user->id;
			$user->type_user_update = 0;
			$user->updated_at = \Carbon::now();
			
		  if($user->save()){
		  	commandSyncClient2Node($user->id);
		  	$arrReturn['error'] = 0;
		  	$arrReturn['message']=trans('Location'.DS.'user.update_profile_success');
		  }else{
		  	$arrReturn['message']=trans('Location'.DS.'user.update_profile_not_success');
		  }
		}
		return response()->json($arrReturn);
	}

	public function getUserCheckin($id_user){
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			abort(404);
		}else{
			$query = Content::select('contents.*')
												->with('_city')
												->with('_district')
												->where('moderation','=','publish')
												->where('active','=',1)
												->leftJoin('checkin','id_content','=','contents.id')
												->where('id_user','=',$user->id)
                                                ->orderBy('contents.id','desc');
			$total = $query->count();
			$contents = $query->paginate(15);

			if(!check_exist($user->avatar)){
				$user->avatar = '/img_user/default.png';
			}
			$this->view->content = view('Location.user.index',[
				'user'=>$user,
				'total'=>$total,
				'contents'=>$contents,
				'module'=>'check-in'
				]);
			return $this->setContent();
		}
	}

	public function getUserLike($id_user){
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			abort(404);
		}else{
			$query = Content::select('contents.*')
												->with('_city')
												->with('_district')
												->where('moderation','=','publish')
												->where('active','=',1)
												->leftJoin('like_content','id_content','=','contents.id')
												->where('id_user','=',$user->id);
			$total = $query->count();
			$contents = $query->paginate(15);

			if(!check_exist($user->avatar)){
				$user->avatar = '/img_user/default.png';
			}
			$this->view->content = view('Location.user.index',['user'=>$user,'total'=>$total,'contents'=>$contents,'module'=>'like']);
			return $this->setContent();
		}
	}

	public function getUserLikeLocation($id_user){
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		// dd($user->avatar, check_exist($user->avatar));
		if(!$user){
			abort(404);
		}else{
			$query = Content::select('contents.*')
												->with('_city')
												->with('_district')
												->where('moderation','=','publish')
												->where('active','=',1)
												->leftJoin('save_like_content','id_content','=','contents.id')
												->where('id_user','=',$user->id)
                                                ->orderBy('contents.id','desc');
			$total = $query->count();
			$contents = $query->paginate(15);

			if(!check_exist($user->avatar)){
				$user->avatar = '/img_user/default.png';
			}
			$this->view->content = view('Location.user.index',['user'=>$user,'total'=>$total,'contents'=>$contents,'module'=>'like-location']);
			return $this->setContent();
		}
	}

	public function getUserFriend($id_user){
		if(Auth::guard('web_client')->guest()){
			abort(404);
		}
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			abort(404);
		}else{
			if(!check_exist($user->avatar)){
				$user->avatar = '/img_user/default.png';
			}
			if(Auth::guard('web_client')->user()->id != $user->id){
				abort(404);
			}
			$this->view->content = view('Location.user.friend',['user'=>$user]);
			return $this->setContent();
		}
	}

	public function getUserChangePassword($id_user){
		if(Auth::guard('web_client')->guest()){
			abort(404);
		}
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			abort(404);
		}else{
			if(!check_exist($user->avatar)){
				$user->avatar = '/img_user/default.png';
			}
			if(Auth::guard('web_client')->user()->id != $user->id){
				abort(404);
			}
			$this->view->content = view('Location.user.index',['user'=>$user,'module'=>'change-password']);
			return $this->setContent();
		}
	}

	

	public function postUserChangePassword(Request $request, $id_user){
		$arrReturn = ['error'=>1,'message'=>''];
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			$arrReturn['message']=trans('Location'.DS.'user.user_not_found');
		}else{
			if($user->password === null || Hash::check($request->old_password, $user->password)){
				$user->password = Hash::make($request->new_password);
				$user->updated_by = $user->id;
				$user->type_user_update = 0;
				$user->updated_at = \Carbon::now();

				if($user->save()){
			  	$arrReturn['error'] = 0;
			  	$arrReturn['message']=trans('Location'.DS.'user.update_password_success');
			  }else{
			  	$arrReturn['message']=trans('Location'.DS.'user.update_password_not_success');
			  }
			}else{
				$arrReturn['message']=trans('Location'.DS.'user.wrong_old_password');
			}
		}
		return response()->json($arrReturn);
	}

	public function postUserUpdateAvatar(Request $request,$id_user){
		$arrReturn = ['error'=>1,'message'=>''];
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			$arrReturn['message']=trans('Location'.DS.'user.user_not_found');
		}else{
			$path = public_path().'/upload/img_user';
			// chmod($path, 0755);
			if(!\File::exists($path)) {
				\File::makeDirectory($path, $mode = 0777, true, true);
			}
			$name = time().".png";
			$img = preg_replace('#^data:image/\w+;base64,#i', '', $request->img);
			if(\File::put($path.'/'.$name, base64_decode($img))){
				$user->avatar ='/'.'upload/img_user'.'/'.$name;
				$user->updated_by = $user->id;
				$user->type_user_update = 0;
				$user->updated_at = \Carbon::now();
				if($user->save()){
					commandSyncClient2Node($user->id);
			  	$arrReturn['error'] = 0;
			  	$arrReturn['message']=trans('Location'.DS.'user.update_avatar_success');
			  }else{
			  	$arrReturn['message']=trans('Location'.DS.'user.update_avatar_not_success');
			  }
			}else{
				$arrReturn['message']=trans('Location'.DS.'user.update_avatar_not_success');
			}
		}
		return response()->json($arrReturn);
	}

	public function getUserWallet($id_user){
		if(Auth::guard('web_client')->guest()){
			abort(404);
		}
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			abort(404);
		}else{
			if(!check_exist($user->avatar)){
				$user->avatar = '/img_user/default.png';
			}
			if(Auth::guard('web_client')->user()->id != $user->id){
				abort(404);
			}
			$transactions = TransactionCoin::where('rollback','=',0)
																			->Where(function($query) use ($user){
																				$query->orWhere('from_client', '=', $user->id)
                      												->orWhere('to_client', '=', $user->id);
																			})
																			->orderBy('created_at','desc')
																			->paginate(20);
			$this->view->content = view('Location.user.index',['user'=>$user,'transactions'=>$transactions,'module'=>'wallet']);
			return $this->setContent();
		}
	}

	public function getUserManagementLocation($id_user)
  {
  	if(Auth::guard('web_client')->guest()){
			abort(404);
		}
    $user = Client::where('id','=',$id_user)->where('active','=',1)->first();
    if(!$user){
      abort(404);
    }
    $contents = Content::select('contents.*')->with('_country')->with('_city')
      ->with('_district')->with('_category_type')->with('_created_by_client')
      ->where('created_by','=',$id_user)->where('type_user','=',0)->whereIn('moderation', ['publish', 'request_publish', 'un_publish'])
      ->orderBy('contents.id','desc');
		$total = $contents->count();
		$contents = $contents->paginate(15);
    if($total > 0){
      if(!check_exist($user->avatar)){
        $user->avatar = '/img_user/default.png';
      }
      $this->view->content = view('Location.user.index',['user'=>$user,'total'=>$total,'contents'=>$contents,'module'=>'management-location']);
      return $this->setContent();
    }else{
      $this->view->content = view('Location.user.index',['user'=>$user,'total'=>$total,'module'=>'management-location']);
      return $this->setContent();
    }
  }

  public function popupNotifyContent(Request $request)
  {
    $data = NotifiContent::where([['id_content','=',$request->id_content],['active','=',1]])->first();
    if($data)
    {
      return Response::json(array(
        'mess' => true,
        'data' => $data,
      ));
    }
    else{
      return Response::json(array(
        'mess' => false,
        'data' => '',
      ));
    }
  }

  public function postPopupNotifyContent(Request $request)
  {
    $check_notify = NotifiContent::where('id_content', '=', $request->id_content)->first();
    if ($check_notify) {
      $check_notify->title = $request->title;
      $check_notify->description = $request->except;
      $check_notify->active = isset($request->active) ? 1 :0;
      $check_notify->start = new Carbon($request->time_start.' '.date("h:i:s",time()));
      $check_notify->end = new Carbon($request->time_end.' '.date("h:i:s",time()));
      $check_notify->save();
    } else {
      NotifiContent::create([
        'id_content' => $request->id_content,
        'title' => $request->title,
        'description' => $request->except,
        'active' => isset($request->active) ? 1 :0,
        'start' => new Carbon($request->time_start.' '.date("h:i:s",time())),
        'end' => new Carbon($request->time_end.' '.date("h:i:s",time())),
      ]);
    }

    return redirect('user/'.Auth::guard('web_client')->user()->id.'/management-location');
  }

  public function getUserCollection($id_user){
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		// dd($user->avatar, check_exist($user->avatar));
		if(!$user){
			abort(404);
		}else{
			$query = Collection::select('collection.*')
												->with('_contents')
												->where('created_by','=',$user->id)
                                                ->orderBy('collection.id', 'DESC');
			$collections = $query->paginate(4);
			$total = $query->count();

			if(!check_exist($user->avatar)){
				$user->avatar = '/img_user/default.png';
			}
			$this->view->content = view('Location.user.index',['user'=>$user,'total'=>$total,'collections'=>$collections,'module'=>'collection']);
			return $this->setContent();
		}
	}

	public function getCreateDiscount($id_user){
		if(Auth::guard('web_client')->guest()){
			abort(404);
		}
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			abort(404);
		}else{

			$contents = Content::where('created_by','=',$id_user)
												 ->where('type_user','=',0)
												 ->whereIn('moderation',['publish'])
												 ->get();
			if(!check_exist($user->avatar)){
				$user->avatar = '/img_user/default.png';
			}
			if(Auth::guard('web_client')->user()->id != $user->id){
				abort(404);
			}
			$this->view->content = view('Location.user.index',[
				'user'=>$user,
				'module'=>'create-discount',
				'contents'=>$contents,
			]);
			return $this->setContent();
		}
	}

	public function getListDiscount($id_user){
		if(Auth::guard('web_client')->guest()){
			abort(404);
		}
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			abort(404);
		}else{
			$discounts = Discount::where('created_by','=',$id_user)
														->with('_base_content')
														->orderBy('created_at','DESC')
														->get();

			if(!check_exist($user->avatar)){
				$user->avatar = '/img_user/default.png';
			}
			if(Auth::guard('web_client')->user()->id != $user->id){
				abort(404);
			}
			$this->view->content = view('Location.user.index',[
				'user'=>$user,
				'module'=>'list-discount',
				'discounts'=>$discounts,
			]);
			return $this->setContent();
		}
	}

	public function getUpdateDiscount($id_user,$id_discount){
		if(Auth::guard('web_client')->guest()){
			abort(404);
		}
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			abort(404);
		}else{
			$discount = Discount::where('id',$id_discount)
													->with('_products')
													->where('created_by','=',$id_user)
													->first();
			if(!$discount){
				abort(404);
			}

			$contents = Content::where('created_by','=',$id_user)
												 ->where('type_user','=',0)
												 ->whereIn('moderation',['publish'])
												 ->get();
			$products = Product::where('content_id',$discount->id_content)
												 ->get();

			$arrCheck = [];
			foreach ($discount->_products as $key => $product) {
				$arrCheck[] = $product->id;
			}
			$arrProduct = [];
			foreach ($products as $key => $product) {
				$arrProduct[$key] = in_array($product->id,$arrCheck);
			}
			if(!check_exist($user->avatar)){
				$user->avatar = '/img_user/default.png';
			}
			if(Auth::guard('web_client')->user()->id != $user->id){
				abort(404);
			}
			$this->view->content = view('Location.user.index',[
				'user'=>$user,
				'module'=>'update-discount',
				'discount'=>$discount,
				'contents'=>$contents,
				'products'=>$products,
				'arrProduct'=>$arrProduct,
			]);
			return $this->setContent();
		}
	}

	public function getDeleteDiscount($id_user,$id_discount){
		if(Auth::guard('web_client')->guest()){
			abort(404);
		}
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			abort(404);
		}else{
			$discount = Discount::where('id',$id_discount)
													->where('created_by','=',$id_user)
													->first();
			if(!$discount){
				abort(404);
			}
			$link_image = str_replace('/',DS,$discount->image);
      if(\File::exists(public_path($link_image)))
        unlink(public_path($link_image));
      if(\File::exists(public_path(str_replace('discount','discount_thumbnail',$link_image))))
        unlink(public_path(str_replace('discount','discount_thumbnail',$link_image)));
			$discount->delete();
			DiscountProduct::where('discount_id',$id_discount)->delete();
			return redirect()->route('list-discount',['id_user'=>$id_user]);
		}
	}

	public function getRevenueInvite($id_user){
		if(Auth::guard('web_client')->guest()){
			abort(404);
		}
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			abort(404);
		}else{
			if(!check_exist($user->avatar)){
				$user->avatar = '/img_user/default.png';
			}
			if($user->register_invite){
				$query = Content::select('contents.*')
												->with('_city')
												->with('_district')
												->where('moderation','=','publish')
												->where('active','=',1)
												->where('code_invite','=',$user->code_invite);
				$total_location = $query->count();
				$contents = $query->paginate(15);

				$arr_content = Content::where('moderation','=','publish')
												->where('active','=',1)
												->where('code_invite','=',$user->code_invite)
												->pluck('id');

				$arr_ads_payment = PaymentAds::selectRaw('sum(total) as total, contents.name,payment_ads.created_at')
												->whereMonth('payment_ads.created_at', '=', date('m'))
												->leftJoin(\Config::get('database.connections.mysql.database').'.contents','contents.id','content_id')
												->whereIn('content_id',$arr_content)
												->groupBy('content_id')
												->get();
				$transactions = [];
				$total = 0;
				$total_revenue = 0;
				foreach ($arr_ads_payment as $key => $value) {
					$tmp['name'] = $value->name;
					$tmp['type'] = trans('global.ads');
					$tmp['total'] = $value->total;
					$tmp['created_at'] = $value->created_at;
					$tmp['revenue'] = $value->total * $user->rate_revenue/100;
					$total += $tmp['total'];
					$total_revenue += $tmp['revenue'];
					$transactions[] = $tmp;
				}
				$this->view->content = view('Location.user.index',[
					'user'=>$user,
					'module'=>'revenue-invite',
					'contents'=>$contents,
					'total_location'=>$total_location,
					'total'=>$total,
					'total_revenue'=>$total_revenue,
					'transactions'=>$transactions
				]);
				return $this->setContent();
			}else{
				return redirect()->route('register_invite');
			}
		}
	}

	public function getCreateAds($id_user){
		if(Auth::guard('web_client')->guest()){
			abort(404);
		}
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			abort(404);
		}else{
			$contents = Content::where('created_by','=',$id_user)
												 ->where('type_user','=',0)
												 ->whereIn('moderation',['publish'])
												 ->get();
			if(!check_exist($user->avatar)){
				$user->avatar = '/img_user/default.png';
			}
			if(Auth::guard('web_client')->user()->id != $user->id){
				abort(404);
			}
			$ads_type = TypeAds::where('active',1)->get();
			$this->view->content = view('Location.user.index',[
				'user'=>$user,
				'module'=>'create-ads',
				'ads_type'=>$ads_type,
				'contents'=>$contents
			]);
			return $this->setContent();
		}
	}

	public function getReCreateAds($id_user,$id_ads){
		if(Auth::guard('web_client')->guest()){
			abort(404);
		}
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			abort(404);
		}else{
			$contents = Content::where('created_by','=',$id_user)
												 ->where('type_user','=',0)
												 ->whereIn('moderation',['publish'])
												 ->get();
			if(!check_exist($user->avatar)){
				$user->avatar = '/img_user/default.png';
			}
			if(Auth::guard('web_client')->user()->id != $user->id){
				abort(404);
			}
			$ads = null;
			if(isset($id_ads)){
				$ads = Ads::where('id',$id_ads)
									->with('_media_ads')
									->with('_type_ads')
									->first();
			}
			$ads_type = TypeAds::get();
			if($ads){
				$this->view->content = view('Location.user.index',[
				'user'=>$user,
				'module'=>'re-create-ads',
				'ads_type'=>$ads_type,
				'contents'=>$contents,
				'ads'			=>$ads
			]);
			}else{
				$this->view->content = view('Location.user.index',[
				'user'=>$user,
				'module'=>'create-ads',
				'ads_type'=>$ads_type,
				'contents'=>$contents
			]);
			}
			
			return $this->setContent();
		}
	}

	public function getPublishAds($id_user,$id_ads){
		if(Auth::guard('web_client')->guest()){
			abort(404);
		}
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			abort(404);
		}else{
			$ads = Ads::where('id',$id_ads)
								->where('approved',1)
								->where('declined',0)
								->with('_type_ads')
								->first();

			$countries = Country::pluck('name', 'id');

			if(!$user){
				abort(404);
			}
			if(!check_exist($user->avatar)){
				$user->avatar = '/img_user/default.png';
			}
			if(Auth::guard('web_client')->user()->id != $user->id){
				abort(404);
			}
			
			$this->view->content = view('Location.user.index',[
				'user'=>$user,
				'module'=>'publish-ads',
				'ads'			=>$ads,
				'countries' => $countries
			]);
			return $this->setContent();
		}
	}

	

	public function getUpdateAds($id_user,$id_ads){
		if(Auth::guard('web_client')->guest()){
			abort(404);
		}
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			abort(404);
		}else{
			$ads = Ads::where('id',$id_ads)
									->with('_media_ads')
									->with('_type_ads')
									->where('created_by',$id_user)
									->where('type_user',0)
									->where('active',0)
									->first();
			if(!$ads){
				abort(404);
			}
			$this->view->content = view('Location.user.index',[
				'user'=>$user,
				'module'=>'update-ads',
				'ads'			=>$ads
			]);
			return $this->setContent();
		}
	}

	public function getListAds($id_user){
		if(Auth::guard('web_client')->guest()){
			abort(404);
		}
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			abort(404);
		}else{
			$ads = Ads::where('created_by',$id_user)
										 ->with('_base_content')
										 ->with('_type_ads')
										 ->orderBy('created_at','DESC')
										 ->get();
			// dd($ads->toArray());
			if(!check_exist($user->avatar)){
				$user->avatar = '/img_user/default.png';
			}
			if(Auth::guard('web_client')->user()->id != $user->id){
				abort(404);
			}
			$this->view->content = view('Location.user.index',[
				'user'=>$user,
				'module'=>'list-ads',
				'ads'=>$ads,
			]);
			return $this->setContent();
		}
	}


	public function getChangeOwner($id_user){
		if(Auth::guard('web_client')->guest()){
			abort(404);
		}
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			abort(404);
		}else{
			if(Auth::guard('web_client')->user()->id != $user->id){
				abort(404);
			}
			$this->view->content = view('Location.user.index',[
				'user'=>$user,
				'module'=>'change-owner'
			]);
			return $this->setContent();
		}
	}

	public function postChangeOwner(Request $request,$id_user){
		$arrReturn = ['error'=>0,'message'=>''];

    $notifi = new Notifi();
    $notifi->content = '';
    $notifi->save();
    $id_notifi = $notifi->id;

    $arrData = [
    	'content' => $request->content,
    	'to_user' => $request->user,
    	'from_user' => $id_user,
    	'id_notifi' => $id_notifi
    ];

    $to_user = Client::find($arrData['to_user']);
    $contents = Content::whereIn('id',$arrData['content'])
													->where('created_by',$arrData['from_user'])
													->where('type_user',0)
												  ->get();
    $status = '';
        $link_content = '';
    foreach ($contents as $key => $content) {
        $link_content .='<br/><a href="'.LOCATION_URL.'/'.$content->alias.'">'.$content->name.'</a>';
        if($key==0)
            $status.=$content->name;
        else
            $status.=', '.$content->name.' ';
    }
    $codeApply = super_encode(json_encode($arrData));
    
    $html = $status.' '.trans('valid.wait_change_owner_to',['link_apply'=>'/change-owner?h='.$codeApply,'link_decline'=>'/change-owner?d='.$codeApply]);
    $content = 'valid.wait_change_owner_to';
    $data = ['code'=>$codeApply,'link_apply'=>'/change-owner?h='.$codeApply,'link_decline'=>'/change-owner?d='.$codeApply,"status" => $status];

    $old_notifi = Notifi::find($id_notifi);
		$old_notifi->type = 'change_owner';
    $old_notifi->save();
    
    $new_notifi = new Notifi();
    $new_notifi->updateNotifiUserByTemplate($id_notifi,$content,$to_user->id,$data);
    

    $arrReturn['message'] = trans('valid.change_owner_to',["status"=>$status, 'to_user'=>$to_user->full_name]);

        $from_user = Client::find($arrData['from_user']);
        $mail_template_to = EmailTemplate::where('machine_name', 'change_owner_nofity')->first();
        $link_accept = LOCATION_URL.'/change-owner?h='.$codeApply.'&email='.LOCATION_URL;
        $link_reject = LOCATION_URL.'/change-owner?d='.$codeApply.'&email='.LOCATION_URL;
        if($mail_template_to)
        {
            $data = [
                'to_full_name' => $to_user->full_name,
                'content_name' => $status,
                'from_full_name' => $from_user->full_name,
                'link_manage' => LOCATION_URL.'/user/'.$to_user->id.'/management-location',
                'link_content' => $link_content,
                'link_accept'=> $link_accept,
                'link_reject'=> $link_reject
            ];
            Mail::send([], [], function($message) use ($mail_template_to, $data,$to_user)
            {
                $message->to($to_user->email, $to_user->full_name)
                    ->subject($mail_template_to['subject'])
                    ->from('kingmapteam@gmail.com', 'KingMap Team')
                    ->setBody($mail_template_to->parse($data),'text/html');
            });
        }

    return response()->json($arrReturn,200,[],JSON_UNESCAPED_UNICODE);
	}

	public function postApplyChangeOwner(Request $request){
		if($request->h){
			$arrApply = json_decode(super_decode($request->h),true);
			Notifi::where('id',$arrApply['id_notifi'])->delete();
			$contents = Content::whereIn('id',$arrApply['content'])
													->where('created_by',$arrApply['from_user'])
													->where('type_user',0)
												  ->get();
	    $status = trans('global.locations').': ';
	    $to_user = Client::find($arrApply['to_user']);
	    $from_user = Client::find($arrApply['from_user']);
	    $link_content = '';
	    $noti_miss_content = array();
	    foreach ($contents as $key => $content) {
	    		$link_content .='<br/><a href="'.LOCATION_URL.'/'.$content->alias.'">'.$content->name.'</a>';
	        $content->created_by = $arrApply['to_user'];
	        $content->type_user = 0;
	        $content->save();
	        if($key==0)
	            $status.=$content->name;
	        else
	            $status.=', '.$content->name.' ';
	        if(!check_update_content($content->id)){
	        		$notifi = new Notifi();
              $link_content =LOCATION_URL.'/edit/location/'.$content->id;
              $text_content = trans('Admin'.DS.'content.noti_update_content', [ 'content' => $content->name ]);
              $notifi->createNotifiUserByTemplate('Admin'.DS.'content.noti_update_content',$content->created_by,['content' => $content->name, 'content_id' => $content->id],$link_content);
          }
	    }
	    $arrReturn['message'] = trans('valid.change_owner_to',['status'=>$status, 'to_user'=>$to_user->full_name]);

	    $mail_template_to = EmailTemplate::where('machine_name', 'change_owner')->first();
	    if($mail_template_to)
	    {
	      $data = [
	        'to_full_name' => $to_user->full_name,
	        'content_name' => $status,
	        'from_full_name' => $from_user->full_name,
	        'link_manage' => LOCATION_URL.'/user/'.$to_user->id.'/management-location',
	        'link_content' => $link_content
	      ];
	      Mail::send([], [], function($message) use ($mail_template_to, $data,$to_user)
	      {
	        $message->to($to_user->email, $to_user->full_name)
	          ->subject($mail_template_to['subject'])
	          ->from('kingmapteam@gmail.com', 'KingMap Team')
	          ->setBody($mail_template_to->parse($data),'text/html');
	      });
	    }

	    $mail_template_from = EmailTemplate::where('machine_name', 'change_owner_from')->first();
	    if($mail_template_from)
	    {
	    	$data = [
	        'to_full_name' => $to_user->full_name,
	        'content_name' => $status,
	        'from_full_name' => $from_user->full_name,
	        'link_manage' => LOCATION_URL.'/user/'.$from_user->id.'/management-location',
	        'link_content' => $link_content
	      ];
	      Mail::send([], [], function($message) use ($mail_template_from, $data,$from_user)
	      {
	        $message->to($from_user->email, $from_user->full_name)
	          ->subject($mail_template_from['subject'])
	          ->from('kingmapteam@gmail.com', 'KingMap Team')
	          ->setBody($mail_template_from->parse($data),'text/html');
	      });
	    }
	    $notifi = new Notifi();
	    $notifi->createNotifiUserByTemplate('valid.change_owner_to',$to_user->id,['status'=>$status, 'to_user'=>$to_user->full_name]);
	    $notifi->createNotifiUserByTemplate('valid.change_owner_to',$from_user->id,['status'=>$status, 'to_user'=>$to_user->full_name]);
	    // $notifi->createNotifiUser(trans('valid.change_owner_to',['status'=>$status, 'to_user'=>$to_user->full_name]),$to_user->id);
		}else{
			if($request->d){
				$arrApply = json_decode(super_decode($request->d),true);
				Notifi::where('id',$arrApply['id_notifi'])->delete();
        $contents = Content::whereIn('id',$arrApply['content'])
            ->where('created_by',$arrApply['from_user'])
            ->where('type_user',0)
            ->get();
        $status = trans('global.locations').': ';
        $to_user = Client::find($arrApply['to_user']);
        $from_user = Client::find($arrApply['from_user']);
        $link_content = '';
        foreach ($contents as $key => $content) {
            $link_content .='<br/><a href="'.LOCATION_URL.'/'.$content->alias.'">'.$content->name.'</a>';
            if($key==0)
                $status.=$content->name;
            else
                $status.=', '.$content->name.' ';
        }
        $mail_template_from = EmailTemplate::where('machine_name', 'reject_owner_from')->first();
        if($mail_template_from)
        {
            $data = [
                'to_full_name' => $to_user->full_name,
                'content_name' => $status,
                'from_full_name' => $from_user->full_name,
                'link_manage' => LOCATION_URL.'/user/'.$from_user->id.'/management-location',
                'link_content' => $link_content
            ];
            Mail::send([], [], function($message) use ($mail_template_from, $data,$from_user)
            {
                $message->to($from_user->email, $from_user->full_name)
                    ->subject($mail_template_from['subject'])
                    ->from('kingmapteam@gmail.com', 'KingMap Team')
                    ->setBody($mail_template_from->parse($data),'text/html');
            });
        }
        $notifi_reject = new Notifi();
        $notifi_reject->createNotifiUserByTemplate('valid.change_owner_reject',$from_user->id,['status'=>$status, 'to_user'=>$to_user->full_name]);
        // $notifi_reject->createNotifiUserByTemplate('valid.change_owner_reject',$to_user->id,['status'=>$status, 'to_user'=>$to_user->full_name]);
			}	
		}
	}

    public function checkContent($id){
        $content = Content::with('_products')->with('_discount')->find($id);
        $imagespace = ImageSpace::where('id_content', '=', $id)->count();
        $imagemenu = ImageMenu::where('id_content', '=', $id)->count();
        $linkcontent = LinkContent::where('id_content', '=', $id)->count();
        if( $content->description == '' || $content->phone == '' || $content->email == '' || count($content->_products) == 0 || count($content->_discount) == 0 || $imagespace == 0 || $imagemenu == 0 || $linkcontent == 0){
            return false;
        }
        return true;
    }

	public function getSearchContent(Request $request){
		$arr_return = [];
		$input = request()->all();
		if(isset($input['query']) && $input['query']!=''){
      $keyword = $input['query'];

			$contents = Content::select('contents.*')
											 ->where('name','like','%'.$keyword.'%')
											 ->with('_country')
											 ->with('_city')
											 ->with('_district')
											 ->where('moderation','publish');
			if(isset($input['id_user']) && $input['id_user']!=0){
				$contents = $contents->where('created_by',$input['id_user'])
														 ->where('type_user',0);
			}
			$contents = $contents->limit(15)
											 		 ->get();
			foreach ($contents as $key => $content) {
				$arr_tmp = [];
				$arr_tmp['id'] = $content->id;
				$arr_tmp['text'] = $content->name;
				$arr_tmp['address'] = $content->address.', '.$content->_district->name.', '.$content->_city->name.', '.$content->_country->name;
				if(check_exist(asset($content->avatar))){
					$arr_tmp['avatar'] = url(str_replace('img_content','img_content_thumbnail',$content->avatar));
				}else{
					$arr_tmp['avatar'] = url('/img_user/default.png');
				}
				$arr_return[] = $arr_tmp;
			}
		}
		return response()->json(['results'=>$arr_return]);
	}

	public function getSearchUser(){
		$arr_return = [];
		$input = request()->all();
		if(isset($input['query']) && $input['query']!=''){
      $keyword = $input['query'];

			$clients = Client::select('clients.*');
			if(isset($input['id_user']) && $input['id_user']!=0){
				$clients = $clients->where('id','!=',$input['id_user']);
			}
			$clients = $clients->where('active',1)
												 ->where(function($query) use($keyword){
												 	return $query->where('email','like','%'.$keyword.'%')
																			 ->orwhere('full_name','like','%'.$keyword.'%')
																		 	 ->orWhere('phone','like','%'.$keyword.'%');
												 })
												 ->limit(15)
											 	 ->get();
			foreach ($clients as $key => $client) {
				$arr_tmp = [];
				$arr_tmp['id'] = $client->id;
				$arr_tmp['text'] = $client->full_name.' - '.$client->email;
				if(check_exist(asset($client->avatar))){
					$arr_tmp['avatar'] = url($client->avatar);
				}else{
					$arr_tmp['avatar'] = url('/img_user/default.png');
				}
				
				$arr_return[] = $arr_tmp;
			}
		}
		return response()->json(['results'=>$arr_return]);
	}
}
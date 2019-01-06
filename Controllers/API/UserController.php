<?php
namespace App\Http\Controllers\API;

use App\Models\Location\Client;
use App\Models\Location\Category;
use App\Models\Location\CategoryContent;
use App\Models\Location\CategoryItem;
use App\Models\Location\CategoryService;
use App\Models\Location\City;
use App\Models\Location\Content;
use App\Models\Location\Country;
use App\Models\Location\District;
use App\Models\Location\GroupContent;
use App\Models\Location\ImageMenu;
use App\Models\Location\ImageSpace;
use App\Models\Location\LikeContent;
use App\Models\Location\SeoContent;
use App\Models\Location\ServiceContent;
use App\Models\Location\VoteContent;
use App\Models\Location\Checkin;
use App\Models\Location\SaveLikeContent;
use App\Models\Location\CollectionContent;
use App\Models\Location\Collection;
use App\Models\Location\TransactionCoin;
use App\Models\Location\EmailTemplate;
use App\Models\Location\Notifi;
use App\Models\Location\NotifiUser;




use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;

class UserController extends BaseController {
	public function login(Request $request){
		try{
			$rules = [
				'username' => 'required',
				'password' => 'required'
			];
			$messages = [
				'username.required' => 'Username is required',
				'password.required' => 'Password is required',
			];

			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$username = $request->username;
				$password = $request->password;
				if (Auth::guard('web_client')->attempt(['email' => $username, 'password' => $password])) {
				  if (Auth::guard('web_client')->attempt(['email' => $username, 'password' => $password, 'active' => 1])) {
				  	$data = [];
				  	$client = Client::where('id',Auth::guard('web_client')->user()->id)->with('_roles')->with('_ctv')->with('_area')->first();
				  	if($client){
				  		if(!empty($client->_ctv)){
				  			$client->_daily = $client->_ctv->_daily->_client;
				  			unset($client->_ctv);
				  		}else{
				  			$client->_daily = null;
				  			unset($client->_ctv);
				  		}
				  		$data_array = $client->toArray();
				  		$data_array['count_area'] = count($client->_area);
				  		$data_array['api_roles'] = null;
				  		foreach ($client->_roles as $key => $role) {
				  			$data_array['api_roles'][$role->machine_name]['name'] = $role->name;
				  			$data_array['api_roles'][$role->machine_name]['active'] = $role->active;
				  		}
				  		$data = [$data_array];
				  	}else{
				  		$data = [Auth::guard('web_client')->user()->toArray()];
				  	}
				  	
				  	return $this->response($data,200);
				  }else{
				  	$e = new \Exception('User not activated',400);
						return $this->error($e);
				  }
				}else{
					$e = new \Exception('Wrong username or password',400);
					return $this->error($e);
				}
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function logout(Request $request){
		try{
			Auth::guard('web_client')->logout();

			session()->flush();

			session()->regenerate();

			$data = [];
			return $this->response($data,200);

		}catch(Exception $e){
			return $this->error($e);
		}

	}
	
	public function loginFB(Request $request){
		try{
			$rules = [
				'id_facebook' => 'required',
				'email' => 'required'
			];
			$messages = [
				'id_facebook.required' => trans('global.id_facebook_required'),
				'email.required' => trans('global.email_required'),
			];

			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$id_facebook = $request->id_facebook;
				$user = Client::where('id_facebook','=',$id_facebook)->first();
				if ($user) {
					if($request->avatar){
						$user->avatar = resize_avatar($request->avatar);
						$user->save();
						commandSyncClient2Node($user->id);
					}
				  if ($user->active==1) {
				  	$data = [];
				  	Auth::guard('web_client')->loginUsingId($user->id);
				  	$client = Client::where('id',Auth::guard('web_client')->user()->id)->with('_roles')->with('_ctv')->with('_area')->first();
				  	if($client){
				  		if(!empty($client->_ctv)){
				  			$client->_daily = $client->_ctv->_daily->_client;
				  			unset($client->_ctv);
				  		}else{
				  			$client->_daily = null;
				  			unset($client->_ctv);
				  		}
				  		$data_array = $client->toArray();
				  		$data_array['count_area'] = count($client->_area);
				  		$data_array['api_roles'] = null;
				  		foreach ($client->_roles as $key => $role) {
				  			$data_array['api_roles'][$role->machine_name]['name'] = $role->name;
				  			$data_array['api_roles'][$role->machine_name]['active'] = $role->active;
				  		}
				  		$data = [$data_array];
				  	}else{
				  		$data = [Auth::guard('web_client')->user()->toArray()];
				  	}
				  	return $this->response($data,200);
				  }else{
				  	$e = new \Exception('User not activated',400);
						return $this->error($e);
				  }
				}else{
					$user = Client::where('email','=',$request->email)->first();
					if ($user) {
					  if ($user->active==1) {
					  	$user->id_facebook = $request->id_facebook;
					  	$user->save();
					  	commandSyncClient2Node($user->id);
					  	$data = [];
					  	Auth::guard('web_client')->loginUsingId($user->id);
					  	$data = [];
					  	$client = Client::where('id',Auth::guard('web_client')->user()->id)->with('_roles')->with('_ctv')->with('_area')->first();
					  	if($client){
					  		if(!empty($client->_ctv)){
					  			$client->_daily = $client->_ctv->_daily->_client;
					  			unset($client->_ctv);
					  		}else{
					  			$client->_daily = null;
					  			unset($client->_ctv);
					  		}
					  		$data_array = $client->toArray();
					  		$data_array['count_area'] = count($client->_area);
					  		$data_array['api_roles'] = null;
					  		foreach ($client->_roles as $key => $role) {
					  			$data_array['api_roles'][$role->machine_name]['name'] = $role->name;
					  			$data_array['api_roles'][$role->machine_name]['active'] = $role->active;
					  		}
					  		$data = [$data_array];
					  	}else{
					  		$data = [Auth::guard('web_client')->user()->toArray()];
					  	}
					  	return $this->response($data,200);
					  }else{
					  	$e = new \Exception('User not activated',400);
							return $this->error($e);
					  }
					}else{
						$rules = [
							'full_name' => 'required|min:3|max:150',
							'email' => 'required|email|max:255',
							'id_facebook' => 'required'
						];
						$messages = [
							'full_name.required' => trans('global.full_name_required'),
							'full_name.min' => trans('global.full_name_min'),
							'full_name.max' => trans('global.full_name_max'),
							'email.required' => trans('global.email_required'),
							'email.email' => trans('global.email_email'),
							'email.max' => trans('global.email_max'),
							'id_facebook.required' => trans('global.id_facebook_required')
						];

						$validator = Validator::make($request->all(), $rules, $messages);
						if ($validator->fails()) {
							$e = new \Exception($validator->errors()->first(),400);
							return $this->error($e);
						}else{
							$client = Client::create([
		            'full_name' => $request->full_name,
		            'email' => $request->email,
		            'avatar' => $request->avatar ? resize_avatar($request->avatar) : make_image_avatar(mb_strtoupper(substr(vn_string($request->full_name),0,1))),
		            'id_facebook' =>$request->id_facebook,
		          ]);
		          if($client){

		       
		       			$client->ma_dinh_danh = create_number_wallet($client->id);
		       			$client->save();

		       			commandSyncClient2Node($client->id);
		       			$trans = new TransactionCoin();
		            $trans->bonus($client, BONUS_REGISTER, trans('transaction.bonus_create_account',['coin'  => money_number(BONUS_REGISTER)]));
		            if($trans->getError()){
		              throw $trans->getError();
		            }
		            $data = [];
						  	Auth::guard('web_client')->loginUsingId($client->id);
						  	$data = [];
						  	$client = Client::where('id',Auth::guard('web_client')->user()->id)->with('_roles')->with('_ctv')->with('_area')->first();
						  	if($client){
						  		if(!empty($client->_ctv)){
						  			$client->_daily = $client->_ctv->_daily->_client;
						  			unset($client->_ctv);
						  		}else{
						  			$client->_daily = null;
						  			unset($client->_ctv);
						  		}
						  		$data_array = $client->toArray();
						  		$data_array['count_area'] = count($client->_area);
						  		$data_array['api_roles'] = null;
						  		foreach ($client->_roles as $key => $role) {
						  			$data_array['api_roles'][$role->machine_name]['name'] = $role->name;
						  			$data_array['api_roles'][$role->machine_name]['active'] = $role->active;
						  		}
						  		$data = [$data_array];
						  	}else{
						  		$data = [Auth::guard('web_client')->user()->toArray()];
						  	}
						  	return $this->response($data,200);
		          }else{
		          	$e = new \Exception('Error create user by Google',400);
								return $this->error($e);
		          }
						}
					}
				}
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function loginGG(Request $request){
		try{
			$rules = [
				'id_google' => 'required',
				'email' => 'required'
			];
			$messages = [
				'id_google.required' => trans('global.id_google_required'),
				'email.required' => trans('global.email_required'),
			];

			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$id_google = $request->id_google;
				$user = Client::where('id_google','=',$id_google)->first();
				if ($user) {
					if($request->avatar){
						$user->avatar = resize_avatar($request->avatar);
						$user->save();
						commandSyncClient2Node($user->id);
					}
				  if ($user->active==1) {
				  	$data = [];
				  	Auth::guard('web_client')->loginUsingId($user->id);
				  	$data = [];
				  	$client = Client::where('id',Auth::guard('web_client')->user()->id)->with('_roles')->with('_ctv')->with('_area')->first();
				  	if($client){
				  		if(!empty($client->_ctv)){
				  			$client->_daily = $client->_ctv->_daily->_client;
				  			unset($client->_ctv);
				  		}else{
				  			$client->_daily = null;
				  			unset($client->_ctv);
				  		}
				  		$data_array = $client->toArray();
				  		$data_array['count_area'] = count($client->_area);
				  		$data_array['api_roles'] = null;
				  		foreach ($client->_roles as $key => $role) {
				  			$data_array['api_roles'][$role->machine_name]['name'] = $role->name;
				  			$data_array['api_roles'][$role->machine_name]['active'] = $role->active;
				  		}
				  		$data = [$data_array];
				  	}else{
				  		$data = [Auth::guard('web_client')->user()->toArray()];
				  	}
				  	return $this->response($data,200);
				  }else{
				  	$e = new \Exception('User not activated',400);
						return $this->error($e);
				  }
				}else{
					$user = Client::where('email','=',$request->email)->first();
					if ($user) {
					  if ($user->active==1) {
					  	$user->id_google = $request->id_google;
					  	$user->save();
					  	commandSyncClient2Node($user->id);
					  	$data = [];
					  	Auth::guard('web_client')->loginUsingId($user->id);
					  	$data = [];
					  	$client = Client::where('id',Auth::guard('web_client')->user()->id)->with('_roles')->with('_ctv')->with('_area')->first();
					  	if($client){
					  		if(!empty($client->_ctv)){
					  			$client->_daily = $client->_ctv->_daily->_client;
					  			unset($client->_ctv);
					  		}else{
					  			$client->_daily = null;
					  			unset($client->_ctv);
					  		}
					  		$data_array = $client->toArray();
					  		$data_array['count_area'] = count($client->_area);
					  		$data_array['api_roles'] = null;
					  		foreach ($client->_roles as $key => $role) {
					  			$data_array['api_roles'][$role->machine_name]['name'] = $role->name;
					  			$data_array['api_roles'][$role->machine_name]['active'] = $role->active;
					  		}
					  		$data = [$data_array];
					  	}else{
					  		$data = [Auth::guard('web_client')->user()->toArray()];
					  	}
					  	return $this->response($data,200);
					  }else{
					  	$e = new \Exception('User not activated',400);
							return $this->error($e);
					  }
					}else{
						$rules = [
							'full_name' => 'required|min:3|max:150',
							'email' => 'required|email|max:255',
							'id_google' => 'required'
						];
						$messages = [
							'full_name.required' => trans('global.full_name_required'),
							'full_name.min' => trans('global.full_name_min'),
							'full_name.max' => trans('global.full_name_max'),
							'email.required' => trans('global.email_required'),
							'email.email' => trans('global.email_email'),
							'email.max' => trans('global.email_max'),
							'id_google.required' => trans('global.id_google_required')
						];

						$validator = Validator::make($request->all(), $rules, $messages);
						if ($validator->fails()) {
							$e = new \Exception($validator->errors()->first(),400);
							return $this->error($e);
						}else{
							$client = Client::create([
		            'full_name' => $request->full_name,
		            'email' => $request->email,
		            'avatar' => $request->avatar ? resize_avatar($request->avatar) : make_image_avatar(mb_strtoupper(substr(vn_string($request->full_name),0,1))),
		            'id_google' =>$request->id_google,
		          ]);
		          if($client){
		          	$client->ma_dinh_danh = create_number_wallet($client->id);
		          	$client->save();
		          	commandSyncClient2Node($client->id);
		            $trans = new TransactionCoin();
		            $trans->bonus($client, BONUS_REGISTER, trans('transaction.bonus_create_account',['coin'  => money_number(BONUS_REGISTER)]));
		            if($trans->getError()){
		              throw $trans->getError();
		            }
		            $data = [];
						  	Auth::guard('web_client')->loginUsingId($client->id);
						  	$data = [];
						  	$client = Client::where('id',Auth::guard('web_client')->user()->id)->with('_roles')->with('_ctv')->with('_area')->first();
						  	if($client){
						  		if(!empty($client->_ctv)){
						  			$client->_daily = $client->_ctv->_daily->_client;
						  			unset($client->_ctv);
						  		}else{
						  			$client->_daily = null;
						  			unset($client->_ctv);
						  		}
						  		$data_array = $client->toArray();
						  		$data_array['count_area'] = count($client->_area);
						  		$data_array['api_roles'] = null;
						  		foreach ($client->_roles as $key => $role) {
						  			$data_array['api_roles'][$role->machine_name]['name'] = $role->name;
						  			$data_array['api_roles'][$role->machine_name]['active'] = $role->active;
						  		}
						  		$data = [$data_array];
						  	}else{
						  		$data = [Auth::guard('web_client')->user()->toArray()];
						  	}
						  	return $this->response($data,200);
		          }else{
		          	$e = new \Exception('Error create user by Google',400);
								return $this->error($e);
		          }
						}
					}
				}
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function register(Request $request){
		try{
			$rules = [
	      'full_name' => 'required|min:3|max:150',
	      'email' => 'required|email|max:255|unique:clients',
	      'phone' => 'required|min:9',
	      'password' => 'required|min:6',
	    ];
	    $messages = [
	      'full_name.required' => trans('global.full_name_required'),
	      'full_name.min' => trans('global.full_name_min'),
	      'full_name.max' => trans('global.full_name_max'),
	      'email.required' => trans('global.email_required'),
	      'email.email' => trans('global.email_email'),
	      'email.max' => trans('global.email_max'),
	      'email.unique' => trans('global.email_unique'),
	      'phone.required' => trans('global.phone_required'),
	      'phone.min' => trans('global.phone_min'),
	      'password.required' => trans('global.password_required'),
	      'password.min' => trans('global.password_min')
	    ];
	    $validator = Validator::make($request->all(), $rules, $messages);
	    if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$client = Client::create([
	        'full_name' => $request->full_name,
	        'email' => $request->email,
	        'password' => bcrypt($request->password),
	        'avatar' => make_image_avatar(mb_strtoupper(substr(vn_string($request->full_name),0,1))),
	        'phone' => $request->phone,
	        'code_invite' => $request->phone,
	        'active' => 0,
	      ]);

	      if($client){
	      	$client->ma_dinh_danh = create_number_wallet($client->id);

	      	$client->updated_by = $client->id;
					$client->type_user_update = 0;
					$client->updated_at = \Carbon::now();

	      	$client->save();
	        $trans = new TransactionCoin();
	        $trans->bonus($client, BONUS_REGISTER, trans('transaction.bonus_create_account',['coin'  => money_number(BONUS_REGISTER)]));
	        if($trans->getError()){
	          throw $trans->getError();
	        }

	        commandSyncClient2Node($client->id);
	      }

	      $mail_template = EmailTemplate::where('machine_name', 'create_client')->first();
	      if($mail_template)
	      {
	        $data = [
	          'full_name' => $request->full_name,
	          'email' => $request->email,
	          'password' => $request->password,
	          'link' => url('/active_client/'.base64_encode($request->email)),
	        ];
	        Mail::send([], [], function($message) use ($mail_template, $data)
	        {
	          $message->to($data['email'], $data['full_name'])
	            ->subject($mail_template['subject'])
	            ->from('kingmapteam@gmail.com', 'KingMap Team')
	            ->setBody($mail_template->parse($data));
	        });
	      }

	      $data = $client->toArray();
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function getUserLikeLocation(Request $request, $id_user){

		try{
			$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
			if(!$user){
				$e = new \Exception(trans('Location'.DS.'user.user_not_found'),400);
				return $this->error($e);
			}else{
				$data = [];
				$skip = $request->skip?$request->skip:0;
				$limit = $request->limit?$request->limit:20;
				$contents = Content::select(
														'contents.*'
													)
													->where('contents.moderation','=','publish')
													->where('contents.active','=',1)
													->with('_country')
													->with('_city')
													->with('_district')
													->distinct('contents.id')
													->leftJoin('save_like_content','id_content','=','contents.id')
													->where('id_user','=',$user->id)
                                                    ->orderBy('contents.id','desc')
													->skip($skip)
													->limit($limit)
													->get();
				if($contents){
					$data = $contents->toArray();
				}
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function getUserCheckin(Request $request, $id_user){

		try{
			$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
			if(!$user){
				$e = new \Exception(trans('Location'.DS.'user.user_not_found'),400);
				return $this->error($e);
			}else{
				$data = [];
				$skip = $request->skip?$request->skip:0;
				$limit = $request->limit?$request->limit:20;
				$contents = Content::select(
														'contents.*'
													)
													->where('contents.moderation','=','publish')
													->where('contents.active','=',1)
													->with('_country')
													->with('_city')
													->with('_district')
													->distinct('contents.id')
													->leftJoin('checkin','id_content','=','contents.id')
													->where('id_user','=',$user->id)
                                                    ->orderBy('contents.id','desc')
													->skip($skip)
													->limit($limit)
													->get();
				if($contents){
					$data = $contents->toArray();
				}
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function getUserLocation(Request $request, $id_user){

		try{
			$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
			if(!$user){
				$e = new \Exception(trans('Location'.DS.'user.user_not_found'),400);
				return $this->error($e);
			}else{
				$data = [];
				$skip = $request->skip?$request->skip:0;
				$limit = $request->limit?$request->limit:20;
				$contents = Content::select(
														'contents.*'
													)
													->with('_country')
													->with('_city')
													->with('_district')
													->with('_category_type')
													->distinct('contents.id')
													->where('type_user',0)
													->where('created_by',$id_user)
													->whereIn('moderation', ['publish', 'request_publish', 'un_publish'])
                                                    ->orderBy('contents.id','desc')
													->skip($skip)
													->limit($limit)
													->get();
				if($contents){
					$data = $contents->toArray();
				}
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function checkLogin(){
		$data = [];
		if(Auth::guard('web_client')->user()){
			$client = Client::where('id',Auth::guard('web_client')->user()->id)->with('_roles')->with('_ctv')->with('_area')->first();
	  	if($client){
	  		if(!empty($client->_ctv)){
	  			$client->_daily = $client->_ctv->_daily->_client;
	  			unset($client->_ctv);
	  		}else{
	  			$client->_daily = null;
	  			unset($client->_ctv);
	  		}
	  		$data_array = $client->toArray();
	  		$data_array['count_area'] = count($client->_area);
	  		$data_array['api_roles'] = null;
	  		foreach ($client->_roles as $key => $role) {
	  			$data_array['api_roles'][$role->machine_name]['name'] = $role->name;
	  			$data_array['api_roles'][$role->machine_name]['active'] = $role->active;
	  		}
	  		$data = [$data_array];
	  	}else{
	  		$data = [Auth::guard('web_client')->user()->toArray()];
	  	}
		}
		return $this->response($data,200);
	}

	public function getStatic($id_user){
		try{
			$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
			if(!$user){
				$e = new \Exception(trans('Location'.DS.'user.user_not_found'),400);
				return $this->error($e);
			}else{
				$data = [];
				$count_checkin = Content::select('contents.id')
							->distinct('contents.id')
							->leftJoin('checkin','id_content','=','contents.id')
							->where('id_user','=',$user->id)
							->where('contents.active',1)
							->count();
				$count_like = Content::select('contents.id')
							->distinct('contents.id')
							->leftJoin('save_like_content','id_content','=','contents.id')
							->where('id_user','=',$user->id)
							->where('contents.active',1)
							->count();
							
				$count_collection = Collection::where('created_by', $id_user)->count();


				$count_location = Content::where('created_by', $id_user)
																	->where('type_user',0)
																	->whereIn('moderation', ['publish', 'request_publish', 'un_publish'])
																	->count();
				$data['count_checkin'] = $count_checkin;
				$data['count_like'] = $count_like;
				$data['count_collection'] = $count_collection;
				$data['count_location'] = $count_location;
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function getUser($id_user){
		try{
			$user = Client::select('id','full_name','email','phone','avatar','description','id_facebook','id_google','address','birthday','code_invite','register_invite','coin','cmnd','active_invite','rate_revenue','social_account')
										->where('id','=',$id_user)
										->where('active','=',1)
										->first();
			if(!$user){
				$e = new \Exception(trans('Location'.DS.'user.user_not_found'),400);
				return $this->error($e);
			}else{
		  	$data = $user->toArray();
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postUpdateUser(Request $request,$id_user){
		try{
			$user = Client::select('id','full_name','email','phone','avatar','description','id_facebook','id_google','address','birthday','code_invite','register_invite','coin','cmnd','active_invite','rate_revenue','social_account')
										->where('id','=',$id_user)
										->where('active','=',1)
										->first();
			if(!$user){
				$e = new \Exception(trans('Location'.DS.'user.user_not_found'),400);
				return $this->error($e);
			}else{
				if(!\Auth::guard('web_client')->user() || $user->id != \Auth::guard('web_client')->user()->id){
					$e = new \Exception(trans('Location'.DS.'user.user_not_found'),400);
					return $this->error($e);
				}else{
					$user->full_name = $request->full_name?$request->full_name:'';
				  $user->birthday = $request->birthday?$request->birthday:null;
				  $user->phone = $request->phone?$request->phone:'';
				  $user->address = $request->address?$request->address:'';
				  $user->description = $request->description?$request->description:'';
				  $path = public_path().'/upload/img_user';
					if($request->file('avatar')) {
						$file = $request->file('avatar');
						if(!\File::exists($path)) {
							\File::makeDirectory($path, $mode = 0777, true, true);
						}
						$name =time(). '.' . $file->getClientOriginalExtension();
						if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
							$file->move($path,$name);
						$user->avatar ='/'.'upload/img_user'.'/'.$name;
					}

					$user->updated_by = $user->id;
					$user->type_user_update = 0;
					$user->updated_at = \Carbon::now();


				  if($user->save()){
				  	$data = [];
						if(Auth::guard('web_client')->user()){
							$client = Client::where('id',Auth::guard('web_client')->user()->id)->with('_roles')->with('_ctv')->with('_area')->first();
					  	if($client){
					  		if(!empty($client->_ctv)){
					  			$client->_daily = $client->_ctv->_daily->_client;
					  			unset($client->_ctv);
					  		}else{
					  			$client->_daily = null;
					  			unset($client->_ctv);
					  		}
					  		$data_array = $client->toArray();
					  		$data_array['count_area'] = count($client->_area);
					  		$data_array['api_roles'] = null;
					  		foreach ($client->_roles as $key => $role) {
					  			$data_array['api_roles'][$role->machine_name]['name'] = $role->name;
					  			$data_array['api_roles'][$role->machine_name]['active'] = $role->active;
					  		}
					  		$data = [$data_array];
					  	}else{
					  		$data = [Auth::guard('web_client')->user()->toArray()];
					  	}
						}
						return $this->response($data,200);
				  }else{
				  	$e = new \Exception(trans('Location'.DS.'user.update_profile_not_success'),400);
						return $this->error($e);
				  }
				}
				
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postChangePassword(Request $request){
		try{
			$rules = [
	      'old_password' => 'required|min:6',
	      'new_password' => 'required|min:6'
	    ];
	    $messages = [
	      'old_password.required' => trans('global.old_password_required'),
	      'old_password.min' => trans('global.old_password_min'),
	      'new_password.required' => trans('global.new_password_required'),
	      'new_password.min' => trans('global.new_password_min'),
	    ];
	    $validator = Validator::make($request->all(), $rules, $messages);
	    if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$id_user = $request->id_user?$request->id_user:0;
				$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
				if(!$user){
					$e = new \Exception(trans('Location'.DS.'user.user_not_found'),400);
					return $this->error($e);
				}else{
					if($user->password === null || Hash::check($request->old_password, $user->password)){
						$user->password = Hash::make($request->new_password);
						$user->updated_by = $user->id;
						$user->type_user_update = 0;
						$user->updated_at = \Carbon::now();
						if($user->save()){
					  	Auth::guard('web_client')->logout();
							session()->flush();
							session()->regenerate();
							$data = [];
							return $this->response($data,200);
					  }else{
					  	$e = new \Exception(trans('Location'.DS.'user.update_password_not_success'),400);
							return $this->error($e);
					  }
					}else{
						$e = new \Exception(trans('Location'.DS.'user.wrong_old_password'),400);
						return $this->error($e);
					}
				}
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function deleteUserLikeLocation($id_content){
		try{
			$user = \Auth::guard('web_client')->user();
			if(!$user){
				$e = new \Exception(trans('Location'.DS.'preview.not_login'),400);
				return $this->error($e);
			}else{
				$check = SaveLikeContent::where('id_content',$id_content)
															  ->where('id_user',$user->id)
															  ->delete();
				$data = [];
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}
	
	public function deleteUserCheckin($id_content){
		try{
			$user = \Auth::guard('web_client')->user();
			if(!$user){
				$e = new \Exception(trans('Location'.DS.'preview.not_login'),400);
				return $this->error($e);
			}else{
				$check = Checkin::where('id_content',$id_content)
															  ->where('id_user',$user->id)
															  ->delete();
				$data = [];
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}
	public function deleteUserLocation($id_content){
		try{
			$user = \Auth::guard('web_client')->user();
			if(!$user){
				$e = new \Exception(trans('Location'.DS.'preview.not_login'),400);
				return $this->error($e);
			}else{
				$content = Content::where('id',$id_content)
											  ->where('created_by',$user->id)
											  ->where('type_user',0)
                      	->first();
        if($content){
        	$content->moderation = 'trash';
		      $content->active = 0;
		      $content->save();
        }
				$data = [];
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function openUserLocation($id_content){
		try{
			$user = \Auth::guard('web_client')->user();
			if(!$user){
				$e = new \Exception(trans('Location'.DS.'preview.not_login'),400);
				return $this->error($e);
			}else{
				$content = Content::where('id',$id_content)
											  ->where('created_by',$user->id)
											  ->where('type_user',0)
                      	->first();
        if($content){
					$content->moderation = 'publish';
					$content->active = 1;
					$content->save();
        }
				$data = [];
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function closeUserLocation($id_content){
		try{
			$user = \Auth::guard('web_client')->user();
			if(!$user){
				$e = new \Exception(trans('Location'.DS.'preview.not_login'),400);
				return $this->error($e);
			}else{
				$content = Content::where('id',$id_content)
											  ->where('created_by',$user->id)
											  ->where('type_user',0)
                      	->first();
        if($content){
        	$content->moderation = 'un_publish';
		      $content->active = 0;
		      $content->save();
        }
				$data = [];
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}


	public function getSearchContent(Request $request){
		try{
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
					$arr_tmp['name'] = $content->name;
					$arr_tmp['address'] = $content->address.', '.$content->_district->name.', '.$content->_city->name.', '.$content->_country->name;
					if(check_exist(asset($content->avatar))){
						$arr_tmp['avatar'] = url($content->avatar);
					}else{
						$arr_tmp['avatar'] = url('/img_user/default.png');
					}
					$arr_return[] = $arr_tmp;
				}
			}
			return $this->response($arr_return,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}
	
	public function getSearchUser(){
		try{	
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
			return $this->response($arr_return,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postChangeOwner(Request $request){
		try{
			$arrReturn = ['message'=>''];

	    $notifi = new Notifi();
	    $notifi->content = '';
	    $notifi->save();
	    $id_notifi = $notifi->id;

	    $arrData = [
	    	'content' => $request->content,
	    	'to_user' => $request->to_user,
	    	'from_user' => $request->from_user,
	    	'id_notifi' => $id_notifi
	    ];

	    $to_user = Client::find($arrData['to_user']);
	    $contents = Content::whereIn('id',$arrData['content'])
													->where('created_by',$arrData['from_user'])
													->where('type_user',0)	
												  ->get();
	    $status = trans('global.locations').': ';
	    foreach ($contents as $key => $content) {
	        if($key==0)
	            $status.=$content->name;
	        else
	            $status.=', '.$content->name.' ';
	    }
	    $codeApply = super_encode(json_encode($arrData));
	    
	    $html = $status.' '.trans('valid.wait_change_owner_to',['link_apply'=>'/change-owner?h='.$codeApply,'link_decline'=>'/change-owner?d='.$codeApply]);
	    $content = 'valid.wait_change_owner_to';
	    $data = ['code'=>$codeApply,'link_apply'=>'/change-owner?h='.$codeApply,'link_decline'=>'/change-owner?d='.$codeApply,"status" => $status];

	    $old_notifi = 	Notifi::find($id_notifi);
			$old_notifi->type = 'change_owner';
	    $old_notifi->save();


	   	$new_notifi = new Notifi();
	    $new_notifi->updateNotifiUserByTemplate($id_notifi,$content,$to_user->id,$data);
	    
	    $arrReturn['message'] = trans('valid.change_owner_to',['status'=>$status,'to_user'=>$to_user->full_name]);
	    $arrReturn['code'] = $codeApply;
	    return $this->response($arrReturn,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postApplyChangeOwner(Request $request){
		try{
			if($request->h){
				$arrApply = json_decode(super_decode($request->h),true);
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
		    $arrReturn['message'] = trans('valid.change_owner_to',['status'=>$status,'to_user'=>$to_user->full_name]);

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

		    $mail_template_from = EmailTemplate::where('machine_name', 'change_owner')->first();
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
		    
		    Notifi::where('id',$arrApply['id_notifi'])->delete();
		    $notifi = new Notifi();

		    $notifi->createNotifiUserByTemplate('valid.change_owner_to',$to_user->id,['status'=>$status, 'to_user'=>$to_user->full_name]);

		    $notifi->createNotifiUserByTemplate('valid.change_owner_to',$from_user->id,['status'=>$status, 'to_user'=>$to_user->full_name]);

				return $this->response($arrReturn,200);
			}else{
				if($request->d){
					$arrApply = json_decode(super_decode($request->d),true);
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
					Notifi::where('id',$arrApply['id_notifi'])->delete();
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
					$notifi = new Notifi();

			    // $notifi->createNotifiUserByTemplate('valid.change_owner_reject',$to_user->id,['status'=>$status, 'to_user'=>$to_user->full_name]);

		    	$notifi->createNotifiUserByTemplate('valid.change_owner_reject',$from_user->id,['status'=>$status, 'to_user'=>$to_user->full_name]);
					$data_return = ['message'=>trans('valid.you_have_decline')];
					return $this->response($data_return,200);
				}	
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function getListNoti(Request $request){
	    try{
	        $notifications = [];
	        $count_notifications = 0;
	        $user = \Auth::guard('web_client')->user();
	        $skip = $request->skip?$request->skip:0;
					$limit = $request->limit?$request->limit:15;
	        if($user){
	            $notifications = Notifi::getNotifi($skip,$limit)->get();
	            if($notifications){
	            	$arr_noti_read = [];
	            	foreach ($notifications as $key => $value) {
	            		$arr_noti_read[] = $value->id;
									if($value->template_notifi_id > 0){
										$content = trans($value->content,json_decode($value->data,true));
									}else{
										$content = $value->content;
									}
	            		$regex = "/<.+>/";
    							$notifications[$key]->contentText = trim(preg_replace($regex, "", $content));
    							$notifications[$key]->data = json_decode($value->data);
	            	}

	            	NotifiUser::whereIn('notifi_id',$arr_noti_read)
	            						->update([
					                  'read_at' => \Carbon::now()
					                ]);
	            }
	            $count_notifications = Notifi::getCountNotifi()->count();
	        }

	        $data = [
	            'news'=>Notifi::getNews()->get(),
	            'count_news'=>Notifi::getNews()->count(),
	            'notifications'=>$notifications,
	            'count_notifications'=>$count_notifications
	        ];
	        return $this->response($data,200);
	    }catch(Exception $e){
	        return $this->error($e);
	    }
	}

	public function registerCTV(Request $request){
		try{
			if(!$request->id){
				$e = new \Exception(trans('Location'.DS.'user.user_not_found'),400);
				return $this->error($e);
			}
			$user = Client::find($request->id);
			if(!$user){
				$e = new \Exception(trans('Location'.DS.'user.user_not_found'),400);
				return $this->error($e);
			}else{
				$rules = [
		      // 'full_name' => 'required|min:3|max:150',
		      'phone' => 'required|min:9',
		      'birthday' => 'required',
		      'address' => 'required',
		      'cmnd' => 'required|min:9',
		      'daily_id' => 'required',
		      'cmnd_image_front' => 'required',
		      'cmnd_image_back' => 'required',
		    ];
		    $messages = [
		      // 'full_name.required' => trans('global.full_name_required'),
		      // 'full_name.min' => trans('global.full_name_min'),
		      // 'full_name.max' => trans('global.full_name_max'),
		      
		      'phone.required' => trans('global.phone_required'),
		      'phone.min' => trans('global.phone_min'),

		      'birthday.required' => trans('global.birthday_required'),
		      'address.required' => trans('global.address_required'),
		      'cmnd.required' => trans('global.cmnd_required'),
		      'cmnd.min' => trans('global.cmnd_min'),
		      'daily_id.required' => trans('global.daily_id_required'),
		      'cmnd_image_front.required' => trans('global.cmnd_image_required'),
		      'cmnd_image_back.required' => trans('global.cmnd_image_required'),
		    ];
		    $validator = Validator::make($request->all(), $rules, $messages);
		    if ($validator->fails()) {
					$e = new \Exception($validator->errors()->first(),400);
					return $this->error($e);
				}else{
					$user->phone    = $request->phone;
					$user->birthday = $request->birthday;
					$user->address  = $request->address;
					$user->cmnd     = $request->cmnd;
					$path = public_path().'/upload/img_user_cmnd';
					if($request->file('cmnd_image_front')) {
						$file = $request->file('cmnd_image_front');
						if(!\File::exists($path)) {
							\File::makeDirectory($path, $mode = 0777, true, true);
						}
						$name ='front_'.time(). '.' . $file->getClientOriginalExtension();
						if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
							$file->move($path,$name);
						$user->cmnd_image_front ='/'.'upload/img_user_cmnd'.'/'.$name;
					}

					if($request->file('cmnd_image_back')) {
						$file = $request->file('cmnd_image_back');
						if(!\File::exists($path)) {
							\File::makeDirectory($path, $mode = 0777, true, true);
						}
						$name ='back_'.time(). '.' . $file->getClientOriginalExtension();
						if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
							$file->move($path,$name);
						$user->cmnd_image_back ='/'.'upload/img_user_cmnd'.'/'.$name;
					}
					
					$daily = Client::find($request->daily_id);
					$user->temp_daily_code = $daily->ma_dinh_danh;
					$user->save();
					return $this->response(trans('global.register_invite_success'),200);
				}
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}
}

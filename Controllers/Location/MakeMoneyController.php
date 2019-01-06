<?php
namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Models\Location\Client;
use App\Models\Location\ClientArea;
use App\Models\Location\ClientStatic;
use App\Models\Location\ClientRole;
use App\Models\Location\ClientInRole;
use App\Models\Location\ClientInStatic;
use App\Models\Location\CTV;
use App\Models\Location\Daily;
use App\Models\Location\GiaoViec;
use App\Models\Location\BlockText;


use App\Models\Location\Content;
use App\Models\Location\Country;
use App\Models\Location\City;
use App\Models\Location\District;
use App\Models\Location\Notifi;
use Illuminate\Http\Request;
use Validator;
use Carbon;
use Illuminate\Support\Facades\Auth;

class MakeMoneyController extends BaseController {
	public function checkClient(){
		if(is_null(Auth::guard('web_client')->user())){
			abort(404);
		}
	}

	public function index(Request $request){
		$this->checkClient();
		$client = Client::where('id',Auth::guard('web_client')->user()->id)
										->first();
		if(!$client){
			return redirect()->to('/');
		}else{
			if($client->hasRole('cong_tac_vien') > 0){
				return redirect()->route('ctv_makemoney');
			}
			if($client->hasRole('tong_dai_ly') > 0){
				return redirect()->route('daily_makemoney');
			}
			if($client->hasRole('ceo') > 0){
				return redirect()->route('ceo_makemoney');
			}

			if($client->hasRole('cong_tac_vien')==-2){
				return redirect()->route('ctv_is_lock');
			}
			if($client->hasRole('tong_dai_ly')==-2){
				return redirect()->route('daily_is_lock');
			}
			if($client->temp_daily_code){
				return redirect()->route('register_makemoney_pending');
			}else{
				return redirect()->route('register_makemoney');
			}
		}
	}

	// Trang đăng ký làm cộng tác viên
	public function getRegister(Request $request){
		$this->checkClient();
		$countries = Country::select('name', 'id')->get();
		$client = Auth::guard('web_client')->user();
		if($client->temp_daily_code){
			return redirect()->route('register_makemoney_pending');
		}
		$this->view->content = view('Location.makemoney.index',[
			'module'    =>'register',
			'countries' => $countries,
			'client' => $client,
		]);
		return $this->setContent();
	}

	public function postRegister(Request $request){
		$user = Client::find(Auth::guard('web_client')->user()->id);
		$rules = [
			'full_name' => 'required|min:3|max:150',
			'phone' => 'required|min:9',
			'birthday_birthDay' => 'required',
			'address' => 'required',
			'cmnd' => 'required|min:9',
			'daily_id' => 'required',
			'cmnd_image_front' => 'required',
			'cmnd_image_back' => 'required',
		];
		$messages = [
			'full_name.required' => trans('global.full_name_required'),
			'full_name.min' => trans('global.full_name_min'),
			'full_name.max' => trans('global.full_name_max'),
			
			'phone.required' => trans('global.phone_required'),
			'phone.min' => trans('global.phone_min'),

			'birthday_birthDay.required' => trans('global.birthday_required'),
			'address.required' => trans('global.address_required'),
			'cmnd.required' => trans('global.cmnd_required'),
			'cmnd.min' => trans('global.cmnd_min'),
			'daily_id.required' => trans('global.daily_id_required'),
			'cmnd_image_front.required' => trans('global.cmnd_image_required'),
			'cmnd_image_back.required' => trans('global.cmnd_image_required'),
		];
		$validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		}else{
			$user->full_name    = $request->full_name;
			$user->phone    = $request->phone;
			$user->birthday = $request->birthday_birthDay;
			$user->address  = $request->address;
			$user->cmnd     = $request->cmnd;

			$user->rate_revenue     = 50;

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
			
			return redirect()->route('register_makemoney');
		}
	}

	public function getRegisterPending(Request $request){
		$this->checkClient();
		$client = Client::where('id',Auth::guard('web_client')->user()->id)->with('_roles')->with('_ctv')->first();
		if($client){
			if(!empty($client->_ctv)){
				$client->_daily = $client->_ctv->_daily->_client;
				unset($client->_ctv);
			}else{
				$client->_daily = null;
				unset($client->_ctv);
			}
		}
		if($client->hasRole('cong_tac_vien') > 0){
			return redirect()->route('makemoney');
		}
		$this->view->content = view('Location.makemoney.index',[
			'module'    =>'register_pending',
			'client' => $client
		]);
		return $this->setContent();
	}

	public function getCTVlock(Request $request){
		$this->checkClient();
		$client = Client::where('id',Auth::guard('web_client')->user()->id)->with('_roles')->with('_ctv')->first();
		if($client){
			if(!empty($client->_ctv)){
				$client->_daily = $client->_ctv->_daily->_client;
				unset($client->_ctv);
			}else{
				$client->_daily = null;
				unset($client->_ctv);
			}
		}
		if($client->hasRole('cong_tac_vien') > 0){
			return redirect()->route('makemoney');
		}
		$this->view->content = view('Location.makemoney.index',[
			'module'    =>'ctv_is_lock',
			'client' => $client
		]);
		return $this->setContent();
	}

	public function getDailylock(Request $request){
		$this->checkClient();
		$client = Client::where('id',Auth::guard('web_client')->user()->id)->with('_roles')->with('_ctv')->first();
		if($client){
			if(!empty($client->_ctv)){
				$client->_daily = $client->_ctv->_daily->_client;
				unset($client->_ctv);
			}else{
				$client->_daily = null;
				unset($client->_ctv);
			}
		}
		if($client->hasRole('cong_tac_vien') > 0){
			return redirect()->route('makemoney');
		}
		$this->view->content = view('Location.makemoney.index',[
			'module'    =>'daily_is_lock',
			'client' => $client
		]);
		return $this->setContent();
	}

	
	



	// CTV

	public function getIndexCTV(Request $request){
		$this->checkClient();

		$client = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_ctv')
										->with('_area')
										->first();
		if($client->hasRole('cong_tac_vien') < 0){
			return redirect()->route('makemoney');
		}
		if($client){
			if(!empty($client->_ctv)){
				$client->_daily = $client->_ctv->_daily->_client;
				unset($client->_ctv);
			}else{
				$client->_daily = null;
				unset($client->_ctv);
			}
		}

		$quyenloi = '';
		$block = BlockText::where('machine_name','quyen_loi_va_nghia_vu_cua_ctv')->first();
		if($block){
			$lang = $request->lang?$request->lang:'vn';
			if($lang=='en'){
				$quyenloi = $block->content_en;
			}else{
				$quyenloi = $block->content_vn;
			}
		}
		$giaoviec = '';
		$gv = GiaoViec::where('from_client',$client->_daily->id)
									->where('to_client',$client->id)
									->first();
		if($gv){
			$giaoviec = $gv->content;
		}

		$info = $this->CTVInfo($client);

		$this->view->content = view('Location.makemoney.index',[
			'module'    =>'ctv_makemoney',
			'client' 		=> $client,
			'quyenloi' 	=> $quyenloi,
			'giaoviec' 	=> $giaoviec,
			'revenue'       => $info['revenue'],
			'count_location'=> $info['count_location'],
		]);
		return $this->setContent();
	}

	public function getCTVLocation(Request $request){
		$this->checkClient();

		$client = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_ctv')
										->with('_area')
										->first();
		if($client->hasRole('cong_tac_vien') < 0){
			return redirect()->route('makemoney');
		}
		if($client){
			if(!empty($client->_ctv)){
				$client->_daily = $client->_ctv->_daily->_client;
				unset($client->_ctv);
			}else{
				$client->_daily = null;
				unset($client->_ctv);
			}
		}
		$keyword = $request->keyword?$request->keyword:'';
											 

		$ctv_info = CTV::where('client_id',$client->id)->first();
		$contents = Content::select('contents.*')
														 ->where(function($query) use($ctv_info,$client){
																return $query->where('ctv_id',$ctv_info->id)
																						 ->orWhere('code_invite',$client->ma_dinh_danh);
														 })
														 ->whereIn('moderation',['publish','request_publish','un_publish']);
		
		if (isset($keyword) && $keyword != '') {
			$contents = $contents->where(function ($query) use ($keyword) {
				return $query->where('contents.name', 'LIKE', '%' . $keyword . '%')
										 ->orWhere('contents.alias', 'LIKE', '%' . str_slug_custom($keyword) . '%');
			});
		}

		$content_list = $contents->with('_country')
														 ->with('_city')
														 ->with('_district')
														 ->paginate(30);

		$info = $this->CTVInfo($client);
		$this->view->content = view('Location.makemoney.index',[
					'module'    =>'ctv_location',
					'client' 		=> $client,
					'revenue'       => $info['revenue'],
					'count_location'=> $info['count_location'],
					'contents' => $content_list,
					'keyword' => $keyword
				]);
		return $this->setContent();
	}

	public function getCTVRevenue(Request $request){
		$this->checkClient();

		$client = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_ctv')
										->with('_area')
										->first();
		if($client->hasRole('cong_tac_vien') < 0){
			return redirect()->route('makemoney');
		}
		if($client){
			if(!empty($client->_ctv)){
				$client->_daily = $client->_ctv->_daily->_client;
				unset($client->_ctv);
			}else{
				$client->_daily = null;
				unset($client->_ctv);
			}
		}
		$ctv_info = CTV::where('client_id',$client->id)->first();

		$new_static = [];

		$static = ClientStatic::selectRaw('
										type,
										rate_revenue_ctv,
										sum(total) as sum,
										sum(total*rate_revenue_ctv/100) as revenue_ctv
								');
		$month = date('n');
		$static = $static->whereRaw('MONTH(`created_at`) = '.$month);
		$year = date('Y');
		$static = $static->whereRaw('YEAR(`created_at`) = '.$year);
		$static = $static->where('ctv_id',$ctv_info->id);
		$static = $static->groupBy('type')
										 ->get();

		foreach ($static as $key => $value) {
			$obj =  array(
					"value" => (float) $value->rate_revenue_ctv/100*$value->sum,
					"name" => trans('global.'.$value->type),
					"type" => $value->type
				);
			$new_static[$value->type] = $obj;
		}									 

		$info = $this->CTVInfo($client);

		$this->view->content = view('Location.makemoney.index',[
			'module'    		=>'ctv_revenue',
			'client' 				=> $client,
			'static' 				=> $new_static,
			'revenue'       => $info['revenue'],
			'count_location'=> $info['count_location'],
		]);
		return $this->setContent();
	}

	public function CTVInfo($client){
		if($client->hasRole('cong_tac_vien') < 0){
			return redirect()->route('makemoney');
		}
		$arr_return = [
			'revenue' => 0,
			'count_location' => 0
		];

		$revenue        = 0;
		$count_location = 0;
		$ctv_info = CTV::where('client_id',$client->id)->first();
		$static = ClientStatic::selectRaw('
												type,
												sum(total) as sum,
												sum(total*rate_revenue_ctv/100) as revenue_ctv
										');
		$month = date('n');
		$static = $static->whereRaw('MONTH(`created_at`) = '.$month);
		$year = date('Y');
		$static = $static->whereRaw('YEAR(`created_at`) = '.$year);
		$static = $static->where('ctv_id',$ctv_info->id);
		$static = $static->groupBy('type')
										 ->get();

		foreach ($static as $key => $value) {
			$revenue+= (float) $value->revenue_ctv;
		}

		
		$contents = Content::select('id')
											 ->where(function($query) use($ctv_info,$client){
													return $query->where('ctv_id',$ctv_info->id)
																			 ->orWhere('code_invite',$client->ma_dinh_danh);
											 })
											 ->whereIn('moderation',['publish','request_publish','un_publish']);
		$count_location = $contents->count();
		
		$arr_return['revenue'] = $revenue;									
		$arr_return['count_location'] = $count_location;									
		return $arr_return;
	}

	// Đại lý
	public function DailyInfo($client){
		
		$arr_return = [];
		$revenue        = 0;
		$count_location = 0;
		$daily_info = Daily::where('client_id',$client->id)->first();
		$static = ClientStatic::selectRaw('
										type,
										sum(total) as sum,
										sum(total*rate_revenue_daily/100) as revenue_daily
								');
		$month = date('n');
		$static = $static->whereRaw('MONTH(`created_at`) = '.$month);
		$year = date('Y');
		$static = $static->whereRaw('YEAR(`created_at`) = '.$year);
		$static = $static->where('daily_id',$daily_info->id);
		$static = $static->groupBy('type')
											 ->get();

		foreach ($static as $key => $value) {
			$revenue+= (float) $value->revenue_daily;
		}									 

		
		$count_ctv = CTV::where('daily_id',$daily_info->id)->count('id');

		$count_ctv_pending = Client::where('temp_daily_code',$client->ma_dinh_danh)
															 ->where('active',1)
															 ->count('id');
		$contents = Content::select('id')
														 ->where(function($query) use($daily_info,$client){
																return $query->where('daily_id',$daily_info->id);
														 })
														 ->whereIn('moderation',['publish','request_publish','un_publish']);
		$count_location = $contents->count();

		$contents_pending = Content::select('id')
														 ->where(function($query) use($daily_info,$client){
																return $query->where('daily_id',$daily_info->id);
														 })
														 ->whereIn('moderation',['request_publish']);
		$count_location_pending = $contents_pending->count();


		$arr_return = [
			'revenue'           => $revenue,
			'count_location'    => $count_location,
			'count_location_pending'    => $count_location_pending,
			'count_ctv'         => $count_ctv,
			'count_ctv_pending' => $count_ctv_pending,
		];
		return $arr_return;
	}

	public function getIndexDaily(Request $request){
		$this->checkClient();

		$client = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_area')
										->first();
		if($client->hasRole('tong_dai_ly') < 0){
			return redirect()->route('makemoney');
		}
		$quyenloi = '';
		$block = BlockText::where('machine_name','quyen_loi_va_nghia_vu_cua_tdl')->first();
		if($block){
			$lang = $request->lang?$request->lang:'vn';
			if($lang=='en'){
				$quyenloi = $block->content_en;
			}else{
				$quyenloi = $block->content_vn;
			}
		}
		$giaoviec = '';
		$gv = GiaoViec::where('to_client',$client->id)
									->first();
		if($gv){
			$giaoviec = $gv->content;
		}

		$info = $this->DailyInfo($client);

		$this->view->content = view('Location.makemoney.index',[
			'module'            =>'daily_makemoney',
			'client'            => $client,
			'quyenloi'          => $quyenloi,
			'giaoviec'          => $giaoviec,
			'revenue'           => $info['revenue'],
			'count_location'    => $info['count_location'],
			'count_ctv'         => $info['count_ctv'],
			'count_ctv_pending' => $info['count_ctv_pending'],
			'count_location_pending' => $info['count_location_pending'],
		]);
		return $this->setContent();
	}

	public function getDailyLocation(Request $request){
		$this->checkClient();

		$client = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_area')
										->first();
		if($client->hasRole('tong_dai_ly') < 0){
			return redirect()->route('makemoney');
		}
		$keyword = $request->keyword?$request->keyword:'';
		
		$daily_info = Daily::where('client_id',$client->id)->first();
		$contents = Content::select('contents.*')
														 ->where(function($query) use($daily_info,$client){
																return $query->where('daily_id',$daily_info->id);
														 })
														 ->whereIn('moderation',['publish','request_publish','un_publish']);
		if (isset($keyword) && $keyword != '') {
			$contents = $contents->where(function ($query) use ($keyword) {
				return $query->where('contents.name', 'LIKE', '%' . $keyword . '%')
										 ->orWhere('contents.alias', 'LIKE', '%' . str_slug_custom($keyword) . '%');
			});
		}

		$content_list = $contents->with('_country')
														 ->with('_city')
														 ->with('_district')
														 ->paginate(30);

		$info = $this->DailyInfo($client);
		$this->view->content = view('Location.makemoney.index',[
			'module'            =>'daily_location',
			'client'            => $client,
			'contents'          => $content_list,
			'keyword'           => $keyword,
			'revenue'           => $info['revenue'],
			'count_location'    => $info['count_location'],
			'count_ctv'         => $info['count_ctv'],
			'count_ctv_pending' => $info['count_ctv_pending'],
			'count_location_pending' => $info['count_location_pending'],
		]);
		return $this->setContent();
	}

	public function getDailyRevenue(Request $request){
		$this->checkClient();

		$client = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_area')
										->first();
		if($client->hasRole('tong_dai_ly') < 0){
			return redirect()->route('makemoney');
		}
		$new_static = [];
		$daily_info = Daily::where('client_id',$client->id)->first();
		$static = ClientStatic::selectRaw('
										type,
										rate_revenue_daily,
										sum(total) as sum,
										sum(total*rate_revenue_daily/100) as revenue_daily
								');
		$month = date('n');
		$static = $static->whereRaw('MONTH(`created_at`) = '.$month);
		$year = date('Y');
		$static = $static->whereRaw('YEAR(`created_at`) = '.$year);
		$static = $static->where('daily_id',$daily_info->id);
		$static = $static->groupBy('type')
										 ->get();

		foreach ($static as $key => $value) {
			$obj =  array(
					"value" => (float) $value->rate_revenue_daily/100*$value->sum,
					"sum" => (float) $value->sum,
					"name" => trans('global.'.$value->type),
					"type" => $value->type
				);
			$new_static[$value->type] = $obj;
		}									 

		$info = $this->DailyInfo($client);

		$this->view->content = view('Location.makemoney.index',[
			'module'            =>'daily_revenue',
			'client'            => $client,
			'static'            => $new_static,
			'revenue'           => $info['revenue'],
			'count_location'    => $info['count_location'],
			'count_ctv'         => $info['count_ctv'],
			'count_ctv_pending' => $info['count_ctv_pending'],
			'count_location_pending' => $info['count_location_pending'],
		]);
		return $this->setContent();
	}

	public function getDailyCTV(Request $request){
		$this->checkClient();
		$client = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_area')
										->first();
		if($client->hasRole('tong_dai_ly') < 0){
			return redirect()->route('makemoney');
		}
		$keyword = $request->keyword?$request->keyword:'';
		$daily_info = Daily::where('client_id',$client->id)->first();
		$role = ClientRole::where('machine_name','cong_tac_vien')->first();
		if($client){
			$ctv = Client::select(
											'clients.id',
											'clients.full_name',
											'clients.email',
											'clients.avatar',
											'client_in_role.active as role_active',
											'clients.daily_code',
											'clients.temp_daily_code',
											'clients.rate_revenue'
										)
									 ->where('daily_code',$client->ma_dinh_danh)
									 ->where('clients.active',1)
									 // ->with('_area')
									 ->with('_ctv')
									 ->with('_roles');

			$ctv = $ctv->rightJoin('client_in_role',function($query) use ($role){
																return $query->where('client_in_role.role_id',$role->id)
																						 ->on('client_in_role.client_id','clients.id');
															});
			if($keyword!=''){
				$ctv = $ctv->where(function($query) use($keyword){
										return $query->where('email','like','%'.$keyword.'%')
																 ->orwhere('full_name','like','%'.$keyword.'%')
																 ->orWhere('phone','like','%'.$keyword.'%');
									 });
			}
			$ctv = $ctv->paginate(30);
		}else{
			$ctv = null;
		}
		$info = $this->DailyInfo($client);
		$this->view->content = view('Location.makemoney.index',[
					'module'            =>'daily_ctv',
					'client'            => $client,
					'keyword'           => $keyword,
					'ctv'               => $ctv,
					'revenue'           => $info['revenue'],
					'count_location'    => $info['count_location'],
					'count_ctv'         => $info['count_ctv'],
					'count_ctv_pending' => $info['count_ctv_pending'],
					'count_location_pending' => $info['count_location_pending'],
				]);
		return $this->setContent();
	}

	public function getDailyCTVPending(Request $request){
		$this->checkClient();
		$client = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_area')
										->first();
		if($client->hasRole('tong_dai_ly') < 0){
			return redirect()->route('makemoney');
		}
		$keyword = $request->keyword?$request->keyword:'';
		$daily_info = Daily::where('client_id',$client->id)->first();

		if($client){
			$ctv = Client::where('temp_daily_code',$client->ma_dinh_danh)
									 ->where('active',1)
									 // ->with('_area')
									 ->with('_ctv')
									 ->with('_roles');

			if($keyword!=''){
				$ctv = $ctv->where(function($query) use($keyword){
										return $query->where('email','like','%'.$keyword.'%')
																 ->orwhere('full_name','like','%'.$keyword.'%')
																 ->orWhere('phone','like','%'.$keyword.'%');
									 });
			}
			$ctv = $ctv->paginate(30);
		}else{
			$ctv = null;
		}
		$info = $this->DailyInfo($client);
		$this->view->content = view('Location.makemoney.index',[
					'module'            =>'daily_ctv_pending',
					'client'            => $client,
					'keyword'           => $keyword,
					'ctv'               => $ctv,
					'revenue'           => $info['revenue'],
					'count_location'    => $info['count_location'],
					'count_ctv'         => $info['count_ctv'],
					'count_ctv_pending' => $info['count_ctv_pending'],
					'count_location_pending' => $info['count_location_pending'],
				]);
		return $this->setContent();
	}

	public function getAcceptCTV(Request $request,$id){
		$daily_id = Auth::guard('web_client')->user()?Auth::guard('web_client')->user()->id:0;
		$ctv_id = $id;
		$role = ClientRole::where('machine_name','cong_tac_vien')->first();
		if($role){
			$ctv = Client::find($ctv_id);
			$daily = Client::find($daily_id);
			if($daily){
				$ctv->daily_code = $daily->ma_dinh_danh;
				$ctv->temp_daily_code = '';
				$ctv->rate_revenue     = 50;
				$ctv->save();
				
				ClientInRole::where('client_id',$ctv->id)
										->where('role_id',$role->id)
										->delete();
				$client_role = new ClientInRole();
				$client_role->client_id = $ctv->id;
				$client_role->role_id = $role->id;
				$client_role->save();
				
				$new_ctv = new CTV();
				$new_ctv->client_id = $ctv->id;
				$daily_info = Daily::where('client_id',$daily->id)->first();
				$new_ctv->daily_id = $daily_info->id;
				$new_ctv->save();
				
				$notifi = new Notifi();
				$text_content_update = 'Admin'.DS.'client.accept_ctv';
				$notifi->createNotifiUserByTemplate($text_content_update,$ctv->id,['daily'=>$daily->full_name]);
				return redirect()->route('daily_ctv_pending')->with(['status' => trans('Admin'.DS.'client.accept_ctv_api').' '.$ctv->full_name]);
			}else{
				abort(403);
			}
		}else{
			abort(403);
		}
	}

	public function getAcceptLocation(Request $request,$id){
		$daily_id = Auth::guard('web_client')->user()?Auth::guard('web_client')->user()->id:0;
		$daily = Client::find($daily_id);
		if($daily){
			$content = Content::where('id',$id)->where('type_user',0)->first();
			if($content){
				if($content->daily_code == $daily->ma_dinh_danh){
					$content->moderation = 'publish';
					$content->active = 1;
					$content->save();
					$notifi = new Notifi();
					$notifi->createNotifiUserByTemplate('Admin'.DS.'client.publish_content',$content->created_by,['content'=>$content->name]);
				}
			}
			return redirect()->back()->with(['status'=>trans('Admin'.DS.'client.publish_content',['content'=>$content->name])]);
			
		}else{
			abort(403);
		}
	}

	public function getDeclineCTV(Request $request,$id){
		$daily_id = Auth::guard('web_client')->user()?Auth::guard('web_client')->user()->id:0;
		$ctv_id = $id;

		$ctv = Client::find($ctv_id);
		$daily = Client::find($daily_id);
		if($daily){
			$ctv->temp_daily_code = '';
			$ctv->save();

			$notifi = new Notifi();
			$text_content_update = 'Admin'.DS.'client.decline_ctv';
			$notifi->createNotifiUserByTemplate($text_content_update,$ctv->id,['daily'=>$daily->full_name]);

			return redirect()->route('daily_ctv_pending')->with(['status' => trans('Admin'.DS.'client.decline_ctv_api').' '.$ctv->full_name]);
		}else{
			abort(403);
		}
	}

	public function getLockCTV(Request $request,$id){
		$daily_id = Auth::guard('web_client')->user()?Auth::guard('web_client')->user()->id:0;
		$ctv_id = $id;
		$role = ClientRole::where('machine_name','cong_tac_vien')->first();
		$ctv = Client::find($ctv_id);
		$daily = Client::find($daily_id);
		if($daily){
			ClientInRole::where('client_id',$ctv->id)
												->where('role_id',$role->id)
												->update([
													'active' => 0
												]);

			$notifi = new Notifi();
			$text_content_update = 'Admin'.DS.'client.lock_ctv';
			$notifi->createNotifiUserByTemplate('Admin'.DS.'client.lock_ctv',$ctv->id,['daily'=>$daily->full_name]);

			return redirect()->route('daily_ctv')->with(['status' => trans('Admin'.DS.'client.lock_ctv_api').' '.$ctv->full_name]);
		}else{
			abort(403);
		}
	}

	public function getUnlockCTV(Request $request,$id){
		$daily_id = Auth::guard('web_client')->user()?Auth::guard('web_client')->user()->id:0;
		$ctv_id = $id;
		$role = ClientRole::where('machine_name','cong_tac_vien')->first();
		$ctv = Client::find($ctv_id);
		$daily = Client::find($daily_id);
		if($daily){
			ClientInRole::where('client_id',$ctv->id)
												->where('role_id',$role->id)
												->update([
													'active' => 1
												]);

			$notifi = new Notifi();
			$text_content_update = 'Admin'.DS.'client.unlock_ctv';
			$notifi->createNotifiUserByTemplate('Admin'.DS.'client.unlock_ctv',$ctv->id,['daily'=>$daily->full_name]);

			return redirect()->route('daily_ctv')->with(['status' => trans('Admin'.DS.'client.unlock_ctv_api').' '.$ctv->full_name]);
		}else{
			abort(403);
		}
	}
	public function getRemoveCTV(Request $request,$id){
		$daily_id = Auth::guard('web_client')->user()?Auth::guard('web_client')->user()->id:0;
		$ctv_id = $id;
		$role = ClientRole::where('machine_name','cong_tac_vien')->first();
    $ctv = Client::find($ctv_id);
    $daily = Client::find($daily_id);
    if($ctv){
    	if($daily){
				$ctv->daily_code = '';
				$ctv->save();

				ClientInRole::where('client_id',$ctv->id)
										->where('role_id',$role->id)
										->delete();

				GiaoViec::where('to_client',$ctv->id)->delete();
																			
				ClientArea::where('client_id',$ctv->id)
									->delete();
				

				$ctv_info = CTV::where('client_id',$ctv->id)->first();
    		Content::where('ctv_id','=',$ctv_info->id)
		           ->update([
			            'ctv_id' => 0,
			            'daily_id' => 0,
			            'code_invite' => '',
			            'daily_code' => ''
		           ]);
		    CTV::where('client_id',$ctv->id)->delete();
				$notifi = new Notifi();
				$text_content_update = 'Admin'.DS.'client.remove_ctv';
				$notifi->createNotifiUserByTemplate('Admin'.DS.'client.remove_ctv',$ctv->id,['daily'=>$daily->full_name]);
				return redirect()->route('daily_ctv')->with(['status' => trans('Admin'.DS.'client.remove_ctv_api').' '.$ctv->full_name]);
    	}else{
    		$e = new \Exception(trans('valid.not_found',['object'=>'Đại lý']),400);
				return $this->error($e);
    	}
    }else{
    	abort(403);
    }
	}


	public function getDailyLocationPending(Request $request){
		$this->checkClient();

		$client = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_area')
										->first();
		if($client->hasRole('tong_dai_ly') < 0){
			return redirect()->route('makemoney');
		}
		$keyword = $request->keyword?$request->keyword:'';
		
		$daily_info = Daily::where('client_id',$client->id)->first();
		$contents = Content::select('contents.*')
														 ->where(function($query) use($daily_info,$client){
																return $query->where('daily_id',$daily_info->id);
														 })
														 ->whereIn('moderation',['request_publish']);
		if (isset($keyword) && $keyword != '') {
			$contents = $contents->where(function ($query) use ($keyword) {
				return $query->where('contents.name', 'LIKE', '%' . $keyword . '%')
										 ->orWhere('contents.alias', 'LIKE', '%' . str_slug_custom($keyword) . '%');
			});
		}

		$content_list = $contents->with('_country')
														 ->with('_city')
														 ->with('_district')
														 ->paginate(30);

		$info = $this->DailyInfo($client);
		$this->view->content = view('Location.makemoney.index',[
			'module'            =>'daily_location_pending',
			'client'            => $client,
			'contents'          => $content_list,
			'keyword'           => $keyword,
			'revenue'           => $info['revenue'],
			'count_location'    => $info['count_location'],
			'count_ctv'         => $info['count_ctv'],
			'count_ctv_pending' => $info['count_ctv_pending'],
			'count_location_pending' => $info['count_location_pending'],
		]);
		return $this->setContent();
	}

	public function getInfoCTV(Request $request,$id){
		$this->checkClient();
		$current = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_area')
										->first();
		if($current->hasRole('tong_dai_ly') < 0 && $current->hasRole('ceo') < 0){
			abort(403);
		}
		$daily_info = Daily::where('client_id',$current->id)->first();

		$client = Client::where('id',$id)
										->with('_roles')
										->with('_ctv')
										->with('_area')
										->first();

		if($client){
			$ctv_info = CTV::where('client_id',$client->id);
			if($current->hasRole('tong_dai_ly') > 0){
				$ctv_info =	$ctv_info->where('daily_id',$daily_info->id);
			}else{
				if($current->hasRole('ceo') < 0){
					abort(404);
				}
			}
			
			
			$ctv_info =	$ctv_info->first();
			if(!$ctv_info){
				abort(404);
			}
			if(!empty($client->_ctv)){
				$client->_daily = $client->_ctv->_daily->_client;
				unset($client->_ctv);
			}else{
				$client->_daily = null;
				unset($client->_ctv);
			}
		}else{
			abort(404);
		}

		$quyenloi = '';
		$block = BlockText::where('machine_name','quyen_loi_va_nghia_vu_cua_ctv')->first();
		if($block){
			$lang = $request->lang?$request->lang:'vn';
			if($lang=='en'){
				$quyenloi = $block->content_en;
			}else{
				$quyenloi = $block->content_vn;
			}
		}
		$giaoviec = '';
		$gv = GiaoViec::where('from_client',$client->_daily->id)
									->where('to_client',$client->id)
									->first();
		if($gv){
			$giaoviec = $gv->content;
		}

		$new_static = [];

		$static = ClientStatic::selectRaw('
										type,
										rate_revenue_ctv,
										sum(total) as sum,
										sum(total*rate_revenue_ctv/100) as revenue_ctv
								');
		$month = date('n');
		$static = $static->whereRaw('MONTH(`created_at`) = '.$month);
		$year = date('Y');
		$static = $static->whereRaw('YEAR(`created_at`) = '.$year);
		$static = $static->where('ctv_id',$ctv_info->id);
		$static = $static->groupBy('type')
										 ->get();

		foreach ($static as $key => $value) {
			$obj =  array(
					"value" => (float) $value->rate_revenue_ctv/100*$value->sum,
					"name" => trans('global.'.$value->type),
					"type" => $value->type
				);
			$new_static[$value->type] = $obj;
		}

		$count_location_ctv = 0;
		$contents = Content::select('id')
											 ->where(function($query) use($ctv_info,$client){
													return $query->where('ctv_id',$ctv_info->id)
																			 ->orWhere('code_invite',$client->ma_dinh_danh);
											 })
											 ->whereIn('moderation',['publish','request_publish','un_publish']);
		$count_location_ctv = $contents->count();


		// Nếu người xem là đại lý
		if($current->hasRole('tong_dai_ly') > 0){
			$info = $this->DailyInfo($current);
			$this->view->content = view('Location.makemoney.index',[
				'module'            =>'info_ctv',
				'client'            => $current,
				'ctv'            		=> $client,
				'quyenloi'					=> $quyenloi,
				'giaoviec'					=> $giaoviec, 
				'static'						=> $new_static, 
				'count_location_ctv'=> $count_location_ctv,          
				'revenue'           => $info['revenue'],
				'count_location'    => $info['count_location'],
				'count_ctv'         => $info['count_ctv'],
				'count_ctv_pending' => $info['count_ctv_pending'],
				'count_location_pending' => $info['count_location_pending'],
			]);
		}

		// Nếu người xem là CEO
		if($current->hasRole('ceo') > 0){
			$info = $this->CEOInfo($current);
			$this->view->content = view('Location.makemoney.index',[
				'module'            =>'info_ctv',
				'client'            => $current,
				'ctv'            		=> $client,
				'quyenloi'					=> $quyenloi,
				'giaoviec'					=> $giaoviec, 
				'static'						=> $new_static, 
				'count_location_ctv'=> $count_location_ctv,          
				'revenue'           => $info['revenue'],
				'count_location'    => $info['count_location'],
				'count_ctv'         => $info['count_ctv'],
				'count_daily'       => $info['count_daily'],
				
			]);
		}
		
		return $this->setContent();
	}

	public function getGrantCTV(Request $request,$id){
		$this->checkClient();
		$current = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_area')
										->first();
		if($current->hasRole('tong_dai_ly') < 0 && $current->hasRole('ceo') < 0){
			abort(403);
		}
		$daily_info = Daily::where('client_id',$current->id)->first();

		$client = Client::where('id',$id)
										->with('_roles')
										->with('_ctv')
										->with('_area')
										->first();

		if($client){
			$ctv_info = CTV::where('client_id',$client->id)
										 ->where('daily_id',$daily_info->id)
										 ->first();
			if(!$ctv_info){
				abort(404);
			}
			if(!empty($client->_ctv)){
				$client->_daily = $client->_ctv->_daily->_client;
				unset($client->_ctv);
			}else{
				$client->_daily = null;
				unset($client->_ctv);
			}
		}else{
			abort(404);
		}

		$giaoviec = '';
		$gv = GiaoViec::where('from_client',$client->_daily->id)
									->where('to_client',$client->id)
									->first();
		if($gv){
			$giaoviec = $gv->content;
		}



		// Nếu người xem là đại lý
		if($current->hasRole('tong_dai_ly') > 0){

			$dai_ly_area = ClientArea::where('client_id',$current->id)->pluck('district_id');
			$dai_ly_city = ClientArea::where('client_id',$current->id)->pluck('city_id');
			$city = City::whereIn('id',$dai_ly_city)->get();

			$districts = District::whereIn('id',$dai_ly_area)->get();
			$old_district = ClientArea::where('client_id',$id)->distinct('district_id')->pluck('district_id')->toArray();
			$country = ClientArea::where('client_id',$current->id)->distinct('country_id')->pluck('country_id')->first();

			$info = $this->DailyInfo($current);
			$this->view->content = view('Location.makemoney.index',[
				'module'                 =>'grant_ctv',
				'client'                 => $current,
				'ctv'                    => $client,
				'giaoviec'               => $giaoviec,          
				'revenue'                => $info['revenue'],
				'count_location'         => $info['count_location'],
				'count_ctv'              => $info['count_ctv'],
				'count_ctv_pending'      => $info['count_ctv_pending'],
				'count_location_pending' => $info['count_location_pending'],
				'districts'              => $districts,
				'country'                => $country,
				'city'                   => $city,
				'old_district'           => $old_district,
			]);
		}
		
		return $this->setContent();
	}

	public function postGrantCTV(Request $request,$id){
		$current = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_area')
										->first();
		$id = $request->id;
		$daily_info = Daily::where('client_id',$current->id)->first();

		$client = Client::where('id',$id)
										->with('_roles')
										->with('_ctv')
										->with('_area')
										->first();
		if($client){
			$ctv_info = CTV::where('client_id',$client->id)
										 ->where('daily_id',$daily_info->id)
										 ->first();
			if(!$ctv_info){
				abort(404);
			}
			if(!empty($client->_ctv)){
				$client->_daily = $client->_ctv->_daily->_client;
				unset($client->_ctv);
			}else{
				$client->_daily = null;
				unset($client->_ctv);
			}
		}else{
			abort(404);
		}

		ClientArea::where('client_id',$id)->delete();
		foreach ($request->district as $key => $value) {
			$district = District::find($value);
			$city = City::find($district->id_city);
			$client_area = new ClientArea();
			$client_area->client_id = $id;
			$client_area->district_id = $value;
			$client_area->city_id = $city->id;
			$client_area->country_id = $city->id_country;
			$client_area->save();
		}

		$giaoviec = GiaoViec::where('from_client',$current->id)
												->where('to_client',$client->id)
												->first();
		if(!$giaoviec){
			\DB::table('giaoviec')->insert([
				'from_client' => $current->id,
				'to_client'   => $client->id,
				'content'     => $request->giaoviec?$request->giaoviec:"",
				'created_at'  => Carbon::now(),
				'updated_at'  => Carbon::now()
			]);
		}else{
			\DB::table('giaoviec')->where('from_client',$current->id)
														->where('to_client',$client->id)
														->update([
															'from_client' => $current->id,
															'to_client'   => $client->id,
															'content'     => $request->giaoviec?$request->giaoviec:"",
															'updated_at'  => Carbon::now()
														]);
		}

		return redirect()->route('daily_ctv')->with(['status' => trans('Location'.DS.'makemoney.grant_ctv').' '.$client->full_name]);
	}

	public function getInfoLocation(Request $request,$id){
		$this->checkClient();
		$current = Client::where('id',Auth::guard('web_client')->user()->id)
										 ->with('_roles')
										 ->with('_area')
										 ->first();
		$content = Content::select('contents.*')
											->where('contents.id',$id)
											->whereIn('moderation',['publish','request_publish','un_publish']);
		$content = $content->with('_country')
											 ->with('_city')
											 ->with('_district')
											 ->first();

		$new_static = [];

		$static = ClientStatic::selectRaw('
										type,
										rate_revenue_ctv,
										sum(total) as sum,
										sum(total*rate_revenue_ctv/100) as revenue_ctv
								');
		$month = date('n');
		$static = $static->whereRaw('MONTH(`created_at`) = '.$month);
		$year = date('Y');
		$static = $static->whereRaw('YEAR(`created_at`) = '.$year);
		$static = $static->where('content_id',$id);
		$static = $static->groupBy('type')
										 ->get();

		foreach ($static as $key => $value) {
			$obj =  array(
					"value" => (float) $value->rate_revenue_ctv/100*$value->sum,
					"name" => trans('global.'.$value->type),
					"type" => $value->type
				);
			$new_static[$value->type] = $obj;
		}

		// Nếu người xem là CTV
		if($current->hasRole('cong_tac_vien') > 0){
			$info = $this->CTVInfo($current);
			$this->view->content = view('Location.makemoney.index',[
				'module'            =>'info_location',
				'client'            => $current,
				'content'           => $content,
				'static'						=> $new_static,           
				'revenue'           => $info['revenue'],
				'count_location'    => $info['count_location'],
				// 'count_ctv'         => $info['count_ctv'],
				// 'count_ctv_pending' => $info['count_ctv_pending'],
				// 'count_location_pending' => $info['count_location_pending'],
			]);
		}

		// Nếu người xem là đại lý
		if($current->hasRole('tong_dai_ly') > 0){
			$info = $this->DailyInfo($current);
			$this->view->content = view('Location.makemoney.index',[
				'module'            =>'info_location',
				'client'            => $current,
				'content'           => $content,
				'static'						=> $new_static,           
				'revenue'           => $info['revenue'],
				'count_location'    => $info['count_location'],
				'count_ctv'         => $info['count_ctv'],
				'count_ctv_pending' => $info['count_ctv_pending'],
				'count_location_pending' => $info['count_location_pending'],
			]);
		}

		// Nếu người xem là CEO
		if($current->hasRole('ceo') > 0){
			$info = $this->CEOInfo($current);
			$this->view->content = view('Location.makemoney.index',[
				'module'            =>'info_location',
				'client'            => $current,
				'content'           => $content,
				'static'						=> $new_static,           
				'revenue'           => $info['revenue'],
				'count_location'    => $info['count_location'],
				'count_ctv'         => $info['count_ctv'],
				'count_daily'       => $info['count_daily'],
			]);
		}
		
		return $this->setContent();
	}


	public function getInfoDaily(Request $request,$id){
		$this->checkClient();
		$current = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_area')
										->first();
		if($current->hasRole('ceo') < 0){
			abort(404);
		}
		$current = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_area')
										->first();



		$client = Client::where('id',$id)
										->with('_roles')
										->with('_area')
										->first();

		if($client){
			$daily_info = Daily::where('client_id',$client->id);
			$daily_info =	$daily_info->first();
			if(!$daily_info){
				abort(404);
			}
		}else{
			abort(404);
		}

		$quyenloi = '';
		$block = BlockText::where('machine_name','quyen_loi_va_nghia_vu_cua_tdl')->first();
		if($block){
			$lang = $request->lang?$request->lang:'vn';
			if($lang=='en'){
				$quyenloi = $block->content_en;
			}else{
				$quyenloi = $block->content_vn;
			}
		}
		$giaoviec = '';
		$gv = GiaoViec::where('from_client',$current->id)
									->where('to_client',$client->id)
									->first();
		if($gv){
			$giaoviec = $gv->content;
		}

		$new_static = [];

		$static = ClientStatic::selectRaw('
										type,
										rate_revenue_daily,
										sum(total) as sum,
										sum(total*rate_revenue_daily/100) as revenue_daily
								');
		$month = date('n');
		$static = $static->whereRaw('MONTH(`created_at`) = '.$month);
		$year = date('Y');
		$static = $static->whereRaw('YEAR(`created_at`) = '.$year);

		$static = $static->groupBy('type')
										 ->get();

		foreach ($static as $key => $value) {
			$obj =  array(
					"value" => (float) $value->rate_revenue_daily/100*$value->sum,
					"name" => trans('global.'.$value->type),
					"type" => $value->type
				);
			$new_static[$value->type] = $obj;
		}

		$count_location_daily = 0;
		$contents = Content::select('id')
											 ->where(function($query) use($daily_info,$client){
													return $query->where('daily_id',$daily_info->id);
											 })
											 ->whereIn('moderation',['publish','request_publish','un_publish']);
		$count_location_daily = $contents->count();




		// Nếu người xem là CEO
		if($current->hasRole('ceo') > 0){
			$info = $this->CEOInfo($current);
			$this->view->content = view('Location.makemoney.index',[
				'module'               =>'info_daily',
				'client'               => $current,
				'daily'                => $client,
				'quyenloi'             => $quyenloi,
				'giaoviec'             => $giaoviec, 
				'static'               => $new_static, 
				'count_location_daily' => $count_location_daily,          
				'revenue'              => $info['revenue'],
				'count_location'       => $info['count_location'],
				'count_ctv'            => $info['count_ctv'],
				'count_daily'          => $info['count_daily'],
				
			]);
		}else{
			abort(404);
		}
		
		return $this->setContent();
	}

	// CEO
	public function CEOInfo($client){
		
		$arr_return = [];
		$revenue        = 0;
		$count_location = 0;

		$static = ClientStatic::selectRaw('
										type,
										sum(total) as sum
								');
		$month = date('n');
		$static = $static->whereRaw('MONTH(`created_at`) = '.$month);
		$year = date('Y');
		$static = $static->whereRaw('YEAR(`created_at`) = '.$year);
		$static = $static->groupBy('type')
											 ->get();

		foreach ($static as $key => $value) {
			$revenue+= (float) $value->sum;
		}									 

		$contents = Content::where('active','=',1)
											 ->where('moderation','=','publish');
		$count_location = $contents->count();
		$role_ctv = ClientRole::where('machine_name','cong_tac_vien')->first();
		$role_daily = ClientRole::where('machine_name','tong_dai_ly')->first();

		$count_ctv = CTV::rightJoin('client_in_role',function($query) use ($role_ctv){
																return $query->where('client_in_role.role_id',$role_ctv->id)
																						 ->on('client_in_role.client_id','ctv.client_id');
															})->count('id');
		$count_daily = Daily::rightJoin('client_in_role',function($query) use ($role_daily){
																return $query->where('client_in_role.role_id',$role_daily->id)
																						 ->on('client_in_role.client_id','daily.client_id');
															})->count('id');

		$arr_return = [
			'revenue'           => $revenue,
			'count_location'    => $count_location,
			'count_ctv'         => $count_ctv,
			'count_daily'				=> $count_daily
		];
		return $arr_return;
	}

	public function getIndexCEO(Request $request){
		$this->checkClient();

		$client = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_area')
										->first();
		if($client->hasRole('ceo') < 0){
			return redirect()->route('makemoney');
		}

		$info = $this->CEOInfo($client);

		$this->view->content = view('Location.makemoney.index',[
			'module'            =>'ceo_makemoney',
			'client'            => $client,
			'revenue'           => $info['revenue'],
			'count_location'    => $info['count_location'],
			'count_ctv'         => $info['count_ctv'],
			'count_daily'       => $info['count_daily'],
		]);
		return $this->setContent();
	}

	public function getCEORevenue(Request $request){
		$this->checkClient();

		$client = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_area')
										->first();
		if($client->hasRole('ceo') < 0){
			return redirect()->route('makemoney');
		}
		$new_static = [];

		$static = ClientStatic::selectRaw('
										type,
										sum(total) as sum
								');
		$month = date('n');
		$static = $static->whereRaw('MONTH(`created_at`) = '.$month);
		$year = date('Y');
		$static = $static->whereRaw('YEAR(`created_at`) = '.$year);
		$static = $static->groupBy('type')
										 ->get();

		foreach ($static as $key => $value) {
			$obj =  array(
					"value" => (float) $value->sum,
					"sum" => (float) $value->sum,
					"name" => trans('global.'.$value->type),
					"type" => $value->type
				);
			$new_static[$value->type] = $obj;
		}									 

		$info = $this->CEOInfo($client);

		$this->view->content = view('Location.makemoney.index',[
			'module'            =>'ceo_revenue',
			'client'            => $client,
			'static'            => $new_static,
			'revenue'           => $info['revenue'],
			'count_location'    => $info['count_location'],
			'count_ctv'         => $info['count_ctv'],
			'count_daily'       => $info['count_daily'],
		]);
		return $this->setContent();
	}

	public function getCEOLocation(Request $request){
		$this->checkClient();

		$client = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_area')
										->first();
		if($client->hasRole('ceo') < 0){
			return redirect()->route('makemoney');
		}
		$keyword = $request->keyword?$request->keyword:'';
		

		$contents = Content::where('active','=',1)
											 ->where('moderation','=','publish');
		if (isset($keyword) && $keyword != '') {
			$contents = $contents->where(function ($query) use ($keyword) {
				return $query->where('contents.name', 'LIKE', '%' . $keyword . '%')
										 ->orWhere('contents.alias', 'LIKE', '%' . str_slug_custom($keyword) . '%');
			});
		}

		$content_list = $contents->with('_country')
														 ->with('_city')
														 ->with('_district')
														 ->paginate(30);

		$info = $this->CEOInfo($client);
		$this->view->content = view('Location.makemoney.index',[
			'module'            =>'ceo_location',
			'client'            => $client,
			'contents'          => $content_list,
			'keyword'           => $keyword,
			'revenue'           => $info['revenue'],
			'count_location'    => $info['count_location'],
			'count_ctv'         => $info['count_ctv'],
			'count_daily'       => $info['count_daily'],
		]);
		return $this->setContent();
	}

	public function getCEOCTV(Request $request){
		$this->checkClient();
		$client = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_area')
										->first();
		if($client->hasRole('ceo') < 0){
			return redirect()->route('makemoney');
		}
		$keyword = $request->keyword?$request->keyword:'';

		$role = ClientRole::where('machine_name','cong_tac_vien')->first();
		if($client){
			$ctv = Client::select(
											'clients.id',
											'clients.full_name',
											'clients.email',
											'clients.avatar',
											'client_in_role.active as role_active',
											'clients.rate_revenue'
										)
									 ->where('clients.active',1)
									 ->with('_ctv')
									 ->with('_roles');

			$ctv = $ctv->rightJoin('client_in_role',function($query) use ($role){
																return $query->where('client_in_role.role_id',$role->id)
																						 ->on('client_in_role.client_id','clients.id');
															});
			if($keyword!=''){
				$ctv = $ctv->where(function($query) use($keyword){
										return $query->where('email','like','%'.$keyword.'%')
																 ->orwhere('full_name','like','%'.$keyword.'%')
																 ->orWhere('phone','like','%'.$keyword.'%');
									 });
			}
			
			$ctv = $ctv->paginate(30);
		}else{
			$ctv = null;
		}
		$info = $this->CEOInfo($client);
		$this->view->content = view('Location.makemoney.index',[
					'module'            =>'ceo_ctv',
					'client'            => $client,
					'keyword'           => $keyword,
					'ctv'               => $ctv,
					'revenue'           => $info['revenue'],
					'count_location'    => $info['count_location'],
					'count_ctv'         => $info['count_ctv'],
					'count_daily'       => $info['count_daily'],
				]);
		return $this->setContent();
	}

	public function getCEODaily(Request $request){
		$this->checkClient();
		$client = Client::where('id',Auth::guard('web_client')->user()->id)
										->with('_roles')
										->with('_area')
										->first();
		if($client->hasRole('ceo') < 0){
			return redirect()->route('makemoney');
		}
		$keyword = $request->keyword?$request->keyword:'';

		$role = ClientRole::where('machine_name','tong_dai_ly')->first();
		if($client){
			$daily = Client::select(
											'clients.id',
											'clients.full_name',
											'clients.email',
											'clients.avatar',
											'client_in_role.active as role_active',
											'clients.rate_revenue'
										)
									 ->where('clients.active',1)
									 ->with('_roles');

			$daily = $daily->rightJoin('client_in_role',function($query) use ($role){
																return $query->where('client_in_role.role_id',$role->id)
																						 ->on('client_in_role.client_id','clients.id');
															});
			if($keyword!=''){
				$daily = $daily->where(function($query) use($keyword){
										return $query->where('email','like','%'.$keyword.'%')
																 ->orwhere('full_name','like','%'.$keyword.'%')
																 ->orWhere('phone','like','%'.$keyword.'%');
									 });
			}
			
			$daily = $daily->paginate(30);
		}else{
			$daily = null;
		}
		$info = $this->CEOInfo($client);
		$this->view->content = view('Location.makemoney.index',[
					'module'            =>'ceo_daily',
					'client'            => $client,
					'keyword'           => $keyword,
					'daily'             => $daily,
					'revenue'           => $info['revenue'],
					'count_location'    => $info['count_location'],
					'count_ctv'         => $info['count_ctv'],
					'count_daily'       => $info['count_daily'],
				]);
		return $this->setContent();
	}
}
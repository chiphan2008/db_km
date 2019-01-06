<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\Client;
use App\Models\Location\ClientGroup;
use App\Models\Location\ClientRole;
use App\Models\Location\ClientInRole;
use App\Models\Location\ClientArea;
use App\Models\Location\ClientStatic;
use App\Models\Location\CTV;
use App\Models\Location\Daily;

use App\Models\Location\GiaoViec;


use App\Models\Location\District;
use App\Models\Location\City;
use App\Models\Location\Country;
use App\Models\Location\Content;
use App\Models\Location\LikeContent;
use App\Models\Location\VoteContent;
use App\Models\Location\Notifi;
use Illuminate\Http\Request;
use App\Models\Location\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Auth;

class ClientController extends BaseController
{
	public function updateIDCTV(){
		$clients = Client::get();
		foreach ($clients as $key => $client) {
			$client->ma_dinh_danh = create_number_wallet($client->id);
			$client->save();
		}
		echo "Done";
	}
	public function getListClient(Request $request)
	{
		$per_page = \Session::has('pagination.'.\Route::currentRouteName()) ? session('pagination.'.\Route::currentRouteName()) : 10;
		$all_client = Client::with('_updated_by')
												->with('_updated_by_client') ;
		$sort = $request->sort?$request->sort:'';
		$input = $request->all();

		if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		if (isset($keyword) && $keyword != '') {

			$all_client->Where(function ($query) use ($keyword) {
				$query->where('full_name', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('email', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('phone', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('ma_dinh_danh', 'LIKE', '%' . $keyword . '%');
			});
		}

		$arr_sort = [];
		if($sort!=''){
			$listSort = explode(',',$sort);
			foreach ($listSort as $key => $value) {
				$item = explode('-',$value);
				if(isset($item[1])){
					$arr_sort[$item[0]] = $item[1];
				}
			}
		}
		if(count($arr_sort)){
			foreach ($arr_sort as $key => $value) {
				$all_client->orderBy($key,$value);
			}
		}else{
			$all_client->orderBy('id','desc');
		}

		$list_client = $all_client->paginate($per_page);

		return view('Admin.client.list', ['list_client' => $list_client, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);

	}

	public function getDetailClient(Request $request, $id, $type)
	{
		$client = Client::find($id);
		if($type == 'info')
		{
			return view('Admin.client.detail_info', ['client' => $client,'type' => $type]);
		}
		elseif ($type == 'content')
		{
			$client_content = Content::where([['created_by', '=', $id], ['type_user', '=', 0]])->with('_country')->with('_city')
				->with('_district')->with('_category_type')->orderBy('created_at','desc');
			$content_of_client = $client_content->paginate(15);
			return view('Admin.client.detail_content', ['client' => $client, 'content_of_client' => $content_of_client,'type' => $type]);
		}
		elseif ($type == 'like')
		{
			$client_content_like = LikeContent::where('id_user' , $id)->pluck('id_content')->toArray();
			$client_content = Content::whereIn('id', $client_content_like)->with('_country')->with('_city')
				->with('_district')->with('_category_type')->orderBy('created_at','desc');
			$input = $request->all();
			if (isset($input['keyword'])) {
				$keyword = $input['keyword'];
			} else {
				$keyword = '';
			}

			if (isset($keyword) && $keyword != '') {

				$client_content->Where(function ($query) use ($keyword) {
					$query->where('name', 'LIKE', '%' . $keyword . '%');
					$query->orWhere('alias', 'LIKE', '%' . $keyword . '%');
				});
			}
			$content_of_client = $client_content->paginate(15);
			return view('Admin.client.detail_content', [
				'client' => $client,
				'content_of_client' => $content_of_client,
				'type' => $type,
				'keyword' => $keyword,
			]);
		}
		elseif ($type == 'vote')
		{
			$client_content_vote = VoteContent::where('id_user' ,'=', $id)->pluck('vote_point','id_content')->toArray();
			$client_content = Content::whereIn('id', array_keys($client_content_vote))->with('_country')->with('_city')
				->with('_district')->with('_category_type')->orderBy('created_at','desc');
			$input = $request->all();
			if (isset($input['keyword'])) {
				$keyword = $input['keyword'];
			} else {
				$keyword = '';
			}

			if (isset($keyword) && $keyword != '') {

				$client_content->Where(function ($query) use ($keyword) {
					$query->where('name', 'LIKE', '%' . $keyword . '%');
					$query->orWhere('alias', 'LIKE', '%' . $keyword . '%');
				});
			}
			$content_of_client = $client_content->paginate(15);
			return view('Admin.client.detail_content', [
				'client' => $client,
				'content_of_client' => $content_of_client,
				'type' => $type,
				'keyword' => $keyword,
				'client_content_vote' => $client_content_vote,
			]);
		}
		else {

			return view('Admin.client.detail_info', ['client' => $client,'type' => $type]);
		}

	}

	public function changeStatus($id)
	{
		$client = Client::find($id);
		$status = $client->active;
		$client_name = $client->full_name;
		$client->active = ($status == 1) ? 0 : 1;
		if($client->register_invite){
			$client->active_invite = ($status == 1) ? 0 : 1;
		}
		$client->updated_by = Auth::guard('web')->user()->id;
		$client->type_user_update = 1;
		$client->updated_at = \Carbon::now();
		$client->save();
		return redirect('admin/client')->with(['status' => 'Client ' . $client_name . ' đã thay đổi trạng thái thành công ']);
	}

	public function getXoaClient($id)
	{
		$client = Client::find($id);
		$client_name = $client->full_name;
		$client->delete();
		\DB::table('client_area')->where('client_id',$id)->delete();
		\DB::table('client_in_role')->where('client_id',$id)->delete();
		\DB::table('client_in_static')->where('client_id',$id)->delete();
		\DB::table('ctv')->where('client_id',$id)->delete();
		\DB::table('daily')->where('client_id',$id)->delete();

		\DB::table('contents')
			 ->where('created_by',$id)
			 ->where('type_user',0)
			 ->update([
			 		'created_by' => 1,
			 		'type_user' => 1,
			 		'ctv_id'=>0,
			 		'daily_id'=>0,
			 		'code_invite' => ''
			 ]);
		commandSyncClient2Node($client->id);
		return redirect('admin/client')->with(['status' => 'Client ' . $client_name . ' đã xóa thành công ']);
	}

	public function getGrantClient($id){
		$client = Client::where('id',$id)
										->with('_roles')
										->first();
		$groups = ClientGroup::where('active',1)
									->with('_roles')
									->get();
		$client_role = [];
		if($client->_roles){
			foreach ($client->_roles as $key => $role) {
				$client_role[$role->group_id] = $role->id;
			}
		}
		//dd($client_role);
		return view('Admin.client.grant', ['client' => $client,'groups'=>$groups, 'client_role' => $client_role]);
	}

	public function postGrantClient(Request $request, $id){
		$client = Client::find($id);
		$client_name = $client->full_name;
		if($request->role){
			ClientInRole::where('client_id',$id)->delete();
			foreach ($request->role as $key => $value) {
				if($value!=0){
					$client_role = new ClientInRole();
					$client_role->client_id = $id;
					$client_role->role_id = $value;
					$client_role->save();

					$client->updated_by = Auth::guard('web')->user()->id;
					$client->type_user_update = 1;
					$client->updated_at = \Carbon::now();
					$client->save();
				}
			}
		}
		//dd(ClientInRole::where('client_id',$id)->get());
		return redirect('admin/client')->with(['status' => 'Client ' . $client_name . ' đã cập nhật thành công ']);
	}

	public function getListDaiLy(Request $request)
	{
		// \DB::enableQueryLog();
		$per_page = \Session::has('pagination.'.\Route::currentRouteName()) ? session('pagination.'.\Route::currentRouteName()) : 10;
		$all_client = DB::table('clients')
										->select(
											'clients.*',
											'client_in_role.active as role_active'
										);
		$sort = $request->sort?$request->sort:'';
		$input = $request->all();

		if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		if (isset($keyword) && $keyword != '') {

			$all_client->Where(function ($query) use ($keyword) {
				$query->where('full_name', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('email', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('phone', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('ma_dinh_danh', 'LIKE', '%' . $keyword . '%');
			});
		}

		$arr_sort = [];
		if($sort!=''){
			$listSort = explode(',',$sort);
			foreach ($listSort as $key => $value) {
				$item = explode('-',$value);
				if(isset($item[1])){
					$arr_sort[$item[0]] = $item[1];
				}
			}
		}
		if(count($arr_sort)){
			foreach ($arr_sort as $key => $value) {
				$all_client->orderBy($key,$value);
			}
		}else{
			$all_client->orderBy('id','desc');
		}
		$role = ClientRole::where('machine_name','tong_dai_ly')->first();

		if($role){
			$all_client = $all_client->join('client_in_role',function($query) use ($role){
				return $query->on('client_in_role.client_id','clients.id');
			})->where('client_in_role.role_id',$role->id);

			
															 
			$list_client = $all_client->paginate($per_page);

			// dd(\DB::getQueryLog());
		}else{
			$list_client = $all_client->limit(0)->get();
		}   

		return view('Admin.client.list_daily', ['list_client' => $list_client, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);
	}

	public function getListCTV(Request $request, $code)
	{
		 // \DB::enableQueryLog();
		$per_page = \Session::has('pagination.'.\Route::currentRouteName()) ? session('pagination.'.\Route::currentRouteName()) : 10;
		$all_client = DB::table('clients')
										->select(
											'clients.id',
											'clients.full_name',
											'clients.email',
											'clients.avatar',
											'client_in_role.active as role_active',
											'clients.daily_code',
											'clients.temp_daily_code',
											'clients.rate_revenue'
										);
		$sort = $request->sort?$request->sort:'';
		$input = $request->all();

		if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		if (isset($keyword) && $keyword != '') {

			$all_client->Where(function ($query) use ($keyword) {
				$query->where('full_name', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('email', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('phone', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('ma_dinh_danh', 'LIKE', '%' . $keyword . '%');
			});
		}

		$arr_sort = [];
		if($sort!=''){
			$listSort = explode(',',$sort);
			foreach ($listSort as $key => $value) {
				$item = explode('-',$value);
				if(isset($item[1])){
					$arr_sort[$item[0]] = $item[1];
				}
			}
		}
		if(count($arr_sort)){
			foreach ($arr_sort as $key => $value) {
				$all_client->orderBy($key,$value);
			}
		}else{
			$all_client->orderBy('id','desc');
		}
		$role = ClientRole::where('machine_name','cong_tac_vien')->first();

		if($role){
			$all_client = $all_client->leftJoin('client_in_role',function($query) use ($role){
																return $query->on('client_in_role.client_id','clients.id');
															})
															->where(function($query) use ($role, $code){
																return $query->where('client_in_role.role_id',$role->id)
																						 ->where('clients.daily_code',$code);
															})
															->orWhere('temp_daily_code',$code);

			$list_client = $all_client->paginate($per_page);		
		}else{
			$list_client = $all_client->limit(0)->get();
		}  

		// dd(\DB::getQueryLog()); 

		return view('Admin.client.list_ctv', ['list_client' => $list_client, 'code'=>$code , 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);
	}

	public function getArea(Request $request, $id){
		$client = Client::where('id',$id)->with('_area')->first();
		$ctv = CTV::where('client_id',$id)->first();
		$daily = Daily::where('client_id',$id)->first();
		$client_name = $client->full_name;
		if($ctv){
			$daily_client = Client::where('ma_dinh_danh',$client->daily_code)->first();
			$dai_ly_area = ClientArea::where('client_id',$daily_client->id)->pluck('district_id');

			$dai_ly_city = ClientArea::where('client_id',$daily_client->id)->pluck('city_id');
			$city = City::whereIn('id',$dai_ly_city)->get();

			$districts = District::whereIn('id',$dai_ly_area)->get();
			$old_district = ClientArea::where('client_id',$id)->distinct('district_id')->pluck('district_id')->toArray();
			$country = ClientArea::where('client_id',$daily_client->id)->distinct('country_id')->pluck('country_id')->first();
			return view('Admin.client.area_ctv', [
					'client' => $client,
					'districts' => $districts,
					'country' => $country,
					'city' => $city,
					'old_district'=>$old_district
				]);
		}

		if($daily){
			$countries = Country::get();
			$old_city = ClientArea::where('client_id',$id)->distinct('city_id')->pluck('city_id');
			$old_district = ClientArea::where('client_id',$id)->distinct('district_id')->pluck('district_id');
			$old_country = ClientArea::where('client_id',$id)->distinct('country_id')->pluck('country_id')->first();
			return view('Admin.client.area_dai_ly', ['client' => $client, 'countries'=>$countries,'old_city'=>$old_city,'old_district'=>$old_district,'old_country'=>$old_country]);
		}
	}

	public function postArea(Request $request, $id){
		$client = Client::where('id',$id)->with('_area')->first();
		$client_name = $client->full_name;

		$rules = [
			'country' => 'required',
			'city' => 'required',
			'district' => 'required',
		];
		$messages = [
			'country.required' => \Lang::get('Location/layout.country_required'),
			'city.required' => \Lang::get('Location/layout.city_required'),
			'district.required' => \Lang::get('Location/layout.district_required'),
		];

		if($client->hasRole('cong_tac_vien')>0){
			unset($rules['country']);
			unset($rules['city']);
		}

		$validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			
			if($client->hasRole('cong_tac_vien') > 0 && $request->district){
				ClientArea::where('client_id',$id)->delete();
				foreach ($request->district as $key => $value) {
					$district = District::find($value);
					$city = City::find($district->id_city);
					$client_area = new ClientArea();
					$client_area->client_id = $id;
					$client_area->district_id = $value;
					$client_area->city_id = $district->id_city;
					$client_area->country_id = $city->id_country;
					$client_area->save();

					$client->updated_by = Auth::guard('web')->user()->id;
          $client->type_user_update = 1;
          $client->updated_at = \Carbon::now();
          $client->save();
				}
				return redirect()->route('list_ctv',['code'=>$client->daily_code])->with(['status' => 'Client ' . $client_name . ' đã cập nhật khu vực thành công ']);
			}

			if($client->hasRole('tong_dai_ly') > 0 && $request->district){
				ClientArea::where('client_id',$id)->delete();
				foreach ($request->district as $key => $value) {
					$district = District::find($value);
					$city = City::find($district->id_city);
					$client_area = new ClientArea();
					$client_area->client_id = $id;
					$client_area->district_id = $value;
					$client_area->city_id = $district->id_city;
					$client_area->country_id = $city->id_country;
					$client_area->save();

					$client->updated_by = Auth::guard('web')->user()->id;
          $client->type_user_update = 1;
          $client->updated_at = \Carbon::now();
          $client->save();
				}
				// Xóa khu vực ctv ko thuộc khu vực mới của đại lý
				$daily = Daily::where('client_id',$id)->first();
				$list_ctv = Client::where('daily_code',$client->ma_dinh_danh)->pluck('id');
				ClientArea::whereIn('client_id',$list_ctv)
									->whereNotIn('district_id',$request->district)
									->delete();

				return redirect()->route('list_dai_ly')->with(['status' => 'Client ' . $client_name . ' đã cập nhật khu vực thành công ']);
			}
		}
	}


	public function findCTV(Request $request){
		$arr_return = [];
		$input = request()->all();
		if(isset($input['query']) && $input['query']!=''){
			$keyword = $input['query'];
			$role_ctv = ClientRole::where('machine_name','cong_tac_vien')->first();
			$role_daily = ClientRole::where('machine_name','tong_dai_ly')->first();
			$role_ctv_id = $role_ctv->id;
			$role_daily_id = $role_daily->id;

			$clients = Client::where('active',1)
												 ->where(function($query) use($keyword){
													return $query->where('email','like','%'.$keyword.'%')
																			 ->orwhere('full_name','like','%'.$keyword.'%')
																			 ->orWhere('phone','like','%'.$keyword.'%')
																			 ->orWhere('ma_dinh_danh', 'LIKE', '%' . $keyword . '%');
												 })
												 ->where('daily_code','')
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

	public function getAddCTV(Request $request,$code){
		$daily = Client::where('ma_dinh_danh',$code)->first();
		$dai_ly_area = ClientArea::where('client_id',$daily->id)->pluck('district_id');
		$dai_ly_city = ClientArea::where('client_id',$daily->id)->pluck('city_id');
		$city = City::whereIn('id',$dai_ly_city)->get();

		$districts = District::whereIn('id',$dai_ly_area)->get();
		$country = ClientArea::where('client_id',$daily->id)->distinct('country_id')->pluck('country_id')->first();
		return view('Admin.client.add_ctv',['code'=>$code, 'districts' => $districts,'city' => $city, 'country' => $country]);
	}

	public function postAddCTV(Request $request,$code){
		$rules = [
			'district' => 'required',
			'user'=>'required'
		];
		$messages = [
			'district.required' => \Lang::get('Location/layout.district_required'),
			'user.required' => \Lang::get('valid.user_required'),
		];
		$validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$role = ClientRole::where('machine_name','cong_tac_vien')->first();
			if($role){
				$id = $request->user;
				$code_daily = $code;
				$client = Client::find($id);
				$client->daily_code = $code_daily;
				$client->temp_daily_code = '';
				$client->rate_revenue = 50;
				$client->save();

				ClientInRole::where('client_id',$id)
										->where('role_id',$role->id)
										->delete();
				$client_role = new ClientInRole();
				$client_role->client_id = $id;
				$client_role->role_id = $role->id;
				$client_role->save();

				ClientArea::where('client_id',$id)->delete();
				foreach ($request->district as $key => $value) {
					$district = District::find($value);
					$city = City::find($district->id_city);
					$client_area = new ClientArea();
					$client_area->client_id = $id;
					$client_area->district_id = $value;
					$client_area->city_id = $district->id_city;
					$client_area->country_id = $city->id_country;
					$client_area->save();
				}



				$daily = Client::where('ma_dinh_danh',$code)->first();

				$new_ctv = new CTV();
				$new_ctv->client_id = $id;
				$daily_info = Daily::where('client_id',$daily->id)->first();
				$new_ctv->daily_id = $daily_info->id;
				$new_ctv->save();

				$client->updated_by = Auth::guard('web')->user()->id;
        $client->type_user_update = 1;
        $client->updated_at = \Carbon::now();
        $client->save();

				$notifi = new Notifi();
				$text_content_update = 'Admin'.DS.'client.add_ctv';
				$notifi->createNotifiUserByTemplate($text_content_update,$client->id,['daily'=>$daily->full_name]);

				return redirect()->route('list_ctv',['code'=>$code])->with(['status' => 'Thêm cộng tác viên '.$client->full_name.' thành công ']);
			}
		}
		
	}

	public function getRemoveCTV(Request $request, $code, $id){
		$client = Client::where('daily_code',$code)
										->where('id',$id)->first();
		$role = ClientRole::where('machine_name','cong_tac_vien')->first();

		if($client){
			$client->daily_code = '';
			$client->updated_by = Auth::guard('web')->user()->id;
      $client->type_user_update = 1;
      $client->updated_at = \Carbon::now();
      $client->save();

			ClientInRole::where('client_id',$client->id)
									->where('role_id',$role->id)
									->delete();
			GiaoViec::where('to_client',$client->id)->delete();
			ClientArea::where('client_id',$client->id)
									->delete();
			$ctv = CTV::where('client_id',$client->id)->first();
			
			Content::where('ctv_id','=',$ctv->id)
					 ->update([
						'ctv_id' => 0,
						'daily_id' => 0,
						'code_invite' => '',
						'daily_code' => ''
					 ]);

			$daily = Client::where('ma_dinh_danh',$code)->first();
			$notifi = new Notifi();
			$text_content_update = 'Admin'.DS.'client.remove_ctv';
			$notifi->createNotifiUserByTemplate($text_content_update,$client->id,['daily'=>$daily->full_name]);

			CTV::where('client_id',$client->id)->delete();
			return redirect()->route('list_ctv',['code'=>$code])->with(['status' => 'Đã xóa cộng tác viên '.$client->full_name.' thành công ']);
		}else{
			return redirect()->route('list_ctv',['code'=>$code]);
		}
	}

	public function getAcceptCTV(Request $request, $code, $id){
		$role = ClientRole::where('machine_name','cong_tac_vien')->first();
		if($role){
			$code_daily = $code;
			$client = Client::find($id);
			$client->daily_code = $code_daily;
			$client->temp_daily_code = '';
			$client->updated_by = Auth::guard('web')->user()->id;
      $client->type_user_update = 1;
      $client->updated_at = \Carbon::now();
      $client->save();

			ClientInRole::where('client_id',$id)
									->where('role_id',$role->id)
									->delete();
			$client_role = new ClientInRole();
			$client_role->client_id = $id;
			$client_role->role_id = $role->id;
			$client_role->save();

			$daily = Client::where('ma_dinh_danh',$code)->first();
			
			$new_ctv = new CTV();
			$new_ctv->client_id = $id;
			$daily_info = Daily::where('client_id',$daily->id)->first();
			$new_ctv->daily_id = $daily_info->id;
			$new_ctv->save();

			
			$notifi = new Notifi();
			$text_content_update = 'Admin'.DS.'client.accept_ctv';
			$notifi->createNotifiUserByTemplate($text_content_update,$client->id,['daily'=>$daily->full_name]);

			return redirect()->route('list_ctv',['code'=>$code])->with(['status' => 'Duyệt cộng tác viên '.$client->full_name.' thành công ']);
		}
		return redirect()->route('list_ctv',['code'=>$code]);
	}

	public function getLockCTV(Request $request, $code, $id){
		$role = ClientRole::where('machine_name','cong_tac_vien')->first();
		if($role){
			$client = Client::find($id);

			$client->updated_by = Auth::guard('web')->user()->id;
      $client->type_user_update = 1;
      $client->updated_at = \Carbon::now();
      $client->save();

			ClientInRole::where('client_id',$id)
									->where('role_id',$role->id)
									->update([
										'active' => 0
									]);

			$daily = Client::where('ma_dinh_danh',$code)->first();
			$notifi = new Notifi();
			$text_content_update = 'Admin'.DS.'client.lock_ctv';
			$notifi->createNotifiUserByTemplate($text_content_update,$client->id,['daily'=>$daily->full_name]);

			return redirect()->route('list_ctv',['code'=>$code])->with(['status' => 'Khóa cộng tác viên '.$client->full_name.' thành công ']);
		}
		return redirect()->route('list_ctv',['code'=>$code]);
	}

	public function getUnlockCTV(Request $request, $code, $id){
		$role = ClientRole::where('machine_name','cong_tac_vien')->first();
		if($role){
			$client = Client::find($id);
			$client->updated_by = Auth::guard('web')->user()->id;
      $client->type_user_update = 1;
      $client->updated_at = \Carbon::now();
      $client->save();
			ClientInRole::where('client_id',$id)
									->where('role_id',$role->id)
									->update([
										'active' => 1
									]);


			$daily = Client::where('ma_dinh_danh',$code)->first();
			$notifi = new Notifi();
			$text_content_update = 'Admin'.DS.'client.unlock_ctv';
			$notifi->createNotifiUserByTemplate($text_content_update,$client->id,['daily'=>$daily->full_name]);

			return redirect()->route('list_ctv',['code'=>$code])->with(['status' => 'Mở khóa cộng tác viên '.$client->full_name.' thành công ']);
		}
		return redirect()->route('list_ctv',['code'=>$code]);
	}

	public function getDeclineCTV(Request $request, $code, $id){
		$client = Client::where('id',$id)
										->where('temp_daily_code',$code)
										->first();
		$client->temp_daily_code = '';
		$client->updated_by = Auth::guard('web')->user()->id;
    $client->type_user_update = 1;
    $client->updated_at = \Carbon::now();
    $client->save();
		$daily = Client::where('ma_dinh_danh',$code)->first();
		$notifi = new Notifi();
		$text_content_update = 'Admin'.DS.'client.decline_ctv';
		$notifi->createNotifiUserByTemplate($text_content_update,$client->id,['daily'=>$daily->full_name]);
		return redirect()->route('list_ctv',['code'=>$code])->with(['status' => 'Đã từ chối đăng ký cộng tác viên '.$client->full_name]);
	}

	public function getAddDaiLy(Request $request){
		$groups = ClientGroup::where('active',1)->get();
		$countries = Country::get();
		return view('Admin.client.add_dai_ly',['groups' => $groups,'countries'=>$countries]);
	}

	public function postAddDaiLy(Request $request){
		$rules = [
			'country' => 'required',
			'city' => 'required',
			'district' => 'required',
			'user'=>'required'
		];
		$messages = [
			'country.required' => \Lang::get('Location/layout.country_required'),
			'city.required' => \Lang::get('Location/layout.city_required'),
			'district.required' => \Lang::get('Location/layout.district_required'),
			'user.required' => \Lang::get('valid.user_required'),
		];
		$validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$client = Client::find($request->user);
			$client->daily_code = "";
			$client->rate_revenue = 20;
			$client->updated_by = Auth::guard('web')->user()->id;
      $client->type_user_update = 1;
      $client->updated_at = \Carbon::now();
      $client->save();

			$client_name = $client->full_name;
			$role_daily = ClientRole::where('machine_name','tong_dai_ly')->first();
			if($role_daily){
				$client_role = new ClientInRole();
				$client_role->client_id = $client->id;
				$client_role->role_id = $role_daily->id;
				if($client_role->save()){
					if($request->district){
						ClientArea::where('client_id',$client->id)->delete();
						foreach ($request->district as $key => $value) {
							$district = District::find($value);
							$client_area = new ClientArea();
							$client_area->client_id = $client->id;
							$client_area->district_id = $value;
							$client_area->city_id = $district->id_city;
							$client_area->country_id = $request->country;
							$client_area->save();
						}
					}

					$new_daily = new Daily();
					$new_daily->client_id = $client->id;
					$new_daily->save();
				}
			}
			return redirect()->route('list_dai_ly')->with(['status' => 'Thêm đại lý '.$client->full_name.' thành công']);
		}
	}

	public function loadRole(Request $request){
		$html = '<option value="0">'.trans('Admin'.DS.'role.no_role').'</option>';
		$client_id = $request->client_id?$request->client_id:0;
		$client_group_id = $request->client_group_id?$request->client_group_id:0;
		if($client_group_id){
			$roles = ClientRole::where('group_id',$client_group_id)
												 ->where('active',1)
												 ->orderBy('name')
												 ->get();
			
			if($client_id){
				$clien_in_role = ClientInRole::where('client_id',$client_id)
																		 ->pluck('role_id')
																		 ->toArray();
			}else{
				$clien_in_role = [];
			}
			foreach ($roles as $key => $role) {
				if(in_array($role->id,$clien_in_role)){
					$html .= '<option selected value="'.$role->id.'">'.$role->name.'</option>';
				}else{
					$html .= '<option value="'.$role->id.'">'.$role->name.'</option>';
				}
			}
		}
		echo $html;
	}

	public function getDetailDaily(Request $request, $id,$type='info'){
		$client = Client::find($id);
		$daily = Daily::where('client_id',$id)->first();
		$client_content = $contents = Content::select('contents.*')
												 ->with('_country')
												 ->with('_city')
												 ->with('_district')
												 ->with('_category_type')
												 ->orderBy('created_at','desc')
												 ->whereIn('moderation',['publish','request_publish','un_publish'])
												 ->where('contents.daily_id',$daily->id);
		$keyword = $request->keyword?$request->keyword:'';
		if (isset($keyword) && $keyword != '') {

			$client_content->Where(function ($query) use ($keyword) {
				$query->where('name', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('alias', 'LIKE', '%' . $keyword . '%');
			});
		}
		$count_content = 0;
		$count_content = $client_content->count();
		

		if($type == 'info'){
			return view('Admin.client.detail_info_daily', [
									'client' => $client,
									 'type' => $type,
									 'keyword' => $keyword,
									 'count_content' => $count_content
								 ]);
		}elseif ($type == 'content'){
			$content_of_client = $client_content->paginate(15);
			return view('Admin.client.detail_content_daily', [
									'client' => $client,
									 'content_of_client' => $content_of_client,
									 'type' => $type,
									 'keyword' => $keyword,
									 'count_content' => $count_content
								 ]);
		}elseif ($type == 'static'){
			$month = intval(Date('m'));
			$year = intval(Date('Y'));
			$static = ClientStatic::selectRaw('type,sum(total) as sum')
														->where('daily_id',$id)
														->groupBy('type')
														->whereRaw('MONTH(`created_at`) = '.$month)
														->whereRaw('YEAR(`created_at`) = '.$year)
														->get();

			return view('Admin.client.detail_static_daily', [
									'client' => $client,
									 'type' => $type,
									 'keyword' => $keyword,
									 'count_content' => $count_content,
									 'static' => $static,
								 ]);
		}else{
			return view('Admin.client.detail_info_daily', [
									'client' => $client,
									 'type' => $type,
									 'keyword' => $keyword,
									 'count_content' => $count_content
								 ]);
		}
	}

	public function getSearchUserAddDaily(Request $request){
		$arr_return = [];
		$input = request()->all();
		if(isset($input['query']) && $input['query']!=''){
			$keyword = $input['query'];
			$role_daily = ClientRole::where('machine_name','tong_dai_ly')->first();
			$all_role_in_group = ClientRole::where('group_id',$role_daily->group_id)->pluck('id');

			$all_client_in_group = ClientInRole::whereIn('role_id',$all_role_in_group)->pluck('client_id')->toArray();

			$clients = Client::select('clients.*')
											 ->where(function($query) use ($keyword){
													return $query->where('email','like','%'.$keyword.'%')
																			 ->orwhere('full_name','like','%'.$keyword.'%')
																			 ->orWhere('phone','like','%'.$keyword.'%')
																			 ->orWhere('ma_dinh_danh','like','%'.$keyword.'%');
											 })
											 //->join('client_in_role','client_id','clients.id')
											 ->whereNotIn('id',$all_client_in_group)
											 ->where('active',1)
											 ->with('_roles')
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
	
	public function getDeleteDaiLy(Request $request, $id){
		$client = Client::find($id);
		$daily = Daily::where('client_id',$client->id)->first();
		$role_daily = ClientRole::where('machine_name','tong_dai_ly')->first();
		$ctv = CTV::where('daily_id','=',$daily->id)->count();
		if($ctv > 0)
		{
			return redirect()->route('list_dai_ly')->with(['err' => 'Đại lý ' . $client->full_name . ' không thể xóa, Vui lòng kiểm tra lại toàn bộ cộng tác viên trước khi xóa !']);
		}

		// $content = Content::where('daily_code','=',$client->ma_dinh_danh)->count();
		// if($content > 0)
		// {
		//   return redirect()->route('list_dai_ly')->with(['err' => 'Đại lý ' . $client->full_name . ' không thể xóa, Vui lòng kiểm tra lại toàn bộ content trước khi xóa !']);
		// }
		Content::where('daily_id','=',$daily->id)
					 ->update([
							'ctv_id' => 0,
							'daily_id' => 0,
							'code_invite' => '',
							'daily_code' => ''
					 ]);
		ClientInRole::where('client_id',$client->id)
								->where('role_id',$role_daily->id)
								->delete();
		GiaoViec::where('to_client',$client->id)->delete();
		Daily::where('client_id',$client->id)->delete();
		return redirect()->route('list_dai_ly')->with(['status' => 'Đại lý ' . $client->full_name . ' đã xóa thành công ']);
	}

	public function getLockDaily(Request $request, $id){
		$role = ClientRole::where('machine_name','tong_dai_ly')->first();
		if($role){
			$client = Client::find($id);
			$client->updated_by = Auth::guard('web')->user()->id;
      $client->type_user_update = 1;
      $client->updated_at = \Carbon::now();
      $client->save();
			ClientInRole::where('client_id',$id)
									->where('role_id',$role->id)
									->update([
										'active' => 0
									]);

			
			$notifi = new Notifi();
			$text_content_update = 'Admin'.DS.'client.lock_daily';
			$notifi->createNotifiUserByTemplate($text_content_update,$client->id,['daily'=>$client->full_name]);

			return redirect()->route('list_dai_ly')->with(['status' => 'Khóa đại lý '.$client->full_name.' thành công ']);
		}
		return redirect()->route('list_dai_ly');
	}

	public function getUnlockDaily(Request $request, $id){
		$role = ClientRole::where('machine_name','tong_dai_ly')->first();
		if($role){
			$client = Client::find($id);
			$client->updated_by = Auth::guard('web')->user()->id;
      $client->type_user_update = 1;
      $client->updated_at = \Carbon::now();
      $client->save();
			ClientInRole::where('client_id',$id)
									->where('role_id',$role->id)
									->update([
										'active' => 1
									]);


		 
			$notifi = new Notifi();
			$text_content_update = 'Admin'.DS.'client.unlock_daily';
			$notifi->createNotifiUserByTemplate($text_content_update,$client->id,['daily'=>$client->full_name]);

			return redirect()->route('list_dai_ly')->with(['status' => 'Mở khóa đại lý '.$client->full_name.' thành công ']);
		}
		return redirect()->route('list_dai_ly');
	}


	public function getMoveCTV(Request $request){
		$list_daily = Client::select("clients.*")
												->rightJoin('daily','daily.client_id','clients.id')
												->get();
		return view('Admin.client.move_ctv', ['list_daily' => $list_daily]);
	}

	public function getCTV(Request $request,$daily_client_id=0){
		$daily = Daily::where('client_id',$daily_client_id)->first();
		$ctv = Client::select('clients.*')
								 ->leftJoin('ctv','ctv.client_id','clients.id')
								 ->where('ctv.daily_id',$daily->id)
								 ->get();
		$html = '';
		if($ctv){
			foreach ($ctv as $key => $value) {
				$html .= '<option data-avatar="'.$value->avatar.'" value="'.$value->id.'">'.$value->full_name.'</option>';
			}
		}
		echo $html;
	}

	public function postMoveCTV(Request $request){
		$daily_id = $request->to_daily;
		$ctv_id = $request->ctv?$request->ctv:[];

		$client_daily = Client::find($daily_id);
		$daily = Daily::where('client_id',$daily_id)->first();
		foreach ($ctv_id as $key => $id) {
			$client_ctv = Client::find($id);
			$ctv = CTV::where('client_id',$id)->first();
			if($ctv){
				// Chuyển đại lý ID
				$ctv->daily_id = $daily->id;
				$ctv->save();
				// Chuyển code đại lý
				$client_ctv->daily_code = $client_daily->ma_dinh_danh;
				$client_ctv->save();

				$client_ctv->updated_by = Auth::guard('web')->user()->id;
	      $client_ctv->type_user_update = 1;
	      $client_ctv->updated_at = \Carbon::now();
	      $client_ctv->save();

				//Change đại lý ID content
				if($request->move_content){
					\DB::table('contents')
						->where('ctv_id',$ctv->id)
						->orWhere('code_invite',$client_ctv->ma_dinh_danh)
						->update([
							'daily_id' => $daily->id
						]);
				}else{
					\DB::table('contents')
						->where('ctv_id',$ctv->id)
						->orWhere('code_invite',$client_ctv->ma_dinh_danh)
						->update([
							'daily_id' => 0
						]);
				}
				
			}
		}
		return redirect()->route('move_ctv')->with(['status' => 'Chuyển cộng tác viên cho tổng đại lý '.$client_daily->full_name.' thành công']);
	}

	public function getDetailCTV(Request $request, $id,$type='info'){
		$client = Client::find($id);
		$ctv = CTV::where('client_id',$id)->first();

		$client_content = $contents = Content::select('contents.*')
												 ->with('_country')
												 ->with('_city')
												 ->with('_district')
												 ->with('_category_type')
												 ->orderBy('created_at','desc')
												 ->whereIn('moderation',['publish','request_publish','un_publish'])
												 ->where('contents.ctv_id',$ctv->id);
		$keyword = $request->keyword?$request->keyword:'';
		if (isset($keyword) && $keyword != '') {

			$client_content->Where(function ($query) use ($keyword) {
				$query->where('name', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('alias', 'LIKE', '%' . $keyword . '%');
			});
		}
		$count_content = 0;
		$count_content = $client_content->count();
		

		if($type == 'info'){
			return view('Admin.client.detail_info_ctv', [
									'client' => $client,
									 'type' => $type,
									 'keyword' => $keyword,
									 'count_content' => $count_content
								 ]);
		}elseif ($type == 'content'){
			$content_of_client = $client_content->paginate(15);
			return view('Admin.client.detail_content_ctv', [
									'client' => $client,
									 'content_of_client' => $content_of_client,
									 'type' => $type,
									 'keyword' => $keyword,
									 'count_content' => $count_content
								 ]);
		}elseif ($type == 'static'){
			$month = intval(Date('m'));
			$year = intval(Date('Y'));
			$static = ClientStatic::selectRaw('type,sum(total) as sum')
														->where('ctv_id',$id)
														->groupBy('type')
														->whereRaw('MONTH(`created_at`) = '.$month)
														->whereRaw('YEAR(`created_at`) = '.$year)
														->get();

			return view('Admin.client.detail_static_ctv', [
									'client' => $client,
									 'type' => $type,
									 'keyword' => $keyword,
									 'count_content' => $count_content,
									 'static' => $static,
								 ]);
		}else{
			return view('Admin.client.detail_info_ctv', [
									'client' => $client,
									 'type' => $type,
									 'keyword' => $keyword,
									 'count_content' => $count_content
								 ]);
		}
	}


	public function getSetupClient(Request $request){
		//Update rate CTV
		$arr_ctv = CTV::pluck('client_id');
		Client::whereIn('id',$arr_ctv)->update([
			'rate_revenue' => 50
		]);

		//Update rate Daily
		$arr_ctv = Daily::pluck('client_id');
		Client::whereIn('id',$arr_ctv)->update([
			'rate_revenue' => 20
		]);

		echo "Done";
	}

	public function getChangeRateClient($id,$rate){
		$client = Client::find($id);
		if($client){
			$client->rate_revenue = $rate;
			
			$client->updated_by = Auth::guard('web')->user()->id;
      $client->type_user_update = 1;
      $client->updated_at = \Carbon::now();
      $client->save();

			if($client->hasRole('cong_tac_vien')>0){
				return  redirect()->route('list_ctv',['code'=>$client->daily_code])->with(['status' => 'Cộng tác viên ' . $client->full_name . ' đã cập nhật tỷ lệ doanh thu thành công ']);
			}
			
			if($client->hasRole('tong_dai_ly')>0){
				return  redirect()->route('list_dai_ly')->with(['status' => 'Đại lý ' . $client->full_name . ' đã cập nhật tỷ lệ doanh thu thành công ']);
			}
		}
	}
}

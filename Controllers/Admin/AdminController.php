<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\AnalyticsCustom;
use Spatie\Analytics\Period;
use Carbon\Carbon;
use Validator;
use App\Models\Booking\HomeBooking;
use App\Models\Location\Country;
use App\Models\Location\City;
use App\Models\Location\District;
use App\Models\Location\Content;
use App\Models\Location\Client;
use App\Models\Location\NotifiAdmin;

use App\Models\Location\CTV;
use App\Models\Location\Daily;
use App\Models\Location\ClientArea;
use App\Models\Location\ClientStatic;
use App\Models\Location\ClientRole;
use App\Models\Location\ClientInRole;
use App\Models\Location\ClientInStatic;




class AdminController extends BaseController
{

	public function getIndex() {
		return view('Admin.layout_admin.master_admin');
	}

	public function logout(Request $request)
	{
		$this->guard()->logout();

		session()->flush();

		session()->regenerate();

		return redirect('/login');
	}

	protected function guard()
	{
		return Auth::guard('web');
	}

	public function sessionPagination(Request $request)
	{
		session()->put('pagination.'.$request->path, $request->pagination);
		echo true;
	}

	public function getAnalytic(Request $request){
		$totalCurrentUser = AnalyticsCustom::getTotalCurrentUser();

		$totalUserRegister = Client::whereRaw('DATE(created_at) = CURDATE()')->count();

		$totalCreatedContent = Content::whereRaw('DATE(created_at) = CURDATE()')->count();

		$totalCTVRegister = CTV::whereRaw('DATE(created_at) = CURDATE()')->count();


		// dd($totalUserRegister, $totalCreatedContent);

		$endDate = Carbon::now()->subDays(1);
		$startDate = Carbon::now()->subDays(7);
		$users = AnalyticsCustom::getUser(Period::create($startDate,$endDate));
		$dataUser = [];
		$totalUser = 0;
		foreach ($users as $key => $value) {
			$totalUser+=$value['user'];
			$dataUser[$key]['date'] = date('Y-m-d',strtotime($value['date']->toDateTimeString()));
			$dataUser[$key]['user'] = $value['user'];
		}

		$sessions = AnalyticsCustom::getSession(Period::create($startDate,$endDate));
		$dataSession = [];
		$totalSession = 0;
		foreach ($sessions as $key => $value) {
			$totalSession+=$value['session'];
			$dataSession[$key]['date'] = date('Y-m-d',strtotime($value['date']->toDateTimeString()));
			$dataSession[$key]['session'] = $value['session'];
		}

		$bounces = AnalyticsCustom::getBounce(Period::create($startDate,$endDate));
		$dataBounce = [];
		$totalBounce = 0;
		foreach ($bounces as $key => $value) {
			$totalBounce+=round($value['bounce'],0);
			$dataBounce[$key]['date'] = date('Y-m-d',strtotime($value['date']->toDateTimeString()));
			$dataBounce[$key]['bounce'] = round($value['bounce'],0);
		}
		$totalBounce = round($totalBounce/count($bounces),2);

		$durations = AnalyticsCustom::getDuration(Period::create($startDate,$endDate));
		$dataDuration = [];
		$totalDuration = 0;
		foreach ($durations as $key => $value) {
			$totalDuration+=round($value['duration'],0);
			$dataDuration[$key]['date'] = date('Y-m-d',strtotime($value['date']->toDateTimeString()));
			$dataDuration[$key]['duration'] = round($value['duration'],0);
		}
		$totalDuration = round($totalDuration/count($durations),0);
		$h_duration = (int) floor($totalDuration/3600);
		$m_duration = (int) floor(($totalDuration%3600)/60);
		$s_duration = (int) floor(($totalDuration%3600)%60);
		if($m_duration<10) $m_duration = '0'.$m_duration;
		if($s_duration<10) $s_duration = '0'.$s_duration;
		$totalDuration = $h_duration.':'.$m_duration.':'.$s_duration;


		$userByType = AnalyticsCustom::getUserByType(Period::create($startDate,$endDate));
		$userByBrowse = AnalyticsCustom::getUserByBrowse(Period::create($startDate,$endDate));
		$userByDevice = AnalyticsCustom::getUserByDevice(Period::create($startDate,$endDate));
		$userByCity = AnalyticsCustom::getUserByCity(Period::create($startDate,$endDate));
		$userBySocial = AnalyticsCustom::getUserBySocial(Period::create($startDate,$endDate));

		$dataUserByCity = [];
		$totalUserByCity = 0;
		$totalSessionByCity = 0;
		$totalBounceByCity = 0;
		$totalDurationByCity = 0;
		foreach ($userByCity as $key => $value) {
			$totalUserByCity     += $value['user'];
			$totalSessionByCity  += $value['session'];
			$totalBounceByCity   += $value['bounce']*$value['user'];
			$totalDurationByCity += $value['duration']*$value['user'];
			$dataUserByCity[$key]['city']     = $value['city'];
			$dataUserByCity[$key]['user']     = $value['user'];
			$dataUserByCity[$key]['session']  = $value['session'];
			$dataUserByCity[$key]['bounce']   = round($value['bounce'],2);

			$h_duration_city = (int) floor($value['duration']/3600);
			$m_duration_city = (int) floor(($value['duration']%3600)/60);
			$s_duration_city = (int) floor(($value['duration']%3600)%60);
			if($m_duration_city<10) $m_duration_city = '0'.$m_duration_city;
			if($s_duration_city<10) $s_duration_city = '0'.$s_duration_city;
			$dataUserByCity[$key]['duration'] = $h_duration_city.':'.$m_duration_city.':'.$s_duration_city;
		}

		$totalBounceByCity = round($totalBounceByCity/$totalUserByCity,2);
		$totalDurationByCity = round($totalDurationByCity/$totalUserByCity,0);
		$h_duration_city = (int) floor($totalDurationByCity/3600);
		$m_duration_city = (int) floor(($totalDurationByCity%3600)/60);
		$s_duration_city = (int) floor(($totalDurationByCity%3600)%60);
		if($m_duration_city<10) $m_duration_city = '0'.$m_duration_city;
		if($s_duration_city<10) $s_duration_city = '0'.$s_duration_city;
		$totalDurationByCity = $h_duration_city.':'.$m_duration_city.':'.$s_duration_city;


		return view('Admin.admin.analytic',[
			'totalCurrentUser'    => $totalCurrentUser,
			'dataUser'            => $dataUser,
			'totalUser'           => $totalUser,
			'dataSession'         => $dataSession,
			'totalSession'        => $totalSession,
			'dataBounce'          => $dataBounce,
			'totalBounce'         => $totalBounce,
			'dataDuration'        => $dataDuration,
			'totalDuration'       => $totalDuration,
			'userByType'          => $userByType,
			'userByBrowse'        => $userByBrowse,
			'userByDevice'        => $userByDevice,
			'userBySocial'        => $userBySocial,
			'dataUserByCity'      =>  $dataUserByCity,
			'totalUserByCity'     =>  $totalUserByCity,
			'totalSessionByCity'  =>  $totalSessionByCity,
			'totalBounceByCity'   =>  $totalBounceByCity,
			'totalDurationByCity' =>  $totalDurationByCity,
			'totalUserRegister'   => $totalUserRegister,
			'totalCreatedContent' => $totalCreatedContent,
			'totalCTVRegister' 		=> $totalCTVRegister,
		]);
	}

	public function getHomePage(){
		$list_home_booking = HomeBooking::orderBy('weight')->get();
		return view('Admin.admin.homepage_booking',[
				'list_home_booking'=>$list_home_booking,
		]);
	}

	public function getAddHomePage(){
		$list_country = Country::get();
		return view('Admin.admin.add_homepage_booking',[
				'list_country'=>$list_country,
		]);
	}

	public function postAddHomePage(Request $request){
		$rules = [
			'city' => 'required|unique:booking.home_booking,city_id',
			'image'=>'required'
		];
		$messages = [
			'city.required' => trans('valid.city_required'),
			'city.unique' => trans('valid.city_unique'),
			'image.required' => trans('valid.image_required')
		];
		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$id_user = Auth::guard('web')->user()->id;
			$home_booking = new HomeBooking();
			$city = City::find($request->city);
			if(!$city){
				return redirect()->back()->with(['status'=>'Thành phố không tồn tại'])->withInput();
			}
			$home_booking->country_id  = $request->country;
			$home_booking->city_id     = $request->city;
			$home_booking->name        = $request->name?$request->name:$city->name;
			$home_booking->alias       = $city->alias;
			$home_booking->weight      = $request->weight;
			$home_booking->active      = $request->has('active');
			if($request->file('image')) {
				$path = public_path().'/upload/home_booking/';
				$file = $request->file('image');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name =time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
					$file->move($path,$name);
				$home_booking->image = '/upload/home_booking/'.$name;
			}
			$home_booking->created_by = $id_user;
			$home_booking->updated_by = $id_user;
			$home_booking->created_at = Carbon::now();
			$home_booking->updated_at = Carbon::now();
			if($home_booking->save()){
				return redirect()->route('list_home_booking')->with(['status' => 'Đã thêm <a href="' . route('update_home_booking',['id' => $home_booking->id]) . '">' . $home_booking->name . '</a> thành công</a>']);
			}

		}
	}

	public function getUpdateHomePage($id){
		$home_booking = HomeBooking::find($id);
		if(!$home_booking){
			abort(404);
		}
		$list_country = Country::get();
		$list_city_home_booking = HomeBooking::where('id','!=',$id)->pluck('city_id');

		$list_city = City::where('id_country', '=', $home_booking->country_id)
								->whereNotIn('id',$list_city_home_booking)
								->get();

		return view('Admin.admin.update_homepage_booking',[
				'list_country'=>$list_country,
				'list_city'		=>$list_city,
				'home_booking'=>$home_booking,
		]);
	}

	public function postUpdateHomePage(Request $request,$id){
		$rules = [
			'city' => 'required'
		];
		$messages = [
			'city.required' => trans('valid.city_required')
		];
		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$id_user = Auth::guard('web')->user()->id;
			$home_booking = HomeBooking::find($id);
			if(!$home_booking){
				abort(404);
			}
			$city = City::find($request->city);
			if(!$city){
				return redirect()->back()->with(['status'=>'Thành phố không tồn tại'])->withInput();
			}
			$home_booking->country_id  = $request->country;
			$home_booking->city_id     = $request->city;
			$home_booking->name        = $request->name?$request->name:$city->name;
			$home_booking->alias       = $city->alias;
			$home_booking->active      = $request->has('active');
			if($request->file('image')) {
				$path = public_path().'/upload/home_booking/';
				$file = $request->file('image');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name =time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
				$file->move($path,$name);
				$home_booking->image = '/upload/home_booking/'.$name;
			}
			$home_booking->updated_by = $id_user;
			$home_booking->updated_at = Carbon::now();
			if($home_booking->update()){
				return redirect()->route('list_home_booking')->with(['status' => 'Đã cập nhật <a href="' . route('update_home_booking',['id' => $home_booking->id]) . '">' . $home_booking->name . '</a> thành công</a>']);
			}

		}
	}

	public function getDeleteHomePage($id){
		$home_booking = HomeBooking::find($id);
		if(!$home_booking){
			abort(404);
		}
		if($home_booking->delete()){
			return redirect()->route('list_home_booking')->with(['status' => 'Đã xóa <a href="' . route('update_home_booking',['id' => $home_booking->id]) . '">' . $home_booking->name . '</a> thành công</a>']);
		}
	}

	public function ajaxLocation(Request $request) {
		$value = $request->value;
		$list_city_home_booking = HomeBooking::pluck('city_id');
		$city = City::where('id_country', '=', $value)
								->whereNotIn('id',$list_city_home_booking)
								->pluck('name', 'id');
		echo '<option value="">-- '.trans('global.city').' --</option>';
		foreach ($city as $key => $value) {
				echo '<option value="' . $key . '">' . $value . '</option>';
		}
	}

	public function getChangeWeightHomePage($id,$weight){
		$home_booking = HomeBooking::find($id);
		if($home_booking){
			$home_booking->weight = $weight;
			$home_booking->save();
			return redirect()->route('list_home_booking')->with(['status' => 'Đã cập nhật thứ tự <a href="' . route('update_home_booking',['id' => $home_booking->id]) . '">' . $home_booking->name . '</a> thành công</a>']);
		}
	}

	public function getListAPI(){
		$app = app();
		$routes = $app->routes->getRoutes();
		return view('Admin.admin.list_api',compact('routes'));
	}

	public function getSearchContent(Request $request){
		$arr_return = [];
		$input = request()->all();
		if(isset($input['query']) && $input['query']!=''){
      $keyword = $input['query'];

			$contents = Content::select('contents.*')
											 ->where('name','like','%'.$keyword.'%')
											 ->with('_city')
											 ->with('_district')
											 ->limit(15)
											 ->get();
			foreach ($contents as $key => $content) {
				$arr_tmp = [];
				$arr_tmp['id'] = $content->id;
				$arr_tmp['text'] = $content->name;
				$arr_tmp['address'] = $content->address.', '.$content->_district->name.', '.$content->_city->name;
				if(check_exist(asset($content->avatar))){
					$arr_tmp['avatar'] = url($content->avatar);
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

			$clients = Client::select('clients.*')
											 ->where('email','like','%'.$keyword.'%')
											 ->orwhere('full_name','like','%'.$keyword.'%')
											 ->orWhere('phone','like','%'.$keyword.'%')
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

	public function getNotifications($offset){
      $notifications = NotifiAdmin::getNotifi()->limit(10)->offset($offset)->get();
      foreach ($notifications as $notification){
          $notification['time'] = date('d-m-Y H:i:s', strtotime($notification->created_at));
      }
      return response()->json(['results'=>$notifications]);
  }

  public function getApp(){
  	return view('Admin.admin.app');
  }

  public function postApp(Request $request){
  	// dd(
  	// 	$request->file('app')->getClientOriginalExtension(),
  	// 	$request->file('app')->getClientMimeType(),
  	// 	$request->file('app')->guessClientExtension()
  	// 	);
  	$rules = [
			'app_file' => 'required|file|max:100000|mimetypes:application/vnd.android.package-archive,application/zip',
		];
		$messages = [
			'app_file.required' => trans('valid.file_required'),
			'app_file.mimetypes' => trans('valid.file_mimetypes',["type"=>".apk"]),
			'app_file.max' => trans('valid.file_size',["size"=>"100MB"])
		];
		$validator = Validator::make($request->all(), $rules, $messages);
		// dd($validator);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$path = public_path().'/upload/app/';
			$file = $request->file('app_file');
			if(!\File::exists($path)) {
				\File::makeDirectory($path, $mode = 0777, true, true);
			}
			$name ='app-kingmap'.'.' . $file->getClientOriginalExtension();
			$file->move($path,$name);
			return redirect()->back()->with(['status' => 'Upload file app thành công']);
		}
  }
  public function updateDataMakeMoney(){
  	// $role_ctv = ClientRole::where('machine_name','cong_tac_vien')->first();
   //  $role_daily = ClientRole::where('machine_name','tong_dai_ly')->first();

   //  $client_in_role_daily = ClientInRole::where('role_id',$role_daily->id)->get();

   //  foreach ($client_in_role_daily as $key => $value) {
   //  	$client = client::find($value->client_id);
   //  	$new_daily = new Daily();
   //  	$new_daily->client_id = $client->id;
   //  	$new_daily->save();
   //  }

   //  $client_in_role_ctv = ClientInRole::where('role_id',$role_ctv->id)->get();
   //  //dd($client_in_role_ctv->toArray());
   //  foreach ($client_in_role_ctv as $key => $value) {
   //  	$client_ctv = Client::find($value->client_id);
   //  	if($client_ctv && isset($client_ctv->daily_code)){
   //  		$client_daily = Client::where('ma_dinh_danh',$client_ctv->daily_code)->first();
	  //   	if($client_daily){
	  //   		$daily = Daily::where('client_id',$client_daily->id)->first();
	  //   		$new_ctv = new CTV();
		 //    	$new_ctv->client_id = $client_ctv->id;
		 //    	$new_ctv->daily_id = $daily->id;
		 //    	$new_ctv->save();

		 //    	$statics = ClientStatic::where('ctv_id',$client_ctv->id)->get();
		 //    	foreach ($statics as $key => $static) {
		 //    		$static->ctv_id = $new_ctv->id;
		 //    		$static->daily_id = $new_ctv->daily_id;
		 //    		$static->save();
		 //    	}

		 //    	$contents = Content::where('code_invite',$client_ctv->ma_dinh_danh)
		 //    										 ->where('type_user',0)
		 //    										 ->get();
		 //    	foreach ($contents as $key => $content) {
		 //    		$ctv_content = CTV::where('client_id',$client_ctv->id)->first();
		 //    		$content->ctv_id = $ctv_content->id;
		 //    		$daily_content_client = Client::where('ma_dinh_danh',$content->daily_code)->first();
		 //    		if($daily_content_client){
		 //    			$daily_content = Daily::where('client_id',$daily_content_client->id)->first();
			//     		$content->daily_id = $daily_content->id;
		 //    		}
		 //    		$content->save();
		 //    	}
	  //   	}
   //  	}
   //  }

  	// Add Đại Lý
  	// set_time_limit(0);
  	// $role_daily = ClientRole::where('machine_name','tong_dai_ly')->first();
  	// $client_daily = Client::find(16);
   //  if($role_daily){
   //    $client_role = new ClientInRole();
   //    $client_role->client_id = $client_daily->id;
   //    $client_role->role_id = $role_daily->id;
   //    $cities = City::where('id_country',1)->pluck('id');
  	// 	$districts = District::whereIn('id_city',$cities)->pluck('id');
   //    if($client_role->save()){
   //      if($districts){
   //        ClientArea::where('client_id',$client_daily->id)->delete();
   //        foreach ($districts as $key => $value) {
   //          $district = District::find($value);
   //          $city = City::find($district->id_city);
   //          $client_area = new ClientArea();
   //          $client_area->client_id = $client_daily->id;
   //          $client_area->district_id = $value;
   //          $client_area->city_id = $district->id_city;
   //          $client_area->country_id = $city->id_country;
   //          $client_area->save();
   //        }
   //      }

   //      $new_daily = new Daily();
   //      $new_daily->client_id = $client_daily->id;
   //      $new_daily->save();
   //    }
   //  }

  	// $clients = Client::where('active',1)
  	//                  ->where('id','!=',16)
  	//                  ->get();
  	// $cities = City::where('id_country',1)->pluck('id');
  	// $districts = District::whereIn('id_city',$cities)->pluck('id');

  	// foreach ($clients as $key => $client) {
  	// 	$role = ClientRole::where('machine_name','cong_tac_vien')->first();
  	// 	$code = '2137381721';
   //    if($role){
   //    	$id = $client->id;
   //      $code_daily = $code;
   //      $client->daily_code = $code_daily;
   //      $client->temp_daily_code = '';
   //      $client->save();

   //      ClientInRole::where('client_id',$id)
   //                  ->where('role_id',$role->id)
   //                  ->delete();
   //      $client_role = new ClientInRole();
   //      $client_role->client_id = $id;
   //      $client_role->role_id = $role->id;
   //      $client_role->save();

   //      ClientArea::where('client_id',$id)->delete();
   //      foreach ($districts as $key => $value) {
   //        $district = District::find($value);
   //        $city = City::find($district->id_city);
   //        $client_area = new ClientArea();
   //        $client_area->client_id = $id;
   //        $client_area->district_id = $value;
   //        $client_area->city_id = $district->id_city;
   //        $client_area->country_id = $city->id_country;
   //        $client_area->save();
   //      }

   //      $daily = Client::where('ma_dinh_danh',$code)->first();

   //      $new_ctv = new CTV();
   //      $new_ctv->client_id = $id;
   //      $daily_info = Daily::where('client_id',$daily->id)->first();
   //      $new_ctv->daily_id = $daily_info->id;
   //      $new_ctv->save();
   //    }
  	// }

  	set_time_limit(0);
  	$all_ctv = CTV::get();
  	foreach ($all_ctv as $key => $ctv) {
  		$client_ctv = Client::find($ctv->client_id);
  		$contents = Content::where('code_invite',$client_ctv->ma_dinh_danh)->get();
  		foreach ($contents as $key2 => $content) {
  			$content->ctv_id = $ctv->id;
  			$content->daily_id = $ctv->daily_id;
  			$content->save();
  		}
  	}
    echo "DOne";
  }

  public function migrateDataMakeMoney(Request $request){
  	set_time_limit(0);
  	$daily_client = Client::find($request->daily);
  	$arr_ctv_client_id = $request->ctv?explode(',', $request->ctv):$request->ctv;
  	$arr_district = $request->district?explode(',', $request->district):$request->district;
  	$role_ctv = ClientRole::where('machine_name','cong_tac_vien')->first();
    $role_daily = ClientRole::where('machine_name','tong_dai_ly')->first();
	  if($daily_client && $arr_district){
	  	$daily_client->daily_code = "";
	  	$daily_client->save();
	  	// Xoa thong tin cu
	  	ClientInRole::where('client_id',$daily_client->id)->delete();
	  	CTV::where('client_id',$daily_client->id)->delete();
	  	Daily::where('client_id',$daily_client->id)->delete();
	  	ClientArea::where('client_id',$daily_client->id)->delete();

	  	// Tao thong tin moi
	  	$new_daily = new Daily();
	  	$new_daily->client_id = $daily_client->id;
	  	$new_daily->save();

	  	// Add role daily
	  	$client_in_role_daily = new ClientInRole();
	  	$client_in_role_daily->role_id = $role_daily->id;
	  	$client_in_role_daily->client_id = $daily_client->id;
	  	$client_in_role_daily->save();

	  	// Add area cho daily moi
	  	foreach ($arr_district as $key => $id) {
	  		$district = District::find($id);
        $city = City::find($district->id_city);
        $client_area = new ClientArea();
        $client_area->client_id = $daily_client->id;
        $client_area->district_id = $district->id;
        $client_area->city_id = $district->id_city;
        $client_area->country_id = $city->id_country;
        $client_area->save();
	  	}

	  	//CTV cũ
	  	$arr_old_ctv_client_id = Client::where('daily_code',$daily_client->ma_dinh_danh)->pluck('id');
	  	foreach ($arr_old_ctv_client_id as $key => $client_id) {
	  		$ctv_client = Client::find($client_id);
	  		$ctv_client->daily_code = $daily_client->ma_dinh_danh;
	  		$ctv_client->temp_daily_code = '';
	  		$ctv_client->save();

	  		// Xóa thông tin cũ client
	  		ClientInRole::where('client_id',$ctv_client->id)->delete();
		  	CTV::where('client_id',$ctv_client->id)->delete();
		  	Daily::where('client_id',$ctv_client->id)->delete();
		  	ClientArea::where('client_id',$ctv_client->id)->delete();

		  	// Tao thong tin moi
		  	$new_ctv = new CTV();
		  	$new_ctv->client_id = $ctv_client->id;
		  	$new_ctv->daily_id = $new_daily->id;
		  	$new_ctv->save();

		  	// Add role ctv
		  	$client_in_role_ctv = new ClientInRole();
		  	$client_in_role_ctv->role_id = $role_ctv->id;
		  	$client_in_role_ctv->client_id = $ctv_client->id;
		  	$client_in_role_ctv->save();

		  	// Add area cho daily moi
		  	foreach ($arr_district as $key => $id) {
		  		$district = District::find($id);
	        $city = City::find($district->id_city);
	        $client_area = new ClientArea();
	        $client_area->client_id = $ctv_client->id;
	        $client_area->district_id = $district->id;
	        $client_area->city_id = $district->id_city;
	        $client_area->country_id = $city->id_country;
	        $client_area->save();
		  	}


		  	$contents = Content::where('code_invite',$ctv_client->ma_dinh_danh)
		    										 ->where('type_user',0)
		    										 ->get();

	    	foreach ($contents as $key => $content) {
	    		$content->ctv_id = $new_ctv->id;
	    		$content->daily_id = $new_daily->id;
	    		$content->save();
	    	}
	  	}
	  	
	  	//CTV mới
	  	foreach ($arr_ctv_client_id as $key => $client_id) {
	  		$ctv_client = Client::find($client_id);
	  		$ctv_client->daily_code = $daily_client->ma_dinh_danh;
	  		$ctv_client->temp_daily_code = '';
	  		$ctv_client->save();

	  		// Xóa thông tin cũ client
	  		ClientInRole::where('client_id',$ctv_client->id)->delete();
		  	CTV::where('client_id',$ctv_client->id)->delete();
		  	Daily::where('client_id',$ctv_client->id)->delete();
		  	ClientArea::where('client_id',$ctv_client->id)->delete();

		  	// Tao thong tin moi
		  	$new_ctv = new CTV();
		  	$new_ctv->client_id = $ctv_client->id;
		  	$new_ctv->daily_id = $new_daily->id;
		  	$new_ctv->save();

		  	// Add role ctv
		  	$client_in_role_ctv = new ClientInRole();
		  	$client_in_role_ctv->role_id = $role_ctv->id;
		  	$client_in_role_ctv->client_id = $ctv_client->id;
		  	$client_in_role_ctv->save();

		  	// Add area cho daily moi
		  	foreach ($arr_district as $key => $id) {
		  		$district = District::find($id);
	        $city = City::find($district->id_city);
	        $client_area = new ClientArea();
	        $client_area->client_id = $ctv_client->id;
	        $client_area->district_id = $district->id;
	        $client_area->city_id = $district->id_city;
	        $client_area->country_id = $city->id_country;
	        $client_area->save();
		  	}


		  	$contents = Content::where('code_invite',$ctv_client->ma_dinh_danh)
		    										 ->where('type_user',0)
		    										 ->get();

	    	foreach ($contents as $key => $content) {
	    		$content->ctv_id = $new_ctv->id;
	    		$content->daily_id = $new_daily->id;
	    		$content->save();
	    	}
	  	}
	  }

	  echo "Done";
  }
}

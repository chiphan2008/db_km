<?php
namespace App\Http\Controllers\API;

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
	public function postStatic(Request $request){
		try{
			$data = [];
			$data['total'] = 0;
			$data['revenue'] = 0;
			$data['count_location'] = 0;
			$data['static'] = [];
			$data['area'] = [];

			$static = ClientStatic::selectRaw('
										type,
										rate_revenue_daily,
										rate_revenue_ctv,
										sum(total) as sum,
										sum(total*rate_revenue_ctv/100) as revenue_ctv,
										sum(total*rate_revenue_daily/100) as revenue_daily
								');


			if($request->month){
				$month = $request->month;
				$static = $static->whereRaw('MONTH(`created_at`) = '.$month);
			}

			if($request->year){
				$year = $request->year;
				$static = $static->whereRaw('YEAR(`created_at`) = '.$year);
			}

			$rate = 0;

			if($request->ctv_id){
				$ctv = Client::where('id',$request->ctv_id)->with('_area')->first();

				$ctv_info = CTV::where('client_id',$request->ctv_id)->first();

				if($ctv){
					$contents = Content::select('id')
														 ->where(function($query) use($ctv_info,$ctv){
														 		return $query->where('ctv_id',$ctv_info->id)
														 								 ->orWhere('code_invite',$ctv->ma_dinh_danh);
														 })
														 ->whereIn('moderation',['publish','request_publish','un_publish']);
					if($request->content_id){
						$contents = $contents->where('id',$request->content_id);
					}
					$contents = $contents->count();
					$rate = ($ctv->rate_revenue/100);
					$data['area'] = $ctv->_area;
				}else{
					$contents = 0;
				}
				$static = $static->where(function($query) use($ctv_info,$ctv){
														 		return $query->where('ctv_id',$ctv_info->id)
														 								 ->orWhere('code',$ctv->ma_dinh_danh);
														 });
			}

			if($request->daily_id){
				
				$daily = Client::where('id',$request->daily_id)->with('_area')->first();

				$daily_info = Daily::where('client_id',$request->daily_id)->first();

				if($daily){
					$role = ClientRole::where('machine_name','ceo')->first();
					if($role){
						$ceo = Client::leftJoin('client_in_role','clients.id','client_in_role.client_id')
											 	 ->where('client_in_role.role_id',$role->id)
											 	 ->first();
						$data['ceo'] = $ceo;
					}else{
						$data['ceo'] = null;
					}
					

					$data['count_ctv'] = CTV::where('daily_id',$daily_info->id)->count('id');

					$contents = Content::select('id')->where('daily_id',$daily_info->id)->whereIn('moderation',['publish','request_publish','un_publish']);
					if($request->content_id){
						$contents = $contents->where('id',$request->content_id);
					}
					$contents = $contents->count();
					$rate = ($daily->rate_revenue/100);
					$data['area'] = $daily->_area;

					$data['count_location_pending'] = Content::where('daily_id',$daily_info->id)
																									 ->where('moderation','request_publish')
																									 ->where('type_user',0)
																									 ->count();
					$data['count_ctv_pending'] = Client::where('temp_daily_code',$daily->ma_dinh_danh)
											 											 ->where('active',1)
											 											 ->count();
				}else{
					$contents = 0;
				}
				$static = $static->where('daily_id',$daily_info->id);
			}

			if($request->ceo_id){
				$ceo = Client::find($request->ceo_id);
				if($ceo){
					$data['count_ctv'] = CTV::count('client_id');
					$role = ClientRole::where('machine_name','tong_dai_ly')->first();
					$arr_daily = [];
					if($role){
						$arr_daily = Daily::pluck('id');
					}
					$data['count_daily'] = count($arr_daily);
					$contents = Content::where('active','=',1)
														 ->where('moderation','=','publish');
					if($request->content_id){
						$contents = $contents->where('id',$request->content_id);
					}
					$contents = $contents->count();
					$rate = (100/100);
					$data['area'] = $ceo->_area;

				}else{
					$contents = 0;
				}
			}

			if($request->content_id){
				$static = $static->where('content_id',$request->content_id);
				$contents = 1;
			}
			//return $this->response($contents,200);
			$data['count_location'] = $contents;

			// $skip = $request->skip?$request->skip:0;
			// $limit = $request->limit?$request->limit:20;
			// $static = $static->limit($limit)
			// 							   ->skip($skip);
										 
			$static = $static->groupBy('type')
											 ->get();

			foreach ($static as $key => $value) {
				$rate = 1;
				if($request->ctv_id){
					$rate = $value->rate_revenue_ctv/100;
				}
				if($request->daily_id){
					$rate = $value->rate_revenue_daily/100;
				}
				$obj = (Object) array(
					"value" => (float) $rate*$value->sum,
					"name" => trans('global.'.$value->type),
					"type" => $value->type
				);
				$data['static'][$key] = $obj;
				$data['total']+= (float) $value->sum;
				

				if($request->ctv_id){
					$data['revenue']+= (float) $value->revenue_ctv;
				}

				if($request->daily_id){
					$data['revenue']+= (float) $value->revenue_daily;
				}


			}
			// $data['revenue'] = $rate*$data['total'];
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postStaticList(Request $request){
		try{
			$data = [];
			$static = ClientStatic::select("client_static.*");
														// ->with('_content')
														// ->with('_transaction');

			if($request->month){
				$month = $request->month;
				$static = $static->whereRaw('MONTH(`created_at`) = '.$month);
			}

			if($request->year){
				$year = $request->year;
				$static = $static->whereRaw('YEAR(`created_at`) = '.$year);
			}

			if($request->ctv_id){
				$ctv = CTV::where('client_id',$request->ctv_id)->first();
				$static = $static->where('ctv_id',$ctv->id);
			}

			if($request->daily_id){
				$daily = Daily::where('client_id',$request->daily_id)->first();
				$static = $static->where('daily_id',$daily->id);
			}

			if($request->type){
				$static = $static->where('type',$request->type);
			}

			if($request->country){
				$static = $static->where('country_id',$request->country);
			}

			if($request->city){
				$static = $static->where('city_id',$request->city);
			}

			if($request->district){
				$static = $static->where('district_id',$request->district);
			}

			if($request->content_id){
				$static = $static->where('content_id',$request->content_id);
			}

			$skip = $request->skip?$request->skip:0;
			$limit = $request->limit?$request->limit:20;
			$static = $static->limit($limit)
										 ->skip($skip);

			$static = $static->get();

			if($static){
				$arr_static = [];
				foreach ($static as $key => $value) {
					$arr_static[$value->type][] = $value;
				}

				foreach ($arr_static as $key => $value) {
					$obj = (Object) array(
						"value" => $value,
						"name" => trans('global.'.$key),
						"type" => $key,
						'count_'.$key => count($value)
					);
					$data['static'][] = $obj;
				}
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public  function postSearchCTV(Request $request){
		try{
			$daily_id = $request->daily_id?$request->daily_id:0;
			$keyword = $request->keyword?$request->keyword:'';
			$data = [];

				$daily = Client::where('id',$daily_id)
											 ->where('active',1)
											 ->first();
				if($daily){
					$ctv = Client::where('daily_code',$daily->ma_dinh_danh)
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
					$skip = $request->skip?$request->skip:0;
					$limit = $request->limit?$request->limit:20;
					$ctv = $ctv->limit($limit)
											->skip($skip);
					$ctv = $ctv->get();
					if($ctv){
						
						$data = $ctv->toArray();
						
						foreach ($data as $key => $value) {
							$data[$key]['role_active'] = 0;
							if($data[$key]['_roles']){
								$data[$key]['role_active'] = $data[$key]['_roles'][0]['active'];
								unset($data[$key]['_roles']);
							}

							$data[$key]['_daily'] = [];

							if(!empty($data[$key]['_ctv'])){
								$data[$key]['_daily'] = $data[$key]['_ctv']['_daily']['_client'];
							}
							unset($data[$key]['_ctv']);

							$data[$key]['count_location'] = 0;
							$ctv_info = CTV::where('client_id',$data[$key]['id'])->first();
							$contents = Content::select('id')->where('ctv_id',$ctv_info->id)->whereIn('moderation',['publish','request_publish','un_publish']);
							$contents = $contents->count();
							if($contents){
								$data[$key]['count_location'] = $contents;
							}
						}
					}
				}

			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public  function postFindDaily(Request $request){
		try{
			$keyword = $request->keyword?$request->keyword:'';
			$data = [];
			$role = ClientRole::where('machine_name','tong_dai_ly')->first();
			if($role){
				$daily = Client::where('clients.active',1)
											 // ->with('_area')
											 ->with('_roles')
											 ->join('client_in_role','client_in_role.client_id','clients.id')
											 ->where('client_in_role.role_id',$role->id);
				if($keyword!=''){
					$daily = $daily->where(function($query) use($keyword){
										 	return $query->where('email','like','%'.$keyword.'%')
																	 ->orwhere('full_name','like','%'.$keyword.'%')
																 	 ->orWhere('phone','like','%'.$keyword.'%');
										 });
				}
				$skip = $request->skip?$request->skip:0;
				$limit = $request->limit?$request->limit:20;
				$daily = $daily->limit($limit)
											 ->skip($skip);
				$daily = $daily->get();
				if($daily){
					$data = $daily->toArray();
					foreach ($data as $key => $value) {
						$data[$key]['role_active'] = 0;
						if($data[$key]['_roles']){
							$data[$key]['role_active'] = $data[$key]['_roles'][0]['active'];
							unset($data[$key]['_roles']);
						}
						$area = ClientArea::select('district_id','city_id','country_id')->where('client_id',$value['id'])->get();
						$data[$key]['_area'] = [];
						if($area){
							$tmp['district'] = [];
							$tmp['city']     = [];
							$tmp['country']  = [];
							foreach ($area as $key2 => $value) {
								$tmp['district'][] = $value->district_id;
								$tmp['city'][]     = $value->city_id;
								$tmp['country'][]  = $value->country_id;
							}
							$tmp['district'] = array_values(array_unique($tmp['district']));
							$tmp['city']     = array_values(array_unique($tmp['city']));
							$tmp['country']  = array_values(array_unique($tmp['country']));
							$data[$key]['_area'] = $tmp;
						}
						$data[$key]['count_location'] = 0;
						$daily_info = Daily::where('client_id',$data[$key]['id'])->first();

						if(!$daily_info){
							$new_daily = new Daily();
		          $new_daily->client_id = $data[$key]['id'];
		          $new_daily->save();
						}else{
							$contents = Content::select('id')->where('daily_id',$daily_info->id)->whereIn('moderation',['publish','request_publish','un_publish']);
							$contents = $contents->count();
							if($contents){
								$data[$key]['count_location'] = $contents;
							}
						}
						
					}
				}
			}
			

			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public  function postSearchDaily(Request $request){
		try{
			$country = $request->country?$request->country:0;
			$city = $request->city?$request->city:0;
			$district = $request->district?$request->district:0;
			$data = [];
			$role = ClientRole::where('machine_name','tong_dai_ly')->first();
			if($role){
				$daily = Client::select('clients.*')
											 ->distinct('clients.id')
											 ->join('client_area','client_area.client_id','clients.id')
											 ->join('client_in_role','client_in_role.client_id','clients.id')
											 ->where('client_in_role.role_id',$role->id)
											 ->with('_area');
				if($country){
					$daily = $daily->where('country_id',$country);
				}
				if($city){
					$daily = $daily->where('city_id',$city);
				}
				if($district){
					$daily = $daily->where('district_id',$district);
				}

				$skip = $request->skip?$request->skip:0;
				$limit = $request->limit?$request->limit:20;
				$daily = $daily->limit($limit)
										 		 ->skip($skip);
				$daily = $daily->get();

				if($daily){
					$data = $daily->toArray();
				}
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postSearchContent(Request $request){
		try{
			// \DB::enableQueryLog();
			$daily_id = $request->daily_id?$request->daily_id:0;

			$ctv_id = $request->ctv_id?$request->ctv_id:0;

			$keyword = $request->keyword?$request->keyword:'';
			$data = [];

			$daily = Daily::where('client_id',$daily_id)
										->first();

			$ctv = CTV::where('client_id',$ctv_id)
								->first();

			$client_daily = Client::find($daily_id);
			$client_ctv = Client::find($ctv_id);

			$contents = Content::select(
															'contents.id',
															'contents.name',
															'contents.tag',
															'contents.address',
															'contents.lat',
															'contents.lat as latitude',
															'contents.lng',
															'contents.lng as longitude',
															'contents.vote',
															'contents.like',
															'contents.alias',
															'contents.avatar',
															\DB::Raw("REPLACE(`contents`.`avatar`,'img_content','img_content_thumbnail') as thumb"),
															'contents.country',
															'contents.city',
															'contents.district',
															'contents.code_invite',
															'contents.daily_code',
															'contents.daily_id',
															'contents.ctv_id',
															'contents.moderation'
													)
												 ->with('_country')
												 ->with('_city')
												 ->with('_district')
												 ->where('type_user',0);
				if($request->moderation){
					$contents = $contents->where('moderation',$request->moderation);
				}else{
					$contents = $contents->whereIn('moderation',['publish','request_publish','un_publish']);
				}
				if (isset($keyword) && $keyword != '') {
					$contents->where(function ($query) use ($keyword) {
						return $query->where('contents.name', 'LIKE', '%' . $keyword . '%')
												 ->orWhere('contents.alias', 'LIKE', '%' . str_slug_custom($keyword) . '%');
					});
				}
				if($daily_id==0 && $ctv_id==0){
					$a = 0;
				}else{
					if($daily){
						$contents = $contents->where(function ($query) use ($daily, $client_daily) {
							$query->where('contents.daily_id',"=",$daily->id)
										->orWhere('contents.daily_code',"=",$client_daily->ma_dinh_danh);
						});
					}else{
						if($ctv){
							$contents = $contents->where(function ($query) use ($ctv, $client_ctv) {
								$query->where('contents.ctv_id',"=",$ctv->id)
											->orWhere('contents.code_invite',"=",$client_ctv->ma_dinh_danh);
							});
						}else{
							$contents = $contents->where('contents.ctv_id',"<",0);
						}
					}
				}
				
				$skip = $request->skip?$request->skip:0;
				$limit = $request->limit?$request->limit:20;
				$contents = $contents->limit($limit)
										 		 		 ->skip($skip);
				$contents = $contents->get();
				// dd(\DB::getQueryLog());
			if($contents){
				$data = $contents->toArray();
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postAreaCTV(Request $request){
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
		      'district' => 'required',
		    ];
		    $messages = [
		      'district.required' => \Lang::get('Location/layout.district_required'),
		    ];
		    $validator = Validator::make($request->all(), $rules, $messages);
		    if ($validator->fails()) {
					$e = new \Exception($validator->errors()->first(),400);
					return $this->error($e);
				}else{
					$id = $request->id;
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
					return $this->response(trans('global.update_area_ctv_success'),200);
				}
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postAcceptCTVOld(Request $request){
		try{
			$daily_id = $request->daily_id?$request->daily_id:0;
			$ctv_id = $request->ctv_id?$request->ctv_id:0;
			$role = ClientRole::where('machine_name','cong_tac_vien')->first();
	    if($role){
	      $ctv = Client::find($ctv_id);
	      $daily = Client::find($daily_id);
	      if($ctv){
	      	if($daily){
	      		$ctv->daily_code = $daily->ma_dinh_danh;
			      $ctv->temp_daily_code = '';
			      $ctv->save();

			      ClientInRole::where('client_id',$id)
		                  ->where('role_id',$role->id)
		                  ->delete();
			      $client_role = new ClientInRole();
			      $client_role->client_id = $id;
			      $client_role->role_id = $role->id;
			      $client_role->save();

			      $notifi = new Notifi();
			      $text_content_update = 'Admin'.DS.'client.accept_ctv';
			      $notifi->createNotifiUserByTemplate('Admin'.DS.'client.accept_ctv',$ctv->id,['daily'=>$daily->full_name]);
			      return $this->response(trans('Admin'.DS.'client.accept_ctv_api'),200);
	      	}else{
	      		$e = new \Exception(trans('valid.not_found',['object'=>'Đại lý']),400);
  					return $this->error($e);
	      	}
	      }else{
	      	$e = new \Exception(trans('valid.not_found',['object'=>'Cộng tác viên']),400);
  				return $this->error($e);
	      }
	    }else{
	    	$e = new \Exception(trans('valid.not_found',['object'=>'Role']),400);
  			return $this->error($e);
	    }
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postDeclineCTVOld(Request $request){
		try{
			$daily_id = $request->daily_id?$request->daily_id:0;
			$ctv_id = $request->ctv_id?$request->ctv_id:0;

      $ctv = Client::find($ctv_id);
      $daily = Client::find($daily_id);
      if($ctv){
      	if($daily){
			    $ctv->temp_daily_code = '';
			    $ctv->save();

			    $notifi = new Notifi();
			    $text_content_update = 'Admin'.DS.'client.decline_ctv';
			    $notifi->createNotifiUserByTemplate('Admin'.DS.'client.decline_ctv',$ctv->id,['daily'=>$daily->full_name]);
		      return $this->response(trans('Admin'.DS.'client.decline_ctv_api'),200);
      	}else{
      		$e = new \Exception(trans('valid.not_found',['object'=>'Đại lý']),400);
					return $this->error($e);
      	}
      }else{
      	$e = new \Exception(trans('valid.not_found',['object'=>'Cộng tác viên']),400);
				return $this->error($e);
      }
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postAcceptCTV(Request $request){
		try{
			$daily_id = $request->daily_id?$request->daily_id:0;
			$ctv_id = $request->ctv_id?$request->ctv_id:[];
			$role = ClientRole::where('machine_name','cong_tac_vien')->first();
	    if($role){
	      $ctvs = Client::whereIn('id',$ctv_id)->get();
	      $daily = Client::find($daily_id);
      	if($daily){
      		foreach ($ctvs as $key => $ctv) {
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
			      $notifi->createNotifiUserByTemplate('Admin'.DS.'client.accept_ctv',$ctv->id,['daily'=>$daily->full_name]);
      		}
		      return $this->response(trans('Admin'.DS.'client.accept_ctv_api'),200);
      	}else{
      		$e = new \Exception(trans('valid.not_found',['object'=>'Đại lý']),400);
					return $this->error($e);
      	}

	    }else{
	    	$e = new \Exception(trans('valid.not_found',['object'=>'Role']),400);
  			return $this->error($e);
	    }
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postDeclineCTV(Request $request){
		try{
			$daily_id = $request->daily_id?$request->daily_id:0;
			$ctv_id = $request->ctv_id?$request->ctv_id:[];

      $ctvs = Client::whereIn('id',$ctv_id)->get();
      $daily = Client::find($daily_id);
      if($daily){
      	foreach ($ctvs as $key => $ctv) {
			    $ctv->temp_daily_code = '';
			    $ctv->save();

			    $notifi = new Notifi();
			    $text_content_update = 'Admin'.DS.'client.decline_ctv';
			    $notifi->createNotifiUserByTemplate('Admin'.DS.'client.decline_ctv',$ctv->id,['daily'=>$daily->full_name]);
		    }
	      return $this->response(trans('Admin'.DS.'client.decline_ctv_api'),200);
    	}else{
    		$e = new \Exception(trans('valid.not_found',['object'=>'Đại lý']),400);
				return $this->error($e);
    	}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postLockCTV(Request $request){
		try{
			$daily_id = $request->daily_id?$request->daily_id:0;
			$ctv_id = $request->ctv_id?$request->ctv_id:[];

      $ctvs = Client::whereIn('id',$ctv_id)->get();
      $daily = Client::find($daily_id);
      $role = ClientRole::where('machine_name','cong_tac_vien')->first();
      if($role){
      	if($daily){
	      	foreach ($ctvs as $key => $ctv) {
			      ClientInRole::where('client_id',$ctv->id)
			                  ->where('role_id',$role->id)
			                  ->update([
			                    'active' => 0
			                  ]);

				    $notifi = new Notifi();
				    $text_content_update = 'Admin'.DS.'client.lock_ctv';
				    $notifi->createNotifiUserByTemplate('Admin'.DS.'client.lock_ctv',$ctv->id,['daily'=>$daily->full_name]);
			    }
		      return $this->response(trans('Admin'.DS.'client.lock_ctv_api'),200);
	    	}else{
	    		$e = new \Exception(trans('valid.not_found',['object'=>'Đại lý']),400);
					return $this->error($e);
	    	}
			}else{
	    	$e = new \Exception(trans('valid.not_found',['object'=>'Role']),400);
  			return $this->error($e);
	    }

		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postUnlockCTV(Request $request){
		try{
			$daily_id = $request->daily_id?$request->daily_id:0;
			$ctv_id = $request->ctv_id?$request->ctv_id:[];

      $ctvs = Client::whereIn('id',$ctv_id)->get();
      $daily = Client::find($daily_id);
      $role = ClientRole::where('machine_name','cong_tac_vien')->first();
      if($role){
      	if($daily){
	      	foreach ($ctvs as $key => $ctv) {
			      ClientInRole::where('client_id',$ctv->id)
			                  ->where('role_id',$role->id)
			                  ->update([
			                    'active' => 1
			                  ]);

				    $notifi = new Notifi();
				    $text_content_update = 'Admin'.DS.'client.unlock_ctv';
				    $notifi->createNotifiUserByTemplate('Admin'.DS.'client.unlock_ctv',$ctv->id,['daily'=>$daily->full_name]);
			    }
		      return $this->response(trans('Admin'.DS.'client.unlock_ctv_api'),200);
	    	}else{
	    		$e = new \Exception(trans('valid.not_found',['object'=>'Đại lý']),400);
					return $this->error($e);
	    	}
			}else{
	    	$e = new \Exception(trans('valid.not_found',['object'=>'Role']),400);
  			return $this->error($e);
	    }
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public  function postSearchCTVPending(Request $request){
		try{
			$daily_id = $request->daily_id?$request->daily_id:0;
			$data = [];
			$daily = Client::where('id',$daily_id)
										 ->where('active',1)
										 ->first();
			if($daily){
				$ctv = Client::where('temp_daily_code',$daily->ma_dinh_danh)
										 ->where('active',1);
				$ctv = $ctv->get();
				if($ctv){
					$data = $ctv->toArray();
				}
			}

			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postRemoveCTV(Request $request){
		try{
			$daily_id = $request->daily_id?$request->daily_id:0;
			$ctv_id = $request->ctv_id?$request->ctv_id:0;
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

		      return $this->response(trans('Admin'.DS.'client.remove_ctv_api'),200);
      	}else{
      		$e = new \Exception(trans('valid.not_found',['object'=>'Đại lý']),400);
					return $this->error($e);
      	}
      }else{
      	$e = new \Exception(trans('valid.not_found',['object'=>'Cộng tác viên']),400);
				return $this->error($e);
      }
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postPublishContent(Request $request){
		try{
			$content_id = $request->content_id?$request->content_id:[];
			$daily_id = $request->daily_id?$request->daily_id:0;
			$daily = Client::find($daily_id);
			if($daily){
				foreach ($content_id as $key => $id) {
					$content = Content::where('id',$id)->where('type_user',0)->first();
					if($content){
						if($content->daily_code == $daily->ma_dinh_danh){
							$content->moderation = 'publish';
							$content->active = 1;
							$content->save();
							$notifi = new Notifi();
							$text_content_update = 'Admin'.DS.'client.publish_content';
							$notifi->createNotifiUserByTemplate('Admin'.DS.'client.publish_content',$content->created_by,['content'=>$content->name]);
						}
					}
				}
				return $this->response(trans('Admin'.DS.'client.publish_content_api'),200);
			}else{
    		$e = new \Exception(trans('valid.not_found',['object'=>'Đại lý']),400);
				return $this->error($e);
    	}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postRejectContent(Request $request){
		try{
			$content_id = $request->content_id?$request->content_id:[];
			$daily_id = $request->daily_id?$request->daily_id:0;
			$daily = Client::find($daily_id);
			if($daily){
				foreach ($content_id as $key => $id) {
					$content = Content::where('id',$id)->where('type_user',0)->first();
					if($content){
						if($content->daily_code == $daily->ma_dinh_danh){
							$content->moderation = 'reject_publish';
							$content->active = 0;
							$content->save();
							$notifi = new Notifi();
							$text_content_update = 'Admin'.DS.'client.reject_content';
							$notifi->createNotifiUserByTemplate('Admin'.DS.'client.reject_content',$content->created_by,['content'=>$content->name]);
						}
					}
				}
				return $this->response(trans('Admin'.DS.'client.reject_content_api'),200);
			}else{
    		$e = new \Exception(trans('valid.not_found',['object'=>'Đại lý']),400);
				return $this->error($e);
    	}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postAreaDaily(Request $request){
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
		      'district' => 'required',
		    ];
		    $messages = [
		      'district.required' => \Lang::get('Location/layout.district_required'),
		    ];
		    $validator = Validator::make($request->all(), $rules, $messages);
		    if ($validator->fails()) {
					$e = new \Exception($validator->errors()->first(),400);
					return $this->error($e);
				}else{
					$id = $request->id;
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
	        // Xóa khu vực ctv ko thuộc khu vực mới của đại lý
	        $client = Client::where('id',$id)->with('_area')->first();
	        $list_ctv = Client::where('daily_code',$client->ma_dinh_danh)->pluck('id');
	        ClientArea::whereIn('client_id',$list_ctv)
	                  ->whereNotIn('district_id',$request->district)
	                  ->delete();
					return $this->response(trans('global.update_area_daily_success'),200);
				}
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postDistrict(Request $request){
		try{
			$arr_city = $request->city?$request->city:[];
			$districts = District::whereIn('id_city', $arr_city)->get();
	    $cities = City::whereIn('id',$arr_city)->get();
	  	$data = [];
	    foreach ($cities as $key1 => $city) {
	        $temp = [];
	        $temp['city'] = $city;
	        $temp['districts'] = [];
	        foreach ($districts as $key2 => $value) {
	            if($value->id_city == $city->id){
	              $temp['districts'][] = $value;
	            }
	        }
	        $data[] = $temp;
	    }
	    return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
		
	}

	public function getGiaoViec(Request $request,$to_client=0){
		$data = [];
		$data = GiaoViec::where('to_client',$to_client)
										->first();
		return $this->response($data,200);
	}

	public function postGiaoViec(Request $request){
		try{
			$rules = [
				'from_client' => 'required',
				'to_client'   => 'required',
				// 'content'     => 'required',
			];
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$data = [];
				$giaoviec = GiaoViec::where('from_client',$request->from_client)
														->where('to_client',$request->to_client)
														->first();
				if(!$giaoviec){
					\DB::table('giaoviec')->insert([
						'from_client' => $request->from_client,
						'to_client'   => $request->to_client,
						'content'     => $request->content?$request->content:"",
						'created_at'  => Carbon::now(),
						'updated_at'  => Carbon::now()
					]);
				}else{
					\DB::table('giaoviec')->where('from_client',$request->from_client)
																->where('to_client',$request->to_client)
																->update([
																	'from_client' => $request->from_client,
																	'to_client'   => $request->to_client,
																	'content'     => $request->content?$request->content:"",
																	'updated_at'  => Carbon::now()
																]);
				}

				$data = GiaoViec::where('from_client',$request->from_client)
														->where('to_client',$request->to_client)
														->first();
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postAddCTV(Request $request){
		try{
			$rules = [
				'daily_id' => 'required',
				'ctv_id'   => 'required'
			];
			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$daily_id = $request->daily_id;
				$ctv_id = $request->ctv_id;
				$client_daily = Client::find($daily_id);
				$daily = Daily::where('client_id',$daily_id)->first();
				$role_ctv = ClientRole::where('machine_name','cong_tac_vien')->first();
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

						//Save Role
						ClientInRole::where('client_id',$client_ctv->id)
												->where('role_id',$role_ctv->id)
												->delete();
						$client_in_role = new ClientInRole();
						$client_in_role->role_id = $role_ctv->id;
						$client_in_role->client_id = $client_ctv->id;
						$client_in_role->save();

						//Change đại lý ID content
						\DB::table('contents')
								->where('ctv_id',$ctv->id)
								->update([
									'daily_id' => 0
								]);
					}else{
						// Tạo mới ctv
						$ctv = new CTV();
						$ctv->client_id = $client_ctv->id;
						$ctv->daily_id = $daily->id;
						$ctv->save();

						// Chuyển code đại lý
						$client_ctv->daily_code = $client_daily->ma_dinh_danh;
						$client_ctv->rate_revenue = 50;
						$client_ctv->save();

						//Save Role
						ClientInRole::where('client_id',$client_ctv->id)
												->where('role_id',$role_ctv->id)
												->delete();
						$client_in_role = new ClientInRole();
						$client_in_role->role_id = $role_ctv->id;
						$client_in_role->client_id = $client_ctv->id;
						$client_in_role->save();

						//Change đại lý ID content
						\DB::table('contents')
						->where('code_invite',$client_ctv->ma_dinh_danh)
						->update([
							'daily_id' => 0,
							'ctv_id' => $ctv->id
						]);
					}
				}
				return $this->response(trans('global.add_ctv_success'),200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}


	public function postFindClient(Request $request){
		try{
			$daily_id = $request->daily_id?$request->daily_id:0;
			$keyword = $request->keyword?$request->keyword:'';
			$data = [];

			$client_daily = Client::where('id',$daily_id)
										 ->where('active',1)
										 ->first();
			$daily = Daily::where('client_id',$daily_id)->first();

			if($client_daily && $daily){
				$ctv_added = CTV::where('daily_id',$daily->id)
				                   ->pluck('client_id');

				$daily_added = Daily::pluck('client_id');

				$ceo_added = [];
				$role_ceo = ClientRole::where('machine_name','ceo')->first();
				if($role_ceo){
					$ceo_added = ClientInRole::where('role_id',$role_ceo->id)->pluck('client_id');
				}

				$clients = Client::where('clients.active',1)
												 ->whereNotIn('id',$ctv_added)
												 ->whereNotIn('id',$ceo_added)
												 ->whereNotIn('id',$daily_added);
				if($keyword!=''){
					$clients = $clients->where(function($query) use($keyword){
										 	return $query->where('email','like','%'.$keyword.'%')
																	 ->orwhere('full_name','like','%'.$keyword.'%')
																 	 ->orWhere('phone','like','%'.$keyword.'%');
										 });
				}
				$skip = $request->skip?$request->skip:0;
				$limit = $request->limit?$request->limit:20;
				$clients = $clients->limit($limit)
													 ->skip($skip);
				$clients = $clients->get();
				if($clients){
					$clients = $clients->toArray();
					foreach ($clients as $key => $client) {
						$ctv = CTV::where('client_id',$client['id'])->first();
						$clients[$key]['daily'] = null;
						if($ctv){
							$clients[$key]['daily'] = Client::leftJoin('daily','daily.client_id','clients.id')
																							->where('daily.id',$ctv->daily_id)
																							->first();
						}
					}
				}

				$data = $clients;
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postFindClientAddDaily(Request $request){
		try{
			$keyword = $request->keyword?$request->keyword:'';
			$data = [];
			$role_daily = ClientRole::where('machine_name','tong_dai_ly')->first();
			$all_role_in_group = ClientRole::where('group_id',$role_daily->group_id)->pluck('id');

			$all_client_in_group = ClientInRole::whereIn('role_id',$all_role_in_group)->pluck('client_id')->toArray();

      $clients = Client::select('clients.*');
      if($keyword != ''){
      $clients = $clients->where(function($query) use ($keyword){
                          return $query->where('email','like','%'.$keyword.'%')
                                       ->orwhere('full_name','like','%'.$keyword.'%')
                                       ->orWhere('phone','like','%'.$keyword.'%')
                                       ->orWhere('ma_dinh_danh','like','%'.$keyword.'%');
                       	});
      }
                       

      $clients = $clients->whereNotIn('id',$all_client_in_group)
                       	 ->where('active',1);
      $skip = $request->skip?$request->skip:0;
			$limit = $request->limit?$request->limit:20;
			$clients = $clients->limit($limit)
												 ->skip($skip);
			$clients = $clients->get();
			if($clients){
				$data = $clients->toArray();
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postAddDaily(Request $request){
		try{
			$rules = [
      	'client_id'=>'required'
	    ];
	    $messages = [
	      'client_id.required' => \Lang::get('valid.user_required'),
	    ];
	    $validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$client = Client::find($request->client_id);
	      $client->daily_code = "";
	      $client->rate_revenue = 20;
	      $client->save();

	      $client_name = $client->full_name;
	      $role_daily = ClientRole::where('machine_name','tong_dai_ly')->first();
	      if($role_daily){
		      $client_role = new ClientInRole();
	        $client_role->client_id = $client->id;
	        $client_role->role_id = $role_daily->id;
					$client_role->save();

					$new_daily = new Daily();
          $new_daily->client_id = $client->id;
          $new_daily->save();

					return $this->response(trans('global.add_daily_success'),200);
				}
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postFindDailyCTV(Request $request){
		try{

			$keyword = $request->keyword?$request->keyword:'';
			$data = [];

			$ctv_added = CTV::pluck('client_id');

			$daily_added = Daily::pluck('client_id');

			$ceo_added = [];
			$role_ceo = ClientRole::where('machine_name','ceo')->first();
			if($role_ceo){
				$ceo_added = ClientInRole::where('role_id',$role_ceo->id)->pluck('client_id');
			}

			$clients = Client::where('clients.active',1)
											 ->where(function($query) use ($ctv_added, $daily_added){
											 	return $query->whereIn('id',$ctv_added)
											 							 ->orWhereIn('id',$daily_added);
											 })
											 ->whereNotIn('id',$ceo_added);
			if($keyword!=''){
				$clients = $clients->where(function($query) use($keyword){
									 	return $query->where('email','like','%'.$keyword.'%')
																 ->orwhere('full_name','like','%'.$keyword.'%')
															 	 ->orWhere('phone','like','%'.$keyword.'%');
									 });
			}
			$skip = $request->skip?$request->skip:0;
			$limit = $request->limit?$request->limit:20;
			$clients = $clients->limit($limit)
												 ->skip($skip);
			$clients = $clients->get();
			if($clients){
				$clients = $clients->toArray();
				foreach ($clients as $key => $client) {
					$ctv = CTV::where('client_id',$client['id'])->first();
					if($ctv){
						$clients[$key]['role'] = 'ctv';
					}
					$daily = Daily::where('client_id',$client['id'])->first();
					if($daily){
						$clients[$key]['role'] = 'daily';
					}
				}
			}

			$data = $clients;
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postChangeRate(Request $request){
		try{
			$rules = [
      	'client_id'=>'required',
      	'rate'=>'required'
	    ];
	    $messages = [
	      'client_id.required' => \Lang::get('valid.user_required'),
	      'rate.required' => \Lang::get('valid.rate_required'),
	    ];
	    $validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$client = Client::find($request->client_id);
	      $client->rate_revenue = $request->rate;
	      $client->save();
	      return $this->response(trans('global.change_rate_success'),200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public  function postFindCTV(Request $request){
		try{
			$keyword = $request->keyword?$request->keyword:'';
			$data = [];
			$ctv = Client::select('clients.*')
									 ->rightJoin('ctv','ctv.client_id','clients.id')
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
			$skip = $request->skip?$request->skip:0;
			$limit = $request->limit?$request->limit:20;
			$ctv = $ctv->limit($limit)
									->skip($skip);
			$ctv = $ctv->orderBy('full_name');
			$ctv = $ctv->get();
			if($ctv){
				$data = $ctv->toArray();
				foreach ($data as $key => $value) {
					$data[$key]['role_active'] = 0;
					if($data[$key]['_roles']){
						$data[$key]['role_active'] = $data[$key]['_roles'][0]['active'];
						unset($data[$key]['_roles']);
					}

					$data[$key]['_daily'] = [];

					if(!empty($data[$key]['_ctv'])){
						$data[$key]['_daily'] = $data[$key]['_ctv']['_daily']['_client'];
					}
					unset($data[$key]['_ctv']);

					$data[$key]['count_location'] = 0;
					$ctv_info = CTV::where('client_id',$data[$key]['id'])->first();

					$contents = Content::select('id')->where('ctv_id','=',$ctv_info->id)->whereIn('moderation',['publish','request_publish','un_publish']);
					$contents = $contents->count();
					if($contents){
						$data[$key]['count_location'] = $contents;
					}
					
				}
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}
}

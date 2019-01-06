<?php
namespace App\Http\Controllers\API;

use App\Models\Location\Country;
use App\Models\Location\City;
use App\Models\Location\District;

use App\Models\Location\Client;
use App\Models\Location\ClientRole;
use App\Models\Location\ClientArea;
use App\Models\Location\ClientInRole;

use Illuminate\Http\Request;
use Validator;
class LocationController extends BaseController {
	public function country(Request $request){
		try{
			$data = Country::select(
											'countries.id',
											'countries.name',
											'countries.alias',
											'countries.weight'
										);
			// $skip = $request->skip?$request->skip:0;
			// $limit = $request->limit?$request->limit:20;
			// $data = $data->limit($limit)
			// 						 ->offset($skip);
			$data = $data->orderBy('weight','asc');
			if($request->ctv_id){
				$role = ClientRole::where('machine_name','cong_tac_vien')->first();
				$client = Client::find($request->ctv_id);
				$check_ctv = ClientInRole::where('client_id',$client->id)
		                  					 ->where('role_id',$role->id)
		                  					 ->count();
		    if($check_ctv>0){
		    	$daily = Client::where('ma_dinh_danh',$client->daily_code)->first();
		      $daily_area = ClientArea::where('client_id',$daily->id)->pluck('district_id');

		    	$arr_country = ClientArea::where('client_id',$client->id)->distinct('country_id')->pluck('country_id')->toArray();

		    	$data = $data->whereIn('id',$arr_country);
		    }
		  }

		  if($request->daily_id){
				$role = ClientRole::where('machine_name','tong_dai_ly')->first();
				$client = Client::find($request->daily_id);
				$check_daily = ClientInRole::where('client_id',$client->id)
		                  					 ->where('role_id',$role->id)
		                  					 ->count();
		    if($check_daily>0){
		    	$arr_country = ClientArea::where('client_id',$client->id)->distinct('country_id')->pluck('country_id')->toArray();
		    	$data = $data->whereIn('id',$arr_country);
		    }
		  }

			$data = $data->get();
			if($data){
				$data = $data->toArray();
			}else{
				$data=[];
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function findCountry(Request $request, $id){
		try{
			if($id){
				$data = Country::find($id);
			}else{
				$data = null;
			}

			if($data){
				$data = $data->toArray();
			}else{
				$data=[];
			}
			return $this->response([$data],200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function city(Request $request, $id_country){
		try{
			$data = City::select(
											'cities.id',
											'cities.name',
											'cities.alias',
											'cities.weight'
										)
									->where('id_country','=',$id_country);
			if($request->ctv_id){
				$role = ClientRole::where('machine_name','cong_tac_vien')->first();
				$client = Client::find($request->ctv_id);
				$check_ctv = ClientInRole::where('client_id',$client->id)
		                  					 ->where('role_id',$role->id)
		                  					 ->count();
		    if($check_ctv>0){
		    	$daily = Client::where('ma_dinh_danh',$client->daily_code)->first();
		      $daily_area = ClientArea::where('client_id',$daily->id)->pluck('district_id');

		    	$arr_city = ClientArea::where('client_id',$client->id)->distinct('city_id')->pluck('city_id')->toArray();
		    	$arr_district = ClientArea::where('client_id',$client->id)->distinct('district_id')->pluck('district_id')->toArray();

		    	$data = $data->whereIn('id',$arr_city);
		    }
		  }

		  if($request->daily_id){
				$role = ClientRole::where('machine_name','tong_dai_ly')->first();
				$client = Client::find($request->daily_id);
				$check_daily = ClientInRole::where('client_id',$client->id)
		                  					 ->where('role_id',$role->id)
		                  					 ->count();
		    if($check_daily>0){
		    	$arr_city = ClientArea::where('client_id',$client->id)->distinct('city_id')->pluck('city_id')->toArray();
		    	$data = $data->whereIn('id',$arr_city);
		    }
		  }
			$data = $data->orderBy('weight','asc');
			$data = $data->get();
			if($data){
				$data = $data->toArray();
			}else{
				$data=[];
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function findCity(Request $request, $id){
		try{
			if($id){
				$data = City::find($id);
			}else{
				$data = null;
			}

			if($data){
				$data = $data->toArray();
			}else{
				$data=[];
			}
			return $this->response([$data],200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function district(Request $request, $id_city){
		try{
			$data = District::select(
											'districts.id',
											'districts.name',
											'districts.alias',
											'districts.weight'
										)
									->where('id_city','=',$id_city);
			// $skip = $request->skip?$request->skip:0;
			// $limit = $request->limit?$request->limit:20;
			// $data = $data->limit($limit)
			// 						 ->offset($skip);
			if($request->ctv_id){
				$role = ClientRole::where('machine_name','cong_tac_vien')->first();
				$client = Client::find($request->ctv_id);
				$check_ctv = ClientInRole::where('client_id',$client->id)
		                  					 ->where('role_id',$role->id)
		                  					 ->count();
		    if($check_ctv>0){
		    	$daily = Client::where('ma_dinh_danh',$client->daily_code)->first();
		      $daily_area = ClientArea::where('client_id',$daily->id)->pluck('district_id');

		    	$arr_city = ClientArea::where('client_id',$client->id)->distinct('city_id')->pluck('city_id')->toArray();
		    	$arr_district = ClientArea::where('client_id',$client->id)->distinct('district_id')->pluck('district_id')->toArray();

		    	$data = $data->whereIn('id',$arr_district);
		    }
		  }

		  if($request->daily_id){
				$role = ClientRole::where('machine_name','tong_dai_ly')->first();
				$client = Client::find($request->daily_id);
				$check_daily = ClientInRole::where('client_id',$client->id)
		                  					 ->where('role_id',$role->id)
		                  					 ->count();
		    if($check_daily>0){
		    	$arr_district = ClientArea::where('client_id',$client->id)->distinct('district_id')->pluck('district_id')->toArray();
		    	$data = $data->whereIn('id',$arr_district);
		    }
		  }

			$data = $data->orderBy('weight','asc');
			$data = $data->get();
			if($data){
				$data = $data->toArray();
			}else{
				$data=[];
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function findDistrict(Request $request, $id){
		try{
			if($id){
				$data = District::find($id);
			}else{
				$data = null;
			}

			if($data){
				$data = $data->toArray();
			}else{
				$data=[];
			}
			return $this->response([$data],200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}
}

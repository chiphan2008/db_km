<?php
namespace App\Http\Controllers\Location;
use Illuminate\Http\Request;
use App\Models\Location\Content;
use App\Models\Location\CategoryContent;
use App\Models\Location\Country;
use App\Models\Location\City;
use App\Models\Location\District;
use App\Models\Location\Category;
use App\Models\Location\CategoryItem;
use App\Models\Location\ServiceItem;

use App\Models\Location\Suggest;
use App\Models\Location\HistorySearch;

use App\Models\Location\PublishAds;
use App\Models\Location\TypeAds;

class SearchController extends BaseController
{
	public function getNewSearch2(Request $request){
		\DB::enableQueryLog();
		$arrData = [];
		$arrData['countries'] = null;
		// $arrData['countries'] = Country::orderBy('weight')->get();
		$arrData['categories'] = null;
		$arrData['categories'] = Category::where('active','=',1)
																		->where('machine_name','not like', '%service%')
																		->orderBy('weight')
																		->get();

		$arrData['category_search'] = null;
		$arrData['category_items'] = null;
		$arrData['services'] = null;
		$arrData['category_item'] = null;
		$arrData['country_search'] = null;
		$arrData['city'] = null;
		$arrData['district'] = null;
		$arrData['countries'] = null;
		$arrData['cities'] = null;
		$arrData['districts'] = null;
		$arrData['contents'] = null;
		$arrData['requests'] = null;
		$arrData['countries'] = Country::orderBy('weight')->get();
		$arrData['requests'] = $request->all();
		$contents = null;
		$arrReturn = [];
		$arrReturn['html'] = '';
		$arrReturn['cities'] = '<option>Chọn thành phố</option>';
		$arrReturn['districts'] = '<option>Chọn quận huyện</option>';

		$arrData['extra_types'] = null;
		$arrData['current_extra_type'] = null;
		

		$query = Content::select(
												'contents.id',
												'contents.name',
												'contents.tag',
												'contents.tag_search',
												'contents.address',
												'contents.lat',
												'contents.lng',
												'contents.vote',
												'contents.like',
												'contents.alias',
												'contents.avatar',
												'contents.country',
												'contents.city',
												'contents.district',
												'contents.last_push',
												'contents.end_push',
												'contents.id_category'
											)
										->where('contents.active','=',1)
										->where('moderation','=','publish')
										->with('_country')
										->with('_city')
										->with('_district')
										// ->orderBy('contents.last_push','desc')
										// ->orderBy('contents.end_push','desc')
										->with('_category_type')
										->leftJoin('countries','contents.country','=','countries.id')
										->leftJoin('cities','contents.city','=','cities.id')
										->leftJoin('districts','contents.district','=','districts.id');

		$arrReturn['q'] = $request->q?$request->q:'';
		$arrData['q'] = $request->q?$request->q:'';

		//Search by keyword
		if($request->q!=''){
			$key = $request->q;
			$query = $query->search(clear_str($key));
		}

		if(!$request->category){
			if($request->type){
				$arrCategory = Category::where('type',$request->type)->pluck('id');
				$query->whereIn('contents.id_category', $arrCategory);
			}
		}else{
			if($request->category_item){
				$arr_category_item = explode(',',$request->category_item);
				if(count($arr_category_item)){
					$arrData['category_items'] = $arr_category_item;
					$query->leftJoin('category_content','contents.id','=','category_content.id_content')
								->whereIn('category_content.id_category_item', $arr_category_item);
				}
			}

			if($request->service){
				$arr_service = explode(',',$request->service);
				if(count($arr_service)){
					$arrData['services'] = $arr_service;
					
					$sql_service = "SELECT `service_content`.`id_content` FROM `service_content` ";
					foreach ($arr_service as $key => $value) {
						$sql_service .= "INNER JOIN(SELECT `service_content`.`id_content` FROM `service_content` where `id_service_item`=$value) tb_$key on `tb_$key`.`id_content` = `service_content`.`id_content`";
					}
					$sql_service .="where `service_content`.`id_service_item`=".$arr_service[0];
					$content_service = \DB::select(\DB::raw($sql_service));
					$arr_content_id = [];
					foreach ($content_service as $key => $value) {
						$arr_content_id[] = $value->id_content;
					}
					// dd($arr_content_id, \DB::getQueryLog());
					$query = $query->whereIn('contents.id',$arr_content_id);
				}
			}

			if($request->category){
				$category = Category::where('id','=',$request->category)
														->where('active','=',1)
														->where('deleted','=',0)
														->with('category_items')
														->with('service_items')
														->first();
				if($category){
					$arrData['category_search'] = $category;
					$query->where('contents.id_category','=',$category->id);

					$arrData['extra_types'] = Content::where('extra_type','!=',null)
																			 ->where('contents.id_category','=',$category->id)
																			 ->groupBy('extra_type')
																			 ->orderBy('extra_type')
																			 ->get()
																			 ->pluck('extra_type');
					if($arrData['extra_types']){
						$arrData['extra_types'] = $arrData['extra_types']->toArray();
						rsort($arrData['extra_types']);
						if(isset($arrData['extra_types'][0])){
							$arrData['current_extra_type'] = $arrData['extra_types'][0];
						}
					}
					if($request->extra_type){
						$arrData['current_extra_type'] = $request->extra_type;
					}
				}
			}
		}

		


		if(session()->has('currentLocation') && !$request->country){
			$currentLocation = explode(',', session()->get('currentLocation'));
			$lat = $currentLocation[0];
			$lng = $currentLocation[1];
			$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&language=vi";
			$data = @file_get_contents($url);
			$jsondata = json_decode($data,true);
			$location = array();
			if(isset($jsondata['results']['0'])){
				foreach($jsondata['results']['0']['address_components'] as $element){
					$location[ implode(' ',$element['types']) ] = $element['long_name'];
				}
				
				$country_str = $location['country political'];
				// $city_str = $location['administrative_area_level_1 political'];
				// $district_str = $location['administrative_area_level_2 political'];

				$country = Country::select('countries.*')
													->selectRaw("MATCH(`name`) AGAINST ('".$country_str." \"".$country_str."\"' in boolean mode) as math_score")
													->whereRaw("MATCH(`name`) AGAINST ('".$country_str." \"".$country_str."\"' in boolean mode) >1")
													->orderBy('math_score', 'desc')
													->orwhere('name','like','%'.$country_str.'%')->first();
				if($country){
					$arrData['country_search'] = $country;
					$arrData['cities'] = City::where('id_country','=',$country->id)->orderBy('weight')->get();
					
					foreach ($arrData['cities'] as $key => $citi_one) {
						if(isset($param['city']) && $param['city'] == $citi_one->alias){
							$arrReturn['cities'] .='<option value="'.$citi_one->alias.'" selected>'.$citi_one->name.'</option>';
						}else{
							$arrReturn['cities'] .='<option value="'.$citi_one->alias.'">'.$citi_one->name.'</option>';
						}
						
					}
					$query->where('contents.country','=',$country->id);
				}
			}
		}


		if($request->country){
			$country = Country::where('id','=',$request->country)->first();
			if($country){
				$arrData['country_search'] = $country;
				$arrData['cities'] = City::where('id_country','=',$country->id)->orderBy('weight')->get();
				
				foreach ($arrData['cities'] as $key => $citi_one) {
					if($request->city && $request->city == $citi_one->id){
						$arrReturn['cities'] .='<option value="'.$citi_one->id.'" selected>'.$citi_one->name.'</option>';
					}else{
						$arrReturn['cities'] .='<option value="'.$citi_one->id.'">'.$citi_one->name.'</option>';
					}
					
				}
				$query->where('contents.country','=',$country->id);
			}
		}
		if($request->city){
			$city = City::where('id','=',$request->city)->first();
			if($city){
				$arrData['city'] = $city;
				$arrData['districts'] = District::where('id_city','=',$city->id)->orderBy('weight')->get();
				foreach ($arrData['districts'] as $key => $citi_one) {
					if($request->district && $request->district == $citi_one->id){
						$arrReturn['districts'] .='<option value="'.$citi_one->id.'" selected>'.$citi_one->name.'</option>';
					}else{
						$arrReturn['districts'] .='<option value="'.$citi_one->id.'">'.$citi_one->name.'</option>';
					}
				}
				$query->where('contents.city','=',$city->id);
			}
		}
		if($request->district){
			$district = District::where('id','=',$request->district)->first();
			if($district){
				$arrData['district'] = $district;
				$query->where('contents.district','=',$district->id);
			}
		}

		//Search lấy những địa điểm gần nhất
		//dd($request->currentLocation);
		if($request->currentLocation){
			$pos = explode(',',$request->currentLocation);
			if(is_array($pos)){
				// session()->put('currentLocation', $request->currentLocation);
				$lat = $pos[0];
				$lng = $pos[1];
				$query = $query->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
												->orderBy('line');
			}
		}else{
			if(session()->has('currentLocation')){
				$currentLocation = explode(',', session()->get('currentLocation'));
				$lat = $currentLocation[0];
				$lng = $currentLocation[1];
				$query = $query->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
												->orderBy('line');
			}else{
				$json = file_get_contents('http://ip-api.com/json/');
				$json = json_decode($json,true);
				$lat = $json['lat'];
				$lng = $json['lon'];
				$query = $query->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
												->orderBy('line');
			}
		}
		$query = $query->distinct('contents.id');
		$query1 = clone $query;
		$query2 = clone $query;
		$query = $query->whereRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) <= 500
													");
		$query1 = $query1->whereRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) <= 25000
													");
		$arrReturn['total_content'] = 0;
		$arrData['totalPage'] = ceil($arrReturn['total_content']/20);
		// if($request->page){
		// 	$contents = $query->limit(20)
		// 			->offset(($request->page-1)*20)
		// 			->get();
		// }else{
			$contents = $query->get();
		// }
		// dd($contents);die;

		// Tính khoảng cách lại
		// if($contents){
		// 	if($request->currentLocation){
		// 		$pos = explode(',',$request->currentLocation);
		// 		if(is_array($pos)){
		// 			session()->put('currentLocation', $request->currentLocation);
		// 			$lat1 = $pos[0];
		// 			$lng1 = $pos[1];
		// 			foreach($contents as $content){
		// 				$content->line = coord2met($lat1, $lng1, $content->lat, $content->lng);
		// 			}
		// 		}
		// 	}else{
		// 		if(session()->has('currentLocation')){
		// 			$currentLocation = explode(',', session()->get('currentLocation'));
		// 			$lat1 = $currentLocation[0];
		// 			$lng1 = $currentLocation[1];
		// 			foreach($contents as $content){
		// 				$content->line = coord2met($lat1, $lng1, $content->lat, $content->lng);
		// 			}
		// 		}
		// 	}
		// }
		
		// dd(\DB::getQueryLog());
		// echo "Total: ".count($contents) ."<br/>";
		// foreach ($contents as $key => $value) {
		// 	echo "name: ".$value->name."<br/>";
		// 	echo "Tag: ".$value->tag_search."<br/>";
		// 	// echo "sum_match: ".($value->name_match+$value->address_match+$value->district_match+$value->city_match)."<br/>";
		// 	echo "name_match: ".$value->name_match."<br/>";
		// 	echo "tag_match: ".$value->tag_match."<br/>";
		// 	// echo "tag_match: ".$value->tag_match_all."<br/>";
		// 	echo "tag_bool_match: ".$value->tag_bool_match."<br/>";
		// 	echo "address_match: ".$value->address_match."<br/>";
		// 	echo "district_match: ".$value->district_match."<br/>";
		// 	echo "city_match: ".$value->city_match."<br/>";
		// // 	echo "country_match: ".$value->country_match."<br/>";
		// 	echo "line: ".$value->line."<br/>";
		// 	echo '<br/> ===== <br/><br/><br/>';
		// }		
		// die;

		$arrData['contents'] = $contents;
		$arrData['total_content'] = $arrReturn['total_content'];

		$arrReturn['currentPage'] = intval($request->page);
		$arrReturn['count'] = count($arrData['contents']);
		$arrReturn['totalPage'] = ceil($arrReturn['total_content']/20);
		$arrReturn['nextPage'] = $arrReturn['currentPage']<$arrReturn['totalPage']?$request->page+1:$arrReturn['totalPage'];
		$arr_json_content = [];
		
		
		if(count($contents) == 0){
			// sleep(1);
			$contents = $query1->limit(20)->get();
			if(count($contents) == 0){
				$contents = $query2->limit(20)->get();
			}
		}
		if($request->q!=''){
			HistorySearch::updateOrCreate([
				'keyword'	=> $request->q,
				'ip'     	=> getUserIP(),
				'agent'  	=> $_SERVER['HTTP_USER_AGENT']
			],[
				'keyword'	=> $request->q,
				'ip'     	=> getUserIP(),
				'agent'  	=> $_SERVER['HTTP_USER_AGENT']
			]);
		}
		// dd(\DB::getQueryLog());
		// dd($contents,$contents1);

		if($contents){
			foreach ($contents as $key => $value) {
				$tmp = [];
				$tmp['center'] = implode(',',[$value->lat, $value->lng]);
				$tmp['id'] = $value->id;
				$tmp['title'] = $value->name;
				$tmp['price'] = $value->name;
				$tmp['line'] = $value->line;
				$tmp['url'] = url('/').'/'.$value->alias;
				if($value->_category_type->marker){
					$tmp['urlImage'] = $value->_category_type->marker;
				}else{
					$tmp['urlImage'] = '/img_default/marker.svg';
				}
				$tmp['posthref'] = url('/').'/'.$value->alias;
				$tmp['postImg'] = $value->avatar;
				$tmp['postAddress'] = $value->address.','.$value->_district->name.','.$value->_city->name.','.$value->_country->name;
				$tmp['postLike'] = $value->like;
				$tmp['postStart'] = ($value->vote/5)*100;
				// if(count($value->_discount_basic)){
				// 	$discount = $value->_discount_basic[0];
				// 	switch ($discount->type) {
				// 		case 'other':
				// 			$tmp['urlgift'] = '/frontend/assets/img/icon/gift.png';
				// 			break;
				// 		case 'percent':
				// 			$tmp['saleNumber'] = $discount->from_percent.'%';
				// 			break;
				// 		case 'percent_fromto':
				// 			$tmp['saleNumber'] = $discount->to_percent.'%';
				// 			break;
				// 		case 'price':
				// 			$tmp['saleNumber'] = $discount->from_price.' '.$discount->currency;
				// 			break;
				// 		case 'price_fromto':
				// 			$tmp['saleNumber'] = $discount->from_price.' '.$discount->currency;
				// 			break;
				// 		default:
				// 			$tmp['urlgift'] = '/frontend/assets/img/icon/gift.png';
				// 			break;
				// 	}
				// }
				$arr_json_content[] = $tmp;
			}
			// die;
		}
		$arrData['json'] = json_encode($arr_json_content);
		$arrReturn['json'] = json_encode($arr_json_content);
		$arrReturn['contents']=$contents;
		$arrData['contents']=$contents;
		// dd($arrData);
		if($request->ajax()){
			$html = view('Location.category.content_item_list',['contents'=>$contents])->render();
			$arrReturn['html'] = $html;
			return response()->json($arrReturn);
		}else{
	    $currentCity = 0;
	    if(session()->has('currentLocation')){
	      $currentLocation = explode(',', session()->get('currentLocation'));
	      $lat = $currentLocation[0];
	      $lng = $currentLocation[1];
	      $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&language=vi";
	      $data = @file_get_contents($url);
	      $jsondata = json_decode($data,true);
	      $location = array();
	      if(isset($jsondata['results']['0'])){
	        foreach($jsondata['results']['0']['address_components'] as $element){
	          $location[ implode(' ',$element['types']) ] = $element['long_name'];
	        }

	        $country_str = isset($location['country political'])?$location['country political']:'';
	        $city_str = isset($location['administrative_area_level_1 political'])?$location['administrative_area_level_1 political']:'';
	        $district_str = isset($location['administrative_area_level_2 political'])?$location['administrative_area_level_2 political']:'';
	        if($city_str != ''){
	            $city_current = City::select('cities.id')
	                              ->selectRaw("MATCH(`name`) AGAINST ('".$city_str." \"".$city_str."\"' in boolean mode) as math_score")
	                              ->whereRaw("MATCH(`name`) AGAINST ('".$city_str." \"".$city_str."\"' in boolean mode) >1")
	                              ->orderBy('math_score', 'desc')
	                              ->orwhere('name','like','%'.$city_str.'%')->first();
	            if($city_current){
	              $currentCity = $city_current->id;
	            }
	        }
	      }
	    }

	    $ads = PublishAds::select('publish_ads.*')
	                      ->selectRaw("RAND () as id_rand ")
	                      ->with('_base_content')
	                      ->leftJoin('type_ads','type_ads.id','publish_ads.type_ads')
	                      ->where('type_ads.machine_name','quang_cao_popup_trang_map')
	                      ->where('publish_ads.active',1)
	                      ->where(function($query) use ($currentCity){
	                        return $query->where('publish_ads.city',$currentCity)
	                              ->orwhere('publish_ads.city',0);
	                      })
	                      ->where(function($query){
	                        return $query->where(function($query_sub){
	                          return $query_sub->where('publish_ads.type_apply','date')
	                                           ->where('publish_ads.date_from','<=',date("Y-m-d H:i:s"))
	                                           ->where('publish_ads.date_to','>=',date("Y-m-d H:i:s"));
	                        })->orwhere(function($query_sub){
	                          return $query_sub->whereRaw('publish_ads.clicked < click');
	                        })->orwhere(function($query_sub){
	                          return $query_sub->whereRaw('publish_ads.viewed < view');
	                        });
	                      })
	                      ->orderBy('publish_ads.city','DESC')
	                      ->orderBy('id_rand','DESC')
	                      ->first();
      
      if($ads){
      	if($ads->type_apply=='view'){
	        $ads->viewed = $ads->viewed + 1;
	        $ads->save();
	      }
      	$arrData['ads'] = $ads;
      }else{
      	$arrData['ads'] = null;
      }
	    // dd($ads);
	    $arrData['type_ads'] = TypeAds::where('machine_name','quang_cao_popup_trang_map')->first();


			$this->view->category_search = $arrData['category_search'];
			$this->view->category_items  = $arrData['category_items'];
			$this->view->country_search  = $arrData['country_search'];
			$this->view->city            = $arrData['city'];
			$this->view->district        = $arrData['district'];
			$this->view->cities          = $arrData['cities'];
			$this->view->districts       = $arrData['districts'];
			$this->view->content = view('Location.search.search_new_2',$arrData);
			return $this->setContent();
		}
	}

	public function getNewSearch(Request $request){
		\DB::enableQueryLog();
		$arrData = [];
		$arrData['countries'] = null;
		// $arrData['countries'] = Country::orderBy('weight')->get();
		$arrData['categories'] = null;
		$arrData['categories'] = Category::where('active','=',1)
																		->where('machine_name','not like', '%service%')
																		->orderBy('weight')
																		->get();

		$arrData['category_search'] = null;
		$arrData['category_items'] = null;
		$arrData['category_item'] = null;
		$arrData['country_search'] = null;
		$arrData['city'] = null;
		$arrData['district'] = null;
		$arrData['countries'] = null;
		$arrData['cities'] = null;
		$arrData['districts'] = null;
		$arrData['contents'] = null;
		$arrData['requests'] = null;
		$arrData['countries'] = Country::orderBy('weight')->get();
		$arrData['requests'] = $request->all();
		$contents = null;
		$arrReturn = [];
		$arrReturn['html'] = '';
		$arrReturn['cities'] = '<option>Chọn thành phố</option>';
		$arrReturn['districts'] = '<option>Chọn quận huyện</option>';

		$arrData['extra_types'] = null;
		$arrData['current_extra_type'] = null;
		

		$query = Content::select(
												'contents.id',
												'contents.name',
												'contents.tag',
												'contents.tag_search',
												'contents.address',
												'contents.lat',
												'contents.lng',
												'contents.vote',
												'contents.like',
												'contents.alias',
												'contents.avatar',
												'contents.country',
												'contents.city',
												'contents.district',
												'contents.last_push',
												'contents.end_push',
												'contents.id_category'
											)
										->where('contents.active','=',1)
										->where('moderation','=','publish')
										->with('_country')
										->with('_city')
										->with('_district')
										// ->orderBy('contents.last_push','desc')
										// ->orderBy('contents.end_push','desc')
										->with('_category_type')
										->leftJoin('countries','contents.country','=','countries.id')
										->leftJoin('cities','contents.city','=','cities.id')
										->leftJoin('districts','contents.district','=','districts.id');

		$arrReturn['q'] = $request->q?$request->q:'';
		$arrData['q'] = $request->q?$request->q:'';

		//Search by keyword
		if($request->q!=''){
			$key = $request->q;
			$query = $query->search(clear_str($key));
		}

		if(!$request->category){
			if($request->type){
				$arrCategory = Category::where('type',$request->type)->pluck('id');
				$query->whereIn('contents.id_category', $arrCategory);
			}
		}else{
			if($request->category_item){
				$arr_category_item = explode(',',$request->category_item);
				if(count($arr_category_item)){
					$arrData['category_items'] = $arr_category_item;
					$query->leftJoin('category_content','contents.id','=','category_content.id_content')
								->whereIn('category_content.id_category_item', $arr_category_item);
				}
			}

			if($request->category){
				$category = Category::where('id','=',$request->category)
														->where('active','=',1)
														->where('deleted','=',0)
														->with('category_items')
														->first();
				if($category){
					$arrData['category_search'] = $category;
					$query->where('contents.id_category','=',$category->id);

					$arrData['extra_types'] = Content::where('extra_type','!=',null)
																			 ->where('contents.id_category','=',$category->id)
																			 ->groupBy('extra_type')
																			 ->orderBy('extra_type')
																			 ->get()
																			 ->pluck('extra_type');
					if($arrData['extra_types']){
						$arrData['extra_types'] = $arrData['extra_types']->toArray();
						rsort($arrData['extra_types']);
						if(isset($arrData['extra_types'][0])){
							$arrData['current_extra_type'] = $arrData['extra_types'][0];
						}
					}
					if($request->extra_type){
						$arrData['current_extra_type'] = $request->extra_type;
					}
				}
			}
		}

		


		if(session()->has('currentLocation') && !isset($param['country'])){
			$currentLocation = explode(',', session()->get('currentLocation'));
			$lat = $currentLocation[0];
			$lng = $currentLocation[1];
			$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&language=vi";
			$data = @file_get_contents($url);
			$jsondata = json_decode($data,true);
			$location = array();
			if(isset($jsondata['results']['0'])){
				foreach($jsondata['results']['0']['address_components'] as $element){
					$location[ implode(' ',$element['types']) ] = $element['long_name'];
				}
				
				$country_str = $location['country political'];
				// $city_str = $location['administrative_area_level_1 political'];
				// $district_str = $location['administrative_area_level_2 political'];

				$country = Country::select('countries.*')
													->selectRaw("MATCH(`name`) AGAINST ('".$country_str." \"".$country_str."\"' in boolean mode) as math_score")
													->whereRaw("MATCH(`name`) AGAINST ('".$country_str." \"".$country_str."\"' in boolean mode) >1")
													->orderBy('math_score', 'desc')
													->orwhere('name','like','%'.$country_str.'%')->first();
				if($country){
					$arrData['country_search'] = $country;
					$arrData['cities'] = City::where('id_country','=',$country->id)->orderBy('weight')->get();
					
					foreach ($arrData['cities'] as $key => $citi_one) {
						if(isset($param['city']) && $param['city'] == $citi_one->alias){
							$arrReturn['cities'] .='<option value="'.$citi_one->alias.'" selected>'.$citi_one->name.'</option>';
						}else{
							$arrReturn['cities'] .='<option value="'.$citi_one->alias.'">'.$citi_one->name.'</option>';
						}
						
					}
					$query->where('contents.country','=',$country->id);
				}
			}
		}


		if($request->country){
			$country = Country::where('id','=',$request->country)->first();
			if($country){
				$arrData['country_search'] = $country;
				$arrData['cities'] = City::where('id_country','=',$country->id)->orderBy('weight')->get();
				
				foreach ($arrData['cities'] as $key => $citi_one) {
					if($request->city && $request->city == $citi_one->id){
						$arrReturn['cities'] .='<option value="'.$citi_one->id.'" selected>'.$citi_one->name.'</option>';
					}else{
						$arrReturn['cities'] .='<option value="'.$citi_one->id.'">'.$citi_one->name.'</option>';
					}
					
				}
				$query->where('contents.country','=',$country->id);
			}
		}
		if($request->city){
			$city = City::where('id','=',$request->city)->first();
			if($city){
				$arrData['city'] = $city;
				$arrData['districts'] = District::where('id_city','=',$city->id)->orderBy('weight')->get();
				foreach ($arrData['districts'] as $key => $citi_one) {
					if($request->district && $request->district == $citi_one->id){
						$arrReturn['districts'] .='<option value="'.$citi_one->id.'" selected>'.$citi_one->name.'</option>';
					}else{
						$arrReturn['districts'] .='<option value="'.$citi_one->id.'">'.$citi_one->name.'</option>';
					}
				}
				$query->where('contents.city','=',$city->id);
			}
		}
		if($request->district){
			$district = District::where('id','=',$request->district)->first();
			if($district){
				$arrData['district'] = $district;
				$query->where('contents.district','=',$district->id);
			}
		}

		//Search lấy những địa điểm gần nhất
		// dd($request->currentLocation);
		if($request->currentLocation){
			$pos = explode(',',$request->currentLocation);
			if(is_array($pos)){
				// session()->put('currentLocation', $request->currentLocation);
				$lat = $pos[0];
				$lng = $pos[1];
				$query = $query->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
												->orderBy('line');
			}
		}else{
			if(session()->has('currentLocation')){
				$currentLocation = explode(',', session()->get('currentLocation'));
				$lat = $currentLocation[0];
				$lng = $currentLocation[1];
				$query = $query->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
												->orderBy('line');
			}else{
				$json = file_get_contents('http://ip-api.com/json/');
				$json = json_decode($json,true);
				$lat = $json['lat'];
				$lng = $json['lon'];
				$query = $query->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
												->orderBy('line');
			}
		}
		$query1 = clone $query;
		$query = $query->whereRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) <= 500
													");
		$query1 = $query1->whereRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) <= 25000
													");

		$query = $query->distinct('contents.id');
		$arrReturn['total_content'] = 0;
		$arrData['totalPage'] = ceil($arrReturn['total_content']/20);
		// if($request->page){
		// 	$contents = $query->limit(20)
		// 			->offset(($request->page-1)*20)
		// 			->get();
		// }else{
			$contents = $query->get();
		// }
		// dd($contents);die;

		// Tính khoảng cách lại
		// if($contents){
		// 	if($request->currentLocation){
		// 		$pos = explode(',',$request->currentLocation);
		// 		if(is_array($pos)){
		// 			session()->put('currentLocation', $request->currentLocation);
		// 			$lat1 = $pos[0];
		// 			$lng1 = $pos[1];
		// 			foreach($contents as $content){
		// 				$content->line = coord2met($lat1, $lng1, $content->lat, $content->lng);
		// 			}
		// 		}
		// 	}else{
		// 		if(session()->has('currentLocation')){
		// 			$currentLocation = explode(',', session()->get('currentLocation'));
		// 			$lat1 = $currentLocation[0];
		// 			$lng1 = $currentLocation[1];
		// 			foreach($contents as $content){
		// 				$content->line = coord2met($lat1, $lng1, $content->lat, $content->lng);
		// 			}
		// 		}
		// 	}
		// }
		
		// dd(\DB::getQueryLog());
		// echo "Total: ".count($contents) ."<br/>";
		// foreach ($contents as $key => $value) {
		// 	echo "name: ".$value->name."<br/>";
		// 	echo "Tag: ".$value->tag_search."<br/>";
		// 	// echo "sum_match: ".($value->name_match+$value->address_match+$value->district_match+$value->city_match)."<br/>";
		// 	echo "name_match: ".$value->name_match."<br/>";
		// 	echo "tag_match: ".$value->tag_match."<br/>";
		// 	// echo "tag_match: ".$value->tag_match_all."<br/>";
		// 	echo "tag_bool_match: ".$value->tag_bool_match."<br/>";
		// 	echo "address_match: ".$value->address_match."<br/>";
		// 	echo "district_match: ".$value->district_match."<br/>";
		// 	echo "city_match: ".$value->city_match."<br/>";
		// // 	echo "country_match: ".$value->country_match."<br/>";
		// 	echo "line: ".$value->line."<br/>";
		// 	echo '<br/> ===== <br/><br/><br/>';
		// }		
		// die;

		$arrData['contents'] = $contents;
		$arrData['total_content'] = $arrReturn['total_content'];

		$arrReturn['currentPage'] = intval($request->page);
		$arrReturn['count'] = count($arrData['contents']);
		$arrReturn['totalPage'] = ceil($arrReturn['total_content']/20);
		$arrReturn['nextPage'] = $arrReturn['currentPage']<$arrReturn['totalPage']?$request->page+1:$arrReturn['totalPage'];
		$arr_json_content = [];
		
		
		if(count($contents) == 0){
			// sleep(1);
			$contents = $query1->limit(20)->get();
		}
		if($request->q!=''){
			HistorySearch::updateOrCreate([
				'keyword'	=> $request->q,
				'ip'     	=> getUserIP(),
				'agent'  	=> $_SERVER['HTTP_USER_AGENT']
			],[
				'keyword'	=> $request->q,
				'ip'     	=> getUserIP(),
				'agent'  	=> $_SERVER['HTTP_USER_AGENT']
			]);
		}
		// dd(\DB::getQueryLog());
		// dd($contents,$contents1);

		if($contents){
			foreach ($contents as $key => $value) {
				$tmp = [];
				$tmp['center'] = implode(',',[$value->lat, $value->lng]);
				$tmp['id'] = $value->id;
				$tmp['title'] = $value->name;
				$tmp['price'] = $value->name;
				$tmp['line'] = $value->line;
				$tmp['url'] = url('/').'/'.$value->alias;
				if($value->_category_type->marker){
					$tmp['urlImage'] = $value->_category_type->marker;
				}else{
					$tmp['urlImage'] = '/img_default/marker.svg';
				}
				$tmp['posthref'] = url('/').'/'.$value->alias;
				$tmp['postImg'] = $value->avatar;
				$tmp['postAddress'] = $value->address.','.$value->_district->name.','.$value->_city->name.','.$value->_country->name;
				$tmp['postLike'] = $value->like;
				$tmp['postStart'] = ($value->vote/5)*100;
				// if(count($value->_discount_basic)){
				// 	$discount = $value->_discount_basic[0];
				// 	switch ($discount->type) {
				// 		case 'other':
				// 			$tmp['urlgift'] = '/frontend/assets/img/icon/gift.png';
				// 			break;
				// 		case 'percent':
				// 			$tmp['saleNumber'] = $discount->from_percent.'%';
				// 			break;
				// 		case 'percent_fromto':
				// 			$tmp['saleNumber'] = $discount->to_percent.'%';
				// 			break;
				// 		case 'price':
				// 			$tmp['saleNumber'] = $discount->from_price.' '.$discount->currency;
				// 			break;
				// 		case 'price_fromto':
				// 			$tmp['saleNumber'] = $discount->from_price.' '.$discount->currency;
				// 			break;
				// 		default:
				// 			$tmp['urlgift'] = '/frontend/assets/img/icon/gift.png';
				// 			break;
				// 	}
				// }
				$arr_json_content[] = $tmp;
			}
			// die;
		}
		$arrData['json'] = json_encode($arr_json_content);
		$arrReturn['json'] = json_encode($arr_json_content);
		$arrReturn['contents']=$contents;
		// dd($arrData);
		if($request->ajax()){
			$html = view('Location.category.content_item_list',['contents'=>$contents])->render();
			$arrReturn['html'] = $html;
			return response()->json($arrReturn);
		}else{
	    $currentCity = 0;
	    if(session()->has('currentLocation')){
	      $currentLocation = explode(',', session()->get('currentLocation'));
	      $lat = $currentLocation[0];
	      $lng = $currentLocation[1];
	      $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&language=vi";
	      $data = @file_get_contents($url);
	      $jsondata = json_decode($data,true);
	      $location = array();
	      if(isset($jsondata['results']['0'])){
	        foreach($jsondata['results']['0']['address_components'] as $element){
	          $location[ implode(' ',$element['types']) ] = $element['long_name'];
	        }

	        $country_str = isset($location['country political'])?$location['country political']:'';
	        $city_str = isset($location['administrative_area_level_1 political'])?$location['administrative_area_level_1 political']:'';
	        $district_str = isset($location['administrative_area_level_2 political'])?$location['administrative_area_level_2 political']:'';
	        if($city_str != ''){
	            $city_current = City::select('cities.id')
	                              ->selectRaw("MATCH(`name`) AGAINST ('".$city_str." \"".$city_str."\"' in boolean mode) as math_score")
	                              ->whereRaw("MATCH(`name`) AGAINST ('".$city_str." \"".$city_str."\"' in boolean mode) >1")
	                              ->orderBy('math_score', 'desc')
	                              ->orwhere('name','like','%'.$city_str.'%')->first();
	            if($city_current){
	              $currentCity = $city_current->id;
	            }
	        }
	      }
	    }

	    $ads = PublishAds::select('publish_ads.*')
	                      ->selectRaw("RAND () as id_rand ")
	                      ->with('_base_content')
	                      ->leftJoin('type_ads','type_ads.id','publish_ads.type_ads')
	                      ->where('type_ads.machine_name','quang_cao_popup_trang_map')
	                      ->where('publish_ads.active',1)
	                      ->where(function($query) use ($currentCity){
	                        return $query->where('publish_ads.city',$currentCity)
	                              ->orwhere('publish_ads.city',0);
	                      })
	                      ->where(function($query){
	                        return $query->where(function($query_sub){
	                          return $query_sub->where('publish_ads.type_apply','date')
	                                           ->where('publish_ads.date_from','<=',date("Y-m-d H:i:s"))
	                                           ->where('publish_ads.date_to','>=',date("Y-m-d H:i:s"));
	                        })->orwhere(function($query_sub){
	                          return $query_sub->whereRaw('publish_ads.clicked < click');
	                        })->orwhere(function($query_sub){
	                          return $query_sub->whereRaw('publish_ads.viewed < view');
	                        });
	                      })
	                      ->orderBy('publish_ads.city','DESC')
	                      ->orderBy('id_rand','DESC')
	                      ->first();
      
      if($ads){
      	if($ads->type_apply=='view'){
	        $ads->viewed = $ads->viewed + 1;
	        $ads->save();
	      }
      	$arrData['ads'] = $ads;
      }else{
      	$arrData['ads'] = null;
      }
	    // dd($ads);
	    $arrData['type_ads'] = TypeAds::where('machine_name','quang_cao_popup_trang_map')->first();


			$this->view->category_search = $arrData['category_search'];
			$this->view->category_items  = $arrData['category_items'];
			$this->view->country_search  = $arrData['country_search'];
			$this->view->city            = $arrData['city'];
			$this->view->district        = $arrData['district'];
			$this->view->cities          = $arrData['cities'];
			$this->view->districts       = $arrData['districts'];
			$this->view->content = view('Location.search.search_new',$arrData);
			return $this->setContent();
		}
	}

	public function anySearch(Request $request){
		\DB::enableQueryLog();
		$arrData = [];
		$arrData['countries'] = null;
		// $arrData['countries'] = Country::orderBy('weight')->get();
		$arrData['categories'] = null;
		$arrData['categories'] = Category::where('active','=',1)
																		->where('machine_name','not like', '%service%')
																		->orderBy('weight')
																		->get();

		$arrData['category_search'] = null;
		$arrData['category'] = null;
		$arrData['category_items'] = null;
		$arrData['country_search'] = null;
		$arrData['country'] = null;
		$arrData['city'] = null;
		$arrData['district'] = null;
		$arrData['countries'] = null;
		$arrData['cities'] = null;
		$arrData['districts'] = null;
		$arrData['contents'] = null;
		$arrData['requests'] = null;
		$arrData['countries'] = Country::orderBy('weight')->get();
		$arrData['requests'] = $request->all();
		$contents = null;
		$arrReturn = [];
		$arrReturn['html'] = '';
		$arrReturn['cities'] = '<option>Chọn thành phố</option>';
		$arrReturn['districts'] = '<option>Chọn quận huyện</option>';

		$query = Content::select(
												'contents.name',
												'contents.tag',
												'contents.tag_search',
												'contents.address',
												'contents.lat',
												'contents.lng',
												'contents.vote',
												'contents.like',
												'contents.alias',
												'contents.avatar',
												'contents.country',
												'contents.city',
												'contents.district',
												'contents.last_push',
												'contents.end_push'
											)
										->where('contents.active','=',1)
										->where('moderation','=','publish')
										// ->orderBy('contents.last_push','desc')
										// ->orderBy('contents.end_push','desc')
										->leftJoin('countries','contents.country','=','countries.id')
										->leftJoin('cities','contents.city','=','cities.id')
										->leftJoin('districts','contents.district','=','districts.id');

		$arrReturn['q'] = $request->q?$request->q:'';
		$arrData['q'] = $request->q?$request->q:'';

		//Search by keyword
		if($request->q!=''){
			$key = $request->q;
			$query = $query->search(clear_str($key));
		}else{
			$query = $query->search(null);
		}

		

		if(!$request->category){
			if($request->type){
				$arrCategory = Category::where('type',$request->type)->pluck('id');
				$query->whereIn('contents.id_category', $arrCategory);
			}
		}else{
			if($request->category_item){
				$arr_category_item = explode(',',$request->category_item);
				if(count($arr_category_item)){
					$arrData['category_items'] = $arr_category_item;
					$query->leftJoin('category_content','contents.id','=','category_content.id_content')
								->whereIn('category_content.id_category_item', $arr_category_item);
				}
			}

			if($request->category){
				$category = Category::where('id','=',$request->category)
														->where('active','=',1)
														->where('deleted','=',0)
														->with('category_items')
														->first();
				if($category){
					$arrData['category_search'] = $category;
					$arrData['category'] = $category;
					$query->where('contents.id_category','=',$category->id);

					$arrData['extra_types'] = Content::where('extra_type','!=',null)
																			 ->where('contents.id_category','=',$category->id)
																			 ->groupBy('extra_type')
																			 ->orderBy('extra_type')
																			 ->get()
																			 ->pluck('extra_type');
					if($arrData['extra_types']){
						$arrData['extra_types'] = $arrData['extra_types']->toArray();
						rsort($arrData['extra_types']);
						if(isset($arrData['extra_types'][0])){
							$arrData['current_extra_type'] = $arrData['extra_types'][0];
						}
					}
					if($request->extra_type){
						$arrData['current_extra_type'] = $request->extra_type;
					}
				}
			}
		}


		if(session()->has('currentLocation') && !isset($param['country'])){
			$currentLocation = explode(',', session()->get('currentLocation'));
			$lat = $currentLocation[0];
			$lng = $currentLocation[1];
			$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&language=vi";
			$data = @file_get_contents($url);

			$jsondata = json_decode($data,true);
			$location = array();
			if(isset($jsondata['results']['0'])){
				foreach($jsondata['results']['0']['address_components'] as $element){
					$location[ implode(' ',$element['types']) ] = $element['long_name'];
				}
				
				$country_str = $location['country political'];
				// $city_str = $location['administrative_area_level_1 political'];
				// $district_str = $location['administrative_area_level_2 political'];
				
				$country = Country::select('countries.*')
													->selectRaw("MATCH(`name`) AGAINST ('".$country_str." \"".$country_str."\"' in boolean mode) as math_score")
													->whereRaw("MATCH(`name`) AGAINST ('".$country_str." \"".$country_str."\"' in boolean mode) >=1")
													->orderBy('math_score', 'desc')
													->orwhere('name','like','%'.$country_str.'%')->first();

				if($country){
					$arrData['country'] = $country;
					$arrData['country_search'] = $country;
					$arrData['cities'] = City::where('id_country','=',$country->id)->orderBy('weight')->get();
					
					foreach ($arrData['cities'] as $key => $citi_one) {
						if(isset($param['city']) && $param['city'] == $citi_one->alias){
							$arrReturn['cities'] .='<option value="'.$citi_one->alias.'" selected>'.$citi_one->name.'</option>';
						}else{
							$arrReturn['cities'] .='<option value="'.$citi_one->alias.'">'.$citi_one->name.'</option>';
						}
						
					}
					$query->where('contents.country','=',$country->id);
				}
			}
		}


		if($request->country){
			$country = Country::where('id','=',$request->country)->first();
			if($country){
				$arrData['country_search'] = $country;
				$arrData['cities'] = City::where('id_country','=',$country->id)->orderBy('weight')->get();
				
				foreach ($arrData['cities'] as $key => $citi_one) {
					if($request->city && $request->city == $citi_one->id){
						$arrReturn['cities'] .='<option value="'.$citi_one->id.'" selected>'.$citi_one->name.'</option>';
					}else{
						$arrReturn['cities'] .='<option value="'.$citi_one->id.'">'.$citi_one->name.'</option>';
					}
					
				}
				$query->where('contents.country','=',$country->id);
			}
		}
		if($request->city){
			$city = City::where('id','=',$request->city)->first();
			if($city){
				$arrData['city'] = $city;
				$arrData['districts'] = District::where('id_city','=',$city->id)->orderBy('weight')->get();
				foreach ($arrData['districts'] as $key => $citi_one) {
					if($request->district && $request->district == $citi_one->id){
						$arrReturn['districts'] .='<option value="'.$citi_one->id.'" selected>'.$citi_one->name.'</option>';
					}else{
						$arrReturn['districts'] .='<option value="'.$citi_one->id.'">'.$citi_one->name.'</option>';
					}
				}
				$query->where('contents.city','=',$city->id);
			}
		}
		if($request->district){
			$district = District::where('id','=',$request->district)->first();
			if($district){
				$arrData['district'] = $district;
				$query->where('contents.district','=',$district->id);
			}
		}

		//Search lấy những địa điểm gần nhất
		// dd($request->currentLocation);
		if($request->currentLocation){
			$pos = explode(',',$request->currentLocation);
			if(is_array($pos)){
				session()->put('currentLocation', $request->currentLocation);
				$lat = $pos[0];
				$lng = $pos[1];
				$query = $query->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
												->orderBy('line');
			}
		}else{
			if(session()->has('currentLocation')){
				$currentLocation = explode(',', session()->get('currentLocation'));
				$lat = $currentLocation[0];
				$lng = $currentLocation[1];
				$query = $query->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
												->orderBy('line');
			}else{
				$json = file_get_contents('http://ip-api.com/json/');
				$json = json_decode($json,true);
				$lat = $json['lat'];
				$lng = $json['lon'];
				$query = $query->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
												->orderBy('line');
			}
		}

		$query = $query->distinct('contents.id');
		$arrReturn['total_content'] = $query->count('contents.id');
		$arrData['totalPage'] = ceil($arrReturn['total_content']/20);
		if($request->test){
			$contents = $query->get();
		}else{
			if($request->page){
				$contents = $query->limit(20)
						->offset(($request->page-1)*20)
						->get();
			}else{
				$contents = $query->limit(20)
						->offset(0)
						->get();
			}
		}
			
		// dd($contents);die;

		// Tính khoảng cách lại
		// if($contents){
		// 	if($request->currentLocation){
		// 		$pos = explode(',',$request->currentLocation);
		// 		if(is_array($pos)){
		// 			session()->put('currentLocation', $request->currentLocation);
		// 			$lat1 = $pos[0];
		// 			$lng1 = $pos[1];
		// 			foreach($contents as $content){
		// 				$content->line = coord2met($lat1, $lng1, $content->lat, $content->lng);
		// 			}
		// 		}
		// 	}else{
		// 		if(session()->has('currentLocation')){
		// 			$currentLocation = explode(',', session()->get('currentLocation'));
		// 			$lat1 = $currentLocation[0];
		// 			$lng1 = $currentLocation[1];
		// 			foreach($contents as $content){
		// 				$content->line = coord2met($lat1, $lng1, $content->lat, $content->lng);
		// 			}
		// 		}
		// 	}
		// }
		
		// dd(\DB::getQueryLog());
		if($request->test){
			echo "Total: ".count($contents) ."<br/>";
			echo "Keyword: ".$request->q ."<br/>";
			$arr_pr = processKeyword($request->q);
			echo "Str match: ".$arr_pr['str_match'] ."<br/>";
			echo "match score: ".$arr_pr['match_score'] ."<br/>";
			// Module Search
			foreach ($contents as $key => $value) {
				echo "name: ".$value->name."<br/>";
				echo "Tag: ".$value->tag_search."<br/>";
				// echo "sum_match: ".($value->name_match+$value->address_match+$value->district_match+$value->city_match)."<br/>";
				echo "name_match: ".$value->name_match."<br/>";
				echo "tag_match: ".$value->tag_match."<br/>";
				// echo "tag_match: ".$value->tag_match_all."<br/>";
				echo "tag_bool_match: ".$value->tag_bool_match."<br/>";
				echo "address_match: ".$value->address_match."<br/>";
				echo "district_match: ".$value->district_match."<br/>";
				echo "city_match: ".$value->city_match."<br/>";
			// 	echo "country_match: ".$value->country_match."<br/>";
				echo "line: ".$value->line."<br/>";
				echo '<br/> ===== <br/><br/><br/>';
			}		
			die;
		}

		$arrData['contents'] = $contents;
		$arrData['total_content'] = $arrReturn['total_content'];

		$arrReturn['currentPage'] = intval($request->page);
		$arrReturn['count'] = count($arrData['contents']);
		$arrReturn['totalPage'] = ceil($arrReturn['total_content']/20);
		$arrReturn['nextPage'] = $arrReturn['currentPage']<$arrReturn['totalPage']?$request->page+1:$arrReturn['totalPage'];
		// dd(\Db::getQueryLog());
		if($request->ajax()){
			$arrReturn['html'] = view('Location.search.search_list', ['contents'=>$contents])->render();
			return response()->json($arrReturn);
		}else{
			$this->view->content = view('Location.search.search', $arrData);
			return $this->setContent();
		}
	}

	public function postLoadCity(Request $request){
		if($request->ajax()){
			$html = '<option value="all">'.trans('global.all').'</option>';
			if($request->country){
				$cities = City::where('id_country','=',$request->country)->orderBy('weight')->get();
				foreach ($cities as $key => $city) {
					$html .= '<option value="'.$city->id.'">'.$city->name.'</option>';
				}
			}
			return response($html);
		}else{
			abort(404);
		}
	}

	public function postLoadDistrict(Request $request){
		if($request->ajax()){
			$html = '<option value="">'.trans('global.all').'</option>';
			if($request->city){
				$districts = District::where('id_city','=',$request->city)->orderBy('weight')->get();
				foreach ($districts as $key => $district) {
					$html .= '<option value="'.$district->id.'">'.$district->name.'</option>';
				}
			}
			return response($html);
		}else{
			abort(404);
		}
	}

	public function postloadCategoryItem(Request $request){
		if($request->ajax()){
			$html = '';
			if($request->category){
				$category_items = CategoryItem::where('category_id','=',$request->category)
																			->where('active','=',1)
																			->where('deleted','=',0)
																			->get();
				foreach ($category_items as $key => $category_item) {
					$html .= '<label class="custom-control custom-checkbox mb-3">
									<input onclick="chooseCategoryItem()" type="checkbox" name="category_item" class="custom-control-input" value="'.$category_item->id.'">
									<span class="custom-control-indicator"></span>
									<span class="custom-control-description">'.$category_item->name.'</span>
								</label>';
				}
			}
			return response($html);
		}else{
			abort(404);
		}
	}

	public function anyAjaxSearch(Request $request){
		if(!$request->ajax()){
			abort(404);
		}else{
			$query_ad = Content::select(
												'contents.id',
												'contents.name',
												'contents.tag',
												'contents.tag_search',
												'contents.address',
												'contents.lat',
												'contents.lng',
												'contents.vote',
												'contents.like',
												'contents.alias',
												'contents.avatar',
												'contents.country',
												'contents.city',
												'contents.district',
												'contents.last_push',
												'contents.view_ad',
												'contents.end_push'
											)
										->where('contents.active','=',1)
										->where('moderation','=','publish')
										->leftJoin('countries','contents.country','=','countries.id')
										->leftJoin('cities','contents.city','=','cities.id')
										->leftJoin('districts','contents.district','=','districts.id');

			$query = Content::select(
												'contents.id',
												'contents.name',
												'contents.tag',
												'contents.tag_search',
												'contents.address',
												'contents.lat',
												'contents.lng',
												'contents.vote',
												'contents.like',
												'contents.alias',
												'contents.avatar',
												'contents.country',
												'contents.city',
												'contents.district',
												'contents.last_push',
												'contents.end_push'
											)
										->where('contents.active','=',1)
										->where('moderation','=','publish')
										->leftJoin('countries','contents.country','=','countries.id')
										->leftJoin('cities','contents.city','=','cities.id')
										->leftJoin('districts','contents.district','=','districts.id');
			$lat = 0;
			$lng = 0;
			//Search by keyword

			if($request->q!=''){
				$key = $request->q;
				$query_ad = $query_ad->searchAd($key);
				$query = $query->search(clear_str($key));
			}else{
				$query = $query->search(null);
			}


			//Search lấy những địa điểm gần nhất
			if($request->currentLocation){
				$pos = explode(',',$request->currentLocation);
				if(is_array($pos)){
					session()->put('currentLocation', $request->currentLocation);
					$lat = $pos[0];
					$lng = $pos[1];
					$query = $query->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
													->orderBy('line');
				}
			}else{
				if(session()->has('currentLocation')){
					$currentLocation = explode(',', session()->get('currentLocation'));
					$lat = $currentLocation[0];
					$lng = $currentLocation[1];
					$query = $query->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
													->orderBy('line');
				}else{
					$json = file_get_contents('http://ip-api.com/json/');
					$json = json_decode($json,true);
					$lat = $json['lat'];
					$lng = $json['lon'];
					$query = $query->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
													->orderBy('line');
				}
			}

			$limit = $request->limit?$request->limit:5;
			$query = $query->distinct('contents.id');
			$contents_ad = null;
			$contents = null;
			if(strlen($request->q)>2){
				$contents_ad = $query_ad->limit($limit)
															->get();
				$count_ad = $query_ad->count();
			}else{
				$count_ad = $limit;
			}
			
			if($count_ad<$limit){
				$contents = $query->limit($limit-$count_ad)
													->get();
				
				// $str_match = '';
				// if(count($arrKey)){
				// 	// foreach ($arrKey as $key => $keyword){
				// 	// 	if($key == 0){
				// 	// 		$str_match.='"';
				// 	// 		$str_match.= $keyword;
				// 	// 		$str_match.='"';
				// 	// 	}else{
				// 	// 		$str_match.=' "';
				// 	// 		$str_match.= $keyword;
				// 	// 		$str_match.='"';
				// 	// 	}
				// 	// }
				// 	$str_match.=' ';
				// 	$str_match.= $arrKey[0];
				// 	$str_match.=' ';
				// }
				// $match_score = count(explode(' ',$arrKey[0]))/2 > 1 ? count(explode(' ',$arrKey[0]))/2 : 1;
				// $match_score = 1;
				// $keyword_length = count($arrKey);
				// $match_score = $match_score>1?$match_score-1:1;
				// $keyword_length = $keyword_length>1?$keyword_length-1:1;
				// $keywords = $request->q;
				// $str_match = '';
				// // $str_match .= '"'.str_replace(' ', '+', $keywords).'"';
				// $arr_keywords = explode(' ',$keywords);
				// if(count($arr_keywords)){
				// 	foreach ($arr_keywords as $key => $keyword){
				// 			$str_match.='"+';
				// 			$str_match.= $keyword;
				// 			$str_match.='"';
				// 	}
				// }
				// $arr_more_keywords = array_keyword($keywords);
				// if(count($arr_more_keywords)){
				// 	foreach ($arr_more_keywords as $key => $keyword){
				// 			$str_match.='"+';
				// 			$str_match.= $keyword;
				// 			$str_match.='"';
				// 	}
				// }
				// // $match_score = count($arr_keywords)<5?count($arr_keywords)*0.75:count($arr_keywords)*0.76;
				// $match_score = (count($arr_keywords)+count($arr_more_keywords))*0.75;
				// $keyword_length = 0;
				// if(mb_detect_encoding($str_match)=='ASCII'){
				// 	$str_match =  utf8_encode($str_match);
				// }
				// $contents = \DB::select(utf8_encode("call searchContent(CONVERT('".$str_match."' USING UTF8),$match_score,$keyword_length,$lat,$lng)"));
				// foreach ($contents as $key => $value) {
					// echo "name: ".$value->name."<br/>";
					// echo "sum_match: ".$value->sum_match."<br/>";
					// echo "name_match: ".$value->name_match."<br/>";
					// echo "tag_match: ".$value->tag_match."<br/>";
					// echo "address_match: ".$value->address_match."<br/>";
					// echo "district_match: ".$value->district_match."<br/>";
					// echo "city_match: ".$value->city_match."<br/>";
					// echo "country_match: ".$value->country_match."<br/>";
					// echo "line: ".$value->line."<br/>";
					// echo '<br/> ===== <br/><br/><br/>';
				// }		
				// die;
			}
			// echo "call searchContent('".vn_string($str_match)."',$match_score,$keyword_length,$lat,$lng)";
			// pr($contents);die;
			
			$arrReturn = [];
			// Tính khoảng cách lại
			// if($contents){
			// 	if($request->currentLocation){
			// 		$pos = explode(',',$request->currentLocation);
			// 		if(is_array($pos)){
			// 			session()->put('currentLocation', $request->currentLocation);
			// 			$lat1 = $pos[0];
			// 			$lng1 = $pos[1];
			// 			foreach($contents as $content){
			// 				$content->line = coord2met($lat1, $lng1, $content->lat, $content->lng);
			// 			}
			// 		}
			// 	}else{
			// 		if(session()->has('currentLocation')){
			// 			$currentLocation = explode(',', session()->get('currentLocation'));
			// 			$lat1 = $currentLocation[0];
			// 			$lng1 = $currentLocation[1];
			// 			foreach($contents as $content){
			// 				$content->line = coord2met($lat1, $lng1, $content->lat, $content->lng);
			// 			}
			// 		}
			// 	}
			// }

			if($contents_ad){
				foreach($contents_ad as $content){
					$content->view_ad = $content->view_ad - 1;
					\DB::table('contents')->where('id', '=', $content->id)
                                ->update([
                                    'view_ad' => $content->view_ad
                        ]);
					$arrTmp = [];
					$arrTmp['adv'] = 1;
					$arrTmp['RestaurantUrl'] = url('/').'/'.$content->alias;
					$arrTmp['RestaurantImage'] = str_replace('img_content','img_content_thumbnail',$content->avatar);
					$arrTmp['RestaurantName'] = $content->name;
					$arrTmp['RestaurantAddress'] = $content->address.', '.$content->_district->name.', '.$content->_city->name.', '.$content->_country->name;
					if($content->line){
						if($content->line > 1000){
							$arrTmp['RestaurantDistance'] = intval($content->line/1000).'km';
						}else{
							$arrTmp['RestaurantDistance'] = intval($content->line).'m';
						}
					}else{
						$arrTmp['RestaurantDistance'] = '';
					}
					$arrReturn[] = $arrTmp;
				}
			}

			if($contents){
				foreach($contents as $content){
					$arrTmp = [];
					$arrTmp['adv'] = 0;
					if(strtotime($content->end_push)>time()){
						$arrTmp['adv'] = 1;
					}
					$arrTmp['RestaurantUrl'] = url('/').'/'.$content->alias;
					$arrTmp['RestaurantImage'] = str_replace('img_content','img_content_thumbnail',$content->avatar);
					$arrTmp['RestaurantName'] = $content->name;
					$arrTmp['RestaurantAddress'] = $content->address.', '.$content->_district->name.', '.$content->_city->name.', '.$content->_country->name;
					//$arrTmp['RestaurantAddress'] = $content->address.', '.$content->district_name.', '.$content->city_name.', '.$content->country_name;
					if($content->line){
						if($content->line > 1000){
							$arrTmp['RestaurantDistance'] = intval($content->line/1000).'km';
						}else{
							$arrTmp['RestaurantDistance'] = intval($content->line).'m';
						}
					}else{
						$arrTmp['RestaurantDistance'] = '';
					}
					$arrReturn[] = $arrTmp;
				}				
			}

			// $arrReturn = array_slice($arrReturn,0,$limit);

			if($request->q==''){
				$suggest = HistorySearch::select('keyword')
																->where("ip","=", getUserIP())
																->where("agent","=", $_SERVER['HTTP_USER_AGENT'])
																// ->orderBy('created_at','DESC')
																->orderBy('id','DESC')
																->limit(5)
																->get();
				if($suggest){
					$suggest = array_reverse($suggest->toArray());
					foreach ($suggest as $key => $value) {
						$arrTmp = [];
						$arrTmp['suggest'] = 1;
						$arrTmp['key'] = $value['keyword'];
						array_unshift($arrReturn,$arrTmp);
					}
				}
			}
			
			//////
			if(count($arrReturn) && $request->q!=''){
				HistorySearch::updateOrCreate([
					'keyword'	=> $request->q,
					'ip'     	=> getUserIP(),
					'agent'  	=> $_SERVER['HTTP_USER_AGENT']
				],[
					'keyword'	=> $request->q,
					'ip'     	=> getUserIP(),
					'agent'  	=> $_SERVER['HTTP_USER_AGENT']
				]);
			}



			// if($request->q!=''){
			// 	$key = $request->q;
			// 	$count_str = strlen($key)*1.5;
			// 	// $arr_test['start'] = microtime(true);
			// 	$suggest = Suggest::select('suggest_keyword.keyword')
			// 										->where("suggest_keyword.keyword","LIKE",$key."_%")
			// 										// ->selectRaw("MATCH (suggest_keyword.keyword) AGAINST ('".$key."' IN BOOLEAN MODE) AS key_match")
			// 										// ->selectRaw("length(suggest_keyword.keyword) as key_length")
			// 										->selectRaw("RAND () as id_rand ")
			// 										// ->whereRaw("MATCH (suggest_keyword.keyword) AGAINST ('".$key."' IN BOOLEAN MODE) >= 1")
			// 										// ->where("suggest_keyword.keyword",'like','%'.$key.'%')
			// 										// ->whereRaw("length(suggest_keyword.keyword) >= ".$count_str)
			// 										->orderBy('weight','ASC')
			// 										// ->orderBy('key_match','DESC')
			// 										->orderBy('id_rand','DESC')
			// 										->limit(3)
			// 										->get();
			// 	if($suggest){
			// 		foreach ($suggest as $key => $value) {
			// 			$arrTmp = [];
			// 			$arrTmp['suggest'] = 1;
			// 			$arrTmp['key'] = $value->keyword;
			// 			array_unshift($arrReturn,$arrTmp);
			// 		}
			// 	}

			// 	// $arr_test['end'] = microtime(true);
			// 	// $arr_test['total'] = $arr_test['end'] - $arr_test['start'];
			// 	// pr($arr_test);die;
			// }

			return response()->json($arrReturn);
		}
	}

	public function postloadCategoryItemNew(Request $request){
		if($request->ajax()){
			$arrReturn = [
				'category_item'=>'',
				'service'=>''
			];
			if($request->category){
				$category_items = CategoryItem::where('category_id','=',$request->category)
																			->where('active','=',1)
																			->where('deleted','=',0)
																			->orderBy('weight')
																			->get();
				$category_item_selected = $request->category_item_selected?$request->category_item_selected:[];
				$html = '';
				foreach ($category_items as $key => $category_item) {
					if($request->category_item_selected && in_array($category_item->id, $request->category_item_selected)){
						$html .= '<option selected value="'.$category_item->id.'">'.$category_item->name.'</option>';
					}else{
						$html .= '<option value="'.$category_item->id.'">'.$category_item->name.'</option>';
					}
				}
				$arrReturn['category_item'] = $html;

				$services = ServiceItem::select('service_items.id','service_items.name')
														->leftJoin('category_service','id_service_item','service_items.id')
														->where('id_category','=',$request->category)
														->where('active','=',1)
														->get();
				$service_selected = $request->service_selected?$request->service_selected:[];
				$html = '';
				foreach ($services as $key => $service) {
					if($request->service_selected && in_array($service->id, $request->service_selected)){
						$html .= '<option selected value="'.$service->id.'">'.$service->name.'</option>';
					}else{
						$html .= '<option value="'.$service->id.'">'.$service->name.'</option>';
					}
				}
				$arrReturn['service'] = $html;

			}
			return response($arrReturn);
		}else{
			abort(404);
		}
	}
}
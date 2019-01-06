<?php
namespace App\Http\Controllers\Discount;
use App\Models\Location\Category;
use App\Models\Location\CategoryItem;
use App\Models\Location\Content;
use App\Models\Location\CategoryContent;
use App\Models\Location\Country;
use App\Models\Location\City;
use App\Models\Location\District;
use Illuminate\Http\Request;
class  CategoryController extends BaseController {

	public function getContentByCategory($category, $request, $param){
		\DB::enableQueryLog();
		$arrReturn = ['totalPage'=>0, 'html'=>''];
		$arrReturn['cities'] = '<option value="all">'.trans('Location'.DS.'category.city').'</option>';
		$arrReturn['districts'] = '<option value="all">'.trans('Location'.DS.'category.district').'</option>';
		$arrData = [];
		$arrData['category'] = $category;
		$arrData['category_item'] = null;
		$arrData['country'] = null;
		$arrData['city'] = null;
		$arrData['district'] = null;
		$arrData['countries'] = null;
		$arrData['cities'] = null;
		$arrData['districts'] = null;
		$arrData['contents'] = null;
		$arrData['countries'] = Country::orderBy('weight')->get();
		$arrData['extra_types'] = null;
		$arrData['current_extra_type'] = null;
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
		$query = Content::select(
												'contents.id',
												'contents.name',
												'contents.tag',
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
										->where('moderation','=','publish')
										->where('contents.active','=',1)
										->where('contents.id_category','=',$category->id)
										->with('_discount_basic')
										->orderBy('contents.last_push','desc')
										->orderBy('contents.end_push','desc')
										->orderBy('contents.updated_at','desc');
		if($request->extra_type){
			$arrData['current_extra_type'] = $request->extra_type;
		}
		if($arrData['current_extra_type']){
			$query->where('contents.extra_type','=',$arrData['current_extra_type']);
		}

		if(isset($param['category_item'])){
			$category_item =  CategoryItem::where('alias','=',$param['category_item'])
																		->first();
			if($category_item){
				$arrData['category_item'] = $category_item;
				$query->leftJoin('category_content','contents.id','=','category_content.id_content')
							->where('category_content.id_category_item','=',$category_item->id);
			}
		}
		if(session()->has('currentLocation') && !isset($param['country'])){
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

				$country_str = $location['country political'];
				$city_str = $location['administrative_area_level_1 political'];
				$district_str = $location['administrative_area_level_2 political'];

				$country = Country::select('countries.*')
													->selectRaw("MATCH(`name`) AGAINST ('".$country_str." \"".$country_str."\"' in boolean mode) as math_score")
													->whereRaw("MATCH(`name`) AGAINST ('".$country_str." \"".$country_str."\"' in boolean mode) >1")
													->orderBy('math_score', 'desc')
													->orwhere('name','like','%'.$country_str.'%')->first();

				$city = City::select('cities.*')
													->selectRaw("MATCH(`name`) AGAINST ('".$city_str." \"".$city_str."\"' in boolean mode) as math_score")
													->whereRaw("MATCH(`name`) AGAINST ('".$city_str." \"".$city_str."\"' in boolean mode) >1")
													->orderBy('math_score', 'desc')
													->orwhere('name','like','%'.$city_str.'%')->first();

				$district = District::select('districts.*')
													->selectRaw("MATCH(`name`) AGAINST ('".$district_str." \"".$district_str."\"' in boolean mode) as math_score")
													->whereRaw("MATCH(`name`) AGAINST ('".$district_str." \"".$district_str."\"' in boolean mode) >1")
													->orderBy('math_score', 'desc')
													->orwhere('name','like','%'.$district_str.'%')->first();
				if($country){
					$arrData['country'] = $country;
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
				if($city){
					$arrData['city'] = $city;
					$arrData['districts'] = District::where('id_city','=',$city->id)->orderBy('weight')->get();
					foreach ($arrData['districts'] as $key => $district_one) {
						if(isset($param['district']) && $param['district'] == $district_one->alias){
							$arrReturn['districts'] .='<option value="'.$district_one->alias.'" selected>'.$district_one->name.'</option>';
						}else{
							$arrReturn['districts'] .='<option value="'.$district_one->alias.'">'.$district_one->name.'</option>';
						}
					}
					$query->where('contents.city','=',$city->id);
				}
				if($district){
					$arrData['district'] = $district;
					$query->where('contents.district','=',$district->id);
				}
			}
		}

		if(isset($param['country'])){
			$country = Country::where('alias','=',$param['country'])->first();
			if($country){
				$arrData['country'] = $country;
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
		if(isset($param['city'])){
			$city = City::where('alias','=',$param['city'])->first();
			if($city){
				$arrData['city'] = $city;
				$arrData['districts'] = District::where('id_city','=',$city->id)->orderBy('weight')->get();
				foreach ($arrData['districts'] as $key => $district_one) {
					if(isset($param['district']) && $param['district'] == $district_one->alias){
						$arrReturn['districts'] .='<option value="'.$district_one->alias.'" selected>'.$district_one->name.'</option>';
					}else{
						$arrReturn['districts'] .='<option value="'.$district_one->alias.'">'.$district_one->name.'</option>';
					}
				}
				$query->where('contents.city','=',$city->id);
			}
		}
		if(isset($param['district'])){
			$district = District::where('alias','=',$param['district'])->first();
			if($district){
				$arrData['district'] = $district;
				$query->where('contents.district','=',$district->id);
			}
		}
		$query = $query->whereHas('_discount_basic');
		$query = $query->distinct('contents.id'); 
		$arrReturn['total'] = $query->count('contents.id');
		$arrData['totalPage'] = ceil($arrReturn['total']/24);
		if($request->page){
			$arrData['contents'] = $query->limit(24)->offset(($request->page-1)*24)->get();
		}else{
			$arrData['contents'] = $query->limit(24)->offset(0)->get();
		}
		// dd(\DB::getQueryLog());
		if($request->ajax()){
			$arrReturn['currentPage'] = intval($request->page);
			$arrReturn['count'] = count($arrData['contents']);
			$arrReturn['totalPage'] = ceil($arrReturn['total']/24);
			$arrReturn['nextPage'] = $arrReturn['currentPage']<$arrReturn['totalPage']?$request->page+1:$arrReturn['totalPage'];
			$arrReturn['html'] = view('Discount.category.category_list', ['contents'=>$arrData['contents']])->render();
			// echo json_encode($arrReturn);
			return response($arrReturn);
		}else{
			$this->view->content = view('Discount.category.category',$arrData);
			return $this->setContent();
		}
	}
	public function getContentByCategoryItem($category, $category_item, $request){
		\DB::enableQueryLog();
		$arrData = [];
		$arrData['category'] = $category;
		$arrData['category_item'] = $category_item;
		$contents = null;
		$count = 0;
		$arrData['extra_types'] = null;
		$arrData['current_extra_type'] = null;
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

		$contents = Content::select(
												'contents.id',
												'contents.name',
												'contents.tag',
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
										->where('contents.id_category','=',$category->id)
										// ->orderBy('contents.last_push','desc')
										// ->orderBy('contents.end_push','desc')
										->with('_discount_basic')
										// ->whereHas('_discount_basic')
										->with('_country')
										->with('_city')
										->with('_district');
		if(session()->has('currentLocation')){
			$currentLocation = explode(',', session()->get('currentLocation'));
			$lat = $currentLocation[0];
			$lng = $currentLocation[1];
			$contents = $contents->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
												->whereRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) <= 500
													")
												->orderBy('line');
		}else{
			$json = file_get_contents('http://ip-api.com/json/');
			$json = json_decode($json,true);
			$lat = $json['latitude'];
			$lng = $json['longitude'];
			$contents = $contents->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
												->whereRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) <= 500
													")
												->orderBy('line');
		}

		if($category_item){
			$contents =  $contents->leftJoin('category_content','contents.id','=','category_content.id_content')
														->where('category_content.id_category_item','=',$category_item->id);
		}

		if($arrData['current_extra_type']){
			$contents = $contents->where('contents.extra_type','=',$arrData['current_extra_type']);
		}

		// $contents =  $contents->rightJoin(\Config::get('database.connections.discount.database').'.discount_content','contents.id','=','discount_content.id_content');

		$contents = $contents->whereHas('_discount_basic');
		$count = 0;
		$count = $contents->count('contents.id');

		$contents = $contents->distinct('contents.id')
												->take(30)
												->skip(0)
												->get();

		$arrData['total'] = $count;
		$arr_json_content = [];
		// dd(\DB::getQueryLog());
		// dd($contents);
		if($contents){
			foreach ($contents as $key => $value) {
				$tmp = [];
				$tmp['center'] = implode(',',[$value->lat, $value->lng]);
				$tmp['id'] = $value->id;
				$tmp['title'] = $value->name;
				$tmp['price'] = $value->name;
				$tmp['line'] = $value->line;
				$tmp['url'] = url('/').'/'.$value->alias;
				if($category->marker){
					$tmp['urlImage'] = $category->marker;
				}else{
					if($category->image){
						$tmp['urlImage'] = $category->image;
					}else{
						$tmp['urlImage'] = '/img_default/marker.svg';
					}
				}
				$tmp['posthref'] = url('/').'/'.$value->alias;
				$tmp['postImg'] = $value->avatar;
				$tmp['postAddress'] = $value->address;
				$tmp['postLike'] = $value->like;
				$tmp['postStart'] = ($value->vote/5)*100;
				if(count($value->_discount_basic)){
					$discount = $value->_discount_basic[0];
					switch ($discount->type) {
						case 'other':
							$tmp['urlgift'] = '/frontend/assets/img/icon/gift.png';
							break;
						case 'percent':
							$tmp['saleNumber'] = $discount->from_percent.'%';
							break;
						case 'percent_fromto':
							$tmp['saleNumber'] = $discount->to_percent.'%';
							break;
						case 'price':
							$tmp['saleNumber'] = $discount->from_price.' '.$discount->currency;
							break;
						case 'price_fromto':
							$tmp['saleNumber'] = $discount->from_price.' '.$discount->currency;
							break;
						default:
							$tmp['urlgift'] = '/frontend/assets/img/icon/gift.png';
							break;
					}
				}
				$arr_json_content[] = $tmp;
			}
		}
		$arrData['json'] = json_encode($arr_json_content);
		$arrData['contents']=$contents;
		// dd($arrData);
		$this->view->content = view('Discount.category.category_item',$arrData);
		return $this->setContent();
	}

	public function postAjaxContentByCategoryItem(Request $request){
		$arrReturn = [];
		$category_item_id = $request->category_item_id;
		$category_id = $request->category_id;
		$category_item = CategoryItem::where('id','=',$category_item_id)->first();
		$category = Category::where('id','=',$category_id)->first();
		$page = $request->page;
		$lat = $request->lat;
		$lng = $request->lng;
		$arrReturn['extra_types'] = null;
		$arrReturn['current_extra_type'] = null;
		$arrReturn['extra_types'] = Content::where('extra_type','!=',null)
																		 ->where('contents.id_category','=',$category->id)
																		 ->groupBy('extra_type')
																		 ->orderBy('extra_type')
																		 ->get()
																		 ->pluck('extra_type');
		if($arrReturn['extra_types']){
			$arrReturn['extra_types'] = $arrReturn['extra_types']->toArray();
			rsort($arrReturn['extra_types']);
			if(isset($arrReturn['extra_types'][0])){
				$arrReturn['current_extra_type'] = $arrReturn['extra_types'][0];
			}
		}
		if($request->extra_type){
			$arrReturn['current_extra_type'] = $request->extra_type;
		}
		$contents = Content::select(
												'contents.id',
												'contents.name',
												'contents.tag',
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
										->where('contents.id_category','=',$category_id)
										// ->orderBy('contents.last_push','desc')
										// ->orderBy('contents.end_push','desc')
										->with('_discount_basic')
										->with('_country')
										->with('_city')
										->with('_district');
		$contents = $contents->selectRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) AS line
													")
												->whereRaw("
													(SQRT(
														POW((`lng` - '+$lng+')*(3.14159265359/180)*COS((`lat` + '+$lat+')*(3.14159265359/180)/2),2)
														+
														POW((`lat` - '+$lat+')*(3.14159265359/180),2)
														)*6371000) <= 500
													")
												->orderBy('line');
		if($category_item_id != 0){
			$contents =  $contents->leftJoin('category_content','contents.id','=','category_content.id_content')
														->where('category_content.id_category_item','=',$category_item_id);
		}

		if($arrReturn['current_extra_type']){
			$contents = $contents->where('contents.extra_type','=',$arrReturn['current_extra_type']);
		}

		// $contents =  $contents->rightJoin(\Config::get('database.connections.discount.database').'.discount_content','contents.id','=','discount_content.id_content');
		$contents = $contents->whereHas('_discount_basic');
		$count = 0;
		$count = $contents->count('contents.id');
		$contents = $contents->distinct('contents.id')
													->take(30)
													->skip(($page-1)*30)
													->get();
		$arrReturn['total'] = $count;
		$arrReturn['totalPage'] = intval(($count/30)+1);
		$arrReturn['nextPage'] = $page<($count/30)+1?$page+1:intval(($count/30)+1);
		$arr_json_content = [];
		$far_away = 0;
		$html = '';
		if($contents){
			foreach ($contents as $key => $value) {
				$tmp = [];
				$tmp['center'] = implode(',',[$value->lat, $value->lng]);
				$tmp['id'] = $value->id;
				$tmp['title'] = $value->name;
				// $tmp['price'] = $value->name;
				$tmp['line'] = $value->line;
				$tmp['url'] = url('/').'/'.$value->alias;
				if($category->marker){
					$tmp['urlImage'] = $category->marker;
				}else{
					if($category->image){
						$tmp['urlImage'] = $category->image;
					}else{
						$tmp['urlImage'] = '/img_default/marker.svg';
					}
				}
				$tmp['posthref'] = url('/').'/'.$value->alias;
				$tmp['postImg'] = $value->avatar;
				$tmp['postAddress'] = $value->address;
				$tmp['postLike'] = $value->like;
				$tmp['postStart'] = ($value->vote/5)*100;
				if(count($value->_discount_basic)){
					$discount = $value->_discount_basic[0];
					switch ($discount->type) {
						case 'other':
							$tmp['urlgift'] = '/frontend/assets/img/icon/gift.png';
							break;
						case 'percent':
							$tmp['saleNumber'] = $discount->from_percent.'%';
							break;
						case 'percent_fromto':
							$tmp['saleNumber'] = $discount->to_percent.'%';
							break;
						case 'price':
							$text_price = '';
							if($discount->from_price>999999){
								$text_price = intval($discount->from_price/1000000).'M';
							}else{
								if($discount->from_price>999){
									$text_price = intval($discount->from_price/1000).'K';
								}else{
									$text_price = $discount->from_price;
								}
							}
							$tmp['saleNumber'] = $text_price;
							break;
						case 'price_fromto':
							$text_price = '';
							if($discount->from_price>999999){
								$text_price = intval($discount->from_price/1000000).'M';
							}else{
								if($discount->from_price>999){
									$text_price = intval($discount->from_price/1000).'K';
								}else{
									$text_price = $discount->from_price;
								}
							}
							$tmp['saleNumber'] = $text_price;
							break;
						default:
							$tmp['urlgift'] = '/frontend/assets/img/icon/gift.png';
							break;
					}
				}
				$arr_json_content[] = $tmp;
			}
		}
		$arrReturn['json'] = json_encode($arr_json_content);
		$html = view('Discount.category.content_item_list',['contents'=>$contents])->render();
		$arrReturn['html'] = $html;
		return response($arrReturn);
	}

	public function getLocation(){
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    if($ip!='127.0.0.1')
			$json = file_get_contents('http://ip-api.com/json/'.$ip);
		else
			$json = file_get_contents('http://ip-api.com/json/');
		return response($json);
	}
}

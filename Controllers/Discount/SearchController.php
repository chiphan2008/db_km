<?php
namespace App\Http\Controllers\Discount;
use Illuminate\Http\Request;
use App\Models\Location\Content;
use App\Models\Location\CategoryContent;
use App\Models\Location\Country;
use App\Models\Location\City;
use App\Models\Location\District;
use App\Models\Location\Category;
use App\Models\Location\CategoryItem;
use App\Models\Discount\Discount;
use App\Models\Discount\DiscountContent;
use App\Models\Location\Suggest;
use Carbon\Carbon;
class SearchController extends BaseController
{
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

		$arrData['category'] = null;
		$arrData['category_items'] = null;
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

		$list_km_running = [];
    $list_km_running = Discount::where('active',1)
                               ->where('date_from','<=',Carbon::now())
                               ->where('date_to','>=',Carbon::now())->pluck('id');
    $list_content_km = [];
    $list_content_km = DiscountContent::whereIn('discount_id',$list_km_running)
                                      ->pluck('id_content');


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
										->where('contents.active','=',1)
										->where('moderation','=','publish')
										->with('_discount')
										->whereIn('contents.id',$list_content_km)
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
				$arrData['category'] = $category;
				$query->where('contents.id_category','=',$category->id);
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
		}


		if($request->country){
			$country = Country::where('id','=',$request->country)->first();
			if($country){
				$arrData['country'] = $country;
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
				$lat = $json['latitude'];
				$lng = $json['longitude'];
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
		if($request->page){
			$contents = $query->limit(20)
					->offset(($request->page-1)*20)
					->get();
		}else{
			$contents = $query->limit(20)
					->offset(0)
					->get();
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

		// foreach ($contents as $key => $value) {
		// 	echo "name: ".$value->name."<br/>";
		// 	echo "Tag: ".$value->tag."<br/>";
		// 	// echo "sum_match: ".($value->name_match+$value->address_match+$value->district_match+$value->city_match)."<br/>";
		// 	echo "name_match: ".$value->name_match."<br/>";
		// 	echo "tag_match: ".$value->tag_match."<br/>";
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
		// dd(\Db::getQueryLog());
		if($request->ajax()){
			$arrReturn['html'] = view('Discount.search.search_list', ['contents'=>$contents])->render();
			return response()->json($arrReturn);
		}else{
			$this->view->content = view('Discount.search.search', $arrData);
			return $this->setContent();
		}
	}

	public function postLoadCity(Request $request){
		if($request->ajax()){
			$html = '<option value="">Chọn thành phố</option>';
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
			$html = '<option value="">Chọn quận huyện</option>';
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
	// dd($request->all());
		if(!$request->ajax()){
			abort(404);
		}else{
			$list_km_running = [];
	    $list_km_running = Discount::where('active',1)
	                               ->where('date_from','<=',Carbon::now())
	                               ->where('date_to','>=',Carbon::now())->pluck('id');
	    $list_content_km = [];
	    $list_content_km = DiscountContent::whereIn('discount_id',$list_km_running)
	                                      ->pluck('id_content');
			$query_ad = Content::select(
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
												'contents.view_ad',
												'contents.end_push'
											)
										->where('contents.active','=',1)
										->where('moderation','=','publish')
										->whereIn('contents.id',$list_content_km)
										->with('_discount')
										->leftJoin('countries','contents.country','=','countries.id')
										->leftJoin('cities','contents.city','=','cities.id')
										->leftJoin('districts','contents.district','=','districts.id');

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
										->where('contents.active','=',1)
										->where('moderation','=','publish')
										->whereIn('contents.id',$list_content_km)
										->with('_discount')
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
					$lat = $json['latitude'];
					$lng = $json['longitude'];
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
			$contents_ad = null;
			$contents_ad = $query_ad->limit(5)
															->get();
			$count_ad = $query_ad->count();
			if($count_ad<5){
				$contents = $query->limit(5-$count_ad)
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

			$arrReturn = array_slice($arrReturn,0,5);

			if($request->q!=''){
				$key = $request->q;
				$count_str = strlen($key)*1.5;
				// $arr_test['start'] = microtime(true);
				$suggest = Suggest::select('suggest_keyword.keyword')
													->selectRaw("MATCH (suggest_keyword.keyword) AGAINST ('".$key."' IN BOOLEAN MODE) AS key_match")
													// ->selectRaw("length(suggest_keyword.keyword) as key_length")
													->selectRaw("RAND () as id_rand ")
													// ->whereRaw("MATCH (suggest_keyword.keyword) AGAINST ('".$key."' IN BOOLEAN MODE) >= 1")
													->where("suggest_keyword.keyword",'like','%'.$key.'%')
													->whereRaw("length(suggest_keyword.keyword) >= ".$count_str)
													->orderBy('weight','ASC')
													->orderBy('key_match','DESC')
													->orderBy('id_rand','DESC')
													->limit(3)
													->get();
				if($suggest){
					foreach ($suggest as $key => $value) {
						$arrTmp = [];
						$arrTmp['suggest'] = 1;
						$arrTmp['key'] = $value->keyword;
						array_unshift($arrReturn,$arrTmp);
					}
				}

				// $arr_test['end'] = microtime(true);
				// $arr_test['total'] = $arr_test['end'] - $arr_test['start'];
				// pr($arr_test);die;
			}

			return response()->json($arrReturn);
		}
	}
}
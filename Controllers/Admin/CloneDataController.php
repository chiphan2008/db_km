<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\Category;
use App\Models\Location\CategoryContent;
use App\Models\Location\CategoryItem;
use App\Models\Location\CategoryService;
use App\Models\Location\City;
use App\Models\Location\Setting;
use App\Models\Location\User;
use App\Models\Location\Client;
use App\Models\Location\Country;
use App\Models\Location\District;
use App\Models\Location\Group;
use App\Models\Location\GroupContent;
use App\Models\Location\LinkContent;
use App\Models\Location\NoteContent;
use App\Models\Location\Notifi;
use App\Models\Location\NotifiContent;
use App\Models\Location\RoleUser;
use App\Models\Location\ServiceContent;
use App\Models\Location\ServiceItem;
use App\Models\Location\Product;
use App\Models\Location\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Models\Location\CTV;
use App\Models\Location\Daily;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Location\Content;
use Illuminate\Support\Facades\Auth;
use App\Models\Location\ImageSpace;
use App\Models\Location\ImageMenu;
use App\Models\Location\DateOpen;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\MessageBag;
use Intervention\Image\Facades\Image;
use Validator;
use Excel;
use App\Models\Location\Jobs\ImportExcel;

class CloneDataController extends BaseController {
	public function getThongTinCongTy(){
		$link = "http://www.thongtincongty.com/";
		$html = self::getData($link);
		$list_result = new \DOMXPath($html);
    $listCities = $list_result->query('//div[@class="list-group"]/a');
    $arr_city = [];
    foreach ($listCities as $key => $city) {
    	$name  = $city->attributes->item(1)->nodeValue;
    	$name  = str_replace('Tỉnh ', "", $name);
    	$name  = str_replace('Thành phố ', "", $name);
    	$tmp['name'] = $name;
    	$tmp['link'] = $city->attributes->item(0)->nodeValue;
    	$arr_city[] = $tmp;
    }
    $arr_name_sort = [];
    foreach ($arr_city as $key => $value) {
    	$arr_name_sort[] = vn_string($value['name']);
    }
   	array_multisort($arr_name_sort, SORT_ASC, $arr_city);

    return view('Admin.clone.thongtincongty', [
            'arr_city' => $arr_city,
		]);
	}

	public function postThongTinCongTy(Request $request){
		set_time_limit(0);
		$link_city = $request->link_city;
		
		$city = $request->city;
		$from_page = $request->from_page;
		$to_page = $request->to_page;
		$moderation = $request->moderation;
		$date_created = $request->date_created?$request->date_created:Carbon::now()->toDateString();

		for($i = $from_page; $i <= $to_page; $i++){
			$link = $link_city."?page=".$i;
			$html = self::getData($link);
			$data['link'] = $link;
			$list_result = new \DOMXPath($html);
	    $listCompany = $list_result->query('//div[@class="search-results"]');
	    foreach ($listCompany as $key => $company) {
	    	$data['name'] = $company->childNodes->item(1)->nodeValue;
	    	$address = trim($company->childNodes->item(3)->nodeValue);
	    	$regex = "/Địa chỉ:(.){5,}$/";
	    	preg_match_all($regex, $address, $arr_address);
	    	$data['address'] = trim(str_replace("Địa chỉ:", "", $arr_address[0][0]));
	    	$data['moderation'] = $moderation;
	    	$data['date_created'] = $date_created;
	    	self::createContentFromThongTinCongTy($data);
	    	sleep(rand(2,5));
	    }
	    sleep(rand(2,5));
		}
		return redirect()->route('clone_thongtincongty')->with([
                'errorInsert' => session()->get('errorInsert'),
                'successInsert' => 'Insert data thành công',
    ])->withInput();
	}

	public function getPageThongTinCongTy(Request $request){
		$link = $request->link;
		$html = self::getData($link);
		$list_result = new \DOMXPath($html);
    $lastPage = $list_result->query('(//ul[@class="pagination"]/li)[last()]');
    $page_return = 0;
    foreach ($lastPage as $key => $page) {
    	$page_return = intval(str_replace($link."?page=", "", $page->childNodes->item(0)->attributes->item(0)->nodeValue));
    }
    return $page_return;
	}

	function getData($link){
		$ch = curl_init($link);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
    curl_setopt($ch, CURLOPT_REFERER, LOCATION_URL);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36'));
    $content = curl_exec($ch);
    curl_close($ch);
    $d = new \DOMDocument();
    @$d->loadHTML($content);
    return $d;
	}


	public function createContentFromThongTinCongTy($data){
		$value = [];
		$value['link'] = $data['link'];
		$value['name'] = $data['name'];
		$value['alias'] = str_slug(clear_str(vn_string($value['name'])));
		$value['country'] = 1;

		$arr_address = explode(",", $data['address']);
		$city_str = trim($arr_address[count($arr_address) -1]);
		$district_str = trim($arr_address[count($arr_address) -2]);
		unset($arr_address[count($arr_address) -1]);
		unset($arr_address[count($arr_address) -1]);
		$address_str = implode(',', $arr_address);
		$value['address'] = $address_str;
		if ($city_str == 'TP Hồ Chí Minh') {
        $value['city'] = 1;
        $city = City::find(1);
    } else {
        $city = City::select('cities.*')
                        ->selectRaw("MATCH(`name`) AGAINST ('" . $city_str . " \"" . $city_str . "\"' in boolean mode) as math_score")
                        ->whereRaw("MATCH(`name`) AGAINST ('" . $city_str . " \"" . $city_str . "\"' in boolean mode) >1")
                        ->orderBy('math_score', 'desc')
                        ->orwhere('name', 'like', '%' . $city_str . '%')->first();
        $value['city'] = $city->id;
    }
    $district = District::select('districts.*')
                                ->selectRaw("MATCH(`name`) AGAINST ('" . $district_str . " \"" . $district_str . "\"' in boolean mode) as math_score")
                                ->whereRaw("MATCH(`name`) AGAINST ('" . $district_str . " \"" . $district_str . "\"' in boolean mode) > 0")
                                ->where('id_city', '=', $value['city'])
                                ->orderBy('math_score', 'desc')
                                ->first();
    $value['district'] = $district->id;
		
		$check_content = Content::where('alias',$value['alias'])->count();
		if($check_content){
			$value['alias'] = str_slug(clear_str(vn_string($value['name']))).'-'.str_slug(clear_str(vn_string($district->name)));;
		}

		$value['id_category'] = 46;
		$value['category_item'] = [399];

		$value['avatar'] = 'https://kingmap.vn/upload/category/1527479763.svg';
		$value['moderation'] = $data['moderation'];
		$value['date'] = $data['date_created'];
		$value['open_from'] = '08:00:00';
		$value['open_to']   = '17:00:00';

    $search_str = implode(',', array_reverse(explode(',', $data['address'])));

		$link_get_location = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($search_str).'&key=AIzaSyCCCOoPlN2D-mfrYEMWkz-eN7MZnOsnZ44';
		$res = file_get_contents($link_get_location);
		$res = json_decode($res,true);

		if(count($res['results']) && $res['results'][0] && $res['results'][0]['geometry'] && $res['results'][0]['geometry']['location']){
					$value['lat'] = $res['results'][0]['geometry']['location']['lat'];
					$value['lng'] = $res['results'][0]['geometry']['location']['lng'];
		}else{
			$value['lat'] = 10.806250;
			$value['lng'] = 106.714622;
			// Địa chỉ tại Kingmap
		}
		
		self::save_content_by_site($value);
	}

	public function save_content_by_site($data) {
      $rules = [
          'name' => 'required|unique:contents,name',
          'alias' => 'required|unique:contents,alias',
          'id_category' => 'required',
          'category_item' => 'required',
          'country' => 'required',
          'city' => 'required',
          'district' => 'required',
          'avatar' => 'required',
          'address' => 'required',
          'lat' => 'required',
          'lng' => 'required',
      ];
      if (isset($data['site']) && $data['site'] == 'vietbando') {
          unset($rules['category_item']);
          unset($rules['name']);
          $rules['lat'] = 'required|unique:contents,lat';
          $rules['lng'] = 'required|unique:contents,lng';
      }
      if (isset($data['site']) && $data['site'] == 'bank') {
          unset($rules['price_from']);
          unset($rules['price_to']);
      }

      $messages = [
          'name.required' => trans('valid.name_required'),
          'name.unique' => trans('valid.name_unique'),
          'alias.required' => trans('valid.alias_required'),
          'alias.unique' => trans('valid.alias_unique'),
          'id_category.required' => trans('valid.id_category_required'),
          'category_item.required' => trans('valid.category_item_required'),
          'price_from.required' => trans('valid.price_from_required'),
          'price_to.required' => trans('valid.price_to_required'),
          'country.required' => trans('valid.country_required'),
          'city.required' => trans('valid.city_required'),
          'district.required' => trans('valid.location_required'),
          'address.required' => trans('valid.address_required'),
          'avatar.required' => trans('valid.avatar_required'),
          'lat.required' => trans('valid.lat_required'),
          'lng.required' => trans('valid.lng_required'),
          'lat.unique' => trans('valid.lat_unique'),
          'lng.unique' => trans('valid.lng_unique'),
      ];

      $validator = Validator::make($data, $rules, $messages);
      if ($validator->fails()) {
          if (isset($data['site'])) {
              $err = $data['name'] . ' - <span style="color: red">Lỗi</span> - ' . $validator->errors()->first() . '<br>';
          } else {
              $err = $data['link'] . ' - <span style="color: red">Lỗi</span> - ' . $validator->errors()->first() . '<br>';
          }
          $mess_err = session()->get('errorInsert');
          $mess_err .= $err;
          session()->flash('errorInsert', $mess_err);
      } else {
          $object = (object) $data;

          $content = Content::create([
                      'name' => $object->name,
                      'alias' => $object->alias,
                      'id_category' => $object->id_category,
                      'country' => $object->country,
                      'city' => $object->city,
                      'district' => $object->district,
                      'address' => $object->address,
                      'tag' => isset($object->tag) ? $object->tag : '',
                      'phone' => isset($object->phone) ? $object->phone : '',
                      'price_from' => isset($object->price_from) ? $object->price_from : 0,
                      'price_to' => isset($object->price_to) ? $object->price_to : 0,
                      'currency' => 'VND',
                      'website' => isset($object->website) ? $object->website : '',
                      'email' => isset($object->email) ? $object->email : '',
                      'description' => isset($object->description) ? $object->description : '',
                      'avatar' => $object->avatar,
                      'vote' => 0,
                      'like' => 0,
                      'type_user' => 1,
                      'extra_type' => isset($object->extra_type) ? $object->extra_type : null,
                      'active' => $object->moderation == 'publish' ? 1 : 0,
                      'lat' => $object->lat,
                      'lng' => $object->lng,
                      'unique_code' => isset($object->unique_code) ? $object->unique_code : null,
                      'moderation' => $object->moderation,
                      'created_by' => 1,
                      'updated_by' => 1,
          ]);

          \DB::table('contents')->where('id', '=', $content->id)
                  ->update([
                      'created_at' => new Carbon($object->date),
                      'updated_at' => new Carbon($object->date)
          ]);

          $lastIdContent = $content->id;

          if (isset($object->site) && $object->site == 'bank') {
              if ($object->extra_type == 'BANK') {
                  DateOpen::create([
                      'id_content' => $lastIdContent,
                      'date_from' => 1,
                      'date_to' => 5,
                      'open_from' => date("H:i:s", strtotime('08:00:00')),
                      'open_to' => date("H:i:s", strtotime('16:30:00')),
                  ]);

                  DateOpen::create([
                      'id_content' => $lastIdContent,
                      'date_from' => 6,
                      'date_to' => 6,
                      'open_from' => date("H:i:s", strtotime('08:00:00')),
                      'open_to' => date("H:i:s", strtotime('11:30:00')),
                  ]);
              } else {
                  DateOpen::create([
                      'id_content' => $lastIdContent,
                      'date_from' => 1,
                      'date_to' => 0,
                      'open_from' => date("H:i:s", strtotime('00:00:00')),
                      'open_to' => date("H:i:s", strtotime('00:00:00')),
                  ]);
              }
          } else {
              if ($object->open_from) {
                  DateOpen::create([
                      'id_content' => $lastIdContent,
                      'date_from' => 1,
                      'date_to' => 6,
                      'open_from' => date("H:i:s", strtotime($object->open_from)),
                      'open_to' => date("H:i:s", strtotime($object->open_to)),
                  ]);
              }
          }

          if (isset($object->category_item)) {
              foreach ($object->category_item as $value) {
                  CategoryContent::create([
                      'id_content' => $lastIdContent,
                      'id_category_item' => $value,
                  ]);
              }
          }

          if (isset($object->service)) {
              foreach ($object->service as $value) {
                  ServiceContent::create([
                      'id_content' => $lastIdContent,
                      'id_service_item' => $value,
                  ]);
              }
          }

          if (isset($object->image_space)) {
              $path = public_path() . '/upload/img_content/';
              $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
              if (!\File::exists($path)) {
                  \File::makeDirectory($path, $mode = 0777, true, true);
              }
              if (!\File::exists($path_thumbnail)) {
                  \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
              }
              foreach ($object->image_space as $file) {

                  $img_name = time() . '_space_' . str_random(13) . '.jpeg';

                  try {
                      self::waterMark($file, $img_name, $path, $path_thumbnail, 'import');

                      $image_space = '/upload/img_content/' . $img_name;

                      ImageSpace::create([
                          'id_content' => $lastIdContent,
                          'name' => $image_space,
                      ]);
                  } catch (\Exception $e) {
                      echo 'Message: ' . $e->getMessage();
                  }
              }
          }

          if (isset($object->image_menu)) {
              $path = public_path() . '/upload/img_content/';
              $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
              if (!\File::exists($path)) {
                  \File::makeDirectory($path, $mode = 0777, true, true);
              }
              if (!\File::exists($path_thumbnail)) {
                  \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
              }
              foreach ($object->image_menu as $file) {

                  $img_name = time() . '_menu_' . str_random(13) . '.jpeg';

                  try {
                      self::waterMark($file, $img_name, $path, $path_thumbnail, 'import');

                      $image_menu = '/upload/img_content/' . $img_name;

                      ImageMenu::create([
                          'id_content' => $lastIdContent,
                          'name' => $image_menu,
                      ]);
                  } catch (\Exception $e) {
                      echo 'Message: ' . $e->getMessage();
                  }
              }
          }

          create_tag_search($lastIdContent);
      }
  }
}
<?php

namespace App\Http\Controllers\Location;

use App\Models\Location\Category;
use App\Models\Location\CategoryContent;
use App\Models\Location\CategoryItem;
use App\Models\Location\CategoryService;
use App\Models\Location\City;
use App\Models\Location\Client;
use App\Models\Location\Content;
use App\Models\Location\Country;
use App\Models\Location\DateOpen;
use App\Models\Location\District;
use App\Models\Location\Group;
use App\Models\Location\GroupContent;
use App\Models\Location\ImageMenu;
use App\Models\Location\ImageSpace;
use App\Models\Location\LinkContent;
use App\Models\Location\ServiceContent;
use App\Models\Location\ServiceItem;
use App\Models\Location\Product;
use App\Models\Location\Discount;
use App\Models\Location\NotifiAdmin;
use App\Models\Location\Branch;

use App\Models\Location\ClientRole;
use App\Models\Location\ClientArea;
use App\Models\Location\ClientInRole;
use App\Models\Location\CTV;
use App\Models\Location\Daily;

use Illuminate\Http\Request;
use App\Models\Location\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\MessageBag;
use \Illuminate\Support\HtmlString;
use Intervention\Image\Facades\Image;
use Validator;
use Carbon;
class AddLocationController extends BaseController
{
	public function postAllData(Request $request)
	{
		$id_category = $request->id_category;
		// get name.
		$data['category_name'] = app('translator')->getFromJson(Category::find($id_category)->name);

		// get category item.
		$list_category_item = CategoryItem::where([['category_id', '=', $id_category], ['active', '=', '1'], ['deleted', '=', '0']])
			->orderBy('weight')->pluck('name', 'id');
		if($id_category == 5)
		{
			$data['list_category_item'] = '';
			foreach ($list_category_item as $id => $name)
			{
				$data['list_category_item'] .= '<li class="form-group  col-md-4">
																						<label class="custom-control custom-radio">
																								<input type="radio" onchange="saveCategory(this)" name="category_item" value="'.$id.'" class="custom-control-input">
																								<span class="custom-control-indicator"></span>
																								<span class="custom-control-description">'.app('translator')->getFromJson($name).'</span>
																						</label>
																				</li>';
			}

			$data['bank_type'] = '<label>'.\Lang::get('Location/layout.bank_type').'</label>
														<select class="custom-select form-control" name="bank_type">
															<option value="BANK"> BANK </option>
															<option value="ATM"> ATM </option>
														</select>';
		}
		else
		{
			$data['list_category_item'] = '';
			foreach ($list_category_item as $id => $name)
			{
				$data['list_category_item'] .= '<li class="form-group  col-md-4">
																				<label class="custom-control custom-checkbox">
																					<input type="checkbox" onchange="saveCategory(this)" name="category_item[]" value="'.$id.'" class="custom-control-input">
																					<span class="custom-control-indicator"></span>
																					<span class="custom-control-description">'.app('translator')->getFromJson($name).'</span>
																				</label>
																			</li>';
			}
		}


		// get category service.
		$list_service = CategoryService::where('id_category', '=', $id_category)->get();
		$data['list_service'] = '';
		foreach ($list_service as $value)
		{
			$data['list_service'] .= '<li class="form-group  col-md-4">
																	<label class="custom-control custom-checkbox">
																		<input  onchange="saveService(this)" type="checkbox" name="service[]" value="'.$value->id_service_item.'" class="custom-control-input">
																		<span class="custom-control-indicator"></span>
																		<span class="custom-control-description">'.app('translator')->getFromJson($value->_service_item->name).'</span>
																	</label>
																</li>';
		}

		// get group contents.
		$list_group = Group::where('id_category', '=', $id_category)->pluck('name', 'id')->toArray();
		if(count($list_group)>0)
		{
			$data['list_group'] = '<label>'.\Lang::get('Location/layout.branch').'</label>';
			$data['list_group'] .= '<select class="custom-select form-control" name="group">';
			$data['list_group'] .= '<option value="">Nothing Selected</option>';
			foreach ($list_group as $key => $value)
			{
				$data['list_group'] .= '<option value="'.$key.'">'.$value.'</option>';
			}
			$data['list_group'] .= '</select>';
		}

		echo json_encode($data);
	}

	public function postAjaxLocation(Request $request)
	{
		$value = $request->value;
		$type = $request->type;
		$role = ClientRole::where('machine_name','cong_tac_vien')->first();
		$client = Client::find(Auth::guard('web_client')->user()->id);
		$check_ctv = ClientInRole::where('client_id',$client->id)
                  					 ->where('role_id',$role->id)
                  					 ->count();
    if($check_ctv>0){
    	$daily = Client::where('ma_dinh_danh',$client->daily_code)->first();
      $dai_ly_area = ClientArea::where('client_id',$daily->id)->pluck('district_id');

    	$arr_city = ClientArea::where('client_id',$client->id)->distinct('city_id')->pluck('city_id')->toArray();
    	$arr_district = ClientArea::where('client_id',$client->id)->distinct('district_id')->pluck('district_id')->toArray();
    }
		switch ($type) {
			case 'city':
				if($check_ctv>0){
					$city = City::where('id_country', '=', $value)
											->whereIn('id',$arr_city)
											->pluck('name', 'id');
				}else{
					$city = City::where('id_country', '=', $value)->pluck('name', 'id');
				}
				
				echo '<option value="">-- '.trans('Location'.DS.'layout.city').' --</option>';
				foreach ($city as $key => $value) {
					echo '<option value="' . $key . '">' . $value . '</option>';
				}
				break;
			case 'district':
				if($check_ctv>0){
					$district = District::where('id_city', '=', $value)
															->whereIn('id',$arr_district)
															->orderBy('weight', 'asc')
															->pluck('name', 'id');
				}else{
					$district = District::where('id_city', '=', $value)->orderBy('weight', 'asc')->pluck('name', 'id');
				}
				echo '<option value="">-- '.trans('Location'.DS.'layout.district').' --</option>';
				foreach ($district as $key => $value) {
					echo '<option value="' . $key . '">' . $value . '</option>';
				}
				break;
			default:
				break;
		}
	}

	public function postCreateLocation(Request $request)
	{
		// pr([$request->wifi,$request->pass_wifi]);die;
		set_time_limit(0);
		$id_user = Auth::guard('web_client')->user()->id;
		$content_avatar = '';
		if ($request->hasFile('avatar')) {
			$file = $request->file('avatar');

			$path = public_path() . '/upload/img_content/';
			$path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
			if (!\File::exists($path)) {
				\File::makeDirectory($path, $mode = 0777, true, true);
			}
			if (!\File::exists($path_thumbnail)) {
				\File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
			}

			$img_name = time() . '_avatar_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));
			
			if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
				self::waterMarkAvatar($file, $img_name, $path, $path_thumbnail);

			$content_avatar = '/upload/img_content/' . $img_name;
		}


		$tag = $request->tag?$request->tag:'';

		$data = [
			'name' => $request->name,
			'alias' => $request->alias,
			'id_category' => $request->id_category,
			'country' => $request->country,
			'city' => $request->city,
			'district' => $request->district,
			'address' => $request->address?$request->address:"",
			'tag' => isset($tag)?rtrim($tag,','):'',
			'phone' => isset($request->phone) ? $request->phone : '',
		 // 'open_from' => date("H:i:s", strtotime($request->open_from)),
		 // 'open_to' => date("H:i:s", strtotime($request->open_to)),
			'price_from' => isset($request->price_from) ? $request->price_from : 0,
			'price_to' => isset($request->price_to) ? $request->price_to : 0,
			'currency' => $request->currency?$request->currency:'VND',
			'website' => $request->website,
			'email' => isset($request->email) ? $request->email : '',
			'description' => $request->description?$request->description:"",
			'wifi' => $request->wifi?$request->wifi:'',
			'pass_wifi' => $request->pass_wifi?$request->pass_wifi:'',
			'avatar' => $content_avatar,
			'vote' => 0,
			'like' => 0,
			'type_user' => 0,
			'active' => 0,
			'lat' => $request->lat,
			'lng' => $request->lng,
			'moderation' => 'request_publish',
			'created_by' => $id_user,
			'updated_by' => $id_user,
			'code_invite' => '',

		];

		if($request->id_category == 5)
		{
			$data['extra_type'] = $request->bank_type;
		}

		$content = Content::create($data);

		if(\Auth::guard('web_client')->user()){
			$role = \Auth::guard('web_client')->user()->getRole('cong_tac_vien')->first();
			if($role && $role->active){
				$content->code_invite = \Auth::guard('web_client')->user()->ma_dinh_danh;
				$content->daily_code = \Auth::guard('web_client')->user()->daily_code;
				$ctv = CTV::where('client_id',\Auth::guard('web_client')->user()->id)->first();
				$content->ctv_id = $ctv->id;
				$content->daily_id = $ctv->daily_id;
				$content->save();
			}
		}

		$lastIdContent = $content->id;

		if ($request->product) {
				foreach ($request->product as $group) {
						$group_name =  $group['group_name'];
						foreach ($group as $key => $value) {
								if($key !== 'group_name'){
										if($value['id'] == 0){
												$product = new Product();
										}else{
												$product = Product::find($value['id']);
										}

										if($product && isset($value['name']) && $value['name']!=''){
												$product->name       = $value['name'];
												$product->price      = $value['price']?$value['price']:0;
												if(isset($value['image'])){
														$file = $value['image'];
														$path = public_path() . '/upload/product/';
														$path_thumbnail = public_path() . '/upload/product_thumbnail/';
														if (!\File::exists($path)) {
																\File::makeDirectory($path, $mode = 0777, true, true);
														}
														if (!\File::exists($path_thumbnail)) {
																\File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
														}

														$img_name = time() . '_product_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

														if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
															self::waterMarkAvatar($file, $img_name, $path, $path_thumbnail);

														$image_product = '/upload/product/' . $img_name;
														$product->image      = $image_product;
												}
												$product->content_id = $lastIdContent;
												$product->type_user  = 0;
												$product->created_by = Auth::guard('web_client')->user()->id;
												$product->updated_by = Auth::guard('web_client')->user()->id;
												$product->group_name = $group_name;
												$product->group_machine_name = str_replace('-','_',str_slug(clear_str($group_name)));
												$product->currency   = $value['currency']?$value['currency']:'VND';
												$product->save();
										}
								}
						}
				}
		}

		if ($request->date_open) {
				foreach ($request->date_open as $value) {
						if ($value['from_hour'] && $value['to_hour']) {
								DateOpen::create([
										'id_content' => $lastIdContent,
										'date_from' => $value['from_date'],
										'date_to' => $value['to_date'],
										'open_from' => $value['from_hour'],
										'open_to' => $value['to_hour'],
								]);
						}
				}
		}

		if($request->id_category == 5)
		{
			CategoryContent::create([
				'id_content' => $lastIdContent,
				'id_category_item' => $request->category_item,
			]);
		}
		else{
			if($request->category_item){
				foreach ($request->category_item as $value) {
					CategoryContent::create([
						'id_content' => $lastIdContent,
						'id_category_item' => $value,
					]);
				}
			}
		}

		if ($request->service) {
			foreach ($request->service as $value) {
				ServiceContent::create([
					'id_content' => $lastIdContent,
					'id_service_item' => $value,
				]);
			}
		}

		if ($request->group) {
			GroupContent::create([
				'id_content' => $lastIdContent,
				'id_group' => $request->group,
			]);
		}

		if ($request->image_space) {
			$path = public_path() . '/upload/img_content/';
			$path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
			if (!\File::exists($path)) {
				\File::makeDirectory($path, $mode = 0777, true, true);
			}
			if (!\File::exists($path_thumbnail)) {
				\File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
			}
			$arr_des_space = $request->des_space;
			$arr_title_space = $request->title_space;
			foreach ($request->image_space as $key => $file) {

				$img_name = (time() + $key) . '_space_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
					self::waterMark($file, $img_name, $path, $path_thumbnail);

				$image_space = '/upload/img_content/' . $img_name;

				ImageSpace::create([
					'id_content' => $lastIdContent,
					'name' => $image_space,
					'title'=> $arr_title_space[$key],
					'description'=> $arr_des_space[$key]
				]);
			}
		}

		if ($request->image_menu) {
			$path = public_path() . '/upload/img_content/';
			$path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
			if (!\File::exists($path)) {
				\File::makeDirectory($path, $mode = 0777, true, true);
			}
			if (!\File::exists($path_thumbnail)) {
				\File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
			}
			$arr_des_menu = $request->des_menu;
			$arr_title_menu = $request->title_menu;
			foreach ($request->image_menu as $key => $file) {

				$img_name = (time() + $key) . '_menu_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
					self::waterMark($file, $img_name, $path, $path_thumbnail);

				$image_menu = '/upload/img_content/' . $img_name;

				ImageMenu::create([
					'id_content' => $lastIdContent,
					'name' => $image_menu,
					'title'=> $arr_title_menu[$key],
					'description'=> $arr_des_menu[$key]
				]);
			}
		}
		if($request->link){
			foreach ($request->link as $value)
			{
				if(isset($value))
				{
						$infoVideo = app('App\Http\Controllers\Admin\ContentController')->getInfoVideo($value);
						if(count($infoVideo)>0 && isset($infoVideo['type']) ){
								LinkContent::create([
										'id_content' => $lastIdContent,
										'link' => $value,
										'type'=> $infoVideo['type'],
										'time'=>$infoVideo['time'],
										'title'=>$infoVideo['title'],
										'id_video'=>$infoVideo['id_video'],
										'thumbnail'=>$infoVideo['thumbnail']
								]);
						};
				}
			}
		}
		

		// if(!$data['description'] || empty($data['description'])){
		//   $description = '';
		//   $content = Content::where('id','=',$lastIdContent)
		//                  ->with('_category_type')
		//                  ->with('_category_items')
		//                  ->with('_country')
		//                  ->with('_city')
		//                  ->with('_district')
		//                  ->with('_date_open')
		//                  ->first();
		//   $description .= $content->name.' ';
		//   $description .= 'tại '.$content->address.' '.$content->_district->name.' '.$content->_city->name.' '.$content->_country->name.', ';
		//   if($content->_category_items){
		//       $description .= 'thuộc thể loại ';
		//       foreach ($content->_category_items as $key_cat => $cat_item) {
		//           if($key_cat==0){
		//               $description .= mb_strtolower($cat_item->name);
		//           }else{
		//               $description .= ' - '.mb_strtolower($cat_item->name);
		//           }

		//       }
		//   }else{
		//       if($content->_category_type){
		//           $description .= 'thuộc thể loại '.mb_strtolower($content->_category_type->name);
		//       }
		//   }

		//   if($content->_date_open){
		//       $description .= ', mở cửa '.mb_strtolower(create_open_time($content->_date_open, \App::getLocale())).', ';
		//   }
		//   // if($content->price_from > 0 && $content->price_to > 0){
		//   //     $description .= 'giá từ '.$content->price_from.$content->currency.' ';
		//   //     $description .= 'đến '.$content->price_to.$content->currency;
		//   // }
		//   $description .='.';
		//   $content->description = $description;
		//   $content->save();
		// }

		$_category_type = Category::find($request->id_category);
		$link = ADMIN_URL.'/content/update/'.$_category_type->machine_name.'/'.$lastIdContent;
		$content_notifi = trans('valid.notify_admin_create_content',['name'=>$content->name]);
		$notifi_admin = new NotifiAdmin();
		$notifi_admin->createNotifi($content_notifi,$link);
		//create_branch($lastIdContent);
		create_tag_search($lastIdContent);

		return Response::json(array(
			'status' => 'success',
			'id' => $lastIdContent
		));
	}

	public function postValidation(Request $request)
	{
		$rules = [
			'category_item' => 'required',
			'name' => 'required|unique:contents,name',
			'alias' => 'required|unique:contents,alias',
			'lat' => 'required',
			'lng' => 'required',
			'id_category' => 'required',
			'email' => 'email',
			// 'open_from' => 'required',
			// 'open_to' => 'required',
			// 'price_from' => 'required|integer|min:0',
			// 'price_to' => 'required|integer|min:0',
			'address' => 'required',
			'country' => 'required',
			'city' => 'required',
			'district' => 'required',
			'tag' => 'tag_min',
		];

		$check_category_item = CategoryItem::where('category_id',$request->id_category)
																			 ->where('active',1)
																			 ->count();
		if($check_category_item == 0){
			unset($rules['category_item']);
		}

		if($request->id_category == 5)
		{
			unset($rules['price_from']);
			unset($rules['price_to']);
		}
		if(!isset($request->email))
		{
			unset($rules['email']);
		}
		// if(!isset($request->type_submit) || (isset($request->type_submit) && $request->type_submit == 'update')){
		//   unset($rules['avatar']);
		// }
		if(isset($request->type_submit) && $request->type_submit == 'update')
		{
			$content_update = Content::find($request->id_edit_content);
			if (trim($content_update->name) == $request->name) {
				$rules['name'] = 'required';
			}

			if ($content_update->alias == $request->alias) {
				$rules['alias'] = 'required';
			}

			unset($rules['country']);
			unset($rules['city']);
			unset($rules['district']);

		}
		$messages = [
			'name.required' => \Lang::get('Location/layout.name_required'),
			'name.unique' => \Lang::get('Location/layout.name_unique'),
			'id_category.required' => \Lang::get('Location/layout.id_category_required'),
			'category_item.required' => \Lang::get('Location/layout.category_item_required'),
			'alias.required' => \Lang::get('Location/layout.alias_required'),
			'alias.unique' => \Lang::get('Location/layout.alias_unique'),
			'country.required' => \Lang::get('Location/layout.country_required'),
			'city.required' => \Lang::get('Location/layout.city_required'),
			'district.required' => \Lang::get('Location/layout.district_required'),
			'address.required' => \Lang::get('Location/layout.address_required'),
			'lat.required' => \Lang::get('Location/layout.lat_required'),
			'lng.required' => \Lang::get('Location/layout.lng_required'),
			'email.email' => \Lang::get('Location/layout.email_email'),
			'open_from.required' => \Lang::get('Location/layout.open_from_required'),
			'open_to.required' => \Lang::get('Location/layout.open_to_required'),
			'price_from.required' => \Lang::get('Location/layout.price_from_required'),
			'price_from.integer' => \Lang::get('Location/layout.price_from_integer'),
			'price_from.min' => \Lang::get('Location/layout.price_from_min'),
			'price_to.required' => \Lang::get('Location/layout.price_to_required'),
			'price_to.integer' => \Lang::get('Location/layout.price_to_integer'),
			'price_to.min' => \Lang::get('Location/layout.price_to_min'),
			'tag.tag_min' => \Lang::get('Location/layout.tag_required'),
		];
		$validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
			$arr_err = $validator->getMessageBag()->toArray();
			return Response::json(array(
				'status' => 'error',
				'message' => [
					key($arr_err) => current($arr_err)[0]
				]
			));
		} else {

			return Response::json(array(
				'status' => 'success',
			));

		}
	}

	public function waterMarkAvatar($file, $img_name, $path, $path_thumbnail)
	{
		// if ($width > 770 || $height > 468) {
		$img = Image::make($file->getRealPath())->orientate()->fit(660,347, function ($constraint) {
			$constraint->upsize();
		});

        $wt = Image::make(public_path() . DS . 'img_default' . DS . 'kingmap.png');
        $img->insert($wt, 'center');
        $img->insert($wt, 'center');

		$img->save($path . $img_name);

		$img_thumbnail = Image::make($file->getRealPath())->orientate()->fit(270,202, function ($constraint) {
			$constraint->upsize();
		})->insert(Image::make('img_default/kingmap.png')->resize(95, 14), 'center');
		$img_thumbnail->save($path_thumbnail . $img_name);
	}

	public function waterMark($file, $img_name, $path, $path_thumbnail)
	{
		$img = Image::make($file->getRealPath())->orientate();
		$width = $img->getSize()->getWidth();
		$height = $img->getSize()->getHeight();

		$max_height = 720;
		$max_width = 1280;

		if($width>$max_width || $height>$max_height){
			$img = Image::make($file->getRealPath())->orientate()->resize(1280, 720, function ($constraint) {
				$constraint->aspectRatio();
				$constraint->upsize();
			});
		}

		$max = $width>$height?$width:$height;

		$wt = Image::make(public_path() . DS . 'img_default' . DS . 'kingmap.png');
    $img->insert($wt, 'center');
    $img->insert($wt, 'center');

		$img->save($path . $img_name);

		$img_thumbnail =
			Image::make($file->getRealPath())->orientate()->fit(270, 202, function ($constraint) {
				$constraint->upsize();
			})
			->insert(Image::make('img_default/kingmap.png')->resize(95, 14), 'center')
			->insert(Image::make('img_default/kingmap.png')->resize(95, 14), 'center');

		$img_thumbnail->save($path_thumbnail . $img_name);
	}
	public function getDeleteLocation($id_content)
	{
		$current_user_id = Auth::guard('web_client')->user()?Auth::guard('web_client')->user()->id:0;
		$content = Content::where('id',$id_content)
											->where('created_by',$current_user_id)
											->where('type_user',0)
											->first();
		
		if(!$content){
			abort(404);
		}

		if($current_user_id == $content->created_by)
		{
			$content->moderation = 'trash';
			$content->active = 0;
			$content->save();
		}
		if(url()->previous()){
			return redirect(url()->previous());
		}else{
			return redirect(url('user/'.$current_user_id.'/management-location'));
		}
	}

	public function getEditLocation($id_content)
	{

		$id_user = Auth::guard('web_client')->user()?Auth::guard('web_client')->user()->id:0;
		$user = Client::where('id','=',$id_user)->where('active','=',1)->first();
		if(!$user){
			abort(404);
		}
		$content = Content::where('id',$id_content)
											->where('created_by',$id_user)
											->where('type_user',0)
											->first();
		if(!$content){
			abort(404);
		}
		
		if($id_user != $content->created_by)
		{
			return redirect(url('user/'.$id_user.'/management-location'));
		}

		$arrData['content'] = $content;
		$arrData['category_name'] = Category::find($content->id_category)->name;

		$list_group = Group::where('id_category', '=', $content->id_category)->pluck('name', 'id')->toArray();
		if(count($list_group)>0)
		{
			$arrData['list_group'] = $list_group;
		}

		$arrData['list_category_item'] = CategoryItem::where([['category_id', '=', $content->id_category], ['active', '=', '1']])->pluck('name', 'id')->toArray();
		$arrData['list_category_item_content'] = CategoryContent::where('id_content', '=', $content->id)->pluck('id_category_item')->toArray();

		$arrData['list_country'] = Country::rightJoin('client_area','client_area.country_id','countries.id')
                                      ->where('client_id',\Auth::guard('web_client')->user()->id)
                                      ->pluck('name', 'id');
                                      
		$role = ClientRole::where('machine_name','cong_tac_vien')->first();
		$client = Client::find(Auth::guard('web_client')->user()->id);
		$check_ctv = ClientInRole::where('client_id',$client->id)
                  					 ->where('role_id',$role->id)
                  					 ->count();

    if($check_ctv>0){
    	$daily = Client::where('ma_dinh_danh',$client->daily_code)->first();
      $dai_ly_area = ClientArea::where('client_id',$daily->id)->pluck('district_id');

    	$arr_city = ClientArea::where('client_id',$client->id)->distinct('city_id')->pluck('city_id')->toArray();
    	$arr_district = ClientArea::where('client_id',$client->id)->distinct('district_id')->pluck('district_id')->toArray();

    	$arrData['list_city'] = City::where('id_country', '=', $content->country)->whereIn('id',$arr_city)->pluck('name', 'id');
			$arrData['list_districts'] = District::where('id_city', '=', $content->city)->whereIn('id',$arr_district)->pluck('name', 'id');
    }else{
    	$arrData['list_city'] = City::where('id_country', '=', $content->country)->pluck('name', 'id');
			$arrData['list_districts'] = District::where('id_city', '=', $content->city)->pluck('name', 'id');
    }

		

		$arrData['list_service'] = CategoryService::where('id_category', '=', $content->id_category)->with('_service_item')->get();
		$arrData['list_service_content'] = ServiceContent::where('id_content', '=', $content->id)->pluck('id_service_item')->toArray();

		$arrData['list_image_space'] = ImageSpace::where('id_content', '=', $content->id)->get();
		$arrData['list_image_menu'] = ImageMenu::where('id_content', '=', $content->id)->get();
		$arrData['list_link_video'] = LinkContent::where('id_content', '=', $content->id)->get();

		$arrData['products'] = Product::where('content_id', '=', $content->id)->orderBy('group_name')->get();
		$arrData['group_product'] = Product::where('content_id', '=', $content->id)
																		->groupBy('group_name')
																		->whereNotNull('group_name')
																		->pluck('group_name');
		$arrData['list_product'] = [];
		$arrData['list_product']['no_group']['group_name'] = '';
		$arr_has_group=[];
		$arr_no_group=[];
		foreach ($arrData['group_product'] as $key => $group) {
				$arrData['list_product'][$key]['group_name'] = $group;
				foreach ($arrData['products'] as $key2 => $product) {
						if($product->group_name === $group && !in_array($product->id,$arr_has_group)){
								$arrData['list_product'][$key][] = $product;
								$arr_has_group[] = $product->id;
						}else{
								if($product->group_name===null && !in_array($product->id,$arr_no_group)){
										$arrData['list_product']['no_group'][] = $product;
										$arr_no_group[] = $product->id;
								}
						}
				}
		}

		if(count($arrData['list_product']['no_group'])<2){
				unset($arrData['list_product']['no_group']);
				$arrData['list_product'] = array_values($arrData['list_product']);
		}

	 // $this->view->content = view('Location.user.edit_location', $arrData);
		$this->view->content = view('Location.user.index',['user'=>$user, 'arrData'=> $arrData, 'module'=>'edit-location']);
		return $this->setContent();
	}

	public function postEditLocation(Request $request)
	{
		$content_update = Content::find($request->id_edit_content);

		if ($request->hasFile('avatar')) {
			$file = $request->file('avatar');
			$path = public_path() . '/upload/img_content/';
			$path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
			if (!\File::exists($path)) {
				\File::makeDirectory($path, $mode = 0777, true, true);
			}
			if (!\File::exists($path_thumbnail)) {
				\File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
			}

			$img_name = time() . '_avatar_' . $file->getClientOriginalName();

			if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
				self::waterMarkAvatar($file, $img_name, $path, $path_thumbnail);
			if (file_exists(public_path($content_update->avatar))) {
				unlink(public_path($content_update->avatar));
			}
			if (file_exists(public_path(str_replace('img_content', 'img_content_thumbnail', $content_update->avatar)))) {
				unlink(public_path(str_replace('img_content', 'img_content_thumbnail', $content_update->avatar)));
			}
			$content_update->avatar = '/upload/img_content/' . $img_name;

		}

		$tag = $request->tag?$request->tag:'';

		$content_update->name = $request->name;
		$content_update->alias = $request->alias;
		$content_update->id_category = $request->id_category;
		if($request->country && $request->city && $request->district){
			$content_update->country = $request->country;
			$content_update->city = $request->city;
			$content_update->district = $request->district;
		}
		$content_update->address = $request->address?$request->address:"";
		$content_update->tag = isset($tag)?rtrim($tag,','):'';
		$content_update->wifi = $request->wifi?$request->wifi:'';
		$content_update->pass_wifi = $request->pass_wifi?$request->pass_wifi:'';
		$content_update->phone = isset($request->phone) ? $request->phone : '';
		$content_update->price_from = isset($request->price_from) ? $request->price_from : 0;
		$content_update->price_to = isset($request->price_to) ? $request->price_to : 0;
		$content_update->currency = isset($request->currency) ? $request->currency : 'VND';
		$content_update->website = isset($request->website) ? $request->website : '';
		$content_update->email = isset($request->email_edit) ? $request->email_edit : '';
		$content_update->description = $request->description?$request->description:"";
		$content_update->lat = $request->lat;
		$content_update->lng = $request->lng;
		$content_update->updated_by = Auth::guard('web_client')->user()->id;
		$content_update->type_user_update = 0;
		if ($content_update->save()) {

			$id = $request->id_edit_content;

			if ($request->product) {
					foreach ($request->product as $group) {
							$group_name =  $group['group_name'];
							foreach ($group as $key => $value) {
									if($key !== 'group_name'){
											if($value['id'] == 0){
													$product = new Product();
											}else{
													$product = Product::find($value['id']);
											}

											if($product && isset($value['name']) && $value['name']!=''){
													$product->name       = $value['name'];
													$product->price      = $value['price']?$value['price']:0;
													if(isset($value['image'])){
															$file = $value['image'];
															$path = public_path() . '/upload/product/';
															$path_thumbnail = public_path() . '/upload/product_thumbnail/';
															if (!\File::exists($path)) {
																	\File::makeDirectory($path, $mode = 0777, true, true);
															}
															if (!\File::exists($path_thumbnail)) {
																	\File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
															}

															$img_name = time() . '_product_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

															if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
																	self::waterMarkAvatar($file, $img_name, $path, $path_thumbnail);

															$image_product = '/upload/product/' . $img_name;
															$product->image      = $image_product;
													}
													$product->content_id = $id;
													$product->type_user  = 0;
													$product->updated_by = Auth::guard('web_client')->user()->id;
													$product->group_name = $group_name;
													$product->group_machine_name = str_replace('-','_',str_slug(clear_str($group_name)));
													$product->currency   = $value['currency']?$value['currency']:'VND';
													$product->save();
											}
									}
							}
					}
			}

			DateOpen::where('id_content', '=', $id)->delete();
			if ($request->date_open) {
					foreach ($request->date_open as $value) {
							if ($value['from_hour'] && $value['to_hour']) {
									DateOpen::create([
											'id_content' => $id,
											'date_from' => $value['from_date'],
											'date_to' => $value['to_date'],
											'open_from' => $value['from_hour'],
											'open_to' => $value['to_hour'],
									]);
							}
					}
			}

			CategoryContent::where('id_content', '=', $id)->delete();
			if ($request->category_item) {
				foreach ($request->category_item as $value) {
					CategoryContent::create([
						'id_content' => $id,
						'id_category_item' => $value,
					]);
				}
			}

			ServiceContent::where('id_content', '=', $id)->delete();
			if ($request->service) {
				foreach ($request->service as $value) {
					ServiceContent::create([
						'id_content' => $id,
						'id_service_item' => $value,
					]);
				}
			}

			$check_group_content = GroupContent::where('id_content', '=', $id)->first();
			if ($request->group) {
				if ($check_group_content) {
					$check_group_content->id_group = $request->group;
					$check_group_content->save();
				} else {
					GroupContent::create([
						'id_content' => $id,
						'id_group' => $request->group,
					]);
				}

			} else {
				if ($check_group_content) {
					$check_group_content->delete();
				}
			}


			if ($request->image_space) {
				$path = public_path() . '/upload/img_content/';
				$path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
				$arr_des_space = $request->des_space;
				$arr_title_space = $request->title_space;
				foreach ($request->image_space as $key => $file) {

					$img_name = (time() + $key) . '_space_' . $file->getClientOriginalName();

					if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
						self::waterMark($file, $img_name, $path, $path_thumbnail);

					$image_space = '/upload/img_content/' . $img_name;

					ImageSpace::create([
						'id_content' => $id,
						'name' => $image_space,
						'title'=> $arr_title_space[$key],
						'description'=> $arr_des_space[$key]
					]);
				}
			}

			if ($request->image_menu) {
				$path = public_path() . '/upload/img_content/';
				$path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
				$arr_des_menu = $request->des_menu;
				$arr_title_menu = $request->title_menu;
				foreach ($request->image_menu as $key => $file) {

					$img_name = (time() + $key) . '_menu_' . $file->getClientOriginalName();

					if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
						self::waterMark($file, $img_name, $path, $path_thumbnail);

					$image_menu = '/upload/img_content/' . $img_name;

					ImageMenu::create([
						'id_content' => $id,
						'name' => $image_menu,
						'title'=> $arr_title_menu[$key],
						'description'=> $arr_des_menu[$key]
					]);
				}
			}
			LinkContent::where('id_content', '=', $id)->delete();
			if($request->link){
				foreach ($request->link as $value)
				{
					if(isset($value))
					{
							$infoVideo = app('App\Http\Controllers\Admin\ContentController')->getInfoVideo($value);
							if(count($infoVideo)>0 && isset($infoVideo['type']) ){
									LinkContent::create([
											'id_content' => $id,
											'link' => $value,
											'type'=> $infoVideo['type'],
											'time'=>$infoVideo['time'],
											'title'=>$infoVideo['title'],
											'id_video'=>$infoVideo['id_video'],
											'thumbnail'=>$infoVideo['thumbnail']
									]);
							};
					}
				}
			}
		}

		// if(!$request->description && $request->id_edit_content){
		//   $description = '';
		//   $content = Content::where('id','=',$request->id_edit_content)
		//                  ->with('_category_type')
		//                  ->with('_category_items')
		//                  ->with('_country')
		//                  ->with('_city')
		//                  ->with('_district')
		//                  ->with('_date_open')
		//                  ->first();
		//   $description .= $content->name.' ';
		//   $description .= 'tại '.$content->address.' '.$content->_district->name.' '.$content->_city->name.' '.$content->_country->name.', ';
		//   if($content->_category_items){
		//       $description .= 'thuộc thể loại ';
		//       foreach ($content->_category_items as $key_cat => $cat_item) {
		//           if($key_cat==0){
		//               $description .= mb_strtolower($cat_item->name);
		//           }else{
		//               $description .= ' - '.mb_strtolower($cat_item->name);
		//           }

		//       }
		//   }else{
		//       if($content->_category_type){
		//           $description .= 'thuộc thể loại '.mb_strtolower($content->_category_type->name);
		//       }
		//   }

		//   if($content->_date_open){
		//       $description .= ', mở cửa '.mb_strtolower(create_open_time($content->_date_open, \App::getLocale())).', ';
		//   }
		//   // if($content->price_from > 0 && $content->price_to > 0){
		//   //     $description .= 'giá từ '.$content->price_from.$content->currency.' ';
		//   //     $description .= 'đến '.$content->price_to.$content->currency;
		//   // }
		//   $description .='.';
		//   $content->description = $description;
		//   $content->save();
		// }

		create_tag_search($id);
		return Response::json(array(
			'status' => 'success',
		));
	}

	public function getDeleteEditImage(Request $request)
	{
		$id = $request->id;
		$type = $request->type;

		if($type == 'edit_image_khong_gian')
		{
			$image = ImageSpace::find($id);
			if (file_exists(public_path($image['name']))) {
				unlink(public_path($image['name']));
			}
			if (file_exists(public_path(str_replace('img_content', 'img_content_thumbnail', $image['name'])))) {
				unlink(public_path(str_replace('img_content', 'img_content_thumbnail', $image['name'])));
			}
			$image->delete();
			echo 'sussess';
		}
		else {
			$image = ImageMenu::find($id);
			if (file_exists(public_path($image['name']))) {
				unlink(public_path($image['name']));
			}
			if (file_exists(public_path(str_replace('img_content', 'img_content_thumbnail', $image['name'])))) {
				unlink(public_path(str_replace('img_content', 'img_content_thumbnail', $image['name'])));
			}
			$image->delete();
			echo 'sussess';
		}
	}

	public function postUpdateEditImage(Request $request){
		$id = $request->id;
		$type = $request->type;
		$title = $request->title?$request->title:'';
		$des = $request->des?$request->des:'';

		if($type == 'space'){
			$image = ImageSpace::find($id);
			$image->title = $title;
			$image->description = $des;
			$image->save();
		}
		if($type == 'menu'){
			$image = ImageMenu::find($id);
			$image->title = $title;
			$image->description = $des;
			$image->save();
		}
	}

	public function getChangeStatusCloseLocation($id_content)
	{
		$current_user_id = Auth::guard('web_client')->user()->id;
		$content = Content::find($id_content);

		if($current_user_id == $content->created_by)
		{
			$content->moderation = 'un_publish';
			$content->active = 0;
			$content->save();
			
		}
		if(url()->previous()){
			return redirect(url()->previous());
		}else{
			return redirect(url('user/'.$current_user_id.'/management-location'));
		}
	}

	public function getChangeStatusOpenLocation($id_content)
	{
		$current_user_id = Auth::guard('web_client')->user()->id;
		$content = Content::find($id_content);

		if($current_user_id == $content->created_by)
		{
			$content->moderation = 'publish';
			$content->active = 1;
			$content->save();
		}
		if(url()->previous()){
			return redirect(url()->previous());
		}else{
			return redirect(url('user/'.$current_user_id.'/management-location'));
		}
	}

	public function vn_to_str($str)
	{
		$unicode = array(
			'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
			'd' => 'đ',
			'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
			'i' => 'í|ì|ỉ|ĩ|ị',
			'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
			'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
			'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
			'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
			'D' => 'Đ',
			'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
			'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
			'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
			'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
			'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
		);
		foreach ($unicode as $nonUnicode => $uni) {
			$str = preg_replace("/($uni)/i", $nonUnicode, $str);
		}
		$str = str_replace(' ', '_', $str);
		return $str;
	}

	public function previewLocation(Request $request){
		// pr($request->all());die;
		if($request->isMethod('post')){
			$data = $request->all();
			
			if(!isset($data['service'])){
				$data['service'] = [];
			}


			if(!isset($data['category_item'])){
				$data['category_item'] = [];
			}

			if(!is_array($data['category_item'])){
				$data['category_item'] = [$data['category_item']];
			}

			$content = [];
			$content['name'] = $data['name'];
			// $content['alias'] = str_slug_custom($data['name']);
			$content['address'] = $data['address'];
			$content['phone'] = isset($data['phone'])?$data['phone']:'';
			$content['email'] = isset($data['email'])?$data['email']:'';
			// $content['open_from'] = $data['open_from'];
			// $content['open_to'] = $data['open_to'];
			// $content['price_from'] = $data['price_from'];
			// $content['price_to'] = $data['price_to'];
			$data_open = [];
			foreach ($data['date_open'] as $key => $value) {
				$data_open[] = (object) array(
						'date_from'   => $value['from_date'],
						'date_to'     => $value['to_date'],
						'open_from'   => date("H:i:s", strtotime($value['from_hour'])),
						'open_to'     => date("H:i:s", strtotime($value['to_hour']))
				);
			}
			
			$content['open_time'] = create_open_time($data_open, \App::getLocale());
								
			// $content['currency'] = $data['currency'];
			$content['link'] = [];
			if(isset($data['link'])) {
                foreach ($data['link'] as $key => $value) {
                    if ($value != ''){
                    	$info_video = app('App\Http\Controllers\Admin\ContentController')->getInfoVideo($value);
                    	if(isset($info_video['type'])){
                    		$content['link'][] = $info_video;
                    	}
                    }
                }
            }

			$content['lat'] = $data['lat'];
			$content['lng'] = $data['lng'];
			$content['description'] = $data['description'];

			$content['like'] = 0;
			$content['vote'] = 0;
			$content['line'] = 0;
			$content['category'] = Category::find($data['id_category']);
			$content['category_item'] = CategoryItem::wherein('id',$data['category_item']?$data['category_item']:[])->get();
			$content['service'] = $data['service']?$data['service']:[];
			$content['all_service'] = CategoryService::where('id_category', '=', $data['id_category'])->with('_service_item')->get();

			$content['breadcrumb']='';
			$content['breadcrumb'].='<a class="" href="'.url('/').'/list/'.$content['category']->alias.'" title="'.app('translator')->getFromJson($content['category']->name).'">'.app('translator')->getFromJson($content['category']->name).'</a>';
			$content['breadcrumb'].='<span>&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;</span>';
			if($content['category_item']){
				foreach ($content['category_item'] as $key => $item) {
					if($key==0)
					$content['breadcrumb'].='<a class="" href="'.url('/').'/list/'.$content['category']->alias.'/'.$item->alias.'" title="'.app('translator')->getFromJson($item->name).'">'.app('translator')->getFromJson($item->name).'</a>';
					else
					$content['breadcrumb'].=' - <a class="" href="'.url('/').'/list/'.$content['category']->alias.'/'.$item->alias.'" title="'.app('translator')->getFromJson($item->name).'">'.app('translator')->getFromJson($item->name).'</a>';
				}
			}

			$content['country'] = Country::find($data['country']);
			$content['city'] = City::find($data['city']);
			$content['district'] = District::find($data['district']);
			$content['avatar'] = '';
			if ($request->hasFile('avatar')) {
				$avatar = $request->file('avatar');
				$data64 = file_get_contents($avatar->path());
				$type = $avatar->extension();
				$img = Image::make('data:image/' . $type . ';base64,' . base64_encode($data64))
										->orientate()
										->fit(660,347, function ($constraint) {
				                $constraint->upsize();
				            })
				            ->encode('data-url');
				$content['avatar'] = $img;
				unlink($avatar->path());
			}
			$content['space'] = [];
			if ($request->image_space) {
				foreach ($request->image_space as $file) {
					$data64 = file_get_contents($file->path());
					$type = $file->extension();
					$content['space'][] = 'data:image/' . $type . ';base64,' . base64_encode($data64);
					unlink($file->path());
				}
			}
			$content['menu'] = [];
			if ($request->image_menu) {
				foreach ($request->image_menu as $file) {
					$data64 = file_get_contents($file->path());
					$type = $file->extension();
					$content['menu'][] = 'data:image/' . $type . ';base64,' . base64_encode($data64);
					unlink($file->path());
				}
			}
			
			if($content){
				$category = $content['category']->alias;
				$foodlist = ['drinks','hotel','entertainment','mua_sam'];
				$banklist = ['bank'];
				$shopklist = ['shop'];
				if(in_array($category,$foodlist)){
					$this->view->content =  view('Location.preview.details_new',['content'=>$content]);
				}else{
					if(in_array($category,$banklist)){
						$this->view->content =  view('Location.preview.details_new',['content'=>$content]);
					}else{
						if(in_array($category,$shopklist)){
							$this->view->content =  view('Location.preview.details_new',['content'=>$content]);
						}else{
							$this->view->content =  view('Location.preview.details_new',['content'=>$content]);
						}
					}
				}
			}
			$file_name = 'preview_'.time().'.html';
			$path = public_path().'/upload/preview/';
			if(!\File::exists($path)) {
				\File::makeDirectory($path, $mode = 0777, true, true);
			}
			// return $this->setContent();
			file_put_contents($path.$file_name,$this->setContent());
			session()->put('content_preview' , url('/upload/preview/'.$file_name));
			return response()->json(['type'=>$content['category']->alias]);
		}else{
			$content =  session()->get('content_preview');
			echo file_get_contents($content);
		}
	}

	public function postCreateCategoryItem(Request $request){
		$arrReturn=[
			'error' => 1,
			'message' => '',
			'data' => []
		];
		if($request->category_item){
			$name = $request->category_item;
			$check = CategoryItem::where('machine_name',str_replace('-', '_',str_slug(vn_string(clear_str($name)))))->first();
			if($check){
				$arrReturn['message'] = trans('valid.category_item_unique');
			}else{
				$weight = CategoryItem::where('category_id', '=',$request->category)->max('weight');
				$category_item = new CategoryItem();
				$category_item->name = $name;
				$category_item->alias = str_slug(vn_string(clear_str($name)));
				$category_item->machine_name = str_replace('-', '_',str_slug(vn_string(clear_str($name))));
				$category_item->active = 0;
				$category_item->approved = 0;

				$category_item->language = 'vn';
				$category_item->weight = isset($weight)?$weight + 1:0;
				$category_item->category_id = $request->category;
				$category_item->description = $request->description;
				$category_item->image ='/frontend/assets/img/upload/cate3.png';
				if($category_item->save()){
					$arrReturn['error'] = 0;
					if($request->category == 5){
						$arrReturn['data'] = '<li class="form-group  col-md-4">
																							<label class="custom-control custom-radio">
																									<input type="radio" onchange="saveCategory(this)" name="category_item" value="'.$category_item->id.'" class="custom-control-input" checked="">
																									<span class="custom-control-indicator"></span>
																									<span class="custom-control-description">'.app('translator')->getFromJson($category_item->name).'</span>
																							</label>
																					</li>';
					}else{
						$arrReturn['data'] = '<li class="form-group  col-md-4">
																							<label class="custom-control custom-radio">
																									<input type="checkbox" onchange="saveCategory(this)" name="category_item[]" value="'.$category_item->id.'" class="custom-control-input" checked="">
																									<span class="custom-control-indicator"></span>
																									<span class="custom-control-description">'.app('translator')->getFromJson($category_item->name).'</span>
																							</label>
																					</li>';
					}

					$link = ADMIN_URL.'/category_item/'.$request->category.'/approve';
					$content_notifi = trans('valid.notify_admin_create_category_item',['name'=>$category_item->name]);
					$notifi_admin = new NotifiAdmin();
					$notifi_admin->createNotifi($content_notifi,$link);
				}
			}
		}else{
			$arrReturn['message'] = trans('valid.category_item_input');
		}
		return response()->json($arrReturn);
	}

	public function postCreateService(Request $request){
		$arrReturn=[
			'error' => 1,
			'message' => '',
			'data' => []
		];
		if($request->service){
			$name = $request->service;
			$check = ServiceItem::where('machine_name',str_replace('-', '_',str_slug(vn_string(clear_str($name)))))->first();
			if($check){
				$arrReturn['message'] = trans('valid.service_unique');
			}else{
				$service = new ServiceItem();
				$service->name = $name;
				$service->machine_name = str_replace('-', '_',str_slug(vn_string(clear_str($name))));
				$service->active = 0;
				$service->approved = 0;

				if($service->save()){
					$category_service = new CategoryService();
					$category_service->id_category = $request->category;
					$category_service->id_service_item = $service->id;
					$category_service->save();
					$arrReturn['error'] = 0;
					$arrReturn['data'] = '<li class="form-group  col-md-4">
																						<label class="custom-control custom-radio">
																								<input type="checkbox" onchange="saveService(this)" name="service[]" value="'.$service->id.'" class="custom-control-input" checked="">
																								<span class="custom-control-indicator"></span>
																								<span class="custom-control-description">'.app('translator')->getFromJson($service->name).'</span>
																						</label>
																				</li>';
					$link = ADMIN_URL.'/service_item/approve';
					$content_notifi = trans('valid.notify_admin_create_service',['name'=>$service->name]);
					$notifi_admin = new NotifiAdmin();
					$notifi_admin->createNotifi($content_notifi,$link);
				}
			} 
		}else{
			$arrReturn['message'] = trans('valid.service_input');
		}
		return response()->json($arrReturn);
	}

	public function postCreateCategory(Request $request){
		$arrReturn=[
			'error' => 1,
			'message' => '',
			'data' => []
		];
		if($request->category){
			$name = $request->category;
			$check = Category::where('machine_name',str_replace('-', '_',str_slug(vn_string(clear_str($name)))))->first();
			if($check){
				$arrReturn['message'] = trans('valid.category_unique');
			}else{
				$category = new Category();
				$category->name = $name;
				$category->alias = str_slug(vn_string(clear_str($name)));
				$category->machine_name = str_replace('-', '_',str_slug(vn_string(clear_str($name))));
				$category->image ='/frontend/assets/img/icon/logo-large.png';
				$category->background ='/frontend/assets/img/upload/bg-food2.jpg';
				$category->marker ='/img_default/marker.svg';
				$category->language = 'vn';
				$category->weight = Category::max('weight') + 1;
				$category->active = 0;
				$category->approved = 0;

				if($category->save()){
					$arrReturn['error'] = 0;
					$arrReturn['message'] = trans('valid.category_wait_approve');

					$link = ADMIN_URL.'/category/approve';
					$content_notifi = trans('valid.notify_admin_create_category',['name'=>$category->name]);
					$notifi_admin = new NotifiAdmin();
					$notifi_admin->createNotifi($content_notifi,$link);
				}
			} 
		}else{
			$arrReturn['message'] = trans('valid.category_input');
		}
		return response()->json($arrReturn);
	}

	public function deleteProduct(Request $request){
		$id = $request->id;
		$product = Product::find($id);
		if (file_exists(public_path($product['image']))) {
				unlink(public_path($product['image']));
		}
		if (file_exists(public_path(str_replace('product', 'product_thumbnail', $product['image'])))) {
				unlink(public_path(str_replace('product', 'product_thumbnail', $product['image'])));
		}
		if($product->delete()){
				echo 'sussess';
		}
	}


	public function deleteGroupProduct(Request $request){
		if($request->id && count($request->id)){
				foreach ($request->id as $key => $id) {
						$product = Product::find($id);
						if ($product['image']!='' &&  file_exists(public_path($product['image']))) {
								unlink(public_path($product['image']));
						}
						if ($product['image']!='' &&  file_exists(public_path(str_replace('product', 'product_thumbnail', $product['image'])))) {
								unlink(public_path(str_replace('product', 'product_thumbnail', $product['image'])));
						}
						$product->delete();
				}   
				echo 'sussess'; 
		}
	}

	public function getCreated(Request $request){
		$html = '';
		if($request->q && $request->q != ''){
			$keyword = $request->q;
			$contents = Content::select(
												'contents.id',
												'contents.name',
												'contents.alias',
												'contents.district'
											)
												->search($keyword)
												->leftJoin('districts','contents.district','=','districts.id')
												->limit(10)
												->get();
			if($contents){
				foreach ($contents as $key => $content) {
					$html .= "<li class='text-danger'><a target='_blank' href='".url($content->alias)."'><i class='fa fa-times'></i> $content->name</a></li>";
				}
			}
		}
		return response($html);
	}

	public function getInfoVideo(Request $request){
		$infoVideo = [];
		if($request->url)
			$infoVideo = app('App\Http\Controllers\Admin\ContentController')->getInfoVideo($request->url);
		return response($infoVideo);
	}

    public function deleteInfoVideo(Request $request){
        if($request->id)
            LinkContent::where('id',$request->id)
                ->delete();
    }

	public function getManageLocaiton($content_id){
		$manager = '';
		$location = '';
		$content = Content::where('id',$content_id)
											->with('_branchs')
											->first();
		if($content){
			$products = Product::where('content_id', '=', $content->id)->orderBy('group_name')->get();
			$group_product = Product::where('content_id', '=', $content->id)
																			->groupBy('group_name')
																			->whereNotNull('group_name')
																			->pluck('group_name');
			$list_product = [];
			$list_product['no_group']['group_name'] = '';
			$arr_has_group=[];
			$arr_no_group=[];
			foreach ($group_product as $key => $group) {
					$list_product[$key]['group_name'] = $group;
					foreach ($products as $key2 => $product) {
							if($product->group_name === $group && !in_array($product->id,$arr_has_group)){
									$list_product[$key][] = $product;
									$arr_has_group[] = $product->id;
							}else{
									if($product->group_name===null && !in_array($product->id,$arr_no_group)){
											$list_product['no_group'][] = $product;
											$arr_no_group[] = $product->id;
									}
							}
					}
			}

			if(count($list_product['no_group'])<2){
					unset($list_product['no_group']);
					$list_product = array_values($list_product);
			}
			
			$list_discount = [];
			$list_discount = Discount::where('id_content',$content->id)
															 ->get();

			$list_group = $content->_branchs;

			$manager = \View::make('Location.layout.content-manage-location',[
				'content_id'    => $content_id,
				'list_product'  => $list_product,
				'list_discount' => $list_discount,
				'list_group'    => $list_group,
			])->render();


			$list_group = $content->_branchs;
			$arr_content = [$content_id];
			foreach ($list_group as $key => $value) {
				$arr_content[] = $value->id;
			}

			$list_content = Content::whereNotIn('id',$arr_content)
														 ->where('created_by',$content->created_by)
														 ->where('type_user',0)
														 ->where('moderation','publish')
														 ->with('_country')
														 ->with('_city')
														 ->with('_district')
														 ->get();

			$location = \View::make('Location.layout.content-add-same-location',[
				'list_content'    => $list_content,
			])->render();
		}
		return response([
			'manager' => $manager,
			'location' => $location,
		]);
	}

	public function postCreateProduct(Request $request){
		$content_id	= $request->content_id;
		$product_id	= $request->product_id;
		$name 	= $request->name;
		$price	= $request->price;
		
		$des  	= $request->des;
		$group_name  	= '';
		if($product_id==0){
			$product = new Product();
		}else{
			$product = Product::find($product_id);
		}
		$product->name       = $name;
		$product->description = $des;
		$product->price      = $price?$price:0;
		if($request->image){
			$file = $request->image;
			$path = public_path() . '/upload/product/';
			$path_thumbnail = public_path() . '/upload/product_thumbnail/';
			if (!\File::exists($path)) {
				\File::makeDirectory($path, $mode = 0777, true, true);
			}
			if (!\File::exists($path_thumbnail)) {
				\File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
			}

			$img_name = time() . '_product_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

			if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
				self::waterMarkAvatar($file, $img_name, $path, $path_thumbnail);

			$image_product = '/upload/product/' . $img_name;
			$product->image      = $image_product;
		}
		$product->content_id = $content_id;
		$product->type_user  = 0;
		if($product_id==0){
			$product->created_by = Auth::guard('web_client')->user()->id;
		}
		$product->updated_by = Auth::guard('web_client')->user()->id;
		$product->group_name = $group_name;
		$product->group_machine_name = str_replace('-','_',str_slug(clear_str($group_name)));
		$product->currency   = 'VND';
		$product->save();
	}

	public function postCreateDiscount(Request $request){
		$content_id	= $request->content_id;
		$discount_id	= $request->discount_id;
		$name 	= $request->name;
		$price	= $request->price;
		$image	= $request->image;
		$des  	= $request->des;
		if($discount_id==0){
			$discount = new Discount();
		}else{
			$discount = Discount::find($discount_id);
		}
		$discount_from = '1990-04-04';
		$discount_to = '2090-04-04';
		$discount->name             = $name ;
  	$discount->description      = $des ;
  	$discount->date_from        = new Carbon($discount_from.'00:00:00');
  	$discount->date_to          = new Carbon($discount_to.'23:59:59');
  	$discount->created_by       = Auth::guard('web_client')->user()->id ;
  	$discount->updated_by       = Auth::guard('web_client')->user()->id ;
    $discount->id_content       = $content_id;
    $discount->active           = 1;
    $discount->approved         = 1;
    $discount->price            = $price;
    if($request->image){
			$file = $request->image;
      $path = public_path() . '/upload/discount/';
      $path_thumbnail = public_path() . '/upload/discount_thumbnail/';
      if (!\File::exists($path)) {
          \File::makeDirectory($path, $mode = 0777, true, true);
      }
      if (!\File::exists($path_thumbnail)) {
          \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
      }
      $img_name = time() . '_discount_' . vn_string($file->getClientOriginalName());

      if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
				self::waterMarkAvatar($file, $img_name, $path, $path_thumbnail);

      $discount->image  = '/upload/discount/' . $img_name;
    }
		$discount->save();
	}
	
	public function addBranch(Request $request){
		$content_id	= $request->content_id;
		$arr_id	= $request->arr_id;
		foreach ($arr_id as $key => $value) {
			$branch = new Branch();
			$branch->id_content = $content_id;
			$branch->id_content_other = $value;
			$branch->active = 1;
			$branch->save();
		}
	}


	public function getremoveProduct($id){
		Product::where('id',$id)
					 ->where('created_by',Auth::guard('web_client')->user()->id)
					 ->delete();
	}
	public function getremoveDiscount($id){
		Discount::where('id',$id)
						->where('created_by',Auth::guard('web_client')->user()->id)
						->delete();
	}
	public function removeBranch(Request $request){
		if($request->id || $request->content_id){
			Branch::where('id_content_other',$request->id)
						->where('id_content',$request->content_id)
						->delete();
		}
	}
}

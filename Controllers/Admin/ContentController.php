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

class ContentController extends BaseController {

    public function getListContent(Request $request) {
        $per_page = Session::has('pagination.' . \Route::currentRouteName()) ? session('pagination.' . \Route::currentRouteName()) : 10;

        $all_content = Content::select('contents.*')->with('_country')->with('_city')
                        ->with('_district')->with('_category_type')->with('_created_by')->with('_updated_by')->with('_created_by_client')->with('_updated_by_client');

        $input = $request->all();
        if (isset($input['keyword'])) {
            $keyword = $input['keyword'];
        } else {
            $keyword = '';
        }

        if (isset($keyword) && $keyword != '') {

            $all_content->Where(function ($query) use ($keyword) {
                $query->where('contents.name', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('contents.alias', 'LIKE', '%' . str_slug_custom($keyword) . '%');
            });
        }

        if (isset($request->date_from)) {
            $date_from = $input['date_from'];
        } else {
            $date_from = '';
        }
        if (isset($date_from) && $date_from != '') {
            $all_content->where('created_at', '>=', $date_from . ' 00:00:00');
        }

        if (isset($request->date_to)) {
            $date_to = $input['date_to'];
        } else {
            $date_to = '';
        }
        if (isset($date_to) && $date_to != '') {
            $all_content->where('created_at', '<=', $date_to . ' 23:59:00');
        }

        if (isset($input['category'])) {
            $category = $input['category'];
        } else {
            $category = '';
        }

        if (isset($category) && $category != '') {
            $all_content->where('contents.id_category', '=', $category)->groupBy('contents.id');
        }

        if (isset($input['users'])) {
            $user = $input['users'];
        } else {
            $user = '';
        }

        if (isset($user) && $user != '') {
            $all_content->where('contents.updated_by', '=', $user)
                        ->where('type_user_update',1)
                        ->groupBy('contents.id');
        }

        if (isset($input['client'])) {
            $client = $input['client'];
        } else {
            $client = '';
        }

        if (isset($client) && $client != '') {
            $all_content->where('contents.created_by', '=', $client)
                        ->where('type_user',0)
                        ->groupBy('contents.id');
        }

        if (isset($input['moderation'])) {
            $moderation = $input['moderation'];
        } else {
            $moderation = '';
        }

        if ($request->category_item) {
            $all_content->leftJoin('category_content', 'contents.id', '=', 'category_content.id_content')
                    ->where('category_content.id_category_item', '=', $request->category_item);
            $category_item = $request->category_item;
        } else {
            $category_item = '';
        }

        if ($request->country) {
            $all_content->where('contents.country', '=', $request->country);
            $country = $request->country;
        } else {
            $country = '';
        }
        if ($request->city) {
            $all_content->where('contents.city', '=', $request->city);
            $city = $request->city;
        } else {
            $city = '';
        }
        if ($request->district) {
            $all_content->where('contents.district', '=', $request->district);
            $district = $request->district;
        } else {
            $district = '';
        }

        if (isset($moderation) && $moderation != '') {
            $all_content->where('moderation', '=', $moderation);
        } else {

        }
        // Nếu ko phải super admin thì chỉ load content của người đó tạo
        // if(Auth::guard('web')->user() && !Auth::guard('web')->user()->hasRole('super_admin')){
        // 	$all_content->where('created_by', '=', Auth::guard('web')->user()->id);
        // }

        $all_content = $all_content->orderBy('created_at', 'DESC')
                ->orderBy('id', 'DESC');
        $list_content = $all_content->paginate($per_page);

        if (Auth::guard('web')->user()->hasRole('content') == true) {
            $list_category = Category::where('active', '=', 1)->get();
        } else {
            $list_category = Category::where('active', '=', 1)->get();
        }
        $list_country = Country::all();
        // dd($list_content->toArray());
        $all_user = User::select('users.*')->where('users.id', '!=', '1')->with('_role_user')->get();
        $all_client = Client::get();
        return view('Admin.content.list', [
            'list_content' => $list_content,
            'keyword' => $keyword,
            'list_category' => $list_category,
            'list_country' => $list_country,
            'category' => $category,
            'moderation' => $moderation,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'category_item' => $category_item,
            'country' => $country,
            'city' => $city,
            'district' => $district,
            'all_user' => $all_user,
            'all_client'=>$all_client,
            'user' => $user,
            'client' => $client,
        ]);
    }

    public function getAddContent($category_type) {
        $data['id_category'] = Category::where('machine_name', '=', $category_type)->pluck('id')->first();
        $data['list_category_item'] = CategoryItem::where([['category_id', '=', $data['id_category']], ['active', '=', '1']])->pluck('name', 'id');
        $data['list_service'] = CategoryService::where('id_category', '=', $data['id_category'])->get();
        $data['list_country'] = Country::pluck('name', 'id');
        $data['list_group'] = Group::where('id_category', '=', $data['id_category'])->pluck('name', 'id');
        $data['role_user'] = RoleUser::where('user_id', '=', Auth::guard('web')->user()->id)->pluck('role_id')->first();
        $data['type'] = $category_type;
        if ($category_type == 'food' || $category_type == 'drinks') {
            return view('Admin.content.add_food', ['data' => $data]);
        } elseif ($category_type == 'bank') {
            return view('Admin.content.add_bank', ['data' => $data]);
        } elseif ($category_type == 'shop' || $category_type == 'mua_sam') {
            return view('Admin.content.add_shop', ['data' => $data]);
        } elseif ($category_type == 'entertainment' || $category_type == 'hotel' || $category_type == 'tram_xang') {
            return view('Admin.content.add_hotel', ['data' => $data]);
        } else {
            return view('Admin.content.add_food', ['data' => $data]);
        }
    }

    public function getImportContent($category_type) {
        $data['id_category'] = Category::where('machine_name', '=', $category_type)->pluck('id')->first();
        $data['list_category_item'] = CategoryItem::where([['category_id', '=', $data['id_category']], ['active', '=', '1']])->pluck('name', 'id');
        $data['list_service'] = CategoryService::where('id_category', '=', $data['id_category'])->get();
        $data['list_country'] = Country::pluck('name', 'id');
        $data['list_group'] = Group::where('id_category', '=', $data['id_category'])->pluck('name', 'id');
        $data['role_user'] = RoleUser::where('user_id', '=', Auth::guard('web')->user()->id)->pluck('role_id')->first();
        $data['type'] = $category_type;
        if ($category_type == 'food') {
            return view('Admin.content.import_food', ['data' => $data]);
        } elseif ($category_type == 'drinks') {
            return view('Admin.content.import_food', ['data' => $data]);
        } elseif ($category_type == 'bank') {
            return view('Admin.content.import_food', ['data' => $data]);
        } elseif ($category_type == 'shop') {
            return view('Admin.content.import_food', ['data' => $data]);
        } elseif ($category_type == 'hotel') {
            return view('Admin.content.import_food', ['data' => $data]);
        } elseif ($category_type == 'entertainment') {
            return view('Admin.content.import_food', ['data' => $data]);
        } else {
            return view('Admin.content.import_food', ['data' => $data]);
        }
    }

    public function getUpdateContent($category_type, $id) {
        $data['id_category'] = Category::where('machine_name', '=', $category_type)->pluck('id')->first();
        ///// Thêm with _date_open để lấy giờ mở cửa
        $content = Content::with('_date_open')->find($id);

        $data['list_category_item'] = CategoryItem::where([['category_id', '=', $data['id_category']], ['active', '=', '1']])->pluck('name', 'id')->toArray();
        $data['list_category_item_content'] = CategoryContent::where('id_content', '=', $id)->pluck('id_category_item')->toArray();

        $data['list_service'] = CategoryService::where('id_category', '=', $data['id_category'])->with('_service_item')->get();
        $data['list_service_content'] = ServiceContent::where('id_content', '=', $id)->pluck('id_service_item')->toArray();

        $data['list_group'] = Group::where('id_category', '=', $data['id_category'])->pluck('name', 'id')->toArray();
        $data['list_group_content'] = GroupContent::where('id_content', '=', $id)->pluck('id_group')->first();

        $data['list_country'] = Country::pluck('name', 'id');
        $data['list_city'] = City::where('id_country', '=', $content->country)->pluck('name', 'id');
        $data['list_districts'] = District::where('id_city', '=', $content->city)->pluck('name', 'id');
        $data['list_image_space'] = ImageSpace::where('id_content', '=', $id)->get();
        $data['list_image_menu'] = ImageMenu::where('id_content', '=', $id)->get();
        $data['role_user'] = RoleUser::where('user_id', '=', Auth::guard('web')->user()->id)->pluck('role_id')->first();
        $data['url_previous'] = url()->previous();
        $data['link_content'] = LinkContent::where('id_content', '=', $id)->get()->toArray();
        $data['products'] = Product::where('content_id', '=', $id)->orderBy('group_name')->get();
        $data['group_product'] = Product::where('content_id', '=', $id)
                                        ->groupBy('group_name')
                                        ->whereNotNull('group_name')
                                        ->pluck('group_name');
        $data['list_product'] = [];
        $data['list_product']['no_group']['group_name'] = '';
        $arr_has_group=[];
        $arr_no_group=[];
        foreach ($data['group_product'] as $key => $group) {
            $data['list_product'][$key]['group_name'] = $group;
            foreach ($data['products'] as $key2 => $product) {
                if($product->group_name === $group && !in_array($product->id,$arr_has_group)){
                    $data['list_product'][$key][] = $product;
                    $arr_has_group[] = $product->id;
                }else{
                    if($product->group_name===null && !in_array($product->id,$arr_no_group)){
                        $data['list_product']['no_group'][] = $product;
                        $arr_no_group[] = $product->id;
                    }
                }
            }
        }

        if(count($data['list_product']['no_group'])<2){
            unset($data['list_product']['no_group']);
            $data['list_product'] = array_values($data['list_product']);
        }
        //dd($data['list_product']);

        $data['type'] = $category_type;
        if ($category_type == 'food' || $category_type == 'drinks') {
            return view('Admin.content.update_food', ['content' => $content, 'data' => $data]);
        } elseif ($category_type == 'bank') {
            return view('Admin.content.update_bank', ['content' => $content, 'data' => $data]);
        } elseif ($category_type == 'shop' || $category_type == 'mua_sam') {
            return view('Admin.content.update_shop', ['content' => $content, 'data' => $data]);
        } elseif ($category_type == 'entertainment' || $category_type == 'hotel' || $category_type == 'tram_xang') {
            return view('Admin.content.update_hotel', ['content' => $content, 'data' => $data]);
        } else {
            return view('Admin.content.update_food', ['content' => $content, 'data' => $data]);
        }
    }

    public function getUpdateCategoryContent($id) {
        $content = Content::find($id);
        $data['categories'] = Category::where('active', '=', '1')->pluck('name','id')->toArray();
        $data['id_category'] = $content->id_category;

        $data['list_category_item'] = CategoryItem::where([['category_id', '=', $data['id_category']], ['active', '=', '1']])->pluck('name', 'id')->toArray();
        $data['list_category_item_content'] = CategoryContent::where('id_content', '=', $id)->pluck('id_category_item')->toArray();

        $data['list_service'] = CategoryService::where('id_category', '=', $data['id_category'])->with('_service_item')->get();
        $data['list_service_content'] = ServiceContent::where('id_content', '=', $id)->pluck('id_service_item')->toArray();

        return view('Admin.content.update_category', ['content' => $content, 'data' => $data]);
    }

    public function getListService($id){
        $category_services = CategoryService::where('id_category', '=', $id)->with('_service_item')->get();
        $category_item = CategoryItem::where([['category_id', '=', $id], ['active', '=', '1']])->get();
        $html_category_services = view('Admin.content.ajax_category_services',['category_services'=>$category_services])->render();
        $html_category_item = view('Admin.content.ajax_category_item',['category_item'=>$category_item])->render();
        $arrReturn['html_category_services'] = $html_category_services;
        $arrReturn['html_category_item'] = $html_category_item;
        return response($arrReturn);
    }

    public function postUpdateCategoryContent($id,Request $request){
        $content_update = Content::find($id);

        $rules = [

             'id_category' => 'required',
             'category_item' => 'required',
        ];


        $messages = [
            'id_category.required' => trans('valid.id_category_required'),
            'category_item.required' => trans('valid.category_item_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $content_update->id_category = $request->id_category;
            $content_update->updated_by = Auth::guard('web')->user()->id;
            if ($content_update->save()) {
                CategoryContent::where('id_content',$id)->delete();
                if ($request->category_item) {
                    foreach ($request->category_item as $value) {
                        CategoryContent::create([
                            'id_content' => $id,
                            'id_category_item' => $value,
                        ]);
                    }
                }

                if ($request->service) {
                    ServiceContent::where('id_content', '=', $id)->delete();
                    foreach ($request->service as $value) {
                        ServiceContent::create([
                            'id_content' => $id,
                            'id_service_item' => $value,
                        ]);
                    }
                }
                create_tag_search($id);
                return redirect()->route('list_content');

            } else {
                $errors = new MessageBag(['error' => 'Không cập nhật được được content']);
                return redirect()->back()->withErrors($errors)->withInput();
            }
        }
    }

    public function postAddFoodContent(Request $request) {
        // dd($request->product);
        $rules = [
            'name' => 'required|unique:contents,name',
            'alias' => 'required|unique:contents,alias',
            'id_category' => 'required',
            // 'category_item' => 'required',
            // 'open_from' => 'required',
            // 'open_to' => 'required',
           // 'price_from' => 'required',
           // 'price_to' => 'required',
            'country' => 'required',
            'city' => 'required',
            'district' => 'required',
            'avatar' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ];
        $messages = [
            'name.required' => trans('valid.name_required'),
            'name.unique' => trans('valid.name_unique'),
            'alias.required' => trans('valid.alias_required'),
            'alias.unique' => trans('valid.alias_unique'),
            'id_category.required' => trans('valid.id_category_required'),
            // 'category_item.required' => trans('valid.category_item_required'),
            'price_from.required' => trans('valid.price_from_required'),
            'price_to.required' => trans('valid.price_to_required'),
            'country.required' => trans('valid.country_required'),
            'city.required' => trans('valid.city_required'),
            'district.required' => trans('valid.location_required'),
            'address.required' => trans('valid.address_required'),
            'avatar.required' => trans('valid.avatar_required'),
            'lat.required' => trans('valid.lat_required'),
            'lng.required' => trans('valid.lng_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $id_user = Auth::guard('web')->user()->id;

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

            $content = Content::create([
                        'name' => $request->name,
                        'alias' => $request->alias,
                        'id_category' => $request->id_category,
                        'country' => $request->country,
                        'city' => $request->city,
                        'district' => $request->district,
                        'address' => $request->address?$request->address:"",
                        'tag' => $request->tag,
                        'phone' => isset($request->phone) ? $request->phone : '',
                        // 'open_from' => date("H:i:s", strtotime($request->open_from)),
                        // 'open_to' => date("H:i:s", strtotime($request->open_to)),
                       // 'price_from' => $request->price_from,
                       // 'price_to' => $request->price_to,
                        'currency' => $request->currency?$request->currency:'VND',
                        'website' => $request->website,
                        'email' => isset($request->email) ? $request->email : '',
                        'description' => $request->description?$request->description:"",
                        'avatar' => $content_avatar,
                        'vote' => 0,
                        'like' => 0,
                        'type_user' => 1,
                        'active' => ($request->moderation == 'publish') ? 1 : 0,
                        'lat' => $request->lat,
                        'lng' => $request->lng,
                        'moderation' => $request->moderation,
                        'created_by' => $id_user,
                        'updated_by' => $id_user,
            ]);

            $lastIdContent = $content->id;

            ////////// Start Add thêm giờ mở cửa

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

            if ($request->product) {
                foreach ($request->product as $group) {
                    $group_name =  $group['group_name']?$group['group_name']:'';
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
                                $product->type_user  = 1;
                                $product->created_by = Auth::guard('web')->user()->id;
                                $product->updated_by = Auth::guard('web')->user()->id;
                                $product->group_name = $group_name;
                                $product->group_machine_name = str_replace('-','_',str_slug(clear_str($group_name)));
                                $product->currency   = $value['currency']?$value['currency']:'VND';
                                $product->save();
                            }
                        }
                    }
                }
            }

            ////////// End Add thêm giờ mở cửa
            // SeoContent::create([
            // 	'id_content' => $lastIdContent,
            // 	'key_word' => isset($request->seo_keyword) ? $request->seo_keyword : '',
            // 	'description' => isset($request->seo_description) ? $request->seo_description : '',
            // ]);

            if ($request->category_item) {
                foreach ($request->category_item as $value) {
                    CategoryContent::create([
                        'id_content' => $lastIdContent,
                        'id_category_item' => $value,
                    ]);
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
                foreach ($request->image_space as $key => $file) {

                    $img_name = (time() + $key) . '_space_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

                    if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
                        self::waterMark($file, $img_name, $path, $path_thumbnail);

                    $image_space = '/upload/img_content/' . $img_name;

                    ImageSpace::create([
                        'id_content' => $lastIdContent,
                        'name' => $image_space,
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
                foreach ($request->image_menu as $key => $file) {
                    $img_name = (time() + $key) . '_menu_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

                    if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
            self::waterMark($file, $img_name, $path, $path_thumbnail);

                    $image_menu = '/upload/img_content/' . $img_name;

                    ImageMenu::create([
                        'id_content' => $lastIdContent,
                        'name' => $image_menu,
                    ]);
                }
            }

            if ($request->link) {
                foreach ($request->link as $value) {
                    if (isset($value)) {
                        $infoVideo = $this->getInfoVideo($value);
                        if(count($infoVideo)>0){
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

            // if(!$request->description){
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

            create_tag_search($lastIdContent);
            return redirect()->route('list_content')->with(['status' => trans('global.locations').' <a href="' . route('update_content', ['category_type' => $content->_category_type->machine_name, 'id' => $content->id]) . '">' . $content->name . '</a> '.trans('valid.added_successful').'</a>']);
        }
    }

    public function postUpdateFoodContent(Request $request, $id) {
        $content_update = Content::find($id);
        $old_active = $content_update->active;
        $rules = [
            'name' => 'required',
            'alias' => 'required|unique:contents,alias',
            'id_category' => 'required',
            // 'category_item' => 'required',
            // 'open_from' => 'required',
            // 'open_to' => 'required',
            // 'price_from' => 'required',
            // 'price_to' => 'required',
            'country' => 'required',
            'city' => 'required',
            'district' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ];

        if (trim($content_update->name) == $request->name) {
            $rules['name'] = 'required';
        }

        if ($content_update->alias == $request->alias) {
            $rules['alias'] = 'required';
        }

        $messages = [
            'name.required' => trans('valid.name_required'),
            'name.unique' => trans('valid.name_unique'),
            'alias.required' => trans('valid.alias_required'),
            'alias.unique' => trans('valid.alias_unique'),
            'id_category.required' => trans('valid.id_category_required'),
            // 'category_item.required' => trans('valid.category_item_required'),
            // 'open_from.required' => 'Thời gian đóng mở cửa là trường bắt buộc',
            // 'open_to.required' => 'Thời gian đóng mở cửa là trường bắt buộc',
            'price_from.required' => trans('valid.price_from_required'),
            'price_to.required' => trans('valid.price_to_required'),
            'country.required' => trans('valid.country_required'),
            'city.required' => trans('valid.city_required'),
            'district.required' => trans('valid.location_required'),
            'address.required' => trans('valid.address_required'),
            'lat.required' => trans('valid.lat_required'),
            'lng.required' => trans('valid.lng_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
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
                if ($content_update->avatar != '/img_default/default_content.png') {
                    if (file_exists(public_path($content_update->avatar))) {
                        unlink(public_path($content_update->avatar));
                    }

                    if (file_exists(public_path(str_replace('img_content', 'img_content_thumbnail', $content_update->avatar)))) {
                        unlink(public_path(str_replace('img_content', 'img_content_thumbnail', $content_update->avatar)));
                    }
                }

                $content_update->avatar = '/upload/img_content/' . $img_name;
            }

            $content_update->name = $request->name;
            $content_update->alias = $request->alias;
            $content_update->id_category = $request->id_category;
            $content_update->country = $request->country;
            $content_update->city = $request->city;
            $content_update->district = $request->district;
            $content_update->address = $request->address?$request->address:"";
            $content_update->tag = $request->tag;
            $content_update->phone = isset($request->phone) ? $request->phone : '';
            $content_update->open_from = date("H:i:s", strtotime($request->open_from));
            $content_update->open_to = date("H:i:s", strtotime($request->open_to));
           // $content_update->price_from = $request->price_from;
           // $content_update->price_to = $request->price_to;
            $content_update->currency = $request->currency?$request->currency:'VND';
            $content_update->website = $request->website;
            $content_update->email = isset($request->email) ? $request->email : '';
            $content_update->description = $request->description?$request->description:"";
            if (Category::where([['id', '=', $request->id_category], ['active', '=', '1']])->first()) {
                $content_update->moderation = $request->moderation;
                $content_update->active = ($request->moderation == 'publish') ? 1 : 0;
            }
            $content_update->lat = $request->lat;
            $content_update->lng = $request->lng;
            $content_update->updated_by = Auth::guard('web')->user()->id;
            $content_update->type_user_update = 1;

            if ($content_update->save()) {
                //// Update Giờ mở cửa mới
                if ($request->date_open) {
                    DateOpen::where('id_content', '=', $id)->delete();
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

                // if ($request->product) {
                //     foreach ($request->product as $value) {
                //         if($value['id'] == 0){
                //             $product = new Product();
                //         }else{
                //             $product = Product::find($value['id']);
                //         }

                //         if($product && isset($value['name']) && $value['name']!=''){
                //             $product->name       = $value['name'];
                //             $product->price      = $value['price']?$value['price']:0;
                //             if(isset($value['image'])){
                //                 $file = $value['image'];
                //                 $path = public_path() . '/upload/product/';
                //                 $path_thumbnail = public_path() . '/upload/product_thumbnail/';
                //                 if (!\File::exists($path)) {
                //                     \File::makeDirectory($path, $mode = 0777, true, true);
                //                 }
                //                 if (!\File::exists($path_thumbnail)) {
                //                     \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
                //                 }

                //                 $img_name = time() . '_product_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

                //                 if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
                //self::waterMarkAvatar($file, $img_name, $path, $path_thumbnail);

                //                 $image_product = '/upload/product/' . $img_name;
                //                 $product->image      = $image_product;
                //             }
                //             $product->content_id = $id;
                //             $product->type_user  = 1;
                //             $product->updated_by = Auth::guard('web')->user()->id;
                //             $product->currency   = $value['currency']?$value['currency']:'VND';
                //             $product->save();
                //         }
                //     }
                // }

                if ($request->product) {
                    foreach ($request->product as $group) {
                        $group_name =  $group['group_name']?$group['group_name']:'';
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
                                    $product->type_user  = 1;
                                    $product->updated_by = Auth::guard('web')->user()->id;
                                    $product->group_name = $group_name;
                                    $product->group_machine_name = str_replace('-','_',str_slug(clear_str($group_name)));
                                    $product->currency   = $value['currency']?$value['currency']:'VND';
                                    $product->save();
                                }
                            }
                        }
                    }
                }

                CategoryContent::where('id_content',$id)->delete();
                if ($request->category_item) {
                    CategoryContent::where('id_content', '=', $id)->delete();
                    foreach ($request->category_item as $value) {
                        CategoryContent::create([
                            'id_content' => $id,
                            'id_category_item' => $value,
                        ]);
                    }
                }

                if ($request->service) {
                    ServiceContent::where('id_content', '=', $id)->delete();
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

                    foreach ($request->image_space as $key => $file) {

                        $img_name = (time() + $key) . '_space_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

                        if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
                self::waterMark($file, $img_name, $path, $path_thumbnail);

                        $image_space = '/upload/img_content/' . $img_name;

                        ImageSpace::create([
                            'id_content' => $id,
                            'name' => $image_space,
                        ]);
                    }
                }

                if ($request->image_menu) {
                    $path = public_path() . '/upload/img_content/';
                    $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';

                    foreach ($request->image_menu as $key => $file) {

                        $img_name = (time() + $key) . '_menu_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

                        if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
                self::waterMark($file, $img_name, $path, $path_thumbnail);

                        $image_menu = '/upload/img_content/' . $img_name;

                        ImageMenu::create([
                            'id_content' => $id,
                            'name' => $image_menu,
                        ]);
                    }
                }
                LinkContent::where('id_content', '=', $id)->delete();
                if ($request->link) {
                    foreach ($request->link as $value) {
                        if (isset($value)) {
                            $infoVideo = $this->getInfoVideo($value);
                            if(count($infoVideo)>0){
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

                

                if($content_update->type_user==0){
                  if($content_update->active && $content_update->active != $old_active){
                    $notifi = new Notifi();
                    $notifi->createNotifiUserByTemplate('Admin'.DS.'content.active_content',$content_update->created_by,['content'=>$content_update->name]);
                  }else{
                    $notifi = new Notifi();
                    $notifi->createNotifiUserByTemplate('Admin'.DS.'content.inactive_content',$content_update->created_by,['content'=>$content_update->name]);
                  }
                }
                create_tag_search($id);
                if(!check_update_content($content_update->id)){
                    $notifi = new Notifi();
                    $link_content_update =LOCATION_URL.'/edit/location/'.$content_update->id;
                    $text_content_update = trans('Admin'.DS.'content.noti_update_content', [ 'content' => $content_update->name ]);
                    $notifi->createNotifiUserByTemplate($text_content_update,$content_update->created_by,['content' => $content_update->name, 'content_id' => $content_update->id],$link_content_update);
                }
                //~~
                if($request->url_previous){
                    return redirect($request->url_previous)->with(['status' => trans('global.locations').' <a href="' . route('update_content', ['category_type' => $content_update->_category_type->machine_name, 'id' => $content_update->id]) . '">' . $content_update->name . '</a> '.trans('valid.updated_successful').'</a>']);
                }else{
                    return redirect()->route('list_content')->with(['status' => trans('global.locations').' <a href="' . route('update_content', ['category_type' => $content_update->_category_type->machine_name, 'id' => $content_update->id]) . '">' . $content_update->name . '</a> '.trans('valid.updated_successful').'</a>']);
                }

            } else {
                $errors = new MessageBag(['error' => 'Không cập nhật được được content']);
                return redirect()->back()->withErrors($errors)->withInput();
            }
        }
    }

    public function postAddBankContent(Request $request) {
        $rules = [
            'name' => 'required|unique:contents,name',
            'alias' => 'required|unique:contents,alias',
            'id_category' => 'required',
            // 'category_item' => 'required',
            // 'open_from' => 'required',
            // 'open_to' => 'required',
            'country' => 'required',
            'city' => 'required',
            'district' => 'required',
            'avatar' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ];
        $messages = [
            'name.required' => trans('valid.name_required'),
            'name.unique' => trans('valid.name_unique'),
            'alias.required' => trans('valid.alias_required'),
            'alias.unique' => trans('valid.alias_unique'),
            'id_category.required' => trans('valid.id_category_required'),
            // 'category_item.required' => trans('valid.category_item_required'),
            // 'open_from.required' => 'Thời gian đóng mở cửa là trường bắt buộc',
            // 'open_to.required' => 'Thời gian đóng mở cửa là trường bắt buộc',
            'country.required' => trans('valid.country_required'),
            'city.required' => trans('valid.city_required'),
            'district.required' => trans('valid.location_required'),
            'address.required' => trans('valid.address_required'),
            'avatar.required' => trans('valid.avatar_required'),
            'lat.required' => trans('valid.lat_required'),
            'lng.required' => trans('valid.lng_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $id_user = Auth::guard('web')->user()->id;

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

            $content = Content::create([
                        'name' => $request->name,
                        'alias' => $request->alias,
                        'id_category' => $request->id_category,
                        'country' => $request->country,
                        'city' => $request->city,
                        'district' => $request->district,
                        'address' => $request->address?$request->address:"",
                        'tag' => $request->tag,
                        'phone' => isset($request->phone) ? $request->phone : '',
                        // 'open_from' => date("H:i:s", strtotime($request->open_from)),
                        // 'open_to' => date("H:i:s", strtotime($request->open_to)),
                        'price_from' => 0,
                        'price_to' => 0,
                        'currency' => '',
                        'website' => $request->website,
                        'email' => isset($request->email) ? $request->email : '',
                        'description' => $request->description?$request->description:"",
                        'avatar' => $content_avatar,
                        'vote' => 0,
                        'like' => 0,
                        'type_user' => 1,
                        'extra_type' => isset($request->bank_type) ? 'ATM' : 'BANK',
                        'active' => ($request->moderation == 'publish') ? 1 : 0,
                        'lat' => $request->lat,
                        'lng' => $request->lng,
                        'moderation' => $request->moderation,
                        'created_by' => $id_user,
                        'updated_by' => $id_user,
            ]);

            $lastIdContent = $content->id;

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

            if ($request->category_item) {
                foreach ($request->category_item as $value) {
                    CategoryContent::create([
                        'id_content' => $lastIdContent,
                        'id_category_item' => $value,
                    ]);
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

            if ($request->image_space) {
                $path = public_path() . '/upload/img_content/';
                $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
                if (!\File::exists($path)) {
                    \File::makeDirectory($path, $mode = 0777, true, true);
                }
                if (!\File::exists($path_thumbnail)) {
                    \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
                }
                foreach ($request->image_space as $key => $file) {

                    $img_name = (time() + $key) . '_space_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

                    if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
                self::waterMark($file, $img_name, $path, $path_thumbnail);

                    $image_space = '/upload/img_content/' . $img_name;

                    ImageSpace::create([
                        'id_content' => $lastIdContent,
                        'name' => $image_space,
                    ]);
                }
            }

            if ($request->link) {
                foreach ($request->link as $value) {
                    if (isset($value)) {
                        $infoVideo = $this->getInfoVideo($value);
                        if(count($infoVideo)>0){
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

            // if(!$request->description){
            //       $description = '';
            //       $content = Content::where('id','=',$lastIdContent)
            //                      ->with('_category_type')
            //                      ->with('_category_items')
            //                      ->with('_country')
            //                      ->with('_city')
            //                      ->with('_district')
            //                      ->with('_date_open')
            //                      ->first();
            //       $description .= $content->name.' ';
            //       $description .= 'tại '.$content->address.' '.$content->_district->name.' '.$content->_city->name.' '.$content->_country->name.', ';
            //       if($content->_category_items){
            //           $description .= 'thuộc thể loại ';
            //           foreach ($content->_category_items as $key_cat => $cat_item) {
            //               if($key_cat==0){
            //                   $description .= mb_strtolower($cat_item->name);
            //               }else{
            //                   $description .= ' - '.mb_strtolower($cat_item->name);
            //               }

            //           }
            //       }else{
            //           if($content->_category_type){
            //               $description .= 'thuộc thể loại '.mb_strtolower($content->_category_type->name);
            //           }
            //       }

            //       if($content->_date_open){
            //           $description .= ', mở cửa '.mb_strtolower(create_open_time($content->_date_open, \App::getLocale())).', ';
            //       }
            //       // if($content->price_from > 0 && $content->price_to > 0){
            //       //     $description .= 'giá từ '.$content->price_from.$content->currency.' ';
            //       //     $description .= 'đến '.$content->price_to.$content->currency;
            //       // }
            //       $description .='.';
            //       $content->description = $description;
            //       $content->save();
            //     }
                create_tag_search($lastIdContent);
            return redirect()->route('list_content')->with(['status' => trans('global.locations').' <a href="' . route('update_content', ['category_type' => $content->_category_type->machine_name, 'id' => $content->id]) . '">' . $content->name . '</a> '.trans('valid.added_successful').'</a>']);
        }
    }

    public function postUpdateBankContent(Request $request, $id) {
        $content_update = Content::find($id);
        $old_active = $content_update->active;
        $rules = [
            'name' => 'required',
            'alias' => 'required|unique:contents,alias',
            'id_category' => 'required',
            // 'category_item' => 'required',
            // 'open_from' => 'required',
            // '<o></o>pen_to' => 'required',
            'country' => 'required',
            'city' => 'required',
            'district' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ];

        if (trim($content_update->name) == $request->name) {
            $rules['name'] = 'required';
        }

        if ($content_update->alias == $request->alias) {
            $rules['alias'] = 'required';
        }

        $messages = [
            'name.required' => trans('valid.name_required'),
            'name.unique' => trans('valid.name_unique'),
            'alias.required' => trans('valid.alias_required'),
            'alias.unique' => trans('valid.alias_unique'),
            'id_category.required' => trans('valid.id_category_required'),
            // 'category_item.required' => trans('valid.category_item_required'),
            // 'open_from.required' => 'Thời gian đóng mở cửa là trường bắt buộc',
            // 'open_to.required' => 'Thời gian đóng mở cửa là trường bắt buộc',
            'country.required' => trans('valid.country_required'),
            'city.required' => trans('valid.city_required'),
            'district.required' => trans('valid.location_required'),
            'address.required' => trans('valid.address_required'),
            'lat.required' => trans('valid.lat_required'),
            'lng.required' => trans('valid.lng_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

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
                if ($content_update->avatar != '/img_default/default_content.png') {
                    if (file_exists(public_path($content_update->avatar))) {
                        unlink(public_path($content_update->avatar));
                    }

                    if (file_exists(public_path(str_replace('img_content', 'img_content_thumbnail', $content_update->avatar)))) {
                        unlink(public_path(str_replace('img_content', 'img_content_thumbnail', $content_update->avatar)));
                    }
                }
                $content_update->avatar = '/upload/img_content/' . $img_name;
            }

            if ($content_update->extra_type == '') {
                $content_update->extra_type = isset($request->bank_type) ? 'ATM' : 'BANK';
            }
            $content_update->name = $request->name;
            $content_update->alias = $request->alias;
            $content_update->country = $request->country;
            $content_update->city = $request->city;
            $content_update->district = $request->district;
            $content_update->address = $request->address?$request->address:"";
            $content_update->tag = $request->tag;
            $content_update->phone = isset($request->phone) ? $request->phone : '';
            // $content_update->open_from = date("H:i:s", strtotime($request->open_from));
            // $content_update->open_to = date("H:i:s", strtotime($request->open_to));
            $content_update->website = $request->website;
            $content_update->email = isset($request->email) ? $request->email : '';
            $content_update->description = $request->description?$request->description:"";
            $content_update->lat = $request->lat;
            $content_update->lng = $request->lng;
            if (Category::where([['id', '=', $request->id_category], ['active', '=', '1']])->first()) {
                $content_update->moderation = $request->moderation;
                $content_update->active = ($request->moderation == 'publish') ? 1 : 0;
            }
            $content_update->updated_by = Auth::guard('web')->user()->id;
            $content_update->type_user_update = 1;

            if ($content_update->save()) {

                if ($request->date_open) {
                    DateOpen::where('id_content', '=', $id)->delete();
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
                CategoryContent::where('id_content',$id)->delete();
                if ($request->category_item) {
                    CategoryContent::where('id_content', '=', $id)->delete();
                    foreach ($request->category_item as $value) {
                        CategoryContent::create([
                            'id_content' => $id,
                            'id_category_item' => $value,
                        ]);
                    }
                }

                if ($request->service) {
                    ServiceContent::where('id_content', '=', $id)->delete();
                    foreach ($request->service as $value) {
                        ServiceContent::create([
                            'id_content' => $id,
                            'id_service_item' => $value,
                        ]);
                    }
                }

                if ($request->image_space) {
                    $path = public_path() . '/upload/img_content/';
                    $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';

                    foreach ($request->image_space as $key => $file) {

                        $img_name = (time() + $key) . '_space_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

                        if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
                self::waterMark($file, $img_name, $path, $path_thumbnail);

                        $image_space = '/upload/img_content/' . $img_name;

                        ImageSpace::create([
                            'id_content' => $id,
                            'name' => $image_space,
                        ]);
                    }
                }
                LinkContent::where('id_content', '=', $id)->delete();
                if ($request->link) {
                    foreach ($request->link as $value) {
                        if (isset($value)) {
                            $infoVideo = $this->getInfoVideo($value);
                            if(count($infoVideo)>0){
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
                

                if($content_update->type_user==0){
                  if($content_update->active && $content_update->active != $old_active){
                    $notifi = new Notifi();
                    $notifi->createNotifiUserByTemplate('Admin'.DS.'content.active_content',$content_update->created_by,['content'=>$content_update->name]);
                  }else{
                    $notifi = new Notifi();
                    $notifi->createNotifiUserByTemplate('Admin'.DS.'content.inactive_content',$content_update->created_by,['content'=>$content_update->name]);
                  }
                }
                create_tag_search($id);
                if(!check_update_content($content_update->id)){
                    $notifi = new Notifi();
                    $link_content_update =LOCATION_URL.'/edit/location/'.$content_update->id;
                    $text_content_update = trans('Admin'.DS.'content.noti_update_content', [ 'content' => $content_update->name ]);
                    $notifi->createNotifiUserByTemplate($text_content_update,$content_update->created_by,['content' => $content_update->name, 'content_id' => $content_update->id],$link_content_update);
                }
                //~~

                if($request->url_previous){
                    return redirect($request->url_previous)->with(['status' => trans('global.locations').' <a href="' . route('update_content', ['category_type' => $content_update->_category_type->machine_name, 'id' => $content_update->id]) . '">' . $content_update->name . '</a> '.trans('valid.updated_successful').'</a>']);
                }else{
                    return redirect()->route('list_content')->with(['status' => trans('global.locations').' <a href="' . route('update_content', ['category_type' => $content_update->_category_type->machine_name, 'id' => $content_update->id]) . '">' . $content_update->name . '</a> '.trans('valid.updated_successful').'</a>']);
                }
                
            } else {
                $errors = new MessageBag(['error' => 'Không cập nhật được được content']);
                return redirect()->back()->withErrors($errors)->withInput();
            }
        }
    }

    public function postAddShopContent(Request $request) {
        $rules = [
            'name' => 'required|unique:contents,name',
            'alias' => 'required|unique:contents,alias',
            'id_category' => 'required',
            // 'category_item' => 'required',
           // 'open_from' => 'required',
           // 'open_to' => 'required',
           // 'price_from' => 'required',
           // 'price_to' => 'required',
            'country' => 'required',
            'city' => 'required',
            'district' => 'required',
            'avatar' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ];
        $messages = [
            'name.required' => trans('valid.name_required'),
            'name.unique' => trans('valid.name_unique'),
            'alias.required' => trans('valid.alias_required'),
            'alias.unique' => trans('valid.alias_unique'),
            'id_category.required' => trans('valid.id_category_required'),
            // 'category_item.required' => trans('valid.category_item_required'),
            // 'open_from.required' => 'Thời gian đóng mở cửa là trường bắt buộc',
            // 'open_to.required' => 'Thời gian đóng mở cửa là trường bắt buộc',
            'price_from.required' => trans('valid.price_from_required'),
            'price_to.required' => trans('valid.price_to_required'),
            'country.required' => trans('valid.country_required'),
            'city.required' => trans('valid.city_required'),
            'district.required' => trans('valid.location_required'),
            'address.required' => trans('valid.address_required'),
            'avatar.required' => trans('valid.avatar_required'),
            'lat.required' => trans('valid.lat_required'),
            'lng.required' => trans('valid.lng_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $id_user = Auth::guard('web')->user()->id;

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

            $content = Content::create([
                        'name' => $request->name,
                        'alias' => $request->alias,
                        'id_category' => $request->id_category,
                        'country' => $request->country,
                        'city' => $request->city,
                        'district' => $request->district,
                        'address' => $request->address?$request->address:"",
                        'tag' => $request->tag,
                        'phone' => isset($request->phone) ? $request->phone : '',
                       // 'open_from' => date("H:i:s", strtotime($request->open_from)),
                       // 'open_to' => date("H:i:s", strtotime($request->open_to)),
                       // 'price_from' => $request->price_from,
                       // 'price_to' => $request->price_to,
                        'currency' => $request->currency?$request->currency:'VND',
                        'website' => $request->website,
                        'email' => isset($request->email) ? $request->email : '',
                        'description' => $request->description?$request->description:"",
                        'avatar' => $content_avatar,
                        'vote' => 0,
                        'like' => 0,
                        'type_user' => 1,
                        'active' => ($request->moderation == 'publish') ? 1 : 0,
                        'lat' => $request->lat,
                        'lng' => $request->lng,
                        'moderation' => $request->moderation,
                        'created_by' => $id_user,
                        'updated_by' => $id_user,
            ]);

            $lastIdContent = $content->id;

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

            if ($request->product) {
                foreach ($request->product as $group) {
                    $group_name =  $group['group_name']?$group['group_name']:'';
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
                                $product->type_user  = 1;
                                $product->created_by = Auth::guard('web')->user()->id;
                                $product->updated_by = Auth::guard('web')->user()->id;
                                $product->group_name = $group_name;
                                $product->group_machine_name = str_replace('-','_',str_slug(clear_str($group_name)));
                                $product->currency   = $value['currency']?$value['currency']:'VND';
                                $product->save();
                            }
                        }
                    }
                }
            }

            if ($request->category_item) {
                foreach ($request->category_item as $value) {
                    CategoryContent::create([
                        'id_content' => $lastIdContent,
                        'id_category_item' => $value,
                    ]);
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
                foreach ($request->image_space as $key => $file) {

                    $img_name = (time() + $key) . '_space_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

                    if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
                self::waterMark($file, $img_name, $path, $path_thumbnail);

                    $image_space = '/upload/img_content/' . $img_name;

                    ImageSpace::create([
                        'id_content' => $lastIdContent,
                        'name' => $image_space,
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
                foreach ($request->image_menu as $key => $file) {

                    $img_name = (time() + $key) . '_menu_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

                    if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
                self::waterMark($file, $img_name, $path, $path_thumbnail);

                    $image_menu = '/upload/img_content/' . $img_name;

                    ImageMenu::create([
                        'id_content' => $lastIdContent,
                        'name' => $image_menu,
                    ]);
                }
            }

            if ($request->link) {
                foreach ($request->link as $value) {
                    if (isset($value)) {
                        $infoVideo = $this->getInfoVideo($value);
                        if(count($infoVideo)>0){
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

            // if(!$request->description){
            //       $description = '';
            //       $content = Content::where('id','=',$lastIdContent)
            //                      ->with('_category_type')
            //                      ->with('_category_items')
            //                      ->with('_country')
            //                      ->with('_city')
            //                      ->with('_district')
            //                      ->with('_date_open')
            //                      ->first();
            //       $description .= $content->name.' ';
            //       $description .= 'tại '.$content->address.' '.$content->_district->name.' '.$content->_city->name.' '.$content->_country->name.', ';
            //       if($content->_category_items){
            //           $description .= 'thuộc thể loại ';
            //           foreach ($content->_category_items as $key_cat => $cat_item) {
            //               if($key_cat==0){
            //                   $description .= mb_strtolower($cat_item->name);
            //               }else{
            //                   $description .= ' - '.mb_strtolower($cat_item->name);
            //               }

            //           }
            //       }else{
            //           if($content->_category_type){
            //               $description .= 'thuộc thể loại '.mb_strtolower($content->_category_type->name);
            //           }
            //       }

            //       if($content->_date_open){
            //           $description .= ', mở cửa '.mb_strtolower(create_open_time($content->_date_open, \App::getLocale())).', ';
            //       }
            //       // if($content->price_from > 0 && $content->price_to > 0){
            //       //     $description .= 'giá từ '.$content->price_from.$content->currency.' ';
            //       //     $description .= 'đến '.$content->price_to.$content->currency;
            //       // }
            //       $description .='.';
            //       $content->description = $description;
            //       $content->save();
            //     }
                create_tag_search($lastIdContent);
            return redirect()->route('list_content')->with(['status' => trans('global.locations').' <a href="' . route('update_content', ['category_type' => $content->_category_type->machine_name, 'id' => $content->id]) . '">' . $content->name . '</a> '.trans('valid.added_successful').'</a>']);
        }
    }

    public function postUpdateShopContent(Request $request, $id) {
        $content_update = Content::find($id);
        $old_active = $content_update->active;
        $rules = [
            'name' => 'required',
            'alias' => 'required|unique:contents,alias',
            'id_category' => 'required',
            // 'category_item' => 'required',
            // 'open_from' => 'required',
            // 'open_to' => 'required',
            // 'price_from' => 'required',
            // 'price_to' => 'required',
            'country' => 'required',
            'city' => 'required',
            'district' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ];

        if (trim($content_update->name) == $request->name) {
            $rules['name'] = 'required';
        }

        if ($content_update->alias == $request->alias) {
            $rules['alias'] = 'required';
        }

        $messages = [
            'name.required' => trans('valid.name_required'),
            'name.unique' => trans('valid.name_unique'),
            'alias.required' => trans('valid.alias_required'),
            'alias.unique' => trans('valid.alias_unique'),
            'id_category.required' => trans('valid.id_category_required'),
            // 'category_item.required' => trans('valid.category_item_required'),
            // 'open_from.required' => 'Thời gian đóng mở cửa là trường bắt buộc',
            // 'open_to.required' => 'Thời gian đóng mở cửa là trường bắt buộc',
            'price_from.required' => trans('valid.price_from_required'),
            'price_to.required' => trans('valid.price_to_required'),
            'country.required' => trans('valid.country_required'),
            'city.required' => trans('valid.city_required'),
            'district.required' => trans('valid.location_required'),
            'address.required' => trans('valid.address_required'),
            'lat.required' => trans('valid.lat_required'),
            'lng.required' => trans('valid.lng_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
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
                if ($content_update->avatar != '/img_default/default_content.png') {
                    if (file_exists(public_path($content_update->avatar))) {
                        unlink(public_path($content_update->avatar));
                    }

                    if (file_exists(public_path(str_replace('img_content', 'img_content_thumbnail', $content_update->avatar)))) {
                        unlink(public_path(str_replace('img_content', 'img_content_thumbnail', $content_update->avatar)));
                    }
                }
                $content_update->avatar = '/upload/img_content/' . $img_name;
            }

            $content_update->name = $request->name;
            $content_update->alias = $request->alias;
            $content_update->id_category = $request->id_category;
            $content_update->country = $request->country;
            $content_update->city = $request->city;
            $content_update->district = $request->district;
            $content_update->address = $request->address?$request->address:"";
            $content_update->tag = $request->tag;
            $content_update->phone = isset($request->phone) ? $request->phone : '';
           // $content_update->open_from = date("H:i:s", strtotime($request->open_from));
           // $content_update->open_to = date("H:i:s", strtotime($request->open_to));
           // $content_update->price_from = $request->price_from;
           // $content_update->price_to = $request->price_to;
            $content_update->currency = $request->currency?$request->currency:'VND';
            $content_update->website = $request->website;
            $content_update->email = isset($request->email) ? $request->email : '';
            $content_update->description = $request->description?$request->description:"";
            $content_update->lat = $request->lat;
            $content_update->lng = $request->lng;
            if (Category::where([['id', '=', $request->id_category], ['active', '=', '1']])->first()) {
                $content_update->moderation = $request->moderation;
                $content_update->active = ($request->moderation == 'publish') ? 1 : 0;
            }
            $content_update->updated_by = Auth::guard('web')->user()->id;
            $content_update->type_user_update = 1;

            if ($content_update->save()) {

                if ($request->date_open) {
                    DateOpen::where('id_content', '=', $id)->delete();
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

                if ($request->product) {
                    foreach ($request->product as $group) {
                        $group_name =  $group['group_name']?$group['group_name']:'';
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
                                    $product->type_user  = 1;
                                    $product->updated_by = Auth::guard('web')->user()->id;
                                    $product->group_name = $group_name;
                                    $product->group_machine_name = str_replace('-','_',str_slug(clear_str($group_name)));
                                    $product->currency   = $value['currency']?$value['currency']:'VND';
                                    $product->save();
                                }
                            }
                        }
                    }
                }
                CategoryContent::where('id_content',$id)->delete();
                if ($request->category_item) {
                    CategoryContent::where('id_content', '=', $id)->delete();
                    foreach ($request->category_item as $value) {
                        CategoryContent::create([
                            'id_content' => $id,
                            'id_category_item' => $value,
                        ]);
                    }
                }

                if ($request->service) {
                    ServiceContent::where('id_content', '=', $id)->delete();
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

                    foreach ($request->image_space as $key => $file) {

                        $img_name = (time() + $key) . '_space_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

                        if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
                self::waterMark($file, $img_name, $path, $path_thumbnail);

                        $image_space = '/upload/img_content/' . $img_name;

                        ImageSpace::create([
                            'id_content' => $id,
                            'name' => $image_space,
                        ]);
                    }
                }

                if ($request->image_menu) {
                    $path = public_path() . '/upload/img_content/';
                    $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';

                    foreach ($request->image_menu as $key => $file) {

                        $img_name = (time() + $key) . '_menu_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

                        if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
                self::waterMark($file, $img_name, $path, $path_thumbnail);

                        $image_menu = '/upload/img_content/' . $img_name;

                        ImageMenu::create([
                            'id_content' => $id,
                            'name' => $image_menu,
                        ]);
                    }
                }
                LinkContent::where('id_content', '=', $id)->delete();
                if ($request->link) {
                    foreach ($request->link as $value) {
                        if (isset($value)) {
                            $infoVideo = $this->getInfoVideo($value);
                            if(count($infoVideo)>0){
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

                

                if($content_update->type_user==0){
                  if($content_update->active && $content_update->active != $old_active){
                    $notifi = new Notifi();
                    $notifi->createNotifiUserByTemplate('Admin'.DS.'content.active_content',$content_update->created_by,['content'=>$content_update->name]);
                  }else{
                    $notifi = new Notifi();
                    $notifi->createNotifiUserByTemplate('Admin'.DS.'content.inactive_content',$content_update->created_by,['content'=>$content_update->name]);
                  }
                }
                create_tag_search($id);
                if(!check_update_content($content_update->id)){
                    $notifi = new Notifi();
                    $link_content_update =LOCATION_URL.'/edit/location/'.$content_update->id;
                    $text_content_update = trans('Admin'.DS.'content.noti_update_content', [ 'content' => $content_update->name ]);
                    $notifi->createNotifiUserByTemplate($text_content_update,$content_update->created_by,['content' => $content_update->name, 'content_id' => $content_update->id],$link_content_update);
                }
                //~~
                if($request->url_previous){
                    return redirect($request->url_previous)->with(['status' => trans('global.locations').' <a href="' . route('update_content', ['category_type' => $content_update->_category_type->machine_name, 'id' => $content_update->id]) . '">' . $content_update->name . '</a> '.trans('valid.updated_successful').'</a>']);
                }else{
                    return redirect()->route('list_content')->with(['status' => trans('global.locations').' <a href="' . route('update_content', ['category_type' => $content_update->_category_type->machine_name, 'id' => $content_update->id]) . '">' . $content_update->name . '</a> '.trans('valid.updated_successful').'</a>']);
                }
            } else {
                $errors = new MessageBag(['error' => 'Không cập nhật được được content']);
                return redirect()->back()->withErrors($errors)->withInput();
            }
        }
    }

    public function postAddHotelContent(Request $request) {
        $rules = [
            'name' => 'required|unique:contents,name',
            'alias' => 'required|unique:contents,alias',
            'id_category' => 'required',
            // 'category_item' => 'required',
            'country' => 'required',
            'city' => 'required',
           // 'price_from' => 'required',
           // 'price_to' => 'required',
            'district' => 'required',
            'avatar' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ];

        if ($request->id_category == 11) {
            unset($rules['category_item']);
            unset($rules['price_from']);
            unset($rules['price_to']);
        }

        $messages = [
            'name.required' => trans('valid.name_required'),
            'name.unique' => trans('valid.name_unique'),
            'alias.required' => trans('valid.alias_required'),
            'alias.unique' => trans('valid.alias_unique'),
            'id_category.required' => trans('valid.id_category_required'),
            // 'category_item.required' => trans('valid.category_item_required'),
            'price_from.required' => trans('valid.price_from_required'),
            'price_to.required' => trans('valid.price_to_required'),
            'country.required' => trans('valid.country_required'),
            'city.required' => trans('valid.city_required'),
            'district.required' => trans('valid.location_required'),
            'address.required' => trans('valid.address_required'),
            'avatar.required' => trans('valid.avatar_required'),
            'lat.required' => trans('valid.lat_required'),
            'lng.required' => trans('valid.lng_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $id_user = Auth::guard('web')->user()->id;

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

            $content = Content::create([
                        'name' => $request->name,
                        'alias' => $request->alias,
                        'id_category' => $request->id_category,
                        'country' => $request->country,
                        'city' => $request->city,
                        'district' => $request->district,
                        'address' => $request->address?$request->address:"",
                        'tag' => $request->tag,
                        'phone' => isset($request->phone) ? $request->phone : '',
                        'open_from' => date("H:i:s", strtotime($request->open_from)),
                        'open_to' => date("H:i:s", strtotime($request->open_to)),
                       // 'price_from' => isset($request->price_from) ? $request->price_from : 0,
                       // 'price_to' => isset($request->price_to) ? $request->price_to : 0,
                        'currency' => isset($request->currency) ? $request->currency : '',
                        'website' => isset($request->website) ? $request->website : '',
                        'email' => isset($request->email) ? $request->email : '',
                        'description' => isset($request->description) ? $request->description : '',
                        'avatar' => $content_avatar,
                        'vote' => 0,
                        'like' => 0,
                        'type_user' => 1,
                        'active' => ($request->moderation == 'publish') ? 1 : 0,
                        'lat' => $request->lat,
                        'lng' => $request->lng,
                        'moderation' => $request->moderation,
                        'created_by' => $id_user,
                        'updated_by' => $id_user,
            ]);

            $lastIdContent = $content->id;

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

            if (isset($request->category_item)) {
                foreach ($request->category_item as $value) {
                    if ($value != null) {
                        CategoryContent::create([
                            'id_content' => $lastIdContent,
                            'id_category_item' => $value,
                        ]);
                    }
                }
            }

            if (isset($request->service)) {
                foreach ($request->service as $value) {
                    ServiceContent::create([
                        'id_content' => $lastIdContent,
                        'id_service_item' => $value,
                    ]);
                }
            }

            if (isset($request->image_space)) {
                $path = public_path() . '/upload/img_content/';
                $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
                if (!\File::exists($path)) {
                    \File::makeDirectory($path, $mode = 0777, true, true);
                }
                if (!\File::exists($path_thumbnail)) {
                    \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
                }
                foreach ($request->image_space as $key => $file) {

                    $img_name = (time() + $key) . '_space_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

                    if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
                self::waterMark($file, $img_name, $path, $path_thumbnail);

                    $image_space = '/upload/img_content/' . $img_name;

                    ImageSpace::create([
                        'id_content' => $lastIdContent,
                        'name' => $image_space,
                    ]);
                }
            }

            if (isset($request->link)) {
                foreach ($request->link as $value) {
                    if (isset($value)) {
                        $infoVideo = $this->getInfoVideo($value);
                        if(count($infoVideo)>0){
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

            // if(!$request->description){
            //       $description = '';
            //       $content = Content::where('id','=',$lastIdContent)
            //                      ->with('_category_type')
            //                      ->with('_category_items')
            //                      ->with('_country')
            //                      ->with('_city')
            //                      ->with('_district')
            //                      ->with('_date_open')
            //                      ->first();
            //       $description .= $content->name.' ';
            //       $description .= 'tại '.$content->address.' '.$content->_district->name.' '.$content->_city->name.' '.$content->_country->name.', ';
            //       if($content->_category_items){
            //           $description .= 'thuộc thể loại ';
            //           foreach ($content->_category_items as $key_cat => $cat_item) {
            //               if($key_cat==0){
            //                   $description .= mb_strtolower($cat_item->name);
            //               }else{
            //                   $description .= ' - '.mb_strtolower($cat_item->name);
            //               }

            //           }
            //       }else{
            //           if($content->_category_type){
            //               $description .= 'thuộc thể loại '.mb_strtolower($content->_category_type->name);
            //           }
            //       }

            //       if($content->_date_open){
            //           $description .= ', mở cửa '.mb_strtolower(create_open_time($content->_date_open, \App::getLocale())).', ';
            //       }
            //       // if($content->price_from > 0 && $content->price_to > 0){
            //       //     $description .= 'giá từ '.$content->price_from.$content->currency.' ';
            //       //     $description .= 'đến '.$content->price_to.$content->currency;
            //       // }
            //       $description .='.';
            //       $content->description = $description;
            //       $content->save();
            //     }
                create_tag_search($lastIdContent);
            return redirect()->route('list_content')->with(['status' => trans('global.locations').' <a href="' . route('update_content', ['category_type' => $content->_category_type->machine_name, 'id' => $content->id]) . '">' . $content->name . '</a> '.trans('valid.added_successful').'</a>']);
        }
    }

    public function postUpdateHotelContent(Request $request, $id) {
        $content_update = Content::find($id);
        $old_active = $content_update->active;
        $rules = [
            'name' => 'required',
            'alias' => 'required|unique:contents,alias',
            'id_category' => 'required',
            // 'category_item' => 'required',
            // 'price_from' => 'required',
            // 'price_to' => 'required',
            'country' => 'required',
            'city' => 'required',
            'district' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ];

        if (trim($content_update->name) == $request->name) {
            $rules['name'] = 'required';
        }

        if ($content_update->alias == $request->alias) {
            $rules['alias'] = 'required';
        }

        if ($request->id_category == 11) {
            unset($rules['category_item']);
            unset($rules['price_from']);
            unset($rules['price_to']);
        }

        $messages = [
            'name.required' => trans('valid.name_required'),
            'name.unique' => trans('valid.name_unique'),
            'alias.required' => trans('valid.alias_required'),
            'alias.unique' => trans('valid.alias_unique'),
            'id_category.required' => trans('valid.id_category_required'),
            // 'category_item.required' => trans('valid.category_item_required'),
            'price_from.required' => trans('valid.price_from_required'),
            'country.required' => trans('valid.country_required'),
            'city.required' => trans('valid.city_required'),
            'district.required' => trans('valid.location_required'),
            'address.required' => trans('valid.address_required'),
            'lat.required' => trans('valid.lat_required'),
            'lng.required' => trans('valid.lng_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
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
                if ($content_update->avatar != '/img_default/default_content.png') {
                    if (file_exists(public_path($content_update->avatar))) {
                        unlink(public_path($content_update->avatar));
                    }

                    if (file_exists(public_path(str_replace('img_content', 'img_content_thumbnail', $content_update->avatar)))) {
                        unlink(public_path(str_replace('img_content', 'img_content_thumbnail', $content_update->avatar)));
                    }
                }
                $content_update->avatar = '/upload/img_content/' . $img_name;
            }

            $content_update->name = $request->name;
            $content_update->alias = $request->alias;
            $content_update->country = $request->country;
            $content_update->city = $request->city;
            $content_update->district = $request->district;
            $content_update->address = $request->address?$request->address:"";
            $content_update->tag = isset($request->tag) ? $request->tag : '';
            $content_update->phone = isset($request->phone) ? $request->phone : '';
           // $content_update->price_from = isset($request->price_from) ? $request->price_from : 0;
           // $content_update->price_to = isset($request->price_to) ? $request->price_to : 0;
            $content_update->website = isset($request->website) ? $request->website : '';
            $content_update->email = isset($request->email) ? $request->email : '';
            $content_update->description = isset($request->description) ? $request->description : "";
            $content_update->lat = $request->lat;
            $content_update->lng = $request->lng;
            if (Category::where([['id', '=', $request->id_category], ['active', '=', '1']])->first()) {
                $content_update->moderation = $request->moderation;
                $content_update->active = ($request->moderation == 'publish') ? 1 : 0;
            }
            $content_update->updated_by = Auth::guard('web')->user()->id;
            $content_update->type_user_update = 1;

            if ($content_update->save()) {

                if ($request->date_open) {
                    DateOpen::where('id_content', '=', $id)->delete();
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

                if (isset($request->category_item)) {
                    CategoryContent::where('id_content', '=', $id)->delete();
                    foreach ($request->category_item as $value) {
                        CategoryContent::create([
                            'id_content' => $id,
                            'id_category_item' => $value,
                        ]);
                    }
                }

                if (isset($request->service)) {
                    ServiceContent::where('id_content', '=', $id)->delete();
                    foreach ($request->service as $value) {
                        ServiceContent::create([
                            'id_content' => $id,
                            'id_service_item' => $value,
                        ]);
                    }
                }

                if (isset($request->image_space)) {
                    $path = public_path() . '/upload/img_content/';
                    $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';

                    foreach ($request->image_space as $key => $file) {

                        $img_name = (time() + $key) . '_space_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

                        if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
                self::waterMark($file, $img_name, $path, $path_thumbnail);

                        $image_space = '/upload/img_content/' . $img_name;

                        ImageSpace::create([
                            'id_content' => $id,
                            'name' => $image_space,
                        ]);
                    }
                }
                LinkContent::where('id_content', '=', $id)->delete();
                if (isset($request->link)) {
                    foreach ($request->link as $value) {
                        if (isset($value)) {
                            $infoVideo = $this->getInfoVideo($value);
                            if(count($infoVideo)>0){
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

                CategoryContent::where('id_content',$id)->delete();
                if ($request->category_item) {
                    CategoryContent::where('id_content', '=', $id)->delete();
                    foreach ($request->category_item as $value) {
                        CategoryContent::create([
                            'id_content' => $id,
                            'id_category_item' => $value,
                        ]);
                    }
                }

                

                if($content_update->type_user==0){
                  if($content_update->active && $content_update->active != $old_active){
                    $notifi = new Notifi();
                    $notifi->createNotifiUserByTemplate('Admin'.DS.'content.active_content',$content_update->created_by,['content'=>$content_update->name]);
                  }else{
                    $notifi = new Notifi();
                    $notifi->createNotifiUserByTemplate('Admin'.DS.'content.inactive_content',$content_update->created_by,['content'=>$content_update->name]);
                  }
                }
                create_tag_search($id);
                if(!check_update_content($content_update->id)){
                    $notifi = new Notifi();
                    $link_content_update =LOCATION_URL.'/edit/location/'.$content_update->id;
                    $text_content_update = trans('Admin'.DS.'content.noti_update_content', [ 'content' => $content_update->name ]);
                    $notifi->createNotifiUserByTemplate($text_content_update,$content_update->created_by,['content' => $content_update->name, 'content_id' => $content_update->id],$link_content_update);
                }
                //~~
                if($request->url_previous){
                    return redirect($request->url_previous)->with(['status' => trans('global.locations').' <a href="' . route('update_content', ['category_type' => $content_update->_category_type->machine_name, 'id' => $content_update->id]) . '">' . $content_update->name . '</a> '.trans('valid.updated_successful').'</a>']);
                }else{
                    return redirect()->route('list_content')->with(['status' => trans('global.locations').' <a href="' . route('update_content', ['category_type' => $content_update->_category_type->machine_name, 'id' => $content_update->id]) . '">' . $content_update->name . '</a> '.trans('valid.updated_successful').'</a>']);
                }
            } else {
                $errors = new MessageBag(['error' => 'Không cập nhật được được content']);
                return redirect()->back()->withErrors($errors)->withInput();
            }
        }
    }

    public function getAjaxLocation(Request $request) {
        $value = $request->value;
        $type = $request->type;

        switch ($type) {
            case 'city':
                $city = City::where('id_country', '=', $value)->pluck('name', 'id');
                echo '<option value="">-- '.trans('global.city').' --</option>';
                foreach ($city as $key => $value) {
                    echo '<option value="' . $key . '">' . $value . '</option>';
                }
                break;
            case 'district':
                if(is_array($value)){
                    $district = District::whereIn('id_city', $value)->get();
                    $arr_city = District::whereIn('id_city', $value)->pluck('id_city');
                    $cities = City::whereIn('id',$arr_city)->get();
                    echo '<option value="">-- '.trans('global.district').' --</option>';
                    foreach ($cities as $key1 => $city) {
                        echo '<optgroup label="'.$city->name.'">';
                        foreach ($district as $key2 => $value) {
                            if($value->id_city == $city->id){
                                echo '<option value="' . $value->id . '">' . $value->name . '</option>';
                            }
                        }
                        echo '</optgroup>';
                    }
                }else{
                    $district = District::where('id_city', '=', $value)->get();
                    echo '<option value="">-- '.trans('global.district').' --</option>';
                    foreach ($district as $key => $value) {
                        echo '<option value="' . $value->id . '">' . $value->name . '</option>';
                    }
                }
                break;
            default:
                break;
        }
    }

    public function getAjaxCategoryItem(Request $request) {
        $id = $request->value;
        $category_item = CategoryItem::where([['category_id', '=', $id], ['active', '=', '1']])->pluck('name', 'id');

        if (count($category_item) > 0) {
            echo '<option value="">-- '.trans('global.category_item').' --</option>';
            foreach ($category_item as $key => $value) {
                echo '<option value="' . $key . '">' . $value . '</option>';
            }
        } else {
            echo 'err';
        }
    }

    public function waterMark($file, $img_name, $path, $path_thumbnail, $type = null) {
        if ($type == 'import') {
            $img = Image::make($file)->orientate();
            $width = $img->getSize()->getWidth();
            $height = $img->getSize()->getHeight();

            $max_height = 720;
            $max_width = 1280;

            if ($width > $max_width || $height > $max_height) {
                $img = Image::make($file)->orientate()->resize(1280, 720, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            $max = $width > $height ? $width : $height;

            $wt = Image::make(public_path() . DS . 'img_default' . DS . 'kingmap.png');
            // An update (check bỏ ty lệ logo)
            // $w_wt = ($max / 950 * 210);
            // $h_wt = ($max / 950 * 50);
            // $wt->resize($w_wt, $h_wt);
            $img->insert($wt, 'center');
            $img->insert($wt, 'center');

            $img->save($path . $img_name);

            $img_thumbnail = Image::make($file)->orientate()->fit(270, 202, function ($constraint) {
                        $constraint->upsize();
                    })->insert(Image::make('img_default/kingmap.png')->resize(95, 14), 'center');
            $img_thumbnail->save($path_thumbnail . $img_name);
        } else {
            $img = Image::make($file)->orientate();
            $width = $img->getSize()->getWidth();
            $height = $img->getSize()->getHeight();

            $max_height = 720;
            $max_width = 1280;

            if ($width > $max_width || $height > $max_height) {
                $img = Image::make($file)->orientate()->resize(1280, 720, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            $max = $width > $height ? $width : $height;

            $wt = Image::make(public_path() . DS . 'img_default' . DS . 'kingmap.png');
            $img->insert($wt, 'center');
            $img->insert($wt, 'center');

            $img->save($path . $img_name);

            $img_thumbnail = Image::make($file)->orientate()->fit(270, 202, function ($constraint) {
                        $constraint->upsize();
                    })
                    ->insert(Image::make('img_default/kingmap.png')->resize(95, 14), 'center')
                    ->insert(Image::make('img_default/kingmap.png')->resize(95, 14), 'center');
            $img_thumbnail->save($path_thumbnail . $img_name);
        }
    }

    public function waterMarkAvatar($file, $img_name, $path, $path_thumbnail, $type = null) {
        if ($type == 'import') {
            $img = Image::make($file)->orientate()->fit(660,347, function ($constraint) {
                $constraint->upsize();
            });

            $wt = Image::make(public_path() . DS . 'img_default' . DS . 'kingmap.png');
            $img->insert($wt, 'center');
            $img->insert($wt, 'center');

            $img->save($path . $img_name);

            $img_thumbnail = Image::make($file)->orientate()->fit(270,202, function ($constraint) {
                $constraint->upsize();
            })->insert(Image::make('img_default/kingmap.png')->resize(95, 14), 'center');
            $img_thumbnail->save($path_thumbnail . $img_name);
        } else {
            $img = Image::make($file)->orientate()->fit(660,347, function ($constraint) {
                $constraint->upsize();
            });

            $wt = Image::make(public_path() . DS . 'img_default' . DS . 'kingmap.png');
            $img->insert($wt, 'center');
            $img->insert($wt, 'center');

            $img->save($path . $img_name);

            $img_thumbnail = Image::make($file)->orientate()->fit(270,202, function ($constraint) {
                $constraint->upsize();
            })->insert(Image::make('img_default/kingmap.png')->resize(95, 14), 'center');
            $img_thumbnail->save($path_thumbnail . $img_name);
        }
    }

    public function changeStatus(Request $request) {
        $change_status = $request->change_status;
        $type_status = $request->type_status;

        if (isset($type_status)) {
            if (count($change_status) > 0) {
                if ($type_status == 2) {
                    foreach ($change_status as $id) {
                        $content = Content::find($id);

                        $notifi = new Notifi();
                        $text_content = trans('Admin'.DS.'content.delete_content', [ 'user' => $content->name ]);
                        $notifi->createNotifiUser($text_content,$content->created_by);

                        if ($content->delete()) {
                            CategoryContent::where('id_content', '=', $id)->delete();
                            ServiceContent::where('id_content', '=', $id)->delete();
                            GroupContent::where('id_content', '=', $id)->delete();
                            ImageSpace::where('id_content', '=', $id)->delete();
                            ImageMenu::where('id_content', '=', $id)->delete();
                            DateOpen::where('id_content', '=', $id)->delete();
                            LinkContent::where('id_content', '=', $id)->delete();
                            Product::where('content_id', '=', $id)->delete();
                        };
                    }
                } else {
                    foreach ($change_status as $id) {
                        $content_update = Content::find($id);
                        $content_update->active = $type_status;
                        $content_update->moderation = ($type_status == 1) ? 'publish' : 'un_publish';
                        $content_update->updated_by = Auth::guard('web')->user()->id;
                        $content_update->save();

                        $notifi = new Notifi();

                        if($type_status == 1){
                            $link_content =LOCATION_URL.'/'.$content_update->alias;
                            $text_content = trans('Admin'.DS.'content.active_content', [ 'content' => $content_update->name ]);
                            $notifi->createNotifiUser($text_content,$content_update->created_by,$link_content);
                        }else{
                            $text_content = trans('Admin'.DS.'content.inactive_content', [ 'content' => $content_update->name ]);
                            $notifi->createNotifiUser($text_content,$content_update->created_by);
                        }

                        if(!check_update_content($content_update->id)){
                            $notifi = new Notifi();
                            $link_content_update =LOCATION_URL.'/edit/location/'.$content_update->id;
                            $text_content_update = trans('Admin'.DS.'content.noti_update_content', [ 'content' => $content_update->name ]);
                            $notifi->createNotifiUserByTemplate($text_content_update,$content_update->created_by,['content' => $content_update->name, 'content_id' => $content_update->id],$link_content_update);
                        }
                    }
                }
            }
        }
        return redirect(URL::to($request->current_url))->with(['status' => 'Đã cập nhật dữ liệu thành công.']);
    }

    public function postImportFoodContent(Request $request, $category_type = '') {
        $category = Category::where('machine_name', '=', $category_type)->first();
        if ($category) {
            set_time_limit(0);
            ini_set('memory_limit', '2048M');
            if ($request->file('fileExcel')) {
                $path = public_path() . '/upload/importExcel/';
                $file = $request->file('fileExcel');
                if (!\File::exists($path)) {
                    \File::makeDirectory($path, $mode = 0777, true, true);
                }
                $name = time() . '.' . $file->getClientOriginalExtension();
                $file->move($path, $name);
                $file_path = $path . $name;
                $id_category = $category->id;

                $fileImage = $request->file('fileImage');
                $path = public_path() . '/upload/img_content/';
                $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
                if (!\File::exists($path)) {
                    \File::makeDirectory($path, $mode = 0777, true, true);
                }
                if (!\File::exists($path_thumbnail)) {
                    \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
                }

                $img_name = time() . '_avatar_' . $this->vn_to_str($fileImage->getClientOriginalName());

                self::waterMarkAvatar($fileImage, $img_name, $path, $path_thumbnail);

                $content_avatar = '/upload/img_content/' . $img_name;

                if ($file_path) {
                    $rows = \Excel::selectSheetsByIndex(0)->load($file_path, function ($reader) {

                            })->all();
                    $header = $rows[0]->keys()->toArray();
                    $arr_check = array(
                        "stt",
                        "tendiadiem",
                        "loai",
                        "dia_chi",
                        "quan",
                        "thanh_pho",
                        "dien_thoai",
                        "email",
                        "gio_mo_cua",
                        "mo_ta",
                        "dich_vu",
                        "gia",
                        "toa_do_gmap",
                        "tu_khoa",
                        "code"
                    );
                    $check = true;
                    foreach ($arr_check as $key => $value) {
                        $check = $check && in_array($value, $header);
                    }

                    if ($check) {
                        $job = new ImportExcel($file_path, $id_category, $content_avatar);
                        dispatch($job);
                        $total = 0;
                        $total = $job->getTotal();
                        $error = $job->getError();

                        \File::delete($file_path);
                        session()->forget('totalExcel');
                        session()->forget('errorImport');
                        if (!$total) {
                            return redirect()->back()->with(['status' => 'No data is imported, please upload another file ' . "<br/>" . $error]);
                        } else {
                            return redirect()->route('list_content')->with(['status' => 'Import ' . $total . ' content '.trans('valid.updated_successful') . "<br/>" . $error]);
                        }
                    } else {
                        return redirect()->back()->with(['status' => 'File excel is not formatted correctly ']);
                    }
                }
            } else {
                return redirect()->back()->with(['status' => 'Error upload file ']);
            }
        } else {
            return redirect()->back()->with(['status' => 'Category not found']);
        }
    }

    public function getDeleteContent($id) {
        $content = Content::find($id);
        $content_name = $content->name;
        if ($content->delete()) {
            CategoryContent::where('id_content', '=', $id)->delete();
            ServiceContent::where('id_content', '=', $id)->delete();
            GroupContent::where('id_content', '=', $id)->delete();
            ImageSpace::where('id_content', '=', $id)->delete();
            ImageMenu::where('id_content', '=', $id)->delete();
            DateOpen::where('id_content', '=', $id)->delete();
            LinkContent::where('id_content', '=', $id)->delete();
            Product::where('content_id', '=', $id)->delete();
        };
        
        return redirect()->back()->with(['status' => trans('global.locations').': '. $content_name . ' '.trans('valid.deleted_successful')]);
    }

    public function getMigrate() {
        $migrate = \DB::table('migrations')->get();
        if ($migrate) {
            dd($migrate->toArray());
        }
    }

    public function getNotifyContent($id) {
        $check_notify = NotifiContent::where('id_content', '=', $id)->first();
        if (isset($check_notify)) {
            return view('Admin.content.notify', ['notify' => $check_notify, 'id' => $id]);
        } else {
            return view('Admin.content.notify', ['id' => $id]);
        }
    }

    public function postNotifyContent(Request $request, $id) {

        $rules = [
            'title' => 'required',
            'description' => 'required',
        ];
        $messages = [
            'title.required' => trans('valid.title_required'),
            'description.required' => trans('valid.description_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

            $check_notify = NotifiContent::where('id_content', '=', $id)->first();
            if ($check_notify) {
                $check_notify->title = $request->title;
                $check_notify->description = $request->description;
                $check_notify->active = isset($request->active) ? 1 : 0;
                $check_notify->start = $request->start_date;
                $check_notify->end = $request->end_date;
                $check_notify->save();
            } else {
                NotifiContent::create([
                    'id_content' => $id,
                    'title' => $request->title,
                    'description' => $request->description?$request->description:"",
                    'active' => isset($request->active) ? 1 : 0,
                    'start' => $request->start_date,
                    'end' => $request->end_date,
                ]);
            }
            return redirect()->route('list_content')->with(['status' => 'Nội dung thông báo '.trans('valid.updated_successful').'']);
        }
    }

    public function postDeleteImg(Request $request) {
        $id = $request->id;
        $type = $request->type;
        
        if ($type == 'image_spaces') {
            $image = ImageSpace::find($id);
            if (file_exists(public_path($image['name']))) {
                unlink(public_path($image['name']));
            }
            if (file_exists(public_path(str_replace('img_content', 'img_content_thumbnail', $image['name'])))) {
                unlink(public_path(str_replace('img_content', 'img_content_thumbnail', $image['name'])));
            }
            $content = Content::find($image->id_content);
            
            if($image->delete()){
                $content->type_user_update = 1;
                $content->updated_by = Auth::guard('web')->user()->id;
                echo 'sussess';
            }
        } else {
            $image = ImageMenu::find($id);
            if (file_exists(public_path($image['name']))) {
                unlink(public_path($image['name']));
            }
            if (file_exists(public_path(str_replace('img_content', 'img_content_thumbnail', $image['name'])))) {
                unlink(public_path(str_replace('img_content', 'img_content_thumbnail', $image['name'])));
            }
            $content = Content::find($image->id_content);
            
            if($image->delete()){
                $content->type_user_update = 1;
                $content->updated_by = Auth::guard('web')->user()->id;
                echo 'sussess';
            }
        }
    }

    public function postDeleteProduct(Request $request) {
        $id = $request->id;
        $product = Product::find($id);
        $content = Content::find($product->content_id);
        if (file_exists(public_path($product['image']))) {
            unlink(public_path($product['image']));
        }
        if (file_exists(public_path(str_replace('product', 'product_thumbnail', $product['image'])))) {
            unlink(public_path(str_replace('product', 'product_thumbnail', $product['image'])));
        }
        if($product->delete()){
            $content->type_user_update = 1;
            $content->updated_by = Auth::guard('web')->user()->id;
            echo 'sussess';
        }
    }

    public function postDeleteGroupProduct(Request $request) {
        if($request->id && count($request->id)){
            foreach ($request->id as $key => $id) {
                $product = Product::find($id);
                $content = Content::find($product->content_id);
                if ($product['image']!='' &&  file_exists(public_path($product['image']))) {
                    unlink(public_path($product['image']));
                }
                if ($product['image']!='' &&  file_exists(public_path(str_replace('product', 'product_thumbnail', $product['image'])))) {
                    unlink(public_path(str_replace('product', 'product_thumbnail', $product['image'])));
                }
                if($product->delete()){
                    $content->type_user_update = 1;
                    $content->updated_by = Auth::guard('web')->user()->id;
                }
            }   
            echo 'sussess'; 
        }
    }

    public function getNoteContent($id) {
        $content = Content::find($id);
        $id_user_create = $content->created_by;
        if (Auth::guard('web')->user()->hasRole('content') == true) {
            if ($id_user_create = !Auth::guard('web')->user()->id) {
                return redirect()->back();
            }
        }

        $note_content = NoteContent::where('id_content', '=', $id)->with('_user_create')->orderBy('id', 'desc')->get();
        return view('Admin.content.list_note_content', [
            'note_content' => $note_content,
            'content' => $content
        ]);
    }

    public function postNoteContent(Request $request, $id) {
        NoteContent::create([
            'id_content' => $id,
            'id_user' => Auth::guard('web')->user()->id,
            'note' => $request->note,
        ]);

        return redirect()->route('note_content', ['id' => $id]);
    }

    public function vn_to_str($str) {
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

    function clean($str) {
        $str = str_replace("&nbsp;", " ", $str);
        $str = preg_replace('/\s+/', ' ', $str);
        $converted = strtr($str, array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
        $str = trim($converted, chr(0xC2) . chr(0xA0));
        return $str;
    }

    public function getDataVinalo($links, $moderation, $date) {
        foreach ($links as $link) {
            try {
                $data = [];
                $d = self::curlGetLink($link['link']);

                $data['name'] = $d->getElementsByTagName('h1')->item(0)->nodeValue;
                $data['description'] = $d->getElementsByTagName('meta')->item(2)->attributes->item(1)->nodeValue;

                $a = new \DOMXPath($d);
                $data['avatar'] = $a->query('//*[@class="topuscr1"]')->item(0)->childNodes->item(0)->childNodes->item(0)->attributes->item(2)->nodeValue;
                $data['address'] = trim($a->query('//*[@class="topuscr4"]')->item(0)->childNodes->item(1)->nodeValue);
                $data['quan'] = trim($a->query('//*[@class="topuscr4"]')->item(0)->childNodes->item(3)->nodeValue);
                $data['cty'] = trim($a->query('//*[@class="topuscr4"]')->item(0)->childNodes->item(5)->nodeValue);

                $data['geo'] = $a->query('//*[@class="topuscr4"]')->item(0)->childNodes->item(1)->attributes->item(1)->nodeValue;
                $data['phone'] = trim($a->query('//*[@class="topuscr5"]')->item(0)->nodeValue);

                if (strpos($a->query('//*[@class="rdct_0"]')->item(0)->nodeValue, 'THEO DÕI')) {
                    $data['cate_type'] = $a->query('//*[@class="rdct_0"]')->item(2)->childNodes->item(1)->childNodes->item(1)->nodeValue;
                } else {
                    $data['cate_type'] = $a->query('//*[@class="rdct_0"]')->item(1)->childNodes->item(1)->childNodes->item(1)->nodeValue;
                    $ks_gmc = $a->query('//*[@class="rdct_0"]')->item(0);
                }

                if ($data['cate_type'] === "Khách sạn") {
                    if (strpos($a->query('//*[@class="rdct_0"]')->item(0)->nodeValue, 'THEO DÕI')) {

                        $data['gmc'] = $a->query('//*[@class="rdct_0"]')->item(1)->childNodes->item(5)->childNodes->item(0)->childNodes->item(2)->nodeValue;
                        $cate_items = $a->query('//*[@class="rdct_0"]')->item(2)->childNodes->item(3)->childNodes;
                        foreach ($cate_items as $cate_item) {
                            if (trim($cate_item->nodeValue) != '' && $cate_item->attributes != '') {
                                if (!empty($cate_item->attributes->item(1))) {
                                    $active = $cate_item->attributes->item(1)->value;
                                } elseif (empty($cate_item->attributes->item(1))) {
                                    $active = $cate_item->attributes->item(0)->value;
                                }
                                if ($active == 'ptldct_1') {
                                    $data['cate_item'][] = $cate_item->childNodes->item(0)->nodeValue;
                                }
                            }
                        }
                        if ($d->getElementsByTagName('table')->item(0)->childNodes->length >= 2) {
                            $price = $d->getElementsByTagName('table')->item(0)->childNodes->item(1)->nodeValue;
                            if (strpos($price, 'Khoảng giá')) {
                                $price = str_replace("Khoảng giá", "", $price);
                                $data['price'] = self::clean($price);
                            }
                        }

                        $albumss_check = $a->query('//*[@class="rdct_0"]')->item(3)->childNodes->item(1)->childNodes->item(3)->nodeValue;
                        if (strpos($albumss_check, 'Xem tất cả')) {
                            $albumss_ks = $a->query('//*[@class="rdct_0"]')->item(3)->childNodes->item(1)->childNodes->item(3);
                            $albumss_ks_link = $albumss_ks->attributes->item(1)->nodeValue;
                        } elseif (strpos($a->query('//*[@class="rdct_0"]')->item(3)->childNodes->item(1)->childNodes->item(4)->nodeValue, 'Xem tất cả')) {
                            $albumss_ks = $a->query('//*[@class="rdct_0"]')->item(3)->childNodes->item(1)->childNodes->item(4);
                            $albumss_ks_link = $albumss_ks->childNodes->item(0)->attributes->item(1)->nodeValue;
                        }
                        if ($albumss_ks) {
                            $arr = explode('-', $albumss_ks_link);
                            $code_img = end($arr);

                            $dl = self::curlGetLink($albumss_ks_link);
                            echo "<script>$(document).ready(function(){ $.post('https://vinalo.com/loadh/albumdd', { img : 10244, id : '" . $code_img . "' }, function(data) { $('#loadcent').html(data); }); })</script>";
                            $al_mige = self::curlPost($code_img);

                            $al = new \DOMXPath($al_mige);
                            $album_ms = $al->query('//*[@class="king_test"]')->item(0)->childNodes;

                            foreach ($album_ms as $album_m) {
                                if (trim($album_m->nodeValue) != '' && trim($album_m->nodeName) != 'hhh') {
                                    $data['img'][] = $album_m->childNodes->item(1)->attributes->item(0)->nodeValue;
                                }
                            }
                        } else {
                            $t = $a->query('//*[@class="dhinhsli"]')->item(0)->childNodes;
                            foreach ($t as $k => $child) {
                                if ($k != 0 && trim($child->nodeValue) != '') {
                                    $data['img'][] = $child->childNodes->item(1)->attributes->item(1)->value;
                                }
                            }
                        }

                        if ($a->query('//*[@id="tien_nghi_room"]')->length != 0) {
                            $tien_nghi_room = $a->query('//*[@id="tien_nghi_room"]')->item(0)->childNodes->item(3)->childNodes->item(1)->childNodes->item(1)->childNodes->item(0)->childNodes->item(1);
                            foreach ($tien_nghi_room->childNodes as $service) {
                                if ($service->nodeValue != 'Tiện nghi' && $service->nodeValue != '' && $service->nodeName == 'p' && $service->attributes->item(0)->value === 'bleftdd_1') {
                                    $data['service'][] = $service->nodeValue;
                                }
                            }
                        } else {
                            if ($d->getElementsByTagName('table')->item(1)->childNodes->length == 2) {
                                $services_ty = $d->getElementsByTagName('table')->item(1)->childNodes->item(1)->nodeValue;
                            } elseif ($d->getElementsByTagName('table')->item(1)->childNodes->length == 1) {
                                $services_ty = $d->getElementsByTagName('table')->item(1)->childNodes->item(0)->nodeValue;
                            }

                            if (strpos($services_ty, 'Tiện nghi')) {
                                $services = $d->getElementsByTagName('table')->item(1)->childNodes->item(0)->childNodes->item(0)->childNodes->item(1)->childNodes;
                                foreach ($services as $k => $ser) {
                                    if ($k != 0 && trim($ser->nodeValue) != '' && $ser->attributes->item(0)->value == 'bleftdd_1') {
                                        $data['service'][] = $ser->nodeValue;
                                    }
                                }
                            } else {
                                $services = $d->getElementsByTagName('table')->item(1)->childNodes->item(2)->childNodes->item(0)->childNodes->item(1)->childNodes;
                                foreach ($services as $k => $ser) {
                                    if ($k != 0 && trim($ser->nodeValue) != '' && $ser->attributes->item(0)->value == 'bleftdd_1') {
                                        $data['service'][] = $ser->nodeValue;
                                    }
                                }
                            }
                        }
                    } else {
                        $data['gmc'] = $a->query('//*[@class="rdct_0"]')->item(0)->childNodes->item(7)->childNodes->item(0)->childNodes->item(2)->nodeValue;
                        $cate_items = $a->query('//*[@class="rdct_0"]')->item(1)->childNodes->item(3)->childNodes;
                        foreach ($cate_items as $cate_item) {
                            if (trim($cate_item->nodeValue) != '' && $cate_item->attributes->item(1)->value == 'ptldct_1') {
                                $data['cate_item'][] = $cate_item->childNodes->item(0)->nodeValue;
                            }
                        }
                        if ($d->getElementsByTagName('table')->item(0)->childNodes->length >= 2) {
                            $price = $d->getElementsByTagName('table')->item(0)->childNodes->item(1)->nodeValue;
                            if (strpos($price, 'Khoảng giá')) {
                                $price = str_replace("Khoảng giá", "", $price);
                                $data['price'] = self::clean($price);
                            }
                        }
                        $albumss_ks = $a->query('//*[@class="rdct_0"]')->item(2)->childNodes->item(1)->childNodes->item(3);
                        if ($albumss_ks) {
                            $albumss_ks_link = $albumss_ks->attributes->item(1)->nodeValue;
                            $arr = explode('-', $albumss_ks_link);
                            $code_img = end($arr);

                            $dl = self::curlGetLink($albumss_ks_link);
                            echo "<script>$(document).ready(function(){ $.post('https://vinalo.com/loadh/albumdd', { img : 10244, id : '" . $code_img . "' }, function(data) { $('#loadcent').html(data); }); })</script>";
                            $al_mige = curlPost($code_img);

                            $al = new \DOMXPath($al_mige);
                            $album_ms = $al->query('//*[@class="king_test"]')->item(0)->childNodes;

                            foreach ($album_ms as $album_m) {
                                if (trim($album_m->nodeValue) != '' && trim($album_m->nodeName) != 'hhh') {
                                    $data['img'][] = $album_m->childNodes->item(1)->attributes->item(0)->nodeValue;
                                }
                            }
                        } else {
                            $t = $a->query('//*[@class="dhinhsli"]')->item(0)->childNodes;
                            foreach ($t as $k => $child) {
                                if ($k != 0 && trim($child->nodeValue) != '') {
                                    $data['img'][] = $child->childNodes->item(1)->attributes->item(1)->value;
                                }
                            }
                        }
                        if ($a->query('//*[@id="tien_nghi_room"]')->length != 0) {
                            $tien_nghi_room = $a->query('//*[@id="tien_nghi_room"]')->item(0)->childNodes->item(3)->childNodes->item(1)->childNodes->item(1)->childNodes->item(0)->childNodes->item(1);
                            foreach ($tien_nghi_room->childNodes as $service) {
                                if ($service->nodeValue != 'Tiện nghi' && $service->nodeValue != '' && $service->nodeName == 'p' && $service->attributes->item(0)->value === 'bleftdd_1') {
                                    $data['service'][] = $service->nodeValue;
                                }
                            }
                        } else {
                            if ($d->getElementsByTagName('table')->item(1)->childNodes->length == 2) {
                                $services_ty = $d->getElementsByTagName('table')->item(1)->childNodes->item(1)->nodeValue;
                            } elseif ($d->getElementsByTagName('table')->item(1)->childNodes->length == 1) {
                                $services_ty = $d->getElementsByTagName('table')->item(1)->childNodes->item(0)->nodeValue;
                            }

                            if (strpos($services_ty, 'Tiện nghi')) {
                                $services = $d->getElementsByTagName('table')->item(1)->childNodes->item(0)->childNodes->item(0)->childNodes->item(1)->childNodes;
                                foreach ($services as $k => $ser) {
                                    if ($k != 0 && trim($ser->nodeValue) != '' && $ser->attributes->item(0)->value == 'bleftdd_1') {
                                        $data['service'][] = $ser->nodeValue;
                                    }
                                }
                            } else {
                                $services = $d->getElementsByTagName('table')->item(1)->childNodes->item(2)->childNodes->item(0)->childNodes->item(1)->childNodes;
                                foreach ($services as $k => $ser) {
                                    if ($k != 0 && trim($ser->nodeValue) != '' && $ser->attributes->item(0)->value == 'bleftdd_1') {
                                        $data['service'][] = $ser->nodeValue;
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $gmc = $d->getElementsByTagName('table')->item(0)->childNodes->item(0)->nodeValue;
                    if (strpos($gmc, 'gian hoạt động')) {
                        $gmc = str_replace("Thời gian hoạt động", "", $gmc);
                        $data['gmc'] = self::clean($gmc);
                    }

                    if (strpos($a->query('//*[@class="rdct_0"]')->item(0)->nodeValue, 'THEO DÕI')) {
                        if ($d->getElementsByTagName('table')->item(0)->childNodes->item(2)) {
                            $price = $d->getElementsByTagName('table')->item(0)->childNodes->item(2)->nodeValue;
                            if (strpos($price, "Khoảng giá")) {
                                $price = str_replace("Khoảng giá", "", $price);
                                $data['price'] = self::clean($price);
                            }
                        }
                        $albumss = $a->query('//*[@class="rightddiemct"]')->item(0)->childNodes->item(7)->childNodes->item(1)->childNodes->item(4)->nodeValue;
                        if ($albumss) {
                            $album = $a->query('//*[@class="rightddiemct"]')->item(0)->childNodes->item(7)->childNodes->item(1)->childNodes->item(4)->childNodes->item(0)->attributes->item(1)->nodeValue;

                            $arr = explode('-', $album);
                            $code_img = end($arr);
                            $dl = '';
                            $dl = self::curlGetLink($album);
                            echo "<script>$(document).ready(function(){ $.post('https://vinalo.com/loadh/albumdd', { img : 10244, id : '" . $code_img . "' }, function(data) { $('#loadcent').html(data); }); })</script>";
                            $al_mige = self::curlPost($code_img);

                            $al = new \DOMXPath($al_mige);
                            $album_ms = $al->query('//*[@class="king_test"]')->item(0)->childNodes;
                            $data['img'] = [];
                            foreach ($album_ms as $album_m) {
                                if (trim($album_m->nodeValue) != '' && trim($album_m->nodeName) != 'hhh') {
                                    $data['img'][] = $album_m->childNodes->item(1)->attributes->item(0)->nodeValue;
                                }
                            }
                        } else {
                            $t = $a->query('//*[@class="dhinhsli"]')->item(0)->childNodes;
                            $data['img'] = [];
                            foreach ($t as $k => $child) {
                                if ($k != 0 && trim($child->nodeValue) != '') {
                                    $data['img'][] = $child->childNodes->item(1)->attributes->item(1)->value;
                                }
                            }
                        }
                        if (strpos($d->getElementsByTagName('table')->item(1)->nodeValue, 'cập nhật thông') == FALSE) {
                            $services_ty = '';
                            if ($d->getElementsByTagName('table')->item(1)->childNodes->length == 2) {
                                $services_ty = $d->getElementsByTagName('table')->item(1)->childNodes->item(1)->nodeValue;
                            } elseif ($d->getElementsByTagName('table')->item(1)->childNodes->length == 1) {
                                $services_ty = $d->getElementsByTagName('table')->item(1)->childNodes->item(0)->nodeValue;
                            }
                            if (strpos($services_ty, 'Tiện ích')) {
                                $services = $d->getElementsByTagName('table')->item(1)->childNodes->item(0)->childNodes->item(0)->childNodes->item(1)->childNodes;
                                foreach ($services as $k => $ser) {
                                    if ($k != 0 && trim($ser->nodeValue) != '' && $ser->attributes->item(0)->value == 'bleftdd_1') {
                                        $data['service'][] = $ser->nodeValue;
                                    }
                                }
                            } else {
                                $services = $d->getElementsByTagName('table')->item(1)->childNodes->item(2)->childNodes->item(0)->childNodes->item(1)->childNodes;
                                foreach ($services as $k => $ser) {
                                    if ($k != 0 && trim($ser->nodeValue) != '' && $ser->attributes->item(0)->value == 'bleftdd_1') {
                                        $data['service'][] = $ser->nodeValue;
                                    }
                                }
                            }
                        }
                    } else {
                        if ($d->getElementsByTagName('table')->item(0)->childNodes->length >= 3) {
                            $price = $d->getElementsByTagName('table')->item(0)->childNodes->item(2)->nodeValue;
                            if (strpos($price, 'Khoảng giá')) {
                                $price = str_replace("Khoảng giá", "", $price);
                                $data['price'] = self::clean($price);
                            }
                        }
                        $albumss = $a->query('//*[@class="rightddiemct"]')->item(0)->childNodes->item(5)->childNodes->item(1)->childNodes->item(4)->nodeValue;
                        if ($albumss) {
                            $album = $a->query('//*[@class="rightddiemct"]')->item(0)->childNodes->item(5)->childNodes->item(1)->childNodes->item(4)->childNodes->item(0)->attributes->item(1)->nodeValue;

                            $arr = explode('-', $album);
                            $code_img = end($arr);
                            $dl = '';
                            $dl = self::curlGetLink($album);
                            echo "<script>$(document).ready(function(){ $.post('https://vinalo.com/loadh/albumdd', { img : 10244, id : '" . $code_img . "' }, function(data) { $('#loadcent').html(data); }); })</script>";
                            $al_mige = self::curlPost($code_img);

                            $al = new \DOMXPath($al_mige);
                            $album_ms = $al->query('//*[@class="king_test"]')->item(0)->childNodes;
                            $data['img'] = [];
                            foreach ($album_ms as $album_m) {
                                if (trim($album_m->nodeValue) != '' && trim($album_m->nodeName) != 'hhh') {
                                    $data['img'][] = $album_m->childNodes->item(1)->attributes->item(0)->nodeValue;
                                }
                            }
                        } else {
                            $t = $a->query('//*[@class="dhinhsli"]')->item(0)->childNodes;
                            $data['img'] = [];
                            foreach ($t as $k => $child) {
                                if ($k != 0 && trim($child->nodeValue) != '') {
                                    $data['img'][] = $child->childNodes->item(1)->attributes->item(1)->value;
                                }
                            }
                        }
                        if (strpos($d->getElementsByTagName('table')->item(1)->nodeValue, 'cập nhật thông') == FALSE) {
                            $services_ty = '';
                            if ($d->getElementsByTagName('table')->item(1)->childNodes->length == 1) {
                                $services_ty = $d->getElementsByTagName('table')->item(1)->childNodes->item(0)->nodeValue;
                                $services = $d->getElementsByTagName('table')->item(1)->childNodes->item(0)->childNodes->item(0)->childNodes->item(1)->childNodes;
                            } elseif ($d->getElementsByTagName('table')->item(1)->childNodes->length == 2) {
                                $services_ty = $d->getElementsByTagName('table')->item(1)->childNodes->item(1)->nodeValue;
                                $services = $d->getElementsByTagName('table')->item(1)->childNodes->item(1)->childNodes->item(0)->childNodes->item(1)->childNodes;
                            }

                            if (strpos($services_ty, 'Tiện ích')) {
                                foreach ($services as $k => $ser) {
                                    if ($k != 0 && trim($ser->nodeValue) != '' && $ser->attributes->item(0)->value == 'bleftdd_1') {
                                        $data['service'][] = $ser->nodeValue;
                                    }
                                }
                            } else {
                                $services = $d->getElementsByTagName('table')->item(1)->childNodes->item(2)->childNodes->item(0)->childNodes->item(1)->childNodes;
                                foreach ($services as $k => $ser) {
                                    if ($k != 0 && trim($ser->nodeValue) != '' && $ser->attributes->item(0)->value == 'bleftdd_1') {
                                        $data['service'][] = $ser->nodeValue;
                                    }
                                }
                            }
                        }
                    }
                }
                $data['description'] = $data['name'] .' - '. $data['address'] .','. $data['quan'] .', '. $data['cty']. ' - '. isset($data['gmc'])? $data['gmc'] : ''. ' - '.isset($data['price'])?$data['price']:'';
                $value = [];
                // convert value.
                $value['name'] = trim($data['name']); //name
                $value['alias'] = str_slug($value['name']); //alias

                $description = explode('-', $data['description']);
                $value['description'] = trim(end($description)); //description

                $value['id_category'] = Category::where('name', '=', $link['categorie'])->pluck('id')->first();
                //category_id

                $list_category_item = explode(',', $link['categorie_items']);
                foreach ($list_category_item as $category) {
                    $value['category_item'][] = CategoryItem::where('name', '=', $category)->where('category_id', '=', $value['id_category'])->pluck('id')->first();
                }
                //category item

                if (isset($data['gmc'])) {
                    if (substr($data['gmc'], 3) == '24/24' || substr($data['gmc'], 3) == '24/7') {
                        $value['open_from'] = '00:00:00';
                        $value['open_to'] = '00:00:00';
                    } elseif (substr($data['gmc'], 3) == 'Always open') {
                        $value['open_from'] = '00:00:00';
                        $value['open_to'] = '00:00:00';
                    } else {
                        $value['open_from'] = substr(trim(explode('-', $data['gmc'])[0]), 3);
                        $value['open_to'] = trim(explode('-', $data['gmc'])[1]);
                    }
                } else {
                    $value['open_from'] = '00:00:00';
                    $value['open_to'] = '00:00:00';
                }
                // open from - open to

                if (!isset($data['price']) || substr($data['price'], 3) == 'Đang cập nhật') {
                    $value['price_from'] = 0;
                    $value['price_to'] = 0;
                } else {
                    if (strpos($data['price'], 'đến') !== false) {
                        $value['price_from'] = str_replace('đ', '', str_replace('.', '', explode('đến', $data['price'])[0]));
                        $value['price_to'] = str_replace('đ', '', str_replace('.', '', explode('đến', $data['price'])[1]));
                    } else {
                        $value['price_from'] = str_replace('đ', '', str_replace('.', '', explode('-', $data['price'])[0]));
                        $value['price_to'] = str_replace('đ', '', str_replace('.', '', explode('-', $data['price'])[1]));
                    }

                    $value['price_from'] = trim($value['price_from']);
                    $value['price_from'] = substr($value['price_from'], 3);

                    $value['price_to'] = trim($value['price_to']);
                }

                if (isset($data['service'])) {
                    $query = '';
                    foreach ($data['service'] as $key2 => $value2) {
                        if ($key2 == 0) {
                            $query .= ' `name` like "%' . trim($value2) . '%" ';
                        } else {
                            $query .= ' or `name` like "%' . trim($value2) . '%" ';
                        }
                    }
                    if ($query) {
                        $service = ServiceItem::whereRaw($query)->pluck('id')->toArray();
                        $service = CategoryService::where('id_category', '=', $value['id_category'])->whereIn('id_service_item', $service)->pluck('id_service_item')->toArray();
                    }
                    $value['service'] = $service;
                }
                //service

                $value['country'] = 1;

                if ($data['cty'] == 'TP. HCM') {
                    $value['city'] = 1;
                } else {
                    $value['city'] = City::select('cities.*')
                                    ->selectRaw("MATCH(`name`) AGAINST ('" . $data['cty'] . " \"" . $data['cty'] . "\"' in boolean mode) as math_score")
                                    ->whereRaw("MATCH(`name`) AGAINST ('" . $data['cty'] . " \"" . $data['cty'] . "\"' in boolean mode) >1")
                                    ->orderBy('math_score', 'desc')
                                    ->orwhere('name', 'like', '%' . $data['cty'] . '%')->first()->id;
                }

                $value['district'] = District::select('districts.*')
                                ->selectRaw("MATCH(`name`) AGAINST ('" . $data['quan'] . " \"" . $data['quan'] . "\"' in boolean mode) as math_score")
                                ->whereRaw("MATCH(`name`) AGAINST ('" . $data['quan'] . " \"" . $data['quan'] . "\"' in boolean mode) > 0")
                                ->where('id_city', '=', $value['city'])
                                ->orderBy('math_score', 'desc')
                                ->first()->id;

                $value['address'] = trim(explode(',', $data['address'])[0]);
                // address

                $latlng = explode('=', $data['geo']);
                $latlng = end($latlng);
                $value['lat'] = explode(',', $latlng)[0];
                $value['lng'] = explode(',', $latlng)[1];
                // latlng

                $value['avatar'] = $data['avatar'];
                // avatar

                $value['image_space'] = $data['img'];
                $value['tag'] = isset($link['tags']) ? $link['tags'] : '';
                $value['link'] = $link['link'];
                $value['moderation'] = $moderation;
                $value['date'] = $date;
                // image space

                self::save_content_by_site($value);
            } catch (\Exception $e) {
                $err = $link['link'] . ' - lỗi DATA <br>';
                $mess_err = session()->get('errorInsert');
                $mess_err .= $err;
                session()->flash('errorInsert', $mess_err);
                continue;
            }
        }
    }

    public function getDataFoody($links, $moderation, $date_insert) {
        foreach ($links as $link) {
            try {
                $data = [];
                $html = self::curlGetLink($link['link']);
                // dd("TEST===: ",$html);
                // $html = self::curlGetLink('https://www.foody.vn/ho-chi-minh/con-ga-trong-tien-giang');

                $data['description'] = $html->getElementsByTagName('meta')->item(2)->attributes->item(1)->nodeValue;

                $html_result = new \DOMXPath($html);
                $defaultLatgitude = $html_result->query('//meta[@itemprop="latitude"]')->item(0)->attributes->item(1)->nodeValue;
                $defaultLongitude = $html_result->query('//meta[@itemprop="longitude"]')->item(0)->attributes->item(1)->nodeValue;
                $data['geo'] = $defaultLatgitude . ',' . $defaultLongitude;

                $id = $html_result->query('//div[@class="report-microsite btn-report-microsite"]')->item(0)->attributes->item(1)->nodeValue;

                // date_default_timezone_set('UTC');
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $date = new \DateTime();
                // $link_get_keyword = 'https://www.foody.vn/__get/Delivery/GetDeliveryDishes?restaurantId=' . $id . '&requestCount=100&nextItemId=0&sortType=2&t=' . $date->getTimestamp();
                // dd($link_get_keyword);
                // $json = self::curlGetFoodLink($link_get_keyword);
                // $tags = [];
                // if (!empty($json)) {
                //     $json_paser = json_decode($json, true);
                //     if (isset($json_paser['Dishes']) && !empty($json_paser['Dishes'])) {
                //         foreach ($json_paser['Dishes']['Items'] as $item) {
                //             $tags[] = $item['Name'];
                //         }
                //     }
                // }

                $data['name'] = $html_result->query('//div[@class="main-info-title"]/h1[@itemprop="name"]')->item(0)->nodeValue;
                $data['avatar'] = $html_result->query('//meta[@property="og:image"]')->item(0)->attributes->item(1)->nodeValue;

                // gia, dia chi, giờ mo cua
                // dia chi
                $data['address'] = $html_result->query('//span[@itemprop="streetAddress"]')->item(0)->nodeValue;
                $data['quan'] = $html_result->query('//span[@itemprop="addressLocality"]')->item(0)->nodeValue;
                $data['cty'] = $html_result->query('//span[@itemprop="addressRegion"]')->item(0)->nodeValue;

                // gio mo cua
                $open = $html_result->query('//div[@class="micro-timesopen"]')->item(0)->childNodes->item(5)->nodeValue;
                if ($open == 'Cả ngày') {
                    $data['gmc'] = '24/24';
                } else {
                    $data['gmc'] = $open;
                }
               // $open = trim($open, '  | ');
               // $time = explode('-', $open);
               // $time[1] = date("H:i", strtotime($time[1]));
               // $open = implode('- ', $time);
               // $data['gmc'] = str_replace(array('AM ', 'PM'), '', $open);

                // gia
                $data['price'] = trim($html_result->query('//span[@itemprop="priceRange"]')->item(0)->nodeValue);

                // Thong tin service
                $services = $html_result->query('//ul[@class="micro-property"]/li');
                foreach ($services as $service) {
                    if ($service->attributes->length == 0) {
                        $data['service'][] = $service->childNodes->item(2)->nodeValue;
                    }
                }
                $des_gmc = isset($data['gmc'])? ', mở cửa từ thứ 2 đến chủ nhật ' .$data['gmc'] : '';
                $des_price = isset($data['price'])? ', giá từ '.$data['price']:'';
                $data['description'] = $data['name'].' tại '. $data['address'] .' '. $data['quan'] .' '. $data['cty']. ', thuộc thể loại '. $link['categorie_items']. $des_gmc . $des_price;

            } catch (\Exception $e) {
                $err = $e->getMessage() . ' ---' . $link['link'] . ' - lỗi DATA <br>';
                $mess_err = session()->get('errorInsert');
                $mess_err .= $err;
                session()->flash('errorInsert', $mess_err);
                continue;
            }
            $value = [];
            $value['name'] = $data['name']; //name
            $value['alias'] = str_slug($value['name']); //alias
            $value['description'] = $data['description']; //description
            $value['id_category'] = Category::where('name', '=', $link['categorie'])->pluck('id')->first();

            if (isset($data['gmc'])) {
                $count = count(explode('-', $data['gmc']));
                if ($count == 2) {
                    $value['open_from'] = trim(explode('-', $data['gmc'])[0]);
                    $value['open_to'] = trim(explode('-', $data['gmc'])[1]);
                } else {
                    $value['open_from'] = '00:00:00';
                    $value['open_to'] = '00:00:00';
                }
            } else {
                $value['open_from'] = '00:00:00';
                $value['open_to'] = '00:00:00';
            }

            if (isset($data['price'])) {
                $count = count(explode('-', $data['price']));
                if ($count == 2) {
                    $value['price_from'] = trim(str_replace('đ', '', str_replace('.', '', explode('-', $data['price'])[0])));
                    $value['price_to'] = trim(str_replace('đ', '', str_replace('.', '', explode('-', $data['price'])[1])));
                } else {
                    $value['price_from'] = 0;
                    $value['price_to'] = 0;
                }
            } else {
                $value['price_from'] = 0;
                $value['price_to'] = 0;
            }
            try {
                if (isset($data['service'])) {
                    $query = '';
                    foreach ($data['service'] as $key2 => $value2) {
                        if ($key2 == 0) {
                            $query .= ' `name` like "%' . trim($value2) . '%" ';
                        } else {
                            $query .= ' or `name` like "%' . trim($value2) . '%" ';
                        }
                    }
                    if ($query) {
                        $service = ServiceItem::whereRaw($query)->pluck('id')->toArray();
                        $service = CategoryService::where('id_category', '=', $value['id_category'])->whereIn('id_service_item', $service)->pluck('id_service_item')->toArray();
                    }
                    $value['service'] = $service;
                }
            } catch (Exception $ex) {
                $err = $e->getMessage() . ' ---' . $link['link'] . ' - lỗi DATA (Service) <br>';
                $mess_err = session()->get('errorInsert');
                $mess_err .= $err;
                session()->flash('errorInsert', $mess_err);
                continue;
            }


            $value['country'] = 1;

            if ($data['cty'] == 'TP. HCM') {
                $value['city'] = 1;
            } else {
                $value['city'] = City::select('cities.*')
                                ->selectRaw("MATCH(`name`) AGAINST ('" . $data['cty'] . " \"" . $data['cty'] . "\"' in boolean mode) as math_score")
                                ->whereRaw("MATCH(`name`) AGAINST ('" . $data['cty'] . " \"" . $data['cty'] . "\"' in boolean mode) >1")
                                ->orderBy('math_score', 'desc')
                                ->orwhere('name', 'like', '%' . $data['cty'] . '%')->first()->id;
            }

            $value['district'] = District::select('districts.*')
                            ->selectRaw("MATCH(`name`) AGAINST ('" . $data['quan'] . " \"" . $data['quan'] . "\"' in boolean mode) as math_score")
                            ->whereRaw("MATCH(`name`) AGAINST ('" . $data['quan'] . " \"" . $data['quan'] . "\"' in boolean mode) > 0")
                            ->where('id_city', '=', $value['city'])
                            ->orderBy('math_score', 'desc')
                            ->first()->id;

            $value['address'] = trim(explode(',', $data['address'])[0]);

            $value['lat'] = explode(',', $data['geo'])[0];
            $value['lng'] = explode(',', $data['geo'])[1];

            $value['avatar'] = $data['avatar'];

            $tag = $value['name'] . ',' . $value['name'] . ' ' . $value['address'] . ',' . $value['name'] . ' ' . $data['quan'] . ',' . $value['name'] . ' ' . $data['cty'];

            // if (!empty($tags)) {
            //     $tag .= ',' . implode(',', $tags);
            // }
            // dd($html->getElementsByTagName('meta')->item(3)->attributes->item(1)->nodeValue);
            $tag .= ','.$html->getElementsByTagName('meta')->item(3)->attributes->item(1)->nodeValue;

            $list_category_item = explode(',', $link['categorie_items']);
            foreach ($list_category_item as $category) {
                $check_category_item = CategoryItem::where('name', '=', $category)->where('category_id', '=', $value['id_category'])->pluck('id')->first();

                if ($check_category_item != null) {
                    $tag .= ',' . $category . ' ' . $value['address'] . ',' . $category . ' ' . $data['quan'] . ',' . $category . ' ' . $data['cty'];
                    $value['category_item'][] = $check_category_item;
                }
            }

            $value['tag'] = $tag;

            $value['moderation'] = $moderation;
            $value['link'] = $link['link'];
            $value['unique_code'] = $link['code'];
            $value['date'] = $date_insert;
            try {
                self::save_content_by_site($value);
            } catch (Exception $ex) {
                $err = $e->getMessage() . ' ---' . $link['link'] . ' - lỗi DATA (Insert) <br>';
                $mess_err = session()->get('errorInsert');
                $mess_err .= $err;
                session()->flash('errorInsert', $mess_err);
                continue;
            }
        }
    }

    public function getReplicateFoody($links, $moderation, $date) {
        foreach ($links as $link) {
            try {
                $data = [];
                $html = self::curlGetLink($link['link']);
                $data['description'] = $html->getElementsByTagName('meta')->item(2)->attributes->item(1)->nodeValue;
                $html_result = new \DOMXPath($html);
                // class=main-info-title
                $data['name'] = $html_result->query('//div[@class="main-info-title"]/h1[@itemprop="name"]')->item(0)->nodeValue;

                $check_alias = $data['name'];
                $check_content = Content::where('alias', '=', str_slug($check_alias))->first();
                $data['name'] = isset($check_content) ? 'Nightlife ' . $data['name'] : $data['name'];


                $defaultLatgitude = $html_result->query('//meta[@itemprop="latitude"]')->item(0)->attributes->item(1)->nodeValue;
                $defaultLongitude = $html_result->query('//meta[@itemprop="longitude"]')->item(0)->attributes->item(1)->nodeValue;
                $data['geo'] = $defaultLatgitude . ',' . $defaultLongitude;

               // $data['name'] = $html_result->query('//div[@class="main-info-title"]/h1[@itemprop="name"]')->item(0)->nodeValue;
                $data['avatar'] = $html_result->query('//meta[@property="og:image"]')->item(0)->attributes->item(1)->nodeValue;

                // gia, dia chi, giờ mo cua
                // dia chi
                $data['address'] = $html_result->query('//span[@itemprop="streetAddress"]')->item(0)->nodeValue;
                $data['quan'] = $html_result->query('//span[@itemprop="addressLocality"]')->item(0)->nodeValue;
                $data['cty'] = $html_result->query('//span[@itemprop="addressRegion"]')->item(0)->nodeValue;
                // gio mo cua
                $open = $html_result->query('//div[@class="micro-timesopen"]')->item(0)->childNodes->item(5)->nodeValue;
                if ($open == 'Cả ngày') {
                    $data['gmc'] = '24/24';
                } else {
                    $data['gmc'] = $open;
                }

                // gia
                $data['price'] = trim($html_result->query('//span[@itemprop="priceRange"]')->item(0)->nodeValue);

                // Thong tin service
                $services = $html_result->query('//ul[@class="micro-property"]/li');
                foreach ($services as $service) {
                    if ($service->attributes->length == 0) {
                        $data['service'][] = $service->childNodes->item(2)->nodeValue;
                    }
                }
                $des_gmc = isset($data['gmc'])? ', mở cửa từ thứ 2 đến chủ nhật ' .$data['gmc'] : '';
                $des_price = isset($data['price'])? ', giá từ '.$data['price']:'';
                $data['description'] = $data['name'].' tại '. $data['address'] .','. $data['quan'] .', '. $data['cty']. ', thuộc thể loại '. $link['categorie_items'].', mở cửa từ thứ 2 đến chủ nhật ' . $des_gmc . $des_price;

                $value = [];
                $value['name'] = $data['name']; //name
                $value['alias'] = str_slug($value['name']); //alias
                $value['description'] = $data['description']; //description
                $value['id_category'] = Category::where('name', '=', $link['categorie'])->pluck('id')->first();

                if (isset($data['gmc'])) {
                    $count = count(explode('-', $data['gmc']));
                    if ($count == 2) {
                        $value['open_from'] = trim(explode('-', $data['gmc'])[0]);
                        $value['open_to'] = trim(explode('-', $data['gmc'])[1]);
                    } else {
                        $value['open_from'] = '00:00:00';
                        $value['open_to'] = '00:00:00';
                    }
                } else {
                    $value['open_from'] = '00:00:00';
                    $value['open_to'] = '00:00:00';
                }

                if (isset($data['price'])) {
                    $count = count(explode('-', $data['price']));
                    if ($count == 2) {
                        $value['price_from'] = trim(str_replace('đ', '', str_replace('.', '', explode('-', $data['price'])[0])));
                        $value['price_to'] = trim(str_replace('đ', '', str_replace('.', '', explode('-', $data['price'])[1])));
                    } else {
                        $value['price_from'] = 0;
                        $value['price_to'] = 0;
                    }
                } else {
                    $value['price_from'] = 0;
                    $value['price_to'] = 0;
                }

                if (isset($data['service'])) {
                    $query = '';
                    foreach ($data['service'] as $key2 => $value2) {
                        if ($key2 == 0) {
                            $query .= ' `name` like "%' . trim($value2) . '%" ';
                        } else {
                            $query .= ' or `name` like "%' . trim($value2) . '%" ';
                        }
                    }
                    if ($query) {
                        $service = ServiceItem::whereRaw($query)->pluck('id')->toArray();
                        $service = CategoryService::where('id_category', '=', $value['id_category'])->whereIn('id_service_item', $service)->pluck('id_service_item')->toArray();
                    }
                    $value['service'] = $service;
                }

                $value['country'] = 1;

                if ($data['cty'] == 'TP. HCM') {
                    $value['city'] = 1;
                } else {
                    $value['city'] = City::select('cities.*')
                                    ->selectRaw("MATCH(`name`) AGAINST ('" . $data['cty'] . " \"" . $data['cty'] . "\"' in boolean mode) as math_score")
                                    ->whereRaw("MATCH(`name`) AGAINST ('" . $data['cty'] . " \"" . $data['cty'] . "\"' in boolean mode) >1")
                                    ->orderBy('math_score', 'desc')
                                    ->orwhere('name', 'like', '%' . $data['cty'] . '%')->first()->id;
                }

                $value['district'] = District::select('districts.*')
                                ->selectRaw("MATCH(`name`) AGAINST ('" . $data['quan'] . " \"" . $data['quan'] . "\"' in boolean mode) as math_score")
                                ->whereRaw("MATCH(`name`) AGAINST ('" . $data['quan'] . " \"" . $data['quan'] . "\"' in boolean mode) > 0")
                                ->where('id_city', '=', $value['city'])
                                ->orderBy('math_score', 'desc')
                                ->first()->id;

                $value['address'] = trim(explode(',', $data['address'])[0]);

                $value['lat'] = explode(',', $data['geo'])[0];
                $value['lng'] = explode(',', $data['geo'])[1];

                $value['avatar'] = $data['avatar'];

                $tag = $value['name'] . ',' . $value['name'] . ' ' . $value['address'] . ',' . $value['name'] . ' ' . $data['quan'] . ',' . $value['name'] . ' ' . $data['cty'];

                $list_category_item = explode(',', $link['categorie_items']);
                foreach ($list_category_item as $category) {
                    $check_category_item = CategoryItem::where('name', '=', $category)->where('category_id', '=', $value['id_category'])->pluck('id')->first();

                    if ($check_category_item != null) {
                        $tag .= ',' . $category . ' ' . $value['address'] . ',' . $category . ' ' . $data['quan'] . ',' . $category . ' ' . $data['cty'];
                        $value['category_item'][] = $check_category_item;
                    }
                }
                $value['tag'] = $tag;

                $value['moderation'] = $moderation;
                $value['link'] = $link['link'];
                $value['date'] = $date;

                self::save_content_by_site($value);
            } catch (\Exception $e) {
                $err = $link['link'] . ' - lỗi DATA <br>';
                $mess_err = session()->get('errorInsert');
                $mess_err .= $err;

                session()->flash('errorInsert', $mess_err);
                continue;
            }
        }
    }

    public function getDataLinkMytour($link_mytour, $moderation, $date, $start_page) {
        $data = [];
        $output = explode('?page=', $link_mytour);
        if (!isset($output[1])) {
            $output['page'] = 1;
        } else {
            $output['page'] = $output[1];
        }
        for ($i = $start_page; $i <= $output['page']; $i++) {
            try {
                if ($i == 1) {
                    $url = explode('?', $link_mytour);
                    $link = $url[0];
                } else {
                    $link = str_replace('?page=' . $output['page'], '?page=' . $i, $link_mytour);
                }
                $html = self::curlGetLink($link);
                $html_result = new \DOMXPath($html);
                $token = $html_result->query('//meta[@name="csrf-token"]')->item(0)->attributes->item(1)->nodeValue;

                $str = $html_result->query('//script[@type="text/javascript"]')->item(2)->nodeValue;
                $str_exp = explode('(function($){', $str);
                $str_text = str_replace('var listing = ', '', $str_exp[0]);
                $str_text = str_replace(';', '', $str_text);
                $str_text = trim($str_text);
                $str_text = str_replace('params', '"params"', $str_text);
                $str_text = str_replace('currentLocation', '"currentLocation"', $str_text);
                $str_text = str_replace('treeBreadcrumb', '"treeBreadcrumb"', $str_text);
                $str_text = str_replace('page', '"page"', $str_text);
                $str_text = str_replace('rate', '"rate"', $str_text);
                $str_text = str_replace("'", '"', $str_text);
                $str_paser = json_decode($str_text, true);
                $str_paser['token'] = $token;

                $result = self::curlPostListMytour($str_paser);
                $html_list = new \DOMXPath($result);
                $lists = $html_list->query('//div[@class="listing-box-price box-price"]/a/attribute::href');
                foreach ($lists as $list) {
                    self::getDataMyTour('https://mytour.vn' . $list->nodeValue, $moderation, $date);
                    sleep(7);
                }
                sleep(120);
            } catch (\Exception $exc) {
                echo $exc->getMessage();
                continue;
            }
        }
    }

    public function getDataMyTour($links, $moderation, $date) {
        $data = [];
        try {
            $html = self::curlGetLink($links, 'mytour');
            $html_result = new \DOMXPath($html);
            $sliders = $html_result->query('//div[@class="slider-sm"]/div[@id="slider"]/ul/li');
            if ($html_result->query('//div[@class="slider-sm"]/div[@id="slider"]/ul')->length >= 1 && $sliders->length >= 1) {
                $heading = $html_result->query('//div[@class="page-header"]/h1[@class="title-lg"]')->item(0);
                $data['name'] = trim($heading->firstChild->nodeValue);
                $star = $html_result->query('//div[@class="page-header"]//h1[@class="title-lg"]/span[@class="star"]');
                if ($star->length < 1) {
                    $data['star'] = '0 sao';
                } else {
                    $t = $star->item(0)->childNodes->item(1)->attributes->item(0)->nodeValue;
                    switch ($t) {
                        case 'star-1':
                            $data['star'] = '1 sao';
                            break;
                        case 'star-2':
                            $data['star'] = '2 sao';
                            break;
                        case 'star-3':
                            $data['star'] = '3 sao';
                            break;
                        case 'star-4':
                            $data['star'] = '4 sao';
                            break;
                        case 'star-5':
                            $data['star'] = '5 sao';
                            break;
                        default :
                            $data['star'] = '0 sao';
                            break;
                    }
                }
                $map_lat = $html_result->query('//div[@class="page-header"]/p[@class="text-df"]/a[@modal-name="modal-map"]/attribute::data-map-lat')->item(0)->nodeValue;
                $map_long = $html_result->query('//div[@class="page-header"]/p[@class="text-df"]/a[@modal-name="modal-map"]/attribute::data-map-lng')->item(0)->nodeValue;
                $data['geo'] = $map_lat . ',' . $map_long;
                $address = $html_result->query('//div[@class="page-header"]/p[@class="text-df"]/a[@modal-name="modal-map"]/span[@class="gray"]')->item(0)->nodeValue;
                $address = explode(',', $address);
                $data['quan'] = $html_result->query('//ul[@id="breadcrumb-scroll"]/li/a')->item(2)->nodeValue;
                $data['cty'] = $html_result->query('//ul[@id="breadcrumb-scroll"]/li/a')->item(1)->nodeValue;
                $data['address'] = trim(array_shift($address));
                $data['gmc'] = '24/24';
                $data['avatar'] = $html_result->query('//div[@class="slider-sm"]/div[@id="slider"]/ul')->item(0)->firstChild->attributes->item(0)->nodeValue;
           // $data['description'] = $html_result->query('//meta[@name="description"]')->item(0)->attributes->item(1)->nodeValue;
                $data['keywords'] = $html_result->query('//meta[@name="keywords"]')->item(0)->attributes->item(1)->nodeValue;

                $sliders = $html_result->query('//div[@class="slider-sm"]/div[@id="slider"]/ul/li');
                $i = 1;
                foreach ($sliders as $slider) {
                    $data['img'][] = $slider->attributes->item(0)->nodeValue;
                    if ($i > 14) {
                        break;
                    }
                    $i++;
                }

                $services = $html_result->query('//div[@class="box"]/div[@class="box-body"]/div[@class="attribute-hotel"]/ul/li/span[@class="attribute-value"]');
                if ($services->length >= 1) {
                    foreach ($services as $service) {
                        $data['service'][] = trim($service->nodeValue);
                    }
                }
                $data_t['id_hotel'] = $html_result->query('//div[@class="page-header"]/p[@class="text-df"]/a[@modal-name="modal-map"]/attribute::check-place')->item(0)->nodeValue;
                ;
                $data_t['_token'] = $html_result->query('//meta[@name="csrf-token"]')->item(0)->attributes->item(1)->nodeValue;

                // price
                $price = self::curlPostMytour($data_t);
                $html_price = new \DOMXPath($price);

                $data_r = $html_price->query('//strong[@class="price-old"]');
                $data['price'] = '';
                if ($data_r->length >= 1) {
                    foreach ($data_r as $r) {
                        $rp[] = $r->firstChild->nodeValue;
                    }
                    if (count($rp) > 1) {
                        $data['price'] = str_replace(',', '', trim(array_shift($rp)) . ' - ' . trim(end($rp)));
                    } else {
                        $data['price'] = str_replace(',', '', trim($rp[0]) . ' - ' . trim($rp[0]));
                    }
                }
                $des_gmc = isset($data['gmc'])? ', mở cửa từ thứ 2 đến chủ nhật ' . $data['gmc'] : '';
                $des_price = isset($data['price'])? ', với mức giá '.$data['price']:'';
                $data['description'] = $data['name']. '  tại ' . $data['address'] .' '. $data['quan'] .' '. $data['cty']. ', thể loại '. $data['star']. $des_gmc . $des_price;

                $value = [];
                $value['name'] = $data['name'];
                $value['alias'] = str_slug($value['name']);

                $value['id_category'] = 6;

                if (isset($data['star'])) {
                    $category_item = CategoryItem::where('name', '=', $data['star'])->where('category_id', '=', $value['id_category'])->pluck('id')->first();

                    if ($category_item != null) {
                        $value['category_item'][] = $category_item;
                    }
                }

                if (isset($data['gmc'])) {
                    $count = count(explode('-', $data['gmc']));
                    if ($count == 2) {
                        $value['open_from'] = trim(explode('-', $data['gmc'])[0]);
                        $value['open_to'] = trim(explode('-', $data['gmc'])[1]);
                    } else {
                        $value['open_from'] = '00:00:00';
                        $value['open_to'] = '00:00:00';
                    }
                } else {
                    $value['open_from'] = '00:00:00';
                    $value['open_to'] = '00:00:00';
                }

                if (isset($data['service'])) {
                    $query = '';
                    foreach ($data['service'] as $key2 => $value2) {
                        if ($key2 == 0) {
                            $query .= ' `name` like "%' . trim($value2) . '%" ';
                        } else {
                            $query .= ' or `name` like "%' . trim($value2) . '%" ';
                        }
                    }
                    if ($query) {
                        $service = ServiceItem::whereRaw($query)->pluck('id')->toArray();
                        $service = CategoryService::where('id_category', '=', $value['id_category'])->whereIn('id_service_item', $service)->pluck('id_service_item')->toArray();
                    }
                    $value['service'] = $service;
                }

                if (isset($data['price'])) {
                    $count = count(explode('-', $data['price']));
                    if ($count == 2) {
                        $value['price_from'] = trim(str_replace('đ', '', str_replace('.', '', explode('-', $data['price'])[0])));
                        $value['price_to'] = trim(str_replace('đ', '', str_replace('.', '', explode('-', $data['price'])[1])));
                    } else {
                        $value['price_from'] = 0;
                        $value['price_to'] = 0;
                    }
                } else {
                    $value['price_from'] = 0;
                    $value['price_to'] = 0;
                }

                $value['country'] = 1;

                if ($data['cty'] == 'TP. HCM') {
                    $value['city'] = 1;
                } else {
                    $value['city'] = City::select('cities.*')
                                    ->selectRaw("MATCH(`name`) AGAINST ('" . $data['cty'] . " \"" . $data['cty'] . "\"' in boolean mode) as math_score")
                                    ->whereRaw("MATCH(`name`) AGAINST ('" . $data['cty'] . " \"" . $data['cty'] . "\"' in boolean mode) >1")
                                    ->orderBy('math_score', 'desc')
                                    ->orwhere('name', 'like', '%' . $data['cty'] . '%')->first()->id;
                }

                $value['district'] = District::select('districts.*')
                                ->selectRaw("MATCH(`name`) AGAINST ('" . $data['quan'] . " \"" . $data['quan'] . "\"' in boolean mode) as math_score")
                                ->whereRaw("MATCH(`name`) AGAINST ('" . $data['quan'] . " \"" . $data['quan'] . "\"' in boolean mode) > 0")
                                ->where('id_city', '=', $value['city'])
                                ->orderBy('math_score', 'desc')
                                ->first()->id;

                $value['address'] = $data['address'];
                $value['lat'] = explode(',', $data['geo'])[0];
                $value['lng'] = explode(',', $data['geo'])[1];

                $value['avatar'] = $data['avatar'];
                $value['image_space'] = $data['img'];

                $tag = $value['name'] . ',' . $value['name'] . ' ' . $value['address'] . ',' . $value['name'] . ' ' . $data['quan'] . ',' . $value['name'] . ' ' . $data['cty'];
                $tag .= ',' . $data['star'] . ' ' . $value['address'] . ',' . $data['star'] . ' ' . $data['quan'] . ',' . $data['star'] . ' ' . $data['cty'];

                $value['tag'] = $tag;
                $value['moderation'] = $moderation;
                $value['link'] = $links;
                $value['date'] = $date;

                self::save_content_by_site($value);
            }
        } catch (\Exception $e) {
            $err = $links . ' - lỗi DATA <br>';
            $mess_err = session()->get('errorInsert');
            $mess_err .= $err;
            session()->flash('errorInsert', $mess_err);
        }
    }

    public function getDataSheIs($links, $moderation, $date) {
        foreach ($links as $link) {
            $data = [];
            try {
                $html = self::curlGetLink($link['link']);
                $html_result = new \DOMXPath($html);
                $str = $html_result->query('//script[@type="text/javascript"]')->item(1)->nodeValue;
                $str_arr = explode('map_X', $str);
                $str_parse = str_replace('var Place = ', '', trim($str_arr[0]));
                $str_parse = rtrim($str_parse, ';');
                $str_parse = json_decode($str_parse, true);

                $data['name'] = $str_parse['Name'];
                $data['avatar'] = $str_parse['Picture'];
                $data['cty'] = $str_parse['CityName'];
                $data['quan'] = $str_parse['DistrictName'];
                $data['address'] = $str_parse['Address'];
                $data['phone'] = $str_parse['Phone'];
                $data['email'] = $str_parse['Email'];
                $data['price'] = $str_parse['PriceDisplay'];
                $data['url'] = $str_parse['Url'];
                $data['geo'] = $str_parse['Latitude'] . ',' . $str_parse['Longtitude'];

                if ($html_result->query('//p[@class="place-time"]')->length >= 1) {
                    $open = $html_result->query('//p[@class="place-time"]')->item(0)->childNodes->item(5)->nodeValue;
                    $data['gmc'] = $open;
                }

                $sliders = $html_result->query('//div[@class="place-slide-photo"]/div[@class="slide-item"]/a/img/attribute::src');
                foreach ($sliders as $slider) {
                    $data['img'][] = $slider->nodeValue;
                }
                $data['keywords'] = $html_result->query('//meta[@name="keywords"]')->item(0)->attributes->item(1)->nodeValue;
               // $data['description'] = $html_result->query('//meta[@name="description"]')->item(0)->attributes->item(1)->nodeValue;

                $des_gmc = isset($data['gmc'])? ', mở cửa từ thứ 2 đến chủ nhật ' . $data['gmc'] : '';
                $des_price = isset($data['price'])? ', với mức giá '.$data['price']:'';
                $data['description'] = $data['name'].' tại '. $data['address'] .','. $data['quan'] .', '. $data['cty']. ', thể loại '. $link['categorie_items']. $des_gmc . $des_price;
                $value = [];
                $value['name'] = $data['name']; //name
                $value['alias'] = str_slug($value['name']); //alias
                $value['description'] = $data['description']; //description
                $value['id_category'] = Category::where('name', '=', $link['categorie'])->pluck('id')->first();

                // Content exists
                if (isset($data['name']) && !empty($data['name'])){
                    $is_content = Content::where('alias', '=', str_slug($value['name']))->first();
                }
                if ($is_content) {
                    $list_category_item = explode(',', $link['categorie_items']);
                    foreach ($list_category_item as $category) {
                        $check_category_item = CategoryItem::where('name', '=', $category)->where('category_id', '=', $value['id_category'])->pluck('id')->first();
                        if ($check_category_item != null) {
                            $content_exits = CategoryContent::where('id_content', '=', $is_content->id)->where('id_category_item', '=', $check_category_item)->first();
                            if ($content_exits == NULL) {
                                $up_cate = CategoryContent::create([
                                    'id_content' => $is_content->id,
                                    'id_category_item' => $check_category_item,
                                ]);
                                if ($up_cate) {
                                    $err = $link['link'] . ' - Update DATA <br>';
                                    $mess_err = session()->get('errorInsert');
                                    $mess_err .= $err;
                                    session()->flash('errorInsert', $mess_err);
                                }
                            }
                        }
                    }
                // end
                }
                else {
                    $list_category_item = explode(',', $link['categorie_items']);
                    foreach ($list_category_item as $category) {
                        $check_category_item = CategoryItem::where('name', '=', $category)->where('category_id', '=', $value['id_category'])->pluck('id')->first();

                        if ($check_category_item != null) {
                            $value['category_item'][] = $check_category_item;
                        }
                    }

                    if (isset($data['gmc'])) {
                        $count = count(explode('-', $data['gmc']));
                        if ($count == 2) {
                            $value['open_from'] = trim(explode('-', $data['gmc'])[0]);
                            $value['open_to'] = trim(explode('-', $data['gmc'])[1]);
                        } else {
                            $value['open_from'] = '00:00:00';
                            $value['open_to'] = '00:00:00';
                        }
                    } else {
                        $value['open_from'] = '00:00:00';
                        $value['open_to'] = '00:00:00';
                    }

                    if (isset($data['price'])) {
                        $count = count(explode('-', $data['price']));
                        if ($count == 2) {
                            $value['price_from'] = trim(str_replace('đ', '', str_replace('.', '', explode('-', $data['price'])[0])));
                            $value['price_to'] = trim(str_replace('đ', '', str_replace('.', '', explode('-', $data['price'])[1])));
                        } else {
                            $value['price_from'] = 0;
                            $value['price_to'] = 0;
                        }
                    } else {
                        $value['price_from'] = 0;
                        $value['price_to'] = 0;
                    }
                    $value['country'] = 1;

                    if ($data['cty'] == 'TP. HCM') {
                        $value['city'] = 1;
                    } else {
                        $value['city'] = City::select('cities.*')
                                        ->selectRaw("MATCH(`name`) AGAINST ('" . $data['cty'] . " \"" . $data['cty'] . "\"' in boolean mode) as math_score")
                                        ->whereRaw("MATCH(`name`) AGAINST ('" . $data['cty'] . " \"" . $data['cty'] . "\"' in boolean mode) >1")
                                        ->orderBy('math_score', 'desc')
                                        ->orwhere('name', 'like', '%' . $data['cty'] . '%')->first()->id;
                    }
                    $value['district'] = District::select('districts.*')
                                    ->selectRaw("MATCH(`name`) AGAINST ('" . $data['quan'] . " \"" . $data['quan'] . "\"' in boolean mode) as math_score")
                                    ->whereRaw("MATCH(`name`) AGAINST ('" . $data['quan'] . " \"" . $data['quan'] . "\"' in boolean mode) > 0")
                                    ->where('id_city', '=', $value['city'])
                                    ->orderBy('math_score', 'desc')
                                    ->first()->id;

                    $value['address'] = trim(explode(',', $data['address'])[0]);
                    $value['lat'] = explode(',', $data['geo'])[0];
                    $value['lng'] = explode(',', $data['geo'])[1];
                    $value['avatar'] = $data['avatar'];
                    $value['tag'] = isset($data['description']) ? $data['description'] : '';
                    $value['moderation'] = $moderation;
                    $value['link'] = $link['link'];
                    $value['image_space'] = $data['img'];
                    $value['date'] = $date;

                    self::save_content_by_site($value);
                }
            } catch (\Exception $ex) {
                $err = $link['link'] . ' - lỗi DATA <br>';
                $mess_err = session()->get('errorInsert');
                $mess_err .= $err;
                session()->flash('errorInsert', $mess_err);
                continue;
            }
        }
    }

    public function getDataVietBanDo($link_data_vietbando, $moderation, $date, $id_category, $category_item, $avatar) {
        parse_str($link_data_vietbando, $output);
        if (!isset($output['page'])) {
            $output['page'] = 1;
        }
        for ($i = 1; $i <= $output['page']; $i++) {
            $link = str_replace('&page=' . $output['page'], '&page=' . $i, $link_data_vietbando);

            $html = self::curlGetVietbanDo($link);
            $html_result = new \DOMXPath($html);
            $lists = $html_result->query('//div[@class="results"]/div[@class="divImgNearby"]/a/attribute::href');
            foreach ($lists as $k => $item) {
                $data = [];
                if ($k <= 15) {
                    try {
                        $v_data = self::curlGetVietbanDo('https://maps.vietbando.com' . $item->nodeValue);

                        $v_html_result = new \DOMXPath($v_data);
                        $name = trim($v_html_result->query('//div[@class="mainTitle"]/h2[@class="name"]')->item(0)->nodeValue);
                        $converted = strtr($name, array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
                        $data['name'] = trim($converted, chr(0xC2) . chr(0xA0));
                        if ($data['name'] == 'Đại Lý Thức Ăn Chăn Nuôi Anh Thy' ||
                                $data['name'] == 'Salon Mr.Đ - Lãnh Địa Tóc' ||
                                $data['name'] == 'Internet Quốc Long' ||
                                $data['name'] == 'Nhà Hàng KhaiSilk - Khai’s Brothers' ||
                                $data['name'] == 'Phòng khám chữa bệnh trĩ tốt nhất tại TPHCM' ||
                                $data['name'] == 'phòng khám đa khoa ÂU Á' ||
                                $data['name'] == 'Yaourt Phô Mai Đà Lạt' ||
                                $data['name'] == 'Trung Tâm Dạy Bơi Việt Nam') {
                            continue;
                        }

                        if ($id_category == 5) {
                            if (strstr($data['name'], "ATM")) {
                                $data['type'] = 'ATM';
                            } else {
                                $data['type'] = 'BANK';
                            }
                        }
                        $address = $v_html_result->query('//div[@class="content-box"]')->item(0)->childNodes->item(3)->childNodes->item(6)->childNodes->item(1)->childNodes->item(1)->nodeValue;
                        $address = explode(',', self::clean($address));
                        $data['quan'] = trim($address[count($address) - 2]);
                        $data['cty'] = trim(end($address));
                        $data['address'] = trim(array_shift($address));
                        $data['phone'] = '';
                        if ($v_html_result->query('//div[@class="content-box"]')->item(0)->childNodes->item(3)->childNodes->item(6)->childNodes->item(1)->childNodes->item(3) && $v_html_result->query('//div[@class="content-box"]')->item(0)->childNodes->item(3)->childNodes->item(6)->childNodes->item(1)
                                ->childNodes->item(3)->childNodes->item(0)->attributes->item(0)->nodeValue == 'fa fa-phone fa-fw') {
                            $data['phone'] = $v_html_result->query('//div[@class="content-box"]')->item(0)->childNodes->item(3)->childNodes->item(6)->childNodes->item(1)->childNodes->item(3)->nodeValue;
                        }
                        $geo = $v_html_result->query('//div[@class="content-box"]')->item(0)->childNodes->item(3)->childNodes->item(8)->childNodes->item(3)->childNodes->item(1)->attributes->item(1)->nodeValue;
                        parse_str($geo, $maps);
                        $data['geo'] = $maps['kv'];
                        $data['keywords'] = $data['name'] . ',' . $data['name'] . ' ' . $data['address'] . ',' . $data['name'] . ' ' . $data['quan'] . ',' . $data['name'] . ' ' . $data['cty'];
                        $data['description'] = $data['name'] . ',' . $data['name'] . ' ' . $data['address'] . ',' . $data['name'] . ' ' . $data['quan'] . ',' . $data['name'] . ' ' . $data['cty'];


                        $value = [];
                        $value['name'] = $data['name']; //name
                        $value['alias'] = str_slug($value['name'] . '-' . $data['address'] . '-' . $data['geo']); //alias
                        $value['description'] = $data['description']; //description
                        $value['id_category'] = $id_category;

                        if (isset($category_item)) {
                            $value['category_item'][] = $category_item;
                        }

                        $value['open_from'] = '00:00:00';
                        $value['open_to'] = '00:00:00';

                        $value['price_from'] = 0;
                        $value['price_to'] = 0;

                        $value['country'] = 1;

                        $data['cty'] = trim(ltrim($data['cty'], "Thành Phố"));
                        $data['cty'] = trim(ltrim($data['cty'], "Tỉnh"));

                        $value['city'] = City::select('cities.*')
                                        ->selectRaw("MATCH(`name`) AGAINST ('" . $data['cty'] . " \"" . $data['cty'] . "\"' in boolean mode) as math_score")
                                        ->whereRaw("MATCH(`name`) AGAINST ('" . $data['cty'] . " \"" . $data['cty'] . "\"' in boolean mode) >1")
                                        ->orderBy('math_score', 'desc')
                                        ->orwhere('name', 'like', '%' . $data['cty'] . '%')->first()->id;

                        $value['district'] = District::select('districts.*')
                                        ->selectRaw("MATCH(`name`) AGAINST ('" . $data['quan'] . " \"" . $data['quan'] . "\"' in boolean mode) as math_score")
                                        ->whereRaw("MATCH(`name`) AGAINST ('" . $data['quan'] . " \"" . $data['quan'] . "\"' in boolean mode) > 0")
                                        ->orwhere('name', 'like', '%' . $data['quan'] . '%')
                                        ->where('id_city', '=', $value['city'])
                                        ->orderBy('math_score', 'desc')
                                        ->first()->id;

                        $value['address'] = $data['address'];

                        $value['lat'] = explode(',', $data['geo'])[0];
                        $value['lng'] = explode(',', $data['geo'])[1];
                        $value['avatar'] = $avatar;
                        $value['tag'] = isset($data['keywords']) ? $data['keywords'] : '';
                        $value['moderation'] = $moderation;
                        $value['date'] = $date;
                        $value['site'] = 'bandoviet';
                        if ($id_category == 5) {
                            $value['extra_type'] = $data['type'];
                        }

                        self::save_content_by_site($value);
                    } catch (\Exception $e) {
                        continue;
                    }
                } else {
                    break;
                }
            }
            sleep(120);
        }
    }

    public function getDataAllBank($link_bank, $moderation, $date, $id_category, $category_item, $avatar, $bank_type) {

        $category_name = CategoryItem::find($category_item)->name;

        $data = [];
        parse_str($link_bank, $output);
        if (!isset($output['p'])) {
            $output['p'] = 1;
        }
        for ($i = 1; $i <= $output['p']; $i++) {
            try {
                $link = str_replace('&p=' . $output['p'], '&p=' . $i, $link_bank);
                $html = self::curlGetLink($link);
                $html_result = new \DOMXPath($html);


                $branch_province = $html_result->query('//select[@name="branch_province"]/option');
                $city = '';
                $city_id = '';
                foreach ($branch_province as $province) {
                    $selected = $province->attributes->item(0)->nodeValue;
                    if ($selected == 'selected') {
                        $city_id = $province->attributes->item(1)->nodeValue;
                        $city = $province->nodeValue;
                        break;
                    }
                }
            } catch (\Exception $ex) {
                $err = $ex->getMessage() . ' - lỗi DATA <br>';
                $mess_err = session()->get('errorInsert');
                $mess_err .= $err;
                session()->flash('errorInsert', $mess_err);
                continue;
            }

            $lists = $html_result->query('//div[@class="search_list"]/div[@class="search_result_table"]/table[@class="search_result_trade"]/tr');
            foreach ($lists as $k => $list) {
                if ($k >= 1) {
                    try {
                        $name2 = trim($list->childNodes->item(0)->nodeValue);
                        $name1 = trim($list->childNodes->item(2)->nodeValue);
                        $address = trim($list->childNodes->item(4)->nodeValue);
                        $str = $list->childNodes->item(0)->childNodes->item(1)->attributes->item(1)->nodeValue;

                        $data['name'] = trim($name2);

                        $address = explode(',', self::clean($address));

                        $data['cty'] = $city;
                        $adds = explode('Q.', trim(array_shift($address)));
                        $data['address'] = $adds[0];

                        $str = str_replace('return loadATM(', '', $str);
                        $str = str_replace(')', '', $str);
                        $str = str_replace("'", '', $str);
                        $value_arr = explode(',', $str);
                        $data['geo'] = $value_arr[0] . ',' . $value_arr[1];
                        $data['quan'] = self::getQuanFormLatLng($value_arr[0], $value_arr[1]);
                        $key = explode('-', $name1);
                        $data['keyword'] = $key[0] . ', ' . '' . $key[1] . ', ' . $key[0] . ' ' . $data['address'] . ', ' . $key[0] . ' ' . $data['quan'] . ', ' . $key[0] . ' ' . $data['cty'] . ', '
                                . $key[1] . ' ' . $data['address'] . ', ' . $key[1] . ' ' . $data['quan'] . ', ' . $key[1] . ' ' . $data['cty'];
                        $data['gmc'] = '08h - 16h30';

                        $value = [];
                        if (!empty($data)) {
                            $value['name'] = $category_name . ' - ' . $data['name']; //name
                            $value['alias'] = str_slug($value['name']); //alias
                            $value['id_category'] = $id_category;
                            $value['category_item'][] = $category_item;
                            $value['country'] = 1;
                            if ($data['cty'] == 'TP.HCM') {
                                $value['city'] = 1;
                            } else {
                                $city1 = City::select('cities.*')
                                                ->selectRaw("MATCH(`name`) AGAINST ('" . trim($data['cty']) . " \"" . trim($data['cty']) . "\"' in boolean mode) as math_score")
                                                ->whereRaw("MATCH(`name`) AGAINST ('" . trim($data['cty']) . " \"" . trim($data['cty']) . "\"' in boolean mode) >1")
                                                ->orderBy('math_score', 'desc')
                                                ->orwhere('name', 'like', '%' . trim($data['cty']) . '%')->first()->id;

                                if (!empty($city1)) {
                                    $value['city'] = $city1;
                                }
                            }

                            if (isset($value['city'])) {
                                $district = District::select('districts.*')
                                                ->selectRaw("MATCH(`name`) AGAINST ('" . $data['quan'] . " \"" . $data['quan'] . "\"' in boolean mode) as math_score")
                                                ->whereRaw("MATCH(`name`) AGAINST ('" . $data['quan'] . " \"" . $data['quan'] . "\"' in boolean mode) > 0")
                                                ->orwhere('name', 'like', '%' . $data['quan'] . '%')
                                                ->where('id_city', '=', $value['city'])
                                                ->orderBy('math_score', 'desc')
                                                ->first()->id;
                                if (isset($district)) {
                                    $value['district'] = $district;
                                }
                            }

                            $value['address'] = trim($data['address']);
                            $value['lat'] = explode(',', $data['geo'])[0];
                            $value['lng'] = explode(',', $data['geo'])[1];
                            $value['avatar'] = $avatar;
                            $value['tag'] = isset($data['keyword']) ? $data['keyword'] : '';
                            $value['moderation'] = $moderation;
                            $value['date'] = $date;
                            $value['extra_type'] = $bank_type;
                            $value['site'] = 'bank';
                            self::save_content_by_site($value);
                        }
                    } catch (\Exception $e) {
                        $err = $e->getMessage() . '- line: ' . $k . ' - lỗi DATA <br>';
                        $mess_err = session()->get('errorInsert');
                        $mess_err .= $err;
                        session()->flash('errorInsert', $mess_err);
                        continue;
                    }
                }
            }
        }
    }

    public function getImportContentTest(Request $request) {
        ini_set('max_execution_time', 0);

        $moderation = $request->moderation;
        $site = $request->site;
        $date = $request->date_created;

        $errorInsert = '';
        session()->flash('errorInsert', $errorInsert);
        if ($site == 'vietbando') {
            self::getDataVietBanDo($request->link, $moderation, $date, $request->id_category, $request->category_item, $request->avatar);
        } elseif ($site == 'mytour') {
            self::getDataLinkMytour($request->link, $moderation, $date, $request->start_page);
        } elseif ($site == 'bank') {
            $bank_type = isset($request->bank_type) ? 'ATM' : "BANK";
            if ($bank_type == 'BANK') {
                self::getDataAllBank($request->link, $moderation, $date, 5, $request->category_item, $request->avatar, $bank_type);
            } else {
                $data_atm = [
                    'name' => $request->hidden_atm_bank,
                    'city' => $request->hidden_atm_province,
                    'quan' => $request->hidden_atm_provincelist,
                    'bankid' => $request->atm_bank,
                    'atm_card' => $request->atm_card,
                    'atm_service' => $request->atm_service,
                    'atm_province' => $request->atm_province,
                    'district' => $request->atm_provincelist,
                ];
                self::getLinkATM($data_atm, $moderation, $date, 5, $request->category_item, $request->avatar, $bank_type);
            }
        } else {
            $file = $request->fileExcel;
            $total = 0;
            session()->forget('total_import_check');
            session()->forget('str_test');
            session()->put('total_import_check', $total);
            $str_test = "";
            session()->put('str_test', $str_test);
            \Excel::selectSheetsByIndex(0)->filter('chunk')->load($file->getRealPath())->chunk(1, function ($results) use ($moderation, $site, $date) {
                foreach ($results as $key => $value) {
                    if (!empty($value->link)) {
                        $total = session()->get('total_import_check');
                        $str_test = session()->get('str_test');

                        if ($total > 0 && $total % 30 == 0) {
                            sleep(120);
                            $str_test .= "===> " . $total;
                            session()->put('str_test', $str_test);
                        }
                        $content = new ContentController();
                        if ($site == 'vinalo') {
                            $content->getDataVinalo($results, $moderation, $date);
                        } elseif ($site === 'sheis') {
                            $content->getDataSheIs($results, $moderation, $date);
                        } elseif ($site == 'replicate_foody') {
                            $content->getReplicateFoody($results, $moderation, $date);
                        } elseif ($site == 'offpeak') {
                            $content->getDataOffpeak($results, $moderation, $date);
                        } else {
                            $content->getDataFoody($results, $moderation, $date);
                        }
                        $total++;
                        session()->put('total_import_check', $total);
                    }
                }
            });
            if (file_exists(public_path('mytour.html'))) {
                unlink(public_path('mytour.html'));
            }
        }

        return redirect()->route('insert_content', ['site' => $site])->with([
                    'errorInsert' => session()->get('errorInsert'),
                    'successInsert' => 'Insert data thành công',
        ]);
    }

    public function save_content_by_site($data) {
        $rules = [
            'name' => 'required|unique:contents,name',
            'alias' => 'required|unique:contents,alias',
            'id_category' => 'required',
            'category_item' => 'required',
            'price_from' => 'required',
            'price_to' => 'required',
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

            if ($object->avatar) {
                $path = public_path() . '/upload/img_content/';
                $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
                if (!\File::exists($path)) {
                    \File::makeDirectory($path, $mode = 0777, true, true);
                }
                if (!\File::exists($path_thumbnail)) {
                    \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
                }

                $img_name = time() . '_avatar_' . str_random(13) . '.jpeg';

                if (isset($object->site)) {
                    self::waterMarkAvatar($object->avatar, $img_name, $path, $path_thumbnail);
                } else {
                    self::waterMarkAvatar($object->avatar, $img_name, $path, $path_thumbnail, 'import');
                }
                $content_avatar = '/upload/img_content/' . $img_name;
            }

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
                        'avatar' => $content_avatar,
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
                        'date_to' => 0,
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

    public function getInsertContent($site) {
        if ($site == 'vietbando' || $site == 'mytour') {
            $list_category = Category::where('active', '=', 1)->pluck('name', 'id')->toArray();

            return view('Admin.content.import_by_site', ['site' => $site, 'list_category' => $list_category]);
        } elseif ($site == 'bank') {
            $list_category = CategoryItem::where([['category_id', '=', 5], ['active', '=', '1']])->pluck('name', 'id');
            return view('Admin.content.import_all_bank', ['site' => $site, 'list_category' => $list_category]);
        } else {
            return view('Admin.content.import_by_site', ['site' => $site]);
        }
    }

    function curlGetLink($link, $page = '') {
        $ch = curl_init($link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:19.0) Gecko/20100101 Firefox/19.0'));
        $content = curl_exec($ch);
        curl_close($ch);
        $d = new \DOMDocument();
        @$d->loadHTML($content);
        if ($page === 'mytour') {
            $path = public_path() . '/mytour.html';
            $d->save($path);
            libxml_use_internal_errors(true);
            $d->loadHTMLFile($path);
            libxml_clear_errors();
        }
        return $d;
    }

    public function curlPost($code_img) {
        // set post fields
        $post = [
            'img' => '10244',
            'id' => $code_img,
        ];

        $ch = curl_init('https://vinalo.com/loadh/albumdd');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:19.0) Gecko/20100101 Firefox/19.0'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // execute!
        $response = curl_exec($ch);
        echo '<script type="text/javascript" src="https://vinalo.com/vina/vina.js"></script>';
        print_r($response);


        $post2 = [
            'number' => 15,
            'offset' => 0,
            'id' => $code_img
        ];
        $ch = curl_init('https://vinalo.com/loadh/albumhinhdd');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:19.0) Gecko/20100101 Firefox/19.0'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post2);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        $res = curl_exec($ch);
        $data = "<div class='king_test'>" . $res . "</div>";
        curl_close($ch);
        $d = new \DOMDocument();
        @$d->loadHTML($data);
        return $d;
    }

    function curlPostMytour($data) {
        $post = [
            'date_checking' => date('d/m/Y'),
            'date_checkout' => date('d/m/Y'),
            'id_hotel' => $data['id_hotel'],
            'num_room' => 1,
            'num_person' => 1,
            '_token' => $data['_token'],
            'show_price_email' => 0
        ];

        $ch = curl_init('https://mytour.vn/hotel/get-price-detail-page');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:19.0) Gecko/20100101 Firefox/19.0'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // execute!
        $response = curl_exec($ch);
        curl_close($ch);
        $d = new \DOMDocument();
        @$d->loadHTML($response);
        return $d;
    }

    function curlGetVietbanDo($link) {
        $ch = curl_init($link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:19.0) Gecko/20100101 Firefox/19.0',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36'));
        $response = curl_exec($ch);
        curl_close($ch);
        $d = new \DOMDocument();
        @$d->loadHTML($response);
        return $d;
    }

    function curlGetFoodLink($link) {
        $ch = curl_init($link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        // curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
        // curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            trans('global.locations').'-Type: application/json',
            'X-Requested-With: XMLHttpRequest',
            'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:19.0) Gecko/20100101 Firefox/19.0',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36'
        ));
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }

    public function getUpdateLocationFoody() {
        return view('Admin.content.update_site');
    }

    public function postUpdateLocationFoody(Request $request) {
        ini_set('max_execution_time', 0);
        $file = $request->fileExcel;
        $total = 0;
        session()->put('total_update', $total);

        \Excel::selectSheetsByIndex(0)->filter('chunk')->load($file->getRealPath())->chunk(1, function ($results) {
            foreach ($results as $key => $value) {
                if (!empty($value->link)) {
                    $total = session()->get('total_update');
                    $content = new ContentController();
                    if (!empty($results)) {
                        $content->test($results);
                    }

                    if ($total > 0 && $total % 10 == 0) {
                        sleep(120);
                    }

                    $total++;
                    session()->put('total_update', $total);
                } else {
                    break;
                }
            }
        });
    }

    public function test($results) {
        foreach ($results as $link) {
            if ($link['link'] != null) {
                $data = [];
                try {
                    // get data from foody
                    $html = self::curlGetLink($link['link']);
                    $html_result = new \DOMXPath($html);

                    $defaultLatgitude = $html_result->query('//meta[@itemprop="latitude"]')->item(0)->attributes->item(1)->nodeValue;
                    $defaultLongitude = $html_result->query('//meta[@itemprop="longitude"]')->item(0)->attributes->item(1)->nodeValue;
                    $geo = $defaultLatgitude . ',' . $defaultLongitude;

                    $name = $html_result->query('//h1[@itemprop="name"]')->item(0)->nodeValue;
                    $alias = str_slug($name); //alias

                    $content = Content::where('alias', '=', $alias)->first();
                    if ($content) {
                        $id = $html_result->query('//div[@class="report-microsite btn-report-microsite"]')->item(0)->attributes->item(1)->nodeValue;
                        date_default_timezone_set('Asia/Ho_Chi_Minh');
                        $date = new \DateTime();
                        $link_test = 'https://www.foody.vn/__get/Delivery/GetDeliveryDishes?restaurantId=' . $id . '&requestCount=100&nextItemId=0&sortType=2&t=' . $date->getTimestamp();

                        $json = self::curlGetFoodLink($link_test);
                        $tags = [];
                        if (!empty($json)) {
                            $json_paser = json_decode($json, true);

                            foreach ($json_paser['Dishes']['Items'] as $item) {
                                $tags[] = $item['Name'];
                            }
                        }

                        $quan = $content->_district->name;
                        $city = $content->_city->name;
                        $tag = $content->name . ',' . $content->name . ' ' . $content->address . ',' . $content->name . ' ' . $quan . ',' . $content->name . ' ' . $city;

                        if (!empty($tags)) {
                            $tag .= ',' . implode(',', $tags);
                        }

                        $list_id_category_item = CategoryContent::where('id_content', '=', $content->id)->pluck('id_category_item')->toArray();
                        foreach ($list_id_category_item as $id_cate_item) {
                            $name_category = CategoryItem::find($id_cate_item)->name;
                            $name_category = app('translator')->getFromJson($name_category);
                            $tag .= ',' . $name_category . ' ' . $content->address . ',' . $name_category . ' ' . $quan . ',' . $name_category . ' ' . $city;
                        }

                        \DB::table('contents')->where('id', '=', $content->id)
                                ->update([
                                    'tag' => $tag,
                                    'lat' => $defaultLatgitude,
                                    'lng' => $defaultLongitude,
                        ]);
                    }
                } catch (\Exception $exc) {
                    echo $exc->getMessage();
                }
            }
        }
    }

    function curlPostListMytour($data) {

        $post = [
            'param' => $data['params'],
            'currentLocation' => $data['currentLocation'],
            'breadcrumb' => $data['treeBreadcrumb'],
            '_token' => $data['token'], // $data['token']
            'page' => $data['page'],
            'rate' => $data['rate'],
            '_check_room' => 1,
            'list_conveniences' => "",
            'router' => 'hotel-listing'
        ];
        $ch = curl_init('https://mytour.vn/load-listing-price');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:19.0) Gecko/20100101 Firefox/19.0'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // execute!
        $response = curl_exec($ch);
        curl_close($ch);
        $d = new \DOMDocument();
        @$d->loadHTML($response);
        return $d;
    }

    public function postAjaxAtm(Request $request) {
        $id = $request->value;
        $type = $request->type;
        if ($type == 'atm_card') {
            $data = self::curlGetLink('https://vayvontieudung.com.vn/index2.php?com=banks&ctr=banks&act=getcard&view=getcard&bankid=' . $id);
            $html_bank_result = new \DOMXPath($data);

            $branch_bank = $html_bank_result->query('//select[@name="atm_card"]/option');
            $option = '';
            foreach ($branch_bank as $item) {
                $option .= '<option value="' . $item->attributes->item(0)->nodeValue . '">' . utf8_decode($item->nodeValue) . '</option>';
            }
        } else {
            $data = self::curlGetLink('https://vayvontieudung.com.vn/index2.php?com=location&ctr=location&act=getdistrict&view=getdistrict&provinceid=' . $id);
            $html_bank_result = new \DOMXPath($data);

            $branch_bank = $html_bank_result->query('//select[@name="district"]/option');
            $option = '';
            foreach ($branch_bank as $item) {
                $option .= '<option value="' . $item->attributes->item(0)->nodeValue . '">' . utf8_decode($item->nodeValue) . '</option>';
            }
        }

        echo $option;
    }

    public function getLinkATM($data_atm, $moderation, $date, $id_category, $category_item, $avatar, $bank_type) {
        $data = [];

        $data_params = [
            'com' => 'banks',
            'ctr' => 'atm',
            'act' => 'list_atm',
            'bankid' => $data_atm['bankid'],
            'atm_card' => $data_atm['atm_card'],
            'atm_service' => $data_atm['atm_service'],
            'atm_province' => $data_atm['atm_province'],
            'district' => 0,
        ];

        $list_html_result = self::curlPostAtm($data_params);
        $list_result = new \DOMXPath($list_html_result);
        $listAtms = $list_result->query('//table[@class="search_result_color"]/tr');

        foreach ($listAtms as $k => $listAtm) {
            if ($k >= 1) {
                $address = utf8_decode(trim($listAtm->childNodes->item(0)->childNodes->item(3)->nodeValue));
                $str = $listAtm->childNodes->item(0)->childNodes->item(3)->attributes->item(1)->nodeValue;

                $address = str_replace(', Q. 1', '', $address);
                $address = str_replace('Q.1', '', $address);
                $address = str_replace('Q. 1', '', $address);
                $adds = explode('Q.', trim($address));
                $address = rtrim($adds[0], ', ');
                $data['address'] = str_replace(',', '', $address);

                $data['cty'] = $data_atm['city'];

                $name_bank = explode('-', $data_atm['name']);
                $data['name'] = 'ATM - ' . trim($name_bank[0]) . ' - ' . $data['address'];
                $str = str_replace('return loadATM(', '', $str);
                $str = str_replace(')', '', $str);
                $str = str_replace("'", '', $str);
                $value_arr = explode(',', $str);
                $data['geo'] = $value_arr[0] . ',' . $value_arr[1];
                $key = explode('-', $data['name']);
                $data['quan'] = self::getQuanFormLatLng($value_arr[0], $value_arr[1]);
                $data['keyword'] = $key[0] . ', ' . '' . $key[1] . ', ' . $key[0] . ' ' . $data['address'] . ', ' . $key[0] . ' ' . $data['quan'] . ', ' . $key[0] . ' ' . $data['cty'] . ', '
                        . $key[1] . ' ' . $data['address'] . ', ' . $key[1] . ' ' . $data['quan'] . ', ' . $key[1] . ' ' . $data['cty'];

                $value = [];
                if (!empty($data)) {
                    $value['name'] = $data['name']; //name
                    $value['alias'] = str_slug($value['name']); //alias
                    $value['id_category'] = $id_category;
                    $value['category_item'][] = $category_item;
                    $value['country'] = 1;
                    if ($data['cty'] == 'TP.HCM') {
                        $value['city'] = 1;
                    } else {
                        $city = City::select('cities.*')
                                        ->selectRaw("MATCH(`name`) AGAINST ('" . $data['cty'] . " \"" . $data['cty'] . "\"' in boolean mode) as math_score")
                                        ->whereRaw("MATCH(`name`) AGAINST ('" . $data['cty'] . " \"" . $data['cty'] . "\"' in boolean mode) >1")
                                        ->orderBy('math_score', 'desc')
                                        ->orwhere('name', 'like', '%' . $data['cty'] . '%')->first()->id;
                        if (isset($city)) {
                            $value['city'] = $city;
                        }
                    }

                    if (isset($value['city'])) {
                        $district = District::select('districts.*')
                                        ->selectRaw("MATCH(`name`) AGAINST ('" . $data['quan'] . " \"" . $data['quan'] . "\"' in boolean mode) as math_score")
                                        ->whereRaw("MATCH(`name`) AGAINST ('" . $data['quan'] . " \"" . $data['quan'] . "\"' in boolean mode) > 0")
                                        ->orwhere('name', 'like', '%' . $data['quan'] . '%')
                                        ->where('id_city', '=', $value['city'])
                                        ->orderBy('math_score', 'desc')
                                        ->first()->id;
                        if (isset($district)) {
                            $value['district'] = $district;
                        }
                    }

                    $value['address'] = trim($data['address']);
                    $value['lat'] = explode(',', $data['geo'])[0];
                    $value['lng'] = explode(',', $data['geo'])[1];
                    $value['avatar'] = $avatar;
                    $value['tag'] = isset($data['keyword']) ? $data['keyword'] : '';
                    $value['moderation'] = $moderation;
                    $value['date'] = $date;
                    $value['extra_type'] = $bank_type;
                    $value['site'] = 'bank';

                    self::save_content_by_site($value);
                }
            }
        }
    }

    function curlPostAtm($data) {
        $post = [
            'com' => 'banks',
            'ctr' => 'atm',
            'act' => 'list_atm',
            'arg[bankid]' => $data['bankid'],
            'arg[atm_card]' => $data['atm_card'],
            'arg[atm_service]' => $data['atm_service'],
            'arg[atm_province]' => $data['atm_province'],
            'arg[district]' => 0,
        ];
        $ch = curl_init('https://vayvontieudung.com.vn/index1.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // execute!
        $response = curl_exec($ch);

        curl_close($ch);
        $d = new \DOMDocument();
        @$d->loadHTML($response);
        return $d;
    }

    public function getQuanFormLatLng($lat, $lng) {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=false";

        try {
            $data = file_get_contents($url);

            $jsondata = json_decode($data, true);
            if ($jsondata['status'] == 'OK') {
                foreach ($jsondata['results'][0]['address_components'] as $value) {
                    if ($value['types'][0] == 'administrative_area_level_2') {
                        return $value['short_name'];
                    }
                }
            }
        } catch (\Exception $ex) {
            return null;
        }
    }

    public function getChangeOwner($id) {
        $content = Content::find($id);
        if (!$content) {
            return redirect()->route('list_content');
        }
        $role_user = RoleUser::where('user_id', '=', Auth::guard('web')->user()->id)->pluck('role_id')->first();
        if ($role_user < 4 || Auth::guard('web')->user()->id == $content->created_by) {
            $client = Client::where('active', '=', 1)->get();
            return view('Admin.content.change_owner', ['content' => $content, 'client' => $client]);
        } else {
            return redirect()->route('list_content');
        }
    }

    public function postChangeOwner(Request $request, $id) {
        $content = Content::find($id);
        if (!$content) {
            return redirect()->route('list_content');
        }
        if($request->change_owner){
            $content->created_by = $request->change_owner;
            $content->type_user = 0;
            $content->confirm = 1;
        }else{
            $content->created_by = 1;
            $content->type_user = 1;
            $content->confirm = 1;
        }
        
        $content->save();
        
        //create_branch($content->id);
        return redirect()->route('update_content', ['category_type' => $content->_category_type->machine_name, 'id' => $content->id]);
    }

    public function getDataOffpeak($link_offpeak, $moderation, $date_insert) {
        foreach ($link_offpeak as $link) {

            $data = [];
            try {
                // get data from foody
                if ($link['link'] != 'NULL') {
                    $html_foody = self::curlGetLink($link['link']);
                    $html_result_foody = new \DOMXPath($html_foody);
                    $defaultLatgitude = $html_result_foody->query('//meta[@itemprop="latitude"]')->item(0)->attributes->item(1)->nodeValue;
                    $defaultLongitude = $html_result_foody->query('//meta[@itemprop="longitude"]')->item(0)->attributes->item(1)->nodeValue;
                    $data['geo_foody'] = $defaultLatgitude . ',' . $defaultLongitude;
                    $data['name_foody'] = $html_result_foody->query('//h1[@itemprop="name"]')->item(0)->nodeValue;
                }

                // get data from Offpeak
                $html = self::curlGetLink($link['update']);
                $html_result = new \DOMXPath($html);
                $geo = $html_result->query('//script[@type="application/ld+json"]')->item(0)->nodeValue;
                $json_paser = json_decode($geo, true);
                $data['name'] = utf8_decode($json_paser['name']);
                $data['avatar'] = $json_paser['image'];
       // $data['address'] = utf8_decode($json_paser['address']['streetAddress']);
                $add = explode(',', utf8_decode($json_paser['address']['streetAddress']));
                $nun = count($add) - 1;
                unset($add[$nun]);
                $data['address'] = implode(',', $add);
                $data['quan'] = utf8_decode($json_paser['address']['addressLocality']);
                $data['cty'] = utf8_decode($json_paser['address']['addressRegion']);
                $data['country'] = utf8_decode($json_paser['address']['addressCountry']);
                $data['phone'] = $json_paser['telephone'];
                $data['open'] = $html_result->query('//div[@class="item-list"]/div[@class="col-sm-6 col-md-6"]/span[@class="item-time"]/p')->item(0)->nodeValue;

                $img_menu = $html_result->query('//div[@id="tab-product-list"]/div[@id="gallery"]/div[@class="row"]/div[@class="col-xs-12 col-sm-4 col-md-4 col-lg-4"]/div[@id="gallery-listing"]/div[@class="img"]/img');
                foreach ($img_menu as $menu) {
                    $data['menu'][] = $menu->attributes->item(1)->nodeValue;
                }
                $img_space = $html_result->query('//div[@id="carousel-example-generic"]/div[@class="carousel-inner"]')->item(0)->childNodes;
                foreach ($img_space as $space) {
                    if ($space->nodeName != '#text' && $space->nodeValue != '') {
                        $data['space'][] = $space->childNodes->item(1)->attributes->item(3)->nodeValue;
                    }
                }
                $data['service'] = ['Có wifi', 'Máy lạnh & điều hòa', 'Có đặt trước', 'Giữ xe máy miễn phí'];
                $data['geo'] = $json_paser['geo']['latitude'] . ',' . $json_paser['geo']['longitude'];
                $data['keywords'] = $data['name'] . ', ' . $data['name'] . ' ' . $data['address'] . ', ' . $data['name'] . ' ' . $data['quan'] . ',' . $data['name'] . ' ' . $data['cty'];

                //////////////////////////////////////////////////////////////////////////////////////////////////////
                /// check data
                /// //////////////////////////////////
                if (isset($data['name_foody'])) {
                    $content = Content::where('alias', '=', str_slug($data['name_foody']))->first();
                }
                if (isset($content)) {

                    \DB::table('contents')->where('id', '=', $content->id)
                            ->update([
                                'phone' => isset($data['phone']) ? $data['phone'] : '',
                    ]);

                    if (isset($data['space'])) {
                        $path = public_path() . '/upload/img_content/';
                        $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
                        if (!\File::exists($path)) {
                            \File::makeDirectory($path, $mode = 0777, true, true);
                        }
                        if (!\File::exists($path_thumbnail)) {
                            \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
                        }
                        foreach ($data['space'] as $file) {

                            $img_name = time() . '_space_' . str_random(13) . '.jpeg';

                            try {
                                self::waterMark($file, $img_name, $path, $path_thumbnail, 'import');

                                $image_space = '/upload/img_content/' . $img_name;

                                ImageSpace::create([
                                    'id_content' => $content->id,
                                    'name' => $image_space,
                                ]);
                            } catch (\Exception $e) {
                                echo 'Message: ' . $e->getMessage();
                            }
                        }
                    }

                    if (isset($data['menu'])) {
                        $path = public_path() . '/upload/img_content/';
                        $path_thumbnail = public_path() . '/upload/img_content_thumbnail/';
                        if (!\File::exists($path)) {
                            \File::makeDirectory($path, $mode = 0777, true, true);
                        }
                        if (!\File::exists($path_thumbnail)) {
                            \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
                        }

                        foreach ($data['menu'] as $file) {

                            $img_name = time() . '_menu_' . str_random(13) . '.jpeg';

                            try {
                                self::waterMark($file, $img_name, $path, $path_thumbnail, 'import');

                                $image_menu = '/upload/img_content/' . $img_name;

                                ImageMenu::create([
                                    'id_content' => $content->id,
                                    'name' => $image_menu,
                                ]);
                            } catch (\Exception $e) {
                                echo 'Message: ' . $e->getMessage();
                            }
                        }
                    }
                } else {
                    $value = [];
                    $value['name'] = $data['name']; //name
                    $value['alias'] = str_slug($value['name']); //alias
                    $value['id_category'] = Category::where('name', '=', $link['categorie'])->pluck('id')->first();

                    if (isset($data['open'])) {
                        $count = count(explode('-', $data['open']));
                        if ($count == 2) {
                            $value['open_from'] = trim(explode('-', $data['open'])[0]);
                            $value['open_to'] = trim(explode('-', $data['open'])[1]);
                        } else {
                            $value['open_from'] = '00:00:00';
                            $value['open_to'] = '00:00:00';
                        }
                    } else {
                        $value['open_from'] = '00:00:00';
                        $value['open_to'] = '00:00:00';
                    }

                    $value['price_from'] = 0;
                    $value['price_to'] = 0;
                    $value['phone'] = isset($data['phone']) ? $data['phone'] : '';

                    if (isset($data['service'])) {
                        $query = '';
                        foreach ($data['service'] as $key2 => $value2) {
                            if ($key2 == 0) {
                                $query .= ' `name` like "%' . trim($value2) . '%" ';
                            } else {
                                $query .= ' or `name` like "%' . trim($value2) . '%" ';
                            }
                        }
                        if ($query) {
                            $service = ServiceItem::whereRaw($query)->pluck('id')->toArray();
                            $service = CategoryService::where('id_category', '=', $value['id_category'])->whereIn('id_service_item', $service)->pluck('id_service_item')->toArray();
                        }
                        $value['service'] = $service;
                    }

                    $value['country'] = 1;

                    $city = City::select('cities.*')
                                    ->selectRaw("MATCH(`name`) AGAINST ('" . $data['cty'] . " \"" . $data['cty'] . "\"' in boolean mode) as math_score")
                                    ->whereRaw("MATCH(`name`) AGAINST ('" . $data['cty'] . " \"" . $data['cty'] . "\"' in boolean mode) >1")
                                    ->orderBy('math_score', 'desc')
                                    ->orwhere('name', 'like', '%' . $data['cty'] . '%')->first()->id;
                    if (isset($city)) {
                        $value['city'] = $city;
                    }

                    if (isset($value['city'])) {
                        $district = District::select('districts.*')
                                        ->selectRaw("MATCH(`name`) AGAINST ('" . $data['quan'] . " \"" . $data['quan'] . "\"' in boolean mode) as math_score")
                                        ->whereRaw("MATCH(`name`) AGAINST ('" . $data['quan'] . " \"" . $data['quan'] . "\"' in boolean mode) > 0")
                                        ->orwhere('name', 'like', '%' . $data['quan'] . '%')
                                        ->where('id_city', '=', $value['city'])
                                        ->orderBy('math_score', 'desc')
                                        ->first()->id;
                        if (isset($district)) {
                            $value['district'] = $district;
                        }
                    }


                    $value['address'] = $data['address'];

                    $value['lat'] = explode(',', $data['geo'])[0];
                    $value['lng'] = explode(',', $data['geo'])[1];

                    $value['avatar'] = $data['avatar'];

                    $tag = $value['name'] . ',' . $value['name'] . ' ' . $value['address'] . ',' . $value['name'] . ' ' . $data['quan'] . ',' . $value['name'] . ' ' . $data['cty'];

                    if (!empty($tags)) {
                        $tag .= ',' . implode(',', $tags);
                    }

                    $list_category_item = explode(',', $link['categorie_items']);
                    foreach ($list_category_item as $category) {
                        $check_category_item = CategoryItem::where('name', '=', $category)->where('category_id', '=', $value['id_category'])->pluck('id')->first();

                        if ($check_category_item != null) {
                            $tag .= ',' . $category . ' ' . $value['address'] . ',' . $category . ' ' . $data['quan'] . ',' . $category . ' ' . $data['cty'];
                            $value['category_item'][] = $check_category_item;
                        }
                    }

                    $value['tag'] = $tag;
                    $value['moderation'] = $moderation;
                    $value['link'] = $link['link'];
                    $value['image_space'] = $data['space'];
                    $value['image_menu'] = $data['menu'];
                    $value['date'] = $date_insert;
                    self::save_content_by_site($value);
                }
            } catch (\Exception $e) {
                $err = $link['link'] . ' - lỗi DATA <br>';
                $mess_err = session()->get('errorInsert');
                $mess_err .= $err;
                session()->flash('errorInsert', $mess_err);
                continue;
            }
        }
    }

    public function updateLike(Request $request) {
        $arrReturn = [
            'error'=>1,
            'message'=>''
        ];
        $id = $request->id;
        $like = (int) $request->like;
        $content = Content::find($id);
        if (!$content) {
            $arrReturn['message'] = trans('global.locations').' not found';
        }
        $content->like = $like;
        if($content->save()){
            $arrReturn['error'] = 0;
        }
        return response($arrReturn);
    }

    public function getUpdateLocation(){
        $list_category = Category::all();
        $list_country = Country::all();
        // session()->flash('status','123');
        return view('Admin.content.update_location', [
            'list_category' => $list_category,
            'list_country' => $list_country
        ]);
    }

    public function checkUpdateLocation(Request $request){
        $all_content = Content::select('contents.id as id','contents.name as text');
        $input = $request->all();
        if ($request->category) {
            $all_content->where('contents.id_category', '=', $request->category);
        }
        if ($request->category_item) {
            $all_content->leftJoin('category_content', 'contents.id', '=', 'category_content.id_content')
                    ->where('category_content.id_category_item', '=', $request->category_item);
            $category_item = $request->category_item;
        } else {
            $category_item = '';
        }
        if ($request->country) {
            $all_content->where('contents.country', '=', $request->country);
            $country = $request->country;
        } else {
            $country = '';
        }
        if ($request->city) {
            $all_content->where('contents.city', '=', $request->city);
            $city = $request->city;
        } else {
            $city = '';
        }
        if ($request->district) {
            $all_content->where('contents.district', '=', $request->district);
            $district = $request->district;
        } else {
            $district = '';
        }
        $count = 0;
        $contents = [];
        $count = $all_content->count();
        if($count<=1000){
            $contents = $all_content->get();
        }
        return [
            'count' => $count,
            'contents' => $contents,
        ];
    }


    public function postUpdateLocation(Request $request){
        ini_set('max_execution_time', 0);
        $all_content = Content::select('contents.*');
        $input = $request->all();
        if ($request->category) {
            $all_content->where('contents.id_category', '=', $request->category);
        }
        if ($request->category_item) {
            $all_content->leftJoin('category_content', 'contents.id', '=', 'category_content.id_content')
                    ->where('category_content.id_category_item', '=', $request->category_item);
            $category_item = $request->category_item;
        } else {
            $category_item = '';
        }
        if ($request->country) {
            $all_content->where('contents.country', '=', $request->country);
            $country = $request->country;
        } else {
            $country = '';
        }
        if ($request->city) {
            $all_content->where('contents.city', '=', $request->city);
            $city = $request->city;
        } else {
            $city = '';
        }
        if ($request->district) {
            $all_content->where('contents.district', '=', $request->district);
            $district = $request->district;
        } else {
            $district = '';
        }
        $list_content = $all_content->get();
        $total = 0;
        $amount = $all_content->count();
        foreach ($list_content as $key => $content) {
            $lat = $content->lat;
            $lng = $content->lng;
            $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&language=vi";
            $data = @file_get_contents($url);
            $jsondata = json_decode($data,true);
            $location = array();
            $index=0;
            if(isset($jsondata['results']['0'])){
                // foreach($jsondata['results']['0']['address_components'] as $element){
                //     $location[ implode(' ',$element['types']) ] = $element['long_name'];
                // }
                while(
                    !isset($location['country political']) ||
                    !isset($location['administrative_area_level_1 political']) ||
                    !isset($location['administrative_area_level_2 political'])
                ){
                    foreach($jsondata['results'][$index]['address_components'] as $element){
                        $location[ implode(' ',$element['types']) ] = $element['long_name'];
                    }
                    $index++;
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
                    $content->country = $country->id;
                }
                if($city){
                    $content->city = $city->id;
                }
                if($district){
                    $content->district = $district->id;
                }
                $content->type_user_update = 1;
                $content->updated_by = Auth::guard('web')->user()->id;
                $content->save();
                $total++;
                sleep(1);
            }
        }

        return redirect()->route('update_location')->with(['status' => 'Update ' . $total . '/'.$amount.' content '.trans('valid.updated_successful') . "<br/>"])->withInput();
    }

    public function getUpdateTag(){
        $list_category = Category::all();
        $list_country = Country::all();
        // session()->flash('status','123');
        return view('Admin.content.update_tag', [
            'list_category' => $list_category,
            'list_country' => $list_country
        ]);
    }

    public function postUpdateTag(Request $request){
        // dd($request->all());
        ini_set('max_execution_time', 0);
        $all_content = Content::select('contents.*')
                              ->with('_category_items')
                              ->with('_category_type')
                              ->with('_district')
                              ->with('_city')
                              ->with('_country');
        $input = $request->all();
        if($request->locations){
            $all_content->whereIn('contents.id', $request->locations);
        }else{
            if ($request->category) {
                $all_content->where('contents.id_category', '=', $request->category);
            }
            if ($request->category_item) {
                $all_content->leftJoin('category_content', 'contents.id', '=', 'category_content.id_content')
                        ->where('category_content.id_category_item', '=', $request->category_item);
                $category_item = $request->category_item;
            } else {
                $category_item = '';
            }
            if ($request->country) {
                $all_content->where('contents.country', '=', $request->country);
                $country = $request->country;
            } else {
                $country = '';
            }
            if ($request->city) {
                $all_content->where('contents.city', '=', $request->city);
                $city = $request->city;
            } else {
                $city = '';
            }
            if ($request->district) {
                $all_content->where('contents.district', '=', $request->district);
                $district = $request->district;
            } else {
                $district = '';
            }
        }

        $list_content = $all_content->get();
        $total = 0;
        $amount = $all_content->count();
        foreach ($list_content as $key => $content) {
            $new_tag = '';
            if(isset($request->option['name'])){
                $new_tag .= $content->name.',';
            }

            if(isset($request->option['category'])){
                $new_tag .= $content->_category_type->name.',';
            }

            if(isset($request->option['category_item'])){
                foreach ($content->_category_items as $key => $item) {
                    $new_tag .= $item->name.',';
                }
            }
            if(isset($request->option['address'])){
                $new_tag .= $content->address.',';
            }

            if(isset($request->option['district'])){
                $new_tag .= $content->_district->name.',';
            }

            if(isset($request->option['city'])){
                $new_tag .= $content->_city->name.',';
            }

            if(isset($request->option['country'])){
                $new_tag .= $content->_country->name.',';
            }

            if($request->tag_more){
                $new_tag .= $request->tag_more.',';
            }

            $new_tag = trim($new_tag,',');
            $new_tag = trim($new_tag,',');

            $content->tag = $new_tag;
            $content->type_user_update = 1;
            $content->updated_by = Auth::guard('web')->user()->id;
            if($content->save()){
                $total++;
            }
        }

        return redirect()->route('update_tag')->with(['status' => 'Update ' . $total . '/'.$amount.' content '.trans('valid.updated_successful') . "<br/>"])->withInput();
    }


    public function getUpdateCategoryMore($id_category,$id_category_item){
        $list_id_content = Content::where('id_category',$id_category)->pluck('id');
        foreach ($list_id_content as $key => $id_content) {
            CategoryContent::where('id_content',$id_content)
                           ->where('id_category_item',$id_category_item)
                           ->delete();
            CategoryContent::create([
                'id_content' => $id_content,
                'id_category_item' => $id_category_item,
            ]);
            create_tag_search($id_content);
        }
        echo "Done";
    }

    public function getChangeOwnerNew() {
        if (Auth::guard('web')->user()->hasRole('super_admin') || Auth::guard('web')->user()->hasRole('admin')) {
            return view('Admin.content.change_owner_new');
        } else {
            return redirect()->route('list_content');
        }
    }

     public function postChangeOwnerNew(Request $request) {
        $rules = [
            'content' => 'required',
            'user'=>'required'
        ];
        $messages = [
            'content.required' => trans('valid.content_required'),
            'user.required' => trans('valid.user_required')
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $contents = Content::whereIn('id',$request->content)->get();
            $status = trans('global.locations').': ';
            $user = Client::find($request->user);
            foreach ($contents as $key => $content) {
                $content->created_by = $request->user;
                $content->type_user = 0;
                $content->save();
                if(!check_update_content($content->id)){
                    $notifi = new Notifi();
                    $link_content =LOCATION_URL.'/edit/location/'.$content->id;
                    $text_content = trans('Admin'.DS.'content.noti_update_content', [ 'content' => $content->name ]);
                    $notifi->createNotifiUserByTemplate($text_content,$content->created_by,['content' => $content->name, 'content_id' => $content->id],$link_content);
                }
                if($key==0)
                    $status.=$content->name;
                else
                    $status.=', '.$content->name;
            }
            $mail_template_to = EmailTemplate::where('machine_name', 'change_owner')->first();
            if($mail_template_to)
            {
              $data = [
                'to_full_name' => $user->full_name,
                'content_name' => $status,
                'from_full_name' => 'KINGMAP',
                'link_manage' => LOCATION_URL.'/user/'.$user->id.'/management-location',
                'link_content' => $link_content
              ];
              Mail::send([], [], function($message) use ($mail_template_to, $data,$user)
              {
                $message->to($user->email, $user->full_name)
                  ->subject($mail_template_to['subject'])
                  ->from('kingmapteam@gmail.com', 'KingMap Team')
                  ->setBody($mail_template_to->parse($data),'text/html');
              });
            }
            return redirect(route('change_owner'))->with(['status'=>trans('valid.change_owner_to',['status'=>$status, 'to_user'=>$user->full_name])]);
        }
    }

    public function getInfoVideo($url){
        $url = urldecode(rawurldecode($url));
        $result = array();
        if (strpos($url, 'facebook.com') > 0) {
            preg_match("~/videos/(?:t\.\d+/)?(\d+)~i", $url, $matches);
            if(isset($matches[1])){
                $id = $matches[1];
                $result = $this->getInfoFacebook($id);
            }else{
                $regex = '/videos\/[a-zA-Z0-9\.]+\/(?:t\.\d+\/)?(\d+)/';
                preg_match($regex, $url, $matches);
                $id = $matches[1];
                $result = $this->getInfoFacebook($id);
            }
        }elseif (strpos($url, 'youtube.com') || strpos($url,"youtu.be")){
            preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);
            if(isset($matches[1])){
                $id = $matches[1];
                $result = $this->getInfoYoutube($id);
            } 
        }
        return $result;
    }

    function getInfoYoutube($id){

        $link = 'https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails,player&id='.$id.'&key=AIzaSyA4_lZ8uw0hpJfJxVHnK_vBBXZckA-0Tr0';
        $description = $this->curlLink($link);
        $data = [];
        if(isset($description['items'][0])){
            $duration = new \DateInterval($description['items'][0]['contentDetails']['duration']);
            $thumbnail = '';

            if(isset($description['items'][0]['snippet']['thumbnails']['maxres'])){
                $thumbnail = $description['items'][0]['snippet']['thumbnails']['maxres']['url'];
            }else if (isset($description['items'][0]['snippet']['thumbnails']['standard'])){
                $thumbnail = $description['items'][0]['snippet']['thumbnails']['standard']['url'];
            }else if (isset($description['items'][0]['snippet']['thumbnails']['high'])){
                $thumbnail = $description['items'][0]['snippet']['thumbnails']['high']['url'];
            }else if (isset($description['items'][0]['snippet']['thumbnails']['medium'])){
                $thumbnail = $description['items'][0]['snippet']['thumbnails']['medium']['url'];
            }else if (isset($description['items'][0]['snippet']['thumbnails']['default'])){
                $thumbnail = $description['items'][0]['snippet']['thumbnails']['default']['url'];
            }


            $data = array(
                'type' => 'youtube',
                'link' => 'http://youtube.com/'.$id,
                'time'=>$duration->format('%H:%I:%S'),
                'title'=>$description['items'][0]['snippet']['title'],
                'id_video'=>$id,
                'thumbnail'=> $thumbnail,
                'player' => $description['items'][0]['player']['embedHtml']
            );
        }

        return $data;

    }

    function getInfoFacebook($id){
        $client_id = Setting::where('key','client_id_facebook')->first();
        $client_secret = Setting::where('key','client_secret_facebook')->first();
        $link_access_token = "https://graph.facebook.com/oauth/access_token?client_id=".$client_id->value."&client_secret=".$client_secret->value."&grant_type=client_credentials";
        $access_token = $this->curlLink($link_access_token);
        $link = 'https://graph.facebook.com/'.$id.'?fields=title,length,format,permalink_url&access_token='.$access_token['access_token'];
        $description = $this->curlLink($link);
        $title = isset($description['title'])?$description['title']:'';
        $permalink_url = isset($description['permalink_url'])?$description['permalink_url']:$id;
        $player = 'https://www.facebook.com/plugins/video.php?height=232&href=https://facebook.com'.$permalink_url;
        if(isset($description['error'])){
            $data = array();
        }else {
            $data = array(
                'type' => 'facebook',
                'link' => 'https://facebook.com'.$permalink_url,
                'time' => gmdate("H:i:s", $description['length']),
                'title' => $title,
                'id_video' => $id,
                'thumbnail' => end($description['format'])['picture'],
                'player' => $player
            );
        }

        return $data;

    }

    function curlLink($link){
        $ch = curl_init($link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_REFERER, LOCATION_URL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json, User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:19.0) Gecko/20100101 Firefox/19.0'));
        $content = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($content, true);

        return $data;



    }

    public function addIdClientToContent(){
        $contents = Content::where('type_user','0')->get();
        foreach($contents as $content){
            $client = Client::where('id',$content->created_by)->first();
            if($client) {
                $content->code_invite = $client->ma_dinh_danh;
                $content->save();
            }
        }

        return 'Done';
    }

    public function updateIdClient(){
        $results = \DB::table('clients')->get();
        foreach ($results as $key => $row) {
            \DB::table('clients')
                ->where('id',$row->id)
                ->update([
                    'ma_dinh_danh' => create_number_wallet($row->id)
                ]);
            \DB::table('contents')
                ->where('created_by',$row->id)
                ->where('type_user','0')
                ->update([
                    'code_invite' => create_number_wallet($row->id)
                ]);
        }
        return 'Done';
    }

    public function updateDailycode(){
        set_time_limit(0);
        // $results = \DB::table('clients')->get();
        // foreach ($results as $key => $row) {
        //     \DB::table('contents')
        //         ->where('code_invite',$row->ma_dinh_danh)
        //         ->where('type_user','0')
        //         ->where('daily_code','')
        //         ->update([
        //             'daily_code' => $row->daily_code
        //         ]);
        // }

        $contents = Content::where('type_user','0')
                            // ->where(function($query){
                            //     return $query->where('daily_id',0)
                            //                  ->orWhere('ctv_id',0);
                            // })
                            ->get();
        foreach ($contents as $key => $row) {
            if($row->daily_code != ''){
                $client_daily = Client::where('ma_dinh_danh',$row->daily_code)->first();
                if($client_daily){
                    $daily = Daily::where('client_id',$client_daily->id)->first();
                    if($daily){
                        $row->daily_id = $daily->id;
                        $row->save();
                    }
                }
            }

            if($row->code_invite != ''){
                $client_ctv = Client::where('ma_dinh_danh',$row->code_invite)->first();
                if($client_ctv){
                    $ctv = CTV::where('client_id',$client_ctv->id)->first();
                    if($ctv){
                        $row->ctv_id = $ctv->id;
                        if($ctv->daily_id != $row->daily_id){
                            $row->daily_id = 0;
                        }
                        $row->save();
                    }
                }
            }

            if($row->daily_code == $row->code_invite){
                $row->daily_code = '';
                $row->daily_id = 0;
                $row->ctv_id = 0;
            }
        }

        return 'Done';
    }

    public function getChangeCTV() {
        if (Auth::guard('web')->user()->hasRole('super_admin') || Auth::guard('web')->user()->hasRole('admin')) {
            return view('Admin.content.change_ctv');
        } else {
            return redirect()->route('list_content');
        }
    }

    public function postChangeCTV(Request $request) {
        $rules = [
            'content' => 'required',
            'daily' => 'required',
            'ctv'=>'required'
        ];
        $messages = [
            'content.required' => trans('valid.content_required'),
            'daily.required' => trans('valid.daily_required'),
            'ctv.required' => trans('valid.ctv_required')
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $contents = Content::whereIn('id',$request->content)->get();
            $status = trans('global.locations').': ';
            $ctv = CTV::find($request->ctv);
            $ctv_client = Client::find($ctv->client_id);
            $daily = Daily::find($request->daily);
            $daily_client = Client::find($daily->client_id);

            foreach ($contents as $key => $content) {
                $content->daily_id = $daily->id;
                $content->ctv_id = $ctv->id;

                $content->code_invite = $ctv_client->ma_dinh_danh;
                $content->daily_code = $daily_client->ma_dinh_danh;
                $content->type_user = 0;
                $content->save();
                if($key==0)
                    $status.=$content->name;
                else
                    $status.=', '.$content->name;
            }

            return redirect(route('change_ctv'))->with(['status'=>trans('valid.change_ctv_to',[
                                                            'status'=>$status, 
                                                            'to_user' => $ctv_client->full_name
                                                        ])]);
        }
    }

    public function getSearchDailyContent(Request $request){
        $arr_return = [];
        $input = request()->all();
        if(isset($input['query']) && $input['query']!=''){
          $keyword = $input['query'];
          $clients = Client::rightJoin('daily','daily.client_id','clients.id')
                             ->where('active',1)
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
    public function getSearchCTVContent(Request $request){
        $arr_return = [];
        $input = request()->all();
        $daily_id = $input['daily']!=''?$input['daily']:0;
        if(isset($input['query']) && $input['query']!=''){
          $keyword = $input['query'];
          $clients = Client::rightJoin('ctv','ctv.client_id','clients.id')
                             ->where('ctv.daily_id',$daily_id)
                             ->where('active',1)
                             ->where(function($query) use($keyword){
                              return $query->where('email','like','%'.$keyword.'%')
                                           ->orwhere('full_name','like','%'.$keyword.'%')
                                           ->orWhere('phone','like','%'.$keyword.'%')
                                           ->orWhere('ma_dinh_danh', 'LIKE', '%' . $keyword . '%');
                             })
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

}

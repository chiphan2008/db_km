<?php


namespace App\Http\Controllers\Location;


use App\Models\Location\Category;
use App\Models\Location\CategoryContent;
use App\Models\Location\CategoryItem;
use App\Models\Location\CategoryService;
use App\Models\Location\City;
use App\Models\Location\Content;
use App\Models\Location\Country;
use App\Models\Location\District;
use App\Models\Location\GroupContent;
use App\Models\Location\ImageMenu;
use App\Models\Location\ImageSpace;
use App\Models\Location\LikeContent;
use App\Models\Location\LinkContent;
use App\Models\Location\NotifiContent;
use App\Models\Location\SeoContent;
use App\Models\Location\ServiceContent;
use App\Models\Location\VoteContent;
use App\Models\Location\Checkin;
use App\Models\Location\SaveLikeContent;
use App\Models\Location\ManageAd;
use App\Models\Location\NotifiAdmin;
use App\Models\Location\Notifi;
use App\Models\Location\EmailTemplate;
use App\Models\Location\Collection;
use App\Models\Location\CollectionContent;
use App\Models\Location\Product;
use App\Models\Location\Discount;
use App\Models\Location\DiscountProduct;

use App\Models\Location\PublishAds;
use App\Models\Location\TypeAds;


use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use App\Models\Location\TransactionCoin;
use Carbon\Carbon;
class ContentController extends BaseController
{

  public function getTimezoneGeo($latitude, $longitude) {
    $json = @file_get_contents("https://maps.googleapis.com/maps/api/timezone/json?location=".$latitude.",".$longitude."&timestamp=0&key=AIzaSyCaRjQ32tv6Bk2dKhP9oBbLHkofWx2xbKU");
    $data = json_decode($json);
    $tzone=isset($data->timeZoneId)?$data->timeZoneId:'Asia/Saigon';
    return $tzone;
  }

  public function getTimeByTimeZone($timezone)
  {
    $current_tz = new \DateTimeZone($timezone);
    $now = new \DateTime('now', $current_tz);
    return $now->format('d-m-Y H:i:s');
  }

  public function getContentFoodByAlias($content)
  {
    Session::put('backUrl', url()->current());

    // create breadcrumb
    $arrData['breadcrumb']='';// $arrData['breadcrumb'] = '<ol class="breadcrumb">';
    $arrData['breadcrumb'].='<a class="" href="'.url('/').'/list/'.$content->_category_type->alias.'" title="'.app('translator')->getFromJson($content->_category_type->name).'">'.app('translator')->getFromJson($content->_category_type->name).'</a>';
    $arrData['breadcrumb'].='<span>&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;</span>';
    if($content->_category_items){
      foreach ($content->_category_items as $key => $item) {
        if($key==0)
        $arrData['breadcrumb'].='<a class="" href="'.url('/').'/list/'.$content->_category_type->alias.'/'.$item->alias.'" title="'.app('translator')->getFromJson($item->name).'">'.app('translator')->getFromJson($item->name).'</a>';
        else
        $arrData['breadcrumb'].=' - <a class="" href="'.url('/').'/list/'.$content->_category_type->alias.'/'.$item->alias.'" title="'.app('translator')->getFromJson($item->name).'">'.app('translator')->getFromJson($item->name).'</a>';
      }
    }
    // $arrData['breadcrumb'].='</ol>';


    $arrData['previous_link'] = url()->previous();
    if(Auth::guard('web_client')->check() == true)
    {
      $id_user = Auth::guard('web_client')->user()->id;
      $check_vote_point = VoteContent::where([['id_user','=',$id_user],['id_content','=',$content->id]])->pluck('vote_point')->first();
      if($check_vote_point)
      {
        $arrData['vote_point'] = $check_vote_point;
      }

      $check_like_point = LikeContent::where([['id_user','=',$id_user],['id_content','=',$content->id]])->pluck('id_content')->first();
      if($check_vote_point)
      {
        $arrData['like_point'] = $check_like_point;
      }
    }else{
      $arrData['vote_point'] = $content->vote;
    }

    $timezone = $this->getTimezoneGeo($content->lat,$content->lng);
    $arrData['datetime'] = $this->getTimeByTimeZone($timezone);
    $arrData['open'] = check_open_time($content->_date_open, $arrData['datetime']);

    $arrData['open_time'] = create_open_time($content->_date_open, \App::getLocale());

    $arrData['content'] = $content;
    $this->view->meta_description = $content->description?$content->description:'';
    $this->view->title .= $content->name?' - '.$content->name:'';
    $this->view->meta_tag .= $content->tag_search?','.$content->tag_search:'';
    $this->view->meta_section = $content->_category_type->alias?$content->_category_type->alias:'';
    $this->view->meta_image = $content->avatar?url('/').str_replace("img_content_thumbnail","img_content",$content->avatar):url('/').'/img_default/share_image.png';
    $arrData['image_space'] = ImageSpace::where('id_content', '=', $content->id)->pluck('name')->toArray();
    $arrData['image_menu'] = ImageMenu::where('id_content', '=', $content->id)->pluck('name')->toArray();
    $arrData['link_video'] = LinkContent::where('id_content', '=', $content->id)->pluck('link')->toArray();
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

    // dd($arrData['list_product']);

    $arrData['list_service'] = CategoryService::where('id_category', '=', $content->id_category)->with('_service_item')->get();
    // pr($arrData['list_service']->toArray());die;
    $arrData['service_content'] = ServiceContent::where('id_content', '=', $content->id)->pluck('id_service_item')->toArray();
    $id_category_content = $content->id_category;
    $arrData['list_suggest'] = Content::select('contents.*')->selectRaw("(SQRT(POW((`lng` - '+$content->lng+')*(3.14159265359/180)*COS((`lat` + '+$content->lat+')*(3.14159265359/180)/2),2)+POW((`lat` - '+$content->lat+')*(3.14159265359/180),2))*6371000) AS line")
      ->orderBy('line')
      ->distinct()
      ->where('contents.id_category', '=', $id_category_content)
      ->where('contents.moderation', '=', 'publish')
      ->where('contents.active', '=', 1)
      ->where('contents.id', '!=', $content->id)
      ->limit(4)
      ->get();

    $id_group_content = GroupContent::where('id_content', '=', $content->id)->pluck('id_group')->first();
    if ($id_group_content) {
      $arrData['list_group'] = Content::select('contents.*')
        ->selectRaw("(SQRT(POW((`lng` - '+$content->lng+')*(3.14159265359/180)*COS((`lat` + '+$content->lat+')*(3.14159265359/180)/2),2)+POW((`lat` - '+$content->lat+')*(3.14159265359/180),2))*6371000) AS line")
        ->orderBy('line')
        ->distinct()
        ->join('group_content', 'contents.id', '=', 'group_content.id_content')
        ->where('group_content.id_group', '=', $id_group_content)
        ->where('contents.id', '!=', $content->id)
        ->where('contents.moderation', '=', 'publish')
        ->where('contents.active', '=', 1)
        // ->limit(8)
        ->get();
    }

    $check_notify_content = NotifiContent::where([['id_content','=',$content->id],['active','=',1]])->first();
    if(isset($check_notify_content))
    {
      if(strtotime($arrData['datetime']) > strtotime($check_notify_content->start) && strtotime($arrData['datetime']) < strtotime($check_notify_content->end))
      {
        $arrData['notify_content'] = $check_notify_content->description;
      }
    }

    $arrData['count_collection'] = CollectionContent::where('content_id','=',$content->id)->count();
    $arrData['collections'] = null;
    if(Auth::guard('web_client')->check() == true)
    {
      $id_user = Auth::guard('web_client')->user()->id;
      $arrData['collections'] = Collection::select('collection.*')
                                          ->where('created_by','=',$id_user)
                                          ->with('_contents')
                                          ->get();
      foreach ($arrData['collections'] as $key => $collection) {
        $arrData['collections'][$key]->check = false;
        foreach ($collection->_contents as $key2 => $cont) {
          if($cont->id == $content->id){
            $arrData['collections'][$key]->check = true;
            break;
          }
        }
      }
    } 

    $this->view->content = view('Location.content.food_details', $arrData);
    return $this->setContent();
  }

  public function getContentBankByAlias($content)
  {
    Session::put('backUrl', url()->current());

    // create breadcrumb
    $arrData['breadcrumb']='';// $arrData['breadcrumb'] = '<ol class="breadcrumb">';
    $arrData['breadcrumb'].='<a class="" href="'.url('/').'/list/'.$content->_category_type->alias.'" title="'.app('translator')->getFromJson($content->_category_type->name).'">'.app('translator')->getFromJson($content->_category_type->name).'</a>';
    $arrData['breadcrumb'].='<span>&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;</span>';
    if($content->_category_items){
      foreach ($content->_category_items as $key => $item) {
        if($key==0)
        $arrData['breadcrumb'].='<a class="" href="'.url('/').'/list/'.$content->_category_type->alias.'/'.$item->alias.'" title="'.app('translator')->getFromJson($item->name).'">'.app('translator')->getFromJson($item->name).'</a>';
        else
        $arrData['breadcrumb'].=' - <a class="" href="'.url('/').'/list/'.$content->_category_type->alias.'/'.$item->alias.'" title="'.app('translator')->getFromJson($item->name).'">'.app('translator')->getFromJson($item->name).'</a>';
      }
    }
    // $arrData['breadcrumb'].='</ol>';
    $arrData['previous_link'] = url()->previous();
    if(Auth::guard('web_client')->check() == true)
    {
      $id_user = Auth::guard('web_client')->user()->id;
      $check_vote_point = VoteContent::where([['id_user','=',$id_user],['id_content','=',$content->id]])->pluck('vote_point')->first();
      if($check_vote_point)
      {
        $arrData['vote_point'] = $check_vote_point;
      }

      $check_like_point = LikeContent::where([['id_user','=',$id_user],['id_content','=',$content->id]])->pluck('id_content')->first();
      if($check_vote_point)
      {
        $arrData['like_point'] = $check_like_point;
      }
    }else{
      $arrData['vote_point'] = $content->vote;
    }

    $timezone = $this->getTimezoneGeo($content->lat,$content->lng);
    $arrData['datetime'] = $this->getTimeByTimeZone($timezone);
    $arrData['open'] = check_open_time($content->_date_open, $arrData['datetime']);

    $arrData['open_time'] = create_open_time($content->_date_open, \App::getLocale());

    $arrData['content'] = $content;
    $this->view->meta_description = $content->description?$content->description:'';
    $this->view->title .= $content->name?' - '.$content->name:'';
    $this->view->meta_tag .= $content->tag_search?','.$content->tag_search:'';
    $this->view->meta_section = $content->_category_type->alias?$content->_category_type->alias:'';
    $this->view->meta_image = $content->avatar?url('/').str_replace("img_content_thumbnail","img_content",$content->avatar):url('/').'/img_default/share_image.png';
    $arrData['image_space'] = ImageSpace::where('id_content', '=', $content->id)->pluck('name')->toArray();
    $arrData['link_video'] = LinkContent::where('id_content', '=', $content->id)->pluck('link')->toArray();

    $id_category_content = $content->id_category;
    $arrData['list_suggest'] = Content::select('contents.*')->selectRaw("(SQRT(POW((`lng` - '+$content->lng+')*(3.14159265359/180)*COS((`lat` + '+$content->lat+')*(3.14159265359/180)/2),2)+POW((`lat` - '+$content->lat+')*(3.14159265359/180),2))*6371000) AS line")
      ->orderBy('line')
      ->distinct()
      ->where('contents.id_category', '=', $id_category_content)
      ->where('contents.moderation', '=', 'publish')
      ->where('contents.active', '=', 1)
      ->where('contents.id', '!=', $content->id)
      ->where('contents.extra_type', '=', $content->extra_type)
      ->limit(4)
      ->get();

    $check_notify_content = NotifiContent::where([['id_content','=',$content->id],['active','=',1]])->first();
    if(isset($check_notify_content))
    {
      if(strtotime($arrData['datetime']) > strtotime($check_notify_content->start) && strtotime($arrData['datetime']) < strtotime($check_notify_content->end))
      {
        $arrData['notify_content'] = $check_notify_content->description;
      }
    }

    $arrData['list_city'] = City::all();

    $this->view->content = view('Location.content.bank_details', $arrData);
    return $this->setContent();
  }

  public function getContentShopByAlias($content)
  {
    Session::put('backUrl', url()->current());

    // create breadcrumb
    $arrData['breadcrumb']='';// $arrData['breadcrumb'] = '<ol class="breadcrumb">';
    $arrData['breadcrumb'].='<a class="" href="'.url('/').'/list/'.$content->_category_type->alias.'" title="'.app('translator')->getFromJson($content->_category_type->name).'">'.app('translator')->getFromJson($content->_category_type->name).'</a>';
    $arrData['breadcrumb'].='<span>&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;</span>';
    if($content->_category_items){
      foreach ($content->_category_items as $key => $item) {
        if($key==0)
        $arrData['breadcrumb'].='<a class="" href="'.url('/').'/list/'.$content->_category_type->alias.'/'.$item->alias.'" title="'.app('translator')->getFromJson($item->name).'">'.app('translator')->getFromJson($item->name).'</a>';
        else
        $arrData['breadcrumb'].=' - <a class="" href="'.url('/').'/list/'.$content->_category_type->alias.'/'.$item->alias.'" title="'.app('translator')->getFromJson($item->name).'">'.app('translator')->getFromJson($item->name).'</a>';
      }
    }
    // $arrData['breadcrumb'].='</ol>';
    $arrData['previous_link'] = url()->previous();
    if(Auth::guard('web_client')->check() == true)
    {
      $id_user = Auth::guard('web_client')->user()->id;
      $check_vote_point = VoteContent::where([['id_user','=',$id_user],['id_content','=',$content->id]])->pluck('vote_point')->first();
      if($check_vote_point)
      {
        $arrData['vote_point'] = $check_vote_point;
      }

      $check_like_point = LikeContent::where([['id_user','=',$id_user],['id_content','=',$content->id]])->pluck('id_content')->first();
      if($check_vote_point)
      {
        $arrData['like_point'] = $check_like_point;
      }
    }else{
      $arrData['vote_point'] = $content->vote;
    }

    $timezone = $this->getTimezoneGeo($content->lat,$content->lng);
    $arrData['datetime'] = $this->getTimeByTimeZone($timezone);
    $arrData['open'] = check_open_time($content->_date_open, $arrData['datetime']);

    $arrData['open_time'] = create_open_time($content->_date_open, \App::getLocale());

    $arrData['content'] = $content;
    $this->view->meta_description = $content->description?$content->description:'';
    $this->view->title .= $content->name?' - '.$content->name:'';
    $this->view->meta_tag .= $content->tag_search?','.$content->tag_search:'';
    $this->view->meta_section = $content->_category_type->alias?$content->_category_type->alias:'';
    $this->view->meta_image = $content->avatar?url('/').str_replace("img_content_thumbnail","img_content",$content->avatar):url('/').'/img_default/share_image.png';
    $arrData['image_space'] = ImageSpace::where('id_content', '=', $content->id)->pluck('name')->toArray();
    $arrData['link_video'] = LinkContent::where('id_content', '=', $content->id)->pluck('link')->toArray();

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
    
    $arrData['list_service'] = CategoryService::where('id_category', '=', $content->id_category)->with('_service_item')->get();
    $arrData['service_content'] = ServiceContent::where('id_content', '=', $content->id)->pluck('id_service_item')->toArray();
    $id_category_content = $content->id_category;
    $arrData['list_suggest'] = Content::select('contents.*')->selectRaw("(SQRT(POW((`lng` - '+$content->lng+')*(3.14159265359/180)*COS((`lat` + '+$content->lat+')*(3.14159265359/180)/2),2)+POW((`lat` - '+$content->lat+')*(3.14159265359/180),2))*6371000) AS line")
      ->orderBy('line')
      ->distinct()
      ->where('contents.id_category', '=', $id_category_content)
      ->where('contents.id', '!=', $content->id)
      ->where('contents.moderation', '=', 'publish')
      ->where('contents.active', '=', 1)
      ->limit(4)
      ->get();

    $id_group_content = GroupContent::where('id_content', '=', $content->id)->pluck('id_group')->first();
    if ($id_group_content) {
      $arrData['list_group'] = Content::select('contents.*')
        ->selectRaw("(SQRT(POW((`lng` - '+$content->lng+')*(3.14159265359/180)*COS((`lat` + '+$content->lat+')*(3.14159265359/180)/2),2)+POW((`lat` - '+$content->lat+')*(3.14159265359/180),2))*6371000) AS line")
        ->orderBy('line')
        ->distinct()
        ->join('group_content', 'contents.id', '=', 'group_content.id_content')
        ->where('group_content.id_group', '=', $id_group_content)
        ->where('contents.id', '!=', $content->id)
        ->where('contents.moderation', '=', 'publish')
        ->where('contents.active', '=', 1)
        // ->limit(8)
        ->get();
    }

    $check_notify_content = NotifiContent::where([['id_content','=',$content->id],['active','=',1]])->first();
    if(isset($check_notify_content))
    {
      if(strtotime($arrData['datetime']) > strtotime($check_notify_content->start) && strtotime($arrData['datetime']) < strtotime($check_notify_content->end))
      {
        $arrData['notify_content'] = $check_notify_content->description;
      }
    }

    $arrData['count_collection'] = CollectionContent::where('content_id','=',$content->id)->count();
    $arrData['collections'] = null;
    if(Auth::guard('web_client')->check() == true)
    {
      $id_user = Auth::guard('web_client')->user()->id;
      $arrData['collections'] = Collection::select('collection.*')
                                          ->where('created_by','=',$id_user)
                                          ->with('_contents')
                                          ->get();
      foreach ($arrData['collections'] as $key => $collection) {
        $arrData['collections'][$key]->check = false;
        foreach ($collection->_contents as $key2 => $cont) {
          if($cont->id == $content->id){
            $arrData['collections'][$key]->check = true;
            break;
          }
        }
      }
    }

    $this->view->content = view('Location.content.shop_details', $arrData);
    return $this->setContent();
  }

  public function getLikeAjax(Request $request)
  {
    $id_content = $request->id_content;
    $id_user = $request->id_user;

    $check_exits = LikeContent::where('id_content', '=', $id_content)
                              ->where('id_user', '=', $id_user)->first();
    if (isset($check_exits)) {
      $content = Content::find($id_content);
      $content->like = $content->like - 1;
      if ($content->save()) {
        LikeContent::where('id_content', '=', $id_content)
                                      ->where('id_user', '=', $id_user)->delete();
        return Response::json(array(
          'mess' => true,
          'value' => $content->like
        ));
      }
    } else {
      LikeContent::create([
        'id_content' => $id_content,
        'id_user' => $id_user,
      ]);

      $content = Content::find($id_content);
      $content->like = $content->like + 1;
      if ($content->save()) {
        return Response::json(array(
          'mess' => true,
          'value' => $content->like
        ));
      }
    }
  }

  public function getVoteAjax(Request $request)
  {
    $id_content = $request->id_content;
    $id_user = $request->id_user;
    $point = $request->point;

    VoteContent::where('id_content', '=', $id_content)
               ->where('id_user', '=', $id_user)->delete();
    VoteContent::create([
      'id_content' => $id_content,
      'id_user' => $id_user,
      'vote_point' => $point,
    ]);

    $avg_point = VoteContent::where('id_content', '=', $id_content)->avg('vote_point');

    $content = Content::find($id_content);
    $content->vote = round($avg_point,2);
    if ($content->save()) {
      return Response::json(array(
        'mess' => true,
        'value' => $content->vote
      ));
    }
  }

  public function getBankAjax(Request $request)
  {

    $id_content = $request->id_content;

    $content = Content::find($id_content);
    $id_category_item_content = CategoryContent::where('id_content', '=', $content->id)->pluck('id_category_item')->first();

    $all_content = Content::select('contents.*')
      ->selectRaw("(SQRT(POW((`lng` - '+$content->lng+')*(3.14159265359/180)*COS((`lat` + '+$content->lat+')*(3.14159265359/180)/2),2)+POW((`lat` - '+$content->lat+')*(3.14159265359/180),2))*6371000) AS line")
      ->orderBy('line')
      ->distinct()
      ->join('category_content', 'contents.id', '=', 'category_content.id_content')
      ->where('category_content.id_category_item', '=', $id_category_item_content)
      ->where('contents.id', '!=', $content->id);

    if(isset($request->id_city) && $request->id_city != '' )
    {
      $all_content->where('contents.city', '=', $request->id_city);
    }

    if(isset($request->id_district) && $request->id_district != '')
    {
      $all_content->where('contents.district', '=', $request->id_district);
    }

    $data_content = $all_content->with('_district')->get();

    $district = District::where('id_city', '=', $request->id_city)->pluck('name', 'id');
    $data['district'] = '<option value="">Quận</option>';
    foreach ($district as $key => $value) {
      $data['district'] .= '<option value="' . $key . '">' . $value . '</option>';
    }

    $data['bank'] = view('Location.content.bank_list_ajax',['list_suggest'=>$data_content])->render();
    return response($data);
  }

  public function getDetailPhoto($param_1,$param_2)
  {

    $content = Content::where('alias', '=', $param_1)->first();
    if (!$content) {
      return redirect('');
    }

    if (in_array($param_2, ['space','menu','video','product']))
    {
      $list_image_menu = ImageMenu::where('id_content', '=', $content->id)->get();
      $list_image_space = ImageSpace::where('id_content', '=', $content->id)->get();
      $list_video = LinkContent::where('id_content', '=', $content->id)->get();
      $data['count_image_menu'] = count($list_image_menu);
      $data['count_image_space'] = count($list_image_space);
      $data['count_video'] = count($list_video);

      $data['products'] = Product::where('content_id', '=', $content->id)->orderBy('group_name')->get();
      $data['group_product'] = Product::where('content_id', '=', $content->id)
                                      ->groupBy('group_name')
                                      ->whereNotNull('group_name')
                                      ->pluck('group_name');
      $data['list_product'] = [];
      $data['list_product']['no_group']['group_name'] = '';
      $arr_has_group=[];
      $arr_no_group=[];
      $count_list_product = 0;
      foreach ($data['group_product'] as $key => $group) {
          $data['list_product'][$key]['group_name'] = $group;
          foreach ($data['products'] as $key2 => $product) {
              if($product->group_name === $group && !in_array($product->id,$arr_has_group)){
                  $data['list_product'][$key][] = $product;
                  $arr_has_group[] = $product->id;
                  $count_list_product = $count_list_product +1;
              }else{
                  if($product->group_name===null && !in_array($product->id,$arr_no_group)){
                      $data['list_product']['no_group'][] = $product;
                      $arr_no_group[] = $product->id;
                      $count_list_product = $count_list_product +1 ;
                  }
              }
          }
      }
      $data['count_list_product'] = $count_list_product;
      $data['content'] = $content;
      $data['image_space'] = $list_image_space;
      $data['image_menu'] = $list_image_menu;
      $data['video'] = $list_video;

      if(Auth::guard('web_client')->guest() == false)
      {
        $data['user'] = Auth::guard('web_client')->user();
      }
      return view('Location.content.detail_photo', ['content' => $content, 'data' => $data, 'type' => $param_2]);
    }
    else
    {
      return redirect(url($content->alias));
    }

  }


  public function getCheckinAjax(Request $request)
  {
    $id_content = $request->id_content;
    $id_user = $request->id_user;

    $check_exits = Checkin::where('id_content', '=', $id_content)
                              ->where('id_user', '=', $id_user)->first();
    if (isset($check_exits)) {
      $content = Content::find($id_content);
      $content->checkin = $content->checkin - 1;
      if ($content->save()) {
        Checkin::where('id_content', '=', $id_content)
                                      ->where('id_user', '=', $id_user)->delete();
        return Response::json(array(
          'mess' => true,
          'value' => $content->checkin
        ));
      }
    } else {
      Checkin::create([
        'id_content' => $id_content,
        'id_user' => $id_user,
      ]);

      $content = Content::find($id_content);
      $content->checkin = $content->checkin + 1;
      if ($content->save()) {
        return Response::json(array(
          'mess' => true,
          'value' => $content->checkin
        ));
      }
    }
  }

  public function getSaveLikeAjax(Request $request)
  {
    $id_content = $request->id_content;
    $id_user = $request->id_user;

    $check_exits = SaveLikeContent::where('id_content', '=', $id_content)
                              ->where('id_user', '=', $id_user)->first();
    if (isset($check_exits)) {
      $content = Content::find($id_content);
      $content->save_like_content = $content->save_like_content - 1;
      if($content->save_like_content<0){
        $content->save_like_content = 0;
      }
      if ($content->save()) {
        SaveLikeContent::where('id_content', '=', $id_content)
                                      ->where('id_user', '=', $id_user)->delete();
        return Response::json(array(
          'mess' => true,
          'value' => $content->save_like_content
        ));
      }
    } else {
      SaveLikeContent::create([
        'id_content' => $id_content,
        'id_user' => $id_user,
      ]);

      $content = Content::find($id_content);
      $content->save_like_content = $content->save_like_content + 1;
      if ($content->save()) {
        return Response::json(array(
          'mess' => true,
          'value' => $content->save_like_content
        ));
      }
    }
  }

  public function getPushContent($id_content){
    $hour = 12;
    $arrReturn = [
      'error' => 1,
      'message' => 'Lỗi tạo quảng cáo từ khóa'
    ];
    $content = Content::find($id_content);
    $content->last_push = Carbon::now();
    $content->end_push = Carbon::now()->addHour($hour);
    $trans = new TransactionCoin();
    $check = $trans->pay(Auth::guard('web_client')->user(), ($hour*PRICE_PUSH), trans_choice('transaction.pay_for_push_content',$hour,['content'=>$content->name, 'hour'=> $hour, 'coin'=>($hour*PRICE_PUSH)]));
    if($check===true){
      $content->save();
      $arrReturn['error'] = 0;
      $arrReturn['message'] = trans_choice('transaction.pay_for_push_content',$hour,['content'=>$content->name, 'hour'=> $hour, 'coin'=>($hour*PRICE_PUSH)]);
    }else{
      $arrReturn['message'] = $trans->getError()->getMessage();
    }
    return response()->json($arrReturn);
  }

  public function postAdContent(Request $request){
    $arrReturn = [
      'error' => 1,
      'message' => ''
    ];
    $id_content = $request->id?$request->id:0;
    $coin = $request->coin?$request->coin:0;
    $view = round($coin/PRICE_KEYWORD,0);
    $keyword_ad = $request->keyword?$request->keyword:[];
    $keyword_ad = implode(',',$keyword_ad);
    $check = true;
    
    $content = Content::where('id','=',$id_content)
                      ->with('_category_type')
                      ->with('_category_items')
                      ->first();

    //Create transaction
    $trans = new TransactionCoin();
    $check = $check && $trans->pay(Auth::guard('web_client')->user(), $coin, trans('transaction.pay_for_ad_content',['content'=>$content->name, 'coin'=>$coin]));
    $transaction_id = $trans->getTransfer();

    if($check){
      //Update content
      $content->view_ad += $view;
      $content->keyword_ad = $keyword_ad;
      $content->active_ad = 0;
      $check = $check && $content->save();
    }else{
      $arrReturn['message'] = $trans->getError()->getMessage();
    }
    

    
    if($check){
      //create notifi admin
      $link = 'https://admin.kingmap.vn/manage-ad/';
      $content_notifi = 'Content <b>'.$content->name.'</b> đã tạo quảng cáo từ khóa, đang chờ duyệt.';
      $notifi_admin = new NotifiAdmin();
      $notifi_admin->createNotifi($content_notifi,$link);
    }

    if($check){
      //create notifi user
      $content_notifi = 'Location'.DS.'user.create_ad_success';
      $notifi = new Notifi();
      $notifi->createNotifiUserByTemplate($content_notifi,$content->created_by,['content'=>$content->name]);
    }

    if($check){
      //create manage ad
      $manage_ad = new ManageAd();
      $manage_ad->content_id = $content->id;
      $manage_ad->content_name = $content->name;
      $manage_ad->keyword_ad = $keyword_ad;
      $manage_ad->transaction_id = $transaction_id;
      $manage_ad->total_view = $coin;
      $manage_ad->total_coin = $view;
      $manage_ad->created_at = new Carbon();
      $manage_ad->updated_at = new Carbon();
      $check = $check && $manage_ad->save();
    }

    if($check){
      $arrReturn['error'] = 0;
      $arrReturn['message'] = "Đã tạo thành công";
    }

    return response()->json($arrReturn);
  }


  public function getConfirmContent($content){
    if(Auth::guard('web_client')->check() == true)
    {
      $arrData['content'] = $content;
      $this->view->content = view('Location.content.confirm_location',$arrData);
      return $this->setContent();
    }else{
      abort(403);
    }
  }

  public function postConfirmContent(Request $request, $id_content){
    try{
        $data = $request->all();
        $mail_template_admin = EmailTemplate::where('machine_name', 'email_confirm_admin')->first();
        if($mail_template_admin)
        {
          $data_send_admin = [
            'full_name' => $data['name'],
            'phone' => $data['phone'],
            'email' => 'info@kingmap.vn',
            'content' => $data['content'],
          ];
          Mail::send([], [], function($message) use ($mail_template_admin, $data_send_admin)
          {
            $message->to($data_send_admin['email'], $data_send_admin['full_name'])
              ->subject($mail_template_admin['subject'])
              ->from('kingmapteam@gmail.com', 'KingMap Team')
              ->setBody($mail_template_admin->parse($data_send_admin));
          });
        }

        $mail_template_customer = EmailTemplate::where('machine_name', 'email_confirm_customer')->first();
        if($mail_template_customer)
        {
          $data_send_customer = [
            'full_name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'content' => $data['content'],
          ];
          Mail::send([], [], function($message) use ($mail_template_customer, $data_send_customer)
          {
            $message->to($data_send_customer['email'], $data_send_customer['full_name'])
              ->subject($mail_template_customer['subject'])
              ->from('kingmapteam@gmail.com', 'KingMap Team')
              ->setBody($mail_template_customer->parse($data_send_customer));
          });
        }
        return redirect()->back()->with(['success'=>trans('Location'.DS.'preview.confirm_location_success')]);
    }catch(\Exception $ex){
      throw $ex;
    }
  }


  public function getContentByAlias($content)
  {
    Session::put('backUrl', url()->current());

    // create breadcrumb
    $arrData['breadcrumb']='';// $arrData['breadcrumb'] = '<ol class="breadcrumb">';
    $arrData['breadcrumb'].='<a class="" href="'.url('/').'/list/'.$content->_category_type->alias.'" title="'.app('translator')->getFromJson($content->_category_type->name).'">'.app('translator')->getFromJson($content->_category_type->name).'</a>';
    $arrData['breadcrumb'].='<span>&nbsp;&nbsp;&rsaquo;&nbsp;&nbsp;</span>';
    if($content->_category_items){
      foreach ($content->_category_items as $key => $item) {
        if($key==0)
        $arrData['breadcrumb'].='<a class="" href="'.url('/').'/list/'.$content->_category_type->alias.'/'.$item->alias.'" title="'.app('translator')->getFromJson($item->name).'">'.app('translator')->getFromJson($item->name).'</a>';
        else
        $arrData['breadcrumb'].=' - <a class="" href="'.url('/').'/list/'.$content->_category_type->alias.'/'.$item->alias.'" title="'.app('translator')->getFromJson($item->name).'">'.app('translator')->getFromJson($item->name).'</a>';
      }
    }
    // $arrData['breadcrumb'].='</ol>';


    $arrData['previous_link'] = url()->previous();
    if(Auth::guard('web_client')->check() == true)
    {
      $id_user = Auth::guard('web_client')->user()->id;
      $check_vote_point = VoteContent::where([['id_user','=',$id_user],['id_content','=',$content->id]])->pluck('vote_point')->first();
      if($check_vote_point)
      {
        $arrData['vote_point'] = $check_vote_point;
      }

      $check_like_point = LikeContent::where([['id_user','=',$id_user],['id_content','=',$content->id]])->pluck('id_content')->first();
      if($check_vote_point)
      {
        $arrData['like_point'] = $check_like_point;
      }
    }else{
      $arrData['vote_point'] = 0;
    }

    $timezone = $this->getTimezoneGeo($content->lat,$content->lng);
    $arrData['datetime'] = $this->getTimeByTimeZone($timezone);
    $arrData['open'] = check_open_time($content->_date_open, $arrData['datetime']);

    $arrData['open_time'] = create_open_time($content->_date_open, \App::getLocale());

    $arrData['content'] = $content;
    $this->view->meta_description = $content->description?$content->description:'';
    $this->view->title .= $content->name?' - '.$content->name:'';
    $this->view->meta_tag .= $content->tag_search?','.$content->tag_search:'';
    $this->view->meta_section = $content->_category_type->alias?$content->_category_type->alias:'';
    $this->view->meta_image = $content->avatar?url('/').str_replace("img_content_thumbnail","img_content",$content->avatar):url('/').'/img_default/share_image.png';
    $arrData['image_space'] = ImageSpace::where('id_content', '=', $content->id)->get();
    $arrData['image_menu'] = ImageMenu::where('id_content', '=', $content->id)->get();
    $arrData['link_video'] = LinkContent::where('id_content', '=', $content->id)->get();
    $arrData['products'] = Product::where('content_id', '=', $content->id)->orderBy('group_name')->get();
    $arrData['group_product'] = Product::where('content_id', '=', $content->id)
                                    ->groupBy('group_name')
                                    ->whereNotNull('group_name')
                                    ->pluck('group_name');
    $arrData['list_product'] = [];
    $arrData['list_product']['no_group']['group_name'] = '';
    $arr_has_group=[];
    $arr_no_group=[];
    $count_list_product = 0;
    foreach ($arrData['group_product'] as $key => $group) {
        $arrData['list_product'][$key]['group_name'] = $group;
        foreach ($arrData['products'] as $key2 => $product) {
            if($product->group_name === $group && !in_array($product->id,$arr_has_group)){
                $arrData['list_product'][$key][] = $product;
                $arr_has_group[] = $product->id;
                $count_list_product = $count_list_product +1;
            }else{
                if($product->group_name===null && !in_array($product->id,$arr_no_group)){
                    $arrData['list_product']['no_group'][] = $product;
                    $arr_no_group[] = $product->id;
                    $count_list_product = $count_list_product +1 ;
                }
            }
        }
    }
    $arrData['count_list_product'] = $count_list_product;

    if(count($arrData['list_product']['no_group'])<2){
        unset($arrData['list_product']['no_group']);
        $arrData['list_product'] = array_values($arrData['list_product']);
    }

    // dd($arrData['list_product']);
    $arrData['discounts'] = [];
    $arrData['discounts'] = Discount::where('id_content',$content->id)
                                    ->where('date_from','<=',Carbon::now())
                                    ->where('date_to','>=',Carbon::now())
                                    ->with('_products')
                                    ->get();
    $arrData['list_service'] = CategoryService::where('id_category', '=', $content->id_category)->with('_service_item')->get();
    // pr($arrData['list_service']->toArray());die;
    $arrData['service_content'] = ServiceContent::where('id_content', '=', $content->id)->pluck('id_service_item')->toArray();
    $id_category_content = $content->id_category;
    $arrData['list_suggest'] = Content::select('contents.*')->selectRaw("(SQRT(POW((`lng` - '+$content->lng+')*(3.14159265359/180)*COS((`lat` + '+$content->lat+')*(3.14159265359/180)/2),2)+POW((`lat` - '+$content->lat+')*(3.14159265359/180),2))*6371000) AS line")
      ->orderBy('line')
      ->distinct()
      ->where('contents.id_category', '=', $id_category_content)
      ->where('contents.moderation', '=', 'publish')
      ->where('contents.active', '=', 1)
      ->where('contents.id', '!=', $content->id)
      ->limit(20)
      ->get();

    // $id_group_content = GroupContent::where('id_content', '=', $content->id)->pluck('id_group')->first();
    // if ($id_group_content) {
    //   $arrData['list_group'] = Content::select('contents.*')
    //     ->selectRaw("(SQRT(POW((`lng` - '+$content->lng+')*(3.14159265359/180)*COS((`lat` + '+$content->lat+')*(3.14159265359/180)/2),2)+POW((`lat` - '+$content->lat+')*(3.14159265359/180),2))*6371000) AS line")
    //     ->orderBy('line')
    //     ->distinct()
    //     ->join('group_content', 'contents.id', '=', 'group_content.id_content')
    //     ->where('group_content.id_group', '=', $id_group_content)
    //     ->where('contents.id', '!=', $content->id)
    //     ->where('contents.moderation', '=', 'publish')
    //     ->where('contents.active', '=', 1)
    //     // ->limit(8)
    //     ->get();
    // }

    $arrData['list_group'] = $content->_branchs;

    $check_notify_content = NotifiContent::where([['id_content','=',$content->id],['active','=',1]])->first();
    $arrData['notify_show_1st'] = 'off';
      if(isset($check_notify_content))
    {
      if(strtotime($arrData['datetime']) > strtotime($check_notify_content->start) && strtotime($arrData['datetime']) < strtotime($check_notify_content->end))
      {
          session_start();
          if(isset($_SESSION['notify_show_1st_'.$content->id]) && !empty($_SESSION['notify_show_1st_'.$content->id])) {
              $_SESSION['notify_show_1st_'.$content->id] = 'off';
          }else{
              $_SESSION['notify_show_1st_'.$content->id] = 'on';
          }
          $arrData['notify_content'] = $check_notify_content->description;
          $arrData['notify_show_1st'] = $_SESSION['notify_show_1st_'.$content->id];
      }
    }

    $arrData['count_collection'] = CollectionContent::where('content_id','=',$content->id)->count();
    $arrData['collections'] = null;
    if(Auth::guard('web_client')->check() == true)
    {
      $id_user = Auth::guard('web_client')->user()->id;
      $arrData['collections'] = Collection::select('collection.*')
                                          ->where('created_by','=',$id_user)
                                          ->with('_contents')
                                          ->get();
      foreach ($arrData['collections'] as $key => $collection) {
        $arrData['collections'][$key]->check = false;
        foreach ($collection->_contents as $key2 => $cont) {
          if($cont->id == $content->id){
            $arrData['collections'][$key]->check = true;
            break;
          }
        }
      }
    } 

    $limit_ads = 1;

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

    $arrData['ads'] = PublishAds::select('publish_ads.*')
                      ->selectRaw("RAND () as id_rand ")
                      ->with('_base_content')
                      ->leftJoin('type_ads','type_ads.id','publish_ads.type_ads')
                      ->where('type_ads.machine_name','quang_cao_trang_chi_tiet')
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
                      ->limit($limit_ads)
                      ->get();
    foreach ($arrData['ads'] as $key => $ads) {
      if($ads->type_apply=='view'){
        $ads->viewed = $ads->viewed + 1;
        $ads->save();
      }
    } 
    $arrData['type_ads'] = TypeAds::where('machine_name','quang_cao_trang_chi_tiet')->first();

    $arrData['category'] = Category::find($content->id_category);

    if($content->moderation == 'publish'){
      $this->view->content = view('Location.content.details_new', $arrData);
    }else{
      $this->view->content = view('Location.content.details_pending', $arrData);
    }
    
    return $this->setContent();
  }
}
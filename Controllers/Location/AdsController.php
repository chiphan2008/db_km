<?php

namespace App\Http\Controllers\Location;
use App\Models\Location\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\MessageBag;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Validator;
use App\Models\Location\Ads;
use App\Models\Location\PublishAds;
use App\Models\Location\PaymentAds;
use App\Models\Location\PriceAds;
use App\Models\Location\TypeAds;

use App\Models\Location\Content;
use App\Models\Location\TransactionCoin;
use App\Models\Location\NotifiAdmin;
use App\Models\Location\Notifi;


use App\Models\Location\Country;
use App\Models\Location\City;
use App\Models\Location\District;


use Carbon\Carbon;

class AdsController extends BaseController
{
  public function postCalPriceAds(Request $request){
    $arrReturn = [
      'price' => 0,
      'total' => 0
    ];
    $quantity = 0;
    switch ($request->type_apply) {
      case 'date':
        $date_from        = new Carbon($request->ads_from.'00:00:00');
        $date_to          = new Carbon($request->ads_to.'23:59:59');
        $quantity = $date_to->diffInDays($date_from) + 1;
        break;
      case 'click':
        $quantity       = $request->click;
        break;
      case 'view':
        $quantity        = $request->view;
        break; 
      default:
        $date_from        = new Carbon($request->ads_from.'00:00:00');
        $date_to          = new Carbon($request->ads_to.'23:59:59');
        $quantity = $date_to->diffInDays($date_from) + 1;
        break;
    }
    $quantity = (float) $quantity;
    $price = PriceAds::where('type_ads',$request->type)
                     ->where('type_apply',$request->type_apply)
                     ->where('min','<=',$quantity)
                     ->where('max','>=',$quantity)
                     ->first();
    if(!$price){
      $price = PriceAds::where('type_ads',$request->type)
                     ->where('type_apply',$request->type_apply)
                     ->where('default','1')
                     ->first();
      if(!$price){
        $price = PriceAds::where('type_ads',$request->type)
                       ->where('type_apply',$request->type_apply)
                       ->where('min','<=',$quantity)
                       ->first();
      }
    }
    if($price){
      $arrReturn = [
        'price' => $price->price,
        'total' => $price->price*$quantity
      ];
    }
    return response()->json($arrReturn);
  }

	public function postCreateAdsOld(Request $request){
    // pr($request->all());die;
		$arrReturn = [
			'error'=>1,
			'message'=> '',
			'data'=>[]
		];
		$rules = [
      'type' => 'required',
      'content' => 'required',
    ];
    $messages = [
      'type.required' => trans('valid.type_required'),
      'content.required' => trans('valid.content_required'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
    	$arrReturn['message'] = $validator->errors()->first();
      return response()->json($arrReturn);
    } else {
      $check = true;
      $coin = $request->total?$request->total:0;
      $content = Content::find($request->content);
      $trans = new TransactionCoin();
      $check = $check && $trans->pay(Auth::guard('web_client')->user(), $coin, trans('transaction.pay_for_ads_content',['content'=>$content->name, 'coin'=>$coin]));
      $transaction_id = $trans->getTransfer();

      if($check){
        $ads = new Ads();
        $ads->type_ads             = $request->type;
        $ads->type_apply       = $request->type_apply ;
        switch ($request->type_apply) {
          case 'date':
            $ads->date_from        = new Carbon($request->ads_from.'00:00:00');
            $ads->date_to          = new Carbon($request->ads_to.'23:59:59');
            break;
          case 'click':
            $ads->click        = $request->click;
            break;
          case 'view':
            $ads->view        = $request->view;
            break; 
          default:
            $ads->date_from        = new Carbon($request->ads_from.'00:00:00');
            $ads->date_to          = new Carbon($request->ads_to.'23:59:59');
            break;
        }
        $ads->price = $request->price?$request->price:0;
        $ads->total = $request->total?$request->total:0;
        $arr_image = [];
        if(in_array($request->kind,['banner'])){
          if($request->ads_image){
            $path = public_path() . '/upload/ads/';
            if (!\File::exists($path)) {
                \File::makeDirectory($path, $mode = 0777, true, true);
            }
            $file = $request->ads_image;
            $name = time() . '_ads_.' . $file->getClientOriginalExtension();
            $link = '/upload/ads/' .time() . '_ads_.' . $file->getClientOriginalExtension();
            $file->move($path,$name);
            $arr_image[] = $link;
          }else{
            if($request->media_ads){
              $arr_image = $request->media_ads;
            }else{
              $arrReturn['message'] = trans('valid.image_required');
              return response()->json($arrReturn);
            }
          }
        }
        $ads->content_id = $request->content;
        $ads->created_by       = Auth::guard('web_client')->user()->id ;
        $ads->updated_by       = Auth::guard('web_client')->user()->id ;

        if($ads->save()){
          foreach ($arr_image as $link) {
            $media = new MediaAds();
            $media->link       = $link;
            $media->ads_id     = $ads->id;
            $media->created_by = Auth::guard('web_client')->user()->id ;
            $media->updated_by = Auth::guard('web_client')->user()->id ;
            $media->save();      
          }
          $arrReturn['error'] = 0;
          $arrReturn['message'] = trans('Location'.DS.'user.ads').' '.trans('valid.added_successful').'<br/>'.trans('valid.approve_24',['object'=>trans('Location'.DS.'user.ads')]);
        }
      }else{
        $arrReturn['message'] = $trans->getError()->getMessage();
      }

      if($check){
        //create notifi admin
        $link = ADMIN_URL.'/ads/ads';
        $content_notifi = trans('Location'.DS.'user.notify_admin_create_ads',['content'=>$content->name]);
        $notifi_admin = new NotifiAdmin();
        $notifi_admin->createNotifi($content_notifi,$link);
      }

      if($check){
        //create notifi user
        $content_notifi = 'Location'.DS.'user.notify_user_create_ads';
        $notifi = new Notifi();
        $notifi->createNotifiUserByTemplate($content_notifi,Auth::guard('web_client')->user()->id,['content'=>$content->name]);
      }

    	
    	return response()->json($arrReturn);
    }
	}

  public function postPublishAds(Request $request){
    // pr($request->all());die;
    $arrReturn = [
      'error'=>1,
      'message'=> '',
      'data'=>[]
    ];

    $check = true;
    $coin = $request->total?$request->total:0;
    $ads = Ads::find($request->id);
    if(!$ads){
      $arrReturn['message'] = '';
    }
    $trans = new TransactionCoin();
    if($ads->choose_type=='content'){
       $content = Content::find($ads->content_id);
       $check = $check && $trans->pay(Auth::guard('web_client')->user(), $coin, trans('transaction.pay_for_ads_content',['content'=>$content->name, 'coin'=>$coin]));
    }else{
      $check = $check && $trans->pay(Auth::guard('web_client')->user(), $coin, trans('transaction.pay_for_ads_content',['content'=>$ads->name, 'coin'=>$coin]));
    }
    
    $transaction_id = $trans->getTransfer();


    if($check){
      $publish_ads = new PublishAds();
      $publish_ads->ads_id   = $ads->id;
      $publish_ads->name     = $ads->name;
      $publish_ads->image     = $ads->image;
      $publish_ads->link     = $ads->link;
      $publish_ads->choose_type     = $ads->choose_type;
      $publish_ads->content_id     = $ads->content_id;

      $publish_ads->type_ads = $ads->type_ads;

      $publish_ads->type_apply       = $request->type_apply ;
      switch ($request->type_apply) {
        case 'date':
          $publish_ads->date_from        = new Carbon($request->ads_from.'00:00:00');
          $publish_ads->date_to          = new Carbon($request->ads_to.'23:59:59');
          break;
        case 'click':
          $publish_ads->click        = $request->click;
          break;
        case 'view':
          $publish_ads->view        = $request->view;
          break; 
        default:
          $publish_ads->date_from        = new Carbon($request->ads_from.'00:00:00');
          $publish_ads->date_to          = new Carbon($request->ads_to.'23:59:59');
          break;
      }
      $publish_ads->price = $request->price?$request->price:0;
      $publish_ads->total = $request->total?$request->total:0;
      $publish_ads->active = 1;

      if($request->country_ads == 'all'){
        $publish_ads->country = 0;
      }else{
        $publish_ads->country = $request->country_ads?$request->country_ads:0;
      }

      if($request->city_ads == 'all'){
        $publish_ads->city = 0;
      }else{
        $publish_ads->city = $request->city_ads?$request->city_ads:0;
      }
      

      $publish_ads->created_by       = Auth::guard('web_client')->user()->id ;
      $publish_ads->updated_by       = Auth::guard('web_client')->user()->id ;

      if($publish_ads->save()){
        $payment = new PaymentAds();
        $payment->publish_ads_id = $publish_ads->id;
        $payment->content_id = $publish_ads->content_id;
        $payment->type_apply = $publish_ads->type_apply;
        $payment->created_by = $publish_ads->created_by;

        $payment->price = $publish_ads->price;
        $payment->total = $publish_ads->total;

        $quantity = 0;
        switch ($publish_ads->type_apply) {
          case 'date':
            $date_from        = new Carbon($publish_ads->date_from);
            $date_to          = new Carbon($publish_ads->date_to);
            $quantity = $date_to->diffInDays($date_from) + 1;
            break;
          case 'click':
            $quantity       = $publish_ads->click;
            break;
          case 'view':
            $quantity        = $publish_ads->view;
            break; 
          default:
            $date_from        = new Carbon($publish_ads->date_from);
            $date_to          = new Carbon($publish_ads->date_to);
            $quantity = $date_to->diffInDays($date_from) + 1;
            break;
        }
        $quantity = (float) $quantity;
        $payment->quantity = $quantity;
        $payment->created_by       = Auth::guard('web_client')->user()->id ;
        $payment->updated_by       = Auth::guard('web_client')->user()->id ;
        $payment->save();

        if($publish_ads->choose_type == 'content'){
          createClientStatic('quang_cao',$payment->id,$payment->content_id,$transaction_id);
        }
        
        $arrReturn['error'] = 0;
        $arrReturn['message'] = trans('Location'.DS.'user.ads').' '.trans('valid.published_successful');
      }
    }else{
      $arrReturn['message'] = $trans->getError()->getMessage();
    }    
    return response()->json($arrReturn);
  }

  public function postCreateAds(Request $request){
    // pr($request->all());die;
    $arrReturn = [
      'error'=>1,
      'message'=> '',
      'data'=>[]
    ];
    $rules = [
      'type' => 'required',
      'name' => 'required',
      'ads_image' => 'required',
    ];
    $messages = [
      'type.required' => trans('Location'.DS.'user.no_choose_ads_type'),
      'ads_image.required' => trans('Location'.DS.'user.no_choose_ads_image'),
      'name.required' => trans('Location'.DS.'user.no_choose_ads_name'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      $arrReturn['message'] = $validator->errors()->first();
      return response()->json($arrReturn);
    } else {
      if($request->choose_type == 'link' && $request->link ==''){
        $arrReturn['message'] = trans('Location'.DS.'user.no_choose_ads_link');
        return response()->json($arrReturn);
      }else{
        $check_link = false;
        $url = filter_var($request->link, FILTER_SANITIZE_URL);
        if(!filter_var($url, FILTER_VALIDATE_URL) === false){
          $check_link = true;
        }
        if($request->choose_type == 'link' && $check_link === false){
          $arrReturn['message'] = trans('Location'.DS.'user.wrong_link');
          return response()->json($arrReturn);
        }
      }

      if($request->choose_type == 'content' && !$request->content){
        $arrReturn['message'] = trans('Location'.DS.'user.no_choose_ads_content');
        return response()->json($arrReturn);
      }

      $ads = new Ads();
      $ads->name             = $request->name;

      $ads->type_ads             = $request->type;
      if($request->ads_image){
        $path = public_path() . '/upload/ads/';
        if (!\File::exists($path)) {
          \File::makeDirectory($path, $mode = 0777, true, true);
        }
        $file = $request->ads_image;
        $name = time() . '_ads_.' . $file->getClientOriginalExtension();
        $link = '/upload/ads/' .time() . '_ads_.' . $file->getClientOriginalExtension();

        $image_resize = Image::make($file->getRealPath());              
        $image_resize->resize($request->width,$request->height);
        $image_resize->save($path.$name);
        $ads->image = $link;
      }
      if($request->choose_type == 'link'){
        $ads->link = $request->link;
      }
      if($request->choose_type == 'content'){
        $ads->content_id = $request->content;
        $content = Content::find($request->content);
      }
      $ads->choose_type      = $request->choose_type;
      $ads->created_by       = Auth::guard('web_client')->user()->id ;
      $ads->updated_by       = Auth::guard('web_client')->user()->id ;

      if($ads->save()){
        //create notifi admin
        $link = ADMIN_URL.'/ads/ads';
        if($request->choose_type == 'content'){
          $content_notifi = trans('Location'.DS.'user.notify_admin_create_ads',['content'=>$content->name]);
        }else{
          $content_notifi = trans('Location'.DS.'user.notify_admin_create_ads',['content'=>'']);
        }
        $notifi_admin = new NotifiAdmin();
        $notifi_admin->createNotifi($content_notifi,$link);

        //create notifi user
        $content_notifi = 'Location'.DS.'user.notify_user_create_ads';
        $notifi = new Notifi();
        if($request->choose_type == 'content'){
          $notifi->createNotifiUserByTemplate($content_notifi,Auth::guard('web_client')->user()->id,['content'=>$content->name]);
        }else{
          $notifi->createNotifiUserByTemplate($content_notifi,Auth::guard('web_client')->user()->id,['content'=>'']);
        }

        $arrReturn['error'] = 0;
        $arrReturn['message'] = trans('Location'.DS.'user.ads').' '.trans('valid.added_successful').'<br/>'.trans('valid.approve_24',['object'=>trans('Location'.DS.'user.ads')]);
      }



      // if($check){
      //   $ads = new Ads();
      //   $ads->type_ads             = $request->type;
      //   $ads->type_apply       = $request->type_apply ;
      //   switch ($request->type_apply) {
      //     case 'date':
      //       $ads->date_from        = new Carbon($request->ads_from.'00:00:00');
      //       $ads->date_to          = new Carbon($request->ads_to.'23:59:59');
      //       break;
      //     case 'click':
      //       $ads->click        = $request->click;
      //       break;
      //     case 'view':
      //       $ads->view        = $request->view;
      //       break; 
      //     default:
      //       $ads->date_from        = new Carbon($request->ads_from.'00:00:00');
      //       $ads->date_to          = new Carbon($request->ads_to.'23:59:59');
      //       break;
      //   }
      //   $ads->price = $request->price?$request->price:0;
      //   $ads->total = $request->total?$request->total:0;
      //   $arr_image = [];
      //   if(in_array($request->kind,['banner'])){
      //     if($request->ads_image){
      //       $path = public_path() . '/upload/ads/';
      //       if (!\File::exists($path)) {
      //           \File::makeDirectory($path, $mode = 0777, true, true);
      //       }
      //       $file = $request->ads_image;
      //       $name = time() . '_ads_.' . $file->getClientOriginalExtension();
      //       $link = '/upload/ads/' .time() . '_ads_.' . $file->getClientOriginalExtension();
      //       $file->move($path,$name);
      //       $arr_image[] = $link;
      //     }else{
      //       if($request->media_ads){
      //         $arr_image = $request->media_ads;
      //       }else{
      //         $arrReturn['message'] = trans('valid.image_required');
      //         return response()->json($arrReturn);
      //       }
      //     }
      //   }
      //   $ads->content_id = $request->content;
      //   $ads->created_by       = Auth::guard('web_client')->user()->id ;
      //   $ads->updated_by       = Auth::guard('web_client')->user()->id ;

      //   if($ads->save()){
      //     foreach ($arr_image as $link) {
      //       $media = new MediaAds();
      //       $media->link       = $link;
      //       $media->ads_id     = $ads->id;
      //       $media->created_by = Auth::guard('web_client')->user()->id ;
      //       $media->updated_by = Auth::guard('web_client')->user()->id ;
      //       $media->save();      
      //     }
      //     $arrReturn['error'] = 0;
      //     $arrReturn['message'] = trans('Location'.DS.'user.ads').' '.trans('valid.added_successful').'<br/>'.trans('valid.approve_24',['object'=>trans('Location'.DS.'user.ads')]);
      //   }
      // }else{
      //   $arrReturn['message'] = $trans->getError()->getMessage();
      // }

      // if($check){
      //   //create notifi admin
      //   $link = ADMIN_URL.'/ads/ads';
      //   $content_notifi = trans('Location'.DS.'user.notify_admin_create_ads',['content'=>$content->name]);
      //   $notifi_admin = new NotifiAdmin();
      //   $notifi_admin->createNotifi($content_notifi,$link);
      // }

      // if($check){
      //   //create notifi user
      //   $content_notifi = 'Location'.DS.'user.notify_user_create_ads';
      //   $notifi = new Notifi();
      //   $notifi->createNotifiUserByTemplate($content_notifi,Auth::guard('web_client')->user()->id,['content'=>$content->name]);
      // }

      
      return response()->json($arrReturn);
    }
  }
  // public function postDeleteImage(Request $request){
  //   $id = $request->id;
  //   $image = AdsImage::find($id);
  //   if (file_exists(public_path($image['link']))) {
  //     unlink(public_path($image['link']));
  //   }
  //   if (file_exists(public_path(str_replace('discount', 'discount_thumbnail', $image['link'])))) {
  //     unlink(public_path(str_replace('discount', 'discount_thumbnail', $image['link'])));
  //   }
  //   $image->delete();
  //   echo 'sussess';
  // }

  public function postUpdateAds(Request $request, $ads_id){
    $arrReturn = [
      'error'=>1,
      'message'=> '',
      'data'=>[]
    ];

    $ads = Ads::where('id',$ads_id)
                  ->with('_media_ads')
                  ->with('_type_ads')
                  ->first();
    if(!$ads){
      $arrReturn['message'] = trans('valid.not_found',['object'=>trans('Location'.DS.'user.ads')]);
    }
    if($ads->_type_ads->kind=='banner'){
      if($request->ads_image){
        $path = public_path() . '/upload/ads/';
        if (!\File::exists($path)) {
            \File::makeDirectory($path, $mode = 0777, true, true);
        }
        $file = $request->ads_image;
        $name = time() . '_ads_.' . $file->getClientOriginalExtension();
        $link = '/upload/ads/' .time() . '_ads_.' . $file->getClientOriginalExtension();
        $file->move($path,$name);
        $arr_image[] = $link;
        $ads->declined = 0;
        if($ads->save()){
          MediaAds::where('ads_id',$ads->id)->delete();
          foreach ($arr_image as $link) {
            $media = new MediaAds();
            $media->link       = $link;
            $media->ads_id     = $ads->id;
            $media->updated_by = Auth::guard('web_client')->user()->id ;
            $media->save();      
          }
          $arrReturn['error'] = 0;
          $arrReturn['message'] = trans('Location'.DS.'user.ads').' '.trans('valid.updated_successful');
        }
      }else{
        $arrReturn['message'] = trans('valid.image_required');
      }
    }
    return response()->json($arrReturn);
  }

  public function getTypeAds(Request $request){
    $type_ads = TypeAds::where('kind', $request->kind)
                       ->where('active',1)
                       ->get();
    if($type_ads){
      $type_ads = $type_ads->toArray();
    }else{
      $type_ads = [];
    }
    return response()->json($type_ads);
  }

  public function getAds(Request $request){
    $arrReturn = [];
    $type_ads = $request->type_ads;
    $limit = $request->limit;

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

    $arrReturn['ads'] = PublishAds::select('publish_ads.*')
                      ->selectRaw("RAND () as id_rand ")
                      ->with('_base_content')
                      ->leftJoin('type_ads','type_ads.id','publish_ads.type_ads')
                      ->where('type_ads.machine_name',$type_ads)
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
                      ->limit($limit)
                      ->get();
    foreach ($arrReturn['ads'] as $key => $ads) {
      if($ads->type_apply=='view'){
        $ads->viewed = $ads->viewed + 1;
        $ads->save();
      }
    } 
    $arrReturn['type_ads'] = TypeAds::where('machine_name',$type_ads)->first();
    return response()->json($arrReturn);
  }
}
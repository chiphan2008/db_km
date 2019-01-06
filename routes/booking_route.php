<?php
use App\Models\Location\Category;
use App\Models\Location\CategoryItem;
use App\Models\Location\Country;
use App\Models\Location\City;
use App\Models\Location\Content;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Location\CustomPage;
use App\Models\Location\CustomPageLanguage;


// ROUTE FRONT END
Route::any('/', 'Booking\\HomeController@anyIndex')->name('index');
Route::get('setlanguage/{lang}', 'Booking\\BaseController@setLangue');
Route::group(['prefix' => 'error'], function () {
  Route::any('/{code}', 'Booking\\ErrorController@anyIndex');
});
Route::get('/getLocation','Location\\CategoryController@getLocation');
Route::post('/saveLocation','Location\\HomeController@postSaveLocation');

Route::get('test', 'Location\\TestController@getIndex');
Route::post('login', 'Location\\LoginController@login');
Route::get('client_logout', 'Location\\LoginController@logout')->name('logoutClient');

Route::post('register', 'Location\\LoginController@postRegisterClient');

Route::get('active_client/{token}', 'Location\\LoginController@getActiveClient');

Route::post('client-password/email', 'Location\\ForgotPasswordController@checkStatusClient');
Route::get('client-password/reset/{token}/{email}', 'Location\\ResetPasswordController@showResetForm');
Route::post('client-password/reset', 'Location\\ResetPasswordController@reset');

Route::post('like-content', 'Location\\ContentController@getLikeAjax');
Route::post('vote-content', 'Location\\ContentController@getVoteAjax');
Route::post('checkin-content', 'Location\\ContentController@getCheckinAjax');
Route::post('save-like-content', 'Location\\ContentController@getSaveLikeAjax');

Route::post('readNotifi', 'Location\\BaseController@readNotifi');


// //Route::post('bank/getBankAjax', 'Location\\ContentController@getBankAjax');
// Route::get('detail-photo/{param_1}/{param_2}','Location\\ContentController@getDetailPhoto');

Route::get('/client/{provider}', 'Location\\SocialFacebookController@redirectToProvider');
Route::get('/client/{provide}/callback', 'Location\\SocialFacebookController@handleProviderCallback');
Route::post('/getContentByCategory','Location\\CategoryController@postAjaxContentByCategoryItem');
Route::get('/getLocation','Location\\CategoryController@getLocation');
Route::any('/search','Location\\SearchController@anySearch');
Route::post('/search/loadCity','Location\\SearchController@postLoadCity');
Route::post('/search/loadDistrict','Location\\SearchController@postLoadDistrict');
Route::post('/search/loadCategoryItem','Location\\SearchController@postloadCategoryItem');
Route::any('/ajaxSearch','Location\\SearchController@anyAjaxSearch');
Route::post('/saveLocation','Location\\HomeController@postSaveLocation');

// Route::post('/createLocationFrontend/StepOne','Location\\AddLocationController@postAllData');
// Route::post('/createLocationFrontend/StepTwo','Location\\AddLocationController@postValidation');
// Route::post('/createLocationFrontend/postLocation','Location\\AddLocationController@postAjaxLocation');
// Route::post('/createLocationFrontend/postCreateLocation','Location\\AddLocationController@postCreateLocation');
// Route::any('/createLocationFrontend/previewLocation','Location\\AddLocationController@previewLocation');

// Route::post('/editLocationFrontend/postEditLocation','Location\\AddLocationController@postEditLocation');

// Route::get('/edit/location/{id_content}','Location\\AddLocationController@getEditLocation')->where(['id_content' => '[0-9]+']);
// Route::get('/changeStatusClose/location/{id_content}','Location\\AddLocationController@getChangeStatusCloseLocation')->where(['id_content' => '[0-9]+']);
// Route::get('/changeStatusOpen/location/{id_content}','Location\\AddLocationController@getChangeStatusOpenLocation')->where(['id_content' => '[0-9]+']);
// Route::get('/delete/location/{id_content}', 'Location\\AddLocationController@getDeleteLocation')->where(['id_content' => '[0-9]+']);
// Route::post('/editLocationFrontend/deleteImage', 'Location\\AddLocationController@getDeleteEditImage');
// Route::post('/popupNotifyContent', 'Location\\UserController@popupNotifyContent');
// Route::post('/postPopupNotifyContent', 'Location\\UserController@postPopupNotifyContent')->name('postPopupNotifyContent');
// Route::get('/push-content/{id_content}', 'Location\\ContentController@getPushContent')->where(['id_content' => '[0-9]+']);
// Route::post('/ad-content', 'Location\\ContentController@postAdContent');
// Route::get('/confirm-location/{id_content}',function($id_content){
//   $content = Content::where('id','=',$id_content)
//                     ->with('_country')
//                     ->with('_city')
//                     ->with('_district')
//                     ->first();
//   if(!$content){
//     abort(404);
//   }
//   $arrParam['content'] = $content;
//   return \App()->make('App\\Http\\Controllers\\Location\\ContentController')->callAction('getConfirmContent',$arrParam);
// });

// Route::post('/confirm-location/{id_content}', 'Location\\ContentController@postConfirmContent');


// Route::any('/contact',function(Request $request){

//   $arrParam = [];
//   if(empty($request->all()))
//   {
//     $arrParam['link'] = 'contact';
//     return \App()->make('App\\Http\\Controllers\\Location\\PageController')->callAction('anyPage',$arrParam);
//   }
//   else
//   {
//     $arrParam['data'] = $request->all();
//     return \App()->make('App\\Http\\Controllers\\Location\\PageController')->callAction('postContactPage',$arrParam);
//   }

// });
Route::group(['prefix' => 'user'], function () {
  Route::get('/{id_user}', 'Location\\UserController@getUser');
  Route::post('/{id_user}', 'Location\\UserController@postUser');
  Route::get('/{id_user}/check-in', 'Location\\UserController@getUserCheckin');
  Route::get('/{id_user}/management-location', 'Location\\UserController@getUserManagementLocation');
  Route::get('/{id_user}/like-location', 'Location\\UserController@getUserLikeLocation');
  Route::get('/{id_user}/like', 'Location\\UserController@getUserLike');
  Route::get('/{id_user}/collection', 'Location\\UserController@getUserCollection');
  Route::get('/{id_user}/friend', 'Location\\UserController@getUserFriend');
  Route::get('/{id_user}/change-password', 'Location\\UserController@getUserChangePassword');
  Route::post('/{id_user}/change-password', 'Location\\UserController@postUserChangePassword');
  Route::post('/{id_user}/update-avatar', 'Location\\UserController@postUserUpdateAvatar');
  Route::get('/{id_user}/wallet', 'Location\\UserController@getUserWallet');
});

Route::group(['prefix' => 'collection'], function () {
  Route::post('/createCollection', 'Location\\CollectionController@postCreateCollection');
  Route::post('/addCollection', 'Location\\CollectionController@postAddCollection');
  Route::post('/updateCollection', 'Location\\CollectionController@postUpdateCollection');
  Route::post('/removeCollection', 'Location\\CollectionController@postRemoveCollection');
  Route::post('/deleteCollection', 'Location\\CollectionController@postDeleteCollection');
}); 

Route::group(['prefix' => 'comment'], function () {
  Route::post('/createCommentContent', 'Location\\CommentController@postcreateCommentContent');
  Route::post('/likeComment', 'Location\\CommentController@postlikeComment');
  Route::post('/loadComment', 'Location\\CommentController@postloadComment');
}); 

Route::group(['prefix' => 'notice'], function () {
  Route::get('/test', 'Location\\NoticeController@getTest');
});

Route::group(['prefix' => 'list'], function () {
  Route::any('/{param_1}', function(Request $request, $param_1){
    $city = City::where('alias','=',$param_1)
      ->first();
    $arrParam = [];
    if($city){
      $arrParam['city'] = $city;
      $arrParam['request'] = $request;
      $lang = $request->session()->get('language');
      if ($lang != null) {
        \App::setLocale($lang);
      } else {
        $lang = \App::getLocale();
        $request->session()->put('language', $lang);
      }
      $arrParam['param'] = null;
      return \App()->make('App\\Http\\Controllers\\Booking\\ListController')->callAction('getHotelByCity',$arrParam);
    }else{
      abort(404);
    }
  });

  // Route::any('/{param_1}/{param_2}', function(Request $request, $param_1, $param_2){
  //   $category = Category::where('alias','=',$param_1)
  //     ->where('deleted','=',0)
  //     ->where('active','=',1)
  //     ->first();
  //   $arrParam = [];
  //   if($category){
  //     $arrParam['category'] = $category;
  //     $arrParam['request'] = $request;
  //     $lang = $request->session()->get('language');
  //     if ($lang != null) {
  //       \App::setLocale($lang);
  //     } else {
  //       $lang = \App::getLocale();
  //       $request->session()->put('language', $lang);
  //     }
  //     $arrParam['param'] = null;
  //     $arrParam['param']['category_item'] = $param_2;
  //     return \App()->make('App\\Http\\Controllers\\Location\\CategoryController')->callAction('getContentByCategory',$arrParam);
  //   }else{
  //     abort(404);
  //   }
  // });

  // Route::any('/{param_1}/{param_2}/{param_3}', function(Request $request, $param_1, $param_2, $param_3){
  //   $category = Category::where('alias','=',$param_1)
  //     ->where('deleted','=',0)
  //     ->where('active','=',1)
  //     ->first();
  //   $arrParam = [];
  //   if($category){
  //     $arrParam['category'] = $category;
  //     $arrParam['request'] = $request;
  //     $lang = $request->session()->get('language');
  //     if ($lang != null) {
  //       \App::setLocale($lang);
  //     } else {
  //       $lang = \App::getLocale();
  //       $request->session()->put('language', $lang);
  //     }
  //     $arrParam['param'] = null;
  //     $arrParam['param']['category_item'] = $param_2;
  //     $arrParam['param']['country'] = $param_3;
  //     return \App()->make('App\\Http\\Controllers\\Location\\CategoryController')->callAction('getContentByCategory',$arrParam);
  //   }else{
  //     abort(404);
  //   }
  // });

  // Route::any('/{param_1}/{param_2}/{param_3}/{param_4}', function(Request $request, $param_1, $param_2, $param_3, $param_4){
  //   $category = Category::where('alias','=',$param_1)
  //     ->where('deleted','=',0)
  //     ->where('active','=',1)
  //     ->first();
  //   $arrParam = [];
  //   if($category){
  //     $arrParam['category'] = $category;
  //     $arrParam['request'] = $request;
  //     $lang = $request->session()->get('language');
  //     if ($lang != null) {
  //       \App::setLocale($lang);
  //     } else {
  //       $lang = \App::getLocale();
  //       $request->session()->put('language', $lang);
  //     }
  //     $arrParam['param'] = null;
  //     $arrParam['param']['category_item'] = $param_2;
  //     $arrParam['param']['country'] = $param_3;
  //     $arrParam['param']['city'] = $param_4;
  //     return \App()->make('App\\Http\\Controllers\\Location\\CategoryController')->callAction('getContentByCategory',$arrParam);
  //   }else{
  //     abort(404);
  //   }
  // });

  // Route::any('/{param_1}/{param_2}/{param_3}/{param_4}/{param_5}',function(Request $request, $param_1,$param_2,$param_3,$param_4,$param_5){
  //   $category = Category::where('alias','=',$param_1)
  //     ->where('deleted','=',0)
  //     ->where('active','=',1)
  //     ->first();
  //   $arrParam = [];
  //   if($category){
  //     $arrParam['category'] = $category;
  //     $arrParam['request'] = $request;
  //     $lang = $request->session()->get('language');
  //     if ($lang != null) {
  //       \App::setLocale($lang);
  //     } else {
  //       $lang = \App::getLocale();
  //       $request->session()->put('language', $lang);
  //     }
  //     $arrParam['param']['category_item'] = $param_2;
  //     $arrParam['param']['country'] = $param_3;
  //     $arrParam['param']['city'] = $param_4;
  //     $arrParam['param']['district'] = $param_5;
  //     return \App()->make('App\\Http\\Controllers\\Location\\CategoryController')->callAction('getContentByCategory',$arrParam);
  //   }else{
  //     abort(404);
  //   }
  // });
});



// 3 param
// Route::get('/{param_1}/{param_2}/{param_3}', function($param_1,$param_2,$param_3){
//   dd($param_1,$param_2,$param_3);
// })->where(['param_1'=>'((?!admin).)*$']);;

// 2 param
Route::get('/{param_1}/{param_2}', function(Request $request, $param_1,$param_2){
  $category_item = CategoryItem::where('alias','=',$param_2)->where("deleted",'=',0)->first();
  $category = Category::where('alias','=',$param_1)->where("deleted",'=',0)->with('category_items')->first();
  if($category && $category_item){
    $arrParam['category'] = $category;
    $arrParam['category_item'] = $category_item;
    $arrParam['request'] = $request;
    $lang = $request->session()->get('language');
    if ($lang != null) {
      \App::setLocale($lang);
    } else {
      $lang = \App::getLocale();
      $request->session()->put('language', $lang);
    }
    return  app()->make('App\\Http\\Controllers\\Location\\CategoryController')->callAction('getContentByCategoryItem',$arrParam);
  }else{
    abort(404);
  }
})->where(['param_1'=>'((?!admin).)*$']);

// // 1 param
Route::any('/{param_1}', function(Request $request, $param_1){
  $category = Category::where('alias','=',$param_1)->first();
  $custom_page = CustomPage::where([['alias','=',$param_1],['status','=',1]])->first();
  $arrParam = [];
  $lang = $request->session()->get('language');

  if ($lang != null) {
    \App::setLocale($lang);
  } else {
    $lang = \App::getLocale();
    $request->session()->put('language', $lang);
  }
  if($category){
    $arrParam['category'] = $category;
    $arrParam['category_item'] = null;
    $arrParam['request'] = $request;
    $lang = $request->session()->get('language');
    if ($lang != null) {
      \App::setLocale($lang);
    } else {
      $lang = \App::getLocale();
      $request->session()->put('language', $lang);
    }
    return  app()->make('App\\Http\\Controllers\\Location\\CategoryController')->callAction('getContentByCategoryItem',$arrParam);
 // }
 // elseif ($custom_page){
 //   $custom_page_lang = CustomPageLanguage::where([['id_custom_page','=',$custom_page->id],['lang','=',$lang]])->first();
 //   if(isset($custom_page_lang))
 //   {
 //     $arrParam['custom_page'] = $custom_page_lang;
 //     $arrParam['link'] = $custom_page->alias;
 //   }
 //   else {
 //     $arrParam['custom_page'] = $custom_page;
 //     $arrParam['link'] = $custom_page->alias;
 //   }
 //   return \App()->make('App\\Http\\Controllers\\Location\\PageController')->callAction('anyPage',$arrParam);
  }else{
    $country = null;
    $country = Country::where('alias','=',$param_1)->first();
    if($country){
      $arrParam['country'] = $country;
      return \App()->make('App\\Http\\Controllers\\Location\\LocationController')->callAction('getContentByCountry',$arrParam);
    }else{
      $content = null;
      $content = Content::select('*')
                        ->where([['alias','=',$param_1],['moderation','=','publish'],['active','=',1]])
                        ->with('_category_type')
                        ->with('_comments')
                        ->with('_category_items');

      if($request->session()->has('currentLocation')){
        $currentLocation = explode(',', $request->session()->get('currentLocation'));
        $lat = $currentLocation[0];
        $lng = $currentLocation[1];
        $content = $content->selectRaw("
                          (SQRT(
                            POW((`lng` - '+$lng+')*COS((`lat` + '+$lat+'))/2,2)
                            +
                            POW((`lat` - '+$lat+'),2)
                            )*2*3.14*6371000/360) AS line
                          ");
      }
      $content = $content->first();
      
      if($content){
        $arrParam['content'] = $content;
        $arrParam['request'] = $request;
        if(isset($content->_category_type)){
          if($content->_category_type->machine_name == 'food' || $content->_category_type->machine_name == 'drinks'
            || $content->_category_type->machine_name == 'hotel'||$content->_category_type->machine_name == 'entertainment'
            || $content->_category_type->machine_name == 'mua_sam' ){
            return \App()->make('App\\Http\\Controllers\\Location\\ContentController')->callAction('getContentFoodByAlias',$arrParam);
          }
          else if($content->_category_type->machine_name == 'bank'){
            if(isset($content->extra_type))
            {
              return \App()->make('App\\Http\\Controllers\\Location\\ContentController')->callAction('getContentBankByAlias',$arrParam);
            }
            else{
              abort(404);
            }
          }
          else if($content->_category_type->machine_name == 'shop'){
            return \App()->make('App\\Http\\Controllers\\Location\\ContentController')->callAction('getContentShopByAlias',$arrParam);
          }
          else{
            return \App()->make('App\\Http\\Controllers\\Location\\ContentController')->callAction('getContentFoodByAlias',$arrParam);
          }
        }else{
          return \App()->make('App\\Http\\Controllers\\Location\\ContentController')->callAction('getContentFoodByAlias',$arrParam);
        }
      }else{
        abort(404);
      }
    }
  }
});

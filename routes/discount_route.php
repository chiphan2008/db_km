<?php
use App\Models\Location\Category;
use App\Models\Location\CategoryItem;
use App\Models\Location\Country;
use App\Models\Location\Content;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Location\CustomPage;
use App\Models\Location\CustomPageLanguage;


// ROUTE FRONT END
Route::any('/', 'Discount\\HomeController@anyIndex')->name('index');
Route::post('/getContentByCategory','Discount\\CategoryController@postAjaxContentByCategoryItem');

Route::group(['prefix' => 'list'], function () {
  Route::any('/{param_1}', function(Request $request, $param_1){
    $category = Category::where('alias','=',$param_1)
      ->where('deleted','=',0)
      ->where('active','=',1)
      ->first();
    $arrParam = [];
    if($category){
      $arrParam['category'] = $category;
      $arrParam['request'] = $request;
      $lang = $request->session()->get('language');
      if ($lang != null) {
        \App::setLocale($lang);
      } else {
        $lang = \App::getLocale();
        $request->session()->put('language', $lang);
      }
      $arrParam['param'] = null;
      return \App()->make('App\\Http\\Controllers\\Discount\\CategoryController')->callAction('getContentByCategory',$arrParam);
    }else{
      abort(404);
    }
  });

  Route::any('/{param_1}/{param_2}', function(Request $request, $param_1, $param_2){
    $category = Category::where('alias','=',$param_1)
      ->where('deleted','=',0)
      ->where('active','=',1)
      ->first();
    $arrParam = [];
    if($category){
      $arrParam['category'] = $category;
      $arrParam['request'] = $request;
      $lang = $request->session()->get('language');
      if ($lang != null) {
        \App::setLocale($lang);
      } else {
        $lang = \App::getLocale();
        $request->session()->put('language', $lang);
      }
      $arrParam['param'] = null;
      $arrParam['param']['category_item'] = $param_2;
      return \App()->make('App\\Http\\Controllers\\Discount\\CategoryController')->callAction('getContentByCategory',$arrParam);
    }else{
      abort(404);
    }
  });

  Route::any('/{param_1}/{param_2}/{param_3}', function(Request $request, $param_1, $param_2, $param_3){
    $category = Category::where('alias','=',$param_1)
      ->where('deleted','=',0)
      ->where('active','=',1)
      ->first();
    $arrParam = [];
    if($category){
      $arrParam['category'] = $category;
      $arrParam['request'] = $request;
      $lang = $request->session()->get('language');
      if ($lang != null) {
        \App::setLocale($lang);
      } else {
        $lang = \App::getLocale();
        $request->session()->put('language', $lang);
      }
      $arrParam['param'] = null;
      $arrParam['param']['category_item'] = $param_2;
      $arrParam['param']['country'] = $param_3;
      return \App()->make('App\\Http\\Controllers\\Discount\\CategoryController')->callAction('getContentByCategory',$arrParam);
    }else{
      abort(404);
    }
  });

  Route::any('/{param_1}/{param_2}/{param_3}/{param_4}', function(Request $request, $param_1, $param_2, $param_3, $param_4){
    $category = Category::where('alias','=',$param_1)
      ->where('deleted','=',0)
      ->where('active','=',1)
      ->first();
    $arrParam = [];
    if($category){
      $arrParam['category'] = $category;
      $arrParam['request'] = $request;
      $lang = $request->session()->get('language');
      if ($lang != null) {
        \App::setLocale($lang);
      } else {
        $lang = \App::getLocale();
        $request->session()->put('language', $lang);
      }
      $arrParam['param'] = null;
      $arrParam['param']['category_item'] = $param_2;
      $arrParam['param']['country'] = $param_3;
      $arrParam['param']['city'] = $param_4;
      return \App()->make('App\\Http\\Controllers\\Discount\\CategoryController')->callAction('getContentByCategory',$arrParam);
    }else{
      abort(404);
    }
  });

  Route::any('/{param_1}/{param_2}/{param_3}/{param_4}/{param_5}',function(Request $request, $param_1,$param_2,$param_3,$param_4,$param_5){
    $category = Category::where('alias','=',$param_1)
      ->where('deleted','=',0)
      ->where('active','=',1)
      ->first();
    $arrParam = [];
    if($category){
      $arrParam['category'] = $category;
      $arrParam['request'] = $request;
      $lang = $request->session()->get('language');
      if ($lang != null) {
        \App::setLocale($lang);
      } else {
        $lang = \App::getLocale();
        $request->session()->put('language', $lang);
      }
      $arrParam['param']['category_item'] = $param_2;
      $arrParam['param']['country'] = $param_3;
      $arrParam['param']['city'] = $param_4;
      $arrParam['param']['district'] = $param_5;
      return \App()->make('App\\Http\\Controllers\\Discount\\CategoryController')->callAction('getContentByCategory',$arrParam);
    }else{
      abort(404);
    }
  });
});

Route::any('/search','Discount\\SearchController@anySearch');
Route::post('/search/loadCity','Discount\\SearchController@postLoadCity');
Route::post('/search/loadDistrict','Discount\\SearchController@postLoadDistrict');
Route::post('/search/loadCategoryItem','Discount\\SearchController@postloadCategoryItem');
Route::any('/ajaxSearch','Discount\\SearchController@anyAjaxSearch');

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
    return  app()->make('App\\Http\\Controllers\\Discount\\CategoryController')->callAction('getContentByCategoryItem',$arrParam);
  }else{
    abort(404);
  }
})->where(['param_1'=>'((?!admin).)*$']);

// 1 param
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
    return  app()->make('App\\Http\\Controllers\\Discount\\CategoryController')->callAction('getContentByCategoryItem',$arrParam);
  }else{
    $country = null;
    $country = Country::where('alias','=',$param_1)->first();
    if($country){
      $arrParam['country'] = $country;
      return \App()->make('App\\Http\\Controllers\\Discount\\LocationController')->callAction('getContentByCountry',$arrParam);
    }else{
      $content = null;
      $content = Content::select('*')
                        ->where([['alias','=',$param_1],['moderation','=','publish'],['active','=',1]])
                        ->with('_category_type')
                        ->with('_discount')
                        ->with('_comments')
                        ->with('_category_items')
                        ->whereHas('_discount');

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
            return \App()->make('App\\Http\\Controllers\\Discount\\ContentController')->callAction('getContentFoodByAlias',$arrParam);
          }
          else if($content->_category_type->machine_name == 'bank'){
            if(isset($content->extra_type))
            {
              return \App()->make('App\\Http\\Controllers\\Discount\\ContentController')->callAction('getContentBankByAlias',$arrParam);
            }
            else{
              abort(404);
            }
          }
          else if($content->_category_type->machine_name == 'shop'){
            return \App()->make('App\\Http\\Controllers\\Discount\\ContentController')->callAction('getContentShopByAlias',$arrParam);
          }
          else{
            return \App()->make('App\\Http\\Controllers\\Discount\\ContentController')->callAction('getContentFoodByAlias',$arrParam);
          }
        }else{
          return \App()->make('App\\Http\\Controllers\\Discount\\ContentController')->callAction('getContentFoodByAlias',$arrParam);
        }
      }else{
        abort(404);
      }
    }
  }
});

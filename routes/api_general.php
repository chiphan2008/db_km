<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/categories','API\\CategoryController@index');
Route::get('/category/{id}','API\\CategoryController@find');

Route::get('/modules','API\\ModuleAppController@index');
Route::get('/module/{id}','API\\ModuleAppController@find');


Route::get('/countries','API\\LocationController@country');
Route::get('/country/{id}','API\\LocationController@findCountry');
Route::get('/cities/{id_country}','API\\LocationController@city');
Route::get('/city/{id}','API\\LocationController@findCity');
Route::get('/cities',function(){
	return noData();
});

Route::get('/districts/{id_city}','API\\LocationController@district');
Route::get('/district/{id}','API\\LocationController@findDistrict');
Route::get('/districts',function(){
	return noData();
});

Route::post('/content-create-comment','API\\ContentController@postcreateCommentContent');
Route::post('/content-like-comment','API\\ContentController@postlikeComment');
Route::get('/content-by-category','API\\ContentController@getContentByCategory');
Route::get('/search-content','API\\ContentController@getListContent');
Route::get('/content/{id}','API\\ContentController@detail');
Route::get('/content-update/{id}','API\\ContentController@detail_update');

Route::get('/content/image-{type}/{content_id}','API\\ContentController@getImageContent');

Route::get('/content',function(){
	return noData();
});

Route::get('/like','API\\ContentController@likeContent');
Route::get('/vote','API\\ContentController@voteContent');
Route::get('/checkin','API\\ContentController@checkinContent');
Route::get('/save-like','API\\ContentController@saveLikeContent');

// Collection route
Route::group(['prefix' => 'collection'], function () {
	Route::get('/',function(){
		return noData();
	});
	Route::post('/create','API\\CollectionController@postCreateCollection');
	Route::post('/edit','API\\CollectionController@postEditCollection');
	Route::get('/get/{id}','API\\CollectionController@getCollection');
	Route::get('/get/user/{user_id}','API\\CollectionController@getCollectionByUser');
	Route::post('/add','API\\CollectionController@postAddCollection');
	Route::post('/remove','API\\CollectionController@postRemoveCollection');
	Route::post('/delete','API\\CollectionController@postDeleteCollection');
});



Route::post('/login','API\\UserController@login');
Route::post('/register','API\\UserController@register');
Route::post('/forgot-password','API\\ForgotPasswordController@checkStatusClient');
Route::get('/logout','API\\UserController@logout');
Route::get('/check-login','API\\UserController@checkLogin');
Route::post('/login-facebook','API\\UserController@loginFB');
Route::post('/login-google','API\\UserController@loginGG');


Route::get('/get-static','API\\ContentController@getStatic');
Route::get('/list-location','API\\ContentController@getListLocation');
Route::get('/get-position','API\\ContentController@getPosition');

Route::post('/create-category-item','API\\ContentController@postCreateCategoryItem');
Route::post('/create-category','API\\ContentController@postCreateCategory');
Route::post('/create-service','API\\ContentController@postCreateService');
Route::post('/create-location','API\\ContentController@postCreateLocation');
Route::post('/update-location','API\\ContentController@postUpdateLocation');
Route::group(['prefix' => 'image'], function () {
	Route::get('/',function(){
		return noData();
	});
	Route::post('/{type}/update','API\\ContentController@updateImage');
	Route::get('/{type}/delete/{id}','API\\ContentController@deleteImage');
});


Route::group(['prefix' => 'user'], function () {
	Route::get('/',function(){
		return noData();
	});
	Route::get('/get/{id_user}','API\\UserController@getUser');
	Route::post('/update/{id_user}','API\\UserController@postUpdateUser');
	Route::get('/get-static/{id_user}','API\\UserController@getStatic');
	Route::post('/change-password','API\\UserController@postChangePassword');
	Route::get('/list-like/{id_user}','API\\UserController@getUserLikeLocation');
	Route::get('/list-checkin/{id_user}','API\\UserController@getUserCheckin');
	Route::get('/list-location/{id_user}','API\\UserController@getUserLocation');

	Route::get('/delete-like/{id_content}','API\\UserController@deleteUserLikeLocation');
	Route::get('/delete-checkin/{id_content}','API\\UserController@deleteUserCheckin');
	Route::get('/delete-location/{id_content}','API\\UserController@deleteUserLocation');
	Route::get('/open-location/{id_content}','API\\UserController@openUserLocation');
	Route::get('/close-location/{id_content}','API\\UserController@closeUserLocation');

	Route::post('/register-ctv','API\\UserController@registerCTV');
});
Route::get('search-content-user','API\\UserController@getSearchContent');
Route::get('search-user','API\\UserController@getSearchUser');
Route::post('change-owner','API\\UserController@postChangeOwner');
Route::get('apply-owner','API\\UserController@postApplyChangeOwner');


Route::group(['prefix' => 'raovat'], function () {
	Route::get('/',function(){
		return noData();
	});

	Route::get('/get/{id}','API\\RaovatController@getRaovat');

	Route::get('/get-list','API\\RaovatController@getListRaovat');
	Route::get('/get-by-type/{raovat_type}','API\\RaovatController@getListRaovatByType');
	Route::get('/get-by-kind/{raovat_kind}','API\\RaovatController@getListRaovatByKind');

	Route::post('/create','API\\RaovatController@postCreateRaovat');
	Route::post('/edit','API\\RaovatController@postEditRaovat');
	Route::post('/delete','API\\RaovatController@postDeleteRaovat');
	Route::post('/delete-image','API\\RaovatController@postDeleteImageRaovat');

});

Route::group(['prefix' => 'product'], function () {
	Route::get('/',function(){
		return noData();
	});

	Route::get('/list/{content_id}','API\\ManagerController@getListProduct');
	Route::get('/{product_id}','API\\ManagerController@getProduct');
	Route::get('/delete/{product_id}','API\\ManagerController@getDeleteProduct');
	Route::post('/create','API\\ManagerController@postCreateProduct');
	Route::post('/edit','API\\ManagerController@postEditProduct');
});

Route::group(['prefix' => 'discount'], function () {
	Route::get('/',function(){
		return noData();
	});

	Route::get('/list/{content_id}','API\\ManagerController@getListDiscount');
	Route::get('/{discount_id}','API\\ManagerController@getDiscount');
	Route::get('/delete/{discount_id}','API\\ManagerController@getDeleteDiscount');
	Route::post('/create','API\\ManagerController@postCreateDiscount');
	Route::post('/edit','API\\ManagerController@postEditDiscount');
});

Route::group(['prefix' => 'branch'], function () {
	Route::get('/',function(){
		return noData();
	});
	Route::get('/list/{content_id}','API\\ManagerController@getListBranch');
	Route::get('/list-content/{content_id}','API\\ManagerController@getListContentBranch');
	Route::post('/add','API\\ManagerController@postAddBranch');
	Route::post('/remove','API\\ManagerController@postRemoveBranch');
});

Route::group(['prefix' => 'static'], function () {
	Route::post('/','API\\MakeMoneyController@postStatic');
	Route::post('/list','API\\MakeMoneyController@postStaticList');

	Route::post('/search-ctv','API\\MakeMoneyController@postSearchCTV');
	Route::post('/search-daily','API\\MakeMoneyController@postSearchDaily');
	Route::post('/find-daily','API\\MakeMoneyController@postFindDaily');
	Route::post('/area-daily','API\\MakeMoneyController@postAreaDaily');


	Route::post('/search-content','API\\MakeMoneyController@postSearchContent');

	Route::post('/area-ctv','API\\MakeMoneyController@postAreaCTV');
	Route::post('/accept-ctv','API\\MakeMoneyController@postAcceptCTV');
	Route::post('/decline-ctv','API\\MakeMoneyController@postDeclineCTV');
	Route::post('/lock-ctv','API\\MakeMoneyController@postLockCTV');
	Route::post('/unlock-ctv','API\\MakeMoneyController@postUnlockCTV');
	Route::post('/remove-ctv','API\\MakeMoneyController@postRemoveCTV');

	Route::post('/search-ctv-pending','API\\MakeMoneyController@postSearchCTVPending');

	Route::post('/publish-content','API\\MakeMoneyController@postPublishContent');
	Route::post('/reject-content','API\\MakeMoneyController@postRejectContent');
	
	Route::post('/district','API\\MakeMoneyController@postDistrict');

	Route::get('/giaoviec/{to_client}','API\\MakeMoneyController@getGiaoViec');
	Route::post('/giaoviec','API\\MakeMoneyController@postGiaoViec');

	Route::post('/add-ctv','API\\MakeMoneyController@postAddCTV');
	Route::post('/find-client','API\\MakeMoneyController@postFindClient');

	Route::post('/find-client-add-daily','API\\MakeMoneyController@postFindClientAddDaily');
	Route::post('/add-daily','API\\MakeMoneyController@postAddDaily');

	Route::post('/find-daily-ctv','API\\MakeMoneyController@postFindDailyCTV');
	Route::post('/find-ctv','API\\MakeMoneyController@postFindCTV');
	Route::post('/change-rate','API\\MakeMoneyController@postChangeRate');
	
});



Route::group(['prefix' => 'wallet'], function () {
	Route::get('/',function(){
		return noData();
	});

	Route::post('/transfer','API\\WalletController@transfer');
});


Route::get('/raovat-type','API\\RaovatController@getListRaovatType');
Route::get('/raovat-type/{id}','API\\RaovatController@getRaovatType');
Route::get('/getlistnoti','API\\UserController@getListNoti');

Route::get('/test','API\\TestController@index');
// Route::get('/test/{id}','API\\TestController@show');
// Route::post('/test','API\\TestController@create');


Route::group(['prefix' => 'showroom'], function () {
	Route::get('/',function(){
		return noData();
	});
	
	Route::post('/categories','API\\ShowroomController@getAllCategory');
});
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
// Route::any('/', 'Location\\HomeController@anyIndex')->name('index');
Route::get('setlanguage/{lang}', 'Location\\BaseController@setLangue');
Route::group(['prefix' => 'error'], function () {
  Route::any('/{code}', 'Location\\ErrorController@anyIndex');
});
Route::get('test', 'Location\\TestController@getIndex');
Route::get('test/info', 'Location\\TestController@getInfo');

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
Route::get('getHTMLNotifi/{id}', 'Location\\BaseController@getHTMLNotifi');
Route::get('save-cookie', 'Location\\BaseController@saveCookies');

Route::get('searchContent', ['uses' => 'Location\\UserController@getSearchContent']);
Route::get('searchUser', ['uses' => 'Location\\UserController@getSearchUser']);
Route::get('change-owner', ['uses' => 'Location\\UserController@postApplyChangeOwner']);


//Route::post('bank/getBankAjax', 'Location\\ContentController@getBankAjax');
Route::get('detail-photo/{param_1}/{param_2}','Location\\ContentController@getDetailPhoto');

Route::get('/client/{provider}', 'Location\\SocialFacebookController@redirectToProvider');
Route::get('/client/{provide}/callback', 'Location\\SocialFacebookController@handleProviderCallback');
// Route::post('/getContentByCategory','Location\\CategoryController@postAjaxContentByCategoryItem');
Route::get('/getLocation','Location\\CategoryController@getLocation');
// Route::any('/search','Location\\SearchController@anySearch');
// Route::post('/search/loadCity','Location\\SearchController@postLoadCity');
// Route::post('/search/loadDistrict','Location\\SearchController@postLoadDistrict');
// Route::post('/search/loadCategoryItem','Location\\SearchController@postloadCategoryItem');
// Route::any('/ajaxSearch','Location\\SearchController@anyAjaxSearch');
Route::post('/saveLocation','Location\\HomeController@postSaveLocation');

Route::post('/createLocationFrontend/StepOne','Location\\AddLocationController@postAllData');
Route::post('/createLocationFrontend/StepTwo','Location\\AddLocationController@postValidation');
Route::post('/createLocationFrontend/postLocation','Location\\AddLocationController@postAjaxLocation');
Route::post('/createLocationFrontend/postCreateLocation','Location\\AddLocationController@postCreateLocation');
Route::any('/createLocationFrontend/previewLocation','Location\\AddLocationController@previewLocation');
Route::post('/createLocationFrontend/postCreateCategoryItem','Location\\AddLocationController@postCreateCategoryItem');
Route::post('/createLocationFrontend/postCreateService','Location\\AddLocationController@postCreateService');
Route::post('/createLocationFrontend/postCreateCategory','Location\\AddLocationController@postCreateCategory');

Route::post('/createLocationFrontend/deleteProduct','Location\\AddLocationController@deleteProduct');
Route::post('/createLocationFrontend/deleteGroupProduct','Location\\AddLocationController@deleteGroupProduct');




Route::post('/editLocationFrontend/postEditLocation','Location\\AddLocationController@postEditLocation');

Route::get('/edit/location/{id_content}','Location\\AddLocationController@getEditLocation')->where(['id_content' => '[0-9]+']);
Route::get('/changeStatusClose/location/{id_content}','Location\\AddLocationController@getChangeStatusCloseLocation')->where(['id_content' => '[0-9]+']);
Route::get('/changeStatusOpen/location/{id_content}','Location\\AddLocationController@getChangeStatusOpenLocation')->where(['id_content' => '[0-9]+']);
Route::get('/delete/location/{id_content}', 'Location\\AddLocationController@getDeleteLocation')->where(['id_content' => '[0-9]+']);
Route::post('/editLocationFrontend/deleteImage', 'Location\\AddLocationController@getDeleteEditImage');
Route::post('/editLocationFrontend/updateImage', 'Location\\AddLocationController@postUpdateEditImage');
Route::post('/popupNotifyContent', 'Location\\UserController@popupNotifyContent');
Route::post('/postPopupNotifyContent', 'Location\\UserController@postPopupNotifyContent')->name('postPopupNotifyContent');
Route::get('/push-content/{id_content}', 'Location\\ContentController@getPushContent')->where(['id_content' => '[0-9]+']);
Route::post('/ad-content', 'Location\\ContentController@postAdContent');
Route::get('/confirm-location/{id_content}',function($id_content){
  $content = Content::where('id','=',$id_content)
                    ->with('_country')
                    ->with('_city')
                    ->with('_district')
                    ->first();
  if(!$content){
    abort(404);
  }
  $arrParam['content'] = $content;
  return \App()->make('App\\Http\\Controllers\\Location\\ContentController')->callAction('getConfirmContent',$arrParam);
});

Route::post('/confirm-location/{id_content}', 'Location\\ContentController@postConfirmContent');


Route::any('/contact',function(Request $request){

  $arrParam = [];
  if(empty($request->all()))
  {
    $arrParam['link'] = 'contact';
    return \App()->make('App\\Http\\Controllers\\Location\\PageController')->callAction('anyPage',$arrParam);
  }
  else
  {
    $arrParam['data'] = $request->all();
    return \App()->make('App\\Http\\Controllers\\Location\\PageController')->callAction('postContactPage',$arrParam);
  }

});

Route::get('/page/{page}', 'Location\\PageController@custom_page')->name('policy-page');

Route::group(['prefix' => 'user'], function () {
  Route::get('/{id_user}', 'Location\\UserController@getUser');
  Route::post('/{id_user}', 'Location\\UserController@postUser');
  Route::get('/{id_user}/check-in', 'Location\\UserController@getUserCheckin');
  Route::get('/{id_user}/management-location', 'Location\\UserController@getUserManagementLocation');
  Route::get('/{id_user}/location', 'Location\\UserController@getUserManagementLocation');
  Route::get('/{id_user}/like-location', 'Location\\UserController@getUserLikeLocation');
  Route::get('/{id_user}/like', 'Location\\UserController@getUserLike');
  Route::get('/{id_user}/collection', 'Location\\UserController@getUserCollection');
  Route::get('/{id_user}/friend', 'Location\\UserController@getUserFriend');
  Route::get('/{id_user}/change-password', 'Location\\UserController@getUserChangePassword');
  Route::post('/{id_user}/change-password', 'Location\\UserController@postUserChangePassword');
  Route::post('/{id_user}/update-avatar', 'Location\\UserController@postUserUpdateAvatar');
  Route::get('/{id_user}/wallet', 'Location\\UserController@getUserWallet');

  Route::get('/{id_user}/create-discount', 'Location\\UserController@getCreateDiscount')->name('create-discount');
  Route::get('/{id_user}/list-discount', 'Location\\UserController@getListDiscount')->name('list-discount');;
  Route::get('/{id_user}/update-discount/{id_discount}', 'Location\\UserController@getUpdateDiscount')->name('update-discount');
  Route::get('/{id_user}/delete-discount/{id_discount}', 'Location\\UserController@getDeleteDiscount')->name('delete-discount');

  Route::get('/{id_user}/create-ads', 'Location\\UserController@getCreateAds')->name('create-ads');
  Route::get('/{id_user}/publish-ads/{id_ads}', 'Location\\UserController@getPublishAds')->name('publish-ads');
  Route::get('/{id_user}/list-ads', 'Location\\UserController@getListAds')->name('list-ads');;
  Route::get('/{id_user}/update-ads/{id_ads}', 'Location\\UserController@getUpdateAds')->name('update-ads');
  Route::get('/{id_user}/delete-ads/{id_ads}', 'Location\\UserController@getDeleteAds')->name('delete-ads');

  Route::get('/{id_user}/revenue-invite', 'Location\\UserController@getRevenueInvite');

  Route::get('/{id_user}/change-owner', 'Location\\UserController@getChangeOwner');
  Route::post('/{id_user}/change-owner', 'Location\\UserController@postChangeOwner');

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

Route::group(['prefix' => 'discount'], function () {
  Route::post('/postCreateDiscount', 'Location\\DiscountController@postCreateDiscount');
  Route::post('/postUpdateDiscount/{discount_id}', 'Location\\DiscountController@postUpdateDiscount');
  Route::post('/postDeleteImage', 'Location\\DiscountController@postDeleteImage');
  Route::post('/postLoadProduct', 'Location\\DiscountController@postLoadProduct');
});

Route::group(['prefix' => 'ads'], function () {
  Route::post('/postCreateAds', 'Location\\AdsController@postCreateAds');
  Route::post('/postPublishAds', 'Location\\AdsController@postPublishAds');
  Route::post('/postCalPriceAds', 'Location\\AdsController@postCalPriceAds');
  Route::get('/getTypeAds', 'Location\\AdsController@getTypeAds');
  Route::get('/getAds', 'Location\\AdsController@getAds');

  Route::post('/postUpdateAds/{ads_id}', 'Location\\AdsController@postUpdateAds');
  Route::post('/postDeleteImage', 'Location\\AdsController@postDeleteImage');
});

Route::group(['prefix' => 'notice'], function () {
  Route::get('/test', 'Location\\NoticeController@getTest');
});

Route::group(['prefix' => 'makemoney'], function () {
  Route::get('/', 'Location\\MakeMoneyController@index')->name('makemoney');
  Route::get('/register', 'Location\\MakeMoneyController@getRegister')->name('register_makemoney');

  Route::get('/register-success', 'Location\\MakeMoneyController@getRegisterPending')->name('register_makemoney_pending');
  Route::get('/ctv-lock', 'Location\\MakeMoneyController@getCTVlock')->name('ctv_is_lock');
  Route::get('/daily-lock', 'Location\\MakeMoneyController@getDailylock')->name('daily_is_lock');

  Route::post('/register', 'Location\\MakeMoneyController@postRegister')->name('register_makemoney');
  

  // Route CTV

  Route::get('/ctv', 'Location\\MakeMoneyController@getIndexCTV')->name('ctv_makemoney');
  Route::get('/ctv-revenue', 'Location\\MakeMoneyController@getCTVRevenue')->name('ctv_revenue');
  Route::get('/ctv-location', 'Location\\MakeMoneyController@getCTVLocation')->name('ctv_location');

  Route::get('/daily', 'Location\\MakeMoneyController@getIndexDaily')->name('daily_makemoney');
  Route::get('/daily-revenue', 'Location\\MakeMoneyController@getDailyRevenue')->name('daily_revenue');
  Route::get('/daily-location', 'Location\\MakeMoneyController@getDailyLocation')->name('daily_location');
  Route::get('/daily-location-pending', 'Location\\MakeMoneyController@getDailyLocationPending')->name('daily_location_pending');
  Route::get('/daily-ctv', 'Location\\MakeMoneyController@getDailyCTV')->name('daily_ctv');
  Route::get('/daily-ctv-pending', 'Location\\MakeMoneyController@getDailyCTVPending')->name('daily_ctv_pending');
  Route::get('/accept-ctv/{id}', 'Location\\MakeMoneyController@getAcceptCTV')->name('daily_accept_ctv');
  Route::get('/accept-location/{id}', 'Location\\MakeMoneyController@getAcceptLocation')->name('daily_accept_location');
  Route::get('/decline-ctv/{id}', 'Location\\MakeMoneyController@getDeclineCTV')->name('daily_decline_ctv');
  Route::get('/lock-ctv/{id}', 'Location\\MakeMoneyController@getLockCTV')->name('daily_lock_ctv');
  Route::get('/unlock-ctv/{id}', 'Location\\MakeMoneyController@getUnlockCTV')->name('daily_unlock_ctv');
  Route::get('/remove-ctv/{id}', 'Location\\MakeMoneyController@getRemoveCTV')->name('daily_remove_ctv');

  Route::get('/info-ctv/{id}', 'Location\\MakeMoneyController@getInfoCTV')->name('info_ctv');
  Route::get('/grant-ctv/{id}', 'Location\\MakeMoneyController@getGrantCTV')->name('grant_ctv');
  Route::post('/grant-ctv/{id}', 'Location\\MakeMoneyController@postGrantCTV');
  Route::get('/info-location/{id}', 'Location\\MakeMoneyController@getInfoLocation')->name('info_location');

  Route::get('/info-daily/{id}', 'Location\\MakeMoneyController@getInfoDaily')->name('info_daily');
  Route::get('/grant-daily/{id}', 'Location\\MakeMoneyController@getGrantDaily')->name('grant_daily');
  Route::post('/grant-daily/{id}', 'Location\\MakeMoneyController@postGrantDaily');

  Route::get('/ceo', 'Location\\MakeMoneyController@getIndexCEO')->name('ceo_makemoney');
  Route::get('/ceo-revenue', 'Location\\MakeMoneyController@getCEORevenue')->name('ceo_revenue');
  Route::get('/ceo-location', 'Location\\MakeMoneyController@getCEOLocation')->name('ceo_location');
  Route::get('/ceo-ctv', 'Location\\MakeMoneyController@getCEOCTV')->name('ceo_ctv');
  Route::get('/ceo-daily', 'Location\\MakeMoneyController@getCEODaily')->name('ceo_daily');
});


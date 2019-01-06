<?php
Route::group(['prefix' => 'ads', 'middleware' => 'checkactive'], function () {
	Route::any('/', ['middleware' => ['permission:view_Ads'], 'uses' => 'Admin\\AdsController@getListAds'])->name('list_ads');
	Route::group(['prefix' => 'ads', 'middleware' => 'checkactive'], function () {

		Route::any('/', ['middleware' => ['permission:view_Ads'], 'uses' => 'Admin\\AdsController@getListAds'])->name('list_ads');
		// Route::get('searchContent', ['uses' => 'Admin\\AdsController@getSearchContent'])->name('search_content');
  
	  Route::get('add', ['middleware' => ['permission:add_Ads'], 'uses' => 'Admin\\AdsController@getAddAds'])->name('add_ads');
	  Route::post('add', ['middleware' => ['permission:add_Ads'], 'uses' => 'Admin\\AdsController@postAddAds'])->name('add_ads');

	  Route::get('update/{id}', ['middleware' => ['permission:edit_Ads'], 'uses' => 'Admin\\AdsController@getUpdateAds'])->name('update_ads');
	  Route::post('update/{id}', ['middleware' => ['permission:edit_Ads'], 'uses' => 'Admin\\AdsController@postUpdateAds']);

	  Route::post('postDeleteImage', ['middleware' => ['permission:edit_Ads'], 'uses' => 'Admin\\AdsController@postDeleteImage']);
	  

	  Route::get('delete/{id}', ['middleware' => ['permission:delete_Ads'], 'uses' => 'Admin\\AdsController@getDeleteAds'])->name('delete_ads');
	  
	  Route::get('getAds/{id}', [ 'uses' => 'Admin\\AdsController@getAds']);
	  Route::get('approveAds/{id}', [ 'uses' => 'Admin\\AdsController@getApproveAds']);
	  Route::post('declineAds', [ 'uses' => 'Admin\\AdsController@postDeclineAds'])->name('decline_ads');
	});
	
	Route::group(['prefix' => 'type-ads', 'middleware' => 'checkactive'], function () {

		Route::any('/', ['middleware' => ['permission:view_TypeAds'], 'uses' => 'Admin\\TypeAdsController@getListTypeAds'])->name('list_type_ads');
  
	  Route::get('add', ['middleware' => ['permission:add_TypeAds'], 'uses' => 'Admin\\TypeAdsController@getAddTypeAds'])->name('add_type_ads');
	  Route::post('add', ['middleware' => ['permission:add_TypeAds'], 'uses' => 'Admin\\TypeAdsController@postAddTypeAds'])->name('add_type_ads');

	  Route::get('update/{id}', ['middleware' => ['permission:edit_TypeAds'], 'uses' => 'Admin\\TypeAdsController@getUpdateTypeAds'])->name('update_type_ads');
	  Route::post('update/{id}', ['middleware' => ['permission:edit_TypeAds'], 'uses' => 'Admin\\TypeAdsController@postUpdateTypeAds']);

	  Route::post('deletePrice', ['middleware' => ['permission:edit_TypeAds'], 'uses' => 'Admin\\TypeAdsController@postDeletePrice']);
	  

	  Route::get('delete/{id}', ['middleware' => ['permission:delete_TypeAds'], 'uses' => 'Admin\\TypeAdsController@getDeleteTypeAds'])->name('delete_type_ads');

	});
});
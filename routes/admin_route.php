<?php

Route::get('login', 'Admin\\LoginController@getLogin')->name('login');
Route::post('login', 'Admin\\LoginController@login');
Route::get('language/{lang}', 'Admin\\BaseController@getLangue')->name('language');

Route::get('readNotifi', 'Admin\\BaseController@readNotifi');
Route::get('readEachNotifi/{id}', 'Admin\\BaseController@readEachNotifi');
Route::get('analytic', 'Admin\\AdminController@getAnalytic');
Route::get('list-api', 'Admin\\AdminController@getListAPI');

Route::get('reset', 'Admin\\ForgotPasswordController@showLinkRequestForm')->name('forgot_pass');
Route::post('send/email', 'Admin\\ForgotPasswordController@sendResetLinkEmail');

Route::get('reset/{token}', 'Admin\\ResetPasswordController@showResetForm');
Route::post('reset', 'Admin\\ResetPasswordController@reset');


Route::group(['middleware' => 'checkactive'], function () {
  Route::get('/', 'Admin\\AdminController@getIndex')->name('dashboard');
});

Route::get('searchContent', ['uses' => 'Admin\\AdminController@getSearchContent'])->name('search_content');
Route::get('searchUser', ['uses' => 'Admin\\AdminController@getSearchUser'])->name('search_user');
Route::get('getNotifications/{offset}', ['uses' => 'Admin\\AdminController@getNotifications'])->name('get_notifications');

Route::get('app', 'Admin\\AdminController@getApp');
Route::post('app', 'Admin\\AdminController@postApp');

// Route Admin 
Route::group(['prefix' => 'admin', 'middleware' => 'checkactive'], function () {
  include 'admin_location_route.php';
});

// Route Location
Route::group(['middleware' => 'checkactive'], function () {
  include 'admin_location_route.php';
});

// Route Booking
Route::group(['middleware' => 'checkactive'], function () {
include 'admin_booking_route.php';
});

// Route Discount 
Route::group(['middleware' => 'checkactive'], function () {
include 'admin_discount_route.php';
});

// Route Ads 
Route::group(['middleware' => 'checkactive'], function () {
include 'admin_ads_route.php';
});

// Route Raovat 
Route::group(['middleware' => 'checkactive'], function () {
include 'admin_raovat_route.php';
});

// Route Showroom 
Route::group(['middleware' => 'checkactive'], function () {
include 'admin_showroom_route.php';
});






  

  






  



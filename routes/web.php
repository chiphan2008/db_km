<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ROUTE BACK END
Route::get('/refresh-token', function () {
  return csrf_token();
});

Route::group(['domain' => env('ADMIN_URL','admins.kingmap.vn')], function () {
	require 'admin_route.php';
});


// Route for all page
require 'general_route.php';

Route::group(['domain' => env('LOCATION_URL','kingmap.vn')], function () {
	require 'location_route.php';
});


Route::group(['domain' => env('BOOKING_URL','booking.kingmap.vn')], function () {
	require 'booking_route.php';
});

Route::group(['domain' => env('DISCOUNT_URL','discount.kingmap.vn')], function () {
	require 'discount_route.php';
});

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

function noData(){
	return response()->json([
												"code"			=>	200,
												"message"		=>	'success',
												"data"			=> 	[]
											],200)
										 ->header('Content-Type', 'application/vnd.api+json');
}

Route::group(['prefix' => 'api', 'middleware'=>'client_credentials'], function () {
	require 'api_general.php';
});

Route::group(['prefix' => 'apis'], function () {
	require 'api_general.php';
});
<?php

namespace App\Http\Controllers\Booking;

class ErrorController extends BaseController
{
	public function anyIndex($code){
		return view('Location.error.error',['code'=>$code]);
	}
}
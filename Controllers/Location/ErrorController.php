<?php

namespace App\Http\Controllers\Location;

class ErrorController extends BaseController
{
	public function anyIndex($code){
		return view('Location.error.error',['code'=>$code]);
	}
}
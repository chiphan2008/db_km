<?php

namespace App\Http\Controllers\Admin;

class ErrorController extends BaseController
{
	public function anyIndex($code){
		return view('Admin.error.error',['code'=>$code]);
	}
}
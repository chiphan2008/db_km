<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Location\Http\Requests;
use Illuminate\Support\Facades\Auth;

class LanguageController extends BaseController
{

	public function getIndex() {
		try
		{
			$lang =  file_get_contents(app_path().DS.'..'.DS.'resources'.DS.'lang'.DS.'vn.json');
			$lang = json_decode($lang,true);
		}
		catch (Illuminate\Filesystem\FileNotFoundException $exception)
		{
				die("The file doesn't exist");
		}
		
		return view('Admin.language.index',['lang'=>$lang]);
	}

	public function getSave(Request $request) {
		$vn = $request->vn?$request->vn:[];
		$en = $request->en?$request->en:[];

		if(count($vn)==0 || count($en)==0){
			return redirect()->route('list_language')->with(['error' => trans('Admin'.DS.'language.empty_lang')]);
		}else{
			$arr_vn = [];
			$arr_en = [];
			foreach ($vn as $key => $value) {
				$arr_vn[$en[$key]] = $vn[$key];
				$arr_en[$vn[$key]] = $en[$key];
			}
			$check = true;
			$path = app_path().DS.'..'.DS.'resources'.DS.'lang';
			$check = $check && file_put_contents($path.DS.'vn.json', json_encode($arr_vn,JSON_UNESCAPED_UNICODE));
			$check = $check && file_put_contents($path.DS.'en.json', json_encode($arr_en,JSON_UNESCAPED_UNICODE));
			if($check){
				return redirect()->route('list_language')->with(['status' => trans('Admin'.DS.'language.lang_is_saved')]);
			}else{
				return redirect()->route('list_language')->with(['error' => trans('Admin'.DS.'language.save_is_error')]);
			}
		}
	}

}

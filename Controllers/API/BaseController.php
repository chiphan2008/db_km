<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Location\BlockText;
use Illuminate\Http\Request;

class BaseController extends Controller {
	protected $block_text = [];

	public function __construct () {
		$this->middleware(function ($request, $next) {
			$lang = $request->lang?$request->lang:'vn';
      if ($lang != null) {
        \App::setLocale($lang);
      }
      return $next($request);
		});
	}
	public function response($data, $status){
		$this->trace_user('api');
		$request = request();
		$lang = $request->lang?$request->lang:'vn';
		
		if($request->block_text){
    	$arr_block = explode(',', $request->block_text);
    	$block = BlockText::whereIn('machine_name',$arr_block)->get();
    	$arr_tmp = [];
    	foreach ($arr_block as $key1 => $value1) {
    		$arr_tmp[$value1] = "";
    		foreach ($block as $key2 => $value2) {
    			if($value2->machine_name == $value1){

    				if ($lang == 'vn') {
			        $arr_tmp[$value1] = $value2->content_vn;
			      }else{
			      	$arr_tmp[$value1] = $value2->content_en;
			      }
    			}
    		}
    	}
    	$this->block_text = $arr_tmp;
    }
		if(is_object($data)){
			$data = [$data];
		}
		return response()->json([
												"code"			=>	$status,
												"message"		=>	'success',
												"data"			=> 	$data,
												"block_text"			=>  $this->block_text
											],$status,[],JSON_UNESCAPED_UNICODE)
										 ->header('Content-Type', 'application/json');
	}

	public function error($e){
		$this->trace_user('api');
		$data = [];
		$message = $e->getMessage();
		$status=400;
		$status = method_exists($e,'getStatusCode')?$e->getStatusCode():$e->getCode();
		return response()->json([
												"code"			=>	$status,
												"message"		=>	$message,
												"data"			=> 	$data
											],$status)
										 ->header('Content-Type', 'application/json');
	}

  public function convert_image($arr){
    $newArr = array();
    foreach ($arr as $k => $v) {
      $image = new \stdClass();
      if(is_object($v)){
      	$image->id = $v->id;
      	$image->url = asset($v->name);
      	$image->title = $v->title;
      	$image->description = $v->description;
      }else{
      	$image->url = asset($v);
      }
      
      $newArr[] = $image;
    }
    return $newArr;
  }

}

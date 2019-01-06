<?php
namespace App\Http\Controllers\API;

use App\Models\Location\ModuleApp;
use Illuminate\Http\Request;
use Validator;
class ModuleAppController extends BaseController {
	public function index(Request $request){
		try{
			$data = ModuleApp::select('module_app.*')
										->where('active','=',1);
			$skip = $request->skip?$request->skip:0;
			$limit = $request->limit?$request->limit:20;
			$data = $data->limit($limit)
									 ->skip($skip);
			$data = $data->orderBy('weight','asc');
			$data = $data->get();
			if($request->language){
				\App::setLocale($request->language);
			}
			if($data){
				foreach ($data as $key => $value) {
					$data[$key]->name = app('translator')->getFromJson($value->name);
				}
				$data = $data->toArray();
			}else{
				$data=[];
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function find(Request $request, $id){
		try{
			if($id){
				$data = ModuleApp::select('module_app.*')
										->where('id','=',$id)
										->where('active','=',1);
				$data = $data->first();
				if($request->language){
					\App::setLocale($request->language);
				}
			}else{
				$data = null;
			}

			if($data){
				$data->name = app('translator')->getFromJson($data->name);
				$data = $data->toArray();
			}else{
				$data=[];
			}
			return $this->response([$data],200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

}

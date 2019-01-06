<?php
namespace App\Http\Controllers\API;

use App\Models\Location\Category;
use Illuminate\Http\Request;
use Validator;
class CategoryController extends BaseController {
	public function index(Request $request){
		try{
			$data = Category::select(
											'categories.id',
											'categories.name',
											'categories.alias',
											'categories.image',
											'categories.weight'
										)
										->with('sub_category')
										->with('service_items')
										->where('machine_name','not like', '%service%')
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
					foreach ($data[$key]['sub_category'] as $key2 => $value2) {
						$data[$key]['sub_category'][$key2]->name = app('translator')->getFromJson($value2->name);
					}
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
				$data = Category::select(
											'categories.id',
											'categories.name',
											'categories.alias',
											'categories.image',
											'categories.weight'
										)
										->with('sub_category')
										->where('id','=',$id)
										->where('machine_name','not like', '%service%')
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
				foreach ($data['sub_category'] as $key2 => $value2) {
					$data['sub_category'][$key2]->name = app('translator')->getFromJson($value2->name);
				}
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

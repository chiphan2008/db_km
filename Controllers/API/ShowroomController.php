<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Location\Client;
use App\Models\Location\TransactionCoin;
use App\Models\Location\EmailTemplate;

use App\Models\Showroom\Type;
use App\Models\Showroom\Product;
use App\Models\Showroom\TypeProduct;
use App\Models\Showroom\Category;
use App\Models\Showroom\CategoryItem;

use App\Models\Location\ProModule;
use App\Models\Location\ProModuleCategory;
use App\Models\Location\ProModuleCategoryItem;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;

class ShowroomController extends BaseController {
	public function getAllCategory(Request $request){
		try{
			$module = ProModule::where('machine_name','showroom')->first();
			$data = [];
			if($module){
				$data = Category::select(
												'pro_categories.id',
												'pro_categories.name',
												'pro_categories.alias',
												'pro_module_category.image',
												'pro_module_category.weight'
											)
											->rightJoin('pro_module_category','pro_module_category.Category_id','pro_categories.id')
											->where('pro_module_category.module_id',$module->id)
											->with('sub_category')
											->where('pro_module_category.active','=',1);
				$skip = $request->skip?$request->skip:0;
				$limit = $request->limit?$request->limit:20;
				$data = $data->limit($limit)
										 ->skip($skip);
				$data = $data->orderBy('pro_module_category.weight','asc');
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
				}
			}
			
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}
}
<?php
namespace App\Http\Controllers\API;

use App\Models\Location\Content;
use App\Models\Location\Product;
use App\Models\Location\Discount;
use App\Models\Location\Branch;


use Illuminate\Http\Request;
use Validator;
use Carbon;
use Illuminate\Support\Facades\Auth;

class ManagerController extends BaseController {

	public function getListProduct($content_id){
		try{
			$products = Product::select('product.*',\DB::Raw("REPLACE(`product`.`image`,'\/product\/','\/product_thumbnail\/') as thumb"))
												 ->where('content_id', '=', $content_id)->orderBy('group_name')->get();
			if($products){
				$data = $products->toArray();
			}else{
				$data = [];
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function getProduct($product_id){
		try{
			$product = Product::select('product.*',\DB::Raw("REPLACE(`product`.`image`,'\/product\/','\/product_thumbnail\/') as thumb"))
												 ->where('id', '=', $product_id)->get();
			if($product){
				$data = $product->toArray();
			}else{
				$data = [];
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function getDeleteProduct($product_id){
		$product = Product::where('id', '=', $product_id)
								      ->where('created_by',Auth::guard('web_client')->user()->id)
											->first();
		if($product){
			$content_id = $product->content_id;
			Product::where('id', '=', $product_id)
				      ->where('created_by',Auth::guard('web_client')->user()->id)
							->delete();
			$products = Product::select('product.*',\DB::Raw("REPLACE(`product`.`image`,'\/product\/','\/product_thumbnail\/') as thumb"))->where('content_id', '=', $content_id)->orderBy('group_name')->get();
			if($products){
				$data = $products->toArray();
			}else{
				$data = [];
			}
			return $this->response($data,200);
		}
		return $this->response([],200);
	}

	public function postCreateProduct(Request $request){
		try{
			$rules = [
				'content_id' => 'required',
				'name'       => 'required',
				'price'      => 'required',
				'des'        => 'required',
				'image'      => 'required'
			];
			$messages = [
				'content_id.required' => trans('valid.content_required'),
				'name.required'       => trans('valid.name_product'),
				'price.required'      => trans('valid.price_product'),
				'des.required'        => trans('valid.des_product'),
				'image.required'      => trans('valid.image_product')
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				if(!Auth::guard('web_client')->user()){
					$e = new \Exception(trans('Location'.DS.'preview.not_login'),400);
					return $this->error($e);
				}
				$content_id	= $request->content_id;
				$name 	= $request->name;
				$price	= $request->price;
				$image	= $request->image;
				$des  	= $request->des;

				$product = new Product();
				$group_name  	= '';
				$product->name       = $name;
				$product->description = $des;
				$product->price      = $price?$price:0;
				if($request->image){
					$file = $request->image;
					$path = public_path() . '/upload/product/';
					$path_thumbnail = public_path() . '/upload/product_thumbnail/';
					if (!\File::exists($path)) {
						\File::makeDirectory($path, $mode = 0777, true, true);
					}
					if (!\File::exists($path_thumbnail)) {
						\File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
					}

					$img_name = time() . '_product_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

					if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
						app('App\Http\Controllers\Location\AddLocationController')->waterMarkAvatar($file, $img_name, $path, $path_thumbnail);

					$image_product = '/upload/product/' . $img_name;
					$product->image      = $image_product;
				}
				$product->content_id = $content_id;
				$product->type_user  = 0;
				$product->created_by = Auth::guard('web_client')->user()->id;
				$product->updated_by = Auth::guard('web_client')->user()->id;
				$product->group_name = $group_name;
				$product->group_machine_name = str_replace('-','_',str_slug(clear_str($group_name)));
				$product->currency   = 'VND';
				$product->save();
				$products = Product::select('product.*',\DB::Raw("REPLACE(`product`.`image`,'\/product\/','\/product_thumbnail\/') as thumb"))->where('content_id', '=', $request->content_id)->orderBy('group_name')->get();
				if($products){
					$data = $products->toArray();
				}else{
					$data = [];
				}
				return $this->response($data,200);
				
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postEditProduct(Request $request){
		try{
			$rules = [
				'content_id' => 'required',
				'name'       => 'required',
				'price'      => 'required',
				'des'        => 'required',
				// 'image'      => 'required'
			];
			$messages = [
				'content_id.required' => trans('valid.content_required'),
				'name.required'       => trans('valid.name_product'),
				'price.required'      => trans('valid.price_product'),
				'des.required'        => trans('valid.des_product'),
				// 'image.required'      => trans('valid.image_product')
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				if(!Auth::guard('web_client')->user()){
					$e = new \Exception(trans('Location'.DS.'preview.not_login'),400);
					return $this->error($e);
				}
				if(!$request->product_id){
					$e = new \Exception(trans('valid.not_found',['object'=>trans('global.product')]),400);
          return $this->error($e);
				}
				$product = Product::find($request->product_id);
				if(!$product){
					$e = new \Exception(trans('valid.not_found',['object'=>trans('global.product')]),400);
          return $this->error($e);
				}
				$content_id	= $request->content_id;
				$name 	= $request->name;
				$price	= $request->price;
				$image	= $request->image;
				$des  	= $request->des;

				$group_name  	= '';
				$product->name       = $name;
				$product->description = $des;
				$product->price      = $price?$price:0;
				if($request->image){
					$file = $request->image;
					$path = public_path() . '/upload/product/';
					$path_thumbnail = public_path() . '/upload/product_thumbnail/';
					if (!\File::exists($path)) {
						\File::makeDirectory($path, $mode = 0777, true, true);
					}
					if (!\File::exists($path_thumbnail)) {
						\File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
					}

					$img_name = time() . '_product_' . str_replace('-','_',str_slug_custom($file->getClientOriginalName()));

					if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
						app('App\Http\Controllers\Location\AddLocationController')->waterMarkAvatar($file, $img_name, $path, $path_thumbnail);

					$image_product = '/upload/product/' . $img_name;
					$product->image      = $image_product;
				}
				$product->content_id = $content_id;
				$product->type_user  = 0;
				$product->updated_by = Auth::guard('web_client')->user()->id;
				$product->group_name = $group_name;
				$product->group_machine_name = str_replace('-','_',str_slug(clear_str($group_name)));
				$product->currency   = 'VND';
				$product->save();
				$products = Product::select('product.*',\DB::Raw("REPLACE(`product`.`image`,'\/product\/','\/product_thumbnail\/') as thumb"))->where('content_id', '=', $request->content_id)->orderBy('group_name')->get();
				if($products){
					$data = $products->toArray();
				}else{
					$data = [];
				}
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}


	public function getListDiscount($content_id){
		try{
			$discounts = Discount::select('discount.*',\DB::Raw("REPLACE(`discount`.`image`,'\/discount\/','\/discount_thumbnail\/') as thumb"))->where('id_content', '=', $content_id)->get();
			if($discounts){
				$data = $discounts->toArray();
			}else{
				$data = [];
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function getDiscount($discount_id){
		try{
			$discount = Discount::select('discount.*',\DB::Raw("REPLACE(`discount`.`image`,'\/discount\/','\/discount_thumbnail\/') as thumb"))->where('id', '=', $discount_id)->get();
			if($discount){
				$data = $discount->toArray();
			}else{
				$data = [];
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function getDeleteDiscount($discount_id){
		$discount = Discount::where('id', '=', $discount_id)
									      ->where('created_by',Auth::guard('web_client')->user()->id)
												->first();
		if($discount){
			$discount->delete();
			$discounts = Discount::select('discount.*',\DB::Raw("REPLACE(`discount`.`image`,'\/discount\/','\/discount_thumbnail\/') as thumb"))->where('id_content', '=', $discount->id_content)->get();
			if($discounts){
				$data = $discounts->toArray();
			}else{
				$data = [];
			}
			return $this->response($data,200);
		}else{
			return $this->response([],200);
		}
	}

	public function postCreateDiscount(Request $request){
		try{
			$rules = [
				'content_id' => 'required',
				'name'       => 'required',
				'price'      => 'required',
				'des'        => 'required',
				'image'      => 'required'
			];
			$messages = [
				'content_id.required' => trans('valid.content_required'),
				'name.required'       => trans('valid.name_discount'),
				'price.required'      => trans('valid.price_discount'),
				'des.required'        => trans('valid.des_discount'),
				'image.required'      => trans('valid.image_discount')
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				if(!Auth::guard('web_client')->user()){
					$e = new \Exception(trans('Location'.DS.'preview.not_login'),400);
					return $this->error($e);
				}
				$content_id	= $request->content_id;
				$name 	= $request->name;
				$price	= $request->price;
				$image	= $request->image;
				$des  	= $request->des;

				$discount = new Discount();
				$discount_from = '1990-04-04';
				$discount_to = '2090-04-04';
				$discount->name             = $name ;
		  	$discount->description      = $des ;
		  	$discount->date_from        = new Carbon($discount_from.'00:00:00');
		  	$discount->date_to          = new Carbon($discount_to.'23:59:59');
		  	$discount->created_by       = Auth::guard('web_client')->user()->id ;
		  	$discount->updated_by       = Auth::guard('web_client')->user()->id ;
		    $discount->id_content       = $content_id;
		    $discount->active           = 1;
		    $discount->approved         = 1;
		    $discount->price            = $price;
		    if($request->image){
					$file = $request->image;
		      $path = public_path() . '/upload/discount/';
		      $path_thumbnail = public_path() . '/upload/discount_thumbnail/';
		      if (!\File::exists($path)) {
		          \File::makeDirectory($path, $mode = 0777, true, true);
		      }
		      if (!\File::exists($path_thumbnail)) {
		          \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
		      }
		      $img_name = time() . '_discount_' . vn_string($file->getClientOriginalName());

		      if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
						app('App\Http\Controllers\Location\AddLocationController')->waterMarkAvatar($file, $img_name, $path, $path_thumbnail);

		      $discount->image  = '/upload/discount/' . $img_name;
		    }
				$discount->save();
				$discounts = Discount::select('discount.*',\DB::Raw("REPLACE(`discount`.`image`,'\/discount\/','\/discount_thumbnail\/') as thumb"))->where('id_content', '=', $request->content_id)->get();
				if($discounts){
					$data = $discounts->toArray();
				}else{
					$data = [];
				}
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postEditDiscount(Request $request){
		try{
			$rules = [
				'content_id' => 'required',
				'name'       => 'required',
				'price'      => 'required',
				'des'        => 'required',
				// 'image'      => 'required'
			];
			$messages = [
				'content_id.required' => trans('valid.content_required'),
				'name.required'       => trans('valid.name_discount'),
				'price.required'      => trans('valid.price_discount'),
				'des.required'        => trans('valid.des_discount'),
				// 'image.required'      => trans('valid.image_discount')
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				if(!Auth::guard('web_client')->user()){
					$e = new \Exception(trans('Location'.DS.'preview.not_login'),400);
					return $this->error($e);
				}
				if(!$request->discount_id){
					$e = new \Exception(trans('valid.not_found',['object'=>trans('global.discount')]),400);
          return $this->error($e);
				}
				$discount = Discount::find($request->discount_id);
				if(!$discount){
					$e = new \Exception(trans('valid.not_found',['object'=>trans('global.discount')]),400);
          return $this->error($e);
				}


				$content_id	= $request->content_id;
				$name 	= $request->name;
				$price	= $request->price;
				$image	= $request->image;
				$des  	= $request->des;

				$discount_from = '1990-04-04';
				$discount_to = '2090-04-04';
				$discount->name             = $name ;
		  	$discount->description      = $des ;
		  	$discount->date_from        = new Carbon($discount_from.'00:00:00');
		  	$discount->date_to          = new Carbon($discount_to.'23:59:59');
		  	$discount->created_by       = Auth::guard('web_client')->user()->id ;
		  	$discount->updated_by       = Auth::guard('web_client')->user()->id ;
		    $discount->id_content       = $content_id;
		    $discount->active           = 1;
		    $discount->approved         = 1;
		    $discount->price            = $price;
		    if($request->image){
					$file = $request->image;
		      $path = public_path() . '/upload/discount/';
		      $path_thumbnail = public_path() . '/upload/discount_thumbnail/';
		      if (!\File::exists($path)) {
		          \File::makeDirectory($path, $mode = 0777, true, true);
		      }
		      if (!\File::exists($path_thumbnail)) {
		          \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
		      }
		      $img_name = time() . '_discount_' . vn_string($file->getClientOriginalName());

		      if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
						app('App\Http\Controllers\Location\AddLocationController')->waterMarkAvatar($file, $img_name, $path, $path_thumbnail);

		      $discount->image  = '/upload/discount/' . $img_name;
		    }
				$discount->save();
				$discounts = Discount::select('discount.*',\DB::Raw("REPLACE(`discount`.`image`,'\/discount\/','\/discount_thumbnail\/') as thumb"))->where('id_content', '=', $request->content_id)->get();
				if($discounts){
					$data = $discounts->toArray();
				}else{
					$data = [];
				}
				return $this->response($data,200);
			}	
		}catch(Exception $e){
			return $this->error($e);
		}
	}


	public function getListBranch(Request $request, $content_id){
		try{
			$content = Content::find($content_id);
			if($content){
				$list_content = Content::select('contents.*',\DB::Raw("REPLACE(`contents`.`avatar`,'img_content','img_content_thumbnail') as thumb"))
															 ->rightJoin('branch','id_content_other','contents.id')
															 ->where('branch.id_content',$content_id)
															 ->where('created_by',$content->created_by)
															 ->where('type_user',0)
															 ->where('moderation','publish')
															 ->with('_country')
															 ->with('_city')
															 ->with('_district');
				$skip = $request->skip?$request->skip:0;
				$limit = $request->limit?$request->limit:20;

				$list_content =  $list_content->limit($limit)
																			->skip($skip)
																			->get();
				if($list_content){
					$data = $list_content->toArray();
				}else{
					$data = [];
				}															
			}else{
				$data = [];
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function getListContentBranch(Request $request, $content_id){
		try{
			$content = Content::where('id',$content_id)
												->with('_branchs')
												->first();
			if($content){
				$list_group = $content->_branchs;
				$arr_content = [$content_id];
				foreach ($list_group as $key => $value) {
					$arr_content[] = $value->id;
				}
				$list_content = Content::select('contents.*',\DB::Raw("REPLACE(`contents`.`avatar`,'img_content','img_content_thumbnail') as thumb"))
															 ->whereNotIn('id',$arr_content)
															 ->where('created_by',$content->created_by)
															 ->where('type_user',0)
															 ->where('moderation','publish')
															 ->with('_country')
															 ->with('_city')
															 ->with('_district');
				$skip = $request->skip?$request->skip:0;
				$limit = $request->limit?$request->limit:20;

				$list_content =  $list_content->limit($limit)
																			->skip($skip)
																			->get();
				if($list_content){
					$data = $list_content->toArray();
				}else{
					$data = [];
				}
			}else{
				$data = [];
			}
			return $this->response($data,200);
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postAddBranch(Request $request){
		try{
			$rules = [
				'content_id' => 'required',
				'arr_content' => 'required'
			];
			$messages = [
				'content_id.required' => trans('valid.content_required'),
				'arr_content.required'=> trans('valid.content_required'),
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				$content_id	= $request->content_id;
				$arr_content	= $request->arr_content;
				foreach ($arr_content as $key => $value) {
					$branch = new Branch();
					$branch->id_content = $content_id;
					$branch->id_content_other = $value;
					$branch->active = 1;
					$branch->save();
				}
				$content = Content::where('id',$content_id)
													->with('_branchs')
													->first();
				if($content){
					$data = $content->_branchs->toArray();
				}else{
					$data = [];
				}
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}

	public function postRemoveBranch(Request $request){
		try{
			$rules = [
				'content_id' => 'required',
				'content_id_other' => 'required'
			];
			$messages = [
				'content_id.required' => trans('valid.content_required'),
				'content_id_other.required'=> trans('valid.content_required'),
			];
			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				Branch::where('id_content_other',$request->content_id_other)
						->where('id_content',$request->content_id)
						->delete();
				$content = Content::where('id',$content_id)
													->with('_branchs')
													->first();
				if($content){
					$data = $content->_branchs->toArray();
				}else{
					$data = [];
				}
				return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}


}

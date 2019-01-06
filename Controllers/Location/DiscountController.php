<?php

namespace App\Http\Controllers\Location;
use App\Models\Location\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\MessageBag;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Validator;


use App\Models\Location\Discount;
use App\Models\Location\Product;

use App\Models\Location\DiscountProduct;
use App\Models\Location\Content;

use Carbon\Carbon;

class DiscountController extends BaseController
{
	public function postCreateDiscount(Request $request){
		$arrReturn = [
			'error'=>1,
			'message'=> '',
			'data'=>[]
		];
		$rules = [
      'name' => 'required',
      'product' => 'required',
      'content' => 'required',
      'description' => 'required',
    ];
    $messages = [
      'name.required' => trans('valid.name_required'),
      'product.required' => trans('valid.product_required'),
      'content.required' => trans('valid.content_required'),
      'description.required' => trans('valid.discount_description_required'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
    	$arrReturn['message'] = $validator->errors()->first();
      return response()->json($arrReturn);
    } else {
    	$discount = new Discount();
    	$discount->name             = $request->name ;
    	$discount->description      = $request->description ;
    	$discount->date_from        = new Carbon($request->discount_from.'00:00:00');
    	$discount->date_to          = new Carbon($request->discount_to.'23:59:59');
    	$discount->created_by       = Auth::guard('web_client')->user()->id ;
    	$discount->updated_by       = Auth::guard('web_client')->user()->id ;
      $discount->id_content       = $request->content;
      $discount->active           = 1;
      $discount->approved         = 1;
      if($request->discount_image){
        $path = public_path() . '/upload/discount/';
        $path_thumbnail = public_path() . '/upload/discount_thumbnail/';
        if (!\File::exists($path)) {
            \File::makeDirectory($path, $mode = 0777, true, true);
        }
        if (!\File::exists($path_thumbnail)) {
            \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
        }
        $file = $request->discount_image;
        $img_name = time() . '_discount_' . vn_string($file->getClientOriginalName());

        waterMark($file, $img_name, $path, $path_thumbnail);

        $discount->image  = '/upload/discount/' . $img_name;
      }
    	if($discount->save()){
    		if($request->product){
          $content = Content::find($request->content);
    			foreach ($request->product as $key => $product) {
    				DiscountProduct::create([
                'discount_id' => $discount->id,
                'id_content' => $request->content,
                'product_id' => $product,
                'id_category'=> $content->id_category
            ]);
    			}
    		}
    		$arrReturn['error'] = 0;
    		$arrReturn['message'] = trans('Location'.DS.'user.discount').' '.trans('valid.added_successful');
    	}
    	return response()->json($arrReturn);
    }
	}

  public function postDeleteImage(Request $request){
    $id = $request->id;
    $image = DiscountImage::find($id);
    if (file_exists(public_path($image['link']))) {
      unlink(public_path($image['link']));
    }
    if (file_exists(public_path(str_replace('discount', 'discount_thumbnail', $image['link'])))) {
      unlink(public_path(str_replace('discount', 'discount_thumbnail', $image['link'])));
    }
    $image->delete();
    echo 'sussess';
  }

  public function postUpdateDiscount(Request $request, $discount_id){
    $arrReturn = [
      'error'=>1,
      'message'=> '',
      'data'=>[]
    ];
    $rules = [
      'name' => 'required',
      'product' => 'required',
      'content' => 'required',
      'description' => 'required',
    ];
    $messages = [
      'name.required' => trans('valid.name_required'),
      'product.required' => trans('valid.product_required'),
      'content.required' => trans('valid.content_required'),
      'description.required' => trans('valid.discount_description_required'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      $arrReturn['message'] = $validator->errors()->first();
      return response()->json($arrReturn);
    } else {
      $discount = Discount::find($discount_id);
      if(!$discount){
        $arrReturn['message'] = trans('valid.not_found',['object'=>trans('Location'.DS.'user.discount')]);
      }
      $discount->name             = $request->name ;
      $discount->description      = $request->description ;
      $discount->date_from        = new Carbon($request->discount_from.'00:00:00');
      $discount->date_to          = new Carbon($request->discount_to.'23:59:59');
      $discount->updated_by       = Auth::guard('web_client')->user()->id ;
      $discount->id_content       = $request->content;
      if($request->discount_image){
        $link_image = str_replace('/',DS,$discount->image);
        if(\File::exists(public_path($link_image)))
          unlink(public_path($link_image));
        if(\File::exists(public_path(str_replace('discount','discount_thumbnail',$link_image))))
          unlink(public_path(str_replace('discount','discount_thumbnail',$link_image)));

        $path = public_path() . '/upload/discount/';
        $path_thumbnail = public_path() . '/upload/discount_thumbnail/';
        if (!\File::exists($path)) {
            \File::makeDirectory($path, $mode = 0777, true, true);
        }
        if (!\File::exists($path_thumbnail)) {
            \File::makeDirectory($path_thumbnail, $mode = 0777, true, true);
        }
        $file = $request->discount_image;
        $img_name = time() . '_discount_' . vn_string($file->getClientOriginalName());

        waterMark($file, $img_name, $path, $path_thumbnail);

        $discount->image  = '/upload/discount/' . $img_name;
      }
      if($discount->save()){
        if($request->product){
          DiscountProduct::where('discount_id',$discount->id)->delete();
          $content = Content::find($request->content);
          foreach ($request->product as $key => $product) {
            DiscountProduct::create([
                'discount_id' => $discount->id,
                'id_content' => $request->content,
                'product_id' => $product,
                'id_category'=> $content->id_category
            ]);
          }
        }
        $arrReturn['error'] = 0;
        $arrReturn['message'] = trans('Location'.DS.'user.discount').' '.trans('valid.updated_successful');
      }
      return response()->json($arrReturn);
    }
  }

  public function postLoadProduct(Request $request){
    $arrReturn = [
      'error'=>1,
      'message'=> '',
      'data'=>[]
    ];
    $id_content = $request->content?$request->content:0;
    if($id_content!=0){
      $list_product = Product::where('content_id',$id_content)->get();
      if($list_product){
        $arrReturn['data'] = $list_product->toArray();
      }
      $arrReturn['error']=0;
    }
    return response()->json($arrReturn);
  }
}
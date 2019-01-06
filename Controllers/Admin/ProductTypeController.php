<?php
namespace App\Http\Controllers\Admin;

use App\Models\Showroom\Type;
use App\Models\Showroom\Product;
use App\Models\Showroom\TypeProduct;



use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;

class ProductTypeController extends BaseController
{
	public function getListProductType(Request $request){
		$all_product_type = Type::with('_created_by');
		$sort = $request->sort?$request->sort:'';
		$input = request()->all();

		if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		if (isset($keyword) && $keyword != '') {

			$all_product_type->Where(function ($query) use ($keyword) {
				$query->where('name', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('machine_name', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('description', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('language', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('weight', 'LIKE', '%' . $keyword . '%');
			});
		}
		$arr_sort = [];
		if($sort!=''){
			$listSort = explode(',',$sort);
			foreach ($listSort as $key => $value) {
				$item = explode('-',$value);
				if(isset($item[1])){
					$arr_sort[$item[0]] = $item[1];
				}
			}
		}
		if(count($arr_sort)){
			foreach ($arr_sort as $key => $value) {
				$all_product_type->orderBy($key,$value);
			}
		}else{
			$all_product_type->orderBy('weight','asc');
		}
		// $all_product_type->orderBy('weight');
		$list_product_type = $all_product_type->paginate(15);
		// pr($list_product_type->toArray());die;
		return view('Admin.product_type.list', ['list_product_type' => $list_product_type, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);
	}

	public function getAddProductType(Request $request){
		return view('Admin.product_type.add');
	}

	public function postAddProductType(Request $request)
	{
		$rules = [
			'name' => 'required',
			'machine_name' => 'required|unique:pro_type,machine_name',
			'alias'=>'required'
		];
		$messages = [
			'name.required' => trans('valid.name_required'),
			'machine_name.required' => trans('valid.machine_name_required'),
      'machine_name.unique' => trans('valid.machine_name_unique'),
			'alias.required' => trans('valid.alias_required')
		];
		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$product_type = new Type();
			$product_type->name = $request->name;
			$product_type->machine_name = $request->machine_name;
			$product_type->alias = $request->alias;
			$product_type->weight = Type::max('weight') + 1;
			$product_type->active =  isset($request->active);
			$product_type->created_by = Auth::guard('web')->user()->id;
			$product_type->updated_by = Auth::guard('web')->user()->id;
			if($request->file('image')) {
				$path = public_path().'/upload/product_type/';
				$file = $request->file('image');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name =time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0){
					$image = crop_image($file,200,200);
					$image->save($path.$name);
				}else{
					if($file->getClientOriginalExtension() === 'svg'){
						$file->move($path,$name);
					}
				}
				$product_type->image = '/upload/product_type/'.$name;
			}else{
				$product_type->image ='/frontend/assets/img/icon/logo-large.png';
			}

			if( $product_type->save() ) {
				return redirect()->route('list_product_type')->with(['status' => 'Loại sản phẩm đã được thêm thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được loại sản phẩm']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getUpdateProductType(Request $request,$id){
		$product_type = Type::find($id);
		return view('Admin.product_type.update',['product_type' => $product_type]);
	}

	public function postUpdateProductType(Request $request,$id){
		$product_type = Type::find($id);
		$old_active = $product_type->active;
		$rules = [
			'name' => 'required',
			'alias' => 'required'

		];
		$messages = [
			'name.required' => trans('valid.name_required'),
			'name.unique' => trans('valid.name_unique')

		];
		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$product_type->name = $request->name;
			$product_type->alias = $request->alias;
			$product_type->active =  isset($request->active);
			$product_type->updated_by = Auth::guard('web')->user()->id;
			if($request->file('image')) {
				$path = public_path().'/upload/product_type/';
				$file = $request->file('image');
				if(!\File::exists($path)) {
					\File::makeDirectory($path, $mode = 0777, true, true);
				}
				$name =time(). '.' . $file->getClientOriginalExtension();
				if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0){
					$image = crop_image($file,200,200);
					$image->save($path.$name);
				}else{
					if($file->getClientOriginalExtension() === 'svg'){
						$file->move($path,$name);
					}
				}
				$product_type->image = '/upload/product_type/'.$name;
			}
			if( $product_type->save() ) {
				return redirect()->route('list_product_type')->with(['status' => 'Loại sản phẩm  đã được cập nhật thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không cập nhật được loại sản phẩm']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}
	
	public function getDeleteProductType($id){
		$product = TypeProduct::where('type_id','=',$id)->count();
    if($product > 0)
    {
      $product_type = Type::find($id);
      $name = $product_type->name;
      return redirect()->route('list_product_type')->with(['err' => 'Loại sản phẩm ' . $name . ' không thể xóa, Vui lòng kiểm tra lại toàn bộ product trước khi xóa !']);
    }
    else {
      $product_type = Type::find($id);
      $name = $product_type->name;
      $product_type->delete();
      return redirect()->route('list_product_type')->with(['status' => 'Loại sản phẩm ' . $name . ' đã xóa thành công ']);
    }
	}

	public function getChangeWeightProductType($id,$weight){
		$current_type = Type::find($id);
		if($current_type){
			$current_type->weight = $weight;
			$current_type->save();
			return redirect()->route('list_product_type');
		}
	}
	
}
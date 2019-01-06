<?php
namespace App\Http\Controllers\API;

use App\Models\Location\Category;
use Illuminate\Http\Request;
use Validator;
class TestController extends BaseController {

	public function index(){
		return $this->response([],200);
	}

	public function show($id){
		$data = Category::where('active','=',1)
										->where('id','=',$id)
										->first();
		if($data){
			$data = $data->toArray();
			return $this->response($data,200);
		}else{
			return $this->response([],200);
		}
	}

	public function create(Request $request){
		$rules = [
			'name' => 'required',
			'machine_name' => 'required|unique:categories,machine_name',
			'alias'=>'required'
		];
		$messages = [
			'name.required' => 'Name là trường bắt buộc',
			'machine_name.required' => 'Machine Name là trường bắt buộc',
      'machine_name.unique' => 'Machine Name đã tồn tại',
			'alias.required'=>'Alias là trường bắt buộc'
		];
		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return $this->error($validator->messages()->toArray(),422);
		} else {
			return  $this->response(null,204);
		}
	}

}

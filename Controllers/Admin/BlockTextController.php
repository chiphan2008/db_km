<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\BlockText;
use App\Models\Location\BlockTextItem;
use App\Models\Location\Content;
use App\Models\Location\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;

class BlockTextController extends BaseController
{

	public function getListBlockText(Request $request)
	{
		$all_block_text = BlockText::with('_created_by');
		$sort = $request->sort?$request->sort:'';
		$input = request()->all();

		if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		if (isset($keyword) && $keyword != '') {

			$all_block_text->Where(function ($query) use ($keyword) {
				$query->where('name', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('machine_name', 'LIKE', '%' . $keyword . '%');
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
				$all_block_text->orderBy($key,$value);
			}
		}
		// $all_block_text->orderBy('weight');
		$list_block_text = $all_block_text->paginate(15);
		// pr($list_block_text->toArray());die;
		session()->pull("from_setting_make_money",null);
		return view('Admin.block_text.list', ['list_block_text' => $list_block_text, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);

	}

	public function getAddBlockText()
	{
		return view('Admin.block_text.add');
	}

	public function postAddBlockText(Request $request)
	{
		$rules = [
			'name' => 'required',
			'machine_name' => 'required|unique:categories,machine_name',
			'alias'=>'required',
			'image'=>'required'
		];
		$messages = [
			'name.required' => trans('valid.name_required'),
			'machine_name.required' => trans('valid.machine_name_required'),
      'machine_name.unique' => trans('valid.machine_name_unique'),
			'alias.required' => trans('valid.alias_required'),
			'image.required'=> trans('valid.image_required')
		];
		if($request->type=="text"){
			unset($rules["image"]);
		}
		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$block_text = new BlockText();
			$block_text->name = $request->name;
			$block_text->machine_name = $request->machine_name;
			$block_text->alias = $request->alias;
			$block_text->type = $request->type;

			if($request->type=="text"){
				$block_text->content_vn = $request->content_vn?$request->content_vn:'';
				$block_text->content_en = $request->content_en?$request->content_en:'';
			}
			
			if($request->type=="image"){
				if($request->file('image')) {
					$path = public_path().'/upload/block_text/';
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
					$block_text->content_vn = '/upload/block_text/'.$name;
					$block_text->content_en = '/upload/block_text/'.$name;
				}
			}

			$block_text->created_by = Auth::guard('web')->user()->id;
			$block_text->updated_by = Auth::guard('web')->user()->id;

			if( $block_text->save() ) {			
				return redirect()->route('list_block_text')->with(['status' => 'Nội dung tĩnh đã được thêm thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được nội dung tĩnh']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getUpdateBlockText($id)
	{

		$block_text = BlockText::find($id);
		return view('Admin.block_text.update', ['block_text' => $block_text]);
	}

	public function postUpdateBlockText(Request $request, $id)
	{
		$from_setting_make_money = session()->pull("from_setting_make_money");
		$block_text = BlockText::find($id);
		$old_active = $block_text->active;
		$rules = [
			'name' => 'required',
			'alias' => 'required',
			'image'=>'required'

		];
		$messages = [
			'name.required' => trans('valid.name_required'),
			'name.unique' => trans('valid.name_unique'),
			'image.required'=> trans('valid.image_required')

		];
		if($request->type=="text"){
			unset($rules["image"]);
		}

		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$block_text->name = $request->name;
			$block_text->alias = $request->alias;

			$block_text->type = $request->type;

			if($request->type=="text"){
				$block_text->content_vn = $request->content_vn?$request->content_vn:'';
				$block_text->content_en = $request->content_en?$request->content_en:'';
			}
			
			if($request->type=="image"){
				if($request->file('image')) {
					$path = public_path().'/upload/block_text/';
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
					$block_text->content_vn = '/upload/block_text/'.$name;
					$block_text->content_en = '/upload/block_text/'.$name;
				}
			}

			$block_text->updated_by = Auth::guard('web')->user()->id;


			if( $block_text->save() ) {
				$name = $block_text->name;
				if($from_setting_make_money){
					return redirect()->route('setting_make_money')->with(['status' => 'Nội dung tĩnh ' . $name . ' đã được cập nhật thành công ']);
				}else{
					return redirect()->route('list_block_text')->with(['status' => 'Nội dung tĩnh ' . $name . ' đã được cập nhật thành công ']);
				}
				
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được nội dung tĩnh']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getDeleteBlockText($id)
	{
    $block_text = BlockText::find($id);
    $name = $block_text->name;
    $block_text->delete();
    return redirect()->route('list_block_text')->with(['status' => 'Nội dung tĩnh ' . $name . ' đã xóa thành công ']);
	}
}

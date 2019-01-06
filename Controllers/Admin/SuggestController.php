<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\Suggest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Validator;
use Carbon\Carbon;

class SuggestController extends BaseController
{

	public function getListSuggest(Request $request)
	{
		$per_page = Session::has('pagination.' . \Route::currentRouteName()) ? session('pagination.' . \Route::currentRouteName()) : 10;
		$all_suggest = Suggest::with('_created_by');
		$sort = $request->sort?$request->sort:'';
		$input = request()->all();

		if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		if (isset($keyword) && $keyword != '') {

			$all_suggest->Where(function ($query) use ($keyword) {
				$query->where('keyword', 'LIKE', /*'%' . */$keyword . '%');
				$query->orWhere('alias', 'LIKE', /*'%' . */$keyword . '%');
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
				$all_suggest->orderBy($key,$value);
			}
		}else{
			$all_suggest->orderBy('weight','asc');
			$all_suggest->orderBy('id','desc');
		}
		// $all_suggest->orderBy('weight');
		$list_suggest = $all_suggest->paginate($per_page);
		// pr($list_suggest->toArray());die;
		return view('Admin.suggest.list', ['list_suggest' => $list_suggest, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);
	}

	public function getAddSuggest()
	{
		return view('Admin.suggest.add');
	}

	public function postAddSuggest(Request $request)
	{
		$rules = [
			'keyword' => 'required|unique:suggest_keyword,keyword',
		];
		$messages = [
			'keyword.required' => trans('valid.keyword_required'),
			'keyword.unique' => trans('valid.keyword_unique'),
		];
		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()->route('list_suggest')->with(['error' => $validator->errors()->first()]);
			// return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$suggest = new Suggest();
			$suggest->keyword = $request->keyword;
			$suggest->alias = str_slug(clear_str($request->keyword));
			$suggest->weight = $request->weight?$request->weight:0;
			$suggest->created_by = Auth::guard('web')->user()->id;
			$suggest->updated_by = Auth::guard('web')->user()->id;
			if( $suggest->save() ) {
				return redirect()->route('list_suggest')->with(['status' => 'Từ khóa gợi ý "'.$request->keyword.'" đã được thêm thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không tạo được suggest']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getUpdateSuggest($id)
	{
		$suggest = Suggest::find($id);
		if(!$suggest){
			abort(404);
		}
		return view('Admin.suggest.update', ['suggest' => $suggest]);
	}

	public function postUpdateSuggest(Request $request, $id)
	{
		$suggest = Suggest::find($id);
		$old_active = $suggest->active;
		$rules = [
			'keyword' => 'required|unique:suggest_keyword,keyword',
		];
		$messages = [
			'keyword.required' => trans('valid.keyword_required'),
			'keyword.unique' => trans('valid.keyword_unique'),
		];
		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$suggest->keyword = $request->keyword;
			$suggest->alias = str_slug(clear_str($request->keyword));
			$suggest->weight = $request->weight?$request->weight:0;
			$suggest->updated_by = Auth::guard('web')->user()->id;


			if( $suggest->save() ) {
				return redirect()->route('list_suggest')->with(['status' => 'Từ khóa gợi ý đã được cập nhật thành công ']);
			} else {
				$errors = new MessageBag(['error' => 'Không cập nhật được từ khóa gợi ý']);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getDeleteSuggest($id)
	{
    $suggest = Suggest::find($id);
    $keyword = '';
    if($suggest){
    	$keyword = $suggest->keyword;
    	$suggest->delete();
    }
    return redirect()->route('list_suggest')->with(['status' => 'Từ khóa gợi ý ' . $keyword . ' đã xóa thành công ']);
	}

	public function getDownSuggest($id){
		$current_suggest = Suggest::find($id);
		$change_suggest = Suggest::where('weight','>',$current_suggest->weight)->orderBy('weight','asc')->first();
		$old_weight = $current_suggest->weight;
		$current_suggest->weight = $change_suggest->weight;
		$change_suggest->weight = $old_weight;
		$current_suggest->save();
		$change_suggest->save();
		return redirect()->route('list_suggest');
	}

	public function getUpSuggest($id){
		$current_suggest = Suggest::find($id);
		$change_suggest = Suggest::where('weight','<',$current_suggest->weight)->orderBy('weight','desc')->first();
		$old_weight = $current_suggest->weight;
		$current_suggest->weight = $change_suggest->weight;
		$change_suggest->weight = $old_weight;
		$current_suggest->save();
		$change_suggest->save();
		return redirect()->route('list_suggest');
	}

	public function getChangeWeightSuggest($id,$weight){
		$current_suggest = Suggest::find($id);
		if($current_suggest){
			$current_suggest->weight = $weight;
			$current_suggest->save();
			return redirect()->back();
		}
	}

	public function getChangeKeywordSuggest($id,$keyword){
		$current_suggest = Suggest::find($id);
		if($current_suggest){
			$current_suggest->keyword = $keyword;
			$current_suggest->alias = str_slug(clear_str($keyword));
			$current_suggest->save();
			return redirect()->back();
		}
	}

	public function postDeleteMultiSuggest(Request $request){
		if(count($request->id)){
			Suggest::whereIn('id',$request->id)->delete();
			session()->flash('status',count($request->id).' từ khóa gợi ý đã xóa thành công ');
		}
	}
}

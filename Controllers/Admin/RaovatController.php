<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;
use Carbon\Carbon;

use App\Models\Location\Raovat;
use App\Models\Location\RaovatType;
use App\Models\Location\RaovatImage;
use Intervention\Image\Facades\Image;

class RaovatController extends BaseController
{
	public function getListRaovat(){
		$per_page = Session::has('pagination.' . \Route::currentRouteName()) ? session('pagination.' . \Route::currentRouteName()) : 10;
		$input = request()->all();

		$all_raovat = Raovat::select('raovat.*')
											->with('_created_by')
											->with('_type')
											->orderBy('active','ASC')
                      ->orderBy('id');

		if (isset($input['keyword']) && $input['keyword']!='') {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}
		$all_raovat = $all_raovat->where('raovat.name','like','%'.$keyword.'%');
		$list_raovat = $all_raovat->paginate($per_page);
		// dd($list_raovat);
		return view('Admin.raovat.list', ['list_raovat' => $list_raovat, 'keyword'=>$keyword]);
	}
	public function getAddRaovat(){
		$data['type'] = RaovatType::where('active',1)->get();
		return view('Admin.raovat.add', ['data' => $data]);
	}

	public function getDeleteRaovat($id){
		$raovat = Raovat::where('id',$id)
							// ->where('approved',0)
							// ->where('declined',0)
							->first();
		if(!$raovat){
			$errors = new MessageBag(['error' => trans('Admin'.DS.'raovat.not_found_raovat')]);
      return redirect()->back()->withErrors($errors)->withInput();
		}
		if($raovat->delete()){
			$image_raovat = RaovatImage::where('raovat_id',$raovat->id)->get();
			foreach ($image_raovat as $key => $value) {
				unlink(public_path($value->link));
			}
			RaovatImage::where('raovat_id',$raovat->id)->delete();
		}
		return redirect(route('list_raovat'))->with(['success'=>trans('Admin'.DS.'raovat.deleted_raovat',['content'=>$raovat->name])]);
	}

}
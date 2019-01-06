<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Validator;
use App\Models\Location\TemplateNotifi;
use App\Models\Location\Notifi;
use App\Models\Location\Client;
use App\Models\Location\NotifiUser;
use App\Models\Location\TemplateNotifiTranslate;
use Illuminate\Support\MessageBag;
use Carbon\Carbon;

use App\Events\getNotifi;
class NotifiController extends BaseController
{
	public function getListNotifi(Request $request)
	{
		$all_notifi = DB::table('notifi');
		$sort = $request->sort?$request->sort:'';
		$input = request()->all();
				if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		if (isset($keyword) && $keyword != '') {

			$all_notifi->Where(function ($query) use ($keyword) {
				$query->where('name', 'LIKE', '%' . $keyword . '%');
				$query->orWhere('zipcode', 'LIKE', '%' . $keyword . '%');
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
				$all_notifi->orderBy($key,$value);
			}
		}else{
			$all_notifi->orderBy('id','desc');
		}
		$list_notifi = $all_notifi->paginate(10);
		return view('Admin.notifi.list',[
								'sort'=> $sort,
								'keyword'=>$keyword,
								'list_notifi' => $list_notifi
							]);
	}

	public function getAddNotifi(){
		$templates = TemplateNotifi::get();
		return view('Admin.notifi.add', [
			'templates' => $templates
		]);
	}

	public function postAddNotifi(Request $request){
		// dd($request->all());
		$rules = [
			'title' => 'required',
			'content' => 'required',
		];

		$messages = [
			'title.required' => trans('Admin'.DS.'template_notifi.name_required'),
			'content.required' => trans('Admin'.DS.'template_notifi.content_required'),
		];

		$validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$notifi = new Notifi();
			$notifi->title	            		= $request->title?$request->title:'';
			if($request->use_template){
				$notifi->template_notifi_id		= $request->template?$request->template:0;
				if($request->machine_name_template)
					$notifi->content	          	= 'notifi.'.$request->machine_name_template;
				else
					$notifi->content 							= $request->content?
																				$request->content:'';
				$notifi->data	              	= $request->data?json_encode($request->data):json_encode([]);
			}else{
				$notifi->content 							= $request->content?
																				$request->content:'';
			}

			if($request->schedule){
				$notifi->schedule	          	= 1;
				$notifi->start	            	= new Carbon($request->from);
				$notifi->end	              	= new Carbon($request->to);
			}else{
				$notifi->show	              	= 1;
			}

			$notifi->link	              		= $request->link?$request->link:'';
			if($request->active){
				$notifi->active	            	= 1;
			}else{
				$notifi->active	            	= 0;
			}

			if($request->everyone){
				$notifi->is_everyone	            	= 0;
			}else{
				$notifi->is_everyone	            	= 1;
			}
			$notifi->created_by = Auth::guard('web')->user()->id;
			if($request->file('image')) {
        $path = public_path().'/upload/notifi/';
        $file = $request->file('image');
        if(!\File::exists($path)) {
          \File::makeDirectory($path, $mode = 0777, true, true);
        }
        $name =time(). '.' . $file->getClientOriginalExtension();
        if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
$file->move($path,$name);
        $notifi->image = '/upload/notifi/'.$name;
      }

			if($notifi->save()){
				if($notifi->is_everyone == 0){
					$list_client = Client::get()->pluck('id');
					if($list_client){
						$list_client = $list_client->toArray();
						foreach ($list_client as $key => $client) {
							$notifi_user = new NotifiUser();
							$notifi_user->notifi_id	=	$notifi->id;
							$notifi_user->user_id  	=	$client;
							$notifi_user->save();
						}
					}
					if($notifi->show && $notifi->active){
					    $data_noti = format_noti($notifi->id);
						event(new getNotifi($data_noti->toArray(),'all'));
					}
				}else{
					if($notifi->show && $notifi->active){
                        $data_noti = format_noti($notifi->id);
						event(new getNotifi($data_noti->toArray(),0));
					}
				}
				
				return redirect()->route('list_notifi')->with(['status' => trans('Admin'.DS.'notifi.add_success')]);
			}else{
				$errors = new MessageBag(['error' => trans('Admin'.DS.'notifi.add_fail')]);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getUpdateNotifi($id){
		$notifi = Notifi::find($id);
		if(!$notifi){
			abort(404);
		}
		$templates = TemplateNotifi::get();
		return view('Admin.notifi.update', [
			'templates' => $templates,
			'notifi' => $notifi
		]);
	}

	public function postUpdateNotifi(Request $request,$id){
		// dd($request->all());
		$rules = [
			'title' => 'required',
			'content' => 'required',
		];

		$messages = [
			'title.required' => trans('Admin'.DS.'template_notifi.name_required'),
			'content.required' => trans('Admin'.DS.'template_notifi.content_required'),
		];

		$validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$notifi = Notifi::find($id);
			if(!$notifi){
				abort(404);
			}
			$notifi->title	            		= $request->title?$request->title:'';
			if($request->use_template){
				$notifi->template_notifi_id		= $request->template?$request->template:0;
				if($request->machine_name_template)
					$notifi->content	          	= 'notifi.'.$request->machine_name_template;
				else
					$notifi->content 							= $request->content?
																				$request->content:'';
				$notifi->data	              	= $request->data?json_encode($request->data):json_encode([]);
			}else{
				$notifi->content 							= $request->content?
																				$request->content:'';
			}

			if($request->schedule){
				$notifi->show	              	= 0;
				$notifi->schedule	          	= 1;
				$notifi->start	            	= new Carbon($request->from);
				$notifi->end	              	= new Carbon($request->to);
			}else{
				$notifi->show	              	= 1;
				$notifi->schedule	          	= 0;
			}

			$notifi->link	              		= $request->link?$request->link:'';

			if($request->active){
				$notifi->active	            	= 1;
			}else{
				$notifi->active	            	= 0;
			}

			if($request->everyone){
				$notifi->is_everyone	            	= 0;
			}else{
				$notifi->is_everyone	            	= 1;
			}

			$notifi->created_by = Auth::guard('web')->user()->id;
			if($request->file('image')) {
        $path = public_path().'/upload/notifi/';
        $file = $request->file('image');
        if(!\File::exists($path)) {
          \File::makeDirectory($path, $mode = 0777, true, true);
        }
        $name =time(). '.' . $file->getClientOriginalExtension();
        if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
$file->move($path,$name);
        $notifi->image = '/upload/notifi/'.$name;
      }
			if($notifi->save()){
				NotifiUser::where('notifi_id', $notifi->id)->delete();
				if($notifi->is_everyone == 0){
					$list_client = Client::get()->pluck('id');
					if($list_client){
						$list_client = $list_client->toArray();
						foreach ($list_client as $key => $client) {
							$notifi_user = new NotifiUser();
							$notifi_user->notifi_id	=	$notifi->id;
							$notifi_user->user_id  	=	$client;
							$notifi_user->save();
						}
					}
					if($notifi->show && $notifi->active){
                        $data_noti = format_noti($notifi->id);
						event(new getNotifi($data_noti->toArray(),'all'));
					}
				}else{
					if($notifi->show && $notifi->active){
                        $data_noti = format_noti($notifi->id);
                        event(new getNotifi($data_noti->toArray(),0));
					}
				}
				return redirect()->route('list_notifi')->with(['status' => trans('Admin'.DS.'notifi.update_success')]);
			}else{
				$errors = new MessageBag(['error' => trans('Admin'.DS.'notifi.update_fail')]);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getDeleteNotifi($id){
		$notifi = Notifi::find($id);
    $notifi_name = $notifi->title;
    $notifi->delete();
    return redirect()->route('list_notifi')->with(['status' => 'Notifi ' . $notifi_name . ' đã xóa thành công ']);
	}
}

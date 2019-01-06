<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Validator;
use App\Models\Location\TemplateNotifi;
use App\Models\Location\TemplateNotifiTranslate;
use Illuminate\Support\MessageBag;

class TemplateNotifiController extends BaseController
{
		//
	public function getListTemplateNotifi(Request $request){
		$all_template = DB::table('template_notifi');
		$sort = $request->sort?$request->sort:'';
		$input = request()->all();
				if (isset($input['keyword'])) {
			$keyword = $input['keyword'];
		} else {
			$keyword = '';
		}

		if (isset($keyword) && $keyword != '') {

			$all_template->Where(function ($query) use ($keyword) {
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
				$all_template->orderBy($key,$value);
			}
		}else{
			$all_template->orderBy('id','desc');
		}
		$list_template = $all_template->paginate(10);
		return view('Admin.template_notifi.list',[
								'sort'=> $sort,
								'keyword'=>$keyword,
								'list_template' => $list_template
							]);
	}

	public function getAddTemplateNotifi(){
		return view('Admin.template_notifi.add');
	}

	public function postAddTemplateNotifi(Request $request){
		// dd($request->all());
		$rules = [
			'name' => 'required',
			'machine_name' => 'required|unique:template_notifi,machine_name',
			'content' => 'required',
		];

		$messages = [
			'machine_name.required' => trans('Admin'.DS.'template_notifi.machine_name_required'),
			'machine_name.unique' => trans('Admin'.DS.'template_notifi.machine_name_unique'),
			'name.required' => trans('Admin'.DS.'template_notifi.name_required'),
			'content.required' => trans('Admin'.DS.'template_notifi.content_required'),
		];
		$validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$template = new TemplateNotifi();
			$template->name = $request->name;
			$template->machine_name = $request->machine_name;
			$template->content = $request->content;
			$template->language = $request->language;
			$template->created_by = Auth::guard('web')->user()->id;
			if($template->save()){
				$template_notifi_id = $template->id;
				$template_trans = new TemplateNotifiTranslate();
				$template_trans->name = $request->name;
				$template_trans->machine_name = $request->machine_name;
				$template_trans->content = $request->content;
				if($request->language=='vn'){
					$template_trans->language = 'en';
				}else{
				 $template_trans->language = 'vn';
				}
				$template_trans->created_by = Auth::guard('web')->user()->id;
				$template_trans->template_notifi_id = $template_notifi_id;
				$template_trans->save();
				self::create_file_lang();
				return redirect()->route('list_template_notifi')->with(['status' => trans('Admin'.DS.'template_notifi.add_success')]);
			}else{
				$errors = new MessageBag(['error' => trans('Admin'.DS.'template_notifi.add_fail')]);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}

	public function getUpdateTemplateNotifi($id){
		$template = TemplateNotifi::find($id);
		if(!$template){
			abort(404);
		}
		return view('Admin.template_notifi.update',[
				'template'=>$template
			]);
	}

	public function postUpdateTemplateNotifi(Request $request,$id){
		// dd($request->all());
		$rules = [
			'name' => 'required',
			'content' => 'required',
		];
		$messages = [
			'name.required' => trans('Admin'.DS.'template_notifi.name_required'),
			'content.required' => trans('Admin'.DS.'template_notifi.content_required'),
		];
		$validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$template = TemplateNotifi::find($id);
			if(!$template){
				abort(404);
			}
			$template->name = $request->name;
			$template->content = $request->content;
			$template->updated_by = Auth::guard('web')->user()->id;
			if($template->save()){
				self::create_file_lang();
				return redirect()->route('list_template_notifi')->with(['status' => trans('Admin'.DS.'template_notifi.update_success')]);
			}else{
				$errors = new MessageBag(['error' => trans('Admin'.DS.'template_notifi.update_fail')]);
				return redirect()->back()->withErrors($errors)->withInput();
			}
		}
	}


	// Translate
	public function getTranslateTemplateNotifi($id,$lang){
		$template = TemplateNotifi::find($id);
		if(!$template){
			abort(404);
		}
		if($template->language != $lang){
			$tmp = TemplateNotifiTranslate::where('template_notifi_id','=',$template->id)
																		->where('language','=',$lang)
																		->first();
			if($tmp){
				$template = $tmp;
			}
		}
		return view('Admin.template_notifi.translate',[
				'template'=>$template
			]);
	}

	public function postTranslateTemplateNotifi(Request $request,$id,$lang){
		// dd($request->all());
		$rules = [
			'content' => 'required',
		];
		$messages = [
			'content.required' => trans('Admin'.DS.'template_notifi.content_required'),
		];
		$validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator)->withInput();
		} else {
			$template = TemplateNotifiTranslate::where('template_notifi_id','=',$id)
																		->where('language','=',$lang)
																		->first();
			if($template){
				$template->content = $request->content;
				$template->updated_by = Auth::guard('web')->user()->id;
			}else{
				$template = TemplateNotifi::where('id','=',$id)
																	->where('language','=',$lang)
																	->first();
				if($template){
					$template->content = $request->content;
					$template->updated_by = Auth::guard('web')->user()->id;
				}else{
					abort(404);
				}
			}

			if($template->save()){
				self::create_file_lang();
				return redirect()->route('list_template_notifi')->with(['status' => trans('Admin'.DS.'template_notifi.update_success')]);
			}else{
				return redirect()->back()->with(['status' => trans('Admin'.DS.'template_notifi.update_fail')]);
			}
		}
	}

	public static function create_file_lang(){
		$list_template = TemplateNotifi::get();
		$list_template_trans = TemplateNotifiTranslate::get();
		$arr_vn = [];
		$arr_en = [];
		foreach ($list_template as $key => $template) {
			if($template->language=='vn'){
				$arr_vn[] = $template;
			}
			if($template->language=='en'){
				$arr_en[] = $template;
			}
		}

		foreach ($list_template_trans as $key => $template) {
			if($template->language=='vn'){
				$arr_vn[] = $template;
			}
			if($template->language=='en'){
				$arr_en[] = $template;
			}
		}

		$head = "<?php\n\treturn array(\n";
		$end = ");";

		$vn = $head;
		$en = $head;
		foreach ($arr_vn as $key => $template) {
			$vn.= "\t\t\t'".$template->machine_name."'"."\t=>\t"."'".$template->content."',\n";
		}
		foreach ($arr_en as $key => $template) {
			$en.= "\t\t\t'".$template->machine_name."'"."\t=>\t"."'".$template->content."',\n";
		}
		$vn .= $end;
		$en .= $end;
		$path = app_path().DS.'..'.DS.'resources'.DS.'lang';
		file_put_contents($path.DS.'vn'.DS.'notifi.php', $vn);
		file_put_contents($path.DS.'en'.DS.'notifi.php', $en);
	}
}

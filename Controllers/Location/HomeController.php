<?php
namespace App\Http\Controllers\Location;
use App\Models\Location\Category;
use App\Models\Location\Content;
use App\Models\Location\Setting;
use Illuminate\Http\Request;
class  HomeController extends BaseController {

	public function anyIndex()
	{
		 // Get Category List
		$categories = Category::where('machine_name','not like', '%service%')
													->where('active','=',1)
													->with('category_items')
													->orderBy('weight')
													->get();
		if($categories){
			foreach ($categories as $key => $value) {
				$categories[$key]->name = app('translator')->getFromJson($value->name);
			}
		}else{
			$categories=[];
		}
		$slogan = Setting::where('key','=','LOCATION_SLOGAN')->pluck('value')->first();
		$this->view->content = view('Location.home.home',[
																		'categories' => $categories,
																		'slogan' => $slogan
																	]);
		return $this->setContent();
	}

	public function postSaveLocation(Request $request){
		if($request->currentLocation){
			session()->put('currentLocation', $request->currentLocation);
		}else{
			echo 'Not location';
		}
	}

	public function newHome(){
		$list_category = Category::where('active',1)->where('approved',1)->limit(10)->get();
		$list_image = [];
		$list_link = [];
		foreach ($list_category as $key => $value) {
			$contents = Content::select('contents.alias','contents.avatar')
													 ->where('id_category',$value->id)
													 ->where('contents.active','=',1)
													 ->where('moderation','=','publish')
													 ->inRandomOrder()
													 ->limit(10)
													 ->get();
			$tmp_image = [];
			$tmp_link = [];
			foreach ($contents as $key2 => $content) {
				# code...
				$tmp_image[] = $content->avatar;
				$tmp_link[] = $content->alias;
			}
			$list_image[] = $tmp_image;
			$list_link[] = $tmp_link;
		}

		$this->view->content = view('Location.home.new_home',[
																		'list_category'	=> $list_category,
																		'list_image'   	=> $list_image,
																		'list_link'   	=> $list_link,
																	]);
		return $this->setContent();
	}

	public function getQrdecode(Request $request){
		$q = $request->q?$request->q:'';
		$value_return = qr_decode($q);
		if(is_array($value_return)){
			return response()->json($value_return);
		}else{
			header('Content-Type: json; charset=utf-8');
			echo $value_return;die;
		}
		
	}
}
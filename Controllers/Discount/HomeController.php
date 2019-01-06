<?php
namespace App\Http\Controllers\Discount;
use App\Models\Location\Category;
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

		$this->view->content = view('Discount.home.home',[
																		'categories' => $categories
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
}
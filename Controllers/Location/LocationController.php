<?php
namespace App\Http\Controllers\Location;
use App\Models\Location\Category;
use App\Models\Location\CategoryItem;
use App\Models\Location\Content;
class  LocationController extends BaseController {

	public function getContentByCountry($country){
		// $category = Category::where('alias','=',$category_alias)->get();
		// $list_category_item = CategoryItem::where('category_id','=',$category->id)->get();
		// $this->view->content = view('Location.home.home');
		// $this->view->content = view('Location.category.category');
		// return return $this->setContent();
		dd($country->toArray());
	}
	public function getContentByCity($city){
		// $category = Category::where('alias','=',$category_alias)->get();
		// $list_category_item = CategoryItem::where('category_id','=',$category->id)->get();
		// $this->view->content = view('Location.home.home');
		$this->view->content = view('Location.category.category');
		return  $this->setContent();
	}
}
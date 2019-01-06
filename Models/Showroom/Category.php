<?php

namespace App\Models\Showroom;
use App\Models\Showroom\Base;

use Illuminate\Database\Eloquent\Model;

class Category extends Base
{
  protected $table = 'pro_categories';
  protected $module = 'showroom';

  public function _created_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
	}

	public function _updated_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
	}

	public function sub_category()
	{
		$data = $this->belongsToMany('App\Models\Showroom\CategoryItem', 'pro_module_category_item','category_id','category_item_id')
								 ->leftJoin('pro_modules','pro_modules.id','pro_module_category_item.module_id')
								 ->where('pro_modules.machine_name','showroom')
								 ->where('pro_module_category_item.active','=',1)
								 ->orderBy('pro_module_category_item.weight','asc')
								 ->orderBy('pro_category_items.name','asc')
								 ->select(
												'pro_category_items.id',
												'pro_category_items.name',
												'pro_category_items.alias',
												'pro_module_category_item.image',
												'pro_module_category_item.weight'
											);
		return $data;
	}
}

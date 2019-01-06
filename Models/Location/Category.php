<?php

namespace App\Models\Location;
use App\Models\Location\Base;
use Illuminate\Database\Eloquent\Model;

class Category extends Base {

	protected $table = 'categories';


	public function _created_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
	}

	 public function _updated_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
	}

	public function _parent()
	{
		return $this->belongsTo('App\Models\Location\Category', 'parent', 'id');
	}

	public function child()
	{
		return $this->hasMany('App\Models\Location\Category', 'parent', 'id');
	}

	public function category_items()
	{
		$data = $this->hasMany('App\Models\Location\CategoryItem', 'category_id', 'id')
								->where('deleted','=',0)
								->where('active','=',1)
								->orderBy('weight','asc')
								->orderBy('name','asc');
		return $data;
	}

	public function service_items()
	{
		return $this->belongsToMany('App\Models\Location\ServiceItem', 'category_service','id_category','id_service_item')
								->where('service_items.active',1);
	}

	public function sub_category()
	{
		$data = $this->hasMany('App\Models\Location\CategoryItem', 'category_id', 'id')
								->where('deleted','=',0)
								->where('active','=',1)
								->orderBy('weight','asc')
								->orderBy('name','asc');
		return $data;
	}
}

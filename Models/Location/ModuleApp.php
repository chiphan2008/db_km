<?php

namespace App\Models\Location;
use App\Models\Location\Base;
use Illuminate\Database\Eloquent\Model;

class ModuleApp extends Base {

	protected $table = 'module_app';


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
		return $this->belongsTo('App\Models\Location\ModuleApp', 'parent', 'id');
	}

	public function child()
	{
		return $this->hasMany('App\Models\Location\ModuleApp', 'parent', 'id');
	}
}

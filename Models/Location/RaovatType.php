<?php

namespace App\Models\Location;
use App\Models\Location\Base;
use Illuminate\Database\Eloquent\Model;

class RaovatType extends Base {

	protected $table = 'raovat_type';


	public function _created_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
	}

	 public function _updated_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
	}

	public function _subtypes()
	{
		return $this->hasMany('App\Models\Location\RaovatSubType', 'raovat_type_id', 'id');
	}

}

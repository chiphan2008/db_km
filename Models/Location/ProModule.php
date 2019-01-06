<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class ProModule extends Base
{
  protected $table = 'pro_modules';

  public function _created_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
	}

	public function _updated_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
	}
}

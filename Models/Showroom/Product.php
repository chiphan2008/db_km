<?php

namespace App\Models\Showroom;
use App\Models\Showroom\Base;

use Illuminate\Database\Eloquent\Model;

class Product extends Base
{
  protected $table = 'pro_product';

  public function _created_by()
	{
		return $this->belongsTo('App\Models\Location\Client', 'created_by', 'id');
	}

	public function _updated_by()
	{
		return $this->belongsTo('App\Models\Location\Client', 'updated_by', 'id');
	}
}

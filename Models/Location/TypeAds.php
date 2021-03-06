<?php

namespace App\Models\Location;
use App\Models\Location\Base;
use Illuminate\Database\Eloquent\Model;

class TypeAds extends Base
{
  protected $table = 'type_ads';

  public function _created_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
	}

	 public function _updated_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
	}

	public function _price_ads()
	{
		return $this->hasMany('App\Models\Location\PriceAds', 'type_ads', 'id');
	}
}

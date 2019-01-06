<?php

namespace App\Models\Showroom;
use App\Models\Showroom\Base;

use Illuminate\Database\Eloquent\Model;

class CategoryItem extends Base
{
  protected $table = 'pro_category_items';
  protected $module = 'showroom';

  public function _created_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
	}

	public function _updated_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
	}

	public function scorpGetAll(){
		
	}
}

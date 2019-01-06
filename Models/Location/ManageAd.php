<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class ManageAd extends Base
{
    //
	protected $table = 'manage_ad';
	public function _content()
	{
		return $this->belongsTo('App\Models\Location\Content', 'content_id', 'id')
								->with('_category_type')
								->with('_category_items')
								->with('_country')
								->with('_city')
								->with('_district');
	}
}

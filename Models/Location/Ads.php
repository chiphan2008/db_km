<?php

namespace App\Models\Location;
use App\Models\Location\Base;
use Illuminate\Database\Eloquent\Model;

class Ads extends Base
{
  protected $table = 'ads';

  public function _content()
	{
		return $this->belongsTo('App\Models\Location\Content', 'content_id', 'id')
								->with('_category_type')
								->with('_category_items')
								->with('_country')
								->with('_city')
								->with('_district');
	}

	public function _base_content()
	{
		return $this->belongsTo('App\Models\Location\Content', 'content_id', 'id');
	}

	public function _type_ads()
	{
		return $this->belongsTo('App\Models\Location\TypeAds', 'type_ads', 'id');
	}

	public function _only_content()
	{
		return $this->belongsTo('App\Models\Location\Content', 'content_id', 'id');
	}

	public function _created_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
	}

	 public function _updated_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
	}

	public function _created_by_client()
  {
    return $this->belongsTo('App\Models\Location\Client', 'created_by', 'id');
  }
  public function _updated_by_client()
  {
    return $this->belongsTo('App\Models\Location\Client', 'updated_by', 'id');
  }
}

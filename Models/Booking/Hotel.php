<?php

namespace App\Models\Booking;
use App\Models\Booking\Base;
use App\Models\Booking\RoomType;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Base
{
  protected $table = 'hotel';

  public function _content()
	{
		return $this->belongsTo('App\Models\Location\Content', 'content_id', 'id')
								->with('_category_type')
								->with('_category_items')
								->with('_country')
								->with('_city')
								->with('_district');
	}

	public function _room_types()
	{
		return $this->hasMany('App\Models\Booking\RoomType','hotel_id','id')
								->where('room_type.active',1)
								->where('price','>',0)
								->where('price_km','>',0)
								->orderBy('price_km','ASC')
								->orderBy('price','ASC');
	}

	public function _only_content()
	{
		return $this->belongsTo('App\Models\Location\Content', 'content_id', 'id');
	}

	public function _types()
	{
		return $this->belongsToMany('App\Models\Booking\Type', 'hotel_type','hotel_id','type_id');
	}

	public function _created_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
	}

	 public function _updated_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
	}
}

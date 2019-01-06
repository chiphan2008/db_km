<?php

namespace App\Models\Booking;
use App\Models\Booking\Base;
use App\Models\Booking\Hotel;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Base
{
  protected $table = 'room_type';

	public function _created_by()
	{
		$data = $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
		return $data;
	}

	public function _hotel()
	{
		$data = $this->belongsTo('App\Models\Booking\Hotel', 'hotel_id', 'id');
		return $data;
	}

	public function _updated_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
	}

	public function _options()
	{
		return $this->belongsToMany('App\Models\Booking\Option', 'room_type_option','room_type_id','option_id')
								->where('extra',0);
	}

	public function _options_extra()
	{
		return $this->belongsToMany('App\Models\Booking\Option', 'room_type_option','room_type_id','option_id')
								->where('extra',1);
	}

	public function _images(){
		return $this->hasMany('App\Models\Booking\RoomTypeImage', 'room_type_id', 'id');
	}
}

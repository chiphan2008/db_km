<?php

namespace App\Models\Booking;
use App\Models\Booking\Base;
use App\Models\Location\Country;
use App\Models\Location\City;
use Illuminate\Database\Eloquent\Model;

class HomeBooking extends Base
{
  protected $table = 'home_booking';

  public function _created_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
	}

	public function _updated_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
	}

	public function _country()
	{
		return $this->belongsTo('App\Models\Location\Country', 'country_id', 'id');
	}

	public function _city()
	{
		return $this->belongsTo('App\Models\Location\City', 'city_id', 'id');
	}
}

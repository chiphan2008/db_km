<?php

namespace App\Models\Booking;
use App\Models\Booking\Base;
use Illuminate\Database\Eloquent\Model;

class Type extends Base
{
  protected $table = 'type';

  
	public function _created_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
	}

	 public function _updated_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
	}
}

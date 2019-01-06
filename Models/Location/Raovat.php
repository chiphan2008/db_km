<?php

namespace App\Models\Location;
use App\Models\Location\Base;
use Illuminate\Database\Eloquent\Model;

class Raovat extends Base {

	protected $table = 'raovat';


	public function _created_by()
	{
		return $this->belongsTo('App\Models\Location\Client', 'created_by', 'id');
	}

	 public function _updated_by()
	{
		return $this->belongsTo('App\Models\Location\Client', 'updated_by', 'id');
	}

	public function _images()
	{
		return $this->hasMany('App\Models\Location\RaovatImage', 'raovat_id', 'id');
	}

	public function _type()
  {
    return $this->belongsTo('App\Models\Location\RaovatType', 'raovat_type', 'id');
  }

  public function _country()
	{
		return $this->belongsTo('App\Models\Location\Country', 'country', 'id');
	}

	public function _city()
	{
		return $this->belongsTo('App\Models\Location\City', 'city', 'id');
	}

	public function _district()
	{
		return $this->belongsTo('App\Models\Location\District', 'district', 'id');
	}
public function _subtypes()
  {
      return $this->belongsToMany('App\Models\Location\RaovatSubType', 'raovat_raovat_subtype','raovat_id','raovat_subtype_id');
  }
}

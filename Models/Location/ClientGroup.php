<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class ClientGroup extends Base
{
  protected $table = 'client_group';
  
  public function _created_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
	}

	 public function _updated_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
	}

	public function _roles(){
		return $this->hasMany('App\Models\Location\ClientRole', 'group_id', 'id')
								->where('client_role.active',1);
	}
}

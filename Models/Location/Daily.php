<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class Daily extends Base
{
  protected $table = 'daily';

  public function _client(){
    return $this->hasOne('App\Models\Location\Client', 'id', 'client_id')
    						->select(
    							"id",
    							"full_name",
    							"avatar",
    							"email"
    						);
  }
}

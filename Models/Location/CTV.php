<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class CTV extends Base
{
  protected $table = 'ctv';

  public function _daily(){
    return $this->hasOne('App\Models\Location\Daily', 'id', 'daily_id')->with("_client");
  }
}

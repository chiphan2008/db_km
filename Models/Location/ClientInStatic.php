<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class ClientInStatic extends Base
{
  protected $table = 'client_in_static';
  public $timestamps = false;
}

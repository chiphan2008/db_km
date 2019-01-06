<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class ClientInRole extends Base
{
  protected $table = 'client_in_role';
  public $timestamps = false;
}

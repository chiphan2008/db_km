<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Base
{
  protected $table = 'permission_role';
  public $timestamps = false;
}

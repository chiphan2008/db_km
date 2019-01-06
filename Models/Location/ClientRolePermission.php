<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class ClientRolePermission extends Base
{
  protected $table = 'client_role_permission';
  public $timestamps = false;
}

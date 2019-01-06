<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Base
{
  protected $table = 'role_user';

  public $timestamps = false;

  protected $fillable = [
    'user_id', 'role_id'
  ];
}

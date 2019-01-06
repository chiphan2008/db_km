<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class GroupContent extends Base
{
  protected $table = 'group_content';

  public $timestamps = false;

  protected $fillable = [
    'name', 'id_group', 'id_content'
  ];
}

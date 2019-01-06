<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class Setting extends Base
{
  protected $table = 'settings_site';

  protected $fillable = [
    'key', 'value',
  ];
}

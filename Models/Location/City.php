<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class City extends Base
{
  protected $table = 'cities';

  public $timestamps = false;

  protected $fillable = [
    'name', 'machine_name', 'alias', 'id_country'
  ];
}

<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class District extends Base
{
  protected $table = 'districts';

  public $timestamps = false;

  protected $fillable = [
    'name', 'machine_name', 'alias', 'id_city'
  ];
}

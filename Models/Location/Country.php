<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class Country extends Base
{
  protected $table = 'countries';

  public $timestamps = false;

  protected $fillable = [
    'name', 'zipcode', 'machine_name', 'alias'
  ];
}

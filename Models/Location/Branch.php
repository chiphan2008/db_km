<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class Branch extends Base
{
  protected $table = 'branch';

  public $timestamps = false;

  protected $fillable = [
    'id_content', 'id_content_other','active'
  ];
}

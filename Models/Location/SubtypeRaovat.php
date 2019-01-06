<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class SubtypeRaovat extends Base
{
  protected $table = 'raovat_raovat_subtype';

  public $timestamps = false;

  protected $fillable = [
    'raovat_id','raovat_subtype_id'
  ];
}

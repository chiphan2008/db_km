<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class NotifiContent extends Base
{
  protected $table = 'notifi_content';
  public $timestamps = false;

  protected $fillable = [
    'id_content', 'title', 'description', 'active', 'start', 'end'
  ];
}

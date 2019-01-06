<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Base
{
  protected $table = 'checkin';

  public $timestamps = false;

  protected $fillable = [
    'id_content', 'id_user'
  ];
}

<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class NotifiType extends Base
{
  protected $table = 'notifi_type';

  protected $fillable = [
    'title', 'status', 'created_by', 'updated_by'
  ];
}

<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class ServiceContent extends Base
{
  protected $table = 'service_content';

  public $timestamps = false;

  protected $fillable = [
    'id_content', 'id_service_item'
  ];
}

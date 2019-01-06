<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Base
{
  protected $table = 'service_items';

  protected $fillable = [
    'name', 'machine_name', 'active', 'created_by', 'updated_by',
  ];

  protected $visible = ['id', 'name'];
}

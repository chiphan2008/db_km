<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class ContentType extends Base
{
  protected $table = 'content_types';

  protected $fillable = [
    'name', 'machine_name', 'alias', 'description', 'language'
  ];
}

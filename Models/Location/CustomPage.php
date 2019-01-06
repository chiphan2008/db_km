<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class CustomPage extends Base
{
  protected $table = 'custom_pages';

  protected $fillable = [
    'title', 'machine_name', 'alias', 'content', 'created_by', 'updated_by', 'status',
  ];

  public function _created_by()
  {
    return $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
  }
}

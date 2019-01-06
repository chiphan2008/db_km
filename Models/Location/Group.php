<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class Group extends Base
{
  protected $table = 'groups';

  protected $fillable = [
    'name', 'machine_name', 'id_category', 'alias', 'created_by', 'updated_by',
  ];

  public function _category_type()
  {
    return $this->belongsTo('App\Models\Location\Category', 'id_category', 'id');
  }
}

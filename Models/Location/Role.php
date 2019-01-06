<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
  protected $table = 'roles';

  protected $fillable = [
    'machine_name', 'name','description','created_by','updated_by'
  ];
  public function _created_by()
    {
        return $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
    }

   public function _updated_by()
    {
        return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
    }
}
<?php

namespace App\Models\Location;
use App\Models\Location\Base;
use Illuminate\Database\Eloquent\Model;

class RaovatSubType extends Base {

  protected $table = 'raovat_subtype';

  public function _created_by()
  {
      return $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
  }

   public function _updated_by()
  {
      return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
  }

  public function _type()
  {
      return $this->belongsTo('App\Models\Location\RaovatType', 'raovat_type_id', 'id');
  }
}

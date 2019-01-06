<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class Discount extends Base
{
    //
	protected $table = 'discount';

  public function _products(){
    return $this->belongsToMany('App\Models\Location\Product', 'discount_product','discount_id','product_id');
  }
  public function _base_content()
  {
    return $this->belongsTo('App\Models\Location\Content', 'id_content', 'id');
  }
  public function _created_by_client()
  {
    return $this->belongsTo('App\Models\Location\Client', 'created_by', 'id');
  }
  public function _updated_by_client()
  {
    return $this->belongsTo('App\Models\Location\Client', 'updated_by', 'id');
  }
}

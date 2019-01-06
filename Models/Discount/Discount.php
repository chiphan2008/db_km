<?php

namespace App\Models\Discount;
use App\Models\Discount\Base;

use Illuminate\Database\Eloquent\Model;

class Discount extends Base
{
    //

	protected $table = 'discount';

  public function __construct(){
    $this->table = \Config::get('database.connections.discount.database').'.discount';
  }
	public function _contents()
  {
      return $this->belongsToMany('App\Models\Location\Content', \Config::get('database.connections.discount.database').'.discount_content','discount_id','id_content')
      ->with('_district')
      ->with('_city')
      ->with('_country');
  }

  public function _images()
  {
      return $this->hasMany('App\Models\Discount\DiscountImage', 'discount_id', 'id');
  }

	public function _date_open()
  {
      return $this->hasMany('App\Models\Discount\DateDiscount', 'discount_id', 'id');
  }

  public function _created_by()
  {
    return $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
  }
  public function _updated_by()
  {
    return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
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

<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class CategoryService extends Base
{
  protected $table = 'category_service';

  public $timestamps = false;

  protected $fillable = [
    'id_category', 'id_service_item'
  ];

  public function _service_item()
  {
    return $this->belongsTo('App\Models\Location\ServiceItem', 'id_service_item', 'id')
    						->where('active','=',1)
                ->where('approved','=',1);
  }
}

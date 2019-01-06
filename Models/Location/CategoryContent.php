<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class CategoryContent extends Base
{
  protected $table = 'category_content';

  public $timestamps = false;

  protected $fillable = [
    'id_content', 'id_category_item'
  ];

  public function _service_name()
  {
    return $this->belongsTo('App\Models\Location\CategoryItem', 'id_category_item', 'id');
  }

  public function _category_item()
  {
    return $this->belongsTo('App\Models\Location\CategoryItem', 'id_category_item', 'id');
  }

  public function _content()
  {
    return $this->belongsTo('App\Models\Location\Content', 'id_content', 'id');
  }
}

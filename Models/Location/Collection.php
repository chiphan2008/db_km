<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class Collection extends Base
{
  protected $table = 'collection';
  public function _contents()
  {
      return $this->belongsToMany('App\Models\Location\Content', 'collection_content','collection_id','content_id')
      				    ->where('contents.active','=',1)->orderBy('collection_content.created_at','desc');
  }
}

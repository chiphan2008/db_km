<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class ImageSpace extends Base
{
  protected $table = 'image_spaces';
  public $timestamps = false;

  protected $fillable = [
    'id_content', 'name','title','description'
  ];
}

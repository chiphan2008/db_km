<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class ImageMenu extends Base
{
  protected $table = 'image_menus';
  public $timestamps = false;

  protected $fillable = [
    'id_content', 'name','title','description'
  ];
}

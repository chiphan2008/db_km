<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class LinkContent extends Base
{
  protected $table = 'link_contents';
  public $timestamps = false;

  protected $fillable = [
    'id_content', 'link','type','time','title','id_video','thumbnail'
  ];
}

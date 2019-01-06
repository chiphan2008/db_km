<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class LikeContent extends Base
{
  protected $table = 'like_content';

  public $timestamps = false;

  protected $fillable = [
    'id_content', 'id_user'
  ];
}

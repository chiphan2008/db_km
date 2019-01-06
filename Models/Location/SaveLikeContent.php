<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class SaveLikeContent extends Base
{
    //
	protected $table = 'save_like_content';

  public $timestamps = false;

  protected $fillable = [
    'id_content', 'id_user'
  ];
}

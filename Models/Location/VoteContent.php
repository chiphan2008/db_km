<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class VoteContent extends Base
{
  protected $table = 'vote_content';

  public $timestamps = false;

  protected $fillable = [
    'id_content', 'id_user', 'vote_point'
  ];
}

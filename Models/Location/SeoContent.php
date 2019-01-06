<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class SeoContent extends Base
{
  protected $table = 'seo_content';

  public $timestamps = false;

  protected $fillable = [
    'id_content', 'key_word', 'description'
  ];
}

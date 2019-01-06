<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class CustomPageLanguage extends Base
{
  protected $table = 'custom_page_language';

  public $timestamps = false;

  protected $fillable = [
    'id_custom_page', 'title', 'content', 'lang'
  ];
}

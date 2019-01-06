<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class DateOpen extends Base
{
    //
	protected $table = 'date_open';
	public $timestamps = false;
  protected $fillable = [
		'id_content',
		'date_from',
		'date_to',
		'open_from',
		'open_to',
  ];

}

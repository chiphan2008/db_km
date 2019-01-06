<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class TraceUser extends Base
{
    //
	protected $table = 'trace_user';

	protected $fillable = [
    'key','ip','agent','os','device','browser','page'
  ];
}

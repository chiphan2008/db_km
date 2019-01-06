<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class GiaoViec extends Base
{
  protected $table = 'giaoviec';

  public $incrementing = false;


  protected $fillable = [
    'from_client','to_client','content'
  ];

  public function _from_client()
	{
		return $this->belongsTo('App\Models\Location\Client', 'from_client', 'id');
	}

	public function _to_client()
	{
		return $this->belongsTo('App\Models\Location\Client', 'to_client', 'id');
	}

}

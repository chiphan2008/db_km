<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class HistorySearch extends Base
{
    //
	protected $table = 'history_search';

	protected $fillable = [
    'keyword','ip','agent'
  ];

	public function _created_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'created_by', 'id');
	}

	 public function _updated_by()
	{
		return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
	}
}

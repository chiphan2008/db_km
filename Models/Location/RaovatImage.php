<?php

namespace App\Models\Location;
use App\Models\Location\Base;
use Illuminate\Database\Eloquent\Model;

class RaovatImage extends Base {

	protected $table = 'raovat_image';
	public $timestamps = false;

  protected $fillable = [
    'raovat_id', 'link'
  ];
}

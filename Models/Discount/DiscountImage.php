<?php

namespace App\Models\Discount;
use App\Models\Discount\Base;

use Illuminate\Database\Eloquent\Model;

class DiscountImage extends Base
{
    //
	protected $table = 'discount_image';

  protected $fillable = [
    'discount_id', 'link'
  ];
}

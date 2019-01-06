<?php

namespace App\Models\Discount;
use App\Models\Discount\Base;

use Illuminate\Database\Eloquent\Model;

class DiscountContent extends Base
{
    //
	
	protected $table = 'discount_content';
  protected $fillable = ['id_content', 'discount_id'];
}

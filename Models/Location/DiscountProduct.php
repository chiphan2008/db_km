<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class DiscountProduct extends Base
{
    //
	
	protected $table = 'discount_product';
  protected $fillable = ['discount_id', 'id_content','product_id','id_category'];
}

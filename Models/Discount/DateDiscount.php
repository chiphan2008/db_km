<?php

namespace App\Models\Discount;
use App\Models\Discount\Base;

use Illuminate\Database\Eloquent\Model;

class DateDiscount extends Base
{
    //
	protected $table = 'date_discount';
	public $timestamps = false;
  protected $fillable = [
		'discount_id',
		'date_from',
		'date_to',
		'time_from',
		'time_to',
  ];
}

<?php

namespace App\Models\Booking;
use App\Models\Booking\Base;
use Illuminate\Database\Eloquent\Model;

class HotelType extends Base
{
  protected $table = 'hotel_type';
  public $timestamps = false;
  protected $fillable = ['hotel_id', 'type_id'];
}

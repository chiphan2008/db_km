<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class ClientStatic extends Base
{
  protected $table = 'client_static';

  public function _content()
  {
    return $this->belongsTo('App\Models\Location\Content', 'content_id', 'id');
  }

  public function _transaction()
  {
    return $this->belongsTo('App\Models\Location\TransactionCoin', 'transaction_id', 'id');
  }
}

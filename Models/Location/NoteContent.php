<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class NoteContent extends Base
{
  protected $table = 'note_content';

  protected $fillable = [
    'id_content', 'id_user', 'note', 'content', 'created_by', 'updated_by'
  ];

  public function _user_create()
  {
    return $this->belongsTo('App\Models\Location\User', 'id_user', 'id');
  }
}

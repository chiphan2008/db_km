<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Base
{
  protected $table = 'email_templates';

  protected $fillable = [
    'name', 'machine_name', 'subject', 'body',
  ];

  public function parse($data)
  {
    $parsed = preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($data) {
      list($shortCode, $index) = $matches;
      if( isset($data[$index]) ) {
        return $data[$index];
      } else {
        throw new \Exception("Shortcode {$shortCode} not found in template id {$this->id}", 1);
      }

    }, $this->body);

    return $parsed;
  }
}

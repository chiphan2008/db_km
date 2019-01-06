<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class TemplateNotifi extends Base
{
	protected $table = 'template_notifi';
	protected $fillable = ['name','machine_name','content','language'];
}

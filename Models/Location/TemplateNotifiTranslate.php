<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;

class TemplateNotifiTranslate extends Base
{
	protected $table = 'template_notifi_translate';
	protected $fillable = ['name','machine_name','content','language'];
}

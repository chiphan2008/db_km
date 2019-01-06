<?php

namespace App\Http\Controllers\Location;
use App\Models\Location\Client;
use App\Models\Location\Notifications\Notice;
class NoticeController extends BaseController
{
	public function getTest(){
		$user = Client::find('1');
		$user->notify(new Notice());
		echo "Done";
	}
}
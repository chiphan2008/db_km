<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Session;
use Browser;
use App\Models\Location\TraceUser;
class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
  public function trace_user($page='location'){
  	if(!Browser::isBot()){
			$agent = Browser::userAgent();
			$browser = Browser::browserName();
			$os = Browser::platformName();
			$device = Browser::deviceFamily().' '.Browser::deviceModel().' '.Browser::mobileGrade();
			$ip = getUserIP();
			$key = base64_encode($agent.', '.$browser.', '.$os.', '.$device.', '.$ip.', '.$page);
			$session = md5($agent.', '.$browser.', '.$os.', '.$device.', '.$ip.', '.$page);
			TraceUser::updateOrCreate([
				'key'			=> $key
			],[
				'key'     => $key,
				'ip'      => $ip,
				'agent'   => $agent,
				'os'      => $os,
				'browser' => $browser,
				'device'  => $device,
				'page'		=> $page
			]);
		}
  }
}

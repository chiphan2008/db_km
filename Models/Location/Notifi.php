<?php

namespace App\Models\Location;
use App\Models\Location\Base;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location\NotifiUser;
//use App\Events\getNotifi;
use DB;
use Carbon\Carbon;
use App\Events\getNotifi;
class Notifi extends Base
{
    //
	protected $table = 'notifi';

	public function scopeGetNews($query)
	{
		$query->select('notifi.*')
					->where('is_everyone','=',1)
					->where('show','=',1)
					->where('active','=',1) 
					->orderBy('updated_at','desc')
					->orderBy('created_at','desc')
					->limit(15);
		return $query;
	}

	public function scopeGetNotifi($query,$skip=0,$limit=15)
	{
		$id_user = 0;
		if(Auth::guard('web_client')->user()){
			$id_user = Auth::guard('web_client')->user()->id;
		}
		$query->select('notifi.*', 'notifi_user.read_at')
					->where('show','=',1)
					->where('active','=',1)
					->where('is_everyone','=',0)
					->leftJoin('notifi_user',function($join) use($id_user){
						$join->on('notifi_user.notifi_id', '=', 'notifi.id');
					})
					->where('user_id','=',$id_user)
					->orderBy('updated_at','desc')
					->orderBy('created_at','desc')
					->skip($skip)
					->limit($limit);
		return $query;
	}

	public function scopeGetCountNotifi($query)
	{
		$id_user = 0;
		if(Auth::guard('web_client')->user()){
			$id_user = Auth::guard('web_client')->user()->id;
		}
		$query->select('notifi.*', 'notifi_user.read_at')
					->where('show','=',1)
					->where('active','=',1)
					->where('is_everyone','=',0)
					->leftJoin('notifi_user',function($join) use($id_user){
						$join->on('notifi_user.notifi_id', '=', 'notifi.id');
					})
					->where('user_id','=',$id_user)
					->whereNull('notifi_user.read_at')
					->orderBy('updated_at','desc')
					->orderBy('created_at','desc');
		return $query;
	}


	public function createNotifiUser($content, $user_id,$link='',$contentText=""){
		$notifi_id = DB::table('notifi')
				->insertGetId([
					'content'    => $content,
					'contentText'    => $contentText,
					'image'		=>	'/frontend/assets/img/logo/logo-icon.png',
					'is_system'    => 1,
					'link' => $link,
					'active'    => 1,
					'show'    => 1,
					'created_at' =>	new Carbon(),
					'updated_at' =>	new Carbon()
				]);
		$notifi_user = new NotifiUser();
		$notifi_user->notifi_id = $notifi_id;
		$notifi_user->user_id = $user_id;
		$notifi_user->save();
		$notifi = format_noti($notifi_id);
		if($notifi->show && $notifi->active){
			event(new getNotifi($notifi->toArray(),$user_id));
		}
		return $notifi_id;
	}

	public function createNotifiUserByTemplate($content, $user_id, $arr_data,$link='',$contentText=""){
		$notifi_id = DB::table('notifi')
				->insertGetId([
					'content'    => $content,
					'contentText'    => $contentText,
					'image'		=>	'/frontend/assets/img/logo/logo-icon.png',
					'template_notifi_id' => '9999999',
					'data'	=>	json_encode($arr_data),
					'is_system'    => 1,
					'link' => $link,
					'active'    => 1,
					'show'    => 1,
					'created_at' =>	new Carbon(),
					'updated_at' =>	new Carbon()
				]);
		$notifi_user = new NotifiUser();
		$notifi_user->notifi_id = $notifi_id;
		$notifi_user->user_id = $user_id;
		$notifi_user->save();
		$notifi = format_noti($notifi_id);
		if($notifi->show && $notifi->active){
			event(new getNotifi($notifi->toArray(),$user_id));
		}
		return $notifi_id;
	}

	public function updateNotifiUserByTemplate($id, $content, $user_id, $arr_data,$contentText=""){
		$notifi_id = $id;
		DB::table('notifi')
			->where('id',$id)
			->update([
					'content'    => $content,
					'contentText'    => $contentText,
					'image'		=>	'/frontend/assets/img/logo/logo-icon.png',
					'template_notifi_id' => '9999999',
					'data'	=>	json_encode($arr_data),
					'is_system'    => 1,
					'active'    => 1,
					'show'    => 1,
					'updated_at' =>	new Carbon()
				]);

		$notifi_user = new NotifiUser();
		$notifi_user->notifi_id = $notifi_id;
		$notifi_user->user_id = $user_id;
		$notifi_user->save();
		$notifi = format_noti($notifi_id);
		if($notifi->show && $notifi->active){
			event(new getNotifi($notifi->toArray(),$user_id));
		}
	}

}

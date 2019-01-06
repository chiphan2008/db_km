<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use App\Roles;
use App\Models\Location\User;
use Illuminate\Support\Facades\Auth;
class NotifiAdmin extends Base
{
    //
	protected $table = 'notifi_admin';
	public $timestamps = true;
	public function createNotifi($content, $link){
		$notifi_id = DB::table('notifi_admin')
				->insertGetId([
					'content'    => $content,
					'link'       => $link,
					'created_at' =>	new Carbon(),
					'updated_at' =>	new Carbon()
				]);
		$list_Role = Role::where('id','<',4)->pluck('id');
		$list_User = User::leftJoin('role_user','users.id','=','role_user.user_id')
											->where('users.active','=',1)
											->whereIn('role_user.role_id',$list_Role)
											->pluck('users.id');
		$arr_data = [];
		foreach ($list_User as $key => $value) {
			$arr_data[] = [
				'user_id'			=>		$value,
				'notifi_id'		=>		$notifi_id,
				'created_at'	=>		new Carbon(),
				'updated_at'	=>		new Carbon()
			];
		}
		DB::table('notifi_user_admin')->insert($arr_data);
	}

	public function scopeGetNotifi($query)
	{
		$id_user = 0;
		if(Auth::guard('web')->user()){
			$id_user = Auth::guard('web')->user()->id;
		}
		
		$query->select('notifi_admin.*', 'notifi_user_admin.read_at', \DB::Raw("ISNULL(notifi_user_admin.read_at) as not_read"))
					->leftJoin('notifi_user_admin',function($join) use ($id_user){
						return $join->on('notifi_user_admin.notifi_id', '=', 'notifi_admin.id')
								 				;
					})
					->where('notifi_user_admin.user_id','=',$id_user)
					->orderBy('not_read','DESC')
					->orderBy('created_at','desc')
					->orderBy('updated_at','desc');
		return $query;
	}
}

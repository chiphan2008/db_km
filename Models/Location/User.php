<?php

namespace App\Models\Location;
use App\Models\Location\Base;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\Models\Location\Notifications\AdminResetPasswordNotification;

class User extends Authenticatable
{
	use Notifiable;
	// use EntrustUserTrait;
	protected $connection = 'mysql';
	protected $table = 'users';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'username', 'password', 'full_name', 'email', 'avatar', 'active', 'parent','created_by', 'updated_by', 'session_id'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	public function sendPasswordResetNotification($token)
	{
		$this->notify(new AdminResetPasswordNotification($token));
	}

	public function _role_user()
	{
		return $this->belongsTo('App\Models\Location\RoleUser', 'id', 'user_id');
	}

	use EntrustUserTrait {
		can as entrustCan;
	}

	public function can($permission, $requireAll = false)
	{
		if ($this->hasRole('super_admin')) {
			return true;
		}
		return $this->entrustCan($permission, $requireAll);
	}
}

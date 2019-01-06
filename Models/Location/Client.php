<?php

namespace App\Models\Location;
use App\Models\Location\Base;
use App\Models\Location\ClientRole;
use App\Models\Location\ClientInRole;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ClientResetPasswordNotification;

class Client extends Authenticatable
{
  use HasApiTokens,Notifiable;
  protected $connection = 'mysql';
  protected $fillable = [
    'full_name', 'email', 'password', 'avatar', 'phone', 'id_facebook', 'id_google','active','cmnd','code_invite','register_invite','rate_revenue', 'role_id', 'daily_code'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  //Send password reset notification
  public function sendPasswordResetNotification($token)
  {
    $this->notify(new ClientResetPasswordNotification($token));
  }

  public function _content_client()
  {
//    return $this->belongsTo('App\Models\Location\ContentType', 'content_type_id', 'id');
    return $this->hasMany('App\Models\Location\Content', 'id', 'created_by');
  }

  public function _collections()
  {
      return $this->hasMany('App\Models\Location\Collection', 'id','created_by');
  }

  public function _checkin()
  {
    return $this->hasMany('App\Models\Location\Checkin', 'id', 'id_user');
  }

  public function notifi(){
    return $this->belongsToMany('App\Models\Location\Notifi', 'notifi_user','user_id','notifi_id');
  }

  public function _roles(){
    return $this->belongsToMany('App\Models\Location\ClientRole', 'client_in_role','client_id','role_id')
                ->leftJoin('client_group','client_role.group_id', 'client_group.id')
                ->select('client_role.id','client_role.name','client_role.machine_name','client_in_role.active','client_role.group_id');
  }

  public function _area(){
    return $this->belongsToMany('App\Models\Location\District', 'client_area','client_id','district_id');
  }

  public function scopeHasRole($query, $role_name){
    $role = ClientRole::where('machine_name',$role_name)->first();
    if($role){
      $client_role = ClientInRole::where('client_id',$this->id)
                                  ->where('role_id',$role->id)
                                  ->first();
      if($client_role){
        if($client_role->active){
          return 1;
        }else{
          return -2;
        }
      }
    }
    return -1;
  } 

  public function scopeGetRole($query, $role_name){
    return $this->belongsToMany('App\Models\Location\ClientRole', 'client_in_role','client_id','role_id')
                ->where('client_role.machine_name',$role_name)
                ->select('client_role.id','client_role.name','client_role.machine_name','client_in_role.active');
  }
  public function scopeGetArea($query){
    return $this->belongsToMany('App\Models\Location\District', 'client_area','client_id','district_id');
  }

  public function _ctv(){
    $ctv =  $this->hasOne('App\Models\Location\CTV', 'client_id', 'id')->with('_daily');
    return $ctv;
  }

  public function _updated_by()
  {
    return $this->belongsTo('App\Models\Location\User', 'updated_by', 'id');
  }
  public function _updated_by_client()
  {
    return $this->belongsTo('App\Models\Location\Client', 'updated_by', 'id');
  }

}

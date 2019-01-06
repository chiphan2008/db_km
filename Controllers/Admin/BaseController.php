<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location\Setting;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Config;
use Session;
use Illuminate\Http\Request;
use App\Models\Location\Menu;
use App\Models\Location\NotifiAdmin;
use App\Models\Location\NotifiUserAdmin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
  protected $user;
  public function __construct () {
        $this->trace_user('admin');
        $this->middleware(function ($request, $next) {

            // Set module admin
            session()->put('module_admin', 'location');
            if ($request->is('admin') || $request->is('admin/*')){
              session()->put('module_admin', 'location');
            }
            if ($request->is('booking') || $request->is('booking/*')){
              session()->put('module_admin', 'booking');
            }

            if ($request->is('discount') || $request->is('discount/*')){
              session()->put('module_admin', 'discount');
            }

            if ($request->is('ads') || $request->is('ads/*')){
              session()->put('module_admin', 'ads');
            }

            if ($request->is('raovat') || $request->is('raovat/*')){
              session()->put('module_admin', 'raovat');
            }

            if ($request->is('showroom') || $request->is('showroom/*')){
              session()->put('module_admin', 'showroom');
            }

            $module = session()->get('module_admin');
            if (!$module){
              session()->put('module_admin', 'location');
              $module = 'location';
            }

            // Set language admin
            $lang = session()->get('language_be');
            if ($lang != null) {
              \App::setLocale($lang);
            } else {
              $lang = \App::getLocale();
              session()->put('language_be', $lang);
            }
            // Get Sidebar
            $this->user = Auth::guard('web')->user();
            if($this->user){
              $sidebar = Menu::getSidebar();
              \View::share('sidebar', $sidebar);
            }

            $title = $this->getSetting('site_name');
            $title = isset($title) && $title!='' ? $title : 'KingMap';
            \View::share('title', $title);

            $favicon = $this->getSetting('favicon');
            $favicon = isset($favicon) && $favicon!='' ? $favicon : 'KingMap';
            \View::share('favicon', $favicon);

            $client_id_google = $this->getSetting('client_id_google');
            $client_id_google = isset($client_id_google) && $client_id_google!='' ? $client_id_google : '';
            \View::share('client_id_google', $client_id_google);

            if(Auth::guard('web')->user()){
              $count_notifications = 0;
              $count_notifications = NotifiUserAdmin::where('user_id', Auth::guard('web')->user()->id)
                                                    ->whereNull('read_at')
                                                    ->count();
//              if($count_notifications>0){
//                $notifications = NotifiAdmin::getNotifi()->limit($count_notifications)->get();
//              }else{
                $notifications = NotifiAdmin::getNotifi()->limit(10)->get();
//              }
              
                                              
              // foreach ($notifications as $key => $value) {
              //   echo $value->id.'<br/>';
              // }
              // die;
              // $count_notifications = $count_notifications;
              \View::share('notifications', $notifications);
              \View::share('count_notifications', $count_notifications);
            }

            return $next($request);
        });  
    }

  public function getLangue (Request $request, $lang) {
    session()->put('language_be', $lang);
    \App::setLocale($lang);
  }

  public function getSetting($key)
  {
    return Setting::where('key','=',$key)->pluck('value')->first();
  }

  public function readNotifi(){
    if(Auth::guard('web')->user()){
      $id_user = Auth::guard('web')->user()->id;
      return  NotifiUserAdmin::where('user_id','=',$id_user)
                        ->whereNull('read_at')
                        ->update([
                          'read_at' => new Carbon()
                        ]);
    }
  }

    public function readEachNotifi($id){
        if(Auth::guard('web')->user()){
            $id_user = Auth::guard('web')->user()->id;
            return  NotifiUserAdmin::where('notifi_id','=',$id)
                ->where('user_id','=',$id_user)
                ->whereNull('read_at')
                ->update([
                    'read_at' => new Carbon()
                ]);
        }
    }
}

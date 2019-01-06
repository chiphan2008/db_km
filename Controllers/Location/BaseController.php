<?php
namespace App\Http\Controllers\Location;
use App\Models\Location\Category;
use App\Models\Location\Content;
use App\Models\Location\Country;
use App\Models\Location\NotifiUser;
use App\Models\Location\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Location\Menu;
use App\Models\Location\User;
use App\Models\Location\Notifi;
use Carbon\Carbon;
use Lang;
use Browser;
use Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use App\Events\getNotifi;
class BaseController extends Controller {
	protected $layout = 'Location.layout.master';
	protected $view ;

	public function __construct(Request $request){
		// echo 'construct';
		$lang = session()->get('language_fe');
		if ($lang != null) {
			\App::setLocale($lang);
		} else {
			$lang = \App::getLocale();
			session()->put('language_fe', $lang);
		}	
		$this->middleware(function ($request, $next) {
			// echo 'middle';
			
			return $next($request);
		});  
		$this->view = (object)[];
		//Some global variable 
		// echo $lang;
		$this->view->menu = Menu::getFrontendMenu($lang);

    $title = $this->getSetting('site_name');
    $title = isset($title) && $title!='' ? $title : 'KingMap';
		$this->view->title = $title;

    $description = $this->getSetting('site_description');
    $description = isset($description) && $description!='' ? $description : 'KingMap';
    $this->view->meta_description = $description;

    $favicon = $this->getSetting('favicon');
    $favicon = isset($favicon) && $favicon!='' ? $favicon : 'KingMap.ico';
    $this->view->favicon = $favicon;

    $google_analytics = $this->getSetting('google_analytics');
    $google_analytics = isset($google_analytics) && $google_analytics!='' ? $google_analytics : '';
    $this->view->google_analytics = $google_analytics;

    $this->view->meta_tag = "";
		$this->view->meta_section = "";
		$this->view->url = url('/');
		
    $this->view->news = Notifi::getNews()->get();
    $this->view->count_news = Notifi::getNews()->count();
    $count_notifications = 0;
    $this->view->notifications = null;
    if(Auth::guard('web_client')->user()){
      $this->view->notifications = Notifi::getNotifi()->get();
      if($this->view->notifications){
        $arr_noti_read = [];
        foreach ($this->view->notifications as $key => $value) {
          $arr_noti_read[] = $value->id;
        }
        NotifiUser::whereIn('notifi_id',$arr_noti_read)
                  ->update([
                    'read_at' => \Carbon::now()
                  ]);
      }
      $count_notifications = Notifi::getCountNotifi()->count();
      // dd(Notifi::getCountNotifi()->count());
    }
    $this->view->count_notifications = $count_notifications;
    
    $this->view->static = Content::getStatic();

		$this->updateDotEnv('services.google.client_id',$this->getSetting('client_id_google'));
		$this->updateDotEnv('services.google.client_secret',$this->getSetting('client_secret_google'));
		$this->updateDotEnv('services.google.redirect',$this->getSetting('redirect_google'));
    $this->updateDotEnv('services.facebook.client_id',$this->getSetting('client_id_facebook'));
    $this->updateDotEnv('services.facebook.client_secret',$this->getSetting('client_secret_facebook'));
    $this->updateDotEnv('services.facebook.redirect',$this->getSetting('redirect_facebook'));
    //$this->updateDotEnv('LOCATION_SLOGAN',$this->getSetting('LOCATION_SLOGAN'),"'");

    // all data add new content.
    $categories = $this->getAllCategory();
    //dd($categories);
    foreach ($categories as $key => $value) {
      $categories[$key]->name = app('translator')->getFromJson($value->name);
    }
    $this->view->category = $categories;

    if(Auth::guard('web_client')->user()){
      $role_ctv = \Auth::guard('web_client')->user()->getRole('cong_tac_vien')->first();
      if($role_ctv && $role_ctv->active){
        $this->view->country = Country::rightJoin('client_area','client_area.country_id','countries.id')
                                      ->where('client_id',\Auth::guard('web_client')->user()->id)
                                      ->pluck('name', 'id');
        $this->view->countries =  Country::rightJoin('client_area','client_area.country_id','countries.id')
                                         ->where('client_id',\Auth::guard('web_client')->user()->id)
                                         ->get();
      }else{
        $this->view->country = Country::pluck('name', 'id');
        $this->view->countries = Country::get();
      }
    }else{
      $this->view->country = Country::pluck('name', 'id');
      $this->view->countries = Country::get();
    }
    

    $this->trace_user();
    $this->view->categories = $categories;
  }

	public function setLangue (Request $request, $lang) {
		session()->put('language_fe', $lang);
		\App::setLocale($lang);
	}

	public function setContent()
	{
		return view('Location.layout.master', (array)$this->view);
	}

  protected function updateDotEnv($key, $newValue, $delim='')
  {
    
    $path = base_path('.env');
    // get old value from current env
    $oldValue = config($key);

    // was there any change?
    if ($oldValue!= null && $oldValue === $newValue) {
      return true;
    }
    $key = str_replace('.','_',$key);
    if (file_exists($path)) {
      file_put_contents(
        $path, str_replace(
          $key.'='.$delim.$oldValue.$delim,
          $key.'='.$delim.$newValue.$delim,
          file_get_contents($path)
        )
      );
    }
  }

  public function getSetting($key)
  {
    return Setting::where('key','=',$key)->pluck('value')->first();
  }

  public function getAllCategory()
  {
    return Category::where('active','=',1)->orderBy('weight')->get();
  }

  public function readNotifi(Request $request){
    if($request->id_user) {
      $id_user = $request->id_user;
      return  NotifiUser::where('user_id','=',$id_user)
                        ->whereNull('read_at')
                        ->update([
                          'read_at' => new Carbon()
                        ]);
    }
  }

  public function saveCookies(Request $request){
    if($request->show_list_map){
      Cookie::queue(Cookie::make('show_list_map', $request->show_list_map, 30*60*24));
    }
    return $_COOKIE;
  }

  public function getHTMLNotifi($id){
    $notifi = Notifi::select('notifi.*', 'notifi_user.read_at')
                    ->where('id','=',$id)
                    ->leftJoin('notifi_user',function($join){
                      $join->on('notifi_user.notifi_id', '=', 'notifi.id');
                    })
                    ->first();
    return view('Location.layout.layout_notifi',["notifi"=>$notifi]);
  }
}
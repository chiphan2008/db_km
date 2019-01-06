<?php
namespace App\Http\Controllers\Booking;
use App\Models\Location\Category;
use App\Models\Location\Content;
use App\Models\Location\Country;
use App\Models\Location\City;
use App\Models\Location\NotifiUser;
use App\Models\Location\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Location\Menu;
use App\Models\Location\Notifi;
use App\Models\Booking\Type;
use Carbon\Carbon;
use Lang;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller {
	protected $layout = 'Booking.layout.master';
	protected $view ;

	public function __construct(Request $request){
    $this->trace_user('booking');
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
    if(Auth::guard('web_client')->user()){
      $count_notifications = 0;
      $this->view->notifications = Notifi::getNotifi()->get();
      foreach ($this->view->notifications as $key => $value) {
        if(!$value->read_at){
          $count_notifications++;
        }
      }
      $this->view->count_notifications = $count_notifications;
    }

    $this->view->types = Type::where('active',1)->get();
    $this->view->list_city = City::get();

    
    $this->view->static = Content::getStatic();

		$this->updateDotEnv('services.google.client_id',$this->getSetting('client_id_google'));
		$this->updateDotEnv('services.google.client_secret',$this->getSetting('client_secret_google'));
		$this->updateDotEnv('services.google.redirect',$this->getSetting('redirect_google'));
    $this->updateDotEnv('services.facebook.client_id',$this->getSetting('client_id_facebook'));
    $this->updateDotEnv('services.facebook.client_secret',$this->getSetting('client_secret_facebook'));
    $this->updateDotEnv('services.facebook.redirect',$this->getSetting('redirect_facebook'));
    //$this->updateDotEnv('LOCATION_SLOGAN',$this->getSetting('LOCATION_SLOGAN'),"'");

  }

	public function setLangue (Request $request, $lang) {
		session()->put('language_fe', $lang);
		\App::setLocale($lang);
	}

	public function setContent()
	{
		return view('Booking.layout.master', (array)$this->view);
	}

  protected function updateDotEnv($key, $newValue, $delim='')
  {

    $path = base_path('.env');
    // get old value from current env
    $oldValue = config($key);
    // was there any change?
    if ($oldValue === $newValue) {
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
}
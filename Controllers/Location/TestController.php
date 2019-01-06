<?php
namespace App\Http\Controllers\Location;
use App\Models\Location\Category;
use App\Models\Location\CategoryContent;
use App\Models\Location\CategoryItem;
use App\Models\Location\CategoryService;
use App\Models\Location\City;
use App\Models\Location\Content;
use App\Models\Location\Product;
use App\Models\Location\Country;
use App\Models\Location\District;
use App\Models\Location\GroupContent;
use App\Models\Location\ImageMenu;
use App\Models\Location\ImageSpace;
use App\Models\Location\LikeContent;
use App\Models\Location\LinkContent;
use App\Models\Location\NotifiContent;
use App\Models\Location\SeoContent;
use App\Models\Location\ServiceContent;
use App\Models\Location\VoteContent;
use App\Models\Location\Checkin;
use App\Models\Location\SaveLikeContent;
use App\Models\Location\ManageAd;
use App\Models\Location\Client;
use App\Models\Location\Comment;
use App\Models\Location\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use App\Models\Location\TransactionCoin;
use Carbon\Carbon;
use App\Models\Location\Suggest;
use Illuminate\Support\Facades\Hash;
use App\Models\Location\TraceUser;
class TestController extends BaseController
{
	public function getIndex(Request $request){
		// $arrData = [
		// 	'content'=>[1,2,3],
		// 	'to_user' => 22,
  //   	'from_user' => 66
		// ];
		// $codeApply = super_encode(json_encode($arrData));

		// $arrApply = json_decode(super_decode($codeApply),true);

		// dd($codeApply,$arrApply);

		// $user = Client::find(36);
		// $user->password = Hash::make('123456');
		// $user->save();

		// $this->view->content = view('Location.test.test',[]);
		// return $this->setContent(); 
		// $id = '9auub_UhQM8';
		// $link = 'https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails,player&id='.$id.'&key=AIzaSyA4_lZ8uw0hpJfJxVHnK_vBBXZckA-0Tr0';
  //   $html = app('App\Http\Controllers\Location\AddLocationController')->getManageLocaiton($request,68272);
  //   $this->view->content = $html;
  //   // return view($html);
		// return $this->setContent(); 

		// $this->view->content = view('Location.test.test',[]);
		// return $this->setContent();

		echo make_image_avatar("A");
	}

	public function getInfo(){
		phpinfo();
	}

	public function postIndex(Request $request){
		dd($request->all());
	}
}

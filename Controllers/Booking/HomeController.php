<?php
namespace App\Http\Controllers\Booking;
use App\Models\Booking\HomeBooking;
use Illuminate\Http\Request;
class  HomeController extends BaseController {

	public function anyIndex()
	{
		$home_bookings = HomeBooking::where('active',1)
																->orderBy('weight')
																->get();

		$this->view->content = view('Booking.home.home',[
																		'home_bookings' => $home_bookings
																	]);
		return $this->setContent();
	}

	public function postSaveLocation(Request $request){
		if($request->currentLocation){
			session()->put('currentLocation', $request->currentLocation);
		}else{
			echo 'Not location';
		}
	}
}
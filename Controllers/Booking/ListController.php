<?php

namespace App\Http\Controllers\Booking;
use App\Models\Booking\HomeBooking;
use App\Models\Booking\Hotel;
use App\Models\Location\City;
use App\Models\Location\ServiceItem;
use Illuminate\Http\Request;
class  ListController extends BaseController {
	public function getHotelByCity($city, $request, $param){
		\DB::enableQueryLog();
		$hotels = Hotel::select('hotel.id','hotel.*')
									 ->with('_content')
									 ->with('_room_types')
									 // ->with('_types')
									 ->where('hotel.active',1)
									 ->leftJoin(\Config::get('database.connections.mysql.database').'.contents','contents.id', 'hotel.content_id')
									 ->where('contents.city',$city->id)
									 ->get();
		// dd(\DB::getQueryLog());
		// dd($hotels);
		$type = isset($param['type'])?$param['type']:null;
		
		$list_service = ServiceItem::where('active',1)
															 ->leftJoin('category_service','id_service_item','service_items.id')
															 ->where('category_service.id_category',6)
															 ->get();
		$this->view->content = view('Booking.list.city',[
																		'hotels' => $hotels,
																		'city'	 => $city,
																		'list_city'=>$this->view->list_city,
																		'types'=>$this->view->types,
																		'type'=>$type,
																		'list_service'=>$list_service,
																	]);
		return $this->setContent();
	}
}
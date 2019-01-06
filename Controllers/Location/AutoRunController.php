<?php
namespace App\Http\Controllers\Location;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Location\TransactionCoin;
use App\Models\Location\Client;
use App\Models\Location\Content;
use App\Models\Ads\PaymentAds;
use Carbon\Carbon;

class AutoRunController extends BaseController
{
	public function updatePushContent(){
		$contents = Content::where('last_push','>',Carbon::create(1991,1,1,0,0,0))->get();
		if($contents){
			$i = 1;
			foreach ($contents as $content) {
				if(strtotime($content->end_push) < strtotime(Carbon::now()->toDateTimeString())){
					$content->last_push = Carbon::create(1990,1,1,0,0,0)->toDateTimeString();
					$content->end_push = Carbon::create(1990,1,1,0,0,0)->toDateTimeString();
					$content->save();
					$i++;
				}
			}
		}
	}

	public function updateRevenueForUser(){
		$clients = Client::where('register_invite',1)
										 ->where('active',1)
										 ->whereNotNull('code_invite')
										 ->get();
		if($clients){
			foreach ($clients as $client) {
				$arr_content = Content::where('moderation','=','publish')
												->where('active','=',1)
												->where('code_invite','=',$client->code_invite)
												->pluck('id');

				$arr_ads_payment= PaymentAds::selectRaw('sum(total) as total, contents.name,payment_ads.created_at')
																		->whereMonth('payment_ads.created_at', '=', date('m'))
																		->leftJoin(\Config::get('database.connections.mysql.database').'.contents','contents.id','content_id')
																		->whereIn('content_id',$arr_content)
																		->groupBy('content_id')
																		->get();
				$total = 0;
				$total_revenue = 0;
				foreach ($arr_ads_payment as $key => $value) {
					$total += $value->total;
					$total_revenue +=  $value->total * $client->rate_revenue/100;
				}

				$trans = new TransactionCoin();
        $trans->bonus($client, $total_revenue, trans('transaction.revenue_account',['coin'  => money_number($total_revenue)]));
        if($trans->getError()){
          throw $trans->getError();
        }
			}
		}
	}
}
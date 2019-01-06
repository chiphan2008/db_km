<?php
namespace App\Http\Controllers\Admin;
use App\Models\Location\Content;
use App\Models\Location\ManageAd;
use App\Models\Location\NotifiAdmin;
use App\Models\Location\Notifi;
use App\Models\Location\Client;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use App\Models\Location\TransactionCoin;
use Carbon\Carbon;

class ManageAdController extends BaseController
{
	public function anyIndex(Request $request){
		if($request->keyword){
			$list_ad = ManageAd::select("manage_ad.*")
								 ->leftJoin('contents','contents.id', 'content_id')
								 ->where('contents.name','like','%'.$request->keyword.'%')
								 ->orderBy('declined')
								 ->orderBy('approved')
								 ->orderBy('created_at','desc');
		}else{
			$list_ad = ManageAd::select("manage_ad.*")
								 ->with('_content')
								 ->orderBy('declined')
								 ->orderBy('approved')
								 ->orderBy('created_at','desc');
		}



		if($request->status){
			if($request->status == 'approved')
				$list_ad = $list_ad->where('approved','=',1);
			if($request->status == 'declined')
				$list_ad = $list_ad->where('declined','=',1);
			if($request->status == 'pending')
				$list_ad = $list_ad->where('approved','=',0)
													 ->where('declined','=',0);
		}


		$list_ad = $list_ad->paginate(10);
		return view('Admin.manage_ad.list',[
			'list_ad'	=>	$list_ad,
			'status'	=> 	$request->status,
			'keyword'	=> 	$request->keyword,
		]);
	}

	public function postUpdate(Request $request){
		$id_ad = $request->id_ad?$request->id_ad:0;
		if($id_ad){
			$ad = ManageAd::find($id_ad);

			//Approve ad
			if($request->approve){
				//Update ad
				$ad->approved = 1;
				$ad->approved_at = new Carbon();
				$ad->approved_by = \Auth::guard('web')->user()->id;
				$ad->save();

				//Update Content
				$content = Content::where('id','=',$ad->content_id)->first();
				$content->active_ad = 1;
				$content->view_ad = $ad->total_view;
				$content->save();

				//create Notifi User
				$notifi = new Notifi();
				$notifi->createNotifiUserByTemplate('Admin'.DS.'manage_ad.approved_ad',$content->created_by,['content'=>$content->name]);
				return redirect(route('list_manage_ad'))->with(['success'=>trans('Admin'.DS.'manage_ad.approved_ad',['content'=>$content->name])]);
			}

			//Decline ad
			if($request->decline){
				//Update ad
				$ad->declined = 1;
				$ad->declined_content = $request->declined_content?$request->declined_content:'';
				$ad->declined_at = new Carbon();
				$ad->declined_by = \Auth::guard('web')->user()->id;
				$ad->save();

				//Update Content
				$content = Content::where('id','=',$ad->content_id)->first();
				$content->active_ad = 0;
				$content->view_ad = 0;
				$content->save();

				//create Notifi User
				$notifi = new Notifi();
				$notifi->createNotifiUserByTemplate('Admin'.DS.'manage_ad.declined_ad_because',$content->created_by,[
					'content'=>$content->name,
					'because'=>$request->declined_content?$request->declined_content:''
				]);


				//Payback coin
				$trans = new TransactionCoin();
				$client = Client::find($content->created_by);
        $trans->payback($client, $ad->total_coin, trans('transaction.payback',['coin'  => money_number($ad->total_coin)]));
        if($trans->getError()){
          throw $trans->getError();
        }

        return redirect(route('list_manage_ad'))->with(['success'=>trans('Admin'.DS.'manage_ad.declined_ad',['content'=>$content->name])]);
			}

			if($request->update){
				$ad->declined_content = $request->declined_content?$request->declined_content:'';

				//Update Content
				$content = Content::where('id','=',$ad->content_id)->first();
				$content->active_ad = $request->has('active_ad');
				$content->keyword_ad = $request->keyword_ad?$request->keyword_ad:'';
				$content->save();

				//create Notifi User
				$notifi = new Notifi();
				$notifi->createNotifiUserByTemplate('Admin'.DS.'manage_ad.updated_ad',$content->created_by,['content'=>$content->name]);

				return redirect(route('list_manage_ad'))->with(['success'=>trans('Admin'.DS.'manage_ad.updated_ad',['content'=>$content->name])]);
			}

		}else{
			return redirect(route('list_manage_ad'))->with(['error' => trans('Admin'.DS.'manage_ad.declined_ad')]);
		}
	}
}

<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\Location\Client;
use App\Models\Location\TransactionCoin;
use App\Models\Location\EmailTemplate;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;

class WalletController extends BaseController {
	public function transfer(Request $request){
		try{
			$rules = [
				'id_user' => 'required',
				'adjust' => 'required'
			];
			$messages = [
				'id_user.required' => trans('valid.id_user_transfer'),
				'adjust.required' => trans('valid.adjust')
			];

			$validator = Validator::make($request->all(), $rules, $messages);
			if ($validator->fails()) {
				$e = new \Exception($validator->errors()->first(),400);
				return $this->error($e);
			}else{
				if(!Auth::guard('web_client')->user()){
					$e = new \Exception(trans('Location'.DS.'preview.not_login'),400);
					return $this->error($e);
				}
				$to_user = Client::where('id',$request->id_user)->where('active',1)->first();
				if(!$to_user){
					$e = new \Exception(trans('valid.not_found',['object'=>trans('global.user')]),400);
					return $this->error($e);
				}
				$trans = new TransactionCoin();
				$trans->transfer(Auth::guard('web_client')->user(),$to_user,$request->adjust,$request->description);
				if($trans->getError()){
          return $this->error( $trans->getError() );
        }
        $data = TransactionCoin::where('id',$trans->getTransfer())
        											 ->with('_from_client')
        											 ->with('_to_client')
        											 ->first();
        return $this->response($data,200);
			}
		}catch(Exception $e){
			return $this->error($e);
		}
	}
}
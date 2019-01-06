<?php

namespace App\Http\Controllers\Location;

use App\Models\Location\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Validator;
use App\Models\Location\Client;
use App\Models\Location\TransactionCoin;

class LoginController extends BaseController
{
  protected $redirectPath = '/';
  protected $redirectTo = '/';

  use AuthenticatesUsers;

  protected function guard()
  {
    return Auth::guard('web_client');
  }

  public function postRegisterInvite(Request $request)
  {
    $arrRequest = $request->all();
    $arrRequest['full_name'] = vn_string($arrRequest['full_name']);
    $rules = [
      'full_name' => 'required|min:3|max:150|regex:/^[a-zA-Z ]*$/',
      'email' => 'required|email|max:255|unique:clients',
      'password' => 'required|min:6|confirmed',
      'phone' => 'required|min:9',
      'cmnd' => 'required|min:9',
    ];
    $messages = [
      'full_name.required' => trans('global.full_name_required'),
      'full_name.min' => trans('global.full_name_min'),
      'full_name.max' => trans('global.full_name_max'),
      'full_name.regex'=>trans('global.full_name_regex'),
      'email.required' => trans('global.email_required'),
      'email.email' => trans('global.email_email'),
      'email.max' => trans('global.email_max'),
      'email.unique' => trans('global.email_unique'),
      'password.required' => trans('global.password_required'),
      'password.min' => trans('global.password_min'),
      'password.confirmed' => trans('global.password_confirmed'),
      'phone.required' => trans('global.phone_required'),
      'phone.min' => trans('global.phone_min'),
      'cmnd.required' => trans('global.cmnd_required'),
      'cmnd.min' => trans('global.cmnd_min'),
    ];

    $validator = Validator::make($arrRequest, $rules, $messages);

    if ($validator->fails()) {
      return Response::json(array(
        'mess' => false,
        'errors' => $validator->getMessageBag()->toArray()
      ));
    } else {

      $client = Client::create([
        'full_name' => $request->full_name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'avatar' => url('img_user/default.png'),
        'active' => 0,
        'cmnd' => $request->cmnd,
        'phone' => $request->phone,
        'code_invite' => $request->phone,
        'register_invite' => 1,
        'rate_revenue'=>80,
      ]);

      if($client){
        $client->ma_dinh_danh = create_number_wallet($client->id);
        $client->save();
        $trans = new TransactionCoin();
        $trans->bonus($client, BONUS_REGISTER, trans('transaction.bonus_create_account',['coin'  => money_number(BONUS_REGISTER)]));
        if($trans->getError()){
          throw $trans->getError();
        }
      }

      $mail_template = EmailTemplate::where('machine_name', 'create_invite')->first();
      if($mail_template)
      {
        $data = [
          'full_name' => $request->full_name,
          'phone' => $request->phone,
          'email' => $request->email,
          'password' => $request->password,
        ];
        Mail::send([], [], function($message) use ($mail_template, $data)
        {
          $message->to($data['email'], $data['full_name'])
            ->subject($mail_template['subject'])
            ->from('kingmapteam@gmail.com', 'KingMap Team')
            ->setBody($mail_template->parse($data));
        });
      }

      return Response::json(array(
        'mess' => false,
        'errors' => ['check_mail'=> trans('global.register_invite_success').'']
      ));
    }
  }

  public function postRegisterClient(Request $request)
  {
    $arrRequest = $request->all();
    $arrRequest['full_name'] = vn_string($arrRequest['full_name']);

    $rules = [
      'full_name' => 'required|min:3|max:150|regex:/^[a-zA-Z ]*$/',
      'email' => 'required|email|max:255|unique:clients',
      'password' => 'required|min:6|confirmed',
    ];
    $messages = [
      'full_name.required' => trans('global.full_name_required'),
      'full_name.min' => trans('global.full_name_min'),
      'full_name.max' => trans('global.full_name_max'),
      'full_name.regex'=>trans('global.full_name_regex'),
      'email.required' => trans('global.email_required'),
      'email.email' => trans('global.email_email'),
      'email.max' => trans('global.email_max'),
      'email.unique' => trans('global.email_unique'),
      'password.required' => trans('global.password_required'),
      'password.min' => trans('global.password_min'),
      'password.confirmed' => trans('global.password_confirmed'),
    ];
    $validator = Validator::make($arrRequest, $rules, $messages);

    if ($validator->fails()) {
      return Response::json(array(
        'mess' => false,
        'errors' => $validator->getMessageBag()->toArray()
      ));
    } else {
      $client = Client::create([
        'full_name' => $request->full_name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'avatar' => url('img_user/default.png'),
        'active' => 0,
      ]);

      if($client){
        $client->ma_dinh_danh = create_number_wallet($client->id);
        $client->avatar = make_image_avatar(mb_strtoupper(substr(vn_string($client->full_name),0,1)));
        $client->save();
        $trans = new TransactionCoin();
        $trans->bonus($client, BONUS_REGISTER, trans('transaction.bonus_create_account',['coin'  => money_number(BONUS_REGISTER)]));
        if($trans->getError()){
          throw $trans->getError();
        }
        commandSyncClient2Node($client->id);
      }

      $mail_template = EmailTemplate::where('machine_name', 'create_client')->first();
      if($mail_template)
      {
        $data = [
          'full_name' => $request->full_name,
          'email' => $request->email,
          'password' => $request->password,
          'link' => url('/active_client/'.base64_encode($request->email)),
        ];
        Mail::send([], [], function($message) use ($mail_template, $data)
        {
          $message->to($data['email'], $data['full_name'])
            ->subject($mail_template['subject'])
            ->from('kingmapteam@gmail.com', 'KingMap Team')
            ->setBody($mail_template->parse($data));
        });
      }

      return Response::json(array(
        'mess' => false,
        'errors' => ['check_mail'=> trans('Location'.DS.'layout.register_success').'']
      ));
    }
  }

  protected function credentials(Request $request)
  {
    return [
      'email' => $request->{$this->username()},
      'password' => $request->password,
      'active' => '1',
    ];
  }

  public function getActiveClient($token)
  {
    $client = Client::where('email','=',base64_decode($token))->first();
    if($client)
    {
      if($client->active == 0)
      {
        $create_at = strtotime($client->created_at);
        $now = strtotime("now");
        $hours = round(($now - $create_at)/3600);
        if($hours < 24)
        {
          $client->active = 1;
          $client->save();
          commandSyncClient2Node($client->id);
          return view('Location.layout.active-client')->with(['status' => 'suss', 'mess' => trans('Location'.DS.'layout.active_success')]);
        }
        else
        {
          $client->delete();
          commandSyncClient2Node($client->id,1);
          return view('Location.layout.active-client')->with(['status' => 'err', 'mess' => trans('Location'.DS.'layout.active_fail')]);
        }
      }
      else{
        return redirect('/');
      }
    }
    else
    {
      return redirect('/');
    }
  }

  public function logout(Request $request)
  {
      
      $this->guard()->logout();

      $request->session()->invalidate();
      $back_url = url()->previous();
      if(
        strpos($back_url,'/user') === false 
        && strpos($back_url,'/edit/location') === false
        && strpos($back_url,'/makemoney') === false
      ){
        return redirect()->back();
      }else{
        return redirect('/');
      }
      
  }
}

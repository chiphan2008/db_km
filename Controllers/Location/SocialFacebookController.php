<?php

namespace App\Http\Controllers\Location;

use App\Models\Location\Client;
use App\Models\Location\TransactionCoin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Socialite;

class SocialFacebookController extends Controller
{

  protected function guard()
  {
    return Auth::guard('web_client');
  }

  public function redirectToProvider($provider)
  {
    Session::put('backUrl',url()->previous());
    return Socialite::driver($provider)->redirect();
  }

  public function handleProviderCallback(Request $request, $provider)
  {
    $client_social = Socialite::driver($provider)->user();

    if($client_social)
    {
      $type = 'id_'.$provider;
      $id_provider = $client_social->getId();

      $client = Client::where($type, '=', $id_provider)->first();
      if($client) {
        $client->avatar = resize_avatar($client_social->getAvatar());
        $client->save();
        commandSyncClient2Node($client->id);
        if($client->active == 1)
        {
          $this->guard()->login($client);
          return redirect(Session::get('backUrl') ? Session::get('backUrl') : '/');
        }
        else {
          return redirect(Session::get('backUrl') ? Session::get('backUrl') : '/');
        }
      }
      else{

        $email = $client_social->getEmail();
        if(isset($email) && !empty($email)){
          $email_client = Client::where('email', '=', $email)->first();

          if($email_client)
          {
            if($email_client->active == 1)
            {
              $email_client->$type = $client_social->getId();
              $email_client->save();
              $this->guard()->login($email_client);
              return redirect(Session::get('backUrl') ? Session::get('backUrl') : '/');
            }
            else {
              return redirect(Session::get('backUrl') ? Session::get('backUrl') : '/');
            }
          }
          else {
            $client = Client::create([
              'full_name' => ($client_social->getName()) ? $client_social->getName() : '',
              'email' => $email,
              'avatar' => ($client_social->getAvatar()) ? resize_avatar($client_social->getAvatar()) : make_image_avatar(mb_strtoupper(substr(vn_string($client_social->getName()),0,1))),
              'id_'.$provider => $client_social->getId(),
            ]);
            if($client){
              $client->ma_dinh_danh = create_number_wallet($client->id);
              $client->save();
              $trans = new TransactionCoin();
              $trans->bonus($client, BONUS_REGISTER, trans('transaction.bonus_create_account',['coin'  => money_number(BONUS_REGISTER)]));
              if($trans->getError()){
                throw $trans->getError();
              }
              commandSyncClient2Node($client->id);
            }

            $this->guard()->login($client);
            return redirect(Session::get('backUrl') ? Session::get('backUrl') : '/');
          }
        }
        else {
          $client = Client::create([
            'full_name' => ($client_social->getName()) ? $client_social->getName() : '',
            'avatar' => ($client_social->getAvatar()) ? resize_avatar($client_social->getAvatar()) : make_image_avatar(mb_strtoupper(substr(vn_string($client_social->getName()),0,1))),
            'id_'.$provider => $client_social->getId(),
          ]);
          if($client){
            $client->ma_dinh_danh = create_number_wallet($client->id);
            $client->save();
            $trans = new TransactionCoin();
            $trans->bonus($client, BONUS_REGISTER, trans('transaction.bonus_create_account',['coin'  => money_number(BONUS_REGISTER)]));
            if($trans->getError()){
              throw $trans->getError();
            }
            commandSyncClient2Node($client->id);
          }

          $this->guard()->login($client);
          return redirect(Session::get('backUrl') ? Session::get('backUrl') : '/');
        }
      }


//      $email = $client_social->getEmail();
//      if(isset($email))
//      {
//        $client = Client::where('email', '=', $email)->first();
//        $id_provider = 'id_'.$provider;
//        if($client) {
//          $client->$id_provider = $client_social->getId();
//          $client->save();
//          $this->guard()->login($client);
//          return redirect()->route('index');
//        }
//        else{
//          $client = Client::create([
//            'full_name' => ($client_social->getName()) ? $client_social->getName() : '',
//            'email' => $email,
//            'avatar' => 'default.png',
//            'id_'.$provider => $client_social->getId(),
//          ]);
//
//          $this->guard()->login($client);
//          return redirect()->route('index');
//        }
//      }
//      else {
//        $client = Client::create([
//          'full_name' => ($client_social->getName()) ? $client_social->getName() : '',
//          'avatar' => 'default.png',
//          'id_'.$provider => $client_social->getId(),
//        ]);
//
//        $this->guard()->login($client);
//        return redirect()->route('index');
//      }
    }
  }
}

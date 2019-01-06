<?php

namespace App\Http\Controllers\API;

use App\Models\Location\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Response;

class ForgotPasswordController extends BaseController
{
  //Sends Password Reset emails
  use SendsPasswordResetEmails;

  public function showLinkRequestForm()
  {
    return view('Location.layout.email');
  }

  public function broker()
  {
    return Password::broker('clients');
  }

  public function checkStatusClient(Request $request)
  {
    try{
      $this->validate($request, ['email' => 'required|email']);
      $user_check = Client::where('email', $request->email)->first();
      if($user_check){
        if ($user_check->active == '1') {
          $this->sendResetLinkEmail($request);
          if(session()->get('status') === 'passwords.sent'){
            return $this->response([],200);
          }else{
            $e = new \Exception(session()->get('status'),400);
            return $this->error($e);
          }
        } else {
          $e = new \Exception(trans('valid.account_has_locked'),400);
          return $this->error($e);
        }
      }else{
        $e = new \Exception(trans('valid.not_found',['object'=>'Email']),400);
          return $this->error($e);
      }
      

    }catch(Exception $e){
      return $this->error($e);
    }
  }
}

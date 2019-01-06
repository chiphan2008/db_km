<?php

namespace App\Http\Controllers\Location;

use Illuminate\Http\Request;
use App\Models\Location\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Validator;

class ResetPasswordController extends BaseController
{
  protected $redirectTo = '/';

  use ResetsPasswords;

  public function showResetForm(Request $request, $token = null, $email)
  {
    $email_check = \DB::table('client_password_resets')->where('email', base64_decode($email))->first();
    if(isset($email_check))
    {
      return view('Location.layout.reset')->with(['token' => $token, 'email' => base64_decode($email)]);
    }
    else
    {
      return view('Location.layout.reset')->with(['token' => $token]);
    }

  }

  public function broker()
  {
    return Password::broker('clients');
  }

  //returns authentication guard of seller
  protected function guard()
  {
    return Auth::guard('web_client');
  }
}

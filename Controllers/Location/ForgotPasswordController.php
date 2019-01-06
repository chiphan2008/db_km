<?php

namespace App\Http\Controllers\Location;

use App\Models\Location\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Response;

class ForgotPasswordController extends Controller
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

    $this->validate($request, ['email' => 'required|email']);
    $user_check = Client::where('email', $request->email)->first();

    if ($user_check->active == '1') {
      $this->sendResetLinkEmail($request);
    } else {
      return Response::json(array(
        'errors' => trans('valid.account_has_locked')
      ));
    }
  }
}

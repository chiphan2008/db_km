<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends BaseController
{
  //Sends Password Reset emails
  use SendsPasswordResetEmails;

  public function showLinkRequestForm()
  {
    return view('Admin.email');
  }

  //Password Broker for Seller Model
  public function broker()
  {
    return Password::broker('users');
  }
}

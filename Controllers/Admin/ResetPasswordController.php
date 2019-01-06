<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Validator;

class ResetPasswordController extends BaseController
{
  protected $redirectTo = 'admin';

  use ResetsPasswords;

  public function showResetForm(Request $request, $token = null)
  {
    return view('Admin.reset')->with(['token' => $token, 'email' => $request->email]
    );
  }

}

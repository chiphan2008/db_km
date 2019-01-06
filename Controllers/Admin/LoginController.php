<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends BaseController
{

  protected $redirectTo = '/';
  
  protected function guard()
  {
    return Auth::guard('web');
  }

  use AuthenticatesUsers;

  public function getLogin()
  {
    return view('Admin.login');
  }

  protected function credentials(Request $request)
  {
    return [
      'email' => $request->{$this->username()},
      'password' => $request->password,
      'active' => '1',
    ];
  }

  public function authenticated(Request $request,User $user){
    if($user->hasRole('content') == true)
    {
      if (!($user instanceof App\Models\Location\User)) {
        return false;
      }
      $previous_session = $user->session_id;

      if ($previous_session) {
        \Session::getHandler()->destroy($previous_session);
      }
     Auth::guard('web')->user()->session_id = \Session::getId();
     Auth::guard('web')->user()->save();
     return redirect()->intended($this->redirectPath());
    }
  }

}

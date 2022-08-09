<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers { login as traitLogin; }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        /*
         * Validate login form data
         */
        $this->validateLogin($request);

        /*
         * Get user by email
         */
        $user = User::where('email', $request->get($this->username(), null))->first();

        /*
         * Fail login if user is disabled
         */
        if ($user !== null && $user->role === 'disabled')
        {
            return $this->sendFailedLoginResponse($request);
        }

        /*
         * Continue with default login process
         */
        return $this->traitLogin($request);
    }
}

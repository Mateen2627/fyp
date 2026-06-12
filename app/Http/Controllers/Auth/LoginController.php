<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void$field
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Use one visible field for the login identifier so validation and
     * authentication errors appear next to the field the user actually filled.
     */
    public function username()
    {
        return 'identity';
    }

    /**
     * Validate the login request using the visible form fields.
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'identity' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Login with either username or email.
     */
    protected function credentials(Request $request)
    {
        $identity = $request->input('identity');
        $field = filter_var($identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $field => $identity,
            'password' => $request->input('password'),
        ];
    }
}

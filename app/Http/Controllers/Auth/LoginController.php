<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return [
            $this->usernameField($request) => $request->email,
            'password' => $request->password,
        ];
    }

    /**
     * Get the username field.
     *
     * @param Request $request
     * @return string
     */
    private function usernameField(Request $request)
    {
        return $this->isEmail($request->email) ? 'email' : 'username';
    }

    /**
     * Check if string is valid email address.
     *
     * @param $string
     * @return mixed
     */
    private function isEmail($string)
    {
        return filter_var($string, FILTER_VALIDATE_EMAIL);
    }
}

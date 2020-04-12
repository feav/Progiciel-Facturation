<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\RESTResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
    */
    // protected function authenticated(Request $request, $user)
    // {
    //     return response([
            
    //     ]);
    // }
    
    /**
     * Show the Login form to User.
     *
     */
    public function showLoginForm(){
        return view('pages.auth.login');
    }

    /**
     * Login User into Application.
     *
     * @param  Request  $request
     * @return redirect()
     */
    public function login(Request $request){
        if (Auth::attempt(
                [
                    'email' => $request->input('email'),
                    'password' => $request->input('password')
                ], 
                $request->has('remember_me'))
            ) {
            return redirect()->route('index');
        }else
            return redirect()->route('login')->withErrors(['msg', "L'adresse email et le mot de passe ne correspondent pas !"]);
    }

    /**
     * Log out current User connected.
     *
     * @return redirect()
     */
    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}

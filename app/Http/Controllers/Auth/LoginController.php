<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\RESTResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use GuzzleHttp\Client;
use Laravel\Passport\Client as OClient; 
use Illuminate\Support\Facades\Route;
use App\User;
use App\Role;
use DB;

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

    public $successStatus = 200;

    
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
        // if (Auth::attempt(
        //         [
        //             'email' => $request->input('email'),
        //             'password' => $request->input('password')
        //         ], 
        //         $request->has('remember_me'))
        //     ) {
        //     return redirect()->route('index');
        // }else
        //     return redirect()->route('login')->withErrors(['msg', "L'adresse email et le mot de passe ne correspondent pas !"]);

        
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) { 
            $oClient = DB::table('oauth_clients')->where('password_client', 1)->first();
            $data = [
                'username' => $request->input('email'),
                'password' => $request->input('password'),
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'grant_type' => 'password',
            ];
            $request = app('request')->create('/oauth/token', 'POST', $data);
            $response = app('router')->prepareResponse($request, app()->handle($request));
            $result = json_decode((string) $response->content(), true);
            $user = Auth::user();
            if(!$user->deleted){
                $user->poste = Role::find($user->role_id) == null ? 'Poste introuvable' : Role::find($user->role_id)->intitule;
                $tabdata=array('code' => 200, 'tokens' => $result, 'user' => $user);
                return response()->json($tabdata, $this->successStatus);
            }else{
                return response()->json(['code' => 403], 200);
            }
        } 
        else { 
            return response()->json(['code' => 401], 200);
        }


        // return response()->json(DB::table('oauth_clients')->where('password_client', 1)->first(), 200);

        // if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
        //     $token = auth()->user()->createToken('access_token')->accessToken;
        //     $user = auth()->user();
        //     $user->poste = Role::find($user->role_id) == null ? 'Poste introuvable' : Role::find($user->role_id)->intitule;
        //     return response()->json(['code' => 200, 'access_token' => $token, 'user' => $user], 200);
        // } else {
        //     return response()->json(['code' => 401], 200);
        // }

        // $http = new Client(['verify' => false]);
        // $response = $http->post('http://localhost:8000/oauth/token', [
        //     'form_params' => [
        //         'username' => $request->input('email'),
        //         'password' => $request->input('password'),
        //         'client_id' => '4',
        //         'client_secret' => 'kde9SZ7Dc08NUpB6vZHR2b1HAkhE8xwiQmyPG8VL',
        //         'grant_type' => 'password',
        //         'scope' => '',
        //     ],
        // ]);
        // return json_decode((string) $response->getBody(), true);
    }

    /**
     * Log out current User connected.
     *
     * @return redirect()
     */
    public function logout(){
        // Auth::logout();
        // return redirect()->route('login');

        Auth::user()->token()->revoke();

        // DB::table('oauth_access_tokens')
        //     ->where('user_id', Auth::user()->id)
        //     ->update([
        //         'revoked' => true
        //     ]);
        return response()->json(['code'=>200], 200); 
    }
}

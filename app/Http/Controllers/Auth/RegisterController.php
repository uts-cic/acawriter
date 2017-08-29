<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use JWT\Authentication\JWT;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    public function awt(Request $request){
        $jws = $request->assertion;

        $jwt = JWT::decode($jws, env('JWT_SECRET', ''));
        # In a complete app we'd also store and validate the jti value to ensure there is no reply on this unique token ID
        $now = strtotime("now");

        $attr = 'https://aaf.edu.au/attributes';

        //if($jwt->aud == "http://localhost:8000" && strtotime($jwt->exp) < $now && $now > strtotime($jwt->nbf)) {
        if($jwt->aud == "http://localhost:8000") {
            $attr = $jwt->{$attr};
            $credentials = array('email' => $attr->mail, 'name' => $attr->displayname, 'password' => ' ');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                Event::fire(new OperationLog($user));

                return redirect()->intended('/home');
            } else {
                $this->create($credentials);
                return redirect()->intended('/home');
            }
        } else {
            //App::abort(403,"JWS was invalid");
            return redirect()->intended('/');
        }
    }
    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function awtAuth($attr)
    {
        $password = '';
        if (Auth::attempt(['email' => $attr->mail, 'password' => $password])) {
            // Authentication passed...

            return redirect('home');
        } else {
            dd("error");
        }
    }






    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}

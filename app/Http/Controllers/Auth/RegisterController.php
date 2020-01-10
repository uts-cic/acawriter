<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Events\UserLog;
use App\Events\UserRegistered;
use Illuminate\Http\Request;
use JWT\Authentication\JWT;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Events\OperationLog;

use OAuthProvider;

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

    public function awt(Request $request)
    {
        $whatRole = 'student';
        $jws = $request->assertion;

        $jwt = JWT::decode($jws, env('AAF_SECRET', ''));


        # In a complete app we'd also store and validate the jti value to ensure there is no reply on this unique token ID
        $now = strtotime("now");

        $attr = 'https://aaf.edu.au/attributes';

        //if($jwt->aud == "http://localhost:8000" && strtotime($jwt->exp) < $now && $now > strtotime($jwt->nbf)) {
        if ($jwt->aud == env('AAF_AUD', '')) {
            $attr = $jwt->{$attr};
            $credentials = array('email' => $attr->mail, 'name' => $attr->displayname, 'password' => ' ');
            $whatRole = Str::is('staff@*', $attr->edupersonscopedaffiliation) ? 'staff' : 'user';

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $message = "login";
                event(new OperationLog($user, $message)); // used for updating log file compared to the db entry
                event(new UserLog($user, $message));

                return redirect()->intended('/home');
            } else {
                $user = $this->create($credentials);
                Auth::login($user);
                $message = "New user created";
                $msg = 'login';
                event(new OperationLog($user, $message));
                event(new UserRegistered($user, $whatRole));
                event(new UserLog($user, $msg)); // logs activity

                return redirect()->intended('/home');
            }
        } else {
            //App::abort(403,"JWS was invalid");
            return redirect()->intended('/');
        }
    }

    public function lti(Request $request)
    {
        try {
            $provider = new OAuthProvider();
            $provider->consumerHandler(function($p) {
                $p->consumer_secret = 'This-is-the-secret';
                return OAUTH_OK;
            });
            $provider->timestampNonceHandler(function() {
                return OAUTH_OK;
            });
            $provider->isRequestTokenEndpoint(true);
            $provider->setParam('url', NULL);
        
            $uri = 'https://acawriter-dev.utscic.edu.au/auth/lti';
            $check = $provider->checkOAuthRequest($uri, OAUTH_HTTP_METHOD_POST);

        } catch (OAuthException $e) {
            switch ($e->getCode()) {
                case OAUTH_BAD_NONCE:
                    die('This LTI request has expired. Please return to your application and restart the launch process.');
                    break;
                case OAUTH_BAD_TIMESTAMP:
                    die('This request is too old. Please return to your application and restart the launch process.');
                    break;
                case OAUTH_CONSUMER_KEY_UNKNOWN:
                    die('Consumer key is unknown, or has been temporarily disabled. Please check your consumer key settings and restart the launch process.');
                    break;
                case OAUTH_CONSUMER_KEY_REFUSED:
                    die('The consumer key was refused. Please check your configuration and follow up with the LTI provider for support.');
                    break;
                case OAUTH_INVALID_SIGNATURE:
                    die('The request signature is invalid, or does not match the signature computed.');
                    break;
                case OAUTH_PARAMETER_ABSENT:
                    die('A required launch parameter was not provided.');
                    break;
                case OAUTH_SIGNATURE_METHOD_REJECTED:
                    die('The signature method was not accepted by the service provider.');
                    break;
                default:
                    // We really shouldn't get any of the other OAuthProvider error codes.
                    // log this.
                    die('General launch error. Please follow up with the tool provider to consult any logs to further diagnose the issue.');
                    break;
            }
        }

        return 'Success!';
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
            'password' => Hash::make($data['password']),
        ]);
    }

    public function showRegistrationForm()
    {
        return redirect('login');
    }
}
